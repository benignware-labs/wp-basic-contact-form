/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;
const { InspectorControls } = wp.blockEditor;
const { Component, Fragment } = wp.element;
const {
	PanelBody,
	TextControl
} = wp.components;

const {
	RichText,
} = wp.blockEditor;

class SubmitButtonEdit extends Component {
	constructor() {
		super( ...arguments );
		this.nodeRef = null;
		this.bindRef = this.bindRef.bind( this );
	}

	bindRef( node ) {
		if ( ! node ) {
			return;
		}
		this.nodeRef = node;
	}

	render() {
		const {
			attributes,
			setAttributes,
			className,
		} = this.props;

		const {
			text,
		} = attributes;

		return (
			
			<Fragment>
				<InspectorControls>
					<PanelBody title={ __( 'Submit Button Settings', 'basic-contact-form' ) }>
						<TextControl
							label={ __( 'Label', 'basic-contact-form' ) }
							value={ text }
							onChange={(value) => setAttributes({
								text: value,
							})}
						/>
					</PanelBody>
				</InspectorControls>
				<div className={className}>
					<button
						className={
							classnames(
								'bcf-submit-button',
								'bcf-button'
							)
						}
						ref={ this.bindRef }
					>
						<RichText
							tagName="span"
							placeholder={ __( 'Add text…', 'basic-contact-form' ) }
							value={ text }
							onChange={ ( value ) => setAttributes( { text: value } ) }
							allowedFormats={ [ 'bold', 'italic', 'strikethrough' ] }
							keepPlaceholderOnFocus
						/>
					</button>
				</div>
			</Fragment>
		);
	}
}

export default SubmitButtonEdit
