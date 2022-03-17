var MediaFrame = gc.media.view.MediaFrame,
	l10n = gc.media.view.l10n,
	Select;

/**
 * gc.media.view.MediaFrame.Select
 *
 * A frame for selecting an item or items from the media library.
 *
 * @memberOf gc.media.view.MediaFrame
 *
 * @class
 * @augments gc.media.view.MediaFrame
 * @augments gc.media.view.Frame
 * @augments gc.media.View
 * @augments gc.Backbone.View
 * @augments Backbone.View
 * @mixes gc.media.controller.StateMachine
 */
Select = MediaFrame.extend(/** @lends gc.media.view.MediaFrame.Select.prototype */{
	initialize: function() {
		// Call 'initialize' directly on the parent class.
		MediaFrame.prototype.initialize.apply( this, arguments );

		_.defaults( this.options, {
			selection: [],
			library:   {},
			multiple:  false,
			state:    'library'
		});

		this.createSelection();
		this.createStates();
		this.bindHandlers();
	},

	/**
	 * Attach a selection collection to the frame.
	 *
	 * A selection is a collection of attachments used for a specific purpose
	 * by a media frame. e.g. Selecting an attachment (or many) to insert into
	 * post content.
	 *
	 * @see media.model.Selection
	 */
	createSelection: function() {
		var selection = this.options.selection;

		if ( ! (selection instanceof gc.media.model.Selection) ) {
			this.options.selection = new gc.media.model.Selection( selection, {
				multiple: this.options.multiple
			});
		}

		this._selection = {
			attachments: new gc.media.model.Attachments(),
			difference: []
		};
	},

	editImageContent: function() {
		var image = this.state().get('image'),
			view = new gc.media.view.EditImage( { model: image, controller: this } ).render();

		this.content.set( view );

		// After creating the wrapper view, load the actual editor via an Ajax call.
		view.loadEditor();
	},

	/**
	 * Create the default states on the frame.
	 */
	createStates: function() {
		var options = this.options;

		if ( this.options.states ) {
			return;
		}

		// Add the default states.
		this.states.add([
			// Main states.
			new gc.media.controller.Library({
				library:   gc.media.query( options.library ),
				multiple:  options.multiple,
				title:     options.title,
				priority:  20
			}),
			new gc.media.controller.EditImage( { model: options.editImage } )
		]);
	},

	/**
	 * Bind region mode event callbacks.
	 *
	 * @see media.controller.Region.render
	 */
	bindHandlers: function() {
		this.on( 'router:create:browse', this.createRouter, this );
		this.on( 'router:render:browse', this.browseRouter, this );
		this.on( 'content:create:browse', this.browseContent, this );
		this.on( 'content:render:upload', this.uploadContent, this );
		this.on( 'toolbar:create:select', this.createSelectToolbar, this );
		this.on( 'content:render:edit-image', this.editImageContent, this );
	},

	/**
	 * Render callback for the router region in the `browse` mode.
	 *
	 * @param {gc.media.view.Router} routerView
	 */
	browseRouter: function( routerView ) {
		routerView.set({
			upload: {
				text:     l10n.uploadFilesTitle,
				priority: 20
			},
			browse: {
				text:     l10n.mediaLibraryTitle,
				priority: 40
			}
		});
	},

	/**
	 * Render callback for the content region in the `browse` mode.
	 *
	 * @param {gc.media.controller.Region} contentRegion
	 */
	browseContent: function( contentRegion ) {
		var state = this.state();

		this.$el.removeClass('hide-toolbar');

		// Browse our library of attachments.
		contentRegion.view = new gc.media.view.AttachmentsBrowser({
			controller: this,
			collection: state.get('library'),
			selection:  state.get('selection'),
			model:      state,
			sortable:   state.get('sortable'),
			search:     state.get('searchable'),
			filters:    state.get('filterable'),
			date:       state.get('date'),
			display:    state.has('display') ? state.get('display') : state.get('displaySettings'),
			dragInfo:   state.get('dragInfo'),

			idealColumnWidth: state.get('idealColumnWidth'),
			suggestedWidth:   state.get('suggestedWidth'),
			suggestedHeight:  state.get('suggestedHeight'),

			AttachmentView: state.get('AttachmentView')
		});
	},

	/**
	 * Render callback for the content region in the `upload` mode.
	 */
	uploadContent: function() {
		this.$el.removeClass( 'hide-toolbar' );
		this.content.set( new gc.media.view.UploaderInline({
			controller: this
		}) );
	},

	/**
	 * Toolbars
	 *
	 * @param {Object} toolbar
	 * @param {Object} [options={}]
	 * @this gc.media.controller.Region
	 */
	createSelectToolbar: function( toolbar, options ) {
		options = options || this.options.button || {};
		options.controller = this;

		toolbar.view = new gc.media.view.Toolbar.Select( options );
	}
});

module.exports = Select;
