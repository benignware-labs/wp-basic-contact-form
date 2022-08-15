<div class="contact">
  <?php print_r($errors); ?>
  <?php if ($title): ?>
    <h3 class="contact-title"><?= $title ?></h3>
  <?php endif; ?>
  <?php if ($success): ?>
    <p class="contact-description lead">
      <?= __('Thanks for your request!', 'basic-contact-form') ?><br/>
      <?= __('We\'ll get back to you as soon as we can.', 'basic-contact-form') ?>
    </p>
  <?php else: ?>
    <?php if ($description): ?>
      <p class="contact-description"><?= $description ?></p>
    <?php endif; ?>
    <form class="contact-form" method="POST">
      <?php if (in_array('name', $fields)) : ?>
        <p class="contact-field">
          <label class="contact-label">
            <?= __('Name', 'basic-contact-form') ?><?= in_array('name', $required) ? '*' : ''; ?>
          </label>
          <div>
            <input class="contact-input<?= $errors['name'] ? ' is-invalid error' : '' ?>" placeholder="<?= __('Please enter your name', 'basic-contact-form') ?>" type="text" name="name" size="50" maxlength="80" value="<?= $data['name'] ?>" />
            <?php if (array_key_exists('name', $errors)): ?>
              <span class="contact-message is-invalid error"><?= $errors['name'] ?></span>
            <?php endif; ?>
          </div>
        </p>
      <?php endif; ?>
      <?php if (in_array('email', $fields)) : ?>
        <p class="contact-field">
          <label class="contact-label">
            <?= __('E-mail', 'basic-contact-form') ?><?= in_array('email', $required) ? '*' : ''; ?>
          </label>
          <div>
            <input class="contact-input<?= $errors['email'] ? ' is-invalid error' : '' ?>" placeholder="<?= __('Please enter your e-mail address', 'basic-contact-form') ?>" type="email" name="email" size="50" maxlength="80" value="<?= $data['email'] ?>" />
            <?php if (array_key_exists('email', $errors)): ?>
              <span class="contact-message is-invalid error"><?= $errors['email'] ?></span>
            <?php endif; ?>
          </div>
        </p>
      <?php endif; ?>
      <?php if (in_array('subject', $fields)) : ?>
        <p class="contact-field">
          <label>
            <?= __('Subject', 'basic-contact-form') ?><?= in_array('subject', $required) ? '*' : ''; ?>
          </label>
          <div>
            <input class="contact-input<?= $errors['subject'] ? ' is-invalid error' : '' ?>" placeholder="<?= __('Please enter a subject', 'basic-contact-form') ?>" type="text" name="subject" size="50" maxlength="256" value="<?= $data['subject'] ?>" />
            <?php if (array_key_exists('subject', $errors)): ?>
              <span class="contact-message is-invalid error"><?= $errors['subject'] ?></span>
            <?php endif; ?>
          </div>
        </p>
      <?php endif; ?>
      <?php if (in_array('message', $fields)) : ?>
        <p class="contact-field">
          <label class="contact-label">
            <?= __('Message', 'basic-contact-form') ?><?= in_array('message', $required) ? '*' : ''; ?>
          </label>
          <div>
            <textarea class="contact-input<?= $errors['message'] ? ' is-invalid error' : '' ?>" placeholder="<?= __('Please enter a message', 'basic-contact-form') ?>" name="message" rows="8"><?= $data['message'] ?></textarea>
            <?php if (array_key_exists('message', $errors)): ?>
              <span class="contact-message is-invalid error"><?= $errors['message'] ?></span>
            <?php endif; ?>
          </div>
        </p>
      <?php endif; ?>
      <p class="contact-footer">
        <?php if (basic_contact_form_has_captcha()): ?>
          <div class="contact-field form-group">
            <?php basic_contact_form_captcha(); ?>
            <?php if (array_key_exists('captcha', $errors)): ?>
              <span class="invalid-feedback contact-message"><?= $errors['captcha'] ?></span>
            <?php endif; ?>
          </div>
        <?php endif; ?>
        <button class="contact-submit" type="submit"><?= __('Send', 'basic-contact-form'); ?></button>
      </p>
    </form>
  <?php endif; ?>
</div>
