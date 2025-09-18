<?php $App = new \App\Libraries\App();  ?>

<div class="d-flex flex-wrap  top-desk ">
      
<div class="d-none d-sm-block  col-sm-1 ">&nbsp;</div>
<div class="col-12 col-sm-9 ">

<h1><?php echo lang('german_lang.themengarten_content_'.$type); ?> - <?php echo lang('german_lang.themengarten_content_block'); ?></h1>


<form action="<?php echo current_url(); ?>" method="post">
<div>
<input type="hidden" name="_type" value="<?php echo(str_replace('_input.php', '', basename(__FILE__))); ?>" />
<?php $var = '_area'; 
$POST[$var] = isset($POST[$var])? $POST[$var]: ""; 

?>
<div class="control-group<?php echo $App::hasError($var); ?>">
<label class="control-label" for="id_<?php echo $var; ?>"><?php echo lang('german_lang.themengarten'.$var); ?></label>
<div class="controls">
<select name="<?php echo $var; ?>" size="1" id="id_<?php echo $var; ?>">
<option<?php echo $App::getSelectDATA($POST[$var], 'main'); ?>><?php echo lang('german_lang.themengarten_area_main'); ?></option>
<option<?php echo $App::getSelectDATA($POST[$var], 'sidebar'); ?>><?php echo lang('german_lang.themengarten_area_sidebar'); ?></option>
</select>
</div>
</div>

<?php 
$var = 'content';
$POST[$var] = isset($POST[$var])? $POST[$var]: ""; 
$value = $POST[$var]; 

if (!is_array($value))  { $value=array($value); }
?>

<div class="control-group">
<label class="control-label" for="id_<?php echo $var; ?>">Link -  statt "weiter": <?php echo lang('german_lang.themengarten_content_text_'.$var); ?></label>
<div class="controls">
<input type="hidden" name="_<?php echo $var; ?>" value="<?php echo isset($value[0]['id'])?$value[0]['id']:''; ?>" />
<?php echo $App::getEditor($var, isset($value[0]['value'])?$value[0]['value']:''); ?>
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


<script type="text/javascript">

    $('.textarea').wysihtml5();
    
    </script>