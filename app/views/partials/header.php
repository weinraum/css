<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta name="referrer" content="strict-origin-when-cross-origin">
  <meta name="theme-color" content="#ffffff">
  <meta name="author" content="Thomas Henke, weinraum">

  <?php // ---- CSRF (falls aktiviert) ---- ?>
  <?= function_exists('csrf_meta') ? csrf_meta() : '' ?>

  <?php // ---- Title / Description ---- ?>
  <?php if (!empty($pageTitle)): ?>
    <title><?= esc($pageTitle) ?></title>
  <?php else: ?>
    <title>weinraum</title>
  <?php endif; ?>

  <?php if (isset($metaDescription) && $metaDescription !== ''): ?>
    <meta name="description" content="<?= esc($metaDescription) ?>">
  <?php endif; ?>

  <?php // ---- Canonical ---- ?>
  <?php if (!empty($canonicalPage)): ?>
    <link rel="canonical" href="<?= esc($canonicalPage, 'attr') ?>">
  <?php endif; ?>

  <?php // ---- Robots: env-basiert ----
  // Stage-Hosts blocken (z.B. neu., wupper.)
  $host = $_SERVER['HTTP_HOST'] ?? '';
  $isStageHost = preg_match('~^(neu|wupper)\.~i', $host);
  if ($isStageHost) : ?>
    <meta name="robots" content="noindex, nofollow, noarchive">
  <?php elseif (isset($meta_robots) && $meta_robots !== ''): ?>
    <?= $meta_robots ?>
  <?php endif; ?>

  <?php // ---- App Icons ---- ?>
  <link rel="apple-touch-icon" sizes="57x57" href="/favicon/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="/favicon/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="/favicon/apple-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="/favicon/apple-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="/favicon/apple-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="/favicon/apple-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="/favicon/apple-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="/favicon/apple-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-icon-180x180.png">
  <link rel="icon" type="image/png" sizes="192x192" href="/favicon/android-icon-192x192.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="/favicon/favicon-96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="/favicon/ms-icon-144x144.png">

  <?php // ---- Google Verification (falls nötig) ---- ?>
  <meta name="google-site-verification" content="amjF9V07Je1toRH8JORNRX1BmjPNd9zchXOCXdQK9Ks">

  <?php
  // ---- Asset-Versionierung (Cache-Busting) ----
  // Empfohlen: in .env -> app.assetVersion=20250912 und via config() laden.
  $v = defined('ASSET_VER') ? ASSET_VER : '20250912';
  ?>

  <?php // ---- Global CSS (nur im Layout einbinden!)
  // <link rel="preload" as="style" href="/_/css/app.css?v=<?= $v ?>">
  // <link rel="stylesheet" href="/_/css/app.css?v=<?= $v ?>">
  // Optional:
  // <link rel="stylesheet" href="/_/css/product_teaser.css?v=<?= $v ?>" media="print" onload="this.media='all'">
  // <noscript><link rel="stylesheet" href="/_/css/product_teaser.css?v=<?= $v ?>"></noscript>
  ?>

  <?php // ---- Seite/Context-spezifische Assets ----
  // NICHT in Partials nachladen; hier zentral steuern.
  $cls = $class ?? '';
  if ($cls === 'admin') : ?>
    <link rel="stylesheet" href="/_/css/main_admin.css?v=<?= $v ?>">
    <link rel="stylesheet" href="/_/libs/select2/dist/css/select2.min.css?v=<?= $v ?>">
    <link rel="stylesheet" href="/_/libs/bootstrap-5.3.0/dist/css/bootstrap.min.css?v=<?= $v ?>">
    <link rel="stylesheet" href="/_/libs/bootstrap-wysihtml5/src/bootstrap-wysihtml5.css?v=<?= $v ?>">
    <link rel="stylesheet" href="/_/libs/bootstrap-wysihtml5/font-awesome/css/font-awesome.min.css?v=<?= $v ?>">
    <link rel="stylesheet" href="/_/css/themify-icons.css?v=<?= $v ?>">
    <script src="/_/js/stuff_admin.js?v=<?= $v ?>" defer></script>
  <?php elseif ($cls === 'weinraum' || $cls === 'rechnung') : ?>
    <link rel="stylesheet" href="/_/css/main_weinraum_6.css?v=<?= $v ?>">
  <?php endif; ?>

  <?php // ---- Libs, nur wenn gebraucht ---- ?>
  <?php if (!empty($needsDataTables)): ?>
    <link rel="stylesheet" href="/_/libs/DataTables/datatables.min.css?v=<?= $v ?>">
  <?php endif; ?>

  <?php // ---- JS global (defer), nicht blockierend ---- ?>
  <?php if (!isset($_nohead_spam) || $_nohead_spam === 'no'): ?>
    <?php if ($cls && $cls !== 'admin' && ($host !== 'neu.weinraum.de')): ?>
      <script src="/_/js/stuff_weinraum.js?v=<?= $v ?>" defer></script>
    <?php endif; ?>
    <script src="/_/libs/moment/moment-locales.js?v=<?= $v ?>" defer></script>
    <script src="/_/js/stuff_7.js?v=<?= $v ?>" defer></script>
  <?php endif; ?>
</head>
<body>
