/* global gc */
/* jshint qunit: true */
/* eslint-env qunit */
/* eslint-disable no-magic-numbers */

( function() {
	'use strict';

	QUnit.module( 'Gallery Media Widget' );

	QUnit.test( 'gallery widget control', function( assert ) {
		var GalleryWidgetControl;
		assert.equal( typeof gc.mediaWidgets.controlConstructors.media_gallery, 'function', 'gc.mediaWidgets.controlConstructors.media_gallery is a function' );
		GalleryWidgetControl = gc.mediaWidgets.controlConstructors.media_gallery;
		assert.ok( GalleryWidgetControl.prototype instanceof gc.mediaWidgets.MediaWidgetControl, 'gc.mediaWidgets.controlConstructors.media_gallery subclasses gc.mediaWidgets.MediaWidgetControl' );
	});

	QUnit.test( 'gallery media model', function( assert ) {
		var GalleryWidgetModel, galleryWidgetModelInstance;
		assert.equal( typeof gc.mediaWidgets.modelConstructors.media_gallery, 'function', 'gc.mediaWidgets.modelConstructors.media_gallery is a function' );
		GalleryWidgetModel = gc.mediaWidgets.modelConstructors.media_gallery;
		assert.ok( GalleryWidgetModel.prototype instanceof gc.mediaWidgets.MediaWidgetModel, 'gc.mediaWidgets.modelConstructors.media_gallery subclasses gc.mediaWidgets.MediaWidgetModel' );

		galleryWidgetModelInstance = new GalleryWidgetModel();
		_.each( galleryWidgetModelInstance.attributes, function( value, key ) {
			assert.equal( value, GalleryWidgetModel.prototype.schema[ key ][ 'default' ], 'Should properly set default for ' + key );
		});
	});

})();
