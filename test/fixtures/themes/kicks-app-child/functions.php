<?php


add_action('after_setup_theme', function() {
  remove_theme_support( 'custom-header' );
}, 11);

add_action('wp_enqueue_scripts', function() {
  // wp_enqueue_style( 'oswald', 'https://fonts.googleapis.com/css?family=Oswald:300,400,700&display=swap', false );
  // wp_enqueue_style( 'open-sans-x', 'http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,400,700,300', false );

  wp_enqueue_style( 'kicks-app-child-style', get_stylesheet_directory_uri() . '/style.css', false );
}, 11);

// Customize Basic Contact Form
add_filter('shortcode_atts_basic_contact_form', function($out, $pairs, $atts, $shortcode) {
  return array_merge($out, array(
    'theme' => array(
      'classes' => array(
        'bcf-form' => 'needs-validation',
        'is-invalid' => 'was-validated',
        'bcf-field' => 'mb-3',
        'bcf-field-checkbox' => 'form-check',
        'bcf-input' => 'form-control',
        'bcf-textarea' => 'form-control',
        'bcf-submit-button' => 'btn btn-primary',
        'bcf-message' => 'invalid-feedback',
        'bcf-checkbox-label' => 'form-check-label',
        'bcf-checkbox' => 'form-check-input'
      )
    )
  ), $atts);
}, 10, 4);

add_action( 'phpmailer_init', function($phpmailer) {
  $phpmailer->Host = 'mailhog';
  $phpmailer->Port = 1025;
  $phpmailer->IsSMTP();
});
