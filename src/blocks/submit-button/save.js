/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
const {
	RichText,
} = wp.blockEditor;

export default function save( { attributes } ) {
	const {
		text
	} = attributes;

	const buttonClasses = classnames(
		'bcf-submit-button',
		'bcf-button'
	);

	return (
		<div>
			<RichText.Content
				tagName="button"
				className={ buttonClasses }
				value={ text }
			/>
		</div>
	);
}
