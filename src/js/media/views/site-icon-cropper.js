var View = gc.media.view,
	SiteIconCropper;

/**
 * gc.media.view.SiteIconCropper
 *
 * Uses the imgAreaSelect plugin to allow a user to crop a Site Icon.
 *
 * Takes imgAreaSelect options from
 * gc.customize.SiteIconControl.calculateImageSelectOptions.
 *
 * @memberOf gc.media.view
 *
 * @class
 * @augments gc.media.view.Cropper
 * @augments gc.media.View
 * @augments gc.Backbone.View
 * @augments Backbone.View
 */
SiteIconCropper = View.Cropper.extend(/** @lends gc.media.view.SiteIconCropper.prototype */{
	className: 'crop-content site-icon',

	ready: function () {
		View.Cropper.prototype.ready.apply( this, arguments );

		this.$( '.crop-image' ).on( 'load', _.bind( this.addSidebar, this ) );
	},

	addSidebar: function() {
		this.sidebar = new gc.media.view.Sidebar({
			controller: this.controller
		});

		this.sidebar.set( 'preview', new gc.media.view.SiteIconPreview({
			controller: this.controller,
			attachment: this.options.attachment
		}) );

		this.controller.cropperView.views.add( this.sidebar );
	}
});

module.exports = SiteIconCropper;
