/**
 * External dependencies
 */
import classnames from 'classnames';

import './style.css';

/**
 * WordPress dependencies
 */

const { Component } = wp.element;

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
					rows={8}
					maxlength={300}
				/>
				<div className="bcf-message"></div>
			</div>
		);
	}
}

export default FormSave;
