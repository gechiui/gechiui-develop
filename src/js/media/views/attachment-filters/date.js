var l10n = gc.media.view.l10n,
	DateFilter;

/**
 * A filter dropdown for month/dates.
 *
 * @memberOf gc.media.view.AttachmentFilters
 *
 * @class
 * @augments gc.media.view.AttachmentFilters
 * @augments gc.media.View
 * @augments gc.Backbone.View
 * @augments Backbone.View
 */
DateFilter = gc.media.view.AttachmentFilters.extend(/** @lends gc.media.view.AttachmentFilters.Date.prototype */{
	id: 'media-attachment-date-filters',

	createFilters: function() {
		var filters = {};
		_.each( gc.media.view.settings.months || {}, function( value, index ) {
			filters[ index ] = {
				text: value.text,
				props: {
					year: value.year,
					monthnum: value.month
				}
			};
		});
		filters.all = {
			text:  l10n.allDates,
			props: {
				monthnum: false,
				year:  false
			},
			priority: 10
		};
		this.filters = filters;
	}
});

module.exports = DateFilter;
