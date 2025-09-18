<?php

function renderWeinMenu(): string
{
    $file = WRITEPATH . 'cache/menus/wein.html';
    if (!is_file($file)) {
        return '<div class="alert alert-info small">Menü wird aufgebaut.</div>';
    }
    $html = @file_get_contents($file);
    return $html !== false ? $html : '<div class="alert alert-info small">Menü wird aufgebaut.</div>';
}
