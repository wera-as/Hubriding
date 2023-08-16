<?php

function Hub_dynamic_return() {
	$content = NULL;

	$content .= "<div class='hub_dynamic_return'>";
	$content .= "	<a href='/'>Hubriding</a>";

	$terms_fylke = get_the_terms(get_the_ID(), 'fylke');
	$terms_kjoretoy = get_the_terms(get_the_ID(), 'kjøretøy');

	foreach ($terms_fylke as $term_fylke) {
		if ($terms_kjoretoy && have_rows('rute_rutekart')) {
			foreach ($terms_kjoretoy as $term_kjoretoy) {
				$content .= "<a href='" . get_home_url() . "/" . $term_fylke->slug . "/#" .  $term_kjoretoy->slug . "' class='return-button'>" . $term_fylke->name . "</a>";
			}
		} else {
			$content .= "<a href='" . get_home_url() . "/" . $term_fylke->slug . "/' class='return-button'>" . $term_fylke->name . "</a>";
		}
	}

	if (have_rows('rute_rutekart')) {
		$unique_hotels = [];
	
		while (have_rows('rute_rutekart')) {
			the_row();
			
			$related_hotel = get_sub_field('rute_tilhorende_hotell');
			
			if (!empty($related_hotel) && !isset($unique_hotels[$related_hotel->post_name])) {
				$unique_hotels[$related_hotel->post_name] = $related_hotel->post_name;
				$content .= "<a href='" . get_home_url() . "/hotell/" . $related_hotel->post_name . "/' class='hub_dynamic_return_btn " . $related_hotel->post_name . "' data-hotel='" . $related_hotel->post_name . "'>" . $related_hotel->post_title . "</a>";
			}
		}
	}
	if (!empty(get_field('rute_navn'))) {
		$content .= "<p>" . get_field('rute_navn') . "</p>";
	}

	if (!empty(get_field('hotell_navn'))) {
		$content .= "<p>" . get_field('hotell_navn') . "</p>";
	}
	
	$content .= "</div>";

	return $content;
}
add_shortcode('Hub_dynamic_return', 'Hub_dynamic_return');