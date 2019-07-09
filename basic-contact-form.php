<?php

/**
 Plugin Name: Basic Contact Form
 Plugin URI: http://github.com/benignware-labs/wp-basic-contact-form
 Description: Yet another Wordpress contact form plugin
 Text Domain: basic-contact-form
 Domain Path: /languages
 Version: 0.2.0-beta.4
 Author: Rafael Nowrotek, Benignware
 Author URI: http://benignware.com
 License: MIT
*/

require_once 'lib/functions.php';
require_once 'lib/helpers.php';
require_once 'lib/settings.php';

require_once plugin_dir_path( __FILE__ ) . 'editor.php';

// Load plugin textdomain
add_action( 'plugins_loaded', function() {
  load_plugin_textdomain( 'basic-contact-form', false, basename(dirname( __FILE__ )) . '/languages' );
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
