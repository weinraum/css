<?php $App = new \App\Libraries\App();  ?>
<script type="text/javascript" src="/_/js/admin_themen.js"  ></script>  
<link rel='stylesheet' href='/_/css/admin_themen.css' type='text/css' media='all' />

<form action="/<?php echo uri_string() ?>" method="post" enctype="multipart/form-data">
<?= csrf_field() ?>
<div class="d-flex flex-wrap  ">    
<div class="d-none d-sm-block col-sm-1 ">&nbsp;</div>
<div class="col-12 col-sm-9 ">
<h1>Mail Content Eingabe</h1>

<?php $var = '_id'; ?>
<input type="hidden" name="_id" value="<?php echo isset($POST[$var])?htmlspecialchars($POST[$var]):"";  ?>" />
<div>
<?php 
$von = isset($POST['von'])? $POST['von']: NULL;
$bis = isset($POST['bis'])? $POST['bis']: NULL;
$betreff = isset($POST['betreff'])?$POST['betreff']:"";
$header = isset($POST['header'])?$POST['header']:"";
$extra_header = isset($POST['extra_header'])?$POST['extra_header']:"";
$only_buyer = isset($POST['only_buyer'])?$POST['only_buyer']:"";
$reste = isset($POST['reste'])?$POST['reste']:"";
$stat = isset($POST['stat'])?$POST['stat']:"";
$shop_prod_id = isset($POST['shop_prod_id'])?$POST['shop_prod_id']:"";
$var = 'Emotion_ID'; 
$POST[$var] = isset($POST[$var])? $POST[$var]: ""; 
$value = $POST[$var];
?>
<div class="prep-data">
<?php if ( isset( $GpPrep['paket']) AND $GpPrep['paket'] == 0 ) { ?>
<h2>Der Mail - Inhalt ist als GeschmackPost vorgesehen. Es sind noch keine Pakete zu dem Inhalt angelegt. </h2>
<?php } 
if ( isset($mailEmotion) AND is_array($mailEmotion) ) { ?>
<div class="emotion left">
<div class="control-group">
<label class="control-label" for="id_<?php echo $var; ?>">Mail - Emotion</label>
<div class="controls">
<select name="<?php echo $var; ?>" id="id_<?php echo $var; ?>" class="country">
<option value="">keine</option>
<?php
foreach ( $mailEmotion as $kme => $vme ) { ?>
<option value="<?php echo $vme['id']?>" <?php echo $App::getSelectDATA($value, $vme['id']); ?>><?php echo ("{$vme['Jahreszeit']}: {$vme['Emotion']}")?></option>
<?php } ?>
</select>
</div>
</div></div>
<?php } ?>
<div class="prod left">
<label class="control-label" >shopi ID</label>
<div class="form-group">
<input  type="text" name="shop_prod_id" value="<?php echo isset($shop_prod_id)?$shop_prod_id:""; ?>" class="form-control" />
</div>                
</div>
<div class="von left">
<label class="control-label" >Mail senden von:</label>
<div class="form-group">
<input id="datetimepicker1" name= 'von' value="<?php echo isset($von)?$App::getHumanDate($von):""; ?>" class="form-control" />
</div>                
</div>
<div class="bis left">
<label class="control-label" >Mail senden bis:</label>
<div class="form-group">
<input id="datetimepicker2" name= 'bis' value="<?php echo isset($bis)?$App::getHumanDate($bis):""; ?>" class="form-control" />
</div>                
</div>

<div class="online left">
<label>Die Mail soll online?</label>
<div class="control-group">
<input type="checkbox" name="stat" value="1" <?php echo $stat == 1?"checked='checked'":""; ?> />
</div>
</div>

<div class="online left">
<label>Die Mail nur an Kunden, die Winzer schon gekauft haben?</label>
<div class="control-group">
<input type="checkbox" name="only_buyer" value="1" <?php echo $only_buyer == 1?"checked='checked'":""; ?> />
</div>
</div>

<div class="online left">
<label>Reste - Mail?</label>
<div class="control-group">
<input type="checkbox" name="reste" value="1" <?php echo $reste == 1?"checked='checked'":""; ?> />
</div>
</div>

<p class="warn"></p>
</div>
<div class="clearfix"><br></div>
<div class="control-group">
<label class="control-label" >Betreff der Mail</label>
<div class="controls">
<input type="text" name="betreff" class="input-block-level" value="<?php echo $betreff; ?>" />
</div>
</div>
<div class="clearfix"><br></div>
<div class="control-group">
<label class="control-label" >«Mail Vorschau ungeöffnet» - steht im Mail Programm als Inhaltsangabe. Entscheidet, ob Mail geöffnet wird. Feld etwa voll schreiben,</label>
<div class="controls">
<input type="text" name="header" class="input-block-level" value="<?php echo $header; ?>" />
</div>
</div>

<div class="clearfix"><br></div>

<div class="control-group">
<label class="control-label" >Paket Name der Weine zu der Mail. KURZ!</label>
<div class="controls">
<input type="text" name="extra_header" id="id_<?php echo $var; ?>" class="input-block-level" value="<?php echo $extra_header; ?>" />
</div>
</div>    
<div class="clearfix"><br></div>     
<?php $var = 'text'; ?>
<?php 
$POST[$var] = isset($POST[$var])? $POST[$var]: ""; 
$value = $POST[$var]; 
?>
<div class="control-group">
<label class="control-label" for="id_<?php echo $var; ?>">Text</label>
<div class="controls">
<?php echo $App::getEditor($var, $value); ?>
</div>
</div>     
<?php $var = 'img'; 
$POST['img'] = isset($POST['img'])? $POST['img']: ""; 
$label = "Bild zum Text - ".$POST['img']; 
if(isset($value) AND $value != "") {} ?>
<div class="control-group">
<label class="control-label"><?php echo $label; ?></label>
<div class="controls">  
<?php
$POST['contID'] = isset($POST['contID'])?$POST['contID']:"";
$_path = '/_data/'.$POST['contID'].'/mail/';
$_pathAbfr = FCPATH.'_data/'.$POST['contID'].'/mail/';
?>    
<div class="content-text-image-image-wrapper"  >
<?php
$var = 'img'; 
$_mailID = isset($POST['ID'])? $POST['ID']: "df"; 
$abfr = $_pathAbfr.$_mailID."_".$POST['img'];
$bild = $_path.$_mailID."_".$POST['img'];
if (is_file($abfr)) { ?>   
<img src="<?php echo $bild ?>" alt="Bild in der Mail"> 
<?php } ?>
</div>
</div>
<?php $label = ''; ?>
<div class="control-group">
<?php if($label!=''): ?>
<label class="control-label"><?php echo $label; ?></label>
<?php endif; ?>
<div class="controls">
<div class="fileupload fileupload-new" data-provides="fileupload">
<div class="input-append">
<input type="hidden" name="_img" value="<?php echo isset($POST['img'])?$POST['img']:''; ?>" /> 
<input type="file" accept="image/*" name="<?php echo $var; ?>" class="input-block-level" />
</div>
</div>
</div>
</div>
</div>
<?php  ?> 
<div class="control-group">
<label class="control-label">&nbsp;</label>

</div>  
</div>   
</div>
</div>


<div class="d-flex flex-wrap  ">    
<div class="d-none d-sm-block col-sm-1 ">&nbsp;</div>
<div class="col-12 col-sm-11 ">
<br><br>
<h1>Zuordnen Weine zu dieser Mail</h1>
<p class="title">Die Zuweisung der Weine zum Artikel und eine Variante - für teurere Weine, Rosé oder Rot. Um dem System beim Versand mehr Möglichkeiten 
zu geben und auch teurere Weine in die Mails zu bringen.</p>
</p>
<div class="form alloc_mail">
<input type="hidden" name="id" value="<?php echo $App::getValue('id'); ?>" />

<div class="product">
<p>Weine im Paket</p>
<?php //print_r($inp_data_alloc['product']);
if ( isset( $inp_data_alloc['product'] ) AND is_array( $inp_data_alloc['product']) ) {
$palim = 0;
foreach ( $inp_data_alloc['product'] as $k => $v ) { 
$palim += 1;
if ( is_array($v) ) {
foreach ( $v as $kp => $vp ) {
if ( is_numeric($k) ) { $proID[$palim] = $k; }
if ( $kp == "anz_fl" ) { $proAnz[$palim] = $vp; }
if ( $kp == "product_alt_id" ) { $prod_alt_id[$palim] = $vp; }
if ( $kp == "anz_alt" ) { $pro_anz_alt[$palim] = $vp; }

//echo ("<br>$kp - $vp");
}
}
} 
}
$_val_total = isset($inp_data_alloc['price_total'])?$inp_data_alloc['price_total']:0;
$_val_alt_total = isset($inp_data_alloc['price_alt_total'])?$inp_data_alloc['price_alt_total']:0;
 
for ($i = 1; $i <= 9; $i++) {
?>

<p class="alloc_mail">
<span class="wein_id">Wein <?php echo $i ?>: ID </span><input type="input" name="inp_data_alloc[product_id][<?php echo $i ?>]" value="<?php echo isset($proID[$i])?$proID[$i]:""; ?>">
<span class="wein_anz">Anzahl im Paket</span><input type="input" name="inp_data_alloc[anz_fl][<?php echo $i ?>]" value="<?php echo isset($proAnz[$i])?$proAnz[$i]:""; ?>">
<span class="alt_wein_id">Alternativer Wein: ID</span><input type="input" name="inp_data_alloc[product_alt_id][<?php echo $i ?>]" value="<?php echo isset($prod_alt_id[$i])?$prod_alt_id[$i]:""; ?>">
<span class="alt_wein_anz">Anzahl: </span><input type="input" name="inp_data_alloc[anz_alt][<?php echo $i ?>]" value="<?php echo isset($pro_anz_alt[$i])?$pro_anz_alt[$i]:""; ?>">
</p>
<?php } 
$_wert = isset($inp_data_alloc['price_total'])?$App::getMoneyValFormated($_val_total):"wird berechnet";
$_wert_alt = isset($_wert_alt)?$App::getMoneyValFormated($_val_alt_total):"wird berechnet";
?>
<p class="summe-berechnet line">
<span class="txt-org">Wert inkl. Versand:</span><span class="wert-org"><?php echo $_wert ?></span></p>
<p class="summe-berechnet">
<span class="txt-alt">Wert Alternative inkl. Versand:</span><span class="wert-alt"><?php echo $_wert_alt ?></span>
</p>
<p class="summe-eingabe">
<span class="wert">Preis ink Vers. oder Reste - %:</span><input type="text" name="inp_data_alloc[price_total]" value="<?php echo $_val_total; ?>">
<span class="wert alt">Und die Alternative:</span><input type="text" name="inp_data_alloc[price_alt_total]" value="<?php echo $_val_alt_total; ?>">
</p>

<div class="controls">
<button type="submit" class="btn"><?php echo lang('german_lang.submit'); ?></button>
<a href="<?php echo $backUrl; ?>" class="btn "><?php echo lang('german_lang.back'); ?></a>
</div>

</div> 
</div>
</div>
</div>
</form>

<div class="clearer"></div>  	




