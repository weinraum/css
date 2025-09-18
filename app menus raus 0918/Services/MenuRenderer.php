<?php namespace App\Services;

class MenuRenderer
{
    public function renderLeftWineMenu($menue, string $class = ''): string
    {
        if (is_string($menue)) return $menue;

        if (is_array($menue)) {
            $html = '<ul class="left-wine-menu">';
            foreach ($menue as $item) {
                $title  = esc($item['title'] ?? '');
                $url    = esc($item['url'] ?? '#');
                $active = !empty($item['active']) ? ' class="aktiv"' : '';
                $html  .= "<li{$active}><a href=\"{$url}\">{$title}</a></li>";
            }
            return $html . '</ul>';
        }
        return '';
    }

    public function renderTypesMenu(): string
    {
        // TODO: später mit echten Typen-Daten füllen
        return '<div class="left-men-typen content"><p class="hint">Weintypen folgen …</p></div>';
    }
}
