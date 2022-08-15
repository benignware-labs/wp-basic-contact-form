<?php

function basic_contact_form_split_val($string, $delimiter = ',') {
  return array_map('trim', explode($delimiter, $string));
}

function basic_contact_form_get_request($options = array()) {
  $options = array_merge(array(
    'field_prefix' => 'bcf_'
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
    // if ( get_magic_quotes_gpc() ) {
    //   $value = stripslashes( $value );
    // }

    if (!$field_prefix || basic_contact_form_starts_with($field, $field_prefix) ) {
      $field = $field_prefix ? basic_contact_form_remove_prefix($field, $field_prefix) : $field;

      $value = is_array($value) ? $value[0] : $value;
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
function basic_contact_form_get_fields($html, $options = array()) {
  $field_prefix = 'bcf_';

  $doc = new DOMDocument();
  @$doc->loadHTML('<?xml encoding="utf-8" ?>' . $html );
  $xpath = new DOMXpath($doc);

  $form_elements = $xpath->query("//input|//textarea|//select");

  $fields = array();

  foreach ($form_elements as $index => $form_element) {
    $raw_name = $form_element->getAttribute('name') ?: $field_prefix . 'field_' . strval($index);
    $type = $form_element->nodeName === 'input' ? ($form_element->getAttribute('type') ?: 'text') : '';
    $required = $form_element->hasAttribute('required');

    $name = $field_prefix ? basic_contact_form_remove_prefix($raw_name, $field_prefix) : $raw_name;

    if ($type !== 'submit') {
      $fields[] = array(
        'name' => $name,
        'type' => $type,
        'required' => $required
      );
    }
  }

  return $fields;
}

function basic_contact_form_render_data($html, $data = array(), $errors = array()) {
  $field_prefix = 'bcf_';
  $parse_wrapper_id = 'basic-contact-form-parse-wrapper';
  $has_errors = count(array_keys($errors)) > 0;
  $has_data = count(array_keys($data)) > 0;

  if ($has_errors || $has_data) {
    $doc = new DOMDocument();
    @$doc->loadHTML('<?xml encoding="utf-8" ?>' . '<div id="' . $parse_wrapper_id . '">' . $html . '</div>' );
    $xpath = new DOMXpath($doc);
  }

  if ($has_errors) {
    $form = $doc->getElementsByTagName('form')->item(0);

    if ($form) {
      $form_classes = explode(' ', $form->getAttribute('class'));
      $form_classes[] = 'is-invalid';

      $form->setAttribute('class', implode(' ', array_unique($form_classes)));
    }

    foreach ($errors as $name => $message) {
      $raw_name = $field_prefix ? $field_prefix . $name : $name;

      $form_element = $xpath->query("//*[(local-name() = 'input' or local-name() = 'select' or local-name() = 'textarea')]")->item(0);

      if ($form_element) {
        $form_element->setAttribute('invalid', 'invalid');
      }

      $message_element = $xpath->query("//*[(local-name() = 'input' or local-name() = 'select' or local-name() = 'textarea')][@name='" . $raw_name . "']/following-sibling::div")->item(0);

      if ($message_element) {
        $messageText = $doc->createTextNode($message);

        $message_element->appendChild($messageText);
      }
    }
  }

  if ($has_data) {
    $form_elements = $xpath->query("//input|//textarea|//select");

    foreach ($form_elements as $index => $form_element) {
      $raw_name = $form_element->getAttribute('name');

      if (!$raw_name) {
        $raw_name = 'field_' . strval($index);
      }

      if ($field_prefix && !basic_contact_form_starts_with($raw_name, $field_prefix)) {
        $raw_name = $field_prefix . $raw_name;
      }

      $name = $field_prefix ? basic_contact_form_remove_prefix($raw_name, $field_prefix) : $raw_name;
      $value = $data[$name];

      if (isset($value)) {
        $form_element->setAttribute('value', $value);
      }

      if (strtolower($form_element->nodeName) === 'select') {
        $options = $form_element->getElementsByTagName('option');

        foreach ($options as $option_element) {
          $option_value = $option_element->getAttribute('value');

          if ($option_value === $value) {
            $option_element->setAttribute('selected', 'selected');
          }
        }
      }
    }
  }

  if ($has_errors || $has_data) {
    $parse_wrapper_element = $doc->getElementById($parse_wrapper_id);

    if ($parse_wrapper_element) {
      $fragment = $doc->createDocumentFragment();

      $children = array();

      foreach ($parse_wrapper_element->childNodes as $child) {
        $children[] = $child;
      }

      foreach ($children as $child) {
        $fragment->appendChild($child);
      }

      $parse_wrapper_element->parentNode->insertBefore($fragment, $parse_wrapper_element);
      $parse_wrapper_element->parentNode->removeChild($parse_wrapper_element);
    }

    $html = preg_replace('~(?:<\?[^>]*>|<(?:!DOCTYPE|/?(?:html|head|body))[^>]*>)\s*~i', '', $doc->saveHTML());
  }

  return $html;
}

// Sanitize form
function basic_contact_form_sanitize_output($html, $options = array()) {
  $options = array_merge(array(
    'form_id' => 'basic_contact_form',
    'hidden' => array(),
    'field_prefix' => 'bcf_',
    'theme' => array_merge(
      [ 'classes' => [] ],
      isset($options['theme']) ? $options['theme'] : []
    )
  ), $options);

  $theme_classes = $options['theme']['classes'];

  $form_id = $options['form_id'];
  $hidden = $options['hidden'];
  $field_prefix = $options['field_prefix'];

  $is_valid = $form_id ? false : true;

  if (!$is_valid) {
    // Parse input
    $doc = new DOMDocument();
    @$doc->loadHTML('<?xml encoding="utf-8" ?>' . $html );
    $xpath = new DOMXpath($doc);

    // Get the container element
    $container = $doc->getElementsByTagName('body')->item(0)->firstChild;
    $container->setAttribute("data-basic-contact-form", $form_id);

    $hidden = array_merge($hidden, array(
      'form_id' => $form_id
    ));

    // Get the form element
    $form = $doc->getElementsByTagName( 'form' )->item(0);

    if ($form) {
      $action = $form->getAttribute('action') ?: basic_contact_form_get_url();
      $form->setAttribute('action', $action);
      $method = $form->getAttribute('method') ?: 'POST';
      $form->setAttribute('method', $method);

      $form->setAttribute('novalidate', 'novalidate');

      // Get elements
      $form_elements = $xpath->query("//input|//textarea|//select");

      $hidden_fields = array();
      // Go through present fields and add update values
      foreach ($form_elements as $index => $form_element) {
        $input_name = $form_element->getAttribute('name');

        if (!$input_name) {
          $input_name = 'field_' . strval($index);
        }

        if ($field_prefix && !basic_contact_form_starts_with($input_name, $field_prefix)) {
          $input_name = "{$field_prefix}{$input_name}";
        }

        $form_element->setAttribute('name', $input_name);

        if (strtolower($form_element->tagName) === 'input') {
          $input_type = $form_element->getAttribute('type');
          if ($input_type === 'hidden' && array_key_exists($input_name, $hidden)) {
            $hidden_fields[] = $input_name;
            $form_element->setAttribute('name', $hidden[$input_name]);
          }
        }
      }
      // And add the missing hidden fields
      foreach ($hidden as $name => $value) {

        if ($name && !basic_contact_form_starts_with($name, $field_prefix)) {
          $name = "{$field_prefix}{$name}";
        }

        if (!in_array($name, $hidden_fields)) {
          $input_element = $doc->createElement('input');
          $input_element->setAttribute('type', 'hidden');
          $input_element->setAttribute('name', $name);
          $input_element->setAttribute('value', $value);
          $form->appendChild($input_element);
        }
      }

      $class_prefix = 'bcf-';
      $theme_elements = $xpath->query('//*[contains(@class, "' . $class_prefix . '")]');

      foreach ($theme_elements as $theme_element) {
        $classes = explode(' ', $theme_element->getAttribute('class'));

        $bcf_classes = array_filter($classes, function($class) use ($theme_classes) {
          return in_array($class, array_keys($theme_classes));
        });

        foreach ($bcf_classes as $bcf_class) {
          $theme_class = $theme_classes[$bcf_class];

          if ($theme_class) {
            $classes[] = $theme_class;

            $theme_element->setAttribute('class', implode(' ', array_unique($classes)));
          }
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

    return '<div id="g-captcha" data-remoteform-permanent class="g-recaptcha" data-sitekey="' . $site_key . '"></div>';
  }

  return '';
}

function basic_contact_form_captcha() {
  echo get_basic_contact_form_captcha();
}

function basic_contact_form_has_captcha() {
  $captcha = get_option('basic_contact_form_option_captcha');

  return $captcha && isset($captcha['enabled']) && $captcha['enabled'];
}


function basic_contact_form_snakeify_keys($array, $arrayHolder = array()) {
  $result = !empty($arrayHolder) ? $arrayHolder : array();

  foreach ($array as $key => $val) {
    $str = $key;
    $str[0] = strtolower($str[0]);
    // $func = create_function('$c', 'return "_" . strtolower($c[1]);');
    $func = function($c) {
      return "_" . strtolower($c[1]);
    };
    $newKey = preg_replace_callback('/([A-Z])/', $func, $str);

    if (!is_array($val)) {
      $result[$newKey] = $val;
    } else {
      $result[$newKey] = basic_contact_form_snakeify_keys($val, $result[$newKey]);
    }
  }
  return $result;
}
