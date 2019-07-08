import './editor.scss';
import './style.scss';

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
			clientId,
			attributes,
			className,
			isSelected,
			setAttributes,
		} = this.props;

		const {
			label,
			name,
			required
		} = attributes;

		return (
			<Fragment>
				<InspectorControls>
					<PanelBody title={ __( 'Checkbox Settings' ) }>
						<TextControl
							label={ __( 'Name' ) }
							checked={ name }
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
				<div className={classnames('bcf-field', className)}>
					<input
						required={required}
						id={`bcf-input-${clientId}`}
						name={name}
						type="checkbox"
						className="bcf-checkbox"
					/>
					<RichText
						tagName="label"
						format="string"
						for={`bcf-input-${clientId}`}
						className="bcf-checkbox-label"
						onChange={(value) => setAttributes({
							label: value
						})}
						value={label}
					/>
					<div className="bcf-message"></div>
				</div>
			</Fragment>
		);
	}
}

export default TextfieldEdit;
