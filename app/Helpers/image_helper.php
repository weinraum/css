<?php
use App\Services\ImagePipelineService;

/** Baut <picture> via Pipeline. */
function picture_for_slice(
    int $contentId,
    int $sliceId,
    string $title = '',
    string $sizes = '(min-width: 992px) 50vw, 100vw',
    string $pref = 'lg'
): string {
    /** @var ImagePipelineService $pipe */
    $pipe = service('imagePipelineService');
    $meta = $pipe->ensure($contentId, $sliceId);
    if (!$meta || empty($meta['baseUrl']) || empty($meta['root'])) {
        return '';
    }
    $base = $meta['baseUrl'];
    $root = $meta['root'];

    $webp = $base . "{$pref}_{$root}.webp";
    $jpg  = $base . "{$pref}_{$root}.jpg";

    $alt = $title !== '' ? $title : 'Bild';
    return '<picture>'
        . '<source type="image/webp" srcset="'.esc($webp).'" sizes="'.esc($sizes).'">'
        . '<source type="image/jpeg" srcset="'.esc($jpg ).'" sizes="'.esc($sizes).'">'
        . '<img src="'.esc($webp).'" alt="'.esc($alt).'" loading="lazy" decoding="async" class="img-fluid" sizes="'.esc($sizes).'">'
        . '</picture>';
}

/** <picture> für Content-Teaser (wr_content.image / imageBreitFlach). */
function picture_for_content(int $contentId, string $field = 'image', string $title = '', string $sizes = '(min-width: 992px) 33vw, 100vw', string $pref = 'lg'): string
{
    /** @var ImagePipelineService $pipe */
    $pipe = service('imagePipelineService');
    $meta = $pipe->ensureContentImage($contentId, $field);
    if (!$meta) return '';

    $base = $meta['baseUrl'];
    $root = $meta['root'];
    $webp = $base."{$pref}_{$root}.webp";
    $jpg  = $base."{$pref}_{$root}.jpg";
    $alt  = $title !== '' ? $title : 'Bild';

    return '<picture>'
        . '<source type="image/webp" srcset="'.esc($webp).'" sizes="'.esc($sizes).'">'
        . '<source type="image/jpeg" srcset="'.esc($jpg ).'" sizes="'.esc($sizes).'">'
        . '<img src="'.esc($webp).'" alt="'.esc($alt).'" loading="lazy" decoding="async" class="card-img-top" sizes="'.esc($sizes).'">'
        . '</picture>';
}
