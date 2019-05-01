<?php
/**
 * Plugin Name: WordPress Primary Category Project
 * Description: Plugin that allows publishers to designate a primary category for posts and custom post types.
 * Version:     1.0.0
 * Author:      Fatemeh Homatash
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define constant
define('WPPC_URL', plugin_dir_url(__FILE__));

// Include files
require_once dirname(__FILE__) . '/includes/class-wppc-meta-box.php';
require_once dirname(__FILE__) . '/includes/class-wppc-shortcode.php';
require_once dirname(__FILE__) . '/includes/save-primary-category.php';
require_once dirname(__FILE__) . '/includes/get-posts.php';

new WPPC_Meta_Box();

new WPPC_Shortcode();