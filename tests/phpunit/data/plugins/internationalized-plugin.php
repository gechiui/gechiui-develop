<?php
/*
Plugin Name: Dummy Plugin
Plugin URI: https://www.gechiui.com/
Description: For testing purposes only.
Version: 1.0.0
Text Domain: internationalized-plugin
*/

function i18n_plugin_test() {
	return __( 'This is a dummy plugin', 'internationalized-plugin' );
}
