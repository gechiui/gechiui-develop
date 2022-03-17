const config = require( '@gechiui/scripts/config/jest-e2e.config' );

const jestVisualRegressionConfig = {
	...config,
	setupFilesAfterEnv: [ '<rootDir>/config/bootstrap.js' ],
};

module.exports = jestVisualRegressionConfig;
