/* jshint node:true */
/* jshint esversion: 6 */
/* globals Set */
var webpackConfig = require( './webpack.config' );
var installChanged = require( 'install-changed' );

module.exports = function(grunt) {
	var path = require('path'),
		fs = require( 'fs' ),
		glob = require( 'glob' ),
		assert = require( 'assert' ).strict,
		spawn = require( 'child_process' ).spawnSync,
		SOURCE_DIR = 'src/',
		BUILD_DIR = 'build/',
		WORKING_DIR = grunt.option( 'dev' ) ? SOURCE_DIR : BUILD_DIR,
 		BANNER_TEXT = '/*! This file is auto-generated */',
		autoprefixer = require( 'autoprefixer' ),
		sass = require( 'sass' ),
		phpUnitWatchGroup = grunt.option( 'group' ),
		buildFiles = [
			'*.php',
			'*.txt',
			'*.html',
			'assets/fonts/**',
			'assets/images/**',
			'assets/vendors/**',
			'src/**',
			'gc-includes/**', // Include everything in gc-includes.
			'gc-admin/**', // Include everything in gc-admin.
			'gc-content/index.php',
			'gc-content/avatar/**',
			'gc-content/languages/**',
			'gc-content/themes/index.php',
			'gc-content/themes/default*/**',
			'gc-content/plugins/index.php',
			'gc-content/plugins/hello.php',
			'gc-content/plugins/akismet/**',
			'!gc-content/themes/default*/node_modules/**',
		],
		changedFiles = {
			php: []
		};

	if ( 'watch:phpunit' === grunt.cli.tasks[ 0 ] && ! phpUnitWatchGroup ) {
		grunt.log.writeln();
		grunt.fail.fatal(
			'Missing required parameters. Example usage: ' + '\n\n' +
			'grunt watch:phpunit --group=community-events' + '\n' +
			'grunt watch:phpunit --group=multisite,mail'
		);
	}

	// First do `npm install` if package.json has changed.
	installChanged.watchPackage();

	// Load tasks.
	require('matchdep').filterDev(['grunt-*', '!grunt-legacy-util']).forEach( grunt.loadNpmTasks );
	// Load legacy utils.
	grunt.util = require('grunt-legacy-util');

	// Project configuration.
	grunt.initConfig({
		postcss: {
			options: {
				processors: [
					autoprefixer({
						cascade: false
					})
				]
			},
			core: {
				expand: true,
				cwd: SOURCE_DIR,
				dest: SOURCE_DIR,
				src: [
					'assets/css/*.css',
					'gc-admin/css/*.css',
					'gc-includes/css/*.css'
				]
			}
		},
 		usebanner: {
			options: {
				position: 'top',
				banner: BANNER_TEXT,
				linebreak: true
			},
			files: {
				src: [
					WORKING_DIR + 'assets/css/*.min.css',
					WORKING_DIR + 'assets/css/*-rtl*.css',
					WORKING_DIR + 'assets/js/*.min.js',
					WORKING_DIR + 'assets/js/**/*.min.js',
					WORKING_DIR + 'gc-admin/css/*.min.css',
					WORKING_DIR + 'gc-admin/css/*-rtl*.css',
					WORKING_DIR + 'gc-admin/js/**/*.min.js',
					WORKING_DIR + 'gc-includes/css/*.min.css',
					WORKING_DIR + 'gc-includes/css/*-rtl*.css',
					WORKING_DIR + 'gc-includes/js/*.min.js',
					WORKING_DIR + 'gc-includes/js/dist/*.min.js'
				]
			}
		},
		clean: {
			plugins: [BUILD_DIR + 'gc-content/plugins'],
			themes: [BUILD_DIR + 'gc-content/themes'],
			files: buildFiles.concat( [
				'!gc-config.php',
			] ).map( function( file ) {
				if ( '!' === file.charAt( 0 ) ) {
					return '!' + BUILD_DIR + file.substring( 1 );
				}
				return BUILD_DIR + file;
			} ),
			css: [
				WORKING_DIR + 'assets/css/*.min.css',
				WORKING_DIR + 'assets/css/*-rtl*.css',
				WORKING_DIR + 'gc-admin/css/*.min.css',
				WORKING_DIR + 'gc-admin/css/*-rtl*.css',
				WORKING_DIR + 'gc-includes/css/*.min.css',
				WORKING_DIR + 'gc-includes/css/*-rtl*.css'
			],
			js: [
				WORKING_DIR + 'assets/js/',
				WORKING_DIR + 'gc-admin/js/',
				WORKING_DIR + 'gc-includes/js/'
			],
			'webpack-assets': [
				WORKING_DIR + 'gc-includes/assets/*',
				WORKING_DIR + 'gc-includes/css/dist/',
				'!' + WORKING_DIR + 'gc-includes/assets/script-loader-packages.php'
			],
			dynamic: {
				dot: true,
				expand: true,
				cwd: WORKING_DIR,
				src: []
			},
			qunit: ['tests/qunit/compiled.html']
		},
		file_append: {
			// grunt-file-append supports only strings for input and output.
			default_options: {
				files: [
					{
						append: 'jQuery.noConflict();',
						input: WORKING_DIR + 'assets/vendors/jquery/jquery.js',
						output: WORKING_DIR + 'assets/vendors/jquery/jquery.js'
					},
					{
						append: 'jQuery.noConflict();',
						input: WORKING_DIR + 'assets/vendors/jquery/jquery.min.js',
						output: WORKING_DIR + 'assets/vendors/jquery/jquery.min.js'
					}
				]
			}
		},
		copy: {
			files: {
				files: [
					{
						dot: true,
						expand: true,
						cwd: SOURCE_DIR,
						src: buildFiles.concat( [
							'!gc-includes/assets/**', // Assets is extracted into separate copy tasks.
							'!js/**', // JavaScript is extracted into separate copy tasks.
							'!.{svn,git}', // Exclude version control folders.
							'!gc-includes/version.php', // Exclude version.php.
							'!**/*.map', // The build doesn't need .map files.
							'!index.php', '!gc-admin/index.php',
							'!_index.php', '!gc-admin/_index.php'
						] ),
						dest: BUILD_DIR
					},
					{
						src: 'gc-config-sample.php',
						dest: BUILD_DIR
					},
					{
						[BUILD_DIR + 'index.php']: ['src/_index.php'],
						[BUILD_DIR + 'gc-admin/index.php']: ['src/gc-admin/_index.php']
					}
				]
			},
			'npm-packages': {
				files: [
					{
						[ WORKING_DIR + 'assets/vendors/backbone.js' ]: [ './node_modules/backbone/backbone.js' ],
						[ WORKING_DIR + 'assets/vendors/backbone.min.js' ]: [ './node_modules/backbone/backbone-min.js' ],

						[ WORKING_DIR + 'assets/vendors/clipboard.js' ]: [ './node_modules/clipboard/dist/clipboard.js' ],
						[ WORKING_DIR + 'assets/vendors/clipboard.min.js' ]: [ './node_modules/clipboard/dist/clipboard.min.js' ],

						[ WORKING_DIR + 'assets/vendors/hoverIntent.js' ]: [ './node_modules/jquery-hoverintent/jquery.hoverIntent.js' ],
						[ WORKING_DIR + 'assets/vendors/hoverIntent.min.js' ]: [ './node_modules/jquery-hoverintent/jquery.hoverIntent.min.js' ],

						// Renamed to avoid conflict with jQuery hoverIntent.min.js (after minifying).
						[ WORKING_DIR + 'assets/vendors/hoverintent-js.min.js' ]: [ './node_modules/hoverintent/dist/hoverintent.min.js' ],

						[ WORKING_DIR + 'assets/vendors/imagesloaded.js' ]: [ './node_modules/imagesloaded/imagesloaded.pkgd.js' ],
						[ WORKING_DIR + 'assets/vendors/imagesloaded.min.js' ]: [ './node_modules/imagesloaded/imagesloaded.pkgd.min.js' ],

						[ WORKING_DIR + 'assets/vendors/jquery/jquery.js' ]: [ './node_modules/jquery/dist/jquery.js' ],
						[ WORKING_DIR + 'assets/vendors/jquery/jquery.min.js' ]: [ './node_modules/jquery/dist/jquery.min.js' ],

						[ WORKING_DIR + 'assets/vendors/jquery/jquery.form.js' ]: [ './node_modules/jquery-form/src/jquery.form.js' ],
						[ WORKING_DIR + 'assets/vendors/jquery/jquery.form.min.js' ]: [ './node_modules/jquery-form/dist/jquery.form.min.js' ],

						[ WORKING_DIR + 'assets/vendors/jquery/jquery.color.js' ]: [ './node_modules/jquery-color/dist/jquery.color.js' ],
						[ WORKING_DIR + 'assets/vendors/jquery/jquery.color.min.js' ]: [ './node_modules/jquery-color/dist/jquery.color.min.js' ],

						[ WORKING_DIR + 'assets/vendors/masonry.js' ]: [ './node_modules/masonry-layout/dist/masonry.pkgd.js' ],
						[ WORKING_DIR + 'assets/vendors/masonry.min.js' ]: [ './node_modules/masonry-layout/dist/masonry.pkgd.min.js' ],

						[ WORKING_DIR + 'assets/vendors/twemoji.js' ]: [ './node_modules/twemoji/dist/twemoji.js' ],
						[ WORKING_DIR + 'assets/vendors/twemoji.min.js' ]: [ './node_modules/twemoji/dist/twemoji.min.js' ],

						[ WORKING_DIR + 'assets/vendors/underscore.js' ]: [ './node_modules/underscore/underscore.js' ],
						[ WORKING_DIR + 'assets/vendors/underscore.min.js' ]: [ './node_modules/underscore/underscore-min.js' ]
					}
				]
			},
			'vendor-js': {
				files: [
					{
						expand: true,
						cwd: './node_modules/bootstrap/dist/js',
						src: [
							'bootstrap.bundle.js',
							'bootstrap.bundle.min.js'
						],
						dest: WORKING_DIR + 'assets/vendors/bootstrap/'
					},
					{
						expand: true,
						cwd: './node_modules/jquery-validation/dist/',
						src: [
							'jquery.validate.js',
							'jquery.validate.min.js'
						],
						dest: WORKING_DIR + 'assets/vendors/jquery-validation/'
					},
					{
						expand: true,
						cwd: './node_modules/perfect-scrollbar/css/',
						src: [
							'**/*',
							'!**/*.map'
						],
						dest: WORKING_DIR + 'assets/vendors/perfect-scrollbar/css/'
					},
					{
						expand: true,
						cwd: './node_modules/perfect-scrollbar/dist/',
						src: [
							'perfect-scrollbar.js',
							'perfect-scrollbar.min.js',
							'css/perfect-scrollbar.css',
							'css/perfect-scrollbar.min.css'

						],
						dest: WORKING_DIR + 'assets/vendors/perfect-scrollbar/'
					},

					{
						expand: true,
						cwd: SOURCE_DIR + 'js/_enqueues/vendor/',
						src: [
							'**/*',
							// '!farbtastic.js',
							// '!iris.min.js',
							'!deprecated/**',
							'!README.md',
							// Ignore unminified version of vendor lib we don't ship.
							'!jquery/jquery.masonry.js',
							'!tinymce/tinymce.js'
						],
						dest: WORKING_DIR + 'assets/vendors/'
					},

					{
						expand: true,
						cwd: SOURCE_DIR + 'js/_enqueues/vendor/deprecated',
						src: [
							'suggest*'
						],
						dest: WORKING_DIR + 'assets/vendors/jquery/'
					},
					{
						//这个源文件不参与生产环境
						expand: true,
						cwd: SOURCE_DIR + 'js/_enqueues/vendor/tinymce/',
						src: 'tinymce.js',
						dest: SOURCE_DIR + 'assets/vendors/tinymce/'
					},

				]
			},
			'assets-js': {
				files: [{
                    expand: true,
                    cwd: SOURCE_DIR + 'js/_enqueues/',
                    src: [
	                    'app.js',
	                    'login/*.js'
                    ],
                    dest: WORKING_DIR + 'assets/js/'
                }]
			},
			'admin-js': {
				files: {
					[ WORKING_DIR + 'gc-admin/js/accordion.js' ]: [ './src/js/_enqueues/lib/accordion.js' ],
					[ WORKING_DIR + 'gc-admin/js/appkeys.js' ]: [ './src/js/_enqueues/admin/appkeys.js' ],
					[ WORKING_DIR + 'gc-admin/js/auth-app.js' ]: [ './src/js/_enqueues/admin/auth-app.js' ],
					[ WORKING_DIR + 'gc-admin/js/code-editor.js' ]: [ './src/js/_enqueues/gc/code-editor.js' ],
					[ WORKING_DIR + 'gc-admin/js/color-picker.js' ]: [ './src/js/_enqueues/lib/color-picker.js' ],
					[ WORKING_DIR + 'gc-admin/js/comment.js' ]: [ './src/js/_enqueues/admin/comment.js' ],
					[ WORKING_DIR + 'gc-admin/js/common.js' ]: [ './src/js/_enqueues/admin/common.js' ],
					[ WORKING_DIR + 'gc-admin/js/custom-background.js' ]: [ './src/js/_enqueues/admin/custom-background.js' ],
					[ WORKING_DIR + 'gc-admin/js/custom-header.js' ]: [ './src/js/_enqueues/admin/custom-header.js' ],
					[ WORKING_DIR + 'gc-admin/js/customize-controls.js' ]: [ './src/js/_enqueues/gc/customize/controls.js' ],
					[ WORKING_DIR + 'gc-admin/js/customize-nav-menus.js' ]: [ './src/js/_enqueues/gc/customize/nav-menus.js' ],
					[ WORKING_DIR + 'gc-admin/js/customize-widgets.js' ]: [ './src/js/_enqueues/gc/customize/widgets.js' ],
					[ WORKING_DIR + 'gc-admin/js/dashboard.js' ]: [ './src/js/_enqueues/gc/dashboard.js' ],
					[ WORKING_DIR + 'gc-admin/js/edit-comments.js' ]: [ './src/js/_enqueues/admin/edit-comments.js' ],
					[ WORKING_DIR + 'gc-admin/js/editor-expand.js' ]: [ './src/js/_enqueues/gc/editor/dfw.js' ],
					[ WORKING_DIR + 'gc-admin/js/editor.js' ]: [ './src/js/_enqueues/gc/editor/base.js' ],
					[ WORKING_DIR + 'gc-admin/js/gallery.js' ]: [ './src/js/_enqueues/lib/gallery.js' ],
					[ WORKING_DIR + 'gc-admin/js/image-edit.js' ]: [ './src/js/_enqueues/lib/image-edit.js' ],
					[ WORKING_DIR + 'gc-admin/js/inline-edit-post.js' ]: [ './src/js/_enqueues/admin/inline-edit-post.js' ],
					[ WORKING_DIR + 'gc-admin/js/inline-edit-tax.js' ]: [ './src/js/_enqueues/admin/inline-edit-tax.js' ],
					[ WORKING_DIR + 'gc-admin/js/language-chooser.js' ]: [ './src/js/_enqueues/lib/language-chooser.js' ],
					[ WORKING_DIR + 'gc-admin/js/link.js' ]: [ './src/js/_enqueues/admin/link.js' ],
					[ WORKING_DIR + 'gc-admin/js/media-gallery.js' ]: [ './src/js/_enqueues/deprecated/media-gallery.js' ],
					[ WORKING_DIR + 'gc-admin/js/media-upload.js' ]: [ './src/js/_enqueues/admin/media-upload.js' ],
					[ WORKING_DIR + 'gc-admin/js/media.js' ]: [ './src/js/_enqueues/admin/media.js' ],
					[ WORKING_DIR + 'gc-admin/js/nav-menu.js' ]: [ './src/js/_enqueues/lib/nav-menu.js' ],
					[ WORKING_DIR + 'gc-admin/js/password-strength-meter.js' ]: [ './src/js/_enqueues/gc/password-strength-meter.js' ],
					[ WORKING_DIR + 'gc-admin/js/plugin-install.js' ]: [ './src/js/_enqueues/admin/plugin-install.js' ],
					[ WORKING_DIR + 'gc-admin/js/post.js' ]: [ './src/js/_enqueues/admin/post.js' ],
					[ WORKING_DIR + 'gc-admin/js/postbox.js' ]: [ './src/js/_enqueues/admin/postbox.js' ],
					[ WORKING_DIR + 'gc-admin/js/revisions.js' ]: [ './src/js/_enqueues/gc/revisions.js' ],
					[ WORKING_DIR + 'gc-admin/js/set-post-thumbnail.js' ]: [ './src/js/_enqueues/admin/set-post-thumbnail.js' ],
					[ WORKING_DIR + 'gc-admin/js/svg-painter.js' ]: [ './src/js/_enqueues/gc/svg-painter.js' ],
					[ WORKING_DIR + 'gc-admin/js/tags-box.js' ]: [ './src/js/_enqueues/admin/tags-box.js' ],
					[ WORKING_DIR + 'gc-admin/js/tags-suggest.js' ]: [ './src/js/_enqueues/admin/tags-suggest.js' ],
					[ WORKING_DIR + 'gc-admin/js/tags.js' ]: [ './src/js/_enqueues/admin/tags.js' ],
					[ WORKING_DIR + 'gc-admin/js/site-health.js' ]: [ './src/js/_enqueues/admin/site-health.js' ],
					[ WORKING_DIR + 'gc-admin/js/privacy-tools.js' ]: [ './src/js/_enqueues/admin/privacy-tools.js' ],
					[ WORKING_DIR + 'gc-admin/js/theme-plugin-editor.js' ]: [ './src/js/_enqueues/gc/theme-plugin-editor.js' ],
					[ WORKING_DIR + 'gc-admin/js/theme.js' ]: [ './src/js/_enqueues/gc/theme.js' ],
					[ WORKING_DIR + 'gc-admin/js/updates.js' ]: [ './src/js/_enqueues/gc/updates.js' ],
					[ WORKING_DIR + 'gc-admin/js/user-profile.js' ]: [ './src/js/_enqueues/admin/user-profile.js' ],
					[ WORKING_DIR + 'gc-admin/js/user-suggest.js' ]: [ './src/js/_enqueues/lib/user-suggest.js' ],
					[ WORKING_DIR + 'gc-admin/js/widgets/custom-html-widgets.js' ]: [ './src/js/_enqueues/gc/widgets/custom-html.js' ],
					[ WORKING_DIR + 'gc-admin/js/widgets/media-audio-widget.js' ]: [ './src/js/_enqueues/gc/widgets/media-audio.js' ],
					[ WORKING_DIR + 'gc-admin/js/widgets/media-gallery-widget.js' ]: [ './src/js/_enqueues/gc/widgets/media-gallery.js' ],
					[ WORKING_DIR + 'gc-admin/js/widgets/media-image-widget.js' ]: [ './src/js/_enqueues/gc/widgets/media-image.js' ],
					[ WORKING_DIR + 'gc-admin/js/widgets/media-video-widget.js' ]: [ './src/js/_enqueues/gc/widgets/media-video.js' ],
					[ WORKING_DIR + 'gc-admin/js/widgets/media-widgets.js' ]: [ './src/js/_enqueues/gc/widgets/media.js' ],
					[ WORKING_DIR + 'gc-admin/js/widgets/text-widgets.js' ]: [ './src/js/_enqueues/gc/widgets/text.js' ],
					[ WORKING_DIR + 'gc-admin/js/widgets.js' ]: [ './src/js/_enqueues/admin/widgets.js' ],
					[ WORKING_DIR + 'gc-admin/js/word-count.js' ]: [ './src/js/_enqueues/gc/utils/word-count.js' ],
					[ WORKING_DIR + 'gc-admin/js/gc-fullscreen-stub.js' ]: [ './src/js/_enqueues/deprecated/fullscreen-stub.js' ],
					[ WORKING_DIR + 'gc-admin/js/xfn.js' ]: [ './src/js/_enqueues/admin/xfn.js' ]
				}
			},
			'includes-js': {
				files: {
					[ WORKING_DIR + 'gc-includes/js/admin-bar.js' ]: [ './src/js/_enqueues/lib/admin-bar.js' ],
					[ WORKING_DIR + 'gc-includes/js/api-request.js' ]: [ './src/js/_enqueues/gc/api-request.js' ],
					[ WORKING_DIR + 'gc-includes/js/autosave.js' ]: [ './src/js/_enqueues/gc/autosave.js' ],
					[ WORKING_DIR + 'gc-includes/js/comment-reply.js' ]: [ './src/js/_enqueues/lib/comment-reply.js' ],
					[ WORKING_DIR + 'gc-includes/js/customize-base.js' ]: [ './src/js/_enqueues/gc/customize/base.js' ],
					[ WORKING_DIR + 'gc-includes/js/customize-loader.js' ]: [ './src/js/_enqueues/gc/customize/loader.js' ],
					[ WORKING_DIR + 'gc-includes/js/customize-models.js' ]: [ './src/js/_enqueues/gc/customize/models.js' ],
					[ WORKING_DIR + 'gc-includes/js/customize-preview-nav-menus.js' ]: [ './src/js/_enqueues/gc/customize/preview-nav-menus.js' ],
					[ WORKING_DIR + 'gc-includes/js/customize-preview-widgets.js' ]: [ './src/js/_enqueues/gc/customize/preview-widgets.js' ],
					[ WORKING_DIR + 'gc-includes/js/customize-preview.js' ]: [ './src/js/_enqueues/gc/customize/preview.js' ],
					[ WORKING_DIR + 'gc-includes/js/customize-selective-refresh.js' ]: [ './src/js/_enqueues/gc/customize/selective-refresh.js' ],
					[ WORKING_DIR + 'gc-includes/js/customize-views.js' ]: [ './src/js/_enqueues/gc/customize/views.js' ],
					[ WORKING_DIR + 'gc-includes/js/heartbeat.js' ]: [ './src/js/_enqueues/gc/heartbeat.js' ],
					[ WORKING_DIR + 'gc-includes/js/mce-view.js' ]: [ './src/js/_enqueues/gc/mce-view.js' ],
					[ WORKING_DIR + 'gc-includes/js/media-editor.js' ]: [ './src/js/_enqueues/gc/media/editor.js' ],
					[ WORKING_DIR + 'gc-includes/js/quicktags.js' ]: [ './src/js/_enqueues/lib/quicktags.js' ],
					[ WORKING_DIR + 'gc-includes/js/shortcode.js' ]: [ './src/js/_enqueues/gc/shortcode.js' ],
					[ WORKING_DIR + 'gc-includes/js/utils.js' ]: [ './src/js/_enqueues/lib/cookies.js' ],
					[ WORKING_DIR + 'gc-includes/js/gc-ajax-response.js' ]: [ './src/js/_enqueues/lib/ajax-response.js' ],
					[ WORKING_DIR + 'gc-includes/js/gc-api.js' ]: [ './src/js/_enqueues/gc/api.js' ],
					[ WORKING_DIR + 'gc-includes/js/gc-auth-check.js' ]: [ './src/js/_enqueues/lib/auth-check.js' ],
					[ WORKING_DIR + 'gc-includes/js/gc-backbone.js' ]: [ './src/js/_enqueues/gc/backbone.js' ],
					[ WORKING_DIR + 'gc-includes/js/gc-custom-header.js' ]: [ './src/js/_enqueues/gc/custom-header.js' ],
					[ WORKING_DIR + 'gc-includes/js/gc-embed-template.js' ]: [ './src/js/_enqueues/lib/embed-template.js' ],
					[ WORKING_DIR + 'gc-includes/js/gc-embed.js' ]: [ './src/js/_enqueues/gc/embed.js' ],
					[ WORKING_DIR + 'gc-includes/js/gc-emoji-loader.js' ]: [ './src/js/_enqueues/lib/emoji-loader.js' ],
					[ WORKING_DIR + 'gc-includes/js/gc-emoji.js' ]: [ './src/js/_enqueues/gc/emoji.js' ],
					[ WORKING_DIR + 'gc-includes/js/gc-list-revisions.js' ]: [ './src/js/_enqueues/lib/list-revisions.js' ],
					[ WORKING_DIR + 'gc-includes/js/gc-lists.js' ]: [ './src/js/_enqueues/lib/lists.js' ],
					[ WORKING_DIR + 'gc-includes/js/gc-pointer.js' ]: [ './src/js/_enqueues/lib/pointer.js' ],
					[ WORKING_DIR + 'gc-includes/js/gc-sanitize.js' ]: [ './src/js/_enqueues/gc/sanitize.js' ],
					[ WORKING_DIR + 'gc-includes/js/gc-util.js' ]: [ './src/js/_enqueues/gc/util.js' ],
					[ WORKING_DIR + 'gc-includes/js/gcdialog.js' ]: [ './src/js/_enqueues/lib/dialog.js' ],
					[ WORKING_DIR + 'gc-includes/js/gclink.js' ]: [ './src/js/_enqueues/lib/link.js' ],
					[ WORKING_DIR + 'gc-includes/js/zxcvbn-async.js' ]: [ './src/js/_enqueues/lib/zxcvbn-async.js' ]
				}
			},
			'gc-admin-css-compat-rtl': {
				options: {
					processContent: function( src ) {
						return src.replace( /\.css/g, '-rtl.css' );
					}
				},
				src: SOURCE_DIR + 'gc-admin/css/gc-admin.css',
				dest: WORKING_DIR + 'gc-admin/css/gc-admin-rtl.css'
			},
			'gc-admin-css-compat-min': {
				options: {
					processContent: function( src ) {
						return src.replace( /\.css/g, '.min.css' );
					}
				},
				files: [
					{
						src: SOURCE_DIR + 'gc-admin/css/gc-admin.css',
						dest: WORKING_DIR + 'gc-admin/css/gc-admin.min.css'
					},
					{
						src:  WORKING_DIR + 'gc-admin/css/gc-admin-rtl.css',
						dest: WORKING_DIR + 'gc-admin/css/gc-admin-rtl.min.css'
					}
				]
			},
			version: {
				options: {
					processContent: function( src ) {
						return src.replace( /^\$gc_version = '(.+?)';/m, function( str, version ) {
							version = version.replace( /-src$/, '' );

							// If the version includes an SVN commit (-12345), it's not a released alpha/beta. Append a timestamp.
							version = version.replace( /-[\d]{5}$/, '-' + grunt.template.today( 'yyyymmdd.HHMMss' ) );

							/* jshint quotmark: true */
							return "$gc_version = '" + version + "';";
						});
					}
				},
				src: SOURCE_DIR + 'gc-includes/version.php',
				dest: BUILD_DIR + 'gc-includes/version.php'
			},
			dynamic: {
				dot: true,
				expand: true,
				cwd: SOURCE_DIR,
				dest: WORKING_DIR,
				src: []
			},
			'dynamic-js': {
				files: {}
			},
			qunit: {
				src: 'tests/qunit/index.html',
				dest: 'tests/qunit/compiled.html',
				options: {
					processContent: function( src ) {
						return src.replace( /(\".+?\/)build(\/.+?)(?:.min)?(.js\")/g , function( match, $1, $2, $3 ) {
							// Don't add `.min` to files that don't have it.
							return $1 + 'build' + $2 + ( /jquery$/.test( $2 ) ? '' : '.min' ) + $3;
						} );
					}
				}
			}
		},
		sass: {
			app: {
				expand: true,
				cwd: SOURCE_DIR + 'assets/scss/',
				dest: WORKING_DIR + 'assets/css/',
				ext: '.css',
				src: ['app.scss'],
				options: {
					implementation: sass
				}
			},
			'admin-bar': {
				expand: true,
				cwd: SOURCE_DIR + 'assets/scss/',
				dest: WORKING_DIR + 'assets/css/',
				ext: '.css',
				src: ['admin-bar.scss'],
				options: {
					implementation: sass
				}
			}
		},
		cssmin: {
			options: {
				compatibility: 'ie11'
			},
			core: {
				expand: true,
				cwd: WORKING_DIR,
				dest: WORKING_DIR,
				ext: '.min.css',
				src: [
					'assets/css/*.css',
					'assets/vendors/perfect-scrollbar/css/*.css',
					'gc-admin/css/*.css',
					'!gc-admin/css/gc-admin*.css',
					'gc-includes/css/*.css',
					'assets/vendors/mediaelement/gc-mediaelement.css'
				]
			},
			rtl: {
				expand: true,
				cwd: WORKING_DIR,
				dest: WORKING_DIR,
				ext: '.min.css',
				src: [
					'assets/css/*-rtl.css',
					'gc-admin/css/*-rtl.css',
					'!gc-admin/css/gc-admin*.css',
					'gc-includes/css/*-rtl.css'
				]
			}
		},
		rtlcss: {
			options: {
				// rtlcss options.
				opts: {
					clean: false,
					processUrls: { atrule: true, decl: false },
					stringMap: [
						{
							name: 'import-rtl-stylesheet',
							priority: 10,
							exclusive: true,
							search: [ '.css' ],
							replace: [ '-rtl.css' ],
							options: {
								scope: 'url',
								ignoreCase: false
							}
						}
					]
				},
				saveUnmodified: false,
				plugins: [
					{
						name: 'swap-dashicons-left-right-arrows',
						priority: 10,
						directives: {
							control: {},
							value: []
						},
						processors: [
							{
								expr: /content/im,
								action: function( prop, value ) {
									if ( value === '"\\f141"' ) { // dashicons-arrow-left
										value = '"\\f139"';
									} else if ( value === '"\\f340"' ) { // dashicons-arrow-left-alt
										value = '"\\f344"';
									} else if ( value === '"\\f341"' ) { // dashicons-arrow-left-alt2
										value = '"\\f345"';
									} else if ( value === '"\\f139"' ) { // dashicons-arrow-right
										value = '"\\f141"';
									} else if ( value === '"\\f344"' ) { // dashicons-arrow-right-alt
										value = '"\\f340"';
									} else if ( value === '"\\f345"' ) { // dashicons-arrow-right-alt2
										value = '"\\f341"';
									}
									return { prop: prop, value: value };
								}
							}
						]
					}
				]
			},
			core: {
				expand: true,
				cwd: SOURCE_DIR,
				dest: WORKING_DIR,
				ext: '-rtl.css',
				src: [
					'assets/css/*.css',
					'gc-admin/css/*.css',
					'gc-includes/css/*.css',

					// Exclude minified and already processed files, and files from external packages.
					// These are present when running `grunt build` after `grunt --dev`.
					'!assets/css/*-rtl.css',
					'!gc-admin/css/*-rtl.css',
					'!gc-includes/css/*-rtl.css',

					'!assets/css/*.min.css',
					'!gc-admin/css/*.min.css',
					'!gc-includes/css/*.min.css',

					'!gc-includes/css/dist',

					// Exceptions.
					'!gc-includes/css/dashicons.css',
					'!gc-includes/css/gc-embed-template.css',
					'!gc-includes/css/gc-embed-template-ie.css'
				]
			},
			dynamic: {
				expand: true,
				cwd: SOURCE_DIR,
				dest: WORKING_DIR,
				ext: '-rtl.css',
				src: []
			}
		},
		jshint: {
			options: grunt.file.readJSON( '.jshintrc' ),
			grunt: {
				src: ['Gruntfile.js']
			},
			tests: {
				src: [
					'tests/qunit/**/*.js',
					'!tests/qunit/vendor/*',
					'!tests/qunit/editor/**'
				],
				options: grunt.file.readJSON( 'tests/qunit/.jshintrc' )
			},
			themes: {
				expand: true,
				cwd: SOURCE_DIR + 'gc-content/themes',
				src: [
					'default*/**/*.js',
					// Third party scripts.
					'!default*/node_modules/**'
	
				]
			},
			media: {
				src: [
					SOURCE_DIR + 'js/media/**/*.js'
				]
			},
			core: {
				expand: true,
				cwd: SOURCE_DIR,
				src: [
					'js/_enqueues/**/*.js',
					// Third party scripts.
					'!js/_enqueues/vendor/**/*.js'
				],
				// Remove once other JSHint errors are resolved.
				options: {
					curly: false,
					eqeqeq: false
				},
				/*
				 * Limit JSHint's run to a single specified file:
				 *
				 *    grunt jshint:core --file=filename.js
				 *
				 * Optionally, include the file path:
				 *
				 *    grunt jshint:core --file=path/to/filename.js
				 */
				filter: function( filepath ) {
					var index, file = grunt.option( 'file' );

					// Don't filter when no target file is specified.
					if ( ! file ) {
						return true;
					}

					// Normalize filepath for Windows.
					filepath = filepath.replace( /\\/g, '/' );
					index = filepath.lastIndexOf( '/' + file );

					// Match only the filename passed from cli.
					if ( filepath === file || ( -1 !== index && index === filepath.length - ( file.length + 1 ) ) ) {
						return true;
					}

					return false;
				}
			},
			plugins: {
				expand: true,
				cwd: SOURCE_DIR + 'gc-content/plugins',
				src: [
					'**/*.js',
					'!**/*.min.js'
				],
				/*
				 * Limit JSHint's run to a single specified plugin directory:
				 *
				 *    grunt jshint:plugins --dir=foldername
				 */
				filter: function( dirpath ) {
					var index, dir = grunt.option( 'dir' );

					// Don't filter when no target folder is specified.
					if ( ! dir ) {
						return true;
					}

					dirpath = dirpath.replace( /\\/g, '/' );
					index = dirpath.lastIndexOf( '/' + dir );

					// Match only the folder name passed from cli.
					if ( -1 !== index ) {
						return true;
					}

					return false;
				}
			}
		},
		jsdoc : {
			dist : {
				dest: 'jsdoc',
				options: {
					configure : 'jsdoc.conf.json'
				}
			}
		},
		qunit: {
			files: [
				'tests/qunit/**/*.html',
				'!tests/qunit/editor/**'
			]
		},
		phpunit: {
			'default': {
				args: ['--verbose', '-c', 'phpunit.xml.dist']
			},
			ajax: {
				args: ['--verbose', '-c', 'phpunit.xml.dist', '--group', 'ajax']
			},
			multisite: {
				args: ['--verbose', '-c', 'tests/phpunit/multisite.xml']
			},
			'ms-ajax': {
				args: ['--verbose', '-c', 'tests/phpunit/multisite.xml', '--group', 'ajax']
			},
			'ms-files': {
				args: ['--verbose', '-c', 'tests/phpunit/multisite.xml', '--group', 'ms-files']
			},
			'external-http': {
				args: ['--verbose', '-c', 'phpunit.xml.dist', '--group', 'external-http']
			},
			'restapi-jsclient': {
				args: ['--verbose', '-c', 'phpunit.xml.dist', '--group', 'restapi-jsclient']
			}
		},
		uglify: {
			options: {
				output: {
					ascii_only: true
				}
			},
			core: {
				expand: true,
				cwd: WORKING_DIR,
				dest: WORKING_DIR,
				ext: '.min.js',
				src: [
					'assets/js/app.js',
					'assets/js/**/*.js',
					'gc-admin/js/**/*.js',
					'gc-includes/js/*.js',
					'assets/vendors/plupload/*.js',
					'assets/vendors/mediaelement/gc-mediaelement.js',
					'assets/vendors/mediaelement/gc-playlist.js',
					'assets/vendors/mediaelement/mediaelement-migrate.js',
					'assets/vendors/tinymce/plugins/gechiui/plugin.js',
					'assets/vendors/tinymce/plugins/gc*/plugin.js',
					'assets/vendors/colorpicker.js',
					'assets/vendors/farbtastic.js',
					'assets/vendors/json2.js',
					'assets/vendors/swfobject.js',
					'assets/vendors/tw-sack.js',

					// Exceptions.
					'!**/*.min.js',
					'!gc-admin/js/custom-header.js', // Why? We should minify this.
					// '!gassets/vendors/farbtastic.js',
					// '!assets/vendors/swfobject.js',
					'!gc-includes/js/gc-embed.js' // We have extra options for this, see uglify:embed.
				]
			},
			embed: {
				options: {
					compress: {
						conditionals: false
					}
				},
				expand: true,
				cwd: WORKING_DIR,
				dest: WORKING_DIR,
				ext: '.min.js',
				src: ['gc-includes/js/gc-embed.js']
			},
			'jquery-ui': {
				options: {
					// Preserve comments that start with a bang.
					output: {
						comments: /^!/
					}
				},
				expand: true,
				cwd: WORKING_DIR + 'assets/vendors/jquery/ui/',
				dest: WORKING_DIR + 'assets/vendors/jquery/ui/',
				ext: '.min.js',
				src: ['*.js']
			},
			imgareaselect: {
				src: WORKING_DIR + 'assets/vendors/imgareaselect/jquery.imgareaselect.js',
				dest: WORKING_DIR + 'assets/vendors/imgareaselect/jquery.imgareaselect.min.js'
			},
			jqueryform: {
				src: WORKING_DIR + 'assets/vendors/jquery/jquery.form.js',
				dest: WORKING_DIR + 'assets/vendors/jquery/jquery.form.min.js'
			},
			moment: {
				src: WORKING_DIR + 'gc-includes/js/dist/vendor/moment.js',
				dest: WORKING_DIR + 'gc-includes/js/dist/vendor/moment.min.js'
			},
			dynamic: {
				expand: true,
				cwd: WORKING_DIR,
				dest: WORKING_DIR,
				ext: '.min.js',
				src: []
			}
		},
		webpack: {
			//environment的值设为production可以正常编译，而设为development就会编译报错
			prod: webpackConfig( { environment: 'production', buildTarget: WORKING_DIR } ),
			dev: webpackConfig( { environment: 'production', buildTarget: WORKING_DIR } ),
			watch: webpackConfig( { environment: 'development', watch: true } )
		},
		concat: {
			tinymce: {
				options: {
					separator: '\n',
					process: function( src, filepath ) {
						return '// Source: ' + filepath.replace( WORKING_DIR, '' ) + '\n' + src;
					}
				},
				src: [
					WORKING_DIR + 'assets/vendors/tinymce/tinymce.min.js',
					WORKING_DIR + 'assets/vendors/tinymce/themes/modern/theme.min.js',
					WORKING_DIR + 'assets/vendors/tinymce/plugins/*/plugin.min.js'
				],
				dest: WORKING_DIR + 'assets/vendors/tinymce/gc-tinymce.js'
			},
			emoji: {
				options: {
					separator: '\n',
					process: function( src, filepath ) {
						return '// Source: ' + filepath.replace( WORKING_DIR, '' ) + '\n' + src;
					}
				},
				src: [
					WORKING_DIR + 'assets/vendors/twemoji.min.js',
					WORKING_DIR + 'gc-includes/js/gc-emoji.min.js'
				],
				dest: WORKING_DIR + 'gc-includes/js/gc-emoji-release.min.js'
			}
		},
		patch:{
			options: {
				file_mappings: {

					'src/gc-admin/js/accordion.js': 'src/js/_enqueues/lib/accordion.js',
					'src/gc-admin/js/appkeys.js': 'src/js/_enqueues/admin/appkeys.js',
					'src/gc-admin/js/auth-app.js': 'src/js/_enqueues/admin/auth-app.js',
					'src/gc-admin/js/code-editor.js': 'src/js/_enqueues/gc/code-editor.js',
					'src/gc-admin/js/color-picker.js': 'src/js/_enqueues/lib/color-picker.js',
					'src/gc-admin/js/comment.js': 'src/js/_enqueues/admin/comment.js',
					'src/gc-admin/js/common.js': 'src/js/_enqueues/admin/common.js',
					'src/gc-admin/js/custom-background.js': 'src/js/_enqueues/admin/custom-background.js',
					'src/gc-admin/js/custom-header.js': 'src/js/_enqueues/admin/custom-header.js',
					'src/gc-admin/js/customize-controls.js': 'src/js/_enqueues/gc/customize/controls.js',
					'src/gc-admin/js/customize-nav-menus.js': 'src/js/_enqueues/gc/customize/nav-menus.js',
					'src/gc-admin/js/customize-widgets.js': 'src/js/_enqueues/gc/customize/widgets.js',
					'src/gc-admin/js/dashboard.js': 'src/js/_enqueues/gc/dashboard.js',
					'src/gc-admin/js/edit-comments.js': 'src/js/_enqueues/admin/edit-comments.js',
					'src/gc-admin/js/editor-expand.js': 'src/js/_enqueues/gc/editor/dfw.js',
					'src/gc-admin/js/editor.js': 'src/js/_enqueues/gc/editor/base.js',
					'src/gc-admin/js/gallery.js': 'src/js/_enqueues/lib/gallery.js',
					'src/gc-admin/js/image-edit.js': 'src/js/_enqueues/lib/image-edit.js',
					'src/gc-admin/js/inline-edit-post.js': 'src/js/_enqueues/admin/inline-edit-post.js',
					'src/gc-admin/js/inline-edit-tax.js': 'src/js/_enqueues/admin/inline-edit-tax.js',
					'src/gc-admin/js/language-chooser.js': 'src/js/_enqueues/lib/language-chooser.js',
					'src/gc-admin/js/link.js': 'src/js/_enqueues/admin/link.js',
					'src/gc-admin/js/media-gallery.js': 'src/js/_enqueues/deprecated/media-gallery.js',
					'src/gc-admin/js/media-upload.js': 'src/js/_enqueues/admin/media-upload.js',
					'src/gc-admin/js/media.js': 'src/js/_enqueues/admin/media.js',
					'src/gc-admin/js/nav-menu.js': 'src/js/_enqueues/lib/nav-menu.js',
					'src/gc-admin/js/password-strength-meter.js': 'src/js/_enqueues/gc/password-strength-meter.js',
					'src/gc-admin/js/plugin-install.js': 'src/js/_enqueues/admin/plugin-install.js',
					'src/gc-admin/js/post.js': 'src/js/_enqueues/admin/post.js',
					'src/gc-admin/js/postbox.js': 'src/js/_enqueues/admin/postbox.js',
					'src/gc-admin/js/revisions.js': 'src/js/_enqueues/gc/revisions.js',
					'src/gc-admin/js/set-post-thumbnail.js': 'src/js/_enqueues/admin/set-post-thumbnail.js',
					'src/gc-admin/js/svg-painter.js': 'src/js/_enqueues/gc/svg-painter.js',
					'src/gc-admin/js/tags-box.js': 'src/js/_enqueues/admin/tags-box.js',
					'src/gc-admin/js/tags-suggest.js': 'src/js/_enqueues/admin/tags-suggest.js',
					'src/gc-admin/js/tags.js': 'src/js/_enqueues/admin/tags.js',
					'src/gc-admin/js/theme-plugin-editor.js': 'src/js/_enqueues/gc/theme-plugin-editor.js',
					'src/gc-admin/js/theme.js': 'src/js/_enqueues/gc/theme.js',
					'src/gc-admin/js/updates.js': 'src/js/_enqueues/gc/updates.js',
					'src/gc-admin/js/user-profile.js': 'src/js/_enqueues/admin/user-profile.js',
					'src/gc-admin/js/user-suggest.js': 'src/js/_enqueues/lib/user-suggest.js',
					'src/gc-admin/js/widgets/custom-html-widgets.js': 'src/js/_enqueues/gc/widgets/custom-html.js',
					'src/gc-admin/js/widgets/media-audio-widget.js': 'src/js/_enqueues/gc/widgets/media-audio.js',
					'src/gc-admin/js/widgets/media-gallery-widget.js': 'src/js/_enqueues/gc/widgets/media-gallery.js',
					'src/gc-admin/js/widgets/media-image-widget.js': 'src/js/_enqueues/gc/widgets/media-image.js',
					'src/gc-admin/js/widgets/media-video-widget.js': 'src/js/_enqueues/gc/widgets/media-video.js',
					'src/gc-admin/js/widgets/media-widgets.js': 'src/js/_enqueues/gc/widgets/media.js',
					'src/gc-admin/js/widgets/text-widgets.js': 'src/js/_enqueues/gc/widgets/text.js',
					'src/gc-admin/js/widgets.js': 'src/js/_enqueues/admin/widgets.js',
					'src/gc-admin/js/word-count.js': 'src/js/_enqueues/gc/utils/word-count.js',
					'src/gc-admin/js/gc-fullscreen-stub.js': 'src/js/_enqueues/deprecated/fullscreen-stub.js',
					'src/gc-admin/js/xfn.js': 'src/js/_enqueues/admin/xfn.js',
					'src/gc-includes/js/admin-bar.js': 'src/js/_enqueues/lib/admin-bar.js',
					'src/gc-includes/js/api-request.js': 'src/js/_enqueues/gc/api-request.js',
					'src/gc-includes/js/autosave.js': 'src/js/_enqueues/gc/autosave.js',
					'src/gc-includes/js/comment-reply.js': 'src/js/_enqueues/lib/comment-reply.js',
					'src/gc-includes/js/customize-base.js': 'src/js/_enqueues/gc/customize/base.js',
					'src/gc-includes/js/customize-loader.js': 'src/js/_enqueues/gc/customize/loader.js',
					'src/gc-includes/js/customize-models.js': 'src/js/_enqueues/gc/customize/models.js',
					'src/gc-includes/js/customize-preview-nav-menus.js': 'src/js/_enqueues/gc/customize/preview-nav-menus.js',
					'src/gc-includes/js/customize-preview-widgets.js': 'src/js/_enqueues/gc/customize/preview-widgets.js',
					'src/gc-includes/js/customize-preview.js': 'src/js/_enqueues/gc/customize/preview.js',
					'src/gc-includes/js/customize-selective-refresh.js': 'src/js/_enqueues/gc/customize/selective-refresh.js',
					'src/gc-includes/js/customize-views.js': 'src/js/_enqueues/gc/customize/views.js',
					'src/gc-includes/js/heartbeat.js': 'src/js/_enqueues/gc/heartbeat.js',
					'src/gc-includes/js/mce-view.js': 'src/js/_enqueues/gc/mce-view.js',
					'src/gc-includes/js/media-editor.js': 'src/js/_enqueues/gc/media/editor.js',
					'src/gc-includes/js/quicktags.js': 'src/js/_enqueues/lib/quicktags.js',
					'src/gc-includes/js/shortcode.js': 'src/js/_enqueues/gc/shortcode.js',
					'src/gc-includes/js/utils.js': 'src/js/_enqueues/lib/cookies.js',
					'src/gc-includes/js/gc-ajax-response.js': 'src/js/_enqueues/lib/ajax-response.js',
					'src/gc-includes/js/gc-api.js': 'src/js/_enqueues/gc/api.js',
					'src/gc-includes/js/gc-auth-check.js': 'src/js/_enqueues/lib/auth-check.js',
					'src/gc-includes/js/gc-backbone.js': 'src/js/_enqueues/gc/backbone.js',
					'src/gc-includes/js/gc-custom-header.js': 'src/js/_enqueues/gc/custom-header.js',
					'src/gc-includes/js/gc-embed-template.js': 'src/js/_enqueues/lib/embed-template.js',
					'src/gc-includes/js/gc-embed.js': 'src/js/_enqueues/gc/embed.js',
					'src/gc-includes/js/gc-emoji-loader.js': 'src/js/_enqueues/lib/emoji-loader.js',
					'src/gc-includes/js/gc-emoji.js': 'src/js/_enqueues/gc/emoji.js',
					'src/gc-includes/js/gc-list-revisions.js': 'src/js/_enqueues/lib/list-revisions.js',
					'src/gc-includes/js/gc-lists.js': 'src/js/_enqueues/lib/lists.js',
					'src/gc-includes/js/gc-pointer.js': 'src/js/_enqueues/lib/pointer.js',
					'src/gc-includes/js/gc-sanitize.js': 'src/js/_enqueues/gc/sanitize.js',
					'src/gc-includes/js/gc-util.js': 'src/js/_enqueues/gc/util.js',
					'src/gc-includes/js/gcdialog.js': 'src/js/_enqueues/lib/dialog.js',
					'src/gc-includes/js/gclink.js': 'src/js/_enqueues/lib/link.js',
					'src/gc-includes/js/zxcvbn-async.js': 'src/js/_enqueues/lib/zxcvbn-async.js',
					'src/gc-includes/js/media/controllers/audio-details.js' : 'src/js/media/controllers/audio-details.js',
					'src/gc-includes/js/media/controllers/collection-add.js' : 'src/js/media/controllers/collection-add.js',
					'src/gc-includes/js/media/controllers/collection-edit.js' : 'src/js/media/controllers/collection-edit.js',
					'src/gc-includes/js/media/controllers/cropper.js' : 'src/js/media/controllers/cropper.js',
					'src/gc-includes/js/media/controllers/customize-image-cropper.js' : 'src/js/media/controllers/customize-image-cropper.js',
					'src/gc-includes/js/media/controllers/edit-attachment-metadata.js' : 'src/js/media/controllers/edit-attachment-metadata.js',
					'src/gc-includes/js/media/controllers/edit-image.js' : 'src/js/media/controllers/edit-image.js',
					'src/gc-includes/js/media/controllers/embed.js' : 'src/js/media/controllers/embed.js',
					'src/gc-includes/js/media/controllers/featured-image.js' : 'src/js/media/controllers/featured-image.js',
					'src/gc-includes/js/media/controllers/gallery-add.js' : 'src/js/media/controllers/gallery-add.js',
					'src/gc-includes/js/media/controllers/gallery-edit.js' : 'src/js/media/controllers/gallery-edit.js',
					'src/gc-includes/js/media/controllers/image-details.js' : 'src/js/media/controllers/image-details.js',
					'src/gc-includes/js/media/controllers/library.js' : 'src/js/media/controllers/library.js',
					'src/gc-includes/js/media/controllers/media-library.js' : 'src/js/media/controllers/media-library.js',
					'src/gc-includes/js/media/controllers/region.js' : 'src/js/media/controllers/region.js',
					'src/gc-includes/js/media/controllers/replace-image.js' : 'src/js/media/controllers/replace-image.js',
					'src/gc-includes/js/media/controllers/site-icon-cropper.js' : 'src/js/media/controllers/site-icon-cropper.js',
					'src/gc-includes/js/media/controllers/state-machine.js' : 'src/js/media/controllers/state-machine.js',
					'src/gc-includes/js/media/controllers/state.js' : 'src/js/media/controllers/state.js',
					'src/gc-includes/js/media/controllers/video-details.js' : 'src/js/media/controllers/video-details.js',
					'src/gc-includes/js/media/models/attachment.js' : 'src/js/media/models/attachment.js',
					'src/gc-includes/js/media/models/attachments.js' : 'src/js/media/models/attachments.js',
					'src/gc-includes/js/media/models/post-image.js' : 'src/js/media/models/post-image.js',
					'src/gc-includes/js/media/models/post-media.js' : 'src/js/media/models/post-media.js',
					'src/gc-includes/js/media/models/query.js' : 'src/js/media/models/query.js',
					'src/gc-includes/js/media/models/selection.js' : 'src/js/media/models/selection.js',
					'src/gc-includes/js/media/routers/manage.js' : 'src/js/media/routers/manage.js',
					'src/gc-includes/js/media/utils/selection-sync.js' : 'src/js/media/utils/selection-sync.js',
					'src/gc-includes/js/media/views/attachment-compat.js' : 'src/js/media/views/attachment-compat.js',
					'src/gc-includes/js/media/views/attachment-filters.js' : 'src/js/media/views/attachment-filters.js',
					'src/gc-includes/js/media/views/attachment-filters/all.js' : 'src/js/media/views/attachment-filters/all.js',
					'src/gc-includes/js/media/views/attachment-filters/date.js' : 'src/js/media/views/attachment-filters/date.js',
					'src/gc-includes/js/media/views/attachment-filters/uploaded.js' : 'src/js/media/views/attachment-filters/uploaded.js',
					'src/gc-includes/js/media/views/attachment.js' : 'src/js/media/views/attachment.js',
					'src/gc-includes/js/media/views/attachment/details-two-column.js' : 'src/js/media/views/details-two-column.js',
					'src/gc-includes/js/media/views/attachment/details.js' : 'src/js/media/views/details.js',
					'src/gc-includes/js/media/views/attachment/edit-library.js' : 'src/js/media/views/edit-library.js',
					'src/gc-includes/js/media/views/attachment/edit-selection.js' : 'src/js/media/views/edit-selection.js',
					'src/gc-includes/js/media/views/attachment/library.js' : 'src/js/media/views/library.js',
					'src/gc-includes/js/media/views/attachment/selection.js' : 'src/js/media/views/selection.js',
					'src/gc-includes/js/media/views/attachment/attachments.js' : 'src/js/media/views/attachments.js',
					'src/gc-includes/js/media/views/attachments/browser.js' : 'src/js/media/views/attachments/browser.js',
					'src/gc-includes/js/media/views/attachments/selection.js' : 'src/js/media/views/attachments/selection.js',
					'src/gc-includes/js/media/views/attachments/audio-details.js' : 'src/js/media/views/attachments/audio-details.js',
					'src/gc-includes/js/media/views/attachments/button-group.js' : 'src/js/media/views/attachments/button-group.js',
					'src/gc-includes/js/media/views/attachments/button.js' : 'src/js/media/views/attachments/button.js',
					'src/gc-includes/js/media/views/button/delete-selected-permanently.js' : 'src/js/media/views/button/delete-selected-permanently.js',
					'src/gc-includes/js/media/views/button/delete-selected.js' : 'src/js/media/views/button/delete-selected.js',
					'src/gc-includes/js/media/views/button/select-mode-toggle.js' : 'src/js/media/views/button/select-mode-toggle.js',
					'src/gc-includes/js/media/views/cropper.js' : 'src/js/media/views/cropper.js',
					'src/gc-includes/js/media/views/edit-image-details.js' : 'src/js/media/views/edit-image-details.js',
					'src/gc-includes/js/media/views/edit-image.js' : 'src/js/media/views/edit-image.js',
					'src/gc-includes/js/media/views/embed.js' : 'src/js/media/views/embed.js',
					'src/gc-includes/js/media/views/embed/image.js' : 'src/js/media/views/embed/image.js',
					'src/gc-includes/js/media/views/embed/link.js' : 'src/js/media/views/embed/link.js',
					'src/gc-includes/js/media/views/embed/url.js' : 'src/js/media/views/embed/url.js',
					'src/gc-includes/js/media/views/focus-manager.js' : 'src/js/media/views/focus-manager.js',
					'src/gc-includes/js/media/views/frame.js' : 'src/js/media/views/frame.js',
					'src/gc-includes/js/media/views/frame/audio-details.js' : 'src/js/media/views/frame/audio-details.js',
					'src/gc-includes/js/media/views/frame/edit-attachments.js' : 'src/js/media/views/frame/edit-attachments.js',
					'src/gc-includes/js/media/views/frame/image-details.js' : 'src/js/media/views/frame/image-details.js',
					'src/gc-includes/js/media/views/frame/manage.js' : 'src/js/media/views/frame/manage.js',
					'src/gc-includes/js/media/views/frame/media-details.js' : 'src/js/media/views/frame/media-details.js',
					'src/gc-includes/js/media/views/frame/post.js' : 'src/js/media/views/frame/post.js',
					'src/gc-includes/js/media/views/frame/select.js' : 'src/js/media/views/frame/select.js',
					'src/gc-includes/js/media/views/frame/video-details.js' : 'src/js/media/views/frame/video-details.js',
					'src/gc-includes/js/media/views/iframe.js' : 'src/js/media/views/iframe.js',
					'src/gc-includes/js/media/views/image-details.js' : 'src/js/media/views/image-details.js',
					'src/gc-includes/js/media/views/label.js' : 'src/js/media/views/label.js',
					'src/gc-includes/js/media/views/media-details.js' : 'src/js/media/views/media-details.js',
					'src/gc-includes/js/media/views/media-frame.js' : 'src/js/media/views/media-frame.js',
					'src/gc-includes/js/media/views/menu-item.js' : 'src/js/media/views/menu-item.js',
					'src/gc-includes/js/media/views/menu.js' : 'src/js/media/views/menu.js',
					'src/gc-includes/js/media/views/modal.js' : 'src/js/media/views/modal.js',
					'src/gc-includes/js/media/views/priority-list.js' : 'src/js/media/views/priority-list.js',
					'src/gc-includes/js/media/views/router-item.js' : 'src/js/media/views/router-item.js',
					'src/gc-includes/js/media/views/router.js' : 'src/js/media/views/router.js',
					'src/gc-includes/js/media/views/search.js' : 'src/js/media/views/search.js',
					'src/gc-includes/js/media/views/selection.js' : 'src/js/media/views/selection.js',
					'src/gc-includes/js/media/views/settings.js' : 'src/js/media/views/settings.js',
					'src/gc-includes/js/media/views/settings/attachment-display.js' : 'src/js/media/views/settings/attachment-display.js',
					'src/gc-includes/js/media/views/settings/gallery.js' : 'src/js/media/views/settings/gallery.js',
					'src/gc-includes/js/media/views/settings/playlist.js' : 'src/js/media/views/settings/playlist.js',
					'src/gc-includes/js/media/views/sidebar.js' : 'src/js/media/views/sidebar.js',
					'src/gc-includes/js/media/views/site-icon-cropper.js' : 'src/js/media/views/site-icon-cropper.js',
					'src/gc-includes/js/media/views/site-icon-preview.js' : 'src/js/media/views/site-icon-preview.js',
					'src/gc-includes/js/media/views/spinner.js' : 'src/js/media/views/spinner.js',
					'src/gc-includes/js/media/views/toolbar.js' : 'src/js/media/views/toolbar.js',
					'src/gc-includes/js/media/views/toolbar/embed.js' : 'src/js/media/views/toolbar/embed.js',
					'src/gc-includes/js/media/views/toolbar/select.js' : 'src/js/media/views/toolbar/select.js',
					'src/gc-includes/js/media/views/uploader/editor.js' : 'src/js/media/views/uploader/editor.js',
					'src/gc-includes/js/media/views/uploader/inline.js' : 'src/js/media/views/uploader/inline.js',
					'src/gc-includes/js/media/views/uploader/status-error.js' : 'src/js/media/views/uploader/status-error.js',
					'src/gc-includes/js/media/views/uploader/status.js' : 'src/js/media/views/uploader/status.js',
					'src/gc-includes/js/media/views/uploader/window.js' : 'src/js/media/views/uploader/window.js',
					'src/gc-includes/js/media/views/video-details.js' : 'src/js/media/views/video-details.js',
					'src/gc-includes/js/media/views/view.js' : 'src/js/media/views/view.js'
				}
			}
		},
		jsvalidate:{
			options: {
				globals: {},
				esprimaOptions:{},
				verbose: false
			},
			build: {
				files: {
					src: [
						WORKING_DIR + 'gc-{admin,includes}/**/*.js',
						WORKING_DIR + 'gc-content/themes/default*/**/*.js',
						'!' + WORKING_DIR + 'gc-content/themes/default*/node_modules/**/*.js',
						'!' + WORKING_DIR + 'gc-includes/blocks/**/*.js',
						'!' + WORKING_DIR + 'gc-includes/js/dist/**/*.js',
					]
				}
			},
			dynamic: {
				files: {
					src: []
				}
			}
		},
		imagemin: {
			core: {
				expand: true,
				cwd: SOURCE_DIR,
				src: [
					'gc-{admin,includes}/images/**/*.{png,jpg,gif,jpeg}',
					'assets/vendors/tinymce/skins/gechiui/images/*.{png,jpg,gif,jpeg}'
				],
				dest: SOURCE_DIR
			}
		},
		replace: {
			'emoji-regex': {
				options: {
					patterns: [
						{
							match: /\/\/ START: emoji arrays[\S\s]*\/\/ END: emoji arrays/g,
							replacement: function() {
								var regex, files,
									partials, partialsSet,
									entities, emojiArray;

								grunt.log.writeln( 'Fetching list of Twemoji files...' );

								// Fetch a list of the files that Twemoji supplies.
								files = spawn( 'svn', [ 'ls', 'https://github.com/twitter/twemoji.git/trunk/assets/svg' ] );
								if ( 0 !== files.status ) {
									grunt.fatal( 'Unable to fetch Twemoji file list' );
								}

								entities = files.stdout.toString();

								// Tidy up the file list.
								entities = entities.replace( /\.svg/g, '' );
								entities = entities.replace( /^$/g, '' );

								// Convert the emoji entities to HTML entities.
								partials = entities = entities.replace( /([a-z0-9]+)/g, '&#x$1;' );

								// Remove the hyphens between the HTML entities.
								entities = entities.replace( /-/g, '' );

								// Sort the entities list by length, so the longest emoji will be found first.
								emojiArray = entities.split( '\n' ).sort( function( a, b ) {
									return b.length - a.length;
								} );

								// Convert the entities list to PHP array syntax.
								entities = '\'' + emojiArray.filter( function( val ) {
									return val.length >= 8 ? val : false ;
								} ).join( '\', \'' ) + '\'';

								// Create a list of all characters used by the emoji list.
								partials = partials.replace( /-/g, '\n' );

								// Set automatically removes duplicates.
								partialsSet = new Set( partials.split( '\n' ) );

								// Convert the partials list to PHP array syntax.
								partials = '\'' + Array.from( partialsSet ).filter( function( val ) {
									return val.length >= 8 ? val : false ;
								} ).join( '\', \'' ) + '\'';

								regex = '// START: emoji arrays\n';
								regex += '\t$entities = array( ' + entities + ' );\n';
								regex += '\t$partials = array( ' + partials + ' );\n';
								regex += '\t// END: emoji arrays';

								return regex;
							}
						}
					]
				},
				files: [
					{
						expand: true,
						flatten: true,
						src: [
							SOURCE_DIR + 'gc-includes/formatting.php'
						],
						dest: SOURCE_DIR + 'gc-includes/'
					}
				]
			},
			'source-maps': {
				options: {
					patterns: [
						{
							match: new RegExp( '//# sourceMappingURL=.*\\s*' ),
							replacement: ''
						}
					]
				},
				files: [
					// {
					// 	expand: true,
					// 	flatten: true,
					// 	src: [
					// 		BUILD_DIR + 'assets/vendors/underscore.js'
					// 	],
					// 	dest: BUILD_DIR + 'assets/vendors/'
					// },
					{
						expand: true,
						// flatten: true,
						cwd:BUILD_DIR + 'assets/vendors/',
						src: [
							'**/*.js',
							'**/*.css'
						],
						dest: BUILD_DIR + 'assets/vendors/'
					}
				]
			}
		},
		_watch: {
			options: {
				interval: 2000
			},
			all: {
				files: [
					SOURCE_DIR + '**',
					'!' + SOURCE_DIR + 'js/**/*.js',
					// Ignore version control directories.
					'!' + SOURCE_DIR + '**/.{svn,git}/**'
				],
				tasks: ['clean:dynamic', 'copy:dynamic'],
				options: {
					dot: true,
					spawn: false
				}
			},
			'js-enqueues': {
				files: [SOURCE_DIR + 'js/_enqueues/**/*.js'],
				tasks: ['clean:dynamic', 'copy:dynamic-js', 'uglify:dynamic', 'jsvalidate:dynamic'],
				options: {
					dot: true,
					spawn: false
				}
			},
			'js-webpack': {
				files: [
					SOURCE_DIR + 'js/**/*.js',
					'!' + SOURCE_DIR + 'js/_enqueues/**/*.js',
					'webpack-dev.config.js'
				],
				tasks: ['clean:dynamic', 'webpack:dev', 'uglify:dynamic', 'jsvalidate:dynamic'],
				options: {
					dot: true,
					spawn: false
				}
			},
			config: {
				files: [
					'Gruntfile.js',
					'webpack.config.js'
				]
			},
			assets: {
				files: [SOURCE_DIR + 'assets/css/**'],
				tasks: ['sass:app', 'sass:admin-bar']
			},
			rtl: {
				files: [
					SOURCE_DIR + 'assets/css/*.css',
					SOURCE_DIR + 'gc-admin/css/*.css',
					SOURCE_DIR + 'gc-includes/css/*.css'
				],
				tasks: ['rtlcss:dynamic'],
				options: {
					spawn: false
				}
			},
			test: {
				files: [
					'tests/qunit/**',
					'!tests/qunit/editor/**'
				],
				tasks: ['qunit']
			}
		}
	});

	// Allow builds to be minimal.
	if( grunt.option( 'minimal-copy' ) ) {
		var copyFilesOptions = grunt.config.get( 'copy.files.files' );
		copyFilesOptions[0].src.push( '!gc-content/plugins/**' );
		copyFilesOptions[0].src.push( '!gc-content/themes/!(default*)/**' );
		grunt.config.set( 'copy.files.files', copyFilesOptions );
	}


	// Register tasks.

	// Webpack task.
	grunt.loadNpmTasks( 'grunt-webpack' );

	// RTL task.
	grunt.registerTask('rtl', ['rtlcss:core']);

	// 编译样式表.
	grunt.registerTask('assets', ['sass:app', 'sass:admin-bar']);

	// JSHint task.
	grunt.registerTask( 'jshint:corejs', [
		'jshint:grunt',
		'jshint:tests',
		'jshint:themes',
		'jshint:core',
		'jshint:media'
	] );

	grunt.registerTask( 'restapi-jsclient', [
		'phpunit:restapi-jsclient',
		'qunit:compiled'
	] );

	grunt.renameTask( 'watch', '_watch' );

	grunt.registerTask( 'watch', function() {
		if ( ! this.args.length || this.args.indexOf( 'webpack' ) > -1 ) {
			grunt.task.run( 'build' );
		}

		if ( 'watch:phpunit' === grunt.cli.tasks[ 0 ] || 'undefined' !== typeof grunt.option( 'phpunit' ) ) {
			grunt.config.data._watch.phpunit = {
				files: [ '**/*.php' ],
				tasks: [ 'phpunit:default' ]
			};
		}

		grunt.task.run( '_' + this.nameArgs );
	} );

	grunt.registerTask( 'precommit:image', [
		'imagemin:core'
	] );

	grunt.registerTask( 'precommit:js', [
		'webpack:prod',
		'jshint:corejs',
		'uglify:imgareaselect',
		'uglify:jqueryform',
		'uglify:moment',
		'qunit:compiled'
	] );

	grunt.registerTask( 'precommit:css', [
		'postcss:core'
	] );

	grunt.registerTask( 'precommit:php', [
		'phpunit'
	] );

	grunt.registerTask( 'precommit:emoji', [
		'replace:emoji-regex'
	] );

	grunt.registerTask( 'precommit', 'Runs test and build tasks in preparation for a commit', function() {
		var done = this.async();
		var map = {
			svn: 'svn status --ignore-externals',
			git: 'git status --short'
		};

		find( [
			__dirname + '/.svn',
			__dirname + '/.git',
			path.dirname( __dirname ) + '/.svn'
		] );

		function find( set ) {
			var dir;

			if ( set.length ) {
				fs.stat( dir = set.shift(), function( error ) {
					error ? find( set ) : run( path.basename( dir ).substr( 1 ) );
				} );
			} else {
				runAllTasks();
			}
		}

		function runAllTasks() {
			grunt.log.writeln( 'Cannot determine which files are modified as SVN and GIT are not available.' );
			grunt.log.writeln( 'Running all tasks and all tests.' );
			grunt.task.run([
				'format:php',
				'precommit:js',
				'precommit:css',
				'precommit:image',
				'precommit:emoji',
				'precommit:php'
			]);

			done();
		}

		function run( type ) {
			var command = map[ type ].split( ' ' );

			grunt.util.spawn( {
				cmd: command.shift(),
				args: command
			}, function( error, result, code ) {
				var taskList = [];

				// Callback for finding modified paths.
				function testPath( path ) {
					var regex = new RegExp( ' ' + path + '$', 'm' );
					return regex.test( result.stdout );
				}

				// Callback for finding modified files by extension.
				function testExtension( extension ) {
					var regex = new RegExp( '\.' + extension + '$', 'm' );
					return regex.test( result.stdout );
				}

				if ( code === 0 ) {
					if ( [ 'package.json', 'Gruntfile.js', 'composer.json' ].some( testPath ) ) {
						grunt.log.writeln( 'Configuration files modified. Running `prerelease`.' );
						taskList.push( 'prerelease' );
					} else {
						if ( [ 'png', 'jpg', 'gif', 'jpeg' ].some( testExtension ) ) {
							grunt.log.writeln( 'Image files modified. Minifying.' );
							taskList.push( 'precommit:image' );
						}

						[ 'js', 'css', 'php' ].forEach( function( extension ) {
							if ( testExtension( extension ) ) {
								grunt.log.writeln( extension.toUpperCase() + ' files modified. ' + extension.toUpperCase() + ' tests will be run.' );
								taskList.push( 'precommit:' + extension );
							}
						} );

						if ( [ 'twemoji.js' ].some( testPath ) ) {
							grunt.log.writeln( 'twemoji.js has updated. Running `precommit:emoji.' );
							taskList.push( 'precommit:emoji' );
						}

						if ( testExtension( 'php' ) ) {
							grunt.log.writeln( 'PHP files modified. Code formatting will be run.' );
							var PHPfiles = result.stdout.split( '\n' );

							// Find .php files that have been modified or added.
							PHPfiles = PHPfiles.filter( function( file ) {
								return /^\s*[MA]\s*.*\.php$/.test( file );
							} );

							PHPfiles = PHPfiles.map( function( file ) {
								return file.replace( /^\s*[MA]\s*/, '' );
							} );

							changedFiles = {
								php: PHPfiles
							};

							taskList.push( 'format:php' );
						}
					}

					grunt.task.run( taskList );
					done();
				} else {
					runAllTasks();
				}
			} );
		}
	} );

	grunt.registerTask( 'copy:js', [
		'copy:npm-packages',
		'copy:vendor-js',
		'copy:assets-js',
		'copy:admin-js',
		'copy:includes-js'
	] );

	grunt.registerTask( 'uglify:all', [
		'uglify:core',
		'uglify:embed',
		'uglify:jquery-ui',
		'uglify:imgareaselect',
		'uglify:jqueryform',
		'uglify:moment'
	] );

	grunt.registerTask( 'build:webpack', [
		'clean:webpack-assets',
		'webpack:prod',
		'webpack:dev',
	] );

	grunt.registerTask( 'build:js', [
		'clean:js',
		'build:webpack',
		'copy:js',
		'file_append',
		'uglify:all',
		'concat:tinymce',
		'concat:emoji',
		'jsvalidate:build'
	] );

	grunt.registerTask( 'build:css', [
		'clean:css',
		'copy:gc-admin-css-compat-rtl',
		'copy:gc-admin-css-compat-min',
		'assets',
		'rtl',
		'cssmin:core',
		'cssmin:rtl',
		'usebanner'
	] );

	grunt.registerTask( 'build:files', [
		'clean:files',
		'copy:files',
		'copy:version',
	] );

	/**
	 * Build verification tasks.
	 */
	grunt.registerTask( 'verify:build', [
		'verify:gc-embed',
		'verify:old-files',
		'verify:source-maps',
	] );

	/**
	 * Build assertions for gc-embed.min.js.
	 *
	 * @ticket 34698
	 */
	grunt.registerTask( 'verify:gc-embed', function() {
		const file = `${ BUILD_DIR }/gc-includes/js/gc-embed.min.js`;

		assert(
			fs.existsSync( file ),
			'The build/gc-includes/js/gc-embed.min.js file does not exist.'
		);

		const contents = fs.readFileSync( file, {
			encoding: 'utf8',
		} );

		assert(
			contents.length > 0,
			'The build/gc-includes/js/gc-embed.min.js file must not be empty.'
		);
		assert(
			false === contents.includes( '&' ),
			'The build/gc-includes/js/gc-embed.min.js file must not contain ampersands.'
		);
	} );

	/**
	 * Build assertions to ensure no project files are inside `$_old_files` in the build directory.
	 *
	 * @ticket 36083
	 */
	grunt.registerTask( 'verify:old-files', function() {
		const file = `${ BUILD_DIR }gc-admin/includes/update-core.php`;

		assert(
			fs.existsSync( file ),
			'The build/gc-admin/includes/update-core.php file does not exist.'
		);

		const contents = fs.readFileSync( file, {
			encoding: 'utf8',
		} );

		assert(
			contents.length > 0,
			'The build/gc-admin/includes/update-core.php file must not be empty.'
		);

		const match = contents.match( /\$_old_files = array\(([^\)]+)\);/ );

		assert(
			match.length > 0,
			'The build/gc-admin/includes/update-core.php file does not include an `$_old_files` array.'
		);

		const files = match[1].split( '\n\t' ).filter( function( file ) {
			// Filter out empty lines
			if ( '' === file ) {
				return false;
			}

			// Filter out commented out lines
			if ( 0 === file.indexOf( '/' ) ) {
				return false;
			}

			return true;
		} ).map( function( file ) {
			// Strip leading and trailing single quotes and commas
			return file.replace( /^\'|\',$/g, '' );
		} );

		files.forEach(function( file ){
			const search = `${ BUILD_DIR }${ file }`;
			assert(
				false === fs.existsSync( search ),
				`${ search } should not be present in the $_old_files array.`
			);
		});
	} );

	/**
	 * Build assertions for the lack of source maps in JavaScript files.
	 *
	 * @ticket 24994
	 * @ticket 46218
	 */
	grunt.registerTask( 'verify:source-maps', function() {
		const ignoredFiles = [
			'build/gc-includes/js/dist/components.js'
		];
		const files = buildFiles.reduce( ( acc, path ) => {
			// Skip excluded paths and any path that isn't a file.
			if ( '!' === path[0] || '**' !== path.substr( -2 ) ) {
				return acc;
			}
			acc.push( ...glob.sync( `${ BUILD_DIR }/${ path }/*.js` ) );
			return acc;
		}, [] );

		assert(
			files.length > 0,
			'No JavaScript files found in the build directory.'
		);

		files
			.filter(file => ! ignoredFiles.includes( file) )
			.forEach( function( file ) {
				const contents = fs.readFileSync( file, {
					encoding: 'utf8',
				} );
				// `data:` URLs are allowed:
				const match = contents.match( /sourceMappingURL=((?!data:).)/ );

				assert(
					match === null,
					`这个 ${ file } 文件中，不能包含资源地图语句sourceMappingURL.`
				);
			} );
	} );

	/**
	 * 执行命令汇总到这里统一执行
	 * npm run build:dev  编译JS+CSS文件
	 * npm run build
	*/
	grunt.registerTask( 'build', function() {
		if ( grunt.option( 'dev' ) ) {
			grunt.task.run( [
				'build:js',
				'build:css',
			] );
		} else {
			grunt.task.run( [
				'build:files',
				'build:js',
				'build:css',
				'replace:source-maps',
				'verify:build'
			] );
		}
	} );

	grunt.registerTask( 'prerelease', [
		'format:php:error',
		'precommit:php',
		'precommit:js',
		'precommit:css',
		'precommit:image'
	] );

	// Testing tasks.
	grunt.registerMultiTask( 'phpunit', 'Runs PHPUnit tests, including the ajax, external-http, and multisite tests.', function() {
		var args = phpUnitWatchGroup ? this.data.args.concat( [ '--group', phpUnitWatchGroup ] ) : this.data.args;

		args.unshift( 'test', '--' );

		grunt.util.spawn({
			cmd: 'composer',
			args: args,
			opts: { stdio: 'inherit' }
		}, this.async());
	});

	grunt.registerTask( 'qunit:compiled', 'Runs QUnit tests on compiled as well as uncompiled scripts.',
		['build', 'copy:qunit', 'qunit']
	);

	grunt.registerTask( 'test', 'Runs all QUnit and PHPUnit tasks.', ['qunit:compiled', 'phpunit'] );

	grunt.registerTask( 'format:php', 'Runs the code formatter on changed files.', function() {
		var done = this.async();
		var flags = this.flags;
		var args = changedFiles.php;

		args.unshift( 'format' );

		grunt.util.spawn( {
			cmd: 'composer',
			args: args,
			opts: { stdio: 'inherit' }
		}, function( error ) {
			if ( flags.error && error ) {
				done( false );
			} else {
				done( true );
			}
		} );
	} );

	grunt.registerTask( 'lint:php', 'Runs the code linter on changed files.', function() {
		var done = this.async();
		var flags = this.flags;
		var args = changedFiles.php;

		args.unshift( 'lint' );

		grunt.util.spawn( {
			cmd: 'composer',
			args: args,
			opts: { stdio: 'inherit' }
		}, function( error ) {
			if ( flags.error && error ) {
				done( false );
			} else {
				done( true );
			}
		} );
	} );

	// Patch task.
	grunt.renameTask('patch_gechiui', 'patch');

	// Add an alias `apply` of the `patch` task name.
	grunt.registerTask('apply', 'patch');

	// Default task.
	grunt.registerTask('default', ['build']);

	/*
	 * Automatically updates the `:dynamic` configurations
	 * so that only the changed files are updated.
	 */
	grunt.event.on( 'watch', function( action, filepath, target ) {
		var src;

		// Only configure the dynamic tasks based on known targets.
		if ( [ 'all', 'rtl', 'webpack', 'js-enqueues', 'js-webpack' ].indexOf( target ) === -1 ) {
			return;
		}

		// Normalize filepath for Windows.
		filepath = filepath.replace( /\\/g, '/' );

		// If the target is a file in the restructured js src.
		if ( target === 'js-enqueues' ) {
			var files = {};
			var configs, dest;

			// If it's a vendor file which are configured with glob matchers.
			if ( filepath.indexOf( SOURCE_DIR + 'js/_enqueues/vendor/' ) === 0 ) {
				// Grab the glob matchers from the copy task.
				configs = grunt.config( [ 'copy', 'vendor-js', 'files' ] );

				// For each glob matcher check if it matches and if so set the variables for our dynamic tasks.
				for ( var i = 0; i < configs.length; i++ ) {
					var config = configs[ i ];
					var relative = path.relative( config.cwd, filepath );
					var minimatch = require('minimatch');

					if ( minimatch.match( config.src, relative, {} ) ) {
						dest = config.dest + relative;
						src = [ path.relative( WORKING_DIR, dest ) ];
						files[ dest ] = [ filepath ];
						break;
					}
				}
			// Or if it's another file which has a straight mapping.
			} else {
				configs = Object.assign( {},
					grunt.config( [ 'copy', 'assets-js', 'files' ] ),
					grunt.config( [ 'copy', 'admin-js', 'files' ] ),
					grunt.config( [ 'copy', 'includes-js', 'files' ] )
				);

				for ( dest in configs ) {
					// If a file in the mapping matches then set the variables for our dynamic tasks.
					if ( dest && configs.hasOwnProperty( dest ) && configs[ dest ][0] === './' + filepath ) {
						files[ dest ] = configs[ dest ];
						src = [ path.relative( WORKING_DIR, dest ) ];
						break;
					}
				}
			}

			// Configure our dynamic-js copy task which uses a file mapping rather than simply copying from src to build.
			if ( action !== 'deleted' ) {
				grunt.config( [ 'copy', 'dynamic-js', 'files' ], files );
			}
		// For the webpack builds configure the jsvalidate task to only check those files build by webpack.
		} else if ( target === 'js-webpack' ) {
			src = [
				'gc-includes/js/media-audiovideo.js',
				'gc-includes/js/media-grid.js',
				'gc-includes/js/media-models.js',
				'gc-includes/js/media-views.js'
			];
		// Else simply use the path relative to the source directory.
		} else {
			src = [ path.relative( SOURCE_DIR, filepath ) ];
		}

		if ( ! src ) {
			grunt.warn( 'Failed to determine the destination file.' );
			return;
		}

		if ( action === 'deleted' ) {
			// Clean up only those files that were deleted.
			grunt.config( [ 'clean', 'dynamic', 'src' ], src );
		} else {
			// Otherwise copy over only the changed file.
			grunt.config( [ 'copy', 'dynamic', 'src' ], src );

			// For javascript also minify and validate the changed file.
			if ( target === 'js-enqueues' ) {
				grunt.config( [ 'uglify', 'dynamic', 'src' ], src );
				grunt.config( [ 'jsvalidate', 'dynamic', 'files', 'src' ], src.map( function( dir ) { return  WORKING_DIR + dir; } ) );
			}
			// For webpack only validate the file, minification is handled by webpack itself.
			if ( target === 'js-webpack' ) {
				grunt.config( [ 'jsvalidate', 'dynamic', 'files', 'src' ], src.map( function( dir ) { return  WORKING_DIR + dir; } ) );
			}
			// For css run the rtl task on just the changed file.
			if ( target === 'rtl' ) {
				grunt.config( [ 'rtlcss', 'dynamic', 'src' ], src );
			}
		}
	});
};
