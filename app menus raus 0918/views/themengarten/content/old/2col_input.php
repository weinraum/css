<?php $App = new \App\Libraries\App();  ?>

    
<?php
if (!function_exists('colTemplate')) {
function colTemplate($_data = NULL, $_index = NULL, $_length = NULL) {
$html = '';
$ids = array();
$prefix = '';
if ($_data === NULL) $prefix = '_';

if ( $_index != NULL ) {

$var = 'left';
$html .= '<div class="control-group col-4">'.PHP_EOL;
$html .= '  <label class="control-label">'.sprintf(lang('german_lang.themengarten_content_2col_'.$var), ($_data===NULL?'':$_index+1)).'</label>'.PHP_EOL;
$html .= '  <div class="controls">'.PHP_EOL;
$html .= '      <input type="hidden" name="_'.$prefix.$var.'['.$_index.']" value="'.$_data[$var][$_index]['id'].'" />'.PHP_EOL;
$html .= '      <textarea name="'.$prefix.$var.'['.$_index.']" class="input-block-level">'.htmlspecialchars($_data[$var][$_index]['value']).'</textarea>'.PHP_EOL;
$html .= '  </div>'.PHP_EOL;
$html .= '</div>'.PHP_EOL;
if ($_data[$var][$_index]['id']) $ids[] = $_data[$var][$_index]['id'];

$var = 'right';
$html .= '<div class="control-group col-4">'.PHP_EOL;
$html .= '  <label class="control-label">'.sprintf(lang('german_lang.themengarten_content_2col_'.$var), ($_data===NULL?'':$_index+1)).'</label>'.PHP_EOL;
$html .= '  <div class="controls">'.PHP_EOL;
$html .= '      <input type="hidden" name="_'.$prefix.$var.'['.$_index.']" value="'.$_data[$var][$_index]['id'].'" />'.PHP_EOL;
$html .= '      <textarea name="'.$prefix.$var.'['.$_index.']" class="input-block-level">'.htmlspecialchars($_data[$var][$_index]['value']).'</textarea>'.PHP_EOL;
$html .= '  </div>'.PHP_EOL;
$html .= '</div>'.PHP_EOL;
if ($_data[$var][$_index]['id']) $ids[] = $_data[$var][$_index]['id'];

if (sizeof($ids) > 0) {
$html .= '<div class="btn-group pull-right col-4">'.PHP_EOL;
$html .= '  <a href="'.($_index==0?'#':current_url().'/up/'.implode('_', $ids)).'" class="btn'.($_index==0?' disabled':'').'"><i class="icon-arrow-up" title="'.lang('german_lang.action_up').'"></i></a>'.PHP_EOL;
$html .= '  <a href="'.($_index==$_length-1?'#':current_url().'/down/'.implode('_', $ids)).'" class="btn'.($_index==$_length-1?' disabled':'').'"><i class="icon-arrow-down" title="'.lang('german_lang.action_down').'"></i></a>'.PHP_EOL;
$html .= '  <a href="'.current_url().'/delete/'.implode('_', $ids).'" class="btn confirm-modal"><i class="icon-remove" title="'.lang('german_lang.action_delete').'"></i></a>'.PHP_EOL;
$html .= '</div>'.PHP_EOL;
$html .= '<div class="clearfix"></div>'.PHP_EOL;
}

$html .= '<hr/>'.PHP_EOL;

return $html;
}
}
}
?>

<div class="d-flex flex-wrap ">
      
<div class="d-none d-sm-block  col-sm-1 ">&nbsp;</div>
<div class="col-12 col-sm-9 ">

<h1><?php echo lang('german_lang.themengarten_content_'.$type); ?> - <?php echo lang('german_lang.themengarten_content_block'); ?></h1>


<form action="<?php echo '/'.uri_string() ?>" method="post">
<div>
<input type="hidden" name="_type" value="<?php echo(str_replace('_input.php', '', basename(__FILE__))); ?>" />
<?php 
$var = '_area'; 
$POST[$var] = isset($POST[$var])? $POST[$var]: ""; ?>
<div class="control-group">
<label class="control-label" for="id_<?php echo $var; ?>"><?php echo lang('german_lang.themengarten_'.$var); ?></label>
<div class="controls">
<select name="<?php echo $var; ?>" size="1" id="id_<?php echo $var; ?>">
<option<?php echo $App::getSelectDATA($POST[$var], 'main'); ?>><?php echo lang('german_lang.themengarten_area_main'); ?></option>
<option<?php echo $App::getSelectDATA($POST[$var], 'sidebar'); ?>><?php echo lang('german_lang.themengarten_area_sidebar'); ?></option>
</select>
</div>
</div>
<hr/>
<?php 
$var = 'leftalign'; 
$POST[$var] = isset($POST[$var])? $POST[$var]: ""; 
$value = $POST[$var]; 
if (!is_array($value)) $value=array($value); ?>
<div class="control-group">
<label class="control-label" for="id_<?php echo $var; ?>"><?php echo lang('german_lang.themengarten_content_2col_'.$var); ?></label>
<div class="controls">
<input type="hidden" name="_<?php echo $var; ?>" value="<?php echo isset($value[0]['id'])?$value[0]['id']:''; ?>" />
<select name="<?php echo $var; ?>" size="1" id="id_<?php echo $var; ?>">
<option<?php echo $App::getSelectDATA($POST[$var], 'alignleft'); ?>><?php echo lang('german_lang.themengarten_align_left'); ?></option>
<option<?php echo $App::getSelectDATA($POST[$var], ''); ?>><?php echo lang('german_lang.themengarten_align_center'); ?></option>
<option<?php echo $App::getSelectDATA($POST[$var], 'alignright'); ?>><?php echo lang('german_lang.themengarten_align_right'); ?></option>
</select>
</div>
</div>
<?php 
$var = 'rightalign'; 
$POST[$var] = isset($POST[$var])? $POST[$var]: ""; 

$value = $POST[$var]; 
if (!is_array($value)) $value=array($value); ?>
<div class="control-group">
<label class="control-label" for="id_<?php echo $var; ?>"><?php echo lang('german_lang.themengarten_content_2col_'.$var); ?></label>
<div class="controls">
<input type="hidden" name="_<?php echo $var; ?>" value="<?php echo isset($value[0]['id'])?$value[0]['id']:''; ?>" />
<select name="<?php echo $var; ?>" size="1" id="id_<?php echo $var; ?>">
<option<?php echo $App::getSelectDATA($POST[$var], 'alignleft'); ?>><?php echo lang('german_lang.themengarten_align_left'); ?></option>
<option<?php echo $App::getSelectDATA($POST[$var], ''); ?>><?php echo lang('german_lang.themengarten_align_center'); ?></option>
<option<?php echo $App::getSelectDATA($POST[$var], 'alignright'); ?>><?php echo lang('german_lang.themengarten_align_right'); ?></option>
</select>
</div>
</div>
<hr/>
<?php 
$var = 'left'; 
$POST[$var] = isset($POST[$var])? $POST[$var]: ""; 

if(is_array($POST[$var])) {
$var1 = 'left'; $data[$var1] = (!is_array($POST[$var1])?array($POST[$var1]):$POST[$var1]);
$var1 = 'right'; $data[$var1] = (!is_array($POST[$var1])?array($POST[$var1]):$POST[$var1]);
foreach($POST[$var] as $k => $file) {
echo colTemplate($data, $k, sizeof($POST[$var]));
}
}
?>


<div class="dn" id="template">
<?php echo colTemplate(); ?>
</div>
<div class="control-group">
<div class="controls">
<a class="btn data-add"><i class="icon-plus"></i> <?php echo lang('german_lang.themengarten_content_2col_add'); ?></a>
<script type="text/javascript">
$(document).ready(function() {
$('.data-add').on('click', function() {
elm = $('#template').clone().removeAttr('id').removeClass('dn');
$(elm).find('[name^=_]').each(function() {
$(this).attr('name', $(this).attr('name').substr(1));
});
$('#template').before(elm);
});
});
</script>
</div>
</div>
<div class="control-group">
<label class="control-label">&nbsp;</label>
<div class="controls">
<button type="submit" class="btn"><?php echo lang('german_lang.submit'); ?></button>
<a href="<?php echo $backUrl; ?>" class="btn btn-link back"><?php echo lang('german_lang.back'); ?></a>
</div>
</div>
</div>
</form>
</div>

<style>
.btn { background-color: #a9b622;}

.dn { display: none; }

.input-append .uneditable-input[class*="span"]  {
display: inline-block;
width: 400px;
height: 30px;
padding: 4px 6px;
color: #999;
cursor: not-allowed;
background-color: #FCFCFC;
border-color: #CCC;
box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.024) inset;
line-height: 20px;
border: 1px solid #CCC;
}
.form-horizontal .control-label {
float: left;
width: 115px;
padding-top: 5px;
text-align: right;
margin: 0 15px 0 0;
}
.form-horizontal .control-group {
margin-bottom: 20px;
}
label {
font-weight: 300;
}

</style>