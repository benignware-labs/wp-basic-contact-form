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

class FormSave extends Component {
	render() {
		const { attributes, className, ...props } = this.props;
		const {
			required,
			label,
			placeholder,
			name,
		} = attributes;

		return (
			<div className={classnames('bcf-field', required ? 'is-required' : '', className)}>
				<label className="bcf-label">{label}</label>
				<textarea
					name={name}
					required={required}
					placeholder={placeholder}
					className="bcf-textarea"
				/>
				<div className="bcf-message"></div>
			</div>
		);
	}
}

export default FormSave;
