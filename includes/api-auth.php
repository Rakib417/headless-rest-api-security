<?php
if (!defined('ABSPATH')) {
    exit;
}

add_filter('rest_authentication_errors', 'hras_restrict_rest_api', 99);

function hras_restrict_rest_api($result)
{
    // 1. If there is already an error, pass it through.
    if (is_wp_error($result)) {
        return $result;
    }

    // 2. Check if Master Switch is ON
    if (!get_option('hras_enabled')) {
        return $result;
    }

    // 3. Admin Bypass: Allow logged-in admins/editors to use the API
    if (is_user_logged_in() && (current_user_can('manage_options') || current_user_can('edit_posts'))) {
        return $result;
    }

    // --- STEP A: VALIDATE API KEY / DOMAIN FIRST ---

    $is_authenticated = false;

    // Check 1: API Key
    $received_key = isset($_SERVER['HTTP_X_API_KEY']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_X_API_KEY'])) : '';
    $saved_key = get_option('hras_api_key');

    if (!empty($saved_key) && hash_equals($saved_key, $received_key)) {
        $is_authenticated = true;
    }

    // Check 2: Domain (Alternative to Key)
    if (!$is_authenticated) {
        $allowed_domain = get_option('hras_allowed_domain');
        if (!empty($allowed_domain)) {
            $origin = isset($_SERVER['HTTP_ORIGIN']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_ORIGIN'])) : '';
            if (empty($origin) && isset($_SERVER['HTTP_REFERER'])) {
                $origin = sanitize_text_field(wp_unslash($_SERVER['HTTP_REFERER']));
            }
            if (!empty($origin)) {
                $origin_host = wp_parse_url($origin, PHP_URL_HOST);
                $allowed_host = wp_parse_url($allowed_domain, PHP_URL_HOST);
                if ($origin_host === $allowed_host) {
                    $is_authenticated = true;
                }
            }
        }
    }

    // If NO valid Key and NO valid Domain -> BLOCK EVERYTHING
    if (!$is_authenticated) {
        return new WP_Error(
            'rest_forbidden',
            __('REST API Restricted: Valid API Key or Allowed Domain required.', 'headless-rest-api-security'),
            array('status' => 401)
        );
    }

    // --- STEP B: CHECK WHITELIST (SCOPE) ---
    // If we are here, the user has a valid Key. Now we check if they are allowed to see THIS route.

    // 1. Get Current Route
    $server_uri = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '';
    $current_route = empty($GLOBALS['wp']->query_vars['rest_route']) ? $server_uri : $GLOBALS['wp']->query_vars['rest_route'];
    $current_route = '/' . ltrim($current_route, '/');

    // 2. Get Request Method (GET, POST, etc.)
    $method = isset($_SERVER['REQUEST_METHOD']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_METHOD'])) : 'GET';
    $method = strtoupper($method);

    // 3. Check against Saved Rules
    $whitelisted_routes = get_option('hras_whitelisted_routes', array());
    $is_route_allowed = false;

    foreach ($whitelisted_routes as $route => $methods) {
        // Does the requested URL start with this whitelisted route? (e.g., /wp/v2/posts)
        if (strpos($current_route, $route) === 0) {
            // Is the HTTP Method allowed? (e.g., GET)
            if (!empty($methods[$method])) {
                $is_route_allowed = true;
                break;
            }
        }
    }

    if ($is_route_allowed) {
        return $result; // ✅ Key is valid AND Route is in Whitelist -> ALLOW
    }

    // If Key is valid but Route is NOT checked in settings -> BLOCK
    return new WP_Error(
        'rest_forbidden',
        __('REST API Scope Error: This route is not whitelisted in settings.', 'headless-rest-api-security'),
        array('status' => 403)
    );
}