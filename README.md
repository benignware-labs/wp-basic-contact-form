# wp-basic-contact-form

Yet another Wordpress contact form plugin

## Usage

Place shortcode inside post content

```
[basic_contact_form]
```

## Customize

Customize programmatically by adding attributes to shortcode

```php
// Customize Basic Contact Form
add_filter('shortcode_atts_basic_contact_form', function($out, $pairs, $atts, $shortcode) {
  return array_merge($out, array(
    'to' => 'admin@example.com', // By default, it sends to admin
    'template' => get_theme_file_path() . '/contact-form.php'
  ), $atts);
}, 10, 4);
```


Adjust form template for Bootstrap 4

```php
<div class="contact">
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
      <p class="contact-description lead"><?= $description ?></p>
    <?php endif; ?>
    <form class="contact-form" method="POST">
      <?php if (in_array('name', $fields)) : ?>
        <p class="contact-field form-group">
          <label class="contact-label">
            <?= __('Name', 'basic-contact-form') ?><?= in_array('name', $required) ? '*' : ''; ?>
          </label>
          <input class="form-control contact-input<?= $errors['name'] ? ' is-invalid' : '' ?>" placeholder="<?= __('Please enter your name', 'basic-contact-form') ?>" type="text" name="name" size="50" maxlength="80" value="<?= $data['name'] ?>" />
          <?php if (array_key_exists('name', $errors)): ?>
            <span class="invalid-feedback contact-message"><?= $errors['name'] ?></span>
          <?php endif; ?>
        </p>
      <?php endif; ?>
      <?php if (in_array('email', $fields)) : ?>
        <p class="contact-field form-group">
          <label class="contact-label">
            <?= __('E-mail', 'basic-contact-form') ?><?= in_array('email', $required) ? '*' : ''; ?>
          </label>
          <input class="form-control contact-input<?= $errors['email'] ? ' is-invalid' : '' ?>" placeholder="<?= __('Please enter your e-mail address', 'basic-contact-form') ?>" type="text" name="email" size="50" maxlength="80" value="<?= $data['email'] ?>" />
          <?php if (array_key_exists('email', $errors)): ?>
            <span class="invalid-feedback contact-message"><?= $errors['email'] ?></span>
          <?php endif; ?>
        </p>
      <?php endif; ?>
      <?php if (in_array('subject', $fields)) : ?>
        <p class="contact-field form-group">
          <label><?= __('Subject', 'basic-contact-form') ?><?= in_array('subject', $required) ? '*' : ''; ?></label>
          <input class="form-control contact-input<?= $errors['subject'] ? ' is-invalid' : '' ?>" placeholder="<?= __('Please enter a subject', 'basic-contact-form') ?>" type="text" name="subject" size="50" maxlength="256" value="<?= $data['subject'] ?>" />
          <?php if (array_key_exists('subject', $errors)): ?>
            <span class="invalid-feedback contact-message"><?= $errors['subject'] ?></span>
          <?php endif; ?>
        </p>
      <?php endif; ?>
      <?php if (in_array('message', $fields)) : ?>
        <p class="contact-field form-group">
          <label class="contact-label">
            <?= __('Message', 'basic-contact-form') ?><?= in_array('message', $required) ? '*' : ''; ?>
          </label>
          <textarea class="form-control contact-input<?= $errors['message'] ? ' is-invalid' : '' ?>" placeholder="<?= __('Please describe what your inquiry is about', 'basic-contact-form') ?>" name="message" size="50" rows="8"><?= $data['message'] ?></textarea>
          <?php if (array_key_exists('message', $errors)): ?>
            <span class="invalid-feedback contact-message"><?= $errors['message'] ?></span>
          <?php endif; ?>
        </p>
      <?php endif; ?>
      <p class="contact-footer">
        <button class="btn btn-primary contact-submit" type="submit"><?= __('Send', 'basic-contact-form'); ?></button>
      </p>
    </form>
  <?php endif; ?>
</div>
```


## Options

| Name            | Description           | Default     |
| --------------- | --------------------- | ----------- |
| `title`         | Form title            | `'Get in contact with us!'`
| `description`   | Form description      | `'Please use our contact form for your inquiry`
| `to`            | Recipient email       | Wordpress admin email
| `fields`        | Comma-separated list of fields to be included in the form | `'name,email,subject,message'`
| `required`      | Comma-separated list of required fields   | `'email'` |
| `template`      | Template file         | The plugin's default template |



## Development

Download [Docker CE](https://www.docker.com/get-docker) for your OS.
Download [NodeJS](https://nodejs.org) for your OS.

### Install

#### Install wordpress

```cli
docker-compose run --rm wp install-wp
```

After installation you can log in with user `wordpress` and password `wordpress`.

#### Install front-end dependencies

```cli
npm i
```

### Development Server

Point terminal to your project root and start up the container.

```cli
docker-compose up -d
```

Point your browser to [http://localhost:8030](http://localhost:8030).

### Mailer

Setup phpmailer to send via mailhog

```php
// Setup phpmailer
add_action( 'phpmailer_init', function($phpmailer) {
  $phpmailer->Host = 'mailhog';
  $phpmailer->Port = 1025;
  $phpmailer->IsSMTP();
});
```

#### Watch front-end dependencies

```cli
npm run watch
```

### Docker

##### Update composer dependencies

```cli
docker-compose run composer update
```

##### Globally stop all running docker containers

```cli
docker stop $(docker ps -a -q)
```

## Production

Create a build for production

```cli
npm run build
```
