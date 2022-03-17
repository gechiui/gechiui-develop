/**
 * gc.media.view.UploaderStatusError
 *
 * @memberOf gc.media.view
 *
 * @class
 * @augments gc.media.View
 * @augments gc.Backbone.View
 * @augments Backbone.View
 */
var UploaderStatusError = gc.media.View.extend(/** @lends gc.media.view.UploaderStatusError.prototype */{
	className: 'upload-error',
	template:  gc.template('uploader-status-error')
});

module.exports = UploaderStatusError;
