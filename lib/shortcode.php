<?php

/**
 * Basic Contact Form Shortcode
 */
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

  $default_template_dir = dirname(__DIR__) . '/template';

  $atts = shortcode_atts(array(
    'id' => null,
    'to' => get_option('basic_contact_form_option_recipient') ?: get_bloginfo('admin_email'),
    'fields' => 'name,email,subject,message',
    'required' => 'email',
    'title' => __('Get in contact with us!', 'basic-contact-form'),
    'description' => __('Please use our contact form for your inquiry', 'basic-contact-form'),
    'template' => $default_template_dir . '/contact-form.php',
    'mail' => array(
      'templates' => array(
        'admin' => $default_template_dir . '/template/mail/contact-admin.php'
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
  ), $errors);

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
  ], $errors);

  // Add nonce field
  $nonce_html = wp_nonce_field( 'basic_contact_form' );
  $content = preg_replace('~</form>~', $nonce_html . '</form>', $content);

  return $content;
}

add_shortcode( 'basic_contact_form', 'basic_contact_form_shortcode');