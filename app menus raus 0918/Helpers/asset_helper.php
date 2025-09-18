<?php

use Config\App;

if (! function_exists('asset_url')) {
    function asset_url(string $path = ''): string
    {
        $base = config(App::class)->assetBase ?? '/';
        return rtrim($base, '/') . '/' . ltrim($path, '/');
    }
}

function asset(string $path): string {
    $url = base_url($path);
    $fs  = FCPATH . ltrim($path, '/');
    $v   = is_file($fs) ? filemtime($fs) : time();
    return $url . '?v=' . $v;
}