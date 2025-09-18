<?php $App = new \App\Libraries\App();  ?>


<?php 


$width = 380;
$abfr = FCPATH."_data/".$content_id."/".$slice_id."/".$image_id."/".$image;
if (file_exists( $abfr )) {
list($width, $height, $type, $attr) = getimagesize($abfr);//echo("hei : $height <br>");
}
$widthTxt = $width -15;
if ( $float != "center") {

if ($width < 385) 	{
$styleImg = "style=\"width: {$width}px;\"";
if ($float == "left") 	$styleTxt = "style=\"width: {$widthTxt}px; margin: 5px 9px 20px 0;\"";
if ($float == "right") 	$styleTxt = "style=\"width: {$widthTxt}px; margin: 5px 0px 20px 25px;\"";
}
if ($width >= 385) 	{
$styleImg = "style=\"max-width: 385px;\"";
$styleTxt = "";
}

}

if ( $float == "center") {
$style = "";
$styleTxt = "";

}

$_path = '/_data/'.$content_id.'/'.$slice_id.'/'.$image_id.'/output/';
$_pathAbfr = '/is/htdocs/wp1112013_RXRAWWY6TZ/www/public/_data/'.$content_id.'/'.$slice_id.'/'.$image_id.'/output/';
?>
<div class="clearfix"></div>

<div class="content content-text-image">
<div class="content-text-image col-xs-12 col-sm-12">

<?php if($image != ''): ?>
<?php      

?>        
<div class="content-text-image-image-wrapper<?php echo($float!=''?' content-text-image-image-'.$float:''); ?>"<?php echo isset($styleImg)?$styleImg:"";?>  >
<a href="<?php echo $content; ?>"  target="_blank">
<img class="img-responsive" src="<?php echo $_path; ?>/<?php echo $image; ?>" alt="<?php echo $caption; ?>" />
</a>
    
<?php if($caption): ?><div <?php echo(" $styleTxt");?>>  <a href="<?php echo $content; ?>"><?php echo $caption; ?></a></div><?php endif; ?>

</div> 
    
<?php endif; ?>

</div>
<div class="clearfix"></div>
    
</div>

<style>
    
</style>    