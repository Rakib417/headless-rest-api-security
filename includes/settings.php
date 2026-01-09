<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_init', 'hras_register_settings');

function hras_register_settings()
{
    // Simple integer sanitization
    register_setting('hras_settings_group', 'hras_enabled', 'intval');

    // URL sanitization
    register_setting('hras_settings_group', 'hras_headless_redirect', 'esc_url_raw');

    // Text sanitization
    register_setting('hras_settings_group', 'hras_api_key', 'sanitize_text_field');
    register_setting('hras_settings_group', 'hras_allowed_domain', 'sanitize_text_field');

    // Custom callback for array sanitization
    register_setting('hras_settings_group', 'hras_whitelisted_routes', 'hras_sanitize_routes_cb');

    add_settings_section('hras_main_section', 'Strict Security Configuration', null, 'hras-settings');

    add_settings_field('hras_enabled', 'Master Switch', 'hras_enabled_cb', 'hras-settings', 'hras_main_section');
    add_settings_field('hras_headless_redirect', 'Headless Frontend URL', 'hras_redirect_cb', 'hras-settings', 'hras_main_section');
    add_settings_field('hras_api_key', 'API Key', 'hras_api_key_cb', 'hras-settings', 'hras_main_section');
    add_settings_field('hras_allowed_domain', 'Allowed Domain', 'hras_domain_cb', 'hras-settings', 'hras_main_section');
    add_settings_field('hras_whitelisted_routes', 'Whitelist Rules', 'hras_routes_grid_cb', 'hras-settings', 'hras_main_section');
}

/**
 * Custom Sanitization Callback for Route Array
 */
function hras_sanitize_routes_cb($input)
{
    if (!is_array($input)) {
        return array();
    }

    $clean = array();
    foreach ($input as $route => $methods) {
        $clean_route = sanitize_text_field($route);
        if (is_array($methods)) {
            foreach ($methods as $method => $value) {
                $clean_method = sanitize_text_field($method);
                $clean[$clean_route][$clean_method] = 1;
            }
        }
    }
    return $clean;
}

/* --- CALLBACKS --- */

function hras_enabled_cb()
{
    $val = get_option('hras_enabled');
    echo '<label><input type="checkbox" name="hras_enabled" value="1" ' . checked(1, $val, false) . '> <strong>Enable Strict Security</strong> (Blocks ALL APIs by default)</label>';
}

function hras_redirect_cb()
{
    $val = get_option('hras_headless_redirect');
    echo '<input type="url" name="hras_headless_redirect" value="' . esc_attr($val) . '" class="regular-text" placeholder="e.g. https://www.mysite.com">';
    echo '<p class="description">Visitors to this API domain will be redirected here.</p>';
}

function hras_api_key_cb()
{
    $key = get_option('hras_api_key');
    if (empty($key)) {
        $key = wp_generate_password(32, false);
    }
    echo '<input type="text" name="hras_api_key" value="' . esc_attr($key) . '" class="regular-text">';
    echo '<p class="description">Required header: <code>X-API-KEY</code></p>';
}

function hras_domain_cb()
{
    $val = get_option('hras_allowed_domain');
    echo '<input type="text" name="hras_allowed_domain" value="' . esc_attr($val) . '" class="regular-text" placeholder="e.g. https://www.mysite.com">';
}

/* 🌟 GRID UI WITH SMART SORTING 🌟 */
function hras_routes_grid_cb()
{
    // FIX: Added phpcs ignore comment because we are intentionally triggering a Core hook
    if (!did_action('rest_api_init')) {
        // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
        do_action('rest_api_init');
    }

    $server = rest_get_server();

    // Safety check: if server is missing (rare), create a dummy to prevent fatal error
    if (!$server) {
        $server = new WP_REST_Server();
        // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
        do_action('rest_api_init', $server);
    }

    $all_routes = array_keys($server->get_routes());
    $saved_rules = get_option('hras_whitelisted_routes', array());
    $http_methods = array('GET', 'POST', 'PUT', 'DELETE');

    // 1. Consolidate Routes
    $display_routes = array();
    foreach ($all_routes as $route) {
        if ($route === '/') {
            continue;
        }
        $parts = explode('/', trim($route, '/'));

        $is_core = (isset($parts[0], $parts[1]) && $parts[0] === 'wp' && $parts[1] === 'v2');

        if ($is_core) {
            $clean_key = '/' . $parts[0] . '/' . $parts[1] . '/' . ($parts[2] ?? '');
        } else {
            $clean_key = '/' . $parts[0] . '/' . ($parts[1] ?? '');
        }

        if (strpos($clean_key, '(?P<') !== false) {
            continue;
        }
        $display_routes[$clean_key] = true;
    }

    // 2. Custom Sorting
    uksort(
        $display_routes,
        function ($a, $b) {
            $priority_map = array(
                '/wp/v2/posts' => 10,
                '/wp/v2/pages' => 20,
                '/wp/v2/users' => 40,
                '/wp/v2' => 80,
                '/' => 999,
            );

            $get_score = function ($route) use ($priority_map) {
                foreach ($priority_map as $key => $score) {
                    if (strpos($route, $key) === 0) {
                        return $score;
                    }
                }
                return 999;
            };

            $score_a = $get_score($a);
            $score_b = $get_score($b);

            if ($score_a !== $score_b) {
                return $score_a - $score_b;
            }
            return strcmp($a, $b);
        }
    );

    ?>
    <style>
        .hras-table-wrapper {
            max-height: 600px;
            overflow-y: auto;
            border: 1px solid #c3c4c7;
            background: #fff;
        }

        .hras-table {
            width: 100%;
            border-collapse: collapse;
        }

        .hras-table th {
            position: sticky;
            top: 0;
            background: #f0f0f1;
            z-index: 10;
            padding: 12px;
            text-align: center;
            border-bottom: 2px solid #c3c4c7;
        }

        .hras-table td {
            border-bottom: 1px solid #eee;
            padding: 8px;
            text-align: center;
            vertical-align: middle;
        }

        .hras-table td.route-name {
            text-align: left;
            background: #f9f9f9;
            font-family: monospace;
            font-weight: 600;
            color: #d63638;
            border-right: 1px solid #eee;
            padding-left: 15px;
        }

        .enable-col {
            background-color: #e5f5fa !important;
            border-right: 1px solid #eee;
        }

        tr.active-row td.route-name {
            color: #008a20;
            background-color: #f6f7f7;
        }

        input[disabled] {
            opacity: 0.3;
            cursor: not-allowed;
        }

        tr[data-type="plugin"] td.route-name {
            border-left: 4px solid #0073aa;
        }

        tr[data-type="core"] td.route-name {
            border-left: 4px solid #d63638;
        }

        tr.active-row[data-type="core"] td.route-name {
            border-left-color: #00a32a;
        }
    </style>

    <div class="hras-table-wrapper">
        <table class="hras-table">
            <thead>
                <tr>
                    <th style="text-align:left; min-width: 250px;">Main API Path</th>
                    <th style="width: 80px; background:#dcdcde; color:#000;">ALLOW</th>
                    <?php foreach ($http_methods as $method): ?>
                        <th style="width: 80px;">
                            <?php echo esc_html($method); ?>
                        </th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($display_routes as $route => $val):
                    $row_id = md5($route);
                    $is_row_active = !empty($saved_rules[$route]);
                    $row_class = $is_row_active ? 'hras-row active-row' : 'hras-row';
                    $row_type = (strpos($route, '/wp/v2') === 0) ? 'core' : 'plugin';
                    ?>
                    <tr class="<?php echo esc_attr($row_class); ?>" id="row-<?php echo esc_attr($row_id); ?>"
                        data-type="<?php echo esc_attr($row_type); ?>">
                        <td class="route-name">
                            <?php echo esc_html($route); ?>
                        </td>
                        <td class="enable-col"><input type="checkbox" class="row-master-toggle" <?php checked($is_row_active, true); ?>></td>
                        <?php
                        foreach ($http_methods as $method):
                            $is_checked = isset($saved_rules[$route][$method]) ? 'checked' : '';
                            $field_name = 'hras_whitelisted_routes[' . esc_attr($route) . '][' . esc_attr($method) . ']';
                            $disabled_attr = $is_row_active ? '' : 'disabled';
                            ?>
                            <td class="check-col"><input type="checkbox" name="<?php echo esc_attr($field_name); ?>" value="1"
                                    class="method-check" <?php echo esc_attr($is_checked); ?>
                                <?php echo esc_attr($disabled_attr); ?>>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const rows = document.querySelectorAll('.hras-row');
            rows.forEach(row => {
                const masterToggle = row.querySelector('.row-master-toggle');
                const methodChecks = row.querySelectorAll('.method-check');
                masterToggle.addEventListener('change', function () {
                    const isEnabled = this.checked;
                    if (isEnabled) {
                        row.classList.add('active-row');
                        methodChecks.forEach(box => { box.removeAttribute('disabled'); box.checked = true; });
                    } else {
                        row.classList.remove('active-row');
                        methodChecks.forEach(box => { box.setAttribute('disabled', 'disabled'); box.checked = false; });
                    }
                });
            });
        });
    </script>
    <?php
}