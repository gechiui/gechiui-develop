var Attachments = gc.media.view.Attachments,
	Selection;

/**
 * gc.media.view.Attachments.Selection
 *
 * @memberOf gc.media.view.Attachments
 *
 * @class
 * @augments gc.media.view.Attachments
 * @augments gc.media.View
 * @augments gc.Backbone.View
 * @augments Backbone.View
 */
Selection = Attachments.extend(/** @lends gc.media.view.Attachments.Selection.prototype */{
	events: {},
	initialize: function() {
		_.defaults( this.options, {
			sortable:   false,
			resize:     false,

			// The single `Attachment` view to be used in the `Attachments` view.
			AttachmentView: gc.media.view.Attachment.Selection
		});
		// Call 'initialize' directly on the parent class.
		return Attachments.prototype.initialize.apply( this, arguments );
	}
});

module.exports = Selection;
