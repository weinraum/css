<?php
if (!isset($seg23)) {$seg23 = "";}
if (!isset($seg2)) {$seg2 = "";}
if (!isset($_SESSION['customer']['id'])) {$_SESSION['customer']['id'] = "";}
if (!isset($navigation)) {$navigation = "";}
if (!isset($show_nav_wine_menu )) {$show_nav_wine_menu = "";}
if (!isset($bread_regionen)) {$bread_regionen = "";}
if (!isset($show_winzer_bread)) {$show_winzer_bread = "";}
if (!isset($show_winzerCat)) {$show_winzerCat = "";}
if (!isset($show_gp_bread)) {$show_gp_bread = "";}
if (!isset($show_lex_bread)) {$show_lex_bread = "";}
if (!isset($show_weinraum_bread)) {$show_weinraum_bread = "";}
if (!isset($show_gp_bread)) {$show_gp_bread = "";}
if (!isset($navigation)) {$navigation = "";}
if (!isset($show_xs_menue_konto)) {$show_xs_menue_konto = "";}
if (!isset($show_gp_bread)) {$show_gp_bread = "";}
if (!isset($_SESSION['critPol1'])) {$_SESSION['critPol1'] = "";}
if (!isset($_SESSION['critPol2'])) {$_SESSION['critPol2'] = "";}
if (!isset($_SESSION['critPol3'])) {$_SESSION['critPol3'] = "";}
if (!isset($_SESSION['critProducer'])) {$_SESSION['critProducer'] = "";}
if (!isset($_SESSION['ID'])) {$_SESSION['ID'] = "";}
if (!isset($_SESSION['critProduct'])) {$_SESSION['critProduct'] = "";}
if (!isset($wherePrice)) {$wherePrice = "";}
if (!isset($whereType)) {$whereType = "";}

$App = new \App\Libraries\App(); 

if ( is_numeric($_SESSION['customer']['id']) ){
$ag_customer = $_SESSION['customer']['name']." ". $_SESSION['customer']['family_name'];
}
if ( !is_numeric($_SESSION['customer']['id']) ){
$ag_customer = "";
}
?>
<header>    
<div class=" top-desk ">
<?= csrf_field() ?>    
<p class="class">https://www.weinraum.de/<?php echo $seg23; ?></p>    
<p class="classLand"><?php echo $seg2; ?></p>   
<div class="kopf d-flex flex-row ">
<div class="brand desk  col-6">
<a href="/" class="weinraum">weinraum</a>
<a href="/" class="unten"><span class="li">_______</span><span class="txt">Seit 1998</span><span class="re">_______</span></a>
</div>   
<div class="office col-6">
<p class="knopfleiste "> 

<a href="/" class="head warenkorb">
<img src="/_/img/ic-kasse.webp" class="warenkorb" alt="weinraum - warenkorb" loading="lazy">
<?php if( isset($_SESSION['cart']['positions']) AND $_SESSION['cart']['positions'] > 0 ) { ?>
<span class="bobble block"><?php echo $_SESSION['cart']['positions']; ?></span>
<?php } 
 else { ?>
<span class="bobble none">0</span>
<?php } ?>
</a>

<a href="/weinraum/<?php echo is_numeric($_SESSION['customer']['id'])?'mw_orderedWines':'anmelden'?>" class="konto ">
<img src="/_/img/ic-konto.webp" class="konto" alt="mein konto" loading="lazy">
</a>

<img src="/_/img/ic-suche.webp" class="suche " alt="weinraum - suche" loading="lazy">

</p>
<p class="knopfleiste "><a href="/weinraum/<?php echo is_numeric($_SESSION['customer']['id'])?'mw_orderedWines':'anmelden'?>" class="ag-name"><?php echo $ag_customer;?></a></p>
</div> 
</div>

<?php   if ( $show_nav_wine_menu == "yes") { ?>    
<div class="d-block d-md-none d-flex main_menue"><p class="menmob">
<span class="menue-switch-button-weine <?php echo((isset($nav_show_weine) == 1 AND $nav_show_weine == 'yes') ? ' active' : '') ?> " >Weine</span>
<span class="menue-switch-button-winzer <?php echo((isset($class) == 1 AND $class == 'winzer') ? ' active' : '') ?> " >Winzer</span>
<span class="menue-switch-button-regionen <?php echo((isset($class)  == 1 AND $class == 'regionen') ? ' active' : '') ?> " >Regionen</span>
<a href="/weinraum/kontakt" class=" kontakt  <?php echo((isset($class) AND $class== 'kontakt' ) ? ' active' : '') ?>">Kontakt</a>
</p></div>
<div class="d-none d-md-block d-flex main_menue">
<a href="/wein/" class="weine <?php echo((isset($nav_show_weine) == 1 AND $nav_show_weine == 'yes') ? ' active' : '') ?>">Weine</a>
<a href="/winzer" class=" winzer  <?php echo((isset($class) == 1 AND $class == 'winzer') ? ' active' : '') ?>">Winzer</a>
<a href="/regionen/frankreich_italien_deutschland" class=" regionen  <?php echo((isset($class)  == 1 AND $class == 'regionen') ? ' active' : '') ?>">Regionen</a>
<a href="/weinraum/kontakt" class=" kontakt  <?php echo((isset($class) AND $class== 'kontakt' ) ? ' active' : '') ?>">Kontakt</a>
</div>     
<p class="linie unten ">&nbsp;</p>
   
<?php }  ?>
<p class="linie mitte">&nbsp;</p>
<div class="bread d-flex">
<?php
if ( isset($bread_regionen) AND $bread_regionen == "yes" AND isset($content[0]['Bread'][0])  ) { ?>
<div class=" bread-inner regionen">
<?php
foreach ( $content[0]['Bread'][0] as $kBread => $vBread ) {
if ($kBread == 0 ) { $Link[$kBread]  = "/".$content[0]['urlsPure'][0][$kBread]."/frankreich_italien_deutschland"; }
if ($kBread == 1 ) { $Link[$kBread]  = "/regionen/".$content[0]['urlsPure'][0][$kBread]; }
if ($kBread > 1 ) { $Link[$kBread]  = $Link[$kBread -1]."/".$content[0]['urlsPure'][0][$kBread]; }
$Bread[$kBread]  = $vBread;
}
$countCat = count($content[0]['Bread'][0]) - 1;
foreach ( $content[0]['Bread'][0] as $kBread => $vBread ) {
if ($kBread == 0 ) {$lala = "<a href= '".$Link[$kBread]."'>".$Bread[$kBread]."</a>"; }
if ($kBread > 0 AND $kBread < $countCat ) { 
if ($kBread == 4 AND $kBread < $countCat ) { 
$lala .= "<br><a href= '".$Link[$kBread]."'>".$Bread[$kBread]."</a>"; 
}
if ($kBread > 4 AND $kBread != $countCat ) { 
$lala .= "<span class='sep'>&nbsp;››&nbsp;</span><a href= '".$Link[$kBread]."'>".$Bread[$kBread]."</a>"; 
}
}
if ($kBread > 0 AND $kBread == $countCat ) { $lala .= "<span class='sep'>&nbsp;››&nbsp;</span><span class='letzt' >".$Bread[$kBread]."</span>"; }
}
if ( $lala == "" ) {$lala = "<span >Regionen</span>";}
?>
<a href = "/" class="start"><img src="/_/img/ic-home.webp" class="suche " alt="startseite"></a><span class="sep">&nbsp;››&nbsp;</span>
<?php echo $lala ?>
<?php
}
if ( isset($bread_regionen) AND $bread_regionen == "yes" AND !isset($content[0]['Bread'][0])  ) {  ?>
<p><a href = "/" class="start"><img src="/_/img/ic-home.webp" class="suche " alt="startseite"></a><span class="sep">&nbsp;››&nbsp;</span><span class='letzt' >Regionen</span></p>
<?php
}
if ( $show_winzer_bread == "yes" AND isset($content[0]['Bread'][0])) {?>
<div class=" bread-inner winzer">
<?php 
foreach ( $content[0]['Bread'][0] as $kBread => $vBread ) {
if ($kBread == 0 ) { $Link[$kBread]  = "/".$content[0]['urlsPure'][0][$kBread]; }
if ($kBread > 0 ) { $Link[$kBread]  = $Link[$kBread -1]."/".$content[0]['urlsPure'][0][$kBread]; }
$Bread[$kBread]  = $vBread;
}
$countCat = count($content[0]['Bread'][0]) - 1;
foreach ( $content[0]['Bread'][0] as $kBread => $vBread ) {
if ($kBread == 0 ) {
$lala = "<a href= '".$Link[$kBread]."'>".$Bread[$kBread]."</a>"; 
$lalaLG = "<a href= '".$Link[$kBread]."'>".$Bread[$kBread]."</a>"; 
}
if ($kBread > 0 AND $kBread < $countCat ) { 
if ($kBread < 3 AND $kBread != $countCat ) { 
$lala .= "<span class='sep'>&nbsp;››&nbsp;</span><a href= '".$Link[$kBread]."'>".$Bread[$kBread]."</a>"; 
$lalaLG .= "<span class='sep'>&nbsp;››&nbsp;</span><a href= '".$Link[$kBread]."'>".$Bread[$kBread]."</a>"; 
}
if ($kBread > 3 AND $kBread != $countCat ) { 
$lala .= "<span class='sep'>&nbsp;››&nbsp;</span></p><p class='d-block d-md-none'><a href= '".$Link[$kBread]."'>".$Bread[$kBread]."</a>"; 
$lalaLG .= "<span class='sep'>&nbsp;››&nbsp;</span><<a href= '".$Link[$kBread]."'>".$Bread[$kBread]."</a>"; 
}
}
if ($kBread <= 3 AND $kBread == $countCat ) { 
$lala .= "<span class='sep'>&nbsp;››&nbsp;</span><span class='letzt' >".$Bread[$kBread]."</span>"; 
$lalaLG .= "<span class='sep'>&nbsp;››&nbsp;</span><span class='letzt' >".$Bread[$kBread]."</span>"; 
}
if ($kBread > 3 AND $kBread == $countCat ) { 
$lala .= "<span class='sep'>&nbsp;››&nbsp;</span></p><p class='d-block d-md-none'><span class='letzt' >".$Bread[$kBread]."</span>"; 
$lalaLG .= "<span class='sep'>&nbsp;››&nbsp;</span><span class='letzt' >".$Bread[$kBread]."</span>"; 
}
}
if ( $lala == "" ) {$lala = "<span >Winzer</span>";}
if ( $lalaLG == "" ) {$lalaLG = "<span >Winzer</span>";}
?>
<p class="d-block d-md-none"><a href = "/" class="start"><img src="/_/img/ic-home.webp" class="suche " alt="startseite"></a><span class="sep">&nbsp;››&nbsp;</span>
<?php echo ("$lala"); ?>
</p>
<p class="d-none d-md-block"><a href = "/" class="start"><img src="/_/img/ic-home.webp" class="suche " alt="startseite"></a><span class="sep">&nbsp;››&nbsp;</span>
<?php echo ("$lalaLG"); ?>
</p>
<?php
}
if ( $show_winzer_bread == "yes" AND  !isset($content)  ) {  ?>
<p><a href = "/" class="start"><img src="/_/img/ic-home.webp" class="suche " alt="startseite"></a><span class="sep">&nbsp;››&nbsp;</span><span class='letzt' >Winzer</span></p>
<?php
}
if ( $show_winzerCat == "yes") { ?>
<div class=" bread-inner categorie">
<?php
foreach ( $categories as $kBread => $vBread ) {//echo("kjl $kBread");
if ($kBread == 0 ) { $Link[$kBread]  = "/".$categoriesIdenti[$kBread]; }
if ($kBread > 0 ) { $Link[$kBread]  = $Link[$kBread -1]."/".$categoriesIdenti[$kBread]; }
$Bread[$kBread]  = $vBread;
}
$countCat = count($categories) -1;
foreach ( $categories as $kBread => $vBread ) {
if ($kBread == 0 ) {$lala = "<a href= '".$Link[$kBread]."'>".$Bread[$kBread]."</a>"; }
if ($kBread > 0 AND $kBread < $countCat ) { $lala .= "<span class='sep'>&nbsp;››&nbsp;</span><<a href= '".$Link[$kBread]."'>".$Bread[$kBread]."</a>"; }
if ($kBread > 0 AND $kBread == $countCat ) { $lala .= "<span class='sep'>&nbsp;››&nbsp;</span><span class='letzt'>".$Bread[$kBread]."</span>"; }
}
}
if ( $show_gp_bread == "yes") { ?>
<p><a href = "/" class="start"><img src="/_/img/ic-home.webp" class="suche " alt="startseite"></a><span class="sep">&nbsp;››&nbsp;</span><a href = "/weinraum/geschmackpost">Weine Ihrer letzten GeschmackPost</a>
<?php if ( $showEinen == 1) { 
$prod_ID = key($weine['wines']);
$lala ="&nbsp;<span class='sep'>››&nbsp;</span>" . $weine['wines'][$prod_ID]['prod_name'];
echo $lala ;
}
?>
</p>
<?php
}
if ( isset($show_gp_checkout) AND $show_gp_checkout == "yes" AND 12 == 13) {?>
<div class=" bread-inner check">
<?php ?>
<p><a href = "/" class="start"><img src="/_/img/ic-home.webp" class="suche " alt="startseite"></a><span class="sep">&nbsp;››&nbsp;</span><a href = "/" class="head warenkorb">Warenkorb</a><span class="sep">&nbsp;››&nbsp;</span><span class="letzt">Kasse</span></p>
<?php }
if ( isset($show_lex_bread) AND $show_lex_bread == "yes") { ?>
<div class=" bread-inner lex">
<p>
<?php
if ( isset($linkBread1) ) { 
if ( $linkBread1 == "/lexikon") {     ?>
<a href = "/" class="start"><img src="/_/img/ic-home.webp" class="suche " alt="startseite"></a><span class="sep">&nbsp;›› </span><a href = "<?php echo $linkBread1 ?>"><?php echo $txtBread1 ?></a><span class="sep">&nbsp;››&nbsp; </span><span class="letzt"><?php echo $txtBread2 ?></span>
<?php } else { ?>
<a href = "/" class="start"><img src="/_/img/ic-home.webp" class="suche " alt="startseite"></a><span class="sep">&nbsp;›› </span><a href = "/lexikon">Lexikon</a><span class="sep">&nbsp;››&nbsp;</span><a href = "<?php echo $linkBread1 ?>"><?php echo $txtBread1 ?></a><span class="sep">&nbsp;››&nbsp; </span><span class="letzt"><?php echo $txtBread2 ?></span>
<?php } 
 } else { 
if ( $txtBread2 == "Lexikon") {     ?>
<a href = "/" class="start"><img src="/_/img/ic-home.webp" class="suche " alt="startseite"></a><span class="sep">&nbsp;›› &nbsp; </span><span class="letzt"><?php echo $txtBread2 ?></span>
<?php } 
else { ?>
<a href = "/" class="start"><img src="/_/img/ic-home.webp" class="suche " alt="startseite"></a><span class="sep">&nbsp;›› </span><a href = "/lexikon">Lexikon</a><span class="sep">&nbsp;›› &nbsp; </span><span class="letzt"><?php echo $txtBread2 ?></span>
<?php }
}
?>
</p>
<?php
}
if ( isset($show_weinraum_bread) AND $show_weinraum_bread == "yes") {?>
<div class=" bread-inner weinraum">
<?php ?>
<p><a href = "/" class="start"><img src="/_/img/ic-home.webp" class="suche " alt="startseite"></a><span class="sep">&nbsp;›› &nbsp; </span><a href = "/weinraum/anmelden">Mein Konto</a><span class="sep">&nbsp;›› </span><span class="letzt"><?php echo $txtBread2 ?></span></p>
<?php } 
if ( $navigation == "wein"  ) {?>
<div class=" bread-inner wein">
<?php ?>
<p><a href = "/" class="start"><img src="/_/img/ic-home.webp" class="suche " alt="startseite"></a><span class="sep">&nbsp;››&nbsp;</span>
<a href = "/wein">Weine</a><?php if ( $class != "wein" ) { ?> <span class="sep">&nbsp;›› </span><?php } ?>
<?php
if ( $class == "weisswein" ) { ?>    <a href = "/weisswein">Weisswein</a> <?php   }
if ( $class == "rotwein" ) { ?>    <a href = "/rotwein">Rotwein</a> <?php  }
if ( $class == "rose" ) { ?>    <a href = "/rose">Rosé</a> <?php  }
if ( $class == "cremant" ) { ?>    <a href = "/cremant">&nbsp;Schaumwein</a> <?php }
if (isset($menue) ) {  
if (isset($_SESSION['critPol1']) AND is_array($_SESSION['critPol1']) ) {  
if (count($_SESSION['critPol1']) > 0) {  
echo("<span class='sep'>&nbsp;››&nbsp;</span>");
// es gibt hier nur einen Wert in alle Weine können es mehr sein. Als Schleife, da zu faul die funktionen für getkey oder wie das heißt zu suchen
foreach ( $_SESSION['critPol1'] as $k => $v ) { 
if ( isset($menue['data']['pol1'][$k]['name_pol1_url'])){
$urlLand = strtolower($menue['data']['pol1'][$k]['name_pol1_url']);
?>
<a href = "/<?php echo $class ?>/<?php echo $urlLand?>"><?php echo $menue['data']['pol1'][$k]['name_pol1'] ?> </a>
<?php
}    
}
}
}
if (is_array($_SESSION['critPol2']) ) {  
if ( count($_SESSION['critPol2']) > 0 ) {  
echo("<span class='sep'>&nbsp;››&nbsp;</span>");
foreach ( $_SESSION['critPol2'] as $k => $v ) {
if ( isset($menue['data']['pol2'][$k]['name_pol2_url'])){
$urlPol2= strtolower($menue['data']['pol2'][$k]['name_pol2_url']);
?>
<a href = "/<?php echo $class ?>/<?php echo ("$urlLand/$urlPol2")?>"><?php echo $menue['data']['pol2'][$k]['name_pol2'] ?> </a>
<?php   
}
}
}
}
//echo("hu4  {$_SESSION['critPol3']}");
if ( isset($_SESSION['critPol3']) AND is_array($_SESSION['critPol3']) ) {  
if ( count($_SESSION['critPol3']) > 0 ) {  
foreach ( $_SESSION['critPol3'] as $k => $v ) {
if ( isset($menue['data']['pol3'][$k]['name_pol3_url'])){
$urlPol3= strtolower($menue['data']['pol3'][$k]['name_pol3_url']);
if ( $urlPol3 != "" ) {    echo("<span class='sep'>&nbsp;››&nbsp;</span>");
}
?>
<a href = "/<?php echo $class ?>/<?php echo ("$urlLand/$urlPol2/$urlPol3")?>"><?php echo $menue['data']['pol3'][$k]['name_pol3'] ?> </a>
<?php   
}
}
}
}
if (is_array($_SESSION['critProducer']) ) {  
if ( count($_SESSION['critProducer']) > 0 ) {  
echo("<span class='sep'>&nbsp;››&nbsp;</span>");
foreach ( $_SESSION['critProducer'] as $k => $v ) { 
// der Wert $menue['data']['producer'] wird nur unter Bedingungen zugewiesen, daher prüfen
if ( isset($menue['data']['producer'][$k]['cont2prod']) ) {   
if ( $menue['data']['producer'][$k]['cont2prod'] == 0) {   
?>
<span ><?php echo $menue['data']['producer'][$k]['producer'] ?> </span>
<?php
}
else { ?>

<?php
}
}
}
}
}
if (is_array($_SESSION['critProduct']) ) {  
if ( count($_SESSION['critProduct']) > 0 ) {  
echo("<span class='sep'>&nbsp;››&nbsp;</span>");
foreach ( $_SESSION['critProduct'] as $k => $v ) {
echo $weine['wines'][$k]['prod_name'];
}
}
}
if ( $wherePrice != "" ) {  
echo("<span class='sep'>&nbsp;››&nbsp;</span>");
echo $wherePrice;
}
if ( $whereType != "" ) {  
echo("<span class='sep'>&nbsp;››&nbsp;</span>");
$rest = substr($whereType, 0, 1);
if  ( $rest == "W") { $color = "Weiß, "; }
if  ( $rest == "R") { $color = "Rot, "; }
echo $color; eh('sidebar_search_wine_type'.$whereType); 
}
} ?>
</p>
<?php } ?>
</div>  
</div>  
</div> 
<?php

/* --------- *    MOBIL MENUE PERSONAL *  */  
if ( is_numeric($_SESSION['customer']['id']) ){
$ag_customer = $_SESSION['customer']['name']." ". $_SESSION['customer']['family_name'];
} 
if ( $show_xs_menue_konto == "yes" ) { 
?>
<div class="d-block d-sm-none ">    
<nav class="pers-navigation <?php  echo($nav_bottom == 'line'? ' bottom': '');  ?> ">
<div class="container-fluid">
<div class="menue-switch-person ">
<p class="menue-switch-button">
<span class="icon-text">Ihr Konto: Menue</span></p>
<input  type="hidden" name="login_nav" value="1" >
</div>
<div id="pers-konto" class="d-flex flex-row pers-konto"> 
<ul class="pers-liste">   
<?php
if ( $_SESSION['customer']['id'] != ""){ 
$navigation = isset($navigation)?$navigation:"";
$sub_navi = isset($sub_navi)?$sub_navi:"";
?>
<li <?php echo( $sub_navi == 'opOrders' ? ' class="active"' : '') ?>>
<a href="/weinraum/mw_openOrders" <?php echo( $sub_navi == 'opOrders' ? ' class="active"' : '')?> >Bestellungen</a></li>
<li <?php echo( $sub_navi == 'invoicesCust' ? ' class="active"' : '') ?>>
<a href="/weinraum/mw_invoices"  <?php echo( $sub_navi == 'invoicesCust' ? ' class="active"' : '')?> >Rechnungen</a></li>
<li <?php echo( $sub_navi == 'ordWines' ? ' class="active"' : '') ?>>
<a href="/weinraum/mw_orderedWines"  <?php echo( $sub_navi == 'ordWines' ? ' class="active"' : '')?> >Weine</a></li>

<!--  <li <?php echo($navigation == 'merkliste' ? ' class="line"' : ' class="inact"') ?>><a href="/weinraum/merkliste" <?php echo($navigation == 'merkliste' ? ' class="active"' : '') ?>>Merkliste</a></li>  -->
<li <?php echo($navigation == 'persDat' ? ' class="line"' : ' class="inact"') ?>>
<?php if ( !isset($_SESSION['customer']['GP_Empfaenger_ohne_Daten']) OR $_SESSION['customer']['GP_Empfaenger_ohne_Daten'] != 1 ) { ?>
<a href="/weinraum/general_persDat/pure" <?php echo($navigation == 'persDat' ? ' class="active"' : '') ?>>Persönliche Daten</a>
<?php
}
if ( isset($_SESSION['customer']['GP_Empfaenger_ohne_Daten']) AND $_SESSION['customer']['GP_Empfaenger_ohne_Daten'] == 1 )  { ?>
<a href="/weinraum/persDat_GP_loggedIn/daten" <?php echo($navigation == 'persDat' ? ' class="active"' : '') ?>>Persönliche Daten</a>
<?php
}
?>
</li>

<?php
if($App::isAdmin() ):     ?>
<li <?php echo($sub_navi == 'admin_tg' ? ' class="active"' : '') ?>><a href="<?php echo $App::getUrl('/admin_themengarten'); ?>"><?php echo lang('german_lang.menu_admin_themengarten'); ?></a></li>
<li <?php echo($sub_navi == 'admin_cat' ? ' class="active"' : '') ?>><a href="<?php echo $App::getUrl('/admin_category'); ?>" ><?php echo lang('german_lang.menu_admin_category'); ?></a></li>
<?php endif; ?>
<li class="inact navigation_xs_navbar"><a href="/logout" >Abmelden</a></li>
<?php } 
if ( $_SESSION['customer']['id'] == ""){ ?>
<li class="inact navigation_xs_navbar d-block d-sm-none"><br><a href="/weinraum/anmelden" >Anmelden</a><br><br><br></li>
<?php } ?>
</ul>
</div>
</div>
</nav>
</div>
<?php } /*    Ende MOBILE VERSION - andere Struktur als für Bildschirm*/ 
if ( isset($show_desk_menue_konto) AND $show_desk_menue_konto == "yes" ) { 
?>
<div class="d-none d-sm-block pers-konto">    
<div class="menue-switch-person <?php echo($nav_bottom == 'line'? ' bottom': '');  ?> ">
<div class="container-fluid">
<div class="pers-konto d-flex flex-row "> 
<ul class="pers-liste d-flex flex-row ">   
<a href="#" class="d-none d-sm-block " >
<?php echo(is_numeric($_SESSION['customer']['id']) ? $ag_customer  : 'Ihr Konto im weinraum') ?></a> 
<?php echo(is_numeric($_SESSION['customer']['id']) ? '' : '    <li class="active d-none d-sm-block">
<a href="/weinraum/anmelden/customer" class="anmelden">Anmelden</a>
</li> ') ?>
<input  type="hidden" name="login_nav" value="1" >
<?php
if ( $_SESSION['customer']['id'] != ""){ ?>
<li <?php echo($navigation == 'merkliste' ? ' class="line"' : ' class="inact"') ?>>
<a href="/weinraum/kasse" <?php echo($navigation == 'basket' ? ' class="basket active"' : 'basket') ?> >
<span class="icon wr-cart"></span>&nbsp;:&nbsp; <?php echo (isset($agWert) AND $agWert)?$agWert."&euro;":""; ?></a>
</li>
<li <?php echo( $sub_navi == 'opOrders' ? ' class="active"' : '') ?>>
<a href="/weinraum/mw_openOrders" <?php echo( $sub_navi == 'opOrders' ? ' class="active"' : '')?> >Bestellungen</a></li>
<li <?php echo( $sub_navi == 'invoicesCust' ? ' class="active"' : '') ?>>
<a href="/weinraum/mw_invoices"  <?php echo( $sub_navi == 'invoicesCust' ? ' class="active"' : '')?> >Rechnungen</a></li>
<li <?php echo( $sub_navi == 'ordWines' ? ' class="active"' : '') ?>>
<a href="/weinraum/mw_orderedWines"  <?php echo( $sub_navi == 'ordWines' ? ' class="active"' : '')?> >Weine</a></li>

<li <?php echo($navigation == 'persDat' ? ' class="line active"' : ' class="inact"') ?>>
<?php if ( !isset($_SESSION['customer']['GP_Empfaenger_ohne_Daten']) OR $_SESSION['customer']['GP_Empfaenger_ohne_Daten'] != 1 ) { ?>
<a href="/weinraum/general_persDat/pure" <?php echo($navigation == 'persDat' ? ' class="active"' : '') ?>>Persönliche Daten</a>
<?php
}
if ( isset($_SESSION['customer']['GP_Empfaenger_ohne_Daten']) AND $_SESSION['customer']['GP_Empfaenger_ohne_Daten'] == 1 )  { ?>
<a href="/weinraum/persDat_GP_loggedIn/daten" <?php echo($navigation == 'persDat' ? ' class="active"' : '') ?>>Persönliche Daten</a>
<?php
}
?>
</li>

<?php
if($App::isAdmin() ):     ?>

<li <?php echo($sub_navi == 'admin_tg' ? ' class="active"' : '') ?>><a href="<?php echo $App::getUrl('/admin_themengarten'); ?>"><?php echo lang('german_lang.menu_admin_themengarten'); ?></a></li>
<li <?php echo($sub_navi == 'admin_cat' ? ' class="active"' : '') ?>><a href="<?php echo $App::getUrl('/admin_category'); ?>" ><?php echo lang('german_lang.menu_admin_category'); ?></a></li>
<?php endif; ?>
<li class="inact"><a href="/logout" >Abmelden</a></li>
<?php } 
if ( $_SESSION['customer']['id'] == ""){ ?>
<li class="active d-block d-sm-none"><br><a href="/weinraum/anmelden" class="anmelden">Anmelden</a><br><br><br></li>
<?php } ?>
</ul>
</div>
</div>
</div>
<div class="clearer"></div>  
<?php
/* ENDE PC VERSION */
}
?>
</header>
<section>

<?php
function show_small( $weine, $indArray, $indPage, $indRow = NULL, $prod_ID = NULL, $_anzPack = NULL, $_liste = NULL, $_aktion = NULL, $_gp = NULL, $linkIdentifers = NULL, $_edit = NULL, $_lazy = NULL) { 
$Content = new \App\Libraries\Content(); 
$App = new \App\Libraries\App(); 
//echo("$_gp - $_lazy<br>");
if ( $_gp == "gp" ) { $_link = "weinraum/geschmackpost"; }   
else {  
if ( $_edit == "edit" ){ $_link = "wein_edit"; }
if ( $_edit == NULL ){ $_link = "wein"; }
}
if (!isset($prod_ID) AND isset($indArray[$indPage]) ) { $prod_ID = $indArray[$indPage]; } 
if (isset($prod_ID) AND is_numeric($prod_ID) ) {  //
if ( isset($weine['wines'][$prod_ID]) ) { 
$abfr_bild = DOEMELLUNNCHE."alt/WeiID".$prod_ID."_254.jpg";
$abfr4_fl_v = DOEMELLUNNCHE."flasche_klein/".$prod_ID."_v.webp";
$abfr4_fl_v_xs = DOEMELLUNNCHE."flasche_klein/".$prod_ID."_v_xs.webp";
$_fi_exists_bild = file_exists($abfr_bild);
$_fi_exists_4fl_v = file_exists($abfr4_fl_v);
$_fi_exists_4fl_v_xs = file_exists($abfr4_fl_v_xs);
// die Abfragen ob der file existitert kann durch einen cron erledigt werden und die Angabe im Wein gespeichert sein.
// 
// auf die picture Nummer umformatieren, abfragen und wenn nicht da: neu speichern
$_heightMin = 550;
$heightBox = 550;
$heightFlaschenBox = 330;
// if (file_exists($abfr4_fl_v) ) { list($width, $height, $type, $attr) = getimagesize($abfr4_fl_v); }
// die höhe der box wird von anzahl der Trauben bestimmt, weil jede eine zeile bekommt
if ( isset($weine['wines'][$prod_ID]['Grapes'])) {
if ( $_heightMin >= 450 ) {
if ( count($weine['wines'][$prod_ID]['Grapes'] ) <= 2 ) {    
$heightBox = $_heightMin;
}
if ( count($weine['wines'][$prod_ID]['Grapes'] ) >= 3 ) {    
$heightBox = $_heightMin + (count($weine['wines'][$prod_ID]['Grapes'] ) -2 )*15;
}
}
}
//$rest = substr($weine['wines'][$prod_ID]['type'], 0, 1);
//if  ( $rest == "W") { $color = "Weiß, "; }
//if  ( $rest == "R") { $color = "Rot, "; }
if ( isset($weine['wines'][$prod_ID]['type']) ) {
$_type = $App::getLinkfromBracket("[[typ || ".$weine['wines'][$prod_ID]['type']."]]", $linkIdentifers);
}
else { $_type = ""; }
if ( isset($weine['wines'][$prod_ID]['cont2prod_identifer']) ) {
$_winzer = $App::getLinkfromBracket("[[win || ".$weine['wines'][$prod_ID]['cont2prod_identifer']." || ". $weine['wines'][$prod_ID]['producer']."]]", $linkIdentifers );
//echo $_winzer;

}
else { $_winzer = ""; }
// Prüfen ob Nutzer angemeldet und ob oder welche Aktiosweine in Mail waren
$_showAktPreis = 0;
if ( isset($_SESSION['customer']) AND isset($_SESSION['gpWeine']) ) {
if ( is_numeric($_SESSION['customer']['id'] ) AND is_array($_SESSION['gpWeine'] ) AND $_aktion == 1) {
if ( $weine['wines'][$prod_ID]['aktions_preis'] > 0) {
$_showAktPreis = 1;    
}
}
}
if($_anzPack > 0 ) { ?>
<div class="ifPack head">
<p class="noBott"><span class="icon round"> <?php echo $_anzPack  ?>x</span></p>
</div>
<div class="clearer">&nbsp;</div>  
<?php } ?>
<div class="teaser <?php echo "teaser_".$prod_ID." "; echo $indRow == 0?'first':'normal'; echo (isset($_SESSION['cart_art'][$weine['wines'][$prod_ID]['prodID']]['number']) AND $_SESSION['cart_art'][$weine['wines'][$prod_ID]['prodID']]['number'] > 0 )?" warenK":"";?> ">
<style {csp-style-nonce} >
div.infoProd{ height: <?php echo isset($heightBox)?$heightBox:'0'  ?>px; }
</style>


<div class="infoProd" >
<p class="producer">
 <?php
if ( !isset($weine['wines'][$prod_ID]['prodID']) ) { }
echo  $_winzer ?></p>
<a href="/<?php echo $_link ?>/<?php  echo strtolower($weine['wines'][$prod_ID]['identifer'])."__".$weine['wines'][$prod_ID]['prodID'] ?>"
 title="Detailansicht von '<?php echo $weine['wines'][$prod_ID]['prod_name'] ?>'" >
<?php  
if (!$_fi_exists_4fl_v  ) {
if ( !$_fi_exists_bild ) { 
if (strlen($weine['wines'][$prod_ID]['prod_name']) > 45) {     //echo strlen($weine['wines'][$prod_ID]['prod_name']);   ?>      
<style {csp-style-nonce} >h2{ width: 100%; }</style>
<h2>
<?php }
if (strlen($weine['wines'][$prod_ID]['prod_name']) < 45) {  ?>
<h2>
<?php } 
echo $weine['wines'][$prod_ID]['prod_name']; 
?>
</h2></a>  
<div class="Flaschencontainer" >
<div class="flasche position-absolute bottom-0">    
<a href="/<?php  echo $_link ?>/<?php  echo strtolower($weine['wines'][$prod_ID]['identifer'])."__".$weine['wines'][$prod_ID]['prodID'] ?>"
 title="Detailansicht von '<?php echo $weine['wines'][$prod_ID]['prod_name'] ?>'" 
<?php  if ( $_liste ) { 
if ( !isset($_pos) ) { $_pos = 1; }
} ?>
>
<?php if (!$_fi_exists_4fl_v AND !$_fi_exists_4fl_v_xs) { ?>
<img src="/_/img/wein-folgt-xs.webp" alt="Bild für diesen Wein folgt bald"  class="img-responsive" <?php echo $_lazy == 1?"loading='lazy'":""; ?> />
<?php  }
if ($_fi_exists_4fl_v AND !$_fi_exists_4fl_v_xs ) { ?>
<img src="<?php echo PICPATH; ?>weine/flasche_klein/<?php echo $prod_ID; ?>_v.jpg"  alt="<?php echo $weine['wines'][$prod_ID]['prod_name']; ?>" class="img-responsive" <?php echo $_lazy == 1?"loading='lazy'":""; ?>/>
<?php } 
if ($_fi_exists_4fl_v_xs AND !$_fi_exists_4fl_v) { ?>
<img src="<?php echo PICPATH; ?>weine/flasche_klein/<?php echo $prod_ID; ?>_v_xs.webp"  alt="<?php echo $weine['wines'][$prod_ID]['prod_name']; ?>" class="img-responsive" <?php echo $_lazy == 1?"loading='lazy'":""; ?>/>
<?php } ?>
</a>
</div>
</div> 
<?php
}
if ( $_fi_exists_bild ) { 
?>
<div class="imagecontainer"><a href="/<?php  echo $_link ?>/<?php  echo strtolower($weine['wines'][$prod_ID]['identifer'])."__".$weine['wines'][$prod_ID]['prodID'] ?>"
 title="Detailansicht von '<?php echo $weine['wines'][$prod_ID]['prod_name'] ?>'" >
<img src="<?php echo PICPATH; ?>weine/alt/WeiID<?php echo $weine['wines'][$prod_ID]['prodID']; ?>_254.jpg" alt="<?php echo $weine['wines'][$prod_ID]['prod_name']; ?>" class="img-responsive" <?php echo $_lazy == 1?"loading='lazy'":""; ?>/>
</a>
</div>

 
<div class="analog flasche none">
<p class="wein-quer "><span class="txt">Weintyp:</span>
<span class="val trauben quer"><?php echo $_type; echo($weine['wines'][$prod_ID]['al'] ? ' - '.$weine['wines'][$prod_ID]['al'].' Vol%' : '') ?></span></p>
<?php
if ( isset($weine['wines'][$prod_ID]['producerZert']) ) { ?>
<p class="row-det type ">bio zertifiziert:</p>
<p class="row-det val"><?php echo (isset($weine['wines'][$prod_ID]['producerZert']))?$weine['wines'][$prod_ID]['producerZert']:""  ?></p>
<?php }
if (isset($weine['wines'][$prod_ID]['Grapes'])  ) { 
$i = -1; 
foreach ( $weine['wines'][$prod_ID]['Grapes'] as $k => $v ) {
$i ++; ?>
<?php echo($i == 0 ? '<p class="wein-quer trauben"><span class="txt">Trauben:</span>' : '') ?>
<span class="val "><?php echo $v['percent']?>% &nbsp;<?php echo $v['grape'];?><?php echo(count($weine['wines'][$prod_ID]['Grapes'])  > 1 ? ',' : '') ?></span>  
<?php } ?>
</p>
<?php } ?>
</div>

<?php }?>

<?php 
} 

if ($_fi_exists_4fl_v ) { 
if (strlen($weine['wines'][$prod_ID]['prod_name']) > 45) {     //echo strlen($weine['wines'][$prod_ID]['prod_name']);   ?>    
<style {csp-style-nonce} >
h2{ width: 100%; }
</style>  
<h2>
<?php }
if (strlen($weine['wines'][$prod_ID]['prod_name']) < 45) {  ?>
<h2>
<?php } 
echo $weine['wines'][$prod_ID]['prod_name']; 
?>
</h2>
</a>
<style {csp-style-nonce} >
div.Flaschencontainer{ <?php echo isset($heightFlaschenBox)?'height: '.$heightFlaschenBox.'px;':''  ?>}
</style>
<div class="Flaschencontainer" >
<div class="flasche position-absolute bottom-0">    
<a href="/<?php  echo $_link ?>/<?php  echo strtolower($weine['wines'][$prod_ID]['identifer'])."__".$weine['wines'][$prod_ID]['prodID'] ?>"
 title="Detailansicht von '<?php echo $weine['wines'][$prod_ID]['prod_name'] ?>'" 
<?php  if ( $_liste ) { 
if ( !isset($_pos) ) { $_pos = 1; }
} ?>
>
<?php if (!$_fi_exists_4fl_v) { ?>
<img src="/_/img/wein-folgt-xs.webp" alt="Bild für diesen Wein folgt bald" class="img-responsive" <?php echo $_lazy == 1?"loading='lazy'":""; ?> />
<?php  }
if ($_fi_exists_4fl_v AND !$_fi_exists_4fl_v_xs ) { ?>
<img src="<?php echo PICPATH; ?>weine/flasche_klein/<?php echo $prod_ID; ?>_v.jpg"  alt="<?php echo $weine['wines'][$prod_ID]['prod_name']; ?>" class="img-responsive" <?php echo $_lazy == 1?"loading='lazy'":""; ?>/>
<?php }
 
if ($_fi_exists_4fl_v_xs ) { ?>
<img src="<?php echo PICPATH; ?>weine/flasche_klein/<?php echo $prod_ID; ?>_v_xs.webp"  alt="<?php echo $weine['wines'][$prod_ID]['prod_name']; ?>" class="img-responsive" <?php echo $_lazy == 1?"loading='lazy'":""; ?>/>
<?php } ?>
</a>
</div>
</div> 
<?php } ?>


<div class="WeinDetails">
<div class="angaben <?php echo (!$_fi_exists_4fl_v AND $_fi_exists_bild )?"quer":"";?>">
<p class="val ">
<span class="left"><?php echo ($weine['wines'][$prod_ID]['year'] ? $weine['wines'][$prod_ID]['year']: 'ohne Jahr')?></span><span class="right"><?php echo $_type; ?></span></p>
<p class="val ">
<span class="left"><?php echo($weine['wines'][$prod_ID]['al'] ? $weine['wines'][$prod_ID]['al'] : 'k.A.') ?></span><span class="right">Vol%</span></p>
<?php if (isset($weine['wines'][$prod_ID]['producerZert'])) { ?>
<p class="val "><span class="left">bio</span><span class="right"><?php echo $weine['wines'][$prod_ID]['producerZert'] ?></span></p>
<?php } ?>

<?php   
if ( isset($weine['wines'][$prod_ID]['Grapes'])  ) { 
$i = -1; 
foreach ( $weine['wines'][$prod_ID]['Grapes']  as $k => $v ) {
$i ++; 
$_traube = (isset($weine['wines'][$prod_ID]['Grapes']) AND count($weine['wines'][$prod_ID]['Grapes']) == 1)?'Traube':'Trauben';    
//echo($i == 0 ? '<p class="type ">'.$_traube.'</p>' : ''); 
$_reb = $App::getLinkfromBracket("[[reb || ".$v['grape']."]]", $linkIdentifers);
?>
<p class="val trauben"><span class="left"><?php echo $v['percent']?>%</span><span class="right"><?php echo $_reb;?></span></p>  
<?php
} 
} ?>    
<p class="val "><a href="/<?php  echo $_link ?>/<?php  echo strtolower($weine['wines'][$prod_ID]['identifer'])."__".$weine['wines'][$prod_ID]['prodID'] ?>"
class="left" title="Detailansicht von '<?php echo $weine['wines'][$prod_ID]['prod_name'] ?>'">
<span class="detTrleft">&nbsp;</span></a>
<a href="/<?php  echo $_link ?>/<?php  echo strtolower($weine['wines'][$prod_ID]['identifer'])."__".$weine['wines'][$prod_ID]['prodID'] ?>"
class="left litext" title="Detailansicht von '<?php echo $weine['wines'][$prod_ID]['prod_name'] ?>'">
<span class="right details">Beschreibung</span><span class="detTrRight"></span></a></p>

<p class="val preis"><span class="left"><?php   echo(" {$weine['wines'][$prod_ID]['content']}l");?></span><span class="right">
<?php if ( $_showAktPreis != 1) { ?>
<?php echo $App::getMoneyValFormated($weine['wines'][$prod_ID]['price']);  ?>&nbsp;&euro; 
<?php }
 if ( $_showAktPreis == 1 ) { ?>
<?php echo $App::getMoneyValFormated($weine['wines'][$prod_ID]['aktions_preis']);  ?>&nbsp;&euro; 
<?php } ?>
</span></p>
</div>

<?php 
if (isset($weine['wines_invoices']) AND is_array($weine['wines_invoices'][$prod_ID])) { ?>
<p class="bought"><span class="left">Gekauft am:</span>
<?php        
$num = 0;
foreach ( $weine['wines_invoices'][$prod_ID] as $k => $v  ) {
$num += 1;
if ( $num%2 == 0 ) { // gerade
$p_open= 1;    
?>
<p class="bought"><span class="left"><a href="/weinraum/mw_invoices#inv_<?php echo $k ?>">
<?php echo $App::getHumanDate( $v['date'] ) ?>
</a></span>        
<?php
}
if ( $num%2 != 0 ) { // ungerade 
$p_open = 0;    
?>
<span class="right"><a href="/weinraum/mw_invoices#inv_<?php echo $k ?>">
<?php echo $App::getHumanDate( $v['date'] ) ?>
</a></span></p>        
<?php
} 
}
if ( $p_open == 1 ) { ?>
</p> 
<?php
}
}
?>    
</div>

<div class="clearer">&nbsp;</div>  
<div class="basket-line show-small">
<?php if($_anzPack > 0 ) { ?>
<div class="ifPack inBox">
<p class="nurDen"><span class="textPack">Oder nur diesen Wein:</span></p>
</div>
<div class="clearer">&nbsp;</div>  
<?php } 
if ($_fi_exists_4fl_v OR !$_fi_exists_bild) { ?>

<?php }
if (!$_fi_exists_4fl_v AND $_fi_exists_bild) { }?>
<div class=" <?php echo ($weine['wines'][$prod_ID]['no_stock'] > 0 )?'cart-box ':'cart-leer '; ?> wei_<?php echo  $prod_ID; ?>">
<?php 
// show cart button
if ($weine['wines'][$prod_ID]['no_stock'] > 0 ) {  ?>
<?php
if (isset($_SESSION['cart_art'][$weine['wines'][$prod_ID]['prodID']]['number']) AND $_SESSION['cart_art'][$weine['wines'][$prod_ID]['prodID']]['number'] == 0 ) { ?> 
<!-- a href="?weiEKZ[<?php echo $weine['wines'][$prod_ID]['prodID']; ?>]=1"> -->
<?php }  ?>
<div class="head-cart"></div>    
<div class="add_one  wei_<?php echo  $prod_ID ?> ">
<input type="hidden"  class="wein" name="hide_<?php echo  $prod_ID ?>_<?php echo $weine['wines'][$prod_ID]['price'] ?>_<?php echo $weine['wines'][$prod_ID]['content'] ?>" value="<?php echo (isset($_SESSION['cart_art'][$weine['wines'][$prod_ID]['prodID']]['number']) ? $_SESSION['cart_art'][$weine['wines'][$prod_ID]['prodID']]['number']:0); ?>" />
<div class="no block <?php echo !file_exists($abfr4_fl_v)?"quer":""; ?>"><span class="number">
<?php 
if ( isset($_SESSION['cart_art'][$weine['wines'][$prod_ID]['prodID']]['number']) AND $_SESSION['cart_art'][$weine['wines'][$prod_ID]['prodID']]['number'] > 0 ) {
echo $_SESSION['cart_art'][$weine['wines'][$prod_ID]['prodID']]['number']; 
}
if ( !isset($_SESSION['cart_art'][$weine['wines'][$prod_ID]['prodID']]['number']) OR $_SESSION['cart_art'][$weine['wines'][$prod_ID]['prodID']]['number'] == 0 ) { echo ("6"); } ?>
</span><span class="link-number" alt="weinraum - warenkorb" ></span>

<?php // die Zahlen werden in main-require per java eingefügt. sonst stehen im quelltext die zahlen und werden in google gerankt?>
<ul class="warenkorb small none craft wei_<?php echo  $prod_ID ?> ">
<li class="nix li_1 color0000000 bg_coloreeeee7"></li>   
<li class="nix li_2 color0000000 bg_coloreeeee7"></li>     
<li class="nix li_3 color0000000 bg_coloreeeee7"></li>     
<li class="nix li_4 color0000000 bg_coloreeeee7"></li>     
<li class="nix li_5 color0000000 bg_coloreeeee7"></li>   
<li class="nix li_6 color0000000 bg_coloreeeee7"></li>   
<li class="nix li_12 color0000000 bg_coloreeeee7"></li>   
<li class="nix li_18 color0000000 bg_coloreeeee7"></li>   
<li class="nix li_24 color0000000 bg_coloreeeee7"></li>    
<li class="input li_oder"></li>
<li class="input_val">
<input  class="input_val" name="name_<?php echo  $prod_ID ?>" type="tel"/></li>
</ul>
<div class="bu_in_ok none"><span class="small ">ok</span></div>
</div>

<?php // wenn in session, grünes feld mit Anzahl
if ( isset($_SESSION['cart_art'][$weine['wines'][$prod_ID]['prodID']]['number']) AND $_SESSION['cart_art'][$weine['wines'][$prod_ID]['prodID']]['number'] > 0 ) { 
$value_this =  $_SESSION['cart_art'][$weine['wines'][$prod_ID]['prodID']]['number']." Flaschen = ".$App::getMoneyValFormated($weine['wines'][$prod_ID]['price'] * $_SESSION['cart_art'][$weine['wines'][$prod_ID]['prodID']]['number']);
?>
<div class="text_basket add_bask bg_color477a16"><span class="txt quer wert"><?php echo $value_this ?>&nbsp;&euro;</span></div>
<?php 
}
// wenn NICHT in session, grünes feld mit Anzahl
if ( (isset($_SESSION['cart_art'][$weine['wines'][$prod_ID]['prodID']]['number']) AND $_SESSION['cart_art'][$weine['wines'][$prod_ID]['prodID']]['number'] == 0) OR  !isset($_SESSION['cart_art'][$weine['wines'][$prod_ID]['prodID']]['number']) ) { ?>
<div class="text_basket add_bask <?php echo $prod_ID  ?>"><span class="txt quer">In den Warenkorb</span></div>
<?php } ?>
</div>
<?php // }  if ($_SESSION['cart_art'][$weine['wines'][$prod_ID]['prodID']]['number'] == 0 ?>
<!-- </a> -->
<?php  } 
if ($weine['wines'][$prod_ID]['no_stock'] <= 0 ) { ?> 
<div class="add sold"><div class="add_bask no"><span class="number">0</span></div><div class="text_basket leer"><span class="txt quer"></span></div></div>
<?php
}
?>
</div>
<p class="val bottom"><span class="ang">Preis pro Flasche, inkl. MwST.
<?php   // die Versandkosten werden in main-require per java eingefügt. sonst stehen im quelltext die zahlen und werden in google gerankt
if ($weine['wines'][$prod_ID]['content'] > 0 ) {
echo(" - ".$App::getMoneyValFormated($weine['wines'][$prod_ID]['price']/$weine['wines'][$prod_ID]['content'])."&euro;/l") ;?>
<?php }
else {
echo(" ") ;?>
<?php } ?>
&nbsp;</span><span class="js versand"></span></p>

<?php
if ( $weine['wines'][$prod_ID]['content'] != "" AND $weine['wines'][$prod_ID]['content'] != 0) {
$unitPrice = $App::getMoneyValFormated($weine['wines'][$prod_ID]['price']/$weine['wines'][$prod_ID]['content']);
}
?>
<div class="max-number-line none <?php echo $prod_ID ?>"><span class="hint">Die maximale Anzahl Flaschen sind: <?php echo $weine['wines'][$prod_ID]['no_stock'] ?></span></div>     
<?php 
$dassollhierhin = 2;
if (isset($_SESSION['cart_art'][$weine['wines'][$prod_ID]['prodID']]['number']) AND $_SESSION['cart_art'][$weine['wines'][$prod_ID]['prodID']]['number'] > 0 AND $dassollhierhin == 1) {  ?>
<div class="teaser-merk">
<?php 
if ( !$_SESSION['watchlist'][$prod_ID] AND $_SESSION['customer']['id'] == "" )   { ?>
<a href="/weinraum/anmelden/merken/<?php echo("$indRow/$prod_ID");  ?>" class="whatch  <?php echo ("$indRow $prod_ID");  ?> ">auf den Merkzettel</a>
<?php }
if ( !$_SESSION['watchlist'][$prod_ID] AND $_SESSION['customer']['id'] != "" )   { ?>
<span  class="watch <?php echo $prod_ID  ?> ">auf den Merkzettel</span>
<?php }
if ( $_SESSION['watchlist'][$prod_ID])   { ?>
<span  class="green">gemerkt!</span>
<?php } ?></div>
<?php } ?>
<?php if (isset($_SESSION['cart_art'][$weine['wines'][$prod_ID]['prodID']]['number']) AND $_SESSION['cart_art'][$prod_ID]['number'] == 0  AND $dassollhierhin == 1 ) {  
if ($weine['wines'][$prod_ID]['no_stock'] > 0 ) {    ?>
<div class="teaser-merk">
<?php 
if ( !$_SESSION['watchlist'][$prod_ID]  AND $_SESSION['customer']['id'] == "" )   { ?>
<a href="/weinraum/anmelden/merken/<?php echo("$indRow/$prod_ID");  ?>" class="whatch <?php echo ("$indRow $prod_ID");  ?> ">auf den Merkzettel</a>
<?php }
if ( !$_SESSION['watchlist'][$prod_ID] AND $_SESSION['customer']['id'] != "" )   { ?>
<span  class="watch <?php echo $prod_ID  ?> ">auf den Merkzettel</span>    
<?php }
if ( $_SESSION['watchlist'][$prod_ID])   { ?>
<span  class="green">gemerkt!</span>
<?php } ?>
</div><?php }
if ($weine['wines'][$prod_ID]['no_stock'] == 0  AND !$_SESSION['informlist'][$prod_ID]  AND $_SESSION['customer']['id'] == "") {    ?>
<div class="teaser-merk"><a href="/weinraum/anmelden/inform/<?php echo("$indRow/$prod_ID");  ?>" class="inform <?php echo ("$indRow $prod_ID");  ?>">Informieren wenn verfügbar</a>             </div>
<?php }
if ($weine['wines'][$prod_ID]['no_stock'] == 0  AND !$_SESSION['informlist'][$prod_ID]   AND $_SESSION['customer']['id'] != "") {    ?>
<div class="teaser-merk"><span  class="inform <?php echo $prod_ID ?> ">Informieren wenn verfügbar</span></div>
<?php }
if ($weine['wines'][$prod_ID]['no_stock'] == 0  AND $_SESSION['informlist'][$prod_ID]  ) {    ?>
<div class="teaser-merk"><span  class="inform green ">Mail kommt wenn da!</span></div>
<?php }
}
?>
<div class="visible-xs-block li2top"><a href="#top"><span class="icon wr-arrow-up"></span></a></div>
</div>

</div>   
</div>    
<?php    
}
}

}
?>

<style {csp-style-nonce} >
.form-control {display: block;width: 100%;padding: .375rem .75rem;color: var(--bs-body-color);background-color: var(--bs-body-bg);background-clip: padding-box;border-radius: .375rem;transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;}
section {max-width: 1500px; clear: both; }
a {color: #2c292e !important;text-decoration: none !important;}
a:focus {color: #575451;text-decoration: none;outline: none;}
a:hover{color: #9B0011 !important;text-decoration: underline !important;}
b {font-weight: 400;}
.block { display: block !important; }
.none { display: none !important; }
.tableToggle { display: table !important;}

.bg_colorf3f3f2 { background-color:#f3f3f2 !important;}
.bg_color8C8C19 { background-color:#8C8C19 !important;}
.bg_coloreeeee7 { background-color:#ffffff !important;}
.bg_color9B0011 { background-color:#6f4c4c !important;}
.bg_color342a07 { background-color:#342a07 !important;}
.bg_color477a16 { background-color:#477a16 !important;}

.color0000000 { color:#000000 !important;}
.colorFFFFFF { color:#FFFFFF !important;}

div.top-desk div.office a.warenkorb div.wk { position: relative; width: 100%; height: 100%; }
.menue-switch-person .pers-konto .pers-liste li {margin: 0 15px 0 0; height: 19px;} 
.menue-switch-person .pers-konto ul.pers-liste {height: 28px;} 
.menue_wein_left.regionen { padding: 0 0 0 5px; }

@media(max-width:767px) {
#menue-switch-weine {margin: 5px auto 40px auto;border-radius: 2px;border-bottom: 1px solid #656262;padding: 0 10px 0 10px;background-color: #e4ded4;width: 90%;}
#menue-switch-winzer {margin: 5px auto 40px auto;border-radius: 2px;border-bottom: 1px solid #656262;padding: 0 10px 0 10px;background-color: #e4ded4;width: 90%;}
#menue-switch-regionen {margin: 5px auto 40px auto;border-radius: 2px;border-bottom: 1px solid #656262;padding: 0 10px 0 10px;background-color: #e4ded4;width: 90%;}
.menue-switch-person .pers-konto  { font: normal normal 400 14px/30px 'roboto', sans-serif !important; margin: 7px auto 10px auto;padding: 3px 0 0 9%;width: 95%;border-top: 1px solid #9B0011;border-bottom: 3px solid #9B0011;z-index: 2;background-color: #FFF;}
div.content-xs-pad{ padding: 0  15px 0 20px !important; margin: 25px auto 0 auto;}
div.weinraum-xs-pad{ padding: 0  0px 0 0px !important; margin: 25px auto 0 auto;}
}

@media(min-width:768px)  { 
div.content-xs-pad{ padding: 0  0px 0 0px !important; }
div.weinraum-xs-pad{ padding: 0  0px 0 0px !important; }
.menue-switch-person .pers-konto  { font: normal normal 400 12px/25px 'roboto', sans-serif; margin: -12px auto 10px auto;padding: 0;width: 760px;border-top: 1px solid #9B0011;border-bottom: 3px solid #9B0011;z-index: 2;background-color: #FFF;}
header div.pers-konto  { margin: -12px 0 35px 0;}
}
@media(min-width:991px)  { 
.menue-switch-person .pers-konto  { font: normal normal 400 15px/28px 'roboto', sans-serif; margin: -10px auto 10px auto;padding: 0 0 0 30px;width: 990px;border-top: 1px solid #9B0011;border-bottom: 3px solid #9B0011;z-index: 2;background-color: #FFF;}
}

 @media only screen and (max-width : 650px) {
h1 { font-size: 36px; }
.gp-head h1.index { font: normal normal 500 22px/32px 'Roboto Slab', serif; }
div.content-text { font: normal normal 300 14px/21px 'roboto', sans-serif; }
ul.warenkorb.cart {left: 40px !important; }
h1.weine {font: normal small-caps 800 18px/25px 'roboto', sans-serif !important;letter-spacing: 0.04em;color: #000 !important;}
}

@media only screen and (min-width : 651px) {
h1 {font-size: 32px; }
gp-head h1.index { font: normal normal 800 40px/42px 'roboto', sans-serif; }
div.content-text { font: normal normal 300 15px/23px 'roboto', sans-serif; letter-spacing: 0.03em;}
h1.weine {font: normal small-caps 800 19px/24px 'roboto', sans-serif !important;letter-spacing: 0.04em;color: #270404 !important;}
}

h2 {color: #202020 !important;font: normal normal 300 17px/21px 'roboto', sans-serif !important;letter-spacing: 0.0em !important;;vertical-align: top !important;}
div.content-text h2 {color: #000000 !important;font: normal normal 700 16px/21px 'roboto', sans-serif !important;margin: 0 0 9px 0 !important;}
div.content-text h3 {color: #000000 !important;font: normal normal 700 16px/21px 'roboto', sans-serif !important;margin: 0 0 9px 0 !important;}
h3 {color: #622019;font: normal normal 300 26px/35px 'Roboto Slab', serif;letter-spacing: .0em;margin: 65px 0 0 0;}
h4 { color: #515151; font: normal normal 300 16px/21px 'Roboto Slab', serif; letter-spacing: .0em;}
.clearer { clear: both; }
.men-left-filter-data span.button:hover {cursor: pointer;}
/* ----------- menue left   */
div.left-men-regionen.content { padding: 0 0 0 0px; }
.menue_wein_left .men-left-filter, .menue_wein_left .men-left-regionen { margin: 0 0 0 0px; padding: 0 0 0 0;}
.menue_wein_left .men-left-filter.hoch { height: 60px; z-index: 5;}
.menue_wein_left .men-left-filter.flach { height: 50px; }
.menue_wein_left .men-left-filter.flachflach { height: 36px; }
.menue_wein_left .men-left-filter-data { width: 90%; margin: 0 0 0 0px; padding: 0 0 0 0px; }
.menue_wein_left .men-left-filter p.title  {border-bottom: 1px dotted #9e9e9e;padding: 0 0px 35px 0;height: 36px;width: 80%;margin: 0 0 0 0px;}

@media(max-width:1020px) {
.menue_wein_left .men-left-filter p .txt, .menue_wein_left .men-left-regionen p .txt {font: normal normal 700 12px/32px 'roboto', sans-serif;}
h1 { font: normal normal 300 19px/32px 'Roboto Slab', sans-serif !important;letter-spacing: 0.0em;color: #000;margin: 0px 0 10px 0px;display: block;width: 100%;}
div.headLink  {font: normal italic 100 13px/31px 'georgia', serif !important;width: 100%;height: 29px; padding: 0 0 5px 8px;margin: 25px 0 25px 0;border-bottom: 1px dotted #f04e0b;}
div.headLink h1 {display: block; float: left; width: auto !important; margin: 0 12px 0 0;}
div.headLink span {display: block; float: right; color: #f04e0b;}
div.headLink span.txt {margin: 3px 0 0 0px;}
div.headLink span.ic {width: 10px; margin: 3px 4px 0 4px;}
div.headLink span.ic img {width: 100%;}
}
@media(min-width:1021px) {
h1 { font: normal normal 300 32px/46px 'Roboto Slab', sans-serif !important;letter-spacing: 0.0em;color: #000;margin: 0px 0 10px 0px;display: block;width: 100%;}
.menue_wein_left .men-left-filter p .txt, .menue_wein_left .men-left-regionen p .txt, .menue_wein_left .men-left-winzer p .txt {font: normal normal 500 13px/32px 'roboto', sans-serif;}
.menue_wein_left.regionen { padding: 0 0 0 15px !important; }
div.headLink  {font: normal italic 100 16px/31px 'georgia', serif !important;width: 100%;height: 45px; padding: 0 0 5px 8px;margin: 25px 0 25px 0;border-bottom: 1px dotted #f04e0b;}
div.headLink h1 {display: block; float: left; width: auto !important; margin: 0 12px 0 0;}
div.headLink span {display: block; float: right; color: #f04e0b;}
div.headLink span.txt {margin: 13px 0 0 0px;}
div.headLink span.ic {width: 14px; margin: 13px 35px 0 9px;}
div.headLink span.ic img {width: 100%;}
}

.left-men-winzer .m2-wine-categories p.cat-head-menue {margin: 0 0 0 0; font: normal normal 800 17px/32px 'roboto', sans-serif;letter-spacing: 0.0em;clear:both;}
.menue_wein_left .men-left-filter p .txt , .menue_wein_left .men-left-regionen p .txt, .menue_wein_left .men-left-winzer p .txt {display: block;float: left;color: #000000;text-align: left;margin: 5px 0px 0px 0px;padding: 0px 5px 1px 0px;}
.menue_wein_left .men-left-regionen.head p  { font: normal normal 800 17px/32px 'roboto', sans-serif;padding: 0 0px 35px 0;height: 36px;width: 80%;margin: 20px 0px 0px 0px; }
.menue_wein_left .men-left-filter p.text {font: normal normal 600 14px/32px 'roboto', sans-serif;text-transform: uppercase;color: #2c292e;letter-spacing: 0.04em;text-align: left;margin: 0px 0px 0px 0px;padding: 0px 5px 1px 20px;}
.menue_wein_left .men-left-filter p .icon, .menue_wein_left .men-left-regionen p .icon  {display: block;float: right;color: #FFF;text-align: right;margin: 11px 10px 0px 0px;}
.men-left-filter-data {margin: 0 0 30px 0px;}
.men-left-filter-data .filter-saved {margin: 0 0 30px 0px;background-color: #faf6e6;padding: 7px 0 0px 7px;}
.men-left-filter-data div.data { margin: 0 0 0 0;  padding: 0 0 0 0;}
.men-left-filter-data p.data { width: 99%; }
.men-left-filter-data.none, .men-left-filter-data .data.none { display: none; }
.men-left-filter-data .data .data span.button {display: block;float: right;margin: -7px 41px 0 0px;height: 25px;width: 12px; border: none;}
.men-left-filter-data .data .data span.button img {width: 100%;}
.men-left-filter-data .data .data span.button.re { margin: 0 0 0 5px; }
.men-left-filter-data .data .data span.val  {font: normal normal 400 12px/14px 'roboto', sans-serif;display: block;float: left;letter-spacing: 0.0em;color: #2b2b2b;margin: 0 0 0 -1px;padding: 5px 4px 1px 4px;min-height: 25px;max-width: calc(100% - 20px);}
.left-men-regionen ul  { padding: 0 0 0 10px; }
.left-men-regionen ul.second-menu  { padding: 0 0 0 0px; }
.left-men-regionen ul li.submenu-land a, .men-left-lexikon ul li.submenu-land a, .left-men-regionen ul li.submenu-land span {font: normal normal 700 17px/32px 'roboto', sans-serif;text-transform: uppercase;letter-spacing: 0.04em;display: block;color: #333333;margin: 25px 0 0 0;}
.men-left-lexikon ul.second-menu li a {display: block;font: 300 15px/23px roboto,sans-serif;text-transform: none;letter-spacing: .08em;margin: 0 0 0 15px;}
.men-left-lexikon ul {padding: 0 0 0 10px;}
.left-men-regionen ul li.submenu-land a.aktiv, .left-men-regionen li.submenu-winzer-land a.aktiv, .left-men-regionen li.submenu-winzer-land ul.second-winzer-menu li ul.winzer-menu a.aktiv {color: #9B0011 !important;}
.left-men-regionen ul.second-menu li  a {display: block;font: normal normal 300 15px/23px 'roboto', sans-serif;text-transform: none;letter-spacing: 0.08em;margin: 0 0 0 15px; }
.left-men-regionen ul li.submenu-winzer-land a, .left-men-regionen ul li.submenu-appellation span, .left-men-regionen ul li.submenu-winzer span {display: block;font: normal normal 800 17px/32px 'roboto', sans-serif;color: #333333;text-transform: uppercase;letter-spacing: 0.04em;margin: 25px 0 0 0;padding: 0 0 0 0 !important;}
.left-men-regionen ul.second-winzer-menu li  ul.winzer-menu  {margin: 0 0 0 0;   padding: 0 0 0 0 !important;}
.left-men-regionen ul.second-winzer-menu li  ul.winzer-menu a, .left-men-regionen ul.second-winzer-menu li.einWinReg a,  .left-men-regionen li.submenu-winzer-land ul.winzer-menu li.WinRegDir a  {display: block;font: normal normal 300 14px/32px 'roboto', sans-serif !important;color: #333333;letter-spacing: 0.02em;margin: 0 0 0 0;text-transform: none;}
div.rebenMenueLinks {margin: 15px 0 0 0;}
div.rebenMenueLinks p.letter {font: normal italic 100 45px/40px 'georgia', serif !important;margin: 25px 0 0 8px !important;padding: 0 0 12px 0;width: 90% !important;color: #f04e0b;border-bottom: 1px dotted #f04e0b; }
div.rebenMenueLinks p {font: normal normal 300 15px/23px 'roboto', sans-serif !important;letter-spacing: 0.08em;margin: 0px 0 0 25px; }


/* ------- END menue left-------- 
----------- TEASER*/

span.ac_match {font-weight: 500; color: #a30000;}
div.max-number-line {margin: 9px 0 5px 0;text-align: center;font-family: 'Arial', sans-serif;font-size: 15px;color: #3EAB1C; letter-spacing: 0.0em;display: none;}
div.max-number-line span {display: block;float: left;margin: 0 0 0 9px;}

.teaser {width: 94%;max-width: 390px;position: relative;margin: 22px auto 10px auto;-webkit-box-shadow: 1px 1px 5px 1px rgba(224, 224, 223,0.6);-moz-box-shadow: 1px 1px 5px 1px rgba(224, 224, 223,0.6);box-shadow: 1px 1px 5px 1px rgba(224, 224, 223,0.6);}
.teaser.warenK {-webkit-box-shadow: 1px 1px 5px 1px rgba(53, 103, 89,0.9);-moz-box-shadow: 1px 1px 5px 1px rgba(53, 103, 89,0.9);box-shadow: 1px 1px 5px 1px rgba(53, 103, 89,0.9);}
div.col-xs-12.add_wine div.col-xs-6 { padding: 0 0 0 0; }
div.col-xs-12.add_wine  { padding: 0 0 0 0; }
.wine-row {padding-left: 0;margin-left: -15px;}
.teaser * { margin: 0; }
.wine-teaser {padding: 0 !important; min-height: 560px;}
.teaser .info {margin-left: 0px;margin-top: 0px;font-size: 12px;display: block;min-height:37px;}
.wine-row-winzer { margin: 0 0 0 -5px;}
.teaser .teaser-merk {position: relative;margin: 0 auto 0px auto;width: 95%;text-align: center;height: 20px;background: none repeat scroll 0% 0% #F2F2F2;border-radius: 0px;padding: 0px 0 0px 0px;}
.teaser .teaser-merk a {font: normal normal 600 12px/15px 'roboto', sans-serif;text-align: center;color: #646967;display: block;background-color: #d7d8d8;height: 20px;padding: 3px 0 0 0;}
.teaser  .teaser-merk span {text-align: center;font-size: 11px;}
.teaser  .teaser-merk a:hover {color: #954C15;background-color: #93A694;}
.teaser .basket-line {clear: both; width: 90%; margin: 0 auto 0 auto;}
.teaser .basket-line .lower a.change {border-left: 1px solid #fff;border-right: 1px solid #fff;padding: 0 5px 0 5px;}
.teaser .basket-line .upper .icon {position: relative;margin: 0 auto 0 auto;}
.teaser .basket-line .upper input:hover {color: #FFF;}
.teaser .basket-line p.statt-price {display: block;width: 165px;margin: 15px auto 15px auto;height: 25px;text-align: right;}
.teaser .basket-line p.statt-price span.button {font: normal normal 800 15px/15px 'roboto', sans-serif;letter-spacing: 1px; color: #ffffff; background-color: #741111; border-top: 3px solid #741111;  border-bottom: 3px solid #741111; border-left: 5px solid #741111; border-right: 5px solid #741111;  border-top-left-radius: 11px; -webkit-border-top-left-radius: 11px; -moz-border-top-left-radius: 11px; border-top-right-radius: 3px; -webkit-border-top-right-radius: 3px; -moz-border-top-right-radius: 3px; border-bottom-left-radius: 2px; -webkit-border-bottom-left-radius: 2px; -moz-border-bottom-left-radius: 2px; border-bottom-right-radius: 11px; -webkit-border-bottom-right-radius: 11px; -moz-border-bottom-right-radius: 11px; height: 27px;width: 55px;margin: 1px 7px 0 10px;padding: 2px 0 3px 0;display: block;float: right;text-align: center;}
.teaser .basket-line p.statt-price span.text {font: normal normal 800 12px/12px 'roboto', sans-serif;letter-spacing: 1px;  color: #741111;margin: 17px 0 0px 0;display: block;float: right;}
.teaser .basket-line .add_one .no, .teaser .basket-line .add.sold .no {position: absolute;top: 1px;left: 1px;padding: 1px 0px 3px 0px;display:block;width: 42px;height: 36px;background-color: #FFF; border: 1px solid #dfe8d6;}
.teaser .basket-line .add_one .no.quer {width: 25%;}
.teaser .basket-line .add_one .no span.number {font: normal normal 500 15px/17px 'roboto', sans-serif;display: block;float: left;width: calc(100% - 19px);color: #000000;height: 30px;padding: 5px 0px 0 0px;text-align: right;position: relative;top: 2px;}
.teaser .basket-line .add_one div.no span.link-number {width: 19px;height: 19px;display: block;position: relative;top: 20px;left: 28px;  background: url(/_/img/link-rot.webp) no-repeat; background-size: contain; border: none;}
.teaser .basket-line .add_one div.no span.link-number:hover {width: 19px;height: 19px;display: block;position: relative;top: 20px;left: 28px;  background: url(/_/img/link-blau.webp) no-repeat; background-size: contain; border: none;}
ul.warenkorb.bottom { left: -130px;}
ul.warenkorb.small, ul.warenkorb.det, ul.warenkorb.wk-modal {position: absolute;list-style: outside none none;width: 270px;z-index: 100;margin: 0;padding: 0;-webkit-box-shadow: 1px 1px 8px 1px rgba(46,61,28,1);-moz-box-shadow: 1px 1px 8px 1px rgba(46,61,28,1);box-shadow: 1px 1px 8px 1px rgba(46,61,28,1);}


@media (max-width : 680px) {
ul.warenkorb.det {bottom: 40px;left: -94px;}
ul.warenkorb.wk-modal {bottom: 90px;left: 52px;}
ul.warenkorb.small {bottom: 40px;left: -60px;}
div.bu_in_ok.bottom {bottom: 9px;left: 186px;}
}
  
@media (min-width : 681px) {
ul.warenkorb.det {bottom: 40px;left: -94px;}
ul.warenkorb.wk-modal {bottom: 80px;left: 60px;}
ul.warenkorb.small {bottom: 40px;left: -32px;}
div.bu_in_ok.bottom {bottom: 9px;left: 186px;}
}
  
@media  (min-width : 720px) {
ul.warenkorb.wk-modal {bottom: 80px;left: 35px;}
}

@media (min-width : 820px) {
ul.warenkorb.wk-modal {bottom: 80px;left: 60px;}
div.bu_in_ok.wk-modal {bottom: 26px;left: 258px;}
}


@media(min-width : 950px) {
ul.warenkorb.wk-modal {bottom: 80px;left: 100px;}
div.bu_in_ok.wk-modal {bottom: 21px;left: 301px;}
}


@media (min-width : 1050px) {
ul.warenkorb.wk-modal {bottom: 80px;left: 120px;}
div.bu_in_ok.wk-modal {bottom: 55px;left: 383px;}
}

div.bu_in_ok {bottom: 29px;left: 235px;margin: 0;padding: 0;position: absolute;width: 60px;height: 60px;border-radius: 30px;z-index: 101;-webkit-box-shadow: 1px 1px 8px 1px rgba(46,61,28,1);-moz-box-shadow: 1px 1px 8px 1px rgba(46,61,28,1);box-shadow: 1px 1px 8px 1px rgba(46,61,28,1);display: none;background-color: #84c3df;}
div.bu_in_ok span {display: block;margin: 15px 0 0 15px;font-size: 24px;font-weight: 600;color: #FFF;}
div.bu_in_ok span.einok {display: block;margin: 17px 0 0 1px;font-size: 24px;font-weight: 600;color: #FFF;}
ul.warenkorb.small li, ul.warenkorb.det li, ul.warenkorb.wk-modal li {float: left;background-color: #ffffff;cursor: pointer;font: normal normal 600 20px/20px 'roboto', sans-serif;line-height: 2.8;width: 33.33%;display: table-cell;text-align: center;}
ul.warenkorb.small li.input, ul.warenkorb.det li.input, ul.warenkorb.wk-modal li.input {color: #fff;background-color: #7e816c;margin-left: 0;width: 50%;}
ul.warenkorb.small li.input_val, ul.warenkorb.det li.input_val, ul.warenkorb.wk-modal li.input_val {color: #fff;background-color: #7e816c;margin-left: 0;width: 50%;}
ul.warenkorb.small li.input_val input, ul.warenkorb.det li.input_val input, ul.warenkorb.wk-modal li.input_val input {width: 65%;font: normal normal 600 20px/18px 'roboto', sans-serif;color: #000;background-color: #ffffff;margin: 8px auto 7px auto;padding: 0 0 0 3px;border: 1px solid #FFF;}

div.modal-versandkosten {position: fixed;display: none;right: 0;width: 100%;top: 64px;height: 100%;background-color: hsla(70.88, 0%, 19%, 0.92);z-index: 100;overflow-y: initial !important;}

@media only screen and (max-width : 850px) {
div.modal-versandkosten div.box {position: fixed;margin: 5px auto 0 auto;left: 15%;top: 20%;height: 200px;width: 350px;background-color: hsla(70.88, 49.81%, 47.06%, 0.97);z-index: 100;overflow: auto;}
div.modal-versandkosten div.line{ width: 340px; height: 190px; margin: 5px auto auto auto;border: 1px solid #FFF;padding: 5px;}
/*div.modal-versandkosten {position: fixed;margin: 5px auto 0 auto;left: 5%;top: 10%;height: 200px;width: 300px;background-color: hsla(70.88, 49.81%, 47.06%, 0.97);z-index: 100;overflow: auto;}*/
}
@media only screen and (min-width : 851px) {
div.modal-versandkosten div.box {position: fixed;margin: 5px auto 0 auto;left: 45%;top: 25%;height: 200px;width: 350px;background-color: hsla(70.88, 49.81%, 47.06%, 0.97);z-index: 100;overflow: auto;}
div.modal-versandkosten div.line{ width: 340px; height: 190px; margin: 5px auto auto auto;border: 1px solid #FFF;padding: 5px;}
}

div.modal-versandkosten p.head{float: left;cursor: pointer;font: normal normal 500 14px/24px 'roboto', sans-serif;width: 100%;text-align: left; color:#FFF;}
div.modal-versandkosten p.text, div.modal-versandkosten p.schliessen{float: left;cursor: pointer;font: normal normal 400 14px/24px 'roboto', sans-serif;width: 100%;text-align: left; color:#FFF;}
div.modal-versandkosten p.schliessen{margin: 10px 0 19px 0; text-align: right;}
div.modal-versandkosten p.text span{float: left;}
div.modal-versandkosten p.text span.label{width: 165px; float: left;}
div.modal-versandkosten p.text span.value{width: 65px; text-align: right;}
div.modal-versandkosten p.text span.labelEU{width: 70px; float: left;}
div.modal-versandkosten p.text span.valueEU{width: 160px; text-align: right;}



.teaser div.cart-box:hover {cursor: pointer;}
.teaser div.cart-box, .teaser div.cart-leer {width: calc(100% - 5px);height: 33px;margin: 10px 0 15px 0;position: relative;}

.teaser div.cart-box.quer, .teaser div.cart-leer.quer {width: calc(78% - 5px);margin: 20px auto 15px auto;}
.teaser .basket-line.quer div.cart-box, .teaser .basket-line.quer div.cart-leer {width: 74%;height: 33px;margin: 0 0 15px 25px;background-color: #000;position: relative;}
.teaser div.cart-box span.txt, .teaser div.cart-leer span.txt{margin: 8px 5px 0 0px;display: block;width: 100%;color: #000;float: left;text-align: center;}
.teaser div.cart-box span.txt.wert{color: #FFF;}

.teaser div.cart-box img.warenkorb {max-width: 55px;width: calc(12%);margin: -2px 8px 0 0px;float: right;}
/*
.teaser div.cart-box.active {background-color: #d1d5c0;}
.teaser div.cart-box.active {background-color: #9B0011;}
*/
.teaser .basket-line .add_one .text_basket {position: absolute;color: #1c1a1a;text-align: left;height: 18px;left: 25%;}
.teaser .basket-line div.add_one .text_basket.add_bask {right: 0px;top: 1px;display:block;font: normal small-caps 600 15px/18px 'roboto', sans-serif;letter-spacing: 0.06em;width: calc(75%);height: 34px; background-color: #dfe8d6;}
.teaser .basket-line div.add.sold .text_basket.leer {position: absolute;right: 0px;top: 8px;display:block;font: italic normal 500 15px/18px 'roboto', sans-serif;color: #ddd;letter-spacing: 0.0em;width: calc(75%);}
.teaser .basket-line .add.sold .text_basket.leer span.txt{width: 90%;}
.teaser .basket-line .add.sold div.no span.number{font: normal normal 600 17px/17px 'roboto', sans-serif;display: block;float: left;width: calc(100% - 19px);color: #000000;height: 30px;padding: 5px 0px 0 0px;text-align: right;position: relative;top: 2px;}
.teaser .basket-line .add_one .text_basket.full {right: 10px;top: 23px;display:block;font-size: 16px;font-weight: 600;}
.teaser .lower a:hover { color: #C33838; }
.teaser a.litext {color: #000 !important;text-decoration: underline !important; }

.teaser .imagecontainer {display: block;margin: 8px;}
.teaser .imagecontainer img{margin: 0 auto 15px auto;width: 90%;}
.teaser .missing_img {	width: 100%;}
.teaser .Flaschencontainer {position: relative;float: left;width: 32%;}
.teaser .Flaschencontainer .flasche {height: 100%;}
.teaser .Flaschencontainer .flasche a {width: 100%;}
.teaser .Flaschencontainer img {height: 100%;margin: 0 6% 0 6%;}
.teaser .WeinDetails {float: left;width: 68%;}
.teaser .WeinDetails .angaben {height: 275px;}
.teaser .WeinDetails .angaben.quer {height: 188px;}

.teaser .WeinDetails .basket-line.show-small {height: 145px;}

.teaser .infoProd {margin-top: 8px;display: block;width: 100%;}
.teaser .infoProd p.producer {font: normal small-caps 300 16px/40px 'roboto', sans-serif !important;text-align: left;margin: 0 0 0 20px;color: #000;}
.teaser .infoProd p.val span { display: block; float: left; }
.teaser .infoProd p.val span.left { font: normal normal 300 13px/18px 'roboto', sans-serif !important;color: #000;letter-spacing: 1px;text-align: right;width: 25%;margin: 0 10px 0 0;}
.teaser .infoProd p.val span.right { font: normal normal 300 13px/18px 'roboto', sans-serif !important;color: #000;letter-spacing: 1px;text-align: right;margin: 0 0 0 0;}
p.val.bottom { font: normal normal 400 10px/14px 'roboto', sans-serif !important;color: #000;letter-spacing: 0.04em;text-align: right;margin: 0 0 0 0;}
p.val.bottom span.js.versand { float: right; } 
.teaser .infoProd p.val span.right.details { margin: 10px 6px 0 23px; }
.teaser .infoProd p.val span.detTrRight { margin: 25px 0 0 0; }
.teaser .infoProd p.val.detail span.left, .teaser .infoProd p.val.detail span.right { font: normal normal 400 10px/18px 'roboto', sans-serif !important;}
.teaser .infoProd p.val.preis { margin: 20px 0 0 0;}
.teaser .infoProd p.val.preis span.right { font: normal normal 800 18px/18px 'roboto', sans-serif !important;letter-spacing: 0.04em;}
.teaser .infoProd p.val.preis span.left { margin: 1px 10px 0 0;}
.teaser .infoProd p.val a span.right.details  { font: normal normal 300 12px/18px 'roboto', sans-serif !important;text-decoration: underline;}

.teaser .infoProd p.val { clear: both;height: 31px;}
.teaser .infoProd .detTrleft {width: calc(20% - 0px); }
.pers-konto .navbar-nav li a {position: relative;display: block;padding: 0 5px 0 5px;z-index: 2;}
.pers-konto .navbar-nav li.active a.anmelden {color: #9B0011 !important;}
.pers-konto .navbar-nav li a:hover, .pers-konto .navbar-nav li a.active { color: #9B0011 !important; }
.pers-konto .navbar-nav li a span.txt {display: block; float: left;}
.pers-konto .navbar-nav li a .detTrRight {display: block;float: left;border-left: 6px solid transparent;border-right: 6px solid transparent;border-top: 6px solid #5f7b8f;margin: 12px 13px 0 2px;}
.teaser .infoProd p.wein-quer { clear: both;height: 22px;margin: 0px 0px 0 12px;}
.teaser .infoProd p.wein-quer span { font: normal normal 500 14px/28px 'roboto', sans-serif !important;display: block;float:left;}
.teaser .infoProd h2 {font: normal normal 700 16px/32px 'roboto slab', sans-serif !important;letter-spacing: 0.0em;padding: 0px 4px 0 20px;margin: 0 0 0 0 !important;text-transform: none;vertical-align: top;height: 73px;}
.teaser .info p.producer{line-height: 1.1;}
.teaser.portrait .info {margin: 8px;font-size: 12px;display: block;width: auto;height: auto;border: none;text-transform: uppercase;}
.teaser.portrait .info p {font-size: 1.2em;line-height: 1.2em;text-transform: uppercase;}
.teaser.portrait .info h2 {font-size: 1.2em;line-height: 1.2em;}
.teaser h1 {margin: 10px 0 15px 0px;}
.teaser.portrait h2 {margin-top: 2px;text-transform: none;}

/* ----------- END WEIN   */

blockquote {border: none;}
blockquote {border: none;}
blockquote::before {color: #cc3300;}
blockquote::before {font-family: 'Georgia', sans-serif;width: 35px;height: 35px;top: 0px;left: 0px;display: block;padding: 0;content: '"';font-size: 42px;line-height: 42px;font-style: italic;font-weight: bold;text-align: center;position: absolute;}
/* -----------  WINEDETAILS  --------  */

.wein_details div.image {min-height: 300px;}
.wein_details .lower a.Detchange {border-left: 1px solid #fff;border-right: 1px solid #fff;padding: 0 5px 0 5px;}
.col-sm-6.grapes, .col-sm-6.grapes.add_cart {padding: 0; }
.wein_details  p.detail, .winedetails  p.grapes {margin: 0 0 10px 15px;} 
.wein_details p.price {clear: both;display: block;width: 100%;margin: 10px 0 10px 0;height: 45px;text-align: right;font: normal normal 400 40px/35px 'roboto', sans-serif;color: #2C292E;}
.wein_details p.statt-price {display: block;width: 100%;margin: 15px 0 15px 0;height: 25px;text-align: right;}
.wein_details p.statt-price span.button {font: normal normal 800 15px/15px 'roboto', sans-serif;letter-spacing: 1px; color: #ffffff; background-color: #741111; border-top: 3px solid #741111;  border-bottom: 3px solid #741111; border-left: 5px solid #741111; border-right: 5px solid #741111;  border-top-left-radius: 11px; -webkit-border-top-left-radius: 11px; -moz-border-top-left-radius: 11px; border-top-right-radius: 3px; -webkit-border-top-right-radius: 3px; -moz-border-top-right-radius: 3px; border-bottom-left-radius: 2px; -webkit-border-bottom-left-radius: 2px; -moz-border-bottom-left-radius: 2px; border-bottom-right-radius: 11px; -webkit-border-bottom-right-radius: 11px; -moz-border-bottom-right-radius: 11px; height: 27px;width: 55px;margin: 1px 7px 0 10px;padding: 2px 0 3px 0;display: block;float: right;text-align: center;}
.wein_details p.statt-price span.text {font: normal normal 800 12px/12px 'roboto', sans-serif;letter-spacing: 1px;  color: #741111;; margin: 17px 0 0px 0;display: block;float: right; }
.wein_details  .lower {position: relative;text-align: center;padding: 0px 0 10px 0px;}
.wein_details  .lower a {position: absolute;width: 210px;height: 25px;right: 0px;top: 11px;padding: 4px 0 0px 0px;font: normal normal 400 13px/16px 'roboto', sans-serif;background: none repeat scroll 0% 0% #E4E4E4;border-radius: 0px;}
.wein_details .line { border-top: 1px solid #DDD; margin: 0px 0 0px 0; }
.wein_details  .imagecontainer.small { margin: 25px 10px 0 0; }
.wein_details  .imagecontainer.small.etikett img { width: 100%; }
.wein_details  .imagecontainer.small.flasche img { max-height: 400px; }
.wein_details  .imagecontainer.small.flasche img.weinBack{ display: none; }
.wein_details  .imagecontainer.small.flasche .flasche-links { float: left; width: 65%; }
.wein_details  .imagecontainer.small.flasche .flasche-rechts { float: left;  width: 22%; }
.wein_details  .imagecontainer.small.flasche .flasche-rechts a { display: block; float: left;  width: 100%; margin: 0px 0 0 0;}
.wein_details h1 {font-size: 29px;border-bottom: 1px solid #ddd;padding: 0 0 0 15px;}
.wein_details .descr.large, p.detail-erzeuger {font: normal normal 300 16px/32px 'roboto', sans-serif;margin: 0 0 30px 0px;color: #000 !important;letter-spacing:0.08em;}
.wein_details p.recommend {font: normal normal 500 14px/24px 'roboto', sans-serif;margin: 7px 0 3px 17px;color: #000 !important;}
.wein_details .details { margin: 24px 0 0px 0px; }
p.row-det {height: auto;clear: both;}
p.row-det.type, p.row-det.preis {font: normal normal 500 14px/19px 'roboto', sans-serif;letter-spacing: 0.06em;height: auto;clear: both;margin: 0 0 0 0px;}
p.row-det.val {font: normal normal 300 14px/19px 'roboto', sans-serif;letter-spacing: 0.02em;height: auto;min-height: 28px;clear: both;margin: 0 0 0 0px;}
p.row-det.val.preis {font: normal normal 700 29px/19px 'roboto', sans-serif;letter-spacing: 0.02em;height: auto;min-height: 28px;clear: both;margin: 10px 0 0 0px;}
p.row-det.val a { color: #000 !important; font: normal normal 300 12px/18px 'roboto', sans-serif !important;text-decoration: underline !important;clear: both;}
div.passend h2 {width: 95%;font: normal normal 300 19px/36px 'roboto', sans-serif;color: #5CB73A;margin: 0px 0 0 0;padding: 0px 0 0 0;}
div.pass-reihe h3 { margin: 15px 0 0 0 !important; }
div.pass-reihe h3 a {display: block;height: 45px;font: normal normal 500 14px/26px 'roboto', sans-serif;color: #333333;}
div.pass-reihe img {width: 65%;}
.basket-merken {position: absolute;top: 60px;left:-35px;width: 120px;height: 26px;background: none repeat scroll 0% 0% #C1553F;background: none repeat scroll 0% 0% #D6DAC2;border-radius: 13px;padding: 3px 0 0 40px;}
.add_cart .addtocart  {position: relative;height: 175px;}
.add_cart.image .addtocart {height: 225px;   }
.add_cart .addtocart  p.statt-price  {height: 25px;    }  
.add_cart .addtocart  p.statt-price span.button {font: normal normal 800 15px/15px 'roboto', sans-serif;letter-spacing: 1px;color: #ffffff;background-color: #741111;border-top: 3px solid #741111;border-bottom: 3px solid #741111;border-left: 5px solid #741111;border-right: 5px solid #741111;border-top-left-radius: 11px;-webkit-border-top-left-radius: 11px;-moz-border-top-left-radius: 11px;border-top-right-radius: 3px;-webkit-border-top-right-radius: 3px;-moz-border-top-right-radius: 3px;border-bottom-left-radius: 2px;-webkit-border-bottom-left-radius: 2px;-moz-border-bottom-left-radius: 2px;border-bottom-right-radius: 11px;-webkit-border-bottom-right-radius: 11px;-moz-border-bottom-right-radius: 11px;height: 27px;width: 55px;margin: 1px 7px 0 10px;padding: 2px 0 3px 0;display: block;float: right;text-align: center;}
.add_cart .addtocart p.statt-price span.text {font: normal normal 800 12px/12px 'roboto', sans-serif;letter-spacing: 1px;color: #741111;margin: 17px 0 0px 0;display: block;float: right;}
.cart-row {width: 100%;height: 54px;border-radius: 10px;}
.cart-row .preis-details, .cart-row .preis-details a {margin: 15px 0 0 0;text-align: center;font: normal normal 500 12px/18px 'roboto', sans-serif;color: #f7ecc3;padding: 0px 5% 0 5%;}
.cart-row-left, .cart-row-right{height: 52px;}
.cart-row-left .price {height: 35px;width: 100%;text-align: right;color: #f7ecc3;letter-spacing: 0.0em;margin: 10px 0 0 0;}

@media(max-width:689px) {
.cart-row-left .price {     font: normal normal 800 23px/34px 'roboto', sans-serif;padding: 0px 9px 0 0; }
.cart-row { margin: 20px auto 0 auto !important; }
}

@media(min-width:690px) {
.cart-row-left .price {     font: normal normal 800 36px/40px 'roboto', sans-serif; }
.cart-row { margin: 30px 0 0 0px !important; }
}

.cart-row .preis-details {border-top: 1px solid #f7ecc3;padding: 5px 5% 0 5%;margin: 10px 5% 0 5%;}
.wein_details_wrapper .sep-winzer p.mehr, .sep-winzer p.mehr {font: normal normal 500 24px/40px 'roboto', sans-serif; letter-spacing: 0.04em;width: 100%; text-align: center; color: #1577ad;border-bottom: 1px dotted #7d0808;padding: 0 0 15px 0;}
.wein_details_wrapper .sep-winzer p.mehr, .sep-winzer p.mehr:hover {color: #7d0808;cursor: pointer;}
.wein_details_wrapper { padding: 0px 2% 0 4%;    }
.wein_details_wrapper p.sep-line-weine { width: 100%; text-align: center; border-top: 1px dotted #333;padding: 15px 0 0 0;}
.wein_details_wrapper p.sep-line-weine span.dot { font-size: 33px; color: #333; }
.wein_details_wrapper p.sep-line-weine span.dot.li { padding: 0 40px 0 0; }
.wein_details_wrapper p.sep-line-weine span.dot.re { padding: 0 0 0 40px; }
.wein_details_wrapper p.sep-line-weine span.txt { font: normal normal 500 36px/40px 'roboto', sans-serif; letter-spacing: 0.04em;color: #333;}
.wein_details_wrapper .content-winzer-feature h2.index {font: normal normal 600 36px/40px 'roboto', sans-serif; color: #622019;margin: 0 !important;padding: 0 0 15px 0;border-bottom: 1px solid #ddd;}
.wein_details_wrapper .content-winzer-feature p.weiter span.txt {font: normal normal 400 16px/40px 'roboto', sans-serif; }
.wein_details_wrapper .content-winzer-feature p.weiter span.nach-unten  {font-weight: 800;font-size: 15px; }
div.zeile_winzer p.letter {font: normal italic 100 45px/40px 'georgia', serif !important;width: 100%;padding: 0 0 5px 8px;margin: 25px 0 25px 0;color: #f04e0b;border-bottom: 1px dotted #f04e0b;}
div.zeile_winzer h3 {font: normal normal 300 15px/23px 'roboto', sans-serif !important;letter-spacing: 0.08em;margin: 0px 0 0 25px; }


div.modal-warenkorb p.head  {font: normal normal 500 18px/27px 'roboto', sans-serif !important;width: 100%;padding: 0 0 6px 0px;margin: 40px 0 25px 0;color: #9B0011;border-bottom: 1px dotted #9B0011;letter-spacing:0.08em;}
p.head a {font: normal normal 100 15px/40px 'roboto', sans-serif !important;width: 100%;padding: 0 0 0px 8px;margin: 5px 0 5px 0;}
p.region a {width: 100%;padding: 0 0 5px 0px;}
p.region a.reg { font: normal italic 600 15px/40px 'georgia', serif !important;margin: 0px 25px 0px 0; }
p.region a.app { font: normal normal 400 13px/40px 'roboto', sans-serif !important;margin: 0px 15px 0px 0; }

div.winzer-m-dat h3.winzer a {color: #515C53 !important;letter-spacing: 0.04em;}
div.add_cart { position: relative; }
div.detail-cart-box {height: 36px;left: 0px;top: 10px;border-radius: 3px;position: absolute;}
div.detail-cart-box.image {width: 85%;min-width: 190px;  }
div.detail-cart-box .add_one .no{position: absolute;top: 20px;left: 10px;padding: 2px 0px 3px 0px;display:block;width: 56px;height: 25px;}
div.detail-cart-box .add_one .text_basket {position: absolute;left: 61px;color: #000;text-align: center;width: 80%;height: 18px;}
div.detail-cart-box .add_one .text_basket.add_bask {font: normal normal 600 17px/17px 'roboto', sans-serif;right: 0px;top: 7px;padding: 7px 0 0 0;display: block;font-size: 17px;width: calc(100% - 59px);height: 34px;background-color: #dfe8d6;}
div.detail-cart-box span.txt.wert{color: #FFF;}

div.detail-cart-box .text_basket.full a { color: #FFF; margin: -2px 0 0 0;display: block; }
div.detail-cart-box  .text_basket span.icon {display: block;position: relative;top: 0px;}
div.detail-cart-box .icon {position: relative;margin: 0 auto 5px auto;font: normal small-caps 500 15px/18px 'roboto', sans-serif;}
div.detail-cart-box .icon img {width: 21px;margin: -3px 0 0 0;}
div.detail-cart-box .add_one .no {position: absolute;top: 7px;left: 5px;padding: 2px 0px 3px 0px;display: block;width: 34px;height: 34px;background-color: #FFF;border: 1px solid #dfe8d6;}
div.detail-cart-box .add_one .no  span.number{font: normal normal 600 17px/17px 'roboto', sans-serif;display: block;float: left;width: 35px;height: 30px;padding: 7px 4px 0 0px;text-align: center;position: relative;top: -2px;}
div.detail-cart-box .add_one div.no span.link-number {width: 19px;height: 19px;display: block;position: relative;top: 20px;left: 28px;  background: url(/_/img/link-rot.webp) no-repeat; background-size: contain; border: none;}
div.detail-cart-box .add_one div.no span.link-number:hover {width: 19px;height: 19px;display: block;position: relative;top: 20px;left: 28px;  background: url(/_/img/link-blau.webp) no-repeat; background-size: contain; border: none;}

div.detail-cart-box .add_one .no span.icon {display: block;float: left;margin: 4px 0 0 2px;}
div.detail-cart-box  input:hover {color: #88A80D;}

.addtocart .detail-merk {position: absolute;right: 0px;top: 160px;text-align: center;height: 20px;background: none repeat scroll 0% 0% #F2F2F2;border-radius: 0px;padding: 0px 0 0px 0px;}

 @media only screen and (max-width : 940px) {
.addtocart .detail-merk.image {top: 215px;width: 85%;min-width: 190px;}
.addtocart .detail-merk {width: 100%;}
div.detail-cart-box {width: 100%;}
div.winzer-m-dat h3.winzer a {font: normal normal 600 19px/19px 'roboto', sans-serif;}
}
 
 @media only screen and (min-width : 941px) {
.addtocart .detail-merk.image {top: 215px;width: 85%;min-width: 190px;}
.addtocart .detail-merk {width: 70%;} 
div.detail-cart-box {width: 70%;}
div.winzer-m-dat h3.winzer a {font: normal normal 600 23px/23px 'roboto', sans-serif;}
}
.addtocart .detail-merk a {font: normal normal 600 12px/15px 'roboto', sans-serif;text-align: center;color: #FFF;display: block;background-color: #718984;height: 20px;padding: 3px 0 0 0;}
/* ----------- END WINEDETAILS  --------  */
p.vk_land {clear: both; }
p.vk_land span {display: block; float: left; }
p.vk_land span.land {text-align: left; width: 125px; margin: 0 0 0 25px;}
p.vk_land span.wert {text-align: right; width: 95px; }
/* ----------------- content -------------------------  */

a.litext { color: #1577ad !important; }
div.content-text-image-image-center img {width: 100%; margin: 5px 5px 5px 0px; }
div.content-text-image-image-left img  {width: 50%; margin: 5px 5px 5px 0px; }
div.winzer-m-dat h3.lexikon a {font: normal normal 400 16px/24px 'roboto', sans-serif;color: #515C53;letter-spacing: 0.02em;}
.content-text-image-image-center div {margin: 5px 5px 20px 23px;text-align: left;position: relative;}
.content-text-image-image-wrapper div {font-size: 14px;font-weight: normal;letter-spacing: 0.0em;line-height: 19px;color: #878984;margin:0 0 15px 0;}
div.rebenMenueLinks p, div.zeile_winzer p {font: 300 15px/23px roboto,sans-serif!important;letter-spacing: .08em;margin: 0 0 0 18px;}

@media  (min-width : 768px) {
div.content-ci4 div.content-wrapper { margin: 35px auto 0 auto;}
}

@media only screen and (max-width:779px) {
.content-wrapper {margin: 0}
div.content-text {margin-left: 0; padding-left: 0;}
div.content-text  blockquote {padding: 0px 0 5px 20px; margin: 0 0 0 35px;} 
blockquote::before { left: 0px;}
div.content-text  p {margin: 9px 0 0 0; }
div.content-text-image-content, div.content content-text  {margin: 15px 0px 12px 0;}
div.content-text-image-content h2, div.content-text h2 {font: 500 25px/40px roboto,sans-serif!important;letter-spacing: .08em; margin: 9px 0px 12px 0;}
div.content-text-image-content h3, div.content-text h3 {font: 400 15px/40px roboto,sans-serif!important;letter-spacing: .08em; margin: 9px 0px 12px 0;}

.teaser-post-frame {padding: 2px 5px 12px 0;margin: 0 0 0 5px}
div.content-text  p {margin: 9px 0 0 0%; }
div.content-text-quote  {margin: 30px 0 40px 10%;padding: 0 0 0 2%;border-left: 1px solid #000;}    
div.content-text-quote   p.ind {font: normal bold 65px/21px 'Georgia',  serif;color: #24a3d1;margin: 9px 0 0 -14%; }
div.content-text-quote   p:not(.ind) {font: italic normal 16px/29px 'Georgia',  serif;margin: -10px 0 0 2%; }
div.content-text-quote p.testimonial {font: normal normal 14px/32px 'Georgia',  serif !important;color: #57715c;margin: 24px 0 0 -61px; text-align: right;}
div.content-text-quote h4 {  font: italic normal 12px/15px 'Georgia',  serif;color: #5D6B5A;width: 545px;}
div.content-text-image-content, div.content content-text {font: normal normal 300 14px/21px 'roboto', sans-serif;letter-spacing: 0.08em;margin: 35px 0px 12px 0;}
}

@media only screen and (min-width:780px) {
.content-wrapper {margin: 32px 0 0 0;}
div.content-text {margin-left: 6px; padding-left: 0;}
div.content-text  blockquote {    padding: 0px 0 5px 2%;margin: 0 0 0 0;} 
blockquote::before { left: -6%;}
div.content-text  p {margin: 9px 0 0 0%; }
div.content-text-quote  {margin: 35px 0 50px 1%;padding: 0 0 0 2%;border-left: 1px solid #89411a;}
div.content-text-quote p.ind {font: normal bold 65px/21px 'Georgia',  serif;color: #24a3d1; margin: 24px 0 0 -61px; }
div.content-text-quote p.testimonial {font: normal normal 14px/32px 'Georgia',  serif !important;color: #57715c;margin: 24px 0 0 55px !important; text-align: left;}
div.content-text-quote p:not(.ind) {font: italic normal 19px/32px 'Georgia',  serif;margin: -10px 0 0 0%; }
div.content-text-quote h4 { font: italic normal 12px/15px 'Georgia',  serif;color: #5D6B5A;width: 545px;}
.teaser-post-frame {padding: 2px 90px 12px 10%;margin: 0 0 0 25px;background: url(/_/img/gepunktete_linie.png) 12% top repeat-y}
div.content-text-image-content, div.content content-text {font: normal normal 300 15px/23px 'roboto', sans-serif;letter-spacing: 0.03em;margin: 0px 0px 12px 0;}
div.content-text-image-content h2, div.content-text h2 {font: 500 32px/40px roboto,sans-serif!important;letter-spacing: .08em; margin: 9px 0px 12px 0;}
div.content-text-image-content h3, div.content-text h3 {font: 400 16px/40px roboto,sans-serif!important;letter-spacing: .08em; margin: 9px 0px 12px 0;}
}
.ui-menu .ui-menu-item  {font: normal normal 400 12px/18px 'roboto', Arial,  sans-serif;}
.ui-menu .ui-menu-item-wrapper {font: normal normal 400 12px/18px 'roboto', Arial,  sans-serif;color: #575451;}
div.weiI-ZeilSeiM span.seitTxt {display: block;float: left;width: 95px;margin: 0px 0 0 0;}
div.weiI-ZeilSeiM {clear: both;margin: 25px 0 0 0;height: 75px;line-height: 27px;padding: 15px 0  0 5px;background-color: #eee;}
div.weiI-ZeilSeiM a {display: block;float: left;width: 25px;text-align: right;margin: 0px 15px 0 0;}
div.weiI-ZeilSeiM span.seitTxt {display: block;float: left;width: 95px;margin: 0px 0 0 0;}
div.weiI-ZeilSeiM span.seite, div.weiI-ZeilSeiM a {font-size: 14px;font-weight: bold;color: #A20D0D;}
div.weiI-ZeilSeiM span.seitTxt {display: block; float: left; width: 95px; margin: 0px 0 0 0;}
div.weiI-ZeilSeiM span.seite, div.weiI-ZeilSeiM a {display: block; float: left; width: 25px;  text-align: right; margin: 0px 15px 0 0;}
div.weiI-ZeilSeiM span.seite {font-size: 14px; font-weight: bold; color: #A20D0D;}
span.no_matches { color: #9B0011; }
span.no_matches { color: #2c292e; }

.add_produkt { display: none; }
.menue-landscape div.kopf div.kopfZeile span.wei-fil { color: #268da7; }
.menue-landscape div.kopf div.kopfZeile img { width: 45px; }
.menue-landscape div.links div {margin: -8px 0 0 0;width: 20%;display: block;float: left;color: #270404;cursor: pointer;}
.menue-landscape div.links div:hover { text-decoration: underline; }
.modal-suche .text, .modal-warenkorb .text {font: normal normal 500 23px/26px 'roboto', sans-serif;color: #270404;margin: 0px auto 0 auto;text-align: center;padding: 9px 0 0 0;-webkit-box-shadow: 1.8125px 1px 33px 2px rgba(247, 247, 247, 0.77);-moz-box-shadow: 1.8125px 1px 33px 2px rgba(247, 247, 247, 0.77);box-shadow: 1.8125px 1px 33px 2px rgba(247, 247, 247, 0.77);border: 1px solid #a9b5be;border-radius: 6px;}
.modal-suche .text {background-color: #ded9cc;}
.modal-warenkorb .text {background-color: #ffffff;}
.modal-warenkorb {position: fixed;display: none;right: 0;width: 100%;top: 64px;height: 100%;background-color: hsla(70.88, 0%, 19%, 0.92);z-index: 100;overflow-y: initial !important} 
.modal-warenkorb.kasse {display: block !important;}

@media only screen and (max-width : 850px) {
div.wein-head-text {width: 90%; margin: 0 auto 0 auto;}
.modal-warenkorb .text {width: 100%;position: absolute;top: 8px;overflow-y: auto;height: 90vh;padding: 0;}
.wein_details  .imagecontainer.small.flasche .flasche-rechts { display: none; }

}
@media only screen and (min-width : 851px) {
.modal-warenkorb .text {width: 80%;max-width: 625px;position: absolute;right: 20%;top: 8px;overflow-y: auto;height: 80vh;padding: 0;}
}

.filter { width: 95%; height: 355px;  margin: -6px auto 5px auto;}
.range-content { width: 100%; height: 192px; margin: 10px auto 30px 0; }
.menue-landscape div.line  { clear: both; width: 90%; height:28px; margin: -8px 0 0 0; }
.menue-landscape div.links { width: 100%; height:45px; margin: 0px auto 0 auto; }

p.schliessen {margin: 0 10px 0 0; width: 95%; height: 30px;}
p.schliessen img {float: right;}
p.schliessen.d-block {float: right;margin: -28px 10px 0 0; width: 10px; height: 30px;}
p.schliessen.d-block img.schliessen {position: relative;width: 100%; margin: 2px 0 0 0;}

div.boxSuche {margin: 0; }
div.suinput {position: relative;}

div.linien {width: 82%; margin: 0 auto 0 auto; height: 20px;position: relative;}
div.linien div.linieFil {display: block; float: left; height: 10px; width: 25%;}
div.linien div.runterOben {height: 10px; width: calc(50% - 0px);position: absolute;top:0px;}
div.linien div.runterUnten {height: 10px; width: calc(50% - 0px);position: absolute;top:10px;}
div.linien div.linieFil.left {border-left: 1px solid #b3b27e;}
div.linien div.linieFil.bottom {border-bottom: 1px solid #b3b27e;}
div.linien div.linieFil.right {border-right: 1px solid #b3b27e;}

.filter .range-content p.button {width:285px; margin: 0 auto 20px auto; height: 25px;}
.filter .range-content p.button span {display:block; width: 85px; height: 25px;float: left; background-color: #c2c2c2;font: normal small-caps 500 12px/26px 'roboto', sans-serif;letter-spacing: 0.3em;}
.filter .range-content p.button span.aktiv {background-color: #b3b27e;}
.filter .range-content p.button span {margin: 0 15px 0 0;}
.filter .range-content p.button span.links {width: 105px; margin: 0 50px 0 10px;}
.filter .range-content p.button span.rechts {width: 105px; }
.filter .range-content p.button span.linksDrei { margin: 0 15px 0 0;}
.filter .range-content p.button span.mitte {margin: 0 15px 0 0;}
.filter .range-content p.button span.rechtsDrei {margin: 0;}

div.head.filterbox {clear: both;}
div.filter-switch { display: block; width: 80%; margin: 0 auto 0 auto; }

@media (max-height: 699px) {
.modal-suche {position: fixed;margin: 5px auto 0 auto;left: 0;width: 100%;top: 0;height: 100%;background-color: hsla(70.88, 0%, 99.5%, 0.87);z-index: 100;overflow:auto;}
p.head-susu { font: normal normal 500 12px/20px 'roboto', sans-serif;width: 95%;margin: 0px 10% 0px 0;padding: 0px 0 0 5%;text-align: left;}
div.suche input {position: absolute;left: 5%;}
div.suche img.suche {position: absolute;left: calc(5% + 195px);top: 24px;}
p.head-sufi { font: normal normal 500 12px/20px 'roboto', sans-serif;width: 95%;margin: 30px 0 0 5%;padding: 10px 12% 0 0;text-align: right;border-top: 1px solid #cbcbcb;}
}
@media (min-height: 700px) {
.modal-suche {position: fixed;margin: 45px auto 0 auto;left: 0;width: 100%;top: 0;height: 100%;background-color: hsla(70.88, 0%, 99.5%, 0.87);z-index: 100;overflow:auto;}
p.head-susu { font: normal normal 500 12px/20px 'roboto', sans-serif;width: 90%;margin: 30px 10% 5px 0;padding: 10px 0 0 10%;text-align: left;border-top: 1px solid #cbcbcb;}
div.suche input {position: absolute;left: 10%;}
div.suche img.suche {position: absolute;left: calc(10% + 195px);top: 42px;}
p.head-sufi { font: normal normal 500 12px/20px 'roboto', sans-serif;width: 90%;margin: 30px 0 0 10%;padding: 10px 10% 0 0;text-align: right;border-top: 1px solid #cbcbcb;}
}

/*.ui-widget.ui-widget-content { top:10px !important; left: 0 !important;}*/

.modal-suche img  { width: 18px;}
.modal-suche .suche input::placeholder {color: #270404;opacity: 1;}
.modal-suche .suche input {font: normal normal 400 12px/20px 'roboto', sans-serif;background-color: #e6e6e6;margin: 0px 0px 0 0px;padding: 12px 4px 7px 10px;height: 25px;width: 175px;letter-spacing: 0em;color: #000000 !important;}
.modal-warenkorb .text p.schliessen {font: normal normal 500 12px/20px 'roboto', sans-serif;background-color: hsla(46.63, 0%, 98%, 0.91);width: 100%;text-align: right;padding: 0 15px 0 0;margin: 0 !important;}
.modal-suche .text p.schliessen:hover, .modal-warenkorb .text p.schliessen:hover,
div.modal-warenkorb div.text div.basketVals div.zurueck p,
div.modal-warenkorb div.text div.footer.warenkorb div.zeile div.zurueck p {cursor: pointer;}
p.desc { margin: 0 0 5px 0; height: 75px;}
.sel-suche { width: 75%; margin: 0 auto;}
div.spacer { height: 5px;}
label { font: normal normal 500 14px/16px 'roboto', sans-serif;letter-spacing: 0.04em;; }
div.range-content select {margin: 5px auto 10px auto;width: 80%;height: 25px;background-color: #b3b27e !important;color: #252b09;padding: 0px 0 0px 10px;border-radius:2px;}
.menue-landscape div.line  { clear: both; width: 90%; height:28px; margin: -8px 0 0 0; }
.menue-landscape div.links { width: 100%; height:50px; margin: 0px auto 0 auto;border-top: 1px solid #ccc1a1;padding: 5px 0 0 0; }
.menue-landscape div.links div.linkBox { height:50px; background-color: #e6e6e6; width:18%; }
.menue-landscape div.links div.linkBox { margin: 0 2.5% 0 0;}
.menue-landscape div.links div.linkBox.ende { margin: 0 0 0 0;}
.menue-landscape div.links div.linkBox.aktiv { background-color: #e4ded4; }
.menue-landscape div.links div.linkBox.bear { background-color: #b3b27e; }
.menue-landscape div.kopf  {margin: 15px 0 19px 0; height: 80px;}
.menue-landscape div.kopf p.schliessen {font: normal normal 400 12px/16px 'roboto', sans-serif;color: #FFFFFF;letter-spacing: 0.08em;margin: 10px 10% 0 0; text-align: right;background-color: #9B0011;width: 138px;float: right;padding: 7px 7px 0 0;}


@media only screen and (max-width : 540px) {
.menue-landscape div.kopf div.kopfZeile div p {font: normal normal 500 11px/14px 'roboto', sans-serif;letter-spacing: 0.04em;}

@media (max-height: 399px) {
.menue-landscape div.kopf div.kopfZeile div p {font: normal normal 500 14px/16px 'roboto', sans-serif;letter-spacing: 0.04em;}
.range-content { width: 100%; height: 192px; margin: 10px auto 30px auto; }
.menue-landscape div.kopf p.wei {font: normal normal 400 12px/16px 'roboto', sans-serif;letter-spacing: 0.08em;margin: 0 0 0 0; width: 90%; text-align: right;}
.menue-landscape div.links div.linkBox p.oben img {width: 6px;margin: 0 0 0 2px;}
.menue-landscape div.links div.linkBox p.oben {font: normal small-caps 700 12px/16px 'roboto', sans-serif;letter-spacing: 0.2em;margin: 0 0 0 0;}
.menue-landscape div.links div.linkBox p.unten {font: normal normal 400 6px/11px 'roboto', sans-serif;letter-spacing: 0.08em;margin: 3px 0 0 0;}
.menue-landscape {position: relative;}

@media (hover: hover) {.filter { width: 95%; height: 355px;  margin: -6px auto 5px auto;}}
@media (hover: none) {.filter { width: calc(100% - 15px); height: 320px; margin: -10px auto 5px 15px; }}
}
@media (min-height: 400px) {
.menue-landscape div.kopf div.kopfZeile div p {font: normal normal 500 12px/16px 'roboto', sans-serif;letter-spacing: 0.04em;}
.range-content { width: 100%; height: 192px; margin: 10px auto 30px auto; }
.menue-landscape div.kopf p.wei {font: normal normal 400 12px/16px 'roboto', sans-serif;letter-spacing: 0.08em;margin: 0 0 0 0; width: 90%; text-align: right;}
.menue-landscape div.links div.linkBox p.oben img {width: 6px;margin: 0 0 0 1px;}
.menue-landscape div.links div.linkBox p.oben {font: normal small-caps 400 10px/16px 'roboto', sans-serif;letter-spacing: 0.2em;margin: 0 0 0 0;}
.menue-landscape div.links div.linkBox p.unten {font: normal normal 400 7px/11px 'roboto', sans-serif;letter-spacing: 0.08em;margin: 3px 0 0 0;}
.menue-landscape {position: relative;}
}
@media (min-height: 600px) {
.menue-landscape div.kopf div.kopfZeile div p {font: normal normal 500 12px/16px 'roboto', sans-serif;letter-spacing: 0.04em;}
.range-content { width: 100%; height: 192px; margin: 10px auto 30px auto; }
.menue-landscape div.kopf  {margin: 15px 0 19px 0;}
.menue-landscape div.kopf p.wei {font: normal normal 400 12px/16px 'roboto', sans-serif;letter-spacing: 0.08em;margin: 0 0 0 0; width: 90%; text-align: right;}
.menue-landscape div.links div.linkBox p.oben img {width: 6px;margin: 0 0 0 1px;}
.menue-landscape div.links div.linkBox p.oben {font: normal small-caps 400 14px/16px 'roboto', sans-serif;letter-spacing: 0.08em;margin: 0 0 0 0;}
.menue-landscape div.links div.linkBox p.unten {font: normal normal 400 7px/11px 'roboto', sans-serif;letter-spacing: 0.08em;margin: 3px 0 0 0;}
.menue-landscape {position: relative;}

@media (hover: none) {.filter { width: 95%; height: 415px; margin: -5px auto 15px auto;}}
@media (hover: hover) {.filter { width: 95%; height: 355px;  margin: -6px auto 5px auto;}}
}
}

@media only screen and (min-width : 541px) {
.menue-landscape div.kopf div.kopfZeile div p {font: normal normal 500 14px/16px 'roboto', sans-serif;letter-spacing: 0.04em;}

@media (max-height: 399px) {
.range-content { width: 90%; height: 192px; margin: 20px auto 30px auto;  }
.menue-landscape div.kopf  {margin: 15px 0 19px 0;}
.menue-landscape div.kopf p.wei {font: normal normal 400 12px/16px 'roboto', sans-serif;letter-spacing: 0.08em;margin: 0 0 0 0; width: 90%; text-align: right;}
.menue-landscape div.links div.linkBox p.oben img {width: 9px;margin: 0 0 0 7px;}
.menue-landscape div.links div.linkBox p.oben {font: normal small-caps 400 12px/16px 'roboto', sans-serif;letter-spacing: 0.3em;margin: 0 0 0 0;}
.menue-landscape div.links div.linkBox p.unten {font: normal normal 400 9px/11px 'roboto', sans-serif;letter-spacing: 0.3em;margin: 3px 0 0 0;}
.menue-landscape {position: relative;}

@media (hover: hover) {.filter { width: 95%; height: 355px;  margin: -6px auto 5px auto;}}
@media (hover: none) { .filter { width: calc(100% - 15px); height: 400px; margin: -15px auto 5px 15px;}
}
}

@media (min-height: 400px) {
.range-content { width: 90%; height: 192px; margin: 20px auto 30px auto;}
.menue-landscape div.kopf  {margin: 15px 0 19px 0;}
.menue-landscape div.kopf p.wei {font: normal normal 400 12px/16px 'roboto', sans-serif;letter-spacing: 0.08em;margin: 0 0 0 0; width: 90%; text-align: right;}
.menue-landscape div.links div.linkBox p.oben img {width: 9px;margin: 0 0 0 7px;}
.menue-landscape div.links div.linkBox p.oben {font: normal small-caps 400 13px/13px 'roboto', sans-serif;letter-spacing: 0.2em;margin: 5px 0 0 0;}
.menue-landscape div.links div.linkBox p.unten {font: normal normal 400 9px/13px 'roboto', sans-serif;letter-spacing: 0.08em;margin: 3px 0 0 0;}
.menue-landscape {position: relative;}

@media (hover: none) {.filter { width: calc(100% - 15px); height: 320px; margin: -15px auto 5px 15px; }}
@media (hover: hover) {.filter { width: 95%; height: 355px;  margin: -6px auto 5px auto;}}
}
}
@media only screen and (min-width : 1050px) {
.menue-landscape div.links div.linkBox p.oben {font: normal small-caps 400 17px/21px 'roboto', sans-serif;letter-spacing: 0.2em;margin: 5px 0 0 0;}
.menue-landscape div.links div.linkBox p.unten {font: normal normal 400 11px/18px 'roboto', sans-serif;letter-spacing: 0.08em;margin: 3px 0 0 0;}
}

.slidecontainer { width: 100%; }
.slider {-webkit-appearance: none;width: 95%;height: 15px;border-radius: 5px;background: #b3b27e;outline: none;opacity: 0.7;-webkit-transition: .2s;transition: opacity .2s;}
.slider:hover { opacity: 1;}
.slider::-webkit-slider-thumb { -webkit-appearance: none;appearance: none;width: 25px;height: 25px;border-radius: 50%;background: #9B0011;cursor: pointer;}
.slider::-moz-range-thumb {width: 25px;height: 25px;border-radius: 50%;background: #04AA6D;cursor: pointer;}
p.sl_labels {width: 100%;font: normal normal 400 13px/25px 'roboto', sans-serif;letter-spacing: 0.0em;}
p.sl_labels span { display: block; float: left; width: 6.1%;margin: 0 12.5% 0 0;}
p.sl_labels span.le { display: block; float: left; width: 6.1%;margin: 0 0 0 0;}

.content.passend div.ads-row   { padding: 0; margin: 35px 0 0 -11px;}
  
div.inhalt-direkt  p.traube {  font: normal normal 300 16px/30px 'roboto', sans-serif; color: #333 !important;}
div.logo-wein-rund img { width: 125px;}
div.winzer div.logo-winzer img { width: 245px;}
div.winzer div.logo-winzer p { margin: 15px 0 0 0;}

* {list-style-type: none;margin: 0;}
* {-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;}
body {font: normal normal 300 16px/28px 'roboto', sans-serif !important;font-display: auto !important;letter-spacing: .08em !important;color: #242125 !important;outline: 0;hyphens: auto;}
.container-fluid {padding: 0 !important; }
picture.img-responsive img { max-width: 100%; height: auto;}
div.home-head {width: 100%; position: relative; }        
div.linie { border-top: 2px solid #6f4c4c;} 
a { color: #2c292e; }
.dropdown-main {z-index: 2;position: relative;display: inline-block}
div.main_menue {left: 0;height: 20px;margin: 0;padding: 0 0 0 0;}
h3.bread-cont { font: normal normal 700 12px/18px 'roboto', sans-serif;margin: 0px 0 0 19px;letter-spacing: 0.08em;color: #575451;}
h3.bread-cont.desk { width: 100%;margin: 0px 0 0px 17px;}
h3.bread-cont.small a { font: normal normal 500 14px/18px 'roboto', sans-serif;letter-spacing: 0.08em;color: #5CB73A;font-style: italic;}
h3 {color: #242125;font-family: 'roboto', serif;font-size: 1.1em;font-weight: normal;letter-spacing: .0em;line-height: 21p}
div.menue_top {width: 100%;height: 175px;margin-top: 0;}
.top-desk p.class, .top-desk p.classLand {display: none; }
ul.warenkorb.nav, ul.warenkorb.det, ul.warenkorb.modal { display: none; }
div.top-desk p.linie { line-height: 1px; }
div.top-desk p.linie.oben {  border-bottom: 1px solid #6f4c4c; margin: 15px 0 0 0; }
div.top-desk p.linie.mitte { margin: 0px 0 0 0;}
div.top-desk p.linie.unten { border-bottom: 1px solid #6f4c4c; margin: 12px 0 5px 0;}
div.top-desk div.bread a { color: #242125 !important; }
div.top-desk div.bread img { width: 20px;margin: -4px 0 0 0;}
div.top-desk div.bread a:hover { color: #9B0011 !important; text-decoration: underline !important; }
div.top-desk div.main_menue button.active { color: #9B0011 !important;  }
div.top-desk div.office {height: 37px;margin: 7px 0 12px 0;}
div.top-desk div.office a.konto img, div.top-desk div.office a.warenkorb img, div.top-desk div.office p.suche img{width: 30px; height: 30px;  margin: -7px 7px 0 0;}

div.wr-head  {position: relative; }
div.wr-head img {z-index:1;width: 100%; }
div.wr-head div.kopfkino {z-index:3;position: absolute; top: 10%; left: 45%;  }
div.wr-head div.kopfkino h3.kopfbox {font: normal normal 500 25px/55px 'Roboto Slab', serif;margin: 1px 0px 0 0px;padding: 0 15px 0 18px; letter-spacing: 0.04em;color:#fff;}
div.wr-head div.kopfkino p.kopfsalat {font: normal normal 500 21px/30px 'roboto', sans-serif;margin: 1px 0px 0 0px;padding: 0 15px 0 18px; letter-spacing: 0.04em;color:#fff;}

div.wr-head div.kopfkinoscheinwelt {z-index:2;position: absolute; top: 10%; left: 45%;background-color:#ddd; mix-blend-mode: multiply; }
div.wr-head div.kopfkinoscheinwelt h3.kopfbox {font: normal normal 500 25px/55px 'Roboto Slab', serif;margin: 1px 0px 0 0px;padding: 0 15px 0 18px; letter-spacing: 0.04em;color:#fff;}
div.wr-head div.kopfkinoscheinwelt p.kopfsalat {font: normal normal 500 21px/30px 'roboto', sans-serif;margin: 1px 0px 0 0px;padding: 0 15px 0 18px; letter-spacing: 0.04em;color:#fff;}


div.drei-home {margin: 5px 0 5px 0;}
div.drei-home div.inhalt {position: relative; }
div.drei-home img {z-index:1;width: 97% !important; }
div.drei-home div.kopfkino {z-index:3;position: absolute; top: 71%; left: 3%; width: 85%}

div.drei-home div.kopfkinoscheinwelt {z-index:3;position: absolute; top: 71%; left: 3%;width: 85%; background-color:#6f4c4c; mix-blend-mode: multiply; }

@media(max-width:929px) {
div.drei-home div.kopfkinoscheinwelt h3.kopfbox {font: normal normal 400 15px/21px 'Roboto Slab', serif;margin: 1px 0px 0 0px;padding: 0 5px 0 10px; letter-spacing: 0.04em;color:#fff;}
div.drei-home div.kopfkinoscheinwelt p.kopfsalat {font: normal normal 300 12px/15px 'roboto', sans-serif;margin: 1px 0px 0 0px;padding: 0 5px 0 10px; letter-spacing: 0.04em;color:#fff;}
div.drei-home div.kopfkino h3.kopfbox {font: normal normal 400 15px/21px 'Roboto Slab', serif;margin: 1px 0px 0 0px;padding: 0 5px 0 10px; letter-spacing: 0.04em;color:#fff;}
div.drei-home div.kopfkino p.kopfsalat {font: normal normal 300 12px/15px 'roboto', sans-serif;margin: 1px 0px 0 0px;padding: 0 5px 0 10px; letter-spacing: 0.04em;color:#fff;}

div.vier-home div.kopfkinoscheinwelt {height: 175px;}
div.vier-home div.kopfkinoscheinwelt h3.kopfbox {font: normal normal 400 14px/22px 'Roboto Slab', serif;margin: 1px 0px 0 0px;padding: 0 5px 0 10px; letter-spacing: 0.04em;color:#fff;}
div.vier-home div.kopfkinoscheinwelt p.kopfsalat {font: normal normal 300 11px/16px 'roboto', sans-serif;margin: 1px 0px 0 0px;padding: 0 5px 0 10px; letter-spacing: 0.04em;color:#fff;}
}

@media(min-width:930px) {
div.drei-home div.kopfkinoscheinwelt h3.kopfbox {font: normal normal 400 19px/28px 'Roboto Slab', serif;margin: 1px 0px 0 0px;padding: 0 5px 0 10px; letter-spacing: 0.04em;color:#fff;}
div.drei-home div.kopfkinoscheinwelt p.kopfsalat {font: normal normal 300 15px/25px 'roboto', sans-serif;margin: 1px 0px 0 0px;padding: 0 5px 0 10px; letter-spacing: 0.04em;color:#fff;}
div.drei-home div.kopfkino h3.kopfbox {font: normal normal 400 19px/28px 'Roboto Slab', serif;margin: 1px 0px 0 0px;padding: 0 5px 0 10px; letter-spacing: 0.04em;color:#fff;}
div.drei-home div.kopfkino p.kopfsalat {font: normal normal 300 15px/25px 'roboto', sans-serif;margin: 1px 0px 0 0px;padding: 0 5px 0 10px; letter-spacing: 0.04em;color:#fff;}

div.vier-home div.kopfkinoscheinwelt {height: 185px;}
div.vier-home div.kopfkinoscheinwelt h3.kopfbox {font: normal normal 400 15px/22px 'Roboto Slab', serif;margin: 1px 0px 0 0px;padding: 0 5px 0 10px; letter-spacing: 0.04em;color:#fff;}
div.vier-home div.kopfkinoscheinwelt p.kopfsalat {font: normal normal 300 12px/17px 'roboto', sans-serif;margin: 1px 0px 0 0px;padding: 0 5px 0 10px; letter-spacing: 0.04em;color:#fff;}
}

@media(min-width:1100px) {
div.drei-home div.kopfkinoscheinwelt h3.kopfbox {font: normal normal 400 19px/28px 'Roboto Slab', serif;margin: 1px 0px 0 0px;padding: 0 5px 0 10px; letter-spacing: 0.04em;color:#fff;}
div.drei-home div.kopfkinoscheinwelt p.kopfsalat {font: normal normal 300 15px/25px 'roboto', sans-serif;margin: 1px 0px 0 0px;padding: 0 5px 0 10px; letter-spacing: 0.04em;color:#fff;}
div.drei-home div.kopfkino h3.kopfbox {font: normal normal 400 19px/28px 'Roboto Slab', serif;margin: 1px 0px 0 0px;padding: 0 5px 0 10px; letter-spacing: 0.04em;color:#fff;}
div.drei-home div.kopfkino p.kopfsalat {font: normal normal 300 15px/25px 'roboto', sans-serif;margin: 1px 0px 0 0px;padding: 0 5px 0 10px; letter-spacing: 0.04em;color:#fff;}

div.vier-home div.kopfkinoscheinwelt {height: 185px;}
div.vier-home div.kopfkinoscheinwelt h3.kopfbox {font: normal normal 400 17px/28px 'Roboto Slab', serif;margin: 1px 0px 0 0px;padding: 0 5px 0 10px; letter-spacing: 0.04em;color:#fff;}
div.vier-home div.kopfkinoscheinwelt p.kopfsalat {font: normal normal 300 13px/21px 'roboto', sans-serif;margin: 1px 0px 0 0px;padding: 0 5px 0 10px; letter-spacing: 0.04em;color:#fff;}
}


#homeDrei {margin: 5px 0 0px 0;}
#homeDrei div.carousel-item {position: relative; }
#homeDrei img {z-index:1;width: 97% !important; }
#homeDrei div.kopfkino {z-index:3;position: absolute; top: 73%; left: 3%; width: 85%}
#homeDrei div.kopfkino h3.kopfbox {font: normal normal 400 17px/28px 'Roboto Slab', serif;margin: 1px 0px 0 0px;padding: 0 5px 0 10px; letter-spacing: 0.04em;color:#fff;}
#homeDrei div.kopfkino p.kopfsalat {font: normal normal 300 14px/21px 'roboto', sans-serif;margin: 1px 0px 0 0px;padding: 0 5px 0 10px; letter-spacing: 0.04em;color:#fff;}

#homeDrei div.kopfkinoscheinwelt {z-index:3;position: absolute; top: 73%; left: 3%;width: 85%;background-color:#463a3a; mix-blend-mode: multiply; }
#homeDrei div.kopfkinoscheinwelt h3.kopfbox {font: normal normal 400 17px/28px 'Roboto Slab', serif;margin: 1px 0px 0 0px;padding: 0 5px 0 10px; letter-spacing: 0.04em;color:#fff;}
#homeDrei div.kopfkinoscheinwelt p.kopfsalat {font: normal normal 300 14px/21px 'roboto', sans-serif;margin: 1px 0px 0 0px;padding: 0 5px 0 10px; letter-spacing: 0.04em;color:#fff;}


div.vier-home {margin: 5px 0 0px 0;}
div.vier-home div.inhalt {position: relative; }
div.vier-home img {z-index:1;width: 97% !important; }
div.vier-home div.kopfkinoscheinwelt {margin: 5px 0 9px 0; width: 97%;background-color:#463a3a; mix-blend-mode: multiply; }

#homeVier {margin: 5px 0 0px 0;}
#homeVier div.carousel-item {position: relative; }
#homeVier img {z-index:1;width: 97% !important; }
#homeVier div.kopfkino {z-index:3;position: absolute; top: 73%; left: 3%; width: 85%}
#homeVier div.kopfkino h3.kopfbox {font: normal normal 400 17px/28px 'Roboto Slab', serif;margin: 1px 0px 0 0px;padding: 0 5px 0 10px; letter-spacing: 0.04em;color:#fff;}
#homeVier div.kopfkino p.kopfsalat {font: normal normal 300 14px/21px 'roboto', sans-serif;margin: 1px 0px 0 0px;padding: 0 5px 0 10px; letter-spacing: 0.04em;color:#fff;}

#homeVier div.kopfkinoscheinwelt {z-index:3;position: absolute; top: 73%; left: 3%;width: 85%;background-color:#463a3a; mix-blend-mode: multiply; }
#homeVier div.kopfkinoscheinwelt h3.kopfbox {font: normal normal 400 17px/28px 'Roboto Slab', serif;margin: 1px 0px 0 0px;padding: 0 5px 0 10px; letter-spacing: 0.04em;color:#fff;}
#homeVier div.kopfkinoscheinwelt p.kopfsalat {font: normal normal 300 14px/21px 'roboto', sans-serif;margin: 1px 0px 0 0px;padding: 0 5px 0 10px; letter-spacing: 0.04em;color:#fff;}



div.top-desk div.office p.suche input {font: normal normal 500 15px/15px 'roboto', sans-serif;margin: 1px 0px 0 0px;padding: 0 4px 0 4px;height: 27px;border: 1px solid #7A1600;border-radius: 3px;color: #270404;letter-spacing: 0em;width: 235px;}
div.top-desk div.office p.suche input::placeholder {font: normal small-caps 500 15px/20px 'roboto', sans-serif;color: #270404;opacity: 1;}
div.top-desk  .office p:hover {cursor: pointer;}
.top-desk div.brand a.weinraum {display: block;color: #270404 !important;text-align: center;width: 100%;}
.top-desk div.brand a.unten {display: block;color: #270404 !important;text-align: center;width: 100%;text-transform: uppercase;}
.pers-navigation div.container-fluid div.navbar .menue-switch-button:not(.collapsed)  {color: #FFF;background-color: #ddd;}
.menue_wein_left .menue-switch-button.collapsed  {color: #FFF;background-color: #e6e2d4;height: 35px;}
.pers-navigation div.container-fluid div.navbar .menue-switch-button.collapsed  {color: #FFF;background-color: #ggg;height: 35px;}
.menue_wein_left .menue-switch-button, .pers-navigation div.container-fluid div.navbar .menue-switch-button {padding: 5px 10px 5px 10px !important;border-radius: 2px;float:right;border:none; background-color: #e4ded4;}
.menue_wein_left .menue-switch-button span, .pers-navigation div.container-fluid div.navbar .menue-switch-button span {font: normal normal 800 12px/16px 'roboto', sans-serif;text-transform: uppercase;text-align: right;color: #000;display: block;width: 100%;margin: 0 auto 0 auto;padding: 0px 0 0px 0;letter-spacing: 0.04em;}
.navbar button.navbar-toggle.lexikon span { color: #000; }
.navbar-toggle .icon-bar { width: 100%; }
.navbar-toggle {position: absolute;float: right;padding: 0;width: 100%; background-color: #FFF;border: none;}
.navbar button.navbar-toggle span {font: normal normal 600 15px/12px 'roboto', sans-serif;text-align: center;display: block;width: 100%;margin: 0 auto 0 auto;padding: 0px 0 0px 0;letter-spacing: 0.04em;}
div.top-desk div.office a.warenkorb span.bobble {position: absolute;margin: 0 !important;border: 1px solid #fff;color: #fff;background-color: #9B0011;text-align: center;}
div.top-desk div.bread { position: relative; }
div.top-desk div.bread p.line {position: absolute;bottom: 1px;height: 1px;background-color: #FFF;} 

div.top-desk div.office p.knopfleiste a.warenkorb {position: relative; }

div.kopf-impressum { width: 65%; }
div.kopf-impressum img { width: 100%; }

div.top-desk div.main_menue p.menmob span {color: #000 !important;}

@media(max-width:340px) {
div.top-desk { height: 155px;padding: 20px 0 0 0;}
div.top-desk div.office p.knopfleiste  {height: 25px; margin: 0 0 0 0;}
div.top-desk div.office p.knopfleiste  img, div.top-desk div.office p.knopfleiste  a, div.top-desk div.office p.knopfleiste div.wk {display: block; float: right;}
div.top-desk div.office p.knopfleiste  img.suche {width: 17px; height: 17px;  margin: 0px 20px 0 0;}
div.top-desk div.office p.knopfleiste  img.warenkorb {width: 17px; height: 17px;  margin: 0px 3px 0 0;}
div.top-desk div.office p.knopfleiste  a.konto {margin: 0px 20px 0 0;}
div.top-desk div.office p.knopfleiste  img.konto {width: 17px; height: 17px;  margin: 0px 0px 0 0;}
div.top-desk div.office a.warenkorb { margin: 0 15px 0 0;}
div.top-desk div.office a.warenkorb span {position: absolute;top: 13px;left: 0;margin: 0 !important;font: normal normal 500 16px/15px 'roboto', sans-serif;  letter-spacing: 0.0em;}
div.top-desk div.office a.warenkorb span.bobble {font: normal normal 500 10px/16px 'roboto', sans-serif !important;top: -9px;left: 11px;width: 18px;height: 18px;border-radius: 9px;}
div.top-desk div.office p.knopfleiste a.ag-name {font: normal normal 500 7px/16px 'roboto', sans-serif !important; display: block; float: right;margin: 0px 20px 0 0;}


div.top-desk div.office {height: 25px;margin: 0px 0 5px 0; padding: 23px 0 0 0;}
div.top-desk div.brand.desk {  padding: 15px 0 0 0;}
div.top-desk div.brand.desk a.weinraum { font: normal normal 700 19px/15px 'roboto', sans-serif; letter-spacing: 0.18em; width: 245px;margin: 0 0 0 20px;text-align: left;text-transform:uppercase;}
div.top-desk div.brand.desk span { display: block; float: left;}
div.top-desk div.brand.desk span.li { font: normal normal 500 7px/16px 'roboto', sans-serif;  letter-spacing: 0em; width: 25px;text-align: left;}
div.top-desk div.brand.desk span.txt { font: normal normal 500 7px/22px 'roboto', sans-serif;  letter-spacing: 0.6em; width: 73px;}
div.top-desk div.brand.desk span.re { font: normal normal 500 7px/16px 'roboto', sans-serif;  letter-spacing: 0em; width: 25px;text-align: right;}

div.top-desk div.brand.desk a.unten { font: normal normal 600 9px/18px 'roboto', sans-serif;  letter-spacing: 0.1em; width: 245px;margin: 0  0 0 20px; height: 50px;text-align: center;}
div.top-desk div.brand.desk a.logo {display: block;width: 245px;height: 56px;margin: 0 auto 15px auto;text-align: center;}
div.top-desk div.brand.desk a.logo img { width: 120px; } 
div.top-desk div.main_menue {width: 275px;margin: 0 auto 0 auto;}

div.top-desk div.main_menue a {font: normal small-caps 400 14px/18px 'roboto', sans-serif;  letter-spacing: 0.1em; height: 28px;margin: 2px 28px 0 0;}
div.top-desk div.main_menue p.menmob span {font: normal small-caps 400 14px/18px 'roboto', sans-serif;  letter-spacing: 0.1em; height: 28px;margin: -3px 10px 0 0 !important;}
div.top-desk div.main_menue p.menmob span.weine {width: 43px;}   
div.top-desk div.main_menue p.menmob span.winzer {width: 53px;}   
div.top-desk div.main_menue p.menmob span.regionen {width: 67px;}   
div.top-desk div.main_menue a.kontakt {width: 55px; margin: 2px 0 0 0 !important;}   


.menue_wein_left { margin: 0 0px 30px 0px !important; }
div.top-desk div.bread div.bread-inner { width: 100%;text-align: center; }
div.top-desk div.bread { padding: 0 5px 0px 0px !important;min-height: 19px;} 
.bread p { width: 100%; height: 20px; margin: 0; padding: 0; line-height: 16px; text-align: center;}
.bread .sep { font: normal normal 500 9px/13px 'roboto', sans-serif; letter-spacing: 0.04em; color: #000;  }
.bread a { font: normal normal 400 10px/13px 'roboto', sans-serif; letter-spacing: 0.04em;  }
.bread .letzt {font: normal normal 400 10px/21px 'roboto', sans-serif; letter-spacing: 0.04em; color: #242125;}
}

@media(min-width:341px) {
div.top-desk { height: 155px;padding: 20px 0 0 0;}
div.kopf { height: 70px;}
div.top-desk div.office p.knopfleiste  {height: 25px; margin: 0 0 0 0;}
div.top-desk div.office p.knopfleiste  img, div.top-desk div.office p.knopfleiste  a, div.top-desk div.office p.knopfleiste div.wk {display: block; float: right;}
div.top-desk div.office p.knopfleiste  img.suche {width: 17px; height: 17px;  margin: 0px 20px 0 0;}
div.top-desk div.office p.knopfleiste  img.warenkorb {width: 17px; height: 17px;  margin: 0px 3px 0 0;}
div.top-desk div.office p.knopfleiste  a.konto {margin: 0px 20px 0 0;}
div.top-desk div.office p.knopfleiste  img.konto {width: 17px; height: 17px;  margin: 0px 0px 0 0;}
div.top-desk div.office a.warenkorb { margin: 0 15px 0 0;}
div.top-desk div.office a.warenkorb span {position: absolute;top: 13px;left: 0;margin: 0 !important;font: normal normal 500 16px/15px 'roboto', sans-serif;  letter-spacing: 0.0em;}
div.top-desk div.office a.warenkorb span.bobble {font: normal normal 500 10px/16px 'roboto', sans-serif !important;top: -9px;left: 11px;width: 18px;height: 18px;border-radius: 9px;}
div.top-desk div.office p.knopfleiste a.ag-name {font: normal normal 500 7px/16px 'roboto', sans-serif !important; display: block; float: right;margin: 0px 20px 0 0;}


div.top-desk div.office {height: 25px;margin: 0px 0 5px 0; padding: 23px 0 0 0;}
div.top-desk div.brand.desk {  padding: 15px 0 0 0;}
div.top-desk div.brand.desk a.weinraum { font: normal normal 700 19px/15px 'roboto', sans-serif; letter-spacing: 0.18em; width: 245px;margin: 0 0 0 20px;text-align: left;text-transform:uppercase;}
div.top-desk div.brand.desk span { display: block; float: left;}
div.top-desk div.brand.desk span.li { font: normal normal 500 7px/16px 'roboto', sans-serif;  letter-spacing: 0em; width: 25px;text-align: left;}
div.top-desk div.brand.desk span.txt { font: normal normal 500 7px/22px 'roboto', sans-serif;  letter-spacing: 0.6em; width: 73px;}
div.top-desk div.brand.desk span.re { font: normal normal 500 7px/16px 'roboto', sans-serif;  letter-spacing: 0em; width: 25px;text-align: right;}

div.top-desk div.brand.desk a.unten { font: normal normal 600 9px/18px 'roboto', sans-serif;  letter-spacing: 0.1em; width: 245px;margin: 0  0 0 20px; height: 50px;text-align: center;}
div.top-desk div.brand.desk a.logo {display: block;width: 245px;height: 56px;margin: 0 auto 15px auto;text-align: center;}
div.top-desk div.brand.desk a.logo img { width: 120px; } 
div.top-desk div.main_menue {width: 315px;margin: 0 auto 0 auto;}

div.top-desk div.main_menue a {font: normal small-caps 400 15px/18px 'roboto', sans-serif;  letter-spacing: 0.1em; height: 28px;margin: 2px 28px 0 0;}
div.top-desk div.main_menue p.menmob span {font: normal small-caps 400 15px/18px 'roboto', sans-serif;  letter-spacing: 0.1em; height: 28px;margin: -3px 19px 0 0 !important;}
div.top-desk div.main_menue p.menmob span.weine {width: 45px;}   
div.top-desk div.main_menue p.menmob span.winzer {width: 55px;}   
div.top-desk div.main_menue p.menmob span.regionen {width: 70px;}   
div.top-desk div.main_menue a.kontakt {width: 55px; margin: 2px 0 0 0 !important;}   


.menue_wein_left { margin: 0 0px 0px 0px !important; }
div.top-desk div.bread div.bread-inner { width: 100%;text-align: center; }
div.top-desk div.bread { padding: 0 5px 0px 0px !important;min-height: 19px;} 
.bread p { width: 100%; height: 20px; margin: 0; padding: 0; line-height: 16px; text-align: center;}
.bread .sep { font: normal normal 500 9px/13px 'roboto', sans-serif;  letter-spacing: 0.04em; color: #000;  }
.bread a { font: normal normal 400 10px/13px 'roboto', sans-serif;  letter-spacing: 0.04em;  }
.bread .letzt {font: normal normal 400 10px/21px 'roboto', sans-serif;  letter-spacing: 0.04em; color: #242125;}
}

@media(min-width:485px) {
div.top-desk { height: 155px;padding: 20px 0 0 0;}
div.top-desk div.office p.knopfleiste  {height: 25px; margin: 0 0 0 0;}
div.top-desk div.office p.knopfleiste  img, div.top-desk div.office p.knopfleiste  a, div.top-desk div.office p.knopfleiste div.wk {display: block; float: right;}
div.top-desk div.office p.knopfleiste  img.suche {width: 17px; height: 17px;  margin: 0px 20px 0 0;}
div.top-desk div.office p.knopfleiste  img.warenkorb {width: 17px; height: 17px;  margin: 0px 3px 0 0;}
div.top-desk div.office p.knopfleiste  a.konto {margin: 0px 20px 0 0;}
div.top-desk div.office p.knopfleiste  img.konto {width: 17px; height: 17px;  margin: 0px 0px 0 0;}
div.top-desk div.office a.warenkorb { margin: 0 15px 0 0;}
div.top-desk div.office a.warenkorb span {position: absolute;top: 13px;left: 0;margin: 0 !important;font: normal normal 500 16px/15px 'roboto', sans-serif;  letter-spacing: 0.0em;}
div.top-desk div.office a.warenkorb span.bobble {font: normal normal 500 10px/16px 'roboto', sans-serif !important;top: -9px;left: 11px;width: 18px;height: 18px;border-radius: 9px;}
div.top-desk div.office p.knopfleiste a.ag-name {font: normal normal 500 8px/16px 'roboto', sans-serif !important; display: block; float: right;margin: 0px 20px 0 0;}


div.top-desk div.office {height: 25px;margin: 0px 0 5px 0; padding: 23px 0 0 0;}
div.top-desk div.brand.desk {  padding: 15px 0 0 0;}
div.top-desk div.brand.desk a.weinraum { font: normal normal 700 19px/15px 'roboto', sans-serif; letter-spacing: 0.18em; width: 245px;margin: 0 0 0 20px;text-align: left;text-transform:uppercase;}
div.top-desk div.brand.desk span { display: block; float: left;}
div.top-desk div.brand.desk span.li { font: normal normal 500 7px/16px 'roboto', sans-serif;  letter-spacing: 0em; width: 25px;text-align: left;}
div.top-desk div.brand.desk span.txt { font: normal normal 500 7px/22px 'roboto', sans-serif;  letter-spacing: 0.6em; width: 73px;}
div.top-desk div.brand.desk span.re { font: normal normal 500 7px/16px 'roboto', sans-serif;  letter-spacing: 0em; width: 25px;text-align: right;}

div.top-desk div.brand.desk a.unten { font: normal normal 600 9px/18px 'roboto', sans-serif;  letter-spacing: 0.1em; width: 245px;margin: 0  0 0 20px; height: 50px;text-align: center;}
div.top-desk div.brand.desk a.logo {display: block;width: 245px;height: 56px;margin: 0 auto 15px auto;text-align: center;}
div.top-desk div.brand.desk a.logo img { width: 120px; } 
div.top-desk div.main_menue {width: 355px;margin: 0 auto 0 auto;}

div.top-desk div.main_menue a {font: normal small-caps 400 18px/18px 'roboto', sans-serif;  letter-spacing: 0.1em; height: 28px;margin: 2px 28px 0 0;}
div.top-desk div.main_menue p.menmob span {font: normal small-caps 400 18px/18px 'roboto', sans-serif;  letter-spacing: 0.1em; height: 28px;margin: -3px 28px 0 0;}
div.top-desk div.main_menue p.menmob span.weine {width: 53px;}   
div.top-desk div.main_menue p.menmob span.winzer {width: 65px;}   
div.top-desk div.main_menue p.menmob span.regionen {width: 80px;}   
div.top-desk div.main_menue a.kontakt {width: 60px; margin: 2px 0 0 0 !important;}   


.menue_wein_left { margin: 0 0px 30px 0px !important; }
div.top-desk div.bread div.bread-inner { width: 100%;text-align: center; }
div.top-desk div.bread { padding: 0 5px 0px 0px !important;min-height: 19px;} 
.bread p { width: 100%; height: 20px; margin: 0; padding: 0; line-height: 16px; text-align: center;}
.bread .sep { font: normal normal 500 9px/13px 'roboto', sans-serif;  letter-spacing: 0.04em; color: #000;  }
.bread a { font: normal normal 400 10px/13px 'roboto', sans-serif;  letter-spacing: 0.04em;  }
.bread .letzt {font: normal normal 400 10px/21px 'roboto', sans-serif;  letter-spacing: 0.04em; color: #242125;}
}

@media(min-width: 565px) {    
div.top-desk { height: 155px;padding: 20px 0 0 0;}
div.top-desk div.office p.knopfleiste  {height: 25px; margin: 0 0 0 0;}
div.top-desk div.office p.knopfleiste  img, div.top-desk div.office p.knopfleiste  a, div.top-desk div.office p.knopfleiste div.wk {display: block; float: right;}
div.top-desk div.office p.knopfleiste  img.suche {width: 17px; height: 17px;  margin: 0px 20px 0 0;}
div.top-desk div.office p.knopfleiste  img.warenkorb {width: 17px; height: 17px;  margin: 0px 3px 0 0;}
div.top-desk div.office p.knopfleiste  a.konto {margin: 0px 20px 0 0;}
div.top-desk div.office p.knopfleiste  img.konto {width: 17px; height: 17px;  margin: 0px 0px 0 0;}
div.top-desk div.office a.warenkorb { margin: 0 15px 0 0;}
div.top-desk div.office a.warenkorb span {position: absolute;top: 13px;left: 0;margin: 0 !important;font: normal normal 500 16px/15px 'roboto', sans-serif;  letter-spacing: 0.0em;}
div.top-desk div.office a.warenkorb span.bobble {font: normal normal 500 10px/16px 'roboto', sans-serif !important;top: -9px;left: 11px;width: 18px;height: 18px;border-radius: 9px;}
div.top-desk div.office p.knopfleiste a.ag-name {font: normal normal 500 9px/16px 'roboto', sans-serif !important; display: block; float: right;margin: 0px 20px 0 0;}

div.top-desk div.office {height: 70px;margin: 0px 0 0px 0; padding: 23px 0 0 0;}
div.top-desk div.brand.desk {  padding: 15px 0 0 0;}
div.top-desk div.brand.desk a.weinraum { font: normal normal 700 19px/15px 'roboto', sans-serif; letter-spacing: 0.18em; width: 245px;margin: 0 0 0 20px;text-align: left;text-transform:uppercase;}
div.top-desk div.brand.desk span { display: block; float: left;}
div.top-desk div.brand.desk span.li { font: normal normal 500 7px/16px 'roboto', sans-serif;  letter-spacing: 0em; width: 25px;text-align: left;}
div.top-desk div.brand.desk span.txt { font: normal normal 500 7px/22px 'roboto', sans-serif;  letter-spacing: 0.6em; width: 73px;}
div.top-desk div.brand.desk span.re { font: normal normal 500 7px/16px 'roboto', sans-serif;  letter-spacing: 0em; width: 25px;text-align: right;}

div.top-desk div.brand.desk a.unten { font: normal normal 600 9px/18px 'roboto', sans-serif;  letter-spacing: 0.1em; width: 245px;margin: 0  0 0 20px; height: 45px;text-align: center;}
div.top-desk div.brand.desk a.logo {display: block;width: 245px;height: 56px;margin: 0 auto 15px auto;text-align: center;}
div.top-desk div.brand.desk a.logo img { width: 120px; } 
div.top-desk div.main_menue {width: 366px;margin: 0 auto 0 auto;}

div.top-desk div.main_menue a {font: normal small-caps 400 19px/18px 'roboto', sans-serif;  letter-spacing: 0.1em; height: 28px;margin: 2px 28px 0 0;}
div.top-desk div.main_menue p.menmob span {font: normal small-caps 400 19px/18px 'roboto', sans-serif;  letter-spacing: 0.1em; height: 28px;margin: -3px 28px 0 0;}
div.top-desk div.main_menue p.menmob span.weine {width: 54px;}   
div.top-desk div.main_menue p.menmob span.winzer {width: 66px;}   
div.top-desk div.main_menue p.menmob span.regionen {width: 83px;}   
div.top-desk div.main_menue a.kontakt {width: 74px; margin: 2px 0 0 0 !important;}   


.menue_wein_left { margin: 0 0px 30px 0px !important; }
div.top-desk div.bread div.bread-inner { width: 100%;text-align: center; }
div.top-desk div.bread { padding: 0 5px 0px 0px !important;min-height: 19px;} 
.bread p { width: 100%; height: 20px; margin: 0; padding: 0; line-height: 16px; text-align: center;}
.bread .sep { font: normal normal 500 9px/13px 'roboto', sans-serif;  letter-spacing: 0.04em; color: #000;  }
.bread a { font: normal normal 400 10px/13px 'roboto', sans-serif;  letter-spacing: 0.04em;  }
.bread .letzt {font: normal normal 400 10px/21px 'roboto', sans-serif;  letter-spacing: 0.04em; color: #242125;}
}

@media(min-width:768px) {   
div.kopf { height: 75px;}
div.top-desk { height: 155px;padding: 20px 0 0 0;}
div.top-desk div.office p.knopfleiste  {height: 25px; margin: 0 0 0 0;}
div.top-desk div.office p.knopfleiste  img, div.top-desk div.office p.knopfleiste  a, div.top-desk div.office p.knopfleiste div.wk {display: block; float: right;}
div.top-desk div.office p.knopfleiste  img.suche {width: 20px; height: 20px;  margin: 0px 20px 0 0;}
div.top-desk div.office p.knopfleiste  img.warenkorb {width: 20px; height: 20px;  margin: 0px 10px 0 0;}
div.top-desk div.office p.knopfleiste  a.konto {margin: 0px 20px 0 0;}
div.top-desk div.office p.knopfleiste  img.konto {width: 20px; height: 20px;  margin: 0px 0px 0 0;}
div.top-desk div.office a.warenkorb { margin: 0 15px 0 0;}
div.top-desk div.office a.warenkorb span {position: absolute;top: 13px;left: 0;margin: 0 !important;font: normal normal 500 16px/15px 'roboto', sans-serif;  letter-spacing: 0.0em;}
div.top-desk div.office a.warenkorb span.bobble {font: normal normal 500 10px/16px 'roboto', sans-serif !important;top: -9px;left: 11px;width: 18px;height: 18px;border-radius: 9px;}
div.top-desk div.office p.knopfleiste a.ag-name {font: normal normal 500 10px/16px 'roboto', sans-serif !important; display: block; float: right;margin: 0px 20px 0 0;}


div.top-desk div.office {height: 25px;margin: 0px 0 5px 0; padding: 23px 0 0 0;}
div.top-desk div.brand.desk {  padding: 15px 0 0 0;}
div.top-desk div.brand.desk a.weinraum { font: normal normal 700 25px/25px 'roboto', sans-serif; letter-spacing: 0.18em; width: 245px;margin: 0 0 0 20px;text-align: left;text-transform:uppercase;}
div.top-desk div.brand.desk span { display: block; float: left;}
div.top-desk div.brand.desk span.li { font: normal normal 500 8px/16px 'roboto', sans-serif;  letter-spacing: 0em; width: 38px;text-align: left;}
div.top-desk div.brand.desk span.txt { font: normal normal 500 9px/22px 'roboto', sans-serif;  letter-spacing: 0.6em; width: 91px;}
div.top-desk div.brand.desk span.re { font: normal normal 500 8px/16px 'roboto', sans-serif;  letter-spacing: 0em; width: 34px;text-align: right;}

div.top-desk div.brand.desk a.unten { font: normal normal 600 9px/18px 'roboto', sans-serif;  letter-spacing: 0.1em; width: 245px;margin: 0  0 0 20px; height: 50px;text-align: center;}
div.top-desk div.brand.desk a.logo {display: block;width: 245px;height: 56px;margin: 0 auto 15px auto;text-align: center;}
div.top-desk div.brand.desk a.logo img { width: 120px; } 
div.top-desk div.main_menue {width: 410px;margin: 0 auto 0 auto;}

div.top-desk div.main_menue a {font: normal small-caps 400 21px/18px 'roboto', sans-serif;  letter-spacing: 0.1em; height: 28px;margin: 2px 28px 0 0;}
div.top-desk div.main_menue a.weine {width: 58px;}   
div.top-desk div.main_menue a.winzer {width: 55px;}   
div.top-desk div.main_menue a.regionen {width: 70px;}   
div.top-desk div.main_menue a.kontakt {width: 51px; margin: 2px 0 0 0;}   


.menue_wein_left { margin: 0 0px 30px 0px !important; }
div.top-desk div.bread div.bread-inner { width: 100%;text-align: center; }
div.top-desk div.bread { padding: 0 5px 0px 0px !important;min-height: 19px;} 
.bread p { width: 100%; height: 20px; margin: 0; padding: 0; line-height: 16px; text-align: center;}
.bread .sep { font: normal normal 500 9px/13px 'roboto', sans-serif;  letter-spacing: 0.04em; color: #000;  }
.bread a { font: normal normal 400 11px/13px 'roboto', sans-serif;  letter-spacing: 0.04em;  }
.bread .letzt {font: normal normal 400 11px/21px 'roboto', sans-serif;  letter-spacing: 0.04em; color: #242125;}
}

.hideMenue {display:none !important;} 

@media(min-width:1280px) {
div.top-desk { height: 165px;padding: 20px 0 0 0;}
div.top-desk div.office p.knopfleiste  {height: 25px; margin: 0 0 0 0;}
div.top-desk div.office p.knopfleiste  img, div.top-desk div.office p.knopfleiste  a, div.top-desk div.office p.knopfleiste div.wk {display: block; float: right;}
div.top-desk div.office p.knopfleiste  img.suche {width: 25px; height: 25px;  margin: 0px 30px 0 0;}
div.top-desk div.office p.knopfleiste  img.warenkorb {width: 25px; height: 25px;  margin: 0px 221px 0 0;}
div.top-desk div.office p.knopfleiste  a.konto {margin: 0px 30px 0 0;}
div.top-desk div.office p.knopfleiste  img.konto {width: 25px; height: 25px;  margin: 0px 0px 0 0;}
div.top-desk div.office a.warenkorb { margin: 0 15px 0 0;}
div.top-desk div.office a.warenkorb span {position: absolute;top: 13px;left: 0;margin: 0 !important;font: normal normal 500 16px/15px 'roboto', sans-serif;  letter-spacing: 0.0em;}
div.top-desk div.office a.warenkorb span.bobble {font: normal normal 500 10px/16px 'roboto', sans-serif !important;top: -9px;left: 11px;width: 18px;height: 18px;border-radius: 9px;}
div.top-desk div.office p.knopfleiste a.ag-name {font: normal normal 500 11px/16px 'roboto', sans-serif !important; display: block; float: right;margin: 6px 236px 0 0;}

div.top-desk div.office {height: 25px;margin: 0px 0 5px 0; padding: 23px 0 0 0;}
div.top-desk div.brand.desk {  padding: 15px 0 0 65px;}
div.top-desk div.brand.desk a.weinraum { font: normal normal 700 30px/32px 'roboto', sans-serif; letter-spacing: 0.2em; width: 245px;margin: 0 0 0 20px;text-align: left;text-transform:uppercase;}
div.top-desk div.brand.desk span { display: block; float: left;}
div.top-desk div.brand.desk span.li { font: normal normal 500 8px/16px 'roboto', sans-serif;  letter-spacing: 0em; width: 53px;text-align: left;}
div.top-desk div.brand.desk span.txt { font: normal normal 500 9px/22px 'roboto', sans-serif;  letter-spacing: 0.6em; width: 91px;}
div.top-desk div.brand.desk span.re { font: normal normal 500 8px/16px 'roboto', sans-serif;  letter-spacing: 0em; width: 52px;text-align: right;}

div.top-desk div.brand.desk a.unten { font: normal normal 600 9px/18px 'roboto', sans-serif;  letter-spacing: 0.1em; width: 245px;margin: 0  0 0 20px; height: 50px;text-align: center;}
div.top-desk div.brand.desk a.logo {display: block;width: 245px;height: 56px;margin: 0 auto 15px auto;text-align: center;}
div.top-desk div.brand.desk a.logo img { width: 120px; } 
div.top-desk div.main_menue {width: 455px;margin: 0 auto 0 auto;}

div.top-desk div.main_menue a {font: normal small-caps 400 22px/24px 'roboto', sans-serif;  letter-spacing: 0.1em; height: 28px;margin: -3px 28px 0 0;}
div.top-desk div.main_menue a.weine {width: 85px;}   
div.top-desk div.main_menue a.winzer {width: 95px;}   
div.top-desk div.main_menue a.regionen {width: 110px;}   
div.top-desk div.main_menue a.kontakt {width: 28px; margin: 0 0 0 0 !important;}   

.menue_wein_left { margin: 0 0px 30px 0px !important; }
div.top-desk div.bread div.bread-inner { width: 100%;text-align: center; }
div.top-desk div.bread { padding: 0 5px 0px 0px !important;min-height: 19px;} 
.bread p { width: 100%; height: 20px; margin: 0; padding: 0; line-height: 16px; text-align: center;}
.bread .sep { font: normal normal 500 9px/13px 'roboto', sans-serif;  letter-spacing: 0.04em; color: #000;  }
.bread a { font: normal normal 400 13px/13px 'roboto', sans-serif;  letter-spacing: 0.04em;  }
.bread .letzt {font: normal normal 400 13px/21px 'roboto', sans-serif;  letter-spacing: 0.04em; color: #242125;}
}

@media only screen and (min-width : 1050px) {
.menue-landscape div.links div.linkBox p.oben {font: normal small-caps 400 17px/21px 'roboto', sans-serif;letter-spacing: 0.2em;margin: 5px 0 0 0;}
.menue-landscape div.links div.linkBox p.unten {font: normal normal 400 11px/18px 'roboto', sans-serif;letter-spacing: 0.08em;margin: 3px 0 0 0;}

}

.slidecontainer { width: 100%; }
.slider {-webkit-appearance: none;width: 95%;height: 15px;border-radius: 5px;background: #b3b27e;outline: none;opacity: 0.7;-webkit-transition: .2s;transition: opacity .2s;}
.slider:hover { opacity: 1;}
.slider::-webkit-slider-thumb { -webkit-appearance: none;appearance: none;width: 25px;height: 25px;border-radius: 50%;background: #9B0011;cursor: pointer;}
.slider::-moz-range-thumb {width: 25px;height: 25px;border-radius: 50%;background: #04AA6D;cursor: pointer;}
p.sl_labels {width: 100%;font: normal normal 400 13px/25px 'roboto', sans-serif;letter-spacing: 0.0em;}
p.sl_labels span { display: block; float: left; width: 6.1%;margin: 0 12.5% 0 0;}
p.sl_labels span.le { display: block; float: left; width: 6.1%;margin: 0 0 0 0;}


input[type="checkbox"] { margin: 10px 0 0 0px; background-color: #FFF !important;}
input[type="checkbox"]:checked {background-color: #268da7 !important;border-color: #268da7 !important;}
.modal-warenkorb div.head.warenkorb img, .modal-warenkorb div.footer.warenkorb img, .modal-suche div.head.suche img { width: 100%; }
.modal-warenkorb div.head.warenkorb img { margin: 0px 0 0 0; }
.modal-warenkorb p.line {clear: both;border-top: 1px dotted #ddd !important;margin: 15px 0 0 0;}
.modal-warenkorb .produkt {position: relative;width: 100%;min-height: 175px;}
.modal-warenkorb .produkt .img {float: left; width: 12%; margin: 0 5px 0 5px; }
.modal-warenkorb .produkt .img img {width: 95%;}
.modal-warenkorb .produkt .details {width: calc(88% - 25px);float: left;margin: 0 0 0 12px;}

@media only screen and (max-width : 900px) {
.modal-warenkorb .produkt .details p.producer { font: normal normal 500 13px/20px 'roboto', sans-serif; letter-spacing: 0.04e;text-align: left; margin: 0 0 0 5px;}
.modal-warenkorb .produkt .details a { font: normal normal 600 20px/40px 'roboto', sans-serif;text-align: left; margin: 0 0 0 5px;}
.modal-warenkorb .text .produkt .details p.head.preis { font: normal normal 500 11px/16px 'roboto', sans-serif; letter-spacing: 0.0em;}
.modal-warenkorb .text .produkt .details p.cont.preis  { font: normal normal 600 14px/16px 'roboto', sans-serif; }
div.cartPack div.preis, div.cartPack div.preisTot, div.cartPack div.space, div.cartPack div.item-menue  { font: normal normal 600 14px/16px 'roboto', sans-serif; }

div.cartPack div.cart-box div.no span.number { font: normal normal 800 16px/16px 'roboto', sans-serif; }
div.cartPack div.item-menue p.pad { width: 39px; }
div.cartPack div.name { font: italic normal 600 19px/16px 'roboto', sans-serif; color: #d12600;  letter-spacing: 0.04em;}
div.cartPack p.head.pakete span { font: italic normal 500 11px/16px 'roboto', sans-serif; }
}

@media only screen and (min-width : 901px) {
.modal-warenkorb .produkt .details p.producer { 
font: normal normal 500 16px/30px 'roboto', sans-serif; 
letter-spacing: 0.04em;
text-align: left; margin: 0 0 0 10px;}.modal-warenkorb .produkt .details a { font: normal normal 600 25px/40px 'roboto', sans-serif;
text-align: left; margin: 0 0 0 10px;}
 .modal-warenkorb .text .produkt .details p.head.preis { font: normal normal 500 14px/16px 'roboto', sans-serif; letter-spacing: 0.04em;}
.modal-warenkorb .text .produkt .details p.cont.preis, div.cartPack div.preisTot, div.cartPack div.preis, div.cartPack div.preisTot, div.cartPack div.space, div.cartPack div.item-menue { font: normal normal 800 18px/16px 'roboto', sans-serif; }
div.cartPack div.cart-box div.no span.number { font: normal normal 800 18px/25px 'roboto', sans-serif; }
div.cartPack div.item-menue p.pad { width: 46px; }
div.cartPack div.name { font: italic normal 600 25px/16px 'roboto', sans-serif; color: #d12600; letter-spacing: 0.04em; }
div.cartPack p.head.pakete span { font: normal normal 500 14px/16px 'roboto', sans-serif; }
}

.modal-warenkorb .produkt .details a {display: block;width: 100%;text-align: left;}
.modal-warenkorb .text .produkt .details p.head.preis {height: 25px;width: 100%;margin: 8px 0 12px 0;}
.modal-warenkorb .text .produkt .details p.cont.preis {clear: both;letter-spacing: 0.04em;margin: 0 0 0 0;}
.modal-warenkorb .produkte { margin: 15px 0 0 0; }
.modal-warenkorb .produkt .details p.head.preis span, .modal-warenkorb .produkt .details p.cont.preis span {display: block;float: left;}
.modal-warenkorb .produkt .details p.head.preis span.einz, .modal-warenkorb .produkt .details p.cont.preis span.einz {margin: 0 0 0 10px;width: 25%;text-align: left;}
.modal-warenkorb .produkt .details p.head.preis span.fla, .modal-warenkorb .produkt .details p.cont.preis span.fla {margin: 0 0 0 0;width: 15%;text-align: right;}
.modal-warenkorb .produkt .details p.head.preis span.ges {margin: 0 0 0 0;width: 36%;text-align: right;}
.modal-warenkorb .produkt .details p.cont.preis span.fla {background-color: #d1d5c0;padding: 4px 3px 4px 3px;border: 1px solid #000;border-radius: 3px;}
.modal-warenkorb .produkt .details p.cont.preis span.fla:hover, .modal-warenkorb .produkt .details p.cont.preis span.icon.ti-trash:hover { cursor: pointer; }
.modal-warenkorb .produkt .details p.cont.preis span.einz, .modal-warenkorb .produkt .details p.cont.preis span.einz {margin: 7px 0 0 10px; }
.modal-warenkorb .produkt .details p.cont.preis span.ges {width: 35%;text-align: right;margin: 0px 0 0 10px;}
.modal-warenkorb .produkt .details p.cont.preis span.icon img { margin: 3px 0 0 30px; width: 14px; }
.modal-warenkorb .produkt .details p.zusInf {clear: both;font: normal normal 400 10px/12px 'roboto', sans-serif;}
.modal-warenkorb .produkt .details p.zusInf span.inh {display: block;float: left;margin: 0 0 0 10px;width: 25%;text-align: left;}
.modal-warenkorb .produkt .details p.zusInf span.mwst {display: block;float: left;width: 50%;text-align: right;}
.modal-warenkorb .produkt .details p.zusInf span.mwst a {font: normal normal 500 12px/12px 'roboto', sans-serif;display: inline;}
.modal-warenkorb .head.warenkorb { position: relative; border-top: 2px solid #d12600;}



 @media only screen and (max-width : 680px) {
.modal-warenkorb .head.warenkorb div.basket div.basketVals div.zurueck p, .modal-warenkorb .head.warenkorb div.basket div.basketVals div.kasse p {font: normal normal 500 10px/12px 'roboto', sans-serif;letter-spacing: 0.0em;}
.modal-warenkorb .head.warenkorb div.zeile p.basket span { font: italic normal 500 11px/12px 'roboto', sans-serif; letter-spacing: 0.0em;}
.modal-warenkorb .head.warenkorb div.basket p.kopf span { display: block; float: left; font: italic normal 500 10px/12px 'roboto', sans-serif;letter-spacing: 0.04em;}
.modal-warenkorb .head.warenkorb p.warenkorb {position: absolute;font: normal small-caps 500 15px/12px 'roboto', sans-serif;letter-spacing: 0.0em;padding: 3px 9px 3px 5px;}
.modal-warenkorb .head.warenkorb div.basket {height: 40%;top: 56%;}
.modal-warenkorb .head.warenkorb div.basket div.basketVals div.zurueck, .modal-warenkorb .head.warenkorb div.basket div.basketVals div.kasse .modal-warenkorb .head.warenkorb div.basket div.basketVals div.kasse-gp-daten {
font: normal normal 500 11px/12px 'roboto', sans-serif; letter-spacing: 0.0em; width: 15%;margin: 0 0 0 0%;}
.modal-warenkorb div.footer.warenkorb div.zeile p.basket span {font: normal normal 600 13px/12px 'roboto', sans-serif;letter-spacing: 0.04em;}
.modal-warenkorb div.footer.warenkorb div.zeile div.zurueck p, .modal-warenkorb div.footer.warenkorb div.zeile div.kasse p, .modal-warenkorb div.footer.warenkorb div.zeile div.kasse-gp-daten p {
font: normal normal 500 10px/12px 'roboto', sans-serif;letter-spacing: 0.04em;}
.modal-warenkorb .head.warenkorb div.versandkosten { top: 6%; }
.modal-warenkorb .head.warenkorb div.versandkosten span {font: italic small-caps 600 10px/12px 'roboto', sans-serif;letter-spacing: 0.02em; height: 15px !important;}
}

 @media only screen and (min-width : 681px) {
.modal-warenkorb .head.warenkorb div.basket div.basketVals div.zurueck p, .modal-warenkorb .head.warenkorb div.basket div.basketVals div.kasse p {font: normal normal 500 13px/12px 'roboto', sans-serif;letter-spacing: 0.02em;}
.modal-warenkorb .head.warenkorb div.zeile p.basket span { font: normal normal 500 15px/12px 'roboto', sans-serif; letter-spacing: 0.02em;}
.modal-warenkorb .head.warenkorb div.basket p.kopf span { display: block; float: left; font: italic normal 500 12px/12px 'roboto', sans-serif;letter-spacing: 0.06em;}
.modal-warenkorb .head.warenkorb p.warenkorb {position: absolute;font: normal small-caps 600 22px/12px 'roboto', sans-serif;letter-spacing: 0.04em;padding: 7px 12px 7px 5px;}
.modal-warenkorb .head.warenkorb div.basket {height: 40%;top: 56%;}
.modal-warenkorb .head.warenkorb div.basket div.basketVals div.zurueck, .modal-warenkorb .head.warenkorb div.basket div.basketVals div.kasse, .modal-warenkorb .head.warenkorb div.basket div.basketVals div.kasse-gp-daten {width: 16%;margin: 0 0 0 1%;}
.modal-warenkorb div.footer.warenkorb div.zeile p.basket span {font: normal normal 600 14px/12px 'roboto', sans-serif;letter-spacing: 0.04em;}
.modal-warenkorb div.footer.warenkorb div.zeile div.zurueck p, .modal-warenkorb div.footer.warenkorb div.zeile div.kasse p, .modal-warenkorb div.footer.warenkorb div.zeile div.kasse-gp-daten p {font: normal normal 400 13px/12px 'roboto', sans-serif;letter-spacing: 0.0em;}
.modal-warenkorb .head.warenkorb div.versandkosten { top: 8%; }
.modal-warenkorb .head.warenkorb div.versandkosten p span {font: italic small-caps 600 11px/12px 'roboto', sans-serif;letter-spacing: 0.02em;height: 15px !important;}
}

 @media only screen and (min-width : 863px) {
.modal-warenkorb .head.warenkorb div.basket div.basketVals div.zurueck p, .modal-warenkorb .head.warenkorb div.basket div.basketVals div.kasse p, .modal-warenkorb .head.warenkorb div.basket div.basketVals div.kasse-gp-daten p {font: normal normal 500 13px/12px 'roboto', sans-serif;letter-spacing: 0.0em;}
.modal-warenkorb .head.warenkorb div.zeile p.basket span { font: normal normal 500 14px/12px 'roboto', sans-serif; letter-spacing: 0.02em;}
.modal-warenkorb .head.warenkorb div.basket p.kopf span { display: block; float: left; font: italic normal 500 12px/12px 'roboto', sans-serif;letter-spacing: 0.06em;}
.modal-warenkorb .head.warenkorb p.warenkorb {position: absolute;font: normal small-caps 600 22px/12px 'roboto', sans-serif;letter-spacing: 0.04em;padding: 7px 12px 7px 5px;}
.modal-warenkorb .head.warenkorb div.basket {height: 40%;top: 56%;}
.modal-warenkorb .head.warenkorb div.basket div.basketVals div.zurueck, .modal-warenkorb .head.warenkorb div.basket div.basketVals div.kasse {width: 15%;margin: 0 0 0 2%;}
.modal-warenkorb div.footer.warenkorb div.zeile p.basket span {font: normal normal 600 15px/12px 'roboto', sans-serif;letter-spacing: 0.0em;}
.modal-warenkorb div.footer.warenkorb div.zeile div.zurueck p, .modal-warenkorb div.footer.warenkorb div.zeile div.kasse p, .modal-warenkorb div.footer.warenkorb div.zeile div.kasse-gp-daten p {font: normal normal 400 14px/12px 'roboto', sans-serif;letter-spacing: 0.0em;}
}

 @media only screen and (min-width : 950px) {
.modal-warenkorb .head.warenkorb div.basket div.basketVals div.zurueck p, .modal-warenkorb .head.warenkorb div.basket div.basketVals div.kasse p, .modal-warenkorb .head.warenkorb div.basket div.basketVals div.kasse-gp-daten p {font: normal normal 500 14px/12px 'roboto', sans-serif;letter-spacing: 0.0em;}
.modal-warenkorb .head.warenkorb div.zeile p.basket span { font: normal normal 500 16px/12px 'roboto', sans-serif; letter-spacing: 0.02em;}
.modal-warenkorb .head.warenkorb div.basket p.kopf span { display: block; float: left; font: italic normal 500 13px/12px 'roboto', sans-serif;letter-spacing: 0.06em;}
.modal-warenkorb .head.warenkorb p.warenkorb {position: absolute;font: normal small-caps 600 26px/12px 'roboto', sans-serif;letter-spacing: 0.04em;padding: 9px 12px 9px 5px;}
.modal-warenkorb .head.warenkorb div.basket {height: 38%;top: 56%;}
.modal-warenkorb .head.warenkorb div.basket div.basketVals div.zurueck, .modal-warenkorb .head.warenkorb div.basket div.basketVals div.kasse {width: 14%;margin: 0 0 0 2%;}
.modal-warenkorb div.footer.warenkorb div.zeile p.basket span {font: normal normal 600 17px/12px 'roboto', sans-serif;letter-spacing: 0.04em;}
.modal-warenkorb div.footer.warenkorb div.zeile div.zurueck p, .modal-warenkorb div.footer.warenkorb div.zeile div.kasse p, .modal-warenkorb div.footer.warenkorb div.zeile div.kasse-gp-daten p {font: normal normal 500 15px/12px 'roboto', sans-serif;letter-spacing: 0.04em;}
}

 @media only screen and (min-width : 1150px) {
.modal-warenkorb .head.warenkorb div.basket div.basketVals div.zurueck p, .modal-warenkorb .head.warenkorb div.basket div.basketVals div.kasse p, .modal-warenkorb .head.warenkorb div.basket div.basketVals div.kasse-gp-daten p {font: normal normal 500 16px/12px 'roboto', sans-serif;letter-spacing: 0.04em;}
.modal-warenkorb .head.warenkorb div.zeile p.basket span { font: normal normal 600 18px/12px 'roboto', sans-serif;letter-spacing: 0.04em;}
.modal-warenkorb .head.warenkorb div.basket p.kopf span { display: block; float: left; font: italic normal 500 14px/12px 'roboto', sans-serif;letter-spacing: 0.06em;}
.modal-warenkorb .head.warenkorb p.warenkorb {background-color: hsla(46.63, 0%, 98%, 0.91);position: absolute;font: normal small-caps 600 30px/12px 'roboto', sans-serif;letter-spacing: 0.04em;padding: 11px 12px 11px 5px;}
.modal-warenkorb .head.warenkorb div.basket {height: 34%;top: 56%;}
.modal-warenkorb .head.warenkorb div.basket div.basketVals div.zurueck, .modal-warenkorb .head.warenkorb div.basket div.basketVals div.kasse, .modal-warenkorb .head.warenkorb div.basket div.basketVals div.kasse-gp-daten {width: 14%;margin: 0 0 0 2%;}
.modal-warenkorb div.footer.warenkorb div.zeile p.basket span {font: normal normal 600 18px/12px 'roboto', sans-serif;letter-spacing: 0.04em;display: block;float: left;}
.modal-warenkorb div.footer.warenkorb div.zeile div.zurueck p, .modal-warenkorb div.footer.warenkorb div.zeile div.kasse p, .modal-warenkorb div.footer.warenkorb div.zeile div.kasse-gp-daten p {font: normal normal 500 16px/12px 'roboto', sans-serif;letter-spacing: 0.04em;}
}
.modal-warenkorb .head.warenkorb p.warenkorb {top: 30%;}
.modal-warenkorb .head.warenkorb div.versandkosten {background-color: hsla(46.63, 0%, 98%, 0.91);position: absolute;left: 1%;width: 98%;padding: 0px 12px 0px 5px;}
.modal-warenkorb .head.warenkorb div.versandkosten p { margin: 0 !important; height: 21px; }
.modal-warenkorb .head.warenkorb div.versandkosten p span { display: block; float: left; margin: 4px 7px 0 0;}
.modal-warenkorb .head.warenkorb div.versandkosten span.hinw { color: #000; }
.modal-warenkorb .head.warenkorb div.versandkosten span.rot { color: #d12600; }
.modal-warenkorb .head.warenkorb p.warenkorb {background-color: hsla(46.63, 0%, 98%, 0.91);position: absolute;color: #d12600;left: 20%;width: 60%;}
.modal-warenkorb .head.warenkorb div.basket {background-color: hsla(46.63, 0%, 98%, 0.91);position: absolute;width: 100%;}
.modal-warenkorb .head.warenkorb div.basket p.kopf.warenwert {margin: 2% 0 0 0;}
.modal-warenkorb .head.warenkorb div.basket p.kopf.versandkosten {margin: 2% 0 0 0;}
.modal-warenkorb .head.warenkorb div.basket p.kopf {clear: both;height: 7%;font: normal normal 600 24px/12px 'roboto', sans-serif;letter-spacing: 0.04em;color: #000;}
.modal-warenkorb .head.warenkorb div.basket p.kopf span { display: block; float: left; } 
.modal-warenkorb .head.warenkorb div.basket p.kopf span.li { width: 55%; text-align: right; }
.modal-warenkorb .head.warenkorb div.basket p.kopf span.re { width: 19%; text-align: right; }
.modal-warenkorb .head.warenkorb div.basket div.basketVals {position: absolute;top: 49%;width: 100%;border-top: 1px solid #000;padding: 10px 0 0 0;}
.modal-warenkorb .head.warenkorb div.basket div.basketVals div.zurueck { clear: both; }
.modal-warenkorb .head.warenkorb div.basket div.basketVals div.zurueck, .modal-warenkorb .head.warenkorb div.basket div.basketVals div.kasse , .modal-warenkorb .head.warenkorb div.basket div.basketVals div.kasse-gp-daten {float: left;height: 5%;background-color: #d12600;border-radius: 3px;}
.modal-warenkorb .head.warenkorb div.basket div.basketVals div.zurueck p, .modal-warenkorb .head.warenkorb div.basket div.basketVals div.kasse p, .modal-warenkorb .head.warenkorb div.basket div.basketVals div.kasse-gp-daten p {color: #FFF;padding: 4px 0 5px 0;margin: 0;}
.modal-warenkorb .head.warenkorb div.basket div.basketVals div.zurueck p:hover, .modal-warenkorb .head.warenkorb div.basket div.basketVals div.kasse p:hover {cursor: pointer;color: #688e44;}
.modal-warenkorb .head.warenkorb div.basket div.basketVals div.zeile {position: relative;float: left;width: 60%;height: 23px;margin: 0 3% 0 3%;border-bottom: 3px solid #d12600;}
.modal-warenkorb .head.warenkorb div.basket div.basketVals div.zeile {}

.modal-warenkorb .head.warenkorb p.basket {width: 100%;position: absolute;}
.modal-warenkorb .head.warenkorb p.basket {top: 3px;}
.modal-warenkorb .head.warenkorb div.angaben {clear: both;margin: 15px 0 0 9px;}
.modal-warenkorb .head.warenkorb div.angaben p.txt {clear: both;font: normal normal 400 13px/28px 'roboto', sans-serif;margin: 3px 0 0 0;height: 28px;}
.modal-warenkorb .head.warenkorb div.angaben p.txt span { display: block; float: left; }
.modal-warenkorb .head.warenkorb div.angaben p.txt span.was { width: 135px; text-align: right; font-weight: 600;}
.modal-warenkorb .head.warenkorb div.angaben p.txt span.ang { text-align: left;margin: 0 0 0 12px; }
@media only screen and (max-width : 863px) {.modal-warenkorb .head.warenkorb div.angaben p.txt span.ang { width: 135px;  } }


.modal-warenkorb .head.warenkorb div.angaben.kontakt p.txt span.ang { text-align: left;margin: 0 0 0 12px; }
.modal-warenkorb .head.warenkorb div.angaben.kontakt a { color: #9B0011 !important; }
.modal-warenkorb .head.warenkorb div.angaben.kontakt a:hover{text-decoration: underline !important;}

.modal-warenkorb .head.warenkorb div.angaben p.txt a { display: block; float: left; text-align: left;margin: 0 0 0 12px; }
.modal-warenkorb .head.warenkorb div.angaben p.txt a.mail, .modal-warenkorb .head.warenkorb div.angaben p.txt span.ang_zwei { clear: both; float: left; text-align: left;margin: 0 0 0 147px; }

.modal-warenkorb .head.warenkorb p.contact a { display: block; float: left; }
.modal-warenkorb .head.warenkorb p.basket span { color: #000; display: block; float: left;}
.modal-warenkorb .head.warenkorb p.basket span.flaschen { margin: 0 0 0 6%; width: 44%; height: 26px; text-align: left;}
.modal-warenkorb .head.warenkorb p.basket span.wert { margin: 0 0 0 0%; width: 44%; height: 26px; text-align: right;}
.modal-warenkorb div.footer.warenkorb {background-color: hsla(46.63, 0%, 98%, 0.91);margin: 18px 0 0 0;border-top: 2px solid #d12600;height: 45px;margin: 12px 0 23px 0;}
.modal-warenkorb div.footer.warenkorb div.zeile p.basket {float: left;width: 59%;margin: 13px 3% 0 3%;border-bottom: 3px solid #d12600;}
.modal-warenkorb div.footer.warenkorb div.zeile p.basket span {display: block;float: left;}
.modal-warenkorb div.footer.warenkorb div.zeile p.basket span.flaschen {margin: 0 0 0 6%;width: 44%;height: 18px;text-align: left;}
.modal-warenkorb div.footer.warenkorb div.zeile p.basket span.wert {margin: 0 0 0 0%;width: 40%;height: 18px;text-align: right;}
.modal-warenkorb div.footer.warenkorb div.zeile div.zurueck { margin: 6px 0 0 3%; }
.modal-warenkorb div.footer.warenkorb div.zeile div.kasse.modal-warenkorb div.footer.warenkorb div.zeile div.kasse,
.modal-warenkorb div.footer.warenkorb div.zeile div.kasse-gp-daten  { margin: 6px 0 0 0; }
.modal-warenkorb div.footer.warenkorb div.zeile div.zurueck, .modal-warenkorb div.footer.warenkorb div.zeile div.kasse, .modal-warenkorb div.footer.warenkorb div.zeile div.kasse-gp-daten {float: left;width: 15%;height: 64%;background-color: #d12600;border-radius: 3px;}
.modal-warenkorb div.footer.warenkorb div.zeile div.zurueck p, .modal-warenkorb div.footer.warenkorb div.zeile div.kasse p, .modal-warenkorb div.footer.warenkorb div.zeile div.kasse-gp-daten p {color: #FFF;padding: 7px 0 5px 0;margin: 0;}

@media(max-width:767px) {
.modal-menue.geklappt:not(.hideMenue) {display: block;}
.modal-menue:not(.geklappt) {display: none;}
.content-menue-detail .inner, .content-menue-detail .inner div { height: calc((98vw - 30px)*0.075 ); }
div.men-left-lexikon:not(.geklappt) {display: none;}
}
@media(min-width:768px) {
.content-menue-detail .inner, .content-menue-detail .inner div { height: calc((77vw - 30px)*0.07 + 0px); max-height: 84px;}
}

.col-footer-desc p.subscribe-super-offers {color: #254a1a;}
.content-menue-detail .inner { clear: both; width: 100%; margin: 0 0 0 0 ; background-color: #1a1a1a;}
.content-menue-detail .inner div { float: left; width: 33.33%; margin: 0px 0 0 0;}
.content-menue-detail .inner div.left, .content-menue-detail div.middle  { border-right: 1px solid #FFF;}
.content-menue-detail .inner div img {display: block; width: 80%; margin: 0 auto 0 auto; }
.bread { padding-left: 0 !important; padding-right: 0 !important;}
.cat_head .img-full, .cat_head .txt-full  { display: none;    }
.zeig-bei-wunsch { display: none;    }
.fancy { width: 640px;  margin: 30px 0 0 0; padding: 0 0 0 15px  !important; }
.row-fancy {max-width: 250px; padding: 0 12px 0 0 !important; margin: 0 0px 0 0; }
.row-fancy.right {margin: 0 0px 0 0; }
.row-fancy img { margin: 0 0 20px 0;}
.fancybox-caption__body h2 { color: #FFF !important; }
.fancybox-caption__body p { color: #FFF; width: 80%; margin: 0 auto 0 auto; text-align: center;}
.panel-heading { padding: 10px 15px 3px 15px; border-radius: 4px;}
div.main-wine-row p.subhead { width: 100%;}

@font-face {font-family: 'roboto';font-style: normal;font-weight: 300;font-display: auto;src: url('/_/css/fonts/roboto-v30-latin-300.eot'); /* IE9 Compat Modes */src: local(''),url('/_/css/fonts/roboto-v30-latin-300.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */url('/_/css/fonts/roboto-v30-latin-300.woff2') format('woff2'), /* Super Modern Browsers */url('/_/css/fonts/roboto-v30-latin-300.woff') format('woff'), /* Modern Browsers */url('/_/css/fonts/roboto-v30-latin-300.ttf') format('truetype'), /* Safari, Android, iOS */url('/_/css/fonts/roboto-v30-latin-300.svg') format('svg'); /* Legacy iOS */}
@font-face {font-family: 'roboto';font-style: italic;font-weight: 400;font-display: auto;src: url('/_/css/fonts/roboto-v30-latin-300italic.eot'); /* IE9 Compat Modes */src: local(''),url('/_/css/fonts/roboto-v30-latin-italic.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */url('/_/css/fonts/roboto-v30-latin-italic.woff2') format('woff2'), /* Super Modern Browsers */url('/_/css/fonts/roboto-v30-latin-italic.woff') format('woff'), /* Modern Browsers */url('/_/css/fonts/roboto-v30-latin-italic.ttf') format('truetype'), /* Safari, Android, iOS */url('/_/css/fonts/roboto-v30-latin-italic.svg') format('svg'); /* Legacy iOS */}
@font-face {font-family: 'roboto';font-style: normal;font-weight: 400;font-display: auto;src: url('/_/css/fonts/roboto-v30-latin-regular.eot'); /* IE9 Compat Modes */src: local(''),url('/_/css/fonts/roboto-v30-latin-regular.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */url('/_/css/fonts/roboto-v30-latin-regular.woff2') format('woff2'), /* Super Modern Browsers */url('/_/css/fonts/roboto-v30-latin-regular.woff') format('woff'), /* Modern Browsers */url('/_/css/fonts/roboto-v30-latin-regular.ttf') format('truetype'), /* Safari, Android, iOS */url('/_/css/fonts/roboto-v30-latin-regular.svg') format('svg'); /* Legacy iOS */}
@font-face {font-family: 'roboto';font-style: italic;font-weight: 500;font-display: auto;src: url('/_/css/fonts/roboto-v30-latin-500italic.eot'); src: local(''),url('/_/css/fonts/roboto-v30-latin-500italic.eot?#iefix') format('embedded-opentype'),url('/_/css/fonts/roboto-v30-latin-500italic.woff2') format('woff2'),url('/_/css/fonts/roboto-v30-latin-500italic.woff') format('woff'),url('/_/css/fonts/roboto-v30-latin-500italic.ttf') format('truetype'),url('/_/css/fonts/roboto-v30-latin-500italic.svg') format('svg');}
@font-face {font-family: 'roboto';font-style: normal;font-weight: 500;font-display: auto;src: url('/_/css/fonts/roboto-v30-latin-500.eot');src: local(''),url('/_/css/fonts/roboto-v30-latin-500.eot?#iefix') format('embedded-opentype'), url('/_/css/fonts/roboto-v30-latin-500.woff2') format('woff2'), url('/_/css/fonts/roboto-v30-latin-500.woff') format('woff'),url('/_/css/fonts/roboto-v30-latin-500.ttf') format('truetype'),url('/_/css/fonts/roboto-v30-latin-500.svg') format('svg');}
@font-face {font-family: 'roboto';font-style: normal;font-weight: 700;font-display: auto;src: url('/_/css/fonts/roboto-v30-latin-700.eot');src: local(''),url('/_/css/fonts/roboto-v30-latin-700.eot?#iefix') format('embedded-opentype'),url('/_/css/fonts/roboto-v30-latin-700.woff2') format('woff2'),url('/_/css/fonts/roboto-v30-latin-700.woff') format('woff'),url('/_/css/fonts/roboto-v30-latin-700.ttf') format('truetype'),url('/_/css/fonts/roboto-v30-latin-700.svg') format('svg');}
@font-face {font-family: 'roboto';font-style: italic;font-weight: 700;font-display: auto;src: url('/_/css/fonts/roboto-v30-latin-700.eot');src: local(''),url('/_/css/fonts/roboto-v30-latin-700italic.eot?#iefix') format('embedded-opentype'),url('/_/css/fonts/roboto-v30-latin-700italic.woff2') format('woff2'),url('/_/css/fonts/roboto-v30-latin-700italic.woff') format('woff'), url('/_/css/fonts/roboto-v30-latin-700italic.ttf') format('truetype'),url('/_/css/fonts/roboto-v30-latin-700italic.svg') format('svg');}
@font-face {font-display: swap;font-family: 'Roboto Slab';font-style: normal;font-weight: 300;font-display: auto;src: url('/_/css/fonts/roboto-slab-v24-latin-300.eot');src: url('/_/css/fonts/roboto-slab-v24-latin-300.eot?#iefix') format('embedded-opentype'),url('/_/css/fonts/roboto-slab-v24-latin-300.woff2') format('woff2'),url('/_/css/fonts/roboto-slab-v24-latin-300.woff') format('woff'),url('/_/css/fonts/roboto-slab-v24-latin-300.ttf') format('truetype'),url('/_/css/fonts/roboto-slab-v24-latin-300.svg#RobotoSlab') format('svg');}
@font-face {font-display: swap;font-family: 'Roboto Slab';font-style: normal;font-weight: 400;font-display: auto;src: url('/_/css/fonts/roboto-slab-v24-latin-regular.eot');src: url('/_/css/fonts/roboto-slab-v24-latin-regular.eot?#iefix') format('embedded-opentype'),url('/_/css/fonts/roboto-slab-v24-latin-regular.woff2') format('woff2'),url('/_/css/fonts/roboto-slab-v24-latin-regular.woff') format('woff'),url('/_/css/fonts/roboto-slab-v24-latin-regular.ttf') format('truetype'),url('/_/css/fonts/roboto-slab-v24-latin-regular.svg#RobotoSlab') format('svg');}
@font-face {font-display: swap; font-family: 'Roboto Slab';font-style: normal;font-weight: 500;font-display: auto;src: url('/_/css/fonts/roboto-slab-v24-latin-500.eot'); src: url('/_/css/fonts/roboto-slab-v24-latin-500.eot?#iefix') format('embedded-opentype'),url('/_/css/fonts/roboto-slab-v24-latin-500.woff2') format('woff2'),url('/_/css/fonts/roboto-slab-v24-latin-500.woff') format('woff'),url('/_/css/fonts/roboto-slab-v24-latin-500.ttf') format('truetype'),url('/_/css/fonts/roboto-slab-v24-latin-500.svg#RobotoSlab') format('svg');}
@font-face {font-display: swap; font-family: 'Roboto Slab';font-style: normal;font-weight: 700;font-display: auto;src: url('/_/css/fonts/roboto-slab-v24-latin-700.eot');src: url('/_/css/fonts/roboto-slab-v24-latin-700.eot?#iefix') format('embedded-opentype'),url('/_/css/fonts/roboto-slab-v24-latin-700.woff2') format('woff2'),url('/_/css/fonts/roboto-slab-v24-latin-700.woff') format('woff'),url('/_/css/fonts/roboto-slab-v24-latin-700.ttf') format('truetype'),url('/_/css/fonts/roboto-slab-v24-latin-700.svg#RobotoSlab') format('svg'); }

[class^="ti-"], [class*=" ti-"] {font-family: 'themify';speak: none;font-style: normal;font-weight: normal;font-variant: normal;text-transform: none;line-height: 1;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;}

.carousel{position:relative;box-sizing:border-box}.carousel *,.carousel *:before,.carousel *:after{box-sizing:inherit}.carousel.is-draggable{cursor:move;cursor:grab}.carousel.is-dragging{cursor:move;cursor:grabbing}.carousel__viewport{position:relative;overflow:hidden;max-width:100%;max-height:100%}.carousel__track{display:flex}.carousel__slide{flex:0 0 auto;width:var(--carousel-slide-width, 60%);max-width:100%;padding:1rem;position:relative;overflow-x:hidden;overflow-y:auto;overscroll-behavior:contain}.has-dots{margin-bottom:calc(0.5rem + 22px)}.carousel__dots{margin:0 auto;padding:0;position:absolute;top:calc(100% + 0.5rem);left:0;right:0;display:flex;justify-content:center;list-style:none;user-select:none}.carousel__dots .carousel__dot{margin:0;padding:0;display:block;position:relative;width:22px;height:22px;cursor:pointer}.carousel__dots .carousel__dot:after{content:"";width:8px;height:8px;border-radius:50%;position:absolute;top:50%;left:50%;transform:translate(-50%, -50%);background-color:currentColor;opacity:.25;transition:opacity .15s ease-in-out}.carousel__dots .carousel__dot.is-selected:after{opacity:1}.carousel__button{width:var(--carousel-button-width, 48px);height:var(--carousel-button-height, 48px);padding:0;border:0;display:flex;justify-content:center;align-items:center;pointer-events:all;cursor:pointer;color:var(--carousel-button-color, currentColor);background:var(--carousel-button-bg, transparent);border-radius:var(--carousel-button-border-radius, 50%);box-shadow:var(--carousel-button-shadow, none);transition:opacity .15s ease}.carousel__button.is-prev,.carousel__button.is-next{position:absolute;top:50%;transform:translateY(-50%)}.carousel__button.is-prev{left:10px}.carousel__button.is-next{right:10px}.carousel__button[disabled]{cursor:default;opacity:.3}.carousel__button svg{width:var(--carousel-button-svg-width, 50%);height:var(--carousel-button-svg-height, 50%);fill:none;stroke:currentColor;stroke-width:var(--carousel-button-svg-stroke-width, 1.5);stroke-linejoin:bevel;stroke-linecap:round;filter:var(--carousel-button-svg-filter, none);pointer-events:none}html.with-fancybox{scroll-behavior:auto}body.compensate-for-scrollbar{overflow:hidden !important;touch-action:none}.fancybox__container{position:fixed;top:0;left:0;bottom:0;right:0;direction:ltr;margin:0;padding:env(safe-area-inset-top, 0px) env(safe-area-inset-right, 0px) env(safe-area-inset-bottom, 0px) env(safe-area-inset-left, 0px);box-sizing:border-box;display:flex;flex-direction:column;color:var(--fancybox-color, #fff);-webkit-tap-highlight-color:rgba(0,0,0,0);overflow:hidden;z-index:1050;outline:none;transform-origin:top left;--carousel-button-width: 48px;--carousel-button-height: 48px;--carousel-button-svg-width: 24px;--carousel-button-svg-height: 24px;--carousel-button-svg-stroke-width: 2.5;--carousel-button-svg-filter: drop-shadow(1px 1px 1px rgba(0, 0, 0, 0.4))}.fancybox__container *,.fancybox__container *::before,.fancybox__container *::after{box-sizing:inherit}.fancybox__container :focus{outline:none}body:not(.is-using-mouse) .fancybox__container :focus{box-shadow:0 0 0 1px #fff,0 0 0 2px var(--fancybox-accent-color, rgba(1, 210, 232, 0.94))}@media all and (min-width: 1024px){.fancybox__container{--carousel-button-width:48px;--carousel-button-height:48px;--carousel-button-svg-width:27px;--carousel-button-svg-height:27px}}.fancybox__backdrop{position:absolute;top:0;right:0;bottom:0;left:0;z-index:-1;background:var(--fancybox-bg, rgba(24, 24, 27, 0.92))}.fancybox__carousel{position:relative;flex:1 1 auto;min-height:0;height:100%;z-index:10}.fancybox__carousel.has-dots{margin-bottom:calc(0.5rem + 22px)}.fancybox__viewport{position:relative;width:100%;height:100%;overflow:visible;cursor:default}.fancybox__track{display:flex;height:100%}.fancybox__slide{flex:0 0 auto;width:100%;max-width:100%;margin:0;padding:48px 8px 8px 8px;position:relative;overscroll-behavior:contain;display:flex;flex-direction:column;outline:0;overflow:auto;--carousel-button-width: 36px;--carousel-button-height: 36px;--carousel-button-svg-width: 22px;--carousel-button-svg-height: 22px}.fancybox__slide::before,.fancybox__slide::after{content:"";flex:0 0 0;margin:auto}@media all and (min-width: 1024px){.fancybox__slide{padding:64px 100px}}.fancybox__content{margin:0 env(safe-area-inset-right, 0px) 0 env(safe-area-inset-left, 0px);padding:36px;color:var(--fancybox-content-color, #374151);background:var(--fancybox-content-bg, #fff);position:relative;align-self:center;display:flex;flex-direction:column;z-index:20}.fancybox__content :focus:not(.carousel__button.is-close){outline:thin dotted;box-shadow:none}.fancybox__caption{align-self:center;max-width:100%;margin:0;padding:1rem 0 0 0;line-height:1.375;color:var(--fancybox-color, currentColor);visibility:visible;cursor:auto;flex-shrink:0;overflow-wrap:anywhere}.is-loading .fancybox__caption{visibility:hidden}.fancybox__container>.carousel__dots{top:100%;color:var(--fancybox-color, #fff)}.fancybox__nav .carousel__button{z-index:40}.fancybox__nav .carousel__button.is-next{right:8px}@media all and (min-width: 1024px){.fancybox__nav .carousel__button.is-next{right:40px}}.fancybox__nav .carousel__button.is-prev{left:8px}@media all and (min-width: 1024px){.fancybox__nav .carousel__button.is-prev{left:40px}}.carousel__button.is-close{position:absolute;top:8px;right:8px;top:calc(env(safe-area-inset-top, 0px) + 8px);right:calc(env(safe-area-inset-right, 0px) + 8px);z-index:40}@media all and (min-width: 1024px){.carousel__button.is-close{right:40px}}.fancybox__content>.carousel__button.is-close{position:absolute;top:-40px;right:0;color:var(--fancybox-color, #fff)}.fancybox__no-click,.fancybox__no-click button{pointer-events:none}.fancybox__spinner{position:absolute;top:50%;left:50%;transform:translate(-50%, -50%);width:50px;height:50px;color:var(--fancybox-color, currentColor)}.fancybox__slide .fancybox__spinner{cursor:pointer;z-index:1053}.fancybox__spinner svg{animation:fancybox-rotate 2s linear infinite;transform-origin:center center;position:absolute;top:0;right:0;bottom:0;left:0;margin:auto;width:100%;height:100%}.fancybox__spinner svg circle{fill:none;stroke-width:2.75;stroke-miterlimit:10;stroke-dasharray:1,200;stroke-dashoffset:0;animation:fancybox-dash 1.5s ease-in-out infinite;stroke-linecap:round;stroke:currentColor}@keyframes fancybox-rotate{100%{transform:rotate(360deg)}}@keyframes fancybox-dash{0%{stroke-dasharray:1,200;stroke-dashoffset:0}50%{stroke-dasharray:89,200;stroke-dashoffset:-35px}100%{stroke-dasharray:89,200;stroke-dashoffset:-124px}}.fancybox__backdrop,.fancybox__caption,.fancybox__nav,.carousel__dots,.carousel__button.is-close{opacity:var(--fancybox-opacity, 1)}.fancybox__container.is-animated[aria-hidden=false] .fancybox__backdrop,.fancybox__container.is-animated[aria-hidden=false] .fancybox__caption,.fancybox__container.is-animated[aria-hidden=false] .fancybox__nav,.fancybox__container.is-animated[aria-hidden=false] .carousel__dots,.fancybox__container.is-animated[aria-hidden=false] .carousel__button.is-close{animation:.15s ease backwards fancybox-fadeIn}.fancybox__container.is-animated.is-closing .fancybox__backdrop,.fancybox__container.is-animated.is-closing .fancybox__caption,.fancybox__container.is-animated.is-closing .fancybox__nav,.fancybox__container.is-animated.is-closing .carousel__dots,.fancybox__container.is-animated.is-closing .carousel__button.is-close{animation:.15s ease both fancybox-fadeOut}.fancybox-fadeIn{animation:.15s ease both fancybox-fadeIn}.fancybox-fadeOut{animation:.1s ease both fancybox-fadeOut}.fancybox-zoomInUp{animation:.2s ease both fancybox-zoomInUp}.fancybox-zoomOutDown{animation:.15s ease both fancybox-zoomOutDown}.fancybox-throwOutUp{animation:.15s ease both fancybox-throwOutUp}.fancybox-throwOutDown{animation:.15s ease both fancybox-throwOutDown}@keyframes fancybox-fadeIn{from{opacity:0}to{opacity:1}}@keyframes fancybox-fadeOut{to{opacity:0}}@keyframes fancybox-zoomInUp{from{transform:scale(0.97) translate3d(0, 16px, 0);opacity:0}to{transform:scale(1) translate3d(0, 0, 0);opacity:1}}@keyframes fancybox-zoomOutDown{to{transform:scale(0.97) translate3d(0, 16px, 0);opacity:0}}@keyframes fancybox-throwOutUp{to{transform:translate3d(0, -30%, 0);opacity:0}}@keyframes fancybox-throwOutDown{to{transform:translate3d(0, 30%, 0);opacity:0}}.fancybox__carousel .carousel__slide{scrollbar-width:thin;scrollbar-color:#ccc rgba(255,255,255,.1)}.fancybox__carousel .carousel__slide::-webkit-scrollbar{width:8px;height:8px}.fancybox__carousel .carousel__slide::-webkit-scrollbar-track{background-color:rgba(255,255,255,.1)}.fancybox__carousel .carousel__slide::-webkit-scrollbar-thumb{background-color:#ccc;border-radius:2px;box-shadow:inset 0 0 4px rgba(0,0,0,.2)}.fancybox__carousel.is-draggable .fancybox__slide,.fancybox__carousel.is-draggable .fancybox__slide .fancybox__content{cursor:move;cursor:grab}.fancybox__carousel.is-dragging .fancybox__slide,.fancybox__carousel.is-dragging .fancybox__slide .fancybox__content{cursor:move;cursor:grabbing}.fancybox__carousel .fancybox__slide .fancybox__content{cursor:auto}.fancybox__carousel .fancybox__slide.can-zoom_in .fancybox__content{cursor:zoom-in}.fancybox__carousel .fancybox__slide.can-zoom_out .fancybox__content{cursor:zoom-out}.fancybox__carousel .fancybox__slide.is-draggable .fancybox__content{cursor:move;cursor:grab}.fancybox__carousel .fancybox__slide.is-dragging .fancybox__content{cursor:move;cursor:grabbing}.fancybox__image{transform-origin:0 0;user-select:none;transition:none}.has-image .fancybox__content{padding:0;background:rgba(0,0,0,0);min-height:1px}.is-closing .has-image .fancybox__content{overflow:visible}.has-image[data-image-fit=contain]{overflow:visible;touch-action:none}.has-image[data-image-fit=contain] .fancybox__content{flex-direction:row;flex-wrap:wrap}.has-image[data-image-fit=contain] .fancybox__image{max-width:100%;max-height:100%;object-fit:contain}.has-image[data-image-fit=contain-w]{overflow-x:hidden;overflow-y:auto}.has-image[data-image-fit=contain-w] .fancybox__content{min-height:auto}.has-image[data-image-fit=contain-w] .fancybox__image{max-width:100%;height:auto}.has-image[data-image-fit=cover]{overflow:visible;touch-action:none}.has-image[data-image-fit=cover] .fancybox__content{width:100%;height:100%}.has-image[data-image-fit=cover] .fancybox__image{width:100%;height:100%;object-fit:cover}.fancybox__carousel .fancybox__slide.has-iframe .fancybox__content,.fancybox__carousel .fancybox__slide.has-map .fancybox__content,.fancybox__carousel .fancybox__slide.has-pdf .fancybox__content,.fancybox__carousel .fancybox__slide.has-video .fancybox__content,.fancybox__carousel .fancybox__slide.has-html5video .fancybox__content{max-width:100%;flex-shrink:1;min-height:1px;overflow:visible}.fancybox__carousel .fancybox__slide.has-iframe .fancybox__content,.fancybox__carousel .fancybox__slide.has-map .fancybox__content,.fancybox__carousel .fancybox__slide.has-pdf .fancybox__content{width:100%;height:80%}.fancybox__carousel .fancybox__slide.has-video .fancybox__content,.fancybox__carousel .fancybox__slide.has-html5video .fancybox__content{width:960px;height:540px;max-width:100%;max-height:100%}.fancybox__carousel .fancybox__slide.has-map .fancybox__content,.fancybox__carousel .fancybox__slide.has-pdf .fancybox__content,.fancybox__carousel .fancybox__slide.has-video .fancybox__content,.fancybox__carousel .fancybox__slide.has-html5video .fancybox__content{padding:0;background:rgba(24,24,27,.9);color:#fff}.fancybox__carousel .fancybox__slide.has-map .fancybox__content{background:#e5e3df}.fancybox__html5video,.fancybox__iframe{border:0;display:block;height:100%;width:100%;background:rgba(0,0,0,0)}.fancybox-placeholder{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0, 0, 0, 0);white-space:nowrap;border-width:0}.fancybox__thumbs{flex:0 0 auto;position:relative;padding:0px 3px;opacity:var(--fancybox-opacity, 1)}.fancybox__container.is-animated[aria-hidden=false] .fancybox__thumbs{animation:.15s ease-in backwards fancybox-fadeIn}.fancybox__container.is-animated.is-closing .fancybox__thumbs{opacity:0}.fancybox__thumbs .carousel__slide{flex:0 0 auto;width:var(--fancybox-thumbs-width, 96px);margin:0;padding:8px 3px;box-sizing:content-box;display:flex;align-items:center;justify-content:center;overflow:visible;cursor:pointer}.fancybox__thumbs .carousel__slide .fancybox__thumb::after{content:"";position:absolute;top:0;left:0;right:0;bottom:0;border-width:5px;border-style:solid;border-color:var(--fancybox-accent-color, rgba(34, 213, 233, 0.96));opacity:0;transition:opacity .15s ease;border-radius:var(--fancybox-thumbs-border-radius, 4px)}.fancybox__thumbs .carousel__slide.is-nav-selected .fancybox__thumb::after{opacity:.92}.fancybox__thumbs .carousel__slide>*{pointer-events:none;user-select:none}.fancybox__thumb{position:relative;width:100%;padding-top:calc(100%/(var(--fancybox-thumbs-ratio, 1.5)));background-size:cover;background-position:center center;background-color:rgba(255,255,255,.1);background-repeat:no-repeat;border-radius:var(--fancybox-thumbs-border-radius, 4px)}.fancybox__toolbar{position:absolute;top:0;right:0;left:0;z-index:20;background:linear-gradient(to top, hsla(0deg, 0%, 0%, 0) 0%, hsla(0deg, 0%, 0%, 0.006) 8.1%, hsla(0deg, 0%, 0%, 0.021) 15.5%, hsla(0deg, 0%, 0%, 0.046) 22.5%, hsla(0deg, 0%, 0%, 0.077) 29%, hsla(0deg, 0%, 0%, 0.114) 35.3%, hsla(0deg, 0%, 0%, 0.155) 41.2%, hsla(0deg, 0%, 0%, 0.198) 47.1%, hsla(0deg, 0%, 0%, 0.242) 52.9%, hsla(0deg, 0%, 0%, 0.285) 58.8%, hsla(0deg, 0%, 0%, 0.326) 64.7%, hsla(0deg, 0%, 0%, 0.363) 71%, hsla(0deg, 0%, 0%, 0.394) 77.5%, hsla(0deg, 0%, 0%, 0.419) 84.5%, hsla(0deg, 0%, 0%, 0.434) 91.9%, hsla(0deg, 0%, 0%, 0.44) 100%);padding:0;touch-action:none;display:flex;justify-content:space-between;--carousel-button-svg-width: 20px;--carousel-button-svg-height: 20px;opacity:var(--fancybox-opacity, 1);text-shadow:var(--fancybox-toolbar-text-shadow, 1px 1px 1px rgba(0, 0, 0, 0.4))}@media all and (min-width: 1024px){.fancybox__toolbar{padding:8px}}.fancybox__container.is-animated[aria-hidden=false] .fancybox__toolbar{animation:.15s ease-in backwards fancybox-fadeIn}.fancybox__container.is-animated.is-closing .fancybox__toolbar{opacity:0}.fancybox__toolbar__items{display:flex}.fancybox__toolbar__items--left{margin-right:auto}.fancybox__toolbar__items--center{position:absolute;left:50%;transform:translateX(-50%)}.fancybox__toolbar__items--right{margin-left:auto}@media(max-width: 640px){.fancybox__toolbar__items--center:not(:last-child){display:none}}.fancybox__counter{min-width:72px;padding:0 10px;line-height:var(--carousel-button-height, 48px);text-align:center;font-size:17px;font-variant-numeric:tabular-nums;-webkit-font-smoothing:subpixel-antialiased}.fancybox__progress{background:var(--fancybox-accent-color, rgba(34, 213, 233, 0.96));height:3px;left:0;position:absolute;right:0;top:0;transform:scaleX(0);transform-origin:0;transition-property:transform;transition-timing-function:linear;z-index:30;user-select:none}.fancybox__container:fullscreen::backdrop{opacity:0}.fancybox__button--fullscreen g:nth-child(2){display:none}.fancybox__container:fullscreen .fancybox__button--fullscreen g:nth-child(1){display:none}.fancybox__container:fullscreen .fancybox__button--fullscreen g:nth-child(2){display:block}.fancybox__button--slideshow g:nth-child(2){display:none}.fancybox__container.has-slideshow .fancybox__button--slideshow g:nth-child(1){display:none}.fancybox__container.has-slideshow .fancybox__button--slideshow g:nth-child(2){display:block}
body.compensate-for-scrollbar{overflow:hidden}.fancybox-active{height:auto}.fancybox-is-hidden{left:-9999px;margin:0;position:absolute!important;top:-9999px;visibility:hidden}.fancybox-container{-webkit-backface-visibility:hidden;height:100%;left:0;outline:none;position:fixed;-webkit-tap-highlight-color:transparent;top:0;-ms-touch-action:manipulation;touch-action:manipulation;transform:translateZ(0);width:100%;z-index:99992}.fancybox-container *{box-sizing:border-box}.fancybox-bg,.fancybox-inner,.fancybox-outer,.fancybox-stage{bottom:0;left:0;position:absolute;right:0;top:0}.fancybox-outer{-webkit-overflow-scrolling:touch;overflow-y:auto}.fancybox-bg{background:#1e1e1e;opacity:0;transition-duration:inherit;transition-property:opacity;transition-timing-function:cubic-bezier(.47,0,.74,.71)}.fancybox-is-open .fancybox-bg{opacity:.9;transition-timing-function:cubic-bezier(.22,.61,.36,1)}.fancybox-caption,.fancybox-infobar,.fancybox-navigation .fancybox-button,.fancybox-toolbar{direction:ltr;opacity:0;position:absolute;transition:opacity .25s ease,visibility 0s ease .25s;visibility:hidden;z-index:99997}.fancybox-show-caption .fancybox-caption,.fancybox-show-infobar .fancybox-infobar,.fancybox-show-nav .fancybox-navigation .fancybox-button,.fancybox-show-toolbar .fancybox-toolbar{opacity:1;transition:opacity .25s ease 0s,visibility 0s ease 0s;visibility:visible}.fancybox-infobar{color:#ccc;font-size:13px;-webkit-font-smoothing:subpixel-antialiased;height:44px;left:0;line-height:44px;min-width:44px;mix-blend-mode:difference;padding:0 10px;pointer-events:none;top:0;-webkit-touch-callout:none;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}.fancybox-toolbar{right:0;top:0}.fancybox-stage{direction:ltr;overflow:visible;transform:translateZ(0);z-index:99994}.fancybox-is-open .fancybox-stage{overflow:hidden}.fancybox-slide{-webkit-backface-visibility:hidden;display:none;height:100%;left:0;outline:none;overflow:auto;-webkit-overflow-scrolling:touch;padding:44px;position:absolute;text-align:center;top:0;transition-property:transform,opacity;white-space:normal;width:100%;z-index:99994}.fancybox-slide:before{content:"";display:inline-block;font-size:0;height:100%;vertical-align:middle;width:0}.fancybox-is-sliding .fancybox-slide,.fancybox-slide--current,.fancybox-slide--next,.fancybox-slide--previous{display:block}.fancybox-slide--image{overflow:hidden;padding:44px 0}.fancybox-slide--image:before{display:none}.fancybox-slide--html{padding:6px}.fancybox-content{background:#fff;display:inline-block;margin:0;max-width:100%;overflow:auto;-webkit-overflow-scrolling:touch;padding:44px;position:relative;text-align:left;vertical-align:middle}.fancybox-slide--image .fancybox-content{animation-timing-function:cubic-bezier(.5,0,.14,1);-webkit-backface-visibility:hidden;background:transparent;background-repeat:no-repeat;background-size:100% 100%;left:0;max-width:none;overflow:visible;padding:0;position:absolute;top:0;transform-origin:top left;transition-property:transform,opacity;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;z-index:99995}.fancybox-can-zoomOut .fancybox-content{cursor:zoom-out}.fancybox-can-zoomIn .fancybox-content{cursor:zoom-in}.fancybox-can-pan .fancybox-content,.fancybox-can-swipe .fancybox-content{cursor:grab}.fancybox-is-grabbing .fancybox-content{cursor:grabbing}.fancybox-container [data-selectable=true]{cursor:text}.fancybox-image,.fancybox-spaceball{background:transparent;border:0;height:100%;left:0;margin:0;max-height:none;max-width:none;padding:0;position:absolute;top:0;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;width:100%}.fancybox-spaceball{z-index:1}.fancybox-slide--iframe .fancybox-content,.fancybox-slide--map .fancybox-content,.fancybox-slide--pdf .fancybox-content,.fancybox-slide--video .fancybox-content{height:100%;overflow:visible;padding:0;width:100%}.fancybox-slide--video .fancybox-content{background:#000}.fancybox-slide--map .fancybox-content{background:#e5e3df}.fancybox-slide--iframe .fancybox-content{background:#fff}.fancybox-iframe,.fancybox-video{background:transparent;border:0;display:block;height:100%;margin:0;overflow:hidden;padding:0;width:100%}.fancybox-iframe{left:0;position:absolute;top:0}.fancybox-error{background:#fff;cursor:default;max-width:400px;padding:40px;width:100%}.fancybox-error p{color:#444;font-size:16px;line-height:20px;margin:0;padding:0}.fancybox-button{background:rgba(30,30,30,.6);border:0;border-radius:0;box-shadow:none;cursor:pointer;display:inline-block;height:44px;margin:0;padding:10px;position:relative;transition:color .2s;vertical-align:top;visibility:inherit;width:44px}.fancybox-button,.fancybox-button:link,.fancybox-button:visited{color:#ccc}.fancybox-button:hover{color:#fff}.fancybox-button:focus{outline:none}.fancybox-button.fancybox-focus{outline:1px dotted}.fancybox-button[disabled],.fancybox-button[disabled]:hover{color:#888;cursor:default;outline:none}.fancybox-button div{height:100%}.fancybox-button svg{display:block;height:100%;overflow:visible;position:relative;width:100%}.fancybox-button svg path{fill:currentColor;stroke-width:0}.fancybox-button--fsenter svg:nth-child(2),.fancybox-button--fsexit svg:first-child,.fancybox-button--pause svg:first-child,.fancybox-button--play svg:nth-child(2){display:none}.fancybox-progress{background:#ff5268;height:2px;left:0;position:absolute;right:0;top:0;transform:scaleX(0);transform-origin:0;transition-property:transform;transition-timing-function:linear;z-index:99998}.fancybox-close-small{background:transparent;border:0;border-radius:0;color:#ccc;cursor:pointer;opacity:.8;padding:8px;position:absolute;right:-12px;top:-44px;z-index:401}.fancybox-close-small:hover{color:#fff;opacity:1}.fancybox-slide--html .fancybox-close-small{color:currentColor;padding:10px;right:0;top:0}.fancybox-slide--image.fancybox-is-scaling .fancybox-content{overflow:hidden}.fancybox-is-scaling .fancybox-close-small,.fancybox-is-zoomable.fancybox-can-pan .fancybox-close-small{display:none}.fancybox-navigation .fancybox-button{background-clip:content-box;height:100px;opacity:0;position:absolute;top:calc(50% - 50px);width:70px}.fancybox-navigation .fancybox-button div{padding:7px}.fancybox-navigation .fancybox-button--arrow_left{left:0;left:env(safe-area-inset-left);padding:31px 26px 31px 6px}.fancybox-navigation .fancybox-button--arrow_right{padding:31px 6px 31px 26px;right:0;right:env(safe-area-inset-right)}.fancybox-caption{background:linear-gradient(0deg,rgba(0,0,0,.85) 0,rgba(0,0,0,.3) 50%,rgba(0,0,0,.15) 65%,rgba(0,0,0,.075) 75.5%,rgba(0,0,0,.037) 82.85%,rgba(0,0,0,.019) 88%,transparent);bottom:0;color:#eee;font-size:14px;font-weight:400;left:0;line-height:1.5;padding:75px 44px 25px;pointer-events:none;right:0;text-align:center;z-index:99996}@supports (padding:max(0px)){.fancybox-caption{padding:75px max(44px,env(safe-area-inset-right)) max(25px,env(safe-area-inset-bottom)) max(44px,env(safe-area-inset-left))}}.fancybox-caption--separate{margin-top:-50px}.fancybox-caption__body{max-height:50vh;overflow:auto;pointer-events:all}.fancybox-caption a,.fancybox-caption a:link,.fancybox-caption a:visited{color:#ccc;text-decoration:none}.fancybox-caption a:hover{color:#fff;text-decoration:underline}.fancybox-loading{animation:a 1s linear infinite;background:transparent;border:4px solid #888;border-bottom-color:#fff;border-radius:50%;height:50px;left:50%;margin:-25px 0 0 -25px;opacity:.7;padding:0;position:absolute;top:50%;width:50px;z-index:99999}@keyframes a{to{transform:rotate(1turn)}}.fancybox-animated{transition-timing-function:cubic-bezier(0,0,.25,1)}.fancybox-fx-slide.fancybox-slide--previous{opacity:0;transform:translate3d(-100%,0,0)}.fancybox-fx-slide.fancybox-slide--next{opacity:0;transform:translate3d(100%,0,0)}.fancybox-fx-slide.fancybox-slide--current{opacity:1;transform:translateZ(0)}.fancybox-fx-fade.fancybox-slide--next,.fancybox-fx-fade.fancybox-slide--previous{opacity:0;transition-timing-function:cubic-bezier(.19,1,.22,1)}.fancybox-fx-fade.fancybox-slide--current{opacity:1}.fancybox-fx-zoom-in-out.fancybox-slide--previous{opacity:0;transform:scale3d(1.5,1.5,1.5)}.fancybox-fx-zoom-in-out.fancybox-slide--next{opacity:0;transform:scale3d(.5,.5,.5)}.fancybox-fx-zoom-in-out.fancybox-slide--current{opacity:1;transform:scaleX(1)}.fancybox-fx-rotate.fancybox-slide--previous{opacity:0;transform:rotate(-1turn)}.fancybox-fx-rotate.fancybox-slide--next{opacity:0;transform:rotate(1turn)}.fancybox-fx-rotate.fancybox-slide--current{opacity:1;transform:rotate(0deg)}.fancybox-fx-circular.fancybox-slide--previous{opacity:0;transform:scale3d(0,0,0) translate3d(-100%,0,0)}.fancybox-fx-circular.fancybox-slide--next{opacity:0;transform:scale3d(0,0,0) translate3d(100%,0,0)}.fancybox-fx-circular.fancybox-slide--current{opacity:1;transform:scaleX(1) translateZ(0)}.fancybox-fx-tube.fancybox-slide--previous{transform:translate3d(-100%,0,0) scale(.1) skew(-10deg)}.fancybox-fx-tube.fancybox-slide--next{transform:translate3d(100%,0,0) scale(.1) skew(10deg)}.fancybox-fx-tube.fancybox-slide--current{transform:translateZ(0) scale(1)}@media (max-height:576px){.fancybox-slide{padding-left:6px;padding-right:6px}.fancybox-slide--image{padding:6px 0}.fancybox-close-small{right:-6px}.fancybox-slide--image .fancybox-close-small{background:#4e4e4e;color:#f2f4f6;height:36px;opacity:1;padding:6px;right:0;top:0;width:36px}.fancybox-caption{padding-left:12px;padding-right:12px}@supports (padding:max(0px)){.fancybox-caption{padding-left:max(12px,env(safe-area-inset-left));padding-right:max(12px,env(safe-area-inset-right))}}}.fancybox-share{background:#f4f4f4;border-radius:3px;max-width:90%;padding:30px;text-align:center}.fancybox-share h1{color:#222;font-size:35px;font-weight:700;margin:0 0 20px}.fancybox-share p{margin:0;padding:0}.fancybox-share__button{border:0;border-radius:3px;display:inline-block;font-size:14px;font-weight:700;line-height:40px;margin:0 5px 10px;min-width:130px;padding:0 15px;text-decoration:none;transition:all .2s;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;white-space:nowrap}.fancybox-share__button:link,.fancybox-share__button:visited{color:#fff}.fancybox-share__button:hover{text-decoration:none}.fancybox-share__button--fb{background:#3b5998}.fancybox-share__button--fb:hover{background:#344e86}.fancybox-share__button--pt{background:#bd081d}.fancybox-share__button--pt:hover{background:#aa0719}.fancybox-share__button--tw{background:#1da1f2}.fancybox-share__button--tw:hover{background:#0d95e8}.fancybox-share__button svg{height:25px;margin-right:7px;position:relative;top:-1px;vertical-align:middle;width:25px}.fancybox-share__button svg path{fill:#fff}.fancybox-share__input{background:transparent;border:0;border-bottom:1px solid #d7d7d7;border-radius:0;color:#5d5b5b;font-size:14px;margin:10px 0 0;outline:none;padding:10px 15px;width:100%}.fancybox-thumbs{background:#ddd;bottom:0;display:none;margin:0;-webkit-overflow-scrolling:touch;-ms-overflow-style:-ms-autohiding-scrollbar;padding:2px 2px 4px;position:absolute;right:0;-webkit-tap-highlight-color:rgba(0,0,0,0);top:0;width:212px;z-index:99995}.fancybox-thumbs-x{overflow-x:auto;overflow-y:hidden}.fancybox-show-thumbs .fancybox-thumbs{display:block}.fancybox-show-thumbs .fancybox-inner{right:212px}.fancybox-thumbs__list{font-size:0;height:100%;list-style:none;margin:0;overflow-x:hidden;overflow-y:auto;padding:0;position:absolute;position:relative;white-space:nowrap;width:100%}.fancybox-thumbs-x .fancybox-thumbs__list{overflow:hidden}.fancybox-thumbs-y .fancybox-thumbs__list::-webkit-scrollbar{width:7px}.fancybox-thumbs-y .fancybox-thumbs__list::-webkit-scrollbar-track{background:#fff;border-radius:10px;box-shadow:inset 0 0 6px rgba(0,0,0,.3)}.fancybox-thumbs-y .fancybox-thumbs__list::-webkit-scrollbar-thumb{background:#2a2a2a;border-radius:10px}.fancybox-thumbs__list a{-webkit-backface-visibility:hidden;backface-visibility:hidden;background-color:rgba(0,0,0,.1);background-position:50%;background-repeat:no-repeat;background-size:cover;cursor:pointer;float:left;height:75px;margin:2px;max-height:calc(100% - 8px);max-width:calc(50% - 4px);outline:none;overflow:hidden;padding:0;position:relative;-webkit-tap-highlight-color:transparent;width:100px}.fancybox-thumbs__list a:before{border:6px solid #ff5268;bottom:0;content:"";left:0;opacity:0;position:absolute;right:0;top:0;transition:all .2s cubic-bezier(.25,.46,.45,.94);z-index:99991}.fancybox-thumbs__list a:focus:before{opacity:.5}.fancybox-thumbs__list a.fancybox-thumbs-active:before{opacity:1}@media (max-width:576px){.fancybox-thumbs{width:110px}.fancybox-show-thumbs .fancybox-inner{right:110px}.fancybox-thumbs__list a{max-width:calc(100% - 10px)}}

/*bs*/
.row {display: flex;flex-wrap: wrap;}.row > * {box-sizing: border-box;flex-shrink: 0;width: 100%;max-width: 100%; }.col {flex:1 0 0% }.row-cols-auto > * {flex: 0 0 auto;width:auto }.col-auto {flex: 0 0 auto;width:auto }.col-1 {flex: 0 0 auto;width:8.33333333% }.col-2 {flex: 0 0 auto;width:16.66666667% }.col-3 {flex: 0 0 auto;width:25% }.col-4 {flex: 0 0 auto;width:33.33333333% }.col-5 {flex: 0 0 auto;width:41.66666667% }.col-6 {flex: 0 0 auto;width:50% }.col-7 {flex: 0 0 auto;width:58.33333333% }.col-8 {flex: 0 0 auto;width:66.66666667% }.col-9 {flex: 0 0 auto;width:75% }.col-10 {flex: 0 0 auto;width:83.33333333% }.col-11 {flex: 0 0 auto;width:91.66666667% }.col-12 {flex: 0 0 auto;width:100% }@media (min-width: 576px) {.col-sm {flex:1 0 0% }.col-sm-auto {flex: 0 0 auto;width:auto }.col-sm-1 {flex: 0 0 auto;width:8.33333333% }.col-sm-2 {flex: 0 0 auto;width:16.66666667% }.col-sm-3 {flex: 0 0 auto;width:25% }.col-sm-4 {flex: 0 0 auto;width:33.33333333% }.col-sm-5 {flex: 0 0 auto;width:41.66666667% }.col-sm-6 {flex: 0 0 auto;width:50% }.col-sm-7 {flex: 0 0 auto;width:58.33333333% }.col-sm-8 {flex: 0 0 auto;width:66.66666667% }.col-sm-9 {flex: 0 0 auto;width:75% }.col-sm-10 {flex: 0 0 auto;width:83.33333333% }.col-sm-11 {flex: 0 0 auto;width:91.66666667% }.col-sm-12 {flex: 0 0 auto;width:100% }}@media (min-width: 768px) {.col-md {flex:1 0 0% }.col-md-auto {flex: 0 0 auto;width:auto }.col-md-1 {flex: 0 0 auto;width:8.33333333% }.col-md-2 {flex: 0 0 auto;width:16.66666667% }.col-md-3 {flex: 0 0 auto;width:25% }.col-md-4 {flex: 0 0 auto;width:33.33333333% }.col-md-5 {flex: 0 0 auto;width:41.66666667% }.col-md-6 {flex: 0 0 auto;width:50% }.col-md-7 {flex: 0 0 auto;width:58.33333333% }.col-md-8 {flex: 0 0 auto;width:66.66666667% }.col-md-9 {flex: 0 0 auto;width:75% }.col-md-10 {flex: 0 0 auto;width:83.33333333% }.col-md-11 {flex: 0 0 auto;width:91.66666667% }.col-md-12 {flex: 0 0 auto;width:100% }}@media (min-width: 992px) {.col-lg {flex:1 0 0% }.col-lg-auto {flex: 0 0 auto;width:auto }.col-lg-1 {flex: 0 0 auto;width:8.33333333% }.col-lg-2 {flex: 0 0 auto;width:16.66666667% }.col-lg-3 {flex: 0 0 auto;width:25% }.col-lg-4 {flex: 0 0 auto;width:33.33333333% }.col-lg-5 {flex: 0 0 auto;width:41.66666667% }.col-lg-6 {flex: 0 0 auto;width:50% }.col-lg-7 {flex: 0 0 auto;width:58.33333333% }.col-lg-8 {flex: 0 0 auto;width:66.66666667% }.col-lg-9 {flex: 0 0 auto;width:75% }.col-lg-10 {flex: 0 0 auto;width:83.33333333% }.col-lg-11 {flex: 0 0 auto;width:91.66666667% }.col-lg-12 {flex: 0 0 auto;width:100% }}.d-inline {display:inline !important }.d-inline-block {display:inline-block !important }.d-block {display:block !important }.d-grid {display:grid !important }.d-table {display:table !important }.d-table-row {display:table-row !important }.d-table-cell {display:table-cell !important }.d-flex {display:flex !important }.d-inline-flex {display:inline-flex !important }.d-none {display:none !important }.flex-fill {flex:1 1 auto !important }.flex-row {flex-direction:row !important }.flex-column {flex-direction:column !important }.flex-wrap {flex-wrap:wrap !important }.flex-nowrap {flex-wrap:nowrap !important }.flex-wrap-reverse {flex-wrap:wrap-reverse !important }@media (min-width: 576px) {.d-sm-inline {display:inline !important }.d-sm-inline-block {display:inline-block !important }.d-sm-block {display:block !important }.d-sm-grid {display:grid !important }.d-sm-table {display:table !important }.d-sm-table-row {display:table-row !important }.d-sm-table-cell {display:table-cell !important }.d-sm-flex {display:flex !important }.d-sm-inline-flex {display:inline-flex !important }.d-sm-none {display:none !important }.flex-sm-fill {flex:1 1 auto !important }.flex-sm-row {flex-direction:row !important }.flex-sm-column {flex-direction:column !important }.flex-sm-wrap {flex-wrap:wrap !important }.flex-sm-nowrap {flex-wrap:nowrap !important }.flex-sm-wrap-reverse {flex-wrap:wrap-reverse !important }}@media (min-width: 768px) {.d-md-inline {display:inline !important }.d-md-inline-block {display:inline-block !important }.d-md-block {display:block !important }.d-md-grid {display:grid !important }.d-md-table {display:table !important }.d-md-table-row {display:table-row !important }.d-md-table-cell {display:table-cell !important }.d-md-flex {display:flex !important }.d-md-inline-flex {display:inline-flex !important }.d-md-none {display:none !important }.flex-md-fill {flex:1 1 auto !important }.flex-md-row {flex-direction:row !important }.flex-md-column {flex-direction:column !important }.flex-md-wrap {flex-wrap:wrap !important }.flex-md-nowrap {flex-wrap:nowrap !important }.flex-md-wrap-reverse {flex-wrap:wrap-reverse !important }}@media (min-width: 992px) {.d-lg-inline {display:inline !important }.d-lg-inline-block {display:inline-block !important }.d-lg-block {display:block !important }.d-lg-grid {display:grid !important }.d-lg-table {display:table !important }.d-lg-table-row {display:table-row !important }.d-lg-table-cell {display:table-cell !important }.d-lg-flex {display:flex !important }.d-lg-inline-flex {display:inline-flex !important }.d-lg-none {display:none !important }.flex-lg-fill {flex:1 1 auto !important }.flex-lg-row {flex-direction:row !important }.flex-lg-column {flex-direction:column !important }.flex-lg-wrap {flex-wrap:wrap !important }.flex-lg-nowrap {flex-wrap:nowrap !important }}@media (min-width: 1200px) {.d-xl-inline {display:inline !important }.d-xl-inline-block {display:inline-block !important }.d-xl-block {display:block !important }.d-xl-grid {display:grid !important }.d-xl-table {display:table !important }.d-xl-table-row {display:table-row !important }.d-xl-table-cell {display:table-cell !important }.d-xl-flex {display:flex !important }.d-xl-inline-flex {display:inline-flex !important }.d-xl-none {display:none !important }}
/*jq ui*/
.ui-selectable{-ms-touch-action:none;touch-action:none}.ui-selectable-helper{position:absolute;z-index:100;border:1px dotted black}.ui-helper-hidden{display:none}.ui-helper-hidden-accessible{border:0;clip:rect(0 0 0 0);height:1px;margin:-1px;overflow:hidden;padding:0;position:absolute;width:1px}.ui-helper-reset{margin:0;padding:0;border:0;outline:0;line-height:1.3;text-decoration:none;font-size:100%;list-style:none}.ui-helper-clearfix:before,.ui-helper-clearfix:after{content:"";display:table;border-collapse:collapse}.ui-helper-clearfix:after{clear:both}.ui-helper-zfix{width:100%;height:100%;top:0;left:0;position:absolute;opacity:0;-ms-filter:"alpha(opacity=0)"}.ui-front{z-index:100}.ui-state-disabled{cursor:default!important;pointer-events:none}.ui-icon{display:inline-block;vertical-align:middle;margin-top:-.25em;position:relative;text-indent:-99999px;overflow:hidden;background-repeat:no-repeat}.ui-widget-icon-block{left:50%;margin-left:-8px;display:block}.ui-widget-overlay{position:fixed;top:0;left:0;width:100%;height:100%}.ui-accordion .ui-accordion-header{display:block;cursor:pointer;position:relative;margin:2px 0 0 0;padding:.5em .5em .5em .7em;font-size:100%}.ui-accordion .ui-accordion-content{padding:1em 2.2em;border-top:0;overflow:auto}.ui-autocomplete{position:absolute;top:0;left:0;cursor:default}.ui-menu{list-style:none;padding:0;margin:0;display:block;outline:0}.ui-menu .ui-menu{position:absolute}.ui-menu .ui-menu-item{margin:0;cursor:pointer;list-style-image:url("data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7")}.ui-menu .ui-menu-item-wrapper{position:relative;padding:3px 1em 3px .4em}.ui-menu .ui-menu-divider{margin:5px 0;height:0;font-size:0;line-height:0;border-width:1px 0 0 0}.ui-menu .ui-state-focus,.ui-menu .ui-state-active{margin:-1px}.ui-menu-icons{position:relative}.ui-menu-icons .ui-menu-item-wrapper{padding-left:2em}.ui-menu .ui-icon{position:absolute;top:0;bottom:0;left:.2em;margin:auto 0}.ui-menu .ui-menu-icon{left:auto;right:0}.ui-button{padding:.4em 1em;display:inline-block;position:relative;line-height:normal;margin-right:.1em;cursor:pointer;vertical-align:middle;text-align:center;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;overflow:visible}.ui-button,.ui-button:link,.ui-button:visited,.ui-button:hover,.ui-button:active{text-decoration:none}.ui-button-icon-only{width:2em;box-sizing:border-box;text-indent:-9999px;white-space:nowrap}input.ui-button.ui-button-icon-only{text-indent:0}.ui-button-icon-only .ui-icon{position:absolute;top:50%;left:50%;margin-top:-8px;margin-left:-8px}.ui-button.ui-icon-notext .ui-icon{padding:0;width:2.1em;height:2.1em;text-indent:-9999px;white-space:nowrap}input.ui-button.ui-icon-notext .ui-icon{width:auto;height:auto;text-indent:0;white-space:normal;padding:.4em 1em}input.ui-button::-moz-focus-inner,button.ui-button::-moz-focus-inner{border:0;padding:0}.ui-controlgroup{vertical-align:middle;display:inline-block}.ui-controlgroup > .ui-controlgroup-item{float:left;margin-left:0;margin-right:0}.ui-controlgroup > .ui-controlgroup-item:focus,.ui-controlgroup > .ui-controlgroup-item.ui-visual-focus{z-index:9999}.ui-controlgroup-vertical > .ui-controlgroup-item{display:block;float:none;width:100%;margin-top:0;margin-bottom:0;text-align:left}.ui-controlgroup-vertical .ui-controlgroup-item{box-sizing:border-box}.ui-controlgroup .ui-controlgroup-label{padding:.4em 1em}.ui-controlgroup .ui-controlgroup-label span{font-size:80%}.ui-controlgroup-horizontal .ui-controlgroup-label + .ui-controlgroup-item{border-left:none}.ui-controlgroup-vertical .ui-controlgroup-label + .ui-controlgroup-item{border-top:none}.ui-controlgroup-horizontal .ui-controlgroup-label.ui-widget-content{border-right:none}.ui-controlgroup-vertical .ui-controlgroup-label.ui-widget-content{border-bottom:none}.ui-controlgroup-vertical .ui-spinner-input{width:75%;width:calc( 100% - 2.4em )}.ui-controlgroup-vertical .ui-spinner .ui-spinner-up{border-top-style:solid}.ui-checkboxradio-label .ui-icon-background{box-shadow:inset 1px 1px 1px #ccc;border-radius:.12em;border:none}.ui-checkboxradio-radio-label .ui-icon-background{width:16px;height:16px;border-radius:1em;overflow:visible;border:none}.ui-checkboxradio-radio-label.ui-checkboxradio-checked .ui-icon,.ui-checkboxradio-radio-label.ui-checkboxradio-checked:hover .ui-icon{background-image:none;width:8px;height:8px;border-width:4px;border-style:solid}.ui-checkboxradio-disabled{pointer-events:none}.ui-datepicker{width:17em;padding:.2em .2em 0;display:none}.ui-datepicker .ui-datepicker-header{position:relative;padding:.2em 0}.ui-datepicker .ui-datepicker-prev,.ui-datepicker .ui-datepicker-next{position:absolute;top:2px;width:1.8em;height:1.8em}.ui-datepicker .ui-datepicker-prev-hover,.ui-datepicker .ui-datepicker-next-hover{top:1px}.ui-datepicker .ui-datepicker-prev{left:2px}.ui-datepicker .ui-datepicker-next{right:2px}.ui-datepicker .ui-datepicker-prev-hover{left:1px}.ui-datepicker .ui-datepicker-next-hover{right:1px}.ui-datepicker .ui-datepicker-prev span,.ui-datepicker .ui-datepicker-next span{display:block;position:absolute;left:50%;margin-left:-8px;top:50%;margin-top:-8px}.ui-datepicker .ui-datepicker-title{margin:0 2.3em;line-height:1.8em;text-align:center}.ui-datepicker .ui-datepicker-title select{font-size:1em;margin:1px 0}.ui-datepicker select.ui-datepicker-month,.ui-datepicker select.ui-datepicker-year{width:45%}.ui-datepicker table{width:100%;font-size:.9em;border-collapse:collapse;margin:0 0 .4em}.ui-datepicker th{padding:.7em .3em;text-align:center;font-weight:bold;border:0}.ui-datepicker td{border:0;padding:1px}.ui-datepicker td span,.ui-datepicker td a{display:block;padding:.2em;text-align:right;text-decoration:none}.ui-datepicker .ui-datepicker-buttonpane{background-image:none;margin:.7em 0 0 0;padding:0 .2em;border-left:0;border-right:0;border-bottom:0}.ui-datepicker .ui-datepicker-buttonpane button{float:right;margin:.5em .2em .4em;cursor:pointer;padding:.2em .6em .3em .6em;width:auto;overflow:visible}.ui-datepicker .ui-datepicker-buttonpane button.ui-datepicker-current{float:left}.ui-datepicker.ui-datepicker-multi{width:auto}.ui-datepicker-multi .ui-datepicker-group{float:left}.ui-datepicker-multi .ui-datepicker-group table{width:95%;margin:0 auto .4em}.ui-datepicker-multi-2 .ui-datepicker-group{width:50%}.ui-datepicker-multi-3 .ui-datepicker-group{width:33.3%}.ui-datepicker-multi-4 .ui-datepicker-group{width:25%}.ui-datepicker-multi .ui-datepicker-group-last .ui-datepicker-header,.ui-datepicker-multi .ui-datepicker-group-middle .ui-datepicker-header{border-left-width:0}.ui-datepicker-multi .ui-datepicker-buttonpane{clear:left}.ui-datepicker-row-break{clear:both;width:100%;font-size:0}.ui-datepicker-rtl{direction:rtl}.ui-datepicker-rtl .ui-datepicker-prev{right:2px;left:auto}.ui-datepicker-rtl .ui-datepicker-next{left:2px;right:auto}.ui-datepicker-rtl .ui-datepicker-prev:hover{right:1px;left:auto}.ui-datepicker-rtl .ui-datepicker-next:hover{left:1px;right:auto}.ui-datepicker-rtl .ui-datepicker-buttonpane{clear:right}.ui-datepicker-rtl .ui-datepicker-buttonpane button{float:left}.ui-datepicker-rtl .ui-datepicker-buttonpane button.ui-datepicker-current,.ui-datepicker-rtl .ui-datepicker-group{float:right}.ui-datepicker-rtl .ui-datepicker-group-last .ui-datepicker-header,.ui-datepicker-rtl .ui-datepicker-group-middle .ui-datepicker-header{border-right-width:0;border-left-width:1px}.ui-datepicker .ui-icon{display:block;text-indent:-99999px;overflow:hidden;background-repeat:no-repeat;left:.5em;top:.3em}.ui-selectmenu-menu{padding:0;margin:0;position:absolute;top:0;left:0;display:none}.ui-selectmenu-menu .ui-menu{overflow:auto;overflow-x:hidden;padding-bottom:1px}.ui-selectmenu-menu .ui-menu .ui-selectmenu-optgroup{font-size:1em;font-weight:bold;line-height:1.5;padding:2px 0.4em;margin:0.5em 0 0 0;height:auto;border:0}.ui-selectmenu-open{display:block}.ui-selectmenu-text{display:block;margin-right:20px;overflow:hidden;text-overflow:ellipsis}.ui-selectmenu-button.ui-button{text-align:left;white-space:nowrap;width:14em}.ui-selectmenu-icon.ui-icon{float:right;margin-top:0}.ui-slider{position:relative;text-align:left}.ui-slider .ui-slider-handle{position:absolute;z-index:2;width:1.2em;height:1.2em;cursor:pointer;-ms-touch-action:none;touch-action:none}.ui-slider .ui-slider-range{position:absolute;z-index:1;font-size:.7em;display:block;border:0;background-position:0 0}.ui-slider.ui-state-disabled .ui-slider-handle,.ui-slider.ui-state-disabled .ui-slider-range{filter:inherit}.ui-slider-horizontal{height:.8em}.ui-slider-horizontal .ui-slider-handle{top:-.3em;margin-left:-.6em}.ui-slider-horizontal .ui-slider-range{top:0;height:100%}.ui-slider-horizontal .ui-slider-range-min{left:0}.ui-slider-horizontal .ui-slider-range-max{right:0}.ui-slider-vertical{width:.8em;height:100px}.ui-slider-vertical .ui-slider-handle{left:-.3em;margin-left:0;margin-bottom:-.6em}.ui-slider-vertical .ui-slider-range{left:0;width:100%}.ui-slider-vertical .ui-slider-range-min{bottom:0}.ui-slider-vertical .ui-slider-range-max{top:0}.ui-spinner{position:relative;display:inline-block;overflow:hidden;padding:0;vertical-align:middle}.ui-spinner-input{border:none;background:none;color:inherit;padding:.222em 0;margin:.2em 0;vertical-align:middle;margin-left:.4em;margin-right:2em}.ui-spinner-button{width:1.6em;height:50%;font-size:.5em;padding:0;margin:0;text-align:center;position:absolute;cursor:default;display:block;overflow:hidden;right:0}.ui-spinner a.ui-spinner-button{border-top-style:none;border-bottom-style:none;border-right-style:none}.ui-spinner-up{top:0}.ui-spinner-down{bottom:0}.ui-tabs{position:relative;padding:.2em}.ui-tabs .ui-tabs-nav{margin:0;padding:.2em .2em 0}.ui-tabs .ui-tabs-nav li{list-style:none;float:left;position:relative;top:0;margin:1px .2em 0 0;border-bottom-width:0;padding:0;white-space:nowrap}.ui-tabs .ui-tabs-nav .ui-tabs-anchor{float:left;padding:.5em 1em;text-decoration:none}.ui-tabs .ui-tabs-nav li.ui-tabs-active{margin-bottom:-1px;padding-bottom:1px}.ui-tabs .ui-tabs-nav li.ui-tabs-active .ui-tabs-anchor,.ui-tabs .ui-tabs-nav li.ui-state-disabled .ui-tabs-anchor,.ui-tabs .ui-tabs-nav li.ui-tabs-loading .ui-tabs-anchor{cursor:text}.ui-tabs-collapsible .ui-tabs-nav li.ui-tabs-active .ui-tabs-anchor{cursor:pointer}.ui-tabs .ui-tabs-panel{display:block;border-width:0;padding:1em 1.4em;background:none}.ui-tooltip{padding:8px;position:absolute;z-index:9999;max-width:300px}body .ui-tooltip{border-width:2px}.ui-widget{font-family:Arial,Helvetica,sans-serif;font-size:1em}.ui-widget .ui-widget{font-size:1em}.ui-widget input,.ui-widget select,.ui-widget textarea,.ui-widget button{font-family:Arial,Helvetica,sans-serif;font-size:1em}.ui-widget.ui-widget-content{border:1px solid #c5c5c5}.ui-widget-content{border:1px solid #ddd;background:#fff;color:#333}.ui-widget-content a{color:#333}.ui-widget-header{border:1px solid #ddd;background:#e9e9e9;color:#333;font-weight:bold}.ui-widget-header a{color:#333}.ui-state-default,.ui-widget-content .ui-state-default,.ui-widget-header .ui-state-default,.ui-button,html .ui-button.ui-state-disabled:hover,html .ui-button.ui-state-disabled:active{border:1px solid #c5c5c5;background:#f6f6f6;font-weight:normal;color:#454545}.ui-state-default a,.ui-state-default a:link,.ui-state-default a:visited,a.ui-button,a:link.ui-button,a:visited.ui-button,.ui-button{color:#454545;text-decoration:none}.ui-state-hover,.ui-widget-content .ui-state-hover,.ui-widget-header .ui-state-hover,.ui-state-focus,.ui-widget-content .ui-state-focus,.ui-widget-header .ui-state-focus,.ui-button:hover,.ui-button:focus{border:1px solid #ccc;background:#ededed;font-weight:normal;color:#2b2b2b}.ui-state-hover a,.ui-state-hover a:hover,.ui-state-hover a:link,.ui-state-hover a:visited,.ui-state-focus a,.ui-state-focus a:hover,.ui-state-focus a:link,.ui-state-focus a:visited,a.ui-button:hover,a.ui-button:focus{color:#2b2b2b;text-decoration:none}.ui-visual-focus{box-shadow:0 0 3px 1px rgb(94,158,214)}.ui-state-active,.ui-widget-content .ui-state-active,.ui-widget-header .ui-state-active,a.ui-button:active,.ui-button:active,.ui-button.ui-state-active:hover{border:1px solid #003eff;background:#007fff;font-weight:normal;color:#fff}.ui-icon-background,.ui-state-active .ui-icon-background{border:#003eff;background-color:#fff}.ui-state-active a,.ui-state-active a:link,.ui-state-active a:visited{color:#fff;text-decoration:none}.ui-state-highlight,.ui-widget-content .ui-state-highlight,.ui-widget-header .ui-state-highlight{border:1px solid #dad55e;background:#fffa90;color:#777620}.ui-state-checked{border:1px solid #dad55e;background:#fffa90}.ui-state-highlight a,.ui-widget-content .ui-state-highlight a,.ui-widget-header .ui-state-highlight a{color:#777620}.ui-state-error,.ui-widget-content .ui-state-error,.ui-widget-header .ui-state-error{border:1px solid #f1a899;background:#fddfdf;color:#5f3f3f}.ui-state-error a,.ui-widget-content .ui-state-error a,.ui-widget-header .ui-state-error a{color:#5f3f3f}.ui-state-error-text,.ui-widget-content .ui-state-error-text,.ui-widget-header .ui-state-error-text{color:#5f3f3f}.ui-priority-primary,.ui-widget-content .ui-priority-primary,.ui-widget-header .ui-priority-primary{font-weight:bold}.ui-priority-secondary,.ui-widget-content .ui-priority-secondary,.ui-widget-header .ui-priority-secondary{opacity:.7;-ms-filter:"alpha(opacity=70)";font-weight:normal}.ui-state-disabled,.ui-widget-content .ui-state-disabled,.ui-widget-header .ui-state-disabled{opacity:.35;-ms-filter:"alpha(opacity=35)";background-image:none}.ui-state-disabled .ui-icon{-ms-filter:"alpha(opacity=35)"}.ui-icon{width:16px;height:16px}.ui-icon,.ui-widget-content .ui-icon{background-image:url("images/ui-icons_444444_256x240.png")}.ui-widget-header .ui-icon{background-image:url("images/ui-icons_444444_256x240.png")}.ui-state-hover .ui-icon,.ui-state-focus .ui-icon,.ui-button:hover .ui-icon,.ui-button:focus .ui-icon{background-image:url("images/ui-icons_555555_256x240.png")}.ui-state-active .ui-icon,.ui-button:active .ui-icon{background-image:url("images/ui-icons_ffffff_256x240.png")}.ui-state-highlight .ui-icon,.ui-button .ui-state-highlight.ui-icon{background-image:url("images/ui-icons_777620_256x240.png")}.ui-state-error .ui-icon,.ui-state-error-text .ui-icon{background-image:url("images/ui-icons_cc0000_256x240.png")}.ui-button .ui-icon{background-image:url("images/ui-icons_777777_256x240.png")}.ui-icon-blank.ui-icon-blank.ui-icon-blank{background-image:none}.ui-icon-caret-1-n{background-position:0 0}.ui-icon-caret-1-ne{background-position:-16px 0}.ui-icon-caret-1-e{background-position:-32px 0}.ui-icon-caret-1-se{background-position:-48px 0}.ui-icon-caret-1-s{background-position:-65px 0}.ui-icon-caret-1-sw{background-position:-80px 0}.ui-icon-caret-2-e-w{background-position:-144px 0}.ui-icon-triangle-1-n{background-position:0 -16px}.ui-icon-triangle-1-ne{background-position:-16px -16px}.ui-icon-triangle-1-e{background-position:-32px -16px}.ui-icon-triangle-1-se{background-position:-48px -16px}.ui-icon-triangle-1-s{background-position:-65px -16px}.ui-icon-triangle-1-sw{background-position:-80px -16px}.ui-icon-triangle-1-w{background-position:-96px -16px}.ui-icon-triangle-1-nw{background-position:-112px -16px}.ui-icon-triangle-2-n-s{background-position:-128px -16px}.ui-icon-triangle-2-e-w{background-position:-144px -16px}.ui-icon-arrow-1-n{background-position:0 -32px}.ui-icon-arrow-1-ne{background-position:-16px -32px}.ui-icon-arrow-1-e{background-position:-32px -32px}.ui-icon-arrow-1-se{background-position:-48px -32px}.ui-icon-arrow-1-s{background-position:-65px -32px}.ui-icon-arrow-1-sw{background-position:-80px -32px}.ui-icon-arrow-1-w{background-position:-96px -32px}.ui-icon-arrow-1-nw{background-position:-112px -32px}.ui-icon-arrow-2-n-s{background-position:-128px -32px}.ui-icon-arrow-2-ne-sw{background-position:-144px -32px}.ui-icon-arrow-2-e-w{background-position:-160px -32px}.ui-icon-arrow-2-se-nw{background-position:-176px -32px}.ui-icon-arrowstop-1-n{background-position:-192px -32px}.ui-icon-arrowstop-1-e{background-position:-208px -32px}.ui-icon-arrowstop-1-s{background-position:-224px -32px}.ui-icon-arrowstop-1-w{background-position:-240px -32px}.ui-icon-arrowthick-1-n{background-position:1px -48px}.ui-icon-arrowthick-1-ne{background-position:-16px -48px}.ui-icon-arrowthick-1-e{background-position:-32px -48px}.ui-icon-arrowthick-1-se{background-position:-48px -48px}.ui-icon-arrowthick-1-s{background-position:-64px -48px}.ui-icon-arrowthick-1-sw{background-position:-80px -48px}.ui-icon-arrowthick-1-w{background-position:-96px -48px}.ui-icon-arrowthick-1-nw{background-position:-112px -48px}.ui-icon-arrowthick-2-n-s{background-position:-128px -48px}.ui-icon-arrowthick-2-ne-sw{background-position:-144px -48px}.ui-icon-arrowthick-2-e-w{background-position:-160px -48px}.ui-icon-arrowthick-2-se-nw{background-position:-176px -48px}.ui-icon-arrowthickstop-1-n{background-position:-192px -48px}.ui-icon-arrowthickstop-1-e{background-position:-208px -48px}.ui-icon-arrowthickstop-1-s{background-position:-224px -48px}.ui-icon-arrowthickstop-1-w{background-position:-240px -48px}.ui-icon-arrowreturnthick-1-w{background-position:0 -64px}.ui-icon-arrowreturnthick-1-n{background-position:-16px -64px}.ui-icon-arrowreturnthick-1-e{background-position:-32px -64px}.ui-icon-arrowreturnthick-1-s{background-position:-48px -64px}.ui-icon-arrowreturn-1-w{background-position:-64px -64px}.ui-icon-arrowreturn-1-n{background-position:-80px -64px}.ui-icon-arrowreturn-1-e{background-position:-96px -64px}.ui-icon-arrowreturn-1-s{background-position:-112px -64px}.ui-icon-arrowrefresh-1-w{background-position:-128px -64px}.ui-icon-arrowrefresh-1-n{background-position:-144px -64px}.ui-icon-arrowrefresh-1-e{background-position:-160px -64px}.ui-icon-arrowrefresh-1-s{background-position:-176px -64px}.ui-icon-arrow-4{background-position:0 -80px}.ui-icon-arrow-4-diag{background-position:-16px -80px}.ui-icon-extlink{background-position:-32px -80px}.ui-icon-newwin{background-position:-48px -80px}.ui-icon-refresh{background-position:-64px -80px}.ui-icon-shuffle{background-position:-80px -80px}.ui-icon-transfer-e-w{background-position:-96px -80px}.ui-icon-transferthick-e-w{background-position:-112px -80px}.ui-icon-folder-collapsed{background-position:0 -96px}.ui-icon-folder-open{background-position:-16px -96px}.ui-icon-document{background-position:-32px -96px}.ui-icon-document-b{background-position:-48px -96px}.ui-icon-note{background-position:-64px -96px}.ui-icon-mail-closed{background-position:-80px -96px}.ui-icon-mail-open{background-position:-96px -96px}.ui-icon-suitcase{background-position:-112px -96px}.ui-icon-comment{background-position:-128px -96px}.ui-icon-person{background-position:-144px -96px}.ui-icon-print{background-position:-160px -96px}.ui-icon-trash{background-position:-176px -96px}.ui-icon-locked{background-position:-192px -96px}.ui-icon-unlocked{background-position:-208px -96px}.ui-icon-bookmark{background-position:-224px -96px}.ui-icon-tag{background-position:-240px -96px}.ui-icon-home{background-position:0 -112px}.ui-icon-flag{background-position:-16px -112px}.ui-icon-calendar{background-position:-32px -112px}.ui-icon-cart{background-position:-48px -112px}.ui-icon-pencil{background-position:-64px -112px}.ui-icon-clock{background-position:-80px -112px}.ui-icon-disk{background-position:-96px -112px}.ui-icon-calculator{background-position:-112px -112px}.ui-icon-zoomin{background-position:-128px -112px}.ui-icon-zoomout{background-position:-144px -112px}.ui-icon-search{background-position:-160px -112px}.ui-icon-wrench{background-position:-176px -112px}.ui-icon-gear{background-position:-192px -112px}.ui-icon-heart{background-position:-208px -112px}.ui-icon-star{background-position:-224px -112px}.ui-icon-link{background-position:-240px -112px}.ui-icon-cancel{background-position:0 -128px}.ui-icon-plus{background-position:-16px -128px}.ui-icon-plusthick{background-position:-32px -128px}.ui-icon-minus{background-position:-48px -128px}.ui-icon-minusthick{background-position:-64px -128px}.ui-icon-close{background-position:-80px -128px}.ui-icon-closethick{background-position:-96px -128px}.ui-icon-key{background-position:-112px -128px}.ui-icon-lightbulb{background-position:-128px -128px}.ui-icon-scissors{background-position:-144px -128px}.ui-icon-clipboard{background-position:-160px -128px}.ui-icon-copy{background-position:-176px -128px}.ui-icon-contact{background-position:-192px -128px}.ui-icon-image{background-position:-208px -128px}.ui-icon-video{background-position:-224px -128px}.ui-icon-script{background-position:-240px -128px}.ui-icon-alert{background-position:0 -144px}.ui-icon-info{background-position:-16px -144px}.ui-icon-notice{background-position:-32px -144px}.ui-icon-help{background-position:-48px -144px}.ui-icon-check{background-position:-64px -144px}.ui-icon-bullet{background-position:-80px -144px}.ui-icon-radio-on{background-position:-96px -144px}.ui-icon-radio-off{background-position:-112px -144px}.ui-icon-pin-w{background-position:-128px -144px}.ui-icon-pin-s{background-position:-144px -144px}.ui-icon-play{background-position:0 -160px}.ui-icon-pause{background-position:-16px -160px}.ui-icon-seek-next{background-position:-32px -160px}.ui-icon-seek-prev{background-position:-48px -160px}.ui-icon-seek-end{background-position:-64px -160px}.ui-icon-seek-start{background-position:-80px -160px}.ui-icon-seek-first{background-position:-80px -160px}.ui-icon-stop{background-position:-96px -160px}.ui-icon-eject{background-position:-112px -160px}.ui-icon-volume-off{background-position:-128px -160px}.ui-icon-volume-on{background-position:-144px -160px}.ui-icon-power{background-position:0 -176px}.ui-icon-signal-diag{background-position:-16px -176px}.ui-icon-signal{background-position:-32px -176px}.ui-icon-battery-0{background-position:-48px -176px}.ui-icon-battery-1{background-position:-64px -176px}.ui-icon-battery-2{background-position:-80px -176px}.ui-icon-battery-3{background-position:-96px -176px}.ui-icon-circle-plus{background-position:0 -192px}.ui-icon-circle-minus{background-position:-16px -192px}.ui-icon-circle-close{background-position:-32px -192px}.ui-icon-circle-triangle-e{background-position:-48px -192px}.ui-icon-circle-triangle-s{background-position:-64px -192px}.ui-icon-circle-triangle-w{background-position:-80px -192px}.ui-icon-circle-triangle-n{background-position:-96px -192px}.ui-icon-circle-arrow-e{background-position:-112px -192px}.ui-icon-circle-arrow-s{background-position:-128px -192px}.ui-icon-circle-arrow-w{background-position:-144px -192px}.ui-icon-circle-arrow-n{background-position:-160px -192px}.ui-icon-circle-zoomin{background-position:-176px -192px}.ui-icon-circle-zoomout{background-position:-192px -192px}.ui-icon-circle-check{background-position:-208px -192px}.ui-icon-circlesmall-plus{background-position:0 -208px}.ui-icon-circlesmall-minus{background-position:-16px -208px}.ui-icon-circlesmall-close{background-position:-32px -208px}.ui-icon-squaresmall-plus{background-position:-48px -208px}.ui-icon-squaresmall-minus{background-position:-64px -208px}.ui-icon-squaresmall-close{background-position:-80px -208px}.ui-icon-grip-dotted-vertical{background-position:0 -224px}.ui-icon-grip-dotted-horizontal{background-position:-16px -224px}.ui-icon-grip-solid-vertical{background-position:-32px -224px}.ui-icon-grip-solid-horizontal{background-position:-48px -224px}.ui-icon-gripsmall-diagonal-se{background-position:-64px -224px}.ui-icon-grip-diagonal-se{background-position:-80px -224px}.ui-corner-all,.ui-corner-top,.ui-corner-left,.ui-corner-tl{border-top-left-radius:3px}.ui-corner-all,.ui-corner-top,.ui-corner-right,.ui-corner-tr{border-top-right-radius:3px}.ui-corner-all,.ui-corner-bottom,.ui-corner-left,.ui-corner-bl{border-bottom-left-radius:3px}.ui-corner-all,.ui-corner-bottom,.ui-corner-right,.ui-corner-br{border-bottom-right-radius:3px}.ui-widget-overlay{background:#aaa;opacity:.3;-ms-filter:Alpha(Opacity=30)}.ui-widget-shadow{-webkit-box-shadow:0 0 5px #666;box-shadow:0 0 5px #666}
</style>
