var l10n = gc.media.view.l10n,
	Uploaded;

/**
 * gc.media.view.AttachmentFilters.Uploaded
 *
 * @memberOf gc.media.view.AttachmentFilters
 *
 * @class
 * @augments gc.media.view.AttachmentFilters
 * @augments gc.media.View
 * @augments gc.Backbone.View
 * @augments Backbone.View
 */
Uploaded = gc.media.view.AttachmentFilters.extend(/** @lends gc.media.view.AttachmentFilters.Uploaded.prototype */{
	createFilters: function() {
		var type = this.model.get('type'),
			types = gc.media.view.settings.mimeTypes,
			uid = window.userSettings ? parseInt( window.userSettings.uid, 10 ) : 0,
			text;

		if ( types && type ) {
			text = types[ type ];
		}

		this.filters = {
			all: {
				text:  text || l10n.allMediaItems,
				props: {
					uploadedTo: null,
					orderby: 'date',
					order:   'DESC',
					author:	 null
				},
				priority: 10
			},

			uploaded: {
				text:  l10n.uploadedToThisPost,
				props: {
					uploadedTo: gc.media.view.settings.post.id,
					orderby: 'menuOrder',
					order:   'ASC',
					author:	 null
				},
				priority: 20
			},

			unattached: {
				text:  l10n.unattached,
				props: {
					uploadedTo: 0,
					orderby: 'menuOrder',
					order:   'ASC',
					author:	 null
				},
				priority: 50
			}
		};

		if ( uid ) {
			this.filters.mine = {
				text:  l10n.mine,
				props: {
					orderby: 'date',
					order:   'DESC',
					author:  uid
				},
				priority: 50
			};
		}
	}
});

module.exports = Uploaded;
