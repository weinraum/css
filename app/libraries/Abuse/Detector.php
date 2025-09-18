<?php namespace App\Libraries\Abuse;

use CodeIgniter\HTTP\RequestInterface;
use Config\Abuse as AbuseCfg;

final class Detector
{
    public static function routeProfile(RequestInterface $req): string
    {
        $path = trim($req->getUri()->getPath(), '/');
        if (str_starts_with($path, 'api/'))   return 'ajax';
        if (str_contains($path, 'login'))     return 'login';
        if (str_contains($path, 'search'))    return 'search';
        if (str_contains($path, 'kontakt'))   return 'contact';
        return 'public';
    }

    public static function analyze(RequestInterface $req, string $profile): array
    {
        /** @var AbuseCfg $cfg */
        $cfg   = config('Abuse');
        $hits  = [];
        $path  = $req->getUri()->getPath();

        // 1) Verbotene Pfad-Fragmente
        if (in_array('forbidden_paths', $cfg->profiles[$profile] ?? [], true)) {
            foreach ($cfg->forbiddenPaths as $frag) {
                if (stripos($path, $frag) !== false) {
                    $hits[] = ['rule'=>'FORBIDDEN_PATH','score'=>$cfg->scores['forbidden_path'],'snippet'=>$frag];
                    break;
                }
            }
        }

        // 2) Eingabe-Vektoren sammeln
        $params = array_merge($req->getGet() ?? [], $req->getPost() ?? []);
        $cookies = $req->getCookie() ?? [];
        $ua  = (string) $req->getHeaderLine('User-Agent');
        $ref = (string) $req->getHeaderLine('Referer');

        // 3) SQL-Keywords in "nicht-Freitext"-Feldern blockend scoren
        if (in_array('sql_keywords_in_disallowed', $cfg->profiles[$profile] ?? [], true)) {
            foreach ($params as $k => $v) {
                if (!is_string($v) || $v === '') continue;

                $isFree = in_array(strtolower($k), ['q','query','search','message','comment','text'], true);
                $norm   = self::normalize($v);

                // Sonderfall: Profil "search" erlaubt einzelnes "union" (nur loggen)
                if ($profile === 'search') {
                    if (self::hasUnionSelect($norm)) {
                        $hits[] = ['rule'=>'SQL_UNION_SELECT','score'=>$cfg->scores['sql_keyword'],'snippet'=>mb_substr($v,0,64)];
                        break;
                    }
                    if (!$isFree && self::containsSqlKeyword($norm, $cfg, allowBareUnion:false)) {
                        $hits[] = ['rule'=>'SQL_KEYWORD','score'=>$cfg->scores['sql_keyword'],'snippet'=>mb_substr($v,0,64)];
                        break;
                    }
                    // einzelnes "union" in Suche: nur loggen (niedriger Score)
                    if (self::isBareUnion($norm)) {
                        $hits[] = ['rule'=>'SQL_BARE_UNION_SEARCH','score'=>1,'snippet'=>mb_substr($v,0,64)];
                    }
                } else {
                    // Alle anderen Profile: hart — englische SQL-Keywords immer scoren/blocken
                    if (!$isFree && (self::hasUnionSelect($norm) || self::containsSqlKeyword($norm, $cfg, allowBareUnion:false))) {
                        $hits[] = ['rule'=>'SQL_KEYWORD','score'=>$cfg->scores['sql_keyword'],'snippet'=>mb_substr($v,0,64)];
                        break;
                    }
                }
            }
        }

        // 4) Danger-Sequenzen
        if (in_array('danger_sequences', $cfg->profiles[$profile] ?? [], true)) {
            foreach ($params as $v) {
                if (!is_string($v) || $v === '') continue;
                $norm = self::normalize($v, decodeOnly:true); // Zeichenfolgen prüfen
                if (preg_match('/(--|\/\*|\*\/|;|0x[0-9a-f]{2,}|%[0-9a-f]{2,})/i', $norm)) {
                    $hits[] = ['rule'=>'DANGER_SEQ','score'=>$cfg->scores['danger_seq'],'snippet'=>mb_substr($v,0,64)];
                    break;
                }
            }
        }

        // 5) Payload-Burst (Gesamtlänge / Encoding-Exzesse)
        if (in_array('payload_burst', $cfg->profiles[$profile] ?? [], true)) {
            $totalLen = 0; foreach ($params as $v) $totalLen += is_string($v)? strlen($v) : 0;
            if ($totalLen > 16*1024 || self::encodingBurst($params)) {
                $hits[] = ['rule'=>'PAYLOAD_BURST','score'=>$cfg->scores['payload_burst'],'snippet'=> (string) $totalLen.'B'];
            }
        }

        // 6) Header/Cookies: Zero Tolerance für SQL-Fragmente (alle Profile)
        //    → in deiner deutschsprachigen Seite nie legitim
        foreach ($cookies as $ck => $cv) {
            if (!is_string($cv) || $cv === '') continue;
            $norm = self::normalize($cv);
            if (self::hasUnionSelect($norm) || self::containsSqlKeyword($norm, $cfg, allowBareUnion:false)) {
                $hits[] = ['rule'=>'SQL_COOKIE','score'=>$cfg->scores['sql_keyword'],'snippet'=>mb_substr($cv,0,64)];
                break;
            }
        }
        if ($ua !== '') {
            $norm = self::normalize($ua);
            if (self::hasUnionSelect($norm) || self::containsSqlKeyword($norm, $cfg, allowBareUnion:false)) {
                $hits[] = ['rule'=>'SQL_UA','score'=>$cfg->scores['sql_keyword'],'snippet'=>mb_substr($ua,0,64)];
            }
        }
        if ($ref !== '') {
            $norm = self::normalize($ref);
            if (self::hasUnionSelect($norm) || self::containsSqlKeyword($norm, $cfg, allowBareUnion:false)) {
                $hits[] = ['rule'=>'SQL_REF','score'=>$cfg->scores['sql_keyword'],'snippet'=>mb_substr($ref,0,64)];
            }
        }

        return $hits;
    }

    // ------- Helpers -------

    private static function hasUnionSelect(string $s): bool
    {
        return str_contains($s, 'union select') || str_contains($s, 'information_schema');
    }

    private static function isBareUnion(string $s): bool
    {
        // "union" als eigenständiges Wort, nicht gefolgt von "select"
        if (!preg_match('/\bunion\b/i', $s)) return false;
        return !str_contains($s, 'union select');
    }

    private static function containsSqlKeyword(string $s, AbuseCfg $cfg, bool $allowBareUnion): bool
    {
        // bare "union" optional
        if (!$allowBareUnion && preg_match('/\bunion\b/i', $s) && !str_contains($s, 'union select')) {
            // wird hier NICHT als Treffer gewertet (Sonderfall handled elsewhere)
            // und fällt nur in Search als Low-Score-Log auf
        }

        // Keywords aus Config (englisch, in deinem Setup nie legitim)
        if (!empty($cfg->sqlKeywords)) {
            $rx = '/\b(' . implode('|', array_map('preg_quote', $cfg->sqlKeywords)) . ')\b/i';
            if (preg_match($rx, $s)) return true;
        }
        return false;
    }

    private static function encodingBurst(array $params): bool
    {
        $joined = implode('&', array_map(fn($v)=> is_string($v)?$v:'', $params));
        $pct = substr_count($joined, '%');
        return $pct > 200; // Heuristik
    }

    private static function normalize(string $v, bool $decodeOnly = false): string
    {
        $s = $v;
        // mehrstufig URL-decodieren
        for ($i=0; $i<3; $i++) {
            $prev = $s;
            $s = rawurldecode($s);
            if ($s === $prev) break;
        }
        // HTML-Entities
        $s = html_entity_decode($s, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        if ($decodeOnly) return $s;
        // Kleinbuchstaben
        $s = mb_strtolower($s, 'UTF-8');
        // überflüssige Whitespaces normalisieren
        $s = preg_replace('/\s+/', ' ', $s);
        return $s;
    }
}
