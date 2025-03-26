

/**
 * External dependencies
 */
import classnames from 'classnames';
import { __ } from '../../utils/i18n';

/**
 * WordPress dependencies
 */
const { InspectorControls, useBlockProps } = wp.blockEditor;
const { Fragment } = wp.element;

const {
	PanelBody,
	ToggleControl,
	TextControl
} = wp.components;

export default function ({
	attributes,
	className,
	isSelected,
	setAttributes,
} ) {
	const blockProps = useBlockProps();
	const {
		type,
		label,
		name,
		placeholder,
		required
	} = attributes;

	return (
		<Fragment>
			<InspectorControls>
				<PanelBody title={ __( 'Textarea Settings', 'basic-contact-form' ) }>

					<TextControl
						label={ __( 'Label' ) }
						value={ label }
						onChange={(value) => setAttributes({
							label: value,
						})}
					/>
					<TextControl
						label={ __( 'Placeholder', 'basic-contact-form' ) }
						value={ placeholder }
						onChange={(value) => setAttributes({
							placeholder: value,
						})}
					/>
					<TextControl
						label={ __( 'Name', 'basic-contact-form') }
						value={ name }
						onChange={(value) => setAttributes({
							name: value,
						})}
					/>
					<ToggleControl
						label={ __( 'Required' , 'basic-contact-form') }
						checked={ required }
						onChange={(value) => setAttributes({
							required: value,
						})}
					/>
				</PanelBody>
			</InspectorControls>
			<div
				className={classnames('bcf-field', required ? 'is-required' : '', className)}
				{...blockProps}
			>
				<label className="bcf-label">{label}</label>
				<textarea
					required={required}
					type={type}
					name={name}
					placeholder={placeholder}
					className="bcf-textarea"
					rows={8}
					maxlength={300}
				/>
				<div className="bcf-message"></div>
			</div>
		</Fragment>
	);
}