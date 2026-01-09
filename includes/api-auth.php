<?php
if (!defined('ABSPATH'))
    exit;

add_filter('rest_pre_dispatch', 'hras_strict_api_guard', 10, 3);

function hras_strict_api_guard($result, $server, $request)
{
    if (!get_option('hras_enabled'))
        return $result;

    // ✅ Admin Bypass (Logged-in users)
    if (is_user_logged_in() && current_user_can('edit_posts')) {
        return $result;
    }

    $current_route = $request->get_route();
    $current_method = $request->get_method();
    $allowed_rules = get_option('hras_whitelisted_routes', []);
    $is_allowed = false;

    // 🔍 Check Whitelist (Prefix Match)
    if (isset($allowed_rules[$current_route][$current_method])) {
        $is_allowed = true;
    } else {
        foreach ($allowed_rules as $base_route => $methods) {
            if (strpos($current_route, $base_route) === 0 && isset($methods[$current_method])) {
                $is_allowed = true;
                break;
            }
        }
    }

    if (!$is_allowed) {
        return new WP_Error('rest_forbidden_strict', 'Access Denied. API disabled.', ['status' => 403]);
    }

    // 🔑 Check API Key
    $server_key = get_option('hras_api_key');
    $client_key = $_SERVER['HTTP_X_API_KEY'] ?? '';
    if (empty($client_key) || !hash_equals($server_key, $client_key)) {
        return new WP_Error('rest_forbidden_key', 'Invalid API Key.', ['status' => 401]);
    }

    // 🌐 Check Domain
    $allowed_domain = get_option('hras_allowed_domain');
    if (!empty($allowed_domain)) {
        $allowed_domain = trim($allowed_domain);
        $origin = $_SERVER['HTTP_ORIGIN'] ?? $_SERVER['HTTP_REFERER'] ?? '';
        if (strpos($origin, $allowed_domain) === false) {
            return new WP_Error('rest_forbidden_domain', 'Domain unauthorized.', ['status' => 403]);
        }
    }

    return $result;
}