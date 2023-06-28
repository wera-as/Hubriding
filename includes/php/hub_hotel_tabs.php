<?php

function Hub_hotel_tabs_template() {
	$content = NULL;
	
	$routePosts = get_posts([
		'post_type'      => 'rute',
		'posts_per_page' => -1,
		'meta_key'       => 'rute_nummer',
		'orderby'        => 'meta_value_num',
		'order'          => 'ASC',
	]);
	
	$relationRoutes = [];
	foreach ($routePosts as $post) {
		$rows = get_field('rute_rutekart', $post->ID);
		if ($rows) {
			foreach ($rows as $row) {
				if ($row['rute_tilhorende_hotell']->ID == get_the_ID()) {
					$relationRoutes[] = $post;
					break;
				}
			}
		}
	};

	if (!empty($relationRoutes)) {
		$termsVehicle = get_terms([
			'taxonomy'    => 'kjøretøy',
			'orderby'     => 'count',
			'order'       => 'DESC',
		]);

		$content .= "<section class='button-row'>";
		$content .= "<div class='background'></div>";

		$vehiclesPosts = [];
		foreach ($termsVehicle as $termVehicle) {
			$vehiclePosts = array_filter($relationRoutes, function($post) use ($termVehicle) {
				$routeVehicles = get_the_terms($post->ID, 'kjøretøy');
				return in_array($termVehicle->term_id, wp_list_pluck($routeVehicles, 'term_id'));
			});

			$vehiclesPosts[$termVehicle->slug] = $vehiclePosts;
			$disabledClass = count($vehiclePosts) > 0 ? '' : 'disabled';
			$content .= "<button class='hub-vehicle-btn $disabledClass' data-vehicle='$termVehicle->slug'>$termVehicle->name</button>";
		}
		
		$content .= "</section>";

		foreach ($vehiclesPosts as $vehicleSlug => $vehiclePosts) {
			$content .= "<section class='hub-grid hub-routes hub-display $vehicleSlug'>";

			foreach ($vehiclePosts as $post) {
				$routeMaps  = get_field('rute_rutekart', $post);
				$routeNum   = get_field('rute_nummer', $post);
				$routeName  = get_field('rute_navn', $post);
				$routeDesc  = get_field('rute_utdrag', $post);
				$routeImg   = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thumbnail');
				$routeVehicles = get_the_terms($post->ID, 'kjøretøy');				
				
				$interactiveBool = false;
					
				if ($routeMaps) {
					foreach ($routeMaps as $routeMap) {
						if ($routeMap['rute_karttype'] == 'interaktiv') {
							$interactiveBool = true;
							break;
						}
					}
				}

				if ($interactiveBool) {
					$content .= "<a href='" . get_permalink($post->ID) . "#" . get_post_field( 'post_name', get_post()) . "' class='interactive'>";
					$content .= "	<div class='hub-ribbon'>";
					$content .= "		<p class='hub-ribbon-inner'>Interaktiv</p>";
					$content .= "	</div>";
				} else {
					$content .= "<a href='" . get_permalink($post->ID) . "#" . get_post_field( 'post_name', get_post()) . "'>";
				}

				$content .= "	<img src='$routeImg[0]'/>";
				$content .= "	<div class='hub-meta'>";
				$content .= "		<h3>Rute $routeNum: <span>$routeName</span></h3>";
				$content .= "		<hr>";
				$content .= "		<p>$routeDesc</p>";
				$content .= "	</div>";
				$content .= "</a>";
			}
			
			$content .= "</section>";
		}
	} else {
		$content .= "<p style='text-align:center'>Det er dessverre ingen ruter knyttet til dette hotellet for øyeblikket. Kom gjerne tilbake ved en senere anledning!</p>";
	
	}
	return $content;
} add_shortcode("Hub_hotel_tabs_template", "Hub_hotel_tabs_template");