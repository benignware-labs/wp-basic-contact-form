import './editor.scss';
import './style.scss';

/**
 * External dependencies
 */
import classnames from 'classnames';
import { get } from 'lodash';
import humanizeString from 'humanize-string';
import { camelizeKeys } from 'humps';

import { getUniqueId } from '../../utils';

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

	componentDidMount() {
		const {
			attributes,
			setAttributes,
		} = this.props;

		const {
			id
		} = attributes;

		if (!id) {
			setAttributes({
				...attributes,
				id: getUniqueId('checkbox')
			});
		}
	}

	render() {
		const {
			attributes,
			className,
			isSelected,
			setAttributes,
		} = this.props;

		const {
			id,
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
				<div className={classnames(
					'bcf-field',
					'bcf-field-checkbox',
					required ? 'is-required' : '',
					className
				)}>
					<input
						required={required}
						id={`bcf-input-${id}`}
						name={name}
						type="checkbox"
						className="bcf-checkbox"
					/>
					<RichText
						tagName="label"
						format="string"
						for={`bcf-input-${id}`}
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
