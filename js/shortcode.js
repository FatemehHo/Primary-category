jQuery(document).ready(function ($) {

    // Pass data to wppc_get_posts function and get response through WordPress ajax API
    function wppc_js_get_posts(primary_category) {

        var data = {
            action: 'get_posts',
            security: ajax_object.ajax_nonce,
            primary_category: primary_category,
        };

        // Display response
        $.post(ajax_object.ajax_url, data, function (response) {
            $('#results').html(response);
        });

    }

    // Get front-end dropdown value and pass it to function
    $("#primary_category_select").change(function () {

        wppc_js_get_posts(this.value);

    });

});