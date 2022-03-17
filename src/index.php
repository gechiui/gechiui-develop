<?php

/**
 * Note: this file exists only to remind developers to build the assets.
 * For the real index.php that gets built and boots GeChiUI,
 * please refer to _index.php.
 */

/** Define ABSPATH as this file's directory */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

if ( file_exists( ABSPATH . 'gc-includes/js/dist/edit-post.js' ) ) {
	require_once ABSPATH . '_index.php';
	return;
}

define( 'GCINC', 'gc-includes' );
require_once ABSPATH . GCINC . '/load.php';

// Standardize $_SERVER variables across setups.
gc_fix_server_vars();

require_once ABSPATH . GCINC . '/functions.php';
define( 'GC_CONTENT_DIR', ABSPATH . 'gc-content' );
require_once ABSPATH . GCINC . '/version.php';

gc_check_php_mysql_versions();
gc_load_translations_early();

// Die with an error message.
$die = sprintf(
	'<p>%s</p>',
	__( '您运行的GeChiUI没有JavaScript和CSS文件。这些需要建造。' )
);

$die .= '<p>' . sprintf(
	/* translators: %s: npm install */
	__( '在运行任何构建任务之前，您需要确保已安装依赖项。您可以通过运行%s来安装这些。' ),
	'<code style="color: green;">npm install</code>'
) . '</p>';

$die .= '<ul>';
$die .= '<li>' . __( '要在开发的同时构建GeChiUI，请运行:' ) . '<br /><br />';
$die .= '<code style="color: green;">npm run dev</code></li>';
$die .= '<li>' . __( '要在更改源文件时自动生成文件，请运行:' ) . '<br /><br />';
$die .= '<code style="color: green;">npm run watch</code></li>';
$die .= '<li>' . __( '要创建GeChiUI的生产构建，请运行:' ) . '<br /><br />';
$die .= '<code style="color: green;">npm run build</code></li>';
$die .= '</ul>';

$die .= '<p>' . sprintf(
	/* translators: 1: NPM URL, 2: Handbook URL. */
	__( '这需要 <a href="%1$s">NPM</a>. <a href="%2$s">了解有关设置本地开发环境的更多信息</a>.' ),
	'https://www.npmjs.com/get-npm',
	__( 'https://make.gechiui.com/core/handbook/tutorials/installing-gechiui-locally/' )
) . '</p>';

gc_die( $die, __( 'GeChiUI &rsaquo; 错误' ) );
