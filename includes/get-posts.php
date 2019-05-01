<?php
function wppc_get_posts()
{
    // Check ajax nonce
    check_ajax_referer('wppc_ajax_nonce_action', 'security');

    $primary_category = sanitize_text_field($_POST['primary_category']);

    if ($primary_category != '0') {
        // Display posts list
        echo WPPC_Shortcode::shortcode_html($primary_category);
    }

    // this is required to terminate immediately and return a proper response
    wp_die();
}

// For users that are logged in
add_action('wp_ajax_get_posts', 'wppc_get_posts');

// For users that are not logged in
add_action('wp_ajax_nopriv_get_posts', 'wppc_get_posts');