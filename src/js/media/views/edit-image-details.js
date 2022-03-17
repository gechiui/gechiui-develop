var View = gc.media.View,
	EditImage = gc.media.view.EditImage,
	Details;

/**
 * gc.media.view.EditImage.Details
 *
 * @memberOf gc.media.view.EditImage
 *
 * @class
 * @augments gc.media.view.EditImage
 * @augments gc.media.View
 * @augments gc.Backbone.View
 * @augments Backbone.View
 */
Details = EditImage.extend(/** @lends gc.media.view.EditImage.Details.prototype */{
	initialize: function( options ) {
		this.editor = window.imageEdit;
		this.frame = options.frame;
		this.controller = options.controller;
		View.prototype.initialize.apply( this, arguments );
	},

	back: function() {
		this.frame.content.mode( 'edit-metadata' );
	},

	save: function() {
		this.model.fetch().done( _.bind( function() {
			this.frame.content.mode( 'edit-metadata' );
		}, this ) );
	}
});

module.exports = Details;
