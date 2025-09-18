<?php namespace App\Models;

use CodeIgniter\Model;

class Aodel extends Model {

public function get($_where = NULL, $_order = NULL, $_limit = NULL) {
$data = array();
$builder = $this->db->table($this->dbTable); 
$builder->select( '*' );
if ($_limit !== NULL) {$builder->limit($_limit);}
if ($_where !== NULL) {
if (is_array($_where)) {
foreach($_where as $k => $v) {$builder->where($k, $v);}
}
else {$builder->where('id', $_where);}
}
if ($_order != NULL) {
if (is_array($_order)) {
foreach($_order as $field => $direction) {$builder->orderBy($field, $direction);}
}
else {$builder->orderBy($_order);}
}
$query = $builder->get();
if ( $builder->countAllResults() > 0 ) {
foreach ($query->getResult('array') as $row) {
$data[] = $row;
}
}
return $data;
}

/** remove helpder data from array coming from form */
public function filterData($_data) {
if (is_array($_data)) {
foreach ($_data as $k => $v) {
if (substr($k, 0, 1) == '_') {
unset($_data[$k]);
}
}
}
return $_data;
}

/** default saver */
public function save_CI4($_data, $_id = NULL, $_conditions = array(), $_db = NULL) {
$builder = $this->db->table($this->dbTable); 
$db      = \Config\Database::connect();
$ret = FALSE;
$_data = $this->filterData($_data);
if (is_numeric($_id)) {
// update
$cond = array_merge(array('id' => $_id), $_conditions);
if ($builder->update($_data, $cond)) {$ret = $_id;}
}
else {
// insert
unset($_data['id']);
if ($builder->insert($_data, TRUE)) { 
$ret = $db->insertID();
// wenn andere Datenbank verwendet wird, funktioniert die letzte Anweisung - scheibar - nicht. die ID ist 0
if ( $ret == 0 ) { 
$builder = $this->db->table($this->dbTable); 
$db      = \Config\Database::connect();
/* !!!! wenn ID gross geschrieben: Fehler !!!! */
$builder->orderBy('id',"desc");
$builder->limit(1);
$q_cont = $builder->get();

if ( count($q_cont->getResult('array')) > 0) {
$_last = $q_cont->getRowArray(); 
/* !!!! wenn ID gross geschrieben: Fehler !!!! */
$ret = $_last['id'];
}

}
}
}
//echo("aodel $ret <br>");
return $ret;
}

/** default saver */
public function save_CI_fremd($_data, $_id = NULL, $_conditions = array(), $_db = NULL) {
$builder = $this->db->table($this->dbTable); 
if ( $_db !== NULL ) {
$db      = \Config\Database::connect($_db);
$ret = FALSE;
$_data = $this->filterData($_data);
if (is_numeric($_id)) {
// update
$cond = array_merge(array('id' => $_id), $_conditions);
if ($builder->update($_data, $cond)) {$ret = $_id;}
}
else {
// insert
unset($_data['id']);
if ($builder->insert($_data, TRUE)) { 
$ret = $db->insertID();
// wenn andere Datenbank verwendet wird, funktioniert die letzte Anweisung - scheibar - nicht. die ID ist 0
if ( $ret == 0 ) { 
$builder = $this->db->table($this->dbTable); 
$db      = \Config\Database::connect();
$builder->orderBy('id',"desc");
$builder->limit(1);
$q_cont = $builder->get();

if ( count($q_cont->getResult('array')) > 0) {
$_last = $q_cont->getRowArray(); 
$ret = $_last['id'];
}

}
}
}
//echo("aodel $ret <br>");
return $ret;
}
}


public function get_stats($_where = NULL, $_order = NULL, $_limit = NULL) {
$db_stats = \Config\Database::connect("stats");
$data = array();
$builder = $db_stats->table($this->dbTable); 
$builder->select( '*' );
if ($_limit !== NULL) {$builder->limit($_limit);}
if ($_where !== NULL) {
if (is_array($_where)) {
foreach($_where as $k => $v) {$builder->where($k, $v);}
}
else {$builder->where('id', $_where);}
}
if ($_order != NULL) {
if (is_array($_order)) {
foreach($_order as $field => $direction) {$builder->orderBy($field, $direction);}
}
else {$builder->orderBy($_order);}
}
$query = $builder->get();
if ( $builder->countAllResults() > 0 ) {
foreach ($query->getResult('array') as $row) {
$data[] = $row;
}
}
return $data;
}

/** default deleter */
public function delete_CI4($_data) {
$builder = $this->db->table($this->dbTable); 
$db      = \Config\Database::connect();
$ret = FALSE;

if (is_numeric($_data)) {$_data = array('id' => $_data);}
$builder = $this->db->table($this->dbTable); 
if ($builder->delete($_data) && $this->db->affectedRows() > 0) {
$ret = TRUE;
}
return $ret;
}

/** default child deleter */
public function deleteChilds($_table, $_col, $_childTable, $_childCol = 'id') {
$ret = FALSE;
$builder = $this->db->table($this->dbTable); 
$builder->where($_childCol.' NOT IN (SELECT DISTINCT('.$_col.') AS '.$_childCol.' FROM '.$_table.')', NULL, FALSE);
if ($builder->delete($_childTable)) {$ret = TRUE;}
return $ret;
}

/** default parent deleter*/
public function deleteParent($_table, $_value) {
$ret = FALSE;
if (!is_array($_value)) {$_value = array('id' => $_value);}
$builder = $this->db->table($this->dbTable); 
if ($builder->delete($_value)) {$ret = TRUE;}
return $ret;
}

 /*$Content_slice_data_model->reposition($sliceId, 'content_slice_id', 'name'); beim anlegen der content_slice_data wird die position 10000 vergeben. 
  hier werden alle postionen gesucht, sortiert und die positionen in schritten von 10 aktualisiert  
Die group Variable ist der Urzustand: es konnten Seiteleisten Inhalte und Main Inhalte angelegt werden. Die group Variable hat einen der Bereiche ausgewählt.
Die sidebar wurde vor Jahren raus genommen, alle Inhalte stehen in main.
Für die Links auf der Seite wurde die area Spalte genommen. Die group_by (select distinct) macht daher keinen Sinn mehr.
Wenn doch noch einmal eine area dazu kommen sollte, muss eine neue Spalte angelegt werden und die Funktion angepaßt werden. 
Damit das «Wissen» bleibt, steht die derzeit ungenutzte Abfrage noch da.
  *   */   

public function reposition($_value, $_key = 'id', $_group = NULL) {
$groups = array('');
if ($_group !== NULL) {
$builder = $this->db->table($this->dbTable); 
$builder->select('DISTINCT('.$_group.')');
$builder->where($_key, $_value);
$query = $builder->get();
if ($builder->countAllResults() > 0) {
$groups = array();
foreach ($query->getResult('array') as $row) {$groups[] = $row[$_group];}
}
}

$builder = $this->db->table($this->dbTable); 
$builder->where($_key, $_value);
$query = $builder->get();
if ($builder->countAllResults() > 0) {
$groups = array();
foreach ($query->getResult('array') as $row) {$groups[] = $row[$_key];}
}


foreach($groups as $group) {
$builder = $this->db->table($this->dbTable); 
$builder->select('*');
$builder->where($_key, $_value);
//echo("<br>key $_key, val {$_value}");

//if ($group != '') {$builder->where($_group, $group);}
$builder->orderBy('position', 'asc');
$q_groups = $builder->get();

if ($builder->countAllResults() > 0) {
$i = 10;
foreach ($q_groups->getResult('array') as $row) {
$builder = $this->db->table($this->dbTable); 
//echo("<br>repos $i, row {$row['id']}");
$builder->update(array('position' => $i), array('id' => $row['id']));
$i = $i + 10;
}
}
}
//exit();
}

public function up($_id = NULL, $_key = NULL, $_group = NULL) {
$data = $this->get($_id);
if ( isset($data[0]) AND is_array($data[0])) {
$builder = $this->db->table($this->dbTable); 
$builder->where('id', $data[0]['id']);
$builder->update(array('position' => $data[0]['position']-15));
$this->reposition($data[0][$_key], $_key, $_group);
}
}

public function down($_id = NULL, $_key = NULL, $_group = NULL) {
$data = $this->get($_id);
if ( isset($data[0]) AND is_array($data[0])) {
$builder = $this->db->table($this->dbTable); 
$builder->where('id', $data[0]['id']);
$builder->update(array('position' => $data[0]['position']+15));
$this->reposition($data[0][$_key], $_key, $_group);
}
}
}
