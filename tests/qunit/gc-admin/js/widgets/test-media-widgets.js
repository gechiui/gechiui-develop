/* globals gc, Backbone */
/* jshint qunit: true */
/* eslint-env qunit */

( function() {
	'use strict';

	QUnit.module( 'Media Widgets' );

	QUnit.test( 'namespace', function( assert ) {
		assert.equal( typeof gc.mediaWidgets, 'object', 'gc.mediaWidgets is an object' );
		assert.equal( typeof gc.mediaWidgets.controlConstructors, 'object', 'gc.mediaWidgets.controlConstructors is an object' );
		assert.equal( typeof gc.mediaWidgets.modelConstructors, 'object', 'gc.mediaWidgets.modelConstructors is an object' );
		assert.equal( typeof gc.mediaWidgets.widgetControls, 'object', 'gc.mediaWidgets.widgetControls is an object' );
		assert.equal( typeof gc.mediaWidgets.handleWidgetAdded, 'function', 'gc.mediaWidgets.handleWidgetAdded is an function' );
		assert.equal( typeof gc.mediaWidgets.handleWidgetUpdated, 'function', 'gc.mediaWidgets.handleWidgetUpdated is an function' );
		assert.equal( typeof gc.mediaWidgets.init, 'function', 'gc.mediaWidgets.init is an function' );
	});

	QUnit.test( 'media widget control', function( assert ) {
		assert.equal( typeof gc.mediaWidgets.MediaWidgetControl, 'function', 'gc.mediaWidgets.MediaWidgetControl' );
		assert.ok( gc.mediaWidgets.MediaWidgetControl.prototype instanceof Backbone.View, 'gc.mediaWidgets.MediaWidgetControl subclasses Backbone.View' );
	});

	QUnit.test( 'media widget model', function( assert ) {
		var widgetModelInstance;
		assert.equal( typeof gc.mediaWidgets.MediaWidgetModel, 'function', 'gc.mediaWidgets.MediaWidgetModel is a function' );
		assert.ok( gc.mediaWidgets.MediaWidgetModel.prototype instanceof Backbone.Model, 'gc.mediaWidgets.MediaWidgetModel subclasses Backbone.Model' );

		widgetModelInstance = new gc.mediaWidgets.MediaWidgetModel();
		assert.equal( widgetModelInstance.get( 'title' ), '', 'gc.mediaWidgets.MediaWidgetModel defaults title to empty string' );
		assert.equal( widgetModelInstance.get( 'attachment_id' ), 0, 'gc.mediaWidgets.MediaWidgetModel defaults attachment_id to 0' );
		assert.equal( widgetModelInstance.get( 'url' ), 0, 'gc.mediaWidgets.MediaWidgetModel defaults url to empty string' );

		widgetModelInstance.set({
			title: 'chicken and ribs',
			attachment_id: '1',
			url: 'https://www.gechiui.com'
		});
		assert.equal( widgetModelInstance.get( 'title' ), 'chicken and ribs', 'gc.mediaWidgets.MediaWidgetModel properly sets the title attribute' );
		assert.equal( widgetModelInstance.get( 'url' ), 'https://www.gechiui.com', 'gc.mediaWidgets.MediaWidgetModel properly sets the url attribute' );
		assert.equal( widgetModelInstance.get( 'attachment_id' ), 1, 'gc.mediaWidgets.MediaWidgetModel properly sets and casts the attachment_id attribute' );
	});

})();
