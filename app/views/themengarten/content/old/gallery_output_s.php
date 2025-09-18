
<!-- 
extrahiert farben aus bildern (automatisch) für Rahmen & Schrift

http://jariz.github.io/vibrant.js/
-->


<script src="/_/libs/vibrant.js-master/dist/Vibrant.js"></script> 

<?php 
if ($_area=='sidebar') {
    $imageType = 'sg';
    $thumbnailType = 'sthumb';
}
else {
    $imageType = 'mg';
    $thumbnailType = 'mthumb';
    $thumbnailTypeGray = 'mthumbgray';
}
$id = 'id_'.md5(rand().time());

$data = array();

$var = 'file'; 
if(is_array($$var)) {
    $maxHeight = 0;
    $maxHeightNew = 0;
    
    ?>



<script>



</script>       


<div class="bs-example col-xs-12 col-sm-12 ">

<div id="myCarousel_<?php echo $slice_id ?>" class="carousel slide" data-interval="3000" data-ride="carousel">
    
    <div class="menue" >
        
        
        
  <!-- Left and right controls -->
  <a class="left carousel-control" href="#myCarousel_<?php echo $slice_id ?>" role="button" data-slide="prev">
‹
  </a>

  
<ol class="carousel-indicators">

<?php
    
    foreach($$var as $k => $v) { ?>

            <li data-target="#myCarousel_<?php echo $slice_id ?> " data-slide-to="<?php echo $k ?>"   <?php echo($k ==0?'class="active"':''); ?>></li>

              
 <?php   }   

    ?>
            
</ol>    

<a class="right carousel-control" href="#myCarousel_<?php echo $slice_id ?>" role="button" data-slide="next">›</a>
 
        
    </div>    
          <?php 

$data = array();

$var = 'file'; 
if(is_array($$var)) {
    $maxHeight = 0;
    $maxHeightNew = 0;
    foreach($$var as $k => $v) {
        $d = array();

        $d['thumbnail'] = $thumbnailType.'_'.$content_id.'_'.$slice_id.'_'.${$var.'_id'}[$k].'__'.$v;
        $d['thumbnailgray'] = $thumbnailTypeGray.'_'.$content_id.'_'.$slice_id.'_'.${$var.'_id'}[$k].'__'.$v;
        $d['image'] = $imageType.'_'.$content_id.'_'.$slice_id.'_'.${$var.'_id'}[$k].'__'.$v;
        $d['headline'] = $headline[$k];
        $d['text'] = $text[$k];
        $count = strlen($d['text']); //echo ("$count <br/>");
        if ($count <= 200 ) $addtxt = 0; 
        if ($count > 200 ) $addtxt = 20; 
        if ($count > 300 ) $addtxt = 40; 
        if ($count > 400 ) $addtxt = 60; 
        if ($count > 500 ) $addtxt = 80; 
        $lines = 0;
        $check = explode("\n", $d['text']); 
        $lines = count($check) -1; //echo("line $lines - {$d['text']} <br>");
        $addtxt += $lines * 20; 
        
        $abfr = FCPATH."_data/".$content_id."/".$slice_id."/".${$var.'_id'}[$k]."/".$v;

        list($width, $height, $type, $attr) = getimagesize($abfr);//echo("hei : $abfr :: $height <br>");
        if ( $height > $width) {
        $picHeight = 577;
        if ($height < $picHeight ) $picHeight = $height;
        $picWidth = $picHeight/$height *$width ;
        $d['format'] = "hoch";
        }
        if ( $height <= $width) {
        $picWidth = 722;
        if ($width < $picWidth ) $picWidth = $width;
        $picHeight = $picWidth/$width*$height ;
        $d['format'] = "quer";
        }
        
        if ( $height > $maxHeight )    {
        if ( $height > $picHeight) {
        	// if ( $d['format'] == "hoch") $maxHeightNew = $picHeight+ 25 +$addtxt;
            if ( $d['format'] == "hoch") $maxHeightNew = $picHeight;
            if ( $d['format'] == "quer") $maxHeightNew = $picHeight+ 45 +$addtxt;
        }
        if ( $height <= $picHeight) $maxHeight = $height+ 45 +$addtxt;
        }//echo("kjsad $maxHeight :: $height :: $picHeight :: $addtxt");
        //echo("max $k -- $maxHeight :: pic $picHeight :: pic hight $height :: add $addtxt");
        if ( $maxHeightNew >  $maxHeight) {
        	$maxHeight = $maxHeightNew;
        }
        
        $d['hoehePic'] = $picHeight;
        $d['breitePic'] = $picWidth; //echo("wie $picWidth");
        if ( $d['format'] == "quer") $d['addHoeheTxt'] = 80 + $addtxt;
        if ( $d['format'] == "hoch") $d['addHoeheTxt'] = 0;
        
        $data[] = $d;
    }
}
elseif (is_string($$var)) {
    $d = array();

    $d['thumbnail'] = $thumbnailType.'_'.$content_id.'_'.$slice_id.'_'.${$var.'_id'}.'__'.$$var;
    $d['thumbnailgray'] = $thumbnailTypeGray.'_'.$content_id.'_'.$slice_id.'_'.${$var.'_id'}.'__'.$$var;
    $d['image'] = $imageType.'_'.$content_id.'_'.$slice_id.'_'.${$var.'_id'}.'__'.$$var;
    $d['headline'] = $headline;
    $d['text'] = $text;

    $data[] = $d;
}

 $picH_css[$slice_id] = $maxHeight;
  //echo ("pic {$picH_css[$slice_id]} ::$slice_id") ;
  //echo ("mac $maxHeight") ;
 
 if ($d['format'] == "hoch") { 
$item_height[$slice_id] = $picH_css[$slice_id] +105;
$item_XS_height[$slice_id] =  1 +105;
 }
 
// java: screen.width
 
 if ($d['format'] == "quer") { 
$item_height[$slice_id] = $picH_css[$slice_id] +15;
$item_200_height[$slice_id] = $maxHeight*0.6;
$item_400_height[$slice_id] = $maxHeight*0.7;
$item_600_height[$slice_id] = $maxHeight*0.8;
$item_800_height[$slice_id] = $maxHeight*0.9;

 }
 $capion_height[$slice_id] = $item_height[$slice_id] - $picH_css[$slice_id] +20;

 
 //echo $picH_css[$slice_id];
?>
    
     
 <style>   
div.bs-example div.menue {
        margin-top: 0px; 

 background:   #EBEACB;
 height: 75px;
}    
    
    
a {
    transition: all 150ms ease 0s;
}
.carousel-indicators {
      top: 3%;
}   
    
.carousel-control , .carousel-control.active {

    position: absolute;
    top: 5%;
    left: 75px;
    width: 30px;
    height: 30px;
    margin-top: -20px;
    font-size: 25px;
    font-weight: 100;
    line-height: 20px;
    color: #7A7A7A;
    text-align: center;
    background: #7A7A7A none repeat scroll 0% 0%;
    border: 3px solid #C39427 ;
    border-radius: 23px;
    opacity: 0.5; 
    
   z-index: 15;
    
    font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
}


.carousel-indicators li {
   border: 1px solid #7A7A7A; 
}

.carousel-control.right {
    right: 75px;
    left: auto;
}


.carousel-indicators .active {
    background-color: #C39427;
}


 @media only screen and (min-width : 200px) {
.item._<?php echo $slice_id ?> {
height: <?php echo $item_200_height[$slice_id] ?>px;
}
}

    

  
 @media only screen and (min-width : 450px) {
.item._<?php echo $slice_id ?> {
height: <?php echo $item_400_height[$slice_id] ?>px;
}
}

    

  
 @media only screen and (min-width : 550px) {
.item._<?php echo $slice_id ?> {
height: <?php echo $item_600_height[$slice_id] ?>px;
}
}

    
  
 @media only screen and (min-width : 650px) {
.item._<?php echo $slice_id ?> {
height: <?php echo $item_800_height[$slice_id] ?>px;
}
}

    

 @media only screen and (min-width : 750px) {

      .item._<?php echo $slice_id ?> {
height: <?php echo $item_height[$slice_id] ?>px;
      }


}



    
.carousel-caption {
    position: absolute;
    bottom: 10px;
padding: 0;
    right: 2%;
    left: 2%;
  
  
   


}    
    
.carousel-caption h3{
    margin: 0;     
    color: #666;
    padding-top: 0px;
    font-size: 52px;

}

.carousel-caption p{
    font-family:  "zimmerBold" ;
    margin: 0;     
    width: 100%;
    color: #544A39;

    line-height: 19px;
    font-size: 15px;
   text-shadow: none;
}

.item{
    position: relative;
    background: #AFB0B0 ;    
    text-align: center;
padding: 4px;
}
.carousel{
        position: relative;
    margin-top: 0px; 
}
.bs-example{
max-width: 730px;
margin: 15px 0 45px 0;
padding: 0;
}
.carousel-inner {
width: 100%;
}
  .carousel-inner > .item._<?php echo $slice_id ?> > img,
  .carousel-inner > .item._<?php echo $slice_id ?> > a > img {
 <?php  if ( $d['format'] == "quer") { ?>
      margin: auto;
 <?php } 
 if ( $d['format'] == "hoch") { ?>
      
float: left;
  height: <?php echo $picH_css[$slice_id] ?>px;
 <?php } ?> 
  
</style>

<div class="carousel-inner" role="listbox">   


    
<?php 
$pic = 0;
foreach($data as $k => $v) { 
$pic =  substr_replace($data[$k]['image'], '', -4, 4) ;      

$agh = htmlspecialchars($v['headline']);
$agt = nl2br(htmlspecialchars($v['text']));

//if ( $pic == "mg_198_814_3912__pilsen-9330") echo ("pic {$data[$k]['image']}");
?>
  <div class="  item _<?php echo $slice_id ?> pic_<?php echo $pic ?>  <?php echo($k ==0?' active':''); ?>">
      
      
<img  class="img-responsive"  src="/_image/<?php echo $data[$k]['image'] ?>" alt="" id="<?php echo $id ?>" >

                
<div class="carousel-caption pic_<?php echo $pic ?>">
<h3><?php echo $agh ?></h3>
<p><?php echo $agt ?></p>
</div>
</div>


 <script>
     
$(document).ready(function(){
setTimeout(function() { 
img = new Image()
img.setAttribute('src', '/_image/<?php echo $data[$k]['image'] ?>');
var ig = "<?php echo $pic?>"; //alert ( ig) ;


// there are values unset. in this case some variant values.
//if ( ig ==  "mg_198_814_3912__pilsen-9330") { alert("lkj <?php echo $pic?>"  ); }
// adjust color of frame & text:

var vibrant = new Vibrant(img, 64, 3);        
var swatches = vibrant.swatches(); 
for (swatch in swatches)
if (swatches.hasOwnProperty(swatch) && swatches[swatch]) {
    
//if ( ig ==  "mg_198_814_3912__pilsen-9330") { alert("hu1" +swatch + "<?php echo $pic?>"  + swatches[swatch].getHex()); }

$(".pic_<?php echo $pic?>").css( "background", swatches['Vibrant'].getHex() );    

//function returns 'only' black or white. adjust a little:
if (swatches['Vibrant'].getTitleTextColor() == "#fff") { $(".carousel-caption.pic_<?php echo $pic?> h1").css( "color", "#D6DBD3" );    }
if (swatches['Vibrant'].getTitleTextColor() == "#000" ) {$(".carousel-caption.pic_<?php echo $pic?> h1").css( "color", "#4C5148" );    }

if (swatches['Vibrant'].getBodyTextColor() == "#fff") { $(".carousel-caption.pic_<?php echo $pic?> p").css( "color", "#D6DBD3" );    }
if (swatches['Vibrant'].getBodyTextColor() == "#000" ) {$(".carousel-caption.pic_<?php echo $pic?> p").css( "color", "#4C5148" );    }
//$(".carousel-caption.pic_<?php echo $pic?> p").css( "color", swatches['Vibrant'].getBodyTextColor() );    

//alert("lkj"+swatch + "color"+swatches[swatch].getTitleTextColor());
    }

//alert("lkj <?php echo $pic?>"  + swatches['Vibrant'].getHex());
 
 });
  }, 1000)
  
 </script>
               
 <?php  
 
 
}   

    ?>      
        

  
    </div>


  
</div>
        
      

</div>
 <div class="clearer"><p><br></p></div>  

<?php
}

?>
 


    <script type="text/javascript">


    $(document).ready(function(){

         $(".carousel").carousel({

             interval : 6000,

             pause: false

         });

    });

    </script>

