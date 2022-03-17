/**
 * External dependencies
 */
const { DefinePlugin } = require( 'webpack' );
const CopyWebpackPlugin = require( 'copy-webpack-plugin' );
const postcss = require( 'postcss' );
const UglifyJS = require( 'uglify-js' );

const { join, basename } = require( 'path' );
const { get } = require( 'lodash' );

/**
 * GeChiUI dependencies
 */
const DependencyExtractionPlugin = require( '@gechiui/dependency-extraction-webpack-plugin' );

const baseDir = join( __dirname, '../../' );

module.exports = function( env = { environment: 'production', watch: false, buildTarget: false } ) {
	const mode = env.environment;
	const suffix = mode === 'production' ? '.min' : '';
	let buildTarget = env.buildTarget ? env.buildTarget : ( mode === 'production' ? 'build' : 'src' );
	buildTarget = buildTarget  + '/gc-includes';

	const dynamicBlockFolders = [
		'archives',
		'block',
		'calendar',
		'categories',
		'file',
		'gallery',
		'image',
		'latest-comments',
		'latest-posts',
		'loginout',
		'navigation',
		'navigation-link',
		'navigation-submenu',
		'page-list',
		'pattern',
		'post-author',
		'post-comments',
		'post-content',
		'post-date',
		'post-excerpt',
		'post-featured-image',
		'post-navigation-link',
		'post-template',
		'post-terms',
		'post-title',
		'query',
		'query-pagination',
		'query-pagination-next',
		'query-pagination-numbers',
		'query-pagination-previous',
		'query-title',
		'rss',
		'search',
		'shortcode',
		'site-logo',
		'site-tagline',
		'site-title',
		'social-link',
		'tag-cloud',
		'template-part',
		'term-description',
	];
	const blockFolders = [
		'audio',
		'button',
		'buttons',
		'code',
		'column',
		'columns',
		'cover',
		'embed',
		'freeform',
		'group',
		'heading',
		'html',
		'list',
		'media-text',
		'missing',
		'more',
		'nextpage',
		'paragraph',
		'preformatted',
		'pullquote',
		'quote',
		'separator',
		'social-links',
		'spacer',
		'table',
		'text-columns',
		'verse',
		'video',
		...dynamicBlockFolders,
	];
	const blockPHPFiles = {
		'widgets/src/blocks/legacy-widget/index.php': 'gc-includes/blocks/legacy-widget.php',
		'widgets/src/blocks/widget-group/index.php': 'gc-includes/blocks/widget-group.php',
		...dynamicBlockFolders.reduce( ( files, blockName ) => {
			files[ `block-library/src/${ blockName }/index.php` ] = `gc-includes/blocks/${ blockName }.php`;
			return files;
		} , {} ),
	};
	const blockMetadataFiles = {
		'widgets/src/blocks/legacy-widget/block.json': 'gc-includes/blocks/legacy-widget/block.json',
		'widgets/src/blocks/widget-group/block.json': 'gc-includes/blocks/widget-group/block.json',
		...blockFolders.reduce( ( files, blockName ) => {
			files[ `block-library/src/${ blockName }/block.json` ] = `gc-includes/blocks/${ blockName }/block.json`;
			return files;
		} , {} ),
	};

	const blockPHPCopies = Object.keys( blockPHPFiles ).map( ( filename ) => ( {
		from: join( baseDir, `node_modules/@gechiui/${ filename }` ),
		to: join( baseDir, `src/${ blockPHPFiles[ filename ] }` ),
	} ) );

	const blockMetadataCopies = Object.keys( blockMetadataFiles ).map( ( filename ) => ( {
		from: join( baseDir, `node_modules/@gechiui/${ filename }` ),
		to: join( baseDir, `src/${ blockMetadataFiles[ filename ] }` ),
	} ) );

	const blockStylesheetCopies = blockFolders.map( ( blockName ) => ( {
		from: join( baseDir, `node_modules/@gechiui/block-library/build-style/${ blockName }/*.css` ),
		to: join( baseDir, `${ buildTarget }/blocks/${ blockName }/` ),
		flatten: true,
		transform: ( content ) => {
			if ( mode === 'production' ) {
				return postcss( [
					require( 'cssnano' )( {
						preset: 'default',
					} ),
				] )
					.process( content, { from: 'src/app.css', to: 'dest/app.css' } )
					.then( ( result ) => result.css );
			}

			return content;
		},
		transformPath: ( targetPath, sourcePath ) => {
			if ( mode === 'production' ) {
				return targetPath.replace( /\.css$/, '.min.css' );
			}

			return targetPath;
		}
	} ) );

	const config = {
		mode,
		entry: {
			'file/view': join( baseDir, `node_modules/@gechiui/block-library/build-module/file/view` ),
			'navigation/view': join( baseDir, `node_modules/@gechiui/block-library/build-module/navigation/view` ),
		},
		output: {
			devtoolNamespace: 'gc',
			filename: `[name]${ suffix }.js`,
			path: join( baseDir, `${ buildTarget }/blocks` ),
		},
		resolve: {
			modules: [
				baseDir,
				'node_modules',
			],
			alias: {
				'lodash-es': 'lodash',
			},
		},
		module: {
			rules: [
				{
					test: /\.js$/,
					use: [ 'source-map-loader' ],
					enforce: 'pre',
				},
			],
		},
		optimization: {
			moduleIds: mode === 'production' ? 'hashed' : 'named',
		},
		plugins: [
			new DefinePlugin( {
				// Inject the `GUTENBERG_PHASE` global, used for feature flagging.
				'process.env.GUTENBERG_PHASE': 1,
				'process.env.FORCE_REDUCED_MOTION': JSON.stringify(
					process.env.FORCE_REDUCED_MOTION
				),
			} ),
			new DependencyExtractionPlugin( {
				injectPolyfill: false,
			} ),
			new CopyWebpackPlugin(
				[
					...blockPHPCopies,
					...blockMetadataCopies,
					...blockStylesheetCopies,
				],
			),
		],
		stats: {
			children: false,
		},

		watch: env.watch,
	};

	if ( config.mode !== 'production' ) {
		config.devtool = process.env.SOURCEMAP || 'source-map';
	}

	if ( mode === 'development' && env.buildTarget === 'build/' ) {
		delete config.devtool;
		config.mode = 'production';
		config.optimization = {
			minimize: false,
			moduleIds: 'hashed',
		};
	}

	return config;
};
