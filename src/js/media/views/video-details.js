var MediaDetails = gc.media.view.MediaDetails,
	VideoDetails;

/**
 * gc.media.view.VideoDetails
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
VideoDetails = MediaDetails.extend(/** @lends gc.media.view.VideoDetails.prototype */{
	className: 'video-details',
	template:  gc.template('video-details'),

	setMedia: function() {
		var video = this.$('.gc-video-shortcode');

		if ( video.find( 'source' ).length ) {
			if ( video.is(':hidden') ) {
				video.show();
			}

			if ( ! video.hasClass( 'youtube-video' ) && ! video.hasClass( 'vimeo-video' ) ) {
				this.media = MediaDetails.prepareSrc( video.get(0) );
			} else {
				this.media = video.get(0);
			}
		} else {
			video.hide();
			this.media = false;
		}

		return this;
	}
});

module.exports = VideoDetails;
