<?php 
$App = new \App\Libraries\App();  
$ContentApp = new \App\Libraries\Content(); 
 
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
<div class="container-fluid d-flex flex-wrap ">
<div class="content content-text-image col-12 col-sm-10<?php echo( isset($_noslice) AND $_noslice=='1'?' first':' not-first'); ?> ">
<?php 
if($image != ''): 
$path = FCPATH.'_data/'.$content_id.'/'.$slice_id.'/'.$image_id;
$_path = UEHRIGE.$content_id.'/'.$slice_id.'/'.$image_id.'/output/';
$abfr = $_path."md_".$image;
$abfr_org = $_path."org_".$image;
$caption = isset($caption)?$caption:"";
if (!is_file($abfr)) {}
$_path = '/_data/'.$content_id.'/'.$slice_id.'/'.$image_id.'/output/';
$_pathAbfr = UEHRIGE.$content_id.'/'.$slice_id.'/'.$image_id.'/output/';

?>   
<div class="content-text-image-image-wrapper<?php echo( isset($float)?' content-text-image-image-'.$float:''); ?>"<?php echo  isset($styleImg)?$styleImg:'';?>  >
<?php
// is_file frage NUR nach file file_exists nach file ODER directory
$_agPicture = 0;
$abfr = $_pathAbfr."md_".$image;

$xss_abfrWEBP = $_pathAbfr."xss_".substr($image, 0, -4).'.webp';
$xs_abfrWEBP = $_pathAbfr."xs_".substr($image, 0, -4).'.webp';
$md_abfrWEBP = $_pathAbfr."md_".substr($image, 0, -4).'.webp';
$lg_abfrWEBP = $_pathAbfr."lg_".substr($image, 0, -4).'.webp';
$llg_abfrWEBP = $_pathAbfr."llg_".substr($image, 0, -4).'.webp';
if (is_file($xss_abfrWEBP) AND is_file($xs_abfrWEBP) AND is_file($md_abfrWEBP) AND is_file($lg_abfrWEBP) )  $_abfr_WEBP_formate = 1;
else $_abfr_WEBP_formate = -1;

// Bild im querformat, neue Formate: webp Bilder, Differenzierung für kleine mobiles
if ( $_abfr_WEBP_formate == 1 ) {  $_agPicture = 1; 

if (is_file($xss_abfrWEBP)) { list($xss_width, $xss_height, $xss_type, $xss_attr) = getimagesize($xss_abfrWEBP); }
if (is_file($xs_abfrWEBP)) { list($xs_width, $xs_height, $xs_type, $xs_attr) = getimagesize($xs_abfrWEBP); }
if (is_file($md_abfrWEBP)) { list($md_width, $md_height, $md_type, $md_attr) = getimagesize($md_abfrWEBP); }
if (is_file($lg_abfrWEBP)) { list($lg_width, $lg_height, $lg_type, $lg_attr) = getimagesize($lg_abfrWEBP); }
if (is_file($llg_abfrWEBP)) { list($llg_width, $llg_height, $llg_type, $llg_attr) = getimagesize($llg_abfrWEBP); }
if ( !isset($imageText) ) {  ?>
<picture class="img-responsive">
<source media="(max-width: 380px)" srcset= "<?php echo $_path ?>xss_<?php echo substr($image, 0, -4).'.webp' ?> width="<?php echo isset($xss_width)?$xss_width:''; ?>" height="auto">
<source media="(min-width: 381px) AND (max-width: 500px)" srcset= "<?php echo $_path ?>xs_<?php echo substr($image, 0, -4).'.webp' ?> width="<?php echo isset($xs_width)?$xs_width:''; ?>" height="auto">
<source media="(min-width: 501px) AND (max-width: 700px)" srcset= "<?php echo $_path ?>md_<?php echo substr($image, 0, -4).'.webp' ?>" width="<?php echo isset($md_width)?$md_width:''; ?>" height="auto">
<source media="(min-width: 701px) AND (max-width: 1000px)" srcset= "<?php echo $_path ?>lg_<?php echo substr($image, 0, -4).'.webp' ?>" width="<?php echo isset($lg_width)?$lg_width:''; ?>" height="auto">
<source media="(min-width: 1001px)" srcset= "<?php echo $_path ?>llg_<?php echo substr($image, 0, -4).'.webp' ?>" width="<?php echo isset($llg_width)?$llg_width:''; ?>" height="auto">
<img src="<?php echo $_path ?>md_<?php echo substr($image, 0, -4).'.webp' ?>" alt="<?php echo $caption; ?>" <?php echo ((isset($_lazy) AND $_lazy == 1)?'loading="lazy"':''); ?> width="<?php echo isset($md_width)?$md_width:''; ?>" height="auto">

</picture>    
 <?php 
}
if ( isset($imageText) AND $imageText == 1 ) {  ?>
<picture class="img-responsive">
<source media="(max-width: 380px)" srcset= "<?php echo $_path ?>xs_<?php echo substr($image, 0, -4).'.webp' ?> width="<?php echo isset($xs_width)?$xs_width:''; ?>" height="auto">
<source media="(min-width: 381px) AND (max-width: 500px)" srcset= "<?php echo $_path ?>md_<?php echo substr($image, 0, -4).'.webp' ?> width="<?php echo isset($md_width)?$md_width:''; ?>" height="auto">
<source media="(min-width: 501px) AND (max-width: 700px)" srcset= "<?php echo $_path ?>lg_<?php echo substr($image, 0, -4).'.webp' ?>" width="<?php echo isset($lg_width)?$lg_width:''; ?>" height="auto ">
<source media="(min-width: 701px)" srcset= "<?php echo $_path ?>llg_<?php echo substr($image, 0, -4).'.webp' ?>" width="<?php echo isset($llg_width)?$llg_width:''; ?>" height="auto">
<img src="<?php echo $_path ?>md_<?php echo substr($image, 0, -4).'.webp' ?>" alt="<?php echo $caption; ?>" <?php echo ((isset($_lazy) AND $_lazy == 1)?'loading="lazy"':''); ?> width="<?php echo isset($md_width)?$md_width:''; ?>" height="auto"  >

</picture>    
 <?php 
}
}

// Bild im querformat, alte Formate: jpeg Bilder, keine Differenzierung kleine mobiles
if ( $_abfr_WEBP_formate != 1 AND is_file($abfr)) {  $_agPicture = 1; ?>
<picture class="img-responsive">
<source media="(max-width: 680px)" srcset= "<?php echo $_path ?>xs_<?php echo $image ?> ">
<source media="(min-width: 681px) AND (max-width: 1023px)" srcset= "<?php echo $_path ?>md_<?php echo $image ?>" >
<source media="(min-width: 1024px)" srcset= "<?php echo $_path ?>lg_<?php echo $image ?>" >
<img src="<?php echo $_path?>md_<?php echo $image; ?>" alt="<?php echo $caption; ?>" <?php echo ((isset($_lazy) AND $_lazy == 1)?'loading="lazy"':''); ?> width="<?php echo isset($width)?$width:''; ?>" height="<?php echo isset($height)?$height:''; ?>>

</picture>    
 <?php }
// Bild im hochformat? HISTORISCH, das Format wird nicht mehr gepflegt, es gibt keine webp Formate!
$abfr = $_pathAbfr."hxs_".$image;
$abfr_org = $_pathAbfr."hxOrg_".$image;
if (is_file($abfr)) { $_agPicture = 1; ?>
<picture class="img-responsive">
<source media="(max-width: 680px)" srcset= "<?php echo $_path ?>hxs_<?php echo $image ?>">
<source media="(min-width: 681px)" srcset= "<?php echo $_path ?>hmd_<?php echo $image ?>">
<?php if (is_file($abfr_org)) { ?>
<a href="<?php echo $_path ?>hxOrg_<?php echo $image ?>" data-fancybox="wein-single" data-caption="<?php echo $caption; ?>"><img src="<?php echo $_path?>md_<?php echo $image; ?>" alt="<?php echo $caption; ?>" class="weinBack img-responsive"  loading="lazy"/></a>
<?php
}
if (!is_file($abfr_org)) { ?>
<img src="<?php echo $_path?>hmd_<?php echo $image; ?>"alt="<?php echo $caption; ?> loading="lazy"">
<?php } ?>
</picture>    
 <?php    } 
$abfr = UEHRIGE.$content_id.'/'.$slice_id.'/'.$image_id.'/'.$image;
if (is_file($abfr) AND $_agPicture == 0 ) { ?>
<img class="img-responsive" src="<?php echo PICPATH; ?>_data/<?php echo ($content_id.'/'.$slice_id.'/'.$image_id.'/'.$image); ?>" alt="<?php echo $caption; ?>" loading="lazy"/>
<?php } ?>
<?php if( isset($caption)): $caption = $App::getLinkfromBracket($caption, $linkIdentifers); ?>
<div <?php echo(" $styleTxt");?>><?php echo $caption; ?></div><?php endif; ?>
</div> 
<?php endif; ?>
<?php if( isset($content) ): ?>
</div>
<div class="d-none d-sm-block col-sm-2">&nbsp;</div>
</div>

<div class="container-fluid d-flex flex-wrap">
<div class=" col-12">
<div class="content content-text-image col-xs-12 col-sm-10 col-lg-9 ">
<div class="content-text-image-content "><p>
<?php      
$search    = array('<br></br>');
$replace   = array("</p><p>");
//$txt       = preg_replace("/\n/", "<br></p><p>", $content);    

$txt       = str_replace("<h2>", "</p><h2>", $content);
$txt       = str_replace("</h2>\r\n", "</h2><p>", $txt);
$txt       = str_replace("<h3>", "</p><h3>", $txt);
$txt       = str_replace("</h3>\r\n", "</h3><p>", $txt);
$txt       = str_replace("\n", "<br></p><p>", $txt);

$txt       = str_replace("<bobbs>", "</p><p class='bobb'><span class='bobb'>&#8226;</span><span class='bobb_txt'>", $txt);
$txt       = str_replace("<bobbe>", "</span></p><p class='clear'>", $txt);
$text2 = $App::getLinkfromBracket($txt, $linkIdentifers);
echo $text2 ?>
</p> </div>
<?php endif; ?>
</div>
<div class="d-none d-sm-block col-sm-2 col-lg-3">&nbsp;</div>
</div>
</div>
<div class="clearfix"></div>
<?php } 
else {
echo("kein Inhalt");    
}
