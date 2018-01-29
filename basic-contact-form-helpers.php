<?php

function basic_contact_form_split_val($string, $delimiter = ',') {
  return array_map('trim', explode($delimiter, $string));
}

function basic_contact_form_get_request() {
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
    $data[$field] = strip_tags( $value );
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

?>
