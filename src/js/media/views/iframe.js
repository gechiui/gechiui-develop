/**
 * gc.media.view.Iframe
 *
 * @memberOf gc.media.view
 *
 * @class
 * @augments gc.media.View
 * @augments gc.Backbone.View
 * @augments Backbone.View
 */
var Iframe = gc.media.View.extend(/** @lends gc.media.view.Iframe.prototype */{
	className: 'media-iframe',
	/**
	 * @return {gc.media.view.Iframe} Returns itself to allow chaining.
	 */
	render: function() {
		this.views.detach();
		this.$el.html( '<iframe src="' + this.controller.state().get('src') + '" />' );
		this.views.render();
		return this;
	}
});

module.exports = Iframe;
