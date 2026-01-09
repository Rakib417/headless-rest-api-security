<?php
if (!defined('ABSPATH'))
    exit;

add_filter('rest_pre_dispatch', 'hras_strict_api_guard', 0, 3); // Priority 0 to run before other plugins
add_filter('xmlrpc_enabled', '__return_false'); // Disable XML-RPC
add_filter('rest_jsonp_enabled', '__return_false'); // Disable JSONP

function hras_strict_api_guard($result, $server, $request)
{
    if (!get_option('hras_enabled'))
        return $result;

    if (is_user_logged_in() && current_user_can('edit_posts')) {
        return $result;
    }

    $current_route = $request->get_route();
    $current_method = $request->get_method();
    $allowed_rules = get_option('hras_whitelisted_routes', []);
    $is_allowed = false;

    if (isset($allowed_rules[$current_route][$current_method])) {
        $is_allowed = true;
    } else {
        foreach ($allowed_rules as $base_route => $methods) {
            // Ensure exact match or sub-path (e.g. /wp/v2/users matching /wp/v2/users/1)
            // but NOT /wp/v2/users-secret
            if (isset($methods[$current_method])) {
                if ($current_route === $base_route || strpos($current_route, $base_route . '/') === 0) {
                    $is_allowed = true;
                    break;
                }
            }
        }
    }

    if (!$is_allowed) {
        return new WP_Error('rest_forbidden_strict', 'Access Denied. API disabled.', ['status' => 403]);
    }

    $server_key = (string) get_option('hras_api_key');
    $client_key = $_SERVER['HTTP_X_API_KEY'] ?? '';
    if (empty($client_key) || !hash_equals($server_key, $client_key)) {
        return new WP_Error('rest_forbidden_key', 'Invalid API Key.', ['status' => 401]);
    }

    $allowed_domain = get_option('hras_allowed_domain');
    if (!empty($allowed_domain)) {

        // Ensure scheme exists for parse_url
        if (strpos($allowed_domain, 'http') !== 0) {
            $allowed_domain = 'https://' . $allowed_domain;
        }

        $origin = $_SERVER['HTTP_ORIGIN'] ?? $_SERVER['HTTP_REFERER'] ?? '';
        if (empty($origin)) {
            return new WP_Error('rest_forbidden_domain', 'Domain unauthorized (No Origin).', ['status' => 403]);
        }

        $allowed_host = parse_url($allowed_domain, PHP_URL_HOST);
        $origin_host = parse_url($origin, PHP_URL_HOST);

        if (!$allowed_host || !$origin_host || strcasecmp($allowed_host, $origin_host) !== 0) {
            return new WP_Error('rest_forbidden_domain', 'Domain unauthorized.', ['status' => 403]);
        }
    }

    return $result;
}