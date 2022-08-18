import classnames from 'classnames';
import './style.css';

const { Component } = wp.element;

class FormSave extends Component {
	render() {
		const { attributes, className, ...props } = this.props;
		const {
			type,
			required,
			label,
			name,
			placeholder,
		} = attributes;

		return (
			<div className={classnames('bcf-field', required ? 'is-required' : '', className)}>
				<label className="bcf-label">{label}</label>
				<input
					required={required}
					type={type}
					name={name}
					placeholder={placeholder}
					className="bcf-input"
				/>
				<div className="bcf-message"></div>
			</div>
		);
	}
}

export default FormSave;
