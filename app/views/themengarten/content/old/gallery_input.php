<?php $App = new \App\Libraries\App();  


if (!function_exists('imagesTemplate')) {
function imagesTemplate($_data = NULL, $_index = NULL, $_length = NULL) {
$html = '';
$ids = array();
$prefix = '';
if ($_data === NULL) $prefix = '_';

$var = 'file';
if (isset($_data[$var][$_index])) {
$html .= '<div class="control-group">'.PHP_EOL;
$html .= '  <label class="control-label">&nbsp;</label>'.PHP_EOL;
$html .= '  <div class="controls">'.PHP_EOL;
$html .= '      <img src="/_image/'.$_data[$var][$_index]['filename'].'" alt="" />'.PHP_EOL;
$html .= '  </div>'.PHP_EOL;
$html .= '</div>'.PHP_EOL;
}
else {
$html .= '<div class="control-group">'.PHP_EOL;
$html .= '  <label class="control-label">'.sprintf(lang('german_lang.themengarten_content_gallery_'.$var), ($_data===NULL?'':$_index+1)).'</label>'.PHP_EOL;
$html .= '  <div class="controls">'.PHP_EOL;
$html .= '      <div class="fileupload fileupload-new" data-provides="fileupload">'.PHP_EOL;
$html .= '          <div class="input-append">'.PHP_EOL;
$html .= '              <input type="hidden" name="_'.$var.'['.$_index.']" value="'.$_data[$var][$_index]['id'].'" />'.PHP_EOL;
$html .= '              <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div>'.PHP_EOL;
$html .= '              <span class="btn ">'.PHP_EOL;
//$html .= '                  <span class="fileupload-new">'.h('action_file_select').'</span><span class="fileupload-exists">'.h('action_file_change').'</span>'.PHP_EOL;
//$html .= '                  <span class="fileupload-new">'.h('action_file_select').'</span><span class="fileupload-exists">'.h('action_file_change').'</span>'.PHP_EOL;
$html .= '                  <input type="hidden" name="'.$prefix.$var.'['.$_index.']" value="" />'.PHP_EOL;
$html .= '                  <input type="file" name="'.$prefix.$var.'['.$_index.']" class="input-block-level"  />'.PHP_EOL;
$html .= '              </span>'.PHP_EOL;
$html .= '              <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">'.lang('german_lang.action_file_remove').'</a>'.PHP_EOL;
$html .= '          </div>'.PHP_EOL;
$html .= '      </div>'.PHP_EOL;
$html .= '  </div>'.PHP_EOL;
$html .= '</div>'.PHP_EOL;
}
if ($_data[$var][$_index]['id']) $ids[] = $_data[$var][$_index]['id'];

$var = 'headline';
$html .= '<div class="control-group">'.PHP_EOL;
$html .= '  <label class="control-label">'.sprintf(lang('german_lang.themengarten_content_gallery_'.$var), ($_data===NULL?'':$_index+1)).'</label>'.PHP_EOL;
$html .= '  <div class="controls">'.PHP_EOL;
$html .= '      <input type="hidden" name="_'.$prefix.$var.'['.$_index.']" value="'.$_data[$var][$_index]['id'].'" />'.PHP_EOL;
$html .= '      <input type="text" name="'.$prefix.$var.'['.$_index.']" class="input-block-level" value="'.htmlspecialchars($_data[$var][$_index]['value']).'" />'.PHP_EOL;
$html .= '  </div>'.PHP_EOL;
$html .= '</div>'.PHP_EOL;
if ($_data[$var][$_index]['id']) $ids[] = $_data[$var][$_index]['id'];

$var = 'text';
$html .= '<div class="control-group">'.PHP_EOL;
$html .= '  <label class="control-label">'.sprintf(lang('german_lang.themengarten_content_gallery_'.$var), ($_data===NULL?'':$_index+1)).'</label>'.PHP_EOL;
$html .= '  <div class="controls">'.PHP_EOL;
$html .= '      <input type="hidden" name="_'.$prefix.$var.'['.$_index.']" value="'.$_data[$var][$_index]['id'].'" />'.PHP_EOL;
$html .= '      <textarea name="'.$prefix.$var.'['.$_index.']" class="input-block-level">'.htmlspecialchars($_data[$var][$_index]['value']).'</textarea>'.PHP_EOL;
$html .= '  </div>'.PHP_EOL;
$html .= '</div>'.PHP_EOL;
if ($_data[$var][$_index]['id']) $ids[] = $_data[$var][$_index]['id'];


$var = 'weiID';
$html .= '<div class="control-group">'.PHP_EOL;
$html .= '  <label class="control-label">'.sprintf(lang('german_lang.themengarten_content_gallery_'.$var), ($_data===NULL?'':$_index+1)).'</label>'.PHP_EOL;
$html .= '  <div class="controls">'.PHP_EOL;
$html .= '      <input type="hidden" name="_'.$prefix.$var.'['.$_index.']" value="'.$_data[$var][$_index]['id'].'" />'.PHP_EOL;
$html .= '      <input type="text" name="'.$prefix.$var.'['.$_index.']" class="input-block-level" value="'.htmlspecialchars($_data[$var][$_index]['value']).'" />'.PHP_EOL;
$html .= '  </div>'.PHP_EOL;
$html .= '</div>'.PHP_EOL;
if ($_data[$var][$_index]['id']) $ids[] = $_data[$var][$_index]['id'];


$var = 'packID';
$html .= '<div class="control-group">'.PHP_EOL;
$html .= '  <label class="control-label">'.sprintf(lang('german_lang.themengarten_content_gallery_'.$var), ($_data===NULL?'':$_index+1)).'</label>'.PHP_EOL;
$html .= '  <div class="controls">'.PHP_EOL;
$html .= '      <input type="hidden" name="_'.$prefix.$var.'['.$_index.']" value="'.$_data[$var][$_index]['id'].'" />'.PHP_EOL;
$html .= '      <input type="text" name="'.$prefix.$var.'['.$_index.']" class="input-block-level" value="'.htmlspecialchars($_data[$var][$_index]['value']).'" />'.$_data[$var][$_index]['value'].PHP_EOL;
$html .= '  </div>'.PHP_EOL;
$html .= '</div>'.PHP_EOL;
if ($_data[$var][$_index]['id']) $ids[] = $_data[$var][$_index]['id'];


$var = 'link';
$html .= '<div class="control-group">'.PHP_EOL;
$html .= '  <label class="control-label">'.sprintf(lang('german_lang.themengarten_content_gallery_'.$var), ($_data===NULL?'':$_index+1)).'</label>'.PHP_EOL;
$html .= '  <div class="controls">'.PHP_EOL;
$html .= '      <input type="hidden" name="_'.$prefix.$var.'['.$_index.']" value="'.$_data[$var][$_index]['id'].'" />'.PHP_EOL;
$html .= '      <input type="text" name="'.$prefix.$var.'['.$_index.']" class="input-block-level" value="'.htmlspecialchars($_data[$var][$_index]['value']).'" />'.PHP_EOL;
$html .= '  </div>'.PHP_EOL;
$html .= '</div>'.PHP_EOL;
if ($_data[$var][$_index]['id']) $ids[] = $_data[$var][$_index]['id'];


if (sizeof($ids) > 0) {
$html .= '<div class="btn-group pull-right">'.PHP_EOL;
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
?>

<div class="d-flex flex-wrap  top-desk ">
      
<div class="d-none d-sm-block  col-sm-1 ">&nbsp;</div>
<div class="col-12 col-sm-9 ">
    
<h1><?php echo lang('german_lang.themengarten_content_'.$type); ?> - <?php echo lang('german_lang.themengarten_content_block'); ?></h1>


<form action="<?php echo current_url(); ?>" method="post" enctype="multipart/form-data">
<div class="form-horizontal">
<input type="hidden" name="_type" value="<?php echo(str_replace('_input.php', '', basename(__FILE__))); ?>" />
<?php 
$var = '_area'; 
$POST[$var] = isset($POST[$var])? $POST[$var]: ""; 
?>
<div class="control-group<?php echo $App::hasError($var); ?>">
<label class="control-label" for="id_<?php echo $var; ?>"><?php echo lang('german_lang.themengarten'.$var); ?></label>
<div class="controls">
<select name="<?php echo $var; ?>" size="1" for="id_<?php echo $var; ?>">
<option<?php echo $App::getSelectDATA($POST[$var], 'main'); ?>><?php echo lang('german_lang.themengarten_area_main'); ?></option>
<option<?php echo $App::getSelectDATA($POST[$var], 'sidebar'); ?>><?php echo lang('german_lang.themengarten_area_sidebar'); ?></option>
</select>
</div>
<hr/>
</div>

<?php 

$var = 'file'; 
$POST[$var] = isset($POST[$var])? $POST[$var]: ""; 

if(is_array($POST[$var])) {
$var1 = 'headline'; $data[$var1] = (!is_array($POST[$var1])?array($POST[$var1]):$POST[$var1]);
$var1 = 'text'; $data[$var1] = (!is_array($POST[$var1])?array($POST[$var1]):$POST[$var1]);
$var1 = 'file'; $data[$var1] = (!is_array($POST[$var1])?array($POST[$var1]):$POST[$var1]);
$var1 = 'weiID'; $data[$var1] = (!is_array($POST[$var1])?array($POST[$var1]):$POST[$var1]);
$var1 = 'packID'; $data[$var1] = (!is_array($POST[$var1])?array($POST[$var1]):$POST[$var1]);
$var1 = 'link'; $data[$var1] = (!is_array($POST[$var1])?array($POST[$var1]):$POST[$var1]);


foreach($POST[$var] as $k => $file) {
$data['file'][$k]['filename'] = 'prev-804_'.$POST['content_id'].'_'.$POST['slice_id'].'_'.$data['file'][$k]['id'].'__'.$data['file'][$k]['value'];
echo imagesTemplate($data, $k, sizeof($POST[$var]));
}
}
?>
<div class="dn" id="template">
<?php echo imagesTemplate(); ?>
</div>
<div class="control-group">
<div class="controls">
<a class="btn image-add"><i class="icon-plus"></i> <?php echo lang('german_lang.themengarten_content_gallery_add'); ?></a>

<script type="text/javascript">
$(document).ready(function() {
$('.image-add').on('click', function() {
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
</div>

<?php echo $App::showModalDelete(); ?>

<style>
    .dn {
    display: none;
}
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
