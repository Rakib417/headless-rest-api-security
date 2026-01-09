<?php
if (!defined('ABSPATH'))
    exit;

add_action('template_redirect', 'hras_trigger_headless_redirect');

function hras_trigger_headless_redirect()
{
    $frontend_url = get_option('hras_headless_redirect');
    if (empty($frontend_url))
        return;

    $frontend_url = untrailingslashit($frontend_url);

    if (is_admin() || strpos($_SERVER['REQUEST_URI'], '/wp-json') !== false || strpos($_SERVER['REQUEST_URI'], 'wp-login.php') !== false) {
        return;
    }

    $final_destination = $frontend_url . $_SERVER['REQUEST_URI'];
    wp_redirect($final_destination, 301);
    exit;
}