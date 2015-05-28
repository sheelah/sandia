<?php

/****************************************
Backend Functions
*****************************************/

/**
 * Load the child theme textdomain for internationalization.
 *
 * Must be loaded before Genesis Framework /lib/init.php is included.
 * Translations can be filed in the /languages/ directory.
 */
function sandia_i18n() {
	load_child_theme_textdomain( 'sandia', get_template_directory() . '/languages' );
}

/**
 * Remove Genesis Theme Settings Metaboxes
 */
function sandia_remove_genesis_metaboxes( $_genesis_theme_settings_pagehook ) {
	//remove_meta_box( 'genesis-theme-settings-feeds',      $_genesis_theme_settings_pagehook, 'main' );
	//remove_meta_box( 'genesis-theme-settings-header',     $_genesis_theme_settings_pagehook, 'main' );
	//remove_meta_box( 'genesis-theme-settings-nav',        $_genesis_theme_settings_pagehook, 'main' );
	//remove_meta_box( 'genesis-theme-settings-breadcrumb', $_genesis_theme_settings_pagehook, 'main' );
	//remove_meta_box( 'genesis-theme-settings-comments',   $_genesis_theme_settings_pagehook, 'main' );
	//remove_meta_box( 'genesis-theme-settings-posts',      $_genesis_theme_settings_pagehook, 'main' );
	//remove_meta_box( 'genesis-theme-settings-blogpage',   $_genesis_theme_settings_pagehook, 'main' );
	//remove_meta_box( 'genesis-theme-settings-scripts',    $_genesis_theme_settings_pagehook, 'main' );
}

/**
 * Reposition Genesis Layout Metabox
 */
function sandia_add_inpost_layout_box() {
	if ( ! current_theme_supports( 'genesis-inpost-layouts' ) )
		return;
	foreach ( (array) get_post_types( array( 'public' => true ) ) as $type ) {
		if ( post_type_supports( $type, 'genesis-layouts' ) )
			add_meta_box( 'genesis_inpost_layout_box', __( 'Layout Settings', 'genesis' ), 'genesis_inpost_layout_box', $type, 'normal', 'low' );
	}
}

/**
 * Remove Dashboard Meta Boxes
 */
function sandia_remove_dashboard_widgets() {
	global $wp_meta_boxes;
	// unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
	// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
	// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
	// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
}

/**
 * Change Admin Menu Order
 */
function sandia_custom_menu_order( $menu_ord ) {
	if ( !$menu_ord ) return true;
	return array(
		// 'index.php', // Dashboard
		// 'separator1', // First separator
		// 'edit.php?post_type=page', // Pages
		// 'edit.php', // Posts
		// 'upload.php', // Media
		// 'gf_edit_forms', // Gravity Forms
		// 'genesis', // Genesis
		// 'edit-comments.php', // Comments
		// 'separator2', // Second separator
		// 'themes.php', // Appearance
		// 'plugins.php', // Plugins
		// 'users.php', // Users
		// 'tools.php', // Tools
		// 'options-general.php', // Settings
		// 'separator-last', // Last separator
	);
}

/**
 * Hide Admin Areas that are not used
 */
function sandia_remove_menu_pages() {
	// remove_menu_page('link-manager.php');
}

/**
 * Remove default link for images
 */
function sandia_imagelink_setup() {
	$image_set = get_option( 'image_default_link_type' );
	if ($image_set !== 'none') {
		update_option( 'image_default_link_type', 'none' );
	}
}

/**
 * Add SVG support in media uploader.
 */
function sandia_enable_svg_upload( $mimes ) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}

function sandia_customize_tinymce( $init ) {
	$init['block_formats'] = 'Paragraph=p; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6; pre=pre; address=address';
	return $init;
}


/****************************************
Frontend
*****************************************/

/**
 * Load apple touch icon in header
 */
function sandia_apple_touch_icon() {
	echo '<link rel="apple-touch-icon" href="' . get_stylesheet_directory_uri() . '/assets/images/apple-touch-icon.png" />' . "\n";
}

/**
 * Favicon
 */
function sandia_favicon_filter() {
	return get_stylesheet_directory_uri() . '/assets/images/favicon.ico';
}

/**
 * Fix SVG sizing
 *
 * By default, WordPress renders all SVG files uploaded through the Media
 * Uploader with both width and height at "1".
 */
function sandia_resize_svg( $output ) {

	// Replace width of "1" with a new width of "100%" and height of "1"
    // with a new height of "auto"
	$output = str_replace(
		'width="1" height="1"',
		'width="100%" height="auto"',
		$output );
	return $output;
}

/**
 * Footer
 */
function sandia_footer() {
	echo '<div class="one-half first" id="footer-left">' . wpautop( genesis_get_option( 'footer-left', 'child-settings' ) ) . '</div>';
	echo '<div class="one-half" id="footer-right">' . wpautop( genesis_get_option( 'footer-right', 'child-settings' ) ) . '</div>';
}

/**
 * Enqueue Web Fonts
 */
function sandia_web_fonts() {
	$font_base_url = '//fonts.googleapis.com/css';
	$font_query     = '?family=Lato:300,400,700,400italic';
	wp_enqueue_style( 'sandia-web-fonts', $font_base_url . $font_query );
}

/**
 * Enqueue Script
 */
function sandia_scripts() {
	if ( !is_admin() ) {
		// Custom plugins and scripts
		wp_enqueue_script( 'customscripts', get_stylesheet_directory_uri() . '/assets/js/sandia.min.js', array('jquery'), NULL, true );
	}
}

/**
 * Remove Query Strings From Static Resources
 */
function sandia_remove_script_version( $src ){
	$parts = explode( '?ver', $src );
	return $parts[0];
}

/**
 * Define custom post type capabilities for use with Members
 */
function sandia_add_post_type_caps() {
	// sandia_add_capabilities( 'portfolio' );
}

/**
 * Add Continue Reading links to posts on archives and blog index pages
 */
function sandia_show_continue_reading_link() {
	if ( is_home() || is_archive() ) {
		printf( '<a href="%s" rel="bookmark" class="continue-reading">%s<span class="screen-reader-text"> %s</span></a>', get_permalink(), __( 'Continue Reading', 'sandia'), get_the_title() );
	}
}

/**
 * Add a link first thing after the body element that will skip to the inner element.
 */
function sandia_add_skip_link() {
	echo '<a class="skip-link screen-reader-text" href="#inner">' . __('Skip to content', 'sandia') . '</a>';
}


/**
 * Adds the title to the "read more" link in archives
 */
function sandia_read_more_link( $link ) {
	return '...<br /> <a href="'. get_permalink() .'" class="more-link">' .
	       __( 'Read more', 'genesis' ) . '<span class="more-link-title screen-reader-text"> ' .
	       __( 'about ', 'sandia' ) . get_the_title() .
	       "</span></a>";
}

/**
 * Adds the title to the "older comments" link in comment navigation
 */
function sandia_prev_comments_link_text( $link ) {
	return sprintf( '&laquo; Older Comments <span class="screen-reader-text"> %s %s</span>', __( 'on', 'sandia'), get_the_title() );
}

/**
 * Adds the title to the "newer comments" link in comment navigation
 */
function sandia_next_comments_link_text( $link ) {
	return sprintf( 'Newer Comments &raquo;<span class="screen-reader-text"> %s %s</span>', __( 'on', 'sandia'), get_the_title() );
}

/**
 * Bind JS handlers to make customizer preview reload changes asynchronously
 */
function sandia_customize_preview_js() {
	wp_enqueue_script( 'sandia_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20150325', true );
}


/****************************************
Misc Theme Functions
*****************************************/

/**
 * Filter Yoast SEO Metabox Priority
 */
function sandia_filter_yoast_seo_metabox() {
	return 'low';
}


/**
 * Adds "inner" id to the site-inner content/sidebar wrap.
 * Using inner, since Genesis uses this id when HTML5 is disabled.
 * @param  array $attributes Array of element attributes
 * @return array             Same array of element attributes with the id added
*/
function sandia_add_content_id( $attributes ) {
	$attributes['id'] = "inner";
	return $attributes;
}
