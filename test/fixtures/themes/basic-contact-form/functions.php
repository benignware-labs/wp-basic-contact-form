<?php

// Enqueue Scripts
add_action('wp_enqueue_scripts', function() {
  wp_register_style('twentyseventeen-style', get_template_directory_uri(). '/style.css');
  wp_enqueue_style('twentyseventeen-style', get_template_directory_uri(). '/style.css');
  wp_enqueue_style( 'childtheme-style', get_stylesheet_directory_uri().'/style.css', array('twentyseventeen-style') );
  wp_enqueue_style('bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css');
  wp_enqueue_script('popper', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js', array( 'jquery' ), '', true);
  wp_enqueue_script('bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', array( 'jquery' ), '', true);
  wp_enqueue_script('turbolinks', 'https://cdn.jsdelivr.net/npm/turbolinks@5.2.0/dist/turbolinks.min.js', null, '', false);
});

// Init bootstrap hooks
if (function_exists('wp_bootstrap_hooks')) {
  wp_bootstrap_hooks();
}

// Customize Basic Contact Form
add_filter('shortcode_atts_basic_contact_form', function($out, $pairs, $atts, $shortcode) {
  return array_merge($out, array(
    'template' => get_theme_file_path() . '/contact-form.php'
  ), $atts);
}, 10, 4);
