<?php

function Hub_route_tabs_template() {
	$content = NULL;
	
	$termsCounty = get_the_terms(get_the_ID(), 'fylke');

	foreach ($termsCounty as $termCounty) {
		$posts = get_posts([
			'post_type'      => 'rute',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'meta_key'       => 'rute_nummer',
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC',
			'tax_query'      => [[
				'taxonomy'   => 'fylke',
				'field'      => 'term_id',
				'terms'      => $termCounty->term_id,
			]],
		]);
		
		$filteredPosts = [];

        foreach ($posts as $post) {
            $routeMaps = get_field('rute_rutekart', $post);
            $hasPublishedHotel = false;

            if ($routeMaps) {
                foreach ($routeMaps as $routeMap) {
                    $associatedHotelID = $routeMap['rute_tilhorende_hotell'];
                    if (get_post_status($associatedHotelID) == 'publish') {
                        $hasPublishedHotel = true;
                        break;
                    }
                }
            }

            if ($hasPublishedHotel) {
                $filteredPosts[] = $post;
            }
        }

        $posts = $filteredPosts;

		if (!empty($posts)) {
			$termsVehicle = get_terms([
				'taxonomy'    => 'kjøretøy',
				'orderby'     => 'count',
				'order'       => 'DESC',
			]);
			
			$content .= "<section class='button-row'>";
			$content .= "<div class='background'></div>";
			
			foreach ($termsVehicle as $termVehicle) {
				$vehiclePosts = array_filter($posts, function($post) use ($termVehicle) {
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
						$content .= "<a href='" . get_permalink($post->ID) . "' class='interactive'>";
						$content .= "	<div class='hub-ribbon'>";
						$content .= "		<p class='hub-ribbon-inner'>Interaktiv</p>";
						$content .= "	</div>";
					} else {
						$content .= "<a href='" . get_permalink($post->ID) . "'>";
					}
					
					$content .= "	<img src='$routeImg[0]'/>";
					$content .= "	<div class='hub-meta'>";
					$content .= "		<h3><strong>Rute $routeNum: </strong>$routeName</h3>";
					$content .= "		<hr>";
					$content .= "		<p>$routeDesc</p>";
					$content .= "	</div>";
					$content .= "</a>";
				}	
				
				$content .= "</section>";
			}
		} else {
			$content .= "<p style='text-align:center'>Det er dessverre ingen ruter i dette fylket for øyeblikket. Kom gjerne tilbake ved en senere anledning!</p>";
		}
	}
	return $content;
} add_shortcode("Hub_route_tabs_template", "Hub_route_tabs_template");