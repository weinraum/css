<?php $App = new \App\Libraries\App(); ?>
<noscript id="css"></noscript>

<?php if (!isset($_SESSION['customer_unsubscribed']) || $_SESSION['customer_unsubscribed'] != 1): ?>
  <!-- Modal: Warenkorb -->
  <div class="modal-warenkorb none <?= (isset($_showkasse) && $_showkasse == 1) ? 'kasse' : '' ?>">
    <div class="text">
      <p class="schliessen">schliessen X</p>
      <!-- ... (DEIN kompletter Warenkorb-Modal-Block unverändert) ... -->
      <?php /* ich kürze hier nicht: übernimm einfach deinen gesamten Warenkorb-HTML von oben bis </div></div> */ ?>
    </div>
  </div>
<?php endif; ?>

<!-- Modal: Suche + Filter -->
<div class="modal-suche none">
  <!-- ... (DEIN kompletter Such/Filter-Block unverändert) ... -->
</div>
