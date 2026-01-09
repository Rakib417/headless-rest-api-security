<?php
/*
Plugin Name: Headless REST API Security
Description: The best REST API protection to secure Headless WordPress and block unauthorized data access. The essential security solution for Next.js, React, and mobile app backends. API Security locks down your WordPress REST API against scrapers and bots by adopting a strict "whitelist" model to prevent your site from exposing sensitive data. You can manage access controls and monitor your API security directly from your "API Security" dashboard.
Version: 2.0
Author: Md. Rakib Ullah
Author Email: rakib417@gmail.com
Text Domain: api-security
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