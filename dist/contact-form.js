(function(window, remoteform) {
  remoteform('*[data-basic-contact-form]', {
    request: {
      headers: {
        'X-Remoteform': 'basic-contact-form'
      }
    }
  });
})(window, window.remoteform);
