<?php

require_once __DIR__ . '/resources/bytes_to_human.php';
require_once __DIR__ . '/resources/get_filesize.php';

function Hub_route_map_template() {
	$content = NULL;

	$content .= "<section class='hub-route'>";
	
	//META START
	$popularity_icon = NULL;
	$time_icon       = NULL;
	$grade_icon      = NULL;

	$grade           = get_field('rute_ferdighetsniva');
	$time            = get_field('rute_tid');
	$distance        = get_field('rute_lengde');
	$popularity      = get_field('rute_popularitet');

	switch ($grade['value']) {
		case "easy":
			$grade_icon     =   "<i class='fa-duotone fa-dial-min'></i>";
			$grade_value    =   "enkel";
			$grade_desc     =   get_field('option_easy_route_desc', 'option');
			break;
		case "medium":
			$grade_icon     =   "<i class='fa-duotone fa-dial-med-low'></i>";
			$grade_value    =   "normal";
			$grade_desc     =   get_field('option_medium_route_desc', 'option');
			break;
		case "hard":
			$grade_icon     =   "<i class='fa-duotone fa-dial'></i>";
			$grade_value    =   "krevende";
			$grade_desc     =   get_field('option_hard_route_desc', 'option');
			break;
		case "extreme":
			$grade_icon     =   "<i class='fa-duotone fa-dial-max'></i>";
			$grade_value    =   "ekstrem";
			$grade_desc     =   get_field('option_extreme_route_desc', 'option');
			break;
	}
	
	switch ($time) {
		case ($time < 120):
			$time_icon = "<i class='fa-duotone fa-hourglass-start'></i>";
			break;
		case ($time >= 120 && $time <= 360):
			$time_icon = "<i class='fa-duotone fa-hourglass-half'></i>";
			break;
		case ($time > 360):
			$time_icon = "<i class='fa-duotone fa-hourglass-end'></i>";
			break;
	}
	
	switch ($popularity) {
		case ($popularity >= 1.0 && $popularity <= 2.5):
			$popularity_icon = "<i class='fa-duotone fa-signal-bars-weak'></i>";
			break;
		case ($popularity >= 2.6 && $popularity <= 5.0):
			$popularity_icon = "<i class='fa-duotone fa-signal-bars-fair'></i>";
			break;
		case ($popularity >= 5.1 && $popularity <= 7.5):
			$popularity_icon = "<i class='fa-duotone fa-signal-bars-good'></i>";
			break;
		case ($popularity >= 7.6 && $popularity <= 10.0):
			$popularity_icon = "<i class='fa-duotone fa-signal-bars-strong'></i>";
			break;
	}

	$content .= "<div class='hub-route-info'>";
	$content .= "	<div class='hub-route-info-item'>";
	$content .= 		$grade_icon;
	$content .= "		<p>Ruten defineres som <strong>$grade_value</strong></p>";
	$content .= "		<span>$grade_desc</span>";
	$content .= "	</div>";
	$content .= "	<div class='hub-route-info-item'>";
	$content .= "		$time_icon";

	if (floor($time / 60) > 1) {
		$content .= "	<p>Tid<br><strong>" . sprintf('%d:%02d', floor($time / 60), ($time % 60)) . " timer</strong></p>";
	} else if (floor($time / 60) == 0) {
		$content .= "	<p>Tid<br><strong>" . sprintf('%d', ($time % 60)) . " minutter</strong></p>";
	} else {
		$content .= "	<p>Tid<br><strong>" . sprintf('%d:%02d', floor($time / 60), ($time % 60)) . " time</strong></p>";
	}
	
	$content .= "	</div>";
	$content .= "	<div class='hub-route-info-item'>";
	$content .= "		<i class='fa-duotone fa-route'></i>";
	$content .= "		<p>Lengde<br><strong>$distance km</strong></p>";
	$content .= "	</div>";
	$content .= "	<div class='hub-route-info-item'>";
	$content .= 		$popularity_icon;
	$content .= "		<p>Popularitet<br><strong>$popularity av 10</strong></p>";
	$content .= "	</div>";
	$content .= "</div>";
	//META END

	//MAP START
	if (have_rows('rute_rutekart')) {
		$unique_hotels = [];
		
		$content .= "<div class='hub-route-map'>";
		$content .= "	<div class='hub-route-button-row'>";
		
		while (have_rows('rute_rutekart')) {
			the_row();
			
			$map_hotel = get_sub_field('rute_tilhorende_hotell');
			
			if (!empty($map_hotel) && !isset($unique_hotels[$map_hotel->post_name])) {
				$unique_hotels[$map_hotel->post_name] = $map_hotel->post_name;
				$content .= "<button class='hub-map-btn' data-map='" . $map_hotel->post_name . "'>" . $map_hotel->post_title . "</button>";
			}
		}
		
		$content .= "</div>";
		reset_rows();	
		
		while (have_rows('rute_rutekart')) {
			the_row();
			
			$map_type           = get_sub_field('rute_karttype');
			$map_hotel          = get_sub_field('rute_tilhorende_hotell');
			$map_gps_id         = get_sub_field('rute_gps_id');
			$map_google_id      = get_sub_field('rute_google_maps');
			$map_img_id         = get_sub_field('rute_kartbilde');	
			$map_interactive_id = get_sub_field('rute_interaktiv');
			
			$iframe_heigth = get_field('option_rutekart_hoyde','option');
			if (isset($unique_hotels[$map_hotel->post_name])) {
				if ($map_type == "ridewithgps") {
					$map_gps_id_prepend = 'https://ridewithgps.com/embeds?type=route&id=';
					$map_gps_id_append  = '&metricUnits=true&sampleGraph=true&privacyCode=ib2gC1Q4I6NHoAFG';
					$content .= "<iframe src='" . $map_gps_id_prepend . $map_gps_id . $map_gps_id_append . "' class='hub-map-container hub-route-display $map_hotel->post_name'></iframe>";
				} else if ($map_type == "googlemaps") {
					$map_google_id_prepend = 'https://www.google.com/maps/embed?';
					$content .= "<iframe src='" . $map_google_id_prepend . $map_google_id . "' class='hub-map-container hub-route-display $map_hotel->post_name'></iframe>";
				} else if ($map_type == "bilde") {
					$content .= "<img src='" . $map_img_id['sizes']['large'] . "' class='hub-map-container hub-route-display $map_hotel->post_name'/>";
				} else if ($map_type == "interaktiv" ) {
					$map_interactive_prepend = "https://sonicmaps.xyz/embed/?u=489&p=";
					$content .= "<iframe src='" . $map_interactive_prepend . $map_interactive_id . "' class='interactive hub-map-container hub-route-display $map_hotel->post_name'></iframe>";
				}
				unset($unique_hotels[$map_hotel->post_name]);
			}
		}
		$content .= "</div>";
		reset_rows();
		//MAP END

		//FILE START
		$content .= "<div class='hub-route-file'>";
		
		while (have_rows('rute_rutekart')) {
			the_row();

			$map_type           = get_sub_field('rute_karttype');
			$map_hotel          = get_sub_field('rute_tilhorende_hotell');
			$map_gps_file       = get_sub_field('rute_fil');
			$map_gps_file_icon  = get_field('option_gpx_ikon', 'option');
			$map_gps_file_title = "<strong>Rute " . get_field('rute_nummer') . "</strong>: " . get_field('rute_navn');

			$ext      = pathinfo($map_gps_file, PATHINFO_EXTENSION);
			$filename = pathinfo($map_gps_file, PATHINFO_FILENAME);

			$size     = Get_Filesize($map_gps_file);
			$filesize = FileSizeConvert($size);
			
			if ($map_type == "ridewithgps" && !isset($unique_hotels[$map_hotel->post_name])) {
				$unique_hotels[$map_hotel->post_name] = $map_hotel->post_name;
				$content .= "<div class='hub-route-file-item hub-route-display $map_hotel->post_name'>";
				$content .= "	<div class='hub-route-file-wrapper'>";
				$content .= "		<img src='$map_gps_file_icon'>";
				$content .= "		<div class='hub-route-file-meta'>";
				$content .= "			<p>$map_gps_file_title</p>";
				$content .= "			<div>";
				$content .= "				<span><i class='fa-solid fa-database'></i>&ensp;" . $filename . "." . $ext . "</span>";
				$content .= "				<span><i class='fa-solid fa-file'></i>&ensp;$filesize</span>";
				$content .= "			</div>";
				$content .= "		</div>";
				$content .= "	</div>";
				$content .= "	<a href='$map_gps_file' target='_blank'>Last ned</a>";
				$content .= "	<p>Det anbefales at GPS er satt til å velge raskeste rute og evt. unngåelser skrudd av for at ruten skal kalkuleres som beskrevet.</p>";
				$content .= "</div>";
			}
		}
		
		$map_description = get_field('rute_kartbeskrivelse');
		
		$content .= "	<p class='hub-route-map-description'>$map_description</p>";
		$content .= "</div>";
		//FILE END
	}
	
	$content .= "</section>";

	return $content;
} add_shortcode("Hub_route_map_template", "Hub_route_map_template");