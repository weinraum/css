<?php namespace App\Controllers;
 
use App\Models\Data_arschloch_model;
use App\Models\Basket_model;
use App\Models\Check_arschloch_model;
use App\Models\Customer_model;
use App\Models\Views_model;
use App\Models\Wine_model;


class Ajax extends Aontroller {
public function __construct() {
parent::__construct();
$this->data['navigation'] = 'compta';
}

public function modify_basket()	{ 
$basket_model= new Basket_model();
$request = \Config\Services::request();
$San = new \App\Libraries\Sanitize();

$_input_cust = $request->getPost('weiID');
$_input_cust = $San::sanitize_light($_input_cust);
$weiID = intval($_input_cust);

$_input_cust = $request->getPost('num_wei');
$_input_cust = $San::sanitize_light($_input_cust);
$num_wei = intval($_input_cust);


$_input_cust = $request->getPost('pakID');
$_input_cust = $San::sanitize_light($_input_cust);
$pakID = intval($_input_cust);

$_input_cust = $request->getPost('oldNum');
$_input_cust = $San::sanitize_light($_input_cust);
$pakID = intval($_input_cust);


//$arsch_data['basket'] = $arsch_data['basket']!=""?$arsch_data['basket']."-".$v:$v;

$oldNum = $_input_cust;

if( $pakID == 0) {$pakID = ''; }
$num_pak = intval($request->getPost('num_pak'));
if( $num_pak == 0) {$num_pak = ''; }
/*
$weiID             	= 334;
$num_wei             	= 222;
$pakID             	= $request->getPost('pakID');
$num_pak             	= $request->getPost('num_pak');

$weiID             	= 1724;
$num_wei             	= 32;
$pakID             	= '';
$num_pak             	= '';
*/
$agRes = $basket_model->getBasket($weiID, $num_wei, $pakID, $num_pak, NULL, $oldNum );

echo $agRes;
}

/* es der Filter bekommt teilweise IDs von Rebsorten, Regionen. Teilweise min/max Werte wie Preis, Alkohol aber dann keine ID
  Die Weintypen werden als ID - Wert übergeben, sind aber keine IDS, sondern Abkürzungen.
  Unsauber und verwirrend, beim bearbeiten erst verstehen. */
public function filter( ) {
$request = \Config\Services::request();
$wine_model= new Wine_model();
$San = new \App\Libraries\Sanitize();

$_RecFilter = $San::sanitize_light($request->getPost('filter'));
$_RecID = $San::sanitize_light($request->getPost('ID'));
$_RecKlar = $San::sanitize_light($request->getPost('klar'));
if ( null !== $_RecFilter){
if ( null !== $_RecID){
if ( $_RecID != "alle") { $_SESSION['globalFilter'][$_RecFilter]['show'] = 1; }
if ( $_RecID == "alle") { 
$_SESSION['globalFilter'][$_RecFilter]['show'] = 0;
$_SESSION['globalFilter'][$_RecFilter]['klar'] = "";
$_SESSION['globalFilter'][$_RecFilter]['min'] = isset($_SESSION['globalFilter'][$_RecFilter]['min'])?0:NULL;
$_SESSION['globalFilter'][$_RecFilter]['max'] = isset($_SESSION['globalFilter'][$_RecFilter]['max'])?0:NULL;
}
$_SESSION['globalFilter'][$_RecFilter]['ID'] = $_RecID;
if ( null !== $_RecKlar ) {
if ( $_RecFilter != "typ") {$_SESSION['globalFilter'][$_RecFilter]['klar'] = $_RecKlar;}
if ( $_RecFilter == "typ") {
switch($_RecID) { 
case 'alle'; $_SESSION['globalFilter']['typ']['klar'] = "alle"; break;
case 'WF'; $_SESSION['globalFilter']['typ']['klar'] = "Weiß fruchtig"; break;
case 'WV'; $_SESSION['globalFilter']['typ']['klar'] = "Weiß volumenreich"; break;
case 'WA'; $_SESSION['globalFilter']['typ']['klar'] = "Weiß aromenreich"; break;
case 'RF'; $_SESSION['globalFilter']['typ']['klar'] = "Rot fruchtig"; break;
case 'RK'; $_SESSION['globalFilter']['typ']['klar'] = "Rot kräftig"; break;
case 'RS'; $_SESSION['globalFilter']['typ']['klar'] = "Rot seidig"; break;
case 'RT'; $_SESSION['globalFilter']['typ']['klar'] = "Rot tanninreich"; break;
case 'RO'; $_SESSION['globalFilter']['typ']['klar'] = "Rosé"; break;
case 'CH'; $_SESSION['globalFilter']['typ']['klar'] = "Champagner"; break;
case 'CR'; $_SESSION['globalFilter']['typ']['klar'] = "Crémant"; break;
case 'PR'; $_SESSION['globalFilter']['typ']['klar'] = "Prosecco"; break;
case 'SR'; $_SESSION['globalFilter']['typ']['klar'] = "Schaumwein rosé"; break;
}
}
}
}
if ( $_RecFilter == "traube" AND null === $request->getPost('min')) {$_SESSION['globalFilter'][$_RecFilter]['min'] = 0;}
if ( $_RecFilter == "traube" AND null === $request->getPost('max')) {$_SESSION['globalFilter'][$_RecFilter]['max'] = 100;}
if ( $_RecFilter == "traube" AND $request->getPost('min') > 0) {$_SESSION['globalFilter'][$_RecFilter]['min'] = $request->getPost('min');}
if ( $_RecFilter == "traube" AND $request->getPost('max') > 0) {$_SESSION['globalFilter'][$_RecFilter]['max'] = $request->getPost('max');}

if ( null !== $request->getPost('min') ){ 
$_SESSION['globalFilter'][$_RecFilter]['show'] = 1; 
$_SESSION['globalFilter'][$_RecFilter]['min'] = $request->getPost('min'); }
if ( null !== $request->getPost('max') ){ 
$_SESSION['globalFilter'][$_RecFilter]['show'] = 1; 
$_SESSION['globalFilter'][$_RecFilter]['max'] = $request->getPost('max'); }
if ( (isset($_SESSION['globalFilter']['typ']['show']) AND $_SESSION['globalFilter']['typ']['show'] == 1) OR 
(isset($_SESSION['globalFilter']['region']['show']) AND $_SESSION['globalFilter']['region']['show'] == 1) OR 
(isset($_SESSION['globalFilter']['traube']['show']) AND $_SESSION['globalFilter']['traube']['show'] == 1) OR 
(isset($_SESSION['globalFilter']['alkohol']['show']) AND $_SESSION['globalFilter']['alkohol']['show'] == 1) OR 
(isset($_SESSION['globalFilter']['preis']['show']) AND $_SESSION['globalFilter']['preis']['show'] == 1)){
$_SESSION['globalFilter']['showResults'] = 1; }
else { 
$_SESSION['globalFilter']['showResults'] = -1; 
unset($_SESSION['_anzWeiSess']);
}
}
$_where = ""; $_grape['ID'] = ""; $_grape['min'] = ""; $_grape['max'] = "";
$_filterWhere = $wine_model->filter_where();
$_where = $_filterWhere['where'];
$_grape = $_filterWhere['grape'];
$weine = $wine_model->get_Wines($_where, NULL, NULL, NULL, $_grape);
$data['anz'] = (isset($weine['wines']) AND count($weine['wines'])>0)?count($weine['wines']):"0";
$data['CSRFToken'] =csrf_hash();
$data['min'] =$request->getPost('min');
$data['max'] =$request->getPost('max');

if ( $_RecFilter == "alkohol") {
// der Wert alle wurde oben schon abgefragt, da für die option - inputs verwandet um trauben, regionen auszusuchen.
if ( $_RecID != "alle") {
if ( $request->getPost('min') == 0 ) { $_SESSION['globalFilter']['alkohol']['klar'] = "< ".$request->getPost('max'); }
else { $_SESSION['globalFilter']['alkohol']['klar'] = "> ".$request->getPost('min'); }
}
}


if ( $_RecFilter == "preis") {
// der Wert alle wurde oben schon abgefragt, da für die option - inputs verwandet um typ, trauben, regionen auszusuchen.
if ( $_RecID != "alle") {
$_SESSION['globalFilter']['preis']['klar'] = $request->getPost('min')." - ".$request->getPost('max'); 
}
}

echo json_encode($data); 
if ( isset($weine['wines']) ) { $_SESSION['_anzWeiSess'] = count($weine['wines']); }
}  

public function test_arsch()	{
$data_arschloch_model = new Data_arschloch_model();
$check_arschloch_model = new Check_arschloch_model();
$arsch_data['date'] = date("Y-m-d H:i:s");
$arsch_data['id_arsch'] = 0;
$arsch_data['remote_addr'] = "";
$arsch_data['referrer'] = "";
$arsch_data['entry_page'] = "";
$arsch_data['cookie'] = "";
$arsch_data['search'] = "";
$arsch_data['login'] = "";
$arsch_data['basket'] = "";
$arsch_data['url_seg'] = "";
$arsch_data['login_attempts'] = "";
$arsch_data['no_short'] = "";
$arsch_data['no_long'] = "";
$arsch_data['post_unknown'] = "";
$dat["entryPage"] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$arsch_data['entry_page'] = $dat["entryPage"];
$arsch_data['referrer'] = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"kein ref";

if(isset($_SERVER['HTTP_X_SUCURI_CLIENTIP'])) { $arsch_data['remote_addr'] = $_SERVER['HTTP_X_SUCURI_CLIENTIP']; }
else { $arsch_data['remote_addr'] = $_SERVER["REMOTE_ADDR"]; }

$arsch_data['search'] = "alskdfj";

$arsch_data['id_arsch'] = 129;
$data_arschloch_model->save_arschloch($arsch_data);

}


public function select_wine()	{

$wine_model= new Wine_model();

$request = \Config\Services::request();
$term = $request->getPost("term");
$term_new = substr($term, 0, 10);


//$term = " when(";


$data_arschloch_model = new Data_arschloch_model();
$check_arschloch_model = new Check_arschloch_model();

$_saveArsch = -1;

if (str_contains(strtolower($term), "'or")) { $term_new = "Chat"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), '"or')) { $term_new = "Dom"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), "' or")) { $term_new = "Chat"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), '" or')) { $term_new = "Dom"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), "'  or")) { $term_new = "Chat"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), '"  or')) { $term_new = "Dom"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), "or'")) { $term_new = "Chat"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), 'or"')) { $term_new = "Dom"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), "or '")) { $term_new = "Chat"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), 'or "')) { $term_new = "Dom"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), "or  '")) { $term_new = "Chat"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), 'or  "')) { $term_new = "Dom"; sleep(rand(1, 4));$_saveArsch = 1;}

if (str_contains(strtolower($term), 'sleep')) { $term_new = "Brig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), '(')) { $term_new = "bul"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ')')) { $term_new = "rou"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' try')) { $term_new = "blanc"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' ;')) { $term_new = "Entre"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' \--')) { $term_new = "Domai"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ';')) { $term_new = "Mod"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), '\--')) { $term_new = "Mas"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' request')) { $term_new = "Mer"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' benchmark')) { $term_new = "Haut"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' select ')) { $term_new = "Unt"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' set ')) { $term_new = "Ber"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' select(')) { $term_new = "Gig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' set(')) { $term_new = "Sau"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' union')) { $term_new = "Bul"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' union(')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' union (')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' where')) { $term_new = "Bul"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' where(')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' ` ')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), '`')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' having ')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), 'insert into')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' update ')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' update')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' using')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), 'script')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), '.php')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), '?=')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), '.js')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' iframe')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' alert')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' using')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' merge')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' when(')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' then(')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' when ')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' then ')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
//echo $term;exit();


$arsch_data['date'] = date("Y-m-d H:i:s");
$arsch_data['id_arsch'] = 0;
$arsch_data['remote_addr'] = "";
$arsch_data['referrer'] = "";
$arsch_data['entry_page'] = "";
$arsch_data['cookie'] = "";
$arsch_data['search'] = "";
$arsch_data['login'] = "";
$arsch_data['basket'] = "";
$arsch_data['url_seg'] = "";
$arsch_data['login_attempts'] = "";
$arsch_data['no_short'] = "";
$arsch_data['no_long'] = "";
$arsch_data['post_unknown'] = "";
$dat["entryPage"] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$arsch_data['entry_page'] = $dat["entryPage"];
$dat["referrer"] =isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"";
$arsch_data['referrer'] = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"";

$arsch_data['remote_addr'] = $_SERVER["REMOTE_ADDR"];

$arsch_data['search'] = $term;

if ( $_saveArsch == 1 ) { 
$arsch_new['date'] = date("Y-m-d H:i:s");
$arsch_new['remote_addr'] = isset($_SERVER["REMOTE_ADDR"])?$_SERVER["REMOTE_ADDR"]:"keine ip";
$arsch_new['attempts'] = 0;
$_newID = $check_arschloch_model->save_CI_fremd($arsch_new, NULL, NULL, "stats");
$arsch_data['id_arsch'] = $_newID;
$data_arschloch_model->save_arschloch($arsch_data);
header('Location: https://www.weinraum.de/weinraum/abwesend'); exit();

}



$where  = "(prod_name LIKE '%".$term_new."%' or producer_name LIKE '%".$term_new."%' )";
$wine_model->ajax_get_Wines($where);
}

 
public function select_wineSmall()	{ 
$Wine_model= new Wine_model();
$request = \Config\Services::request();
$San = new \App\Libraries\Sanitize();

$ID = $San::sanitize_light($request->getPost("weiID"));
if ( is_numeric($ID)) { $Wine_model->ajax_get_WineSmall($ID); }
}

/* Die function ist aus zawe. hier ungenutzt */
public function select_winery()	{ 
$wine_model= new Wine_model();
$request = \Config\Services::request();
$term = $request->getPost("term");

$term_new = substr($term, 0, 10);

$data_arschloch_model = new Data_arschloch_model();
$check_arschloch_model = new check_arschloch_model();

$_saveArsch = -1;

if (str_contains(strtolower($term), "'or")) { $term_new = "Chat"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), '"or')) { $term_new = "Dom"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), "' or")) { $term_new = "Chat"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), '" or')) { $term_new = "Dom"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), "'  or")) { $term_new = "Chat"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), '"  or')) { $term_new = "Dom"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), "or'")) { $term_new = "Chat"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), 'or"')) { $term_new = "Dom"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), "or '")) { $term_new = "Chat"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), 'or "')) { $term_new = "Dom"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), "or  '")) { $term_new = "Chat"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), 'or  "')) { $term_new = "Dom"; sleep(rand(1, 4));$_saveArsch = 1;}

if (str_contains(strtolower($term), 'sleep')) { $term_new = "Brig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), '(')) { $term_new = "bul"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ')')) { $term_new = "rou"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' try')) { $term_new = "blanc"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' ;')) { $term_new = "Entre"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' \--')) { $term_new = "Domai"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ';')) { $term_new = "Mod"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), '\--')) { $term_new = "Mas"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' request')) { $term_new = "Mer"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' benchmark')) { $term_new = "Haut"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' select ')) { $term_new = "Unt"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' set ')) { $term_new = "Ber"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' select(')) { $term_new = "Gig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' set(')) { $term_new = "Sau"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' union')) { $term_new = "Bul"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' union(')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' union (')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' where')) { $term_new = "Bul"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' where(')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' ` ')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), '`')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' having ')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), 'insert into')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' update ')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' update')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' using')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), 'script')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), '.php')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), '?=')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), '.js')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' iframe')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' alert')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' using')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' merge')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' when(')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' then(')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' when ')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}
if (str_contains(strtolower($term), ' then ')) { $term_new = "Vig"; sleep(rand(1, 4));$_saveArsch = 1;}

$arsch_data['date'] = date("Y-m-d H:i:s");
$arsch_data['id_arsch'] = 0;
$arsch_data['remote_addr'] = "";
$arsch_data['referrer'] = "";
$arsch_data['entry_page'] = "";
$arsch_data['cookie'] = "";
$arsch_data['search'] = "";
$arsch_data['login'] = "";
$arsch_data['basket'] = "";
$arsch_data['url_seg'] = "";
$arsch_data['login_attempts'] = "";
$arsch_data['no_short'] = "";
$arsch_data['no_long'] = "";
$arsch_data['post_unknown'] = "";
$dat["entryPage"] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$arsch_data['entry_page'] = $dat["entryPage"];
$dat["referrer"] =isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"";
$arsch_data['referrer'] = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"";

if(isset($_SERVER['HTTP_X_SUCURI_CLIENTIP'])) { $arsch_data['remote_addr'] = $_SERVER['HTTP_X_SUCURI_CLIENTIP']; }
else { $arsch_data['remote_addr'] = $_SERVER["REMOTE_ADDR"]; }

$arsch_data['search'] = $term;

if ( $_saveArsch == 1 ) { 
$arsch_new['date'] = date("Y-m-d H:i:s");
$arsch_new['remote_addr'] = isset($_SERVER["REMOTE_ADDR"])?$_SERVER["REMOTE_ADDR"]:"keine ip";
$arsch_new['attempts'] = 0;
$_newID = $check_arschloch_model->save_CI_fremd($arsch_new, NULL, NULL, "stats");
$arsch_new['id_arsch'] = $_newID;
$data_arschloch_model->save_arschloch($arsch_data);
header('Location: https://www.weinraum.de/weinraum/abwesend'); exit();
}

$where  = "producer  LIKE '%".$term_new."%' ";
$wine_model->ajax_get_Wineries($where);
}

public function jutjupp()	{ 
$customer_model= new Customer_model();
$views_model= new Views_model();
$request = \Config\Services::request();

$cuid = $request->getPost("cuid");
$cookie_viewid = $request->getPost("cookie_viewid");
if ( isset($cuid ) AND is_numeric($cuid ) ) { $customer_model->save_CI4(array('jutju' => 1), $cuid ); }
if ( isset($cookie_viewid ) AND is_numeric($cookie_viewid) ) { $views_model->update_jutjupp( $cookie_viewid ); }
$_SESSION['_jutjupp'] = "alles";

$data['CSRFToken'] =csrf_hash();
echo json_encode($data); 
}

function php_mail($data) {
include(AFFENARSCH."mail_log.php");
require_once (FCPATH."mail/phpmailer610/src/Exception.php");
require_once (FCPATH."mail/phpmailer610/src/PHPMailer.php");
require_once (FCPATH."mail/phpmailer610/src/SMTP.php");


$mail  = new PHPMailer();
//$mail->setLanguage('fr', $path.'classes/phpmailer/language');
$mail->IsSMTP();

$mail->DKIM_domain = 'weinraum.de';
$mail->DKIM_private = FCPATH."mail/phpmailer610/pass/phpmailer_dkim_private.pem";
$mail->DKIM_selector = 'phpmailer';
$mail->DKIM_passphrase = '1400231455';
$mail->SMTPAuth = true;
$mail->Host = $mailHost;
$mail->Port = $mailPort;
$mail->Username = $mailUsername;
$mail->Password = $mailPassword;
$mail->DKIM_identity = $data['from_mail'];
$mail->DKIM_copyHeaderFields = false;
$mail->CharSet = 'UTF-8'; 
$mail->MsgHTML($data['html_message']);
$mail->AltBody    = $data['alt_message'];
$mail->Subject    = $data['subject'];
$mail->SetFrom($data['from_mail'], $data['from_name']);
$mail->AddAddress($data['send_to_mail'], $data['send_to_name']." ".  $data['send_to_family_name']);
if(!$mail->Send()) echo "Mailer Error: " . $mail->ErrorInfo;
$mail->ClearAddresses();
}

 



}
