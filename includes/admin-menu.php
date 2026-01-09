<?php
if (!defined('ABSPATH'))
    exit;

add_action('admin_menu', 'hras_add_admin_menu');

function hras_add_admin_menu()
{
    add_menu_page(
        'API Security',           // Page Title
        'API Security',           // Menu Title (What you see in the sidebar)
        'manage_options',         // Capability
        'hras-settings',          // Slug (Keeps the connection to settings.php)
        'hras_render_admin_page', // Callback
        'dashicons-shield',       // Shield Icon
        80                        // Position
    );
}