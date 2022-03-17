var l10n = gc.media.view.l10n,
	EditAttachmentMetadata;

/**
 * gc.media.controller.EditAttachmentMetadata
 *
 * A state for editing an attachment's metadata.
 *
 * @memberOf gc.media.controller
 *
 * @class
 * @augments gc.media.controller.State
 * @augments Backbone.Model
 */
EditAttachmentMetadata = gc.media.controller.State.extend(/** @lends gc.media.controller.EditAttachmentMetadata.prototype */{
	defaults: {
		id:      'edit-attachment',
		// Title string passed to the frame's title region view.
		title:   l10n.attachmentDetails,
		// Region mode defaults.
		content: 'edit-metadata',
		menu:    false,
		toolbar: false,
		router:  false
	}
});

module.exports = EditAttachmentMetadata;
