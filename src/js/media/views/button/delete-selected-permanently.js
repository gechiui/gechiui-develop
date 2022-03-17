var Button = gc.media.view.Button,
	DeleteSelected = gc.media.view.DeleteSelectedButton,
	DeleteSelectedPermanently;

/**
 * gc.media.view.DeleteSelectedPermanentlyButton
 *
 * When MEDIA_TRASH is true, a button that handles bulk Delete Permanently logic
 *
 * @memberOf gc.media.view
 *
 * @class
 * @augments gc.media.view.DeleteSelectedButton
 * @augments gc.media.view.Button
 * @augments gc.media.View
 * @augments gc.Backbone.View
 * @augments Backbone.View
 */
DeleteSelectedPermanently = DeleteSelected.extend(/** @lends gc.media.view.DeleteSelectedPermanentlyButton.prototype */{
	initialize: function() {
		DeleteSelected.prototype.initialize.apply( this, arguments );
		this.controller.on( 'select:activate', this.selectActivate, this );
		this.controller.on( 'select:deactivate', this.selectDeactivate, this );
	},

	filterChange: function( model ) {
		this.canShow = ( 'trash' === model.get( 'status' ) );
	},

	selectActivate: function() {
		this.toggleDisabled();
		this.$el.toggleClass( 'hidden', ! this.canShow );
	},

	selectDeactivate: function() {
		this.toggleDisabled();
		this.$el.addClass( 'hidden' );
	},

	render: function() {
		Button.prototype.render.apply( this, arguments );
		this.selectActivate();
		return this;
	}
});

module.exports = DeleteSelectedPermanently;
