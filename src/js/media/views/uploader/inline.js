var View = gc.media.View,
	UploaderInline;

/**
 * gc.media.view.UploaderInline
 *
 * The inline uploader that shows up in the 'Upload Files' tab.
 *
 * @memberOf gc.media.view
 *
 * @class
 * @augments gc.media.View
 * @augments gc.Backbone.View
 * @augments Backbone.View
 */
UploaderInline = View.extend(/** @lends gc.media.view.UploaderInline.prototype */{
	tagName:   'div',
	className: 'uploader-inline',
	template:  gc.template('uploader-inline'),

	events: {
		'click .close': 'hide'
	},

	initialize: function() {
		_.defaults( this.options, {
			message: '',
			status:  true,
			canClose: false
		});

		if ( ! this.options.$browser && this.controller.uploader ) {
			this.options.$browser = this.controller.uploader.$browser;
		}

		if ( _.isUndefined( this.options.postId ) ) {
			this.options.postId = gc.media.view.settings.post.id;
		}

		if ( this.options.status ) {
			this.views.set( '.upload-inline-status', new gc.media.view.UploaderStatus({
				controller: this.controller
			}) );
		}
	},

	prepare: function() {
		var suggestedWidth = this.controller.state().get('suggestedWidth'),
			suggestedHeight = this.controller.state().get('suggestedHeight'),
			data = {};

		data.message = this.options.message;
		data.canClose = this.options.canClose;

		if ( suggestedWidth && suggestedHeight ) {
			data.suggestedWidth = suggestedWidth;
			data.suggestedHeight = suggestedHeight;
		}

		return data;
	},
	/**
	 * @return {gc.media.view.UploaderInline} Returns itself to allow chaining.
	 */
	dispose: function() {
		if ( this.disposing ) {
			/**
			 * call 'dispose' directly on the parent class
			 */
			return View.prototype.dispose.apply( this, arguments );
		}

		/*
		 * Run remove on `dispose`, so we can be sure to refresh the
		 * uploader with a view-less DOM. Track whether we're disposing
		 * so we don't trigger an infinite loop.
		 */
		this.disposing = true;
		return this.remove();
	},
	/**
	 * @return {gc.media.view.UploaderInline} Returns itself to allow chaining.
	 */
	remove: function() {
		/**
		 * call 'remove' directly on the parent class
		 */
		var result = View.prototype.remove.apply( this, arguments );

		_.defer( _.bind( this.refresh, this ) );
		return result;
	},

	refresh: function() {
		var uploader = this.controller.uploader;

		if ( uploader ) {
			uploader.refresh();
		}
	},
	/**
	 * @return {gc.media.view.UploaderInline}
	 */
	ready: function() {
		var $browser = this.options.$browser,
			$placeholder;

		if ( this.controller.uploader ) {
			$placeholder = this.$('.browser');

			// Check if we've already replaced the placeholder.
			if ( $placeholder[0] === $browser[0] ) {
				return;
			}

			$browser.detach().text( $placeholder.text() );
			$browser[0].className = $placeholder[0].className;
			$browser[0].setAttribute( 'aria-labelledby', $browser[0].id + ' ' + $placeholder[0].getAttribute('aria-labelledby') );
			$placeholder.replaceWith( $browser.show() );
		}

		this.refresh();
		return this;
	},
	show: function() {
		this.$el.removeClass( 'hidden' );
		if ( this.controller.$uploaderToggler && this.controller.$uploaderToggler.length ) {
			this.controller.$uploaderToggler.attr( 'aria-expanded', 'true' );
		}
	},
	hide: function() {
		this.$el.addClass( 'hidden' );
		if ( this.controller.$uploaderToggler && this.controller.$uploaderToggler.length ) {
			this.controller.$uploaderToggler
				.attr( 'aria-expanded', 'false' )
				// Move focus back to the toggle button when closing the uploader.
				.trigger( 'focus' );
		}
	}

});

module.exports = UploaderInline;
