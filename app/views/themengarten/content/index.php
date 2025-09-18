<?php echo App::getMessageBox(); ?>

<?php if( $class == 'user_themengarten' && App::hasFullAccount() ): ?>
<div class="row">
    <div class="span content-wrapper">
        <a href="<?php echo App::getUrl('user/themengarten/add') ?>" class="btn"><i class="icon-plus"></i> <?php eh('user_themengarten_action_add'); ?></a>
    </div>
</div>
<?php endif; ?>
<?php

if ( is_array( $content ) && sizeof($content) > 0 ) {


/* start seite zweispaltig mit kochen / wein
 *  die weiteren seiten zeigen an, was da ist
 */	
	
	
/*	------------------------------------------------------------------------------------------------
 *   	-------------------    doppelte zeile(n) am kopf der seite  ----------------    */

if ( $istStart== 1) {


if ( is_array( $headContent ) && sizeof($headContent) > 0 ) {
	$indRow = 0;
	foreach ( $headContent as $k => $v ) {
		$indRow += 1;
		if ( $indRow < 16 ) {
			if ( !empty( $v[0]['date'] ) ) {
				require_once FCPATH."classes/class.general.php";
				$agDatum = new FormatDatum( $v[0]['date'], 'Monat', 'vier' );
			}
//echo ("hu $k {$v[0]['id']}");
if ( !empty( $v[0]['image'] ) ) {?>
<div class="row">
<div class="span content-wrapper content-double-preview-wrapper">
<div class='left'>
<?php  
			if ( !empty( $v[0]['title'] ) ) {
?>
	<div class='headline'> 
	<h1><a href="themengarten/<?php echo $v[0]['urls'][0];   ?>" class="headIndex"><?php echo htmlspecialchars( $v[0]['title'] ); ?></a></h1>
	<p class='sepHead'>&nbsp;</p>
	</div>
<?php 			} ?>
	<div class='info teastxt'>
<?php
			

	if ( !empty( $v[0]['teaser'] ) ) {
		$search = array( '<br><br>' );
		$replace = array( '</p><p>' );
		$txt = str_replace( $search, $replace, $v[0]['teaser'] );		
		echo $txt;
	}

?>

	</div>	<!-- teastxt -->

<p class="teasBottom">

<span  class='author'><?php echo $v[0]['user'][0]['firstname'].' '.$v[0]['user'][0]['lastname'].', '.$agDatum->DatKompl;?> </span>
<span  class='komment'>
<?php 
	$count = ( is_array( $v[0]['comments'] )?count( $v[0]['comments'] ):0 );
	echo sprintf( h( 'content_comment_count'.( $count<2?$count:'' ) ), $count );
	?>
</span>
<?php 
	if ( !empty( $v[0]['urls'][0] ) and count( $v[0]['slices'][0] ) > 1 ) {
?>
			<a href="themengarten/<?php echo $v[0]['urls'][0]; ?>" class="weiter"><?php eh( 'themengarten_more' ); ?></a>
<?php
			} ?>
</p>
			


	</div>	<!-- left -->
	<div class='right'>
	<div class='pic'>
<?php if ( !empty( $v[0]['image'] ) ): ?>
		<img src="/_image/vorschau_<?php echo form_prep( $v[0]['id'].'__'.$v[0]['image'] ); ?>" alt="" />
<?php endif; ?>
	</div> 		<!-- pic -->
	</div>		<!-- right -->
	</div>		<!-- content-double-preview-wrapper -->
	</div>		<!-- row -->
	
<?php 
} // pic != empty 


// doppel ohne Bild

if ( empty( $v[0]['image'] ) ) {?>
<div class="row">
<div class="content-left-column">

	<div class="span content-wrapper   content-double-previewSansPic-wrapper">
<?php
			if ( !empty( $v[0]['title'] ) ) {
?>

	<div class='headline'> 
	<h1><a href="themengarten/<?php echo $v[0]['urls'][0]; ?>" class="headIndex"><?php echo htmlspecialchars( $v[0]['title'] ); ?></a></h1>
	<p class='sepHead'>&nbsp;</p>
	</div>
<?php 			} ?>
	<div class='info teastxt'>
<?php
			

	if ( !empty( $v[0]['teaser'] ) ) {
		$search = array( '<br><br>' );
		$replace = array( '</p><p>' );
		$txt = str_replace( $search, $replace, $v['teaser'] );

		echo $txt;
	}

?>

	</div>	<!-- teastxt -->

<p class="teasBottom">

<span  class='author'><?php echo $v[0]['user'][0]['firstname'].' '.$v[0]['user'][0]['lastname'].', '.$agDatum->DatKompl;?> </span>
<span  class='komment'>
<?php 
	$count = ( is_array( $v[0]['comments'] )?count( $v[0]['comments'] ):0 );
	echo sprintf( h( 'content_comment_count'.( $count<2?$count:'' ) ), $count );
	?>
</span>
<?php 
	if ( !empty( $v[0]['urls'][0] ) and count( $v[0]['slices'][0] ) > 1 ) {
?>
			<a href="themengarten/<?php echo $v[0]['urls'][0]; ?>" class="weiter"><?php eh( 'themengarten_more' ); ?></a>
<?php
			} ?>
</p>
			



	</div>		<!-- content-double-preview-wrapper -->
	</div>		<!-- content-left-column -->
	</div>		<!-- row -->
		
<?php
} // pic == empty 


}


		
	}
}



/*	------------------------------------------------------------------------------------------------
 *   	-------------------    rechts und links - spaltig  zeile(n) in der mitte  der seite  ----------------    */



// linke spalte = kochen seiten


if ( is_array( $leftContent ) && sizeof($leftContent) > 0 ) { ?>
<div class="row">
<div class="content-left-column">
<p class='TitColumn'>Der Esstisch im Themengarten</p>
<img src="/bilder3/seite/Themengarten_main2.jpg" alt="themengarten" class='themen' />
<p class='TxtColumn'>Kaufen, kochen, kombinieren: über den Genuss von Wein und Essen im Themengarten lesen, schreiben, teilen.</p>


<?php 
	$indRow = 0;
	foreach ( $leftContent as $k => $v ) {
		$indRow += 1;
		if ( $indRow < 16 ) {
			if ( !empty( $v[0]['date'] ) ) {
				require_once FCPATH."classes/class.general.php";
				$agDatum = new FormatDatum( $v[0]['date'], 'Monat', 'vier' );
			}
//echo ("hu $k {$v[0]['id']}");
?>
<div class="span content-wrapper content-small-preview-wrapper cspw_left">
<?php  
			if ( !empty( $v[0]['title'] ) ) {

// beim ersten durchgang steht der themengarten schon da. blöde lösung, aber fix:

$komisch = 	htmlspecialchars( $v[0]['title'] );
if ( $indRow == 1)	echo("<h1><a href='{$v[0]['urls'][0]}' class='headIndex'>$komisch</a></h1>");
if ( $indRow > 1)	echo("<h1><a href='themengarten/{$v[0]['urls'][0]}' class='headIndex'>$komisch</a></h1>");
?>

	<p class='sepHead'>&nbsp;</p>
	
	
<?php if ( !empty( $v[0]['image'] ) ): ?>
		<img src="/_image/vorschauSmall_<?php echo form_prep( $v[0]['id'].'__'.$v[0]['image'] ); ?>" alt="" />
<?php endif; ?>
	
<?php 			} ?>
	<div class='info teastxt'>
<?php
			

if ( !empty( $v[0]['teaser'] ) ) {
	$search = array( '<br><br>' );
	$replace = array( '</p><p>' );
	$txt = str_replace( $search, $replace, $v[0]['teaser'] );
$txt = "<p>".$txt;
	echo $txt;
}

?>

</div>	<!-- teastxt -->


<p class='mehr'>
<?php 
	if ( !empty( $v[0]['urls'][0] ) and count( $v[0]['slices'][0] ) > 1 ) {

// beim ersten durchgang steht der themengarten schon da. blöde lösung, aber fix:

if ( $indRow == 1)	{echo("<a href='{$v[0]['urls'][0]}' class='weiter'>"); eh( 'themengarten_more' ); echo("</a>");}
if ( $indRow > 1)	{echo("<a href='themengarten/{$v[0]['urls'][0]}' class='weiter'>");  eh( 'themengarten_more' ); echo("</a>");}


			} ?>
</p>			
<p class="teasBottom">

<span  class='author'><?php echo $v[0]['user'][0]['firstname'].' '.$v[0]['user'][0]['lastname'].', '.$agDatum->DatKompl;?> </span>
<span  class='komment'>
<?php 
	$count = ( is_array( $v[0]['comments'] )?count( $v[0]['comments'] ):0 );
	echo sprintf( h( 'content_comment_count'.( $count<2?$count:'' ) ), $count );
	?>
</span>
</p>
			
</div>		<!-- content-small-preview-wrapper -->
	
<?php 

}
}
?>
</div>		<!-- content-double-preview-wrapper -->

<?php 
}




// rechte (mittlere)  spalte = wein & winzer seiten


if ( is_array( $middleContent ) && sizeof($middleContent) > 0 ) { ?>
<div class="content-right-column">
<p class='TitColumn'>Von Winzern & Weinbergen</p>
<?php 
$indRow = 0;
foreach ( $middleContent as $k => $v ) {
	$indRow += 1;
	if ( $indRow < 16 ) {
		if ( !empty( $v[0]['date'] ) ) {
			require_once FCPATH."classes/class.general.php";
			$agDatum = new FormatDatum( $v[0]['date'], 'Monat', 'vier' );
		}
?>
<?php
if ( !empty( $v[0]['Bread'] ) ) {
		$anzLevel = count( $v[0]['Bread'] );
		$bumm = "";
		foreach ( $v[0]['Bread'][0] as $indLev => $LevIdentifer ) {
			//$bumm = $bumm  . "++ ". $v['urlsPure'][0][$indLev] . ":: ".$LevIdentifer;
		if ($bumm != "" AND $indLev < 3) $bumm = $bumm  .  ":: ".$LevIdentifer;
		if ($bumm == "") $bumm = $LevIdentifer;	
		}
	}
?>

<div class="span content-wrapper content-small-preview-wrapper cspw_right">
	<p class="kopfZeile"><?php if ( !empty( $v[0]['Bread'] ) ) echo $bumm; ?></p>

<?php  
if ( !empty( $v[0]['title'] ) ) {
?>
 
<h1><a href="themengarten/<?php echo $v[0]['urls'][0];   ?>" class="headIndex"><?php echo htmlspecialchars( $v[0]['title'] ); ?></a></h1>
<p class='sepHead'>&nbsp;</p>
<?php 			} ?>

<div class='info teastxt'>
<?php
if ( !empty( $v[0]['teaser'] ) ) {
	$search = array( '<br><br>' );
	$replace = array( '</p><p>' );
	$txt = str_replace( $search, $replace, $v[0]['teaser'] );
	$txt = "<p>".$txt;
    list($before, $after) = explode('</p>', $txt, 2);
	echo $before;
	
	if ( !empty( $v[0]['image'] ) ): ?>
		<img src="/_image/vorschauSmall_<?php echo form_prep( $v[0]['id'].'__'.$v[0]['image'] ); ?>" alt="" />
	<?php 
	endif;
	echo $after;
	 
}

?>

</div>	<!-- teastxt -->



<p class='mehr'>
<?php 
	if ( !empty( $v[0]['urls'][0] ) and count( $v[0]['slices'][0] ) > 1 ) {
?>
			<a href="themengarten/<?php echo $v[0]['urls'][0]; ?>" class="weiter"><?php eh( 'themengarten_more' ); ?></a>
<?php
			} ?>
</p>			
<p class="teasBottom">

<span  class='author'><?php echo $v[0]['user'][0]['firstname'].' '.$v[0]['user'][0]['lastname'].', '.$agDatum->DatKompl;?> </span>
<span  class='komment'>
<?php 
	$count = ( is_array( $v[0]['comments'] )?count( $v[0]['comments'] ):0 );
	echo sprintf( h( 'content_comment_count'.( $count<2?$count:'' ) ), $count );
	?>
</span>
</p>
</div>		<!-- content-small-preview-wrapper -->
	
<?php 

}
}
?>
</div>		<!-- content-right-column -->
</div>		<!--row -->

<?php 
}




}


/*   --------------------------------------------------------------------------------
 * 					einspaltiges layout wenn nicht startseite (class == home)     */

if ($istStart!= 1){

	$indRow = 0;
	foreach ( $content as $k => $vd ) {
		$indRow += 1;
		if ( $indRow < 16 ) {
			if ( !empty( $vd['date'] ) ) {
				require_once FCPATH."classes/class.general.php";
				$agDatum = new FormatDatum( $vd['date'], 'Monat', 'vier' );
			}
//echo ("hu $k {$v[0]['id']}");
if ( !empty( $vd['image'] ) ) {?>
<div class="row">
<div class="span content-wrapper content-double-preview-wrapper">
<div class='left'>
<?php  
			if ( !empty( $vd['title'] ) ) {
?>
	<div class='headline'> 
	<h1><a href="<?php echo $vd['urls'][0];   ?>" class="headIndex"><?php echo htmlspecialchars( $vd['title'] ); ?></a></h1>
	<p class='sepHead'>&nbsp;</p>
	</div>
<?php 			} ?>
	<div class='info teastxt'>
<?php
			

	if ( !empty( $vd['teaser'] ) ) {
		$search = array( '<br><br>' );
		$replace = array( '</p><p>' );
		$txt = str_replace( $search, $replace, $vd['teaser'] );		
		echo $txt;
	}

?>

	</div>	<!-- teastxt -->

<p class="teasBottom">

<span  class='author'><?php echo $vd['user'][0]['firstname'].' '.$vd['user'][0]['lastname'].', '.$agDatum->DatKompl;?> </span>
<span  class='komment'>
<?php 
	$count = ( is_array( $vd['comments'] )?count( $vd['comments'] ):0 );
	echo sprintf( h( 'content_comment_count'.( $count<2?$count:'' ) ), $count );
	?>
</span>
<?php 
	if ( !empty( $vd['urls'][0] ) and count( $vd['slices'][0] ) > 1 ) {
?>
			<a href="<?php echo $vd['urls'][0]; ?>" class="weiter"><?php eh( 'themengarten_more' ); ?></a>
<?php
			} ?>
</p>
			


	</div>	<!-- left -->
	<div class='right'>
	<div class='pic'>
<?php if ( !empty( $vd['image'] ) ): ?>
		<img src="/_image/vorschau_<?php echo form_prep( $vd['id'].'__'.$vd['image'] ); ?>" alt="" />
<?php endif; ?>
	</div> 		<!-- pic -->
	</div>		<!-- right -->
	</div>		<!-- content-double-preview-wrapper -->
	</div>		<!-- row -->
	
<?php 
} // pic != empty 


// doppel ohne Bild

if ( empty( $vd['image'] ) ) {?>
<div class="row">
<div class="content-left-column">

	<div class="span content-wrapper   content-double-previewSansPic-wrapper">
<?php
			if ( !empty( $vd['title'] ) ) {
?>

	<div class='headline'> 
	<h1><a href="<?php echo $vd['urls'][0]; ?>" class="headIndex"><?php echo htmlspecialchars( $vd['title'] ); ?></a></h1>
	<p class='sepHead'>&nbsp;</p>
	</div>
<?php 			} ?>
	<div class='info teastxt'>
<?php
			
	if ( !empty( $vd['teaser'] ) ) {
		$search = array( '<br><br>' );
		$replace = array( '</p><p>' );
		$txt = str_replace( $search, $replace, $vd['teaser'] );
		echo $txt;
	}

?>

	</div>	<!-- teastxt -->

<p class="teasBottom">

<span  class='author'><?php echo $vd['user'][0]['firstname'].' '.$vd['user'][0]['lastname'].', '.$agDatum->DatKompl;?> </span>
<span  class='komment'>
<?php 
	$count = ( is_array( $vd['comments'] )?count( $vd['comments'] ):0 );
	echo sprintf( h( 'content_comment_count'.( $count<2?$count:'' ) ), $count );
	?>
</span>
<?php 
	if ( !empty( $vd['urls'][0] ) and count( $vd['slices'][0] ) > 1 ) {
?>
			<a href="<?php echo $vd['urls'][0]; ?>" class="weiter"><?php eh( 'themengarten_more' ); ?></a>
<?php
			} ?>
</p>
			



	</div>		<!-- content-double-preview-wrapper -->
	</div>		<!-- content-left-column -->
	</div>		<!-- row -->
		
<?php
} // pic == empty 


}


		
}
}
}


else {
?>
	<div class="row">
	    <div class="span content-wrapper">
			<p><?php eh('no_articles'); ?></p>
		</div>
	</div>
<?php	
}
?>
