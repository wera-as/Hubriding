<?php

function Hub_hotel_grid_template() {
    $content = NULL;
	
	$terms = get_the_terms(get_the_ID(), 'fylke');

	foreach ($terms as $term) {
		$posts = get_posts([
			'post_type'   => 'hotell',
			'numberposts' => -1,
			'orderby'     => 'title',
			'order'       => 'ASC',
			'tax_query'   => [[
				'taxonomy' => 'fylke',
				'field'    => 'term_id',
				'terms'    => $term->term_id,
			]],
		]);

		if (!empty($posts)) {
			$content .= "<section class='hub-grid hub-hotels'>";
			
			foreach ($posts as $post) {
				$hotelName = get_field('hotell_navn', $post);
				$hotelDesc = get_field('hotell_utdrag', $post);
				$hotelImg  = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thumbnail');

				$content .= "<a href='" . get_permalink($post->ID) . "'>";
				$content .= "	<img src='$hotelImg[0]'/>";
				$content .= "	<div class='hub-meta'>";
				$content .= "		<h3>$hotelName</h3>";
				$content .= "		<hr>";
				$content .= "		<p>$hotelDesc</p>";	
				$content .= "	</div>";
				$content .= "</a>";
			}	
			$content .= "</section>";
			
		} else {
			$content .= "<p style='text-align:center'>Det er dessverre ingen hoteller i dette fylket for øyeblikket. Kom gjerne tilbake ved en senere anledning!</p>";
		}
	}
    return $content;

} add_shortcode("Hub_hotel_grid_template", "Hub_hotel_grid_template");