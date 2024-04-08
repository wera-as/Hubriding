<?php

require_once __DIR__ . '/resources/bytes_to_human.php';
require_once __DIR__ . '/resources/get_filesize.php';
require_once __DIR__ . '/../img/hub_signal_bars.php';


function Hub_route_map_template()
{

	error_reporting(E_ALL);
ini_set('display_errors', 1);


	$content = NULL;

	$routeID    =   get_the_ID();

	$content .= "<section class='hub-route'>";

	//META START
	$popularity_icon = NULL;
	$time_icon       = NULL;
	$grade_icon      = NULL;

	$grade           = get_field('rute_ferdighetsniva');
	$time            = get_field('rute_tid');
	$distance        = get_field('rute_lengde');

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

	$rootPath   =   $_SERVER['DOCUMENT_ROOT'];
	$config     =   require $rootPath . '/../hub_db.php';

	$host       =   $config['db_host'];
	$db         =   $config['db_name'];
	$user       =   $config['db_user'];
	$pass       =   $config['db_pass'];
	$charset    =   $config['db_charset'];

	$conn = new mysqli($host, $user, $pass, $db);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$conn->set_charset($charset);

	// Prepare the query to fetch the entire table
	$stmt = $conn->prepare("
    SELECT v.* FROM wp_hub_route_visitor_count v
    JOIN wp_posts p ON v.PageID = p.ID
    WHERE p.post_status = 'publish'
    ");
	$stmt->execute();
	$result = $stmt->get_result();

	// Create an array to store the visits and a variable to store the max views
	$visits_array = [];
	$max_views = 0;

	// Loop through the result and populate the visits array
	while ($row = $result->fetch_assoc()) {
		$visits_array[$row['PageID']] = $row['Visits'];
		if ($row['Visits'] > $max_views) {
			$max_views = $row['Visits'];
		}
	}

	// Close the statement and connection
	$stmt->close();
	$conn->close();

	// Get the visits for the given route ID
	if (isset($visits_array[$routeID])) {
		$visits = $visits_array[$routeID];
	} else {
		// If the route ID is not found in the array, set visits to 0
		$visits = 0;
	}

	// Calculate the rating (old)
	//$rating = round(1 + 9 * (log($visits + 1) / log($max_views + 1)));
	
	//Calculate the rating
	function calculatePercentile($visitorCounts, $currentVisitorCount) {
		sort($visitorCounts);
		$totalRoutes = count($visitorCounts);
		$countLessThanCurrent = 0;

		foreach ($visitorCounts as $count) {
			if ($count < $currentVisitorCount) {
				$countLessThanCurrent++;
			}
		}

		// Calculate percentile rank (not in percentage form)
		$percentileRank = $countLessThanCurrent / $totalRoutes;

		return (1 + floor($percentileRank * 10)); // This maps the lowest percentiles to the lowest ratings
	}
	
	$rating = calculatePercentile(array_values($visits_array), $visits);

	switch ($rating) {
		case ($rating >= 1.0 && $rating <= 2.0):
			$popularity_icon = HUB_SIGNAL_1_BAR;
			break;
		case ($rating >= 2.1 && $rating <= 4.0):
			$popularity_icon = HUB_SIGNAL_2_BARS;
			break;
		case ($rating >= 4.1 && $rating <= 6.0):
			$popularity_icon = HUB_SIGNAL_3_BARS;
			break;
		case ($rating >= 6.1 && $rating <= 8.0):
			$popularity_icon = HUB_SIGNAL_4_BARS;
			break;
		case ($rating >= 8.1 && $rating <= 10.0):
			$popularity_icon = HUB_SIGNAL_FULL_BARS;
			break;
	}

	$content .= "<div class='hub-route-info'>";
	$content .= "	<div class='hub-route-info-item'>";
	$content .= 		$grade_icon;
	$content .= "		<p>Ruten defineres som <strong>$grade_value</strong></p>";
	$content .= "		<span>$grade_desc</span>";
	$content .= "	</div>";

	if (!empty($time)) {
		$content .= "<div class='hub-route-info-item'>";
		$content .= "	$time_icon";

		if (floor($time / 60) > 1) {
			$content .= "<p>Tid<br><strong>" . sprintf('%d:%02d', floor($time / 60), ($time % 60)) . " timer</strong></p>";
		} else if (floor($time / 60) == 0) {
			$content .= "<p>Tid<br><strong>" . sprintf('%d', ($time % 60)) . " minutter</strong></p>";
		} else {
			$content .= "<p>Tid<br><strong>" . sprintf('%d:%02d', floor($time / 60), ($time % 60)) . " time</strong></p>";
		}
		$content .= "</div>";
	}

	$content .= "	<div class='hub-route-info-item'>";
	$content .= "		<i class='fa-duotone fa-route'></i>";
	$content .= "		<p>Lengde<br><strong>$distance km</strong></p>";
	$content .= "	</div>";
	$content .= "	<div class='hub-route-info-item'>";
	$content .= 		$popularity_icon;
	$content .= "		<p>Popularitet<br><strong>$rating av 10</strong></p>";
	$content .= "		<span>Regnes ut automatisk</span>";
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

			if (!empty($map_hotel) && $map_hotel->post_status === 'publish' && !isset($unique_hotels[$map_hotel->post_name])) {
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
			$map_gps_type       = get_sub_field('rute_gps_type');
			$map_gps_id_route   = get_sub_field('rute_gps_id');
			$map_gps_id_trip    = get_sub_field('rute_gps_id_trip');
			$map_google_id      = get_sub_field('rute_google_maps');
			$map_img_id         = get_sub_field('rute_kartbilde');
			$map_interactive_id = get_sub_field('rute_interaktiv');

			$iframe_heigth = get_field('option_rutekart_hoyde', 'option');
			if (isset($unique_hotels[$map_hotel->post_name])) {
				if ($map_type == "ridewithgps") {
					$map_gps_id_append        = '&metricUnits=true&sampleGraph=true&privacyCode=ib2gC1Q4I6NHoAFG';
					$map_gps_id_append_alt    = '&metricUnits=true&sampleGraph=true';
					if ($map_gps_type == "route") {
						$map_gps_id_prepend_route = 'https://ridewithgps.com/embeds?type=route&id=';
						$content .= "<iframe src='" . $map_gps_id_prepend_route . $map_gps_id_route . $map_gps_id_append_alt . "' class='hub-map-container hub-route-display $map_hotel->post_name'></iframe>";
					}
					if ($map_gps_type == "trip") {
						$map_gps_id_prepend_trip  = 'https://ridewithgps.com/embeds?type=trip&id=';
						$content .= "<iframe src='" . $map_gps_id_prepend_trip . $map_gps_id_trip . $map_gps_id_append_alt . "' class='hub-map-container hub-route-display $map_hotel->post_name'></iframe>";
					}
				} else if ($map_type == "googlemaps") {
					$map_google_id_prepend = 'https://www.google.com/maps/';
					$content .= "<iframe src='" . $map_google_id_prepend . $map_google_id . "' class='hub-map-container hub-route-display $map_hotel->post_name'></iframe>";
				} else if ($map_type == "bilde") {
					$content .= "<img src='" . $map_img_id['sizes']['large'] . "' class='hub-map-container hub-route-display $map_hotel->post_name'/>";
				} else if ($map_type == "interaktiv") {
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

			if (!empty($map_gps_file) && !isset($unique_hotels[$map_hotel->post_name])) {
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
				$content .= "	<a href='$map_gps_file' download=''>Last ned</a>";
				$content .= "	<p>Det anbefales at GPS er satt til å velge raskeste rute og evt. unngåelser skrudd av for at ruten skal kalkuleres som beskrevet.</p>";
				$content .= "</div>";
			}
		}

		$map_description = get_field('rute_kartbeskrivelse');

		$content .= "	<div class='hub-route-map-description'>$map_description</div>";
		$content .= "</div>";
		//FILE END
	}

	$content .= "</section>";

	return $content;
}
add_shortcode("Hub_route_map_template", "Hub_route_map_template");
