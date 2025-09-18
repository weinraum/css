<?php
// ==== Eingaben defensiv entpacken (alles kommt aus der Cell) ====
$seg23                 = $seg23                 ?? '';
$seg2                  = $seg2                  ?? '';
$navigation            = $navigation            ?? '';
$show_nav_wine_menu    = $show_nav_wine_menu    ?? '';
$bread_regionen        = $bread_regionen        ?? '';
$show_winzer_bread     = $show_winzer_bread     ?? '';
$show_winzerCat        = $show_winzerCat        ?? '';
$show_gp_bread         = $show_gp_bread         ?? '';
$show_lex_bread        = $show_lex_bread        ?? '';
$show_weinraum_bread   = $show_weinraum_bread   ?? '';
$show_xs_menue_konto   = $show_xs_menue_konto   ?? '';
$show_desk_menue_konto = $show_desk_menue_konto ?? '';
$nav_bottom            = $nav_bottom            ?? '';
$class                 = $class                 ?? '';
$txtBread2             = $txtBread2             ?? '';
$wherePrice            = $wherePrice            ?? '';
$whereType             = $whereType             ?? '';
$menue                 = $menue                 ?? null; // erwartete Struktur: ['data'=>['pol1'=>..., 'pol2'=>..., ...]]
$weine                 = $weine                 ?? null;
$linkIdentifers        = $linkIdentifers        ?? null;
$ag_customer           = $ag_customer           ?? '';   // kommt aus Cell (Session ausgewertet)
$cartPositions         = (int) ($cartPositions  ?? 0);   // kommt aus Cell
$sess                  = $sess                  ?? [      // kommt aus Cell
    'critPol1'=>[], 'critPol2'=>[], 'critPol3'=>[],
    'critProducer'=>[], 'critProduct'=>[], 'customer'=>[],
    'cart_art'=>[], 'watchlist'=>[], 'informlist'=>[],
];

// Hilfsvariablen
$host                 = $_SERVER['HTTP_HOST'] ?? '';
$nav_show_weinliste   = $nav_show_weinliste   ?? null;  // optionales Flag aus Aufrufer
$nav_show_weine       = $nav_show_weine       ?? null;
$_nohead_spam         = $_nohead_spam         ?? null;

// Sicher: CSRF-Meta (falls verfügbar)
$csrfField = function_exists('csrf_field') ? csrf_field() : '';
?>
<header>
  <div class="top-desk">
    <?= $csrfField ?>
    <p class="class">https://www.weinraum.de/<?= esc($seg23) ?></p>
    <p class="classLand"><?= esc($seg2) ?></p>

    <div class="kopf d-flex flex-row">
      <!-- Brand -->
      <div class="brand desk col-6 col-lg-2">
        <a href="/weinliste/weine_waehlen" class="weinraum">weinraum</a>
        <a href="/weinliste/weine_waehlen" class="unten">
          <span class="li">_______</span><span class="txt">Seit 1998</span><span class="re">_______</span>
        </a>
      </div>

      <!-- Desktop: Icons/Links -->
      <div class="d-none d-sm-block office col-6 col-lg-10">
        <p class="knopfleiste">
          <?php if (!isset($_nohead_spam) || $_nohead_spam !== 1): ?>
            <a href="/" class="head warenkorb">
              <img src="/_/img/ic-kasse.webp" class="warenkorb" alt="weinraum - warenkorb" loading="lazy">
              <span class="bobble <?= $cartPositions > 0 ? 'block' : 'none' ?>"><?= (int) $cartPositions ?></span>
            </a>
            <span class="warenkorb">warenkorb</span>

            <a href="/weinraum/<?= $ag_customer !== '' ? 'mw_orderedWines' : 'anmelden' ?>" class="konto">
              <img src="/_/img/ic-konto.webp" class="konto" alt="mein konto" loading="lazy">
              <span class="konto">konto</span>
            </a>

            <img src="/_/img/ic-suche.webp" class="suche" alt="weinraum - suche" loading="lazy">
            <span class="suche">suche</span>
          <?php endif; ?>
        </p>
        <p class="knopfleiste">
          <a href="/weinraum/<?= $ag_customer !== '' ? 'mw_orderedWines' : 'anmelden' ?>" class="ag-name">
            <?= esc($ag_customer) ?>
          </a>
        </p>
      </div>

      <!-- Mobile: Icons -->
      <div class="d-block d-sm-none office col-6 col-lg-10">
        <p class="knopfleiste">
          <?php if (!isset($_nohead_spam) || $_nohead_spam !== 1): ?>
            <a href="/" class="head warenkorb">
              <img src="/_/img/ic-kasse.webp" class="warenkorb" alt="weinraum - warenkorb" loading="lazy">
              <span class="bobble <?= $cartPositions > 0 ? 'block' : 'none' ?>"><?= (int) $cartPositions ?></span>
            </a>
            <a href="/weinraum/<?= $ag_customer !== '' ? 'mw_orderedWines' : 'anmelden' ?>" class="konto">
              <img src="/_/img/ic-konto.webp" class="konto" alt="mein konto" loading="lazy">
            </a>
            <img src="/_/img/ic-suche.webp" class="suche" alt="weinraum - suche" loading="lazy">
          <?php endif; ?>
        </p>
        <p class="knopfleiste">
          <a href="/weinraum/<?= $ag_customer !== '' ? 'mw_orderedWines' : 'anmelden' ?>" class="ag-name">
            <?= esc($ag_customer) ?>
          </a>
        </p>
      </div>
    </div>

    <p class="linie oben">&nbsp;</p>

    <?php if ($show_nav_wine_menu === 'yes'): ?>
      <!-- Hauptmenü -->
      <div class="d-block d-md-none main_menue">
        <p class="menmob">
          <span class="menue-switch-button-winzer <?= (!empty($nav_show_weinliste) && $nav_show_weinliste === 'yes') ? 'active' : '' ?>">Weinliste</span>
          <span class="menue-switch-button-weine <?= (!empty($nav_show_weine) && $nav_show_weine === 'yes') ? 'active' : '' ?>">Weine</span>
          <a href="/weinraum/kontakt" class="kontakt <?= ($class === 'kontakt') ? 'active' : '' ?>">Kontakt</a>
          <a href="/sale" class="sale"><img src="/_/img/240330-sale.png" alt="Wein Angebote reduziert" class="sale img-responsive" loading="lazy"/></a>
        </p>
        <p class="linie unten">&nbsp;</p>
      </div>

      <div class="d-none d-md-block box_main_men">
        <div class="main_menue">
          <a href="/weinliste/weine_waehlen" class="weine 3 <?= (!empty($nav_show_weinliste) && $nav_show_weinliste === 'yes') ? 'active' : '' ?>">Weinliste</a>
          <a href="/weine/index" class="weine 3 <?= (!empty($nav_show_weine) && $nav_show_weine === 'yes') ? 'active' : '' ?>">Weine</a>
          <a href="/weinraum/kontakt" class="kontakt <?= ($class === 'kontakt') ? 'active' : '' ?>">Kontakt</a>
        </div>
        <p class="linie unten">&nbsp;</p>
      </div>
    <?php endif; ?>

    <div class="clearer"></div>

    <!-- Breadcrumbs -->
    <div class="bread d-flex">
      <?php if (!empty($show_weinraum_bread) && $show_weinraum_bread === 'yes'): ?>
        <div class="bread-inner weinraum">
          <p>
            <a href="/weinraum/anmelden">Mein Konto</a>
            <span class="sep">&nbsp;›› </span><span class="letzt"><?= esc($txtBread2) ?></span>
          </p>
        </div>
      <?php endif; ?>

      <?php if ($navigation === 'wein'): ?>
        <div class="bread-inner wein">
          <p>
            <a href="/wein">Weine</a><?= ($class !== 'wein') ? ' <span class="sep">&nbsp;›› </span>' : '' ?>

            <?php if ($class === 'weisswein'): ?><a href="/weisswein">Weisswein</a><?php endif; ?>
            <?php if ($class === 'rotwein'):   ?><a href="/rotwein">Rotwein</a><?php endif; ?>
            <?php if ($class === 'rose'):      ?><a href="/rose">Rosé</a><?php endif; ?>
            <?php if ($class === 'cremant'):   ?><a href="/cremant">&nbsp;Schaumwein</a><?php endif; ?>

            <?php
            // Land/Pol2/Pol3/Producer/Product aus $sess + $menue lesen, nicht aus $_SESSION
            $urlLand = '';
            if (!empty($sess['critPol1']) && is_array($sess['critPol1']) && !empty($menue['data']['pol1'])) {
                echo "<span class='sep'>&nbsp;››&nbsp;</span>";
                foreach ($sess['critPol1'] as $k => $_) {
                    if (!empty($menue['data']['pol1'][$k]['name_pol1_url'])) {
                        $urlLand = strtolower($menue['data']['pol1'][$k]['name_pol1_url']);
                        echo '<a href="/' . esc($class) . '/' . esc($urlLand) . '">' . esc($menue['data']['pol1'][$k]['name_pol1']) . '</a>';
                    }
                }
            }

            $urlPol2 = '';
            if (!empty($sess['critPol2']) && is_array($sess['critPol2']) && !empty($menue['data']['pol2'])) {
                echo "<span class='sep'>&nbsp;››&nbsp;</span>";
                foreach ($sess['critPol2'] as $k => $_) {
                    if (!empty($menue['data']['pol2'][$k]['name_pol2_url'])) {
                        $urlPol2 = strtolower($menue['data']['pol2'][$k]['name_pol2_url']);
                        echo '<a href="/' . esc($class) . '/' . esc("$urlLand/$urlPol2") . '">' . esc($menue['data']['pol2'][$k]['name_pol2']) . '</a>';
                    }
                }
            }

            if (!empty($sess['critPol3']) && is_array($sess['critPol3']) && !empty($menue['data']['pol3'])) {
                foreach ($sess['critPol3'] as $k => $_) {
                    if (!empty($menue['data']['pol3'][$k]['name_pol3_url'])) {
                        $urlPol3 = strtolower($menue['data']['pol3'][$k]['name_pol3_url']);
                        if ($urlPol3 !== '') echo "<span class='sep'>&nbsp;››&nbsp;</span>";
                        echo '<a href="/' . esc($class) . '/' . esc("$urlLand/$urlPol2/$urlPol3") . '">' . esc($menue['data']['pol3'][$k]['name_pol3']) . '</a>';
                    }
                }
            }

            if (!empty($sess['critProducer']) && is_array($sess['critProducer']) && !empty($menue['data']['producer'])) {
                echo "<span class='sep'>&nbsp;››&nbsp;</span>";
                foreach ($sess['critProducer'] as $k => $_) {
                    if (isset($menue['data']['producer'][$k]['cont2prod']) && (int)$menue['data']['producer'][$k]['cont2prod'] === 0) {
                        echo '<span>' . esc($menue['data']['producer'][$k]['producer']) . '</span>';
                    }
                }
            }

            if (!empty($sess['critProduct']) && is_array($sess['critProduct']) && !empty($weine['wines'])) {
                echo "<span class='sep'>&nbsp;››&nbsp;</span>";
                foreach ($sess['critProduct'] as $k => $_) {
                    if (!empty($weine['wines'][$k]['prod_name'])) {
                        echo esc($weine['wines'][$k]['prod_name']);
                    }
                }
            }

            if ($wherePrice !== '') {
                echo "<span class='sep'>&nbsp;››&nbsp;</span>" . esc($wherePrice);
            }

            if ($whereType !== '') {
                echo "<span class='sep'>&nbsp;››&nbsp;</span>";
                $rest  = substr($whereType, 0, 1);
                $color = ($rest === 'W') ? 'Weiß, ' : (($rest === 'R') ? 'Rot, ' : '');
                echo esc($color);
                // ehemals eh('sidebar_search_wine_type'.$whereType) – falls du hier Text brauchst, bitte vorberechnen und als Variable liefern
            }
            ?>
          </p>
        </div>
      <?php endif; ?>
    </div> <!-- /.bread -->

    <!-- Konto-Navigation (mobil) -->
    <?php if ($show_xs_menue_konto === 'yes'): ?>
      <div class="d-block d-sm-none">
        <nav class="pers-navigation <?= ($nav_bottom === 'line') ? 'bottom' : '' ?>">
          <div class="container-fluid">
            <div class="menue-switch-person">
              <p class="menue-switch-button"><span class="icon-text">Ihr Konto: Menue</span></p>
              <input type="hidden" name="login_nav" value="1">
            </div>

            <div id="pers-konto" class="d-flex flex-row pers-konto">
              <ul class="pers-liste">
                <?php $isLoggedIn = !empty($sess['customer']['id']); ?>
                <?php $sub_navi   = $sub_navi ?? ''; ?>

                <?php if ($isLoggedIn): ?>
                  <li <?= ($sub_navi === 'opOrders') ? 'class="active"' : '' ?>><a href="/weinraum/mw_openOrders">Bestellungen</a></li>
                  <li <?= ($sub_navi === 'invoicesCust') ? 'class="active"' : '' ?>><a href="/weinraum/mw_invoices">Rechnungen</a></li>
                  <li <?= ($sub_navi === 'ordWines') ? 'class="active"' : '' ?>><a href="/weinraum/mw_orderedWines">Weine</a></li>

                  <?php if (!($sess['customer']['GP_Empfaenger_ohne_Daten'] ?? false)): ?>
                    <li <?= ($navigation === 'persDat') ? 'class="line"' : 'class="inact"' ?>>
                      <a href="/weinraum/general_persDat/pure" <?= ($navigation === 'persDat') ? 'class="active"' : '' ?>>Persönliche Daten</a>
                    </li>
                  <?php else: ?>
                    <li <?= ($navigation === 'persDat') ? 'class="line"' : 'class="inact"' ?>>
                      <a href="/weinraum/persDat_GP_loggedIn/daten" <?= ($navigation === 'persDat') ? 'class="active"' : '' ?>>Persönliche Daten</a>
                    </li>
                  <?php endif; ?>

                  <?php if (!empty($sess['customer']['is_admin'])): ?>
                    <li <?= ($sub_navi === 'admin_tg') ? 'class="active"' : '' ?>><a href="/admin_themengarten">Beiträge</a></li>
                    <li <?= ($sub_navi === 'admin_cat') ? 'class="active"' : '' ?>><a href="/admin_category">Kategorien</a></li>
                  <?php endif; ?>

                  <li class="inact navigation_xs_navbar"><a href="/logout">Abmelden</a></li>
                <?php else: ?>
                  <li class="inact navigation_xs_navbar d-block d-sm-none"><br><a href="/weinraum/anmelden">Anmelden</a><br><br><br></li>
                <?php endif; ?>
              </ul>
            </div>
          </div>
        </nav>
      </div>
    <?php endif; ?>

    <!-- Konto-Navigation (Desktop) -->
    <?php if ($show_desk_menue_konto === 'yes'): ?>
      <div class="d-none d-sm-block pers-konto">
        <div class="menue-switch-person <?= ($nav_bottom === 'line') ? 'bottom' : '' ?>">
          <div class="container-fluid">
            <div class="pers-konto d-flex flex-row">
              <ul class="pers-liste d-flex flex-row">
                <a href="#" class="d-none d-sm-block">
                  <?= $ag_customer !== '' ? esc($ag_customer) : 'Ihr Konto im weinraum' ?>
                </a>

                <?php if ($ag_customer === ''): ?>
                  <li class="active d-none d-sm-block"><a href="/weinraum/anmelden" class="anmelden">Anmelden</a></li>
                <?php endif; ?>

                <input type="hidden" name="login_nav" value="1">

                <?php if ($ag_customer !== ''): ?>
                  <?php $sub_navi = $sub_navi ?? ''; $navigation = $navigation ?? ''; $agWert = $agWert ?? null; ?>
                  <li <?= ($navigation === 'basket') ? 'class="line"' : 'class="inact"' ?>>
                    <a href="/weinraum/kasse" class="<?= ($navigation === 'basket') ? 'basket active' : 'basket' ?>">
                      <span class="icon wr-cart"></span>&nbsp;:&nbsp; <?= $agWert ? esc($agWert) . '€' : '' ?>
                    </a>
                  </li>
                  <li <?= ($sub_navi === 'opOrders') ? 'class="active"' : '' ?>><a href="/weinraum/mw_openOrders">Bestellungen</a></li>
                  <li <?= ($sub_navi === 'invoicesCust') ? 'class="active"' : '' ?>><a href="/weinraum/mw_invoices">Rechnungen</a></li>
                  <li <?= ($sub_navi === 'ordWines') ? 'class="active"' : '' ?>><a href="/weinraum/mw_orderedWines">Weine</a></li>

                  <li <?= ($navigation === 'persDat') ? 'class="line active"' : 'class="inact"' ?>>
                    <?php if (!($sess['customer']['GP_Empfaenger_ohne_Daten'] ?? false)): ?>
                      <a href="/weinraum/general_persDat/pure" <?= ($navigation === 'persDat') ? 'class="active"' : '' ?>>Persönliche Daten</a>
                    <?php else: ?>
                      <a href="/weinraum/persDat_GP_loggedIn/daten" <?= ($navigation === 'persDat') ? 'class="active"' : '' ?>>Persönliche Daten</a>
                    <?php endif; ?>
                  </li>

                  <?php if (!empty($sess['customer']['is_admin'])): ?>
                    <li <?= ($sub_navi === 'admin_tg') ? 'class="active"' : '' ?>><a href="/admin_themengarten">Beiträge</a></li>
                    <li <?= ($sub_navi === 'admin_cat') ? 'class="active"' : '' ?>><a href="/admin_category">Kategorien</a></li>
                  <?php endif; ?>

                  <li class="inact"><a href="/logout">Abmelden</a></li>
                <?php endif; ?>
              </ul>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <div class="clearer"></div>
  </div>
</header>

<section>
<!-- ab hier beginnt dein Seiten-Content -->
