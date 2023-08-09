<?php

/**
 * Plugin Name: WP Post Alternative Content Grid
 * Plugin URI: https://github.com/kahnu044/wp-post-alternative-content-grid
 * Description: This plugin provides the shortcodes for posts grid.
 * Version: 0.1
 * Author: Kahnu
 * Author URI: https://github.com/kahnu044
 **/

// Enqueue CSS and Js
function wp_post_grid_styles_and_scripts()
{
    wp_enqueue_script('main-script', plugins_url('assets/js/main.js', __FILE__), array('jquery'), null, true);

    // Localize the script with data
    wp_localize_script(
        'main-script',
        'admin_obj',
        array(
            'ajax_url' => admin_url('admin-ajax.php')
        )
    );
}
add_action('wp_enqueue_scripts', 'wp_post_grid_styles_and_scripts');


// Define the shortcode and its callback function
add_shortcode('wp_post_custom_post_grid', 'wp_post_custom_post_grid_handler');

// Callback function for the shortcode
function wp_post_custom_post_grid_handler($atts, $content = null)
{
    $atts = shortcode_atts(
        array(
            'total' => 10,
            'alternate_content' => 'yes',
        ),
        $atts
    );

    $all_posts = new WP_Query([
        'post_type' => 'post',
        'posts_per_page' => 2,
        'orderby' => 'date',
        'order' => 'DESC',
        'paged' => 1,
    ]);

    if ($all_posts->have_posts()) {
        $content = '<ul class="wp-grid-post-list">';
        while ($all_posts->have_posts()) : $all_posts->the_post();
            // Get post data
            $post_title = get_the_title();
            $post_excerpt = get_the_excerpt();
            $post_permalink = get_permalink();
            $post_author = get_the_author();
            $post_date = get_the_date();
            $post_featured_image = get_the_post_thumbnail();

            // Build the HTML for each post
            $content .= '<li class="wp-grid-post-item">';
            $content .= '<h2 class="wp-grid-post-title"><a href="' . $post_permalink . '">' . $post_title . '</a></h2>';
            $content .= '<div class="wp-grid-post-excerpt">' . $post_excerpt . '</div>';
            $content .= '<div class="wp-grid-post-author">' . __('Author: ', 'textdomain') . $post_author . '</div>';
            $content .= '<div class="wp-grid-post-date">' . __('Date: ', 'textdomain') . $post_date . '</div>';
            $content .= '<div class="wp-grid-post-featured-image">' . $post_featured_image . '</div>';
            $content .= '</li>';
        endwhile;
        $content .= '</ul>';
    }
    wp_reset_postdata();

    $content .= '<div class="btn__wrapper">
                    <a href="#!" class="btn" id="wp-load-more-post">Load more</a>
                </div>';

    return $content;
}


// Ajax function for load more posts

add_action('wp_ajax_wp_load_more_posts', 'wp_load_more_posts_handler');
add_action('wp_ajax_nopriv_wp_load_more_posts', 'wp_load_more_posts_handler');

function wp_load_more_posts_handler()
{
    $all_posts = new WP_Query([
        'post_type' => 'post',
        'posts_per_page' => 2,
        'orderby' => 'date',
        'order' => 'DESC',
        'paged' => $_POST['paged'],
    ]);

    $content = '';

    if ($all_posts->have_posts()) {
        while ($all_posts->have_posts()) : $all_posts->the_post();

            $post_title = get_the_title();
            $post_excerpt = get_the_excerpt();
            $post_permalink = get_permalink();
            $post_author = get_the_author();
            $post_date = get_the_date();
            $post_featured_image = get_the_post_thumbnail();

            $content .= '<li class="wp-grid-post-item">';
            $content .= '<h2 class="wp-grid-post-title"><a href="' . $post_permalink . '">' . $post_title . '</a></h2>';
            $content .= '<div class="wp-grid-post-excerpt">' . $post_excerpt . '</div>';
            $content .= '<div class="wp-grid-post-author">' . __('Author: ', 'textdomain') . $post_author . '</div>';
            $content .= '<div class="wp-grid-post-date">' . __('Date: ', 'textdomain') . $post_date . '</div>';
            $content .= '<div class="wp-grid-post-featured-image">' . $post_featured_image . '</div>';
            $content .= '</li>';
        endwhile;
    }

    echo $content;
    exit;
}