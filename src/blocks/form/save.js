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

		} = attributes;

		return (
			<form className={ classnames('bcf-form', className) }>
				<InnerBlocks.Content />
			</form>
		);
	}
}

export default FormSave;
