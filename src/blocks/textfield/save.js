/**
 * External dependencies
 */
import classnames from 'classnames';
import { noop } from 'lodash';

import './style.scss';

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
			type,
			required,
			label,
			placeholder,
		} = attributes;

		return (
			<div className={classnames('bcf-field', required ? 'is-required' : '', className)}>
				<label className="bcf-label">{label}</label>
				<input
					required={required}
					type={type}
					placeholder={placeholder}
					className="bcf-input"
				/>
				<div className="bcf-message"></div>
			</div>
		);
	}
}

export default FormSave;
