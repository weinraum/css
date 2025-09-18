<?php 
namespace App\Libraries;

class Home_page_varianten {

public static function get_head ( $_contHead = NULL ) {
// echo("hu");print_r($_contHead['urls'][0]);
if ( is_array($_contHead) ) { ?>
<a href="/<?php echo $_contHead['urls'][0]; ?>">
<?php 
$_webp = substr($_contHead['imageBreitFlach'], 0, -4).".webp";
$_abfr_webp = "https://www.weinraum.de/_data/".$_contHead['id']."/output/lg_".$_webp;
$abfr4_lg = asset_url($_contHead['id']."/output/lg_".$_webp);
if ( file_exists($abfr4_lg) ) {
?>
<picture class="img-responsive" >
<source media="(max-width: 380px)" srcset= "https://www.weinraum.de/_data/<?php echo $_contHead['id']?>/output/xss_<?php echo $_webp?>">
<source media="(min-width: 381px) AND (max-width: 600px)" srcset= "https://www.weinraum.de/_data/<?php echo $_contHead['id']?>/output/xs_<?php echo $_webp?>">
<source media="(min-width: 601px) AND (max-width: 900px)" srcset= "https://www.weinraum.de/_data/<?php echo $_contHead['id']?>/output/md_<?php echo $_webp?>">
<source media="(min-width: 801px) AND (max-width: 900px)" srcset= "https://www.weinraum.de/_data/<?php echo $_contHead['id']?>/output/lg_<?php echo $_webp?>">
<img src="https://www.weinraum.de/_data/<?php echo $_contHead['id']?>/output/<?php echo $_contHead['imageBreitFlach']?>" class="d-block w-100" alt="<?php echo $_contHead['teaser']?>">
</picture>

<?php } else { ?>
<picture class="img-responsive" >
<source media="(max-width: 380px)" srcset= "https://www.weinraum.de/_data/<?php echo $_contHead['id']?>/output/xss_<?php echo $_contHead['imageBreitFlach']?>">
<source media="(min-width: 381px) AND (max-width: 600px)" srcset= "https://www.weinraum.de/_data/<?php echo $_contHead['id']?>/output/xs_<?php echo $_contHead['imageBreitFlach']?>">
<source media="(min-width: 601px) AND (max-width: 900px)" srcset= "https://www.weinraum.de/_data/<?php echo $_contHead['id']?>/output/md_<?php echo $_contHead['imageBreitFlach']?>">
<img src="https://www.weinraum.de/_data/<?php echo $_contHead['id']?>/output/<?php echo $_contHead['imageBreitFlach']?>" class="d-block w-100" alt="<?php echo $_contHead['teaser']?>">
</picture>
<?php } ?>

<h1 class="kopfdeckel"><?php echo $_contHead['teaser']?></h1>
<p class="kopfstory"><?php echo $_contHead['teaser_5']?>. <?php echo $_contHead['teaser_7']?></p>
</a>
<?php    
}
}
public static function get_zwei ( ) {
 ?> 


<?php    
}

public static function get_drei ( $_contentDrei = NULL) {

if ( is_array($_contentDrei) AND count($_contentDrei) === 3 ) { ?>
<div id="homeDrei" class="d-block d-md-none carousel slide">
<div class="carousel-inner">
<?php
$i = 0;
foreach ($_contentDrei as $key => $value) {   
$i++;
if ( $value['imageIndex'] !== "" ) { ?>
<div class="carousel-item <?php echo $i == 1?" active":"" ?>" >
<a href="/<?php echo $value['urls'][0]; ?>" >
<div class="kopfkino">
<h3 class="kopfbox"><?php echo $value['teaser']?></h3>
<p class="kopfsalat"><?php echo $value['teaser_5']?></p>
</div>
<div class="kopfkinoscheinwelt">
<h3 class="kopfbox"><?php echo $value['teaser']?></h3>
<p class="kopfsalat"><?php echo $value['teaser_5']?></p>
</div>
<picture class="img-responsive" >
<source media="(max-width: 380px)" srcset= "https://www.weinraum.de/_data/<?php echo $value['id']?>/output/xss_<?php echo $value['imageIndex']?>">
<source media="(min-width: 381px) AND (max-width: 600px)" srcset= "https://www.weinraum.de/_data/<?php echo $value['id']?>/output/xs_<?php echo $value['imageIndex']?>">
<source media="(min-width: 601px)" srcset= "https://www.weinraum.de/_data/<?php echo $value['id']?>/output/md_<?php echo $value['imageIndex']?>">
<img src="https://www.weinraum.de/_data/<?php echo $value['id']?>/output/md_<?php echo $value['imageIndex']?>" class="d-block w-100" alt="<?php echo $value['teaser']?>">
</picture> 
</a>
</div>
<?php    
}
}?>
</div>
<button class="carousel-control-prev" type="button" data-bs-target="#homeDrei" data-bs-slide="prev">
<span class="carousel-control-prev-icon" aria-hidden="true"><</span>
<span class="visually-hidden">Previous</span>
</button>
<button class="carousel-control-next" type="button" data-bs-target="#homeDrei" data-bs-slide="next">
<span class="carousel-control-next-icon" aria-hidden="true">></span>
<span class="visually-hidden">Next</span>
</button>
</div>

<div class="d-none d-md-block drei-home ">
<div class="container-fluid d-flex col-12 ">

<?php
$i = 0;
foreach ($_contentDrei as $key => $value) {   
$i++;
if ( $i == 1 ) $_agBl ="links col-4";
if ( $i == 2 ) $_agBl ="mitte col-4";
if ( $i == 3 ) $_agBl ="rechts col-4";

if ( $value['imageIndex'] !== "" ) { ?>
<div class="inhalt <?php echo $_agBl ?>" >
<a href="/<?php echo $value['urls'][0]; ?>" >
<div class="kopfkino">
<h3 class="kopfbox"><?php echo $value['teaser']?></h3>
<p class="kopfsalat"><?php echo $value['teaser_5']?></p>
</div>
<div class="kopfkinoscheinwelt">
<h3 class="kopfbox"><?php echo $value['teaser']?></h3>
<p class="kopfsalat"><?php echo $value['teaser_5']?></p>
</div>
<img src="https://www.weinraum.de/_data/<?php echo $value['id']?>/output/<?php echo $value['image']?>" class="d-block w-100" alt="<?php echo $value['teaser']?>">
</a>
</div>
<?php    
}
}?>
</div>

</div>



<?php    
}
}

public static function get_vier ( $_contentVier = NULL ) { 
if ( is_array($_contentVier) AND count($_contentVier) === 4 ) { ?>
<div id="homeVier" class="d-block d-md-none carousel slide">
<div class="carousel-inner">
<?php
$i = 0;
foreach ($_contentVier as $key => $value) {   
$i++;
if ( $value['imageIndex'] !== "" ) { ?>
<div class="carousel-item <?php echo $i == 1?" active":"" ?>" >
<?php if ( isset($value['urls'][0]) ) { ?><a href="/<?php echo $value['urls'][0]; ?>" ><?php } ?>
<div class="kopfkino">
<h3 class="kopfbox"><?php echo $value['teaser']?></h3>
<p class="kopfsalat"><?php echo $value['teaser_5']?></p>
</div>
<div class="kopfkinoscheinwelt">
<h3 class="kopfbox"><?php echo $value['teaser']?></h3>
<p class="kopfsalat"><?php echo $value['teaser_5']?></p>
</div>
<picture class="img-responsive" >
<source media="(max-width: 380px)" srcset= "https://www.weinraum.de/_data/<?php echo $value['id']?>/output/xss_<?php echo $value['imageIndex']?>">
<source media="(min-width: 381px) AND (max-width: 600px)" srcset= "https://www.weinraum.de/_data/<?php echo $value['id']?>/output/xs_<?php echo $value['imageIndex']?>">
<source media="(min-width: 601px)" srcset= "https://www.weinraum.de/_data/<?php echo $value['id']?>/output/md_<?php echo $value['imageIndex']?>">
<img src="https://www.weinraum.de/_data/<?php echo $value['id']?>/output/md_<?php echo $value['imageIndex']?>" class="d-block w-100" alt="<?php echo $value['teaser']?>">
</picture> 
<?php if ( isset($value['urls'][0]) ) { ?></a><?php } ?>
</div>
<?php    
}
}?>
</div>
<button class="carousel-control-prev" type="button" data-bs-target="#homeVier" data-bs-slide="prev">
<span class="carousel-control-prev-icon" aria-hidden="true"><</span>
<span class="visually-hidden">Previous</span>
</button>
<button class="carousel-control-next" type="button" data-bs-target="#homeVier" data-bs-slide="next">
<span class="carousel-control-next-icon" aria-hidden="true">></span>
<span class="visually-hidden">Next</span>
</button>
</div>

<div class="d-none d-md-block vier-home ">
<div class="container-fluid d-flex col-12 ">

<?php
$i = 0;
foreach ($_contentVier as $key => $value) {   
$i++;
if ( $i == 1 ) $_agBl ="links col-3";
if ( $i == 2 OR $i == 3) $_agBl ="mitte col-3";
if ( $i == 4 ) $_agBl ="rechts col-3";

if ( $value['imageIndex'] !== "" ) { ?>
<div class="inhalt <?php echo $_agBl ?>" >
<?php if ( isset($value['urls'][0]) ) { ?><a href="/<?php echo $value['urls'][0]; ?>" ><?php } ?>
<img src="https://www.weinraum.de/_data/<?php echo $value['id']?>/output/<?php echo $value['imageIndex']?>" class="d-block w-100" alt="<?php echo $value['teaser']?>">

<div class="kopfkinoscheinwelt">
<h3 class="kopfbox"><?php echo $value['teaser']?></h3>
<p class="kopfsalat"><?php echo $value['teaser_5']?> <?php echo $value['teaser_6']?></p>
</div>
<?php if ( isset($value['urls'][0]) ) { ?></a><?php } ?>
</div>
<?php    
}
}?>
</div>

</div>



<?php    
} 
}

/*
if ( isset($data['contHome']) AND is_array($data['contHome']) ) {
foreach ( $data['contHome'] as $kR => $vR ) {
$_title = "";
if ( isset($vR['Bread'][0]) AND is_array($vR['Bread'][0]) ) { foreach ( $vR['Bread'][0] as $kBr => $vBr ) {$_title .= $vBr.'/ ';} }


if ( !is_numeric($kR) AND $kR == "flat") {
if ( isset($vR['urls'][0]) AND $vR['imageBreitFlach'] !== "" ) {
$_SESSION['contHome']['flat_link'] = '<a href="https://www.weinraum.de/'.$vR['urls'][0].'">';
$_SESSION['contHome']['flat_alt'] = 'alt="'.$vR['imageBreitFlach'].'"  title="'.$_title.'"';
$_SESSION['contHome']['flat_org'] = 'https://www.weinraum.de/_data/'.$vR['id'].'/output/lg_'.$vR['imageBreitFlach'];
$_SESSION['contHome']['flat_lg'] = 'https://www.weinraum.de/_data/'.$vR['id'].'/output/lg_'.$vR['imageBreitFlach'];
$_SESSION['contHome']['flat_md'] = 'https://www.weinraum.de/_data/'.$vR['id'].'/output/md_'.$vR['imageBreitFlach'];
$_SESSION['contHome']['flat_xs'] = 'https://www.weinraum.de/_data/'.$vR['id'].'/output/xs_'.$vR['imageBreitFlach'];
$_SESSION['contHome']['teaser'] = $vR['teaser'];
$_SESSION['contHome']['teaser_5'] = $vR['teaser_5'];
*/


public static function get_einen_winzer () { ?>
<div class="flat-row">
<h2><?php echo $_SESSION['contHome']['flat_link']; echo $_SESSION['contHome']['teaser']  ?></a></h2>
<p><?php echo $_SESSION['contHome']['flat_link']; echo $_SESSION['contHome']['teaser_5'] ?></a></p>
<div class="flat-row-img">
<?php echo $_SESSION['contHome']['flat_link']; ?>
<picture class="img-responsive" >
<source media="(max-width: 680px)" srcset= "<?php echo $_SESSION['contHome']['flat_lg'] ?>">
<source media="(min-width: 681px) AND (max-width: 1023px)" srcset= "<?php echo $_SESSION['contHome']['flat_lg'] ?>">
<source media="(min-width: 1024px)" srcset= "<?php echo $_SESSION['contHome']['flat_lg'] ?>">
<img src="<?php echo $_SESSION['contHome']['flat_org'] ?>" <?php echo $_SESSION['contHome']['flat_alt'] ?>>
</picture> 
</a>
</div>
</div>
<?php 
}


public static function get_panneaux ( ) {
 ?> 
<div class="content-head">
<div class="home-front">
<?php 
if ( isset($_SESSION['contHome']) And is_array($_SESSION['contHome']) ) {
foreach ( $_SESSION['contHome'] as $kP => $vP ) { 
if ( is_numeric($kP) ) {?>
<div class="links cont d-none d-lg-block "><?php echo $vP['link_h'] ?></div>
<div class="rahmen d-block d-lg-none"><div class="links cont-xs d-block d-lg-none "><?php echo $vP['link_q'] ?></div></div>
<?php
}
}
}
// home back ist der kasten hinter den panneaux
?>
</div>    
<div class="home-back"></div>
</div>
<?php    
}

} 
?>





    
    




