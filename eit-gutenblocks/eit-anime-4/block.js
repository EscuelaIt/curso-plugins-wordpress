/**
 * Hello World: Step 4
 *
 * Adding extra controls: built-in alignment toolbar.
 */
( function ( blocks, editor, i18n, element, blockEditor ) {
	var el = element.createElement;
	var __ = i18n.__;
	var RichText = editor.RichText;
	var AlignmentToolbar = editor.AlignmentToolbar;
	var BlockControls = editor.BlockControls;
	var useBlockProps = blockEditor.useBlockProps;


	/**
	 * Se pueden pasar parámetros para sobreescribir la configuracion de block.json
	 */
	blocks.registerBlockType( 'eit-gutenblocks/eit-anime-4', {
		// title: __( 'EIT: Anime 4B', 'eit-gutenblocks' ),
		// icon: 'universal-access-alt',
		// category: 'layout',

		// attributes: {
		// 	content: {
		// 		type: 'array',
		// 		source: 'children',
		// 		selector: 'p',
		// 	},
		// 	alignment: {
		// 		type: 'string',
		// 		default: 'none',
		// 	},
		// },

		// example: {
		// 	attributes: {
		// 		content: __( 'Hello world', 'eit-gutenblocks' ),
		// 		alignment: 'right',
		// 	},
		// },

		edit: function ( props ) {
			var content = props.attributes.content;
			var alignment = props.attributes.alignment;

			function onChangeContent( newContent ) {
				props.setAttributes( { content: newContent } );
			}

			function onChangeAlignment( newAlignment ) {
				props.setAttributes( {
					alignment:
						newAlignment === undefined ? 'none' : newAlignment,
				} );
			}

			return [
				el(
					BlockControls,
					{ key: 'controls' },
					el( AlignmentToolbar, {
						value: alignment,
						onChange: onChangeAlignment,
					} )
				),
				el(
					RichText,
					useBlockProps( {
						key: 'richtext',
						tagName: 'p',
						style: { textAlign: alignment },
						className: props.className,
						onChange: onChangeContent,
						value: content,
					} )
				),
			];
		},

		save: function ( props ) {
			return el(
				RichText.Content,
				useBlockProps.save( {
					tagName: 'p',
					className:
						'eit-gutenblocks-eit-anime-4-align-' +
						props.attributes.alignment,
					value: props.attributes.content,
				} )
			);
		},
	} );
} )(
	window.wp.blocks,
	window.wp.editor,
	window.wp.i18n,
	window.wp.element,
	window.wp.blockEditor
);
