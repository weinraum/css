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

$var = 'left'; 
if(is_array($$var)) {
foreach($$var as $k => $v) {
$d = array();
$d['left'] = $left[$k];
$d['right'] = $right[$k];
$data[] = $d;
}
}
elseif (is_string($$var)) {
$d = array();
$d['left'] = $left;
$d['right'] = $right;
$data[] = $d;
}
?>
<?php if( isset($left) AND is_array($left) AND sizeof($left) > 0): ?>
<div class="content col-xs-12">
<?php foreach($data as $k => $v): ?>
<div class="content-2col-left col-xs-4 {leftalign}">
<?php echo htmlspecialchars($v['left']); ?>
</div>
<div class="content-2col-right  col-xs-8 {rightalign}">
<?php echo nl2br($v['right']); ?>
</div>
<div class="clearer"></div>
<?php endforeach; ?>
</div>
<div class="clearer"></div>

<?php endif; ?>
