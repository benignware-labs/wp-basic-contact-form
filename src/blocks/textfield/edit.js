

/**
 * External dependencies
 */
import classnames from 'classnames';
import { get } from 'lodash';
import humanizeString from 'humanize-string';
import { camelizeKeys } from 'humps';

/**
 * WordPress dependencies
 */
const { __, _x } = wp.i18n;
const {
	InnerBlocks,
	InspectorControls,
	RichText
} = wp.editor;


const { Component, Fragment } = wp.element;

const {
	PanelBody,
	SelectControl,
	ToggleControl,
	TextControl
} = wp.components;

const { withSelect } = wp.data;

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
							checked={ label }
							onChange={(value) => setAttributes({
								label: value,
							})}
						/>
						<TextControl
							label={ __( 'Name' ) }
							checked={ name }
							onChange={(value) => setAttributes({
								name: value,
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
				<div className={classnames('bcf-field', className)}>
					<label className="bcf-label">{label}{required ? '*' : ''}</label>
					<input
						required={required}
						type={type}
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
