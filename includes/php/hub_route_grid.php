<?php

function Hub_route_grid_template() {
    $content = NULL;
	
	$terms = get_the_terms(get_the_ID(), 'fylke');

	foreach ($terms as $term) {
		$posts = get_posts([
			'post_type'   => 'rute',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'orderby'     => 'title',
			'order'       => 'ASC',
			'tax_query'   => [[
				'taxonomy' => 'fylke',
				'field'    => 'term_id',
				'terms'    => $term->term_id,
			]],
		]);

		if (!empty($posts)) {
			$content .= "<section class='hub-grid hub-routes'>";
			
			foreach ($posts as $post) {
				$routeNum   = get_field('rute_nummer', $post);
				$routeName  = get_field('rute_navn', $post);
				$routeDesc  = get_field('rute_utdrag', $post);
				$routeImg   = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'medium');
				$routeVehicles = get_the_terms($post->ID, 'kjøretøy');

				$content .= "<a href='" . get_permalink($post->ID) . "'>";
				$content .= "	<img src='$routeImg[0]'/>";
				$content .= "	<div class='hub-meta'>";
				$content .= "		<h3>Rute $routeNum: <span>$routeName</span></h3>";
				$content .= "		<hr>";
				$content .= "		<p>$routeDesc</p>";
				$content .= "	</div>";
				$content .= "	<div class='hub-vehicles'>";
				
				$vehicleTermSlugs = array_map(
					function($term) { return $term->slug; }, 
					get_terms([
						'taxonomy' => 'kjøretøy',
						'orderby'     => 'count',
						'order'       => 'DESC',
					])
				);

				foreach ($vehicleTermSlugs as $vehicleTermSlug) {
					$termObject = get_term_by('slug', $vehicleTermSlug, 'kjøretøy');
					$imageVehicle = get_field('ikon', $termObject);
					$activeClass = '';

					if ($routeVehicles) {
						foreach ($routeVehicles as $routeVehicle) {
							if ($routeVehicle->slug == $vehicleTermSlug) {
								$activeClass = 'active';
								break;
							}
						}
					}

					$content .= "<img class='$activeClass' src='" . $imageVehicle['url'] . "'/>";
				}
				
				$content .= "</div>";
				$content .= "</a>";
			}	
			$content .= "</section>";
			
		} else {
			$content .= "<p style='text-align:center'>Det er dessverre ingen ruter i dette fylket for øyeblikket. Kom gjerne tilbake ved en senere anledning!</p>";
		}
	}
    return $content;

} add_shortcode("Hub_route_grid_template", "Hub_route_grid_template");