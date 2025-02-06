/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;
const { Component, Fragment } = wp.element;

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
