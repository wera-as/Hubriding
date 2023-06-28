<?php 
 /**
 * Plugin Name: Hubriding Functionality
 * Plugin URI: https://wera.no
 * Tested up to: 6.2.2
 * Requires PHP: 7.4
 * Description: Custom functionality. Essential. Do not touch.
 * Version: 1.0
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
include_once __DIR__ . '/includes/php/hub_return_button.php';

/**
 * Includes the plugin's CPT's
 */
include_once __DIR__ . '/includes/post_types/hub_post_type_hotel.php';
include_once __DIR__ . '/includes/post_types/hub_post_type_route.php';

/**
 * Includes the plugin's Custom taxonomies
 */
include_once __DIR__ . '/includes/post_types/tax/hub_tax_county.php';
include_once __DIR__ . '/includes/post_types/tax/hub_tax_vehicle.php';

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
    add_menu_page('Hubriding', 'Hubriding', 'administrator', 'hubriding', '', 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDkuNjEgMTA5LjY0Ij4KICA8cGF0aCBmaWxsPSIjZmZmIiBkPSJtNTUuMzYgODUuODMtLjQyLS41SDMyLjM4Yy0yLjA5IDAtNC4xNi0uNDgtNS41MS0yLjEyLTEuMzctMS42Ni0xLjM2LTMuNzctLjkxLTUuNzRzNS4wNy0yMy4xIDUuMDctMjMuMXYtLjAzbDEuNzgtOC4wNiAxLjk4LTkuMDItLjQyLS41aC0xMi42bC0uMzguMy0zLjY3IDE2LjU3LS42NC41SC43TDAgNTMuMyAxMS42Mi41bC42NC0uNWgxNi4zOGwuNy44My02LjU2IDI5Ljg3LjQyLjVoMTIuNjFsLjM4LS4zTDQyLjg3LjVsLjY0LS41aDE2LjM4bC43LjgzLTYuNjMgMzAuMTR2LjAyTDQzLjMzIDc5LjI3bC40Mi41aDEyLjYybC4zOC0uMyAxMC42NS00OC40LjY0LS41aDE2LjM4bC43LjgzLTUuMTcgMjMuNTkuNDIuNWgyMi41NWMyLjA5IDAgNC4xNi40OCA1LjUxIDIuMTIgMS4zNyAxLjY2IDEuMzYgMy43Ny45MSA1Ljc0di4wMmwtMy4wMSAxMy44OXYuMDRhNi4zNiA2LjM2IDAgMCAxLTEuNjIgMi43MiA3LjggNy44IDAgMCAxLTIuNzkgMS45MmwtLjQxLjE2LS4wNi44NC4zOC4yMWMuNzkuNDQgMS40NCAxLjEgMS44NiAxLjkuNDUuODIuNTUgMS42OC4zMiAyLjU4czAgLjAyIDAgLjAyTDEwMSAxMDEuNDhhMTAuMjYgMTAuMjYgMCAwIDEtMTAuMjcgOC4xNkg1MS4wMmwtLjctLjgzIDUuMDUtMjIuOTZ2LS4wMlptMTkuNTUtOC4wMXYuMDJsLS4zMiAxLjQzLjQyLjVoMTIuNjJsLjM4LS4zIDMuOTctMTcuOTEtLjQyLS41SDc4Ljk0bC0uMzguMy0uMDUuMjMtMy40MyAxNS42MS0uMTYuNjJoLS4wMVptLTEuMzIgNy41Mi0uMzguMy0zLjk3IDE3LjkxLjQyLjVoMTIuNjFsLjM4LS4zIDMuOTctMTcuOTEtLjQyLS41SDczLjU5WiIvPgo8L3N2Zz4=', 6);
}
add_action('admin_menu', 'add_hub_menu_page');

function get_random_inspiration_quote()
{
    $path_to_json = "https://raw.githubusercontent.com/wera-as/inspirational-quotes-source/main/quotes-new.json";

    $json_content = file_get_contents($path_to_json);

    $array = json_decode($json_content, true);

    $one_item = $array[rand(0, count($array) - 1)];

    $html_output = "&ldquo;<span class='insp_quote_quote'>" . $one_item['quote'] . "</span>&rdquo;<br><span class='insp_quote_author'>" . $one_item['author'] . "</span><br><br><span class='insp_quote_hart'>Made with ♥️ by <a href='https://wera.no/' target='_blank'>Wera AS</a></span>";

    return $html_output;
}

add_filter('admin_footer_text', 'get_random_inspiration_quote');