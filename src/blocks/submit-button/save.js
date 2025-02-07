/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
const {
	RichText,
	InnerBlocks
} = wp.blockEditor;

export default function save( { attributes, className } ) {
	const {
		text
	} = attributes;

	return (
		<div className={className}>
			<InnerBlocks.Content />
		</div>
	);
}
