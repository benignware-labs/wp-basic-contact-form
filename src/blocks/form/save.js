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
