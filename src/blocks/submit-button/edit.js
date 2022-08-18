/**
 * External dependencies
 */
import classnames from 'classnames';
import { camelizeKeys } from 'humps';

/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;
const { Component, Fragment } = wp.element;

const {
	RichText,
} = wp.editor;

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
			isSelected,
			className,
		} = this.props;

		const {
			text,
		} = attributes;

		return (
			<Fragment>
				<div className={ className } ref={ this.bindRef }>
					<RichText
						tagName="button"
						placeholder={ __( 'Add text…' ) }
						value={ text }
						onChange={ ( value ) => setAttributes( { text: value } ) }
						formattingControls={ [ 'bold', 'italic', 'strikethrough' ] }
						className={ classnames(
							'bcf-submit-button',
						) }
						keepPlaceholderOnFocus
					/>
				</div>
			</Fragment>
		);
	}
}

export default SubmitButtonEdit
