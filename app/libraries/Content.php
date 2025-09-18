<?php 
namespace App\Libraries;


use App\Models\Category_model;
use App\Models\Content_model;
use App\Models\Content2category_model;
use App\Models\Content2identifer_model;
use App\Models\Wine_model;

use \Datetime;

class Content {
const ALL = 'all';
const CONTENT = 'content';
const SIDEBAR = 'sidebar';

private static $instance;
private $user = NULL;
private $content = array();
public function getHtml($_area = self::CONTENT) {
}
/* sucht sich die inhalte der slices zusammen und wirft alle html - angaben raus. der ersten 200 zeichen werden genommen. In der Gallery stehen hidden html Angaben. 
 * Die werden auch als Teaser ausgegeben => ziemlich blöd. Die hier übergebenen slices sind daher OHNE gallery. Datenquelle: content-model.php
 * ggf. auch andere Formate ausschliessen. oder sonstwie im model ändern, wenn der teaser hakt*/
    

// show package 
// 1. para, Package Data 2. wine Data 3. Data of product in Package content table 4. area where to display

public static function getPackage($pack = NULL, $prod = NULL,  $packData = NULL, $area = NULL){

$_vk  = 6.5;     

// show package
if ( $area == 'sidebar proposal') { $html .= "<div class='packageProp-frame'><div class='sidebar-themengarten-package-title  packProposalTitle'><h1 class='index'>".htmlspecialchars($pack['name'])."</h1></div>";}
if ( $area == 'sidebar') { $html = "<div class='sidebar-themengarten-package-title  packLeftBar'></div>";}
// show products in package
$gesWines  = 0;     
$html .= '<div class="package_display">';
$line = 0;
foreach ($prod as $prod_ID => $vv) { //echo $prod_ID;
$line += 1;    
$gesWines  +=  $packData[$prod_ID]['number']*$vv['wine']['price'];  
//if ( $line == 1 AND  $area == 'sidebar proposal') {
if ( $line == 1 ) {$html .= '<div class="packitem first">';}
//if ( $line > 1  OR $area == 'sidebar') {
if ( $line > 1  ) {$html .= '<div class="packitem">';}
if ( $vv['name_pol3_url'] ) {$html .= '<p><span class="number">'.$packData[$prod_ID]['number'].' x </span><span class="product"><a href="/wein/'.$vv['name_pol1_url'].'/'.$vv['name_pol2_url'].'/'.$vv['name_pol3_url'].'/'.$vv['identifer_prod'].'/'.$prod_ID.'"><b>'.$vv['wine']['prod_name'].'</b></a>, ';}
if ( !$vv['name_pol3_url'] ) {$html .= '<p><span class="number">'.$packData[$prod_ID]['number'].' x </span><span class="product"><a href="/wein/'.$vv['name_pol1_url'].'/'.$vv['name_pol2_url'].'/'.$vv['name_pol2_url'].'/'.$vv['identifer_prod'].'/'.$prod_ID.'"><b>'.$vv['wine']['prod_name'].'</b></a>, ';}
$html .= $vv['producer'].'</span></p></div><div class="product"><p class="packData">';

if( $vv['wine']['content'] != "" AND $vv['wine']['content'] !=0){$html .= '<span class="grund">'.$vv['wine']['content'].'l, '.APP::getMoneyValFormated($vv['wine']['price'], TRUE).' ('. APP::getMoneyValFormated($vv['wine']['price']/$vv['wine']['content'], TRUE).' &euro;/l)</span>'.PHP_EOL;}
else if( $vv['wine']['content'] == "" ) {$html .= '<span class="grund">Inhalt fehlt, '.APP::getMoneyValFormated($vv['wine']['price'], TRUE).'</span>';}
$html .= '<span class="preistot">'.APP::getMoneyValFormated($vv['wine']['price']*$packData[$prod_ID]['number'], TRUE).'</span></p></div><div class="clearer"></div>';
}
$reduction = -$gesWines;//echo $gesWines;
$valuePack = $pack['price'] + $_vk; 
$gesWines = $gesWines + $_vk; 
$html .= '<div class="packitem"></div>';

if ( $pack['price'] < 100 ) {}
$html .= '<div class="product"><p class="packData"><span class="txtPrTot">Versand </span><span class="preistot">'.APP::getMoneyValFormated($_vk, TRUE).'</span></p></div>';
$html .= '<div class="clearer"></div><div class="product"><p class="packData"><span class="txtPrTot">Reduziert um:</span><span class="preistot">'.APP::getMoneyValFormated($pack['price'] - $gesWines, TRUE).'</span></p></div>';
$html .= '<div class="clearer"></div><div class="packitem"></div><div class="product"><p class="packData"><span class="txtPrTot">Preis des Paketes: </span><span class="preistot">'.APP::getMoneyValFormated($pack['price'], TRUE).'</span></p></div><div class="clearer"></div></div>';
if ( $area == 'sidebar proposal') {    
$html .= "<div class='packageProp-frame'><div class='sidebar-themengarten-package-title  packProposal'>";
if ($_SESSION['cart_pac'][$pack['id']]['number'] > 0 ) { $html .= "<a href='?pakEKZ=".$pack['id']."]=0' title='Nehmen Sie das Paket ".htmlspecialchars($pack['name'])." aus dem Warenkorb'><div class='icon wr-rem'></div>";} 
if ($_SESSION['cart_pac'][$pack['id']]['number'] == 0 ) { $html .= "<a href='?pakEKZ=".$pack['id']."' title='Legen Sie das Paket ".htmlspecialchars($pack['name'])." in den Warenkorb'><div class='icon wr-add'></div>";}
$html .= "</a></div></div>";
}
 
if ( $area == 'sidebar') {    
$html .= "<div class='package-buy'>";
// $html .= '<a href="/weinraum/kasse" class="buybutton">bestellen</a>'.PHP_EOL;
if ($_SESSION['cart_pac'][$pack['id']]['number'] == 0 ) {  $html .= "<a href='?pakEKZ=".$pack['id']."' class='buybutton'>In den Warenkorb</a>";} 
if ($_SESSION['cart_pac'][$pack['id']]['number'] > 0 ) {  $html .= "<a href='?pakEKZ=".$pack['id']."' class='nobuybutton'>Im Warenkorb</a>";} 
$html .= '</div>'.PHP_EOL;
}
return $html;
}

// $html.= self::getPackage($content[0]['gp_package'], $content[0]['gp_prodID'], $content[0]['gp_prod_in_pack'], 'sidebar');
// 1. para, Package Data 2. wine Data 3. Data of product in Package content table 4. area where to display

public static function getProposals($prop_packages) {
$html = "";
foreach ($prop_packages as $pack_ID => $v) {$html.= self::getPackage($v['package'], $v['productData'], $v['productPackData'], 'sidebar proposal'); } 
return $html;
}

public static function getTeaser($_slices, $_length = 200, $_hyphen = '&nbsp;...') {
$html = '';
$html .= self::getContentHtml($_slices);
$html = str_replace("\n", ' ', strip_tags($html));
if (strlen($html) > $_length) {
$lines = explode("\n", wordwrap($html, $_length, "\n"));
$html = $lines[0].$_hyphen;
}
return $html;
}

public static function getSliceHtml($_slice, $_urls = NULL, $_lazy = NULL, $_format_slice = NULL) {
$html = '';
$_slice['data']['_area'] = $_slice['area'];
$_slice['data']['content_id'] = $_slice['content_id'];
$_slice['data']['slice_id'] = $_slice['id'];
$_slice['data']['_urls'] = $_urls;
$_slice['data']['is_provence_left'] = $_slice['is_provence_img']; //Hier die Möglichkeit das einzelne Bild zu checken
$_slice['data']['is_provence_right'] = $_slice['is_provence_rechts_img']; //Hier die Möglichkeit das einzelne Bild zu checken
$_slice['data']['is_provence_img'] = 1;
//$_slice['data']['float'] = isset($_slice['float'])?float:"";
$_slice['data']['format_slice'] = $_format_slice;
if ( $_format_slice == "xs" AND $_slice['type'] == "text_image") { $_slice['data']['img_xs_magn'] = -1; }
if ( $_format_slice != "xs" AND $_slice['type'] == "text_image") { $_slice['data']['img_xs_magn'] = 1; }

if ( $_lazy == 1 ) $_slice['data']['_lazy'] = -1;
if ( $_lazy != 1 ) $_slice['data']['_lazy'] = 1;
//if (isset($_slice['data']['imageText'])) echo("{$_slice['data']['imageText']}-{$_slice['data']['slice_id']}");
$file = dirname(__FILE__).'/../Views/themengarten/content/'.$_slice['type'].'_output.php';
if (is_file($file)) {
$html .= view('themengarten/content/'.$_slice['type'].'_output', $_slice['data']);
}
return $html;
}

// funktion zeigt vorschau auf index seite an und verlinkt auf den artikel
public static function getContentHtmlPreview($_slices, $_urls, $_title) {
$html = '';
if (is_array($_slices) && sizeof($_slices) > 0) {
foreach ($_slices as $k => $slice) {    
if ($slice['area'] == "main") {      
 /* hier ist content preview  html, da wird der artikel nur bis zum trenner separation angezeigt und die ausgabe gestoppt (end= 1)
 * in conten steht die abfrage nach separation. der artikel wird dort ganz angezeigt */    
 
if ( $slice['type'] == "separation" ) {
//$html .= "<a href='/themengarten/$_urls' title='Lesen Sie mehr über ' $_title'>... weiter lesen.</a>";
$html .=  self::getSliceHtml($slice, $_urls);
$end = 1;
}
if ( $slice['type'] != "separation" AND $end != 1) {$html .=  self::getSliceHtml($slice);}
}
}
}

return $html;
}

// funktion zeigt vorschau im kopf der seite an "mehr anzeigen " dann per java auf der gleichen seite

public static function getContentHtmlPreviewFirstTwo($_slices, $_area = 'main', $_class = NULL) {
//$huhu = sizeof($_slices) ;
//echo("sl $huhu");
$html = '';
if (is_array($_slices) && sizeof($_slices) > 0) {
 
$end_display = 0;

$_slice = 0;    
foreach ($_slices as $k => $slice) {
if ($slice['area'] == $_area) {
$alrDispl = -1;
$_slice ++;
if ( $_slice == 2 ) {
if ( $_class == "regionen" ) {$html .=  '<div class="sep-winzer"> <p class="mehr zeige"  id="sectionContent">Über die Region ... </p></div>';}
if ( $_class == "weintypen" ) {$html .=  '<div class="sep-winzer"> <p class="mehr zeige"  id="sectionContent">Mehr zu diesem Weintyp ...</p></div>';}
if ( $_class == "winzer" ) {$html .=  '<div class="sep-winzer"> <p class="mehr zeige"  id="sectionContent">Mehr zum Winzer anzeigen ...</p></div>';}
if ( $_class == "wein_passend_zu" ) {$html .=  '<div class="sep-winzer"> <p class="mehr zeige"  id="sectionContent">Mehr zu dieser Kombination anzeigen ...</p></div>';}
if ( $_class == "passend_ind" ) {$html .=  '<div class="sep-winzer"> <p class="mehr zeige"  id="sectionContent">Warum der Mensch manches lieber mag </p></div>';}
$html .=  '<div class="zeig-bei-wunsch">';
}

if ($slice['type'] == 'text' AND isset($_slices['wines_grower']) AND is_array($_slices['wines_grower']) AND $displayed != 1) {
// prüft, ob weine dargestellt werden sollen    
$dispWines = self::setDisplayWines($_slices['wines_grower'], $slice);
if ( $dispWines == 1) { 
$displayed = 1;
$html .=  '<div class="content-txt_wines">';
$html .= '<div class="content_wines"><div class="content-wines-titel">';
$html .= $_slices['wines_grower'][0]['winzer'].' - die Weine:</div>';
foreach ($_slices['wines_grower']['weine'] as $wein){
$html .= '<a href="/wein/'.$_slices['wines_grower']['land'].'/'.$_slices['wines_grower']['reg_name'].'/'.$_slices['wines_grower']['regionUrl'].'/'.$_slices['wines_grower'][0]['bez_men'].'/'.$wein['id'] .'">'.$wein['name'] .' '.$wein['jg'].'</a>';
}

/* separation is for firth page shortage of content */
if ( $slice['type'] != "separation" ) {$html .= '</div>'.self::getSliceHtml($slice).'</div>';}
$alrDispl = 1;
}
}
if ( $alrDispl != 1 AND $end_display == 0 ) {
/* hier ist content html, da wird der ganze artikel angezeigt. 
 * in conten preview steht die abfrage nach separation nicht. der trenner wird angezeigt und die ausgabe dann gestoppt */    
   
if ( $slice['type'] != "separation"  ) {$html .=  self::getSliceHtml($slice);}
if ( $slice['type'] == "separation" ) {}
}
}
}
$jkjkj = sizeof($_slices);
if ( sizeof($_slices) > 1 ) {$html .=  '</div>';}
}

return $html;
}



public static function setDisplayWines($_wines, $_slice) {
$numWord = str_word_count($_slice['data']['content']);
$numWines = isset($_wines['weine'])?count($_wines['weine']):0;
if ($numWord > ($numWines * 12 + 12) AND $numWines != 0) $display = 1;

//echo("im Dsisl $display :: $numWord :: $numWines");
return $display;
}

public static function getContentHtml($_slices, $_area = NULL) {

$html = '';
$links = '';
if (is_array($_slices) && sizeof($_slices) > 0) {
$end_display = 0;
$_count_slices = 0;
$_count_text_image = 0;
$_count_links = 0;
foreach ($_slices as $k => $slice) { 
if ( isset($slice['link']) AND $slice['link'] != "main" AND $slice['link'] != "") {
$_count_links ++;
if ( $_count_links == 1) { $links .=  '<div class="links_content"><p><span class="head">Aus dem Inhalt:<br></span></p>'; } 
$links .=  '<p><span class="dot">&#8226;</span><a href = "#link_men_'.$k.'" class="link_content"> '.$slice['link'].'</a></p>';
}
}
if ( $_count_links > 0 ) {$links .=  '</div><div class="clearer"></div>';}

foreach ($_slices as $k => $slice) {
$_count_slices ++;
if ( $_count_slices == 1 AND $slice['type'] == "text") { $html .= $links; }

$alrDispl = -1;
if ( $slice['type'] == "text_image") { $_count_text_image ++; }
//if ( isset($slice['imageText']) ) { echo("n");}
if (isset($slice['type']) AND $slice['type'] == 'text' AND isset($_slices['wines_grower']) AND is_array($_slices['wines_grower']) AND $displayed != 1) {
// prüft, ob weine dargestellt werden sollen    
$dispWines = self::setDisplayWines($_slices['wines_grower'], $slice);
if ( $dispWines == 1) { 
$displayed = 1;
$html .=  '<div class="content-txt_wines">';
$html .= '<div class="content_wines"><div class="content-wines-titel">';
$html .= $_slices['wines_grower'][0]['winzer'].' - die Weine:</div>';
foreach ($_slices['wines_grower']['weine'] as $wein){
$html .= '<a href="/wein/'.$_slices['wines_grower']['land'].'/'.$_slices['wines_grower']['reg_name'].'/'.$_slices['wines_grower']['regionUrl'].'/'.$_slices['wines_grower'][0]['bez_men'].'/'.$wein['id'] .'">'.$wein['name'] .' '.$wein['jg'].'</a>';
}
/* separation is for firth page shortage of content */
if ( $slice['type'] != "separation" ) {
$html .= '</div>'.self::getSliceHtml($slice, NULL, NULL, $_area).'</div>';
}
$alrDispl = 1;
}
}
if ( $alrDispl != 1 AND $end_display == 0 ) {
/* hier ist content html:der ganze artikel. in content preview stoppt die ausgabe bei separation*/   
  
if ( $slice['type'] != "separation" ) {
/* die Bilder werden lazy geladen, ausser das 1. und es steht oben, damit das schneller angezeigt wird.*/     
if ( $slice['type'] == "text_image" AND $_count_text_image == 1 ) { 
if ( isset($slice['link']) AND $slice['link'] != "") { $html .=  "<a id='link_men_".$k."'></a>"; } 
$html .=  self::getSliceHtml($slice, NULL, 1);
}
if ( $slice['type'] == "text_image" AND $_count_slices == 1 ) { $html .= $links; }
// in allen anderen Fällen kein lazy für Bilder
if ( $slice['type'] != "text_image" OR ( $slice['type'] == "text_image" AND $_count_text_image > 1) ) { 
if ( isset($slice['link']) AND $slice['link'] != "") { $html .=  "<a id='link_men_".$k."'></a>"; }
$html .=  self::getSliceHtml($slice, NULL, -1, $_area);
}
}
//$end_display = 1;    
if ( $slice['type'] == "separation" ) {}
}
}
}

return $html;
}



public static function getMagazinIndex( $_data = NULL) {
$Content2identifer_model = new Content2identifer_model();
$linkIdentifers = $Content2identifer_model->getIdentifers();

$App = new \App\Libraries\App();  

$html = '';
$html_xs = "";
$html_li = "";
$html_mi = "";
$html_re  = "";
$html_2_li = ""; 
$html_2_re = "";

$i = 0;
$_arrlinks = array(1, 4, 7, 10, 13, 16, 19);
$_arrmitte = array(2, 5, 8, 11, 14, 17, 20);
$_arrrechts = array(3, 6, 9, 12, 15, 18, 21);

/* ausgabe in drei varianten 3, 2, 1 Spalte für screen tablet und telefon. die Ausgaben werden über d-block gesteuert 
 * beginnen mit 21 Seiten auf erster Seite, dann auf 15 reduzieren und die Seitenzahlen einfügen.
 * Wenn es dann 2178^ Seiten sind, neu nachdenken.
 */

if ( isset($_data['content']) AND is_array($_data['content'])  ) {

/* einzelne Spalten zusammenstellen */
foreach ($_data['content_datum'] as $k_cat => $v_cat) {   
if ( isset($v_cat) AND is_array($v_cat)  ) { 
$_noItems = count($v_cat);
$_anzrows_3 = floor($_noItems/3);
$_anzrows_2 = floor($_noItems/2);
foreach ($v_cat as $key => $value) { 
$i++;

if ( $i == 1 ) { 
$html_xs = '<div class="col-12 magazin-item " >';
$html_li = '<div class="col-4 magazin-item left" >';
$html_2_li = '<div class="col-4 magazin-item left" >';
}
if ( $i == 2 ) { $html_mi = '</div><div class="col-4 magazin-item mitte" >'; $html_2_re = '</div><div class="col-6 magazin-item rechts" >';}
if ( $i == 3 ) { $html_re = '</div><div class="col-4 magazin-item rechts" >'; }

if ( $value['image'] !== "" ) { 
$value['image'] = substr($value['image'], 0,-4).".webp";

$value['teaser_6'] = str_replace("\n", "<br></p><p class='mag_ind_txt'>", $value['teaser_6']);
$value['teaser_6'] = $App::getLinkfromBracket($value['teaser_6'], $linkIdentifers);

$html_xs .= '<div class="mag_box"><a href="/magazin/'.$value['identifer'].'">';
$html_xs .= '<h3 class="mag_ind_head">'.$value['teaser'].'</h3></a>';
$html_xs .= '<a href="/magazin/'.$value['identifer'].'"><img src="https://www.weinraum.de/_data/'.$value['id'].'/output/'.$value['image'].'" class="" alt="'.$value['teaser'].'"></a>'.PHP_EOL;
$html_xs .= '<p class="mag_ind_txt">'.$value['teaser_6'].'</p></div>';


if (in_array($i, $_arrlinks)) {
$html_li .= '<div class="mag_box"><a href="/magazin/'.$value['identifer'].'">';
$html_li .= '<h3 class="mag_ind_head">'.$value['teaser'].'</h3></a>';
$html_li .= '<a href="/magazin/'.$value['identifer'].'"><img src="https://www.weinraum.de/_data/'.$value['id'].'/output/'.$value['image'].'" class="" alt="'.$value['teaser'].'"></a>'.PHP_EOL;
$html_li .= '<p class="mag_ind_txt">'.$value['teaser_6'].'</p></div>';
}

if (in_array($i, $_arrmitte)) {
$html_mi .= '<div class="mag_box"><a href="/magazin/'.$value['identifer'].'">';
$html_mi .= '<h3 class="mag_ind_head">'.$value['teaser'].'</h3></a>';
$html_mi .= '<a href="/magazin/'.$value['identifer'].'"><img src="https://www.weinraum.de/_data/'.$value['id'].'/output/'.$value['image'].'" class="" alt="'.$value['teaser'].'"></a>'.PHP_EOL;
$html_mi .= '<p class="mag_ind_txt">'.$value['teaser_6'].'</p></div>';
}

if (in_array($i, $_arrrechts)) {
$html_re .= '<div class="mag_box"><a href="/magazin/'.$value['identifer'].'">';
$html_re .= '<h3 class="mag_ind_head">'.$value['teaser'].'</h3></a>';
$html_re .= '<a href="/magazin/'.$value['identifer'].'"><img src="https://www.weinraum.de/_data/'.$value['id'].'/output/'.$value['image'].'" class="" alt="'.$value['teaser'].'"></a>'.PHP_EOL;
$html_re .= '<p class="mag_ind_txt">'.$value['teaser_6'].'</p></div>';
}

// ungerade
if ($i % 2 != 0) {
$html_2_li .= '<div class="mag_box"><a href="/magazin/'.$value['identifer'].'">';
$html_2_li .= '<h3 class="mag_ind_head">'.$value['teaser'].'</h3></a>';
$html_2_li .= '<a href="/magazin/'.$value['identifer'].'"><img src="https://www.weinraum.de/_data/'.$value['id'].'/output/'.$value['image'].'" class="" alt="'.$value['teaser'].'"></a>'.PHP_EOL;
$html_2_li .= '<p class="mag_ind_txt">'.$value['teaser_6'].'</p></div>';
}
}
//gerade
else {
$html_2_re .= '<div class="mag_box"><a href="/magazin/'.$value['identifer'].'</a>';
$html_2_re .= '<h3 class="mag_ind_head">'.$value['teaser'].'</h3></a>';
$html_2_re .= '<a href="/magazin/'.$value['identifer'].'"><img src="https://www.weinraum.de/_data/'.$value['id'].'/output/'.$value['image'].' class="" alt="'.$value['teaser'].'"></a>'.PHP_EOL;
$html_2_re .= '<p class="mag_ind_txt">'.$value['teaser_6'].'</p></div>';
}
}
}
}

/* Gesamten index zusammenstellen */

$html .= '<div class="d-none d-lg-block col-12 magazin"><div class=" d-flex flex-wrap ">'.PHP_EOL;
$html .= $html_li.''.$html_mi.' '.$html_re.'</div></div></div>'.PHP_EOL;

$html .= '<div class="d-none d-md-block d-lg-none col-12 magazin"><div class="d-flex flex-wrap ">'.PHP_EOL;
$html .= $html_2_li.' '.$html_2_re.'</div></div></div>'.PHP_EOL;

$html .= '<div class="d-xs-block d-md-none col-12 magazin"><div class="d-flex flex-wrap ">'.PHP_EOL;
$html .= $html_xs.'</div></div></div>'.PHP_EOL;

}
return $html;
}


/* Neu in 2024: die Seiten zur Region sollen mehr wie eine Zeitung aussehen, kurze Häppachen für Überblick und auf detaillierteren Inhalt verweisen, der dann auf diese
 * Seite verlinkt. Dazu zwei Spalten für Inhalt statt nur einer wie sonst immer.
 */
public static function getContentSpalten( $_slices, $_area = NULL) {

$html = '';
$links = '';
$html_xs = "";
$html_li = "";
$html_mi = "";
$html_re  = "";
$html_2_li = "";
$html_2_re = "";

$i = 0;
$_arrlinks = array(1, 4, 7, 10, 13, 16, 19);
$_arrmitte = array(2, 5, 8, 11, 14, 17, 20);
$_arrrechts = array(3, 6, 9, 12, 15, 18, 21);

/* ausgabe in drei varianten 3, 2, 1 Spalte für screen tablet und telefon. die Ausgaben werden über d-block gesteuert 
 * beginnen mit 21 Seiten auf erster Seite, dann auf 15 reduzieren und die Seitenzahlen einfügen.
 * Wenn es dann 2178^ Seiten sind, neu nachdenken.
 */

if ( isset($_slices) AND is_array($_slices)  ) {
$_count_links = 0;
foreach ($_slices as $k => $slice) { 
if ( isset($slice['link']) AND $slice['link'] != "main" AND $slice['link'] != "") {
$_count_links ++;
if ( $_count_links == 1) { $links .=  '<div class="links_content zeitung"><p><span class="head">Aus dem Inhalt:<br></span></p>'; } 
$links .=  '<p><span class="dot">&#8226;</span><a href = "#link_men_'.$k.'" class="link_content"> '.$slice['link'].'</a></p>';
}
}
if ( $_count_links > 0 ) {$links .=  '</div>';}


foreach ($_slices as $k => $slice) { 

/* einzelne Spalten zusammenstellen */
if ( isset($slice) AND is_array($slice)  ) { 
$_noItems = count($slice);
$_anzrows_3 = floor($_noItems/3);
$_anzrows_2 = floor($_noItems/2);
$i++;

if ( $i == 1 ) { 

$html_xs = '<div class="col-12 zeitung-item " >'.$links;
$html_li = '<div class="col-4 zeitung-item left" >'.$links;
$html_2_li = '<div class="col-6 zeitung-item left" >'.$links;
}
if ( $i == 2 ) { $html_mi = '</div><div class="col-4 zeitung-item mitte" >'; $html_2_re = '</div><div class="col-6 zeitung-item rechts" >';}
if ( $i == 3 ) { $html_re = '</div><div class="col-4 zeitung-item rechts" >'; }

$html_xs .= self::getSliceHtml($slice, NULL, NULL, $_area);

if (in_array($i, $_arrlinks)) { $html_li .= self::getSliceHtml($slice, NULL, NULL, $_area); }
if (in_array($i, $_arrmitte)) { $html_mi .= self::getSliceHtml($slice, NULL, NULL, $_area); }
if (in_array($i, $_arrrechts)) { $html_re .= self::getSliceHtml($slice, NULL, NULL, $_area); }

// ungerade
if ($i % 2 != 0) { $html_2_li .= self::getSliceHtml($slice, NULL, NULL, $_area); }
//gerade
else { $html_2_re .= self::getSliceHtml($slice, NULL, NULL, $_area); }
}
}

/* Gesamten index zusammenstellen */
$html .= '<div class="d-none d-lg-block col-12 zeitung"><div class=" d-flex flex-wrap ">'.PHP_EOL;
$html .= $html_li.''.$html_mi.' '.$html_re.'</div></div></div>'.PHP_EOL;

$html .= '<div class="d-none d-md-block d-lg-none col-12 zeitung"><div class="d-flex flex-wrap ">'.PHP_EOL;
$html .= $html_2_li.' '.$html_2_re.'</div></div></div>'.PHP_EOL;

$html .= '<div class="d-xs-block d-md-none col-12 zeitung"><div class="d-flex flex-wrap ">'.PHP_EOL;
$html .= $html_xs.'</div></div></div>'.PHP_EOL;

}
return $html;
}

/* Neu in 2024: Provence steht exemplarisch für eine Seite mit (vielen ) schönen Bildern. Damit der «groove» aufkommt, in der desctop variante 
 * hochformat Bilder links, die hier sortiert werden. Der Rest einfach in das rechte div kloppen und die linken hochformatbilder 
 * für mobile über die Beiträge setzen und über d- class die Sichtbarkeit steuer.
 */
public static function getContentProvence( $_slices) {
$Content2identifer_model = new Content2identifer_model();
$linkIdentifers = $Content2identifer_model->getIdentifers();

$App = new \App\Libraries\App();  

$html = '';
$html_xs = '';
$html_li = '';
$html_re = ''; 
$i = 0;

/* ausgabe in drei varianten 3, 2, 1 Spalte für screen tablet und telefon. die Ausgaben werden über d-block gesteuert 
 * beginnen mit 21 Seiten auf erster Seite, dann auf 15 reduzieren und die Seitenzahlen einfügen.
 * Wenn es dann 2178^ Seiten sind, neu nachdenken.
 */

if ( isset($_slices) AND is_array($_slices)  ) {
foreach ($_slices as $k => $slice) { 
 $_showreli = -1;
/* einzelne Spalten zusammenstellen */
if ( isset($slice) AND is_array($slice)  ) { 
$i++;

if ( $i == 1 ) { 
$html_xs = '<div class="col-12 d-block d-md-none provence-zeile-xs " >';
$html_li = '<div class="col-6 d-none d-md-block provence-zeile-md left" >';
$html_re = '<div class="col-6 d-none d-md-block provence-zeile-md right" >';
}
//print_r($slice);echo("<br><br>");
if ( isset($slice['is_provence_img']) AND $slice['is_provence_img'] == 1 ) { $html_li .= self::getSliceHtml($slice); $_showreli = 1;}
if ( isset($slice['is_provence_rechts_img']) AND $slice['is_provence_rechts_img'] == 1 ) { $html_re .= self::getSliceHtml($slice);  $_showreli = 1;}
if (  $_showreli == -1 ) { $html_re .= self::getSliceHtml($slice); }

$html_xs .= self::getSliceHtml($slice, NULL, NULL, "xs");
}
}

/* Gesamten index zusammenstellen => hier nur die divs schliessen */
$html .= '<div class="d-flex flex-wrap col-12">'.PHP_EOL;
$html .= $html_xs . '</div>';
$html .= $html_li . '</div>';
$html .= $html_re . '</div>';
$html .= '</div>';

}
return $html;
}

public static function getRegionenMenue($_startCategoryId, $_Winzer = NULL) {  
/* *such regionen = kategorie 174 und erstellt das  menue für die Regionen, wird in admin themengarten nach jeder Überarbeitung neu gespeichert.
winzer werden genauso verfrühstückt, aber der link ist winzer statt regionen */    
    
$html = '';
$xml = '';
$start = microtime(true);

$Category_model = new Category_model();
$Content_model = new Content_model();
$Content2category_model = new Content2category_model();
/*
$html .= '<div class="d-block d-md-none navbar navbar-header">'.PHP_EOL;
$html .= '<button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-expanded="true" aria-controls="collapseOne">'.PHP_EOL;
if ( $_Winzer == "winzer" ) {
$html .= '<span class="txt">Winzer auswählen</span></button></div>'.PHP_EOL;
}
if ( $_Winzer == "region" ) {
$html .= '<span class="txt">Region auswählen</span></button></div>'.PHP_EOL;
}
*/
$html .= '<div class="m2-wine-categories">'.PHP_EOL;
// anfang ungenutzt
if (is_numeric($_startCategoryId)) {
$caDeMenue =  $Category_model->getParentIdentifers($_startCategoryId);
$level_act = count($caDeMenue);
}
else{$level_act = 0; }
// ende ungenutzt


if (is_numeric($_startCategoryId)) { $data = array('parent_id' => $_startCategoryId, 'status' => '1'); }
else {$data = array('parent_id' => NULL, 'status' => '1');}
$categories = $Category_model->get($data, 'position');
// = categories mit status == NULL also ganz oben im menü
if (is_array($categories) AND count($categories) > 0) { 
// kategorien die kind der startcat sind laufen von oben nach unten durch wenn winzer start z.B.
// winzer/frankeich/languedoc/corbieres/2anes
$i_firstcat = 0; 
foreach($categories as $category ) {
//$time_elapsed_secs = microtime(true) - $start;
//echo("<br><br>content 1  $time_elapsed_secs cat {$category['id']}<br>"); 

$i_firstcat ++;   
$_submenue = 0;    
$active = '';

// prüfen, ob in Kategierie direkt zugeordnete Inhalte stehen
$_chk_cont_catHEAD = $Category_model->getCategoryDirectContentIds($category['id']);
$cont_catHEAD = count($_chk_cont_catHEAD);

// prüfen ob unterkategorien in kategorie enthalten sind
$caDeMenue =  $Category_model->getParentIdentifers($category['id']);
$count = sizeof($caDeMenue);
// wenn inhalte in kategorie, ist count > 0
if ( $count > 0) {  
// pre check ob in Kategorie Kind - Kategorien enthalten sind und prüfen, ob darin Inhalt zugeordnet ist
$head_contains_cont = 0;
// suche kategorien, deren parentID die aktuelle ID ist ==> sucht kind elemente
$categories_1 = $Category_model->get(array('parent_id' => $category['id'], 'status' => '1'), 'position');
if ( isset($categories_1) AND is_array($categories_1) AND count($categories_1) > 0) { // es gibt kind - kategorien
foreach($categories_1 as $category_1 ) {
// sucht inhalte auch in Kind Kategorien der Kategorie
$count_cat2 = $Category_model->getCategoryContentIds($category_1['id']);
$count_2 = count($count_cat2);
// Artikel direkt zur Kategorie. Wenn es die gibt, diese Kategorie als Link
$cat_ids[$category_1['id']] = $Category_model->getCategoryDirectContentIds($category_1['id']);
//echo("<br>{$category_1['id']}");
$count_cont_cat1[$category_1['id']] = count($cat_ids);
if ( isset($cat_ids[$category_1['id']]) AND is_array($cat_ids[$category_1['id']])) { foreach ($cat_ids[$category_1['id']] as $kc => $vc) { $_cont2cat_1[$category_1['id']][] = $vc;}
}
if ( $count_2 > 0) { $head_contains_cont = 1; }
}
}
/* $head_contains_cont == 1 heisst in der child cat stehen inhalte. ausgegeben wird die paarent cat
 * in jeder Ebene prüfen, ob zu einer cat direkte Inhalte vorhanden sind. Dann link, sonst nur text in Überschrift
echo("<br>hu ".implode('/', $category['identifers']));
echo("<br>hu ".$category['name']);
 */

if ( $head_contains_cont === 1) {

// $i_firstcat == 1 bedeutet es ist die erste kategorie, hier: Land   
if ( $i_firstcat == 1 ) { 
if ( isset($category['identifers']) AND $category['identifers'] != "" AND $cont_catHEAD > 0) {
$html .= '<p class="cat-head-menue"><a href="https://www.weinraum.de/'.implode('/', $category['identifers']).'">'.$category['name'].'</a></p>'.PHP_EOL; 
}
else {
$html .= '<p class="cat-head-menue">'.$category['name'].'</p>'.PHP_EOL; 
}
}
if ( $i_firstcat > 1 ) { 
if ( isset($category['identifers']) AND $category['identifers'] != "" AND $cont_catHEAD > 0) {
$html .= '</ul></div><p class="cat-head-menue"><a href="https://www.weinraum.de/'.implode('/', $category['identifers']).'">'.$category['name'].'</a></p>'.PHP_EOL; 
}
else {
$html .= '</ul></div><p class="cat-head-menue">'.$category['name'].'</p>'.PHP_EOL; 
}
}
// die div gehen bei neuen ländern und regionen auf. Das letzte div wird am ende aller scheifen einfach geschlossen. die zwischen div in der zeile vor diesem komentar.
$html .= '<div class="left-men-regionen"><ul>'.PHP_EOL;
$_submenue = 1;
}
// hier sollte nichts stehen: in den Kind Elementen stehen keine Inhalte (wenn ich mich nicht irre, hihihi)
//if ( $head_contains_cont === 0) {}
}
// categories, die kinder der $_startCategoryId sind
if (is_array($categories_1) AND count($categories_1) > 0) { // ohne count werden <ul></ul> ausgegeben, die die formatierung kaputt machen
  // region  
foreach($categories_1 as $category_1 ) {
$count_1 = sizeof($Category_model->getCategoryContentIds($category_1['id']));
// zählt contents für diese cat
$_submenue = 0;
$lalaREG = array();
$contentID = $Content2category_model->get(array('category_id' => $category_1['id']) );
if ( isset($contentID[0]['content_id']) ) { $lalaREG = $Content_model->just_get($contentID[0]['content_id']); }
// prüft, ob in der direkt cat $category_1 inhalte stehen - NICHT kind - elemente
if ( $count_1 > 0) {
// pre check ob  content in kindern der $category_1. Kinder stehen in $categories_2
$head_cont_cat2 = 0;
// Kinder dieser cat
$data_2 = array('parent_id' => $category_1['id'], 'status' => '1');
$categories_2 = $Category_model->get($data_2, 'position');

if (is_array($categories_2) AND count($categories_2) > 0) { 
$lala ="";
foreach($categories_2 as $category_2 ) {
$count_cat2 = $Category_model->getCategoryContentIds($category_2['id']);
$count_2 = count($count_cat2);
//if ( isset($count_cat2) AND is_array($count_cat2)) { foreach ($count_cat2 as $kc => $vc) { echo("count {$vc} <br>"); } }
// prüft, ob in der $categories_2 inhalte stehen. $categories_2 ist sind kinder von $categories_1
if ( $count_2 > 0) { $head_cont_cat2 = 1;}
}
}
// gibt die Kinder der $_startCategoryId aus, für die Inhalte oder weitere Kind - Categories gefunden wurden
if ( $_Winzer == "region" ) {
if ( isset($lalaREG['identifer']) AND $lalaREG['identifer'] != "") {
$html .= '<li class="submenu-land "><a href="https://www.weinraum.de/regionen/'.$lalaREG['identifer'].'">'.$category_1['name'].'</a>'.PHP_EOL;
$xml .= '<url><loc>https://www.weinraum.de/regionen/'.$lalaREG['identifer'].'</loc></url>'.PHP_EOL;
$_submenue = 1;
}
if ( isset($lalaREG['identifer']) AND $lalaREG['identifer'] == "") {
//echo("<br>hu ohne".$lalaREG['identifer']." inhalt oben".$category_1['id']);

$html .= '<li class="submenu-land "><a href="https://www.weinraum.de/'.implode('/', $category_1['identifers']).'">'.$category_1['name'].'</a>'.PHP_EOL;
$_submenue = 1;
}    
}
if ( $_Winzer == "winzer" ) {
$_submenue = 1;
$_chkCat1 = 0;
/* Hier im Untermenü, z.B. Languedoc und den Appellationen. WENN es zu einer Appellation einen Artikel gibt (direkt dieser Appellation zugewiesen)
 * soll der Name der Appellation als Link dargestellt werden. Wenn nicht, aber es innerhalb der Appellation Inhalte gibt, wird die Appellation
 * als Text ausgegeben. Wenn es auch unterhalb der Appellation keine Inhalte gibt, wird diese auch nicht ausgegeben
 */

// prüfen, ob es in der Kategorie direkten Artikel gibt. Das ist hier Winzer Languedoc oder Winzer Rhone
if ( isset($_cont2cat_1[$category_1['id']]) AND is_array($_cont2cat_1[$category_1['id']])) {
//gehe durch die Artikel für Winzer -> Languedoc. WENN ist das nur einer, die array - Variante ist HISTORISCH! 
foreach ($_cont2cat_1[$category_1['id']] as $kc => $vc) {
$_contWinzer = $Content_model->just_get($vc);
if ( isset($_contWinzer['identifer']) ) {
$_chkIdent = explode("-", $_contWinzer['identifer']);
// vermutlich: prüft ob es einen Artikel winzer/languedoc gibt. 
$_chkCatIndent = isset($category_1['identifer'])?$category_1['identifer']:NULL;
// die folgende Klammer wird nie ausgegeben, ist Teil der array - Variante
// vermutlich: prüft ob es einen Artikel anders als winzer/languedoc gibt. 
if ( isset($_chkIdent[0]) AND $_chkIdent[0] == "winzer" AND isset($_chkIdent[1]) AND $_chkIdent[1] != $_chkCatIndent) {
$_chkCat1 = 1;
$html .= '<li class="submenu-winzer-land "><a href="https://www.weinraum.de/winzer/'.$category['identifer'].'/'.$category_1['identifer'].'">'.$category_1['name'].'</a>'.PHP_EOL;
if ( isset($_cont2cat_1[$category_1['id']]) AND is_array($_cont2cat_1[$category_1['id']])) {
$html .= '<ul class="winzer-menu">'.PHP_EOL; 
foreach ($_cont2cat_1[$category_1['id']] as $kc => $vc) { 
$_contWinzer = $Content_model->just_get($vc);
$html .= '<li class="WinRegDir"><a href="https://www.weinraum.de/winzer'.'/'.$_contWinzer['identifer'].'">'.htmlspecialchars($_contWinzer['title']).'</a></li>'.PHP_EOL;
}
$html .= '</ul>'.PHP_EOL; 
}
}
else {
// vermutlich: prüft ob es einen Artikel winzer/languedoc gibt. 
if ( isset($_chkIdent[0]) AND $_chkIdent[0] == "winzer" AND isset($_chkIdent[1]) AND $_chkIdent[1] == $_chkCatIndent) {
$_chkCat1 = 1;
//echo("<br>hu 3".$category_1['identifer']." inhalt oben");

$html .= '<li class="submenu-winzer-land "><a href="https://www.weinraum.de/winzer/winzer-'.$_chkIdent[1].'">'.$category_1['name'].'</a>'.PHP_EOL;
}
}
}
}
}
if ( $_chkCat1 == 0 ) {
//echo("<br>hu 4".$category_1['identifer']." inhalt oben");

//$html .= '<li class="submenu-winzer-land "><a href="https://www.weinraum.de/winzer/'.$category['identifer'].'/'.$category_1['identifer'].'">'.$category_1['name'].'</a>'.PHP_EOL;
$html .= '<li class="submenu-winzer-land txt">'.$category_1['name'].PHP_EOL;
}

}
if ( $_Winzer == "region" ) { if (  $head_cont_cat2 == 1 ) { $html .= '<ul class="second-menu">'.PHP_EOL; }}
if ( $_Winzer == "winzer" ) { if (  $head_cont_cat2 == 1 ) { $html .= '<ul class="second-winzer-menu">'.PHP_EOL; }}
// folgende abfrage sollte identisch mit $head_contains_cont == 1  sein. trotzdem, um array fehler zu vermeiden:
if (is_array($categories_2) AND count($categories_2) > 0) { 
$lala ="";
// appellation
foreach($categories_2 as $category_2 ) {
$count_cat2 = $Category_model->getCategoryContentIds($category_2['id']);
$count_2 = count($count_cat2);
$cat_ids2[$category_2['id']] = $Category_model->getCategoryDirectContentIds($category_2['id']);

// prüft, ob in der cat inhalte stehen
if ( $count_2 > 0) { 
if ( $count_2 == 1 ) {
$contentID = $Category_model->getCategoryContentIds($category_2['id']);
$lala = $Content_model->just_get($contentID[0]);
}
if ( $_Winzer == "region" AND isset($lala['date_mod'])) {
//echo("con {$contentID[0]}");exit();
$date = new DateTime($lala['date_mod']);
$_dateFomat = date_format($date, DATE_W3C);
$html .= '<li><a href="https://www.weinraum.de/regionen/'.$lala['identifer'].'">'.htmlspecialchars($category_2['name']).'</a></li>'.PHP_EOL;
$xml .= '<url><loc>https://www.weinraum.de/regionen/'.$lala['identifer'].'</loc><lastmod>'.$_dateFomat.'</lastmod></url>'.PHP_EOL;
}
if ( $_Winzer == "winzer" ) {
$head_contWinzer = 0;
// Kinder dieser cat
$data_3 = array('parent_id' => $category_2['id'], 'status' => '1');
$categories_3 = $Category_model->get($data_3, 'position');
if (is_array($categories_3) AND count($categories_3) > 0) { 
$lala ="";
foreach($categories_3 as $category_3 ) {
// inhalte in Kategorie 3 MIT Unterkategorien, die gibt es jedoch nicht (?: Winzer)
$count_3 = sizeof($Category_model->getCategoryContentIds($category_3['id']));
$count_3_head = sizeof($Category_model->getCategoryDirectContentIds($category_3['id']));
// es gibt Inhalte
if ( $count_3 > 0) {$head_contWinzer = 1;}
}
}
// Winzer in der ersten Reihe ohne weitere Artikel
if ( $head_contWinzer == 0) {
$contentID = $Category_model->getCategoryContentIds($category_2['id']);
$_contWinzer = $Content_model->just_get($contentID[0]);
if ( $_contWinzer['title'] AND $_contWinzer['status'] == 1 ) {
//echo("<br> bääm {$_contWinzer['identifer']}");
$html .= '<li class="einWinReg"><a href="https://www.weinraum.de/winzer'.'/'.$_contWinzer['identifer'].'">'.htmlspecialchars($_contWinzer['title']).'</a></li>'.PHP_EOL;}
}
// Es gibt weitere Inhalte in der Kategorie   
if ( $head_contWinzer == 1) {

// Prüfen, ob zu Überschrift Appellation Artikel vorhanden
$_chkCat2 = 0;
if ( isset($cat_ids2[$category_2['id']]) AND is_array($cat_ids2[$category_2['id']])) { 
foreach ($cat_ids2[$category_2['id']] as $kc => $vc) { 
$_contCat2 = $Content_model->just_get($vc);
if ( isset($_contCat2['identifer']) ) {
$_chkIdentCat2 = explode("-", $_contCat2['identifer']);
$_chkCat2Indent = isset($category_2['identifer'])?$category_2['identifer']:NULL;

//if ( isset($_chkIdentCat2[1]) ) echo ("<br>cat $_chkCat2Indent - {$_chkIdentCat2[0]} - {$_chkIdentCat2[1]} -- $_chkCat2Indent");
//if ( !isset($_chkIdentCat2[1])) echo ("<br>cat $_chkCat2Indent - {$_chkIdentCat2[0]} - nix -- $_chkCat2Indent");

if ( isset($_chkIdentCat2[0]) AND $_chkIdentCat2[0] == "winzer" AND isset($_chkIdentCat2[1]) AND $_chkIdentCat2[1] == $_chkCat2Indent) {
//echo("<br>ganz unten 1".$category_2['name']." inhalt oben");

$_chkCat2 = 1;
$html .= '<li><a href="https://www.weinraum.de/winzer/winzer-'.$_chkIdentCat2[1].'">'.$category_2['name'].'</a>'.PHP_EOL;
}
}
}
}

if ( $_chkCat2 == 0) {
//echo("<br>ganz unten 2".$category_2['name']." inhalt oben");

//$html .= '<li><a href="https://www.weinraum.de/winzer'.'/'.$category['identifer'].'/'.$category_1['identifer'].'/'.$category_2['identifer'].'">'.htmlspecialchars($category_2['name']).'</a>'.PHP_EOL;
$html .= '<li class="txt">'.htmlspecialchars($category_2['name']).PHP_EOL;
}
//echo("<br>nix 33");

$html .= '<ul class="winzer-menu">'.PHP_EOL; 
if (is_array($categories_3) AND count($categories_3) > 0) { 
$lala ="";
// appellation
foreach($categories_3 as $category_3 ) {
$count_3 = sizeof($Category_model->getCategoryContentIds($category_3['id']));
// prüft, ob in der cat inhalte stehen
if ( $count_3 > 0) { 
if ( $count_3 == 1 ) {}
$contentID = $Category_model->getCategoryContentIds($category_3['id']);
$_contWinzer = $Content_model->just_get($contentID[0]);
}
if ( $_contWinzer['title'] AND $_contWinzer['status'] == 1 ) {
//echo("<br>ganz unten 3".$category_2['name']." inhalt oben");

$html .= '<li><a href="https://www.weinraum.de/winzer'.'/'.$_contWinzer['identifer'].'">'.htmlspecialchars($_contWinzer['title']).' </a></li>'.PHP_EOL;
}
}
}
$html .= '</ul></li>'.PHP_EOL;  
}
}
}
}
}
if (  $head_cont_cat2 == 1 ) { 
$html .= '</ul></li>'.PHP_EOL;  
}
}
} // inkl. abfrage head content
}

//$time_elapsed_secs = microtime(true) - $start;
//echo("<br><br>ende content 1  $time_elapsed_secs cat {$category['id']}<br>");

}
if (  $_submenue == 1 ) {  $html .= '</ul>';  }
else { $html .= '</li></ul>'.PHP_EOL; }
$html .= '</div></div>'.PHP_EOL;
$data['html'] = $html;
$data['xml'] = $xml;
}
return $data;
}

/* in Admin_themengarten wird $_menue über $menueAll['data'] = $Wine_model->get_menue(NULL, NULL, NULL, NULL, NULL, NULL) ;
zugewiesen und der Wert $menueAll an diese Funktion hier übergeben */

public static function getWeinHeadMenue_Mobile($_menue = NULL, $_color = NULL) {
if ( isset($_color) ) {  
$html = '';
$Wine_model = new Wine_model();
if ( isset($_menue['data']['pol1'] ) AND is_array( $_menue['data']['pol1'] ) && sizeof($_menue['data']['pol1']) > 0 ) {
$html .= '<ul>'.PHP_EOL;
if ( $_color == 'weisswein') {$html .= '<li class="submenu-land "><a href = "https://www.weinraum.de/weisswein" class="men_cont inakt rot">Alle Weißweine</a></li>'.PHP_EOL;}
if ( $_color == 'rotwein') {$html .= '<li class="submenu-land "><a href = "https://www.weinraum.de/rotwein" class="men_cont inakt rot">Alle Rotweine</a></li>'.PHP_EOL;}
if ( $_color == 'rose') {$html .= '<li class="submenu-land "><a href = "https://www.weinraum.de/rose" class="men_cont inakt rot">Alle Rosé</a></li>'.PHP_EOL;}
if ( $_color == 'cremant') {$html .= '<li class="submenu-land "><a href = "https://www.weinraum.de/cremant" class="men_cont inakt rot">Alle Schaumweine</a></li>'.PHP_EOL;}
if ( isset($_menue['data']['pol1'] ) AND is_array( $_menue['data']['pol1'] ) ) {
foreach ($_menue['data']['pol1'] as $k => $v) {
/* - zählen nachfolgendes menu
 * Hier ist die Option, die aktuell nicht vorgesehen ist: Menü «Rotwein», «Weisswein» etc. Ist mit dem Filter entfallen, weil das praktischer sein sollte* Aber auch komplizierter. Mal sehen, wie es angenommen wird. */
$show_pol1 = 0;
$wherePol1 = "pol1_ID =  ". $v['ID'];
$_whereType = "";
if ( $_color == 'weisswein') { $_whereType = "men_Weiss";  }
if ( $_color == 'rotwein') { $_whereType = "men_Rot";  }
if ( $_color == 'rose') { $_whereType = "men_Rose";  }
if ( $_color == 'cremant') { $_whereType = "men_Cremant";  }
$_men2['data'] = $Wine_model->get_head_menue($_whereType, $wherePol1);
if ( isset($_men2['data']['pol2'] ) AND is_array( $_men2['data']['pol2'] ) ) {
foreach ($_men2['data']['pol2'] as $kk => $vv) {
if ( $vv['noProdActive'] > 0 ) {$show_pol1 = 1;}
}
if ( $show_pol1 == 1 ) {
$html .= '<li class="submenu-land '.$v['name_pol1'].' "><a  href="https://www.weinraum.de/'.$_color.'/'.strtolower($v['name_pol1_url']).'" >'.$v['name_pol1'].'</a><ul class="second-menu">'.PHP_EOL;
$wherePol1 = "pol1_ID =  ". $v['ID'];
if ( isset($_menue['data']['pol2'] ) AND is_array( $_menue['data']['pol2'] ) ) {
foreach ($_men2['data']['pol2'] as $kk => $vv) {
if ( $vv['noProdActive'] > 0 ) {$html .= '<li><a href="https://www.weinraum.de/'.$_color.'/'.strtolower($v['name_pol1_url']).'/'.strtolower($vv['name_pol2_url']).'" >'.$vv['name_pol2'].'</a></li>'.PHP_EOL;}
}
}
$html .= '</ul>'.PHP_EOL;
}
}
}
$html .= '</ul>'.PHP_EOL;
}
}
}
return $html;
}

public static function get_linkesWeinMenu($menue = NULL, $_color = NULL ) {
$html = '';
if (isset($menue['data']['pol1'])) {
if (is_array($menue['data']['pol1'])) {
foreach ($menue['data']['pol1'] as $k => $v) { 
if (isset($v['noProdActive']) AND isset($_SESSION['critPol1'][$k]) ) {
if ( $v['noProdActive'] > 0 ) {    
if ( $_SESSION['critPol1'][$k]==1 ) { $link_land = $v['name_pol1_url'];}
}
}
}
}
}
if ( isset($menue['data']['pol2']) AND is_array($menue['data']['pol2'])) { 
foreach ( $menue['data']['pol2'] as $k => $v) {  
if ( isset($v['noProdActive']) AND isset($_SESSION['critPol2'][$k]) ) { 
if ( $v['noProdActive'] > 0 ) {      
if ( $_SESSION['critPol2'][$k] ==1 ) {
$ag_region = $v['name_pol2'];
$link_region = $v['name_pol2_url'];
}
}
}
}
}
if (isset($menue['data']['pol3']) AND is_array($menue['data']['pol3'])) {
foreach ($menue['data']['pol3'] as $k => $v) { 
if (isset($v['noProdActive']) AND isset($_SESSION['critPol3'][$k]) ) {

if ( $v['noProdActive'] > 0 ) { 
if ( $_SESSION['critPol3'][$k] == 1 ) {
$ag_appell = $v['name_pol3'];
$link_appell= $v['name_pol3_url'];
}
}    
}
}
}
if ( isset($menue['data']['pol3']) AND count($menue['data']['pol3']) > 0 AND isset($ag_region)) { 
$html .= '<br><br>'.PHP_EOL;
$html .= '<p class="cat-head-menue">Appellationen</p>'.PHP_EOL;
$html .= '<div class="left-men-regionen">'.PHP_EOL;
$html .= '<ul><li  class="submenu-appellation "><span>'.$ag_region.'</span>'.PHP_EOL;
$html .= '<ul class="second-menu ">'.PHP_EOL;
}
if ( isset($menue['data']['pol3']) AND is_array($menue['data']['pol3']) AND isset($link_region) ) { 
foreach ($menue['data']['pol3'] as $k => $v) { 
if ( isset($v['noProdActive']) AND $v['noProdActive'] > 0 ) {    
$_appelaktiv = (isset($_SESSION['critPol3'][$k]) AND $_SESSION['critPol3'][$k]==1)?'aktiv':'';
if ( $_color == 'rotwein') {$html .= '<li><a href="https://www.weinraum.de/rotwein/'.$link_land.'/'.$link_region.'/'.$v['name_pol3_url'].'"  class="wine-cat-item '.$_appelaktiv.$v['name_pol3'].' </a></li>'.PHP_EOL;}
if ( $_color == 'weisswein') {$html .= '<li><a href="https://www.weinraum.de/weisswein/'.$link_land.'/'.$link_region.'/'.$v['name_pol3_url'].'"  class="wine-cat-item '.$_appelaktiv.$v['name_pol3'].' </a></li>'.PHP_EOL;}
if ( $_color == 'rose') {$html .= '<li><a href="https://www.weinraum.de/rose/'.$link_land.'/'.$link_region.'/'.$v['name_pol3_url'].'"  class="wine-cat-item '.$_appelaktiv.'">'.$v['name_pol3'].' </a></li>'.PHP_EOL;}
if ( $_color == 'cremant') {$html .= '<li><a href="https://www.weinraum.de/cremant/'.$link_land.'/'.$link_region.'/'.$v['name_pol3_url'].'"  class="wine-cat-item '.$_appelaktiv.'">'.$v['name_pol3'].' </a></li>'.PHP_EOL;}
if ( $_color == 'wein') {$html .= '<li><a href="https://www.weinraum.de/weine/'.$link_land.'/'.$link_region.'/'.$v['name_pol3_url'].'"  class="wine-cat-item '.$_appelaktiv.'">'.$v['name_pol3'].' </a></li>'.PHP_EOL;}
}
}

if ( count($menue['data']['pol3']) > 0 ) { 
$html .= '</ul></li></ul></div>'.PHP_EOL;
}
}

/*  ----------------  winzer */
if ( isset($menue['data']['producer'])  ) { 
$d =  count($menue['data']['producer']);
if ( count($menue['data']['producer']) > 0 ) {
$html .= '<br><br>'.PHP_EOL;
$html .= '<p class="cat-head-menue">Winzer</p>'.PHP_EOL;
$html .= '<div class="left-men-regionen">'.PHP_EOL;

if ( isset($ag_appell) AND $ag_appell != "" ) {$html .= '<ul><li  class="submenu-winzer "><span>'.$ag_region.', '.$ag_appell.'</span>'.PHP_EOL;}
if ( !isset($ag_appell) OR $ag_appell == "" ) {
if ( isset($ag_region) ) { $html .= '<ul><li  class="submenu-winzer "><span>'.$ag_region.'</span>'.PHP_EOL;}
}
$html .= '<ul class="second-menu ">'.PHP_EOL;    
foreach ($menue['data']['producer'] as $k => $v) { 
if ( $v['noProd'] > 0 ) {
if ( isset($link_appell) AND $link_appell != "" AND isset($link_region)) {
$html .= '<li><a href="/weine/'.$link_land.'/'.$link_region.'/'.$link_appell.'/'.strtolower($v['identifer_prod']).'" '.(isset($_SESSION['critProducer'][$k])?' class="wine-cat-item aktiv"':' class="wine-cat-item "').'>'.$v['producer'].' </a></li>'.PHP_EOL;
}
if ( (!isset($link_appell) OR $link_appell == "") AND isset($link_land) AND isset($link_region) AND isset($v['identifer_prod'])) {
$html .= '<li><a href="/weine/'.$link_land.'/'.$link_region.'/'.strtolower($v['identifer_prod']).'" '.(isset($_SESSION['critProducer'][$k])?' class="wine-cat-item aktiv"':' class="wine-cat-item "').'>'.$v['producer'].' </a></li>'.PHP_EOL;
}
}
}
$html .= '</ul></li></ul></div>'.PHP_EOL;
}
}
return $html;
}

public static function getArticlePrev($content) {
$html = "";
$html .= '<div class="column1"  style="margin: 25px 10px 0 0;">'.PHP_EOL;
$html .= '<div class="sidebar-article no-separator">'.PHP_EOL;
$html .= '<h3><a href="'.App::getUrl('themengarten/'.$content['urls'][0].'').'">'.$content['title'].'</a></h3>'.PHP_EOL;
$html .= '<div class="content-text-image-image-wrapper content-text-image-image-center">'.PHP_EOL;
$html .= '<a href="'.App::getUrl('themengarten/'.$content['urls'][0].'').'"><img src="/_gpost/si2_'.$content['id'].'__'.$content['image'].'" alt="'.$content['image'].'" /></a>'.PHP_EOL;
if($content['subtitle'] != ""){$html .= '<div>'.$content['subtitle'].'</div>'.PHP_EOL;}
$html .= '</div>'.PHP_EOL;
$html .= '<div class="content-text-image-content">'.PHP_EOL;
$html .= '<p>'.$content['teaser_5'].'</p>'.PHP_EOL;
$html .= '</div>'.PHP_EOL;
$html .= '<div class="article-more"><a href="'.App::getUrl('themengarten/'.$content['urls'][0].'').'">mehr...</a></div>'.PHP_EOL;
$html .= '<div class="clearer"></div>'.PHP_EOL;
$html .= '</div>'.PHP_EOL;
$html .= '</div>'.PHP_EOL;
$html .= '<div class="clearer"><br></div>'.PHP_EOL;
return $html;
}	

public static function getCart($kind_cart, $adressBook = NULL) {
$Rechnges = 0;
$ges_mwst = 0;
$ges_n = 0; 
$html = "";
if( isset($_SESSION['cart_art']) AND is_array($_SESSION['cart_art']) ) {
$html .= '<div class="ekz-warenkorb">'.PHP_EOL;
$html .= '<form ID="form_cart" method="post" action="'. current_url().'">'.PHP_EOL;

//---------- Neuen  Warenkorb erstellen -----------------
$inhalt = ""; 
$j = 0; $k = 0;
$noWeine = count($_SESSION['cart_art']);   
if( $noWeine > 0 ) {

foreach ($_SESSION['cart_art'] as $WID => $v) { 
$j++;
//echo("cart 4 $WID {$_SESSION['cart_art'][$WID]['product']['wine']['prod_name']}");
            //$data = $this->wine_model->get_Wine($WID);
$prod_ID = $WID;
$Rechnges += $v['number'] * $v['product']['wine']['price'];
$ges_mwst += $Rechnges/1.19*0.19;
$ges_n += $Rechnges/1.19;
$search     = array('\'', '.');
$replace    = array("", "");
if ( isset($v['product']['producer'])) {
$Ref_winzer = str_replace($search, $replace, $v['product']['producer']); // replaces sep. characters of names
}
if ( isset($v['product']['prod_name'])) {$Ref_name   = str_replace($search, $replace, $v['product']['prod_name']);}
if ( $j == 1 ) {$html .= '<p class="TitEkz">Wein'.($noWeine>1?'e':'').'</p>'.PHP_EOL;}
if ( $v['number'] != 0 ) {
if ( !isset($v['product']['name_pol3_url']) AND isset($v['product']['name_pol2_url']) ) { $v['product']['name_pol3_url'] = $v['product']['name_pol2_url'] ;}
$html .= '<div class="cartitem">'.PHP_EOL;
$html .= '<div class="wk-img">'.PHP_EOL;          
if ( isset($v['product']['name_pol3_url']) AND isset($v['product']['name_pol2_url'])  AND isset($v['product']['name_pol1_url']) AND isset($v['product']['identifer_prod']) AND isset($v['product']['wine']['prod_name']) ) { $html .= '<a href="/wein/'.$v['product']['name_pol1_url'].'/'.$v['product']['name_pol2_url'].'/'.$v['product']['name_pol3_url'].'/'.$v['product']['identifer_prod'].'/'.$WID.'" title="Detailansicht von '.$v['product']['wine']['prod_name'].'"  alt="Bild von '.$v['product']['wine']['prod_name'].'">'.PHP_EOL; }
$abfr_bild = "/is/htdocs/wp1112013_RXRAWWY6TZ/www/public/weine/alt/WeiID".$prod_ID."_254.jpg";
$abfr4_fl_q = "/is/htdocs/wp1112013_RXRAWWY6TZ/www/public/weine/flasche_quer_klein/".$prod_ID."_v.jpg";
if (!file_exists($abfr4_fl_q) AND file_exists($abfr_bild) ) { 
if ( isset($v['product']['wine']['prod_name']) ) { $html .= '<img src="'.PICPATH.'weine/alt/WeiID'.$prod_ID.'_254.jpg" class="img-responsive"  alt="Bild von '.$v['product']['wine']['prod_name'].'"/></a>'.PHP_EOL;}
}
if (file_exists($abfr4_fl_q)  ) {
if ( isset($v['product']['wine']['prod_name']) ) {      $html .= '<img src="'.PICPATH.'weine/flasche_quer_klein/'.$prod_ID.'_v.jpg" class="img-responsive"  alt="Bild von '.$v['product']['wine']['prod_name'].'"/>'.PHP_EOL;}
}
if ( !file_exists($abfr4_fl_q) AND !file_exists($abfr_bild)) { $html .= '<img src="/_/img/wein-folgt-q.jpg" alt="Bild für diesen Wein folgt bald"  class="img-responsive" />'.PHP_EOL;} 
$html .= '</a></div>'.PHP_EOL;
$html .= '<div class="wein">'.PHP_EOL;
// $v['product']['name_pol3_url'] is url of appellation, only in france. if value is not set, take region - value
if ( !isset($v['product']['wine']['name_pol3_url']) AND isset($v['product']['wine']['name_pol2_url']) ) { $v['product']['wine']['name_pol3_url'] = $v['product']['wine']['name_pol2_url'] ;}
if ( isset($v['product']['wine']['name_pol3_url']) AND isset($v['product']['wine']['name_pol2_url'])  AND isset($v['product']['wine']['name_pol1_url']) AND isset($v['product']['wine']['identifer_prod'])  ) { 
$_agName = $v['product']['wine']['prod_name']?$v['product']['wine']['prod_name']:"Der Wein";
$_agJahr = $v['product']['wine']['year']?", ".$v['product']['wine']['year']:"";
$html .= '<p><a href="/wein/'.$v['product']['wine']['name_pol1_url'].'/'.$v['product']['wine']['name_pol2_url'].'/'.$v['product']['wine']['name_pol3_url'].'/'.$v['product']['wine']['identifer_prod'].'/'.$WID.'">'.$_agName.' '.$_agJahr.PHP_EOL;
}
else { $html .= '<p>'; }
if ( isset($v['product']['producer']) AND isset($v['product']['wine']['year']) ) { $html .= $v['product']['producer'].'</a><br>Jahrgang: '.$v['product']['wine']['year'].'</p>'.PHP_EOL;	}
else { $html .= '</a></p>'; }
$html .= '</div>'.PHP_EOL;
if ( isset($v['product']['wine']['price']) ) { $html .= '<div class="price">'.APP::getMoneyValFormated($v['product']['wine']['price'], TRUE).'</div>'.PHP_EOL;}
else { $html .= '<div class="price"></div>'; }
$html .= '<div class="item-menue">'.PHP_EOL; 
if ($kind_cart != "checkout" ) {  $html .= '<div class="inpNum">'.PHP_EOL; }
if ($kind_cart == "checkout" ) { $html .= '<div class="inpNum show">'.PHP_EOL;   }
if ($kind_cart != "checkout" ) { $html .= '<div class="inpNum-txt ">Anzahl ändern</div>'.PHP_EOL;          }
$html .= '<a class="sonderW"><div class="cart-box wei_'.$prod_ID.'">'.PHP_EOL;
$html .= '<div class="head-cart"></div><div class="add_one  wei_'.$prod_ID.' ">'.PHP_EOL;
$html .= '<input type="hidden"  class="wein" name="hide_'.$prod_ID.'_'.$v['product']['wine']['price'].' " value="'.$v['number'].'" >'.PHP_EOL;
$html .= '<input type="hidden"  class="" name="baskOrg_'.$prod_ID.'" value="'.$v['number'].'" >'.PHP_EOL;
if ($kind_cart != "checkout" ) {$html .= '<div class="no"><span class="number">'.$v['number'].'</span>'.PHP_EOL; }
if ($kind_cart == "checkout" ) {$html .= '<div class="no-show"><span class="number-show">'.$v['number'].' Flaschen</span>'.PHP_EOL; }
if ($kind_cart != "checkout" ) {
$html .= '<ul class="warenkorb cart wei_'.$prod_ID.'"><li class="nix">1</li><li class="nix">2</li><li class="nix">3</li><li class="nix">4</li><li class="nix">5</li><li class="nix">6</li><li class="nix">12</li><li class="nix">18</li><li class="nix">24</li><li class="input">oder:</li><li class="input_val"><input  class="input_val" name="name_<?php echo  $prod_ID ?>" type="tel"/></li></ul>'.PHP_EOL;
$html .= '<div class="bu_in_ok"><span class="ti-check"></span></div>'.PHP_EOL; 
}
$html .= '</div></div></div></div></a>'.PHP_EOL;
if ($kind_cart != "checkout" ) {$html .= '<div class="loeschen"><div class="loe-butt-txt ">Löschen</div><div class="loe-butt"><a  href= "?weiEKZ['.$WID.']=0  "  class="basketButton "><span class="round">x</span></a></div></div>'.PHP_EOL;}
$html .= '</div>'.PHP_EOL;
$html .= '<div class="priceTot preisTot wei_'.$prod_ID.'">'.APP::getMoneyValFormated($v['number'] * $v['product']['wine']['price'], TRUE).'</div>'.PHP_EOL;

$html .= '</div>'.PHP_EOL;
$html .= '<div class="clearer"></div>'.PHP_EOL;
} // geht alle session weine durch
}
}
}
if ( isset($_SESSION['cart_pac']) AND is_array($_SESSION['cart_pac']) > 0 ) {
$anzPack = 0;
$ges = 0;
if( !isset($_SESSION['cart_art']) OR !is_array($_SESSION['cart_art']) ) {$html .= '<div class="ekz-warenkorb">'.PHP_EOL;}
foreach ($_SESSION['cart_pac'] as $pakID => $v) { //echo("bu $pakID lli : {$v['number']}");
$Rechnges += $v['number'] * $v['price'];
$ges_mwst += $Rechnges/1.19*0.19;
$ges_n += $ges/1.19;
$nu = count($_SESSION['cart_pac']);
if ( $v['number'] != 0 ) {
$anzPack ++;
// verschrobene abfrage: wenn packet in session gelöscht ist count session trotzdem > 0
if ( $anzPack == 1 ) {
$html .= '<div class="clearer"><br></div>'.PHP_EOL;
$html .= '<p class="TitEkz">Paket'.(count($_SESSION['cart_pac'])>1?'e':'').'</p>'.PHP_EOL;  
}  
// show package 
$html .= '<div class="cartPack">'.PHP_EOL;
$html .= '<div class="packHead">'.PHP_EOL; 
$html .= '<div class="name"> '.$_SESSION['cart_pac'][$pakID]['name'].'</div>'.PHP_EOL;
$html .= '<div class="preis"> '.APP::getMoneyValFormated($v['price'], TRUE).'</div>'.PHP_EOL;
$html .= '<div class="item-menue">'.PHP_EOL;    
if ($kind_cart != "checkout" ) {$html .= '<a class="sonderW"><div class="cart-box pack_'.$pakID.' ">'.PHP_EOL; }
if ($kind_cart == "checkout" ) {$html .= '<a class="sonderW show"><div class="cart-box pack_'.$pakID.' ">'.PHP_EOL; }
$html .= '<div class="add_one  pack_'.$pakID.' ">'.PHP_EOL;
$html .= '<input type="hidden"  class="wein" name="hide_'.$pakID.'_'.$v['price'].'_pack" value="'.$v['number'].'" >'.PHP_EOL;
$html .= '<input type="hidden"  class="" name="baskOrg_'.$pakID.'" value="'.$v['number'].'" >'.PHP_EOL;
if ($kind_cart != "checkout" ) {$html .= '<div class="no"><div class="inpNum-txt ">Anzahl ändern</div><span class="number">'.$v['number'].'</span>'.PHP_EOL;}
if ($kind_cart == "checkout" ) {$html .= '<div class="no show">'.$v['number'].' Paket'.(count($_SESSION['cart_pac'])>1?'e':'').''.PHP_EOL;}
if ($kind_cart != "checkout" ) {$html .= '<ul class="warenkorb cart pak_'.$pakID.'"><li class="nix">1</li><li class="nix">2</li><li class="nix">3</li><li class="nix">4</li><li class="nix">5</li><li class="nix">6</li><li class="nix">12</li><li class="nix">18</li><li class="nix">24</li><li class="input">oder:</li><li class="input_val"><input  class="input_val" name="name_<?php echo  $prod_ID ?>" type="tel"/></li></ul>'.PHP_EOL;}
$html .= '</div></div></div></a>'.PHP_EOL;
if ($kind_cart != "checkout" ) {$html .= '<div class="loeschen"><div class="loe-butt-txt ">Löschen</div><a href= "?pakEKZ['.$pakID.']=0" ><span class="round">x</span></a></div></div>'.PHP_EOL;}
if ($kind_cart == "checkout" ) {$html .= '</div>'.PHP_EOL;}
$html .= '<div class="preisTot pack_'.$pakID.'">'.APP::getMoneyValFormated($v['number'] * $v['price'], TRUE).'</div>'.PHP_EOL;
$html .= '</div>'.PHP_EOL;
$html .= '</div>'.PHP_EOL;
// show products in package
$gesWines  = 0;     
// foreach produkte herausgenommen. stehen ganz am ENDE von datei
$reduction = -$gesWines;
$html .= '<div class="clearer"></div>'.PHP_EOL;
} 
}
}
$html .= '<div class="clearer"><br><br></div>'.PHP_EOL;
$html .= '<p class="TitEkz">Gesamt</p>'.PHP_EOL;
$html .= '<div class="versand">'.PHP_EOL;
$html .= '<p class="Gesamt"><span class="text">Warenwert, inkl. MwSt.</span><span class="val">'. APP::getMoneyValFormated($Rechnges, TRUE, TRUE).'</span>'.PHP_EOL;
$_SESSION['cart']['ship_coun'] = isset($_SESSION['cart']['ship_coun'])?$_SESSION['cart']['ship_coun']:"DE";
$versand     =  $_SESSION['cart']['no_pack'] * $_SESSION['kostenPack'][$_SESSION['cart']['ship_coun']]; 
$ges_mwst += $versand/1.19*0.19;
$ges_n += $versand/1.19;
//$_SESSION['cart']['ship_countryClear'] = isset($dataShipCoun['country'])?$dataShipCoun['country']:"";
$html .= '<p class="Versandkosten desk"><span class="versko">Versandkosten</span><span class="land">'.$_SESSION['cart']['ship_countryClear'].'</span><span class="val">'.APP::getMoneyValFormated($_SESSION['cart']['versandkosten-wert-und-country'], TRUE, TRUE).'</span></p>'.PHP_EOL;
$html .= '<p class="Versandkosten mobile"><span class="versko">Versandkosten</span></p><p class="deu">'.APP::getMoneyValFormated($_SESSION['cart']['versandkosten-wert-und-country'], TRUE, TRUE).'</p>'.PHP_EOL;
if( $kind_cart == "checkout"   ) {
$html .= '<p class="Gesamt"><span class="text">Gesamtbetrag der Bestellung:</span><span class="val">'. APP::getMoneyValFormated($Rechnges+$_SESSION['cart']['versandkosten-wert-und-country'], TRUE, TRUE).'</span></p>'.PHP_EOL;
if( isset($_SESSION['debit_coupon_jetzt']) AND $_SESSION['debit_coupon_jetzt'] == 1) { 
$Couponges = 0;
foreach ($_SESSION['debit_coupon'] as $sessCoupID =>$sessCoupon) { $Couponges -= $sessCoupon['actual_amount'];}
if ( $Rechnges > abs($Couponges)) {     $inv_coupons = $Rechnges + $versand + $Couponges;$coupons_rest = 0;}
if ( $Rechnges < abs($Couponges)) { $inv_coupons = 0; $coupons_rest = $Couponges + $Rechnges; }
if ( $Rechnges == abs($Couponges)) { $inv_coupons = 0; $coupons_rest = 0; }
$html .= '<p class="Gesamt"><span class="text">Rechnungsbetrag nach  Anzug Ihrer Gutscheine: </span><span class="val">'.APP::getMoneyValFormated($inv_coupons, TRUE, TRUE).'</span></p>'.PHP_EOL;
}
}
$html .= '</div>'.PHP_EOL;
$html .= '<div class="clearer"></div>'.PHP_EOL;
$html .= '<div class="clearer"></div>'.PHP_EOL;
if ($kind_cart == "buy" ) { 
$html .= '<div class="order">'.PHP_EOL;
if ( $_SESSION['customer']['id'] > 0) {$html .= '<a href="/weinraum/adressbuch" class="forwardbutton">weiter </a>'.PHP_EOL;}
else {$html .= '<a href="/weinraum/anmelden/checkout" class="forwardbutton">weiter</a>'.PHP_EOL;}
$html .= '</div>'.PHP_EOL;
$html .= '<div class="clearer"></div>'.PHP_EOL;
} 
/* *    ----------------   coupons */
if( isset($_SESSION['debit_coupon']) AND is_array($_SESSION['debit_coupon']) AND $kind_cart != "checkout"   ) {  
$html .= '<h2 class="Gutscheine">Sie haben Gutscheine:</h2>'.PHP_EOL; 
$Couponges = 0;
foreach ($_SESSION['debit_coupon'] as $sessCoupID =>$sessCoupon) { 
                   
$html .= '<div class="coupon">'.PHP_EOL;
$html .= '<p class="row_coupon"><a href="/weinraum/own_coupons/">Von <b>'.$sessCoupon['name'].'</b></a>, im Wert von '.
$APP::getMoneyValFormated($sessCoupon['amount'], TRUE).PHP_EOL;
if ( $sessCoupon['actual_amount'] < $sessCoupon['amount'] ) {$html .= '<span class="preis"> Restwert nach Einlösung: '.APP::getMoneyValFormated($sessCoupon['actual_amount'], TRUE).'</span></p>'.PHP_EOL;	}
$html .= '</div>'.PHP_EOL;
$html .= '<div class="clearer"></div>'.PHP_EOL;
$Couponges -= $sessCoupon['actual_amount'];
}
if ( $Rechnges > abs($Couponges)) {     $inv_coupons = $Rechnges + $Couponges;$coupons_rest = 0;}
if ( $Rechnges < abs($Couponges)) { $inv_coupons = 0; $coupons_rest = $Couponges + $Rechnges; }
if ( $Rechnges == abs($Couponges)) { $inv_coupons = 0; $coupons_rest = 0; }
$html .= '<div class="clearer"></div>'.PHP_EOL;
$html .= '<h2 class="">Ihr Rechnungsbetrag mit eingelösten Gutscheinen:</h2>'.PHP_EOL;
$html .= '<div class="coupon-sum">'.PHP_EOL;
if ( count($_SESSION['debit_coupon']) >= 1 ) {$html .= '<p>Zu zahlender Rechnungsbetrag  (Lieferung nach D) bei Anzug Ihrer Gutscheine: '.APP::getMoneyValFormated($inv_coupons, TRUE, TRUE).'</p>'.PHP_EOL;}
$html .= '</div>'.PHP_EOL;
$html .= '<div class="clearer"></div>'.PHP_EOL;
}
// Seite Profen und Kaufen Button, keine wahl mehr, kein aktualisieren, nur anzeigen
if ($kind_cart == "buy" ) { 
$html .= '<div class="coupon-select">'.PHP_EOL;
if ( isset($_SESSION['debit_coupon_jetzt']) AND $_SESSION['debit_coupon_jetzt'] != 1 ) {
if ( count($_SESSION['debit_coupon']) >= 1 ) { $html .= '<a href="#" class="button coupon_recalc">Gutscheine einlösen</a>'.PHP_EOL; }
}
if ( isset($_SESSION['debit_coupon_jetzt']) AND $_SESSION['debit_coupon_jetzt'] == 1 ) {
if ( count($_SESSION['debit_coupon']) >= 1 ) { $html .= '<a href="#" class="button coupon_recalc">Gutscheine nicht einlösen</a>'.PHP_EOL; }
}
$html .= '</div>'.PHP_EOL;
$html .= '<div class="clearer"><br><br><br><br></div>'.PHP_EOL;
}
$html .= '</form>'.PHP_EOL;
$html .= '</div>'.PHP_EOL;
return $html;
}
public static function getWatchList() {
$html = "";
if(is_array($_SESSION['watchList']) ) {
$html .= '<div class=" watchList">';
foreach ($_SESSION['watchList'] as $WID => $v) { $j++;
$search     = array('\'', '.');
$replace    = array("", "");
$Ref_winzer = str_replace($search, $replace, $v['wine']['producer']); // replaces sep. characters of names
$Ref_name   = str_replace($search, $replace, $v['wine']['prod_name']);
$html .= '<div class="col-xs-12 col-sm-6 listitem">'.PHP_EOL;
$abfr_bild = FCPATH."bilder/WeiID".$WID."_254.jpg";
if (file_exists($abfr_bild)) { 
$html .= '<div class="image">';
$html .= '<a href="/wein/'.$v['name_pol1_url'].'/'.$v['name_pol2_url'].'/'.$v['name_pol3_url'].'/'.$v['identifer_prod'].'/'.$WID.'
" title="Detailansicht von ' .$v['wine']['prod_name'].'">'.PHP_EOL;    
$html .= '<img src="/_weinbrum/vorschau__WeiID'.$WID.'_254.jpg" alt="'.$v['wine']['prod_name'].'" class=""/></a>'.PHP_EOL;
$html .= '</div>'.PHP_EOL;
}
if (file_exists($abfr_bild)) { $html .= '<div class="weinML">'.PHP_EOL;}
else { $html .= '<div class="weinML ohne">'.PHP_EOL;}
if ( !$v['product']['name_pol3_url'] ) { $v['product']['name_pol3_url']  = $v['wine']['name_pol2_url']; }
$html .= '<p><a href="/wein/'.$v['name_pol1_url'].'/'.$v['name_pol2_url'].'/'.$v['name_pol3_url'].'/'.$v['identifer_prod'].'/'.$WID.'"><b>'.$v['wine']['prod_name'].'</b> von: '.$v['producer'].'</a>, '.PHP_EOL;
$html .= $v['product']['producer'].'</p>'.PHP_EOL;	
$html .= '<p class="listDet"><span class="det">Nr. '.$WID.', '.$v['wine']['content'].'l, '.PHP_EOL;
$html .= '</span><span class="preis">'.APP::getMoneyValFormated($v['wine']['price'], TRUE).'</span></p>'.PHP_EOL;
$key = array_key_exists($WID, $_SESSION['cart_art']);  
$html .= '<p class="button"> '.PHP_EOL;
if ( $key ) {
if ( $v['wines'][$WID]['no_stock']  > 0 ) {$html .= '<a  href= "?weiEKZ['.$WID.']=1  "  class="basketButton "></span><b>+</b>&nbsp;&nbsp;Warenkorb</a> '.PHP_EOL;}
if ( $v['wines'][$WID]['no_stock']  == 0 ) {$html .= '<a  href= ""  class="basketButton "></span>&nbsp;&nbsp;nicht mehr da</a> '.PHP_EOL;}
}
if ( !$key ) {
if ( $v['wines'][$WID]['no_stock']  > 0 ) {$html .= '<a  href= "?weiEKZ['.$WID.']=1  "  class="basketButton "></span><b>+</b>&nbsp;&nbsp;Warenkorb</a> '.PHP_EOL;}
if ( $v['wines'][$WID]['no_stock']  == 0 ) {$html .= '<a  href= ""  class="basketButton "></span>&nbsp;&nbsp;nicht mehr da</a> '.PHP_EOL;}
}
$html .= '<a href= "?weiWL['.$WID.']=0  " class="watchlistButton "></span><b>-</b>&nbsp;&nbsp;Merkzettel</a></p> '.PHP_EOL;
$html .= '</div>'.PHP_EOL;
$html .= '<p class="line"><span class="inner">&nbsp;</span></p>'.PHP_EOL;
$html .= '</div>'.PHP_EOL;
}
}
return $html;   
}

public static function getRebsortenMenu($_catID = NULL) {
$Content_model = new Content_model();
$html = '';
$xml = '';
$content = $Content_model->getContentForCategory($_catID, 1, "identifer");
$anz_cont = count($content);
if ($anz_cont%2 == 0  ) { $anzContHalf =  $anz_cont/2; } // gerade 
else { $anzContHalf =  ($anz_cont + 1)/2; } // ungerade 
$i_letter = 0;
$i_ges = 0;
$first_old = "nix";
foreach ($content as $v => $k)  { 
$i_ges += 1;   
$first_new = substr ( $k['identifer'] , 0 , 1 );
if ( $first_new != $first_old ) {  $new_letter = 1; }
if ( $first_new == $first_old ) { $i_letter += 1; $new_letter = 0;}
if ($new_letter == 0 ) { //gleicher Buchstabe 
if ($i_letter%2 != 0 ) { $html .= '<div class="row zeile_winzer col-12">'.PHP_EOL; } // ungerade  == Zeilenanfang 
$date = new DateTime($k['date_mod']);
$_dateFomat = date_format($date, DATE_W3C);
$html .= '<div class="col-12 col-sm-6 winzer-m-dat"  >'.PHP_EOL;
$html .= '<h2 class="head-modal-men"><a href ="/rebsorten/'.$k['identifer'].'"  class="winzer" >'.$k['title'].'</a></h2>'.PHP_EOL;
$xml .= '<url><loc>https://www.weinraum.de/rebsorten/'.$k['identifer'].'</loc><lastmod>'.$_dateFomat.'</lastmod></url>'.PHP_EOL;
if ( isset($k['pol2']) ) { $html .= '<p class="sub">'.isset($k['pol2'])?$k['pol2']:"".'</p>'.PHP_EOL; }
else { $html .= '<p class="sub"></p>'.PHP_EOL; }
$html .= '</div>'.PHP_EOL;
if ($i_letter%2 == 0  ) { $html .= '</div>'.PHP_EOL;}// gerade == Zeilenende 
}

if ($new_letter == 1 ) { // neuer Buchstabe =>Zeilenanfang  
$_agfirst = strtoupper ($first_new);
// erster Buchstabe. nix offen oder ungerade winzernummer   
if ($i_ges != 0 AND $i_letter%2 != 0 ) { $html .= '</div>'.PHP_EOL;}
$html .= '<div class="row zeile_winzer  col-12">'.PHP_EOL;
$html .= '<p class="letter">'.$_agfirst.'</p>'.PHP_EOL;
$date = new DateTime($k['date_mod']);
$_dateFomat = date_format($date, DATE_W3C);
$html .= '<div class="col-12 col-sm-6 winzer-m-dat"  >'.PHP_EOL;
$html .= '<h2 class="head-modal-men"><a href ="/rebsorten/'.$k['identifer'].'" class="winzer" >'.$k['title'].'</a></h2>'.PHP_EOL;
$xml .= '<url><loc>https://www.weinraum.de/rebsorten/'.$k['identifer'].'</loc><lastmod>'.$_dateFomat.'</lastmod></url>'.PHP_EOL;
$html .= '</div>'.PHP_EOL;
$i_letter = 1;
$first_old = $first_new;
}   
}
$html .= '</div>'.PHP_EOL;
$data['html'] = $html;
$data['xml'] = $xml;
return $data;
}


    
public static function getRebsortenLeftMenu($_catID = NULL) {
$Content_model = new Content_model();
$html = '';
$xml = '';
$content = $Content_model->getContentForCategory($_catID, 1, "identifer");
$anz_cont = count($content);
if ($anz_cont%2 == 0  ) { $anzContHalf =  $anz_cont/2; }// gerade 
else { $anzContHalf =  ($anz_cont + 1)/2;}
$i_letter = 0;
$i_ges = 0;
$first_old = "nix";
foreach ($content as $v => $k)  { 
$i_ges += 1;   
$first_new = substr ( $k['identifer'] , 0 , 1 );
if ( $first_new != $first_old ) { $new_letter = 1;} 

if ( $first_new == $first_old ) { $i_letter += 1;$new_letter = 0;}
if ($new_letter == 0 ) { //gleicher Buchstabe 
if ($i_letter%2 != 0 ) { $html .= '<div class="zeile_winzer col-xs-12">'.PHP_EOL;     } // ungerade  == Zeilenanfang 
$html .= '<div class="col-xs-12 winzer-m-dat"  >'.PHP_EOL;
$html .= '<p class="head-modal-men"><a href ="/rebsorten/'.$k['identifer'].'"  class="winzer" >'.$k['title'].'</a></p>'.PHP_EOL;
$xml .= '<url><loc>https://www.weinraum.de/rebsorten/'.$k['identifer'].'</loc></url>';
if ( isset($k['pol2']) ) { $html .= '<p class="sub">'.$k['pol2'].'</p>'.PHP_EOL; }
$html .= '</div>'.PHP_EOL;
if ($i_letter%2 == 0  ) { $html .= '</div>'.PHP_EOL;}// gerade == Zeilenende 
}

if ($new_letter == 1 ) { // neuer Buchstabe =>Zeilenanfang  
if ($i_ges != 0 AND $i_letter%2 != 0 ) { $html .= '</div>'.PHP_EOL;} // erster Buchstabe. nix offen oder ungerade winzernummer
$html .= '<div class="zeile_winzer  col-xs-12">'.PHP_EOL;
$html .= '<p class="letter">'.$first_new.'</p>'.PHP_EOL;
$html .= '<div class="col-xs-12 winzer-m-dat"  >'.PHP_EOL;
$html .= '<p class="head-modal-men"><a href ="/rebsorten/'.$k['identifer'].'" class="winzer" >'.$k['title'].'</a></p>'.PHP_EOL;
$xml .= '<url><loc>https://www.weinraum.de/rebsorten/'.$k['identifer'].'</loc></url>';
$html .= '</div>'.PHP_EOL;
$i_letter = 1;
$first_old = $first_new;
}
}
//$html .= '</div>'.PHP_EOL;
$data['html'] = $html;
$data['xml'] = $xml;
return $data;
}

//$xml .= $Content::getSitemapPassendLex(173, 180);

public static function getSitemapPassendLex ($_catPass = NULL, $_catLex = NULL, $_catMag1 = NULL, $_catMag2 = NULL, $_catMag3 = NULL, $_catMag4 = NULL) {
$Content_model = new Content_model();
$xml = '';
$_arr_IDs = array(0 => $_catPass, 1 => $_catLex, 2 =>  $_catMag1, 3 =>  $_catMag2, 4 =>  $_catMag3, 5 =>  $_catMag4);    
foreach ($_arr_IDs as $ki => $_catID) {
$content = $Content_model->getContentForCategory($_catID, 1, "identifer");
foreach ($content as $v => $k)  { 
$date = new DateTime($k['date_mod']);
$_dateFomat = date_format($date, DATE_W3C);
if ( $_catID == $_catPass ) { $xml .= '<url><loc>https://www.weinraum.de/wein_passend_zu/'.$k['identifer'].'</loc><lastmod>'.$_dateFomat.'</lastmod></url>'.PHP_EOL; }
if ( $_catID == $_catLex ) { $xml .= '<url><loc>https://www.weinraum.de/lexikon/'.$k['identifer'].'</loc><lastmod>'.$_dateFomat.'</lastmod></url>.PHP_EOL'.PHP_EOL; }
if ( $_catID == $_catMag1 ) { $xml .= '<url><loc>https://www.weinraum.de/magazin/'.$k['identifer'].'</loc><lastmod>'.$_dateFomat.'</lastmod></url>.PHP_EOL'.PHP_EOL; }
if ( $_catID == $_catMag2 ) { $xml .= '<url><loc>https://www.weinraum.de/magazin/'.$k['identifer'].'</loc><lastmod>'.$_dateFomat.'</lastmod></url>.PHP_EOL'.PHP_EOL; }
if ( $_catID == $_catMag3 ) { $xml .= '<url><loc>https://www.weinraum.de/magazin/'.$k['identifer'].'</loc><lastmod>'.$_dateFomat.'</lastmod></url>.PHP_EOL'.PHP_EOL; }
if ( $_catID == $_catMag4 ) { $xml .= '<url><loc>https://www.weinraum.de/magazin/'.$k['identifer'].'</loc><lastmod>'.$_dateFomat.'</lastmod></url>.PHP_EOL'.PHP_EOL; }
}
}
return $xml;
}


public static function getSitemapWeine($_menue) {
$xml = '';
$Wine_model = new Wine_model();
$_arr_color = array(0 => "rotwein", 1 => "weisswein", 2 => "rose", 3 => "cremant" );    
$Anzweine = 0;
foreach ($_arr_color as $k => $_color) {
if ( is_array( $_menue['data']['pol1'] ) && sizeof($_menue['data']['pol1']) > 0 ) {
foreach ($_menue['data']['pol1'] as $k => $v) {
/* ---------------- *  zählen nachfolgendes menu */    
$show_pol1 = 0;
$wherePol1 = "pol1_ID =  ". $v['ID'];
if ( $_color == 'weisswein') {$_whereType = "men_Weiss"; }
if ( $_color == 'rotwein') {$_whereType = "men_Rot"; }
if ( $_color == 'rose') {$_whereType = "men_Rose"; }
if ( $_color == 'cremant') {$_whereType = "men_Cremant"; }
$_men2['data'] = $Wine_model->get_head_menue($_whereType, $wherePol1);  
foreach ($_men2['data']['pol2'] as $kk => $vv) {
if ( $vv['noProdActive'] > 0 ) {$show_pol1 = 1;}
}     
if ( $show_pol1 == 1 ) {
$wherePol1 = "pol1_ID =  ". $v['ID'];
/* * strtolower und ?page=1 einfügen */
foreach ($_men2['data']['pol2'] as $kk => $vv) {
if ( $vv['noProdActive'] > 0 ) {
// suche nach weinen für farbe und LAND AND REGION
$where = "wr_product.stock_act > 0 AND wr_product.pol1_ID =  " . $v['ID']. " AND wr_product.pol2_ID =  " . $vv['ID'];
if ( $_color == 'weisswein') {if ( $where) { $where .= " AND (wr_product.type = 'WF' OR wr_product.type = 'WV' OR wr_product.type = 'WA' OR wr_product.type = 'WE' )";          }}
if ( $_color == 'rotwein') {if ( $where) { $where .= " AND (wr_product.type = 'RF' OR wr_product.type = 'RS' OR wr_product.type = 'RK' OR wr_product.type = 'RT' )";          }}
if ( $_color == 'rose') {if ( $where) { $where .= " AND wr_product.type = 'RO' ";          }}
if ( $_color == 'cremant') {if ( $where) { $where .= " AND wr_product.type = 'S' ";          }}
$weine = $Wine_model->get_Wines($where);
if ( isset($weine['wines']) AND is_array($weine['wines'])) {
foreach ($weine['wines'] as $prod_ID => $weiCont) {
$Anzweine ++;
if ( isset($weiCont['name_pol3_url']) AND $weiCont['name_pol3_url'] == "") { $weiCont['name_pol3_url'] = $weiCont['name_pol2_url']; }
$date = new DateTime($weiCont['date_mod']);
$_dateFomat = date_format($date, DATE_W3C);
$xml .= "<url><loc>https://www.weinraum.de/wein/".strtolower($weiCont['identifer'])."__".$weiCont['prodID'].'</loc><lastmod>'.$_dateFomat.'</lastmod></url>'.PHP_EOL;
}
}
}
}
}
}
}
}
return $xml;
}

}