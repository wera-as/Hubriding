<?php

function Hub_return_button() {

    $content = NULL;
    $content .= "<a href='". $_SERVER['HTTP_REFERER'] ."' class='hub-return'>Gå tilbake</a>";
    return $content;
}
add_shortcode('Hub_return_button', 'Hub_return_button');
