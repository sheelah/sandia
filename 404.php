<?php
/**
 * 404.php
 * @author Sheelah Brennan
 * @license GPL-2.0+
 */

//* Remove default loop
remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action( 'genesis_loop', 'genesis_404' );
/**
 * This function outputs a 404 "Not Found" error message
 *
 * @since 1.6
 */
function genesis_404() {

	echo genesis_html5() ? '<article class="entry">' : '<div class="post hentry">';

		printf( '<h1 class="entry-title">%s</h1>', __( 'Page Not Found', 'sandia' ) );
		echo '<div class="entry-content">';

		?> <p><?php printf( __( 'The page you are looking for no longer exists. Perhaps you can return back to the site\'s <a href="%s">homepage</a> and see if you can find what you are looking for. Or, you can try finding it by using the search form below.', 'genesis', 'sandia' ), esc_url( home_url( '/' ) ) ); ?></p><?php

				echo '<p>' . get_search_form() . '</p>';
		?>

			<h2><?php _e( 'Pages:', 'genesis', 'sandia' ); ?></h4>
			<ul>
				<?php wp_list_pages( 'title_li=' ); ?>
			</ul>

			<h2><?php _e( 'Categories:', 'genesis', 'sandia' ); ?></h4>
			<ul>
				<?php wp_list_categories( 'sort_column=name&title_li=' ); ?>
			</ul>

			<h2><?php _e( 'Authors:', 'genesis', 'sandia' ); ?></h4>
			<ul>
				<?php wp_list_authors( 'exclude_admin=0&optioncount=1' ); ?>
			</ul>

			<h2><?php _e( 'Monthly:', 'genesis', 'sandia' ); ?></h4>
			<ul>
				<?php wp_get_archives( 'type=monthly' ); ?>
			</ul>

			<h2><?php _e( 'Recent Posts:', 'genesis', 'sandia' ); ?></h4>
			<ul>
				<?php wp_get_archives( 'type=postbypost&limit=100' ); ?>
			</ul>

			<?php
			echo '</div>';

		echo genesis_html5() ? '</article>' : '</div>';

}

genesis();
