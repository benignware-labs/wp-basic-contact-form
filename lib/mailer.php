<?php

add_action('phpmailer_init', function($phpmailer) {
  $settings = get_option('basic_contact_form_option_phpmailer');

  if (!empty($settings['enabled'])) {
    $phpmailer->Host = $settings['host'];
    $phpmailer->Port = intval($settings['port']);

    $phpmailer->SMTPAuth = !empty($settings['username']);
    
    if ($phpmailer->SMTPAuth) {
      $phpmailer->Username = $settings['username'];
      $phpmailer->Password = $settings['password'];
    }

    if (!empty($settings['encryption'])) {
      $phpmailer->SMTPSecure = $settings['encryption'];
    }

    $phpmailer->isSMTP();
  }
});
