<div class="bcf">
  <?php if ($title): ?>
    <h3 class="bcf-title"><?= $title ?></h3>
  <?php endif; ?>
  <?php if ($success): ?>
    <p class="bcf-description">
      <?= __('Thanks for your request!', 'basic-contact-form') ?><br/>
      <?= __('We\'ll get back to you as soon as we can.', 'basic-contact-form') ?>
    </p>
  <?php else: ?>
    <?php if ($description): ?>
      <p class="bcf-description"><?= $description ?></p>
    <?php endif; ?>
    <form class="bcf-form" method="POST">
      <?php if (in_array('name', $fields)) : ?>
        <p class="bcf-field">
          <label class="bcf-label">
            <?= __('Name', 'basic-contact-form') ?><?= in_array('name', $required) ? '*' : ''; ?>
          </label>
          <input
            class="bcf-input<?= array_key_exists('name', $errors) && $errors['name'] ? ' is-invalid' : '' ?>"
            placeholder="<?= __('Please enter your name', 'basic-contact-form') ?>"
            type="text"
            name="name"
            size="50"
            maxlength="80"
            value="<?= isset($data['name']) && $data['name']; ?>"
          />
          <?php if (array_key_exists('name', $errors)): ?>
            <span class="invalid-feedback bcf-message"><?= $errors['name'] ?></span>
          <?php endif; ?>
        </p>
      <?php endif; ?>
      <?php if (in_array('email', $fields)) : ?>
        <p class="bcf-field">
          <label class="bcf-label">
            <?= __('E-mail', 'basic-contact-form') ?><?= in_array('email', $required) ? '*' : ''; ?>
          </label>
          <input
            class="bcf-input<?= array_key_exists('email', $errors) && $errors['email'] ? ' is-invalid' : '' ?>"
            placeholder="<?= __('Please enter your e-mail address', 'basic-contact-form') ?>"
            type="email"
            name="email"
            size="50"
            maxlength="80"
            value="<?= isset($data['email']) && $data['email'] ?>"
          />
          <?php if (array_key_exists('email', $errors)): ?>
            <span class="invalid-feedback bcf-message"><?= $errors['email'] ?></span>
          <?php endif; ?>
        </p>
      <?php endif; ?>
      <?php if (in_array('subject', $fields)) : ?>
        <p class="bcf-field">
          <label><?= __('Subject', 'basic-contact-form') ?><?= in_array('subject', $required) ? '*' : ''; ?></label>
          <input
            class="bcf-input<?= array_key_exists('subject', $errors) && $errors['subject'] ? ' is-invalid' : '' ?>"
            placeholder="<?= __('Please enter a subject', 'basic-contact-form') ?>"
            type="text"
            name="subject"
            size="50"
            maxlength="256"
            value="<?= isset($data['subject']) && $data['subject'] ?>"
          />
          <?php if (array_key_exists('subject', $errors)): ?>
            <span class="invalid-feedback bcf-message"><?= $errors['subject'] ?></span>
          <?php endif; ?>
        </p>
      <?php endif; ?>
      <?php if (in_array('message', $fields)) : ?>
        <p class="bcf-field">
          <label class="bcf-label">
            <?= __('Message', 'basic-contact-form') ?><?= in_array('message', $required) ? '*' : ''; ?>
          </label>
          <textarea
            class="bcf-input<?= array_key_exists('message', $errors) && $errors['message'] ? ' is-invalid' : '' ?>"
            placeholder="<?= __('Please enter a message', 'basic-contact-form') ?>"
            name="message"
            size="50"
            rows="8"
          ><?= isset($data['message']) && $data['message'] ?></textarea>
          <?php if (array_key_exists('message', $errors)): ?>
            <span class="invalid-feedback bcf-message"><?= $errors['message'] ?></span>
          <?php endif; ?>
        </p>
      <?php endif; ?>
      <?php if (basic_contact_form_has_captcha()): ?>
        <div class="bcf-field">
          <?php basic_contact_form_captcha(); ?>
          <?php if (array_key_exists('captcha', $errors)): ?>
            <span class="invalid-feedback bcf-message"><?= $errors['captcha'] ?></span>
          <?php endif; ?>
        </div>
      <?php endif; ?>
      <p class="bcf-footer">
        <button class="bcf-submit" type="submit"><?= __('Send', 'basic-contact-form'); ?></button>
      </p>
    </form>
  <?php endif; ?>
</div>
