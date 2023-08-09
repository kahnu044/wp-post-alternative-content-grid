<?php

/**
 * Plugin Name: WP Post Alternative Content Grid
 * Plugin URI: https://github.com/kahnu044/wp-post-alternative-content-grid
 * Description: This plugin provides the shortcodes for posts grid.
 * Version: 0.1
 * Author: Kahnu
 * Author URI: https://github.com/kahnu044
 **/

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
        'posts_per_page' => 6,
        'orderby' => 'date',
        'order' => 'DESC',
        'paged' => 1,
    ]);

    if ($all_posts->have_posts()) {
        $content = '<ul class="publication-list">';
        while ($all_posts->have_posts()) : $all_posts->the_post();
            // Get post data
            $post_title = get_the_title();
            $post_excerpt = get_the_excerpt();
            $post_permalink = get_permalink();
            $post_author = get_the_author();
            $post_date = get_the_date();
            $post_featured_image = get_the_post_thumbnail();

            // Build the HTML for each post
            $content .= '<li class="publication-item">';
            $content .= '<h2 class="publication-title"><a href="' . $post_permalink . '">' . $post_title . '</a></h2>';
            $content .= '<div class="publication-excerpt">' . $post_excerpt . '</div>';
            $content .= '<div class="publication-author">' . __('Author: ', 'textdomain') . $post_author . '</div>';
            $content .= '<div class="publication-date">' . __('Date: ', 'textdomain') . $post_date . '</div>';
            $content .= '<div class="publication-featured-image">' . $post_featured_image . '</div>';
            $content .= '</li>';
        endwhile;
        $content .= '</ul>';
    }
    wp_reset_postdata();

    $content .= '<div class="btn__wrapper">
                    <a href="#!" class="btn btn__primary" id="load-more">Load more</a>
                </div>';

    // Process and manipulate content if needed
    $processed_content = '<p>' . $content . '</p>';

    // Return the processed content
    return $processed_content;
}