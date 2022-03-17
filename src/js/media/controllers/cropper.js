var l10n = gc.media.view.l10n,
	Cropper;

/**
 * gc.media.controller.Cropper
 *
 * A class for cropping an image when called from the header media customization panel.
 *
 * @memberOf gc.media.controller
 *
 * @class
 * @augments gc.media.controller.State
 * @augments Backbone.Model
 */
Cropper = gc.media.controller.State.extend(/** @lends gc.media.controller.Cropper.prototype */{
	defaults: {
		id:          'cropper',
		title:       l10n.cropImage,
		// Region mode defaults.
		toolbar:     'crop',
		content:     'crop',
		router:      false,
		canSkipCrop: false,

		// Default doCrop Ajax arguments to allow the Customizer (for example) to inject state.
		doCropArgs: {}
	},

	/**
	 * Shows the crop image window when called from the Add new image button.
	 *
	 *
	 * @return {void}
	 */
	activate: function() {
		this.frame.on( 'content:create:crop', this.createCropContent, this );
		this.frame.on( 'close', this.removeCropper, this );
		this.set('selection', new Backbone.Collection(this.frame._selection.single));
	},

	/**
	 * Changes the state of the toolbar window to browse mode.
	 *
	 *
	 * @return {void}
	 */
	deactivate: function() {
		this.frame.toolbar.mode('browse');
	},

	/**
	 * Creates the crop image window.
	 *
	 * Initialized when clicking on the Select and Crop button.
	 *
	 *
	 * @fires crop window
	 *
	 * @return {void}
	 */
	createCropContent: function() {
		this.cropperView = new gc.media.view.Cropper({
			controller: this,
			attachment: this.get('selection').first()
		});
		this.cropperView.on('image-loaded', this.createCropToolbar, this);
		this.frame.content.set(this.cropperView);

	},

	/**
	 * Removes the image selection and closes the cropping window.
	 *
	 *
	 * @return {void}
	 */
	removeCropper: function() {
		this.imgSelect.cancelSelection();
		this.imgSelect.setOptions({remove: true});
		this.imgSelect.update();
		this.cropperView.remove();
	},

	/**
	 * Checks if cropping can be skipped and creates crop toolbar accordingly.
	 *
	 *
	 * @return {void}
	 */
	createCropToolbar: function() {
		var canSkipCrop, toolbarOptions;

		canSkipCrop = this.get('canSkipCrop') || false;

		toolbarOptions = {
			controller: this.frame,
			items: {
				insert: {
					style:    'primary',
					text:     l10n.cropImage,
					priority: 80,
					requires: { library: false, selection: false },

					click: function() {
						var controller = this.controller,
							selection;

						selection = controller.state().get('selection').first();
						selection.set({cropDetails: controller.state().imgSelect.getSelection()});

						this.$el.text(l10n.cropping);
						this.$el.attr('disabled', true);

						controller.state().doCrop( selection ).done( function( croppedImage ) {
							controller.trigger('cropped', croppedImage );
							controller.close();
						}).fail( function() {
							controller.trigger('content:error:crop');
						});
					}
				}
			}
		};

		if ( canSkipCrop ) {
			_.extend( toolbarOptions.items, {
				skip: {
					style:      'secondary',
					text:       l10n.skipCropping,
					priority:   70,
					requires:   { library: false, selection: false },
					click:      function() {
						var selection = this.controller.state().get('selection').first();
						this.controller.state().cropperView.remove();
						this.controller.trigger('skippedcrop', selection);
						this.controller.close();
					}
				}
			});
		}

		this.frame.toolbar.set( new gc.media.view.Toolbar(toolbarOptions) );
	},

	/**
	 * Creates an object with the image attachment and crop properties.
	 *
	 *
	 * @return {$.promise} A jQuery promise with the custom header crop details.
	 */
	doCrop: function( attachment ) {
		return gc.ajax.post( 'custom-header-crop', _.extend(
			{},
			this.defaults.doCropArgs,
			{
				nonce: attachment.get( 'nonces' ).edit,
				id: attachment.get( 'id' ),
				cropDetails: attachment.get( 'cropDetails' )
			}
		) );
	}
});

module.exports = Cropper;
