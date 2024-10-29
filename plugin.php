<?php
/*
Plugin Name: Automated Keywords Generator
Plugin URI: http://mr.hokya.com/automated-keywords-generator
Description: Automatically adds keywords and description meta tag on every page based on title, tags, categories, etc. Simple but powerful !
Version: 2.25
Author: Julian Widya Perdana
Author URI: http://mr.hokya.com/
*/

function akg_notify () {
	echo '<p>You are using <strong><a href="http://mr.hokya.com/automated-keywords-generator" target="_blank">Automated Keywords Generator</a></strong> to improve your SEO</p>';
}

function akg_parse ($str) {
	$parsed = str_replace(" ",", ",$str);
	if (strpos($str," ")) return $str.", ".$parsed;
	else return $str;
}

function akg_act () {
	$name = get_option("blogname");
	$desc = get_option("blogdescription");
	
	if (is_tag()) $title = single_tag_title('',false);
	if (is_category()) $title = single_cat_title('',false);
	if (is_single() || is_page()) {
		$add = "";
		$p = get_query_var("p");
		$post = get_post($p);
		$title = single_post_title('',false);
		
		$cats = get_the_category($post->ID);
		if (is_array($cats)) {
			foreach ($cats as $cats) {
				$add .= ", ".$cats->name;
			}
		}
		
		$tags = get_the_tags($post->ID);
		if (is_array($tags)) {
			foreach ($tags as $tags) {
				$add .= ", ".$tags->name;
			}
		}
		
		$description = substr(strip_tags($post->post_content),0,200);
	}
	if (!is_home()) {
		echo '<meta name="keywords" content="'.akg_parse($title).', '.akg_parse($name).$add.'" />';
		echo '<meta name="description" content="'.$description.'" />';
	} else {
		echo '<meta name="keywords" content="'.akg_parse($desc).', '.$name.'" />';
		echo '<meta name="description" content="'.$desc.'" />';
	}
}

add_action('wp_head','akg_act');
add_action('rightnow_end','akg_notify');

?>