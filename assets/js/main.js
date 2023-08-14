
jQuery(document).ready(function () {

    // Load more
    let currentPage = 1;
    jQuery('#wp-load-more-post').on('click', function () {

        var postType = jQuery(this).data('post-type') || 'post';

        currentPage++;

        jQuery.ajax({
            type: 'POST',
            url: admin_obj.ajax_url,
            dataType: 'html',
            data: {
                action: 'wp_load_more_posts',
                paged: currentPage,
                postType: postType
            },
            success: function (response) {

                let responseData = JSON.parse(response);

                //Hide load more button
                if (responseData.total_page <= currentPage) {
                    jQuery('#wp-load-more-post').hide();
                }
                jQuery('.wp-post-alternative-grids').append(responseData.content);
            }
        });
    });
});