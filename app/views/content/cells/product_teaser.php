<?php if (!$w) return; ?>
<?php
$detailUrl = '/'.$_link.'/'.strtolower($w['identifer']).'__'.$w['prodID'];
$lazyAttr  = $_lazy ? 'loading="lazy"' : '';
$hasAkt    = (isset($w['aktions_preis']) && $w['aktions_preis'] > 0);
?>

<div class="teaser teaser_<?= (int)$prod_ID ?> <?= ($inCart > 0 ? 'warenK' : '') ?>">
  <div class="infoProd">
    <?php if ($hasAkt): ?>
      <img src="/_/img/240330-sale.png" alt="Wein Angebote reduziert" class="sale img-responsive" loading="lazy" width="90" height="90" />
    <?php endif; ?>

    <p class="producer">
      <?php
      if (isset($w['cont2prod_identifer'])) {
        echo $App::getLinkfromBracket("[[win || {$w['cont2prod_identifer']} || {$w['producer']}]]", $linkIdentifers);
      } else {
        echo esc($w['producer'] ?? '');
      }
      ?>
    </p>

    <a href="<?= esc($detailUrl) ?>" title="<?= esc("Detailansicht von '{$w['prod_name']}'", 'attr') ?>">
      <h2><?= esc($w['prod_name']) ?></h2>
    </a>

    <div class="Flaschencontainer">
      <div class="flasche position-absolute bottom-0">
        <a href="<?= esc($detailUrl) ?>" title="<?= esc("Detailansicht von '{$w['prod_name']}'", 'attr') ?>">
          <?php if ($img['has_fl_v']): ?>
            <img src="<?= esc($img['src_fl_v']) ?>" alt="<?= esc($w['prod_name'], 'attr') ?>" class="img-responsive" <?= $lazyAttr ?> width="<?= (int)$img['w'] ?>" height="<?= (int)$img['h'] ?>" />
          <?php elseif ($img['has_fl_v_xs']): ?>
            <img src="<?= esc($img['src_fl_v_webp']) ?>" alt="<?= esc($w['prod_name'], 'attr') ?>" class="img-responsive" <?= $lazyAttr ?> width="<?= (int)$img['w'] ?>" height="<?= (int)$img['h'] ?>" />
          <?php elseif ($img['has_alt']): ?>
            <img src="<?= esc($img['src_alt']) ?>" alt="<?= esc($w['prod_name'], 'attr') ?>" class="img-responsive" <?= $lazyAttr ?> width="<?= (int)$img['w'] ?>" height="<?= (int)$img['h'] ?>" />
          <?php else: ?>
            <img src="<?= esc($img['placeholder']) ?>" alt="Bild für diesen Wein folgt bald" class="img-responsive" <?= $lazyAttr ?> width="254" height="254" />
          <?php endif; ?>
        </a>
      </div>
    </div>

    <div class="WeinDetails">
      <div class="angaben">
        <p class="val"><span class="left"><?= esc($w['year'] ?? 'ohne Jahr') ?></span><span class="right"><?= $_type ?></span></p>
        <p class="val"><span class="left"><?= esc($w['al'] ?? 'k.A.') ?></span><span class="right">Vol%</span></p>

        <?php if (!empty($w['producerZert'])): ?>
          <p class="val"><span class="left">bio</span><span class="right"><?= esc($w['producerZert']) ?></span></p>
        <?php endif; ?>

        <?php if (!empty($w['Grapes']) && is_array($w['Grapes'])): ?>
          <?php foreach ($w['Grapes'] as $g): ?>
            <?php $_reb = $App::getLinkfromBracket("[[reb || {$g['grape']}]]", $linkIdentifers); ?>
            <p class="val trauben"><span class="left"><?= (int)$g['percent'] ?>%</span><span class="right"><?= $_reb ?></span></p>
          <?php endforeach; ?>
        <?php endif; ?>

        <p class="val">
          <a href="<?= esc($detailUrl) ?>" class="left" title="<?= esc("Detailansicht von '{$w['prod_name']}'", 'attr') ?>"><span class="detTrleft">&nbsp;</span></a>
          <a href="<?= esc($detailUrl) ?>" class="left litext" title="<?= esc("Detailansicht von '{$w['prod_name']}'", 'attr') ?>"><span class="right details">Beschreibung</span><span class="detTrRight"></span></a>
        </p>

        <?php if (!$hasAkt): ?>
          <p class="val preis">
            <span class="left"><?= esc(number_format((float)$w['content'], 2, ',', '')) ?>l</span>
            <span class="right"><?= esc($App::getMoneyValFormated($w['price'])) ?>&nbsp;&euro;</span>
          </p>
        <?php else: ?>
          <?php $_agProz = $App::getMoneyValFormated(($w['price'] - $w['aktions_preis']) / max(0.01, $w['aktions_preis']) * 100); ?>
          <div class="aktion">
            <div class="aktion-content"><?= esc(number_format((float)$w['content'], 2, ',', '')) ?>l</div>
            <div class="aktion-preis-aktion"><?= esc($App::getMoneyValFormated($w['price'])) ?>&nbsp;&euro;</div>
            <div class="aktion-preis-statt">statt</div>
            <div class="aktion-preis-vorher"><?= esc($App::getMoneyValFormated($w['aktions_preis'])) ?>&nbsp;&euro;</div>
            <div class="aktion-reduktion"><?= esc($_agProz) ?>%</div>
          </div>
        <?php endif; ?>
      </div>

      <div class="basket-line show-small">
        <div class="<?= ($w['no_stock'] > 0 ? 'cart-box' : 'cart-leer').' wei_'.(int)$prod_ID ?>">
          <?php if ($w['no_stock'] > 0): ?>
            <div class="head-cart"></div>
            <div class="add_one wei_<?= (int)$prod_ID ?>">
              <input type="hidden" class="wein" value="<?= (int)$inCart ?>" />
              <div class="no block">
                <span class="number" aria-hidden="true"><?= ($inCart > 0 ? (int)$inCart : 6) ?></span>
                <span class="link-number" aria-hidden="true"></span>
                <ul class="warenkorb small none craft wei_<?= (int)$prod_ID ?>">
                  <li class="nix li_1"></li><li class="nix li_2"></li><li class="nix li_3"></li><li class="nix li_4"></li>
                  <li class="nix li_5"></li><li class="nix li_6"></li><li class="nix li_12"></li><li class="nix li_18"></li>
                  <li class="nix li_24"></li>
                  <li class="input li_oder"></li>
                  <li class="input_val"><input class="input_val" type="tel"/></li>
                </ul>
                <div class="bu_in_ok none"><span class="small">ok</span></div>
              </div>

              <?php if ($inCart > 0): ?>
                <?php $value_this = $inCart." Flaschen = ".$App::getMoneyValFormated($w['price'] * $inCart); ?>
                <div class="text_basket add_bask bg_color477a16"><span class="txt quer wert"><?= esc($value_this) ?>&nbsp;&euro;</span></div>
              <?php else: ?>
                <div class="text_basket add_bask <?= (int)$prod_ID ?>"><span class="txt quer">In den Warenkorb</span></div>
              <?php endif; ?>
            </div>
          <?php else: ?>
            <div class="add sold">
              <div class="add_bask no"><span class="number">0</span></div>
              <div class="text_basket leer"><span class="txt quer"></span></div>
            </div>
          <?php endif; ?>
        </div>

        <p class="val bottom">
          <span class="ang">
            Preis pro Flasche, inkl. MwSt.
            <?php if (!empty($w['content'])): ?>
              - <?= esc($App::getMoneyValFormated($w['price'] / max(0.01, $w['content']))) ?>&euro;/l
            <?php endif; ?>
            &nbsp;
          </span>
          <span class="js versand"></span>
        </p>
      </div>
    </div>
  </div>

  <div class="clearer">&nbsp;</div>
</div>
<?php
// ---- Vertrags-Checks & Defaults (harmlos, verhindert Fatals) ----
$w              = $w              ?? null;
if (!$w || !is_array($w)) return;

$prod_ID        = isset($prod_ID) ? (int)$prod_ID : (int)($w['prodID'] ?? 0);
$_link          = $_link          ?? 'weine';
$_lazy          = !empty($_lazy);
$inCart         = isset($inCart) ? (int)$inCart : 0;
$linkIdentifers = $linkIdentifers ?? null;
$_type          = $_type          ?? '';          // z. B. "Weiß", "Rot", etc.

// Bild-Metadaten-Struktur absichern
$img = array_replace([
  'has_fl_v'       => false,
  'has_fl_v_xs'    => false,
  'has_alt'        => false,
  'src_fl_v'       => '',
  'src_fl_v_webp'  => '',
  'src_alt'        => '',
  'placeholder'    => '/_/img/wein-folgt-xs.webp',
  'w'              => 254,
  'h'              => 254,
], $img ?? []);

// ---- Klassen-Referenz für statische Helper-Calls (verhindert "Undefined variable $App") ----
$App = \App\Libraries\App::class;

// ---- Abgeleitete Werte (nur Präsentationslogik) ----
$detailUrl = '/'.trim($_link,'/').'/'.strtolower($w['identifer'] ?? '').'__'.(int)($w['prodID'] ?? 0);
$lazyAttr  = $_lazy ? 'loading="lazy"' : '';
$hasAkt    = !empty($w['aktions_preis']) && (float)$w['aktions_preis'] > 0;
?>
