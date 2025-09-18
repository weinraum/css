<?php $items = $tree['items'] ?? []; ?>
<div class="list-group">
  <?php foreach ($items as $it): ?>
    <a class="list-group-item" href="<?= esc($it['url'] ?? '#') ?>">
      <?= esc($it['label'] ?? '') ?>
    </a>
  <?php endforeach; ?>
</div>
