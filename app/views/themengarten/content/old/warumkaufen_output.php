<?php $App = new \App\Libraries\App();  ?>


<div class="clearfix"></div>

<div class="content content-warumkaufen ">
<div class="pos-img ">

<?php // echo("conte $context img $context_img");
if (  $context == "win" ) { 
$_imagelg = PICPATH. "_bilderDetail/".$context."__lg_winzer-warum-kaufen.jpg"; 
$_imagemd = PICPATH. "_bilderDetail/".$context."__sm_winzer-warum-kaufen.jpg"; 
$_imagexs = PICPATH. "_bilderDetail/".$context."__xs_winzer-warum-kaufen-xs.jpg"; 
$_alt = "warum diesen winzer kaufen?";
}
   
if ( $context == "reg" ) { 
$_imagelg = PICPATH. "_bilderDetail/".$context."__lg_region-warum-kaufen.jpg"; 
$_imagemd = PICPATH. "_bilderDetail/".$context."__sm_region-warum-kaufen.jpg"; 
$_imagexs = PICPATH. "_bilderDetail/".$context."__xs_region-warum-kaufen-xs.jpg"; 
$_alt = "warum diesen winzer kaufen?";
   
}   

if (  strlen ( $context ) > 3 ) {
$_imagelg = PICPATH. "_bilderDetail/".$context."__lg_passend-".$context_img.".jpg";
$_imagemd = PICPATH. "_bilderDetail/".$context."__sm_passend-".$context_img.".jpg";
$_imagexs = PICPATH. "_bilderDetail/".$context."__xs_passend-".$context_img."-xs.jpg";
$_alt = "wein passend zu " .$context_img ;
$_agcontext = ucfirst($context_img);
if ( $context_img == "kaese") {$_agcontext  = "Käse"; }
}   

   
?>
<!--
<img class="img-responsive visible-lg-block" src="<?php echo PICPATH; ?>_bilderDetail/win__lg_winzer-warum-kaufen.jpg" alt="warum winzer kaufen" />
<img class="img-responsive  visible-md-block " src="<?php echo PICPATH; ?>_bilderDetail/win__sm_winzer-warum-kaufen.jpg" alt="warum winzer kaufen" />
<img class="img-responsive visible-xs-block visible-sm-block" src="<?php echo PICPATH; ?>_bilderDetail/win__xs_winzer-warum-kaufen-xs.jpg" alt="warum winzer kaufen" />
 -->   

<img class="img-responsive d-none d-xl-block " src="<?php echo $_imagelg ?>" alt="<?php echo $_alt ?>" />
<img class="img-responsive d-none d-lg-block d-xl-none" src="<?php echo $_imagemd  ?>" alt="<?php echo $_alt ?>" />
<img class="img-responsive d-block d-lg-none" src="<?php echo $_imagexs   ?>" alt="<?php echo $_alt ?>" />
    



<div class="content terroir ">
<p class="caption"> <a href="#sectionContent" class="jumpSection">
<?php 
switch ($context) {
case 'reg':
echo("Terroir");
break;

case 'win':
echo("Lage des Gutes");
break;

case 'passend': 
if ( $_agcontext != "Vegetarisch") { echo("Wein zu $_agcontext"); }
if ( $_agcontext == "Vegetarisch") { echo("Wein zu {$context_img}en Gerichten"); }
break;

default:
echo("Terroir");
break;
}
?>
</a></p>
<p class="txt"><a href="#sectionContent" class="jumpSection">
<?php      

if($terroir): 
$search    = array('<br></br>');
$replace   = array("</p><p>");
$txt            = preg_replace("/\n/", "</p><p>", $terroir);    
$text2 = $App::getLinkfromBracket($txt, $linkIdentifers);
echo $text2; 
endif;
?>

</a></p> </div>



<div class="content weine ">
<p class="caption"><a href="#sectionWeine" class="jumpSection">
<?php 
switch ($context) {
case 'reg':
echo("Weine");
break;

case 'win':
echo("Weine des Winzers");
break;

case 'passend':
echo("Passende Weine");
break;

default:
echo("Weine");
break;
}
?>

</a></p>
<p class="txt"><a href="#sectionWeine" class="jumpSection">
<?php      

if($weine): 
$search    = array('<br></br>');
$replace   = array("</p><p>");

$txt            = preg_replace("/\n/", "</p><p>", $weine);    
$text2 = $App::getLinkfromBracket($txt, $linkIdentifers);
//echo $linkIdentifers;
echo $text2; 
endif;
?>

</a></p> </div>

<div class="content winzer ">
<p class="caption"><a href="#sectionWinzer" class="jumpSection">
   
<?php 
switch ($context) {
case 'reg':
echo("Winzer der Region");
break;

case 'win':
echo("Der Winzer");
break;

case 'passend':
echo("Zubereitung");
break;

default:
case 'reg':
echo("Winzer");
break;
}
?>


</a></p>
<p class="txt"><a href="#sectionWinzer" class="jumpSection">
<?php      

if($winzer): 
$search    = array('<br></br>');
$replace   = array("</p><p>");

$txt            = preg_replace("/\n/", "</p><p>", $winzer);    
$text2 = $App::getLinkfromBracket($txt, $linkIdentifers);
//echo $linkIdentifers;
echo $text2; 
endif;
?>

</a></p> </div>

</div>
</div>

<div class="clearfix"></div>
    