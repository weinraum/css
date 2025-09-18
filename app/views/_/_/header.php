<?php header("Content-Type: text/html; charset=utf-8");        
/* IN DER CONFIG.php die session variablen speichern:  den Pfad kontrollieren und darauf achten, dass der schreibrechte hat!
* $header = apache_request_headers(); foreach ($header as $headers => $value) { echo "$headers: $value <br />\n"; }  */
?>
<!DOCTYPE html>
<head >
<meta charset="utf-8">
<meta name="referrer" content="no-referrer">
<meta name="referrer" content="no-referrer-when-downgrade">
<meta name="referrer" content="strict-origin">
<link rel="apple-touch-icon" sizes="57x57" href="/favicon/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/favicon/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/favicon/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/favicon/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/favicon/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/favicon/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/favicon/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/favicon/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/favicon/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/favicon/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/favicon/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
<?php
if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)) {
header('X-UA-Compatible: IE=edge,chrome=1');
}
if (isset($pageTitle) AND $pageTitle != ''): ?>        
<title><?php echo htmlspecialchars($pageTitle) ?></title>
<?= csrf_meta() ?>
<?php endif; 
if (isset($metaDescription) ):    
if ($metaDescription != ''): ?>        
<meta name="description" content="<?php echo htmlspecialchars($metaDescription) ?>">
<?php 
endif; 
endif;

if (isset($_SERVER['HTTP_HOST']) ){       
$chkServer = explode(".", $_SERVER['HTTP_HOST']);
if ( $chkServer[0] == "neu" OR $chkServer[0] == "wupper") { ?>
<meta name="robots" content="none, noarchive, noindex, nofollow"> 
<?php }
if ( $chkServer[0] == "www") {
if ( isset($meta_robots) AND $meta_robots != ''): echo $meta_robots;endif; 
}
}
?>

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="author" content="Thomas Henke, weinraum">

<?php if( isset($class) AND ( $class != 'weinraum' AND $class != "admin")) { ?>
<?php 
if( isset($_SESSION['is_crawler']) AND ( $_SESSION['is_crawler'] != 1)) {}
if( isset($_SESSION['is_crawler']) AND ( $_SESSION['is_crawler'] == 1)) {} ?>

<script  src="/_/js/stuff_7.js" defer></script>

<?php }
if( isset($class) AND ( $class == 'weinraum' OR $class == 'rechnung' OR $class == "admin")) { ?>
<?php if($class == "admin") { ?>
<link rel='stylesheet' href='/_/css/main_admin.css' type='text/css' media='all' />
<link href="/_/libs/select2/dist/css/select2.min.css" rel="stylesheet" />
<link rel='stylesheet' href='/_/css/themify-icons.css'  type='text/css' media='all' >
<?php } ?>

<?php if($class == "admin") { ?>
<script  src="/_/js/stuff_admin.js" defer></script>
<link rel='stylesheet' href='/_/libs/bootstrap-5.3.0/dist/css/bootstrap.min.css' type='text/css' media='all' >

<?php } 
if($class !== "admin") { ?>
<script  src="/_/js/stuff_weinraum.js" defer></script>
<?php } ?>

<script type="text/javascript" src="/_/libs/moment/moment-locales.js"></script>
<link rel="stylesheet" href="/_/libs/DataTables/datatables.min.css" > 

<?php if($class == "weinraum" OR $class == 'rechnung') { ?>
<link rel='stylesheet' href='/_/css/main_weinraum_6.css' type='text/css' media='all' />
<?php } ?>
<?php } 
if (isset($canonicalPage) AND $canonicalPage != '' ) {         ?>      
<link rel="canonical" href=" <?php echo $canonicalPage; ?>">
<?php 
}
?>
<meta name="google-site-verification" content="amjF9V07Je1toRH8JORNRX1BmjPNd9zchXOCXdQK9Ks" >
</head><body >
<?php