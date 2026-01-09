<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('template_redirect', 'hras_trigger_headless_redirect');

function hras_trigger_headless_redirect()
{
    $frontend_url = get_option('hras_headless_redirect');
    if (empty($frontend_url)) {
        return;
    }

    $frontend_url = untrailingslashit($frontend_url);

    // 1. Safe extraction of REQUEST_URI
    // We check if it exists, unslash it to remove PHP escape characters, and sanitize it as a URL.
    $request_uri = isset($_SERVER['REQUEST_URI']) ? esc_url_raw(wp_unslash($_SERVER['REQUEST_URI'])) : '';

    if (empty($request_uri)) {
        return;
    }

    // 2. Ignore Admin, Login, API
    // Using the sanitized $request_uri variable now
    if (is_admin() || strpos($request_uri, '/wp-json') !== false || strpos($request_uri, 'wp-login.php') !== false) {
        return;
    }

    // 3. Build the final destination URL
    $final_destination = $frontend_url . $request_uri;

    // 4. Get the host from your setting
    // FIX: Replaced parse_url with wp_parse_url for compatibility
    $allowed_host = wp_parse_url($frontend_url, PHP_URL_HOST);

    // 5. Add it to the Allowed Redirect Hosts whitelist
    if ($allowed_host) {
        add_filter(
            'allowed_redirect_hosts',
            function ($hosts) use ($allowed_host) {
                $hosts[] = $allowed_host;
                return $hosts;
            }
        );
    }

    // 6. Use wp_safe_redirect
    wp_safe_redirect($final_destination, 301);
    exit;
}