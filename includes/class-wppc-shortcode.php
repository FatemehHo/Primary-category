<?php
class WPPC_Shortcode
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, 'register_script'));
        add_shortcode('wppc-input', array($this, 'primary_category_input'));
        add_shortcode('wppc-list', array($this, 'primary_category_list'));
    }

    public function register_script()
    {
        wp_register_script('wppc-shortcode-script', WPPC_URL . 'js/shortcode.js', array('jquery'));
    }

    // Define wppc-input shortcode
    public function primary_category_input()
    {
        wp_enqueue_script('wppc-shortcode-script');

        $args = array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'ajax_nonce' => wp_create_nonce('wppc_ajax_nonce_action')
        );

        // Set ajax object
        wp_localize_script('wppc-shortcode-script', 'ajax_object', $args);

        $html = '<form action="">';
        $html .= '<select name="" id="primary_category_select">';
        $html .= '<option value="0">Select a category</option>';

        $args = array(
            'taxonomy' => 'category',
            'hide_empty' => false,
            'fields' => 'names'
        );

        // Get all available categories
        $categories = get_terms($args);

        // Unset uncategorized from category array
        $categories = WPPC_Meta_Box::unset_uncategorized_from_categories($categories);

        foreach ($categories as $category) {
            $html .= '<option value="' . $category . '">' . $category . '</option>';
        }

        $html .= '</select>';
        $html .= '</form>';
        $html .= '<div id="results"></div>';

        return $html;
    }

    // Define wppc-list shortcode
    public function primary_category_list($atts)
    {
        if (isset($atts['name'])) {
            $atts = shortcode_atts(
                array(
                    'name' => 'Uncategorized'
                ),
                $atts
            );

            $atts['name'] = sanitize_text_field($atts['name']);

            // Display posts list
            return self::shortcode_html($atts['name']);

        } else {
            return '<p>Specify category in shortcode.</p>';
        }
    }

    public static function shortcode_html($primary_category)
    {
        if (term_exists($primary_category, 'category') !== null) {
            if ($primary_category == 'Uncategorized') {
                return '<p>Uncategorized is not a primary category.</p>';
            }

            $args = array(
                'post_type' => 'any',
                'meta_key' => '_wppc_primary_category',
                'meta_value' => $primary_category
            );

            // Get all posts with match meta_key
            $the_query = new WP_Query($args);

            // Posts Loop
            if ($the_query->have_posts()) {
                $html = '<ul>';

                while ($the_query->have_posts()) {
                    $the_query->the_post();
                    $html .= '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
                }

                $html .= '</ul>';

                // Restore original Post Data
                wp_reset_postdata();

                return $html;
            } else {
                return '<p>There is no post with this primary category.</p>';
            }

        } else {
            return '<p>Category not exist.</p>';
        }
    }
}