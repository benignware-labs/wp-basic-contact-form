

/**
 * External dependencies
 */
import classnames from 'classnames';
import { get } from 'lodash';
import humanizeString from 'humanize-string';
import { camelizeKeys } from 'humps';

import EditableList from '../../components/EditableList';

import { getUniqueId } from '../../utils';

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
	PanelRow,
	PanelBody,
	SelectControl,
	ToggleControl,
	TextControl,
	Dashicon,
	Button,
	Panel
} = wp.components;

const { withSelect } = wp.data;

class SelectEdit extends Component {
	constructor() {
		super( ...arguments );

		this.createOptionInput = this.createOptionInput.bind(this);
	}

	createOptionInput(id, value = '', label = '') {
		const { setAttributes, attributes } = this.props;
		const { options } = attributes;

		return (
			<TextControl data-id={id} type="text" defaultValue={value} onChange={(value) => {
				console.log('value', value, 'ID: ', id);

				const nextOptions = options.map((option) => {
					console.log('option!!!...', option.id, ' ----- ', id, option.id === id);

					const result = option.id === id ? {
						...option,
						value,
						label: value
					} : option;

					return result;
				});

				console.log('nextOptions', nextOptions);

				setAttributes({
					...attributes,
					options: nextOptions
				})
			}}/>
		)
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
			options,
			required
		} = attributes;

		console.log('options...', options);
		return (
			<Fragment>
				<InspectorControls>
					<PanelBody title={ __( 'Select Settings' ) }>
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
						<ToggleControl
							label={ __( 'Required' ) }
							checked={ required }
							onChange={(value) => setAttributes({
								required: value,
							})}
						/>
						<EditableList
							label={ __( 'Options' ) }
							dropId={ `drop-container-${clientId}` }
							onItemsChange={(items) => {
								setAttributes({
									...attributes,
									options: items.map(({ id }) => options.find((option) => option.id === id))
								});
							}}
							items={options.map(({ value, label, id }, index) => {
								return ({
									id: id || getUniqueId('option'),
									removable: true,
									content: this.createOptionInput(id, value, label)
								});
							})}
							render={({ content, items, add, props: { max } }) => (
				        <div>
				          {content}
				          <footer>
				            { (!max || items.length < max) ? (
				              <Button style={{ width: '100%', display: 'flex', justifyContent: 'center' }} isDefault isLarge onClick={() => {
												const id = getUniqueId('option');

				                add({
													id,
													removable: true,
													content: this.createOptionInput(id)
												});

												setAttributes({
													...attributes,
													options: [
														...attributes.options,
														{ id, value: '', label: '' }
													]
												})

				              }}>{ __('Add Option') }</Button>
				            ) : (
				              <span>Item limit has been reached. Please remove an item before adding another one.</span>
				            )}
				          </footer>
				        </div>
				      )}
						/>
					</PanelBody>
				</InspectorControls>
				<div className={classnames('bcf-field', required ? 'is-required' : '', className)}>
					<label className="bcf-label">{label}{required ? '*' : ''}</label>
					<select
						name={name}
						required={required}
						className="bcf-select"
					>
						{options.map(({ value, label, id }) => (
							<option data-id={id} key={value} value={value}>{label || value}</option>
						))}
					</select>
					<div className="bcf-message"></div>
				</div>
			</Fragment>
		);
	}
}

export default SelectEdit;
