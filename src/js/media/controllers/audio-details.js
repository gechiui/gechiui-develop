var State = gc.media.controller.State,
	l10n = gc.media.view.l10n,
	AudioDetails;

/**
 * gc.media.controller.AudioDetails
 *
 * The controller for the Audio Details state
 *
 * @memberOf gc.media.controller
 *
 * @class
 * @augments gc.media.controller.State
 * @augments Backbone.Model
 */
AudioDetails = State.extend(/** @lends gc.media.controller.AudioDetails.prototype */{
	defaults: {
		id: 'audio-details',
		toolbar: 'audio-details',
		title: l10n.audioDetailsTitle,
		content: 'audio-details',
		menu: 'audio-details',
		router: false,
		priority: 60
	},

	initialize: function( options ) {
		this.media = options.media;
		State.prototype.initialize.apply( this, arguments );
	}
});

module.exports = AudioDetails;
