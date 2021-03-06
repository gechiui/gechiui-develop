const dotenv = require( 'dotenv' );
const dotenv_expand = require( 'dotenv-expand' );
const { execSync } = require( 'child_process' );

// GC_BASE_URL interpolates LOCAL_PORT, so needs to be parsed by dotenv_expand().
dotenv_expand( dotenv.config() );

// Run the tests, passing additional arguments through to the test script.
execSync(
	'gc-scripts test-e2e --config tests/e2e/jest.config.js ' +
		process.argv.slice( 2 ).join( ' ' ),
	{ stdio: 'inherit' }
);
