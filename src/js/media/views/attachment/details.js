/* global ClipboardJS */
var Attachment = gc.media.view.Attachment,
	l10n = gc.media.view.l10n,
	$ = jQuery,
	Details,
	__ = gc.i18n.__;

Details = Attachment.extend(/** @lends gc.media.view.Attachment.Details.prototype */{
	tagName:   'div',
	className: 'attachment-details',
	template:  gc.template('attachment-details'),

	/*
	 * Reset all the attributes inherited from Attachment including role=checkbox,
	 * tabindex, etc., as they are inappropriate for this view. See #47458 and [30483] / #30390.
	 */
	attributes: {},

	events: {
		'change [data-setting]':          'updateSetting',
		'change [data-setting] input':    'updateSetting',
		'change [data-setting] select':   'updateSetting',
		'change [data-setting] textarea': 'updateSetting',
		'click .delete-attachment':       'deleteAttachment',
		'click .trash-attachment':        'trashAttachment',
		'click .untrash-attachment':      'untrashAttachment',
		'click .edit-attachment':         'editAttachment',
		'keydown':                        'toggleSelectionHandler'
	},

	/**
	 * Copies the attachment URL to the clipboard.
	 *
	 *
	 * @param {MouseEvent} event A click event.
	 *
	 * @return {void}
	 */
	 copyAttachmentDetailsURLClipboard: function() {
		var clipboard = new ClipboardJS( '.copy-attachment-url' ),
			successTimeout;

		clipboard.on( 'success', function( event ) {
			var triggerElement = $( event.trigger ),
				successElement = $( '.success', triggerElement.closest( '.copy-to-clipboard-container' ) );

			// Clear the selection and move focus back to the trigger.
			event.clearSelection();
			// Handle ClipboardJS focus bug, see https://github.com/zenorocha/clipboard.js/issues/680
			triggerElement.trigger( 'focus' );

			// Show success visual feedback.
			clearTimeout( successTimeout );
			successElement.removeClass( 'hidden' );

			// Hide success visual feedback after 3 seconds since last success.
			successTimeout = setTimeout( function() {
				successElement.addClass( 'hidden' );
			}, 3000 );

			// Handle success audible feedback.
			gc.a11y.speak( __( '文件URL已复制至剪贴板' ) );
		} );
	 },

	/**
	 * Shows the details of an attachment.
	 *
	 *
	 * @constructs gc.media.view.Attachment.Details
	 * @augments gc.media.view.Attachment
	 *
	 * @return {void}
	 */
	initialize: function() {
		this.options = _.defaults( this.options, {
			rerenderOnModelChange: false
		});

		// Call 'initialize' directly on the parent class.
		Attachment.prototype.initialize.apply( this, arguments );

		this.copyAttachmentDetailsURLClipboard();
	},

	/**
	 * Gets the focusable elements to move focus to.
	 *
	 */
	getFocusableElements: function() {
		var editedAttachment = $( 'li[data-id="' + this.model.id + '"]' );

		this.previousAttachment = editedAttachment.prev();
		this.nextAttachment = editedAttachment.next();
	},

	/**
	 * Moves focus to the previous or next attachment in the grid.
	 * Fallbacks to the upload button or media frame when there are no attachments.
	 *
	 */
	moveFocus: function() {
		if ( this.previousAttachment.length ) {
			this.previousAttachment.trigger( 'focus' );
			return;
		}

		if ( this.nextAttachment.length ) {
			this.nextAttachment.trigger( 'focus' );
			return;
		}

		// Fallback: move focus to the "Select Files" button in the media modal.
		if ( this.controller.uploader && this.controller.uploader.$browser ) {
			this.controller.uploader.$browser.trigger( 'focus' );
			return;
		}

		// Last fallback.
		this.moveFocusToLastFallback();
	},

	/**
	 * Moves focus to the media frame as last fallback.
	 *
	 */
	moveFocusToLastFallback: function() {
		// Last fallback: make the frame focusable and move focus to it.
		$( '.media-frame' )
			.attr( 'tabindex', '-1' )
			.trigger( 'focus' );
	},

	/**
	 * Deletes an attachment.
	 *
	 * Deletes an attachment after asking for confirmation. After deletion,
	 * keeps focus in the modal.
	 *
	 *
	 * @param {MouseEvent} event A click event.
	 *
	 * @return {void}
	 */
	deleteAttachment: function( event ) {
		event.preventDefault();

		this.getFocusableElements();

		if ( window.confirm( l10n.warnDelete ) ) {
			this.model.destroy();
			this.moveFocus();
		}
	},

	/**
	 * Sets the Trash state on an attachment, or destroys the model itself.
	 *
	 * If the mediaTrash setting is set to true, trashes the attachment.
	 * Otherwise, the model itself is destroyed.
	 *
	 *
	 * @param {MouseEvent} event A click event.
	 *
	 * @return {void}
	 */
	trashAttachment: function( event ) {
		var library = this.controller.library,
			self = this;
		event.preventDefault();

		this.getFocusableElements();

		// When in the Media Library and the Media Trash is enabled.
		if ( gc.media.view.settings.mediaTrash &&
			'edit-metadata' === this.controller.content.mode() ) {

			this.model.set( 'status', 'trash' );
			this.model.save().done( function() {
				library._requery( true );
				/*
				 * @todo We need to move focus back to the previous, next, or first
				 * attachment but the library gets re-queried and refreshed.
				 * Thus, the references to the previous attachments are lost.
				 * We need an alternate method.
				 */
				self.moveFocusToLastFallback();
			} );
		} else {
			this.model.destroy();
			this.moveFocus();
		}
	},

	/**
	 * Untrashes an attachment.
	 *
	 *
	 * @param {MouseEvent} event A click event.
	 *
	 * @return {void}
	 */
	untrashAttachment: function( event ) {
		var library = this.controller.library;
		event.preventDefault();

		this.model.set( 'status', 'inherit' );
		this.model.save().done( function() {
			library._requery( true );
		} );
	},

	/**
	 * Opens the edit page for a specific attachment.
	 *
	 *
	 * @param {MouseEvent} event A click event.
	 *
	 * @return {void}
	 */
	editAttachment: function( event ) {
		var editState = this.controller.states.get( 'edit-image' );
		if ( window.imageEdit && editState ) {
			event.preventDefault();

			editState.set( 'image', this.model );
			this.controller.setState( 'edit-image' );
		} else {
			this.$el.addClass('needs-refresh');
		}
	},

	/**
	 * Triggers an event on the controller when reverse tabbing (shift+tab).
	 *
	 * This event can be used to make sure to move the focus correctly.
	 *
	 *
	 * @fires gc.media.controller.MediaLibrary#attachment:details:shift-tab
	 * @fires gc.media.controller.MediaLibrary#attachment:keydown:arrow
	 *
	 * @param {KeyboardEvent} event A keyboard event.
	 *
	 * @return {boolean|void} Returns false or undefined.
	 */
	toggleSelectionHandler: function( event ) {
		if ( 'keydown' === event.type && 9 === event.keyCode && event.shiftKey && event.target === this.$( ':tabbable' ).get( 0 ) ) {
			this.controller.trigger( 'attachment:details:shift-tab', event );
			return false;
		}
	},

	render: function() {
		Attachment.prototype.render.apply( this, arguments );

		gc.media.mixin.removeAllPlayers();
		this.$( 'audio, video' ).each( function (i, elem) {
			var el = gc.media.view.MediaDetails.prepareSrc( elem );
			new window.MediaElementPlayer( el, gc.media.mixin.mejsSettings );
		} );
	}
});

module.exports = Details;
