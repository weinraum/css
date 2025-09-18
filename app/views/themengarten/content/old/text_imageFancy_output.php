<?php 
$App = new \App\Libraries\App();  
$ContentApp = new \App\Libraries\Content(); 

?>


<?php 


$style = "";
$styleTxt =  "";

if ( isset($content_id) AND isset($slice_id) AND isset($image_id) AND isset($image)) {
$abfr = FCPATH."/".$content_id."/".$slice_id."/".$image_id."/".$image;
if (is_file($abfr)) { list($width, $height, $type, $attr) = getimagesize($abfr);

$widthTxt = $width -15;
if ( $float != "center") {

if ($width < 385) 	{
$styleImg = "style=\"width: {$width}px;\"";
if ($float == "left") 	$styleTxt = "style=\"width: {$widthTxt}px; margin: 5px 9px 20px 0;\"";
if ($float == "right") 	$styleTxt = "style=\"width: {$widthTxt}px; margin: 5px 0px 20px 25px;\"";
}
if ($width >= 385) 	{
$styleImg = "style=\"max-width: 385px;\"";
$styleTxt =  "style=\"max-width: 85%;\"";
}

}

if ( $float == "center") {
$style = "";
$styleTxt =  "style=\"max-width: 85%;\"";

}
}



?>
<div class="clearfix"></div>

<div class="content content-text-image col-12 col-sm-10 <?php echo( isset($_noslice) AND $_noslice=='1'?' first':' not-first'); ?> ">

<?php 
if($image != ''): 
    
/*  Pfad zum original Bild  OHNE den Bildnamen, der wird in der funktion speichern_file angehängt */
    
$path = FCPATH.'_data/'.$content_id.'/'.$slice_id.'/'.$image_id;

/* Pfad zum eventuellen output - Bild. Es wird nur eins abgefragt, die anderen werden dann schon da sein ...
 * die output Bilder werden ab CI4 in photoshop bereit gestellt. Die alte Version, "kleine" Bilder durch php in dem image folder ablegen zu lassen,
 * hat zu riesigen Dateien mit sehr schlechter Darstellung geführt.
 * Liegen die kleinen output - Versionen nicht vor (bei neuem content nicht angegeben und bei altem nicht nachgepflegt), werden die original - files
 * angezeigt. Die sind kleiner und viel besser als die pho Versionen.
 */
$_path = '/is/htdocs/wp1112013_RXRAWWY6TZ/www/public/_data/'.$content_id.'/'.$slice_id.'/'.$image_id.'/output/';
$abfr = $_path."md_".$image;

if (!is_file($abfr)) { //echo("son mist $abfr - path $path - _path $_path");
}

$_path = '/_data/'.$content_id.'/'.$slice_id.'/'.$image_id.'/output/';
$_pathAbfr = '/is/htdocs/wp1112013_RXRAWWY6TZ/www/public/_data/'.$content_id.'/'.$slice_id.'/'.$image_id.'/output/';

?>
     
<div class="content-text-image-image-wrapper<?php echo( isset($float)?' content-text-image-image-'.$float:''); ?>"<?php echo  isset($styleImg)?$styleImg:'';?>  >

 <?php
$_agPicture = 0;

$abfr = $_pathAbfr."md_".$image;

// Bild im querformat
if (is_file($abfr)) {  $_agPicture = 1; ?>
    

<picture class="img-responsive">
<source media="(max-width: 680px)" srcset= "<?php echo $_path ?>xs_<?php echo $image ?>">
<source media="(min-width: 681px) AND (max-width: 1023px)" srcset= "<?php echo $_path ?>md_<?php echo $image ?>">
<source media="(min-width: 1024px)" srcset= "<?php echo $_path ?>lg_<?php echo $image ?>">
<a href="<?php echo $_path ?>languedoc-220407-28.jpg" data-fancybox="wein-single" data-caption="kleines Winzer Bild"><img src="<?php echo $_path?>md_<?php echo $image; ?>" alt="weinchen"  class="weinBack img-responsive" /></a>

</picture>  
    
    
 <?php }

// Bild im hochformat?
$abfr = $_pathAbfr."hxs_".$image;
if (is_file($abfr)) { $_agPicture = 1; ?>

<picture class="img-responsive">
<source media="(max-width: 680px)" srcset= "<?php echo $_path ?>hxs_<?php echo $image ?>">
<source media="(min-width: 681px)" srcset= "<?php echo $_path ?>hmd_<?php echo $image ?>">
<img src="<?php echo $_path?>hmd_<?php echo $image; ?>" alt="kleines Winzer Bild">
</picture> 
    
    
 <?php    } 
$abfr = '/is/htdocs/wp1112013_RXRAWWY6TZ/www/public/_data/'.$content_id.'/'.$slice_id.'/'.$image_id.'/'.$image;
if (is_file($abfr) AND $_agPicture == 0 ) { ?>

<img class="img-responsive" src="<?php echo PICPATH; ?>_data/<?php echo ($content_id.'/'.$slice_id.'/'.$image_id.'/'.$image); ?>" alt="<?php echo $caption; ?>" />
<?php } ?>

<?php if( isset($caption)): $caption = $App::getLinkfromBracket($caption, $linkIdentifers); ?>
<div <?php echo(" $styleTxt");?>><?php echo $caption; ?></div><?php endif; ?>
</div> 
    
<?php endif; ?>
<?php if( isset($content) ): ?>
</div>

<div class="content content-text-image col-xs-12 col-sm-10 ">

<div class="content-text-image-content "><p>
<?php      
$search    = array('<br></br>');
$replace   = array("</p><p>");


$txt            = preg_replace("/\n/", "</p><p>", $content);    

$text2 = $App::getLinkfromBracket($txt, $linkIdentifers);
//echo $linkIdentifers;
echo $text2 ?>

</p> </div>
<?php endif; ?>

</div>
<div class="visible-sm-block visible-lg-block  col-sm-1">&nbsp;</div>
<div class="clearfix"></div>
 
<?php } 
else {
echo("kein Inhalt");    
}