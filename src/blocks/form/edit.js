import './editor.scss';
import './style.scss';

/**
 * External dependencies
 */
import { getUniqueId } from '../../utils';

/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;
const {
	InnerBlocks,
	InspectorControls,
	URLInput,
} = wp.editor;

const { Component, Fragment } = wp.element;

const {
	PanelBody
} = wp.components;

/**
 * Constants
 */
const TEMPLATE = [
	[ 'core/heading', { level: 2, className: 'bcf-title', placeholder: __( 'Get in contact with us!' ) } ],
	[ 'core/paragraph', {  placeholder: __( 'Please use our contact form for your inquiry' ) } ],
	[ 'basic-contact-form/textfield', {
		label: __('Name'),
		name: 'name',
		placeholder: __( 'Please enter your name' ),
		required: false
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
		required: false
	} ],
	[ 'basic-contact-form/textarea', {
		label: __('Content'),
		name: 'content',
		placeholder: __( 'Please enter your message' ),
		required: false
	} ],
	[ 'basic-contact-form/submit-button', { type: 'submit', text: __('Submit') } ],
];

class FormEdit extends Component {
	constructor() {
		super( ...arguments );
	}

	componentDidMount() {
		const {
			attributes,
			setAttributes,
		} = this.props;

		const {
			id
		} = attributes;

		if (!id) {
			setAttributes({
				...attributes,
				id: getUniqueId('form')
			});
		}
	}

	render() {
		const {
			attributes,
			className,
			isSelected,
			setAttributes,
		} = this.props;

		const {
			id,
			redirectTo
		} = attributes;

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
				<form
					data-basic-contact-form={id}
					className={className}
				>
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
