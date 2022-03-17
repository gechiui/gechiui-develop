var AttachmentDisplay = gc.media.view.Settings.AttachmentDisplay,
	EmbedImage;

/**
 * gc.media.view.EmbedImage
 *
 * @memberOf gc.media.view
 *
 * @class
 * @augments gc.media.view.Settings.AttachmentDisplay
 * @augments gc.media.view.Settings
 * @augments gc.media.View
 * @augments gc.Backbone.View
 * @augments Backbone.View
 */
EmbedImage = AttachmentDisplay.extend(/** @lends gc.media.view.EmbedImage.prototype */{
	className: 'embed-media-settings',
	template:  gc.template('embed-image-settings'),

	initialize: function() {
		/**
		 * Call `initialize` directly on parent class with passed arguments
		 */
		AttachmentDisplay.prototype.initialize.apply( this, arguments );
		this.listenTo( this.model, 'change:url', this.updateImage );
	},

	updateImage: function() {
		this.$('img').attr( 'src', this.model.get('url') );
	}
});

module.exports = EmbedImage;
