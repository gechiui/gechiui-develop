/**
 * gc.media.view.Settings.Gallery
 *
 * @memberOf gc.media.view.Settings
 *
 * @class
 * @augments gc.media.view.Settings
 * @augments gc.media.View
 * @augments gc.Backbone.View
 * @augments Backbone.View
 */
var Gallery = gc.media.view.Settings.extend(/** @lends gc.media.view.Settings.Gallery.prototype */{
	className: 'collection-settings gallery-settings',
	template:  gc.template('gallery-settings')
});

module.exports = Gallery;
