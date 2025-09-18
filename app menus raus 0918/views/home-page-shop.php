<?= $this->extend('layouts/default') ?>
<?= $this->section('content') ?>
<?php
/** Generische Startseite auf Basis von $sections.
 * Struktur pro Section:
 * [
 *   'type'  => 'teaser_row' | 'article_full' | 'carousel' | ...
 *   'title' => string|null,
 *   'cols'  => int (bei teaser_row),
 *   'items' => [
 *      [
 *        'cid'     => int,
 *        'title'   => string,
 *        'url'     => string,
 *        'excerpt' => ?string,
 *        'meta'    => ['type'=>?, 'updated_at'=>?],
 *        'render'  => ['mode' => 'teaser_row'|'article_full'|...],
 *        'agg'     => \App\Domain\ContentAggregate (nur bei article_full, optional)
 *      ],
 *   ],
 *   'id'    => string|null
 * ]
 */
helper(['image']); // picture_for_content(), picture_for_slice()
$sections = $sections ?? [];
?>

<?php foreach ($sections as $sec): ?>
  <?php $type = (string)($sec['type'] ?? ''); ?>

  <?php if ($type === 'teaser_row'): ?>
    <div class="container my-4">
      <?php if (!empty($sec['title'])): ?>
        <h2 class="h4 mb-3"><?= esc($sec['title']) ?></h2>
      <?php endif; ?>
      <div class="row g-3">
        <?php
          $cols = (int)($sec['cols'] ?? 3);
          $colClass = match ($cols) {
            2 => 'col-md-6',
            3 => 'col-md-4',
            4 => 'col-md-3',
            default => 'col-md-4'
          };
        ?>
        <?php foreach ($sec['items'] as $item): ?>
          <div class="col-12 <?= $colClass ?>">
            <div class="card h-100 shadow-sm">
              <?php
                $cid   = (int)($item['cid'] ?? 0);
                $title = (string)($item['title'] ?? '');
                $url   = (string)($item['url'] ?? '#');

                // Bild via Pipeline/Helper; erst breit/flach, dann Standard
                $imgHtml = '';
                if ($cid > 0 && function_exists('picture_for_content')) {
                    $imgHtml = picture_for_content($cid, 'imageBreitFlach', $title, '(min-width: 992px) 33vw, 100vw', 'lg');
                    if ($imgHtml === '') {
                        $imgHtml = picture_for_content($cid, 'image', $title, '(min-width: 992px) 33vw, 100vw', 'lg');
                    }
                }
              ?>
              <?php if ($imgHtml !== ''): ?>
                <?= $imgHtml ?>
              <?php else: ?>
                <div class="ratio ratio-16x9 bg-light d-flex align-items-center justify-content-center">
                  <span class="text-muted small">Kein Bild · ID <?= (int)$cid ?></span>
                </div>
              <?php endif; ?>

              <div class="card-body">
                <h3 class="h5 mb-2">
                  <a href="<?= esc($url) ?>" class="stretched-link text-decoration-none">
                    <?= esc($title) ?>
                  </a>
                </h3>
                <?php if (!empty($item['excerpt'])): ?>
                  <p class="card-text"><?= esc($item['excerpt']) ?></p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

<?php elseif ($type === 'article_full'): ?>
  <?php foreach ($sec['items'] as $a): ?>
    <section class="container my-5">
      <div class="row">
<aside class="col-12 col-lg-3 mb-4 mb-lg-0">
  <?= view_cell(\App\Cells\NavigationCell::class.'::render', [
      'part' => 'left'
  ]) ?>
</aside>


        <main class="col-12 col-lg-9">
          <?php if (!empty($a['agg'])): ?>
            <?= view('content/show_article', ['agg' => $a['agg']]) ?>
          <?php else: ?>
            <!-- Fallback ohne Aggregate -->
            <h2 class="h3 mb-3"><?= esc((string)($a['title'] ?? '')) ?></h2>
            <?php if (!empty($a['excerpt'])): ?>
              <p class="lead"><?= esc((string)$a['excerpt']) ?></p>
            <?php endif; ?>
          <?php endif; ?>
        </main>
      </div>
    </section>
  <?php endforeach; ?>


  <?php elseif ($type === 'carousel'): ?>
    <?php $carouselId = esc($sec['id'] ?? ('carousel-'.uniqid())); ?>
    <div id="<?= $carouselId ?>" class="carousel slide my-4" data-bs-ride="carousel">
      <div class="carousel-inner">
        <?php foreach ($sec['items'] as $i => $item): ?>
          <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
            <?php
              $cid   = (int)($item['cid'] ?? 0);
              $title = (string)($item['title'] ?? '');
              $imgHtml = '';
              if ($cid > 0 && function_exists('picture_for_content')) {
                  $imgHtml = picture_for_content($cid, 'imageBreitFlach', $title, '(min-width: 992px) 100vw, 100vw', 'llg');
                  if ($imgHtml === '') {
                      $imgHtml = picture_for_content($cid, 'image', $title, '(min-width: 992px) 100vw, 100vw', 'llg');
                  }
              }
            ?>
            <?php if ($imgHtml !== ''): ?>
              <?= $imgHtml ?>
            <?php else: ?>
              <div class="ratio ratio-21x9 bg-light d-flex align-items-center justify-content-center">
                <span class="text-muted small">Kein Bild · ID <?= (int)$cid ?></span>
              </div>
            <?php endif; ?>
            <div class="carousel-caption d-none d-md-block">
              <h3 class="h5">
                <a class="text-white text-decoration-none" href="<?= esc((string)($item['url'] ?? '#')) ?>">
                  <?= esc($title) ?>
                </a>
              </h3>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Zurück</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Weiter</span>
      </button>
    </div>

  <?php endif; ?>
<?php endforeach; ?>

<?= $this->endSection() ?>
