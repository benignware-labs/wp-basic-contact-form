<?php

function basic_contact_form_register_settings() {
   add_option( 'basic_contact_form_option_recipient', '');


   register_setting( 'basic_contact_form_options_group', 'basic_contact_form_option_recipient', 'basic_contact_form_option_recipient_callback' );
}
add_action( 'admin_init', 'basic_contact_form_register_settings' );


function basic_contact_form_register_options_page() {
  add_options_page( __('Contact Form', 'basic-contact-form'), __('Contact Form', 'basic-contact-form'), 'manage_options', 'basic_contact_form', 'basic_contact_form_options_page');
}
add_action('admin_menu', 'basic_contact_form_register_options_page');


function basic_contact_form_options_page() {
  ?>
    <div class="wrap">
      <?php screen_icon(); ?>
      <h1><?= __('Settings'); ?> › <?= __('Contact Form', 'basic-contact-form'); ?></h1>
      <form method="post" action="options.php">
        <?php settings_fields( 'basic_contact_form_options_group' ); ?>
        <table class="form-table">
          <tr valign="top">
            <th scope="row">
              <label for="basic_contact_form_option_recipient">Recipient</label>
            </th>
            <td>
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
