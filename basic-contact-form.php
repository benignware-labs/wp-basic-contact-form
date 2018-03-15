<?php

/**
 Plugin Name: Basic Contact Form
 Plugin URI: http://github.com/benignware-labs/wp-basic-contact-form
 Description: Yet another Wordpress contact form plugin
 Text Domain: basic-contact-form
 Domain Path: /languages
 Version: 0.0.6
 Author: Rafael Nowrotek, Benignware
 Author URI: http://benignware.com
 License: MIT
*/

require_once 'basic-contact-form-helpers.php';

/**
 * Load plugin textdomain.
 *
 * @since 0.0.6
 */
function basic_contact_form_load_textdomain() {
  $path = basename(dirname( __FILE__ )) . '/languages';
  $result = load_plugin_textdomain( 'basic-contact-form', false, $path );
  if (!$result) {
    echo "Plugin textdomain could not be loaded";
    exit;
  }
}
add_action( 'plugins_loaded', 'basic_contact_form_load_textdomain' );


function basic_contact_form_mail($to, $subject = '', $body = '', $headers = '') {
  $headers = array(
    'Content-Type: text/html; charset=UTF-8',
    'From: '. get_bloginfo('name') .' <'. get_bloginfo('admin_email') .'>'
  );
  $headers = 'From: '. get_bloginfo('name') .' <'. get_bloginfo('admin_email') .'>' . "\r\n";

  $result = wp_mail( $to, $subject, $body, $headers );
  return $result;
}

function basic_contact_form_sanitize_output($html, $form_name = null) {
  $is_valid = $form_name ? false : true;
  if (!$is_valid) {
    // Parse input
    $doc = new DOMDocument();
    @$doc->loadHTML('<?xml encoding="utf-8" ?>' . $html );
    // Get the container element
    $container = $doc->getElementsByTagName('body')->item(0)->firstChild;
    $container->setAttribute("data-$form_name", 'test');
    // Get the form element
    $form = $doc->getElementsByTagName( 'form' )->item(0);
    if ($form) {
      $action = $form->getAttribute('action') ?: $_SERVER['REQUEST_URI'];
      $form->setAttribute('action', $action);
      $method = $form->getAttribute('method') ?: 'POST';
      $form->setAttribute('method', $method);
    } else {
      // TODO: Handle error "Output must contain a form"
    }
    if (!$is_valid) {
      $html = preg_replace('~(?:<\?[^>]*>|<(?:!DOCTYPE|/?(?:html|head|body))[^>]*>)\s*~i', '', $doc->saveHTML());
    }
  }
  return $html;
}

/**
 * Basic Contact Form Shortcode
 */
function basic_contact_form_shortcode( $atts = array() ) {
  $messages = array(
    'empty' => __('This field cannot be empty', 'basic-contact-form'),
    'email_invalid' => __('You have to enter a valid e-mail address', 'basic-contact-form')
  );

  $atts = shortcode_atts(array(
    'to' => get_bloginfo('admin'),
    'fields' => 'name,email,subject,message',
    'required' => 'email',
    'title' => __('Get in contact with us!', 'basic-contact-form'),
    'description' => __('Please use our contact form for your inquiry', 'basic-contact-form'),
    'template' => dirname(__FILE__) . '/basic-contact-form-template.php'
  ), $atts, 'basic_contact_form');

  // Get arrays from string lists
  if (is_string($atts['fields'])) {
    $atts['fields'] = basic_contact_form_split_val($atts['fields']);
  }

  if (is_string($atts['required'])) {
    $atts['required'] = basic_contact_form_split_val($atts['required']);
  }

  // Extract attributes
  foreach($atts as $key => $value) {
    $$key = $atts[$key];
  }

  // Get request data
  $request = basic_contact_form_get_request();

  $errors = array();

  // Check against method and header
  // && $request['headers']['X-Ajaxform'] === 'basic-contact-form'
  if ( $request['method'] === 'POST') {
    $data = $request['data'];

    // If the required fields are empty, switch $error to TRUE and set the result text to the shortcode attribute named 'error_empty'
    foreach ( $required as $key ) {
      $value = trim( $data[$key] );
      if ( empty( $value ) ) {
        $errors[$key] = $messages['empty'];
      }
    }

    // And if the e-mail is not valid, switch $error to TRUE and set the result text to the shortcode attribute named 'error_noemail'
    if ( !$errors['email'] && !is_email( $data['email'] ) ) {
      $errors['email'] = $messages['email_invalid'];
    }

    if ( !count(array_keys($errors)) ) {
      // Success
      $success = true;

      // Get message data
      $user_email = $data['email'];
      // TODO: Dynamic message with template
      $mail_content = <<<EOT
Hello Admin,\n
a contact request has been issued by $user_email.\n
EOT;
      foreach ($data as $key => $value) {
        $mail_content.= "$key:\t\t\t\t$value\n";
      }
      // Actually send mail to admin
      basic_contact_form_mail($to, 'Contact Form Request', $mail_content);
    }
  }

  $output = basic_contact_form_render($template, array(
    'title' => $title,
    'description' => $description,
    'required' => $required,
    'fields' => $fields,
    'success' => $success,
    'errors' => $errors,
    'action' => $action,
    'data' => $data
  ));

  // Sanitize form with identifier
  $output = basic_contact_form_sanitize_output($output, 'basic-contact-form');

  return $output;
}
add_shortcode( 'basic_contact_form', 'basic_contact_form_shortcode' );

function basic_contact_form_enqueue_scripts() {
  wp_enqueue_script( 'basic-contact-form', plugin_dir_url( __FILE__ ) . 'dist/wp-basic-contact-form.js' );
}
add_action('wp_enqueue_scripts', 'basic_contact_form_enqueue_scripts');

?>
