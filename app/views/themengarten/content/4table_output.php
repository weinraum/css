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

?>


<div class="ads-row col-12 ">


<?php
/*
 * im form wird diese datei OHNE die globale variable data angesteuert, in der production - ausgabe liegen die daten vor.
 * das kann bei den out - put func verwirrende sein. zum einen kommt keine ausgabe im form, auf der seite schon.
 * besonders aber wenn man für das form die daten aus $data sucht und die nicht findet.
 * wenn ändern, dann in den controllern für admin und dort die $data als $this->data definieren. kostet bei nichtbeachten man schöne stunde!
 */

if ( isset($erste) AND is_array($erste)) {
foreach($erste as $k => $v) { 
$text = $App::getLinkfromBracket($v, $linkIdentifers);
if ( $k == 0 ) { $kopf = '<table class="four_col_table"><thead><tr><td>'.$text.'</td>'; }
if ( $k > 0 ) { $zeile[$k] = '<tr><td>'.$text.'</td>'; }
}
}
if ( isset($erste) AND !is_array($erste)) {
$text = $App::getLinkfromBracket($erste, $linkIdentifers);
$kopf = '<td>'.$text.'</td>';
}


if ( isset($zweite) AND is_array($zweite)) {
foreach($zweite as $k => $v) {
$text = $App::getLinkfromBracket($v, $linkIdentifers);
if ( $k == 0 ) { $kopf .= '<td>'.$text.'</td>'; }
if ( $k > 0 ) { $zeile[$k] .= '<td>'.$text.'</td>'; }
}
}
if ( isset($zweite) AND !is_array($zweite)) {
$text = $App::getLinkfromBracket($erste, $linkIdentifers);
$kopf = '<td>'.$text.'</td>';
}

if ( isset($dritte) AND is_array($dritte)) {
foreach($dritte as $k => $v) {
$text = $App::getLinkfromBracket($v, $linkIdentifers);
if ( $k == 0 ) { $kopf .= '<td>'.$text.'</td>'; }
if ( $k > 0 ) { $zeile[$k] .= '<td>'.$text.'</td>'; }
}
}
if ( isset($dritte) AND !is_array($dritte)) {
$text = $App::getLinkfromBracket($dritte, $linkIdentifers);
$kopf = '<td>'.$text.'</td>';
}


if ( isset($vierte) AND is_array($vierte)) {
foreach($vierte as $k => $v) {
$text = $App::getLinkfromBracket($v, $linkIdentifers);
if ( $k == 0 ) { $kopf .= '<td>'.$text.'</td></tr></thead><tbody>'; }
if ( $k > 0 ) { $zeile[$k] .= '<td>'.$text.'</td></tr>'; }
}
}
if ( isset($vierte) AND !is_array($vierte)) {
$text = $App::getLinkfromBracket($vierte, $linkIdentifers);
$kopf = '<td>'.$text.'</td>';
}

$table = $kopf;
if ( isset($zeile) AND is_array($zeile)) {
foreach ($zeile as $kz => $vz ) {
$table .= $vz;    
}
if ( isset($zeile) AND !is_array($zeile)) {
$table .= $zeile;    
}

}
$table .= "</table>";
echo $table;

?>
</div>

<style {csp-style-nonce} >
div.ads-row table tbody tr td a{color: #1577ad;}
@media(max-width:689px) {
div.ads-row   { padding: 0; margin: 35px 0 0 0;}
div.ads-row table tbody tr td {font: italic normal 400 11px/19px 'Georgia', sans-serif;padding: 5px 10px 5px 0;}
div.ads-row table thead tr td {font: normal normal 600 16px/24px 'brandon-grotesque', sans-serif;padding: 0 10px 5px 0;}
}

.content.passend div.ads-row   { padding: 0; margin: 35px 0 0 -11px;}

@media(min-width:690px) {
div.menuunderimage {position: relative;width: 100%;top: -5px;left: 0;margin: 0 0 0 0 !important;}
div.ads-row   { padding: 0; margin: 35px 0 0 5px;}
div.ads-row table  { hyphens: none;}
div.ads-row table tbody tr td {font: italic normal 400 13px/25px 'Georgia', serif;padding: 5px 50px 5px 0;}
div.ads-row table thead tr td {font: normal normal 600 15px/24px 'Georgia', serif;padding: 0 10px 5px 0;letter-spacing: 0.04em;}
}

div.ads-row  table tbody tr td:last-child  { padding: 5px 0 5px 0; }
div.ads-row table tbody tr td {border-bottom: 1px solid #d2584b;color: #000000;vertical-align: top;}
div.ads-row table thead tr td {border-bottom: 1px solid #d2584b;color: #000000;vertical-align: bottom;}
div.ads-row table thead tr td:last-child  { padding: 0 0 5px 0; }

</style>