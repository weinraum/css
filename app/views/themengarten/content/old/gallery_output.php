<?php $App = new \App\Libraries\App();  ?>



<div class="ads-row col-xs-12 col-sm-12 ">




<?php 

$pic = 0;
$var = 'file'; 
if(is_array($$var)) {
$anz = count($$var);    
if ( $anz == 1 ) {$_col = "col-sm-12 ONE "; }
if ( $anz == 2 ) {$_col = "col-sm-6 TWO "; }
if ( $anz == 3 ) {$_col = "col-sm-4 THREE "; }

foreach($$var as $k => $v) {

$pic =  substr_replace($file[$k], '', -4, 4) ;      

$agh = htmlspecialchars($headline[$k]);
$agt = $text[$k];

//if ( $pic == "mg_198_814_3912__pilsen-9330") echo ("pic {$data[$k]['image']}");
?>

<div class="visible-xs-block  col-xs-12">
<div class="  item _<?php echo $slice_id ?> pic_<?php echo $pic ?>  <?php echo($k ==0?' active':''); ?>">
      
<a href="<?php echo isset($link[$k])?$link[$k]:"" ?>" alt="<?php echo $agh ?>"   > 
<img  class="img-responsive"  src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'_'.$slice_id.'_'.${$var.'_id'}[$k].'__'.$v; ?>" alt="" id="<?php echo isset($id)?$id:"" ?>" >
</a>
                
<div class="carousel-caption pic_<?php echo $pic ?>">
<h3><?php echo $agh ?></h3>
<p><?php echo $agt ?></p>
</div>
</div>
</div>

<div class="visible-sm-block visible-md-block visible-lg-block  <?php echo isset($_col)?$_col:""   ?>">
<div class="  item _<?php echo $slice_id ?> pic_<?php echo $pic ?>  <?php echo($k ==0?' active':''); ?>">
      
  <a href="<?php echo isset($link[$k])?$link[$k]:"" ?>" alt="<?php echo isset($agh)?$agh:"" ?>"   >     
<img  class="img-responsive"    src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'_'.$slice_id.'_'.${$var.'_id'}[$k].'__'.$v; ?>"alt="" id="<?php echo isset($id)?$id:""  ?>" >
</a>
                
<div class="carousel-caption pic_<?php echo $pic ?>">
<h3><?php echo $agh?></h3>
<p><?php echo $agt ?></p>
</div>
</div>
</div>


<div class="visible-xs-block clearer"><p><br><br><br></p></div>  

               
 <?php      }  ?>

<div class="visible-xs-block clearer"><p><br><br><br></p></div>  

<?php }   ?>
            
 
        
</div>    
    
     
 <style>   
div.bs-example div.menue {
        margin-top: 0px; 

 background:   #EBEACB;
 height: 75px;
}    
    
    
a {
    transition: all 150ms ease 0s;
}

</style>