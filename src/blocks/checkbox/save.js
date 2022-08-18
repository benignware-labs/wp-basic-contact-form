import classnames from 'classnames';
import './style.css';

const { Component } = wp.element;

class FormSave extends Component {
	render() {
		const { attributes, className, clientId, ...props } = this.props;
		const {
			id,
			type,
			name,
			label,
			placeholder,
			required
		} = attributes;

		return (
			<div className={classnames(
				'bcf-field',
				'bcf-field-checkbox',
				required ? 'is-required' : '',
				className
			)}>
				<input
					id={`bcf-input-${id}`}
					name={name}
					type="checkbox"
					className="bcf-checkbox"
					required={required}
				/>
				<label class="bcf-checkbox-label" for={`bcf-input-${id}`}>
					<span dangerouslySetInnerHTML={ { __html: label } } /></label>
				<div className="bcf-message"></div>
			</div>
		);
	}
}

export default FormSave;
