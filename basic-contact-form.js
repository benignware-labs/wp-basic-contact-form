import ajaxform from 'ajaxform';
console.log('**** erwwerwHELLO');

ajaxform('*[data-basic-contact-form]', {
  request: {
    headers: {
      'X-Ajaxform': 'basic-contact-form'
    }
  }
});
