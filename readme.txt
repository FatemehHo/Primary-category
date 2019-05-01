=== WordPress Primary Category Project ===

This plugin allows publishers to designate a primary category for posts and custom post types (not page).
On the front-end, you can query for posts and custom post types based on their primary categories with two shortcodes.

When you choose your primary category from meta box dropdown, you do not need to click on update button, just wait a few second to see the green message appear. 

First one: [wppc-list name='primary_category_name']
With this shortcode you can query for posts base on primary category name and see posts list.

Second one: [wppc-input]
With this shortcode you can query for posts base on dropdown and see ajax posts list.

In this plugin we add new meta box to edit page.
We dont override WordPress category meta box (to prevent conflict with other plugins like Yoast)

After the post has been updated, in case of category change, if current primary category doesnt exist in selected category, primary category field will be unset.
After updating post you need refresh to see new category in primary category meta box dropdown.