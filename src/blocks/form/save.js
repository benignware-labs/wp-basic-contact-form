import classnames from 'classnames';
import './style.css';

const { InnerBlocks } = wp.blockEditor;
const { Component } = wp.element;

class FormSave extends Component {
	render() {
		const { attributes, className, ...props } = this.props;
		const {
			id
		} = attributes;

		return (
			<form data-basic-contact-form={id} className={ classnames('bcf-form', className) }>
				<InnerBlocks.Content />
			</form>
		);
	}
}

export default FormSave;
