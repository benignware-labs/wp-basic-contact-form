# wp-basic-contact-form

The most basic contact-form ever

## Usage

Place shortcode inside post content

```
[basic_contact_form]
```

### Customize

Customize by programmatically adding attributes to shortcode

```php
// Customize Basic Contact Form
function custom_shortcode_atts_basic_contact_form($out, $pairs, $atts, $shortcode) {
  $result = array_merge($out, array(
    'to' => 'admin@example.com', // By default, it sends to admin
    'template' => 'file://path-to-template-file.php'
  ), $atts);
  return $result;
}
add_filter( 'shortcode_atts_basic_contact_form', 'custom_shortcode_atts_basic_contact_form', 10, 4);
```
