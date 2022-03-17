/* global mockedApiResponse, Backbone, jsWidgetsEndpointSchema */
/**
 * @var mockedApiResponse defined in gc-api-generated.js
 */

var pathToData = {
	'gc-json/gc/v2/': mockedApiResponse.Schema,
	'gc-json/gc/v2/categories': mockedApiResponse.CategoriesCollection,
	'gc-json/gc/v2/comments': mockedApiResponse.CommentsCollection,
	'gc-json/gc/v2/media': mockedApiResponse.MediaCollection,
	'gc-json/gc/v2/pages': mockedApiResponse.PagesCollection,
	'gc-json/gc/v2/posts': mockedApiResponse.PostsCollection,
	'gc-json/gc/v2/statuses': mockedApiResponse.StatusesCollection,
	'gc-json/gc/v2/tags': mockedApiResponse.TagsCollection,
	'gc-json/gc/v2/taxonomies': mockedApiResponse.TaxonomiesCollection,
	'gc-json/gc/v2/types': mockedApiResponse.TypesCollection,
	'gc-json/gc/v2/users': mockedApiResponse.UsersCollection,
	'gc-json/gc/v2/category': mockedApiResponse.CategoryModel,
	'gc-json/gc/v2/media1': mockedApiResponse.MediaModel,
	'gc-json/gc/v2/page': mockedApiResponse.PageModel,
	'gc-json/gc/v2/post': mockedApiResponse.PostModel,
	'gc-json/gc/v2/tag': mockedApiResponse.TagModel,
	'gc-json/gc/v2/user': mockedApiResponse.UserModel,
	'gc-json/gc/v2/taxonomy': mockedApiResponse.TaxonomyModel,
	'gc-json/gc/v2/status': mockedApiResponse.StatusModel,
	'gc-json/gc/v2/type': mockedApiResponse.TypeModel,
	'gc-json/js-widgets/v1/': jsWidgetsEndpointSchema,
	'gc-json/gc/v2/users/me': mockedApiResponse.me,
	'gc-json/gc/v2/settings': mockedApiResponse.settings
};

/**
 * Mock the ajax callbacks for our tests.
 *
 * @param  {object} param The parameters sent to the ajax request.
 *
 * @return {Object}       A jQuery deferred object that resolves with the mapped data.
 */
Backbone.ajax = function ( param ) {

	var data,
		request = param.url
			.replace( 'http://remotehost/', '' )
			.replace( 'http://localhost/', '' );

	if ( pathToData[ request ] ) {
		data = pathToData[ request ];
	}

	// Call success handler.
	param.success( data );
	var deferred = jQuery.Deferred();

	// Resolve the deferred with the mocked data.
	deferred.resolve( data );

	// Return the deferred promise that will resolve with the expected data.
	return deferred.promise();

};
