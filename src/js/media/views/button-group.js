var $ = Backbone.$,
	ButtonGroup;

/**
 * gc.media.view.ButtonGroup
 *
 * @memberOf gc.media.view
 *
 * @class
 * @augments gc.media.View
 * @augments gc.Backbone.View
 * @augments Backbone.View
 */
ButtonGroup = gc.media.View.extend(/** @lends gc.media.view.ButtonGroup.prototype */{
	tagName:   'div',
	className: 'button-group button-large media-button-group',

	initialize: function() {
		/**
		 * @member {gc.media.view.Button[]}
		 */
		this.buttons = _.map( this.options.buttons || [], function( button ) {
			if ( button instanceof Backbone.View ) {
				return button;
			} else {
				return new gc.media.view.Button( button ).render();
			}
		});

		delete this.options.buttons;

		if ( this.options.classes ) {
			this.$el.addClass( this.options.classes );
		}
	},

	/**
	 * @return {gc.media.view.ButtonGroup}
	 */
	render: function() {
		this.$el.html( $( _.pluck( this.buttons, 'el' ) ).detach() );
		return this;
	}
});

module.exports = ButtonGroup;
