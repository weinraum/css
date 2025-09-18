<?php 
namespace App\Libraries;
use App\Models\Link_ohne_ziel_model;

use \Datetime;

/* ----------   HANDLE POST DATE * 
 * static funktionen werden nicht in den aufrufenden Teil eingebunden sondern hier augerufen
 * Sie werden über "::" aufgerufen. nicht static funktionen werden eingebunden und über -> aufgerufen  */

class App {
public static function convertFilesArray(array $_files, $top = TRUE) {
$files = array();
foreach($_files as $name=>$file){
if($top) $sub_name = $file['name'];
else    $sub_name = $name;
if(is_array($sub_name)){
foreach(array_keys($sub_name) as $key){
$files[$name][$key] = array(
'name'     => $file['name'][$key],
'type'     => $file['type'][$key],
'tmp_name' => $file['tmp_name'][$key],
'error'    => $file['error'][$key],
'size'     => $file['size'][$key],
);
$files[$name] = self::convertFilesArray($files[$name], FALSE);
}
}else{$files[$name] = $file;}
}
return $files;
}

public static function saveFile($_file, $_destFolder, $_umask = 0777) {
$ret = TRUE;
$oldumask = umask(0);
if (!is_dir($_destFolder)) { 
if (!mkdir($_destFolder, $_umask, TRUE)) { $ret = FALSE; }
}
if (isset($_file['tmp_name']) AND isset($_file['name']) ) {
if (!$ret || !move_uploaded_file($_file['tmp_name'], $_destFolder.'/'.$_file['name'])) { $ret = FALSE; }
}
else { $ret = FALSE; }
umask($oldumask);
return $ret;
}

public static function chkDir($_destFolder, $_umask = 0777) {
$ret = TRUE;
$oldumask = umask(0);
if (!is_dir($_destFolder)) { 
if (!mkdir($_destFolder, $_umask, true)) { $ret = FALSE; }
}
umask($oldumask);
return $ret;
}

public static function getEditor($_var, $_data) {
$html = '';
$html .=<<<EOT
<textarea id="id_{$_var}" name="{$_var}" class=" textarea" rows="10" >{$_data}</textarea>
EOT;
return $html;
}

public static function getHumanDate($_date, $_format = 'dateformat_date') { // gibt eine variable aus der german_lang.php datei aus(language)
$date = NULL;
if ($_date != '') { $date = date(lang('german_lang.'.$_format), strtotime($_date));}
return $date;
}

public static function getGermanMonth($_date) { // gibt eine variable aus der german_lang.php datei aus(language)
$date = NULL;
if ($_date != '') {  $nummonth = date('n', strtotime($_date));}
switch ($nummonth) {
case 1:
    $date = "Janurar";
    break;
case 2:
    $date = "Februar";
    break;
case 3:
    $date = "März";
    break;
case 4:
    $date = "April";
    break;
case 5:
    $date = "Mai";
    break;
case 6:
    $date = "Juni";
    break;
case 7:
    $date = "Juli";
    break;
case 8:
    $date = "August";
    break;
case 9:
    $date = "September";
    break;
case 10:
    $date = "Oktober";
    break;
case 11:
    $date = "November";
    break;
case 12:
    $date = "Dezember";
    break;
}
$date = $date ." ";
return $date;
}

public static function getGermanMonthShort($_date) { 
$date = NULL;
if ($_date != '') { $nummonth = date('n', strtotime($_date));}
switch ($nummonth) {
case 1:
    $date = "Jan";
    break;
case 2:
    $date = "Feb";
    break;
case 3:
    $date = "Mär";
    break;
case 4:
    $date = "Apr";
    break;
case 5:
    $date = "Mai";
    break;
case 6:
    $date = "Jun";
    break;
case 7:
    $date = "Jul";
    break;
case 8:
    $date = "Aug";
    break;
case 9:
    $date = "Sep";
    break;
case 10:
    $date = "Okt";
    break;
case 11:
    $date = "Nov";
    break;
case 12:
    $date = "Dez";
    break;
}
$date = $date ." ";
return $date;
}

public static function getMoneyValFormated($_amount, $_euro = False, $_Null = False) {
$date = NULL;
if ($_amount != 0 ) {
if (!$_euro) { $date = number_format($_amount,2,'.',' '); }
if ($_euro) { $date = number_format($_amount,2,'.',' ') . " &euro;"; }
}
if ($_amount == 0 AND $_Null) {	$date = $_euro==true?"0.00 &euro;":"0.00";}
if ($_amount == 0 AND !$_Null) {	$date = ""; }
return $date;
}

public static function getSqlDate($_date) {
$date = NULL;
if ($_date != '') {$date = date('Y-m-d', DateTime::createFromFormat(lang('german_lang.dateformat_date'), $_date)->format('U'));}
return $date;
}
    
public static function getValue($_var = NULL, $_htmlize = TRUE, $POST = NULL) {
$request = \Config\Services::request();
$_method = $request->getMethod(); 
$var = NULL;
if ($_htmlize) {
if ($_method == "post") { $var = $request->getPost($_var)!== NULL?htmlspecialchars($request->getPost($_var)):NULL; }
if (isset($POST[$_var]) AND $_method != "post") { $var =htmlspecialchars($POST[$_var]); }
}
else {
if ($_method == "post") { $var = $request->getPost($_var); }
if (isset($POST[$_var]) AND $_method != "post") { $var = $POST[$_var];$var = (($var !='' && $var !='0' && $var !="0000-00-00")? $var:'');  }
}
return $var; 
}
    
/* CI3 las $_POST Werte aus, egal welche Methode (GET oder POST). CI4 nur bei POST. Die Urversion von decaf speichert auch bei get die Daten in der globalen Variable $_POST.
das kann man machen, sauber ist es nicht. In CI4 werden form Daten in $data['POST'] gespeichert. Sieht ähnlich aus, ist aber keine globale V.
getValueDATA war die erste Lösung, die Erweiterung von getValue um den Check der Methoden die (bessere?) Lösung des Problemes. */

public static function getValueDATA($POST = NULL, $_var = NULL, $_htmlize = TRUE) {
$var = NULL;
if ( $POST ) {
if ($_htmlize) {$var = htmlspecialchars($POST[$_var]);}
else { $var = isset($POST[$_var])?$POST[$_var]:NULL; }
}
return $var; 
}

public static function getValueChkNull($_var, $_htmlize = TRUE) {
$request = \Config\Services::request();
if ($_htmlize) {
if (is_string($request($_var))) {
$var = htmlspecialchars($request->getPost($_var));  
$var = (($var !='' && $var !='0' && $var !="0000-00-00")? $var:''); 
}
else { $var = NULL; }
}
else {
$var = $request->getPost($_var);
$var = (($var !='' && $var !='0' && $var !="0000-00-00")? $var:''); 
}
return $var;
}

public static function getCheckbox($_var = NULL, $_default = NULL, $POST = NULL) {
$request = \Config\Services::request();
$_method = $request->getMethod(); 
$html = '';
$html .= ' value="'.htmlspecialchars($_default).'"';
if ( $_method == "post") { 
if (NULL !== $request->getPost($_var) AND $request->getPost($_var) == $_default) { $html .= ' checked="checked"'; } 
if (NULL === $request->getPost($_var) AND isset($POST[$_var])) { if ( $POST[$_var] == $_default) { $html .= ' checked="checked"'; } }
}
if (isset($POST[$_var]) AND $_method != "post") { if ( $POST[$_var] == $_default) { $html .= ' checked="checked"'; } }
return $html;
}

public static function getDataCheckbox($_var, $_default = NULL, $POST = NULL) {
$html = '';
$html .= ' value="'.htmlspecialchars($_default).'"';
if ( isset($POST[$_var]) AND $POST[$_var] == $_default) {$html .= ' checked="checked"';}
return $html;
}


public static function getSelect($_var, $_value) {
$request = \Config\Services::request();
$_method = $request->getMethod(); 
$html = '';
$html .= ' value="'.htmlspecialchars($_value).'"';
if (is_array($request->getPost($_var))) {
if (in_array($_value, $request->getPost($_var))) {$html .= ' selected="selected"';}
}
else {
if ($request->getPost($_var) == $_value) {$html .= ' selected="selected"';}
}    
return $html;
}

/* neue Funktion der unteren getDataSelect für CI4 - geändert, weil POST und DB Daten besser getrennt werden können und
 * es mit CI4 nicht mehr so einfach war POST zu verwenden, wenn kein Form übergeben wurde (Method post).
 * 
 * Das hätte ich sicher mit einer Anweisung erzwingen können, aber die Trennung von Post und DB / session Daten macht ja auch Sinn.
 * 
 * Daher den Wert eines array - elementes in neuer Funktion übergeben.
 */

public static function getSelectDATA($_data = NULL, $_value = NULL) { 
// $_data = Wert aus POST oder Datenbank, $_value = Wert der im Select steht)
$html = '';
$html .= ' value="'.htmlspecialchars($_value).'"';
if (is_array($_data) ) {
if (in_array($_value, $_data)) {$html .= ' selected="selected"';}
}
else {
if ($_data == $_value) {$html .= ' selected="selected"';}
}    
return $html;
}

public static function getDataSelect($_var, $_value) {
$html = '';
$value = $request->getPost($_var);
$html .= ' value="'.htmlspecialchars($_value).'"';
if ($value[0]['value'] == $_value) { $html .= ' selected="selected"'; }
return $html;
}

public static function formError($_var, $_htmlize = TRUE) {
$validation =  \Config\Services::validation();
$html = '';
$html = '<p class="error">'. $validation->getError($_var).'</p>';
return $html;
}

public static function isAdmin() {
$admins = array(869);
$ret = FALSE;
if (self::isLoggedIn()) {
if ( isset($_SESSION['customer']) AND in_array($_SESSION['customer']['id'], $admins)) {$ret = TRUE;}
}
return $ret;
}

public static function isLoggedIn() {
$ret = FALSE;
if ( isset($_SESSION['customer']['id']) AND is_numeric($_SESSION['customer']['id']) ) { $ret = TRUE; }
return $ret;
}

/* bereitstellung der kundendaten in externer klasse, um nicht direkt auf session - werte zuzugreifen. 
* die können im user-model geändert werden: z.B. um die alte weinraum - Anmeldung durch die CI zu ersetzen. */

public static function getUser($_key = NULL) {
$ret = FALSE;
$user = isset($_SESSION['user'])?$_SESSION['user']:""; 
if ( $user != "" ) {
if ($_key) {$ret = $user[$_key];}
else {$ret = $user;}
}
return $ret;
}

/* die hash werte können mit «.» oder «/» beginnen oder enden. dann funktioniert die Übergabe über die url nicht.
 * 
 * Lösung: den hash um Regionen ergänzen, wenn ein . oder / gefunden wurde.
 * Vor der Ausgabe wieder rück - umformen und die Regionen gegen . oder / austauschen.
 * theoretisch ist das unsauber, weil der berechnete hash provence oder minervois am Ende haben könnten.
 * Die Worte dürften aber ausreichend lang sein um eine Überprüfung auf diese Zufälligkeit in der hin Version (substr($_hash, -8)=="provence"?...) zu lassen
 */
public static function geschiss_mit_elli($_hash = NULL) {
//$_hash ="roussillon&9siusdk%&/roussillon";echo("<br>ha elli $hash<br>");
$hash = substr($_hash, 0, 9)=="languedoc"?substr($_hash, 9):$_hash;
$hash = substr($hash, 0, 10)=="roussillon"?substr($hash, 10):$hash; 
$_length = strlen($hash);
$hash = substr($hash, -9)=="languedoc"?substr($hash, 0, $_length - 9):$hash;
$_length = strlen($hash);
$hash = substr($hash, -10)=="roussillon"?substr($hash, 0, $_length - 10):$hash; 
$hash = str_replace("Paulinshofberg", "//", $hash);
$hash = str_replace("Schlossberg", "/./", $hash);
return $hash;
}
public static function wie_ollawai($_hash = NULL) {
//$_hash =".dfjkg/";echo ("<br>hasholla $hash");
$hash = substr($_hash, -1)=="."?$_hash."languedoc":$_hash;
$hash = substr($_hash, -1)=="/"?$hash."roussillon":$hash; 
$hash = substr($_hash, 0, 1)=="."?"languedoc".$hash:$hash;
$hash = substr($_hash, 0, 1)=="/"?"roussillon".$hash:$hash; 
$hash = str_replace("/./", "Schlossberg", $hash);
$hash = str_replace("//", "Paulinshofberg", $hash);
return $hash;
}

public static function pr($_content, $_exit = FALSE) {
echo('<pre>');
print_r($_content);
echo('</pre>');
if ($_exit) { exit(); }
}

public static function isPost() {
$request = \Config\Services::request();
$ret = FALSE;
if ($request->getMethod(true) == 'POST') { $ret = TRUE;}
return $ret;
}

public static function getUrl($_url) {
$url = explode('/', $_url);
return base_url($url);
}

public static function forward($_url) {
header('Location: '.base_url($_url));
exit();
}

public static function showModalDelete() {
$headline = lang('german_lang.delete_headline');
$msg = lang('german_lang.delete_msg');
$delete = lang('german_lang.action_delete2');
$close = lang('german_lang.action_close');

$html =<<<EOT
<div class="mod-admin-delete " id="confirmModal">
<div class="mod-header">
<button type="button" class="close">&times;</button>
<h3>{$headline}</h3>
</div>
<div class="mod-body">
<p>{$msg}</p>
</div>
<div class="mod-footer">
<a href="#" class="btn btn-danger confirm">{$delete}</a>
<a href="#" class="btn schliessen" >{$close}</a>
</div>
</div>
EOT;
return $html;
}

public static function showModalLoad() {

$html =<<<EOT
<div class="mod-admin-load " id="confirmModal_Load">
<div class="mod-header">
<button type="button" class="close" >&times;</button>
<h3>VORSICHT! GEFAHR VON DATENVERLUST: Inhalt neu Laden</h3>
<p>Der Inhalt kann aus dem Verzeichnis receiver geladen, extern bearbeitet und in den «receiver» geschrieben werden. Hier werden diese neuen Daten geladen.<br><br>
Das ist etwas, wenn man <b><i>genau</i></b> weiß, was man macht! Und besonders: wenn man weiß, dass neue Daten im receiver stehen!
Wenn alte Daten oder falsche Daten im receiver stehen, werden mit dem Klick auf «laden» alle neuen Daten überschrieben!
</div>
<div class="mod-body">
<p>Soll wirklich der Inhalt neu geladen werden? Wenn im receiver alte Daten stehen, gehen alle neueren Eingaben verloren!!!<br>
Die Daten im receiver stehen unter dem Inhalt. Im Zweifel vergeichen!</p>
</div>
<div class="mod-footer">
<a href="#" class="btn btn-danger confirm">Laden</a>
<a href="#" class="btn schliessen" >Schliessen</a>
</div>
</div>
EOT;
return $html;
}

    
    

public static function getRowPaymSales($_bank, $_wr_customer, $_form = "") {
unset($teile);  
if ($_form == "" ) {
/* Abgleich Daten der Rechnung mit Überweisung. Hervorheben der Übereinstimmungen. Unterscheidet auch z.B. Doppelnamen.
 * 
*/   

$teile = explode($_wr_customer, strtolower($_bank)); 
$html = "<span class=' orange'>$teile[0]</span>
 <span class='daddel gruenSicht'>$_wr_customer</span>
 <span class='daddel orange'>$teile[1]</span>";
}
if ($_form != "" ) {
$teile = explode($_wr_customer, $_form);
$html = "$teile[0]</span>
 <span class='daddel gruenSicht'>$_wr_customer</span>
 <span class='daddel orange'>$teile[1]</span>";
}

return $html;
}
 
public static function getLinkfromBracket($_text, $_identifers) {
/* identifer model     $data[$row['controllerID']][] = $row[cat_identifer, controllerID, contID, identifer};
* Name : Grenache Gris
* identifer: grenache_gris
* => Suche Leerzeichen, erstze durch "_", =>Zeichen strtolower, Suche Umlaute, erstze durch ae, ue etc.
VORSICHT BEI TIPPFEHLERN [[reg | Pfalz]] => nur EIN | dann existiert kein $contIndent[1]  
*/

$find = array();
$replace = array();
$orgMiracle = array();
$replaceMiracle = array();
$txt = "";
preg_match_all('/\[{2}(.*?)\]{2}/', $_text, $matches); 

if ( count($matches[0]) > 0 ) {
foreach ($matches[0] as $k => $v ) {
$find = array("[[","]]");
$replace = array("", "");
$inner =  str_replace($find, $replace, $v);
// https://stackoverflow.com/questions/2592502/i-have-a-string-with-u00a0-and-i-need-to-replace-it-with-str-replace-fail
$inner = str_replace( chr( 194 ) . chr( 160 ), ' ', $inner );
//$contIndent = array();
//$key = NULL;
//$inner = "reg || Toskana";
$contIndent = explode(' || ', $inner); 
//if ( !isset($contIndent[1]) ) {$contIndent[0] = substr($inner, 0, 3);$contIndent[1] = substr($inner, 8); }
//print_r($contIndent);
// zeigt auch sonderzeichen: echo json_encode($contIndent);

if ( isset($contIndent[0]) AND isset($contIndent[1]) ) {
$_class = strtolower($contIndent[0]);
$search = array(" ");
$replace = array("");
$_class=  str_replace($search, $replace, $_class);
$search = array("á", "à", "é", "è", "ô", "ù", "ä", "ö", "ü", "ß", "´", " ");
$replace = array("a", "a", "e", "e", "o", "u", "ae", "oe", "ue", "ss", "", "-");

$_identiferFromOrg = strtolower($contIndent[1]);
$_identiferFromOrg=  str_replace($search, $replace, $_identiferFromOrg);

/* die weintypen hard codiert: es gibt nur diese weintypen, NIE änderungen  

 $data = array( 'RF' => 'Rot, fruchtig',
'RS' => 'Rot, seidig',
'RK' => 'Rot, kräftig',
'RT' => 'Rot, tanninreich',
'RO' => 'Rosé',
'WF' => 'Weiß,  jung & fruchtig',
'WV' => 'Weiß, volumenreich',
'WA' => 'Weß, Aromasorte',
'WE' => 'Weiß, edelsüß',
'S' => 'Sekt',
'SR' => 'Rosé Sekt',
'FR' => 'Frizzante',
'CH' => 'Champagner',
'CHR' => 'Champagner Rosé',
'CR' => 'Crémant',
'CRR' => 'Crémant Rosé',
  
 * 
 * hinweis zu str_replace: Weil str_replace() von links nach rechts ersetzt, kann ein zuvor eingesetzter Wert ersetzt werden, 
 * wenn mehrere Ersetzungen durchgeführt werden. Das letzte Beispiel zeigt, was dies für Auswirkungen hat. 
 */

if ( $_class == "typ" OR  $_class == "weintypen") { 
$_ivo = $_identiferFromOrg;
if ( $_ivo == "rf" ) { $_identiferFromOrg = "rotwein-fruchtig"; $contIndent[2] = isset($contIndent[2])?$contIndent[2]:"Rot, fruchtig"; }
if ( $_ivo == "rs" ) { $_identiferFromOrg = "rotwein-seidig";  $contIndent[2] = isset($contIndent[2])?$contIndent[2]:"Rot, seidig"; }
if ( $_ivo == "rk" ) { $_identiferFromOrg = "rotwein-kraeftig";  $contIndent[2] = isset($contIndent[2])?$contIndent[2]:"Rot, kräftig"; }
if ( $_ivo == "rt" ) { $_identiferFromOrg = "rotwein-tanninreich";  $contIndent[2] = isset($contIndent[2])?$contIndent[2]:"Rot, tanninreich"; }
if ( $_ivo == "wf" ) { $_identiferFromOrg = "weisswein_jung";  $contIndent[2] = isset($contIndent[2])?$contIndent[2]:"Weiß, jung & fruchtig"; }
if ( $_ivo == "wv" ) { $_identiferFromOrg = "weisswein_volumenreich";  $contIndent[2] = isset($contIndent[2])?$contIndent[2]:"Weiß, volumenreich"; }
if ( $_ivo == "wa" ) { $_identiferFromOrg = "weisswein_aromasorte";  $contIndent[2] = isset($contIndent[2])?$contIndent[2]:"Weiß, Aromensorte"; }
if ( $_ivo == "we" ) { $_identiferFromOrg = "weisswein_edelsuess";  $contIndent[2] = isset($contIndent[2])?$contIndent[2]:"Weiß, edelsüß"; }
if ( $_ivo == "s" ) { $_identiferFromOrg = "sekt";  $contIndent[2] = isset($contIndent[2])?$contIndent[2]:"Sekt"; }
if ( $_ivo == "sr" ) { $_identiferFromOrg = "rose-sekt";  $contIndent[2] = isset($contIndent[2])?$contIndent[2]:"Rosé Sekt"; }
if ( $_ivo == "fr" ) { $_identiferFromOrg = "frizzante";  $contIndent[2] = isset($contIndent[2])?$contIndent[2]:"Frizzante / Spumante"; }
if ( $_ivo == "ch" ) { $_identiferFromOrg = "champagner";  $contIndent[2] = isset($contIndent[2])?$contIndent[2]:"Champagner"; }
if ( $_ivo == "cr" ) { $_identiferFromOrg = "cremant";  $contIndent[2] = isset($contIndent[2])?$contIndent[2]:"Crémant"; }

}


if ( $_class == "reb" OR  $_class == "rebsorten") { $_class = "rebsorten"; }
if ( $_class == "reg" OR  $_class == "regionen") { $_class = "regionen"; }
if ( $_class == "pas" OR  $_class == "passend") { $_class = "wein_passend_zu"; }
if ( $_class == "lex" OR  $_class == "lexikon") { $_class = "lexikon"; }
if ( $_class == "win" OR  $_class == "winzer") { $_class = "winzer"; }
if ( $_class == "typ" OR  $_class == "weintypen") { $_class = "weintypen"; }
if ( $_class == "mag" OR  $_class == "magazin") { $_class = "magazin"; }
$orgMiracle[] = $v;
if ( isset($_identifers[$_class])) {$key = array_search($_identiferFromOrg, $_identifers[$_class]); }
else { $key = NULL; }

/* Bei Region und Winzer wurde das System auf wein umgestellt. Hier wurde der path zum identifer in die Table content2identifer eingefügt */
if ( isset($key) AND is_numeric($key)) {
if ( $_identifers['path'][$key] == "") {}
if ( $_class != "winzer" AND $_class != "regionen" AND 3 == 4) {

$replaceMiracle[] = "<a href='/".$_class."/".$_identiferFromOrg."' class='litext'>".(isset($contIndent[2])?$contIndent[2]:$contIndent[1])."</a>".PHP_EOL;
}
if ( ($_class == "winzer" OR $_class == "regionen")  AND 3 == 4) {
if ( $_identifers['identifer'][$key] != "") {
if ( $_class == "winzer" ) { $replaceMiracle[] = "<a href='/".$_class."/".$_identifers['identifer'][$key]."' class='litext'>".(isset($contIndent[2])?$contIndent[2]:$contIndent[1])."</a>".PHP_EOL; }
if ( $_class == "regionen") {$replaceMiracle[] = "<a href='/".$_class."/".$_identifers['identifer'][$key]."' class='litext'>".(isset($contIndent[2])?$contIndent[2]:$contIndent[1])."</a>".PHP_EOL; }

}
if ( $_identifers['path'][$key] == "") {
/* in weinraum keine Links mehr, da die auf traubenwerke verweisen
$replaceMiracle[] = "<a href='/".$_class."/".$_identiferFromOrg."' class='litext'>".(isset($contIndent[2])?$contIndent[2]:$contIndent[1])."</a>".PHP_EOL;
 */
}
}

$replaceMiracle[] = (isset($contIndent[2])?$contIndent[2]:$contIndent[1]).PHP_EOL;

}
else { 
if ( isset($_SESSION['customer']['id']) AND $_SESSION['customer']['id'] == 869 ) {$replaceMiracle[] = "<span class = 'no_matches'>".(isset($contIndent[2])?$contIndent[2]:$contIndent[1])."</span>";} 
else { $replaceMiracle[] = (isset($contIndent[2])?$contIndent[2]:$contIndent[1]); }
}
}
else { $replaceMiracle[] = "";}
$txt = str_replace($orgMiracle, $replaceMiracle, $_text);    
}
}

if ( count($matches[0]) == 0 ) { $txt = $_text;}

return $txt;
}   
    

    
public static function check_no_content_2_link($_text = NULL, $_identifers = NULL, $_contid = NULL, $_cont_identifer = NULL) { 
if ( $_contid !== NULL AND $_cont_identifer !== NULL) {
$Link_ohne_ziel_model = new Link_ohne_ziel_model();
$find = array();
$replace = array();
$orgMiracle = array();
$replaceMiracle = array();
$_chkDa = array();
$_chkSave = array();
$txt = "";
preg_match_all('/\[{2}(.*?)\]{2}/', $_text, $matches); 
if ( count($matches[0]) > 0 ) {
foreach ($matches[0] as $k => $v ) {
$find = array("[[","]]");
$replace = array("", "");
$inner =  str_replace($find, $replace, $v);
$inner = str_replace( chr( 194 ) . chr( 160 ), ' ', $inner );
$contIndent = explode(' || ', $inner); 
if ( isset($contIndent[0]) AND isset($contIndent[1]) ) {
$_class = strtolower($contIndent[0]);
$search = array(" ");
$replace = array("");
$_class=  str_replace($search, $replace, $_class);

$search = array("á", "à", "é", "è", "ô", "ù", "ä", "ö", "ü", "ß", "´", " ");
$replace = array("a", "a", "e", "e", "o", "u", "ae", "oe", "ue", "ss", "", "-");

$_identiferFromOrg = strtolower($contIndent[1]);
$_identiferFromOrg=  str_replace($search, $replace, $_identiferFromOrg);

if ( $_class == "typ" OR  $_class == "weintypen") { 
$_ivo = $_identiferFromOrg;
if ( $_ivo == "rf" ) { $_identiferFromOrg = "rotwein-fruchtig"; $contIndent[2] = isset($contIndent[2])?$contIndent[2]:"Rot, fruchtig"; }
if ( $_ivo == "rs" ) { $_identiferFromOrg = "rotwein-seidig";  $contIndent[2] = isset($contIndent[2])?$contIndent[2]:"Rot, seidig"; }
if ( $_ivo == "rk" ) { $_identiferFromOrg = "rotwein-kraeftig";  $contIndent[2] = isset($contIndent[2])?$contIndent[2]:"Rot, kräftig"; }
if ( $_ivo == "rt" ) { $_identiferFromOrg = "rotwein-tanninreich";  $contIndent[2] = isset($contIndent[2])?$contIndent[2]:"Rot, tanninreich"; }
if ( $_ivo == "wf" ) { $_identiferFromOrg = "weisswein_jung";  $contIndent[2] = isset($contIndent[2])?$contIndent[2]:"Weiß, jung & fruchtig"; }
if ( $_ivo == "wv" ) { $_identiferFromOrg = "weisswein_volumenreich";  $contIndent[2] = isset($contIndent[2])?$contIndent[2]:"Weiß, volumenreich"; }
if ( $_ivo == "wa" ) { $_identiferFromOrg = "weisswein_aromasorte";  $contIndent[2] = isset($contIndent[2])?$contIndent[2]:"Weiß, Aromensorte"; }
if ( $_ivo == "we" ) { $_identiferFromOrg = "weisswein_edelsuess";  $contIndent[2] = isset($contIndent[2])?$contIndent[2]:"Weiß, edelsüß"; }
if ( $_ivo == "s" ) { $_identiferFromOrg = "sekt";  $contIndent[2] = isset($contIndent[2])?$contIndent[2]:"Sekt"; }
if ( $_ivo == "sr" ) { $_identiferFromOrg = "rose-sekt";  $contIndent[2] = isset($contIndent[2])?$contIndent[2]:"Rosé Sekt"; }
if ( $_ivo == "fr" ) { $_identiferFromOrg = "frizzante";  $contIndent[2] = isset($contIndent[2])?$contIndent[2]:"Frizzante / Spumante"; }
if ( $_ivo == "ch" ) { $_identiferFromOrg = "champagner";  $contIndent[2] = isset($contIndent[2])?$contIndent[2]:"Champagner"; }
if ( $_ivo == "cr" ) { $_identiferFromOrg = "cremant";  $contIndent[2] = isset($contIndent[2])?$contIndent[2]:"Crémant"; }

}


if ( $_class == "reb" OR  $_class == "rebsorten") { $_class = "rebsorten"; }
if ( $_class == "reg" OR  $_class == "regionen") { $_class = "regionen"; }
if ( $_class == "pas" OR  $_class == "passend") { $_class = "wein_passend_zu"; }
if ( $_class == "lex" OR  $_class == "lexikon") { $_class = "lexikon"; }
if ( $_class == "win" OR  $_class == "winzer") { $_class = "winzer"; }
if ( $_class == "typ" OR  $_class == "weintypen") { $_class = "weintypen"; }
if ( $_class == "mag" OR  $_class == "magazin") { $_class = "magazin"; }

$orgMiracle[] = $v;
if ( isset($_identifers[$_class])) {$key = array_search($_identiferFromOrg, $_identifers[$_class]); }
if ( !isset($key) ) {
/* Die Datensätze in «Link_ohne_Ziel» für diese content_id werden im cron_content controller vor der slices Schleife gelöscht, falls 
links im content entfernt wurden / artikel geändert. Hier immer neu und aktuell anlegen.*/
$_chkDa['cont_id'] = $_contid;
$_chkDa['cont_identifer'] = $_cont_identifer;
$_chkDa['link'] = $contIndent[1];
$Link_ohne_ziel_model->save_CI4($_chkDa);
}
}
}
}
}     
}
  
public static function getMailPersonalized($_text, $_identifers) {
$find = array();
$replace = array();
$orgMiracle = array();
$replaceMiracle = array();

preg_match_all('/\[{2}(.*?)\]{2}/', $_text, $matches); 
if (is_array($matches) ) {
foreach ($matches[0] as $k => $v ) {
$find = array("[[","]]");
$replace = array("", "");
$inner =  str_replace($find, $replace, $v);
$orgMiracle[] = $v;
$key = array_search($inner, $_identifers); 

if ( isset($_identifers[$inner])) { $replaceMiracle[] = $_identifers[$inner]; }
}
$txt = str_replace($orgMiracle, $replaceMiracle, $_text);    
}
if ( !is_array($matches) ) { $txt = $_text; }
return $txt;
}   
   


}
