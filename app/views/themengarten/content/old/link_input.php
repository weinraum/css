<?php $App = new \App\Libraries\App();  ?>

<div class="d-flex flex-wrap  top-desk ">
<div class="d-none d-sm-block  col-sm-1 ">&nbsp;</div>
<div class="col-12 col-sm-9 ">

<h1><?php echo lang('german_lang.themengarten_content_'.$type); ?> - <?php echo lang('german_lang.themengarten_content_block'); ?></h1>


<form action="<?php echo current_url(); ?>" method="post" enctype="multipart/form-data">
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
$var = 'float'; 
$POST[$var] = isset($POST[$var])? $POST[$var]: ""; 
$value = $POST[$var]; 

if (!is_array($value)) $value=array($value); ?>

<div class="control-group<?php echo $App::hasError($var); ?>">
<label class="control-label" for="id_<?php echo $var; ?>"><?php echo lang('german_lang.themengarten_content_text_image_'.$var); ?></label>
<div class="controls">
<input type="hidden" name="_<?php echo $var; ?>" value="<?php echo isset($value[0]['id'])?$value[0]['id']:''; ?>" />
<select name="<?php echo $var; ?>" size="1" id="id_<?php echo $var; ?>">
<option<?php echo $App::getSelectDATA($POST[$var], 'center'); ?>><?php echo lang('german_lang.themengarten_float_center'); ?></option>
<option<?php echo $App::getSelectDATA($POST[$var], 'left'); ?>><?php echo lang('german_lang.themengarten_float_left'); ?></option>
<option<?php echo $App::getSelectDATA($POST[$var], 'right'); ?>><?php echo lang('german_lang.themengarten_float_right'); ?></option>
</select>
</div>
</div>
        
<?php $var = 'image'; 

if ( isset($_area) AND $_area=='sidebar') {
    $imageType = 'si2';
}
else {
if ( isset($_area) AND $float != "center") { // der Auswahl - Punkt im Menü ist leer. Hier dann "mittig" oder ähnliches einfügen
$imageType = 'mi2';
}
if ( isset($_area) AND $float == "center") { 
$imageType = 'mg';
}
}


$label = lang('german_lang.themengarten_field_'.$var); 

$POST[$var] = isset($POST[$var])? $POST[$var]: ""; 
$value = $POST[$var]; 

if($value != "" ): ?>
<div class="control-group">
<label class="control-label"><?php echo $label; ?></label>
<div class="controls">
<img class="img-responsive" src="/_image/<?php echo htmlspecialchars($imageType.'_'.$contentId.'_'.$sliceId.'_'.isset($value[0]['id'])?$value[0]['id']:''.'__'.isset($value[0]['value'])?$value[0]['value']:'') ?>" alt="" />
<label class="control-label checkbox" for="id__delete<?php echo $var; ?>">

 <input type="hidden" name="_<?php echo $var; ?>" value="<?php echo isset($value[0]['id'])?$value[0]['id']:''; ?>" /> <!-- wichtig: übergibt die id vom Bild ! -->

<input type="hidden" name="_delete<?php echo $var; ?>" value="0" />
<input type="checkbox" id="id__delete<?php echo $var; ?>" name="_delete<?php echo $var; ?>" value="1" />
<?php echo lang('german_lang.themengarten_field__delete'.$var); ?>
</label>
</div>
</div>
<?php $label = ''; ?>
<?php endif; ?>
<div class="control-group">
<?php if($label!=''): ?>
<label class="control-label"><?php echo $label; ?></label>
<?php endif; ?>
<div class="controls">
<div class="fileupload fileupload-new" data-provides="fileupload">
<div class="input-append">
<input type="file" accept="image/*" name="<?php echo $var; ?>" class="input-block-level" />
</div>
</div>
</div>
</div>
        
             
        
<?php 

/* entspricht input text-image */

$var = 'caption'; ?>
<?php 
$POST[$var] = isset($POST[$var])? $POST[$var]: ""; 
$value = $POST[$var]; 

if (!is_array($value)) $value=array($value); ?>
<div class="control-group<?php echo $App::hasError($var); ?>">
<label class="control-label" for="id_<?php echo $var; ?>"><?php echo lang('german_lang.themengarten_content_text_image_'.$var); ?></label>
<div class="controls">
<input type="hidden" name="_<?php echo $var; ?>" value="<?php echo isset($value[0]['id'])?$value[0]['id']:''; ?>" />
<input type="text" name="<?php echo $var; ?>" id="id_<?php echo $var; ?>" class="input-block-level" value="<?php echo htmlspecialchars(isset($value[0]['value'])?$value[0]['value']:''); ?>" />
</div>
</div>

<?php $var = 'content'; 
$POST[$var] = isset($POST[$var])? $POST[$var]: ""; 
$value = $POST[$var]; 

if (!is_array($value)) $value=array($value); ?>

<div class="control-group">
<label class="control-label" for="id_<?php echo $var; ?>">Link, der dem Bild hinterlegt ist</label>
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

        