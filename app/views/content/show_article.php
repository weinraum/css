<?php declare(strict_types=1);
/** @var \App\Domain\ContentAggregate $agg */
helper(['image']);

$cid   = (int)($agg->content['id'] ?? 0);
$title = (string)($agg->content['title'] ?? '');

// Slice-Daten: content + caption aus wr_content_slice_data
$loadSliceKV = static function (int $sliceId): array {
    $kv = [];
    try {
        $db = db_connect();
        $rows = $db->table('wr_content_slice_data')
            ->select('name, value')
            ->where('content_slice_id', $sliceId)
            ->orderBy('position', 'ASC')
            ->get()->getResultArray();
        foreach ($rows as $r) {
            $n = (string)$r['name'];
            if ($n !== '' && !array_key_exists($n, $kv)) {
                $kv[$n] = (string)$r['value'];
            }
        }
    } catch (\Throwable) {}
    return $kv;
};
?>
<div class="article-flow mx-auto" style="max-width:72ch;">
  <?php if ($title !== ''): ?>
    <h1 class="h2 mb-3"><?= esc($title) ?></h1>
  <?php endif; ?>

  <?php foreach ($agg->slices as $slice): ?>
    <?php
      $sid = (int)($slice['id'] ?? 0);
      if ($sid <= 0) continue;

      $kv      = $loadSliceKV($sid);
      $textRaw = (string)($kv['content'] ?? '');
      $caption = (string)($kv['caption'] ?? '');

      $imgHtml = '';
      if ($cid && $sid && function_exists('picture_for_slice')) {
          // KORREKTE PARAMETER-REIHENFOLGE
          $imgHtml = picture_for_slice($cid, $sid, $title, '(min-width: 900px) 72ch, 100vw', 'lg') ?: '';
      }
    ?>

    <?php if (trim($textRaw) !== ''): ?>
      <div class="mb-3">
        <?= (strpos($textRaw, '<') !== false) ? $textRaw : '<p>'.esc($textRaw).'</p>' ?>
      </div>
    <?php endif; ?>

    <?php if ($imgHtml !== ''): ?>
      <figure class="my-3">
        <?= $imgHtml ?>
        <?php if ($caption !== ''): ?>
          <figcaption class="text-muted small mt-2"><?= esc($caption) ?></figcaption>
        <?php endif; ?>
      </figure>
    <?php endif; ?>
  <?php endforeach; ?>
</div>
