import classnames from 'classnames';
import humanizeString from 'humanize-string';

const { __ } = wp.i18n;
const { InspectorControls } = wp.blockEditor;
const { Component, Fragment } = wp.element;
const {
	PanelBody,
	SelectControl,
	ToggleControl,
	TextControl
} = wp.components;

class TextfieldEdit extends Component {
	constructor() {
		super( ...arguments );
	}

	render() {
		const {
			attributes,
			className,
			isSelected,
			setAttributes,
		} = this.props;

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
					<PanelBody title={ __( 'Textfield Settings' ) }>

						<TextControl
							label={ __( 'Label' ) }
							value={ label }
							onChange={(value) => setAttributes({
								label: value,
							})}
						/>
						<TextControl
							label={ __( 'Name' ) }
							value={ name }
							onChange={(value) => setAttributes({
								name: value,
							})}
						/>
						<TextControl
							label={ __( 'Placeholder' ) }
							value={ placeholder }
							onChange={(value) => setAttributes({
								placeholder: value,
							})}
						/>
						<SelectControl
							label={__( 'Type' )}
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
							label={ __( 'Required' ) }
							checked={ required }
							onChange={(value) => setAttributes({
								required: value,
							})}
						/>
					</PanelBody>
				</InspectorControls>
				<div className={classnames('bcf-field', required ? 'is-required' : '', className)}>
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
}

export default TextfieldEdit;
