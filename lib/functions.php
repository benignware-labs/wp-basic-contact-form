<?php

function basic_contact_form_shortcode( $atts = array(), $content ) {
  global $post;

  $captcha = get_option('basic_contact_form_option_captcha');

  $messages = array(
    'empty' => __('This field cannot be empty', 'basic-contact-form'),
    'email_invalid' => __('You have to enter a valid e-mail address', 'basic-contact-form')
  );

  $atts = shortcode_atts(array(
    'to' => get_option('basic_contact_form_option_recipient') ?: get_bloginfo('admin_email'),
    'fields' => 'name,email,subject,message',
    'required' => 'email',
    'title' => __('Get in contact with us!', 'basic-contact-form'),
    'description' => __('Please use our contact form for your inquiry', 'basic-contact-form'),
    'template' => dirname(__FILE__) . '/templates/contact-form.php',
    'mail' => array(
      'templates' => array(
        'admin' => dirname(__FILE__) . '/templates/mail/contact-admin.php'
      )
    )
  ), $atts, 'basic_contact_form');

  // Get arrays from string lists
  if (is_string($atts['fields'])) {
    $atts['fields'] = basic_contact_form_split_val($atts['fields']);
  }

  if (is_string($atts['required'])) {
    $atts['required'] = basic_contact_form_split_val($atts['required']);
  }

  $atts = apply_filters('basic_contact_form_options', $atts);

  // Extract attributes
  foreach($atts as $key => $value) {
    $$key = $atts[$key];
  }

  // Get request data
  $request = basic_contact_form_get_request(array(
    'field_prefix' => 'bcf_'
  ));

  $errors = array();
  $data = array(
    'subject' => $post ? get_the_title($post) : ''
  );

  // Check against method and header
  // && $request['headers']['X-Ajaxform'] === 'basic-contact-form'
  if ( $request['method'] === 'POST' ) {
    $data = array_merge($data, $request['data']);

    if ($content) {
      $content = basic_contact_form_sanitize_output($content, array(
        'form_name' => 'basic-contact-form',
        'field_prefix' => 'bcf_' // bcf_
      ));

      $fields = basic_contact_form_get_fields($content);

      foreach ($fields as $index => $field) {
        $name = $field['name'];

        if ($field['required'] && empty(trim($data[$name]))) {
          $errors[$name] = $messages['empty'];
        } else if ($field['type'] === 'email' && !is_email($data[$name])) {
          $errors[$name] = $messages['email_invalid'];
        }
      }

    } else {
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
    }

    if (basic_contact_form_has_captcha()) {
      if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
        $secret = $captcha['secret_key'];
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);

        if (!$responseData->success) {
          $errors['captcha'] = 'Robot verification failed, please try again.';
        }
      } else {
        $errors['captcha'] = 'Robot verification failed, please try again.';
      }
    }

    if ( !count(array_keys($errors)) ) {
      // Success
      $success = true;

      // Collect mail data

      // Recipients
      $recipients = array_map('trim', explode(';', $to));

      // From
      $mail_from = $data['name'] . ($data['name'] ? ' <'. $data['email'] . '>' : $data['email']);

      // Subject
      $mail_subject = $data['subject'] ? $data['subject'] : __('Contact form request', 'basic-contact-form');

      // Template
      $mail_template = $atts['mail']['templates']['admin'];

      // Body
      $mail_body = basic_contact_form_render($mail_template, array(
        'title' => $title,
        'description' => $description,
        'required' => $required,
        'fields' => $fields,
        'success' => $success,
        'errors' => $errors,
        'action' => $action,
        'data' => $data
      ));

      // Headers
      $mail_headers = 'Reply-To: ' . $mail_from;

      // Actually send mail to recipients
      foreach ($recipients as $recipient) {
        wp_mail( trim($recipient), $mail_subject, $mail_body, $mail_headers );
      }
    }
  }

  if ($content) {

    $output.= basic_contact_form_render_data($content, $data, $errors);
  } else {
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
  }

  // Sanitize form with identifier

  $output = basic_contact_form_sanitize_output($output, array(
    'form_name' => 'basic-contact-form',
    'field_prefix' => 'bcf_' // bcf_
  ));

  return $output;
}
