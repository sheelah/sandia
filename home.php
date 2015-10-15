<?php
/**
 * Template Name: Home
 *
 * Description: A page template to show the blog index
 * @package sandia
 */

// Get rid of blog index page title
remove_action( 'genesis_before_loop', 'genesis_do_posts_page_heading' );

genesis();
