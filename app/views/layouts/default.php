<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="preload" href="/_/fonts/titillium-web-v17-latin-600.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="/_/fonts/titillium-web-v17-latin-700.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="/_/fonts/roboto-v30-latin-regular.woff2" as="font" type="font/woff2" crossorigin>

<link rel="stylesheet" href="/_/css/app.min.css?v=1">

    <?php // Titel & Meta aus $pageTitle / $metaDescription ?>
    <title><?= esc($pageTitle ?? 'weinraum') ?></title>
    <?php if (!empty($metaDescription)): ?>
        <meta name="description" content="<?= esc($metaDescription) ?>">
    <?php endif; ?>

    <?php // Bootstrap 5.3 aus /public/_/libs/bootstrap ... ?>
    <link rel="stylesheet" href="/_/libs/bootstrap/bootstrap.min.css" nonce="{csp-style-nonce}">

    <?php // Deine Haupt-CSS – optional pro Seite via $css_dat ?>
    <?php if (!empty($css_dat)): ?>
        <link rel="stylesheet" href="/_/css/<?= esc($css_dat) ?>.css" nonce="{csp-style-nonce}">
    <?php endif; ?>

    <?php // Favicons (aus header.php übernommen, optional kürzen) ?>
    <link rel="icon" type="image/png" href="/favicon/favicon-32x32.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-icon-180x180.png">
    <?php if (!empty($canonicalPage)): ?>
        <link rel="canonical" href="<?= esc($canonicalPage) ?>">
    <?php endif; ?>
</head>
<body class="<?= esc($class ?? '') ?>">

    <?= $this->renderSection('content') ?>

    <script src="/_/libs/bootstrap/bootstrap.bundle.min.js" nonce="{csp-script-nonce}"></script>

    <?php // Falls du eine schlanke main_require.js brauchst (ohne jQuery): ?>
    <?php if (!empty($inlineJs)) : ?>
        <script nonce="{csp-script-nonce}"><?= $inlineJs ?></script>
    <?php endif; ?>
<?= view('partials/footer') ?>
<script defer src="<?= base_url('assets/vendor.min.js') ?>"></script>
<script defer src="<?= base_url('assets/app.min.js') ?>"></script>
</body>
</html>
