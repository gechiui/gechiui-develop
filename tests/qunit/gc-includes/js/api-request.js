/* global gc, JSON */
( function( QUnit ) {
	var originalRootUrl = window.gcApiSettings.root;

	var expectedHeaders = {
		'X-GC-Nonce': 'not_a_real_nonce',
		'Accept': 'application/json, */*;q=0.1'
	};

	QUnit.module( 'gc-api-request', {
		afterEach: function() {
			window.gcApiSettings.root = originalRootUrl;
		}
	} );

	QUnit.test( 'does not mutate original object', function( assert ) {
		var settingsOriginal = {
			url: 'aaaaa',
			path: 'gc/v2/posts',
			headers: {
				'Header-Name': 'value'
			},
			data: {
				orderby: 'something'
			}
		};

		var settings = gc.apiRequest.buildAjaxOptions( settingsOriginal );

		assert.notStrictEqual( settings, settingsOriginal );
		assert.notStrictEqual( settings.headers, settingsOriginal.headers );
		assert.strictEqual( settings.data, settingsOriginal.data );

		assert.deepEqual( settings, {
			url: 'http://localhost/gc-json/gc/v2/posts',
			headers: {
				'X-GC-Nonce': 'not_a_real_nonce',
				'Accept': 'application/json, */*;q=0.1',
				'Header-Name': 'value'
			},
			data: {
				orderby: 'something'
			}
		} );

		assert.deepEqual( settingsOriginal, {
			url: 'aaaaa',
			path: 'gc/v2/posts',
			headers: {
				'Header-Name': 'value'
			},
			data: {
				orderby: 'something'
			}
		} );
	} );

	QUnit.test( 'does not add nonce header if already present', function( assert ) {
		[ 'X-GC-Nonce', 'x-gc-nonce', 'X-GC-NONCE' ].forEach( function( headerName ) {
			var nonceHeader = {};
			nonceHeader[ headerName ] = 'still_not_a_real_nonce';

			var settingsOriginal = {
				url: 'aaaa',
				headers: JSON.parse( JSON.stringify( nonceHeader ) )
			};

			var settings = gc.apiRequest.buildAjaxOptions( settingsOriginal );

			assert.notStrictEqual( settings, settingsOriginal );

			var expected = {
				Accept: 'application/json, */*;q=0.1'
			};
			expected[ headerName ] = nonceHeader[ headerName ];

			assert.deepEqual( settings, {
				url: 'aaaa',
				headers: expected
			} );
		} );
	} );

	QUnit.test( 'does not add nonce header if ?_gcnonce=... present', function( assert ) {
		var settingsOriginal = {
			url: 'aaaa',
			data: {
				_gcnonce: 'definitely_not_a_real_nonce'
			}
		};

		var settings = gc.apiRequest.buildAjaxOptions( settingsOriginal );

		assert.notStrictEqual( settings, settingsOriginal );

		assert.deepEqual( settings, {
			url: 'aaaa',
			headers: {
				'Accept': 'application/json, */*;q=0.1'
			},
			data: {
				_gcnonce: 'definitely_not_a_real_nonce'
			}
		} );
	} );

	QUnit.test( 'does not add accept header if already present', function( assert ) {
		var settingsOriginal = {
			url: 'aaaa',
			headers: {
				'Accept': 'text/xml'
			}
		};

		var settings = gc.apiRequest.buildAjaxOptions( settingsOriginal );

		assert.strictEqual( settingsOriginal.headers.Accept, settings.headers.Accept );
	} );

	QUnit.test( 'accepts namespace and endpoint', function( assert ) {
		assert.deepEqual( gc.apiRequest.buildAjaxOptions( {
			namespace: 'gc/v2',
			endpoint: 'posts'
		} ), {
			url: 'http://localhost/gc-json/gc/v2/posts',
			headers: expectedHeaders
		} );
	} );

	QUnit.test( 'accepts namespace and endpoint with slashes', function( assert ) {
		assert.deepEqual( gc.apiRequest.buildAjaxOptions( {
			namespace: '/gc/v2/',
			endpoint: '/posts'
		} ), {
			url: 'http://localhost/gc-json/gc/v2/posts',
			headers: expectedHeaders
		} );
	} );

	QUnit.test( 'accepts namespace and empty endpoint', function( assert ) {
		assert.deepEqual( gc.apiRequest.buildAjaxOptions( {
			namespace: 'gc/v2',
			endpoint: ''
		} ), {
			url: 'http://localhost/gc-json/gc/v2',
			headers: expectedHeaders
		} );
	} );

	QUnit.test( 'accepts empty namespace and empty endpoint', function( assert ) {
		assert.deepEqual( gc.apiRequest.buildAjaxOptions( {
			namespace: '',
			endpoint: ''
		} ), {
			url: 'http://localhost/gc-json/',
			headers: expectedHeaders
		} );
	} );

	QUnit.test(
		'accepts namespace and endpoint with slashes (plain permalinks)',
		function( assert ) {
			window.gcApiSettings.root = 'http://localhost/index.php?rest_route=/';
			assert.deepEqual( gc.apiRequest.buildAjaxOptions( {
				namespace: '/gc/v2/',
				endpoint: '/posts?orderby=title'
			} ), {
				url: 'http://localhost/index.php?rest_route=/gc/v2/posts&orderby=title',
				headers: expectedHeaders
			} );
		}
	);

	QUnit.test(
		'accepts empty namespace and empty endpoint (plain permalinks)',
		function( assert ) {
			window.gcApiSettings.root = 'http://localhost/index.php?rest_route=/';
			assert.deepEqual( gc.apiRequest.buildAjaxOptions( {
				namespace: '',
				endpoint: ''
			} ), {
				url: 'http://localhost/index.php?rest_route=/',
				headers: expectedHeaders
			} );
		}
	);
} )( window.QUnit );
