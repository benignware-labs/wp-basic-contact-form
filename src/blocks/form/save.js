import classnames from 'classnames';
import './style.css';

const { InnerBlocks, useBlockProps } = wp.blockEditor;

export default function({ attributes, className }) {
	const blockProps = useBlockProps.save();
	const {
		id
	} = attributes;

	return (
		<form
			data-basic-contact-form={id}
			className={ classnames('bcf-form', className)}
			{...blockProps}
		>
			<InnerBlocks.Content />
		</form>
	);
}
