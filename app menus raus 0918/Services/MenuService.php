<?php namespace App\Services;

use Config\Menu as MenuConfig;

final class MenuService
{
    public function __construct(
        private MenuSourceProvider $source,
        private MenuBuilder $builder,
        private MenuCache $cache,
        private MenuConfig $cfg
    ) {}
// oben in der Klasse ergänzen:
private function buildTreeCompat(string $section, string $locale, string $srcJson): array
{
    // 1) JSON -> Array
    $src = json_decode($srcJson, true) ?: [];

    // 2) Mehrere mögliche Builder-Methoden unterstützen
    //    (je nachdem, wie dein MenuBuilder implementiert ist)
    if (method_exists($this->builder, 'buildFromSource')) {
        // Neuer Stil: nimmt String/JSON
        /** @noinspection PhpUndefinedMethodInspection */
        return $this->builder->buildFromSource($srcJson);
    }

    if (method_exists($this->builder, 'buildFromArray')) {
        // Häufiger Stil: nimmt bereits geparstes Array
        /** @noinspection PhpUndefinedMethodInspection */
        return $this->builder->buildFromArray($src, $section, $locale);
    }

    if (method_exists($this->builder, 'build')) {
        // Generisch: build($section, $data, $ctx?)
        /** @noinspection PhpUndefinedMethodInspection */
        return $this->builder->build($section, $src, ['locale' => $locale]);
    }

    // Letzte Rettung: erwarte Struktur ['items'=>[]]
    return is_array($src) && isset($src['items']) ? $src : ['items' => []];
}

    public function getTree(string $section, string $locale='de'): array
    {
        $locale  = $locale ?: $this->cfg->defaultLocale;
        $srcJson = $this->source->get($section, $locale);
        if ($srcJson === '') return ['items'=>[]];

        $hash = substr(sha1($srcJson), 0, 16);
        if ($t = $this->cache->getTree($section,$locale,$hash)) return $t;

        if (!$this->cache->tryLock($section,$locale)) {
            usleep(150*1000);
            return $this->getTree($section,$locale);
        }
        try {
$tree = $this->buildTreeCompat($section, $locale, $srcJson);
            $this->cache->invalidateSection($section,$locale); // alte Hashes loswerden
            $this->cache->saveTree($section,$locale,$hash,$tree);
            return $tree;
        } finally {
            $this->cache->unlock($section,$locale);
        }
    }

    public function render(string $section, string $locale='de', array $ctx=[]): string
    {
        $locale  = $locale ?: $this->cfg->defaultLocale;
        $srcJson = $this->source->get($section, $locale);
        if ($srcJson === '') return '';

        $hash = substr(sha1($srcJson), 0, 16);
        if ($html = $this->cache->getHtml($section,$locale,$hash)) return $html;

        $tree = $this->getTree($section,$locale);
        $html = view('menus/'.$section, ['tree'=>$tree, 'ctx'=>$ctx]);
        $this->cache->saveHtml($section,$locale,$hash,$html);
        return $html;
    }

    /** Admin invalidieren (Section oder alle) */
    public function invalidate(?string $section=null, string $locale='de'): void
    {
        $targets = $section ? [$section] : $this->cfg->sections;
        foreach ($targets as $sec) $this->cache->invalidateSection($sec, $locale);
    }
}
