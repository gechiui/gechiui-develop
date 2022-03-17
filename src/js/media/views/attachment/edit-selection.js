/**
 * gc.media.view.Attachment.EditSelection
 *
 * @memberOf gc.media.view.Attachment
 *
 * @class
 * @augments gc.media.view.Attachment.Selection
 * @augments gc.media.view.Attachment
 * @augments gc.media.View
 * @augments gc.Backbone.View
 * @augments Backbone.View
 */
var EditSelection = gc.media.view.Attachment.Selection.extend(/** @lends gc.media.view.Attachment.EditSelection.prototype */{
	buttons: {
		close: true
	}
});

module.exports = EditSelection;
