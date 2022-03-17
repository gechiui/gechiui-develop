/**
 * gc.media.controller.StateMachine
 *
 * A state machine keeps track of state. It is in one state at a time,
 * and can change from one state to another.
 *
 * States are stored as models in a Backbone collection.
 *
 * @memberOf gc.media.controller
 *
 *
 *
 * @class
 * @augments Backbone.Model
 * @mixin
 * @mixes Backbone.Events
 */
var StateMachine = function() {
	return {
		// Use Backbone's self-propagating `extend` inheritance method.
		extend: Backbone.Model.extend
	};
};

_.extend( StateMachine.prototype, Backbone.Events,/** @lends gc.media.controller.StateMachine.prototype */{
	/**
	 * Fetch a state.
	 *
	 * If no `id` is provided, returns the active state.
	 *
	 * Implicitly creates states.
	 *
	 * Ensure that the `states` collection exists so the `StateMachine`
	 * can be used as a mixin.
	 *
	 *
	 * @param {string} id
	 * @return {gc.media.controller.State} Returns a State model from
	 *                                     the StateMachine collection.
	 */
	state: function( id ) {
		this.states = this.states || new Backbone.Collection();

		// Default to the active state.
		id = id || this._state;

		if ( id && ! this.states.get( id ) ) {
			this.states.add({ id: id });
		}
		return this.states.get( id );
	},

	/**
	 * Sets the active state.
	 *
	 * Bail if we're trying to select the current state, if we haven't
	 * created the `states` collection, or are trying to select a state
	 * that does not exist.
	 *
	 *
	 * @param {string} id
	 *
	 * @fires gc.media.controller.State#deactivate
	 * @fires gc.media.controller.State#activate
	 *
	 * @return {gc.media.controller.StateMachine} Returns itself to allow chaining.
	 */
	setState: function( id ) {
		var previous = this.state();

		if ( ( previous && id === previous.id ) || ! this.states || ! this.states.get( id ) ) {
			return this;
		}

		if ( previous ) {
			previous.trigger('deactivate');
			this._lastState = previous.id;
		}

		this._state = id;
		this.state().trigger('activate');

		return this;
	},

	/**
	 * Returns the previous active state.
	 *
	 * Call the `state()` method with no parameters to retrieve the current
	 * active state.
	 *
	 *
	 * @return {gc.media.controller.State} Returns a State model from
	 *                                     the StateMachine collection.
	 */
	lastState: function() {
		if ( this._lastState ) {
			return this.state( this._lastState );
		}
	}
});

// Map all event binding and triggering on a StateMachine to its `states` collection.
_.each([ 'on', 'off', 'trigger' ], function( method ) {
	/**
	 * @function on
	 * @memberOf gc.media.controller.StateMachine
	 * @instance
	 * @return {gc.media.controller.StateMachine} Returns itself to allow chaining.
	 */
	/**
	 * @function off
	 * @memberOf gc.media.controller.StateMachine
	 * @instance
	 * @return {gc.media.controller.StateMachine} Returns itself to allow chaining.
	 */
	/**
	 * @function trigger
	 * @memberOf gc.media.controller.StateMachine
	 * @instance
	 * @return {gc.media.controller.StateMachine} Returns itself to allow chaining.
	 */
	StateMachine.prototype[ method ] = function() {
		// Ensure that the `states` collection exists so the `StateMachine`
		// can be used as a mixin.
		this.states = this.states || new Backbone.Collection();
		// Forward the method to the `states` collection.
		this.states[ method ].apply( this.states, arguments );
		return this;
	};
});

module.exports = StateMachine;
