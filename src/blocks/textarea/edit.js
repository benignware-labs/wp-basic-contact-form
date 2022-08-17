

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
					<PanelBody title={ __( 'Textarea Settings' ) }>

						<TextControl
							label={ __( 'Label' ) }
							value={ label }
							onChange={(value) => setAttributes({
								label: value,
							})}
						/>
						<TextControl
							label={ __( 'Placeholder' ) }
							value={ placeholder }
							onChange={(value) => setAttributes({
								placeholder: value,
							})}
						/>
						<TextControl
							label={ __( 'Name' ) }
							value={ name }
							onChange={(value) => setAttributes({
								name: value,
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
					<textarea
						required={required}
						type={type}
						name={name}
						placeholder={placeholder}
						className="bcf-textarea"
					/>
					<div className="bcf-message"></div>
				</div>
			</Fragment>
		);
	}
}

export default TextfieldEdit;
