# wp-basic-contact-form

Yet another Wordpress contact form plugin

## Usage

In order to create a basic contact form on your site, you can either assemble from Gutenberg blocks or use classic short code as described below.

### Blocks

Create a new page titled e.g. "Contact". Say what you want your users to appreciate before inquiring. Insert `basic-contact-form/form` block from the editor.

#### Thank you page

Create a new page titled e.g. "Thank you". Switch back to your contact page and select your form block. In block settings, set `redirectTo` to your newly created page.

### Shortcode

Place shortcode inside post content. 

```
[basic_contact_form]
```

#### Shortcode Attributes

| Name            | Description           | Default     |
| --------------- | --------------------- | ----------- |
| `title`         | Form title            | `'Get in contact with us!'`
| `description`   | Form description      | `'Please use our contact form for your inquiry`
| `to`            | Recipient email       | Wordpress admin email
| `fields`        | Comma-separated list of fields to be included in the form | `'name,email,subject,message'`
| `required`      | Comma-separated list of required fields   | `'email'` |
| `template`      | Template file         | The plugin's default template |


#### Customize

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

An example on how to adjust form template for Bootstrap 4 can be found [here](/test/fixtures/themes/basic-contact-form/contact-form.php)

## Plugin settings

#### Recipient email

The target email address, defaults to blog admin

#### Captcha



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

Open up mailhog at [http://localhost:8025](http://localhost:8025).


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
