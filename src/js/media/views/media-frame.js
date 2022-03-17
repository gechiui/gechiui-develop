var Frame = gc.media.view.Frame,
	l10n = gc.media.view.l10n,
	$ = jQuery,
	MediaFrame;

/**
 * gc.media.view.MediaFrame
 *
 * The frame used to create the media modal.
 *
 * @memberOf gc.media.view
 *
 * @class
 * @augments gc.media.view.Frame
 * @augments gc.media.View
 * @augments gc.Backbone.View
 * @augments Backbone.View
 * @mixes gc.media.controller.StateMachine
 */
MediaFrame = Frame.extend(/** @lends gc.media.view.MediaFrame.prototype */{
	className: 'media-frame',
	template:  gc.template('media-frame'),
	regions:   ['menu','title','content','toolbar','router'],

	events: {
		'click .media-frame-menu-toggle': 'toggleMenu'
	},

	/**
	 * @constructs
	 */
	initialize: function() {
		Frame.prototype.initialize.apply( this, arguments );

		_.defaults( this.options, {
			title:    l10n.mediaFrameDefaultTitle,
			modal:    true,
			uploader: true
		});

		// Ensure core UI is enabled.
		this.$el.addClass('gc-core-ui');

		// Initialize modal container view.
		if ( this.options.modal ) {
			this.modal = new gc.media.view.Modal({
				controller: this,
				title:      this.options.title
			});

			this.modal.content( this );
		}

		// Force the uploader off if the upload limit has been exceeded or
		// if the browser isn't supported.
		if ( gc.Uploader.limitExceeded || ! gc.Uploader.browser.supported ) {
			this.options.uploader = false;
		}

		// Initialize window-wide uploader.
		if ( this.options.uploader ) {
			this.uploader = new gc.media.view.UploaderWindow({
				controller: this,
				uploader: {
					dropzone:  this.modal ? this.modal.$el : this.$el,
					container: this.$el
				}
			});
			this.views.set( '.media-frame-uploader', this.uploader );
		}

		this.on( 'attach', _.bind( this.views.ready, this.views ), this );

		// Bind default title creation.
		this.on( 'title:create:default', this.createTitle, this );
		this.title.mode('default');

		// Bind default menu.
		this.on( 'menu:create:default', this.createMenu, this );

		// Set the menu ARIA tab panel attributes when the modal opens.
		this.on( 'open', this.setMenuTabPanelAriaAttributes, this );
		// Set the router ARIA tab panel attributes when the modal opens.
		this.on( 'open', this.setRouterTabPanelAriaAttributes, this );

		// Update the menu ARIA tab panel attributes when the content updates.
		this.on( 'content:render', this.setMenuTabPanelAriaAttributes, this );
		// Update the router ARIA tab panel attributes when the content updates.
		this.on( 'content:render', this.setRouterTabPanelAriaAttributes, this );
	},

	/**
	 * Sets the attributes to be used on the menu ARIA tab panel.
	 *
	 *
	 * @return {void}
	 */
	setMenuTabPanelAriaAttributes: function() {
		var stateId = this.state().get( 'id' ),
			tabPanelEl = this.$el.find( '.media-frame-tab-panel' ),
			ariaLabelledby;

		tabPanelEl.removeAttr( 'role aria-labelledby tabindex' );

		if ( this.state().get( 'menu' ) && this.menuView && this.menuView.isVisible ) {
			ariaLabelledby = 'menu-item-' + stateId;

			// Set the tab panel attributes only if the tabs are visible.
			tabPanelEl
				.attr( {
					role: 'tabpanel',
					'aria-labelledby': ariaLabelledby,
					tabIndex: '0'
				} );
		}
	},

	/**
	 * Sets the attributes to be used on the router ARIA tab panel.
	 *
	 *
	 * @return {void}
	 */
	setRouterTabPanelAriaAttributes: function() {
		var tabPanelEl = this.$el.find( '.media-frame-content' ),
			ariaLabelledby;

		tabPanelEl.removeAttr( 'role aria-labelledby tabindex' );

		// Set the tab panel attributes only if the tabs are visible.
		if ( this.state().get( 'router' ) && this.routerView && this.routerView.isVisible && this.content._mode ) {
			ariaLabelledby = 'menu-item-' + this.content._mode;

			tabPanelEl
				.attr( {
					role: 'tabpanel',
					'aria-labelledby': ariaLabelledby,
					tabIndex: '0'
				} );
		}
	},

	/**
	 * @return {gc.media.view.MediaFrame} Returns itself to allow chaining.
	 */
	render: function() {
		// Activate the default state if no active state exists.
		if ( ! this.state() && this.options.state ) {
			this.setState( this.options.state );
		}
		/**
		 * call 'render' directly on the parent class
		 */
		return Frame.prototype.render.apply( this, arguments );
	},
	/**
	 * @param {Object} title
	 * @this gc.media.controller.Region
	 */
	createTitle: function( title ) {
		title.view = new gc.media.View({
			controller: this,
			tagName: 'h1'
		});
	},
	/**
	 * @param {Object} menu
	 * @this gc.media.controller.Region
	 */
	createMenu: function( menu ) {
		menu.view = new gc.media.view.Menu({
			controller: this,

			attributes: {
				role:               'tablist',
				'aria-orientation': 'vertical'
			}
		});

		this.menuView = menu.view;
	},

	toggleMenu: function( event ) {
		var menu = this.$el.find( '.media-menu' );

		menu.toggleClass( 'visible' );
		$( event.target ).attr( 'aria-expanded', menu.hasClass( 'visible' ) );
	},

	/**
	 * @param {Object} toolbar
	 * @this gc.media.controller.Region
	 */
	createToolbar: function( toolbar ) {
		toolbar.view = new gc.media.view.Toolbar({
			controller: this
		});
	},
	/**
	 * @param {Object} router
	 * @this gc.media.controller.Region
	 */
	createRouter: function( router ) {
		router.view = new gc.media.view.Router({
			controller: this,

			attributes: {
				role:               'tablist',
				'aria-orientation': 'horizontal'
			}
		});

		this.routerView = router.view;
	},
	/**
	 * @param {Object} options
	 */
	createIframeStates: function( options ) {
		var settings = gc.media.view.settings,
			tabs = settings.tabs,
			tabUrl = settings.tabUrl,
			$postId;

		if ( ! tabs || ! tabUrl ) {
			return;
		}

		// Add the post ID to the tab URL if it exists.
		$postId = $('#post_ID');
		if ( $postId.length ) {
			tabUrl += '&post_id=' + $postId.val();
		}

		// Generate the tab states.
		_.each( tabs, function( title, id ) {
			this.state( 'iframe:' + id ).set( _.defaults({
				tab:     id,
				src:     tabUrl + '&tab=' + id,
				title:   title,
				content: 'iframe',
				menu:    'default'
			}, options ) );
		}, this );

		this.on( 'content:create:iframe', this.iframeContent, this );
		this.on( 'content:deactivate:iframe', this.iframeContentCleanup, this );
		this.on( 'menu:render:default', this.iframeMenu, this );
		this.on( 'open', this.hijackThickbox, this );
		this.on( 'close', this.restoreThickbox, this );
	},

	/**
	 * @param {Object} content
	 * @this gc.media.controller.Region
	 */
	iframeContent: function( content ) {
		this.$el.addClass('hide-toolbar');
		content.view = new gc.media.view.Iframe({
			controller: this
		});
	},

	iframeContentCleanup: function() {
		this.$el.removeClass('hide-toolbar');
	},

	iframeMenu: function( view ) {
		var views = {};

		if ( ! view ) {
			return;
		}

		_.each( gc.media.view.settings.tabs, function( title, id ) {
			views[ 'iframe:' + id ] = {
				text: this.state( 'iframe:' + id ).get('title'),
				priority: 200
			};
		}, this );

		view.set( views );
	},

	hijackThickbox: function() {
		var frame = this;

		if ( ! window.tb_remove || this._tb_remove ) {
			return;
		}

		this._tb_remove = window.tb_remove;
		window.tb_remove = function() {
			frame.close();
			frame.reset();
			frame.setState( frame.options.state );
			frame._tb_remove.call( window );
		};
	},

	restoreThickbox: function() {
		if ( ! this._tb_remove ) {
			return;
		}

		window.tb_remove = this._tb_remove;
		delete this._tb_remove;
	}
});

// Map some of the modal's methods to the frame.
_.each(['open','close','attach','detach','escape'], function( method ) {
	/**
	 * @function open
	 * @memberOf gc.media.view.MediaFrame
	 * @instance
	 *
	 * @return {gc.media.view.MediaFrame} Returns itself to allow chaining.
	 */
	/**
	 * @function close
	 * @memberOf gc.media.view.MediaFrame
	 * @instance
	 *
	 * @return {gc.media.view.MediaFrame} Returns itself to allow chaining.
	 */
	/**
	 * @function attach
	 * @memberOf gc.media.view.MediaFrame
	 * @instance
	 *
	 * @return {gc.media.view.MediaFrame} Returns itself to allow chaining.
	 */
	/**
	 * @function detach
	 * @memberOf gc.media.view.MediaFrame
	 * @instance
	 *
	 * @return {gc.media.view.MediaFrame} Returns itself to allow chaining.
	 */
	/**
	 * @function escape
	 * @memberOf gc.media.view.MediaFrame
	 * @instance
	 *
	 * @return {gc.media.view.MediaFrame} Returns itself to allow chaining.
	 */
	MediaFrame.prototype[ method ] = function() {
		if ( this.modal ) {
			this.modal[ method ].apply( this.modal, arguments );
		}
		return this;
	};
});

module.exports = MediaFrame;
