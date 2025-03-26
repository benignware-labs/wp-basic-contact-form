<?php

function bc_render_submit_button_block($content, $block) {
  if ($block['blockName'] === 'basic-contact-form/submit-button') {
    $content = preg_replace('/<a/', '<button', $content);
  }

  return $content;
}

add_filter('render_block', 'bc_render_submit_button_block', 10, 2);