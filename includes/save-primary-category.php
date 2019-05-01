<?php
function wppc_save_primary_category()
{
    // Check ajax nonce
    check_ajax_referer('wppc_ajax_nonce_action', 'security');

    $post_id = intval($_POST['post_id']);
    $primary_category = sanitize_text_field($_POST['primary_category']);

    if (current_user_can('edit_posts') && $post_id) {
        // Set primary category from post
        echo WPPC_Meta_Box::save_primary_category($post_id, $primary_category);
    }

    // this is required to terminate immediately and return a proper response
    wp_die();
}

// For users that are logged in
add_action('wp_ajax_save_primary_category', 'wppc_save_primary_category');