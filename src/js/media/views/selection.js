var _n = gc.i18n._n,
	sprintf = gc.i18n.sprintf,
	Selection;

/**
 * gc.media.view.Selection
 *
 * @memberOf gc.media.view
 *
 * @class
 * @augments gc.media.View
 * @augments gc.Backbone.View
 * @augments Backbone.View
 */
Selection = gc.media.View.extend(/** @lends gc.media.view.Selection.prototype */{
	tagName:   'div',
	className: 'media-selection',
	template:  gc.template('media-selection'),

	events: {
		'click .edit-selection':  'edit',
		'click .clear-selection': 'clear'
	},

	initialize: function() {
		_.defaults( this.options, {
			editable:  false,
			clearable: true
		});

		/**
		 * @member {gc.media.view.Attachments.Selection}
		 */
		this.attachments = new gc.media.view.Attachments.Selection({
			controller: this.controller,
			collection: this.collection,
			selection:  this.collection,
			model:      new Backbone.Model()
		});

		this.views.set( '.selection-view', this.attachments );
		this.collection.on( 'add remove reset', this.refresh, this );
		this.controller.on( 'content:activate', this.refresh, this );
	},

	ready: function() {
		this.refresh();
	},

	refresh: function() {
		// If the selection hasn't been rendered, bail.
		if ( ! this.$el.children().length ) {
			return;
		}

		var collection = this.collection,
			editing = 'edit-selection' === this.controller.content.mode();

		// If nothing is selected, display nothing.
		this.$el.toggleClass( 'empty', ! collection.length );
		this.$el.toggleClass( 'one', 1 === collection.length );
		this.$el.toggleClass( 'editing', editing );

		this.$( '.count' ).text(
			/* translators: %s: Number of selected media attachments. */
			sprintf( _n( '已选择%s个项目。', '已选择%s个项目。', collection.length ), collection.length )
		);
	},

	edit: function( event ) {
		event.preventDefault();
		if ( this.options.editable ) {
			this.options.editable.call( this, this.collection );
		}
	},

	clear: function( event ) {
		event.preventDefault();
		this.collection.reset();

		// Move focus to the modal.
		this.controller.modal.focusManager.focus();
	}
});

module.exports = Selection;
