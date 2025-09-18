<?php namespace App\Services;

use CodeIgniter\Cache\CacheInterface;
use CodeIgniter\Database\BaseConnection;

/**
 * Erzeugt Bildvarianten unter /_data/{content_id}/{slice_id}/output/
 * - löscht NIE ganze Ordner
 * - überschreibt nur genau die Ziel-Dateien, die erzeugt werden
 * - cached Meta pro (content_id, slice_id)
 */
final class ImagePipelineService
{
    public function __construct(
        private BaseConnection  $primaryDb,
        private CacheInterface  $cache,
        private ?BaseConnection $fallbackDb = null
    ) {}

    /** Wähle die DB, in der wr_content_slice_data existiert. */
    private function db(): BaseConnection
    {
        try {
            if (method_exists($this->primaryDb, 'tableExists') &&
                $this->primaryDb->tableExists('wr_content_slice_data')) {
                return $this->primaryDb;
            }
        } catch (\Throwable) {}
        return $this->fallbackDb ?: $this->primaryDb;
    }

    /**
     * Baut/aktualisiert Varianten und liefert Meta für <picture>.
     * @return array{baseUrl:string,root:string,ext:string}|null
     */
   public function ensure(int $contentId, int $sliceId): ?array
{
    $rootPath   = rtrim(FCPATH, '/\\');
    $dirSlice   = "{$rootPath}/_data/{$contentId}/{$sliceId}";
    $dirContent = "{$rootPath}/_data/{$contentId}";

    // 1) image_id ermitteln (DB→FS). Dann im image_id-Ordner die Quelle suchen.
    [$imageId, $imgDir] = $this->resolveImageDir($contentId, $sliceId, $dirSlice);

    // Wenn kein image_id-Verzeichnis gefunden: KEIN Fallback auf Content-Root,
    // sonst landet wieder überall dasselbe Bild.
    if ($imageId === 0 || !is_dir($imgDir)) {
        return null;
    }

    $ck = "imgmeta_{$contentId}_{$sliceId}_{$imageId}";
    if ($meta = $this->cache->get($ck)) {
        if ($this->filesExist($meta)) return $meta;
    }

    $outDir  = "{$imgDir}/output";
    @is_dir($outDir) || @mkdir($outDir, 0775, true);

    // 2) Quelle im image_id-Ordner bestimmen (Original bevorzugt, sonst größte Variante)
    $src = $this->pickBestSourceInImageDir($imgDir);
    if (!$src) {
        // Fallback: existierende Outputs dieses image_id verwenden
        $meta = $this->metaFromExistingOutput($outDir, null, null);
        if ($meta) {
            $this->cache->save($ck, $meta, 3600);
            return $meta;
        }
        return null;
    }

    [$root, $ext] = $this->splitRootExt(basename($src));

    // 3) Varianten schreiben – nur Ziel-Dateien überschreiben, nichts löschen
    if ($this->needsRebuild($outDir, $src, $root)) {
        $this->buildVariants($src, $outDir, $root, $ext, null);
        // optional: Slice-Meta aktualisieren
        try {
            $this->upsertSliceData($sliceId, 'image_root', $root);
            $this->upsertSliceData($sliceId, 'image_ext',  $ext);
            $this->upsertSliceData($sliceId, 'image_id',   (string)$imageId);
        } catch (\Throwable) {}
        $this->cache->delete("content_agg_{$contentId}");
    }

    $meta = [
        'baseUrl' => "/_data/{$contentId}/{$sliceId}/{$imageId}/output/",
        'root'    => $root,
        'ext'     => $ext ?: 'jpg',
    ];

    if ($this->filesExist($meta)) {
        $this->cache->save($ck, $meta, 3600);
    }
    return $meta;
}


    /** Quelle auflösen: bevorzugt Dateiname aus DB, sonst neueste Bilddatei im Slice-, dann im Content-Ordner. */
    private function resolveSource(int $contentId, int $sliceId, string $dirSlice, string $dirContent): array
    {
        // a) DB: wr_content_slice_data.image (oder JSON {"filename": ...})
        $filename = null;
        try {
            $row = $this->db()
                ->table('wr_content_slice_data')
                ->select('image')
                ->where('content_id', $contentId)
                ->where('slice_id',   $sliceId)
                ->get()->getRowArray();

            if ($row && !empty($row['image'])) {
                $val = (string)$row['image'];
                if ($val !== '') {
                    if ($val[0] === '{') {
                        $j = json_decode($val, true);
                        if (is_array($j) && !empty($j['filename'])) $val = (string)$j['filename'];
                    }
                    $filename = basename($val);
                }
            }
        } catch (\Throwable) { /* DB optional */ }

        $candidates = [];
        $add = function(string $p) use (&$candidates) {
            if (is_file($p)) $candidates[filemtime($p) ?: 0] = $p;
        };

        $scanImage = function(string $dir) {
            if (!is_dir($dir)) return null;
            $bestTime = -1; $best = null;
            foreach (glob($dir.'/*.{jpg,jpeg,png,webp,JPG,JPEG,PNG,WEBP}', GLOB_BRACE) ?: [] as $p) {
                $name = basename($p);
                // nur Originale ohne Größenpräfix
                if (preg_match('/^(xss|xs|sm|md|lg|llg|hxs|hmd|hxorg)_/i', $name)) continue;
                $t = filemtime($p) ?: 0;
                if ($t > $bestTime) { $bestTime = $t; $best = $p; }
            }
            return $best;
        };

        // bevorzugt exakter Dateiname im Slice-Ordner
        if ($filename) {
            foreach ([$dirSlice, $dirContent] as $dir) {
                foreach (['', 'xss_','xs_','sm_','md_','lg_','llg_','hxs_','hmd_','hxorg_'] as $pref) {
                    foreach (['', '.webp','.jpg','.jpeg','.png'] as $ext) {
                        $p = rtrim($dir,'/').'/'.$pref.$filename.$ext;
                        if (is_file($p)) $add($p);
                    }
                }
            }
        }

        // sonst neueste Datei im Slice-, dann Content-Ordner
        $sliceNewest   = $scanImage($dirSlice);
        $contentNewest = $scanImage($dirContent);
        if ($sliceNewest)  $add($sliceNewest);
        if ($contentNewest)$add($contentNewest);

        if (!$candidates) return [null, null];
        krsort($candidates);
        $src = reset($candidates);
        $base = preg_replace('/\.(webp|jpe?g|png)$/i', '', basename($src));
        // Präfixe abwerfen, um auf den „Root“-Basenamen zu kommen
        $base = preg_replace('/^(xss|xs|sm|md|lg|llg|hxs|hmd|hxorg)_/i', '', $base);
        return [$src, $base];
    }

    /** Wenn bereits Outputs existieren, nutze sie als Meta-Fallback. */
    private function pickExistingMeta(string $outSlice, string $outContent): ?array
    {
        $pick = function(string $dir) {
            if (!is_dir($dir)) return null;
            $bestTime = -1; $bestFile = null;
            foreach (glob($dir.'/*.{webp,jpg,jpeg,WEBP,JPG,JPEG}', GLOB_BRACE) ?: [] as $p) {
                $t = filemtime($p) ?: 0;
                if ($t > $bestTime) { $bestTime = $t; $bestFile = $p; }
            }
            if (!$bestFile) return null;
            $name = basename($bestFile);
            $root = preg_replace('/^(xss|xs|sm|md|lg|llg|hxs|hmd|hxorg)_/i', '', $name);
            $root = preg_replace('/\.(webp|jpe?g)$/i', '', $root);
            $ext  = strtolower(pathinfo($bestFile, PATHINFO_EXTENSION)) ?: 'jpg';
            $baseUrl = rtrim(str_replace(rtrim(FCPATH,'/'), '', $dir), '/').'/';
            return ['baseUrl'=>$baseUrl,'root'=>$root,'ext'=>$ext];
        };

        return $pick($outSlice) ?: $pick($outContent);
    }

    /** Schreibt eine Variante – überschreibt nur genau die Zieldatei. */
    private function writeVariant(string $src, string $dst, int $targetWidth, string $format): void
    {
        // Falls Imagick verfügbar, nimm Imagick; sonst GD
        if (class_exists(\Imagick::class)) {
            $im = new \Imagick($src);
            $im->setImageOrientation(\Imagick::ORIENTATION_TOPLEFT);
            $im->autoOrient();
            $im->thumbnailImage($targetWidth, 0);
            if ($format === 'webp') {
                $im->setImageFormat('webp');
                $im->setImageCompressionQuality(86);
            } else {
                $im->setImageFormat('jpeg');
                $im->setImageCompressionQuality(86);
            }
            @is_dir(dirname($dst)) || @mkdir(dirname($dst), 0775, true);
            $im->writeImage($dst);
            $im->clear(); $im->destroy();
            return;
        }

        // GD Fallback
        $info = getimagesize($src);
        if (!$info) return;
        [$w,$h,$type] = $info;
        $ratio = $h > 0 ? $w / $h : 1;
        $nw = $targetWidth;
        $nh = (int)round($nw / $ratio);

        switch ($type) {
            case IMAGETYPE_WEBP: $image = imagecreatefromwebp($src); break;
            case IMAGETYPE_JPEG: $image = imagecreatefromjpeg($src); break;
            case IMAGETYPE_PNG:  $image = imagecreatefrompng($src);  break;
            default: return;
        }
        if (!$image) return;

        $dstImg = imagecreatetruecolor($nw, $nh);
        imagecopyresampled($dstImg, $image, 0,0,0,0, $nw,$nh, $w,$h);

        @is_dir(dirname($dst)) || @mkdir(dirname($dst), 0775, true);
        if ($format === 'webp') {
            imagewebp($dstImg, $dst, 86);
        } else {
            imagejpeg($dstImg, $dst, 86);
        }
        imagedestroy($dstImg);
        imagedestroy($image);
    }

    /** Prüft, ob die Standard-Zieldateien vorhanden sind. */
    private function filesExist(array $meta): bool
    {
        $base = rtrim(FCPATH, '/').$meta['baseUrl'];
        $root = $meta['root'];
        foreach (['lg','llg'] as $p) {
            if (!is_file($base."{$p}_{$root}.webp")) return false;
            if (!is_file($base."{$p}_{$root}.jpg"))  return false;
        }
        return true;
    }
// === in class ImagePipelineService, unterhalb von ensure() einfügen ===

private function resolveImageDir(int $cid, int $sid, string $dirSlice): array
{
    $imgId = 0;

    // a) DB: image_id lesen (falls vorhanden)
    try {
        $row = $this->db()->table('wr_content_slice_data')
            ->select('value')
            ->where('content_slice_id', $sid)
            ->whereIn('name', ['image_id','imageId','img_id','imgId'])
            ->orderBy('position','ASC')
            ->get()->getRowArray();
        if ($row && is_numeric($row['value'])) {
            $imgId = (int)$row['value'];
        }
    } catch (\Throwable) {}

    // b) Falls nicht in DB: numerische Unterordner scannen und den mit Bild wählen
    if ($imgId === 0 && is_dir($dirSlice)) {
        $cand = [];
        foreach (glob($dirSlice.'/*') ?: [] as $p) {
            if (!is_dir($p)) continue;
            $b = basename($p);
            if (!ctype_digit($b)) continue;
            $src = $this->pickBestSourceInImageDir($p);
            if ($src) $cand[filemtime($src) ?: 0] = (int)$b;
        }
        if ($cand) { krsort($cand); $imgId = reset($cand); }
    }

    return [$imgId, $imgId ? ($dirSlice.'/'.$imgId) : $dirSlice];
}

private function pickBestSourceInImageDir(string $imgDir): ?string
{
    if (!is_dir($imgDir)) return null;
    $preferExact = [];
    $preferLarge = [];

    foreach (glob($imgDir.'/*') ?: [] as $p) {
        if (!is_file($p) || str_contains($p, '/output/')) continue;
        $name = basename($p);
        $ext  = strtolower(pathinfo($p, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','webp'], true)) continue;

        if (!preg_match('/^(xss|xs|sm|md|lg|llg|hxs|hmd)_/i', $name) || str_starts_with($name, 'hxOrg_')) {
            $preferExact[filemtime($p) ?: 0] = $p; // Original
        } elseif (preg_match('/^(llg|lg|md|xs|xss)_/i', $name)) {
            $preferLarge[filemtime($p) ?: 0] = $p; // größte Variante
        }
    }

    if ($preferExact) { krsort($preferExact); return reset($preferExact); }
    if ($preferLarge) { krsort($preferLarge); return reset($preferLarge); }
    return null;
}

private function splitRootExt(string $name): array
{
    $ext  = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    $root = preg_replace('/\.(webp|jpe?g|png)$/i', '', $name);
    $root = preg_replace('/^(xss|xs|sm|md|lg|llg|hxs|hmd|hxorg)_/i', '', $root);
    return [$root, $ext ?: 'jpg'];
}

private function needsRebuild(string $outDir, string $src, string $root): bool
{
    $check = ["lg_{$root}.webp", "lg_{$root}.jpg", "llg_{$root}.webp", "llg_{$root}.jpg"];
    foreach ($check as $f) {
        $p = $outDir.'/'.$f;
        if (!is_file($p)) return true;
        if ((filemtime($p) ?: 0) < (filemtime($src) ?: 0)) return true;
    }
    return false;
}

private function buildVariants(string $src, string $outDir, string $root, string $ext, ?int $maxW = null): void
{
    @is_dir($outDir) || @mkdir($outDir, 0775, true);
    $targets = [
        'xss' => 200,
        'xs'  => 360,
        'md'  => 768,
        'lg'  => 1280,
        'llg' => 1920,
    ];
    if ($maxW) {
        foreach ($targets as $k => $w) if ($w > $maxW) unset($targets[$k]);
    }
    foreach ($targets as $pref => $w) {
        $this->writeVariant($src, "{$outDir}/{$pref}_{$root}.webp", $w, 'webp');
        $this->writeVariant($src, "{$outDir}/{$pref}_{$root}.jpg",  $w, 'jpg');
    }
}

private function metaFromExistingOutput(string $outDir, ?int $cid = null, ?int $sid = null): ?array
{
    if (!is_dir($outDir)) return null;
    $bestTime = -1; $bestFile = null;
    foreach (glob($outDir.'/*.{webp,jpg,jpeg}', GLOB_BRACE) ?: [] as $p) {
        $t = filemtime($p) ?: 0;
        if ($t > $bestTime) { $bestTime = $t; $bestFile = $p; }
    }
    if (!$bestFile) return null;
    $name = basename($bestFile);
    [$root, $ext] = $this->splitRootExt($name);
    $baseUrl = str_replace(rtrim(FCPATH,'/'), '', $outDir) . '/';
    return ['baseUrl' => $baseUrl, 'root' => $root, 'ext' => $ext ?: 'jpg'];
}

private function upsertSliceData(int $sliceId, string $key, string $val): void
{
    try {
        $db = $this->db();
        $tbl = $db->table('wr_content_slice_data');
        $exists = $tbl->where([
            'content_slice_id' => $sliceId,
            'name' => $key
        ])->get()->getRowArray();

        if ($exists) {
            $tbl->where('id', $exists['id'])->update(['value' => $val]);
        } else {
            $tbl->insert([
                'content_slice_id' => $sliceId,
                'name'  => $key,
                'value' => $val,
                'position' => 0
            ]);
        }
    } catch (\Throwable) {
        // optional, ignorieren
    }
}
/** Content-Bildvarianten unter /_data/{cid}/output/ erzeugen. */
public function ensureContentImage(int $contentId, string $field = 'image'): ?array
{
    $rootPath = rtrim(FCPATH, '/\\');
    $dir      = "{$rootPath}/_data/{$contentId}";
    $outDir   = "{$dir}/output";

    // Cache-Key nur erlaubte Zeichen
    $ck = 'cimg_' . $contentId . '_' . preg_replace('/[^A-Za-z0-9_]/', '_', $field);
    if (($meta = $this->cache->get($ck)) && $this->filesExist($meta)) {
        return $meta;
    }

    // 1) Dateiname aus DB holen (wr_content.{image|imageBreitFlach})
    $baseName = null;
    try {
        $row = $this->db()
            ->table('wr_content')
            ->select($field)
            ->where('id', $contentId)
            ->get()->getRowArray();
        $val = trim((string)($row[$field] ?? ''));
        if ($val !== '') {
            $val = basename($val);                 // nur Dateiname
            $val = preg_replace('/\.(webp|jpe?g|png)$/i', '', $val); // ohne Ext
            $baseName = $val;                      // z. B. "brusset_weingut"
        }
    } catch (\Throwable) {}

    // 2) Quelle im Content-Ordner auflösen
    $src = null;
    if ($baseName) {
        // bevorzugt Originale ohne Präfix oder hxOrg_
        foreach ([
            "{$dir}/{$baseName}.jpg",
            "{$dir}/{$baseName}.jpeg",
            "{$dir}/{$baseName}.png",
            "{$dir}/{$baseName}.webp",
            "{$dir}/hxOrg_{$baseName}.jpg",
            "{$dir}/hxOrg_{$baseName}.jpeg",
            "{$dir}/hxOrg_{$baseName}.png",
            "{$dir}/hxOrg_{$baseName}.webp",
            // notfalls auch vorhandene Varianten als Quelle
            "{$dir}/llg_{$baseName}.jpg",
            "{$dir}/llg_{$baseName}.webp",
            "{$dir}/lg_{$baseName}.jpg",
            "{$dir}/lg_{$baseName}.webp",
        ] as $p) {
            if (is_file($p)) { $src = $p; break; }
        }
    }

    // wenn nichts gefunden: neueste sinnvolle Datei im Content-Ordner
    if (!$src && is_dir($dir)) {
        $bestT = -1; $best = null;
        foreach (glob($dir.'/*.{jpg,jpeg,png,webp,JPG,JPEG,PNG,WEBP}', GLOB_BRACE) ?: [] as $p) {
            $name = basename($p);
            if (preg_match('/^(xss|xs|sm|md|lg|llg|hxs|hmd)_/i', $name)) continue;
            $t = filemtime($p) ?: 0;
            if ($t > $bestT) { $bestT = $t; $best = $p; }
        }
        $src = $best;
        if ($src) {
            $baseName = preg_replace('/\.(webp|jpe?g|png)$/i', '', basename($src));
            $baseName = preg_replace('/^(hxorg|xss|xs|sm|md|lg|llg|hxs|hmd)_/i', '', $baseName);
        }
    }

    if (!$src || !$baseName) {
        // Fallback: existierende Outputs verwenden
        $m = $this->metaFromExistingOutput($outDir, null, null);
        if ($m) { $this->cache->save($ck, $m, 3600); }
        return $m;
    }

    // 3) Varianten schreiben (nie Ordner löschen)
    @is_dir($outDir) || @mkdir($outDir, 0775, true);
    foreach (['xss'=>200,'xs'=>360,'md'=>768,'lg'=>1280,'llg'=>1920] as $pref => $w) {
        $this->writeVariant($src, "{$outDir}/{$pref}_{$baseName}.webp", $w, 'webp');
        $this->writeVariant($src, "{$outDir}/{$pref}_{$baseName}.jpg",  $w, 'jpg');
    }

    $meta = [
        'baseUrl' => "/_data/{$contentId}/output/",
        'root'    => $baseName,
        'ext'     => 'jpg',
    ];

    if ($this->filesExist($meta)) {
        $this->cache->save($ck, $meta, 3600);
        return $meta;
    }
    return null;
}


}
