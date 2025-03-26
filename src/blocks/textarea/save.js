/**
 * External dependencies
 */
import classnames from 'classnames';

import './style.css';

const { useBlockProps } = wp.blockEditor;

export default function( { attributes, className } ) {
	const blockProps = useBlockProps.save();
	const {
		required,
		label,
		placeholder,
		name,
	} = attributes;

	return (
		<div
			{...blockProps}
			className={classnames('bcf-field', required ? 'is-required' : '', className)}
		>
			<label className="bcf-label">{label}</label>
			<textarea
				name={name}
				required={required}
				placeholder={placeholder}
				className="bcf-textarea"
				rows={8}
				maxlength={300}
			/>
			<div className="bcf-message"></div>
		</div>
	);
}