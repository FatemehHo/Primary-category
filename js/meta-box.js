jQuery(document).ready(function ($) {

    // Pass data to wppc_save_primary_category function and get response through WordPress ajax API
    function wppc_js_save_primary_category(primary_category) {

        var data = {
            action: 'save_primary_category',
            security: ajax_object.ajax_nonce,
            post_id: ajax_object.post_id,
            primary_category: primary_category,
        };

        // Display response
        $.post(ajax_object.ajax_url, data, function (response) {
            $('#results').html(response);
        });

    }

    // Get edit page dropdown value and pass it to function
    $("#primary_category").change(function () {

        wppc_js_save_primary_category(this.value);

    });

});