const config = require( '@gechiui/scripts/config/jest-e2e.config' );

const jestE2EConfig = {
	...config,
	setupFilesAfterEnv: [
		'<rootDir>/config/bootstrap.js',
	],
};

module.exports = jestE2EConfig;
