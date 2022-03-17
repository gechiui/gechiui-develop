/**
 * gc.media.view.Spinner
 *
 * Represents a spinner in the Media Library.
 *
 *
 *
 * @memberOf gc.media.view
 *
 * @class
 * @augments gc.media.View
 * @augments gc.Backbone.View
 * @augments Backbone.View
 */
var Spinner = gc.media.View.extend(/** @lends gc.media.view.Spinner.prototype */{
	tagName:   'span',
	className: 'spinner',
	spinnerTimeout: false,
	delay: 400,

	/**
	 * Shows the spinner. Delays the visibility by the configured amount.
	 *
	 *
	 * @return {gc.media.view.Spinner} The spinner.
	 */
	show: function() {
		if ( ! this.spinnerTimeout ) {
			this.spinnerTimeout = _.delay(function( $el ) {
				$el.addClass( 'is-active' );
			}, this.delay, this.$el );
		}

		return this;
	},

	/**
	 * Hides the spinner.
	 *
	 *
	 * @return {gc.media.view.Spinner} The spinner.
	 */
	hide: function() {
		this.$el.removeClass( 'is-active' );
		this.spinnerTimeout = clearTimeout( this.spinnerTimeout );

		return this;
	}
});

module.exports = Spinner;
