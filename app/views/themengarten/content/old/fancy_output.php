<?php $App = new \App\Libraries\App();  ?>



<div class="fancy col-xs-12  ">


<p class="line">zum Vergrößern Klick aufs Bild: <span class="glyphicon glyphicon-zoom-in"></span></p>



<?php 

$pic = 0;
$var = 'file'; 
if(is_array($$var)) {
$anz = count($$var);    
if ( $anz <= 3 ) {$_col = "col-sm-12 ONE "; }
if ( $anz == 4 ) {$_col = "col-sm-6 TWO "; }
if ( $anz > 4 ) {$_col = "col-sm-4 THREE "; }

$b = 0;
$h = 0;

$ind = 0;
foreach($$var as $k => $v) {
$ind ++;

if ( $anz <= 4 ) {
$img_b_k[$ind] = $k; $img_b_v[$ind] = $v; 
}
 

if ( $anz > 4 ) {
list($width, $height, $type, $attr) = getimagesize(PICPATH."_data/".$content_id."/".$slice_id."/".${$var.'_id'}[$k]."/".$v);
if ( $width / $height > 1 ) { $b += 1; $img_b_k[$b] = $k; $img_b_v[$b] = $v;  }
if ( $width / $height < 1 ) { $h += 1; $img_h_k[$h] = $k; $img_h_v[$h] = $v; }
$img_all_k[$ind] = $k; $img_all_v[$ind] = $v; 
}
 
 

}
//echo("lala $anz");
if ( $anz < 3) {$type = 1; }

if ( $anz >= 3) {
if ( $h == 0 ) { $type = 1; }
if ( $h == 1 AND $b == 2) { $type = 2; }
if ( $h == 1 AND $b == 3) { $type = 3; }
if ( $h == 1 AND $b > 3) { $type = 4; }

if ( $h == 2 AND $b == 2) { $type = 5; }
if ( $h == 2 AND $b == 3) { $type = 6; }
if ( $h == 2 AND $b == 4) { $type = 7; }
if ( $h == 2 AND $b == 5) { $type = 8; }

if ( $h == 3 AND $b == 3) { $type = 9; }
if ( $h == 4 AND $b == 5) { $type = 10;}

//echo("anz $h , $b ty $type");
}

//echo("type $type");
if ( $type == 1) {
if ( $anz == 1) { ?>
<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy">  
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[1]].'/'.$img_b_v[1]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[1]]); ?></h2><p> <?php echo $text[$img_b_k[1]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[1]].'/'.$img_b_v[1]; ?>" alt="<?php echo $text[$img_b_k[1]]; ?>"  class="img-responsive" /></a>
</div>
<?php
}

if ( $anz == 2) { ?>
<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy">  
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[1]].'/'.$img_b_v[1]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[1]]); ?></h2><p> <?php echo $text[$img_b_k[1]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[1]].'/'.$img_b_v[1]; ?>" alt="<?php echo $text[$img_b_k[1]]; ?>"  class="img-responsive" /></a>
</div>
<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy"> 
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[2]].'/'.$img_b_v[2]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[2]]); ?></h2><p> <?php echo $text[$img_b_k[2]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[2]].'/'.$img_b_v[2]; ?>" alt="<?php echo $text[$img_b_k[2]]; ?>"  class="img-responsive" /></a>
</div>
<?php
}

if ( $anz == 3) { ?>
<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy ">  
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[1]].'/'.$img_b_v[1]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[1]]); ?></h2><p> <?php echo $text[$img_b_k[1]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[1]].'/'.$img_b_v[1]; ?>" alt="<?php echo $text[$img_b_k[1]]; ?>"  class="img-responsive" /></a>
</div>
<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy">
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[2]].'/'.$img_b_v[2]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[2]]); ?></h2><p> <?php echo $text[$img_b_k[2]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[2]].'/'.$img_b_v[2]; ?>" alt="<?php echo $text[$img_b_k[2]]; ?>"  class="img-responsive" /></a>
</div>
<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy  "> 
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[3]].'/'.$img_b_v[3]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[3]]); ?></h2><p> <?php echo $text[$img_b_k[3]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[3]].'/'.$img_b_v[3]; ?>" alt="<?php echo $text[$img_b_k[3]]; ?>"  class="img-responsive" /></a>
</div>
<?php
}


if ( $anz == 4) { ?>
<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy ">  
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[1]].'/'.$img_b_v[1]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[1]]); ?></h2><p> <?php echo $text[$img_b_k[1]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[1]].'/'.$img_b_v[1]; ?>" alt="<?php echo $text[$img_b_k[1]]; ?>"  class="img-responsive" /></a>
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[2]].'/'.$img_b_v[2]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[2]]); ?></h2><p> <?php echo $text[$img_b_k[2]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[2]].'/'.$img_b_v[2]; ?>" alt="<?php echo $text[$img_b_k[2]]; ?>"  class="img-responsive" /></a>
</div>
<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy">
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[3]].'/'.$img_b_v[3]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[3]]); ?></h2><p> <?php echo $text[$img_b_k[3]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[3]].'/'.$img_b_v[3]; ?>" alt="<?php echo $text[$img_b_k[3]]; ?>"  class="img-responsive" /></a>
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[4]].'/'.$img_b_v[4]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[4]]); ?></h2><p> <?php echo $text[$img_b_k[4]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[4]].'/'.$img_b_v[4]; ?>" alt="<?php echo $text[$img_b_k[4]]; ?>"  class="img-responsive" /></a>
</div>
<?php
}


if ( $anz == 5 OR $anz == 6 ) { ?>
<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy ">  
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[1]].'/'.$img_b_v[1]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[1]]); ?></h2><p> <?php echo $text[$img_b_k[1]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[1]].'/'.$img_b_v[1]; ?>" alt="<?php echo $text[$img_b_k[1]]; ?>"  class="img-responsive" /></a>
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[2]].'/'.$img_b_v[2]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[2]]); ?></h2><p> <?php echo $text[$img_b_k[2]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[2]].'/'.$img_b_v[2]; ?>" alt="<?php echo $text[$img_b_k[2]]; ?>"  class="img-responsive" /></a>
</div>
<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy">
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[3]].'/'.$img_b_v[3]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[3]]); ?></h2><p> <?php echo $text[$img_b_k[3]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[3]].'/'.$img_b_v[3]; ?>" alt="<?php echo $text[$img_b_k[3]]; ?>"  class="img-responsive" /></a>
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[4]].'/'.$img_b_v[4]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[4]]); ?></h2><p> <?php echo $text[$img_b_k[4]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[4]].'/'.$img_b_v[4]; ?>" alt="<?php echo $text[$img_b_k[4]]; ?>"  class="img-responsive" /></a>
</div>
<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy  right visible-md-block visible-lg-block">
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[5]].'/'.$img_b_v[5]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[5]]); ?></h2><p> <?php echo $text[$img_b_k[5]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[5]].'/'.$img_b_v[3]; ?>" alt="<?php echo $text[$img_b_k[5]]; ?>"  class="img-responsive" /></a>

<?php if ( $anz == 6 ) { ?>
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[6]].'/'.$img_b_v[6]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[6]]); ?></h2><p> <?php echo $text[$img_b_k[6]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[6]].'/'.$img_b_v[4]; ?>" alt="<?php echo $text[$img_b_k[6]]; ?>"  class="img-responsive" /></a>
<?php } ?>
</div>
<?php
}

if ( $anz > 6  ) { ?>
<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy ">  
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[1]].'/'.$img_b_v[1]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[1]]); ?></h2><p> <?php echo $text[$img_b_k[1]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[1]].'/'.$img_b_v[1]; ?>" alt="<?php echo $text[$img_b_k[1]]; ?>"  class="img-responsive" /></a>
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[2]].'/'.$img_b_v[2]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[2]]); ?></h2><p> <?php echo $text[$img_b_k[2]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[2]].'/'.$img_b_v[2]; ?>" alt="<?php echo $text[$img_b_k[2]]; ?>"  class="img-responsive" /></a>
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[3]].'/'.$img_b_v[3]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[3]]); ?></h2><p> <?php echo $text[$img_b_k[3]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[3]].'/'.$img_b_v[3]; ?>" alt="<?php echo $text[$img_b_k[3]]; ?>"  class="img-responsive" /></a>
</div>
<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy">
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[4]].'/'.$img_b_v[4]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[4]]); ?></h2><p> <?php echo $text[$img_b_k[4]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[4]].'/'.$img_b_v[4]; ?>" alt="<?php echo $text[$img_b_k[4]]; ?>"  class="img-responsive" /></a>
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[5]].'/'.$img_b_v[5]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[5]]); ?></h2><p> <?php echo $text[$img_b_k[5]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[5]].'/'.$img_b_v[5]; ?>" alt="<?php echo $text[$img_b_k[5]]; ?>"  class="img-responsive" /></a>
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[6]].'/'.$img_b_v[6]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[6]]); ?></h2><p> <?php echo $text[$img_b_k[6]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[6]].'/'.$img_b_v[6]; ?>" alt="<?php echo $text[$img_b_k[6]]; ?>"  class="img-responsive" /></a>
</div>
<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy  right visible-md-block visible-lg-block">
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[7]].'/'.$img_b_v[7]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[7]]); ?></h2><p> <?php echo $text[$img_b_k[7]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[7]].'/'.$img_b_v[7]; ?>" alt="<?php echo $text[$img_b_k[7]]; ?>"  class="img-responsive" /></a>

<?php if ( $anz > 7 ) { ?>
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[8]].'/'.$img_b_v[8]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[8]]); ?></h2><p> <?php echo $text[$img_b_k[8]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[8]].'/'.$img_b_v[8]; ?>" alt="<?php echo $text[$img_b_k[8]]; ?>"  class="img-responsive" /></a>
<?php } ?>

<?php if ( $anz > 8 ) { ?>
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[9]].'/'.$img_b_v[9]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[9]]); ?></h2><p> <?php echo $text[$img_b_k[9]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[9]].'/'.$img_b_v[9]; ?>" alt="<?php echo $text[$img_b_k[9]]; ?>"  class="img-responsive" /></a>
<?php } ?>
</div>
<?php
}


}






if ( $type == 2) {
 ?>
<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy">  
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[1]].'/'.$img_b_v[1]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[1]]); ?></h2><p> <?php echo $text[$img_b_k[1]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[1]].'/'.$img_b_v[1]; ?>" alt="<?php echo $text[$img_b_k[1]]; ?>"  class="img-responsive" /></a>
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[2]].'/'.$img_b_v[2]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[2]]); ?></h2><p> <?php echo $text[$img_b_k[2]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[2]].'/'.$img_b_v[2]; ?>" alt="<?php echo $text[$img_b_k[2]]; ?>"  class="img-responsive" /></a>
</div>

<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy">  
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_h_k[1]].'/'.$img_h_v[1]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_h_k[1]]); ?></h2><p> <?php echo $text[$img_h_k[1]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_h_k[1]].'/'.$img_h_v[1]; ?>" alt="<?php echo $text[$img_h_k[1]]; ?>"  class="img-responsive" /></a>
</div>

<?php

}




if ( $type == 3) {
 ?>
<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy">  
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[1]].'/'.$img_b_v[1]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[1]]); ?></h2><p> <?php echo $text[$img_b_k[1]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[1]].'/'.$img_b_v[1]; ?>" alt="<?php echo $text[$img_b_k[1]]; ?>"  class="img-responsive" /></a>
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[2]].'/'.$img_b_v[2]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[2]]); ?></h2><p> <?php echo $text[$img_b_k[2]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[2]].'/'.$img_b_v[2]; ?>" alt="<?php echo $text[$img_b_k[2]]; ?>"  class="img-responsive" /></a>
</div>

<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy">  
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_h_k[1]].'/'.$img_h_v[1]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_h_k[1]]); ?></h2><p> <?php echo $text[$img_h_k[1]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_h_k[1]].'/'.$img_h_v[1]; ?>" alt="<?php echo $text[$img_h_k[1]]; ?>"  class="img-responsive" /></a>
</div>
    
<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy right visible-md-block visible-lg-block">  
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[3]].'/'.$img_b_v[3]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[3]]); ?></h2><p> <?php echo $text[$img_b_k[3]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[3]].'/'.$img_b_v[3]; ?>" alt="<?php echo $text[$img_b_k[3]]; ?>"  class="img-responsive" /></a>
</div>
<?php

}



if ( $type == 4) {
 ?>
<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy">  
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_h_k[1]].'/'.$img_h_v[1]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_h_k[1]]); ?></h2><p> <?php echo $text[$img_h_k[1]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_h_k[1]].'/'.$img_h_v[1]; ?>" alt="<?php echo $text[$img_h_k[1]]; ?>"  class="img-responsive" /></a>
<?php if ( $anz > 5 ) { ?>
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[5]].'/'.$img_b_v[5]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[5]]); ?></h2><p> <?php echo $text[$img_b_k[5]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[5]].'/'.$img_b_v[5]; ?>" alt="<?php echo $text[$img_b_k[5]]; ?>"  class="img-responsive" /></a>
<?php } ?>
</div>

<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy">  
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[1]].'/'.$img_b_v[1]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[1]]); ?></h2><p> <?php echo $text[$img_b_k[1]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[1]].'/'.$img_b_v[1]; ?>" alt="<?php echo $text[$img_b_k[1]]; ?>"  class="img-responsive" /></a>
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[2]].'/'.$img_b_v[2]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[2]]); ?></h2><p> <?php echo $text[$img_b_k[2]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[2]].'/'.$img_b_v[2]; ?>" alt="<?php echo $text[$img_b_k[2]]; ?>"  class="img-responsive" /></a>
<?php if ( $anz > 6 ) { ?>
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[7]].'/'.$img_b_v[7]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[7]]); ?></h2><p> <?php echo $text[$img_b_k[7]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[7]].'/'.$img_b_v[7]; ?>" alt="<?php echo $text[$img_b_k[7]]; ?>"  class="img-responsive" /></a>
<?php } ?>
</div>
    
<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy right visible-md-block visible-lg-block">  
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[3]].'/'.$img_b_v[3]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[3]]); ?></h2><p> <?php echo $text[$img_b_k[3]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[3]].'/'.$img_b_v[3]; ?>" alt="<?php echo $text[$img_b_k[3]]; ?>"  class="img-responsive" /></a>
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[4]].'/'.$img_b_v[4]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[4]]); ?></h2><p> <?php echo $text[$img_b_k[4]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[4]].'/'.$img_b_v[4]; ?>" alt="<?php echo $text[$img_b_k[4]]; ?>"  class="img-responsive" /></a>
<?php if ( $anz > 7 ) { ?>
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[8]].'/'.$img_b_v[8]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[8]]); ?></h2><p> <?php echo $text[$img_b_k[8]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[8]].'/'.$img_b_v[8]; ?>" alt="<?php echo $text[$img_b_k[8]]; ?>"  class="img-responsive" /></a>
<?php } ?>
</div>
<?php

}


if ( $type == 5) {
 ?>
<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy">  
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_h_k[1]].'/'.$img_h_v[1]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_h_k[1]]); ?></h2><p> <?php echo $text[$img_h_k[1]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_h_k[1]].'/'.$img_h_v[1]; ?>" alt="<?php echo $text[$img_h_k[1]]; ?>"  class="img-responsive" /></a>
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[1]].'/'.$img_b_v[1]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[1]]); ?></h2><p> <?php echo $text[$img_b_k[1]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[1]].'/'.$img_b_v[1]; ?>" alt="<?php echo $text[$img_b_k[1]]; ?>"  class="img-responsive" /></a>
</div>

<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy">  
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[2]].'/'.$img_b_v[2]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[2]]); ?></h2><p> <?php echo $text[$img_b_k[2]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[2]].'/'.$img_b_v[2]; ?>" alt="<?php echo $text[$img_b_k[2]]; ?>"  class="img-responsive" /></a>
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_h_k[2]].'/'.$img_h_v[2]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_h_k[2]]); ?></h2><p> <?php echo $text[$img_h_k[2]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[2]].'/'.$img_h_v[2]; ?>" alt="<?php echo $text[$img_h_k[2]]; ?>"  class="img-responsive" /></a>
</div>
    
<?php

}


if ( $type == 6) {
 ?>
<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy">  
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_h_k[1]].'/'.$img_h_v[1]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_h_k[1]]); ?></h2><p> <?php echo $text[$img_h_k[1]]; ?></p>"><img src="<?php echo PICPATH; ?>_image/fancy-lang3_<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_h_k[1]].'/'.$img_h_v[1]; ?>" alt="<?php echo $text[$img_h_k[1]]; ?>"  class="img-responsive" /></a>

</div>

<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy">  
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[1]].'/'.$img_b_v[1]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[1]]); ?></h2><p> <?php echo $text[$img_b_k[1]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[1]].'/'.$img_b_v[1]; ?>" alt="<?php echo $text[$img_b_k[1]]; ?>"  class="img-responsive" /></a>
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[2]].'/'.$img_b_v[2]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[2]]); ?></h2><p> <?php echo $text[$img_b_k[2]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[2]].'/'.$img_b_v[2]; ?>" alt="<?php echo $text[$img_b_k[2]]; ?>"  class="img-responsive" /></a>
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[3]].'/'.$img_b_v[3]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[3]]); ?></h2><p> <?php echo $text[$img_b_k[3]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[3]].'/'.$img_b_v[3]; ?>" alt="<?php echo $text[$img_b_k[3]]; ?>"  class="img-responsive" /></a>

</div>
    
<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy right visible-md-block visible-lg-block">  
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_h_k[2]].'/'.$img_h_v[2]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_h_k[2]]); ?></h2><p> <?php echo $text[$img_h_k[2]]; ?></p>"><img src="<?php echo PICPATH; ?>_image/fancy-lang3_<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_h_k[2]].'/'.$img_h_v[2]; ?>" alt="<?php echo $text[$img_h_k[2]]; ?>"  class="img-responsive" /></a>

</div>
<?php

}


if ( $type == 7) {
 ?>
<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy">  
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_h_k[1]].'/'.$img_h_v[1]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_h_k[1]]); ?></h2><p> <?php echo $text[$img_h_k[1]]; ?></p>"><img src="<?php echo PICPATH; ?>_image/fancy-lang3_<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_h_k[1]].'/'.$img_h_v[1]; ?>" alt="<?php echo $text[$img_h_k[1]]; ?>"  class="img-responsive" /></a>

</div>

<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy">  
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[1]].'/'.$img_b_v[1]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[1]]); ?></h2><p> <?php echo $text[$img_b_k[1]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[1]].'/'.$img_b_v[1]; ?>" alt="<?php echo $text[$img_b_k[1]]; ?>"  class="img-responsive" /></a>
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[2]].'/'.$img_b_v[2]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[2]]); ?></h2><p> <?php echo $text[$img_b_k[2]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[2]].'/'.$img_b_v[2]; ?>" alt="<?php echo $text[$img_b_k[2]]; ?>"  class="img-responsive" /></a>
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[3]].'/'.$img_b_v[3]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[3]]); ?></h2><p> <?php echo $text[$img_b_k[3]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[3]].'/'.$img_b_v[3]; ?>" alt="<?php echo $text[$img_b_k[3]]; ?>"  class="img-responsive" /></a>

</div>
    
<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy right visible-md-block visible-lg-block">  
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_h_k[2]].'/'.$img_h_v[2]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_h_k[2]]); ?></h2><p> <?php echo $text[$img_h_k[2]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_h_k[2]].'/'.$img_h_v[2]; ?>" alt="<?php echo $text[$img_h_k[2]]; ?>"  class="img-responsive" /></a>
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[4]].'/'.$img_b_v[4]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[4]]); ?></h2><p> <?php echo $text[$img_b_k[4]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[4]].'/'.$img_b_v[4]; ?>" alt="<?php echo $text[$img_b_k[4]]; ?>"  class="img-responsive" /></a>

</div>
<?php

}


if ( $type == 8) {
 ?>
<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy">  
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_h_k[1]].'/'.$img_h_v[1]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_h_k[1]]); ?></h2><p> <?php echo $text[$img_h_k[1]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_h_k[1]].'/'.$img_h_v[1]; ?>" alt="<?php echo $text[$img_h_k[1]]; ?>"  class="img-responsive" /></a>
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[1]].'/'.$img_b_v[1]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[1]]); ?></h2><p> <?php echo $text[$img_b_k[1]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[1]].'/'.$img_b_v[1]; ?>" alt="<?php echo $text[$img_b_k[1]]; ?>"  class="img-responsive" /></a>

</div>

<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy">  
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[2]].'/'.$img_b_v[2]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[2]]); ?></h2><p> <?php echo $text[$img_b_k[2]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[2]].'/'.$img_b_v[2]; ?>" alt="<?php echo $text[$img_b_k[2]]; ?>"  class="img-responsive" /></a>
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[3]].'/'.$img_b_v[3]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[3]]); ?></h2><p> <?php echo $text[$img_b_k[3]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[3]].'/'.$img_b_v[3]; ?>" alt="<?php echo $text[$img_b_k[3]]; ?>"  class="img-responsive" /></a>
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[4]].'/'.$img_b_v[4]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[4]]); ?></h2><p> <?php echo $text[$img_b_k[4]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[4]].'/'.$img_b_v[4]; ?>" alt="<?php echo $text[$img_b_k[4]]; ?>"  class="img-responsive" /></a>

</div>
    
<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy right visible-md-block visible-lg-block">  
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[5]].'/'.$img_b_v[5]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[5]]); ?></h2><p> <?php echo $text[$img_b_k[5]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[5]].'/'.$img_b_v[3]; ?>" alt="<?php echo $text[$img_b_k[5]]; ?>"  class="img-responsive" /></a>
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_h_k[2]].'/'.$img_h_v[2]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_h_k[2]]); ?></h2><p> <?php echo $text[$img_h_k[2]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_h_k[2]].'/'.$img_h_v[2]; ?>" alt="<?php echo $text[$img_h_k[2]]; ?>"  class="img-responsive" /></a>

</div>
<?php

}


if ( $type == 9) {
 ?>
<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy">  
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_h_k[1]].'/'.$img_h_v[1]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_h_k[1]]); ?></h2><p> <?php echo $text[$img_h_k[1]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_h_k[1]].'/'.$img_h_v[1]; ?>" alt="<?php echo $text[$img_h_k[1]]; ?>"  class="img-responsive" /></a>
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[1]].'/'.$img_b_v[1]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[1]]); ?></h2><p> <?php echo $text[$img_b_k[1]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[1]].'/'.$img_b_v[1]; ?>" alt="<?php echo $text[$img_b_k[1]]; ?>"  class="img-responsive" /></a>
</div>

<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy">  
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[2]].'/'.$img_b_v[2]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[2]]); ?></h2><p> <?php echo $text[$img_b_k[2]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[2]].'/'.$img_b_v[2]; ?>" alt="<?php echo $text[$img_b_k[2]]; ?>"  class="img-responsive" /></a>
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_h_k[2]].'/'.$img_h_v[2]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_h_k[2]]); ?></h2><p> <?php echo $text[$img_h_k[2]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_h_k[2]].'/'.$img_h_v[2]; ?>" alt="<?php echo $text[$img_h_k[2]]; ?>"  class="img-responsive" /></a>
</div>


<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy right visible-md-block visible-lg-block">  
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[3]].'/'.$img_b_v[3]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_b_k[3]]); ?></h2><p> <?php echo $text[$img_b_k[3]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_b_k[3]].'/'.$img_b_v[3]; ?>" alt="<?php echo $text[$img_b_k[3]]; ?>"  class="img-responsive" /></a>
<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_h_k[3]].'/'.$img_h_v[3]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_h_k[3]]); ?></h2><p> <?php echo $text[$img_h_k[3]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_h_k[3]].'/'.$img_h_v[3]; ?>" alt="<?php echo $text[$img_h_k[3]]; ?>"  class="img-responsive" /></a>
</div>


<?php

}



//echo("hu");
if ( $type == 10) {
for ($_img = 1; $_img < 10;  $_img++){    
 //  echo("i $_img");
if ( $_img == 1 OR $_img == 4  OR $_img ==7 ) {
 ?>
<div class="col-xs-6 col-sm-6  col-lg-4 row-fancy">  
<?php } ?>

<a href="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_all_k[$_img]].'/'.$img_all_v[$_img]; ?>" data-fancybox="wein-single" data-caption="<h2><?php echo htmlspecialchars($headline[$img_all_k[$_img]]); ?></h2><p> <?php echo $text[$img_all_k[$_img]]; ?></p>"><img src="<?php echo PICPATH; ?>_data/<?php echo  $content_id.'/'.$slice_id.'/'.${'file_id'}[$img_all_k[$_img]].'/'.$img_all_v[$_img]; ?>" alt="<?php echo $text[$img_all_k[$_img]]; ?>"  class="img-responsive" /></a>

<?php

if ( $_img == 3 OR $_img == 6  OR $_img ==9 ) {
 ?>
</div>  
<?php } ?>

<?php
}
}




 ?>

<div class="visible-xs-block clearer"><p><br><br><br></p></div>  

<?php }   ?>
            
 
        
</div>    
    
     
<style>
.row-fancy img { width: 200px; }
</style>