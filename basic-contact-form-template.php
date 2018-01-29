<div class="bcf">
  <?php if ($title): ?>
    <h3 class="bcf-title"><?= $title ?></h3>
  <?php endif; ?>
  <?php if ($description): ?>
    <p class="bcf-description"><?= $description ?></p>
  <?php endif; ?>
  <?php if ($success): ?>
    <p>
      Thanks for your e-mail!<br/>
      We'll get back to you as soon as we can.
    </p>
  <?php else: ?>
    <form class="bcf-form" method="POST">
      <?php if (in_array('name', $fields)) : ?>
        <p class="bcf-field">
          <label class="bcf-label">
            Name<?= in_array('name', $required) ? '*' : ''; ?>
            <input class="bcf-input-text bcf-name <?= $errors['name'] ? 'bcf-error' : '' ?>" placeholder="Please enter your name" type="text" name="name" size="50" maxlength="80" value="<?= $data['name'] ?>" />
          </label>
          <?php if (array_key_exists('name', $errors)): ?>
            <span class="bcf-message bcf-error"><?= $errors['name'] ?></span>
          <?php endif; ?>
        </p>
      <?php endif; ?>
      <?php if (in_array('email', $fields)) : ?>
        <p class="bcf-field">
          <label class="bcf-label">
            E-mail<?= in_array('email', $required) ? '*' : ''; ?>
            <input class="bcf-input-text bcf-email <?= $errors['email'] ? 'bcf-error' : '' ?>" placeholder="Please enter your e-mail address" type="text" name="email" size="50" maxlength="80" value="<?= $data['email'] ?>" />
          </label>
          <?php if (array_key_exists('email', $errors)): ?>
            <span class="bcf-message bcf-error"><?= $errors['email'] ?></span>
          <?php endif; ?>
        </p>
      <?php endif; ?>
      <?php if (in_array('subject', $fields)) : ?>
        <p class="bcf-field">
          <label>Subject<?= in_array('subject', $required) ? '*' : ''; ?>
            <input class="bcf-input-text bcf-subject <?= $errors['subject'] ? 'bcf-error' : '' ?>" placeholder="Please enter a subject" type="text" name="subject" size="50" maxlength="256" value="<?= $data['subject'] ?>" />
          </label>
          <?php if (array_key_exists('subject', $errors)): ?>
            <span class="bcf-message bcf-error"><?= $errors['subject'] ?></span>
          <?php endif; ?>
        </p>
      <?php endif; ?>
      <?php if (in_array('message', $fields)) : ?>
        <p class="bcf-field">
          <label class="bcf-label">
            Message<?= in_array('message', $required) ? '*' : ''; ?>
            <textarea class="bcf-input-textarea bcf-message <?= $errors['message'] ? 'bcf-error' : '' ?>" placeholder="Please describe what's your inquiry is about" name="message" rows="8"><?= $data['message'] ?></textarea>
          </label>
          <?php if (array_key_exists('message', $errors)): ?>
            <span class="bcf-message bcf-error"><?= $errors['message'] ?></span>
          <?php endif; ?>
        </p>
      <?php endif; ?>
      <p class="bcf-footer">
        <input class="bcf-submit" type="submit" value="Send" name="send" id="bcf_send" />
      </p>
    </form>
  <?php endif; ?>
</div>
