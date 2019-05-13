import '@babel/polyfill';
import 'url-polyfill';
import 'mdn-polyfills/Element.prototype.closest';
import 'mdn-polyfills/Element.prototype.classList';
import 'isomorphic-fetch';

import remoteform from 'remoteform/src/remoteform';

remoteform('*[data-basic-contact-form]', {
  request: {
    headers: {
      'X-Remoteform': 'basic-contact-form'
    }
  }
});
