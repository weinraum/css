<?php
// Minimal-View: nur Content-HTML oder Fallback anzeigen
// Erwartet: $content_html (string) oder $fallback_msg (string)

if (!empty($content_html)) {
    echo $content_html;
} else {
    $msg = !empty($fallback_msg) ? $fallback_msg : 'Für diesen Winzer ist derzeit kein Inhalt verfügbar.';
    echo '<div class="info-box">'.$msg.'</div>';
}
