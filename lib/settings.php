<?php

function basic_contact_form_register_settings() {
  // delete_option( 'basic_contact_form_option_captcha' );
   add_option( 'basic_contact_form_option_recipient', '');
   add_option( 'basic_contact_form_option_captcha', array(
     'enabled' => 0
   ));

   register_setting( 'basic_contact_form_options_group', 'basic_contact_form_option_recipient', 'basic_contact_form_option_recipient_callback' );
   register_setting( 'basic_contact_form_options_group', 'basic_contact_form_option_captcha', array(
     // 'sanitize_callback' => 'basic_contact_form_option_captcha_callback',
     'default' => array(
       'enabled' => 0,
       'site_key' => '',
       'secret_key' => ''
     )
   ));
}
add_action( 'admin_init', 'basic_contact_form_register_settings' );


function basic_contact_form_register_options_page() {
  add_options_page( __('Contact Form', 'basic-contact-form'), __('Contact Form', 'basic-contact-form'), 'manage_options', 'basic_contact_form', 'basic_contact_form_options_page');
}
add_action('admin_menu', 'basic_contact_form_register_options_page');


function basic_contact_form_options_page() {
  $recipient = get_option('basic_contact_form_option_recipient');
  $captcha = get_option('basic_contact_form_option_captcha');

  ?>
    <div class="wrap">
      <style>
        input[name='basic_contact_form_option_captcha[enabled]'] ~ div {
          display: none;
        }

        input[name='basic_contact_form_option_captcha[enabled]']:checked ~ div {
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
                value="<?php echo get_option('basic_contact_form_option_recipient'); ?>"
              />
              <p class="description"><?= __('Separate multiple email-addresses by semicolon', 'basic-contact-form'); ?></p>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row">
              <label><?= __('Captcha', 'basic-contact-form'); ?></label>
            </th>
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
                    value="<?php echo $captcha['site_key']; ?>"
                  />
                </div>
                <div>
                  <p><label for="basic_contact_form_option_captcha[secret_key]"><?= __('Secret Key', 'basic-contact-form'); ?></label></p>
                  <input
                    class="regular-text"
                    type="text"
                    id="basic_contact_form_option_captcha[secret_key]"
                    name="basic_contact_form_option_captcha[secret_key]"
                    value="<?php echo $captcha['secret_key']; ?>"
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
