<?php $App = new \App\Libraries\App();  ?>



<div class="clearfix"></div>

<div class="content content-iconZeile ">
<p class="line">&nbsp;</p>
<?php // echo("conte $context img $context_img");
/*im image controller werden die original - bilder über den ersten Teil nach dem letzten "/" , den context, gesucht:

if ( $imgUse == "home") {  $file = dirname(__FILE__).'/../../bilder_start/home/'; }
if ( $imgUse == "win") { $file = dirname(__FILE__).'/../../bilder_start/winzer/'; }
if ( $imgUse == "reg") { $file = dirname(__FILE__).'/../../bilder_start/region/'; }
if ( $imgUse == "passend") { $file = dirname(__FILE__).'/../../bilder_start/essen/'; }
  
 der ist hier immer "icons"
 */
//$file = dirname(__FILE__).'/../../bilder_start/icons/';

if ( $context == "terroir") { $file = 'icon-terroir.jpg'; $_alt = "Das Terroir"; }
if ( $context == "winzer") {  $file = 'icon-winzer.jpg';  $_alt = "Die Winzer";}
if ( $context == "weine") { $file = 'icon-weine.jpg';  $_alt = "Die Weine";}

/*
if ( $context == "passend") { $file .= 'icon_passend.jpg';  $_alt = "Das Essen";}
if ( $context == "wei_pass") { $file .= 'icon_pass_weine.jpg';  $_alt = "Passende Weine";}
if ( $context == "zubereitung") { $file .= 'icon_kochen.jpg';  $_alt = "Zubereitung";}
*/  

$_imagemd = PICPATH. "_bilderDetail/icons__sm_".$file;
$_imagexs = PICPATH. "_bilderDetail/icons__xs_".$file;



   
?>


<img class="img-responsive visible-md-block visible-sm-block visible-lg-block" src="<?php echo $_imagemd  ?>" alt="<?php echo $_alt ?>" />
<img class="img-responsive visible-xs-block " src="<?php echo $_imagexs   ?>" alt="<?php echo $_alt ?>" />
    
</div>

<div class="clearfix"></div>
    