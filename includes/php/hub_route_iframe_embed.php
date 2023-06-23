<?php

function hub_route_iframe_embed()
{

    $content = NULL;

    $map_id_object = get_field_object('rute_gps_id');

    $url        =   $map_id_object['prepend'];
    $map_id     =   $map_id_object['value'];

    $url_string =  $url . "" . $map_id . "&metricUnits=true&sampleGraph=true&privacyCode=ib2gC1Q4I6NHoAFG";

    $iframe_heigth      =   get_field('option_rutekart_hoyde','option');

    $content .= "<iframe src='" . $url_string . "' style='width: 1px; min-width: 100%; height: " . $iframe_heigth . "px; border: none;' scrolling='no'></iframe>";

    return $content;
}
add_shortcode('hub_route_iframe_embed', 'hub_route_iframe_embed');
