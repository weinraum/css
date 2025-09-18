<?php $App = new \App\Libraries\App();  ?>
<link rel='stylesheet' href='/_/css/admin_themen.css' type='text/css' media='all' />

<div class="d-flex flex-wrap ">   
<div class="d-none d-sm-block  col-sm-1 ">&nbsp;</div>
<div class="col-12 col-sm-9 ">

<h1><?php echo lang('german_lang.themengarten_content_'.$type); ?> - <?php echo lang('german_lang.themengarten_content_block'); ?></h1>      
<form action="/<?php echo uri_string() ?>" method="post">
<?= csrf_field() ?>
<div>
<input type="hidden" name="_type" value="<?php echo(str_replace('_input.php', '', basename(__FILE__))); ?>" />
<?php $var = 'content'; 
$POST[$var] = isset($POST[$var])? $POST[$var]: ""; 
$value = $POST[$var]; 
if (!is_array($value)) $value=array($value); ?>
<div class="control-group">
<div class="controls">
<input type="hidden" name="_<?php echo $var; ?>" value="<?php echo isset($value[0]['id'])?$value[0]['id']:''; ?>" />
<?php echo $App::getEditor($var, isset($value[0]['value'])?$value[0]['value']:''); ?>
</div>
</div>
<?php $var = 'testimonial';
$POST[$var] = isset($POST[$var])? $POST[$var]: ""; 
$value = $POST[$var]; 
if (!is_array($value)) $value=array($value); ?>
<div class="control-group">
<label class="control-label" for="id_<?php echo $var; ?>"><?php echo lang('german_lang.themengarten_content_quote_'.$var); ?></label>
<div class="controls">
<input type="hidden" name="_<?php echo $var; ?>" value="<?php echo isset($value[0]['id'])?$value[0]['id']:''; ?>" />
<input type="text" name="<?php echo $var; ?>" id="id_<?php echo $var; ?>" class="input-block-level" value="<?php echo htmlspecialchars(isset($value[0]['value'])?$value[0]['value']:''); ?>" />
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