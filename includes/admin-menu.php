<?php
if (!defined('ABSPATH'))
    exit;

add_action('admin_menu', 'hras_add_admin_menu');

function hras_add_admin_menu()
{
    add_menu_page(
        'Headless REST API Security', // Page Title
        'Headless API Security',      // Sidebar Menu Title
        'manage_options',             // Capability
        'hras-settings',              // SLUG
        'hras_render_admin_page',     // Callback
        'dashicons-shield',           // Icon
        80                            // Position
    );
}