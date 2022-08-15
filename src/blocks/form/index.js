/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks

/**
 * Internal dependencies
 */
import edit from './edit';
import icon from './icon';
import metadata from './block.json';
import save from './save';

const { name } = metadata;

export { metadata, name };

export const settings = {
	title: __( 'Form' ),
	description: __( 'Form block.' ),
	icon,
	keywords: [ __( 'form' ) ],
	supports: {
		align: [ 'wide', 'full' ],
		html: false,
	},
	edit,
	save,
};

registerBlockType(name, {
	...metadata,
	...settings
});
