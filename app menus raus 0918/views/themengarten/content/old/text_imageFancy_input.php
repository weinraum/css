<?php 
$App = new \App\Libraries\App();  

/* in den hidden _var felder stehen die ids des content_slice_data damit werden diese Felder aktualisiert. 
 * Fehlen diese hidden input. werden die slice_data neu angelegt.
 */

?>

<div class="d-flex flex-wrap  ">
      
<div class="d-none d-sm-block  col-sm-1 ">&nbsp;</div>
<div class="col-12 col-sm-9 ">

<h1><?php echo lang('german_lang.themengarten_content_'.$type); ?> - <?php echo lang('german_lang.themengarten_content_block'); ?></h1>


<form action="<?php echo '/'.uri_string() ?>" method="post" enctype="multipart/form-data">
<div>
<input type="hidden" name="_type" value="<?php echo(str_replace('_input.php', '', basename(__FILE__))); ?>" />
<?php 
$var = '_area'; 
$POST[$var] = isset($POST[$var])? $POST[$var]: ""; 

?>
<div class="control-group ">
<label class="control-label" for="id_<?php echo $var; ?>"><?php echo lang('german_lang.themengarten'.$var); ?></label>
<div class="controls">
<select name="<?php echo $var; ?>" size="1" id="id_<?php echo $var; ?>">
<option<?php echo $App::getSelectDATA($POST[$var], 'main'); ?>><?php echo lang('german_lang.themengarten_area_main'); ?></option>
<option<?php echo $App::getSelectDATA($POST[$var], 'sidebar'); ?>><?php echo lang('german_lang.themengarten_area_sidebar'); ?></option>
</select>
</div>
</div>
        
        
<?php $var = 'float'; ?>
<?php 
$POST[$var] = isset($POST[$var])? $POST[$var]: ""; 
$value = $POST[$var]; 

if (!is_array($value)) $value=array($value); ?>
<div class="control-group ">
<label class="control-label" for="id_<?php echo $var; ?>"><?php echo lang('german_lang.themengarten_content_text_image_'.$var); ?></label>
<div class="controls">
<input type="hidden" name="_<?php echo $var; ?>" value="<?php echo isset($value[0]['id'])?$value[0]['id']:''; ?>" />
<select name="<?php echo $var; ?>" size="1" id="id_<?php echo $var; ?>">
<option <?php echo $App::getSelectDATA($POST[$var], 'center'); ?>><?php echo lang('german_lang.themengarten_float_center'); ?></option>
<option <?php echo $App::getSelectDATA($POST[$var], 'left'); ?>><?php echo lang('german_lang.themengarten_float_left'); ?></option>
<option <?php echo $App::getSelectDATA($POST[$var], 'right'); ?>><?php echo lang('german_lang.themengarten_float_right'); ?></option>
</select>
</div>
</div>
        
<?php $var = 'image'; 

$label = lang('german_lang.themengarten_field_'.$var); 
$POST[$var] = isset($POST[$var])? $POST[$var]: ""; 
$value = $POST[$var]; 

if(isset($value) AND $value != ""): ?>
<div class="control-group">
<label class="control-label"><?php echo $label; ?></label>
<div class="controls">
    



<?php
    
/* Prüfen, ob die output - Bild Files schon angelegt sind. Wenn nicht, dann hier erledigen.
 * Die output files sind die verkleinerten Versionen des Original Bildes
 * 
 *  Pfad zum original Bild  OHNE den Bildnamen, der wird in der funktion speichern_file angehängt 

 Der Teil ist kopiert, befüllen der anderen Variablen Namen, um den Speicher - Teil austauschbau zu lassen. 
  */
    
$content_id = isset($contentId)?$contentId:"";
$slice_id = isset($sliceId)?$sliceId:"";
$image_id = $value[0]['id'];
$image = $value[0]['value'];

$path = FCPATH.'_data/'.$content_id.'/'.$slice_id.'/'.$image_id;

// Pfad zum eventuellen output - Bild. Es wird nur eins abgefragt, die anderen werden dann schon da sein ...
$_path = FCPATH.'_data/'.$content_id.'/'.$slice_id.'/'.$image_id.'/output/';
$abfr = $_path."md_".$image;

if (!is_file($abfr)) { //echo("son mist $abfr - path $path - _path $_path");
// public function speichern_file($_fileName = NULL, $path = NULL, $_path = NULL) {
$_pathOK = FALSE;
$_pathOK = $App::chkDir($_path);

if ( $_pathOK ) { // = der Pfad existiert oder wurde angelegt
if ( is_file($path.'/'.$image) ) {  // = das original Bild gibt es - NIX MACHEN! einfach nehmen im output
//echo("img $image - $path - $_path"); exit();
//$App::saveFile( $image, $path, $_path);

}
}  
}

$_path = '/_data/'.$content_id.'/'.$slice_id.'/'.$image_id.'/output/';
$_pathAbfr = FCPATH.'_data/'.$content_id.'/'.$slice_id.'/'.$image_id.'/output/';

?>
     
<div class="content-text-image-image-wrapper<?php echo( isset($float)?' content-text-image-image-'.$float:''); ?>"<?php echo  isset($styleImg)?$styleImg:'';?>  >

 <?php
 
 $abfr = $_pathAbfr."md_".$image;

 // Bild im querformat
 if (is_file($abfr)) {  ?>
    

<picture class="img-responsive">
<source media="(max-width: 680px)" srcset= "<?php echo $_path ?>xs_<?php echo $image ?>">
<source media="(min-width: 681px) AND (max-width: 1023px)" srcset= "<?php echo $_path ?>md_<?php echo $image ?>">
<source media="(min-width: 1024px)" srcset= "<?php echo $_path ?>lg_<?php echo $image ?>">
<img src="<?php echo $_path?>md_<?php echo $image; ?>" alt="kleines Winzer Bild">
</picture>  
    
    
 <?php }

// Bild im hochformat
 if (!is_file($abfr)) { ?>

<picture class="img-responsive">
<source media="(max-width: 680px)" srcset= "<?php echo $_path ?>hxs_<?php echo $image ?>">
<source media="(min-width: 681px)" srcset= "<?php echo $_path ?>hmd_<?php echo $image ?>">
<img src="<?php echo $_path?>hmd_<?php echo $image; ?>" alt="kleines Winzer Bild">
</picture> 
    
    
 <?php    } ?>







<label class="control-label checkbox" for="id__delete<?php echo $var; ?>">

<input type="hidden" name="_name_<?php echo $var; ?>" value="<?php echo $value[0]['value']; ?>" /> <!-- name (file -handling) und id des Bildes (db handling) ! -->
<input type="hidden" name="_<?php echo $var; ?>" value="<?php echo isset($value[0]['id'])?$value[0]['id']:''; ?>" /> 

<input type="hidden" name="_delete<?php echo $var; ?>" value="0" />
<input type="checkbox" id="id__delete<?php echo $var; ?>" name="_delete<?php echo $var; ?>" value="1" />
<?php echo lang('german_lang.themengarten_field_delete_image'); ?>
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
<!-- <div class="uneditable-input span5"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new"><?php echo lang('german_lang.action_file_select'); ?></span><span class="fileupload-exists"><?php echo lang('german_lang.action_file_change'); ?></span><input type="file" name="<?php echo $var; ?>" class="input-block-level" /></span><a href="#" class="btn fileupload-exists" data-dismiss="fileupload"><?php echo lang('german_lang.action_file_remove'); ?></a> -->
<input type="file" accept="image/*" name="<?php echo $var; ?>" class="input-block-level" />
</div>
</div>
</div>
</div>
        
             
        
<?php $var = 'caption'; ?>
<?php 
$POST[$var] = isset($POST[$var])? $POST[$var]: ""; 
$value = $POST[$var]; 
//echo("val $value");
if (!is_array($value)) $value=array($value); ?>
<div class="control-group ">
<label class="control-label" for="id_<?php echo $var; ?>"><?php echo lang('german_lang.themengarten_content_text_image_'.$var); ?></label>
<div class="controls">
<input type="hidden" name="_<?php echo $var; ?>" value="<?php echo isset($value[0]['id'])?$value[0]['id']:''; ?>" />
<input type="text" name="<?php echo $var; ?>" id="id_<?php echo $var; ?>" class="input-block-level" value="<?php echo isset($value[0]['value'])?$value[0]['value']:''; ?>" />
</div>
</div>
        
        
<?php $var = 'content'; ?>
<?php 
$POST[$var] = isset($POST[$var])? $POST[$var]: ""; 
$value = $POST[$var]; 


if (isset($value) ) { ?>
<div class="control-group">
<label class="control-label" for="id_<?php echo $var; ?>"><?php echo lang('german_lang.themengarten_content_text_image_'.$var); ?></label>
<div class="controls">
<input type="hidden" name="_<?php echo $var; ?>" value="<?php echo isset($value[0]['id'])?$value[0]['id']:""; ?>" />

<?php echo $App::getEditor($var, isset($value[0]['value'])? $value[0]['value']: ""); ?>
</div>
</div>
<?php } ?> 
<div class="control-group">
<label class="control-label">&nbsp;</label>
<div class="controls">
<button type="submit" class="btn"><?php echo lang('german_lang.submit'); ?></button>
<a href="<?php echo $backUrl; ?>" class="btn btn-link back"><?php echo lang('german_lang.back'); ?></a>
</div>
</div>

    
</div>

</div>
</form>

</div>
<div class="clearfix"></div>


<style>
.btn { background-color: #a9b622;}
textarea { width: 100%;}
input  { width: 100%;}
</style>
