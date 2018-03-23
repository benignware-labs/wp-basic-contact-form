import remoteform from 'remoteform';

remoteform('*[data-basic-contact-form]', {
  request: {
    headers: {
      'X-Remoteform': 'basic-contact-form'
    }
  }
});
