import { __ } from '../../utils/i18n';

import edit from './edit';
import icon from './icon';
import metadata from './block.json';
import save from './save';

const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks
const { name } = metadata;

export { metadata, name };

export const settings = {
	title: __( 'Checkbox', 'basic-contact-form' ),
	description: __( 'Checkbox block.', 'basic-contact-form' ),
	parent: 'basic-contact-form/form',
	icon,
	keywords: [ __( 'checkbox', 'basic-contact-form' ) ],
	supports: {
		align: [ 'wide', 'full' ],
		html: false,
	},
	// transforms,
	edit,
	save,
	/*
	getEditWrapperProps( attributes ) {

		const classes = classnames(
			// className,
			'bcf-field'
		);

		return {
			className: classes,
		};
	},
	*/
};

registerBlockType(name, {
	...metadata,
	...settings
});
