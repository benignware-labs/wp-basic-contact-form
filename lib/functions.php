<?php

add_action('init', function() {
  global $post;
  global $basic_contact_form_errors;
  global $basic_contact_form_data;
  global $basic_contact_form_success;

  $hidden_fields = array(
    'form_id', 'post_id', 'redirect_to'
  );

  $messages = array(
    'empty' => __('This field cannot be empty', 'basic-contact-form'),
    'email_invalid' => __('You have to enter a valid e-mail address', 'basic-contact-form')
  );

  $recipient = get_option('basic_contact_form_option_recipient') ?: get_bloginfo('admin_email');

  $request = basic_contact_form_get_request(array(
    'field_prefix' => 'bcf_'
  ));

  if (
    $request['method'] === 'POST' &&
    isset($request['headers']['X-Remoteform']) &&
    strpos($request['headers']['X-Remoteform'], 'basic-contact-form') !== false
  ) {
    // We got a request
    $basic_contact_form_data = $data;

    // By default, we can find the nonce in the "_wpnonce" request parameter.
    $nonce = $_REQUEST['_wpnonce'];

    if ( ! wp_verify_nonce( $nonce, 'basic_contact_form' ) ) {
      // Get out of here, the nonce is rotten
      exit;
    }

    $data = $request['data'];

    $post_id = $data['post_id'] ?: $post->ID;
    $redirect_to = $data['redirect_to'] ?: null;
    $form_id = $data['form_id'] ?: null;

    $errors = array();

    if ($post_id) {
      $content_post = get_post($post_id);
      $content = $content_post ? $content_post->post_content : '';
      $content = apply_filters('the_content', $content);
      $content = str_replace(']]>', ']]&gt;', $content);

      // Only take the target form into account...
      $doc = new DOMDocument();
      @$doc->loadHTML('<?xml encoding="utf-8" ?>' . $content . '</div>' );
      $xpath = new DOMXpath($doc);
      $form = $xpath->query('//form[@data-basic-contact-form="' . $form_id . '"]')->item(0);

      $content = preg_replace('~(?:<\?[^>]*>|<(?:!DOCTYPE|/?(?:html|head|body))[^>]*>)\s*~i', '', $doc->saveHTML($form));

      $content = basic_contact_form_sanitize_output($content, array(
        'form_id' => $form_id,
        'field_prefix' => 'bcf_',
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
            $errors['captcha'] = __('Robot verification failed, please try again.', 'basic-contact-form');
          }
        } else {
          $errors['captcha'] = __('Robot verification failed, please try again.', 'basic-contact-form');
        }
      }

      if ( !count(array_keys($errors)) ) {
        // Success
        $success = true;

        $mail_headers = array(
          'Content-Type: text/html; charset=UTF-8',
        );

        // Collect mail data

        // Recipients
        $recipients = array_map('trim', explode(';', $recipient));

        $mail_headers[] = 'From: ' . get_bloginfo('name') . ' <' . get_bloginfo('admin_email') . '>';

        // Reply-To
        if ($data['name'] && $data['email']) {
          $mail_from = $data['name'] ? $data['name'] . ' <'. $data['email'] . '>' : $data['email'];
          $mail_headers[] = 'Reply-To: ' . $mail_from;
        }

        // Subject
        $mail_subject = $data['subject'] ? $data['subject'] : __('Contact Request', 'basic-contact-form');

        $mail_data = array_filter(
          $data,
          function ($key) use ($hidden_fields) {
            return !in_array($key, $hidden_fields);
          },
          ARRAY_FILTER_USE_KEY
        );

        // Render Mail
        $mail_template = plugin_dir_path( __DIR__ ) . 'template/mail/contact-admin.php';
        $mail_body = basic_contact_form_render($mail_template, array(
          'data' => $mail_data
        ));

        // Headers
        // Actually send mail to recipients
        // echo 'SEND EMAIL...';
        // exit;
        foreach ($recipients as $recipient) {
          $res = wp_mail($recipient, $mail_subject, $mail_body, $mail_headers );
        }

        $basic_contact_form_success = true;

        if ($redirect_to) {
          wp_redirect($redirect_to);
          exit;
        }
      } else {
        $basic_contact_form_data = $data;
        $basic_contact_form_errors = $errors;
        $basic_contact_form_success = false;
      }
    }
  }
});

function basic_contact_form_shortcode( $atts = array(), $content = null ) {
  if (is_admin() && !wp_doing_ajax()) {
    return '';
  }

  global $post;
  global $basic_contact_form_errors;
  global $basic_contact_form_data;
  global $basic_contact_form_success;

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
    'template' => dirname(__FILE__) . '/template/contact-form.php',
    'mail' => array(
      'templates' => array(
        'admin' => dirname(__FILE__) . '/template/mail/contact-admin.php'
      )
    ),
    'redirect_to' => null,
    'theme' => [
      'classes' => []
    ]
  ), $atts, 'basic_contact_form');

  // Get arrays from string lists
  if (is_string($atts['fields'])) {
    $atts['fields'] = basic_contact_form_split_val($atts['fields']);
  }

  if (is_string($atts['required'])) {
    $atts['required'] = basic_contact_form_split_val($atts['required']);
  }

  $atts = apply_filters('basic_contact_form_options', $atts);

  // Get request data
  $request = basic_contact_form_get_request(array(
    'field_prefix' => 'bcf_'
  ));

  if ($request['method'] === 'POST' && !isset($request['headers']['X-Remoteform'])) {
    return '';
  }

  $errors = $basic_contact_form_errors ?: array();
  $data = $basic_contact_form_data ?: array();
  $success = $basic_contact_form_success;

  $form_id = $atts['id'] ?: 'basic-contact-form-' . uniqid();

  $template = $atts['template'];

  $template_data = array_merge($atts, array(
    'data' => $data,
    'errors' => $errors,
    'success' => $success
  ));

  $content = $content ?: basic_contact_form_render($template, $template_data);

  $content = basic_contact_form_sanitize_output($content, array(
    'form_id' => $form_id,
    'field_prefix' => 'bcf_',
  ));

  $content = basic_contact_form_render_data($content, $data, $errors);

  $content = basic_contact_form_sanitize_output($content, [
    'form_id' => $form_id,
    'hidden' => [
      'post_id' => get_the_ID(),
      'redirect_to' => $atts['redirect_to']
    ],
    'field_prefix' => 'bcf_',
    'theme' => array_merge(
      [ 'classes' => [] ],
      isset($atts['theme']) ? $atts['theme'] : []
    )
  ]);

  // Add nonce field
  $nonce_html = wp_nonce_field( 'basic_contact_form' );
  $content = preg_replace('~</form>~', $nonce_html . '</form>', $content);

  return $content;
}
