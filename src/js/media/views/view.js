/**
 * gc.media.View
 *
 * The base view class for media.
 *
 * Undelegating events, removing events from the model, and
 * removing events from the controller mirror the code for
 * `Backbone.View.dispose` in Backbone 0.9.8 development.
 *
 * This behavior has since been removed, and should not be used
 * outside of the media manager.
 *
 * @memberOf gc.media
 *
 * @class
 * @augments gc.Backbone.View
 * @augments Backbone.View
 */
var View = gc.Backbone.View.extend(/** @lends gc.media.View.prototype */{
	constructor: function( options ) {
		if ( options && options.controller ) {
			this.controller = options.controller;
		}
		gc.Backbone.View.apply( this, arguments );
	},
	/**
	 * @todo The internal comment mentions this might have been a stop-gap
	 *       before Backbone 0.9.8 came out. Figure out if Backbone core takes
	 *       care of this in Backbone.View now.
	 *
	 * @return {gc.media.View} Returns itself to allow chaining.
	 */
	dispose: function() {
		/*
		 * Undelegating events, removing events from the model, and
		 * removing events from the controller mirror the code for
		 * `Backbone.View.dispose` in Backbone 0.9.8 development.
		 */
		this.undelegateEvents();

		if ( this.model && this.model.off ) {
			this.model.off( null, null, this );
		}

		if ( this.collection && this.collection.off ) {
			this.collection.off( null, null, this );
		}

		// Unbind controller events.
		if ( this.controller && this.controller.off ) {
			this.controller.off( null, null, this );
		}

		return this;
	},
	/**
	 * @return {gc.media.View} Returns itself to allow chaining.
	 */
	remove: function() {
		this.dispose();
		/**
		 * call 'remove' directly on the parent class
		 */
		return gc.Backbone.View.prototype.remove.apply( this, arguments );
	}
});

module.exports = View;
