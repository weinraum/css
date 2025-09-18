<p>
        <?php 

    $search     									= array('<br><br>');
    $replace   					 				= array("</p><p>");
    $txt 							= str_replace($search, $replace, $content);

    
   echo $content; ?>
</p>