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
	InnerBlocks,
	InspectorControls,
	URLInput,
	URLInputButton
} = wp.editor;

const { Component, Fragment } = wp.element;

const {
	PanelBody
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
		placeholder: __( 'Please enter your name' ),
		required: true
	} ],
	[ 'basic-contact-form/textfield', {
		type: 'email',
		label: __('Email'),
		name: 'email',
		placeholder: __( 'Please enter your Email' ),
		required: true
	} ],
	[ 'basic-contact-form/textfield', {
		label: __('Subject'),
		name: 'subject',
		placeholder: __( 'Please enter a subject' ),
		required: true
	} ],
	[ 'basic-contact-form/textarea', {
		label: __('Content'),
		name: 'content',
		placeholder: __( 'Please enter your message' ),
		required: true
	} ],
	[ 'basic-contact-form/submit-button', {  type: 'submit', text: __('Submit') } ],
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
			redirectTo
		} = attributes;

		console.log('redirectTo', redirectTo);

		return (
			<Fragment>
				<InspectorControls>
					<PanelBody title={ __( 'Form Settings' ) }>
						<URLInput
							label={ __('Redirect to') }
							value={ redirectTo }
							className="basic-contact-form-editor-panel-control"
							/* eslint-disable jsx-a11y/no-autofocus */
							// Disable Reason: The rule is meant to prevent enabling auto-focus, not disabling it.
							autoFocus={ false }
							/* eslint-enable jsx-a11y/no-autofocus */
							onChange={ ( value ) => setAttributes( { redirectTo: value } ) }
						/>
					</PanelBody>
				</InspectorControls>
				<form className={className}>
					<InnerBlocks
						template={ TEMPLATE }
						templateInsertUpdatesSelection={ false }
					/>
				</form>
			</Fragment>
		);
	}
}

export default FormEdit;
