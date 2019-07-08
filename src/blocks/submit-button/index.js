/**
 * Internal dependencies
 */
import deprecated from './deprecated';
import edit from './edit';
import icon from './icon';
import metadata from './block.json';
import save from './save';


import './editor.scss';
import './style.scss';

/**
 * WordPress dependencies
 */
const { __, _x } = wp.i18n;
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks



const { name } = metadata;

export { metadata, name };

export const settings = {
	title: __( 'Submit Button' ),
	description: __( 'Button to trigger form submission.' ),
	icon,
	keywords: [ __( 'button' ) ],
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
