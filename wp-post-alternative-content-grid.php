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
    wp_enqueue_script('main-script', plugins_url('assets/js/main.js', __FILE__), array('jquery'), '444null', true);
    wp_enqueue_style('main-style', plugins_url('assets/css/style.css', __FILE__), array(), null, false);

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
            'post_type' => 'post',
            'posts_per_page' => 6,
            'alternate_content' => 'yes',
        ),
        $atts
    );

    $post_type = $atts['post_type'] ? $atts['post_type'] : 'post';

    $content = '<section class="wp-post-alternative-grids">';

    $all_posts = new WP_Query([
        'post_type' => $post_type,
        'posts_per_page' => $atts['posts_per_page'],
        'orderby' => 'date',
        'order' => 'ASC',
        'paged' => 1,
    ]);

    if ($all_posts->found_posts > 0) {
        $content .= get_post_grid_template($all_posts);
    } else {
        $content .= 'No Content found';
    }

    wp_reset_postdata();

    $content .= '</section>';

    // Check if there are more posts than posts_per_page
    if ($all_posts->found_posts > $atts['posts_per_page']) {
        $content .= '<div class="wp-post-grid-load-more">
                <a href="#!" data-post-type="' . $post_type . '" class="btn" id="wp-load-more-post">Load more</a>
            </div>';
    }
    return $content;
}


// Ajax function for load more posts

add_action('wp_ajax_wp_load_more_posts', 'wp_load_more_posts_handler');
add_action('wp_ajax_nopriv_wp_load_more_posts', 'wp_load_more_posts_handler');

function wp_load_more_posts_handler()
{
    $postType = $_POST['postType'];

    $all_posts = new WP_Query([
        'post_type' => $postType,
        'posts_per_page' => 6,
        'orderby' => 'date',
        'order' => 'DESC',
        'paged' => $_POST['paged'],
    ]);

    $content = get_post_grid_template($all_posts);

    $result = [
        'total_page' => $all_posts->max_num_pages, // get total no of pages
        'content' => $content,
    ];

    echo json_encode($result);
    die();
}

function get_post_grid_template($all_posts)
{
    $content = '';
    if ($all_posts->have_posts()) {
        $post_count = 1;
        while ($all_posts->have_posts()) : $all_posts->the_post();
            // Get post data
            $post_title = get_the_title();

            // Limit the excerpt to 20 words
            $post_excerpt = get_the_excerpt();
            $excerpt_words = explode(' ', $post_excerpt);
            if (count($excerpt_words) > 20) {
                $post_excerpt = implode(' ', array_slice($excerpt_words, 0, 20));
                $post_excerpt .= '...'; // Add ellipsis to indicate truncated content
            }

            $post_permalink = get_permalink();
            // $post_author = get_the_author();
            // $post_date = get_the_date();
            // $post_featured_image = get_the_post_thumbnail();

            $post_id = get_the_ID(); // Get the ID of the current post
            $featured_image_url = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'full')[0];

            // Build the HTML for each post
            $even_class = $post_count % 2 == 0 ? 'wp-post-even-grid' : '';

            $single_grid_class = "wp-post-single-grid " . $even_class;
            $content .= '<div class="' . $single_grid_class . '">
                            <h3 class="wp-post-grid-headline">' . $post_title . '</h3>
                            <p class="wp-post-grid-content">' . $post_excerpt . ' </p>
                            <span class="wp-post-read-more">
                                <a class="wp-post-read-more-link" href="' . $post_permalink . '">Learn More &raquo;</a>
                            </span>
                            <img class="wp-post-grid-image"
                            src="' . $featured_image_url . '"
                            alt="">
                        </div>';
            $post_count++;
        endwhile;
    }

    return     $content;
}
