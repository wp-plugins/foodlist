<?php

// get tag meta by tag_id
function get_foodlist_menu_tag_meta($tag_id, $key = '', $single = false) {
    return get_metadata('foodlist_menu_tag', $tag_id, $key, $single);
}

// update menu tag meta
function update_foodlist_menu_tag_meta($tag_id, $meta_key, $meta_value, $prev_value = '') {
    return update_metadata('foodlist_menu_tag', $tag_id, $meta_key, $meta_value, $prev_value);
}

// add menu tag meta
function add_foodlist_menu_tag_meta($tag_id, $meta_key, $meta_value, $unique = false) {
    return add_metadata('foodlist_menu_tag', $tag_id, $meta_key, $meta_value, $unique);
}

// delete menu tag meta
function delete_foodlist_menu_tag_meta($tag_id, $meta_key, $meta_value = '') {
    return delete_metadata('foodlist_menu_tag', $tag_id, $meta_key, $meta_value);
}

// delete menu tag metadata on tag deletion
add_action('delete_fl-menu-tag', 'delete_foodlist_menu_tag_meta_all');
function delete_foodlist_menu_tag_meta_all($tag_id) {
    global $wpdb;
    $meta_ids = $wpdb->get_col($wpdb->prepare(
        "SELECT meta_id FROM $wpdb->foodlist_menu_tagmeta WHERE foodlist_menu_tag_id = %d ", $tag_id
    ));
	foreach ($meta_ids as $mid) {
		delete_metadata_by_mid('foodlist_menu_tag', $mid);
    }
}

// this function is used to allow native wordpress api to work witht the custom metadata tables
function fl_setup_wpdb_meta_table(){
    global $wpdb;
    $wpdb->foodlist_menu_tagmeta = $wpdb->prefix.'foodlist_menu_tagmeta';
}
add_action('init', 'fl_setup_wpdb_meta_table');
