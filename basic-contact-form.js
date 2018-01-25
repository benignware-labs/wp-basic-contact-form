import ajaxform from 'ajaxform';

ajaxform('*[data-basic-contact-form]', {
  request: {
    headers: {
      'X-Ajaxform': 'basic-contact-form'
    }
  }
});
