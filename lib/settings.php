<?php

function basic_contact_form_register_settings() {
  add_option( 'basic_contact_form_option_recipient', '' );
  add_option( 'basic_contact_form_option_captcha', array(
    'enabled' => 0
  ));
  add_option( 'basic_contact_form_option_phpmailer', array(
    'enabled'    => 0,
    'host'       => '',
    'port'       => '',
    'encryption' => '',
    'username'   => '',
    'password'   => '',
  ));

  register_setting( 'basic_contact_form_options_group', 'basic_contact_form_option_recipient', 'basic_contact_form_option_recipient_callback' );
  register_setting( 'basic_contact_form_options_group', 'basic_contact_form_option_captcha', array(
    'default' => array(
      'enabled'    => 0,
      'site_key'   => '',
      'secret_key' => ''
    )
  ));
  register_setting( 'basic_contact_form_options_group', 'basic_contact_form_option_phpmailer' );
}
add_action( 'admin_init', 'basic_contact_form_register_settings' );

function basic_contact_form_register_options_page() {
  add_options_page( __('Contact Form', 'basic-contact-form'), __('Contact Form', 'basic-contact-form'), 'manage_options', 'basic_contact_form', 'basic_contact_form_options_page');
}
add_action('admin_menu', 'basic_contact_form_register_options_page');

function basic_contact_form_options_page() {
  $recipient  = get_option('basic_contact_form_option_recipient');
  $captcha    = get_option('basic_contact_form_option_captcha');
  $phpmailer  = get_option('basic_contact_form_option_phpmailer');

  ?>
  <div class="wrap">
    <style>
      input[name='basic_contact_form_option_captcha[enabled]'] ~ div,
      input[name='basic_contact_form_option_phpmailer[enabled]'] ~ div {
        display: none;
      }
      input[name='basic_contact_form_option_captcha[enabled]']:checked ~ div,
      input[name='basic_contact_form_option_phpmailer[enabled]']:checked ~ div {
        display: block;
      }
    </style>
    <?php screen_icon(); ?>
    <h1><?= __('Settings'); ?> › <?= __('Contact Form', 'basic-contact-form'); ?></h1>
    <form method="post" action="options.php">
      <?php settings_fields( 'basic_contact_form_options_group' ); ?>
      <table class="form-table">
        <tr valign="top">
          <th scope="row">
            <label for="basic_contact_form_option_recipient"><?= __('Recipient', 'basic-contact-form'); ?></label>
          </th>
          <td valign="top">
            <input
              class="regular-text"
              type="text"
              id="basic_contact_form_option_recipient"
              name="basic_contact_form_option_recipient"
              value="<?php echo esc_attr($recipient); ?>"
            />
            <p class="description"><?= __('Separate multiple email-addresses by semicolon', 'basic-contact-form'); ?></p>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row"><?= __('Captcha', 'basic-contact-form'); ?></th>
          <td>
            <input id="basic_contact_form_option_captcha[enabled]" type="checkbox" name="basic_contact_form_option_captcha[enabled]" value="1" <?php checked( $captcha['enabled'], 1 ); ?> />
            <label for="basic_contact_form_option_captcha[enabled]">ReCaptcha</label>
            <div class="basic-contact-form-option-group">
              <div>
                <p><label for="basic_contact_form_option_captcha[site_key]"><?= __('Site Key', 'basic-contact-form'); ?></label></p>
                <input
                  class="regular-text"
                  type="text"
                  id="basic_contact_form_option_captcha[site_key]"
                  name="basic_contact_form_option_captcha[site_key]"
                  value="<?= isset($captcha['site_key']) ? esc_attr($captcha['site_key']) : ''; ?>"
                />
              </div>
              <div>
                <p><label for="basic_contact_form_option_captcha[secret_key]"><?= __('Secret Key', 'basic-contact-form'); ?></label></p>
                <input
                  class="regular-text"
                  type="text"
                  id="basic_contact_form_option_captcha[secret_key]"
                  name="basic_contact_form_option_captcha[secret_key]"
                  value="<?= isset($captcha['secret_key']) ? esc_attr($captcha['secret_key']) : ''; ?>"
                />
              </div>
            </div>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row"><?= __('PHPMailer (SMTP)', 'basic-contact-form'); ?></th>
          <td>
            <input id="basic_contact_form_option_phpmailer[enabled]" type="checkbox" name="basic_contact_form_option_phpmailer[enabled]" value="1" <?php checked( $phpmailer['enabled'], 1 ); ?> />
            <label for="basic_contact_form_option_phpmailer[enabled]"><?= __('Enable custom SMTP settings', 'basic-contact-form'); ?></label>
            <div class="basic-contact-form-option-group">
              <div>
                <p><label for="basic_contact_form_option_phpmailer[host]"><?= __('Host', 'basic-contact-form'); ?></label></p>
                <input class="regular-text" type="text" id="basic_contact_form_option_phpmailer[host]" name="basic_contact_form_option_phpmailer[host]" value="<?= esc_attr($phpmailer['host']); ?>" />
              </div>
              <div>
                <p><label for="basic_contact_form_option_phpmailer[port]"><?= __('Port', 'basic-contact-form'); ?></label></p>
                <input class="regular-text" type="text" id="basic_contact_form_option_phpmailer[port]" name="basic_contact_form_option_phpmailer[port]" value="<?= esc_attr($phpmailer['port']); ?>" />
              </div>
              <div>
                <p><label for="basic_contact_form_option_phpmailer[encryption]"><?= __('Encryption (ssl/tls)', 'basic-contact-form'); ?></label></p>
                <input class="regular-text" type="text" id="basic_contact_form_option_phpmailer[encryption]" name="basic_contact_form_option_phpmailer[encryption]" value="<?= esc_attr($phpmailer['encryption']); ?>" />
              </div>
              <div>
                <p><label for="basic_contact_form_option_phpmailer[username]"><?= __('Username', 'basic-contact-form'); ?></label></p>
                <input class="regular-text" type="text" id="basic_contact_form_option_phpmailer[username]" name="basic_contact_form_option_phpmailer[username]" value="<?= esc_attr($phpmailer['username']); ?>" />
              </div>
              <div>
                <p><label for="basic_contact_form_option_phpmailer[password]"><?= __('Password', 'basic-contact-form'); ?></label></p>
                <input
                  class="regular-text"
                  type="password"
                  id="basic_contact_form_option_phpmailer_password"
                  name="basic_contact_form_option_phpmailer[password]"
                  value="<?= esc_attr($phpmailer['password']); ?>"
                  autocomplete="new-password"
                />
              </div>
            </div>
          </td>
        </tr>
      </table>
      <?php submit_button(); ?>
    </form>
  </div>
  <?php
}

function basic_contact_form_option_recipient_callback($data) {
  $data = trim($data);

  if ($data) {
    $recipients = array_map('trim', explode(';', $data));
    $valid = true;

    foreach ($recipients as $email) {
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $valid = false;
      }
    }

    if (!$valid) {
      add_settings_error(
        'hasEmailError',
        'validationError',
        'Invalid Email',
        'error'
      );
    } else {
      return sanitize_text_field($data);
    }
  }
}
