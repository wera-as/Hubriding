<?php

function hub_route_information_facts()
{

    $content            =   NULL;
    $popularity_icon    =   NULL;
    $grade_icon         =   NULL;

    $grade              =   get_field('rute_ferdighetsniva');
    $distance           =   get_field('rute_lengde');
    $popularity         =   get_field('rute_popularitet');

    switch ($grade['value']) {
        case "easy":
            $grade_icon     =   "<i class='fa-thin fa-dial-min'></i>";
            $grade_value    =   "enkel";
            $grade_desc     =   get_field('option_easy_route_desc', 'option');
            break;
        case "medium":
            $grade_icon     =   "<i class='fa-thin fa-dial-med-low'></i>";
            $grade_value    =   "normal";
            $grade_desc     =   get_field('option_medium_route_desc', 'option');
            break;
        case "hard":
            $grade_icon     =   "<i class='fa-thin fa-dial'></i>";
            $grade_value    =   "krevende";
            $grade_desc     =   get_field('option_hard_route_desc', 'option');
            break;
        case "extreme":
            $grade_icon     =   "<i class='fa-thin fa-dial-max'></i>";
            $grade_value    =   "ekstrem";
            $grade_desc     =   get_field('option_extreme_route_desc', 'option');
            break;
    }
	
	switch ($popularity) {
		case ($popularity >= 1.0 && $popularity <= 2.5):
            $popularity_icon = "<i class='fa-thin fa-signal-weak'></i>";
            break;
        case ($popularity >= 2.6 && $popularity <= 5.0):
            $popularity_icon = "<i class='fa-thin fa-signal-fair'></i>";
            break;
        case ($popularity >= 5.1 && $popularity <= 7.5):
            $popularity_icon = "<i class='fa-thin fa-signal-good'></i>";
            break;
        case ($popularity >= 7.6 && $popularity <= 10.0):
            $popularity_icon = "<i class='fa-thin fa-signal-strong'></i>";
            break;
    }

	$content .= "<section class='route-info'>";
	$content .= "	<div class='route-info-container hub-grade'>";
	$content .= 		$grade_icon;
	$content .= "		<p>Denne ruten regnes som <strong>$grade_value</strong></p>";
	$content .= "		<span>$grade_desc</span>";
	$content .= "	</div>";
	$content .= "	<div class='route-info-container hub-distance'>";
	$content .= "		<i class='fa-thin fa-route'></i>";
	$content .= "		<p>Lengde<br><strong>$distance km</strong></p>";
	$content .= "	</div>";
	$content .= "	<div class='route-info-container hub-popularity'>";
	$content .= 		$popularity_icon;
	$content .= "		<p>Popularitet<br><strong>$popularity av 10</strong></p>";
	$content .= "	</div>";
	$content .= "</section>";

    return $content;
}
add_shortcode('hub_route_information_facts', 'hub_route_information_facts');
