<?php
/**
 * Erwartet:
 * - bool $showWeinMenu
 * - bool $showLexikonBox
 * - string $weinMenuTitle
 * - string $lexikonBoxTitle
 */
helper('menu');
?>
<aside class="leftbar" aria-label="Seitennavigation">
  <?php if (!empty($showWeinMenu)): ?>
    <section class="leftbar-section leftbar-wein">
      <h2 class="leftbar-title"><?= esc($weinMenuTitle) ?></h2>
      <?= renderWeinMenu() ?>
    </section>
  <?php endif; ?>

  <?php if (!empty($showLexikonBox)): ?>
    <section class="leftbar-section leftbar-lexikon">
      <h2 class="leftbar-title"><?= esc($lexikonBoxTitle) ?></h2>
      <ul class="lexikon-links">
        <li><a href="/blogs/lexikon/ester">Ester</a></li>
        <li><a href="/blogs/lexikon/spontangaerung">Spontangärung</a></li>
        <li><a href="/blogs/lexikon/norisoprenoide">Norisoprenoide</a></li>
      </ul>
    </section>
  <?php endif; ?>
</aside>
