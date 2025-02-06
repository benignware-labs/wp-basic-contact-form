/**
 * Internal dependencies
 */
import deprecated from './deprecated';
import edit from './edit';
import icon from './icon';
import metadata from './block.json';
import save from './save';


import './editor.css';
import './style.css';

/**
 * WordPress dependencies
 */
const { __, _x } = wp.i18n;
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks



const { name } = metadata;

export { metadata, name };

export const settings = {
	title: __( 'Submit Button', 'basic-contact-form' ),
	description: __( 'Button to trigger form submission.', 'basic-contact-form' ),
	parent: 'basic-contact-form/form',
	icon,
	keywords: [ __( 'button', 'basic-contact-form' ) ],
	supports: {
		align: true,
		alignWide: false,
	},
	/*styles: [
		{ name: 'default', label: _x( 'Default', 'block style' ), isDefault: true },
		{ name: 'outline', label: __( 'Outline' ) },
		// { name: 'squared', label: _x( 'Squared', 'block style' ) },
	],*/
	edit,
	save,
	deprecated,
};

registerBlockType( name, {
	...metadata,
	...settings
});
