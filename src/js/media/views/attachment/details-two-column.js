var Details = gc.media.view.Attachment.Details,
	TwoColumn;

/**
 * gc.media.view.Attachment.Details.TwoColumn
 *
 * A similar view to media.view.Attachment.Details
 * for use in the Edit Attachment modal.
 *
 * @memberOf gc.media.view.Attachment.Details
 *
 * @class
 * @augments gc.media.view.Attachment.Details
 * @augments gc.media.view.Attachment
 * @augments gc.media.View
 * @augments gc.Backbone.View
 * @augments Backbone.View
 */
TwoColumn = Details.extend(/** @lends gc.media.view.Attachment.Details.TowColumn.prototype */{
	template: gc.template( 'attachment-details-two-column' ),

	initialize: function() {
		this.controller.on( 'content:activate:edit-details', _.bind( this.editAttachment, this ) );

		Details.prototype.initialize.apply( this, arguments );
	},

	editAttachment: function( event ) {
		if ( event ) {
			event.preventDefault();
		}
		this.controller.content.mode( 'edit-image' );
	},

	/**
	 * Noop this from parent class, doesn't apply here.
	 */
	toggleSelectionHandler: function() {}

});

module.exports = TwoColumn;
