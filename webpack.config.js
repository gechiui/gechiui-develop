const blocksConfig = require( './tools/webpack/blocks' );
const mediaConfig = require( './tools/webpack/media' );
const packagesConfig = require( './tools/webpack/packages' );

module.exports = function( env = { environment: "production", watch: false, buildTarget: false } ) {
	this.module = {
        rules: [
            {test:/\.js$/,use:'babel-loader',exclude:/node_modules/}  //.js 使用babel匹配的规则
        ]
    };

	if ( ! env.watch ) {
		env.watch = false;
	}

	if ( ! env.buildTarget ) {
		env.buildTarget = ( env.mode === 'production' ? 'build/' : 'src/' );
	}

	const config = [
		blocksConfig( env ),
		mediaConfig( env ),
		packagesConfig( env ),
	];

	return config;
};
