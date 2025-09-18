<div class="visible-sm-block visible-md-block visible-lg-block col-sm-1 ">&nbsp;</div>
<div class="col-xs-12 col-sm-9 ">

<h1><?php eh('themengarten_content_'.$type); ?> - <?php eh('themengarten_content_sep_block'); ?></h1>

        
<form action="<?php echo current_url(); ?>" method="post">
<div>
<input type="hidden" name="_type" value="<?php echo(str_replace('_input.php', '', basename(__FILE__))); ?>" />
<input type="hidden" name="_area" value="main" />

<div class="control-group">
<label class="control-label">&nbsp;</label>
<div class="controls">
<button type="submit" class="btn"><?php eh('submit'); ?></button>
<a href="<?php echo $backUrl; ?>" class="btn btn-link back"><?php eh('back'); ?></a>
</div>
</div>
</div>
</form>
</div>


