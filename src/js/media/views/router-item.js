/**
 * gc.media.view.RouterItem
 *
 * @memberOf gc.media.view
 *
 * @class
 * @augments gc.media.view.MenuItem
 * @augments gc.media.View
 * @augments gc.Backbone.View
 * @augments Backbone.View
 */
var RouterItem = gc.media.view.MenuItem.extend(/** @lends gc.media.view.RouterItem.prototype */{
	/**
	 * On click handler to activate the content region's corresponding mode.
	 */
	click: function() {
		var contentMode = this.options.contentMode;
		if ( contentMode ) {
			this.controller.content.mode( contentMode );
		}
	}
});

module.exports = RouterItem;
