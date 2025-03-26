import './editor.css';
import './style.css';
import { __ } from '../../utils/i18n';
import edit from './edit';
import icon from './icon';
import metadata from './block.json';
import save from './save';

const { registerBlockType } = wp.blocks;
const { name } = metadata;

export { metadata, name };

export const settings = {
	title: __( 'Textfield', 'basic-contact-form' ),
	description: __( 'Textfield block.', 'basic-contact-form' ),
	parent: 'basic-contact-form/form',
	icon,
	keywords: [ __( 'textfield', 'basic-contact-form' ) ],
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
