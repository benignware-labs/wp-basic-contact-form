<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'block_categories_all', function($categories, $post) {
	return array_merge(
		$categories,
		array(
			array(
				'slug' => 'forms',
				'title' => __( 'Forms', 'basic-contact-form' ),
			),
		)
	);
}, 10, 2);

/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * Assets enqueued:
 * 1. blocks.style.build.css - Frontend + Backend.
 * 2. blocks.build.js - Backend.
 * 3. blocks.editor.build.css - Backend.
 *
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */


// Hook: Block assets.
add_action( 'init', function() { // phpcs:ignore
	// Register block styles for both frontend + backend.
	wp_register_style(
		'basic-contact-form-css', // Handle.
		plugins_url( 'dist/blocks/style-blocks.css',  __FILE__ ), // Block style CSS.
		array( 'wp-editor' ), // Dependency to include the CSS after it.
		null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: File modification time.
	);

	// Register block editor script for backend.
	wp_register_script(
		'basic-contact-form-js', // Handle.
		plugins_url( 'dist/blocks/blocks.js',  __FILE__ ), // Block.build.js: We register the block here. Built with Webpack.
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), // Dependencies, defined above.
		null, // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: filemtime — Gets file modification time.
		true // Enqueue the script in the footer.
	);

	// Register block editor styles for backend.
	wp_register_style(
    'basic-contact-form-editor-css', // Handle.
		plugins_url( 'dist/blocks/blocks.css',  __FILE__ ), // Block editor CSS.
		array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
		null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
	);

	/**
	 * Register Gutenberg block on server-side.
	 *
	 * Register the block on server-side to ensure that the block
	 * scripts and styles for both frontend and backend are
	 * enqueued when the editor loads.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type#enqueuing-block-scripts
	 * @since 1.16.0
	 */
	register_block_type(
		'basic-contact-form/form', array(
			// Enqueue blocks.style.build.css on both frontend & backend.
			'style'         => 'basic-contact-form-css',
			// Enqueue blocks.build.js in the editor only.
			'editor_script' => 'basic-contact-form-js',
			// Enqueue blocks.editor.build.css in the editor only.
			'editor_style'  => 'basic-contact-form-editor-css',
			'render_callback' => function($attributes, $content) {
				$params = basic_contact_form_snakeify_keys($attributes);

				return basic_contact_form_shortcode($params, $content);
			}
		)
	);
});
