<?php

add_action('init', function() {
  global $post;
  global $basic_contact_form_errors;
  global $basic_contact_form_data;

  $messages = array(
    'empty' => __('This field cannot be empty', 'basic-contact-form'),
    'email_invalid' => __('You have to enter a valid e-mail address', 'basic-contact-form')
  );

  $request = basic_contact_form_get_request(array(
    'field_prefix' => 'bcf_'
  ));

  if ( $request['method'] === 'POST' ) {
    $data = $request['data'];

    $post_id = $data['post_id'] ?: $post->ID;
    $redirect_to = $data['redirect_to'] ?: null;
    $form_id = $data['form_id'] ?: null;

    $errors = array();

    if ($post_id) {
      $content_post = get_post($post_id);
      $content = $content_post->post_content;
      $content = apply_filters('the_content', $content);
      $content = str_replace(']]>', ']]&gt;', $content);

      // Only take the target form into account...
      $doc = new DOMDocument();
      @$doc->loadHTML('<?xml encoding="utf-8" ?>' . $content . '</div>' );
      $xpath = new DOMXpath($doc);
      $form = $xpath->query('//form[@data-basic-contact-form="' . $form_id . '"]')->item(0);

      if (!$form) {
        return;
      }

      $content = preg_replace('~(?:<\?[^>]*>|<(?:!DOCTYPE|/?(?:html|head|body))[^>]*>)\s*~i', '', $doc->saveHTML($form));

      $content = basic_contact_form_sanitize_output($content, array(
        'form_id' => $form_id,
        'field_prefix' => 'bcf_', // bcf_m
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

      if (basic_contact_form_has_captcha()) {
        $captcha = get_option('basic_contact_form_option_captcha');

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

        if ($redirect_to) {
          wp_redirect($redirect_to);
          exit;
        }
      } else {
        $basic_contact_form_errors = $errors;
        $basic_contact_form_data = $data;
      }
    }
  }
});

function basic_contact_form_shortcode( $atts = array(), $content ) {
  global $post;
  global $basic_contact_form_errors;
  global $basic_contact_form_data;

  $captcha = get_option('basic_contact_form_option_captcha');

  $messages = array(
    'empty' => __('This field cannot be empty', 'basic-contact-form'),
    'email_invalid' => __('You have to enter a valid e-mail address', 'basic-contact-form')
  );

  $atts = shortcode_atts(array(
    'id' => null,
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
    ),
    'redirect_to' => null
  ), $atts, 'basic_contact_form');

  // Get arrays from string lists
  if (is_string($atts['fields'])) {
    $atts['fields'] = basic_contact_form_split_val($atts['fields']);
  }

  if (is_string($atts['required'])) {
    $atts['required'] = basic_contact_form_split_val($atts['required']);
  }

  $atts = apply_filters('basic_contact_form_options', $atts);

  if (!$atts['theme']) {
    $atts['theme'] = array(
      'classes' => array()
    );
  }

  // Get request data
  $request = basic_contact_form_get_request(array(
    'field_prefix' => 'bcf_'
  ));

  // Check against method and header
  // && $request['headers']['X-Ajaxform'] === 'basic-contact-form'
  $errors = $basic_contact_form_errors ?: array();
  $data = $basic_contact_form_data ?: array();

  $form_id = $atts['id'] ?: 'basic-contact-form-' . uniqid();

  $content = basic_contact_form_sanitize_output($content, array(
    'form_id' => $form_id,
    'field_prefix' => 'bcf_', // bcf_m
  ));

  $content = basic_contact_form_render_data($content, $data, $errors);

  $content = basic_contact_form_sanitize_output($content, array(
    'form_id' => $form_id,
    'hidden' => array(
      'post_id' => get_the_ID(),
      'redirect_to' => $atts['redirect_to']
    ),
    'field_prefix' => 'bcf_', // bcf_
    'theme' => $atts['theme'] ?: array()
  ));

  return $content;
}
