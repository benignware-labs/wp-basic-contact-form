

/**
 * External dependencies
 */
import classnames from 'classnames';

import EditableList from '../../components/EditableList';

import { getUniqueId } from '../../utils';
import { __ } from '../../utils/i18n';

/**
 * WordPress dependencies
 */
const {
	InspectorControls,
} = wp.blockEditor;

const { Component, Fragment } = wp.element;

const {
	PanelBody,
	ToggleControl,
	TextControl,
	Button,
} = wp.components;

class SelectEdit extends Component {
	constructor() {
		super( ...arguments );

		this.createOptionInput = this.createOptionInput.bind(this);
	}

	createOptionInput(id, value = '') {
		const { setAttributes, attributes } = this.props;
		const { options } = attributes;

		return (
			<TextControl data-id={id} type="text" defaultValue={value} onChange={(value) => {
				const nextOptions = options.map((option) => {
					const result = option.id === id ? {
						...option,
						value,
						label: value
					} : option;

					return result;
				});

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

		return (
			<Fragment>
				<InspectorControls>
					<PanelBody title={ __( 'Select Settings', 'basic-contact-form' ) }>
						<TextControl
							label={ __( 'Label', 'basic-contact-form' ) }
							value={ label }
							onChange={(value) => setAttributes({
								label: value,
							})}
						/>
						<TextControl
							label={ __( 'Name', 'basic-contact-form' ) }
							value={ name }
							onChange={(value) => setAttributes({
								name: value,
							})}
						/>
						<ToggleControl
							label={ __( 'Required', 'basic-contact-form' ) }
							checked={ required }
							onChange={(value) => setAttributes({
								required: value,
							})}
						/>
						<EditableList
							label={ __( 'Options', 'basic-contact-form' ) }
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

				              }}>{ __('Add Option', 'basic-contact-form') }</Button>
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
					<label className="bcf-label">{label}</label>
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
