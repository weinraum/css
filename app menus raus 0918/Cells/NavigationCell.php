<?php
namespace App\Cells;

use CodeIgniter\View\Cells\Cell;

class NavigationCell extends Cell
{
    // ---- Eingaben aus view_cell(...) ----
    public ?string $part       = 'top';   // 'top' oder 'left'
    public ?string $class      = 'home';
    public ?string $method     = '';
    public ?string $navigation = 'home';

    public ?string $simuliere_leftbar_regionen  = 'no';
    public ?string $simuliere_leftbar_wein      = 'no';
    public ?string $simuliere_leftbar_winzer    = 'no';
    public ?string $simuliere_leftbar_weintypen = 'no';
    public ?string $simuliere_leftbar_lexikon   = 'no';
    public ?string $show_leftbar_lexikon        = 'no';
    public ?string $show_leftbar_wein           = 'no';

    public ?string $lexikon = '';

    // HTML-Fragmente (können übergeben ODER automatisch befüllt werden)
    public ?string $menueRegionen   = '';
    public ?string $menueWinzer     = '';
    public ?string $menueWeinfarbe  = '';
    public ?string $rebsortenLinks  = '';
    public $menue = null; // Top-Menü

    public ?string $headerH1 = '';
    public ?string $page     = '';
    public ?string $seitSpan = '';
    public ?string $seitLink = '';

    public int $cache_ttl = 0;

    // -------------------------------------
    // Render
    // -------------------------------------
    public function render(): string
    {
        // Cache-Key ohne verbotene Zeichen
        $key = 'wr_navcell_' . md5(json_encode([
            $this->part, $this->class, $this->method, $this->navigation,
            $this->simuliere_leftbar_regionen, $this->simuliere_leftbar_wein,
            $this->simuliere_leftbar_winzer, $this->simuliere_leftbar_weintypen,
            $this->simuliere_leftbar_lexikon, $this->show_leftbar_lexikon, $this->show_leftbar_wein,
            $this->lexikon,
        ], JSON_UNESCAPED_UNICODE));

        if ($this->cache_ttl > 0) {
            $cached = cache()->get($key);
            if (is_string($cached)) {
                return $cached;
            }
        }

        $this->hydrateMenus();

        $data = $this->toArray();
        $data['menue']          = $data['menue']          ?? '';
        $data['menueRegionen']  = $data['menueRegionen']  ?? '';
        $data['menueWeinfarbe'] = $data['menueWeinfarbe'] ?? '';
        $data['menueWinzer']    = $data['menueWinzer']    ?? '';
        $data['rebsortenLinks'] = $data['rebsortenLinks'] ?? '';

        // Partials: top oder left
        $view = $this->part === 'left' ? 'Cells/nav_left' : 'Cells/nav_top';
        $html = view($view, $data);

        if ($this->cache_ttl > 0) {
            cache()->save($key, $html, $this->cache_ttl);
        }
        return $html;
    }

    private function toArray(): array
    {
        return [
            'part' => $this->part,
            'class' => $this->class,
            'method' => $this->method,
            'navigation' => $this->navigation,

            'simuliere_leftbar_regionen'  => $this->simuliere_leftbar_regionen,
            'simuliere_leftbar_wein'      => $this->simuliere_leftbar_wein,
            'simuliere_leftbar_winzer'    => $this->simuliere_leftbar_winzer,
            'simuliere_leftbar_weintypen' => $this->simuliere_leftbar_weintypen,
            'simuliere_leftbar_lexikon'   => $this->simuliere_leftbar_lexikon,
            'show_leftbar_lexikon'        => $this->show_leftbar_lexikon,
            'show_leftbar_wein'           => $this->show_leftbar_wein,

            'lexikon'        => $this->lexikon,
            'menueRegionen'  => $this->menueRegionen,
            'menueWinzer'    => $this->menueWinzer,
            'menueWeinfarbe' => $this->menueWeinfarbe,
            'rebsortenLinks' => $this->rebsortenLinks,
            'menue'          => $this->menue,

            'headerH1' => $this->headerH1,
            'page'     => $this->page,
            'seitSpan' => $this->seitSpan,
            'seitLink' => $this->seitLink,
        ];
    }

    // -------------------------------------
    // Daten aus dem MenuService ziehen
    // -------------------------------------
    private function hydrateMenus(): void
    {
        /** @var \App\Services\MenuService $svc */
        $svc = service('menuService');

        $ctx = [
            'activeSlug' => $this->lexikon ?: null,
            'navigation' => $this->navigation ?: null,
            'class'      => $this->class ?? null,
        ];

        // Top-Menü
        if (!$this->menue) {
            $this->menue = $svc->render('main', 'de', $ctx) ?: '';
        }

        // Linke Spalte
        if ($this->menueRegionen === '') {
            $this->menueRegionen = $svc->render('regionen', 'de', $ctx) ?: '';
        }
        if ($this->menueWinzer === '') {
            $this->menueWinzer = $svc->render('winzer', 'de', $ctx) ?: '';
        }
if ($this->menueWeinfarbe === '') {
    // 1) statische Section 'wein' (wr_menu_source)
    $this->menueWeinfarbe = $svc->render('wein', 'de', $ctx) ?: '';
}

if ($this->menueWeinfarbe === '') {
    // 2) Fallback: dynamisch aus 'wein_winzer' (Region -> Winzer)
    try {
        $tree  = $svc->getTree('wein_winzer', 'de');
        $items = $tree['items'] ?? [];
        if ($items) {
            if (class_exists(\App\Services\MenuRenderer::class)) {
                $this->menueWeinfarbe = (new \App\Services\MenuRenderer())
                    ->renderLeftWineMenu($items, $this->class ?? '');
            } else {
                // Minimaler Fallback ohne Renderer
                $html = '<ul class="left-wine-menu">';
                foreach ($items as $it) {
                    $title = esc($it['title'] ?? '');
                    $url   = esc($it['url'] ?? '#');
                    $html .= "<li><a href=\"{$url}\">{$title}</a></li>";
                }
                $this->menueWeinfarbe = $html . '</ul>';
            }
        }
    } catch (\Throwable) {
        // still quietly empty
    }
}

        if ($this->rebsortenLinks === '') {
            $maybe = $svc->render('lexikon-rebsorten', 'de', $ctx);
            if ($maybe !== '') $this->rebsortenLinks = $maybe;
        }
    }
}
