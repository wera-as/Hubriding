<?php

function Hub_hotel_booking_template() {
	$content = NULL;

	$hotelIdObject = get_field_object('hotell_synxis_id');
	$hotelIdUrl    = $hotelIdObject['prepend'];
	$hotelId       = $hotelIdObject['value'];
	$hotelPromo    = get_field('hotell_synxis_promo');
	if (!empty($hotelPromo)) {
		$hotelUrl = $hotelIdUrl . $hotelId . "&promo=" . $hotelPromo;
		$content .= "<a href='$hotelUrl' target='_blank'>Book rom som Hubrider</a>";
	} else {
		$hotelUrl = $hotelIdUrl . $hotelId;
		$content .= "<a href='$hotelUrl' target='_blank'>Book rom hos oss</a>";
	}

	return $content;
} add_shortcode("Hub_hotel_booking_template", "Hub_hotel_booking_template");