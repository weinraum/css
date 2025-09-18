<?php 
$_pathZU = '/_/img/';
$_pathAbfr = UEHRIGE.$content_id.'/'.$slice_id.'/'.$image_id.'/output/';
$_agPicture = 0;
$_path = '/_data/'.$content_id.'/'.$slice_id.'/'.$image_id.'/output/';
$abfr = $_pathAbfr."md_".$image;

if ( isset($class) AND $class == "admin" ) {?>

<p > Video ID:<br><?php echo isset($content)?$content:"keine video - id" ?></p>
<p > Titel des Videos:<br><?php echo isset($caption)?$caption:"kein Titel für das Video" ?></p>
<?php
// is_file frage NUR nach file file_exists nach file ODER directory
if ( isset($image) AND $image != '') {


/*
if ( !is_file($llg_abfrWEBP) AND is_dir($_pathAbfr)) { // echo("$path  $image");exit();
$image = \Config\Services::image()->withFile($path."/".$image)->convert(IMAGETYPE_WEBP)->save($llg_abfrWEBP);
chmod($llg_abfrWEBP, 0770);
}
if (is_file($xss_abfrWEBP) AND is_file($xs_abfrWEBP) AND is_file($md_abfrWEBP) AND is_file($lg_abfrWEBP) )  $_abfr_WEBP_formate = 1;
else $_abfr_WEBP_formate = -1;

// Bild im querformat, neue Formate: webp Bilder, Differenzierung für kleine mobiles
if ( $_abfr_WEBP_formate == 1 ) {  $_agPicture = 1; }
*/

if ( !isset($imageText) ) {  ?>
<picture class="img-responsive">
<source media="(max-width: 380px)" srcset= "<?php echo $_path ?>xss_<?php echo substr($image, 0, -4).'.webp' ?>">
<source media="(min-width: 381px) AND (max-width: 500px)" srcset= "<?php echo $_path ?>xs_<?php echo substr($image, 0, -4).'.webp' ?>">
<source media="(min-width: 501px) AND (max-width: 700px)" srcset= "<?php echo $_path ?>md_<?php echo substr($image, 0, -4).'.webp' ?>">
<source media="(min-width: 701px) AND (max-width: 1000px)" srcset= "<?php echo $_path ?>lg_<?php echo substr($image, 0, -4).'.webp' ?>">
<source media="(min-width: 1001px)" srcset= "<?php echo $_path ?>llg_<?php echo substr($image, 0, -4).'.webp' ?>">
<img src="<?php echo $_path ?>md_<?php echo substr($image, 0, -4).'.webp' ?>" alt="<?php echo $caption; ?>" <?php echo ((isset($_lazy) AND $_lazy == 1)?'loading="lazy"':''); ?> width="<?php echo isset($md_width)?$md_width:''; ?>" height="<?php echo isset($md_height)?$md_height:''; ?>">

</picture> 
 <?php 
}
}
?>

<?php
}
else {
if ( !isset($_SESSION['_jutjupp']) OR (isset($_SESSION['_jutjupp']) AND $_SESSION['_jutjupp'] == "nein") ) {
?>

<div class="col-xs-12 ">

<div class='chk_video'>
<div class='genehmigung'>
<div class="bild">
<a href ='#' class='jutjupp button ___<?php echo isset($caption)?$caption:"" ?>___<?php echo isset($content)?$content:"" ?>___eins___<?php echo isset($_SESSION['_cookie_viewid'])?$_SESSION['_cookie_viewid']:"" ?>___<?php echo isset($_SESSION['_cuid'])?$_SESSION['_cuid']:"" ?>'>
<picture class="img-responsive">
<source media="(max-width: 380px)" srcset= "<?php echo $_pathZU ?>zustimmung-video-350.webp">
<source media="(min-width: 381px)" srcset= "<?php echo $_pathZU ?>zustimmung-video-480.webp">
<img src="<?php echo $_pathZU ?>zustimmung-video-480.webp" alt="<?php echo $caption; ?>" <?php echo ((isset($_lazy) AND $_lazy == 1)?'loading="lazy"':''); ?> width="<?php echo isset($md_width)?$md_width:''; ?>" height="<?php echo isset($md_height)?$md_height:''; ?>">
</picture>
</a>
</div>
<div class="text">
<p class="text head">Videos werden von <span class="jutjupp">YouTube</span> geladen.</p>
<p class="text dat">Sie akzeptieren die youtube 
<a href="https://policies.google.com/privacy" target="_blank" rel="nofollow noopener noreferrer" class="daschu">Datenschutzerklärung</a>
wenn Sie die Videos anschauen.<br>Ihre Zustimmung können Sie jederzeit widerrufen.</p>
<a href ='#' class='jutjupp button ___<?php echo isset($caption)?$caption:"" ?>___<?php echo isset($content)?$content:"" ?>___eins___<?php echo isset($_SESSION['_cookie_viewid'])?$_SESSION['_cookie_viewid']:"" ?>___<?php echo isset($_SESSION['_cuid'])?$_SESSION['_cuid']:"" ?>'>Videos ansehen</a>
</div>
</div>

<div class='video-thumb eins'>
<?php
// is_file frage NUR nach file file_exists nach file ODER directory
if ( isset($image) AND $image != '') {
$_agPicture = 0;
$abfr = $_pathAbfr."md_".$image;

$xss_abfrWEBP = $_pathAbfr."xss_".substr($image, 0, -4).'.webp';
$xs_abfrWEBP = $_pathAbfr."xs_".substr($image, 0, -4).'.webp';
$md_abfrWEBP = $_pathAbfr."md_".substr($image, 0, -4).'.webp';
$lg_abfrWEBP = $_pathAbfr."lg_".substr($image, 0, -4).'.webp';
$llg_abfrWEBP = $_pathAbfr."llg_".substr($image, 0, -4).'.webp';
if ( !is_file($llg_abfrWEBP) AND is_dir($_pathAbfr)) { // echo("$path  $image");exit();
$image = \Config\Services::image()->withFile($path."/".$image)->convert(IMAGETYPE_WEBP)->save($llg_abfrWEBP);
chmod($llg_abfrWEBP, 0770);
}
if (is_file($xss_abfrWEBP) AND is_file($xs_abfrWEBP) AND is_file($md_abfrWEBP) AND is_file($lg_abfrWEBP) )  $_abfr_WEBP_formate = 1;
else $_abfr_WEBP_formate = -1;

// Bild im querformat, neue Formate: webp Bilder, Differenzierung für kleine mobiles
if ( $_abfr_WEBP_formate == 1 ) {  $_agPicture = 1; 

if (is_file($xss_abfrWEBP)) { list($xss_width, $xss_height, $xss_type, $xss_attr) = getimagesize($xss_abfrWEBP); }
if (is_file($xs_abfrWEBP)) { list($xs_width, $xs_height, $xs_type, $xs_attr) = getimagesize($xs_abfrWEBP); }
if (is_file($md_abfrWEBP)) { list($md_width, $md_height, $md_type, $md_attr) = getimagesize($md_abfrWEBP); }
if (is_file($lg_abfrWEBP)) { list($lg_width, $lg_height, $lg_type, $lg_attr) = getimagesize($lg_abfrWEBP); }
if (is_file($llg_abfrWEBP)) { list($llg_width, $llg_height, $llg_type, $llg_attr) = getimagesize($llg_abfrWEBP); }
?>
<a href ='#' class='jutjupp button ___<?php echo isset($caption)?$caption:"" ?>___<?php echo isset($content)?$content:"" ?>___eins___<?php echo isset($_SESSION['_cookie_viewid'])?$_SESSION['_cookie_viewid']:"" ?>___<?php echo isset($_SESSION['_cuid'])?$_SESSION['_cuid']:"" ?>'>
<picture class="img-responsive">
<source media="(max-width: 380px)" srcset= "<?php echo $_path ?>xss_<?php echo substr($image, 0, -4).'.webp' ?>">
<source media="(min-width: 381px) AND (max-width: 500px)" srcset= "<?php echo $_path ?>xs_<?php echo substr($image, 0, -4).'.webp' ?>">
<source media="(min-width: 501px) AND (max-width: 700px)" srcset= "<?php echo $_path ?>md_<?php echo substr($image, 0, -4).'.webp' ?>">
<source media="(min-width: 701px) AND (max-width: 1000px)" srcset= "<?php echo $_path ?>lg_<?php echo substr($image, 0, -4).'.webp' ?>">
<source media="(min-width: 1001px)" srcset= "<?php echo $_path ?>llg_<?php echo substr($image, 0, -4).'.webp' ?>">
<img src="<?php echo $_path ?>md_<?php echo substr($image, 0, -4).'.webp' ?>" alt="<?php echo $caption; ?>" <?php echo ((isset($_lazy) AND $_lazy == 1)?'loading="lazy"':''); ?> width="<?php echo isset($md_width)?$md_width:''; ?>" height="<?php echo isset($md_height)?$md_height:''; ?>">
</picture>    
</a>
<?php 
}
}
?>

</div>
</div>
<div class='video eins'></div>

</div>

<?php 
}
if ( isset($_SESSION['_jutjupp']) AND $_SESSION['_jutjupp'] == "alles")  { ?>
<div class="video eins">
<div class='yt-frame'>
<iframe class='yt-video' src='https://www.youtube-nocookie.com/embed/<?php echo isset($content)?$content:"" ?>' title='<?php echo isset($caption)?$caption:"" ?>' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share' allowfullscreen></iframe>
</div>

</div>

<?php
}
} ?>

<style {csp-style-nonce} >
@media only screen and (max-width : 680px) {
div.chk_video {width: 100%;}
div.chk_video div.genehmigung {width: 100%;}
div.chk_video div.genehmigung div.bild {width: 65%;margin: 0 auto 0 auto;}
div.chk_video div.genehmigung div.text {width: 100%; }
div.chk_video div.genehmigung div.text p.text.head {font: normal normal 500 16px/18px 'roboto', sans-serif; letter-spacing: 0.04em;width: 100%; text-align: center; color: #000;padding: 0 0 15px 0;}
div.chk_video div.genehmigung div.text p.text.head span.jutjupp {font: normal normal 500 18px/18px 'roboto', sans-serif; letter-spacing: 0.04em;width: 100%; text-align: center; color: #d40d00;padding: 0 0 15px 0;}
div.chk_video div.genehmigung div.text p.text.dat {font: normal normal 300 8px/16px 'roboto', sans-serif; letter-spacing: 0.0em;width: 100%; text-align: center; color: #000;padding: 0 0 15px 0;}
div.chk_video div.genehmigung div.text p.text.dat a.daschu { text-decoration:underline;color: #d40d00 !important;}
div.chk_video div.genehmigung div.text a.jutjupp {display:block; width: 185px;height: 35px;margin: 0 auto 0 auto;background-color:#d40d00;border-radius:9px;color:#FFF!important;font:normal small-caps 600 19px/33px 'roboto',sans-serif;text-align: center;letter-spacing: 0.08em;}

div.video {width: 90%; }
div.video .yt-video {width: 100%; aspect-ratio: 16 / 9;}
}
@media only screen and (min-width : 681px) {
div.chk_video {width: 90%;}
div.chk_video div.genehmigung {width: 100%;}
div.chk_video div.genehmigung div.bild {width: 35%;margin: 0 5% 0 0;float: left;}
div.chk_video div.genehmigung div.text {width: 60%; float: left;}
div.chk_video div.genehmigung div.text p.text.head {font: normal normal 500 18px/18px 'roboto', sans-serif; letter-spacing: 0.04em;width: 100%; text-align: center; color: #000;padding: 0 0 15px 0;}
div.chk_video div.genehmigung div.text p.text.head span.jutjupp {font: normal normal 500 24px/18px 'roboto', sans-serif; letter-spacing: 0.04em;width: 100%; text-align: center; color: #d40d00;padding: 0 0 15px 0;}
div.chk_video div.genehmigung div.text p.text.dat {font: normal normal 300 10px/16px 'roboto', sans-serif; letter-spacing: 0.04em;width: 100%; text-align: center; color: #000;padding: 0 0 15px 0;}
div.chk_video div.genehmigung div.text p.text.dat a.daschu { text-decoration:underline;color: #d40d00 !important;}
div.chk_video div.genehmigung div.text a.jutjupp {display:block; width: 185px;height: 35px;margin: 0 auto 0 auto;background-color:#d40d00;border-radius:9px;color:#FFF!important;font:normal small-caps 600 19px/33px 'roboto',sans-serif;text-align: center;letter-spacing: 0.08em;}


div.video {width: 90%; }
div.video .yt-video {width: 100%; aspect-ratio: 16 / 9;}

}

</style>
