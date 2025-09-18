<?php 
use App\Models\Content2identifer_model;

$App = new \App\Libraries\App();  
 

/* Das ist für die cron functions, die rufen diese identifers im controller auf, die werden aber nicht wie in den views mit $this->data übergeben und müssen in den views
 * gesondert abgerufen werden.
 */

if ( !isset($linkIdentifers) ) {
$Content2identifer_model = new Content2identifer_model();
$linkIdentifers = $Content2identifer_model->getIdentifers();
}

$_sonder = -1;
$style = "";
$styleTxt =  "";

/*VORSICHT MIT DATEN!!! in der config view kann man einstellen, dass Daten auch ohne sie zu übergeben für die view weiter leben.
 * 
 * Das kann tricks sein!
 * 
 *     /**
     * When false, the view method will clear the data between each
     * call. This keeps your data safe and ensures there is no accidental
     * leaking between calls, so you would need to explicitly pass the data
     * to each view. You might prefer to have the data stick around between
     * calls so that it is available to all views. If that is the case,
     * set $saveData to true.
     *
     * @var bool
    
    public $saveData = true;
  */

$float = isset($float)?$float:"";
$img_xs_magn = isset($img_xs_magn)?$img_xs_magn:1;

if ( isset($imageText) AND $imageText == 1 ) { $float = ""; }
if ( isset($content_id) AND isset($slice_id) AND isset($image_id) AND isset($image)) { 
$abfr = UEHRIGE."/".$content_id."/".$slice_id."/".$image_id."/".$image;
if (is_file($abfr)) { list($width, $height, $type, $attr) = getimagesize($abfr);
$widthTxt = $width -15;
$style = "";
$styleTxt =  "style=\"max-width: 85%;\"";
}
?>
<div class="clearfix"></div>
<div class="container-fluid d-flex flex-wrap ">
<?php 
/* Hier wirds kompliziert, um nicht noch eine neue view Datei zu erstellen. Die provence Seiten werden ohne Abstände erzeugt */
if ( isset( $is_provence_left) AND $is_provence_left == 1 )   { $_sonder = 1; ?>
<div class="content content-text-image col-12 ">
<?php
} 
if ( isset( $is_provence_right) AND $is_provence_right == 1 )   { $_sonder = 1; ?>
<div class="content content-text-image col-12 ">
<?php
} 
if ( isset($format_slice) AND $format_slice == "Zeitung" AND $_sonder == -1 )   { $_sonder = 1; ?>
<div class="content content-text-image col-11 ">
<?php
}
if ( $_sonder == -1 ) { ?>
<div class="content content-text-image col-12 col-sm-11 col-lg-8<?php echo( isset($_noslice) AND $_noslice=='1'?' first':' not-first'); ?> ">
<?php }
if($image != ''): 
$path = UEHRIGE.$content_id.'/'.$slice_id.'/'.$image_id;
$_path = '/_data/'.$content_id.'/'.$slice_id.'/'.$image_id.'/output/';
$abfr = $_path."md_".$image;
$abfr_org = $_path."org_".$image;
$caption = isset($caption)?$caption:"";
$alt_txt = "";
if( $caption == "" AND isset($content) ) { 
$chk_alt_txt = explode(' ', $content, 10); 
if (is_array($chk_alt_txt ) ) {
$_i = 0;
foreach ( $chk_alt_txt as $kAT => $vAT ) {
$_i ++;
if ( $_i < 10 ) { $alt_txt .= " ".$vAT; }
}
}
$search = array("<b>", "</b>", "<h2>", "</h2>");
$replace = array("", "", "", "");
$alt_txt=  str_replace($search, $replace, $alt_txt);

}
if( $caption != "" ) { $alt_txt = $caption; }

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
if ( !is_file($llg_abfrWEBP) AND is_dir($_pathAbfr)) { // echo("$path  $image");exit();
$image = \Config\Services::image()->withFile($path."/".$image)->convert(IMAGETYPE_WEBP)->save($llg_abfrWEBP);
chmod($llg_abfrWEBP, 0770);
}
if (is_file($xss_abfrWEBP) AND is_file($xs_abfrWEBP) AND is_file($md_abfrWEBP) AND is_file($lg_abfrWEBP) ) { $_abfr_WEBP_formate = 1; }
else { $_abfr_WEBP_formate = -1; }

// Bild im querformat, neue Formate: webp Bilder, Differenzierung für kleine mobiles
if ( $_abfr_WEBP_formate == 1 ) {  $_agPicture = 1; 

if (is_file($xss_abfrWEBP)) { list($xss_width, $xss_height, $xss_type, $xss_attr) = getimagesize($xss_abfrWEBP); }
if (is_file($xs_abfrWEBP)) { list($xs_width, $xs_height, $xs_type, $xs_attr) = getimagesize($xs_abfrWEBP); }
if (is_file($md_abfrWEBP)) { list($md_width, $md_height, $md_type, $md_attr) = getimagesize($md_abfrWEBP); }
if (is_file($lg_abfrWEBP)) { list($lg_width, $lg_height, $lg_type, $lg_attr) = getimagesize($lg_abfrWEBP); }
if (is_file($llg_abfrWEBP)) { list($llg_width, $llg_height, $llg_type, $llg_attr) = getimagesize($llg_abfrWEBP); }
if ( !isset($imageText) OR $img_xs_magn == -1) {  ?>
<picture class="img-responsive">
<source media="(max-width: 380px)" srcset= "<?php echo $_path ?>xss_<?php echo substr($image, 0, -4).'.webp' ?>">
<source media="(min-width: 381px) AND (max-width: 500px)" srcset= "<?php echo $_path ?>xs_<?php echo substr($image, 0, -4).'.webp' ?>">
<source media="(min-width: 501px) AND (max-width: 700px)" srcset= "<?php echo $_path ?>md_<?php echo substr($image, 0, -4).'.webp' ?>">
<source media="(min-width: 701px) AND (max-width: 1000px)" srcset= "<?php echo $_path ?>lg_<?php echo substr($image, 0, -4).'.webp' ?>">
<source media="(min-width: 1001px)" srcset= "<?php echo $_path ?>llg_<?php echo substr($image, 0, -4).'.webp' ?>">
<img src="<?php echo $_path ?>md_<?php echo substr($image, 0, -4).'.webp' ?>" alt="<?php echo $alt_txt; ?>" <?php echo ((isset($_lazy) AND $_lazy == 1)?'loading="lazy"':''); ?> width="<?php echo isset($md_width)?$md_width:''; ?>" height="<?php echo isset($md_height)?$md_height:''; ?>">

</picture>    
 <?php 
}
if ( isset($imageText) AND $imageText == 1 AND $img_xs_magn == 1) {  ?>

<div class="magnifying-glass">
<img class="xs magnifying-glass__img" src="<?php echo $_path ?>xs_<?php echo substr($image, 0, -4).'.webp' ?>" draggable="false" alt="<?php echo $alt_txt; ?>" <?php echo ((isset($_lazy) AND $_lazy == 1)?'loading="lazy"':''); ?>>
<img class="sd magnifying-glass__img" src="<?php echo $_path ?>md_<?php echo substr($image, 0, -4).'.webp' ?>" draggable="false" alt="<?php echo $alt_txt; ?>" <?php echo ((isset($_lazy) AND $_lazy == 1)?'loading="lazy"':''); ?>>
<img class="lg magnifying-glass__img" src="<?php echo $_path ?>lg_<?php echo substr($image, 0, -4).'.webp' ?>" draggable="false" alt="<?php echo $alt_txt; ?>" <?php echo ((isset($_lazy) AND $_lazy == 1)?'loading="lazy"':''); ?>>
<img class="llg magnifying-glass__img" src="<?php echo $_path ?>llg_<?php echo substr($image, 0, -4).'.webp' ?>" draggable="false" alt="<?php echo $alt_txt; ?>" <?php echo ((isset($_lazy) AND $_lazy == 1)?'loading="lazy"':''); ?>>
   
</div>

<div class="magnifying-glass__magnifier">
<div class="magnifying-glass__enlarged-image">
<img class="xs magnifying-glass__img lupe" src="<?php echo $_path ?>llg_<?php echo substr($image, 0, -4).'.webp' ?>" alt="<?php echo $alt_txt; ?>" draggable="false" >
<img class="sd magnifying-glass__img lupe" src="<?php echo $_path ?>textlg_<?php echo substr($image, 0, -4).'.webp' ?>" alt="<?php echo $alt_txt; ?>" draggable="false" >
<img class="lg magnifying-glass__img lupe" src="<?php echo $_path ?>textllg_<?php echo substr($image, 0, -4).'.webp' ?>" alt="<?php echo $alt_txt; ?>" draggable="false" >
</div>
</div>



 <?php 
}
}

// Bild im querformat, alte Formate: jpeg Bilder, keine Differenzierung kleine mobiles
if ( $_abfr_WEBP_formate != 1 AND is_file($abfr)) {  $_agPicture = 1; ?>
<picture class="img-responsive">
<source media="(max-width: 680px)" srcset= "<?php echo $_path ?>xs_<?php echo $image ?> ">
<source media="(min-width: 681px) AND (max-width: 1023px)" srcset= "<?php echo $_path ?>md_<?php echo $image ?>" >
<source media="(min-width: 1024px)" srcset= "<?php echo $_path ?>lg_<?php echo $image ?>" >
<img src="<?php echo $_path?>md_<?php echo $image; ?>" alt="<?php echo $alt_txt; ?>" <?php echo ((isset($_lazy) AND $_lazy == 1)?'loading="lazy"':''); ?> width="<?php echo isset($width)?$width:''; ?>" height="<?php echo isset($height)?$height:''; ?>">

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
<a href="<?php echo $_path ?>hxOrg_<?php echo $image ?>" data-fancybox="wein-single" data-caption="<?php echo $caption; ?>"><img src="<?php echo $_path?>md_<?php echo $image; ?>" alt="<?php echo $alt_txt; ?>" class="weinBack img-responsive"  loading="lazy"/></a>
<?php
}
if (!is_file($abfr_org)) { ?>
<img src="<?php echo $_path?>hmd_<?php echo $image; ?>"alt="<?php echo $alt_txt; ?>" loading="lazy"">
<?php } ?>
</picture>    
 <?php    } 
$abfr = UEHRIGE.$content_id.'/'.$slice_id.'/'.$image_id.'/'.$image;
if (is_file($abfr) AND $_agPicture == 0 ) { ?>
<img class="img-responsive" src="<?php echo PICPATH; ?>_data/<?php echo ($content_id.'/'.$slice_id.'/'.$image_id.'/'.$image); ?>" alt="<?php echo $alt_txt; ?>" loading="lazy"/>
<?php } ?>
<?php if( isset($caption)): $caption = $App::getLinkfromBracket($caption, $linkIdentifers); ?>
<div ><?php echo $caption; ?></div><?php endif; ?>
</div> 
<?php endif; ?>
<?php if( isset($content) ) { ?>
</div>
<?php if ( !isset($is_provence_img) OR isset($is_provence_img) AND $is_provence_img != 1) { ?>
<div class="d-none d-sm-block col-sm-1">&nbsp;</div>
<?php }?>
</div>

<div class="container-fluid d-flex flex-wrap">
<div class=" col-12">
<?php
$_sonder = -1;

if ( isset( $is_provence_left) AND $is_provence_left == 1 )   { $_sonder = 1; ?>
<div class="content content-text-image zeitung col-xs-12  ">
<?php
} 
if ( isset( $is_provence_right) AND $is_provence_right == 1 )   { $_sonder = 1; ?>
<div class="content content-text-image zeitung col-xs-12 ">
<?php
} 
if ( isset($format_slice) AND $format_slice == "Zeitung" AND $_sonder == -1 )   { $_sonder = 1; ?>
<div class="content content-text-image zeitung col-xs-12  "><?php
}
if ( $_sonder == -1 ) { ?>
<div class="content content-text-image col-xs-12 col-sm-10 col-lg-8 ">
<?php } ?>


<div class="content-text-image-content "><p>
<?php      
$search  = array('<br></br>');
$replace = array("</p><p>");
//$txt       = preg_replace("/\n/", "<br></p><p>", $content);    

$txt = str_replace("<h2>", "</p><h2>", $content);
$txt = str_replace("</h2>\r\n", "</h2><p>", $txt);
$txt = str_replace("<h3>", "</p><h3>", $txt);
$txt = str_replace("</h3>\r\n", "</h3><p>", $txt);
$txt = str_replace("\n", "<br></p><p>", $txt);

$txt = str_replace("<bobbs>", "</p><p class='bobb'><span class='bobb'>&#8226;</span><span class='bobb_txt'>", $txt);
$txt = str_replace("<bobbe>", "</span></p><p class='clear'>", $txt);

$text2 = $App::getLinkfromBracket($txt, $linkIdentifers);
echo $text2 ?>
</p> </div>
<?php } ?>
</div>
<?php
if ( isset( $is_provence_left) AND $is_provence_left == 1 )   { $_sonder = 1; ?>
<div class="d-none d-sm-block col-sm-1">&nbsp;</div>
<?php
} 
if ( isset($format_slice) AND $format_slice == "Zeitung" AND $_sonder == -1 )   { $_sonder = 1; ?>
<div class="d-none d-sm-block col-sm-1">&nbsp;</div><?php
}
if ( $_sonder == -1 ) { ?>
<div class="d-none d-sm-block col-sm-1 col-lg-3">&nbsp;</div>
<?php } ?>

</div>
</div>
<div class="clearfix"></div>
<?php } 
else {
echo("kein Inhalt");    
}

?>



<style {csp-style-nonce} >

.magnifying-glass {display: flex;background: black;}
.magnifying-glass__img { width: 100%;}
.magnifying-glass__magnifier { position:fixed;top:0;left:0;z-index:1;overflow: hidden;width: 15vw;max-width: 10rem;height: 15vw;max-height: 10rem;border: 5px solid rgba(white, 0.25);border-radius: 50%;background-color: transparent;pointer-events: none;opacity: 0;transition: opacity 0.25s ease;}
.magnifying-glass__enlarged-image {position:absolute;top:50%;left:-10%;width:100vw;}
.magnifying-glass__img.lupe { width: 120%;}

@media(max-width: 580px) {
.magnifying-glass img.xs.magnifying-glass__img {display:block;}
.magnifying-glass img.sd.magnifying-glass__img {display:none;}
.magnifying-glass img.lg.magnifying-glass__img {display:none;}
.magnifying-glass img.llg.magnifying-glass__img {display:none;}

.magnifying-glass__magnifier img.xs.magnifying-glass__img {display:block;}
.magnifying-glass__magnifier img.sd.magnifying-glass__img {display:none;}
.magnifying-glass__magnifier img.lg.magnifying-glass__img {display:none;}
}

@media(min-width: 581px) {
.magnifying-glass img.xs.magnifying-glass__img {display:none;}
.magnifying-glass img.sd.magnifying-glass__img {display:block;}
.magnifying-glass img.lg.magnifying-glass__img {display:none;}
.magnifying-glass img.llg.magnifying-glass__img {display:none;}

.magnifying-glass__magnifier img.xs.magnifying-glass__img {display:none;}
.magnifying-glass__magnifier img.sd.magnifying-glass__img {display:block;}
.magnifying-glass__magnifier img.lg.magnifying-glass__img {display:none;}
}

@media(min-width: 701px) {
.magnifying-glass img.xs.magnifying-glass__img {display:none;}
.magnifying-glass img.sd.magnifying-glass__img {display:none;}
.magnifying-glass img.lg.magnifying-glass__img {display:block;}
.magnifying-glass img.llg.magnifying-glass__img {display:none;}

.magnifying-glass__magnifier img.xs.magnifying-glass__img {display:none;}
.magnifying-glass__magnifier img.sd.magnifying-glass__img {display:none;}
.magnifying-glass__magnifier img.lg.magnifying-glass__img {display:block;}
}

@media(min-width: 901px) {
.magnifying-glass img.xs.magnifying-glass__img {display:none;}
.magnifying-glass img.sd.magnifying-glass__img {display:none;}
.magnifying-glass img.lg.magnifying-glass__img {display:none;}
.magnifying-glass img.llg.magnifying-glass__img {display:block;}


}

@media(min-width: 1201px) {
.magnifying-glass img.xss.magnifying-glass__img {display:none;}
.magnifying-glass img.xs.magnifying-glass__img {display:none;}
.magnifying-glass img.md.magnifying-glass__img {display:none;}
.magnifying-glass img.lg.magnifying-glass__img {display:none;}
.magnifying-glass img.llg.magnifying-glass__img {display:block;}
.magnifying-glass__img.lupe { width: 110%;}

}

</style>