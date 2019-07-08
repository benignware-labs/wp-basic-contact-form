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
    'themme' => array(
      'classes' => array(
        'bcf-input' => 'form-control'
      )
    )
  ), $atts);
}, 10, 4);


add_filter( 'pre_custom_archive_template', function($template, $post_id, $post_type) {
  return 'archive-job.php';
}, 11, 3);
