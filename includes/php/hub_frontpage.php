<?php

function Hub_frontpage_template()
{
	$content = NULL;

	$featured_image = wp_get_attachment_url(get_post_thumbnail_id($post->ID), 'full');

	$content .= "<section id='pagepiling'>";
	$content .= "	<div data-anchor='main' id='main' class='section pp-scrollable' style='background-image:linear-gradient(rgba(0,0,0,0.25),rgba(0,0,0,0.75)),url($featured_image);'>";
	$content .= "		<div class='hub-main-container'>";
	$content .= "			<a href='/'><img src='/wp-content/uploads/2023/05/hubriding-logo-w.svg' id='hub-logo' alt='Hubriding logo'/></a>";
	$content .= "			<h1>Fantastiske opplev&shy;elser over hele landet</h1>";
	$content .= "			<h3>Hvilken <span>Hubrider</span> er du?</h3>";
	$content .= "			<div class='hub-buttons-front upper'>";

	$argsVehicle = [
		'taxonomy'       => 'kjøretøy',
		'posts_per_page' => -1,
		'orderby'        => 'count',
		'order'          => 'DESC',
	];

	$queryVehicle = new WP_Term_Query($argsVehicle);
	if (!empty($queryVehicle->terms)) {
		foreach ($queryVehicle->terms as $termVehicle) {
			$content .= "	<a href='#$termVehicle->slug' data-menuanchor='$termVehicle->slug' class='$termVehicle->slug'>$termVehicle->name</a>";
		}
	}

	$content .= "			</div>";
	$content .= "			<div class='hub-buttons-front lower'>";
	$content .= "				<a href='#elementor-action%3Aaction%3Dpopup%3Aopen%26settings%3DeyJpZCI6IjE2NTciLCJ0b2dnbGUiOnRydWV9'>Hva er Hubriding?</a>";
	$content .= "				<a href='/nyheter'>Gladsaker</a>";
	$content .= "			</div>";
	$content .= "				<a href='https://www.dehistoriske.no/' target='_blank'><img src='/wp-content/uploads/2023/06/de-historiske_logo.svg' id='dh-logo' alt='De Historiske logo'></a>";
	$content .= "		</div>";
	$content .= "	</div>";

	$queryVehicle = new WP_Term_Query($argsVehicle);
	if (!empty($queryVehicle->terms)) {
		foreach ($queryVehicle->terms as $termVehicle) {
			$image_id = get_field('bilde', $termVehicle);
			$image = $image_id['sizes']['full'];

			$content .= "	<div data-anchor='$termVehicle->slug' class='section pp-scrollable $termVehicle->slug' style='background-image:linear-gradient(rgba(0,0,0,0.25),rgba(0,0,0,0.75)),url($image);'>";
			$content .= "		<h2>Hubriding for deg som er $termVehicle->name</h2>";
			$content .= "		<div class='hub-counties'>";

			$argsCounty = [
				'post_type'      => 'page',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'orderby'        => [
					'menu_order' => 'ASC',
					'rand'      => 'ASC'
				],
				'tax_query'      => [
					[
						'taxonomy' => 'kjøretøy',
						'field'    => 'term_id',
						'terms'    => $termVehicle->term_id,
					]
				],
			];

			$pagesCounty = get_posts($argsCounty);
			if (!empty($pagesCounty)) {
				foreach ($pagesCounty as $pageCounty) {
					$pagesTerm = get_the_terms($pageCounty->ID, 'fylke');

					$content .= "<a href='" . get_permalink($pageCounty->ID) . "#" . $termVehicle->slug . "'>";
					$content .= 	$pageCounty->post_title;

					if (!empty($pagesTerm)) {
						foreach ($pagesTerm as $pageTerms) {
							$imageCounty = get_field('fylkesvapen', $pageTerms);
							if ($imageCounty) {
								$content .= "<img src='" . $imageCounty['url'] . "' alt='" . $imageCounty->name . "' />";
							}
						}
					}

					$content .= "</a>";
				}
			}
			$content .= "		</div>";
			$content .= "	<a data-menuanchor='main' href='#main'>Tilbake til toppen</a>";
			$content .= "	</div>";
		}
	}
	$content .= "</section>";

	return $content;
}
add_shortcode("Hub_frontpage_template", "Hub_frontpage_template");
