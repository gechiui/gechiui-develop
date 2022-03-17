/**
 * gc.media.controller.MediaLibrary
 *
 * @memberOf gc.media.controller
 *
 * @class
 * @augments gc.media.controller.Library
 * @augments gc.media.controller.State
 * @augments Backbone.Model
 */
var Library = gc.media.controller.Library,
	MediaLibrary;

MediaLibrary = Library.extend(/** @lends gc.media.controller.MediaLibrary.prototype */{
	defaults: _.defaults({
		// Attachments browser defaults. @see media.view.AttachmentsBrowser
		filterable:      'uploaded',

		displaySettings: false,
		priority:        80,
		syncSelection:   false
	}, Library.prototype.defaults ),

	/**
	 *
	 * @param options
	 */
	initialize: function( options ) {
		this.media = options.media;
		this.type = options.type;
		this.set( 'library', gc.media.query({ type: this.type }) );

		Library.prototype.initialize.apply( this, arguments );
	},

	/**
	 */
	activate: function() {
		// @todo this should use this.frame.
		if ( gc.media.frame.lastMime ) {
			this.set( 'library', gc.media.query({ type: gc.media.frame.lastMime }) );
			delete gc.media.frame.lastMime;
		}
		Library.prototype.activate.apply( this, arguments );
	}
});

module.exports = MediaLibrary;
