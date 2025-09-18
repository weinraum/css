<?php $App = new \App\Libraries\App();  ?>

<div class="container-fluid d-flex flex-wrap ">
<div class="content content-text-quote col-xs-12 col-sm-10">
<p class="ind">”</p>
<p>
<?php 
$txt            = preg_replace("/\n/", "</p><p>", $content);    
echo $txt ?>
</p>
<p class="testimonial">
<?php 
$txt            = preg_replace("/\n/", "</p><p>", $testimonial);    
echo $txt ?>
</p>
</div>
<div class="d-none d-sm-block col-sm-2">&nbsp;</div>
</div>