<?php

/*
 * Note: this file exists only to remind developers to build the assets.
 * For the real gc-admin/index.php that gets built and boots GeChiUI,
 * please refer to gc-admin/_index.php.
 */

if ( file_exists( __DIR__ . '/../gc-includes/js/dist/edit-post.js' ) ) {
	require_once __DIR__ . '/_index.php';
	return;
}

require_once dirname( __DIR__ ) . '/index.php';
