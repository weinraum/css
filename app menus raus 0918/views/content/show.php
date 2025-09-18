<?php
/** @var \App\Domain\ContentAggregate $agg */

helper('image'); // nutzt picture_for_slice() aus app/Helpers/image_helper.php

// ----------------- kleine Helfer -----------------
$normType = static function (?string $t): string {
    $t = strtolower(trim((string)$t));
    $t = str_replace(['-', ' '], '_', $t);
    // Synonyme
    return match ($t) {
        'richtext', 'paragraph'               => 'text',
        'textimage', 'image_text', 'img_text','imgtext' => 'text_image',
        default => $t,
    };
};

$get = static function (array $d, string $key, string $default = ''): string {
    $v = $d[$key] ?? null;
    return is_string($v) ? $v : $default;
};

// ----------------- Renderer -----------------
$cid = (int)($agg->content['id'] ?? 0);
$heroPrinted = false;

$renderHero = static function(array $data, int $cid, int $sid) use (&$heroPrinted, $get): string {
    $title = $get($data, 'title');
    $cap   = $get($data, 'caption');

    if (!function_exists('picture_for_slice')) {
        $tit  = $title !== '' ? '<h2>'.esc($title).'</h2>' : '';
        $capT = $cap   !== '' ? '<p class="caption">'.esc($cap).'</p>' : '';
        return '<section class="slice slice-hero">'.$tit.$capT.'</section>';
    }

    // Hero groß rendern
    $pic = picture_for_slice($cid, $sid, $title, '(min-width: 992px) 1000px, 100vw', 'llg');

    // Nur der allererste Hero: eager + hohe Priorität
    if (!$heroPrinted && $pic !== '') {
        $pic = str_replace('loading="lazy"', 'loading="eager" fetchpriority="high"', $pic);
        $heroPrinted = true;
    }

    $tit  = $title !== '' ? '<h2>'.esc($title).'</h2>' : '';
    $capT = $cap   !== '' ? '<p class="caption">'.esc($cap).'</p>' : '';
    return '<section class="slice slice-hero">'.$tit.$pic.$capT.'</section>';
};


$renderText = static function(array $data): string {
    // Textquellen: html → content → text
    if (!empty($data['html']))    return '<section class="slice slice-text">'.$data['html'].'</section>';
    if (!empty($data['content'])) return '<section class="slice slice-text">'.nl2br(esc($data['content'])).'</section>';
    if (!empty($data['text']))    return '<section class="slice slice-text">'.nl2br(esc($data['text'])).'</section>';
    return '';
};

$renderTextImage = static function(array $data, int $cid, int $sid) use ($get): string {
    $title = $get($data, 'title');
    $cap   = $get($data, 'caption');
    $align = strtolower(trim($get($data, 'align'))); // 'right' → Bild rechts

    // Body: html → content → text
    $body  = '';
    if (!empty($data['html']))         $body = $data['html'];
    elseif (!empty($data['content']))  $body = nl2br(esc($data['content']));
    elseif (!empty($data['text']))     $body = nl2br(esc($data['text']));

$pic = picture_for_slice($cid, $sid, $title, '(min-width: 992px) 50vw, 100vw', 'lg');


    if ($pic === '' && $body === '') return '';

    if ($pic === '') {
        $h = $title !== '' ? '<h3 class="h5">'.esc($title).'</h3>' : '';
        return '<section class="slice slice-text">'.$h.$body.'</section>';
    }

    $capT  = $cap !== '' ? '<p class="caption small text-muted">'.esc($cap).'</p>' : '';
    $txt   = ($title !== '' ? '<h3 class="h5">'.esc($title).'</h3>' : '') . $body;

    $imgCol = '<div class="col-md-6">'.$pic.$capT.'</div>';
    $txtCol = '<div class="col-md-6">'.$txt.'</div>';
    $row    = ($align === 'right') ? $txtCol.$imgCol : $imgCol.$txtCol;

    return '<section class="slice slice-text-image"><div class="row g-3">'.$row.'</div></section>';
};

$renderImage = static function(array $data, int $cid, int $sid, string $title=''): string {
$pic = picture_for_slice($cid, $sid, $title, '(min-width: 992px) 75vw, 100vw', 'llg');

    return $pic !== '' ? '<section class="slice slice-image">'.$pic.'</section>' : '';
};

// ----------------- Ausgabe -----------------
$out = [];

foreach ($agg->slices as $sl) {

    $sid  = (int)($sl['id'] ?? 0);
    $type = $normType($sl['type'] ?? '');
    $data = $sl['data'] ?? [];

    switch ($type) {
        case 'hero':        $out[] = $renderHero($data, $cid, $sid); break;
        case 'text':        $out[] = $renderText($data); break;
        case 'text_image':  $out[] = $renderTextImage($data, $cid, $sid); break;
        case 'image':       $out[] = $renderImage($data, $cid, $sid, $get($data,'title')); break;
        default:
            $out[] = '<div class="info-box">Unbekannter Slice-Typ: '.esc($type).'</div>';
    }
}

echo ($html = implode("\n", array_filter($out))) !== ''
     ? $html
     : '<div class="info-box">Für diesen Inhalt sind aktuell keine darstellbaren Slices vorhanden.</div>';
