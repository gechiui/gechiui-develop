var MediaDetails = gc.media.view.MediaDetails,
	AudioDetails;

/**
 * gc.media.view.AudioDetails
 *
 * @memberOf gc.media.view
 *
 * @class
 * @augments gc.media.view.MediaDetails
 * @augments gc.media.view.Settings.AttachmentDisplay
 * @augments gc.media.view.Settings
 * @augments gc.media.View
 * @augments gc.Backbone.View
 * @augments Backbone.View
 */
AudioDetails = MediaDetails.extend(/** @lends gc.media.view.AudioDetails.prototype */{
	className: 'audio-details',
	template:  gc.template('audio-details'),

	setMedia: function() {
		var audio = this.$('.gc-audio-shortcode');

		if ( audio.find( 'source' ).length ) {
			if ( audio.is(':hidden') ) {
				audio.show();
			}
			this.media = MediaDetails.prepareSrc( audio.get(0) );
		} else {
			audio.hide();
			this.media = false;
		}

		return this;
	}
});

module.exports = AudioDetails;
