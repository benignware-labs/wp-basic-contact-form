<?php

/**
 Plugin Name: Basic Contact Form
 Plugin URI: http://github.com/benignware-labs/wp-basic-contact-form
 Description: The most basic contact-form ever
 Version: 0.0.3
 Author: Rafael Nowrotek, Benignware
 Author URI: http://benignware.com
 License: MIT
*/

function basic_contact_form_mail($to, $subject = '', $body = '', $headers = '') {
  $headers = array(
    'Content-Type: text/html; charset=UTF-8',
    'From: '. get_bloginfo('name') .' <'. get_bloginfo('admin_email') .'>'
  );
  $headers = 'From: '. get_bloginfo('name') .' <'. get_bloginfo('admin_email') .'>' . "\r\n";

  $result = wp_mail( $to, $subject, $body, $headers );
  return $result;
}

function basic_contact_form_get_request_headers() {
    $headers = array();
    foreach($_SERVER as $key => $value) {
        if (substr($key, 0, 5) <> 'HTTP_') {
            continue;
        }
        $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
        $headers[$header] = $value;
    }
    return $headers;
}

function basic_contact_form_get_request() {
  $form_data = array();
  // This part fetches everything that has been POSTed, sanitizes them and lets us use them as $form_data['subject']
  foreach ( $_POST as $field => $value ) {
    if ( get_magic_quotes_gpc() ) {
      $value = stripslashes( $value );
    }
    $form_data[$field] = strip_tags( $value );
  }
  $request = array(
    'headers' => basic_contact_form_get_request_headers(),
    'fields' => $form_data,
    'method' => $_SERVER['REQUEST_METHOD']
  );
  return $request;
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
      $form->setAttribute('method', 'POST');
    } else {
      // TODO: Handle error "Output must contain a form"
    }
    if (!$is_valid) {
      $html = preg_replace('~(?:<\?[^>]*>|<(?:!DOCTYPE|/?(?:html|head|body))[^>]*>)\s*~i', '', $doc->saveHTML());
    }
  }
  return $html;
}

function basic_contact_form_sanitize_atts($atts, $default) {
  $result = array();
  foreach($fields as $key => $value) {
    if (array_key_exists($permitted) || in_array($key, $permitted)) {
      $result[$key] = $value;
    }
  }
  return $result;
}

function basic_contact_form_render($template, $fields) {
  foreach($fields as $key => $value) {
    $$key = $fields[$key];
  }
  ob_start();
  include $template;
  $output = ob_get_contents();
  ob_end_clean();
  return $output;
}

/**
 * Basic Contact Form Shortcode
 */
function basic_contact_form_shortcode( $atts = array() ) {
  $required = array( 'email' ); // Set required fields
  $messages = array(
    'empty' => 'This field cannot be empty',
    'email_invalid' => 'You have to enter a valid e-mail address'
  );

  $atts = shortcode_atts(array(
    'to' => get_bloginfo('admin'),
    'template' => dirname(__FILE__) . '/basic-contact-form-template.php'
  ), $atts, 'basic_contact_form');

  // Extract attributes
  foreach($atts as $key => $value) {
    $$key = $atts[$key];
  }

  // Get request data
  $request = basic_contact_form_get_request();

  // Check against method and header
  if ( $request['method'] === 'POST' && $request['headers']['X-Ajaxform'] === 'basic-contact-form') {
    $fields = $request['fields'];
    $errors = array();

    // If the required fields are empty, switch $error to TRUE and set the result text to the shortcode attribute named 'error_empty'
    foreach ( $required as $key ) {
      $value = trim( $fields[$key] );
      if ( empty( $value ) ) {
        $errors[$key] = $messages['empty'];
      }
    }

    // And if the e-mail is not valid, switch $error to TRUE and set the result text to the shortcode attribute named 'error_noemail'
    if ( ! is_email( $fields['email'] ) ) {
      $errors['email'] = $messages['email_invalid'];
    }

    if ( !count(array_keys($errors)) ) {
      // Success
      $success = true;

      // Get message data
      $user_email = $fields['email'];
      // TODO: Dynamic message with template
      $mail_content = <<<EOT
Hello Admin,\n
a contact request has been issued by $user_email
EOT;
      // Actually send mail to admin
      basic_contact_form_mail($to, 'Contact Form Request', $mail_content);
    }
  }

  $output = basic_contact_form_render($template, array(
    'success' => $success,
    'errors' => $errors,
    'action' => $action,
    'fields' => $fields
  ));

  // Sanitize form with identifier
  $output = basic_contact_form_sanitize_output($output, 'basic-contact-form');

  return $output;
}
add_shortcode( 'basic_contact_form', 'basic_contact_form_shortcode' );

function basic_contact_form_enqueue_scripts() {
  wp_enqueue_script( 'basic-contact-form', plugin_dir_url( __FILE__ ) . 'dist/basic-contact-form.js' );
}
add_action('wp_enqueue_scripts', 'basic_contact_form_enqueue_scripts');

?>
