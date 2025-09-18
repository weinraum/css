<?php
/** @var array $tree */
/** @var array $ctx */

// Erwartete Struktur: $tree = ['items' => [ ['label'=>'…','url'=>'…','children'=>[...]], ... ] ]
$items = $tree['items'] ?? [];

if (!$items) {
  // Sichtbarer Fallback für Debug
  echo '<ul class="nav nav-pills py-2"><li class="nav-item"><span class="nav-link disabled">Menü leer</span></li></ul>';
  return;
}

// Flacher Renderer (ohne Dropdowns). Bei Bedarf auf Dropdown erweitern.
echo '<ul class="nav nav-pills py-2">';
foreach ($items as $it) {
  $label = $it['label'] ?? '';
  $url   = $it['url']   ?? '#';
  echo '<li class="nav-item"><a class="nav-link" href="'.esc($url).'">'.esc($label).'</a></li>';
}
echo '</ul>';
