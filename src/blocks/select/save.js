/**
 * External dependencies
 */
import classnames from 'classnames';
import { noop } from 'lodash';

import './style.css';

/**
 * WordPress dependencies
 */
const {
	InnerBlocks
} = wp.editor;

const { Component } = wp.element;

const { withSelect } = wp.data;

class SelectSave extends Component {
	render() {
		const { attributes, className, ...props } = this.props;
		const {
			name,
			label,
			options,
			required
		} = attributes;

		return (
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
		);
	}
}

export default SelectSave;
