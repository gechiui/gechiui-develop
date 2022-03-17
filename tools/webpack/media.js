const UglifyJsPlugin = require( 'uglifyjs-webpack-plugin' );

var path            = require( 'path' ),
	admin_files     = {};

const baseDir = path.join( __dirname, '../../' );

module.exports = function( env = { environment: 'production', watch: false, buildTarget: false } ) {
	const include_files = {
		[ env.buildTarget + 'gc-includes/js/media-audiovideo.js' ]: ['./src/js/_enqueues/gc/media/audiovideo.js'],
		[ env.buildTarget + 'gc-includes/js/media-audiovideo.min.js' ]: ['./src/js/_enqueues/gc/media/audiovideo.js'],
		[ env.buildTarget + 'gc-includes/js/media-grid.js' ]: ['./src/js/_enqueues/gc/media/grid.js'],
		[ env.buildTarget + 'gc-includes/js/media-grid.min.js' ]: ['./src/js/_enqueues/gc/media/grid.js'],
		[ env.buildTarget + 'gc-includes/js/media-models.js' ]: ['./src/js/_enqueues/gc/media/models.js'],
		[ env.buildTarget + 'gc-includes/js/media-models.min.js' ]: ['./src/js/_enqueues/gc/media/models.js'],
		[ env.buildTarget + 'gc-includes/js/media-views.js' ]: ['./src/js/_enqueues/gc/media/views.js'],
		[ env.buildTarget + 'gc-includes/js/media-views.min.js' ]: ['./src/js/_enqueues/gc/media/views.js'],
	};

	const mediaConfig = {
		mode: "production",
		cache: true,
		entry: Object.assign( admin_files, include_files ),
		output: {
			path: baseDir,
			filename: '[name]',
		},
		optimization: {
			minimize: true,
			moduleIds: 'hashed',
			minimizer: [
				new UglifyJsPlugin( {
					include: /\.min\.js$/,
				} )
			]
		},
		watch: env.watch,
	};

	return mediaConfig;
};
