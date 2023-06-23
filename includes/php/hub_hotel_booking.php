<?php

function Hub_hotel_booking_template() {
	$content = NULL;

	$hotelIdObject = get_field_object('hotell_synxis_id');
	$hotelIdUrl    = $hotelIdObject['prepend'];
	$hotelId       = $hotelIdObject['value'];
	$hotelUrl      = $hotelIdUrl . $hotelId;

	$content .= "<a href='$hotelUrl' target='_blank'>Book rom hos oss nå!</a>";

	return $content;
} add_shortcode("Hub_hotel_booking_template", "Hub_hotel_booking_template");