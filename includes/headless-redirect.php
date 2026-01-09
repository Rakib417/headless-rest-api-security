<?php
if (!defined('ABSPATH'))
    exit;

add_action('template_redirect', 'hras_trigger_headless_redirect');

function hras_trigger_headless_redirect()
{

    // 1. Check if the setting is empty
    $frontend_url = get_option('hras_headless_redirect');
    if (empty($frontend_url)) {
        return; // No redirect set, do nothing.
    }

    // 2. Clean URL (remove trailing slash for consistency)
    $frontend_url = untrailingslashit($frontend_url);

    // 3. IGNORE LIST (Do not redirect these)
    // - Admin Dashboard
    // - Login Page
    // - API Requests (wp-json)
    // - Cron jobs or XML-RPC
    if (
        is_admin() ||
        strpos($_SERVER['REQUEST_URI'], '/wp-json') !== false ||
        strpos($_SERVER['REQUEST_URI'], 'wp-login.php') !== false ||
        strpos($_SERVER['REQUEST_URI'], 'wp-cron.php') !== false
    ) {
        return;
    }

    // 4. PERFORM REDIRECT
    // We grab the current path (e.g., /about-us) and append it to the frontend URL
    $request_uri = $_SERVER['REQUEST_URI'];

    $final_destination = $frontend_url . $request_uri;

    // 301 = Permanent Redirect (Good for SEO if moving completely)
    // 302 = Temporary (Better while developing)
    // Let's use 301 for production headless.
    wp_redirect($final_destination, 301);
    exit;
}