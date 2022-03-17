/**
 * External dependencies
 */
const { DefinePlugin } = require( 'webpack' );
const CopyWebpackPlugin = require( 'copy-webpack-plugin' );
const LiveReloadPlugin = require( 'webpack-livereload-plugin' );
const postcss = require( 'postcss' );
const UglifyJS = require( 'uglify-js' );

const { join, basename } = require( 'path' );
const { get } = require( 'lodash' );

/**
 * GeChiUI dependencies
 */
const CustomTemplatedPathPlugin = require( '@gechiui/custom-templated-path-webpack-plugin' );
const DependencyExtractionPlugin = require( '@gechiui/dependency-extraction-webpack-plugin' );
const LibraryExportDefaultPlugin = require( '@gechiui/library-export-default-webpack-plugin' );

/**
 * Internal dependencies
 */
const { dependencies } = require( '../../package' );

const baseDir = join( __dirname, '../../' );

/**
 * Given a string, returns a new string with dash separators converedd to
 * camel-case equivalent. This is not as aggressive as `_.camelCase` in
 * converting to uppercase, where Lodash will convert letters following
 * numbers.
 *
 * @param {string} string Input dash-delimited string.
 *
 * @return {string} Camel-cased string.
 */
function camelCaseDash( string ) {
	return string.replace(
		/-([a-z])/g,
		( match, letter ) => letter.toUpperCase()
	);
}

/**
 * Maps vendors to copy commands for the CopyWebpackPlugin.
 *
 * @param {Object} vendors     Vendors to include in the vendor folder.
 * @param {string} buildTarget The folder in which to build the packages.
 *
 * @return {Object[]} Copy object suitable for the CopyWebpackPlugin.
 */
function mapVendorCopies( vendors, buildTarget ) {
	return Object.keys( vendors ).map( ( filename ) => ( {
		from: join( baseDir, `node_modules/${ vendors[ filename ] }` ),
		to: join( baseDir, `${ buildTarget }/js/dist/vendor/${ filename }` ),
	} ) );
}

module.exports = function( env = { environment: 'production', watch: false, buildTarget: false } ) {
	const mode = env.environment;
	const suffix = mode === 'production' ? '.min' : '';
	let buildTarget = env.buildTarget ? env.buildTarget : ( mode === 'production' ? 'build' : 'src' );
	buildTarget = buildTarget  + '/gc-includes';

	const GECHIUI_NAMESPACE = '@gechiui/';
	const BUNDLED_PACKAGES = [ '@gechiui/icons', '@gechiui/interface' ];
	const packages = Object.keys( dependencies )
		.filter( ( packageName ) =>
 			! BUNDLED_PACKAGES.includes( packageName ) &&
 			packageName.startsWith( GECHIUI_NAMESPACE )
 		)
		.map( ( packageName ) => packageName.replace( GECHIUI_NAMESPACE, '' ) );

	const vendors = {
		'lodash.js': 'lodash/lodash.js',
		'gc-polyfill.js': '@gechiui/babel-preset-default/build/polyfill.js',
		'gc-polyfill-fetch.js': 'whatwg-fetch/dist/fetch.umd.js',
		'gc-polyfill-element-closest.js': 'element-closest/element-closest.js',
		'gc-polyfill-node-contains.js': 'polyfill-library/polyfills/__dist/Node.prototype.contains/raw.js',
		'gc-polyfill-url.js': 'core-js-url-browser/url.js',
		'gc-polyfill-dom-rect.js': 'polyfill-library/polyfills/__dist/DOMRect/raw.js',
		'gc-polyfill-formdata.js': 'formdata-polyfill/FormData.js',
		'gc-polyfill-object-fit.js': 'objectFitPolyfill/src/objectFitPolyfill.js',
		'moment.js': 'moment/moment.js',
		'react.js': 'react/umd/react.development.js',
		'react-dom.js': 'react-dom/umd/react-dom.development.js',
		'regenerator-runtime.js': 'regenerator-runtime/runtime.js',
	};

	const minifiedVendors = {
		'lodash.min.js': 'lodash/lodash.min.js',
		'gc-polyfill.min.js': '@gechiui/babel-preset-default/build/polyfill.min.js',
		'gc-polyfill-formdata.min.js': 'formdata-polyfill/formdata.min.js',
		'gc-polyfill-url.min.js': 'core-js-url-browser/url.min.js',
		'gc-polyfill-object-fit.min.js': 'objectFitPolyfill/dist/objectFitPolyfill.min.js',
		'moment.min.js': 'moment/min/moment.min.js',
		'react.min.js': 'react/umd/react.production.min.js',
		'react-dom.min.js': 'react-dom/umd/react-dom.production.min.js',
	};

	const minifyVendors = {
		'regenerator-runtime.min.js': 'regenerator-runtime/runtime.js',
		'gc-polyfill-fetch.min.js': 'whatwg-fetch/dist/fetch.umd.js',
		'gc-polyfill-element-closest.min.js': 'element-closest/element-closest.js',
		'gc-polyfill-node-contains.min.js': 'polyfill-library/polyfills/__dist/Node.prototype.contains/raw.js',
		'gc-polyfill-dom-rect.min.js': 'polyfill-library/polyfills/__dist/DOMRect/raw.js',
	};

	const phpFiles = {
		'block-serialization-default-parser/parser.php': 'gc-includes/class-gc-block-parser.php',
	};

	const developmentCopies = mapVendorCopies( vendors, buildTarget );
	const minifiedCopies = mapVendorCopies( minifiedVendors, buildTarget );
	const minifyCopies = mapVendorCopies( minifyVendors, buildTarget ).map( ( copyCommand ) => {
		return {
			...copyCommand,
			transform: ( content ) => {
				return UglifyJS.minify( content.toString() ).code;
			},
		};
	} );

	let vendorCopies = mode === "development" ? developmentCopies : [ ...minifiedCopies, ...minifyCopies ];

	let cssCopies = packages.map( ( packageName ) => ( {
		from: join( baseDir, `node_modules/@gechiui/${ packageName }/build-style/*.css` ),
		to: join( baseDir, `${ buildTarget }/css/dist/${ packageName }/` ),
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

	const phpCopies = Object.keys( phpFiles ).map( ( filename ) => ( {
		from: join( baseDir, `node_modules/@gechiui/${ filename }` ),
		to: join( baseDir, `src/${ phpFiles[ filename ] }` ),
	} ) );

	const config = {
		mode,

		entry: packages.reduce( ( memo, packageName ) => {
			const name = camelCaseDash( packageName );
			memo[ name ] = join( baseDir, `node_modules/@gechiui/${ packageName }` );
			return memo;
		}, {} ),
		output: {
			devtoolNamespace: 'gc',
			filename: `[basename]${ suffix }.js`,
			path: join( baseDir, `${ buildTarget }/js/dist` ),
			library: {
				root: [ 'gc', '[name]' ]
			},
			libraryTarget: 'this',
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
				// Inject the `COMPONENT_SYSTEM_PHASE` global, used for controlling Component System roll-out.
				'process.env.COMPONENT_SYSTEM_PHASE': 0,
				'process.env.FORCE_REDUCED_MOTION': JSON.stringify(
					process.env.FORCE_REDUCED_MOTION
				),
			} ),
			new LibraryExportDefaultPlugin( [
				'api-fetch',
				'deprecated',
				'dom-ready',
				'redux-routine',
				'token-list',
				'server-side-render',
				'shortcode',
				'warning',
			].map( camelCaseDash ) ),
			new CustomTemplatedPathPlugin( {
				basename( path, data ) {
					let rawRequest;

					const entryModule = get( data, [ 'chunk', 'entryModule' ], {} );
					switch ( entryModule.type ) {
						case 'javascript/auto':
							rawRequest = entryModule.rawRequest;
							break;

						case 'javascript/esm':
							rawRequest = entryModule.rootModule.rawRequest;
							break;
					}

					if ( rawRequest ) {
						return basename( rawRequest );
					}

					return path;
				},
			} ),
			new DependencyExtractionPlugin( {
				injectPolyfill: true,
				combineAssets: true,
				combinedOutputFile: '../../assets/script-loader-packages.php',
			} ),
			new CopyWebpackPlugin(
				[
					...vendorCopies,
					...cssCopies,
					...phpCopies,
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

	if ( config.mode === 'development' ) {
		config.plugins.push( new LiveReloadPlugin( { port: process.env.GECHIUI_LIVE_RELOAD_PORT || 35729 } ) );
	}

	return config;
};
