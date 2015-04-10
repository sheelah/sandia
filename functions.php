<?php

/**
 * Theme Setup
 * @since 1.0.0
 *
 * This setup function attaches all of the site-wide functions
 * to the correct hooks and filters. All the functions themselves
 * are defined below this setup function.
 *
 */

add_action( 'genesis_setup','child_theme_setup', 15 );
function child_theme_setup() {

	/****************************************
	Define child theme version
	*****************************************/

	define( 'CHILD_THEME_VERSION', filemtime( get_stylesheet_directory() . '/style.css' ) );


	/****************************************
	Include theme helper functions
	*****************************************/

	include_once( CHILD_DIR . '/lib/theme-helpers.php' );


	/****************************************
	Setup child theme functions
	*****************************************/

	include_once( CHILD_DIR . '/lib/theme-functions.php' );


	/****************************************
	Backend
	*****************************************/

	// Image Sizes
	// add_image_size( $name, $width = 0, $height = 0, $crop = false );

	// Clean up Head
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );

	// Structural Wraps
	add_theme_support( 'genesis-structural-wraps', array(
		'header',
		'nav',
		'subnav',
		'inner',
		'footer-widgets',
		'footer'
	) );

	// Unregister Secondary Nav Menu
	add_theme_support( 'genesis-menus', array(
		'primary' => 'Primary Navigation Menu'
	) );

	// Sidebars
	unregister_sidebar( 'sidebar-alt' );
	genesis_register_sidebar( array(
		'name' => 'Footer',
		'id' => 'custom-footer'
	) );
	//add_theme_support( 'genesis-footer-widgets', 4 );

	// Execute shortcodes in widgets
	// add_filter( 'widget_text', 'do_shortcode' );

	// Remove Unused Page Layouts
	genesis_unregister_layout( 'content-sidebar-sidebar' );
	genesis_unregister_layout( 'sidebar-sidebar-content' );
	genesis_unregister_layout( 'sidebar-content-sidebar' );

	// Remove Unused User Settings
	// remove_action( 'show_user_profile', 'genesis_user_options_fields' );
	// remove_action( 'edit_user_profile', 'genesis_user_options_fields' );
	// remove_action( 'show_user_profile', 'genesis_user_archive_fields' );
	// remove_action( 'edit_user_profile', 'genesis_user_archive_fields' );
	// remove_action( 'show_user_profile', 'genesis_user_seo_fields' );
	// remove_action( 'edit_user_profile', 'genesis_user_seo_fields' );
	// remove_action( 'show_user_profile', 'genesis_user_layout_fields' );
	// remove_action( 'edit_user_profile', 'genesis_user_layout_fields' );

	// Editor Styles
	add_editor_style( 'editor-style.css' );

	// Remove Genesis Theme Settings Metaboxes
	add_action( 'genesis_theme_settings_metaboxes', 'sandia_remove_genesis_metaboxes' );

	// Reposition Genesis Layout Settings Metabox
	remove_action( 'admin_menu', 'genesis_add_inpost_layout_box' );
	add_action( 'admin_menu', 'sandia_add_inpost_layout_box' );

	// Setup Child Theme Settings
	include_once( CHILD_DIR . '/lib/child-theme-settings.php' );

	// Prevent File Modifications
	define ( 'DISALLOW_FILE_EDIT', true );

	// Set Content Width
	// The 3 numbers correspond to content width of the default, small and large layouts.
    // The default layout refers to 2-column layouts; small refers to 3-column layouts
    // and large refers to the full-width content layout.
	$content_width = apply_filters( 'content_width', 740, 740, 1140 );

	// Add support for custom background
	add_theme_support( 'custom-background' );

	// Add support for custom header
	add_theme_support( 'genesis-custom-header', array(
		'width' => 960,
		'height' => 80,
		'header_image' => get_stylesheet_directory_uri() . '/assets/images/header.png'
	) );

	// Remove Dashboard Meta Boxes
	add_action( 'wp_dashboard_setup', 'sandia_remove_dashboard_widgets' );

	// Change Admin Menu Order
	add_filter( 'custom_menu_order', 'sandia_custom_menu_order' );
	add_filter( 'menu_order', 'sandia_custom_menu_order' );

	// Hide Admin Areas that are not used
	add_action( 'admin_menu', 'sandia_remove_menu_pages' );

	// Remove default link for images
	add_action( 'admin_init', 'sandia_imagelink_setup', 10 );

	// Define custom post type capabilities for use with Members
	// add_action( 'admin_init', 'sandia_add_post_type_caps' );


	/****************************************
	Frontend
	*****************************************/

	// Add HTML5 markup structure
	add_theme_support( 'html5', array(
		'comment-list',
		'search-form',
		'comment-form',
		'gallery',
		'caption',
	) );

	// Add viewport meta tag for mobile browsers
	add_theme_support( 'genesis-responsive-viewport' );

	// Load Apple touch icon in header
	add_action( 'wp_head', 'sandia_apple_touch_icon', 9 );

	// Remove Edit link
	add_filter( 'genesis_edit_post_link', '__return_false' );

	// Remove Default Genesis footer and add custom footer
	remove_action( 'genesis_footer', 'genesis_do_footer' );
	add_action( 'genesis_footer', 'sandia_footer' );

	// Enqueue Scripts
	add_action( 'wp_enqueue_scripts', 'sandia_scripts' );

	// Enqueue Web Fonts
	add_action( 'wp_enqueue_scripts', 'sandia_web_fonts' );

	// Remove Query Strings From Static Resources
	add_filter( 'script_loader_src', 'sandia_remove_script_version', 15, 1 );
	add_filter( 'style_loader_src', 'sandia_remove_script_version', 15, 1 );

	// Remove Read More Jump
	add_filter( 'the_content_more_link', 'sandia_remove_more_jump_link' );

	// Remove the Site Description
	remove_action( 'genesis_site_description', 'genesis_seo_site_description' );

	// Set favicon
	add_filter( 'genesis_pre_load_favicon', 'sandia_favicon_filter' );


	/****************************************
	Theme Views
	*****************************************/

	include_once( CHILD_DIR . '/lib/theme-views.php' );


	/****************************************
	Require Plugins
	*****************************************/

	require_once( CHILD_DIR . '/lib/class-tgm-plugin-activation.php' );
	require_once( CHILD_DIR . '/lib/theme-require-plugins.php' );

	add_action( 'tgmpa_register', 'sandia_register_required_plugins' );

}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function sandia_customize_preview_js() {
    wp_enqueue_script( 'sandia_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20150325', true );
}
add_action( 'customize_preview_init', 'sandia_customize_preview_js' );

/****************************************
Misc Theme Functions
*****************************************/

// Unregister the superfish scripts
add_action( 'custom_disable_superfish', 'sandia_unregister_superfish' );

// Filter Yoast SEO Metabox Priority
add_filter( 'wpseo_metabox_prio', 'sandia_filter_yoast_seo_metabox' );

