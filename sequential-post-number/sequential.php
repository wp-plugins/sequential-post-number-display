<?php
/*
Plugin Name: Sequential Post Number Display
Plugin URI: http://thegreyparrots.com/
Description: This plugin allow you to assign a sequential number to posts and you can display it with each post
Version: 0.0.1
Author: The Grey Parrots
Author URI: http://thegreyparrots.com/
License: GPLv2 or later
*/
/*
<?php echo do_shortcode('[sqNumber]');?>
<?php echo get_post_meta(get_the_ID(),'incr_number',true); ?>
*/
function updatePostNumbers() {
    global $wpdb;
    $queryString = "SELECT $wpdb->posts.* FROM $wpdb->posts 
                 WHERE $wpdb->posts.post_status = 'publish' 
                 AND $wpdb->posts.post_type = 'post' 
                 ORDER BY $wpdb->posts.post_date ASC";
    $pagePosts = $wpdb->get_results($queryString, OBJECT);
    $counts = 0 ;
    if($pagePosts):
	    foreach ($pagePosts as $post):
		$counts++;
		add_post_meta($post->ID, 'incr_number', $counts, true);
		update_post_meta($post->ID, 'incr_number', $counts);
	    endforeach;
    endif;
}

function sqNumberCall($atts) {
	return get_post_meta(get_the_ID(),'incr_number',true);
}
add_shortcode('sqNumber', 'sqNumberCall');

add_action ( 'publish_post', 'updatePostNumbers', 11 );
add_action ( 'deleted_post', 'updatePostNumbers' );
register_activation_hook( __FILE__, 'updatePostNumbers' );
add_action ( 'edit_post', 'updatePostNumbers' );
?>
