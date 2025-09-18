<?php $App = new \App\Libraries\App(); ?>
<footer>
  <div class="d-flex flex-wrap col-footer footer-desc-imp col-12">
    <div class="col-footer-desc d-flex d-wrap col-12 col-md-4">
      <div class="d-xs-block d-md-none col-1">&nbsp;</div>
      <div class="col-11 col-md-12 footer-links">
        <p class="footer">Einkaufen</p>
        <a href="/weinraum/versandkosten">Zahlung: Überweisung</a>
        <p class="row-text">Versand: meist in 1 - 2 Tagen</p>
        <p class="img"><img src="/_/img/vers_kl.jpg" class="versand" alt="dhl" title="Versand mit DHL" loading="lazy"></p>
        <a href="/weinraum/versandkosten">Versandkosten</a>
        <a href="/weinraum/widerruf">Widerrufsrecht</a>
      </div>
    </div>

    <div class="clearer d-xs-block d-md-none"><br><br></div>

    <div class="col-footer-desc d-flex d-wrap col-12 col-md-4">
      <div class="d-xs-block d-md-none col-1">&nbsp;</div>
      <div class="col-11 col-md-12">
        <p class="footer">Information</p>
        <a href="/weinraum/weinversand">Darum weinraum</a>
        <a href="/weinraum/beratung">Persönliche Beratung</a>
        <a href="/weinraum/agb">AGB</a>
        <a href="/weinraum/datenschutz">Datenschutz</a>
        <a href="/weinraum/impressum/">Impressum</a>
        <a href="/lexikon/">Wein Lexikon</a>
        <a href="/wein_passend_zu/salat_gemuese_fleisch_fisch/">Wein passend zu Essen</a>
      </div>
    </div>

    <div class="clearer d-xs-block d-md-none"><br><br></div>

    <div class="col-footer-desc d-flex d-wrap col-12 col-md-4">
      <div class="d-xs-block d-md-none col-1">&nbsp;</div>
      <div class="col-11 col-md-12 footer-kontakt">
        <p class="footer">Ihr Kontakt mit dem weinraum</p>
        <p class="row-text"><a href="tel:088112232766" class="phone">Tel. 0881 122 327 66</a></p>
        <p class="row-text"><span>E-Mail:</span> <a href="mailto:thomas.henke@weinraum.de" class="email">thomas.henke@weinraum.de</a></p>

        <p class="row-text"><br>Angebote nur im gut gemachten Newsletter:</p>
        <form id="newlog" action="/weinraum/subscribe" method="post">
          <?= csrf_field() ?>
          <p class="adress">
            <input name="email_subr_footer" class="gp-email" placeholder="Ihre E-Mail Adresse" type="email" required>
            <button type="submit" class="subsc-send">Anmelden</button>
          </p>
          <input type="hidden" name="_url" value="<?= esc(uri_string()) ?>">
        </form>
      </div>
    </div>

    <div class="clearer d-xs-block d-sm-none"><br><br></div>
    <div class="clearer"></div>
  </div>
</footer>
