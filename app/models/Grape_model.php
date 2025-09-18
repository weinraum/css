<?php namespace App\Models;
use App\Models\Content_cache_model;
use App\Models\Wine_model;

class Grape_model extends Aodel {
protected $dbTable = 'wr_grapes';

public function getRelated( $_grapeID = NULL) {
$data = array();
if ( $_grapeID != NULL ){
$Wine_model = new Wine_model();
$_wines2grape = $Wine_model->get_Wines_byGrape( $_grapeID, NULL , NULL );
if ( isset($_wines2grape['wines']) AND is_array($_wines2grape['wines']) ) { //print_r($_wines2grape); exit();
foreach ( $_wines2grape['wines'] as $k => $v ) {
if ( isset($v['identifer_prod'])) { $data['winzer'][$v['identifer_prod']] = $v['producer']; $data['winzer_contID'][$v['identifer_prod']] =$v['rel_win_cont_id'];}
if ( isset($v['name_pol1_url'])) { $data['region1'][$v['name_pol1_url']] = $v['name_pol1'];$data['region1_contID'][$v['name_pol1_url']] =$v['rel_cont1_id'];
if ( isset($v['name_pol2_url'])) { $data['region2'][$v['name_pol1_url']][$v['name_pol2_url']] = $v['name_pol2'];$data['region2_contID'][$v['name_pol1_url']][$v['name_pol2_url']] =$v['rel_cont2_id']>0?$v['rel_cont2_id']:"-1";
if ( isset($v['name_pol3_url'])) $data['region3'][$v['name_pol1_url']][$v['name_pol2_url']][$v['name_pol3_url']] = $v['name_pol3'];$data['region3_contID'][$v['name_pol1_url']][$v['name_pol2_url']][$v['name_pol3_url']] =$v['rel_cont3_id']>0?$v['rel_cont3_id']:"-1";
}
}
}
}
}
return($data);
}


public function save_grape_act() {
$Content_cache_model = new Content_cache_model();
$data = array();
$data['save'] = "";
$dataVal['save'] = "";
$builder = $this->db->table('wr_grapes');
$builder->select( "wr_grapes.ID AS grapeID, grape");
$builder->distinct();
$builder->where( 'wr_product.online', 1);
$builder->join('wr_grapes2wine', 'wr_grapes.ID =wr_grapes2wine.grape_ID');
$builder->join('wr_product', 'wr_grapes2wine.product_ID =wr_product.ID');
$builder->orderBy("grape", "asc"); 
$query = $builder->get();

if ( count($query->getResult('array')) > 0 ) {
foreach ( $query->getResult('array')as $row_grape ) {
$builder = $this->db->table('wr_grapes2wine');
$builder->select( "wr_product.ID ");
$builder->where( 'wr_grapes2wine.grape_ID', $row_grape['grapeID']);
$builder->where( 'wr_product.online', 1);
$builder->join('wr_product', 'wr_grapes2wine.product_ID =wr_product.ID');
$q_no = $builder->get();
if ( count($q_no->getResult('array')) > 0 ) { $_ag = count($q_no->getResult('array')); }
else {$_ag = 0; }
$data['save'] .= '<option value="'.$row_grape['grapeID'].'">'.$row_grape['grape'].' ('.$_ag.')</option>';
$resID[$row_grape['grapeID']] = $_ag;
$resGrape[$row_grape['grapeID']] = $row_grape['grape'];
}
$d['value'] =  $data['save'];
$Content_cache_model->save_CI4($d, 409);
/*
arsort($resID);
foreach ( $resID as $k => $v ) {$dataVal['save'] .= '<option value="'.$k.'">'.$resGrape[$k].' ('.$v.')</option>';}
$d['value'] =  $dataVal['save'];
$Content_cache_model->save_CI4($d, 410);  
*/
}
}

}