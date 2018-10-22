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
// Customize basic-contact-form shortcode
function custom_shortcode_atts_basic_contact_form($out, $pairs, $atts, $shortcode) {
  $result = array_merge($out, array(
    'to' => 'admin@example.com', // By default, it sends to admin
    'template' => 'file://path-to-template-file.php'
  ), $atts);
  return $result;
}
add_filter( 'shortcode_atts_basic_contact_form', 'custom_shortcode_atts_basic_contact_form', 10, 4);
```

## Options

| Name          | Description           | Default     |
| ------------- | --------------------- | ----------- |
| `title`       | Form title            | `'Get in contact with us!'`
| `description`       | Form description            | `'Please use our contact form for your inquiry`
| `to`          | Recipient email       | Wordpress admin email
| `fields`      | Comma-separated list of fields to be included in the form | `'name,email,subject,message'`
| `required`    | Comma-separated list of required fields   | `'email'` |
| `template`    | Template file   | The plugin's default template |


## Development

Download [Docker CE](https://www.docker.com/get-docker) for your OS.

### Environment

Point terminal to your project root and start up the container.

```cli
docker-compose up -d
```

Open your browser at [http://localhost:9030](http://localhost:9030).

Activate the plugin.

### Useful commands

#### Startup services

```cli
docker-compose up -d
```
You may omit the `-d`-flag for verbose output.

#### Shutdown services

In order to shutdown services, issue the following command

```cli
docker-compose down
```

#### List containers

```cli
docker-compose ps
```

#### Remove containers

```cli
docker-compose rm
```

#### Update wordpress admin email

```cli
docker-compose run wp-cli option update admin_email admin@example.com
```

#### Open wordpress bash

Open bash at wordpress directory

```cli
docker-compose exec wordpress bash
```

#### Update composer dependencies

If it's complaining about the composer.lock file, you probably need to update the dependencies.

```cli
docker-compose run composer update
```

#### List all globally running docker containers

```cli
docker ps
```

#### Globally stop all running docker containers

If you're working with multiple docker projects running on the same ports, you may want to stop all services globally.

```cli
docker stop $(docker ps -a -q)
```

#### Globally remove all containers

```cli
docker rm $(docker ps -a -q)
```

#### Remove all docker related stuff

```cli
docker system prune
```
