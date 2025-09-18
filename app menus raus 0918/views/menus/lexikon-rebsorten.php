<?php $items = $tree['items'] ?? []; ?>
<ul class="list-unstyled">
  <?php foreach ($items as $it): ?>
    <li><a href="<?= esc($it['url'] ?? '#') ?>"><?= esc($it['label'] ?? '') ?></a></li>
  <?php endforeach; ?>
</ul>
