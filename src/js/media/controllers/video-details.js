/**
 * gc.media.controller.VideoDetails
 *
 * The controller for the Video Details state
 *
 * @memberOf gc.media.controller
 *
 * @class
 * @augments gc.media.controller.State
 * @augments Backbone.Model
 */
var State = gc.media.controller.State,
	l10n = gc.media.view.l10n,
	VideoDetails;

VideoDetails = State.extend(/** @lends gc.media.controller.VideoDetails.prototype */{
	defaults: {
		id: 'video-details',
		toolbar: 'video-details',
		title: l10n.videoDetailsTitle,
		content: 'video-details',
		menu: 'video-details',
		router: false,
		priority: 60
	},

	initialize: function( options ) {
		this.media = options.media;
		State.prototype.initialize.apply( this, arguments );
	}
});

module.exports = VideoDetails;
