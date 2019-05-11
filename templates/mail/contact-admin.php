
<?= sprintf( esc_html__( 'Hello %s,', 'basic-contact-form' ), get_bloginfo( 'name' ) ); ?>

<?php if ($data['message']): ?>
<?= $data['message'] ?>
<?php endif; ?>


<?php if ($data['name']): ?>
<?= __('Yours sincerely', 'basic-contact-form') ?>,
<?= $data['name'] ?>
<? endif; ?>
