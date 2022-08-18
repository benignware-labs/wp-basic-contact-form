<?php

/**
 Plugin Name: Basic Contact Form
 Plugin URI: http://github.com/benignware-labs/wp-basic-contact-form
 Description: Yet another Wordpress contact form plugin
 Text Domain: basic-contact-form
 Domain Path: /languages
 Version: 1.0.0
 Author: Rafael Nowrotek, Benignware
 Author URI: http://benignware.com
 License: MIT
*/

require_once 'lib/functions.php';
require_once 'lib/helpers.php';
require_once 'lib/settings.php';

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load plugin textdomain
add_action( 'plugins_loaded', function() {
  load_plugin_textdomain( 'basic-contact-form', false, basename(dirname( __FILE__ )) . '/languages' );
});

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
		'basic-contact-form-editor', // Handle.
		plugins_url( 'dist/blocks/blocks.js',  __FILE__ ), // Block.build.js: We register the block here. Built with Webpack.
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), // Dependencies, defined above.
		null, // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: filemtime — Gets file modification time.
		true // Enqueue the script in the footer.
	);

	// Register block editor styles for backend.
	wp_register_style(
    'basic-contact-form-editor', // Handle.
		plugins_url( 'dist/blocks/blocks.css',  __FILE__ ), // Block editor CSS.
		array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
		null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
	);

	wp_set_script_translations(
    'basic-contact-form-editor',
    'basic-contact-form',
    plugin_dir_path( __FILE__ ) . 'languages'
  );
});

add_action( 'init', function() { // phpcs:ignore
	register_block_type(
		'basic-contact-form/form', array(
      'textdomain' => 'basic-contact-form',
			// Enqueue blocks.style.build.css on both frontend & backend.
			'style'         => 'basic-contact-form-css',
			// Enqueue blocks.build.js in the editor only.
			'editor_script' => 'basic-contact-form-editor',
			// Enqueue blocks.editor.build.css in the editor only.
			'editor_style'  => 'basic-contact-form-editor',
			'render_callback' => function($attributes, $content) {
				$params = basic_contact_form_snakeify_keys($attributes);

				return basic_contact_form_shortcode($params, $content);
			}
		)
	);
});

// Enqueue plugin scripts
add_action('wp_enqueue_scripts', function() {
  if (basic_contact_form_has_captcha()) {
    wp_enqueue_script( 'recaptcha', 'https://www.google.com/recaptcha/api.js', array(), false, true);
  }

  wp_register_script('remoteform', plugin_dir_url( __FILE__ ) . 'dist/remoteform.js');
  wp_enqueue_script('remoteform');

  wp_enqueue_script( 'basic-contact-form', plugin_dir_url( __FILE__ ) . 'dist/contact-form.js' );
  wp_enqueue_style( 'basic-contact-form', plugin_dir_url( __FILE__ ) . 'dist/contact-form.css' );
});

/**
 * Basic Contact Form Shortcode
 */
add_shortcode( 'basic_contact_form', 'basic_contact_form_shortcode');

?>
