import { getUniqueId } from '../../utils';
import './editor.css';
import { __ } from '../../utils/i18n';

// const { __ } = wp.i18n;
const {
	InnerBlocks,
	InspectorControls,
	URLInput
} = wp.blockEditor;
const { Component, Fragment } = wp.element;
const { PanelBody } = wp.components;

/**
 * Constants
 */
const TEMPLATE = [
	[ 'core/heading', { level: 2, className: 'bcf-title', placeholder: __( 'Get in contact with us!', 'Basic Contact Form' ) } ],
	[ 'core/paragraph', {  placeholder: __( 'Please use our contact form for your inquiry', 'basic-contact-form' ) } ],
	[ 'basic-contact-form/textfield', {
		label: __('Name', 'basic-contact-form'),
		name: 'name',
		placeholder: __( 'Please enter your name', 'basic-contact-form' ),
		required: false
	} ],
	[ 'basic-contact-form/textfield', {
		type: 'email',
		label: __('Email', 'basic-contact-form'),
		name: 'email',
		placeholder: __( 'Please enter your e-mail address', 'basic-contact-form' ),
		required: true
	} ],
	[ 'basic-contact-form/textfield', {
		label: __('Subject', 'basic-contact-form'),
		name: 'subject',
		placeholder: __( 'Please enter a subject', 'basic-contact-form' ),
		required: false
	} ],
	[ 'basic-contact-form/textarea', {
		label: __('Message', 'basic-contact-form'),
		name: 'message',
		placeholder: __( 'Please enter a message', 'basic-contact-form' ),
		required: false
	} ],
	[ 'basic-contact-form/submit-button', { type: 'submit', text: __('Submit', 'basic-contact-form') } ],
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
					<PanelBody title={ __( 'Form Settings', 'basic-contact-form' ) }>
						<URLInput
							label={ __('Redirect to', 'basic-contact-form') }
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
