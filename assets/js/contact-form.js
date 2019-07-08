(function(window, remoteform) {
  console.log('hello forms');

  remoteform('*[data-basic-contact-form]', {
    request: {
      headers: {
        'X-Remoteform': 'basic-contact-form'
      }
    }
  });

})(window, window.remoteform);
