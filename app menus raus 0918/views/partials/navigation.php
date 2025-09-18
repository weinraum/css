<?php
/**
 * app/Views/partials/navigation.php
 *
 * Ein schlankes Partial, das NUR die NavigationCell aufruft.
 * - Alle Parameter sind defensiv vorbelegt.
 * - navTtl/navDebug können vom Controller gesetzt werden (optional).
 */

$navTtl   = isset($navTtl) ? (int)$navTtl : 600;   // Standard: 10 Min Cache
$navDebug = !empty($navDebug);                     // Debug: Cache aus + Marker in HTML

echo view_cell(\App\Cells\NavigationCell::class . '::render', [
    // Segmente/Seitenkontext
    'seg23'                 => $seg23                 ?? '',
    'seg2'                  => $seg2                  ?? '',
    'navigation'            => $navigation            ?? '',   // z.B. 'wein', 'basket', 'persDat' …
    'show_nav_wine_menu'    => $show_nav_wine_menu    ?? '',
    'bread_regionen'        => $bread_regionen        ?? '',
    'show_winzer_bread'     => $show_winzer_bread     ?? '',
    'show_winzerCat'        => $show_winzerCat        ?? '',
    'show_gp_bread'         => $show_gp_bread         ?? '',
    'show_lex_bread'        => $show_lex_bread        ?? '',
    'show_weinraum_bread'   => $show_weinraum_bread   ?? '',
    'show_xs_menue_konto'   => $show_xs_menue_konto   ?? '',
    'show_desk_menue_konto' => $show_desk_menue_konto ?? '',
    'nav_bottom'            => $nav_bottom            ?? '',
    'class'                 => $class                 ?? '',
    'txtBread2'             => $txtBread2             ?? '',

    // Datenquellen
    'menue'                 => $menue          ?? null, // erwartete Struktur: ['data' => …]
    'weine'                 => $weine          ?? null, // erwartete Struktur: ['wines' => …]
    'linkIdentifers'        => $linkIdentifers ?? null, // (Legacy-Key beibehalten)

    // Filter
    'wherePrice'            => $wherePrice ?? '',
    'whereType'             => $whereType  ?? '',

    // Cache/Debug
    'ttl'                   => $navDebug ? 0 : $navTtl,
    'debug'                 => $navDebug,
]);
