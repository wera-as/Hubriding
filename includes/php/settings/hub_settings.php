<?php

if (function_exists('acf_add_options_page')) {

	acf_add_options_page(
		[
		'page_title' 	=>	'Innstillinger',
		'menu_title'	=>	'Innstillinger',
		'menu_slug' 	=>	'w_innstillinger',
		'capability'	=>	'edit_posts',
		'redirect'		=>	false,
		'parent_slug'   => 	'hubriding'
		]
	);
}
?>
