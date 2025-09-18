<?php $App = new \App\Libraries\App();  ?>


<?php 

$var = 'left'; 
if(is_array($$var)) {
    foreach($$var as $k => $v) {
        $d = array();

        $d['left'] = $left[$k];
        $d['right'] = $right[$k];

        $data[] = $d;
    }
}
elseif (is_string($$var)) {
    $d = array();

/*    $d['left'] = $left[$k];
    $d['right'] = $right[$k];
 * 
 */
    
 $d['left'] = $left;
 $d['right'] = $right;
    
//echo ("j  $k $v {$left}");
    $data[] = $d;
}
?>
<?php if(sizeof($left) > 0): ?>
<div class="content col-xs-12">
<?php foreach($data as $k => $v): ?>
    <div class="content-2col-left col-xs-4 {leftalign}">
        <?php echo htmlspecialchars($v['left']); ?>

    </div>
    
    <div class="content-2col-right  col-xs-8 {rightalign}">
        <?php echo nl2br($v['right']); ?>
    </div>
    <div class="clearer"></div>
<?php endforeach; ?>
</div>
    <div class="clearer"></div>

<?php endif; ?>


<style {csp-style-nonce} >
div.ads-row table tbody tr td a{color: #1577ad;}
@media(max-width:689px) {
div.ads-row   { padding: 0; margin: 35px 0 0 0;}
div.ads-row table tbody tr td {font: italic normal 400 11px/19px 'Georgia', sans-serif;padding: 5px 10px 5px 0;}
div.ads-row table thead tr td {font: normal normal 600 16px/24px 'brandon-grotesque', sans-serif;padding: 0 10px 5px 0;}
}

.content.passend div.ads-row   { padding: 0; margin: 35px 0 0 -11px;}

@media(min-width:690px) {
div.menuunderimage {position: relative;width: 100%;top: -5px;left: 0;margin: 0 0 0 0 !important;}
div.ads-row   { padding: 0; margin: 35px 0 0 5px;}
div.ads-row table  { hyphens: none;}
div.ads-row table tbody tr td {font: italic normal 400 13px/25px 'Georgia', serif;padding: 5px 50px 5px 0;}
div.ads-row table thead tr td {font: normal normal 600 15px/24px 'Georgia', serif;padding: 0 10px 5px 0;letter-spacing: 0.04em;}
}

div.ads-row  table tbody tr td:last-child  { padding: 5px 0 5px 0; }
div.ads-row table tbody tr td {border-bottom: 1px solid #d2584b;color: #000000;vertical-align: top;}
div.ads-row table thead tr td {border-bottom: 1px solid #d2584b;color: #000000;vertical-align: bottom;}
div.ads-row table thead tr td:last-child  { padding: 0 0 5px 0; }

</style>