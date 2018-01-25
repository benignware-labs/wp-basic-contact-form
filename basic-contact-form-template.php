<div class="bcf">
  <h3 class="bcf-title">Get in contact</h3>
  <?php if ($success) :?>
    <p>
      Thanks for your e-mail!<br/>
      We'll get back to you as soon as we can.
    </p>
  <?php else: ?>
    <form class="bcf-form">
      <p class="bcf-field">
        <label for="bcf-email">E-mail</label>
        <input class="bcf-email <?= $errors['email'] ? 'bcf-error' : '' ?>" placeholder="Please enter your E-mail" type="text" name="email" id="bcf_email" size="50" maxlength="50" value="<?= $fields['email'] ?>" />
        <?php if ($errors['email']): ?>
          <span class="bcf-message bcf-error"><?= $errors['email'] ?></span>
        <?php endif; ?>
      </p>
      <p class="bcf-footer">
        <input class="bcf-submit" type="submit" value="Send" name="send" id="bcf_send" />
      </p>
    </form>
  <?php endif; ?>
</div>
