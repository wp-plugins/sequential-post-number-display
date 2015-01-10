<?php
/*
Plugin Name: Sequential Post Number Display
Plugin URI: http://thegreyparrots.com/
Description: This plugin allow you to assign a sequential number to posts and you can display it with each post, now you can configure it for Pages, Categories & Tags Pages too.
Version: 1.1.0
Author: The Grey Parrots
Author URI: http://thegreyparrots.com/
License: GPLv2 or later
*/

add_action('admin_enqueue_scripts', 'includeScript');
add_action('admin_menu', 'createMenu');
add_action('publish_post', 'updatePostNumbers', 11);
add_action('deleted_post', 'updatePostNumbers', 11);
add_action('edit_post', 'updatePostNumbers', 11);
add_shortcode('sqNumber', 'sqNumberCall');
register_activation_hook(__FILE__, 'updatePostNumbersAndOptions');
register_deactivation_hook(__FILE__, 'deletePostNumbersAndOptions');

function includeScript() {
	wp_enqueue_script('sqNumber_js', plugin_dir_url(__FILE__).'sequential.js', array('jquery'));
}

function createMenu() {
	add_options_page('Sequential Post Configuration Admin Settings', 'Sequential Post', 'manage_options', 'post_config', 'sqNumberSettingsPage');
}

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
			update_post_meta($post->ID, 'tgp_incr_number', $counts);
	    endforeach;
	endif;
}

function sqNumberCall() {
	$selectedHomePage = get_option('tgp_sqNumber_home_page');
	
	if(is_string($selectedHomePage) && !$selectedHomePage && is_home() && (get_option('show_on_front') == "posts" || !get_option('page_on_front'))) return false;
	
	$pages = get_pages(); 
	$selectedPageIdList = get_option('tgp_sqNumber_page');
	if(is_array($selectedPageIdList)) {		//this section will be processed when option name exists
		foreach($pages as $page) {
			if(in_array($page->ID, $selectedPageIdList)) continue;
			elseif(is_page($page->ID)) return false;
		}
	}
	
	$categories = get_categories(); 
	$selectedCatIdList = get_option('tgp_sqNumber_cat');
	if(is_array($selectedCatIdList)) {		//this section will be processed when option name exists
		foreach($categories as $category) {
			if(in_array($category->term_id, $selectedCatIdList)) continue;
			elseif(is_category($category->term_id)) return false;
		}
	}
	
	$tags = get_tags(); 
	$selectedTagIdList = get_option('tgp_sqNumber_tag');
	if(is_array($selectedTagIdList)) {		//this section will be processed when option name exists
		foreach($tags as $tag) {
			if(in_array($tag->term_id, $selectedTagIdList)) continue;
			elseif(is_tag($tag->term_id)) return false;
		}
	}
	
	return get_post_meta(get_the_ID(), 'tgp_incr_number', true);
}

function sqNumberSettingsPage() { 
		if(isset($_GET['tab']) && $_GET['tab'] != "") $current_tab = $_GET['tab'];
		else $current_tab = 'page_config';
		adminTabs($current_tab);
		if($current_tab == 'page_config') pageConfiguration();
		elseif($current_tab == 'cat_config') categoryConfiguration();
		elseif($current_tab == 'tag_config') tagConfiguration();
}

function adminTabs($current_tab) {
	$tabs = array(
			'page_config' => 'Page Configuration', 
			'cat_config' => 'Category Configuration', 
			'tag_config' => 'Tag Configuration'
	); 
?>
    <h2 class="nav-tab-wrapper">
		<?php 
		foreach($tabs as $tab_slug => $tab_name): 
			if($current_tab == $tab_slug) $isActiveTab = ' nav-tab-active';
			else $isActiveTab = '';
			echo "<a class='nav-tab".$isActiveTab."' href='?page=post_config&tab=".$tab_slug."'>$tab_name</a>";
		endforeach; 
		?>
	</h2>
<?php
}
//Content for Page Configuration tab
function pageConfiguration() {
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		if(isset($_POST['saveAllPages']) || isset($_POST['home_page'])) {
			update_option('tgp_sqNumber_page', array_filter($_POST['page']));
			update_option('tgp_sqNumber_home_page', array_filter($_POST['home_page']));
		}
	}
?>
	<form method="post" action="">
		<div class="allPages">
			<h2>Page Configuration </h2>
			<p>To enable Sequential Post Number kindly put the checkbox on and click on "Save Changes"!!</p>
			<label><input id="chkAllPages" type="checkbox" /><span id="page"></span></label>	<!-- span used for Select All/Deselect All in javascript -->
			<br><br>
			<?php
			$selectedHomePage = get_option('tgp_sqNumber_home_page'); 
			if($selectedHomePage === false) $homeChecked = 'checked = "checked"';	//When no option is entried
			elseif($selectedHomePage) $homeChecked = 'checked = "checked"';
			else $homeChecked = "";
			if(get_option('show_on_front') == "posts" || !get_option('page_on_front'))
				echo '<input type="hidden" name="home_page" /><label><input class="pagechkbox blogPage" type="checkbox" value="homePageId" name="home_page" '.$homeChecked.'/>Home Page / Blog Page<br/></label>';
			
			$pages = get_pages(); 
			$selectedPageIdList = get_option('tgp_sqNumber_page');	
			foreach($pages as $page):
				if($selectedPageIdList === false) $checked = 'checked = "checked"';	//When no option is entried
				elseif(in_array($page->ID, $selectedPageIdList)) $checked = 'checked = "checked"';
				else $checked = "";
				$class = "";
				if(get_option('page_for_posts') && $page->ID == get_option('page_for_posts')) {
					$class = 'blogPage';
					$page->post_title .= " / Blog Page";
					$checked = $homeChecked;
				}
				echo '<input type="hidden" name="page[]" /><label><input class="pagechkbox '.$class.'" type="checkbox" value="'.$page->ID.'" name="page[]" '.$checked.'/>'.$page->post_title.'<br/></label>';
			endforeach;
			?>
			<p class="submit">
				<input type="submit" name="saveAllPages" class="button-primary" value="Save Changes" />
			</p>
		</div> 
	</form>
<?php 
}
//Content for Category Configuration tab
function categoryConfiguration() { 
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		if(isset($_POST['saveCat']) && isset($_POST['cat'])) update_option('tgp_sqNumber_cat', array_filter($_POST['cat']));
	}
?>
	<form method="post" action="">
		<div class="category">
			<h2>Category Configuration </h2>
			<p>To enable Sequential Post Number kindly put the checkbox on and click on "Save Changes"!!</p>
			<label><input id="chkAllCatFiles" type="checkbox" /><span id="cat"></span></label>	<!-- span used for Select All/Deselect All in javascript -->
			<br><br>
			<?php 
			$categories = get_categories(); 
			$selectedCatIdList = get_option('tgp_sqNumber_cat');
			//creating checkboxes for all categories
			foreach ($categories as $category):
				if($selectedCatIdList === false) $checked = 'checked = "checked"';	//When no option is entried
				elseif(in_array($category->cat_ID, $selectedCatIdList)) $checked = 'checked = "checked"';
				else $checked = '';
				echo '<input type="hidden" name="cat[]" /><label><input class="catchkbox" type="checkbox" name="cat[]" value="'.$category->cat_ID.'" '.$checked.'/>'.$category->name.'<br/></label>';			
			endforeach;
			?>
			<p class="submit">
				<input type="submit" name="saveCat" class="button-primary" value="Save Changes" />
			</p>
		</div>
	</form>
<?php 
} 
//Content for Tag Configuration tab
function tagConfiguration() { 
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		if(isset($_POST['saveTag'])) update_option('tgp_sqNumber_tag', array_filter($_POST['tag']));
	}
?>
	<form method="post" action="">
		<div class="tag">
			<h2>Tag Configuration </h2>
			<p>To enable Sequential Post Number kindly put the checkbox on and click on "Save Changes"!!</p>
			<label><input id="chkAllTagFiles" type="checkbox" /><span id="tag"></span></label>	<!-- span used for Select All/Deselect All in javascript -->
			<br><br>
			<?php
			$tags = get_tags();
			$selectedTagIdList = get_option('tgp_sqNumber_tag');
			//creating checkboxes for all tags
			foreach ($tags as $tag):
				if($selectedTagIdList === false) $checked = 'checked = "checked"';	//When no option is entried
				elseif(in_array($tag->term_id, $selectedTagIdList)) $checked = 'checked = "checked"';
				else $checked = '';
				echo '<input type="hidden" name="tag[]" /><label><input class="tagchkbox" type="checkbox" name="tag[]" value="'.$tag->term_id.'" '.$checked.'> '.$tag->name.'<br/></label>';
			endforeach;
			?>
			<p class="submit">
				<input type="submit" name="saveTag" class="button-primary" value="Save Changes" />
			</p>
		</div>
	</form>
<?php 
}
//On plugin activation
function updatePostNumbersAndOptions() {
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
			update_post_meta($post->ID, 'tgp_incr_number', $counts);
	    endforeach;
	endif;
}
//On plugin deactivation
function deletePostNumbersAndOptions() {
	global $wpdb;
    $queryString = "SELECT $wpdb->posts.* FROM $wpdb->posts 
                 	WHERE $wpdb->posts.post_status = 'publish' 
                 	AND $wpdb->posts.post_type = 'post'";
    $pagePosts = $wpdb->get_results($queryString, OBJECT); 
    if($pagePosts):
	    foreach ($pagePosts as $post):
			delete_post_meta($post->ID, 'tgp_incr_number');
	    endforeach;
	endif;
	
	delete_option('tgp_sqNumber_home_page');
	delete_option('tgp_sqNumber_page');
	delete_option('tgp_sqNumber_cat');
	delete_option('tgp_sqNumber_tag');
}
?>
