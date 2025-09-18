<?php namespace App\Services;

final class MenuBuilder
{
    /**
     * Baut aus einer JSON-Quelle ({"items":[...]}) + Kontext ein flaches oder verschachteltes Menü-Array.
     * Erwartet identifier-basierte Items.
     *
     * @param array $source ['items'=>[ ['identifier'=>..., 'title'=>..., 'url'=>..., 'children'=>[]], ... ]]
     * @param array $ctx    ['activeIdentifer'=>?, 'navigation'=>?] – nutze nur identifier, keine Slugs.
     */
    public function buildTree(array $source, array $ctx = []): array
    {
        $items = (array)($source['items'] ?? []);
        $active = (string)($ctx['activeIdentifer'] ?? ($ctx['activeSlug'] ?? '')); // falls alt übergeben wurde
        $markActive = function(array $it) use (&$markActive, $active): array {
            $it['active'] = !empty($active) && isset($it['identifier']) && (string)$it['identifier'] === (string)$active;
            if (!empty($it['children']) && is_array($it['children'])) {
                $it['children'] = array_map($markActive, $it['children']);
                // Bubble-up aktiv
                if (!$it['active']) {
                    foreach ($it['children'] as $c) {
                        if (!empty($c['active'])) { $it['active'] = true; break; }
                    }
                }
            }
            return $it;
        };
        return array_map($markActive, $items);
    }

    /**
     * Liefert einen stabilen Hash über Source+Kontext (für Cache-Key).
     * Wichtig: Nur deterministische, kleine Teile des Kontextes einbeziehen.
     */
    public function computeHash(string $sourceJson, array $ctx = []): string
    {
        $ctxSlim = [
            'activeIdentifer' => $ctx['activeIdentifer'] ?? ($ctx['activeSlug'] ?? null),
            'navigation'      => $ctx['navigation'] ?? null,
        ];
        return sha1($sourceJson . '|' . json_encode($ctxSlim, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
    }
}
