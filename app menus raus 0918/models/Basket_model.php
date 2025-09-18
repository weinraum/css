<?php namespace App\Models;

use App\Models\Adressbook_model;
use App\Models\Customer_model;
use App\Models\Packages_model;
use App\Models\Package_content_model;
use App\Models\Save_basket_model;
use App\Models\Wine_model;


class Basket_model extends Aodel {



public function getBasket($weiID = NULL, $num_wei = NULL, $pakID = NULL, $num_pak = NULL, $coup_ID = NULL, $oldNum = NULL) {

//unset($_SESSION['cart_pac']);

//unset($_SESSION);

//echo("paoben $pakID");

$Wine_model = new Wine_model();
$Packages_model = new Packages_model();
$Package_content_model = new Package_content_model();
$Customer_model = new Customer_model();
$SaveBasket_model = new Save_basket_model();
$request = \Config\Services::request();

/*
$lulu =$Wine_model->get_Wine_Basket(1725);
$ret['weinName'] = $lulu['wine']['prod_name'];
$ret['weinID'] = $lulu['wine']['prodID'];
$ret['weinIdentifer'] = $lulu['wine']['identifer'];
$ret['weinPreis'] = $lulu['wine']['price'];
$ret['weinWinzer'] = $lulu['wine']['producer'];
$ret['weinJG'] = $lulu['wine']['year'];
$ret['weinCont'] = $lulu['wine']['content'];
*/

/* testen save basket  

$_SESSION['cart_art'][1721]['product'] = $Wine_model->get_Wine_Basket(1721);$_SESSION['cart_art'][1721]['number'] =2; 
$SaveBasket_model->update_basket();
unset($_SESSION['cart_art']); 
*/
/* * prüfen, ob angemeldeter besucher nach 25.5. 2021 double opt in */
     
if ( isset( $_SESSION['customer']['id'] ) AND is_numeric($_SESSION['customer']['id']) AND !isset($_SESSION['customer']['confirmed']) ) {//echo ("s {$_SESSION['customer']['id']}") ;
$customer = $Customer_model->get($_SESSION['customer']['id']);
if ( isset($customer[0]['signIn_confirm_time']) AND $customer[0]['signIn_confirm_time'] != "2000-01-01 00:00:00" AND $_SESSION['customer']['id'] > 4136) {$_SESSION['customer']['confirmed'] = 1;    }
if ( isset($customer[0]['signIn_confirm_time']) AND $customer[0]['signIn_confirm_time'] == "2000-01-01 00:00:00" AND $_SESSION['customer']['id'] > 4136) {$_SESSION['customer']['confirmed'] = 0;    }
if (  $_SESSION['customer']['id'] <= 4136) {$_SESSION['customer']['confirmed'] = 1;    }
}
//if (  isset( $_SESSION['customer'] ) AND !is_numeric($_SESSION['customer']['id'] ) ) {$_SESSION['customer']['confirmed'] = 1;    }
// zum Test:
// unset($_SESSION['cart_pac']); 
$pakEKZForm     = array();
$weiEKZ         = array();
$weiEKZForm     = array();
if ( !$weiID AND !$pakID AND !$coup_ID) {
$_chkPackEKZ =  $request->getGet('pakEKZ');   

if ( isset($_chkPackEKZ) AND !is_array($_chkPackEKZ) ) { $pakEKZ[$_chkPackEKZ] = 1; }
if ( isset($_chkPackEKZ) AND is_array($_chkPackEKZ) ) { $pakEKZ = $_chkPackEKZ; }
//foreach ( $pakEKZ as $k => $v) { echo ("k $k v $v"); }
$pakEKZForm     = $request->getPost('pakEKZForm');
$weiEKZ         = $request->getGet('weiEKZ');
$weiEKZForm     = $request->getPost('weiEKZForm');
if ($request->getVar('couponUse') == -1 OR $request->getVar('couponUse') == 1 ) {
$coupon_use    	= $request->getVar('couponUse');
}
}
if ( $pakID != NULL ) {
if ( $num_pak >= 0 ){ $pakEKZ[$pakID] = $num_pak; }
}
if ( $weiID != NULL ) {
if ( $num_wei >= 0 ){ $weiEKZ[$weiID] = $num_wei; }
}
if ( !empty($weiEKZForm) ) { $weiEKZ = $weiEKZForm; }
if ( !empty($pakEKZForm) ) { $pakEKZ = $pakEKZForm; }
$gesamt = 0;
$positionen = 0;
$ges_bott = 0;
if( !empty($weiEKZ)) {  
foreach ($weiEKZ as $weinID=>$weiAnzUeb) {
if ( !is_numeric($weiAnzUeb) ) { $weiAnzUeb = 0; }
if ( $weiAnzUeb < 0 ) { $weiAnzUeb = 0; }
$weiAnzUeb 	= round($weiAnzUeb);
$key = isset($_SESSION['cart_art'][$weinID])?true:false;  
if ( isset($_SESSION['cart_art'])) { 
// -------------------------------------------- 
// den wein gibt es schon in der warenkorb - session 
// -------------------------------------------- 
if ( $key  AND !isset($_SESSION['cart_art'][$weinID]['number']) OR (isset($_SESSION['cart_art'][$weinID]['number']) AND $_SESSION['cart_art'][$weinID]['number']!= $weiAnzUeb) ) {  // der Wein ist in der session enthalten, die Anzahl ist unterschiedlich 
if ( $weiAnzUeb > 0 ) {           
$_SESSION['cart_art'][$weinID]['number']    = $weiAnzUeb;
if ( isset($_SESSION['cart_art'][$weinID]['product']['stock_avail']) AND $_SESSION['cart_art'][$weinID]['product']['stock_avail'] >= $weiAnzUeb) {
$_SESSION['cart_art'][$weinID]['number'] = $weiAnzUeb;
$ret['no_org'] = $weiAnzUeb;
$ret['no_correct'] = $weiAnzUeb;
$ret['price2correct'] = $_SESSION['cart_art'][$weinID]['product']['wine']['price'];
}
if ( isset($_SESSION['cart_art'][$weinID]['product']['stock_avail']) AND $_SESSION['cart_art'][$weinID]['product']['stock_avail'] < $weiAnzUeb) {
$_SESSION['cart_art'][$weinID]['number'] = $_SESSION['cart_art'][$weinID]['product']['stock_avail'];
$ret['no_org'] = $weiAnzUeb;
$ret['no_correct'] = $_SESSION['cart_art'][$weinID]['product']['stock_avail'];
$ret['price2correct'] = $_SESSION['cart_art'][$weinID]['product']['wine']['price'];
}
}
//unset($_SESSION['cart_art'][$weinID]); 
if ( $weiAnzUeb == 0 ) { 
if ( isset($_SESSION['cart_art'][$weinID]) ) { unset($_SESSION['cart_art'][$weinID]);   }
if ( isset($_SESSION['cart_art'][0]) ) { unset($_SESSION['cart_art'][0]); }
}
/* if new value of product === 0 => delete
 * unset ersetzt scheinbar den index durch null. statt z.B. $_SESSION['cart_art'][87] mit Inhalt gibt es dann $_SESSION['cart_art'][0] ohne Inhalt */
}
}
// -------------------------------------------- 
// der Wein ist nicht in der session enthalten
// -------------------------------------------- 
if( !$key AND $weiAnzUeb > 0 ) { 
$_SESSION['cart_art'][$weinID]['product'] = $Wine_model->get_BasketWine($weinID);
$ret['weinName'] = $_SESSION['cart_art'][$weinID]['product']['wine']['prod_name'];
$ret['weinID'] = $_SESSION['cart_art'][$weinID]['product']['wine']['prodID'];
$ret['weinIdentifer'] = $_SESSION['cart_art'][$weinID]['product']['wine']['identifer'];
$ret['weinPreis'] = $_SESSION['cart_art'][$weinID]['product']['wine']['price'];
$ret['weinWinzer'] = $_SESSION['cart_art'][$weinID]['product']['wine']['producer'];
$ret['weinJG'] = $_SESSION['cart_art'][$weinID]['product']['wine']['year'];
$ret['weinCont'] = $_SESSION['cart_art'][$weinID]['product']['wine']['content'];
// prüfen ob sonderpreis für mail:
if ( isset($_SESSION['gpWeine']) AND is_array($_SESSION['gpWeine']) AND is_numeric($_SESSION['customer']['id'] )  ) {
foreach ($_SESSION['gpWeine'] as $k => $v) { 
$_typ = substr($k, 0, 3);
if ( $_typ == 'akt' ) {
foreach ($v['wines'] as $kw => $vw) { 

if ( $vw['ID'] == $weinID) {
if ( $_SESSION['cart_art'][$weinID]['product']['wine']['aktions_preis'] > 0) {
$_SESSION['cart_art'][$weinID]['product']['wine']['price'] = $_SESSION['cart_art'][$weinID]['product']['wine']['aktions_preis'];   
}
}
}
}
}
}
//$_SESSION['cart_art'][$weinID]['product']['stock_avail'] = $_SESSION['cart_art'][$weinID]['product']['wine']['stock_act'] - $_SESSION['cart_art'][$weinID]['product']['wine']['invoiced'];
//echo("moder avail {$_SESSION['cart_art'][$weinID]['product']['stock_avail']} = {$_SESSION['cart_art'][$weinID]['product']['wine']['stock_act']} - {$_SESSION['cart_art'][$weinID]['product']['wine']['invoiced']}"); 
if ( $_SESSION['cart_art'][$weinID]['product']['stock_avail'] >= $weiAnzUeb) {
$_SESSION['cart_art'][$weinID]['number'] = $weiAnzUeb;
$ret['no_org'] = $weiAnzUeb;
$ret['no_correct'] = $weiAnzUeb;
$ret['price2correct'] = $_SESSION['cart_art'][$weinID]['product']['wine']['price'];
}
if ( $_SESSION['cart_art'][$weinID]['product']['stock_avail'] < $weiAnzUeb) {
$_SESSION['cart_art'][$weinID]['number'] = $_SESSION['cart_art'][$weinID]['product']['stock_avail'];
$ret['no_org'] = $weiAnzUeb;
$ret['no_correct'] = $_SESSION['cart_art'][$weinID]['product']['stock_avail'];
$ret['price2correct'] = $_SESSION['cart_art'][$weinID]['product']['wine']['price'];
}
}
}                              
}
//echo("model insidde {$_SESSION['cart_art'][$weinID]['product']['wine']['ordered']},  {$ret['no_correct'] }<br>");
/*	 ----------------------------------------------------
 * 	add all wines for total price
 */ 
//unset($_SESSION['cart_art']);
if( isset($_SESSION['cart_art']) AND is_array($_SESSION['cart_art']) ) { 
foreach ($_SESSION['cart_art'] as $sessWeiID => $sessWein ) { 
if ( isset($_SESSION['cart_art'][$sessWeiID]['number'])) {
$gesamt += ($_SESSION['cart_art'][$sessWeiID]['number'] * $sessWein['product']['wine']['price']);
$ges_bott += $_SESSION['cart_art'][$sessWeiID]['number'];
$positionen += 1;
}
}
$_SESSION['cart']['ship_cost'] = $gesamt>100?-1:1; 
}
/*----------------------------------------------------------------------------------------------------------
 * 	                Pakete                    */
if( isset($pakEKZ) AND is_array($pakEKZ)) {
foreach ($pakEKZ as $pakID => $pakAnzUeb) { 
if ( $pakAnzUeb < 0 )  $pakAnzUeb = 0;
$pakAnzUeb = round($pakAnzUeb);
$key = isset($_SESSION['cart_pac'][$pakID])?true:false;  
// der Wein zu dem Paket ist schon im Warenkorb
if ( $key ) {  // die neue Anzahl ist unterschiedlich zu bestehenden Warenkorb
// wenn 'number' undefined :
$_SESSION['cart_pac'][$pakID]['number'] = isset($_SESSION['cart_pac'][$pakID]['number'])?$_SESSION['cart_pac'][$pakID]['number']:0; 
if ( $_SESSION['cart_pac'][$pakID]['number'] != $pakAnzUeb ) {  
$_SESSION['cart_pac'][$pakID]['number'] = $pakAnzUeb; 
if ( $pakAnzUeb == 0 ) { 
if ( isset($_SESSION['cart_pac'][$pakID]) ) { unset($_SESSION['cart_pac'][$pakID]); }
if ( isset($_SESSION['cart_pac'][0]) ) { unset($_SESSION['cart_pac'][0]); }
}
if ( $pakAnzUeb > 0 ) {
$_SESSION['cart_pac'][$pakID]['noFla'] = 0;
foreach ($_SESSION['cart_pac'][$pakID]['products'] as $k => $v) {  // im model wird weiID als index übergeben
$_SESSION['cart_pac'][$pakID]['noFla'] += $pakAnzUeb*$v['number'];
//echo("prod id $k:{$v['number']}: {$_SESSION['cart_pac'][$pakID]['products'][$k]['product_data']} :: {$_SESSION['cart_pac'][$pakID]['products'][$k]['product_data']}");
}
}
}
}
if( !$key AND $pakAnzUeb > 0 AND isset( $pakID ) AND is_numeric($pakID) ) { // der Wein in diesem Paket ist nicht in der session enthalten
$row_Pak = $Packages_model->get( $pakID );
if( isset( $row_Pak[0] ) ) { 
$_SESSION['cart_pac'][$pakID]               = $row_Pak[0];
$_SESSION['cart_pac'][$pakID]['number']     = $pakAnzUeb; 
$_SESSION['cart_pac'][$pakID]['PakAnz']     = $pakAnzUeb;
$row_WeiPak = $Package_content_model->getProducts($pakID);
$_SESSION['cart_pac'][$pakID]['noFla'] = 0;
foreach ($row_WeiPak as $k=>$v) {  // im model wird weiID als index übergeben
$_SESSION['cart_pac'][$pakID]['products'][$k]      = $v;
$_SESSION['cart_pac'][$pakID]['products'][$k]['product_data'] =$Wine_model->get_Wine($k);
$_SESSION['cart_pac'][$pakID]['noFla'] += $v['number']*$_SESSION['cart_pac'][$pakID]['number'];
}
}
// Werte werden in main_require verwendet. Sie geben die Eingabewerte zurück
$ret['no_org'] = $pakAnzUeb;
$ret['no_correct'] = $pakAnzUeb;
}
}
}
/*	 ------------------------------------------------------------------------------------------------------------------
 * 	        session pakete */
if ( isset($_SESSION['cart_pac']) ) { 
if( $_SESSION['cart_pac'] != "") {
foreach ($_SESSION['cart_pac'] as $pakID => $package) { //echo("paket $pakID number <br>");exit();
$gesamt += $package['price']*$package['number'];
$positionen += 1;
$ges_bott += $package['noFla'];
//echo("pa $pakID k {$package['noFla']} anz pak {$package['number']} alles $ges_bott ");
}
}
/* -----------  gesamt , versandkosten
 * Pakete sind immer inkl. Versand */
if ( count($_SESSION['cart_pac'] ) == 0) {  
// Kunde legt unter 100 in Warenkorb, ['ship_cost'] ist 1 und muss bei 102 Euro zu -1 gesetzt werden  
if ( $gesamt >= 100 ) { 
$_SESSION['cart']['ship_cost'] = -1;  
$ret['ship'] = 0;
}
if ( $gesamt > 0 AND $gesamt < 100 ) {  
$ret['ship'] = $_SESSION['kostenPack']['Versand normal'];  
$gesamt += $_SESSION['kostenPack']['Versand normal'];	
$_SESSION['cart']['ship_cost'] = 1;
}
}
// Pakete enthalten Versandkosten
if ( count($_SESSION['cart_pac']) > 0 ) { 
$_SESSION['cart']['ship_cost'] = -1; 
$ret['ship'] = 0;
}
}
// ANZAHL PAKETE
$chk_anz_pak = $ges_bott / 18; // anzahl 18er pakete
$no_18 = floor($chk_anz_pak);  // gehen alle flaschen in 18er oder muss ein kleines dazu?
if ( $no_18 <= 1 ) { $_SESSION['cart']['no_pack'] = 1; } // alles in 18er
if ( $no_18 == $chk_anz_pak ) { $_SESSION['cart']['no_pack'] = $no_18; } // alles in 18er
if ( $no_18 < $chk_anz_pak ) { $_SESSION['cart']['no_pack'] = $no_18 + 1; } // einz dazu
if ( $ges_bott == 0 ) { $_SESSION['cart']['no_pack'] = 0; } // nix

$_SESSION['cart']['total']   = $gesamt;
$ret['val'] = $gesamt;
$ret['positions'] = $positionen;
$_SESSION['cart']['positions']   = $positionen;
$_SESSION['cart']['tot_bott'] = $ges_bott;
/* speichern des basket in db */
$SaveBasket_model->update_basket();
$this->getVersandkosten();
/**    ----------------   coupons    */
if (isset($coupon_use ) AND isset($_SESSION['debit_coupon']) ) {
if($coupon_use == -1 AND is_array($_SESSION['debit_coupon']) ) { 
$_SESSION['debit_coupon_jetzt'] = -1;    
}
if(  $coupon_use == 1 AND is_array($_SESSION['debit_coupon'])  ) {
$_SESSION['debit_coupon_jetzt'] = 1;    
/* die sortierung der coupons im ekz ist nach erstell - datum. hier werden die kleinsten werte zuerst gebraucht.
 * das wurde im controller bereit gestellt, daher hier schleife nach coupon_sort. Die Schleife offenbart nur die keys in der 
 * Reihenfolge aufsteigender Werte. Auf die coupons wird dann mit diesem key zugegriffen. */
$sum_coup = 0;
foreach ($_SESSION['debit_coupon'] as $couponID =>$set_coupon) {
$coupon_saved = 0;
if ( $_SESSION['debit_coupon'][$couponID]['actual_amount'] + $sum_coup <= $gesamt) {
$sum_coup += $_SESSION['debit_coupon'][$couponID]['actual_amount'];    
$coupon_saved = 1;
}
if ( ($_SESSION['debit_coupon'][$couponID]['actual_amount'] + $sum_coup) > $gesamt AND $coupon_saved == 0 ) {
$sum_coup = $gesamt;    
//$_SESSION['coup_change_new'] = $_SESSION['debit_coupon'][$couponID]['Amount'] - ( $gesamt - $sum_coup); $_SESSION['coup_change_ID'] = $couponID;
}
}
$_SESSION['coup_change_tot'] = $sum_coup;
}
}
$ret['gesamt'] = $gesamt;
$ret['ges_bottlles'] = $ges_bott;
$ret['CSRFToken'] = csrf_hash();
$ret['oldNum'] = $oldNum;

if (isset($_SESSION['cart']['no_pack']) ) { $ret['ges_boxes'] = $_SESSION['cart']['no_pack']; }
return json_encode($ret);
}

public function getVersandkosten () {
$Adressbook_model = new Adressbook_model();
$Customer_model = new Customer_model();
// Prüfen Ausland Versand
if ( isset($_SESSION['shipment_Adr_ID']) AND is_numeric($_SESSION['shipment_Adr_ID'])) {
$shipCoun = $Adressbook_model->get($_SESSION['shipment_Adr_ID']); 
$_SESSION['cart']['ship_coun'] = isset($shipCoun[0]['country'])?$shipCoun[0]['country']:"";
if ( isset($shipCoun[0]['country']) ) {
$dataShipCoun = $Customer_model->getClearCountry($shipCoun[0]['country']); 
$_SESSION['cart']['ship_countryClear'] = isset($dataShipCoun['country'])?$dataShipCoun['country']:"";
$_SESSION['cart']['ship_coun'] = $shipCoun[0]['country'];
}
}
if ( (!isset($_SESSION['shipment_Adr_ID']) AND isset($_SESSION['customer']['contry'])) OR ( isset($_SESSION['customer']['contry']) AND isset($_SESSION['shipment_Adr_ID']) AND $_SESSION['shipment_Adr_ID'] == "Kunde") ) {
$_SESSION['cart']['ship_coun'] = isset($_SESSION['customer']['contry'])?$_SESSION['customer']['contry']:"";
$dataShipCoun = $Customer_model->getClearCountry($_SESSION['customer']['contry']); 
$_SESSION['cart']['ship_countryClear'] = isset($dataShipCoun['country'])?$dataShipCoun['country']:"";
}
if ( !isset($_SESSION['cart']['ship_coun']) ) {
$_SESSION['cart']['ship_coun'] = "DE";
$_SESSION['cart']['ship_countryClear'] = "Deutschland";
}

if ( isset($_SESSION['cart']['ship_coun']) AND $_SESSION['cart']['ship_coun'] != "DE" AND $_SESSION['cart']['ship_coun'] != "") { 
// ReWert unter 100 Euro = Versand Inland, Versand Ausland zusätzlich
if( isset($_SESSION['cart']['ship_cost']) AND $_SESSION['cart']['ship_cost'] ==  1 ) {  $_SESSION['cart']['versandkosten-wert-und-country'] = $_SESSION['kostenPack']['Versand normal'] + $_SESSION['cart']['no_pack'] * $_SESSION['kostenPack'][$_SESSION['cart']['ship_coun']]; }
// ReWert über 100 Euro = kein Versand Inland, Versand Ausland zusätzlich
if( isset($_SESSION['cart']['ship_cost']) AND $_SESSION['cart']['ship_cost']  ==  -1  ) {  $_SESSION['cart']['versandkosten-wert-und-country'] =  $_SESSION['cart']['no_pack'] * $_SESSION['kostenPack'][$_SESSION['cart']['ship_coun']]; }
}
if ( isset($_SESSION['cart']['ship_coun']) AND $_SESSION['cart']['ship_coun'] == "DE") {
if ( isset($_SESSION['cart']['ship_cost']) AND $_SESSION['cart']['ship_cost'] == 1 ) { $_SESSION['cart']['versandkosten-wert-und-country'] = $_SESSION['kostenPack']['Versand normal']; }
else { $_SESSION['cart']['versandkosten-wert-und-country'] = 0; }
}
if ( isset($_SESSION['cart']['versandkosten-wert-und-country']) AND isset($_SESSION['cart']['ship_cost']) ) {
//echo("ver {$_SESSION['cart']['versandkosten-wert-und-country']} // {$_SESSION['cart']['ship_cost']}-");
}
}




}
                            
?>
