import classnames from 'classnames';
import humanizeString from 'humanize-string';
import { __ } from '../../utils/i18n';

const {
	InspectorControls,
	useBlockProps
} = wp.blockEditor;
const { Fragment } = wp.element;
const {
	PanelBody,
	SelectControl,
	ToggleControl,
	TextControl
} = wp.components;

export default function ({
	attributes,
	className,
	isSelected,
	setAttributes,
}) {
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
				<PanelBody title={ __( 'Textfield Settings', 'basic-contact-form' ) }>
					<TextControl
						label={ __( 'Label', 'basic-contact-form' ) }
						value={ label }
						onChange={(value) => setAttributes({
							label: value,
						})}
					/>
					<TextControl
						label={ __( 'Name' ,'basic-contact-form' ) }
						value={ name }
						onChange={(value) => setAttributes({
							name: value,
						})}
					/>
					<TextControl
						label={ __( 'Placeholder', 'basic-contact-form' ) }
						value={ placeholder }
						onChange={(value) => setAttributes({
							placeholder: value,
						})}
					/>
					<SelectControl
						label={__( 'Type', 'basic-contact-form' )}
						value={ attributes.type }
						options={ [ 'text', 'email' ].map((value) => ({
							label: __(humanizeString(value)), value
						})) }
						onChange={ ( value ) => this.props.setAttributes({
							...attributes,
							type: value
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
				{...blockProps}
				className={classnames('bcf-field', required && 'is-required', className)}
			>
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
		</Fragment>
	);
}