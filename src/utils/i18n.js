export const __ = (string, domain = 'basic-contact-form') => {
	const t = wp.i18n.__(string, domain);

	if (t !== string) {
		return t;
	}

  if (window.basicContactForm) {
    const entry = window.basicContactForm.i18n.find(item => item.singular === string || item.plural === string);

    if (entry && entry.translations.length > 0) {
      return entry.translations[0];
    }
  }

	return string;
}
