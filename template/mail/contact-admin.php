<?= sprintf( esc_html__( 'Hello %s Team,', 'basic-contact-form' ), get_bloginfo( 'name' ) ); ?>

a Contact Form Request has been submitted.

<br/>
<br/>
<table border="1">
  <tbody>
   <?php foreach($data as $key => $value): ?>
     <tr>
       <td><?= $key; ?></td>
       <td><?= $value; ?></td>
     </tr>
   <?php endforeach; ?>
  <tbody>
</table>
