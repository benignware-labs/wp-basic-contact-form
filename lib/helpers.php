<?php

function basic_contact_form_split_val($string, $delimiter = ',') {
  return array_map('trim', explode($delimiter, $string));
}

function basic_contact_form_get_request($options = array()) {
  $options = array_merge(array(
    'field_prefix' => ''
  ), $options);

  $field_prefix = $options['field_prefix'];

  // Get request method
  $method = $_SERVER['REQUEST_METHOD'];
  // Get request headers
  $headers = array();
  foreach($_SERVER as $key => $value) {
    if (substr($key, 0, 5) <> 'HTTP_') {
      continue;
    }
    $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
    $headers[$header] = $value;
  }
  // Get post data
  $data = array();

  // This part fetches everything that has been POSTed, sanitizes them and lets us use them as $form_data['subject']
  foreach ( $_POST as $field => $value ) {
    if ( get_magic_quotes_gpc() ) {
      $value = stripslashes( $value );
    }
    if (!$field_prefix || basic_contact_form_starts_with($field, $field_prefix) ) {
      $field = $field_prefix ? basic_contact_form_remove_prefix($field, $field_prefix) : $field;
      $data[$field] = strip_tags( $value );
    }
  }

  // return as array
  $request = array(
    'headers' => $headers,
    'data' => $data,
    'method' => $method
  );
  return $request;
}

function basic_contact_form_render($template, $template_data = array()) {
  foreach($template_data as $key => $value) {
    $$key = $template_data[$key];
  }
  ob_start();
  include $template;
  $output = ob_get_contents();
  ob_end_clean();
  return $output;
}

function basic_contact_form_get_url() {
  $uri = $_SERVER['REQUEST_URI'];
  $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
  $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  return $url;
}

function basic_contact_form_starts_with($haystack, $needle) {
  $length = strlen($needle);
  return (substr($haystack, 0, $length) === $needle);
}

function basic_contact_form_ends_with($haystack, $needle) {
  $length = strlen($needle);
  return $length === 0 ||  (substr($haystack, -$length) === $needle);
}

function basic_contact_form_remove_prefix($text, $prefix) {
  if (0 === strpos($text, $prefix)) {
    $text = substr($text, strlen($prefix));
  }
  return $text;
}

// Sanitize form
function basic_contact_form_sanitize_output($html, $options = array()) {
  $options = array_merge(array(
    'form_name' => null,
    'hidden' => array(),
    'field_prefix' => ''
  ), $options);

  $form_name = $options['form_name'];
  $hidden = $options['hidden'];
  $field_prefix = $options['field_prefix'];

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
      $action = $form->getAttribute('action') ?: basic_contact_form_get_url();
      $form->setAttribute('action', $action);
      $method = $form->getAttribute('method') ?: 'POST';
      $form->setAttribute('method', $method);
      // Get elements
      $input_elements = $form->getElementsByTagName( 'input' );
      $textarea_elements = $form->getElementsByTagName( 'textarea' );

      $form_elements = array();
      foreach ($input_elements as $input_element) {
        array_push($form_elements, $input_element);
      }
      foreach ($textarea_elements as $textarea_element) {
        array_push($form_elements, $textarea_element);
      }

      $hidden_fields = array();
      // Go through present fields and add update values
      foreach ($form_elements as $form_element) {
        $input_name = $form_element->getAttribute('name');

        if ($field_prefix && !basic_contact_form_starts_with($input_name, $field_prefix)) {
          $input_name = $field_prefix . $input_name;
          $form_element->setAttribute('name', $input_name);
        }

        if (strtolower($form_element->tagName) === 'input') {
          $input_type = $form_element->getAttribute('type');
          if ($input_type === 'hidden' && array_key_exists($hidden, $input_name)) {
            $hidden_fields[] = $input_name;
            $form_element->setAttribute('name', $hidden[$input_name]);
          }
        }
      }
      // And add the missing hidden fields
      foreach ($hidden as $field => $value) {
        if (!in_array($field, $hidden_fields)) {
          $input_element = $doc->createElement('input');
          $input_element->setAttribute('type', 'hidden');
          $input_element->setAttribute('name', $field);
          $input_element->setAttribute('value', $hidden[$field]);
          $form->appendChild($input_element);
        }
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

function get_basic_contact_form_captcha() {
  $captcha = get_option('basic_contact_form_option_captcha');

  if (($captcha && $captcha['enabled'])) {
    $site_key = $captcha['site_key'];

    // $script = '<script src="https://www.google.com/recaptcha/api.js"></script>';

    return '<div id="g-captcha" data-remoteform-permanent class="g-recaptcha" data-sitekey="' . $site_key . '"></div>';
  }

  return '';
}

function basic_contact_form_captcha() {
  echo get_basic_contact_form_captcha();
}

function basic_contact_form_has_captcha() {
  $captcha = get_option('basic_contact_form_option_captcha');

  return ($captcha && $captcha['enabled']);
}


?>
