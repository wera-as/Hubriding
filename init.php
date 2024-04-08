<?php

/**
 * Plugin Name: Hubriding Functionality
 * Plugin URI: https://wera.no
 * Tested up to: 6.3
 * Requires PHP: 7.4
 * Description: Custom functionality. Essential. Do not touch.
 * Version: 1.1
 * Author: Wera AS
 * Author URI: https://wera.no
 **/

/**
 * Includes the plugin's ACF settings page
 */
include_once __DIR__ . '/includes/php/settings/hub_settings.php';

/**
 * Includes the plugin's PHP files
 */
include_once __DIR__ . '/includes/php/hub_frontpage.php';
include_once __DIR__ . '/includes/php/hub_hotel_grid.php';
include_once __DIR__ . '/includes/php/hub_hotel_tabs.php';
include_once __DIR__ . '/includes/php/hub_hotel_booking.php';
include_once __DIR__ . '/includes/php/hub_route_tabs.php';
include_once __DIR__ . '/includes/php/hub_route_map.php';
include_once __DIR__ . '/includes/php/hub_404_quotes.php';
include_once __DIR__ . '/includes/php/hub_dynamic_return.php';
include_once __DIR__ . '/includes/php/hub_route_visit_counter.php';

/**
 * Includes the plugin's CPT's
 */
include_once __DIR__ . '/includes/post_types/hub_post_type_hotel.php';
include_once __DIR__ . '/includes/post_types/hub_post_type_route.php';
include_once __DIR__ . '/includes/post_types/hub_post_type_arrival.php';

/**
 * Includes the plugin's Custom taxonomies
 */
include_once __DIR__ . '/includes/post_types/tax/hub_tax_vehicle.php';
include_once __DIR__ . '/includes/post_types/tax/hub_tax_county.php';

function hubriding_cf_load_css()
{
    wp_enqueue_style(
        'hubriding_cf_styles_root',
        plugin_dir_url(__FILE__) .  'includes/css/root.css',
        [],
        filemtime(plugin_dir_path(__FILE__) .  'includes/css/root.css')
    );
}
add_action('wp_enqueue_scripts', 'hubriding_cf_load_css');


function hubriding_cf_load_admin_css()
{
    wp_enqueue_style(
        'hubriding_cf_styles_admin',
        plugin_dir_url(__FILE__) .  'includes/css/admin.css',
        [],
        filemtime(plugin_dir_path(__FILE__) .  'includes/css/admin.css')
    );
}
add_action('admin_enqueue_scripts', 'hubriding_cf_load_admin_css');

/**
 * Loads JS dependencies
 */

function hubriding_load_js_dependencies()
{
    wp_enqueue_script("jquery");

    wp_enqueue_script(
        'pagepiling',
        plugin_dir_url(__FILE__) .  'includes/js/jquery.pagepiling.min.js',
        [],
        filemtime(plugin_dir_path(__FILE__) .  'includes/js/jquery.pagepiling.min.js')
    );
}
add_action('wp_enqueue_scripts', 'hubriding_load_js_dependencies');

/**
 * Disable the comment functionality
 */
function remove_comments()
{
    remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'remove_comments');

/**
 * Adds the custom "Hubriding" menu page to include all CPT's
 */
function add_hub_menu_page()
{
    require_once __DIR__ . '/includes/img/base64/hub_logo.php';

    add_menu_page('Hubriding', 'Hubriding', 'publish_pages', 'hubriding', '', HUBRIDING_LOGO, 5);
}
add_action('admin_menu', 'add_hub_menu_page');

function editor_remove_menu_items()
{
    if (in_array('editor', wp_get_current_user()->roles)) {
        remove_menu_page('tools.php');
        remove_menu_page('edit-comments.php');
        remove_menu_page('edit.php?post_type=elementor_library');
        remove_menu_page('post-new.php?post_type=elementor_library');
    }
}
add_action('admin_menu', 'editor_remove_menu_items');

function editor_remove_from_admin_bar($wp_admin_bar)
{
    if (in_array('editor', wp_get_current_user()->roles)) {
        $wp_admin_bar->remove_node('comments');
        $wp_admin_bar->remove_node('new-elementor_library');
    }
}
add_action('admin_bar_menu', 'editor_remove_from_admin_bar', 999);

/**
 * Adds support for largest possible image size
 */
add_image_size('full', 2560, 2560);

/**
 * Adds random inspirational quotes
 */
function get_random_inspiration_quote()
{
    $path_to_json = "https://raw.githubusercontent.com/wera-as/inspirational-quotes-source/main/quotes-new.json";
    $fallback_html_output = "<span class='insp_quote_hart'>Made with ♥️ by <a href='https://wera.no/' target='_blank'>Wera AS</a></span>";

    $json_content = @file_get_contents($path_to_json);

    if ($json_content === FALSE) {
        return $fallback_html_output;
    }

    $array = json_decode($json_content, true);

    if (json_last_error() != JSON_ERROR_NONE) {
        return $fallback_html_output;
    }

    if (!is_array($array) || count($array) <= 0) {
        return $fallback_html_output;
    }

    $one_item = $array[array_rand($array)];

    if (isset($one_item['link']) && !empty($one_item['link'])) {
        $author_html = "<a href='" . $one_item['link'] . "' target='_blank' class='insp_quote_author'>" . $one_item['author'] . "</a>";
    } else {
        $author_html = $one_item['author'];
    }

    $html_output = "&ldquo;<span class='insp_quote_quote'>" . $one_item['quote'] . "</span>&rdquo;<br><span class='insp_quote_author'>" . $author_html . "</span><br><br>" . $fallback_html_output;

    return $html_output;
}

add_filter('admin_footer_text', 'get_random_inspiration_quote');
