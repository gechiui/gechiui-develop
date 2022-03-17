/**
 * gc.media.view.Settings.Playlist
 *
 * @memberOf gc.media.view.Settings
 *
 * @class
 * @augments gc.media.view.Settings
 * @augments gc.media.View
 * @augments gc.Backbone.View
 * @augments Backbone.View
 */
var Playlist = gc.media.view.Settings.extend(/** @lends gc.media.view.Settings.Playlist.prototype */{
	className: 'collection-settings playlist-settings',
	template:  gc.template('playlist-settings')
});

module.exports = Playlist;
