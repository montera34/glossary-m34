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

if (!defined('M34GLOSSARY_CPT')) define('M34GLOSSARY_CPT', $cpt);
if (!defined('M34GLOSSARY_TAX_LETTER')) define('M34GLOSSARY_TAX_LETTER', $tax1);
if (!defined('M34GLOSSARY_TAX_GROUP')) define('M34GLOSSARY_TAX_GROUP', $tax2);

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
		//'menu_icon' => get_template_directory_uri() . '/images/quincem-dashboard-pt-badge.png',
		'hierarchical' => false, // if true this post type will be as pages
		'query_var' => true,
		'supports' => array('title', 'editor','excerpt','author','trackbacks','thumbnail'),
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

?>
