/**
 * gc.media.view.Frame
 *
 * A frame is a composite view consisting of one or more regions and one or more
 * states.
 *
 * @memberOf gc.media.view
 *
 * @see gc.media.controller.State
 * @see gc.media.controller.Region
 *
 * @class
 * @augments gc.media.View
 * @augments gc.Backbone.View
 * @augments Backbone.View
 * @mixes gc.media.controller.StateMachine
 */
var Frame = gc.media.View.extend(/** @lends gc.media.view.Frame.prototype */{
	initialize: function() {
		_.defaults( this.options, {
			mode: [ 'select' ]
		});
		this._createRegions();
		this._createStates();
		this._createModes();
	},

	_createRegions: function() {
		// Clone the regions array.
		this.regions = this.regions ? this.regions.slice() : [];

		// Initialize regions.
		_.each( this.regions, function( region ) {
			this[ region ] = new gc.media.controller.Region({
				view:     this,
				id:       region,
				selector: '.media-frame-' + region
			});
		}, this );
	},
	/**
	 * Create the frame's states.
	 *
	 * @see gc.media.controller.State
	 * @see gc.media.controller.StateMachine
	 *
	 * @fires gc.media.controller.State#ready
	 */
	_createStates: function() {
		// Create the default `states` collection.
		this.states = new Backbone.Collection( null, {
			model: gc.media.controller.State
		});

		// Ensure states have a reference to the frame.
		this.states.on( 'add', function( model ) {
			model.frame = this;
			model.trigger('ready');
		}, this );

		if ( this.options.states ) {
			this.states.add( this.options.states );
		}
	},

	/**
	 * A frame can be in a mode or multiple modes at one time.
	 *
	 * For example, the manage media frame can be in the `Bulk Select` or `Edit` mode.
	 */
	_createModes: function() {
		// Store active "modes" that the frame is in. Unrelated to region modes.
		this.activeModes = new Backbone.Collection();
		this.activeModes.on( 'add remove reset', _.bind( this.triggerModeEvents, this ) );

		_.each( this.options.mode, function( mode ) {
			this.activateMode( mode );
		}, this );
	},
	/**
	 * Reset all states on the frame to their defaults.
	 *
	 * @return {gc.media.view.Frame} Returns itself to allow chaining.
	 */
	reset: function() {
		this.states.invoke( 'trigger', 'reset' );
		return this;
	},
	/**
	 * Map activeMode collection events to the frame.
	 */
	triggerModeEvents: function( model, collection, options ) {
		var collectionEvent,
			modeEventMap = {
				add: 'activate',
				remove: 'deactivate'
			},
			eventToTrigger;
		// Probably a better way to do this.
		_.each( options, function( value, key ) {
			if ( value ) {
				collectionEvent = key;
			}
		} );

		if ( ! _.has( modeEventMap, collectionEvent ) ) {
			return;
		}

		eventToTrigger = model.get('id') + ':' + modeEventMap[collectionEvent];
		this.trigger( eventToTrigger );
	},
	/**
	 * Activate a mode on the frame.
	 *
	 * @param string mode Mode ID.
	 * @return {this} Returns itself to allow chaining.
	 */
	activateMode: function( mode ) {
		// Bail if the mode is already active.
		if ( this.isModeActive( mode ) ) {
			return;
		}
		this.activeModes.add( [ { id: mode } ] );
		// Add a CSS class to the frame so elements can be styled for the mode.
		this.$el.addClass( 'mode-' + mode );

		return this;
	},
	/**
	 * Deactivate a mode on the frame.
	 *
	 * @param string mode Mode ID.
	 * @return {this} Returns itself to allow chaining.
	 */
	deactivateMode: function( mode ) {
		// Bail if the mode isn't active.
		if ( ! this.isModeActive( mode ) ) {
			return this;
		}
		this.activeModes.remove( this.activeModes.where( { id: mode } ) );
		this.$el.removeClass( 'mode-' + mode );
		/**
		 * Frame mode deactivation event.
		 *
		 * @event gc.media.view.Frame#{mode}:deactivate
		 */
		this.trigger( mode + ':deactivate' );

		return this;
	},
	/**
	 * Check if a mode is enabled on the frame.
	 *
	 * @param string mode Mode ID.
	 * @return bool
	 */
	isModeActive: function( mode ) {
		return Boolean( this.activeModes.where( { id: mode } ).length );
	}
});

// Make the `Frame` a `StateMachine`.
_.extend( Frame.prototype, gc.media.controller.StateMachine.prototype );

module.exports = Frame;
