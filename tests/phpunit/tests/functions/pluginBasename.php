<?php

/**
 * Tests for plugin_basename()
 *
 * @group functions.php
 * @group plugins
 * @covers ::plugin_basename
 */
class Tests_Functions_PluginBasename extends GC_UnitTestCase {

	/**
	 * @var array
	 */
	protected $gc_plugin_paths_backup;

	/**
	 * Normalized path to plugin directory.
	 *
	 * @var string
	 */
	protected $gc_plugin_path;

	public function set_up() {
		parent::set_up();

		$this->gc_plugin_paths_backup = $GLOBALS['gc_plugin_paths'];
		$this->gc_plugin_path         = gc_normalize_path( GC_PLUGIN_DIR );
	}

	public function tear_down() {
		$GLOBALS['gc_plugin_paths'] = $this->gc_plugin_paths_backup;

		parent::tear_down();
	}

	/**
	 * @ticket 29154
	 */
	public function test_return_correct_basename_for_symlinked_plugins() {
		global $gc_plugin_paths;

		$gc_plugin_paths = array(
			$this->gc_plugin_path . '/a-symlinked-plugin' => 'C:/www/path/plugins/a-plugin',
		);

		$basename = plugin_basename( 'c:\www\path\plugins\a-plugin\plugin.php' );
		$this->assertSame( 'a-symlinked-plugin/plugin.php', $basename );
	}

	/**
	 * @ticket 28441
	 */
	public function test_return_correct_basename_for_symlinked_plugins_with_path_conflicts() {
		global $gc_plugin_paths;

		$gc_plugin_paths = array(
			$this->gc_plugin_path . '/plugin' => '/Users/me/Dropbox/Development/Repositories/plugin',
			$this->gc_plugin_path . '/trunk'  => '/Users/me/Dropbox/Development/Repositories/plugin/trunk',
		);

		$basename = plugin_basename( '/Users/me/Dropbox/Development/Repositories/plugin/trunk/plugin.php' );
		$this->assertSame( 'trunk/plugin.php', $basename );
	}
}
