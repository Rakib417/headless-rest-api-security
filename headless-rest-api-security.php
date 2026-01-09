<?php
/*
Plugin Name: Headless REST API Security
Description: Strict Whitelist Security & Headless Redirects for WordPress.
Version: 2.0
Author: Md. Rakib Ullah
Author URI: https://www.linkedin.com/in/rakib417/
Author Email: rakib417@gmail.com
Text Domain: headless-rest-api-security
*/

if (!defined('ABSPATH'))
    exit;

// 1. Load Admin Interface
require_once plugin_dir_path(__FILE__) . 'includes/admin-page.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-menu.php';
require_once plugin_dir_path(__FILE__) . 'includes/settings.php';

// 2. Load Security Logic
require_once plugin_dir_path(__FILE__) . 'includes/api-auth.php';
require_once plugin_dir_path(__FILE__) . 'includes/headless-redirect.php';