var Controller = gc.media.controller,
	SiteIconCropper;

/**
 * gc.media.controller.SiteIconCropper
 *
 * A state for cropping a Site Icon.
 *
 * @memberOf gc.media.controller
 *
 * @class
 * @augments gc.media.controller.Cropper
 * @augments gc.media.controller.State
 * @augments Backbone.Model
 */
SiteIconCropper = Controller.Cropper.extend(/** @lends gc.media.controller.SiteIconCropper.prototype */{
	activate: function() {
		this.frame.on( 'content:create:crop', this.createCropContent, this );
		this.frame.on( 'close', this.removeCropper, this );
		this.set('selection', new Backbone.Collection(this.frame._selection.single));
	},

	createCropContent: function() {
		this.cropperView = new gc.media.view.SiteIconCropper({
			controller: this,
			attachment: this.get('selection').first()
		});
		this.cropperView.on('image-loaded', this.createCropToolbar, this);
		this.frame.content.set(this.cropperView);

	},

	doCrop: function( attachment ) {
		var cropDetails = attachment.get( 'cropDetails' ),
			control = this.get( 'control' );

		cropDetails.dst_width  = control.params.width;
		cropDetails.dst_height = control.params.height;

		return gc.ajax.post( 'crop-image', {
			nonce: attachment.get( 'nonces' ).edit,
			id: attachment.get( 'id' ),
			context: 'site-icon',
			cropDetails: cropDetails
		} );
	}
});

module.exports = SiteIconCropper;
