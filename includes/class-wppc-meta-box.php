<?php

class WPPC_Meta_Box
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_script'));
        add_action('add_meta_boxes', array($this, 'add_meta_box'));
    }

    public function enqueue_script()
    {
        global $post;

        wp_enqueue_script('wppc-meta-box-script', WPPC_URL . 'js/meta-box.js', array('jquery'));

        $args = array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'ajax_nonce' => wp_create_nonce('wppc_ajax_nonce_action'),
            'post_id' => isset($post->ID) ? $post->ID : ''
        );

        // Set ajax object
        wp_localize_script('wppc-meta-box-script', 'ajax_object', $args);
    }

    public function add_meta_box()
    {
        // Get all available post types
        $post_types = get_post_types();

        // Add meta box to all available post types
        foreach ($post_types as $post_type) {
            if ($post_type != 'page') {
                add_meta_box(
                    'wppc_primary_category',
                    'Primary Category',
                    array($this, 'meta_box_html'),
                    $post_type,
                    'side'
                    );
            }
        }
    }

    public function meta_box_html()
    {
        global $post;

        $args = array(
            'fields' => 'names'
        );

        // Get post categories
        $post_categories = wp_get_object_terms($post->ID, 'category', $args);

        // Unset uncategorized from post category array
        $post_categories = self::unset_uncategorized_from_categories($post_categories);

        if (count($post_categories) < 1) {
            $html = '<p>First add a category</p>';

            // Unset primary category from post
            self::save_primary_category($post->ID, '0');
        } else {
            // Get post _wppc_primary_category field
            $current_primary_category = get_post_meta($post->ID, '_wppc_primary_category', true);

            $html = '<label for=primary_category">Primary Category</label>';
            $html .= '<div id="results"></div>';
            $html .= '<select name="primary_category" id="primary_category" style="width: 100%; margin-top: 10px;">';
            $html .= '<option value="0">Select primary category</option>';

            foreach ($post_categories as $category) {
                $html .= '<option value="' . $category . '" ' . selected($current_primary_category, $category, false) . '>' . $category . '</option>';
            }

            $html .= '</select>';

            if (!in_array($current_primary_category, $post_categories)) {
                // Unset primary category from post
                self::save_primary_category($post->ID, '0');
            }
        }
        echo $html;
    }

    public static function unset_uncategorized_from_categories($categories)
    {
        if (($key = array_search('Uncategorized', $categories)) !== false) {
            unset($categories[$key]);
        }
        return $categories;
    }

    public static function save_primary_category($post_id, $primary_category)
    {
        if ($primary_category == '0') {
            update_post_meta($post_id, '_wppc_primary_category', '');

            return '<p style="margin: 10px 0 0 0; background-color: yellow; padding: 5px">Primary category was unset.</p>';
        } else {
            update_post_meta($post_id, '_wppc_primary_category', $primary_category);

            return '<p style="margin: 10px 0 0 0; background-color: #aed581; padding: 5px">Primary category was set to "' . $primary_category . '".</p>';
        }
    }
}