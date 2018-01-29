<div class="bcf">
  <?php if ($title): ?>
    <h3 class="bcf-title"><?= $title ?></h3>
  <?php endif; ?>
  <?php if ($description): ?>
    <p class="bcf-description"><?= $description ?></p>
  <?php endif; ?>
  <?php if ($success): ?>
    <?= __('Thanks for your request! We\'ll get back to you as soon as we can.', 'basic-contact-form'); ?>
    </p>
  <?php else: ?>
    <form class="bcf-form" method="POST">
      <?php if (in_array('name', $fields)) : ?>
        <p class="bcf-field">
          <label class="bcf-label">
            <?= __('Name', 'basic-contact-form') ?><?= in_array('name', $required) ? '*' : ''; ?>
          </label>
          <input class="bcf-input-text bcf-name <?= $errors['name'] ? 'bcf-error' : '' ?>" placeholder="<?= __('Please enter your name', 'basic-contact-form') ?>" type="text" name="name" size="50" maxlength="80" value="<?= $data['name'] ?>" />
          <?php if (array_key_exists('name', $errors)): ?>
            <span class="bcf-message bcf-error"><?= $errors['name'] ?></span>
          <?php endif; ?>
        </p>
      <?php endif; ?>
      <?php if (in_array('email', $fields)) : ?>
        <p class="bcf-field">
          <label class="bcf-label">
            <?= __('E-mail', 'basic-contact-form') ?><?= in_array('email', $required) ? '*' : ''; ?>
          </label>
          <input class="bcf-input-text bcf-email <?= $errors['email'] ? 'bcf-error' : '' ?>" placeholder="<?= __('Please enter your e-mail address', 'basic-contact-form') ?>" type="text" name="email" size="50" maxlength="80" value="<?= $data['email'] ?>" />
          <?php if (array_key_exists('email', $errors)): ?>
            <span class="bcf-message bcf-error"><?= $errors['email'] ?></span>
          <?php endif; ?>
        </p>
      <?php endif; ?>
      <?php if (in_array('subject', $fields)) : ?>
        <p class="bcf-field">
          <label>
            <?= __('Subject', 'basic-contact-form') ?><?= in_array('subject', $required) ? '*' : ''; ?>
          </label>
          <input class="bcf-input-text bcf-subject <?= $errors['subject'] ? 'bcf-error' : '' ?>" placeholder="<?= __('Please enter a subject', 'basic-contact-form') ?>" type="text" name="subject" size="50" maxlength="256" value="<?= $data['subject'] ?>" />
          <?php if (array_key_exists('subject', $errors)): ?>
            <span class="bcf-message bcf-error"><?= $errors['subject'] ?></span>
          <?php endif; ?>
        </p>
      <?php endif; ?>
      <?php if (in_array('message', $fields)) : ?>
        <p class="bcf-field">
          <label class="bcf-label">
            <?= __('Message', 'basic-contact-form') ?><?= in_array('message', $required) ? '*' : ''; ?>
          </label>
          <textarea class="bcf-input-textarea bcf-message <?= $errors['message'] ? 'bcf-error' : '' ?>" placeholder="<?= __('Please describe what your inquiry is about', 'basic-contact-form') ?>" name="message" rows="8"><?= $data['message'] ?></textarea>
          <?php if (array_key_exists('message', $errors)): ?>
            <span class="bcf-message bcf-error"><?= $errors['message'] ?></span>
          <?php endif; ?>
        </p>
      <?php endif; ?>
      <p class="bcf-footer">
        <button class="bcf-submit" type="submit"><?= __('Send', 'basic-contact-form'); ?></button>
      </p>
    </form>
  <?php endif; ?>
</div>
