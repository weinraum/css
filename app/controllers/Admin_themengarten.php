<?php namespace App\Controllers;

use App\Models\Category_model;
use App\Models\Content_model;
use App\Models\Content_slice_model;
use App\Models\Content_slice_data_model;
use App\Models\Content2mail_model;
use App\Models\Content2category_model;
use App\Models\Content2identifer_model;
use App\Models\Content2prodtype_model;
use App\Models\Cron_status_model;

use App\Models\GpPreparePers_model;
use App\Models\Gp_2prod_ids_model;
use App\Models\Gp_reste_model;
use App\Models\Grape_model;
use App\Models\Mail_emotion_model;

use App\Models\Pol1_model;
use App\Models\Pol2_model;
use App\Models\Pol3_model;

use App\Models\Producer_model;
use App\Models\Wine_model;
use CodeIgniter\Events\Events;

use \Datetime;

class Admin_themengarten extends Aontroller {
    	
public function __construct() {
parent::__construct();

$App = new \App\Libraries\App(); 
$request = \Config\Services::request();
$this->data['auth'] = TRUE;
$this->data['admin'] = TRUE; 

if ( !$App::isAdmin() ) { $App::forward("/weinraum/anmelden"); }

}

public function index() {
$Content_model = new Content_model();
$data = array();
$this->data['auth'] = TRUE;
$this->data['navigation'] = 'admin'; 
$this->data['sub_navi'] = "admin_tg";
if ( isset($_SESSION['customer']['id']) AND $_SESSION['customer']['id'] == 869) {$data['data'] = $Content_model->just_get(NULL, array('id >=' => 1));}

//if ( isset($_SESSION['customer']['id']) AND $_SESSION['customer']['id'] != 869) { $data['data'] = $Content_model->get(array('id >=' => 1, 'stat_correct ' => 0),array('date' => 'DESC'));}
$this->data['leftbar'] = "no";
$this->data['class'] = "admin";
$this->data['leftbar'] = "no";
$this->data['simuliere_leftbar_wein'] = "no";
$this->data['nav_cart'] = 'yes';
$this->data['nav_weinStyles'] = 'yes';
$this->data['show_nav_wine_menu'] = 'yes';
$this->data['show_wineStyles_menu'] = 'yes';
$this->data['nav_cart'] = 'yes';
$this->data['nav_weinStyles'] = 'yes';
$this->data['show_xs_menue_konto'] = 'yes';
$this->data['show_desk_menue_konto'] = 'yes';
$this->data['show_nav_ordering'] = 'no';
$this->data['show_xs_nav_ordering'] = 'no';
$this->data['nav_bottom']  = "line";
$this->render('admin/themengarten/index', $data);
}

public function add() {
$App = new \App\Libraries\App(); 
$this->data['leftbar'] = "no";
$this->data['navigation'] = 'admin'; 
$this->data['class'] = "admin";
$_POST['user_id'] = $App::getUser('id');
if ($App::isPost()) {$this->form(self::UPDATE);}
if (!$App::isPost()) {$this->form(self::ADD);}

}

public function update() {
$Content_slice_model = new Content_slice_model();
$Content_slice_data_model = new Content_slice_data_model();

$App = new \App\Libraries\App(); 
$request = \Config\Services::request();
$this->data['auth'] = TRUE;
$this->data['admin'] = TRUE; 
$this->data['leftbar'] = "no";
$this->data['navigation'] = 'admin'; 
$this->data['sub_navi'] = "admin_tg";
$this->data['class'] = "admin";
$this->data['nav_cart'] = 'yes';
$this->data['nav_weinStyles'] = 'yes';
$this->data['show_nav_wine_menu'] = 'yes';
$this->data['show_wineStyles_menu'] = 'yes';

$this->data['nav_cart'] = 'yes';
$this->data['nav_weinStyles'] = 'yes';
$this->data['show_xs_menue_konto'] = 'yes';
$this->data['show_desk_menue_konto'] = 'yes';
$this->data['show_nav_ordering'] = 'no';
$this->data['show_xs_nav_ordering'] = 'no';
$this->data['nav_bottom']  = "line";
$this->data['uri_segments'][5] = isset($this->data['uri_segments'][5])?$this->data['uri_segments'][5]:"";

$params = array();
$params['up'] = (isset($this->data['uri_segments'][4]) AND $this->data['uri_segments'][4]=="up")?$this->data['uri_segments'][5]:"";
$params['down'] = (isset($this->data['uri_segments'][4]) AND $this->data['uri_segments'][4]=="down")?$this->data['uri_segments'][5]:"";
$params['content'] = (isset($this->data['uri_segments'][4]) AND $this->data['uri_segments'][4]=="content")?$this->data['uri_segments'][5]:"";
if ( isset($this->data['uri_segments'][3] ) AND $this->data['uri_segments'][3] == "allocate" ) {
$this->form(self::ALLOCATE);
$hideForm = TRUE;
} 

// isset - abfragen vermeiden
if ( !isset($this->data['uri_segments'][4] ) ) { $this->data['uri_segments'][4] = ""; }
if ( !isset($this->data['uri_segments'][5] ) ) { $this->data['uri_segments'][5] = ""; }
switch($this->data['uri_segments'][4]) { 
case 'up';    
$Content_slice_model->up_cs($this->data['uri_segments'][5]);
$App::forward('/'.$this->data['uri_segments'][1].'/'.$this->data['uri_segments'][2].'/'.$this->data['uri_segments'][3]);
break;
case 'down';
$Content_slice_model->down_cs($this->data['uri_segments'][5]);
$App::forward('/'.$this->data['uri_segments'][1].'/'.$this->data['uri_segments'][2].'/'.$this->data['uri_segments'][3]);
break;
case 'content';

/* mehrspaltigen beiträgen z.B. Tabellen ist im Detail - bearbeiten ebenfalls up/down/delete verfügbar.     
 * dafür das folgende switch.
 * 
 * Das forward dürfte nicht so laufen, da immer wieder die gleiche Seite aufgerufen wird. Die Seite so aufrufen, dass der admin content angezeigt wird 
 * und die zu vielen $this->data['uri_segments'][4] löschen
 */

switch ($this->data['uri_segments'][5]) { 
case 'up': 
$Content_slice_data_model->upContSlice($params['up'], $params['content']);
$App::forward('/'.$this->data['uri_segments'][1].'/'.$this->data['uri_segments'][2].'/'.$this->data['uri_segments'][3].'/'.$this->data['uri_segments'][4].'/'.$this->data['uri_segments'][5].'/'.$this->data['uri_segments'][6]);
break;
case 'down': echo("down");exit();
$Content_slice_data_model->downContSlice($params['down'], $params['content']);
$App::forward('/'.$this->data['uri_segments'][1].'/'.$this->data['uri_segments'][2].'/'.$this->data['uri_segments'][3].'/'.$this->data['uri_segments'][4].'/'.$this->data['uri_segments'][5].'/'.$this->data['uri_segments'][6]);
break;
case 'delete':
$Content_slice_data_model->deleteContSlice($params['delete'], $params['content']);
$App::forward('/'.$this->data['uri_segments'][1].'/'.$this->data['uri_segments'][2].'/'.$this->data['uri_segments'][3].'/'.$this->data['uri_segments'][4]);
break;
default: 
/* die type wird im leeren formular als get übergeben. wenn der input ausgefüllt wird, kommt die type variable als post mit dem formular
wird hier genutzt, um die Rechtschreibprüfung zu nutzen*/
$_type = "";
if ( $request->getGet('type') !== NULL ) $_type = $request->getGet('type');
if ( $request->getPOST('_type') !== NULL ) $_type = $request->getPOST('_type');
if ( $_type != "") $this->content($this->data['uri_segments'][3], $this->data['uri_segments'][5], $_type);
if ( $_type == "") { 
$this->content($this->data['uri_segments'][3], $this->data['uri_segments'][5], NULL);
//echo("zeile 146 kein type"); 
/* Wenn eine leere Eingabe - Seite aufgerufen wird, ist der type leer, es gibt $this->data['uri_segments'][5] => man landet hier.
 * Hier soll das language tool arbeiten und daher der CSP header nicht gesendet werden.
 * Das erreicht man, indem CI durch exit() abgebrochen wird. Wenn das HIER passiert, ist alles schon ausgegeben, insbesondere die 
 * input files und mit exit() kann das language tool arbeiten.
 */

exit(); 

}
$hideForm = TRUE;
}
break;
case 'mail';
$this->content_mail($this->data['uri_segments'][3], $this->data['uri_segments'][5], isset($this->data['uri_segments'][6])?$this->data['uri_segments'][6]:"");
$hideForm = TRUE;
break;
case 'delete';
if ( isset($this->data['uri_segments'][5]) AND is_numeric($this->data['uri_segments'][5])) {    
if ($Content_slice_model->deleteSlice($this->data['uri_segments'][5], NULL) ) {
$path = FCPATH.'_data/'.$this->data['uri_segments'][3].'/'.$this->data['uri_segments'][5];
helper('filesystem');
delete_files($path, TRUE);
if (is_dir($path)) { rmdir($path); }
}
}
else {}
$App::forward('/'.$this->data['uri_segments'][1].'/'.$this->data['uri_segments'][2].'/'.$this->data['uri_segments'][3]);
break;
}
if (!isset($hideForm) OR !$hideForm ) { $this->form(self::UPDATE); }
}

public function form( $_type )  { 
$Content_model = new Content_model();
$Category_model = new Category_model();
$Content2category_model = new Content2category_model();
$Content2mail_model = new Content2mail_model();
$Content2prodtype_model = new Content2prodtype_model();
$Content2identifer_model = new Content2identifer_model();
$Cron_status_model = new Cron_status_model();
$Grape_model = new Grape_model();
$Pol1_model = new Pol1_model();
$Pol2_model = new Pol2_model();
$Pol3_model = new Pol3_model();
$Producer_model = new Producer_model();
$Wine_model = new Wine_model();
//$start = microtime(true);
$this->data['auth'] = TRUE;
$this->data['admin'] = TRUE; 
$this->data['linkIdentifers'] = $Content2identifer_model->getIdentifers();
$App = new \App\Libraries\App(); 
$validation =  \Config\Services::validation();
$request = \Config\Services::request();
$this->data['leftbar'] = "no";
$this->data['class'] = "admin";
$this->data['nav_cart'] = 'yes';
$this->data['nav_weinStyles'] = 'yes';
$this->data['show_nav_wine_menu'] = 'yes';
$this->data['show_wineStyles_menu'] = 'yes';
$this->data['nav_cart'] = 'yes';
$this->data['nav_weinStyles'] = 'yes';
$this->data['show_xs_menue_konto'] = 'yes';
$this->data['show_desk_menue_konto'] = 'yes';
$this->data['show_nav_ordering'] = 'no';
$this->data['show_xs_nav_ordering'] = 'no';
$this->data['nav_bottom']  = "line";
$data = array();

$data['type'] = $_type;
$data['id'] = 0;
if ( $data['type'] != "allocate") { $data['id'] = isset($this->data['uri_segments'][3])?$this->data['uri_segments'][3]:""; }
if ( $data['type'] == "allocate") { $data['id'] = isset($this->data['uri_segments'][4])?$this->data['uri_segments'][4]:""; }
$data['backUrl'] = $App::getUrl('/admin_themengarten'); 

// $data['id'] ist vorhanden wenn content bearbeitet wird. bei den conten slice daten ist der Wert null und dieser block wird übersprungen
if ($App::isPost() ) {
if ( $data['type'] != "allocate") {
$var = 'date';
$validation->setRule($var, 'lang:themengarten_field_'.$var, 'required');
$var = 'title';
$validation->setRule($var, 'lang:themengarten_field_'.$var, 'required');
if ($validation->withRequest($request)->run() ) {
$_POST['date'] = $App::getSqlDate($request->getPost('date'));
$_POST['date_mod'] = date('Y-m-d G:i:s');
// get old data
//$oldData = $Content_model->get($data['id']); 
// wenn files geladen werden -> speichern

if (isset($_FILES['image']['name']) ) {if ($_FILES['image']['name'] != '') { $_POST['image'] = $_FILES['image']['name']; }}
if (isset($_FILES['imageBreitFlach']['name']) ) {if ($_FILES['imageBreitFlach']['name'] != '') { $_POST['imageBreitFlach'] = $_FILES['imageBreitFlach']['name']; }}
if (isset($_FILES['image_2quer']['name']) ) {if ($_FILES['image_2quer']['name'] != '') { $_POST['image_2quer'] = $_FILES['image_2quer']['name']; }}
if (isset($_FILES['imageIndex']['name']) ) { if ($_FILES['imageIndex']['name'] != '') { $_POST['imageIndex'] = $_FILES['imageIndex']['name']; }}
if (isset($_FILES['imageQuad']['name']) ) { if ($_FILES['imageQuad']['name'] != '') { $_POST['imageQuad'] = $_FILES['imageQuad']['name']; } }
// wenn Bilder gelöscht werden sollen-> auch die alten DB Werte im Form löschen
if ($request->getPost('_actualimage') != "" AND $request->getPost('_deleteimage') == '1' ) { $_POST['image'] = ""; }
if ($request->getPost('_actualimage_2quer') != "" AND $request->getPost('_deleteimage_2quer') == '1' ) { $_POST['image_2quer'] = ""; }
if ($request->getPost('_actualimageBreitFlach') != "" AND $request->getPost('_deleteimageBreitFlach') == '1' ) { $_POST['imageBreitFlach'] = ""; }
if ($request->getPost('_actualimageIndex') != "" AND $request->getPost('_deleteimageIndex') == '1' ) { $_POST['imageIndex'] = ""; }
if ($request->getPost('_actualimageQuad') != "" AND $request->getPost('_deleteimageQuad') == '1' ) { $_POST['imageQuad'] = ""; }

//print_r($_POST);
if (trim($request->getPost('identifer')) == '') { $_POST['identifer'] = url_title($request->getPost('title')); }
$_POST['identifer'] = strtolower($_POST['identifer']);
//$_POST['title'] = $request->getPost('title');
/*  die post variablem OHNE unterstrich sind elemente der content tabelle, die MIT aus anderen tabellen. die werden im aufruf eignener controller verwandt */
//$_POST = $request->getPost(NULL); 

if ( !isset($_POST['onstart']) ) { $_POST['onstart'] = 0; }
if ( !isset($_POST['status']) ) { $_POST['status'] = 0; }
if ( !isset($_POST['is_zeitung']) ) { $_POST['is_zeitung'] = -1; }
if ( !isset($_POST['is_provence']) ) { $_POST['is_provence'] = -1; }
if ( !isset($_POST['is_traubenwerke']) ) { $_POST['is_traubenwerke'] = -1; }
$_is_traubenwerke = $_POST['is_traubenwerke'];

if ( !isset($_POST['date_cont_cron']) ) { $_POST['date_cont_cron'] = date('Y-m-d G:i:s'); }

// CI4 gibt die ID zurück oder ein FALSE

if ( isset($_POST['id']) AND $_POST['id'] !== NULL ) {
$_path = VENTOUX.'receiver/'.$_POST['id'].'/';
if ( file_exists($_path.'/receiver.txt') ) {
$_POST['_receiver'] = file_get_contents($_path.'/receiver.txt', true);
}
}

unset($_POST['CSRFToken']);
$contentId = $Content_model->save_CI4($_POST, $_POST['id']);
if ( is_numeric($contentId) ) {
if ( is_array($request->getPost('_categories') ) ) { 
$cat = $request->getPost('_categories');
$identContr = $Category_model->getOldestParentIdentifer($cat[0]);
$_path = "";
if (isset($identContr) AND is_array($identContr) AND count($identContr) > 0 ) {
foreach ($identContr as $k => $v ) { $_path .= "/".$v;  }
$controllerCont = $Category_model->get(array("identifer" => $identContr[0])); 
/*  getOldestParentIdentifer ermittelt die erste cat, zu der Artikel gehört. Anders ausgedrückt: parent_id = NULL. Also entweder  Winzer, Regionen, Rebsorten, Lexikon etc
 * von dieser ersten Cat wird der identifer in content2identifer gespeichert. Im Text steht [[reg || Rhone]]. Die Artikel werden in der Library APP zugewiesen und als Link ausgegeben.
 * Die Regionen Seiten wurden 2023 geändert und als canonische Seite immer die wein/land/region Seite ausgegeben.
 * DER NUTZER muss als identifer des Artikels den GLEICHEN der pol1, pol2, pol3 wählen, sonst werden die Werte nicht richtig zugewiesen.
 * dann werden zwar die Weine des z.B. Minervois ausgegeben. Der Artikel unter den Weinen aber nur, wenn der auch «Minervois» als identifer hat wie die pol3
 */
// Winzer / Regionen
if ( isset($controllerCont[0]['id']) AND ($controllerCont[0]['id'] == 87 OR $controllerCont[0]['id'] == 174) ) {
$this->data['canonicalPage'] = NULL;
$_chkLand = $Pol1_model->get(array('related-cont-ID' => $data['id']));
if ( isset($_chkLand[0]['name_pol1_url']) ) { $_path = "/wein/".$_chkLand[0]['name_pol1_url']; }

$_chkRegion = $Pol2_model->get(array('related-cont-ID' => $data['id']));
if ( isset($_chkRegion[0]['name_pol2_url'])) {
$_chkLandReg = $Pol1_model->get(array('ID' => $_chkRegion[0]['pol1_ID']));
if ( isset($_chkLandReg[0]['name_pol1_url'])) {
$_path = "/wein/".$_chkLandReg[0]['name_pol1_url']."/".$_chkRegion[0]['name_pol2_url'];
}
}

$_chkAppellation = $Pol3_model->get(array('related-cont-ID' => $data['id']));
if ( isset($_chkAppellation[0]['name_pol3_url'])) {
$_chkLandApp = $Pol1_model->get(array('ID' => $_chkAppellation[0]['pol1_ID']));
$_chkRegApp = $Pol2_model->get(array('ID' => $_chkAppellation[0]['pol2_ID']));
if ( isset($_chkLandApp[0]['name_pol1_url']) AND isset($_chkRegApp[0]['name_pol2_url'])) {
$_path = "/wein/".$_chkLandApp[0]['name_pol1_url']."/".$_chkRegApp[0]['name_pol2_url']."/".$_chkAppellation[0]['name_pol3_url'];
}
}
//echo("data {$data['id']}");
$_chkProducer = $Producer_model->get(array('cont2prod' => $data['id']));
if ( isset($_chkProducer[0]['identifer_prod'])) {
$_chkLandprod = $Pol1_model->get(array('ID' => $_chkProducer[0]['pol1_ID']));
$_chkRegProd = $Pol2_model->get(array('ID' => $_chkProducer[0]['pol2_ID']));
$_chkAppProd = $Pol3_model->get(array('ID' => $_chkProducer[0]['pol3_ID']));
if ( isset($_chkLandprod[0]['name_pol1_url']) AND isset($_chkRegProd[0]['name_pol2_url'])) {
if ( isset($_chkAppProd[0]['name_pol3_url']) AND $_chkAppProd[0]['name_pol3_url'] != "" ) { //echo("haaau {$_chkRegProd[0]['name_pol2_url']} - {$_chkAppProd[0]['name_pol3_url']}");
$_path = "/wein/".$_chkLandprod[0]['name_pol1_url']."/".$_chkRegProd[0]['name_pol2_url']."/".$_chkAppProd[0]['name_pol3_url']."/".$_chkProducer[0]['identifer_prod'];
}
else {
$_path = "/wein/".$_chkLandprod[0]['name_pol1_url']."/".$_chkRegProd[0]['name_pol2_url']."/".$_chkProducer[0]['identifer_prod'];
}
}
}
}

if ( is_numeric($request->getPost('id')) AND $request->getPost('id') > 0  ) { $Content2identifer_model->saveIdentifer($controllerCont[0]['id'], $request->getPost('id'), $request->getPost('identifer'), $_path) ;}
else {$Content2identifer_model->saveIdentifer($controllerCont[0]['id'], $contentId, $request->getPost('identifer'), $_path) ;}
}
}    
/*--------- *  save categories HIER WERDEN JETZT KEINE ÄNDERUNGEN GESPEICHERT! */
$Content2category_model->saveCategories($request->getPost('_categories'), $contentId);
if (is_numeric($request->getPost('cont4cat'))) { 
$cat_teaser = $Category_model->get($request->getPost('cont4cat'));
if ($cat_teaser[0]['producer_id'] > 0) {$Content2category_model->saveCont2POL($contentId, $cat_teaser[0]['producer_id'], 'prod');}
if ($cat_teaser[0]['pol3_id'] > 0 AND $cat_teaser[0]['producer_id'] == 0 ) {$Content2category_model->saveCont2POL($contentId, $cat_teaser[0]['pol3_id'], 3); }
if ($cat_teaser[0]['pol2_id'] > 0 AND $cat_teaser[0]['pol3_id'] == 0 AND $cat_teaser[0]['producer_id'] == 0) {$Content2category_model->saveCont2POL($contentId, $cat_teaser[0]['pol2_id'], 2);}
if ($cat_teaser[0]['pol1_id'] > 0 AND $cat_teaser[0]['pol2_id'] == 0 AND $cat_teaser[0]['pol3_id'] == 0 AND $cat_teaser[0]['producer_id'] == 0) {$Content2category_model->saveCont2POL($contentId, $cat_teaser[0]['pol1_id'], 1);}
}
else { $Content2category_model->saveCont2POL($contentId, NULL, NULL);  }

/*------------------ *  save Trauben */
if (is_numeric($request->getPost('_cont4grape'))) { 
$grapeact = $Grape_model->get(array('cont_id' => $contentId) );
if ( isset($contact[0]['cont_id']) AND $contact[0]['cont_id'] == $contentId ) {
$contactold = $Grape_model->select(array('cont_id' => $contentId) );
$Grape_model->save(array('cont_id' => ""), $contactold[0]['ID'] );   
}
if ( !isset($contact[0]['cont_id']) OR (isset($contact[0]['cont_id']) AND $contact[0]['cont_id'] != $contentId) ) { $Grape_model->save_CI4(array('cont_id' => $contentId), $request->getPost('_cont4grape') );   }
}


// ⬇️ NACH deinen save-Aufrufen — am besten direkt nach dem Block, der $cat_teaser lädt und saveCont2POL ausführt:
try {
    $p1 = $p2 = $p3 = null;
    $producerId = null;

    // Wenn cont4cat gesetzt ist, können wir pol/producer gezielt aus der Kategorie lesen
    if (is_numeric($request->getPost('cont4cat'))) {
        $cat_teaser = $Category_model->get($request->getPost('cont4cat'));
        if (!empty($cat_teaser[0])) {
            $producerId = !empty($cat_teaser[0]['producer_id']) ? (int)$cat_teaser[0]['producer_id'] : null;
            $p1 = !empty($cat_teaser[0]['pol1_id']) ? (int)$cat_teaser[0]['pol1_id'] : null;
            $p2 = !empty($cat_teaser[0]['pol2_id']) ? (int)$cat_teaser[0]['pol2_id'] : null;
            $p3 = !empty($cat_teaser[0]['pol3_id']) ? (int)$cat_teaser[0]['pol3_id'] : null;
        }
    }

    // Falls Content einem Producer zugeordnet ist → Producer-abhängige Regionen neu zählen
    if ($producerId) {
        Events::trigger('producer:changed', $producerId);
    }

    // Regionen/Rebsorten-Menüs neu (gezielt, wenn p1/p2/p3 vorhanden; sonst konservativ)
    Events::trigger('content:menuChanged', $p1, $p2, $p3);
} catch (\Throwable $e) {
    log_message('error', 'Menu invalidation failed in Admin_themengarten::form: '.$e->getMessage());
}

 
$_path = FCPATH.'_data/'.$contentId.'/output';
// Bilder gelöschen wenn neue geladen werden

if ( $request->getPost('_actualimage') !== NULL AND isset($_POST['image']) AND $request->getPost('_actualimage') != $_POST['image']  ) { if ( file_exists($_path.'/'.$request->getPost('_actualimage')) ) { unlink($_path.'/'.$request->getPost('_actualimage'));} }
if ($request->getPost('_actualimage_2quer') !== NULL AND isset($_POST['image_2quer']) AND  $request->getPost('_actualimage_2quer') != $_POST['image_2quer']  ) { if ( file_exists($_path.'/'.$request->getPost('_actualimage_2quer')) ) { unlink($_path.'/'.$request->getPost('_actualimage_2quer')); } }

if ($request->getPost('_actualimageIndex') !== NULL AND isset($_POST['imageIndex']) AND $request->getPost('_actualimageIndex') != $_POST['imageIndex']  ) { 
if ( file_exists($_path.'/'.$request->getPost('_actualimageIndex')) ) { unlink($_path.'/'.$request->getPost('_actualimageIndex')); } 
if ( file_exists($_path.'/md_'.$request->getPost('_actualimageIndex')) ) { unlink($_path.'/md_'.$request->getPost('_actualimageIndex')); } 
if ( file_exists($_path.'/xs_'.$request->getPost('_actualimageIndex')) ) { unlink($_path.'/xs_'.$request->getPost('_actualimageIndex')); } 
if ( file_exists($_path.'/xss_'.$request->getPost('_actualimageIndex')) ) { unlink($_path.'/xss_'.$request->getPost('_actualimageIndex')); } 

}
if ($request->getPost('_actualimageQuad') !== NULL AND isset($_POST['imageQuad']) AND  $request->getPost('_actualimageQuad') != $_POST['imageQuad'] ) { 
if ( file_exists($_path.'/'.$request->getPost('_actualimageQuad')) ) { unlink($_path.'/'.$request->getPost('_actualimageQuad')); } 
}

if ($request->getPost('_actualimageBreitFlach') !== NULL AND isset($_POST['imageBreitFlach']) AND  $request->getPost('_actualimageBreitFlach') != $_POST['imageBreitFlach']  ) { 
if ( file_exists($_path.'/'.$request->getPost('_actualimageBreitFlach')) ) { unlink($_path.'/'.$request->getPost('_actualimageBreitFlach')); }
if ( file_exists($_path.'/lg_'.$request->getPost('_actualimageBreitFlach')) ) { unlink($_path.'/lg_'.$request->getPost('_actualimageBreitFlach')); } 
if ( file_exists($_path.'/md_'.$request->getPost('_actualimageBreitFlach')) ) { unlink($_path.'/md_'.$request->getPost('_actualimageBreitFlach')); }
if ( file_exists($_path.'/xs_'.$request->getPost('_actualimageBreitFlach')) ) { unlink($_path.'/xs_'.$request->getPost('_actualimageBreitFlach')); }
if ( file_exists($_path.'/xss_'.$request->getPost('_actualimageBreitFlach')) ) { unlink($_path.'/xss_'.$request->getPost('_actualimageBreitFlach')); }
}

/*------------------ *  save Bilder */
if (isset($_FILES['image']['name']) OR $request->getPost('_deleteimage') !== NULL ) {$this->chk_file_content( $_FILES['image'] , 'image', $contentId );}  
if (isset($_FILES['image_2quer']['name']) OR $request->getPost('_deleteimage_2quer') !== NULL ) {$this->chk_file_content( $_FILES['image_2quer'] , 'image_2quer', $contentId );}  
if (isset($_FILES['imageQuad']['name']) OR $request->getPost('_deleteimageQuad') !== NULL ) {$this->chk_file_content( $_FILES['imageQuad'] , 'imageQuad', $contentId );}  
if (isset($_FILES['imageIndex']['name']) OR $request->getPost('_deleteimageIndex') !== NULL ) {$this->chk_file_content( $_FILES['imageIndex'] , 'imageIndex', $contentId );}  
if (isset($_FILES['imageBreitFlach']['name']) OR $request->getPost('_deleteimageBreitFlach') !== NULL ) {$this->chk_file_content( $_FILES['imageBreitFlach'] , 'imageBreitFlach', $contentId );}  
$chk_cat = $Category_model->get(array('status >=' => 0));
foreach($chk_cat as $k => $v) {
$chk_co2ca = $Category_model->getCategoryActiveContentIds($v['id']);
$n = count($chk_co2ca);
if ($n > 0 ) {$Category_model->save_CI4(array('status' => 1), $v['id']);}
if ($n == 0 ) {$Category_model->save_CI4(array('status' => 0), $v['id']);}
}
}
else {  }
}
}


if ( $data['type'] == "allocate") { $Content2prodtype_model->saveAlloc( $data['id'], $request->getPost('inp_data_alloc') ); }
/* * speichern des content identifiers der produzenten im file der produzenten, damit der link zum Winzer - Content ohne zusätzliche Abfrage der
 * identifers aus dem content - Datensatz möglich ist. */
$prod_data = $Producer_model->get(array('cont2prod !=' => ""));
foreach($prod_data as $k => $v ) {
$content_data = $Content_model->get(array('id' => $v['cont2prod']));
$upd_cont['cont2prod_identifer'] =  $content_data[0]['identifer'];
unset($upd_cont['is_traubenwerke']);

if ( isset($_is_traubenwerke) AND $_is_traubenwerke == 1 AND isset($_chkProducer[0]['ID']) AND $_chkProducer[0]['ID'] == $v['ID']){ 
/* Die Weine von diesem producer auf traubenwerke setzen */
$upd_cont['is_traubenwerke'] = 1;

}

$Producer_model->save_CI4($upd_cont, $v['ID']); 
}
//public function get($_where = NULL, $_order = NULL, $_status = NULL, $_limit = NULL, $ids = NULL, $_bead = NULL, $_slices = NULL) {




// --- Cache direkt aktualisieren (Minimal-Variante, ohne Events) ---
try {
    $cache = service('cacheContent');

    // Content-spezifische Rebuilds (falls vorhanden)
    if (method_exists($cache, 'rebuildOnContentSave')) {
        $cid = isset($contentId) ? (int)$contentId : (isset($_POST['id']) ? (int)$_POST['id'] : 0);
        if ($cid > 0) {
            $cache->rebuildOnContentSave($cid);
        }
    }

    // Menüs: gezielt nach Categories, sonst komplett
    $cats = [];
    if (!empty($_POST['_categories']) && is_array($_POST['_categories'])) {
        foreach ($_POST['_categories'] as $c) { if (is_numeric($c)) $cats[] = (int)$c; }
    }
    if (!empty($cats) && method_exists($cache, 'rebuildMenusByCategories')) {
        $cache->rebuildMenusByCategories($cats);
    } else {
        // Fallback: alles neu
        if (method_exists($cache, 'rebuildMenusFull')) {
            $cache->rebuildMenusFull();
        } elseif (method_exists($cache, 'rebuildAll')) {
            $cache->rebuildAll();
        }
    }
} catch (\Throwable $e) {
    log_message('error', 'Cache rebuild (form) failed: '.$e->getMessage());
}
// --- /Cache direkt aktualisieren ---





if (is_numeric($data['id']) && $content = $Content_model->get($data['id'], NULL, NULL, NULL, NULL, 1, 1)) {
$_POST = $content[0];
$_path = VENTOUX.'receiver/'.$data['id'].'/';
if ( file_exists($_path.'/receiver.txt') ) {
$_POST['_receiver'] = file_get_contents($_path.'/receiver.txt', true);
}

// foreach: es gab die Möglichkeit mehrere categories zu wählen. Das ist denkbar, bringt aber nur Unübersichtlichkeit. Die Seiten über Links vernetzt. Ein get statt foreach wäre auch gut.
if ( isset($content[0]['slices']) AND is_array($content[0]['slices']) ) {
foreach ($content[0]['slices'] as $k  => $slice) { $tmp['main'][] = $slice; }
if ( isset($tmp) AND is_array($tmp)) {$data['slices'] = $tmp;}
// hier werden die categories für die Auswahl in $_POST geschrieben. Das ist auch 2024 richtig so.
$_POST['_categories'] = array();
foreach($Content2category_model->getContentCategories($data['id']) as $d) {$_POST['_categories'][] = $d['category_id'];}
}
}
}
else {
if ( $data['type'] != "allocate") {
//public function get($_where = NULL, $_order = NULL, $_status = NULL, $_limit = NULL, $ids = NULL, $_bead = NULL, $_slices = NULL) {

if (is_numeric($data['id']) && $content = $Content_model->get($data['id'], NULL, NULL, NULL, NULL, NULL, 1)) {

$_POST = $content[0];

$_path = VENTOUX.'receiver/'.$data['id'].'/';
if ( file_exists($_path.'/receiver.txt') ) {
$_POST['_receiver'] = file_get_contents($_path.'/receiver.txt', true);
}

$_POST['_categories'] = array();

// foreach: es gab die Möglichkeit mehrere categories zu wählen. Das ist denkbar, bringt aber nur Unübersichtlichkeit. Die Seiten über Links vernetzt. Ein get statt foreach wäre auch gut.
foreach($Content2category_model->getContentCategories($data['id']) as $d) {$_POST['_categories'][] = $d['category_id'];}
foreach($Grape_model->get(NULL, 'grape ASC') as $d) {$data['_grapes'][$d['ID']] = $d;}
/* keine ahnung, was hier passiert (2024). Es gab Inhalte für verschiedene Bereiche: main und sidebar. Das ist schon lange weg und die db table «area» wird für 
 * das Inhaltsverzeichnis auf der Seite benutz: es ist der Link im IV zu diesem Slice. Gute Verwendung, da auch nur einmal genutzt.
 */
$tmp = array();
foreach ($content[0]['slices'] as $k  => $slice) {$tmp['main'][] = $slice;} $data['slices'] = $tmp; }
}
$data['cont2mail'] = $Content2mail_model->get(array('contID' =>$data['id'] ));   
}
$path = APPPATH.'Views/themengarten/content/*_input.php';
foreach(glob($path) as $file) {$data['types'][str_replace('_input.php', '', basename($file))] = lang('german_lang.themengarten_content_'.str_replace('_input.php', '', basename($file)));}
$data['categories'] = $Category_model->getSelectList();
if ( $data['type'] != "allocate") {
$data['POST'] = $_POST;

//$time_elapsed_secs = microtime(true) - $start;
//echo("<br>ende  $time_elapsed_secs<br>");
$Cron_status_model->save_CI4(array('date' => date('Y-m-d G:i:s')), 9);
$this->render('admin/themengarten/form', $data);
}
if ( $data['type'] == "allocate") {
if ( is_numeric($data['id'])) {
//echo("data {$data['id']}");exit();
$content = $Content_model->get($data['id']);

$_POST = isset($content[0])?$content[0]:NULL;
$data['pol_data'] = $Wine_model->allocationCrit(); // 
$data['pol_data_alloc'] = $Content2prodtype_model->getAlloc($data['id']);
}
$this->render('admin/themengarten/form-cont2', $data);
}


}



/*  --- * DEBUGGING!!!!
 * das forward auskommentieren, um in der content library test - ausgaben machen zu können, sonst werden die bei dem forward überschrieben !!!!!

 $_POST Ein assoziatives Array von Variablen, die dem aktuellen Skript mittels der HTTP POST-Methode übergeben werden, 
 * wenn application/x-www-form-urlencoded oder multipart/form-data als HTTP Content-Type für die Anfrage verwendet wurde.
 => das ist in content der Fall. Wenn nicht mit request arbeiten!  */

public function content( $_contentId = NULL, $_sliceId = NULL, $_type = NULL ) {   
$Content_slice_model = new Content_slice_model();
$Content_slice_data_model = new Content_slice_data_model();
$Content2identifer_model = new Content2identifer_model();
$Content_model = new Content_model();
$App = new \App\Libraries\App(); 
$request = \Config\Services::request();
$data['linkIdentifers'] = $Content2identifer_model->getIdentifers();
$data['backUrl'] = '/'.$this->data['uri_segments'][1].'/'.$this->data['uri_segments'][2].'/'.$this->data['uri_segments'][3];
$data['contentId'] = $_contentId;
$data['sliceId'] = $_sliceId;


if ($_type == '' && is_numeric($data['sliceId'])) {
$tmp = $Content_slice_model->get($data['sliceId']);
$_type = $tmp[0]['type'];
}
$data['type'] = $_type;
/* file namen wenn das form übergeben wurde
 * $_FILES ist superglobales array wie SESSION   */ 

if ( $App::isPost()) { 
unset($_POST['CSRFToken']);

if ( isset($_FILES) AND is_array($_FILES)) {      
foreach($_FILES as $k => $v) { 
if (substr($k, 0, 1) != '_') {
// erst für mehrere hochgeladene FILES ($v ist array)    
if (is_array($v) AND !isset($v['tmp_name'])) {
foreach ($v as $kk => $vv) { 
if (isset($vv['name']) && $vv['name'] != '') {  $_POST[$k][$kk] = $vv['name'];}
}
}
// ... dann nur für ein Bild
else { 
if (isset($v['name']) && $v['name'] != '') { $_POST[$k] = $v['name'];}
}
}
}
}
// insert neues sclice wenn das form abgeschickt wurde
if (!is_numeric($data['sliceId'])) {
// add slice das Feld «area» - Überschrieben «Link Text Inhalt» im Form ist der Texxt im Inhaltsverzeichnis
if ( isset($_POST['_imageZeitung']) AND $_POST['_imageZeitung'] == 1 ) { $sliceId = $Content_slice_model->save_slice(array('content_id' => $data['contentId'], 'position' => 999999, 'type' => $_POST['_type'], 'area' => (isset($_POST['_area'])?$_POST['_area']:''), 'is_provence_img' => 1 )); }
else { $sliceId = $Content_slice_model->save_slice(array('content_id' => $data['contentId'], 'position' => 999999, 'type' => $_POST['_type'], 'area' => (isset($_POST['_area'])?$_POST['_area']:''), 'is_provence_img' => -1  )); }

if ( isset($_POST['_imageRechtsZeitung']) AND $_POST['_imageRechtsZeitung'] == 1 ) { $sliceId = $Content_slice_model->save_slice(array('content_id' => $data['contentId'], 'position' => 999999, 'type' => $_POST['_type'], 'area' => (isset($_POST['_area'])?$_POST['_area']:''), 'is_provence_rechts_img' => 1 )); }
else { $sliceId = $Content_slice_model->save_slice(array('content_id' => $data['contentId'], 'position' => 999999, 'type' => $_POST['_type'], 'area' => (isset($_POST['_area'])?$_POST['_area']:''), 'is_provence_rechts_img' => -1  )); }

$Content_slice_model->reposition($data['contentId'], 'content_id');
$_FILES = $App::convertFilesArray($_FILES);
$type = $_POST['_type'];
foreach($_POST as $k => $v) {
if (substr($k, 0, 1) != '_') {
/*------------------------------
 *  mehrere slices z.B. tabelle, gallery, derzeit nicht genutzt   
 * ------------------------------ */
if (is_array($v)) {
foreach ($v as $kk => $vv) {
$dataId = $Content_slice_data_model->save_CI4(array('content_slice_id' => $sliceId, 'name' => $k, 'value' => $vv, 'position' => 10000+$kk));
if (isset($_FILES[$k][$kk])) {
$path = FCPATH.'_data/'.$data['contentId'].'/'.$sliceId.'/'.$dataId;
// output - ordner in data prüfen und ggf. schaffen
$_path = FCPATH.'_data/'.$data['contentId'].'/'.$sliceId.'/'.$dataId.'/output/';
$_pathOK = FALSE;
$_pathOK = $App::chkDir($_path);
// neues Bld speichern
$App::saveFile($_FILES[$k][$kk], $path); 
if ( $_pathOK ) { include APPPATH .'Controllers/inc_assign_imgfiles_kk.php';  } 
// Ende mehrere slices hochladen (= is_array $v)
}
}
// reposition sortiert die positionen neu. Oben wurde 10000 vergeben, das neue slice data wird ans Ende gestellt und bekommt +10 von der letzten alten position
$Content_slice_data_model->reposition($sliceId, 'content_slice_id', 'name');
}
/*--------------------------------------------
 *  nur ein slice übergeben (z.B. text-image)
 * ----------------------------------------------- */
else {     
if ( $k == "image") {
if ( isset($_POST['_imageText']) AND $_POST['_imageText'] == 1 ) { $dataId = $Content_slice_data_model->save_CI4(array('content_slice_id' => $sliceId, 'name' => $k, 'value' => $v, 'imageText' => 1));}
else { $dataId = $Content_slice_data_model->save_CI4(array('content_slice_id' => $sliceId, 'name' => $k, 'value' => $v)); }
}
else { $dataId = $Content_slice_data_model->save_CI4(array('content_slice_id' => $sliceId, 'name' => $k, 'value' => $v)); }
if (isset($_FILES[$k]['name'])) {
$path = FCPATH.'_data/'.$data['contentId'].'/'.$sliceId.'/'.$dataId;
// output - ordner in data prüfen und ggf. schaffen
$_path = FCPATH.'_data/'.$data['contentId'].'/'.$sliceId.'/'.$dataId.'/output/';
$_pathOK = FALSE;
$_pathOK = $App::chkDir($_path);
// neues Bld speichern
$App::saveFile($_FILES[$k], $path);
/* Bilder können als fertige Versionen mit der Nomenklatur xs_, md_, lg_ im Verzeichnis launcher übergeben werden. Das bietet die Möglichkeit bei kleinem Bild
 * nur einen Ausschnitt anzubieten. Bedeutet aber «komplizierte» Bearbeitung des Photos und ftp Zugang ist erforderlich, muss beherrscht werden.
 * Die einfache (und normale) Alternative: Bild als 1800 Web-Datei auf der Festplatte bereit stellen und die kleineren Varianten von php bearbeiten lassen.
Die hochkant - Versionen hxs und hmd werden nur über den launcher übergeben. Automatisch werden derzeit keine hochkant Bilder erkannt.* 
 */
if ( $_pathOK ) { include APPPATH .'Controllers/inc_assign_imgfiles.php';  } 
}
}
}
}
}
// update bestehende slice
else { 
$_FILES = $App::convertFilesArray($_FILES);
//print_r($_POST);exit();
foreach($_POST as $k => $v) { 
if (substr($k, 0, 1) != '_') {
// wenn $V ein array ist, wurden mehrere Datensätze übergeben (tabelle, slider, gallery    
if (is_array($v)) {
$ids = $request->getPost('_'.$k);
foreach ($v as $kk => $vv) { 
// hier keine Abfrage nach Text image, weil mehrfache Eingabe ohnehin nicht mehr verwendet wird. Das bleibt nur für «falls» stehen. Dann imageText reinpfimeln
if (!is_numeric($ids[$kk])) { $dataId = $Content_slice_data_model->save_CI4(array('value' => $vv, 'content_slice_id' => $data['sliceId'], 'name' => $k, 'position' => 99999));}
else { $dataId = $Content_slice_data_model->save_CI4(array('value' => $vv), $ids[$kk], array('content_slice_id' => $data['sliceId'], 'name' => $k));}
// Bilder speichern, verarbeiten
if (isset($_FILES[$k][$kk]['name'])) {
$path = FCPATH.'_data/'.$data['contentId'].'/'.$data['sliceId'].'/'.$dataId;
// output - ordner in data prüfen und ggf. schaffen
$_path = FCPATH.'_data/'.$data['contentId'].'/'.$sliceId.'/'.$dataId.'/output/';
$_pathOK = FALSE;
$_pathOK = $App::chkDir($_path);
// delete old imageQuad wenn es eins gab und der löschen knopf gedrückt
if ($_POST['_image'] !== NULL AND $_POST['_deleteimage'] !== NULL ) {
if ( file_exists($path.'/'.$_POST['_image']) ) {unlink($path.'/'.$_POST['_image']);}
if ( file_exists($_path.'/xs_'.$_POST['_image']) ) {unlink($_path.'/xs_'.$_POST['_image']);}  
}
// delete old imageQuad wenn es eins gab und ein anderes geladen wird
if ( $_POST['_image'] !== NULL AND $_FILES[$k][$kk]['name'] != "" ) {
if ( file_exists($path.'/'.$_POST['_image']) AND $_POST['_image'] != $_FILES[$k][$kk]['name'] ) { unlink($path.'/'.$_POST['_image']); }
// die alten output - Bilder löschen
if ( file_exists($_path.'/xs_'.$_POST['_image'])  AND $_POST['_image'] != $_FILES[$k][$kk]['name'] ) { unlink($_path.'/xs_'.$_POST['_image']); }
if ( file_exists($_path.'/md_'.$_POST['_image'])  AND $_POST['_image'] != $_FILES[$k][$kk]['name'] ) { unlink($_path.'/md_'.$_POST['_image']); }
if ( file_exists($_path.'/lg_'.$_POST['_image'])  AND $_POST['_image'] != $_FILES[$k][$kk]['name'] ) { unlink($_path.'/lg_'.$_POST['_image']); }
// prüfen ob es auch hochkant bilder gibt und die löschen 
if ( file_exists($_path.'/hxs_'.$_POST['_image'])  AND $_POST['_image'] != $_FILES[$k][$kk]['name'] ) { unlink($_path.'/hxs_'.$_POST['_image']); }
if ( file_exists($_path.'/hmd_'.$_POST['_image'])  AND $_POST['_image'] != $_FILES[$k][$kk]['name'] ) { unlink($_path.'/hmd_'.$_POST['_image']);  }

$_wbp_name = substr($_POST['_image'], -4).".webp";
// die Bilder werden als .jpg hochgeladen. Daher nicht die .webp Bildern mit dem neuen Bild vergleichen, um ggf. das .webp zu löschen
if ( file_exists($_path.'/xss_'.$_wbp_name)  AND $_POST['_image'] != $_FILES[$k][$kk]['name'] ) { unlink($_path.'/hxs_'.$_wbp_name); }
if ( file_exists($_path.'/xs_'.$_wbp_name)  AND $_POST['_image'] != $_FILES[$k][$kk]['name'] ) { unlink($_path.'/xs_'.$_wbp_name); }
if ( file_exists($_path.'/md_'.$_wbp_name)  AND $_POST['_image'] != $_FILES[$k][$kk]['name'] ) { unlink($_path.'/md_'.$_wbp_name); }
if ( file_exists($_path.'/lg_'.$_wbp_name)  AND $_POST['_image'] != $_FILES[$k][$kk]['name'] ) { unlink($_path.'/lg_'.$_wbp_name); }

}
$App::saveFile($_FILES[$k][$kk], $path);
if ( $_pathOK ) { include APPPATH .'Controllers/inc_assign_imgfiles_kk.php';  } 
}
}
// reposition
$Content_slice_data_model->reposition($data['sliceId'], 'content_slice_id', 'name');
}
else {  
// public function save_CI4($_data, $_id = NULL, $_conditions = array(), $_db = NULL) {

$ids = isset($_POST['_'.$k])?$_POST['_'.$k]:"";
if ( $k == "caption") { 
$_imgID = $Content_slice_data_model->get(array('content_slice_id' => $data['sliceId'], 'name' => 'image'));
if ( isset($_imgID[0]['id']) ) {
if ( isset($_POST['_imageText']) AND $_POST['_imageText'] == 1 ) { $dataId = $Content_slice_data_model->save_CI4(array('imageText' => 1), $_imgID[0]['id']); }
else { $dataId = $Content_slice_data_model->save_CI4(array('imageText' => NULL), $_imgID[0]['id']); }
}

$_captID = $Content_slice_data_model->get(array('content_slice_id' => $data['sliceId'], 'name' => 'caption'));
if ( isset($_captID[0]['id']) ) {
$dataId = $Content_slice_data_model->save_CI4(array('value' => $v), $_captID[0]['id']);
}
}
else { 
if (!is_numeric($ids)) {$dataId = $Content_slice_data_model->save_CI4(array('value' => $v, 'content_slice_id' => $data['sliceId'], 'name' => $k, 'position' => 99999));}
else {$dataId = $Content_slice_data_model->save_CI4(array('value' => $v), $ids, array('content_slice_id' => $data['sliceId'], 'name' => $k));}
}
// file processing
if (isset($_FILES[$k]['name'])) {
$path = FCPATH.'_data/'.$data['contentId'].'/'.$data['sliceId'].'/'.$dataId;
// output - ordner in data prüfen und ggf. schaffen
$_path = FCPATH.'_data/'.$data['contentId'].'/'.$data['sliceId'].'/'.$dataId.'/output/';
$_pathOK = FALSE;
$_pathOK = $App::chkDir($_path);
// delete old image wenn es eins gab und der löschen knopf gedrückt
if (isset($_POST['_imageText']) AND $_POST['_name_image'] !== NULL AND $_POST['_deleteimage'] !== NULL ) {
if ( file_exists($path.'/'.$_POST['_name_image']) ) { unlink($path.'/'.$_POST['_name_image']);}
if ( file_exists($_path.'/xs_'.$_POST['_name_image']) ) { unlink($_path.'/xs_'.$_POST['_name_image']); }  
}
// delete old imageQuad wenn es eins gab und ein anderes geladen wird
if ( isset($_POST['_image']) AND $_POST['_image'] !== NULL AND $_FILES[$k]['name'] != "" ) {
if ( file_exists($path.'/'.$_POST['_name_image']) AND $_POST['_name_image'] != $_FILES[$k]['name'] ) { unlink($path.'/'.$_POST['_name_image']); }
// die alten output - Bilder löschen
$_loeName = substr($_POST['_name_image'], 0, -3)."webp";

if ( file_exists($_path.'/xss_'.$_loeName)  AND $_POST['_name_image'] != $_FILES[$k]['name'] ) { unlink($_path.'/xss_'.$_loeName); }
if ( file_exists($_path.'/xs_'.$_loeName)  AND $_POST['_name_image'] != $_FILES[$k]['name'] ) { unlink($_path.'/xs_'.$_loeName); }
if ( file_exists($_path.'/md_'.$_loeName)  AND $_POST['_name_image'] != $_FILES[$k]['name'] ) { unlink($_path.'/md_'.$_loeName); }
if ( file_exists($_path.'/lg_'.$_loeName)  AND $_POST['_name_image'] != $_FILES[$k]['name'] ) { unlink($_path.'/lg_'.$_loeName); }
if ( file_exists($_path.'/llg_'.$_loeName)  AND $_POST['_name_image'] != $_FILES[$k]['name'] ) { unlink($_path.'/llg_'.$_loeName); }
// prüfen ob es auch hochkant bilder gibt und die löschen 
if ( file_exists($_path.'/hxs_'.$_POST['_name_image'])  AND $_POST['_name_image'] != $_FILES[$k]['name'] ) { unlink($_path.'/hxs_'.$_POST['_name_image']); }
if ( file_exists($_path.'/hmd_'.$_POST['_name_image'])  AND $_POST['_name_image'] != $_FILES[$k]['name'] ) { unlink($_path.'/hmd_'.$_POST['_name_image']); }

$_wbp_name = substr($_POST['_image'], -4).".webp";
// die Bilder werden als .jpg hochgeladen. Daher nicht die .webp Bildern mit dem neuen Bild vergleichen, um ggf. das .webp zu löschen
if ( file_exists($_path.'/xss_'.$_wbp_name)  AND $_POST['_image'] != $_FILES[$k]['name'] ) { unlink($_path.'/hxs_'.$_wbp_name); }
if ( file_exists($_path.'/xs_'.$_wbp_name)  AND $_POST['_image'] != $_FILES[$k]['name'] ) { unlink($_path.'/xs_'.$_wbp_name); }
if ( file_exists($_path.'/md_'.$_wbp_name)  AND $_POST['_image'] != $_FILES[$k]['name'] ) { unlink($_path.'/md_'.$_wbp_name); }
if ( file_exists($_path.'/lg_'.$_wbp_name)  AND $_POST['_image'] != $_FILES[$k]['name'] ) { unlink($_path.'/lg_'.$_wbp_name); }

}   
$App::saveFile($_FILES[$k], $path);
if ( $_pathOK ) { include APPPATH .'Controllers/inc_assign_imgfiles.php';  } 
}
}
}
}

// speichere in content, dass erneuert, damit das html über cron_content erneuert wird echo("im".$_POST['_imageZeitung']);exit();

$Content_slice_model->save_CI4(array('area' => (isset($_POST['_area'])?$_POST['_area']:'')), $data['sliceId'] );

if ( isset($_POST['_imageZeitung']) AND $_POST['_imageZeitung'] == 1 ) { $Content_slice_model->save_CI4(array('area' => (isset($_POST['_area'])?$_POST['_area']:''), 'is_provence_img' => 1 ), $data['sliceId'] );}
else { $Content_slice_model->save_CI4(array('is_provence_img' => -1 ), $data['sliceId'] ); }

if ( isset($_POST['_imageRechtsZeitung']) AND $_POST['_imageRechtsZeitung'] == 1 ) { $Content_slice_model->save_CI4(array('area' => (isset($_POST['_area'])?$_POST['_area']:''), 'is_provence_rechts_img' => 1 ), $data['sliceId'] );}
else { $Content_slice_model->save_CI4(array('is_provence_rechts_img' => -1 ), $data['sliceId'] ); }

// speichere neues Datum in content, damit das html über cron_content erneuert wird (sucht nach neuem Datum)
$Content_model->save_CI4(array('date_mod'=>date('Y-m-d G:i:s')), $_contentId);

// --- Cache direkt aktualisieren nach Slice-Änderung ---
try {
    $cache = service('cacheContent');
    if (method_exists($cache, 'rebuildOnContentSave')) {
        $cache->rebuildOnContentSave((int) $_contentId);
    } elseif (method_exists($cache, 'rebuildAll')) {
        $cache->rebuildAll();
    }
} catch (\Throwable $e) {
    log_message('error', 'Cache rebuild (slice) failed: '.$e->getMessage());
}
// --- /Cache direkt aktualisieren ---

// get data und lösche die $_POST Daten
$_POST = array();
$_POST['slice_id'] = $data['sliceId'];
$_POST['content_id'] = $data['contentId'];
$tmp = $Content_slice_data_model->get(array('content_slice_id' => $data['sliceId']), 'position');
foreach($tmp as $k => $v) {$_POST[$v['name']][] = $v;}
// get area
$tmp = $Content_slice_model->get($data['sliceId']);
$_POST['_area'] = $tmp[0]['area'];
$_POST['_imageZeitung'] = isset($tmp[0]['is_provence_img'])?$tmp[0]['is_provence_img']:-1;
$_POST['_imageRechtsZeitung'] = isset($tmp[0]['is_provence_rechts_img'])?$tmp[0]['is_provence_rechts_img']:-1;
}
$data['POST'] = $_POST;

$App::forward('/'.$this->data['uri_segments'][1].'/'.$this->data['uri_segments'][2].'/'.$this->data['uri_segments'][3]);
} 
else { //echo("<br><br><br>neue");exit();
if ( isset($data['sliceId']) AND is_numeric($data['sliceId']) ) {
$_POST = array();
$_POST['slice_id'] = $data['sliceId'];
$_POST['content_id'] = $data['contentId'];
// get data
$tmp = $Content_slice_data_model->get(array('content_slice_id' => $_sliceId), 'position');
foreach($tmp as $k => $v) {$_POST[$v['name']][] = $v;}

// get area
$tmp = $Content_slice_model->get($_sliceId);
$_POST['_area'] = $tmp[0]['area'];
$_POST['_imageZeitung'] = isset($tmp[0]['is_provence_img'])?$tmp[0]['is_provence_img']:-1;
$_POST['_imageRechtsZeitung'] = isset($tmp[0]['is_provence_rechts_img'])?$tmp[0]['is_provence_rechts_img']:-1;
}
$data['POST'] = $_POST;
$file = dirname(__FILE__).'/../Views/themengarten/content/'.$data['type'].'_input.php';
if (is_file($file)) {$this->render('themengarten/content/'.$data['type'].'_input.php', $data);}
else { 
/*  ---------- DEBUGGING!!! * das forward auskommentieren, um in der content library test - ausgaben machen zu können, sonst werden die bei dem forward überschrieben !!!!! */ 
$App::forward('/'.$this->data['uri_segments'][1].'/'.$this->data['uri_segments'][2].'/'.$this->data['uri_segments'][3]);
}
}
}

public function content_mail( $content_id = NULL, $_versID = NULL, $_delete = NULL) {
$Content_model = new Content_model();
$Content2mail_model = new Content2mail_model();
$Gp_2prod_ids_model = new Gp_2prod_ids_model();
$Gp_reste_model = new GP_reste_model();

$Mail_emotion_model = new Mail_emotion_model();

$App = new \App\Libraries\App(); 
$request = \Config\Services::request();
$data = array();
$data['backUrl'] = '/'.$this->data['uri_segments'][1].'/'.$this->data['uri_segments'][2].'/'.$this->data['uri_segments'][3].'/'.$this->data['uri_segments'][4].'/'.$this->data['uri_segments'][5];

/* Die daten für den Mailversand stehen in den vorbereiteten Mail. Die hätte auch in content2mail stehen können. Beider Varianten sind zum Teil aufwändig
 * zu ermitteln. Jetzt muss hier umständlich gesucht werden, dafür geht das heraussuchen von aktuellen Mails direkt in gpPreparePers einfacher.

if (is_numeric($_versID)) {$_prepMails = $GpPreparePers_model->get( array('content2mail_id' => $_versID) );}
*/
$content = $Content_model->get($content_id);

$_POST = isset($content[0])?$content[0]:NULL;
//$data['pol_data'] = $Wine_model->allocationCrit();
/*
if ( isset($where) ) { $where .= " AND (wr_product.type = 'WF' OR wr_product.type = 'WV' OR wr_product.type = 'WA'  )";          }
if ( !isset($where) )  { $where = " (wr_product.type = 'WF' OR wr_product.type = 'WV' OR wr_product.type = 'WA' )";          }

*/

if ( $App::isPost() ) {
$_mail_alloc = -1;
if ( $request->getPost('inp_data_alloc') !== NULL AND is_array($request->getPost('inp_data_alloc')) ) { 
$Gp_2prod_ids_model->saveAlloc( $_versID );

if ( $request->getPost('reste') !== NULL AND $request->getPost('reste') == 1 ) { $Gp_reste_model->saveAlloc( $_versID ); }
$_mail_alloc = 1;
}
$_POST = $request->getPost();
$data['POST'] = $request->getPost();
if ( $content_id != NULL) {
if ( $_delete == NULL) {
/* ---   BILD    */
if ( isset($_FILES) AND is_array($_FILES)) {   
$_FILES = $App::convertFilesArray($_FILES);
// Bild - Pfad da?
// Pfad zum eventuellen output - Bild. 
$path = FCPATH.'_data/'.$content_id.'/mail/';
$_pathLauncher = FCPATH.'launcher';
if ( isset($_FILES['img']['name'])  AND $_FILES['img']['name'] != "" AND ($_POST['_img'] == "" OR $_FILES['img']['name'] != $_POST['_img']) ) {$update['img'] = $_FILES['img']['name'];}
if ( !isset($_FILES['img']['name']) ) {$update['img'] = $_POST['_img'];}
/* speichern der Vorbereiteten Mails. Die kommen in die $Content2mail_model und in die $GpPreparePers_model Tabelle
Im content2 Mail wird der Inhalt für die Mail z.B. Mail über das Burgund vorbereitet und mit «Sehr geehrter x, heute neue Werbung» an den Kunden gesendet.
In GpPreparePers sollen / sollten (!) Mails an einen Kunden persönlich vorvereitet werden -> Sie waren doch im Burgund, hier eine Mail, die Sie interessieren könnte:
-> Inhalt der Mail über das Burgund
Das persönliche Vorbereiten ist nicht umgesetzt und kompliziert.
Bleibt die Herausforderung, dass die Mail, wenn sie 3 Wochen später rausgesucht wird, oft schon alt ist (die ersten Sonnenstrahlen / Schneeflocken / Spargel). Diese
Aktualität aber gerade den Charme der weinraum Mails ausmacht.
 *  */

$update['contID'] = $content_id;
$update['stat'] = isset($_POST['stat'])?$_POST['stat']:0;
$update['only_buyer'] = isset($_POST['only_buyer'])?$_POST['only_buyer']:0;
$update['reste'] = isset($_POST['reste'])?$_POST['reste']:0;
$update['betreff'] = isset($_POST['betreff'])?$_POST['betreff']:"";
$update['extra_header'] = isset($_POST['extra_header'])?$_POST['extra_header']:"";
$update['header'] = isset($_POST['header'])?$_POST['header']:"";
$update['text'] = isset($_POST['text'])?$_POST['text']:"";
$update['Emotion_ID'] = isset($_POST['Emotion_ID'])?$_POST['Emotion_ID']:0;
$update['von'] = isset($_POST['von'])?date('Y-m-d', strtotime($_POST['von'])):NULL;
$update['bis'] = isset($_POST['bis'])?date('Y-m-d', strtotime($_POST['bis'])):NULL;
//print_r($_POST); exit();
if (!is_numeric($_versID)) { $_versID = $Content2mail_model->save_CI4($update); }
if (is_numeric($_versID)) {$Content2mail_model->save_CI4($update, $_versID);}
if ( isset($_FILES['img']['name']) AND $_FILES['img']['name'] != "" ) {
// das org - Bild löschen
if ( file_exists($path.'/'.$_POST['_img']) AND $_FILES['img']['name'] != $_POST['_img'] AND isset($_POST['_img']) AND $_POST['_img'] != "") {unlink($path.$_POST['_img']);}
$_fileOrgName = $_FILES['img']['name'];
$_FILES['img']['name'] = (isset($_FILES['img']['name']) AND $_FILES['img']['name'] != "")?$_versID."_".$_FILES['img']['name']:"";
$_pathOK = FALSE;
$_pathOK = $App::chkDir($path);
 if ( $_pathOK ) { // = der Pfad existiert oder wurde angelegt
// neues Bld speichern
$App::saveFile($_FILES['img'], $path);
// das launcher Bild löschen
if ( file_exists($_pathLauncher.'/'.$_fileOrgName) ) {  unlink($_pathLauncher.'/'.$_fileOrgName);}
}
}  
}
$data['inp_data_alloc'] = $Gp_2prod_ids_model->getAlloc($_versID);

if ( $_mail_alloc == -1 ) { $App::forward('/'.$this->data['uri_segments'][1].'/'.$this->data['uri_segments'][2].'/'.$this->data['uri_segments'][3]); }
if ( $_mail_alloc == 1 ) { $App::forward('/'.$this->data['uri_segments'][1].'/'.$this->data['uri_segments'][2].'/'.$this->data['uri_segments'][3].'/'.$this->data['uri_segments'][4].'/'.$this->data['uri_segments'][5]); }

}
if ( $_delete == "delete") {
if ( is_numeric($_versID) ) { 
$mail2delete = $Content2mail_model->get(array('contID' =>$content_id, 'ID' =>$_versID  ));
$_fileName = $mail2delete[0]['image'];
$Content2mail_model->delete($_versID);
// Pfad zum eventuellen output - Bild. 
$path = FCPATH.'_data/'.$content_id.'/mail/';
$_path = FCPATH.'_data/'.$content_id.'/mailoutput/';

if ( isset($_fileName) AND $_fileName != "" ) {
if ( file_exists($path.'/'.$_fileName) ) {
// das org - Bild löschen
unlink($path.'/'.$_fileName);
// das output - Bild löschen
unlink($_path.'/'.$_fileName);
}
}
}
}


// Zuweisen der Mail zu Produkten
if ( $data['type'] == "allocate") { $Content2prodtype_model->saveAlloc( $data['id'], $request->getPost('inp_data_alloc') ); }

}
}
else { 
if ( $_versID !== NULL ) {
$_lala = $Content2mail_model->get($_versID );
if ( isset($_lala[0]['von']) AND $_lala[0]['von'] == "1970-01-01") {$_lala[0]['von'] = "1999-01-01";}
if ( isset($_lala[0]['bis']) AND $_lala[0]['bis'] == "1970-01-01") {$_lala[0]['bis'] = "2069-12-31";}
$data = isset($_lala[0])?$_lala[0]:NULL;
//echo("ljk $_versID "); print_r($data);
$data['inp_data_alloc'] = $Gp_2prod_ids_model->getAlloc($_versID);
$data['shop_prod_id'] = isset($data['inp_data_alloc']['shop_prod_id'])?$data['inp_data_alloc']['shop_prod_id']:0;
$data['backUrl'] = '/'.$this->data['uri_segments'][1].'/'.$this->data['uri_segments'][2].'/'.$this->data['uri_segments'][3].'/'.$this->data['uri_segments'][4].'/'.$this->data['uri_segments'][5];

$data['POST'] = isset($data)?$data:NULL;

$data['mailEmotion'] = $Mail_emotion_model->get(NULL, 'sort asc');
$this->render('themengarten/content/mail_text_in.php', $data); 
exit();
}
if ( $_versID === NULL ) {
$data['backUrl'] = '/'.$this->data['uri_segments'][1].'/'.$this->data['uri_segments'][2].'/'.$this->data['uri_segments'][3].'/'.$this->data['uri_segments'][4];
$data['mailEmotion'] = $Mail_emotion_model->get(NULL, 'sort asc');
$this->render('themengarten/content/mail_text_in.php', $data); 
exit();

}

}
}

public function delete() {
$Content_model = new Content_model();
$App = new \App\Libraries\App(); 
$id = isset($this->data['uri_segments'][3])?$this->data['uri_segments'][3]:"";
if (is_numeric($id)) { $Content_model->delete($id); }
$App::forward('/admin_themengarten');
}


/* die Bildnamen stammen aus uralten Versionen. Da die irgendwo verwendet und abgefragt werden (können), bleiben die Namen in der db gleich, auch wenn die Verwendung eine
 * völlig andere ist.
 * 
 * Das Hochladen über das Verzeichnis Laucher stammt a) aus der Zeit als das resize nicht funktioniert hat.
 * b) können je nach Größe verschiedene Bilder geladen werden.
 * 
 * Daher ist das Hochladen verschieden großer Bilder über den launcher weiter als Schattenfunktion enthalten.
 * Die neue Startseite (April 2023) sieht nur eine Größe vor - wenn die Zeilen zu klein werden, werden die Bilder übereinander gestapelt.
 */

public function chk_file_content  ( $_file = NULL, $name = NULL, $contentId = NULL ) {     
$App = new \App\Libraries\App(); 
$request = \Config\Services::request();
if ( $_file !== NULL AND $name !== NULL AND $contentId !== NULL) {
$path = FCPATH.'_data/'.$contentId;
// output - ordner in data prüfen und ggf. schaffen
//$_pathLauncher = FCPATH.'launcher';$abfr_bild = $_pathLauncher.'/'.$_file['name'];if (file_exists($abfr_bild)  ) {copy($_pathLauncher.'/'.$_file['name'], $_path.'/'.$_file['name'] );unlink($_pathLauncher.'/'.$_file['name']);}
// die launcher variante kann für verschiedene Bilder groß/klein genutzt werden. macht alles VIEL zu kompliziert !!!!!

$_path = FCPATH.'_data/'.$contentId.'/output/';
$_pathOK = FALSE;
$_pathOK = $App::chkDir($_path);
$App::saveFile($_file, $_path);


if (isset($_file['name']) AND $_file['name'] != "" AND $_pathOK ) {
if ( $name !== "imageBreitFlach" AND $name !== "imageIndex") {
$_name = substr($_file['name'], 0, -4).".webp";
$image = \Config\Services::image()->withFile($_path.'/'.$_file['name'])->convert(IMAGETYPE_WEBP)->save($_path.'/'.$_name);
}

if ( $name == "imageBreitFlach" ) {
$_name = substr($_file['name'], 0, -4).".webp";
$image = \Config\Services::image()->withFile($_path.'/'.$_file['name'])->convert(IMAGETYPE_WEBP)->save($_path.'/'.$_name);

$_name = "xss_".substr($_file['name'], 0, -4).".webp";
$image = \Config\Services::image()->withFile($_path.'/'.$_file['name'])->resize(450, 5, true, 'width')->convert(IMAGETYPE_WEBP)->save($_path.'/'.$_name);

$_name = "xs_".substr($_file['name'], 0,-4).".webp";
$image = \Config\Services::image()->withFile($_path.'/'.$_file['name'])->resize(650, 5, true, 'width')->convert(IMAGETYPE_WEBP)->save($_path.'/'.$_name);

$_name = "md_".substr($_file['name'], 0,-4).".webp";
$image = \Config\Services::image()->withFile($_path.'/'.$_file['name'])->resize(800, 5, true, 'width')->convert(IMAGETYPE_WEBP)->save($_path.'/'.$_name);

$_name = "lg_".substr($_file['name'], 0,-4).".webp";
$image = \Config\Services::image()->withFile($_path.'/'.$_file['name'])->resize(1200, 5, true, 'width')->convert(IMAGETYPE_WEBP)->save($_path.'/'.$_name);

}

if ( $name == "imageIndex" ) {
$_name = "xss_".substr($_file['name'], 0,-4).".webp";
$image = \Config\Services::image()->withFile($_path.'/'.$_file['name'])->resize(450, 5, true, 'width')->convert(IMAGETYPE_WEBP)->save($_path.'/'.$_name);

$_name = "xs_".substr($_file['name'], 0,-4).".webp";
$image = \Config\Services::image()->withFile($_path.'/'.$_file['name'])->resize(650, 5, true, 'width')->convert(IMAGETYPE_WEBP)->save($_path.'/'.$_name);

$_name = "md_".substr($_file['name'], 0,-4).".webp";
$image = \Config\Services::image()->withFile($_path.'/'.$_file['name'])->resize(1050, 5, true, 'width')->convert(IMAGETYPE_WEBP)->save($_path.'/'.$_name);

}
}
}
}



/* Schnittstelle zum ex- und import von content.
 * Um Daten in KI oder auch Textverarbeitung bearbeiten zu können, müssen die Inhalte in Textform aus dem System raus und wieder rein.
 * Dazu müssen die content und content slice Informationen als Sonderzeichen in den Text und nur die «value» Daten werden bearbeitet.
 * Beim Import werden die content und content slice Informationen ausgelesen und die neuen «value» Werte ersetzt.
 * 
 * Wenn die Inhalte für wordpress in eine z.B. xml oder csv Struktur überführt werden sollen, ist der export eine Basis und kann angepaßt werden.
 */
public function export() {   
$Content_model = new Content_model();
$App = new \App\Libraries\App(); 

$content_id = isset($this->data['uri_segments'][3])?$this->data['uri_segments'][3]:"";
// die vorletzte 1 bedeutet, dass die slices ausgelesen werden und nicht nur der Inhalt, die letzte, dass die slice raw daten ausgelesen werden, nicht die für die Ausgabe optimierten
if (is_numeric($content_id) ) { 
$content = $Content_model->get($content_id, NULL, NULL, NULL, NULL, NULL, 1, 1);

if (isset($content) AnD is_array($content)) {

$content_txt = "";
$_txt_export = "";
foreach ($content[0]['slices'] as $k  => $slice) {
/* die input dateien definieren, wie viele und welche content slice data (csd) Zeilen erstellt werden. Wenn hier im Quelltext z.B. neben der Unterschrift 
 * unter einem Bild noch ein seo alt - Text aufgenommen wird, ist das eine neue Zeile in csd.
 * Um das zu handlen, müsste entweder die Angabe der Felder fest hinterlegt werden. Oder das System lernt selbst, analysiert die Daten und scheibt
 * die Angaben in eine Datei. Oder das lernen wird übersprungen und beim neuen input - file sofort eine Datei mit den csd namen angelegt => Beste Variante!
 * in slice enthalten : id, conent_id, type, area, link, is_provence etc.
 * hier werden nur die cs_data value Werte aktualisiert.
 * für einen kompletten Export z.B. wordpress dieses slice Daten auch verwenden
 * Derzeit gibt es nur Text, Text & Bild, Zitat, Video
 * Text & Bild, Text werden hier zu Fuß hard coded und es soll der Himmel runter fallen, wenn neue input files erzeugt werden!
 */
$slice_type = "";
$slice_id = "";
$content_txt = "";
$content_val = "";
$_i = 0;
foreach ($slice as $k => $slice_data) {
$_i ++;
//if ( $k != "data") {echo("<br>slice $k: $slice_data<br>");}
if ( $k === "type") { $slice_type = $slice_data; }
if ( $k === "id") { $slice_id = $slice_data; }
/* Die Slice wird als array mit den Werten des Slice übergeben name => wert.
 * Die Slice data Werte in dem data array. Das wird gesucht und diese Inhalte analog als array ausgelesen. Es müssen nicht die Werte aus der db sein, daher wird in den models
 * die «pure» variante ausgelesen: nur die Werte der sclices und sclice data aus der db. Ansonsten stehen manchmal noch Winzer Namen und deren Weine oder sonstwas in den 
 * slice data Angaben für die Ausgabe auf der Seite. Das interessiert hier alles nicht bei der Editierbarkeit.
 * BEACHTEN: Die db Felder der slice data werden in der input Datei erstellt: wenn dort neues Feld aufgenommen wird, ändert sich hier die Abfrage! siehe oben.
 * Für den Export, um es in ein neues System (wordpress) einladen zu können, kann das anders sein.
 */
if ( $k == "data") {
foreach ($slice_data as $kd => $v_data) {
switch ($slice_type) {
// text ist das Gleiche wie text image. Das ist viel !!! komplizierter, aber text läuft damit auch. Wozu neu machen? In text alle Kommentare gelöscht!
case "text":
if ( is_array($v_data) ) {
$cont_type = "";
$content_val = "";
$caption_val = "";
$_first = 0;
$_chkval = 0;
foreach ($v_data as $kkd => $vv_data) {
if ( $kkd === "id" AND $content_txt === "" ) { $content_txt .="##b".$vv_data; $_first = 1;}
if ( $kkd === "id" AND $content_txt != "" AND $_first != 1) { $content_txt .="<br>##b".$vv_data; $_first = 1;}

if ( $kkd === "name" ) { $cont_type = $vv_data; }
if ( $kkd === "value" AND $cont_type == "content") { $content_val = $vv_data; $_chkval = 1;}

}
if ( $cont_type == "image") {
$_strlen = strlen($content_txt);
$_krpos = strrpos($content_txt, "##"); // das Kreuz hat 3 digits!
$_cut = $_krpos - $_strlen ;
$content_txt = substr($content_txt, 0, $_cut);
}
else {
if ( $content_txt != ""  ) { 
if ( $cont_type == "content") { $content_txt .= "t##"; }
if ( $cont_type == "caption") { $content_txt .= "b##"; }
}
if ( $content_val != "" ) { $content_txt .= "<br>Textallei:<br>".$content_val; }
else { if ( $cont_type == "content") { $content_txt .= "<br>Textallei<br>leer";} }

}
$content_val = "";
$caption_val = "";
}
break;
case "text_image":
if ( is_array($v_data) ) {
$cont_type = "";
$content_val = "";
$caption_val = "";
$_first = 0;
$_chkval = 0;
foreach ($v_data as $kkd => $vv_data) {
/* Möglich, die slice id mit anzuhängen. Macht aber fürs editieren keinen Sinn : if ( $kkd === "id" ) { if ( $content_txt === "" ) { $content_txt .="[[{cont_slice_id||".$slice_id."}{".$kkd."||".$vv_data."}"; $_first = 1;}if ( $content_txt != "" AND $_first != 1) { $content_txt .="{".$kkd."||".$vv_data."}";}} */
/* es gibt slice data Zeilen, die interessieren hier nicht. Die id werden aber vorher zugewiesen. Der Haken: die slice data Werte kommen nicht verlässlich in einer Reihenfolge und können dahe nicht über $_i abgefangen werden.
 ein Löschen der uninteressanten image Werte daher erst möglich, wenn die slice data Schleife durchlaufen ist. */
if ( $kkd === "id" AND $content_txt === "" ) { $content_txt .="##b".$vv_data; $_first = 1;}
if ( $kkd === "id" AND $content_txt != "" AND $_first != 1) { $content_txt .="<br>##b".$vv_data; $_first = 1;}

if ( $kkd === "name" ) { $cont_type = $vv_data; }
if ( $kkd === "value" AND $cont_type == "content") { $content_val = $vv_data; $_chkval = 1;}
if ( $kkd === "value" AND $cont_type == "caption") { $caption_val = $vv_data; $_chkval = 1;}
}
if ( $cont_type == "image") {
/* Image soll nicht editiert werden. Es ist aber bei Image schon die id eingefügt und soll hier wieder raus. Das letzte «†» suchen und ab da die Zeichen abschneiden
 */
$_strlen = strlen($content_txt);
$_krpos = strrpos($content_txt, "##"); // das Kreuz hat 3 digits!
$_cut = $_krpos - $_strlen ;
$content_txt = substr($content_txt, 0, $_cut);

}
else {
if ( $content_txt != ""  ) { 
if ( $cont_type == "content") { $content_txt .= "t##"; }
if ( $cont_type == "caption") { $content_txt .= "b##"; }
}
/* HIER NICHTS ÄNDERN!!! NIE!!! 
 * Unten werden die Zeichen zerpflückt und wenn die Zahl der Buchstaben nicht stimmt, kommt nur Müll bei raus!
 */
if ( $content_val != "" ) { $content_txt .= "<br>Bild Text:<br>".$content_val; }
else { if ( $cont_type == "content") { $content_txt .= "<br>Bild Text:<br>leer";} }
if ( $caption_val != "" ) { $content_txt .= "<br>Bild Unterschrift:<br>".$caption_val."<br>"; }
else { if ( $cont_type == "caption") { $content_txt .= "<br>Bild Unterschrift:<br>leer<br>";} }
}
$content_val = "";
$caption_val = "";
}
break;
case "text_quote":
break;
case "video":
break;

default:
echo("Fehler: es wurde kein input type - Muster gefunden!");exit();
}
}
//echo ("<br><br>ende:<br>$content_txt");
$_txt_export .= $content_txt;

}
}
}
}

/* export txt file: */
$_path = VENTOUX.'receiver/'.$content_id.'/';
$_pathOK = FALSE;
$_pathOK = $App::chkDir($_path);
if (isset($_txt_export) AND $_txt_export != "" AND $_pathOK ) {

if(file_put_contents($_path.'receiver.txt', $_txt_export)){
$App::forward('/admin_themengarten/update/'.$content_id);}
else { echo("das Speichern hat nicht geklappt. Über die «zurück» Taste wieder zur Eingabe"); }
}
}

}

public function get_first_data( $_text = NULL) {
$Content_slice_data_model = new Content_slice_data_model;

if ( $_text != NULL ) {
/* übergabe 
##b1234566t##
Image Bildtext
rasteau

##b1234566c##
Image Text
das ist das schöne Rasteau
*/
$_txtcut = substr($_text, 2); // ohne die Kreuze am Anfang
$_cont_type = substr($_txtcut, 0, 1);
$_txtcut2 = substr($_txtcut, 1); // ohne den content type
$_krpos = strpos($_txtcut2, "##"); // Position der beiden Kreuze am Anfang, die Position hängt von der Anzahl der Stellen der id ab
$_id = substr($_txtcut2, 0, $_krpos-1);
//$_strlen_id = strlen($_id); // Länge der id in zahlen

$contstyle = substr($_txtcut2, $_krpos-1, 1); // vom Rest txtcut2 bis zur Position der letzten ## - 1, da Positionen von 0 zählen und dann einen Buchstaben auslesen
$_bisKreuz_next = substr($_txtcut2, $_krpos+2);
$_posKreuz_next = strpos($_bisKreuz_next, "##");
//echo("<br>id $_id style $contstyle bis kreiz $_posKreuz_next<br>");

//echo("style {{{{{$_bisKreuz_next}}}}} $_posKreuz_next<br>");
$cont_value = "";
if ( $_posKreuz_next > 6 ) {
if ( $contstyle == "b") { $cont_value = substr($_bisKreuz_next, 26, $_posKreuz_next-34)!=NULL?substr($_bisKreuz_next, 26, $_posKreuz_next-34):"ohne Text"; }
if ( $contstyle == "t") { $cont_value = substr($_bisKreuz_next, 18, $_posKreuz_next-19)!=NULL?substr($_bisKreuz_next, 18, $_posKreuz_next-22):"ohne Text";}
}
else {
if ( $contstyle == "b") { $cont_value = substr($_bisKreuz_next, 26)!=NULL?substr($_bisKreuz_next, 26):"ohne Text"; }
if ( $contstyle == "t") { $cont_value = substr($_bisKreuz_next, 18)!=NULL?substr($_bisKreuz_next, 18):"ohne Text";}

}
//echo("id $_id  value $cont_value<br>");

if ( $contstyle == "b") {$cont_rest = substr($_bisKreuz_next, $_krpos,);}
if ( $contstyle == "t") {$cont_rest = substr($_bisKreuz_next, $_krpos,15);}
$cont_rest = substr($_txtcut2, $_krpos + 2);
$_krpos2 = strpos($cont_rest, "##");


if ( $_krpos2 > 6 ) { $_cont_next = substr($cont_rest, $_krpos2); }
else { $_cont_next = NULL;}

//echo("<br> save cs_id:{$_id}- value{$cont_value}---");

$update['value'] = $cont_value;
$Content_slice_data_model->save_CI4($update, $_id);

if ( strlen($_cont_next) > 2 ) { $this->get_first_data( $_cont_next );}

}   
}

public function import( ) {   
$App = new \App\Libraries\App(); 

$content_id = isset($this->data['uri_segments'][3])?$this->data['uri_segments'][3]:"";
if ( is_numeric( $content_id ) ) {
$_path = VENTOUX.'receiver/'.$content_id.'/';
if ( file_exists($_path.'/receiver.txt') ) {
$_txt_import = file_get_contents($_path.'/receiver.txt', true);
$path = FCPATH.'_data/'.$content_id;
//echo $_txt_export;
$_first_data = $this->get_first_data( $_txt_import );

$App::forward('/admin_themengarten/update/'.$content_id);
}
else {echo("kein receiver.txt file für diesen Inhalt");}
}
}


public function act_receiver() {
$App = new \App\Libraries\App(); 
$request = \Config\Services::request();

$id = isset($this->data['uri_segments'][3])?$this->data['uri_segments'][3]:"";

if ( is_numeric($id) ) {
if ($App::isPost() ) {
$_receiver_file = $request->getPost('_receiver');
if ( isset( $_receiver_file )  ) {
/* export txt file: */
$_path = VENTOUX.'receiver/'.$id.'/';
$_pathOK = FALSE;
$_pathOK = $App::chkDir($_path);
if (isset($_receiver_file) AND $_pathOK ) {

if(file_put_contents($_path.'receiver.txt', $_receiver_file)){ $App::forward('/admin_themengarten/update/'.$id); }
else { echo("das Speichern hat nicht geklappt. Über die «zurück» Taste wieder zur Eingabe"); }
}
}
}
}
}

}

