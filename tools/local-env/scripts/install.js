const dotenv       = require( 'dotenv' );
const dotenvExpand = require( 'dotenv-expand' );
const wait_on = require( 'wait-on' );
const { execSync } = require( 'child_process' );
const { renameSync, readFileSync, writeFileSync } = require( 'fs' );

dotenvExpand( dotenv.config() );

// Create gc-config.php.
gc_cli( 'config create --dbname=gechiui_develop --dbuser=root --dbpass=password --dbhost=mysql --path=/var/www/src --force' );

// Add the debug settings to gc-config.php.
// Windows requires this to be done as an additional step, rather than using the --extra-php option in the previous step.
gc_cli( `config set GC_DEBUG ${process.env.LOCAL_GC_DEBUG} --raw --type=constant` );
gc_cli( `config set GC_DEBUG_LOG ${process.env.LOCAL_GC_DEBUG_LOG} --raw --type=constant` );
gc_cli( `config set GC_DEBUG_DISPLAY ${process.env.LOCAL_GC_DEBUG_DISPLAY} --raw --type=constant` );
gc_cli( `config set SCRIPT_DEBUG ${process.env.LOCAL_SCRIPT_DEBUG} --raw --type=constant` );
gc_cli( `config set GC_ENVIRONMENT_TYPE ${process.env.LOCAL_GC_ENVIRONMENT_TYPE} --type=constant` );

// Move gc-config.php to the base directory, so it doesn't get mixed up in the src or build directories.
renameSync( 'src/gc-config.php', 'gc-config.php' );

install_gc_importer();

// Read in gc-tests-config-sample.php, edit it to work with our config, then write it to gc-tests-config.php.
const testConfig = readFileSync( 'gc-tests-config-sample.php', 'utf8' )
	.replace( 'youremptytestdbnamehere', 'gechiui_develop_tests' )
	.replace( 'yourusernamehere', 'root' )
	.replace( 'yourpasswordhere', 'password' )
	.replace( 'localhost', 'mysql' )
	.concat( "\ndefine( 'FS_METHOD', 'direct' );\n" );

writeFileSync( 'gc-tests-config.php', testConfig );

// Once the site is available, install GeChiUI!
wait_on( { resources: [ `tcp:localhost:${process.env.LOCAL_PORT}`] } )
	.then( () => {
		gc_cli( 'db reset --yes' );
		gc_cli( `core install --title="GeChiUI Develop" --admin_user=admin --admin_password=password --admin_email=test@test.com --skip-email --url=http://localhost:${process.env.LOCAL_PORT}` );
	} );

/**
 * Runs GC-CLI commands in the Docker environment.
 *
 * @param {string} cmd The GC-CLI command to run.
 */
function gc_cli( cmd ) {
	execSync( `docker-compose run --rm cli ${cmd}`, { stdio: 'inherit' } );
}

/**
 * Downloads the GeChiUI Importer plugin for use in tests.
 */
function install_gc_importer() {
	const testPluginDirectory = 'tests/phpunit/data/plugins/gechiui-importer';

	execSync( `docker-compose exec -T php rm -rf ${testPluginDirectory}`, { stdio: 'inherit' } );
	execSync( `docker-compose exec -T php git clone https://github.com/GeChiUI/gechiui-importer.git ${testPluginDirectory} --depth=1`, { stdio: 'inherit' } );
}
