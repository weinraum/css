<?php $App = new \App\Libraries\App();  ?>

</div><div class="d-flex flex-wrap  top-desk ">
      
<div class="d-none d-sm-block  col-sm-1 ">&nbsp;</div>
<div class="col-12 col-sm-9 ">


<h1><?php echo lang('german_lang.warumkaufen_content'); ?> </h1>

        
<form action="<?php echo current_url(); ?>" method="post" enctype="multipart/form-data">
<div>
<input type="hidden" name="_type" value="<?php echo(str_replace('_input.php', '', basename(__FILE__))); ?>" />

         
<?php 
$var = 'context'; 
$POST[$var] = isset($POST[$var])? $POST[$var]: ""; 
$value = $POST[$var]; 

if (!is_array($value)) $value=array($value); ?>

<div class="control-group<?php echo $App::hasError($var); ?>">
<label class="control-label" for="id_<?php echo $var; ?>"><?php echo lang('german_lang.warumkaufen_text_'.$var); ?></label>
<div class="controls">
<input type="hidden" name="_<?php echo $var; ?>" value="<?php echo isset($value[0]['id'])?$value[0]['id']:''; ?>" />
<select name="<?php echo $var; ?>" size="1" id="id_<?php echo $var; ?>">
<option<?php echo $App::getSelectDATA($POST[$var], 'win'); ?>><?php echo lang('german_lang.warumkaufen_context_win'); ?></option>
<option<?php echo $App::getSelectDATA($POST[$var], 'reg'); ?>><?php echo lang('german_lang.warumkaufen_context_reg'); ?></option>
<option<?php echo $App::getSelectDATA($POST[$var], 'passend'); ?>><?php echo lang('german_lang.warumkaufen_context_pass'); ?></option>
</select>
</div>
</div>


         
<?php 
$var = 'context_img';
$POST[$var] = isset($POST[$var])? $POST[$var]: ""; 
$value = $POST[$var]; 

if (!is_array($value)) $value=array($value); ?>

<div class="control-group<?php echo $App::hasError($var); ?>">
<label class="control-label" for="id_<?php echo $var; ?>"><?php echo lang('german_lang.warumkaufen_text_'.$var); ?></label>
<div class="controls">
<input type="hidden" name="_<?php echo $var; ?>" value="<?php echo isset($value[0]['id'])?$value[0]['id']:''; ?>" />
<select name="<?php echo $var; ?>" size="1" id="id_<?php echo $var; ?>">
<option<?php echo $App::getSelectDATA($POST[$var], 'win'); ?>>Winzer</option>
<option<?php echo $App::getSelectDATA($POST[$var], 'reg'); ?>>Region</option>
<option<?php echo $App::getSelectDATA($POST[$var], 'fisch'); ?>>Passend Fisch</option>
<option<?php echo $App::getSelectDATA($POST[$var], 'fleisch'); ?>>Passend Fleisch</option>
<option<?php echo $App::getSelectDATA($POST[$var], 'steak'); ?>>Passend Steak</option>
<option<?php echo $App::getSelectDATA($POST[$var], 'kalb'); ?>>Passend Kalb</option>
<option<?php echo $App::getSelectDATA($POST[$var], 'wild'); ?>>Passend Wild</option>
<option<?php echo $App::getSelectDATA($POST[$var], 'schwein'); ?>>Passend Schwein</option>
<option<?php echo $App::getSelectDATA($POST[$var], 'lamm'); ?>>Passend Lamm</option>
<option<?php echo $App::getSelectDATA($POST[$var], 'huhn'); ?>>Passend Huhn</option>
<option<?php echo $App::getSelectDATA($POST[$var], 'pute'); ?>>Passend Pute</option>
<option<?php echo $App::getSelectDATA($POST[$var], 'kaese'); ?>>Passend Käse</option>
<option<?php echo $App::getSelectDATA($POST[$var], 'spargel'); ?>>Passend Spargel</option>
<option<?php echo $App::getSelectDATA($POST[$var], 'pasta'); ?>>Passend Pasta</option>
<option<?php echo $App::getSelectDATA($POST[$var], 'risotto'); ?>>Passend Risotto</option>
<option<?php echo $App::getSelectDATA($POST[$var], 'vegetarisch'); ?>>Passend Vegetarisch</option>
<option<?php echo $App::getSelectDATA($POST[$var], 'vegan'); ?>>Passend Vegan</option>
</select>
</div>
</div>


        




<?php $var = 'terroir'; 
$POST[$var] = isset($POST[$var])? $POST[$var]: ""; 
$value = $POST[$var]; 

if (!is_array($value)) $value=array($value); ?>

<div class="control-group">
<label class="control-label" for="id_<?php echo $var; ?>"><?php echo lang('german_lang.warumkaufen_content_'.$var); ?></label>
<div class="controls">
<input type="hidden" name="_<?php echo $var; ?>" value="<?php echo isset($value[0]['id'])?$value[0]['id']:''; ?>" />
<?php echo $App::getEditor($var, isset($value[0]['value'])?$value[0]['value']:''); ?>
</div>
</div>

                
<?php $var = 'winzer';
$POST[$var] = isset($POST[$var])? $POST[$var]: ""; 
$value = $POST[$var]; 
if (!is_array($value)) $value=array($value); ?>

<div class="control-group">
<label class="control-label" for="id_<?php echo $var; ?>"><?php echo lang('german_lang.warumkaufen_content_'.$var); ?></label>
<div class="controls">
<input type="hidden" name="_<?php echo $var; ?>" value="<?php echo isset($value[0]['id'])?$value[0]['id']:''; ?>" />
<?php echo $App::getEditor($var, isset($value[0]['value'])?$value[0]['value']:''); ?>
</div>
</div>

                
<?php $var = 'weine'; 
$POST[$var] = isset($POST[$var])? $POST[$var]: ""; 
$value = $POST[$var]; 

if (!is_array($value)) $value=array($value); ?>

<div class="control-group">
<label class="control-label" for="id_<?php echo $var; ?>"><?php echo lang('german_lang.warumkaufen_content_'.$var); ?></label>
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