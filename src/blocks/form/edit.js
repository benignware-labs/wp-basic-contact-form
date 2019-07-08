import './editor.scss';
import './style.scss';

/**
 * External dependencies
 */
import classnames from 'classnames';
import { get } from 'lodash';
import humanizeString from 'humanize-string';
import { camelizeKeys } from 'humps';



/**
 * WordPress dependencies
 */
const { __, _x } = wp.i18n;
const {
	InnerBlocks
} = wp.editor;

const { Component, Fragment } = wp.element;

const {
} = wp.components;

const { withSelect } = wp.data;

/**
 * Constants
 */
const TEMPLATE = [
	[ 'core/heading', { level: 2, className: 'bcf-title', placeholder: __( 'Title' ) } ],
	[ 'core/paragraph', {  placeholder: __( 'Description' ) } ],
	[ 'basic-contact-form/textfield', {
		label: __('Name'),
		name: 'name',
		placeholder: _( 'Please enter your name' )
	} ],
	[ 'basic-contact-form/textfield', {
		type: 'email',
		label: __('Email'),
		name: 'email',
		placeholder: _( 'Please enter your Email' )
	} ],
	[ 'basic-contact-form/select', {
		label: __('Hello'),
		options: [{
			value: 'Hello World!'
		}, {
			value: 'Hello World 2!'
		}]
	} ],
	[ 'basic-contact-form/textfield', {  type: 'submit', text: __('Submit') } ],
];

class FormEdit extends Component {
	constructor() {
		super( ...arguments );
	}

	render() {
		const {
			attributes,
			className,
			isSelected,
			setAttributes,
		} = this.props;

		const {
		} = attributes;

		return (
			<form className={className}>
				<InnerBlocks
					template={ TEMPLATE }
					templateInsertUpdatesSelection={ false }
				/>
			</form>
		);
	}
}

export default FormEdit;
