<?php $App = new \App\Libraries\App();   ?>


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

