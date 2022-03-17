/*global QUnit, gc, sinon */
jQuery( function( $ ) {

	QUnit.module( 'gc.updates' );

	QUnit.test( 'Initially, the update lock should be false', function( assert ) {
		assert.strictEqual( gc.updates.ajaxLocked, false );
	});

	QUnit.test( 'The nonce should be set correctly', function( assert ) {
		assert.equal( gc.updates.ajaxNonce, window._gcUpdatesSettings.ajax_nonce );
	});

	QUnit.test( 'decrementCount correctly decreases the update number', function( assert ) {
		var menuItemCount  = $( '#menu-plugins' ).find( '.plugin-count' ).eq( 0 ).text();
		var screenReaderItemCount = $( '#gc-admin-bar-updates' ).find( '.screen-reader-text' ).text();
		var adminItemCount = $( '#gc-admin-bar-updates' ).find( '.ab-label' ).text();
		assert.equal( menuItemCount, 2, 'Intial value is correct' );
		assert.equal( screenReaderItemCount, '2 Plugin Updates', 'Intial value is correct' );
		assert.equal( adminItemCount, 2, 'Intial value is correct' );

		gc.updates.decrementCount( 'plugin' );

		// Re-read these values.
		menuItemCount  = $( '#menu-plugins' ).find( '.plugin-count' ).eq( 0 ).text();
		screenReaderItemCount = $( '#gc-admin-bar-updates' ).find( '.screen-reader-text' ).text();
		adminItemCount = $( '#gc-admin-bar-updates' ).find( '.ab-label' ).text();
		assert.equal( menuItemCount, 1 );

		// @todo: Update screen reader count.
		// Should the screenReader count change? Is that announced to the user?
		// assert.equal( screenReaderItemCount, '1 Plugin Update' );
		assert.equal( adminItemCount, 1 );
	});

	QUnit.test( '`beforeunload` should only fire when locked', function( assert ) {
		gc.updates.ajaxLocked = false;
		assert.notOk( gc.updates.beforeunload(), '`beforeunload` should not fire.' );
		gc.updates.ajaxLocked = true;
		assert.equal( gc.updates.beforeunload(), window._gcUpdatesSettings.l10n.beforeunload, '`beforeunload` should equal the localized `beforeunload` string.' );
		gc.updates.ajaxLocked = false;
	});

	// FTP creds... exist?
	// Admin notice?

	QUnit.module( 'gc.updates.plugins', {
		beforeEach: function() {
			this.oldPagenow = window.pagenow;
			window.pagenow = 'plugins';
			sinon.spy( jQuery, 'ajax' );
		},
		afterEach: function() {
			window.pagenow = this.oldPagenow;
			gc.updates.ajaxLocked = false;
			gc.updates.queue = [];
			jQuery.ajax.restore();
		}
	} );

	QUnit.test( 'Update lock is set when plugins are updating', function( assert ) {
		gc.updates.updatePlugin( {
			plugin: 'test/test.php',
			slug: 'test'
		} );
		assert.strictEqual( gc.updates.ajaxLocked, true );
	});

	QUnit.test( 'Plugins are queued when the lock is set', function( assert ) {
		var value = [
			{
				action: 'update-plugin',
				data: {
					plugin: 'test/test.php',
					slug: 'test',
					success: null,
					error: null
				}
			}
		];

		gc.updates.ajaxLocked = true;
		gc.updates.updatePlugin( {
			plugin: 'test/test.php',
			slug: 'test',
			success: null,
			error: null
		} );

		assert.deepEqual( gc.updates.queue, value );
	});

	QUnit.test( 'If plugins are installing (lock is set), the beforeUnload function should fire', function( assert ) {
		gc.updates.updatePlugin( {
			plugin: 'test/test.php',
			slug: 'test'
		} );
		assert.equal( gc.updates.beforeunload(), window._gcUpdatesSettings.l10n.beforeunload );
	} );

	QUnit.test( 'Starting a plugin update should call the update API', function( assert ) {
		gc.updates.updatePlugin( {
			plugin: 'test/test.php',
			slug: 'test'
		} );
		assert.ok( jQuery.ajax.calledOnce );
		assert.equal( jQuery.ajax.getCall( 0 ).args[0].url, '/gc-admin/admin-ajax.php' );
		assert.equal( jQuery.ajax.getCall( 0 ).args[0].data.action, 'update-plugin' );
		assert.equal( jQuery.ajax.getCall( 0 ).args[0].data.slug, 'test' );
	} );
	QUnit.test( 'Installing a plugin should call the API', function( assert ) {
		gc.updates.installPlugin( { slug: 'jetpack' } );
		assert.ok( jQuery.ajax.calledOnce );
		assert.equal( jQuery.ajax.getCall( 0 ).args[0].url, '/gc-admin/admin-ajax.php' );
		assert.equal( jQuery.ajax.getCall( 0 ).args[0].data.action, 'install-plugin' );
		assert.equal( jQuery.ajax.getCall( 0 ).args[0].data.slug, 'jetpack' );
	} );
	QUnit.test( 'Deleting a plugin should call the API', function( assert ) {
		gc.updates.deletePlugin( { slug: 'jetpack', plugin: 'jetpack/jetpack.php' } );
		assert.ok( jQuery.ajax.calledOnce );
		assert.equal( jQuery.ajax.getCall( 0 ).args[0].url, '/gc-admin/admin-ajax.php' );
		assert.equal( jQuery.ajax.getCall( 0 ).args[0].data.action, 'delete-plugin' );
		assert.equal( jQuery.ajax.getCall( 0 ).args[0].data.slug, 'jetpack' );
	} );

	// QUnit.test( 'A successful update changes the message?', function( assert ) {} );
	// QUnit.test( 'A failed update changes the message?', function( assert ) {} );

	QUnit.module( 'gc.updates.themes', {
		beforeEach: function() {
			this.oldPagenow = window.pagenow;
			window.pagenow = 'themes';
			sinon.spy( jQuery, 'ajax' );
		},
		afterEach: function() {
			window.pagenow = this.oldPagenow;
			gc.updates.ajaxLocked = false;
			gc.updates.queue = [];
			jQuery.ajax.restore();
		}
	} );

	QUnit.test( 'Update lock is set when themes are updating', function( assert ) {
		gc.updates.updateTheme( 'twentyeleven' );
		assert.strictEqual( gc.updates.ajaxLocked, true );
	});

	QUnit.test( 'If themes are installing (lock is set), the beforeUnload function should fire', function( assert ) {
		gc.updates.updateTheme( { slug: 'twentyeleven' } );
		assert.equal( gc.updates.beforeunload(), window._gcUpdatesSettings.l10n.beforeunload );
	} );

	QUnit.test( 'Starting a theme update should call the update API', function( assert ) {
		gc.updates.updateTheme( { slug: 'twentyeleven' } );
		assert.ok( jQuery.ajax.calledOnce );
		assert.equal( jQuery.ajax.getCall( 0 ).args[0].url, '/gc-admin/admin-ajax.php' );
		assert.equal( jQuery.ajax.getCall( 0 ).args[0].data.action, 'update-theme' );
		assert.equal( jQuery.ajax.getCall( 0 ).args[0].data.slug, 'twentyeleven' );
	} );

	QUnit.test( 'Installing a theme should call the API', function( assert ) {
		gc.updates.installTheme( { slug: 'twentyeleven' } );
		assert.ok( jQuery.ajax.calledOnce );
		assert.equal( jQuery.ajax.getCall( 0 ).args[0].url, '/gc-admin/admin-ajax.php' );
		assert.equal( jQuery.ajax.getCall( 0 ).args[0].data.action, 'install-theme' );
		assert.equal( jQuery.ajax.getCall( 0 ).args[0].data.slug, 'twentyeleven' );
	} );

	QUnit.test( 'Deleting a theme should call the API', function( assert ) {
		gc.updates.deleteTheme( { slug: 'twentyeleven' } );
		assert.ok( jQuery.ajax.calledOnce );
		assert.equal( jQuery.ajax.getCall( 0 ).args[0].url, '/gc-admin/admin-ajax.php' );
		assert.equal( jQuery.ajax.getCall( 0 ).args[0].data.action, 'delete-theme' );
		assert.equal( jQuery.ajax.getCall( 0 ).args[0].data.slug, 'twentyeleven' );
	} );

	// QUnit.test( 'A successful update changes the message?', function( assert ) {} );
	// QUnit.test( 'A failed update changes the message?', function( assert ) {} );
});
