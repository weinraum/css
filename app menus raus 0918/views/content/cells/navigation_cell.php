<?php
// Erwartete Variablen aus der Cell:
// $menue (Topnav-HTML), $menueRegionen, $menueWinzer, $menueWeinfarbe, $rebsortenLinks
// Flags: $show_leftbar_wein, $show_leftbar_lexikon, $simuliere_leftbar_regionen, $simuliere_leftbar_winzer
?>
<header class="container-fluid border-bottom mb-3">
  <div class="container py-2 d-flex justify-content-between align-items-center">
    <a class="navbar-brand fw-semibold" href="/">weinraum</a>
    <nav aria-label="Hauptnavigation">
      <?= $menue ?? '' ?>
    </nav>
  </div>
</header>

<div class="container">
  <div class="row">
    <aside class="col-12 col-md-3 d-none d-md-block">
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
    </aside>

    <main class="col-12 col-md-9">
      <?php if (!empty($headerH1)): ?>
        <h1 class="h4 mt-2"><?= esc($headerH1) ?></h1>
      <?php endif; ?>
      <!-- Hinweis: Der eigentliche Seiteninhalt steht in deiner View unterhalb des view_cell()-Aufrufs -->
    </main>
  </div>
</div>
