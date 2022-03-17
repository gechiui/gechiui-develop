var Menu = gc.media.view.Menu,
	Router;

/**
 * gc.media.view.Router
 *
 * @memberOf gc.media.view
 *
 * @class
 * @augments gc.media.view.Menu
 * @augments gc.media.view.PriorityList
 * @augments gc.media.View
 * @augments gc.Backbone.View
 * @augments Backbone.View
 */
Router = Menu.extend(/** @lends gc.media.view.Router.prototype */{
	tagName:   'div',
	className: 'media-router',
	property:  'contentMode',
	ItemView:  gc.media.view.RouterItem,
	region:    'router',

	attributes: {
		role:               'tablist',
		'aria-orientation': 'horizontal'
	},

	initialize: function() {
		this.controller.on( 'content:render', this.update, this );
		// Call 'initialize' directly on the parent class.
		Menu.prototype.initialize.apply( this, arguments );
	},

	update: function() {
		var mode = this.controller.content.mode();
		if ( mode ) {
			this.select( mode );
		}
	}
});

module.exports = Router;
