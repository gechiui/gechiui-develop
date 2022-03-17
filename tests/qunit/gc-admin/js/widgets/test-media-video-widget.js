/* globals gc */
/* jshint qunit: true */
/* eslint-env qunit */
/* eslint-disable no-magic-numbers */

( function() {
	'use strict';

	QUnit.module( 'Video Media Widget' );

	QUnit.test( 'video widget control', function( assert ) {
		var VideoWidgetControl, videoWidgetControlInstance, videoWidgetModelInstance, mappedProps, testVideoUrl;
		testVideoUrl = 'https://videos.files.gechiui.com/AHz0Ca46/gc4-7-vaughan-r8-mastered_hd.mp4';
		assert.equal( typeof gc.mediaWidgets.controlConstructors.media_video, 'function', 'gc.mediaWidgets.controlConstructors.media_video is a function' );
		VideoWidgetControl = gc.mediaWidgets.controlConstructors.media_video;
		assert.ok( VideoWidgetControl.prototype instanceof gc.mediaWidgets.MediaWidgetControl, 'gc.mediaWidgets.controlConstructors.media_video subclasses gc.mediaWidgets.MediaWidgetControl' );

		videoWidgetModelInstance = new gc.mediaWidgets.modelConstructors.media_video();
		videoWidgetControlInstance = new VideoWidgetControl({
			el: jQuery( '<div></div>' ),
			syncContainer: jQuery( '<div></div>' ),
			model: videoWidgetModelInstance
		});

		// Test mapModelToMediaFrameProps().
		videoWidgetControlInstance.model.set({ error: false, url: testVideoUrl, loop: false, preload: 'meta' });
		mappedProps = videoWidgetControlInstance.mapModelToMediaFrameProps( videoWidgetControlInstance.model.toJSON() );
		assert.equal( mappedProps.url, testVideoUrl, 'mapModelToMediaFrameProps should set url' );
		assert.equal( mappedProps.loop, false, 'mapModelToMediaFrameProps should set loop' );
		assert.equal( mappedProps.preload, 'meta', 'mapModelToMediaFrameProps should set preload' );

		// Test mapMediaToModelProps().
		mappedProps = videoWidgetControlInstance.mapMediaToModelProps( { loop: false, preload: 'meta', url: testVideoUrl, title: 'random movie file title' } );
		assert.equal( mappedProps.title, undefined, 'mapMediaToModelProps should ignore title inputs' );
		assert.equal( mappedProps.loop, false, 'mapMediaToModelProps should set loop' );
		assert.equal( mappedProps.preload, 'meta', 'mapMediaToModelProps should set preload' );
	});

	QUnit.test( 'video widget control renderPreview', function( assert ) {
		var videoWidgetControlInstance, videoWidgetModelInstance, done;
		done = assert.async();

		videoWidgetModelInstance = new gc.mediaWidgets.modelConstructors.media_video();
		videoWidgetControlInstance = new gc.mediaWidgets.controlConstructors.media_video({
			el: jQuery( '<div></div>' ),
			syncContainer: jQuery( '<div></div>' ),
			model: videoWidgetModelInstance
		});
		assert.equal( videoWidgetControlInstance.$el.find( 'a' ).length, 0, 'No video links should be rendered' );
		videoWidgetControlInstance.model.set({ error: false, url: 'https://videos.files.gechiui.com/AHz0Ca46/gc4-7-vaughan-r8-mastered_hd.mp4' });

		// Due to renderPreview being deferred.
		setTimeout( function() {
			assert.equal( videoWidgetControlInstance.$el.find( 'a[href="https://videos.files.gechiui.com/AHz0Ca46/gc4-7-vaughan-r8-mastered_hd.mp4"]' ).length, 1, 'One video link should be rendered' );
			done();
		}, 50 );

		done();
	});

	QUnit.test( 'video media model', function( assert ) {
		var VideoWidgetModel, videoWidgetModelInstance;
		assert.equal( typeof gc.mediaWidgets.modelConstructors.media_video, 'function', 'gc.mediaWidgets.modelConstructors.media_video is a function' );
		VideoWidgetModel = gc.mediaWidgets.modelConstructors.media_video;
		assert.ok( VideoWidgetModel.prototype instanceof gc.mediaWidgets.MediaWidgetModel, 'gc.mediaWidgets.modelConstructors.media_video subclasses gc.mediaWidgets.MediaWidgetModel' );

		videoWidgetModelInstance = new VideoWidgetModel();
		_.each( videoWidgetModelInstance.attributes, function( value, key ) {
			assert.equal( value, VideoWidgetModel.prototype.schema[ key ][ 'default' ], 'Should properly set default for ' + key );
		});
	});

})();
