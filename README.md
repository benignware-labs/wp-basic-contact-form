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

See [/test/fixtures/themes/basic-contact-form/contact-form.php](here) for an example how to adjust form template for Bootstrap 4.


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
