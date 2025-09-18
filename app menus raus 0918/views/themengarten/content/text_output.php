<?php 
use App\Models\Content2identifer_model;

$App = new \App\Libraries\App();  
/* Das ist für die cron functions, die rufen diese identifers im controller auf, die werden aber nicht wie in den views mit $this->data übergeben und müssen in den views
 * gesondert abgerufen werden.
 */

if ( !isset($linkIdentifers) ) {
$Content2identifer_model = new Content2identifer_model();
$linkIdentifers = $Content2identifer_model->getIdentifers();
}
$_sonder = -1;

?>

<div class="container-fluid d-flex flex-wrap ">
<div class=" col-12">
<?php
if ( isset($format_slice) AND $format_slice == "Zeitung" AND $_sonder == -1 )   { $_sonder = 1; ?>
<div class="content-text col-xs-12 col-sm-11 zeitung' ?>"><p>
<?php
}
if ( $_sonder == -1 ) { ?>
<div class="content-text col-xs-12 col-sm-11 col-lg-8 ' ?>"><p>
<?php } 

//$txt    = preg_replace("/\n/", "<br>", $content); 
if( isset($content) ) {

$txt = str_replace("<h2>", "</p><h2>", $content);
$txt = str_replace("</h2>\r\n", "</h2><p>", $txt);
$txt = str_replace("<h3>", "</p><h3>", $txt);
$txt = str_replace("</h3>\r\n", "</h3><p>", $txt);
$txt = str_replace("\n", "<br></p><p>", $txt);
$txt = str_replace("<bobbs>", "</p><p class='bobb'><span class='bobb'>&#8226;</span><span class='bobb_txt'>", $txt);
$txt = str_replace("<bobbe>", "</span></p><p class='clear'>", $txt);
$text2 = $App::getLinkfromBracket($txt, $linkIdentifers);
echo $text2; 
}?>
</p></div>
<div class="d-none d-sm-block col-sm-1 col-lg-3">&nbsp;</div>
</div>
</div>