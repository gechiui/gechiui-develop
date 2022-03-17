var Select = gc.media.view.Toolbar.Select,
	l10n = gc.media.view.l10n,
	Embed;

/**
 * gc.media.view.Toolbar.Embed
 *
 * @memberOf gc.media.view.Toolbar
 *
 * @class
 * @augments gc.media.view.Toolbar.Select
 * @augments gc.media.view.Toolbar
 * @augments gc.media.View
 * @augments gc.Backbone.View
 * @augments Backbone.View
 */
Embed = Select.extend(/** @lends gc.media.view.Toolbar.Embed.prototype */{
	initialize: function() {
		_.defaults( this.options, {
			text: l10n.insertIntoPost,
			requires: false
		});
		// Call 'initialize' directly on the parent class.
		Select.prototype.initialize.apply( this, arguments );
	},

	refresh: function() {
		var url = this.controller.state().props.get('url');
		this.get('select').model.set( 'disabled', ! url || url === 'http://' );
		/**
		 * call 'refresh' directly on the parent class
		 */
		Select.prototype.refresh.apply( this, arguments );
	}
});

module.exports = Embed;
