import classnames from 'classnames';
import './style.css';

const { useBlockProps } = wp.blockEditor;

export default function ( { attributes, className, ...props }) {
	const blockProps = useBlockProps.save();
	const {
		type,
		required,
		label,
		name,
		placeholder,
	} = attributes;

	return (
		<div {...blockProps} className={classnames('bcf-field', required && 'is-required', className)}>
			<label className="bcf-label">{label}</label>
			<input
				required={required}
				type={type}
				name={name}
				placeholder={placeholder}
				className="bcf-input"
			/>
			<div className="bcf-message"></div>
		</div>
	);
}