</section><footer>

<?php $App = new \App\Libraries\App();  ?>
    
<div class ="d-flex flex-wrap col-footer  footer-desc-imp  col-12">
<div class ="col-footer-desc d-flex d-wrap col-12 col-md-4">
<div class ="d-xs-block d-md-none col-1">&nbsp;</div>
<div class ="col-11 col-md-12 footer-links">
<p class="footer">Einkaufen</p>
<p class='row-text' >Zahlung: Überweisung</p>
<p class='row-text' >Versand: meist in 1 - 2 Tagen</p>
<p class="img"><img src="/_/img/vers_kl.jpg" class="versand" alt="dhl" title="Versand mit DHL" loading="lazy"></p>
<a href="https://traubenwerke.de/policies/shipping-policy" class='' >Versandkosten</a>
<a href="https://traubenwerke.de/policies/refund-policy" class='' >Widerrufsrecht</a>
</div>
</div>  
<div class="clearer d-xs-block d-md-none "><br><br></div>
<div class ="col-footer-desc d-flex d-wrap  col-12 col-md-4">
<div class ="d-xs-block d-md-none col-1">&nbsp;</div>
<div class ="col-11 col-md-12">
<p class="footer">Information</p>
<a href="https://traubenwerke.de/blogs/der-weinraum/darum-weinraum" class='' >Darum weinraum</a>
<a href="https://traubenwerke.de/blogs/der-weinraum/personliche-beratung" class='' >Persönliche Beratung</a>
<a href="https://traubenwerke.de/policies/terms-of-service" class='' >AGB</a>
<a href="https://traubenwerke.de/policies/privacy-policy" class='' >Datenschutz</a>
<a href="https://traubenwerke.de/pages/impressum">Impressum</a>
<a href="https://traubenwerke.de/blogs/lexikon">Wein Lexikon</a>
<a href="https://traubenwerke.de/blogs/wein-passend-zu">Wein passend zu Essen</a>
</div>
</div>
<div class="clearer d-xs-block d-md-none"><br><br></div>
<div class ="col-footer-desc d-flex d-wrap  col-12 col-md-4">
<div class ="d-xs-block d-md-none col-1">&nbsp;</div>
<div class ="col-11 col-md-12 footer-kontakt">
<p class="footer">Ihr Kontakt mit dem weinraum</p>
<p class='row-text' ><a href="tel:08807946037" class="phone">Tel. 0881 122 327 66</a></p>
<p class='row-text' ><span>E-Mail:</span> <a href='mailto:thomas.henke@weinraum.de' class="email"> thomas.henke@weinraum.de</a>

<p class='row-text' ><br>Gerne helfe ich persönlich bei allen Fragen</p>

</div>
</div>  
<div class="clearer d-xs-block d-sm-none "><br><br></div>   
<div class="clearer"></div>
</div>
</footer>

<noscript id="css"></noscript>
<?= csrf_field() ?>
<?php if ( !isset($_SESSION['customer_unsubscribed']) OR (isset($_SESSION['customer_unsubscribed']) AND $_SESSION['customer_unsubscribed'] != 1)  ) { ?>
<div class="modal-warenkorb <?php echo ( (isset($_showkasse) AND $_showkasse == 1)?"block":"none"); ?>">
<div class="text">
<p class="schliessen">schliessen X</p>  
<div class="col-12 checkout">
<div class="head warenkorb waren <?php echo (isset($_SESSION['cart']['total']) AND $_SESSION['cart']['total']  > 0 )?"":"none"; ?>">
<picture class="img-responsive">
<source media="(max-width: 540px)" srcset= "/_/img/warenkorb-kopf.jpg">
<source media="(min-width: 541px)" srcset= "/_/img/warenkorb-kopf-scr.jpg">
<img src="/_/img/warenkorb-kopf.jpg" class="warenkorb" alt="der warenkorb" loading="lazy">
</picture> 
<div class="versandkosten none
<?php //echo ( (isset($_SESSION['cart']['total']) AND $_SESSION['cart']['total'] < 100)?"":"none" ); ?>">
<p >
<span class="hinw">Hinweis: noch </span>
<span class="rot"><?php echo $App::getMoneyValFormated(100 - (isset($_SESSION['cart']['total'])?$_SESSION['cart']['total']:0)) ?></span>
<span class="hinw">bis zur Lieferung </span>
<span class="rot"> ohne Versandkosten</span>
</p>
</div>
<p class="warenkorb">Ihr Warenkorb</p>
<div class="basket">
<p class="kopf warenwert">
<span class="li">Warenwert inkl. MwSt.:</span> 
<span class="re"><?php echo $App::getMoneyValFormated(isset($_SESSION['cart']['total'])?$_SESSION['cart']['total']:0) ?> &euro;</span>
</p>
<p class="kopf versandkosten">
<span class="li">Versandkosten:</span> 
<span class="re"><?php echo $App::getMoneyValFormated((isset($_SESSION['cart']['total']) AND isset($_SESSION['cart']['ship_cost']) AND $_SESSION['cart']['ship_cost'] != -1 )?$_SESSION['kostenPack']['Versand normal']:0, NULL, 1) ?> &euro;</span>
</p>
<div class="basketVals">
<div class="zurueck"><p>zurück</p></div>
<div class="zeile">
<p class="basket">
<span class="flaschen"><?php echo (isset($_SESSION['cart']['tot_bott']) AND $_SESSION['cart']['tot_bott'] > 0)?$_SESSION['cart']['tot_bott']:"?"; ?> Flaschen</span>
<span class="wert"><?php echo (isset($_SESSION['cart']['total']) AND $_SESSION['cart']['total'] > 0)? $App::getMoneyValFormated($_SESSION['cart']['total']).'&euro;':"?"; ?></span>
</p>
</div>
<?php
if ( !isset($_SESSION['customer']['GP_Empfaenger_ohne_Daten']) OR $_SESSION['customer']['GP_Empfaenger_ohne_Daten'] != 1 ) { ?>
<div class="kasse"><p>zur Kasse</p></div>
<?php }
if ( isset($_SESSION['customer']['GP_Empfaenger_ohne_Daten']) AND $_SESSION['customer']['GP_Empfaenger_ohne_Daten'] == 1 ) { ?>
<div class="kasse-gp-daten"><p>zur Kasse</p></div>
<?php } ?>
</div>
</div>
</div>

<div class="head warenkorb leer <?php echo ( (!isset($_SESSION['cart']['total'])  OR $_SESSION['cart']['total']  == 0 )?"":"none"); ?>">
<p class="head wk_leer">Ihr Warenkorb ist noch leer</p>
<div class="angaben">
<p class="txt"><span class="was">Versand in D</span><span class="ang"><?php echo isset($_SESSION['kostenPack']['Versand normal'])?$App::getMoneyValFormated($_SESSION['kostenPack']['Versand normal'], TRUE):""?> bis 100€; frei ab 100€</span>
<span class="ang_zwei">additionally, per parcel: AT 9.50€, EU 15.50€</span> </p>
<p class="txt"><br><span class="was">Versand</span><span class="ang">1 - 2 Werktage, DHL</span></p>
<p class="txt"><br><span class="was">Zahlung</span><span class="ang">Rechnung / Vorkasse bei Erstbestellung</span></p>
</div>
<div class="clearer"><br><br></div>

<div class="angaben kontakt">
<p class="txt kontakt"><span class="was">Beratung</span>
<a href="tel:088112232766" class="telefon">0881 122 327 66</a>
<span class="ang_zwei">Täglich von 8:00 bis 20:00 Uhr</span>
<a href="mailto:thomas.henke@weinraum.de" class="mail">thomas.henke@weinraum.de</a>
</p>
</div>
<div class="clearer"><br><br></div>

</div>
<div class="pakete">
<?php 
$Rechnges = 0;
$ges_mwst = 0;
$ges_n = 0;
$ges = 0;
$inhalt = ""; 
if (isset($_SESSION['cart_pac']) AND is_array($_SESSION['cart_pac']) ) {
$anzPack = 0;
foreach ($_SESSION['cart_pac'] as $pakID => $v) { //echo("bu $pakID lli : {$v['number']}");	
$v['price'] = (!isset($v['price']) OR $v['price'] == "")?0:$v['price'];
$v['number'] = (!isset($v['number']) OR $v['number'] == "")?0:$v['number'];
$v['name'] = (!isset($v['name']) OR $v['name'] == "")?0:$v['name'];
$Rechnges += $v['number'] * $v['price'];
$ges_mwst += $ges/1.19*0.19;
$ges_n += $ges/1.19;
$nu = count($_SESSION['cart_pac']);
if ( $v['number'] != 0 ) {
$anzPack ++;
// verschrobene abfrage: wenn packet in session gelöscht ist count session trotzdem > 0
if ( $anzPack == 1 ) { ?>
<div class="clearer"></div>
<p class="line">&nbsp;</p>
<p class="TitEkz">Paket <?php echo (count($_SESSION['cart_pac'])>1?'e':'')?> </p>
<?php    
}
$_anzMin = $v['number'] - 1;
$_anzPlus = $v['number'] + 1;
?>  
<div class="cartPack">
<div class="name "><?php echo $v['name']?></div>
<p class="head pakete ">
<span class="fla">Fl.</span>
<span class="einz">Einzel</span>
<span class="anz">Anz.</span>
<span class="ges">Gesamt</span></p> 
<div class="space left"><?php echo $v['noFla'] ?></div>
<div class="preis left"><?php echo $App::getMoneyValFormated($v['price'], TRUE)?></div>
<div class="item-menue left">   
<p class="pad"><a href = "/weinraum/kasse?pakEKZ[<?php echo $pakID ?>]=<?php echo $_anzMin ?>">-</a><span class="number"><?php echo $v['number']?></span><a href = "/weinraum/kasse?pakEKZ[<?php echo $pakID ?>]=<?php echo $_anzPlus ?>">+</a></p>
</div>
<div class="preisTot left pack_<?php echo $pakID ?>"><?php echo $App::getMoneyValFormated($v['number'] * $v['price'], TRUE)?></div>
<div class="loe left"><span class="icon ti-trash loe wei <?php echo  $pakID ?>"></span></div> 
</div>
<div class="clearer"></div>
<?php
} 
}
}
?>
</div>
<div class="produkte">
<?php
if( isset($_SESSION['cart_art']) ) {
$j = 0; $k = 0;
$noWeine = count($_SESSION['cart_art']);   
if( $noWeine > 0 ) {
foreach ($_SESSION['cart_art'] as $WID => $v) { 
$j++;
if ( isset($v['product']['wine']) ) {
$v['price'] = (!isset($v['price']) OR $v['price'] == "")?0:$v['price'];
$v['number'] = (!isset($v['number']) OR $v['number'] == "")?0:$v['number'];
$v['name'] = (!isset($v['name']) OR $v['name'] == "")?0:$v['name'];
$Rechnges += $v['number'] * $v['product']['wine']['price'];
$ges_mwst += $ges/1.19*0.19;
$ges_n += $ges/1.19;
$_pathPic = DOEMELLUNNCHE."flasche_klein/".$WID."_v.jpg";
$_pathPic_v_xs = DOEMELLUNNCHE."flasche_klein/".$WID."_v_xs.webp";

if ( $j > 1 ) { ?>
<div class="clearer"></div>
<?php } ?>
<div class="produkt wei_<?php echo $WID; ?>">
<?php if ( $j > 1 ) { ?>
<p class="line">&nbsp;</p>
<?php } ?>
<div class="img">
<a href="/wein/<?php echo strtolower($v['product']['wine']['identifer'])."__".$WID?>">
<?php 
if ( file_exists($_pathPic ) AND !file_exists($_pathPic_v_xs )  ) { ?>
<img src="<?php echo PICPATH; ?>weine/flasche_klein/<?php echo $WID ?>_v.jpg"  alt="<?php echo $v['product']['wine']['prod_name']; ?>" class="img-responsive" loading="lazy"/>
<?php 
} 
if ( file_exists($_pathPic_v_xs )  ) {
?>
<img src="<?php echo PICPATH; ?>weine/flasche_klein/<?php echo $WID ?>_v_xs.webp"  alt="<?php echo $v['product']['wine']['prod_name']; ?>" class="img-responsive" loading="lazy"/>
<?php 
} 
if ( !file_exists($_pathPic ) AND !file_exists($_pathPic_v_xs )  )  echo ("&nbsp");
?>
</a>
</div>
<div class="details">
<p class="producer"><?php echo ("{$v['product']['wine']['producer']} ");?></p> 
<a href="/wein/<?php echo strtolower($v['product']['wine']['identifer'])."__".$WID?>"><?php echo ("{$v['product']['wine']['prod_name']}, {$v['product']['wine']['year']}") ?></a>
<p class="head preis">
<span class="einz">Einzel</span>
<span class="fla">Anz.</span>
<span class="ges">Gesamt</span></p> 

<p class="cont preis"><span class="einz">
<?php echo $App::getMoneyValFormated($v['product']['wine']['price']); ?> &euro;  </span><span class="fla">
<?php echo $v['number']; ?></span>
<span class="ges">
<?php 
// der ti-trash war ein themify icon, jetzt wird auch von main_require darauf zugegriffen, daher lassen
echo $App::getMoneyValFormated($v['number'] * $v['product']['wine']['price'], TRUE) ?></span>
<span class="ti-trash icon loe-wk wk-modal wei_ <?php echo  $WID ?>"><img src="/_/img/ic-muell.webp" class="konto" alt="mein konto" loading="lazy"></span></p> 
<input type="hidden" class="wein" name="hide_<?php echo  $WID ?>_<?php echo $v['product']['wine']['price'] ?>_0_0_<?php echo $v['product']['wine']['content'] ?>" value="<?php echo (isset($_SESSION['cart_art'][$WID]['number']) ? $_SESSION['cart_art'][$WID]['number']:''); ?>" >
<input type="hidden" class="" name="baskOrg_<?php echo  $WID ?>" value="<?php echo (isset($_SESSION['cart_art'][$WID]['number']) AND is_numeric($_SESSION['cart_art'][$WID]['number']))?$_SESSION['cart_art'][$WID]['number']: 0; ?>" >
<ul class="warenkorb wk-modal wei_<?php echo  $WID ?> craft none">
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
<li class="input_val"><input class="input_val" name="name_<?php echo  $WID ?>" type="tel"/></li>
</ul>
<div class="bu_in_ok none wk-modal"><span class="einok">ok</span></div>
<p class="zusInf oben"><span class="inh">
<?php if ( $v['product']['wine']['content'] > 0 ) { echo ($v['product']['wine']['content']."l "); }
else { echo("0,75l");}?></span><span class="mwst">
Preis inkl. Mwst.</span></p> 
<p class="zusInf unten"><span class="inh">
<?php 
if ( $v['product']['wine']['content'] > 0 ) {
echo ($App::getMoneyValFormated($v['product']['wine']['price']/$v['product']['wine']['content'])."&euro;/l") ;
}
else {
echo ($App::getMoneyValFormated($v['product']['wine']['price']/0.75)."&euro;/l");
}
?> </span><span class="mwst"><a href="/weinraum/versandkosten" class="litext">Versandkosten</a></span></p> 
</div>
</div>
<?php
}
}
} 
}?>
</div>
<div class="clearer"></div>

<div class="produkt add_produkt">
<div class="img">
<a href=""><img src="" alt="neues Produkt" class="img-responsive" loading="lazy"></a>
</div>
<div class="details">
<p class="producer"></p> 
<a href="" class="product"></a>
<p class="head preis"><span class="einz">Einzel</span><span class="fla">Flaschen</span><span class="ges">Gesamt</span></p> 
<p class="cont preis"><span class="einz"></span><span class="fla"></span><span class="ges"></span><span class="icon ti-trash loe-wk wk-modal "><img src="/_/img/ic-muell.webp" class="konto" alt="mein konto" loading="lazy"></span></p> 
<input type="hidden" class="wein" name="" value="" >
<ul class="warenkorb wk-modal none craft add_none">
<li class="nix li_1"></li>   
<li class="nix li_2"></li>     
<li class="nix li_3"></li>     
<li class="nix li_4"></li>     
<li class="nix li_5"></li>   
<li class="nix li_6"></li>   
<li class="nix li_12"></li>   
<li class="nix li_18"></li>   
<li class="nix li_24"></li>   
<li class="input li_oder"></li>
<li class="input_val"><input  class="input_val" name="name_" type="tel"/></li>  
</ul>
<div class="bu_in_ok none wk-modal"><span class="einok">ok</span></div>

<p class="zusInf oben"><span class="inh"></span><span class="mwst">Preis inkl. Mwst.</span></p> 
<p class="zusInf unten"><span class="inh"></span><span class="mwst"></span></p> 
</div>
</div>
<?php

if( isset($_SESSION['cart']['total'])  AND $_SESSION['cart']['total']  > 0 ){ ?>
<div class="footer warenkorb">
<div class="zeile">
<div class="zurueck"><p>zurück</p></div>
<p class="basket">
<?php
if ( isset($_SESSION['cart']['tot_bott']) AND $_SESSION['cart']['tot_bott'] > 0) $_agAnz = $_SESSION['cart']['tot_bott'];
else $_agAnz = 0; 
if ( isset($_SESSION['cart']['tot_bott']) AND $_SESSION['cart']['tot_bott'] == 0) $_agFl = $_agAnz. " Flaschen";
if ( isset($_SESSION['cart']['tot_bott']) AND $_SESSION['cart']['tot_bott'] == 1) $_agFl = $_agAnz. " Flasche";
if ( isset($_SESSION['cart']['tot_bott']) AND $_SESSION['cart']['tot_bott'] >= 1) $_agFl = $_agAnz. " Flaschen";
if ( !isset($_SESSION['cart']['tot_bott']) ) $_agFl = $_agAnz. " Flaschen";
?>
<span class="flaschen"><?php echo $_agFl ?></span>
<span class="wert"><?php echo (isset($_SESSION['cart']['total']) AND $_SESSION['cart']['total'] > 0)? $App::getMoneyValFormated($_SESSION['cart']['total']).'&euro;':"?"; ?></span>
</p>
<?php
if ( !isset($_SESSION['customer']['GP_Empfaenger_ohne_Daten']) OR $_SESSION['customer']['GP_Empfaenger_ohne_Daten'] != 1 ) { ?>
<div class="kasse"><p>zur Kasse</p></div>
<?php }
if ( isset($_SESSION['customer']['GP_Empfaenger_ohne_Daten']) AND $_SESSION['customer']['GP_Empfaenger_ohne_Daten'] == 1 ) { ?>
<div class="kasse-gp-daten"><p>zur Kasse</p></div>
<?php } ?>
</div>
</div>
<?php } ?>
</div>
</div>
</div>
<?php } ?>
<div class="modal-suche none">
<div class="col-11 col-sm-10 col-md-9 text ">
<div class="boxSuche">

<div class="suche">
<p class="schliessen d-none d-sm-block"><img src="/_/img/ic-schliessen.gif" class="schliessen" alt="schliessen" loading="lazy"></p>

<div class="suinput">
<p class="head-susu">Wein oder Winzer suchen:</p> 
<input value="" placeholder="" id="winzer1">&nbsp;&nbsp;<img src="/_/img/ic-suche.gif" class="suche " alt="weinraum - suche">
<p class="schliessen d-block d-sm-none"><img src="/_/img/ic-schliessen.gif" class="schliessen" alt="schliessen" loading="lazy"></p>

</div>
</div>
</div>

<p class="head-sufi">Wein nach Eigenschaften filtern:</p> 

<div class="filter">
<div class="spacer">&nbsp;</div>
<div class="menue-landscape">
<div class="kopf">
<p class="wei tot">alle Weine: <?php echo isset($_SESSION['_anzWeiTot'])?$_SESSION['_anzWeiTot']:"" ?></p>
<p class="wei fil ">nach den Filtern: <?php echo isset($_SESSION['_anzWeiSess'])?$_SESSION['_anzWeiSess']:"" ?></p>
<p class="schliessen">Die Weine anzeigen</p> 

</div>

<div class="clearer"></div>
<div class="links">
<div class="typ linkBox <?php echo (isset($_SESSION['globalFilter']['typ']['show']) AND $_SESSION['globalFilter']['typ']['show'] == 1)?"aktiv session":"";?>"><p class="oben">Typ<img src="/_/img/ic-unten.gif" class="linkTyp " alt="schalter"></p><p class="unten"><?php echo isset($_SESSION['globalFilter']['typ']['klar'])?$_SESSION['globalFilter']['typ']['klar']:'' ?></p></div>
<div class="region linkBox <?php echo (isset($_SESSION['globalFilter']['region']['show']) AND $_SESSION['globalFilter']['region']['show'] == 1)?"aktiv session":"";?>"><p class="oben">Region<img src="/_/img/ic-unten.gif" class="linkTyp " alt="schalter"></p><p class="unten"><?php echo isset($_SESSION['globalFilter']['region']['klar'])?$_SESSION['globalFilter']['region']['klar']:'' ?></p></div>
<div class="traube linkBox <?php echo (isset($_SESSION['globalFilter']['traube']['show']) AND $_SESSION['globalFilter']['traube']['show'] == 1)?"aktiv session":"";?>"><p class="oben">Traube<img src="/_/img/ic-unten.gif" class="linkTyp " alt="schalter"></p><p class="unten"><?php echo isset($_SESSION['globalFilter']['traube']['klar'])?$_SESSION['globalFilter']['traube']['klar']:'' ?></p></div>
<div class="alkohol linkBox <?php echo (isset($_SESSION['globalFilter']['alkohol']['show']) AND $_SESSION['globalFilter']['alkohol']['show'] == 1)?"aktiv session":"";?>"><p class="oben">Vol.%<img src="/_/img/ic-unten.gif" class="linkTyp " alt="schalter"></p><p class="unten"><?php echo isset($_SESSION['globalFilter']['alkohol']['klar'])?$_SESSION['globalFilter']['alkohol']['klar']:'' ?></p></div>
<div class="preis linkBox <?php echo (isset($_SESSION['globalFilter']['preis']['show']) AND $_SESSION['globalFilter']['preis']['show'] == 1)?"aktiv session":"";?> ende"><p class="oben">Preis<img src="/_/img/ic-unten.gif" class="linkTyp " alt="schalter"></p><p class="unten"><?php echo isset($_SESSION['globalFilter']['preis']['klar'])?$_SESSION['globalFilter']['preis']['klar']:'' ?></p></div>
</div>
<div class="spacer ">&nbsp;</div>
<div class="linien ">
<div class="linieFil li2"></div>
<div class="linieFil li1"></div>
<div class="linieFil re1"></div>
<div class="linieFil re2"></div>
<div class="linieFil runterOben none"></div>
<div class="linieFil runterUnten none"></div>

</div>
<div class="range-content typ none">
<div class="select-typ">
<select name="_region" id="sel_typ" class="form-control form-select sel-filter typ <?php echo isset($_SESSION['globalFilter']['typ']['ID'])?$_SESSION['globalFilter']['typ']['ID']:'' ?>">
<?php 
if (isset($_SESSION['globalFilter']['typ']['ID'])) {$_datOpt = $_SESSION['globalFilter']['typ']['ID'];} 
else {$_datOpt = "";} ?>
<option  value="alle">alle</option>
<option disabled>____________________</option>
<option disabled>Weißwein</option>
<option <?php echo $App::getSelectDATA( $_datOpt, 'WF'); ?>>Jung & fruchtig</option>
<option <?php echo $App::getSelectDATA( $_datOpt, 'WV'); ?>>Volumenreich</option>
<option <?php echo $App::getSelectDATA( $_datOpt, 'WA'); ?>>Aromensorte</option>

<option disabled>____________________</option>
<option disabled>Rotwein</option>
<option <?php echo $App::getSelectDATA( $_datOpt, 'RF'); ?>>frisch & fruchtig</option>
<option <?php echo $App::getSelectDATA( $_datOpt, 'RS'); ?>>Seidig</option>
<option <?php echo $App::getSelectDATA( $_datOpt, 'RK'); ?>>Kräftig</option>
<option <?php echo $App::getSelectDATA( $_datOpt, 'RT'); ?>>Tanninreich</option>

<option disabled>____________________</option>
<option disabled>Rosé</option>
<option <?php echo $App::getSelectDATA( $_datOpt, 'RO'); ?>>Rosé</option>

<option disabled>____________________</option>
<option disabled>Schaumweine</option>
<option <?php echo $App::getSelectDATA( $_datOpt, 'CH'); ?>>Champagner</option>
<option <?php echo $App::getSelectDATA( $_datOpt, 'CR'); ?>>Crémant</option>
<option <?php echo $App::getSelectDATA( $_datOpt, 'PR'); ?>>Prosecco</option>
<option <?php echo $App::getSelectDATA( $_datOpt, 'SR'); ?>>Rosé</option>
</select>  
</div>
<div class="clearer range">&nbsp;</div>
</div>
<div class="range-content region-range none">
<div class="select-region">
<select name="_region" id="sel_reg" class="form-control form-select sel-filter region <?php echo isset($_SESSION['globalFilter']['region']['ID'])?$_SESSION['globalFilter']['region']['ID']:'' ?> ">
<option  value="alle">alle</option>
<option disabled>____________________</option>
<?php
echo $_sucheRegionen;
?>
 </select>  
</div>
<div class="clearer range">&nbsp;</div>
</div>
<div class="range-content trauben-range none">
<p class="button">
<span class="links <?php echo (isset($_SESSION['globalFilter']['preis']['max']) AND $_SESSION['globalFilter']['preis']['max'] == 10)?' aktiv':'' ?>">enthält</span>
<span class="rechts  <?php echo (isset($_SESSION['globalFilter']['preis']['max']) AND $_SESSION['globalFilter']['preis']['max'] == 100)?' aktiv':'aktiv' ?>">> 75%</span>
</p>
<div class="select-trauben">
<select name="_traube" id="sel_traube" class="form-control form-select sel-filter traube <?php echo isset($_SESSION['globalFilter']['traube']['ID'])?$_SESSION['globalFilter']['traube']['ID'].' ':'' ?> <?php echo isset($_SESSION['globalFilter']['traube']['min'])?$_SESSION['globalFilter']['traube']['min'].' ':'' ?> <?php echo isset($_SESSION['globalFilter']['traube']['max'])?$_SESSION['globalFilter']['traube']['max'].' ':'' ?>">
<option value="alle">alle</option>
<?php
echo $_sucheTrauben;
?>
</select>  
</div>
</div>

<div class="range-content alkohol-range none">
<p class="button"><span class="links <?php echo (isset($_SESSION['globalFilter']['alkohol']['klar']) AND strpos($_SESSION['globalFilter']['alkohol']['klar'], "<") !== FALSE)?' aktiv':'' ?>">weniger als</span><span class="rechts <?php echo (isset($_SESSION['globalFilter']['alkohol']['klar']) AND strpos($_SESSION['globalFilter']['alkohol']['klar'], ">") !== FALSE)?' aktiv':'' ?>">mehr als</span></p>
<div class=" alkohol">
<?php 
$_valSlider = 3;
if ( isset($_SESSION['globalFilter']['alkohol']['min']) AND $_SESSION['globalFilter']['alkohol']['min'] !== 0 ) {
switch( $_SESSION['globalFilter']['alkohol']['min'] ) {
case 12;$_valSlider = 1;break;
case 12.5;$_valSlider = 2;break;
case 13;$_valSlider = 3;break;
case 13.5;$_valSlider = 4;break;
case 14;$_valSlider = 5;break;
case 14.5;$_valSlider = 6;break;
}
}
if ( isset($_SESSION['globalFilter']['alkohol']['max']) AND $_SESSION['globalFilter']['alkohol']['max'] !== 20 ) {
switch( $_SESSION['globalFilter']['alkohol']['max'] ) {
case 12;$_valSlider = 1;break;
case 12.5;$_valSlider = 2;break;
case 13;$_valSlider = 3;break;
case 13.5;$_valSlider = 4;break;
case 14;$_valSlider = 5;break;
case 14.5;$_valSlider = 6;break;
}}
?>
<input type="range" id="rangeAlk" class="slider" min="1" max="6" value="<?php echo $_valSlider ?>" >
<p class="sl_labels"><span class="val">12.0%</span><span class="val">12.5%</span><span class="val">13.0%</span><span class="val">13.5%</span><span class="val">14.0%</span><span class="val le">14.5%</span></p></div>
<div class="clearer range">&nbsp;</div>
</div>

<div class="range-content preis-range none">
<p class="button">
<span class="linksDrei <?php echo (isset($_SESSION['globalFilter']['preis']['max']) AND $_SESSION['globalFilter']['preis']['max'] == 10)?' aktiv':'' ?>">< 10 &euro;</span>
<span class="mitte <?php echo (isset($_SESSION['globalFilter']['preis']['max']) AND $_SESSION['globalFilter']['preis']['max'] == 20)?' aktiv':'aktiv' ?>">10 - 20 &euro;</span>
<span class="rechtsDrei  <?php echo (isset($_SESSION['globalFilter']['preis']['max']) AND $_SESSION['globalFilter']['preis']['max'] == 100)?' aktiv':'' ?>">> 20 &euro;</span>
</p>
</div>


<div class="clearer range">&nbsp;</div>
</div>
</div>
</div>
</div>

<div class="modal-versandkosten none">
<div class="box">
<div class="line">
<p class="schliessen">schliessen</p>

<p class="head">Versandkosten in Deutschland</p>
<p class="text"><span class="label">Bestellwert ab 100€:</span><span class="value">Frei</span></p>
<p class="text"><span class="label">Bestellwert bis 100€:</span><span class="value"> 6.50€</span></p>
<p class="text"><span class="labelEU">Europe:</span><span class="valueEU"> +15.50€ per Parcel</span></p>
</div>
</div>
</div>

</body>
</html>

<style {csp-style-nonce} >
/* ----------- FOOTER  */
.col-footer p { text-align: left; }
.col-footer a { margin: 0 9px 0 0; }
div.footer-desc-imp { margin: 25px 0 25px 0; }
div.col-footer-desc p.footer {font: normal normal 700 20px/21px 'Roboto Slab', serif;color: #2d2a27;margin: 10px 0 7px 15px;display: block;width: 100%;text-transform: uppercase;text-align: left;letter-spacing: 0.04em;}
div.col-footer-desc p.row-text, div.col-footer-desc a, div.col-footer-desc p.row-text a {color: #2d2a27 !important;margin: 10px 0 7px 15px;display: block;width: 100%;text-align: left;letter-spacing: 0.02em;}
div.col-footer-desc p.row-text a {    margin: 10px 0 7px 0px; }
div.col-footer-desc p.row-text a.email , div.col-footer-desc p.row-text span { display: inline;}
div.col-footer-desc p.img img {max-height: 25px;}
/* ----- ----------- END FOOTER*/

div.col-footer {width: 100%;background-color: #f4f4ed;}
div.col-footer div.col-xs-12 {padding: 0;    }
.col-footer-desc .footer-kontakt {position: relative; }


@media only screen and (max-width : 640px) {
div.footer-links {padding: 0 0 0 0px;}
div.col-footer-desc p.row-text, div.col-footer-desc a, div.col-footer-desc p.row-text a {font: normal normal 400 15px/25px 'roboto', sans-serif;}
div.col-footer-desc p.img { margin: -3px 0 15px 15px; }
p.adress { height: 115px;font: normal normal 500 17px/22px 'roboto', sans-serif;color: #801515;padding: 0px 0 0 0;}
p.adress input { font: normal normal 500 15px/30px 'roboto', sans-serif;color: #141212;padding: 0 0 0 15px;height: 32px;width: 255px;}
p.adress a.gp-send { font: normal normal 500 16px/22px 'roboto', sans-serif;color: #FFF;margin: 0 0 0 15px;}
}
 
@media only screen and (min-width : 641px) {
div.footer-links {padding: 0 0 0 20px;}
div.col-footer { margin: 45px 0 0 0; }
div.col-footer p.title  {padding: 5px 0 13px 0;text-align: left;}
div.col-footer-desc p.row-text, div.col-footer-desc a, div.col-footer-desc p.row-text a {font: normal normal 400 15px/25px 'roboto', sans-serif;}
div.col-footer-desc p.img { margin: -3px 0 32px 15px; }
}

@media only screen and (min-width : 941px) {
div.col-footer { margin: 45px 0 0 0; }
div.col-footer p.title  {padding: 20px 0 25px 0;text-align: left;}
div.col-footer-desc p.row-text, div.col-footer-desc a, div.col-footer-desc p.row-text a {font: normal normal 400 15px/23px 'roboto', sans-serif;}
div.col-footer-desc p.img { margin: -3px 0 32px 15px; }
p.adress { height: 115px;font: normal normal 500 18px/26px 'roboto', sans-serif;color: #801515;padding: 4px 0 0px 14px;}
}

div.partner {height: 65px; }
div.partner img {display: block; float: left;    }
div.footer-xs {margin: 25px 0 25px 0;border-top: 1px solid #ddd; }

</style>

