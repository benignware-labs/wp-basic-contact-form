/**
 * External dependencies
 */
import classnames from 'classnames';
import { noop } from 'lodash';

import { getUniqueId } from '../../utils';

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
		const { attributes, className, clientId, ...props } = this.props;
		const {
			id,
			type,
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
				<label class="bcf-checkbox-label" for={`bcf-input-${id}`}><span dangerouslySetInnerHTML={ { __html: label } } /></label>
				<div className="bcf-message"></div>
			</div>
		);
	}
}

export default FormSave;
