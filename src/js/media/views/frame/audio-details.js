var MediaDetails = gc.media.view.MediaFrame.MediaDetails,
	MediaLibrary = gc.media.controller.MediaLibrary,

	l10n = gc.media.view.l10n,
	AudioDetails;

/**
 * gc.media.view.MediaFrame.AudioDetails
 *
 * @memberOf gc.media.view.MediaFrame
 *
 * @class
 * @augments gc.media.view.MediaFrame.MediaDetails
 * @augments gc.media.view.MediaFrame.Select
 * @augments gc.media.view.MediaFrame
 * @augments gc.media.view.Frame
 * @augments gc.media.View
 * @augments gc.Backbone.View
 * @augments Backbone.View
 * @mixes gc.media.controller.StateMachine
 */
AudioDetails = MediaDetails.extend(/** @lends gc.media.view.MediaFrame.AudioDetails.prototype */{
	defaults: {
		id:      'audio',
		url:     '',
		menu:    'audio-details',
		content: 'audio-details',
		toolbar: 'audio-details',
		type:    'link',
		title:    l10n.audioDetailsTitle,
		priority: 120
	},

	initialize: function( options ) {
		options.DetailsView = gc.media.view.AudioDetails;
		options.cancelText = l10n.audioDetailsCancel;
		options.addText = l10n.audioAddSourceTitle;

		MediaDetails.prototype.initialize.call( this, options );
	},

	bindHandlers: function() {
		MediaDetails.prototype.bindHandlers.apply( this, arguments );

		this.on( 'toolbar:render:replace-audio', this.renderReplaceToolbar, this );
		this.on( 'toolbar:render:add-audio-source', this.renderAddSourceToolbar, this );
	},

	createStates: function() {
		this.states.add([
			new gc.media.controller.AudioDetails( {
				media: this.media
			} ),

			new MediaLibrary( {
				type: 'audio',
				id: 'replace-audio',
				title: l10n.audioReplaceTitle,
				toolbar: 'replace-audio',
				media: this.media,
				menu: 'audio-details'
			} ),

			new MediaLibrary( {
				type: 'audio',
				id: 'add-audio-source',
				title: l10n.audioAddSourceTitle,
				toolbar: 'add-audio-source',
				media: this.media,
				menu: false
			} )
		]);
	}
});

module.exports = AudioDetails;
