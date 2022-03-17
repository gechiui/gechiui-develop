/**
 * gc.media.view.Attachment.Selection
 *
 * @memberOf gc.media.view.Attachment
 *
 * @class
 * @augments gc.media.view.Attachment
 * @augments gc.media.View
 * @augments gc.Backbone.View
 * @augments Backbone.View
 */
var Selection = gc.media.view.Attachment.extend(/** @lends gc.media.view.Attachment.Selection.prototype */{
	className: 'attachment selection',

	// On click, just select the model, instead of removing the model from
	// the selection.
	toggleSelection: function() {
		this.options.selection.single( this.model );
	}
});

module.exports = Selection;
