/**
 * External dependencies
 */
import classnames from 'classnames';
import { __ } from '../../utils/i18n';
/**
 * WordPress dependencies
 */


const { InspectorControls, InnerBlocks } = wp.blockEditor;
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
				<div className={className}>
					<InnerBlocks
						allowedBlocks={['core/buttons']}
						template={[
							['core/buttons', {}, [
								['core/button', { text: __('Send'), type: 'submit' }]
							]]]}
					/>
				</div>
			</Fragment>
		);
	}
}

export default SubmitButtonEdit
