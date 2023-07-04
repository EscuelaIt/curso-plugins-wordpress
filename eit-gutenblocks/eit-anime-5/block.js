( function ( blocks, editor, i18n, element, components, _, blockEditor ) {
	var __ = i18n.__;
	var el = element.createElement;
	var RichText = blockEditor.RichText;
	var MediaUpload = blockEditor.MediaUpload;
	var useBlockProps = blockEditor.useBlockProps;

	blocks.registerBlockType( 'eit-gutenblocks/eit-anime-5', {
		title: __( 'EIT: Anime Card', 'eit-gutenblocks' ),
		icon: 'index-card',
		category: 'layout',
		attributes: {
			title: {
				type: 'array',
				source: 'children',
				selector: 'h2',
			},
			mediaID: {
				type: 'number',
			},
			mediaURL: {
				type: 'string',
				source: 'attribute',
				selector: 'img',
				attribute: 'src',
			},
			sagas: {
				type: 'array',
				source: 'children',
				selector: '.sagas',
			},
			plot: {
				type: 'array',
				source: 'children',
				selector: '.steps',
			},
		},

		example: {
			attributes: {
				title: __( 'Dragon Ball Z', 'eit-gutenblocks' ),
				mediaID: 1,
				mediaURL:
					'https://upload.wikimedia.org/wikipedia/commons/9/9b/Dragon_Ball_Z_Logo.png',
				sagas: [
					{ type: 'li', props: { children: [ 'Sayajin' ] } },
					{ type: 'li', props: { children: [ 'Freezer' ] } },
					{ type: 'li', props: { children: [ 'Garlick Jr.' ] } },
					{ type: 'li', props: { children: [ 'Cell' ] } },
					{ type: 'li', props: { children: [ 'Majin Buu' ] } },
				],
				plot: [
					__( 'The continuation of Dragon Ball that recounts the adventures of Goku in his adult stage', 'eit-gutenblocks' ),
				],
			},
		},

		edit: function ( props ) {
			var attributes = props.attributes;

			var onSelectImage = function ( media ) {
				return props.setAttributes( {
					mediaURL: media.url,
					mediaID: media.id,
				} );
			};

			return el(
				'div',
				useBlockProps( { className: props.className } ),
				el( RichText, {
					tagName: 'h2',

					placeholder: __(
						'Write Anime title…',
						'eit-gutenblocks'
					),
					value: attributes.title,
					onChange: function ( value ) {
						props.setAttributes( { title: value } );
					},
				} ),
				el(
					'div',
					{ className: 'anime-image' },
					el( MediaUpload, {
						onSelect: onSelectImage,
						allowedTypes: 'image',
						value: attributes.mediaID,
						render: function ( obj ) {
							return el(
								components.Button,
								{
									className: attributes.mediaID
										? 'image-button'
										: 'button button-large',
									onClick: obj.open,
								},
								! attributes.mediaID
									? __( 'Upload Image', 'eit-gutenblocks' )
									: el( 'img', { src: attributes.mediaURL } )
							);
						},
					} )
				),
				el( 'h3', {}, i18n.__( 'Sagas', 'eit-gutenblocks' ) ),
				el( RichText, {
					tagName: 'ul',
					multiline: 'li',
					placeholder: i18n.__(
						'Write a list of sagas…',
						'eit-gutenblocks'
					),
					value: attributes.sagas,
					onChange: function ( value ) {
						props.setAttributes( { sagas: value } );
					},
					className: 'sagas',
				} ),
				el( 'h3', {}, i18n.__( 'Plot', 'eit-gutenblocks' ) ),
				el( RichText, {
					tagName: 'div',
					placeholder: i18n.__(
						'Write the plot…',
						'eit-gutenblocks'
					),
					value: attributes.plot,
					onChange: function ( value ) {
						props.setAttributes( { plot: value } );
					},
				} )
			);
		},
		save: function ( props ) {
			var attributes = props.attributes;

			return el(
				'div',
				useBlockProps.save( { className: props.className } ),
				el( RichText.Content, {
					tagName: 'h2',
					value: attributes.title,
				} ),
				attributes.mediaURL &&
					el(
						'div',
						{ className: 'anime-image' },
						el( 'img', { src: attributes.mediaURL } )
					),
				el( 'h3', {}, i18n.__( 'Sagas', 'eit-gutenblocks' ) ),
				el( RichText.Content, {
					tagName: 'ul',
					className: 'sagas',
					value: attributes.sagas,
				} ),
				el( 'h3', {}, i18n.__( 'Plot', 'eit-gutenblocks' ) ),
				el( RichText.Content, {
					tagName: 'div',
					className: 'steps',
					value: attributes.plot,
				} )
			);
		},
	} );
} )(
	window.wp.blocks,
	window.wp.editor,
	window.wp.i18n,
	window.wp.element,
	window.wp.components,
	window._,
	window.wp.blockEditor
);
