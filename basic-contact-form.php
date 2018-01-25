<?php

/**
 Plugin Name: Basic Contact Form
 Plugin URI: http://github.com/benignware-labs/wp-basic-contact-form
 Description: The most basic contact-form ever
 Version: 0.0.1
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

function basic_contact_form_get_post_data($form_id = null) {
  if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
    return null;
  }
  $form_data = array();
  // This part fetches everything that has been POSTed, sanitizes them and lets us use them as $form_data['subject']
  foreach ( $_POST as $field => $value ) {
    if ( get_magic_quotes_gpc() ) {
      $value = stripslashes( $value );
    }
    $form_data[$field] = strip_tags( $value );
  }
  // If specified, match against form id
  if ($form_id && array_key_exists($form_id, $form_data)) {
    return $form_data;
  }
  return null;
}

function basic_contact_form_sanitize_form($html, $form_id = null) {
  $is_valid = $form_id ? false : true;
  if (!$is_valid) {
    // Parse input
    $doc = new DOMDocument();
    @$doc->loadHTML('<?xml encoding="utf-8" ?>' . $html );
    // Get the form element
    $form = $doc->getElementsByTagName( 'form' )->item(0);
    if ($form) {
      // Find input elements
      $form_elements = $doc->getElementsByTagName( 'input' );
      foreach($form_elements as $form_element) {
        $name = $form_element->getAttribute('name');
        // Actually match name attribute against form id
        if ($name === $form_id) {
          // ok
          $is_valid = true;
        }
      }
      if (!$is_valid) {
        // Add the identifier element
        $input_element = $doc->createElement('input');
        $input_element->setAttribute('name', $form_id);
        $input_element->setAttribute('type', 'hidden');
        $form->appendChild($input_element);
      }
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
  $required_fields = array( 'email' ); // Set required fields

  $default_atts = array(
    'to' => get_bloginfo('admin'),
    'class' => 'basic-contact-form',
    'form_class' => '',
    'form_error_class' => '',
    'field_class' => 'field',
    'field_error_class' => '',
    "label_email" => "Email",
    "label_submit" => "Send",
    "input_class" => 'input',
    "input_error_class" => "error",
    "message_class" => "message",
    "message_error_class" => "error",
    "button_class" => "button",
    "footer_class" => "",
    // the error message when at least one of the required fields are empty:
    "message_error_empty" => "Please fill in all the required fields.",
    // the error message when the e-mail address is not valid:
    "message_error_email_invalid" => "Please enter a valid e-mail address.",
    // and the success message when the e-mail is sent:
    "message_success" => "Thanks for your e-mail! We'll get back to you as soon as we can.",
    'message_success_class' => 'success',
    // Specify custom template
    'form_template' => dirname(__FILE__) . '/basic-contact-form-template.php'
  );

  $atts = shortcode_atts($default_atts, $atts, 'basic_contact_form');

  // Extract known keys only
  foreach($default_atts as $key => $value) {
    $$key = $atts[$key];
  }

  $form_data = basic_contact_form_get_post_data('basic-contact-form');

  print_r($form_data);

  // If the <form> element is POSTed, run the following code
  if ( $form_data ) {
    $form_errors = array();

    // If the required fields are empty, switch $error to TRUE and set the result text to the shortcode attribute named 'error_empty'
    foreach ( $required_fields as $field ) {
      $value = trim( $form_data[$field] );
      if ( empty( $value ) ) {
        $form_errors[$field] = $message_error_empty;
      }
    }

    // And if the e-mail is not valid, switch $error to TRUE and set the result text to the shortcode attribute named 'error_noemail'
    if ( ! is_email( $form_data['email'] ) ) {
      $form_errors['email'] = $message_error_email_invalid;
    }

    if ( !count($form_errors) ) {
      // Success
      $success = true;

      // Get message data
      $user_email = $form_data['email'];
      // TODO: Dynamic message
      $mail_content = <<<EOT
Hello Admin,\n
a contact request has been issued by $user_email
EOT;
      // Actually send mail to admin
      basic_contact_form_mail($to, 'Contact Form Request', $mail_content);
    }
  }

  ob_start();
  include $form_template;
  $output = ob_get_contents();
  ob_end_clean();

  // Sanitize form with identifier
  $output = basic_contact_form_sanitize_form($output, 'basic-contact-form');

  return $output;
}
add_shortcode( 'basic_contact_form', 'basic_contact_form_shortcode' );


function basic_contact_form_enqueue_scripts() {
  wp_enqueue_script( 'basic-contact-form', plugin_dir_url( __FILE__ ) . 'dist/basic-contact-form.js' );
}
add_action('wp_enqueue_scripts', 'basic_contact_form_enqueue_scripts');

?>
