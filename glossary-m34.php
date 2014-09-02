<?php
/*
Plugin Name: Glossary m34
Description: This plugin allows you to create a glossary of terms and to insert it in your website via shortcodes.
Version: 0.1
Author: montera34
Author URI: http://montera34.com
License: GPLv2+
*/

/* EDIT THIS VARS TO CONFIG THE PLUGIN */
$cpt = "glossary"; // custom post type name and slug for permalinks
$tax1 = "letter"; // letters taxonomy name and slug for permalinks
$tax2 = "group"; // groups taxonomy name and slug for permalinks
/* STOP EDIT */

// plugin main activation function
//register_activation_hook( __FILE__, 'm34glossary_activate' );
//function m34glossary_activate() {

	if (!defined('M34GLOSSARY_CPT')) define('M34GLOSSARY_CPT', $cpt);
	if (!defined('M34GLOSSARY_TAX_LETTER')) define('M34GLOSSARY_TAX_LETTER', $tax1);
	if (!defined('M34GLOSSARY_TAX_GROUP')) define('M34GLOSSARY_TAX_GROUP', $tax2);

	// Custom post type and taxonomies
	add_action( 'init', 'm34glossary_create_post_type', 0 );
	add_action( 'init', 'm34glossary_build_taxonomies', 0 );

//} // END plugin main activation function

/* Create CPT glossary */
function m34glossary_create_post_type() {
	// Módulo post type
	register_post_type( M34GLOSSARY_CPT, array(
		'labels' => array(
			'name' => __( 'Glossary','m34glossary' ),
			'singular_name' => __( 'Glossary term','m34glossary' ),
			'add_new_item' => __( 'Add term','m34glossary' ),
			'edit' => __( 'Edit','m34glossary' ),
			'edit_item' => __( 'Edit this term','m34glossary' ),
			'new_item' => __( 'New term','m34glossary' ),
			'view' => __( 'View term','m34glossary' ),
			'view_item' => __( 'View this term','m34glossary' ),
			'search_items' => __( 'Search terms','m34glossary' ),
			'not_found' => __( 'No terms found','m34glossary' ),
			'not_found_in_trash' => __( 'No term in the trash','m34glossary' ),
			'parent' => __( 'Parent','m34glossary' )
		),
		'description' => '',
		'has_archive' => true,
		'public' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => true,
		'menu_position' => 5,
		'menu_icon' => plugins_url( '/images/m34gloss-dashboard-pt.png' , __FILE__),
		'hierarchical' => false, // if true this post type will be as pages
		'query_var' => true,
		'supports' => array('title', 'editor','excerpt','author','trackbacks'),
		'rewrite' => array('slug'=>M34GLOSSARY_CPT,'with_front'=>false),
		'can_export' => true,
		'_builtin' => false,
	));
} /* END register CPT glossary */

/* register taxonomies */
function m34glossary_build_taxonomies() {

	register_taxonomy( M34GLOSSARY_TAX_LETTER, array(M34GLOSSARY_CPT), array(
		'hierarchical' => true,
		'label' => __( 'Letter','m34glossary' ),
		'name' => __( 'Letters','m34glossary' ),
		'query_var' => M34GLOSSARY_TAX_LETTER,
		'rewrite' => array( 'slug' => M34GLOSSARY_TAX_LETTER, 'with_front' => false ),
		'show_admin_column' => true
	) );

	register_taxonomy( M34GLOSSARY_TAX_GROUP, array(M34GLOSSARY_CPT), array(
		'hierarchical' => true,
		'label' => __( 'Group','m34glossary' ),
		'name' => __( 'Groups','m34glossary' ),
		'query_var' => M34GLOSSARY_TAX_GROUP,
		'rewrite' => array( 'slug' => M34GLOSSARY_TAX_GROUP, 'with_front' => false ),
		'show_admin_column' => true
	) );
} /* END register taxonomies */

/* modify posts order in glossary CPT archives */
add_shortcode('m34glossary', 'm34glossary_glossary_order');
function m34glossary_glossary_order( $query ) {
	$posts_args = array(
		'post_type' => M34GLOSSARY_CPT,
		'nopaging' => true,
		'orderby' => 'title',
		'order' => 'ASC'
	);
	$tax_args = array(
		'orderby' => 'name',
		'order' => 'ASC',
	);
	$glossary_posts = get_posts($posts_args);
	$letters = get_terms(M34GLOSSARY_TAX_LETTER,$tax_args);

	foreach ( $letters as $letter ) {
		$glossary_reordered[$letter->term_id]['letter_tit'] = $letter->name;
	}
	foreach ( $glossary_posts as $term ) {
		$term_letters = get_the_terms($term->ID,M34GLOSSARY_TAX_LETTER);
		$term_groups = get_the_terms($term->ID,M34GLOSSARY_TAX_GROUP);
		foreach ( $term_letters as $term_letter ) {
			$term_letter_id = $term_letter->term_id;
		}
		if ( is_array($term_groups) ) {
			$term_groups_out = " [";
			foreach ( $term_groups as $term_group ) {
				$term_group_perma = get_term_link($term_group);
				$term_groups_out .= "<a href='" .$term_group_perma. "' title='" .sprintf( __('View all the terms in %s','m34glossary'),$term_group->name ). "'>" .$term_group->name. "</a>, ";
			}
			$term_groups_out = preg_replace( '/, $/', ']', $term_groups_out );
		} else { $term_groups_out = ""; }
		$term_tit = get_the_title($term->ID);
		$term_perma = get_permalink($term->ID);
		$term_content = apply_filters( 'the_content', $term->post_content );
		$glossary_reordered[$term_letter_id][$term->ID] = "<a href='" .$term_perma. "' title='". $term_tit. "'>". $term_tit. "</a>" .$term_groups_out;
	} // END foreach $glossary

	$glossary_out = "";
	foreach ( $glossary_reordered as $letter_posts ) {
		$count = 0;
		$glossary_out .= "<section>";
		foreach ( $letter_posts as $term_out ) {
			if ( $count == 0 ) { $glossary_out .= "<header><h2 class='m34gloss-sec-tit'>" .$term_out. "</h2>"; }
			else { $glossary_out .= "<div class='m34gloss-term'>" .$term_out. "</div>"; }
			$count++;
		}
		$glossary_out .= "</section>";
	}
	return $glossary_out;

} /* END modify loop in glossary CPT archives */

/* output letters list */
add_shortcode('m34glossary_letters', 'm34glossary_letters');
function m34glossary_letters() {
	$letters = get_terms( M34GLOSSARY_TAX_LETTER );
	if ( is_array($letters) ) {
		$letters_out = "<nav><ul class='m34gloss-letters-nav'>";
		foreach ( $letters as $letter ) {
			$letter_perma = get_term_link($letter);
			$letters_out .= "<li class='m34gloss-letter'><a href='" .$letter_perma. "'>" .$letter->name. "</a></li>";

		}
		$letters_out .= "</ul></nav>";
	} else {
		$letters_out = "<div>" .__('There is no letters in the glossary or they have no terms. Before using this shortcode, please, create some terms and letters.','m34glossary' ). "</div>";
	}
	return $letters_out;
}
/* END output letters list */
?>
