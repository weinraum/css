<?php // Linke Spalte, ohne <main> – nur Aside-Inhalt! ?>
<?php if (($show_leftbar_wein ?? 'no') === 'yes'): ?>
  <div class="mb-3">
    <div class="fw-semibold mb-1">Wein</div>
    <?= $menueWeinfarbe ?? '' ?>
  </div>
<?php endif; ?>

<?php if (($show_leftbar_lexikon ?? 'no') === 'yes'): ?>
  <div class="mb-3">
    <div class="fw-semibold mb-1">Lexikon</div>
    <?= $rebsortenLinks ?? '' ?>
  </div>
<?php endif; ?>

<?php if (($simuliere_leftbar_regionen ?? 'no') === 'yes' && !empty($menueRegionen)): ?>
  <div class="mb-3">
    <div class="fw-semibold mb-1">Regionen</div>
    <?= $menueRegionen ?>
  </div>
<?php endif; ?>

<?php if (($simuliere_leftbar_winzer ?? 'no') === 'yes' && !empty($menueWinzer)): ?>
  <div class="mb-3">
    <div class="fw-semibold mb-1">Winzer</div>
    <?= $menueWinzer ?>
  </div>
<?php endif; ?>
