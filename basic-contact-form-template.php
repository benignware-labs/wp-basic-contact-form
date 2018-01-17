<div class="<?= $class ?>">
  <form class="<?= $form_class ?>" method="post" action="<?= $action ?>">
    <div class="<?=  $field_class ?>">
      <label for="bcf-email"><?= $label_email ?></label>
      <input class="<?= $input_class ?>" placeholder="<?= $placeholder_email ?>" type="text" name="email" id="bcf_email" size="50" maxlength="50" value="<?= $form_data['email'] ?>" />
      <?php if ($form_errors['email']): ?>
        <div class="<?= $message_class ?> <?= $message_error_class ?>"><?= $form_errors['email'] ?></div>
      <?php endif; ?>
    </div>
    <div class="<?= $footer_class ?>">
      <input class="<?= $button_class ?>" type="submit" value="<?= $label_submit ?>" name="send" id="bcf_send" />
    </div>
  </form>
</div>
