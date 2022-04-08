this["gc"] = this["gc"] || {}; this["gc"]["url"] =
/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./node_modules/@gechiui/url/build-module/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/@gechiui/url/build-module/add-query-args.js":
/*!******************************************************************!*\
  !*** ./node_modules/@gechiui/url/build-module/add-query-args.js ***!
  \******************************************************************/
/*! exports provided: addQueryArgs */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "addQueryArgs", function() { return addQueryArgs; });
/* harmony import */ var _get_query_args__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./get-query-args */ "./node_modules/@gechiui/url/build-module/get-query-args.js");
/* harmony import */ var _build_query_string__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./build-query-string */ "./node_modules/@gechiui/url/build-module/build-query-string.js");
/**
 * Internal dependencies
 */


/**
 * Appends arguments as querystring to the provided URL. If the URL already
 * includes query arguments, the arguments are merged with (and take precedent
 * over) the existing set.
 *
 * @param {string} [url=''] URL to which arguments should be appended. If omitted,
 *                          only the resulting querystring is returned.
 * @param {Object} [args]   Query arguments to apply to URL.
 *
 * @example
 * ```js
 * const newURL = addQueryArgs( 'https://google.com', { q: 'test' } ); // https://google.com/?q=test
 * ```
 *
 * @return {string} URL with arguments applied.
 */

function addQueryArgs() {
  let url = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
  let args = arguments.length > 1 ? arguments[1] : undefined;

  // If no arguments are to be appended, return original URL.
  if (!args || !Object.keys(args).length) {
    return url;
  }

  let baseUrl = url; // Determine whether URL already had query arguments.

  const queryStringIndex = url.indexOf('?');

  if (queryStringIndex !== -1) {
    // Merge into existing query arguments.
    args = Object.assign(Object(_get_query_args__WEBPACK_IMPORTED_MODULE_0__["getQueryArgs"])(url), args); // Change working base URL to omit previous query arguments.

    baseUrl = baseUrl.substr(0, queryStringIndex);
  }

  return baseUrl + '?' + Object(_build_query_string__WEBPACK_IMPORTED_MODULE_1__["buildQueryString"])(args);
}


/***/ }),

/***/ "./node_modules/@gechiui/url/build-module/build-query-string.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@gechiui/url/build-module/build-query-string.js ***!
  \**********************************************************************/
/*! exports provided: buildQueryString */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "buildQueryString", function() { return buildQueryString; });
/**
 * Generates URL-encoded query string using input query data.
 *
 * It is intended to behave equivalent as PHP's `http_build_query`, configured
 * with encoding type PHP_QUERY_RFC3986 (spaces as `%20`).
 *
 * @example
 * ```js
 * const queryString = buildQueryString( {
 *    simple: 'is ok',
 *    arrays: [ 'are', 'fine', 'too' ],
 *    objects: {
 *       evenNested: {
 *          ok: 'yes',
 *       },
 *    },
 * } );
 * // "simple=is%20ok&arrays%5B0%5D=are&arrays%5B1%5D=fine&arrays%5B2%5D=too&objects%5BevenNested%5D%5Bok%5D=yes"
 * ```
 *
 * @param {Record<string,*>} data Data to encode.
 *
 * @return {string} Query string.
 */
function buildQueryString(data) {
  let string = '';
  const stack = Object.entries(data);
  let pair;

  while (pair = stack.shift()) {
    let [key, value] = pair; // Support building deeply nested data, from array or object values.

    const hasNestedData = Array.isArray(value) || value && value.constructor === Object;

    if (hasNestedData) {
      // Push array or object values onto the stack as composed of their
      // original key and nested index or key, retaining order by a
      // combination of Array#reverse and Array#unshift onto the stack.
      const valuePairs = Object.entries(value).reverse();

      for (const [member, memberValue] of valuePairs) {
        stack.unshift([`${key}[${member}]`, memberValue]);
      }
    } else if (value !== undefined) {
      // Null is treated as special case, equivalent to empty string.
      if (value === null) {
        value = '';
      }

      string += '&' + [key, value].map(encodeURIComponent).join('=');
    }
  } // Loop will concatenate with leading `&`, but it's only expected for all
  // but the first query parameter. This strips the leading `&`, while still
  // accounting for the case that the string may in-fact be empty.


  return string.substr(1);
}


/***/ }),

/***/ "./node_modules/@gechiui/url/build-module/clean-for-slug.js":
/*!******************************************************************!*\
  !*** ./node_modules/@gechiui/url/build-module/clean-for-slug.js ***!
  \******************************************************************/
/*! exports provided: cleanForSlug */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "cleanForSlug", function() { return cleanForSlug; });
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_0__);
/**
 * External dependencies
 */

/**
 * Performs some basic cleanup of a string for use as a post slug.
 *
 * This replicates some of what `sanitize_title()` does in GeChiUI core, but
 * is only designed to approximate what the slug will be.
 *
 * Converts Latin-1 Supplement and Latin Extended-A letters to basic Latin
 * letters. Removes combining diacritical marks. Converts whitespace, periods,
 * and forward slashes to hyphens. Removes any remaining non-word characters
 * except hyphens. Converts remaining string to lowercase. It does not account
 * for octets, HTML entities, or other encoded characters.
 *
 * @param {string} string Title or slug to be processed.
 *
 * @return {string} Processed string.
 */

function cleanForSlug(string) {
  if (!string) {
    return '';
  }

  return Object(lodash__WEBPACK_IMPORTED_MODULE_0__["trim"])(Object(lodash__WEBPACK_IMPORTED_MODULE_0__["deburr"])(string).replace(/[\s\./]+/g, '-').replace(/[^\w-]+/g, '').toLowerCase(), '-');
}


/***/ }),

/***/ "./node_modules/@gechiui/url/build-module/filter-url-for-display.js":
/*!**************************************************************************!*\
  !*** ./node_modules/@gechiui/url/build-module/filter-url-for-display.js ***!
  \**************************************************************************/
/*! exports provided: filterURLForDisplay */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "filterURLForDisplay", function() { return filterURLForDisplay; });
/**
 * Returns a URL for display.
 *
 * @param {string}      url       Original URL.
 * @param {number|null} maxLength URL length.
 *
 * @example
 * ```js
 * const displayUrl = filterURLForDisplay( 'https://www.gechiui.com/gutenberg/' ); // www.gechiui.com/gutenberg
 * const imageUrl = filterURLForDisplay( 'https://www.gechiui.com/gc-content/uploads/img.png', 20 ); // …ent/uploads/img.png
 * ```
 *
 * @return {string} Displayed URL.
 */
function filterURLForDisplay(url) {
  let maxLength = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
  // Remove protocol and www prefixes.
  let filteredURL = url.replace(/^(?:https?:)\/\/(?:www\.)?/, ''); // Ends with / and only has that single slash, strip it.

  if (filteredURL.match(/^[^\/]+\/$/)) {
    filteredURL = filteredURL.replace('/', '');
  }

  const mediaRegexp = /([\w|:])*\.(?:jpg|jpeg|gif|png|svg)/;

  if (!maxLength || filteredURL.length <= maxLength || !filteredURL.match(mediaRegexp)) {
    return filteredURL;
  } // If the file is not greater than max length, return last portion of URL.


  filteredURL = filteredURL.split('?')[0];
  const urlPieces = filteredURL.split('/');
  const file = urlPieces[urlPieces.length - 1];

  if (file.length <= maxLength) {
    return '…' + filteredURL.slice(-maxLength);
  } // If the file is greater than max length, truncate the file.


  const index = file.lastIndexOf('.');
  const [fileName, extension] = [file.slice(0, index), file.slice(index + 1)];
  const truncatedFile = fileName.slice(-3) + '.' + extension;
  return file.slice(0, maxLength - truncatedFile.length - 1) + '…' + truncatedFile;
}


/***/ }),

/***/ "./node_modules/@gechiui/url/build-module/get-authority.js":
/*!*****************************************************************!*\
  !*** ./node_modules/@gechiui/url/build-module/get-authority.js ***!
  \*****************************************************************/
/*! exports provided: getAuthority */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getAuthority", function() { return getAuthority; });
/**
 * Returns the authority part of the URL.
 *
 * @param {string} url The full URL.
 *
 * @example
 * ```js
 * const authority1 = getAuthority( 'https://www.gechiui.com/help/' ); // 'www.gechiui.com'
 * const authority2 = getAuthority( 'https://localhost:8080/test/' ); // 'localhost:8080'
 * ```
 *
 * @return {string|void} The authority part of the URL.
 */
function getAuthority(url) {
  const matches = /^[^\/\s:]+:(?:\/\/)?\/?([^\/\s#?]+)[\/#?]{0,1}\S*$/.exec(url);

  if (matches) {
    return matches[1];
  }
}


/***/ }),

/***/ "./node_modules/@gechiui/url/build-module/get-filename.js":
/*!****************************************************************!*\
  !*** ./node_modules/@gechiui/url/build-module/get-filename.js ***!
  \****************************************************************/
/*! exports provided: getFilename */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getFilename", function() { return getFilename; });
/**
 * Returns the filename part of the URL.
 *
 * @param {string} url The full URL.
 *
 * @example
 * ```js
 * const filename1 = getFilename( 'http://localhost:8080/this/is/a/test.jpg' ); // 'test.jpg'
 * const filename2 = getFilename( '/this/is/a/test.png' ); // 'test.png'
 * ```
 *
 * @return {string|void} The filename part of the URL.
 */
function getFilename(url) {
  let filename;

  try {
    filename = new URL(url, 'http://example.com').pathname.split('/').pop();
  } catch (error) {}

  if (filename) {
    return filename;
  }
}


/***/ }),

/***/ "./node_modules/@gechiui/url/build-module/get-fragment.js":
/*!****************************************************************!*\
  !*** ./node_modules/@gechiui/url/build-module/get-fragment.js ***!
  \****************************************************************/
/*! exports provided: getFragment */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getFragment", function() { return getFragment; });
/**
 * Returns the fragment part of the URL.
 *
 * @param {string} url The full URL
 *
 * @example
 * ```js
 * const fragment1 = getFragment( 'http://localhost:8080/this/is/a/test?query=true#fragment' ); // '#fragment'
 * const fragment2 = getFragment( 'https://www.gechiui.com#another-fragment?query=true' ); // '#another-fragment'
 * ```
 *
 * @return {string|void} The fragment part of the URL.
 */
function getFragment(url) {
  const matches = /^\S+?(#[^\s\?]*)/.exec(url);

  if (matches) {
    return matches[1];
  }
}


/***/ }),

/***/ "./node_modules/@gechiui/url/build-module/get-path-and-query-string.js":
/*!*****************************************************************************!*\
  !*** ./node_modules/@gechiui/url/build-module/get-path-and-query-string.js ***!
  \*****************************************************************************/
/*! exports provided: getPathAndQueryString */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getPathAndQueryString", function() { return getPathAndQueryString; });
/* harmony import */ var ___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! . */ "./node_modules/@gechiui/url/build-module/index.js");
/**
 * Internal dependencies
 */

/**
 * Returns the path part and query string part of the URL.
 *
 * @param {string} url The full URL.
 *
 * @example
 * ```js
 * const pathAndQueryString1 = getPathAndQueryString( 'http://localhost:8080/this/is/a/test?query=true' ); // '/this/is/a/test?query=true'
 * const pathAndQueryString2 = getPathAndQueryString( 'https://www.gechiui.com/help/faq/' ); // '/help/faq'
 * ```
 *
 * @return {string} The path part and query string part of the URL.
 */

function getPathAndQueryString(url) {
  const path = Object(___WEBPACK_IMPORTED_MODULE_0__["getPath"])(url);
  const queryString = Object(___WEBPACK_IMPORTED_MODULE_0__["getQueryString"])(url);
  let value = '/';
  if (path) value += path;
  if (queryString) value += `?${queryString}`;
  return value;
}


/***/ }),

/***/ "./node_modules/@gechiui/url/build-module/get-path.js":
/*!************************************************************!*\
  !*** ./node_modules/@gechiui/url/build-module/get-path.js ***!
  \************************************************************/
/*! exports provided: getPath */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getPath", function() { return getPath; });
/**
 * Returns the path part of the URL.
 *
 * @param {string} url The full URL.
 *
 * @example
 * ```js
 * const path1 = getPath( 'http://localhost:8080/this/is/a/test?query=true' ); // 'this/is/a/test'
 * const path2 = getPath( 'https://www.gechiui.com/help/faq/' ); // 'help/faq'
 * ```
 *
 * @return {string|void} The path part of the URL.
 */
function getPath(url) {
  const matches = /^[^\/\s:]+:(?:\/\/)?[^\/\s#?]+[\/]([^\s#?]+)[#?]{0,1}\S*$/.exec(url);

  if (matches) {
    return matches[1];
  }
}


/***/ }),

/***/ "./node_modules/@gechiui/url/build-module/get-protocol.js":
/*!****************************************************************!*\
  !*** ./node_modules/@gechiui/url/build-module/get-protocol.js ***!
  \****************************************************************/
/*! exports provided: getProtocol */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getProtocol", function() { return getProtocol; });
/**
 * Returns the protocol part of the URL.
 *
 * @param {string} url The full URL.
 *
 * @example
 * ```js
 * const protocol1 = getProtocol( 'tel:012345678' ); // 'tel:'
 * const protocol2 = getProtocol( 'https://www.gechiui.com' ); // 'https:'
 * ```
 *
 * @return {string|void} The protocol part of the URL.
 */
function getProtocol(url) {
  const matches = /^([^\s:]+:)/.exec(url);

  if (matches) {
    return matches[1];
  }
}


/***/ }),

/***/ "./node_modules/@gechiui/url/build-module/get-query-arg.js":
/*!*****************************************************************!*\
  !*** ./node_modules/@gechiui/url/build-module/get-query-arg.js ***!
  \*****************************************************************/
/*! exports provided: getQueryArg */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getQueryArg", function() { return getQueryArg; });
/* harmony import */ var _get_query_args__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./get-query-args */ "./node_modules/@gechiui/url/build-module/get-query-args.js");
/**
 * Internal dependencies
 */

/**
 * @typedef {{[key: string]: QueryArgParsed}} QueryArgObject
 */

/**
 * @typedef {string|string[]|QueryArgObject} QueryArgParsed
 */

/**
 * Returns a single query argument of the url
 *
 * @param {string} url URL.
 * @param {string} arg Query arg name.
 *
 * @example
 * ```js
 * const foo = getQueryArg( 'https://www.gechiui.com?foo=bar&bar=baz', 'foo' ); // bar
 * ```
 *
 * @return {QueryArgParsed|void} Query arg value.
 */

function getQueryArg(url, arg) {
  return Object(_get_query_args__WEBPACK_IMPORTED_MODULE_0__["getQueryArgs"])(url)[arg];
}


/***/ }),

/***/ "./node_modules/@gechiui/url/build-module/get-query-args.js":
/*!******************************************************************!*\
  !*** ./node_modules/@gechiui/url/build-module/get-query-args.js ***!
  \******************************************************************/
/*! exports provided: getQueryArgs */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getQueryArgs", function() { return getQueryArgs; });
/* harmony import */ var _get_query_string__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./get-query-string */ "./node_modules/@gechiui/url/build-module/get-query-string.js");
/**
 * Internal dependencies
 */

/** @typedef {import('./get-query-arg').QueryArgParsed} QueryArgParsed */

/**
 * @typedef {Record<string,QueryArgParsed>} QueryArgs
 */

/**
 * Sets a value in object deeply by a given array of path segments. Mutates the
 * object reference.
 *
 * @param {Record<string,*>} object Object in which to assign.
 * @param {string[]}         path   Path segment at which to set value.
 * @param {*}                value  Value to set.
 */

function setPath(object, path, value) {
  const length = path.length;
  const lastIndex = length - 1;

  for (let i = 0; i < length; i++) {
    let key = path[i];

    if (!key && Array.isArray(object)) {
      // If key is empty string and next value is array, derive key from
      // the current length of the array.
      key = object.length.toString();
    } // If the next key in the path is numeric (or empty string), it will be
    // created as an array. Otherwise, it will be created as an object.


    const isNextKeyArrayIndex = !isNaN(Number(path[i + 1]));
    object[key] = i === lastIndex ? // If at end of path, assign the intended value.
    value : // Otherwise, advance to the next object in the path, creating
    // it if it does not yet exist.
    object[key] || (isNextKeyArrayIndex ? [] : {});

    if (Array.isArray(object[key]) && !isNextKeyArrayIndex) {
      // If we current key is non-numeric, but the next value is an
      // array, coerce the value to an object.
      object[key] = { ...object[key]
      };
    } // Update working reference object to the next in the path.


    object = object[key];
  }
}
/**
 * Returns an object of query arguments of the given URL. If the given URL is
 * invalid or has no querystring, an empty object is returned.
 *
 * @param {string} url URL.
 *
 * @example
 * ```js
 * const foo = getQueryArgs( 'https://www.gechiui.com?foo=bar&bar=baz' );
 * // { "foo": "bar", "bar": "baz" }
 * ```
 *
 * @return {QueryArgs} Query args object.
 */


function getQueryArgs(url) {
  return (Object(_get_query_string__WEBPACK_IMPORTED_MODULE_0__["getQueryString"])(url) || '' // Normalize space encoding, accounting for PHP URL encoding
  // corresponding to `application/x-www-form-urlencoded`.
  //
  // See: https://tools.ietf.org/html/rfc1866#section-8.2.1
  ).replace(/\+/g, '%20').split('&').reduce((accumulator, keyValue) => {
    const [key, value = ''] = keyValue.split('=') // Filtering avoids decoding as `undefined` for value, where
    // default is restored in destructuring assignment.
    .filter(Boolean).map(decodeURIComponent);

    if (key) {
      const segments = key.replace(/\]/g, '').split('[');
      setPath(accumulator, segments, value);
    }

    return accumulator;
  }, {});
}


/***/ }),

/***/ "./node_modules/@gechiui/url/build-module/get-query-string.js":
/*!********************************************************************!*\
  !*** ./node_modules/@gechiui/url/build-module/get-query-string.js ***!
  \********************************************************************/
/*! exports provided: getQueryString */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getQueryString", function() { return getQueryString; });
/**
 * Returns the query string part of the URL.
 *
 * @param {string} url The full URL.
 *
 * @example
 * ```js
 * const queryString = getQueryString( 'http://localhost:8080/this/is/a/test?query=true#fragment' ); // 'query=true'
 * ```
 *
 * @return {string|void} The query string part of the URL.
 */
function getQueryString(url) {
  let query;

  try {
    query = new URL(url, 'http://example.com').search.substring(1);
  } catch (error) {}

  if (query) {
    return query;
  }
}


/***/ }),

/***/ "./node_modules/@gechiui/url/build-module/has-query-arg.js":
/*!*****************************************************************!*\
  !*** ./node_modules/@gechiui/url/build-module/has-query-arg.js ***!
  \*****************************************************************/
/*! exports provided: hasQueryArg */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "hasQueryArg", function() { return hasQueryArg; });
/* harmony import */ var _get_query_arg__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./get-query-arg */ "./node_modules/@gechiui/url/build-module/get-query-arg.js");
/**
 * Internal dependencies
 */

/**
 * Determines whether the URL contains a given query arg.
 *
 * @param {string} url URL.
 * @param {string} arg Query arg name.
 *
 * @example
 * ```js
 * const hasBar = hasQueryArg( 'https://www.gechiui.com?foo=bar&bar=baz', 'bar' ); // true
 * ```
 *
 * @return {boolean} Whether or not the URL contains the query arg.
 */

function hasQueryArg(url, arg) {
  return Object(_get_query_arg__WEBPACK_IMPORTED_MODULE_0__["getQueryArg"])(url, arg) !== undefined;
}


/***/ }),

/***/ "./node_modules/@gechiui/url/build-module/index.js":
/*!*********************************************************!*\
  !*** ./node_modules/@gechiui/url/build-module/index.js ***!
  \*********************************************************/
/*! exports provided: isURL, isEmail, getProtocol, isValidProtocol, getAuthority, isValidAuthority, getPath, isValidPath, getQueryString, buildQueryString, isValidQueryString, getPathAndQueryString, getFragment, isValidFragment, addQueryArgs, getQueryArg, getQueryArgs, hasQueryArg, removeQueryArgs, prependHTTP, safeDecodeURI, safeDecodeURIComponent, filterURLForDisplay, cleanForSlug, getFilename, normalizePath */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _is_url__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./is-url */ "./node_modules/@gechiui/url/build-module/is-url.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "isURL", function() { return _is_url__WEBPACK_IMPORTED_MODULE_0__["isURL"]; });

/* harmony import */ var _is_email__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./is-email */ "./node_modules/@gechiui/url/build-module/is-email.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "isEmail", function() { return _is_email__WEBPACK_IMPORTED_MODULE_1__["isEmail"]; });

/* harmony import */ var _get_protocol__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./get-protocol */ "./node_modules/@gechiui/url/build-module/get-protocol.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "getProtocol", function() { return _get_protocol__WEBPACK_IMPORTED_MODULE_2__["getProtocol"]; });

/* harmony import */ var _is_valid_protocol__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./is-valid-protocol */ "./node_modules/@gechiui/url/build-module/is-valid-protocol.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "isValidProtocol", function() { return _is_valid_protocol__WEBPACK_IMPORTED_MODULE_3__["isValidProtocol"]; });

/* harmony import */ var _get_authority__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./get-authority */ "./node_modules/@gechiui/url/build-module/get-authority.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "getAuthority", function() { return _get_authority__WEBPACK_IMPORTED_MODULE_4__["getAuthority"]; });

/* harmony import */ var _is_valid_authority__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./is-valid-authority */ "./node_modules/@gechiui/url/build-module/is-valid-authority.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "isValidAuthority", function() { return _is_valid_authority__WEBPACK_IMPORTED_MODULE_5__["isValidAuthority"]; });

/* harmony import */ var _get_path__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./get-path */ "./node_modules/@gechiui/url/build-module/get-path.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "getPath", function() { return _get_path__WEBPACK_IMPORTED_MODULE_6__["getPath"]; });

/* harmony import */ var _is_valid_path__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./is-valid-path */ "./node_modules/@gechiui/url/build-module/is-valid-path.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "isValidPath", function() { return _is_valid_path__WEBPACK_IMPORTED_MODULE_7__["isValidPath"]; });

/* harmony import */ var _get_query_string__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./get-query-string */ "./node_modules/@gechiui/url/build-module/get-query-string.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "getQueryString", function() { return _get_query_string__WEBPACK_IMPORTED_MODULE_8__["getQueryString"]; });

/* harmony import */ var _build_query_string__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./build-query-string */ "./node_modules/@gechiui/url/build-module/build-query-string.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "buildQueryString", function() { return _build_query_string__WEBPACK_IMPORTED_MODULE_9__["buildQueryString"]; });

/* harmony import */ var _is_valid_query_string__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./is-valid-query-string */ "./node_modules/@gechiui/url/build-module/is-valid-query-string.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "isValidQueryString", function() { return _is_valid_query_string__WEBPACK_IMPORTED_MODULE_10__["isValidQueryString"]; });

/* harmony import */ var _get_path_and_query_string__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./get-path-and-query-string */ "./node_modules/@gechiui/url/build-module/get-path-and-query-string.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "getPathAndQueryString", function() { return _get_path_and_query_string__WEBPACK_IMPORTED_MODULE_11__["getPathAndQueryString"]; });

/* harmony import */ var _get_fragment__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./get-fragment */ "./node_modules/@gechiui/url/build-module/get-fragment.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "getFragment", function() { return _get_fragment__WEBPACK_IMPORTED_MODULE_12__["getFragment"]; });

/* harmony import */ var _is_valid_fragment__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ./is-valid-fragment */ "./node_modules/@gechiui/url/build-module/is-valid-fragment.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "isValidFragment", function() { return _is_valid_fragment__WEBPACK_IMPORTED_MODULE_13__["isValidFragment"]; });

/* harmony import */ var _add_query_args__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ./add-query-args */ "./node_modules/@gechiui/url/build-module/add-query-args.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "addQueryArgs", function() { return _add_query_args__WEBPACK_IMPORTED_MODULE_14__["addQueryArgs"]; });

/* harmony import */ var _get_query_arg__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! ./get-query-arg */ "./node_modules/@gechiui/url/build-module/get-query-arg.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "getQueryArg", function() { return _get_query_arg__WEBPACK_IMPORTED_MODULE_15__["getQueryArg"]; });

/* harmony import */ var _get_query_args__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! ./get-query-args */ "./node_modules/@gechiui/url/build-module/get-query-args.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "getQueryArgs", function() { return _get_query_args__WEBPACK_IMPORTED_MODULE_16__["getQueryArgs"]; });

/* harmony import */ var _has_query_arg__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__(/*! ./has-query-arg */ "./node_modules/@gechiui/url/build-module/has-query-arg.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "hasQueryArg", function() { return _has_query_arg__WEBPACK_IMPORTED_MODULE_17__["hasQueryArg"]; });

/* harmony import */ var _remove_query_args__WEBPACK_IMPORTED_MODULE_18__ = __webpack_require__(/*! ./remove-query-args */ "./node_modules/@gechiui/url/build-module/remove-query-args.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "removeQueryArgs", function() { return _remove_query_args__WEBPACK_IMPORTED_MODULE_18__["removeQueryArgs"]; });

/* harmony import */ var _prepend_http__WEBPACK_IMPORTED_MODULE_19__ = __webpack_require__(/*! ./prepend-http */ "./node_modules/@gechiui/url/build-module/prepend-http.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "prependHTTP", function() { return _prepend_http__WEBPACK_IMPORTED_MODULE_19__["prependHTTP"]; });

/* harmony import */ var _safe_decode_uri__WEBPACK_IMPORTED_MODULE_20__ = __webpack_require__(/*! ./safe-decode-uri */ "./node_modules/@gechiui/url/build-module/safe-decode-uri.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "safeDecodeURI", function() { return _safe_decode_uri__WEBPACK_IMPORTED_MODULE_20__["safeDecodeURI"]; });

/* harmony import */ var _safe_decode_uri_component__WEBPACK_IMPORTED_MODULE_21__ = __webpack_require__(/*! ./safe-decode-uri-component */ "./node_modules/@gechiui/url/build-module/safe-decode-uri-component.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "safeDecodeURIComponent", function() { return _safe_decode_uri_component__WEBPACK_IMPORTED_MODULE_21__["safeDecodeURIComponent"]; });

/* harmony import */ var _filter_url_for_display__WEBPACK_IMPORTED_MODULE_22__ = __webpack_require__(/*! ./filter-url-for-display */ "./node_modules/@gechiui/url/build-module/filter-url-for-display.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "filterURLForDisplay", function() { return _filter_url_for_display__WEBPACK_IMPORTED_MODULE_22__["filterURLForDisplay"]; });

/* harmony import */ var _clean_for_slug__WEBPACK_IMPORTED_MODULE_23__ = __webpack_require__(/*! ./clean-for-slug */ "./node_modules/@gechiui/url/build-module/clean-for-slug.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "cleanForSlug", function() { return _clean_for_slug__WEBPACK_IMPORTED_MODULE_23__["cleanForSlug"]; });

/* harmony import */ var _get_filename__WEBPACK_IMPORTED_MODULE_24__ = __webpack_require__(/*! ./get-filename */ "./node_modules/@gechiui/url/build-module/get-filename.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "getFilename", function() { return _get_filename__WEBPACK_IMPORTED_MODULE_24__["getFilename"]; });

/* harmony import */ var _normalize_path__WEBPACK_IMPORTED_MODULE_25__ = __webpack_require__(/*! ./normalize-path */ "./node_modules/@gechiui/url/build-module/normalize-path.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "normalizePath", function() { return _normalize_path__WEBPACK_IMPORTED_MODULE_25__["normalizePath"]; });





























/***/ }),

/***/ "./node_modules/@gechiui/url/build-module/is-email.js":
/*!************************************************************!*\
  !*** ./node_modules/@gechiui/url/build-module/is-email.js ***!
  \************************************************************/
/*! exports provided: isEmail */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "isEmail", function() { return isEmail; });
const EMAIL_REGEXP = /^(mailto:)?[a-z0-9._%+-]+@[a-z0-9][a-z0-9.-]*\.[a-z]{2,63}$/i;
/**
 * Determines whether the given string looks like an email.
 *
 * @param {string} email The string to scrutinise.
 *
 * @example
 * ```js
 * const isEmail = isEmail( 'hello@www.gechiui.com' ); // true
 * ```
 *
 * @return {boolean} Whether or not it looks like an email.
 */

function isEmail(email) {
  return EMAIL_REGEXP.test(email);
}


/***/ }),

/***/ "./node_modules/@gechiui/url/build-module/is-url.js":
/*!**********************************************************!*\
  !*** ./node_modules/@gechiui/url/build-module/is-url.js ***!
  \**********************************************************/
/*! exports provided: isURL */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "isURL", function() { return isURL; });
/**
 * Determines whether the given string looks like a URL.
 *
 * @param {string} url The string to scrutinise.
 *
 * @example
 * ```js
 * const isURL = isURL( 'https://www.gechiui.com' ); // true
 * ```
 *
 * @see https://url.spec.whatwg.org/
 * @see https://url.spec.whatwg.org/#valid-url-string
 *
 * @return {boolean} Whether or not it looks like a URL.
 */
function isURL(url) {
  // A URL can be considered value if the `URL` constructor is able to parse
  // it. The constructor throws an error for an invalid URL.
  try {
    new URL(url);
    return true;
  } catch {
    return false;
  }
}


/***/ }),

/***/ "./node_modules/@gechiui/url/build-module/is-valid-authority.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@gechiui/url/build-module/is-valid-authority.js ***!
  \**********************************************************************/
/*! exports provided: isValidAuthority */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "isValidAuthority", function() { return isValidAuthority; });
/**
 * Checks for invalid characters within the provided authority.
 *
 * @param {string} authority A string containing the URL authority.
 *
 * @example
 * ```js
 * const isValid = isValidAuthority( 'www.gechiui.com' ); // true
 * const isNotValid = isValidAuthority( 'gechiui#org' ); // false
 * ```
 *
 * @return {boolean} True if the argument contains a valid authority.
 */
function isValidAuthority(authority) {
  if (!authority) {
    return false;
  }

  return /^[^\s#?]+$/.test(authority);
}


/***/ }),

/***/ "./node_modules/@gechiui/url/build-module/is-valid-fragment.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@gechiui/url/build-module/is-valid-fragment.js ***!
  \*********************************************************************/
/*! exports provided: isValidFragment */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "isValidFragment", function() { return isValidFragment; });
/**
 * Checks for invalid characters within the provided fragment.
 *
 * @param {string} fragment The url fragment.
 *
 * @example
 * ```js
 * const isValid = isValidFragment( '#valid-fragment' ); // true
 * const isNotValid = isValidFragment( '#invalid-#fragment' ); // false
 * ```
 *
 * @return {boolean} True if the argument contains a valid fragment.
 */
function isValidFragment(fragment) {
  if (!fragment) {
    return false;
  }

  return /^#[^\s#?\/]*$/.test(fragment);
}


/***/ }),

/***/ "./node_modules/@gechiui/url/build-module/is-valid-path.js":
/*!*****************************************************************!*\
  !*** ./node_modules/@gechiui/url/build-module/is-valid-path.js ***!
  \*****************************************************************/
/*! exports provided: isValidPath */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "isValidPath", function() { return isValidPath; });
/**
 * Checks for invalid characters within the provided path.
 *
 * @param {string} path The URL path.
 *
 * @example
 * ```js
 * const isValid = isValidPath( 'test/path/' ); // true
 * const isNotValid = isValidPath( '/invalid?test/path/' ); // false
 * ```
 *
 * @return {boolean} True if the argument contains a valid path
 */
function isValidPath(path) {
  if (!path) {
    return false;
  }

  return /^[^\s#?]+$/.test(path);
}


/***/ }),

/***/ "./node_modules/@gechiui/url/build-module/is-valid-protocol.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@gechiui/url/build-module/is-valid-protocol.js ***!
  \*********************************************************************/
/*! exports provided: isValidProtocol */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "isValidProtocol", function() { return isValidProtocol; });
/**
 * Tests if a url protocol is valid.
 *
 * @param {string} protocol The url protocol.
 *
 * @example
 * ```js
 * const isValid = isValidProtocol( 'https:' ); // true
 * const isNotValid = isValidProtocol( 'https :' ); // false
 * ```
 *
 * @return {boolean} True if the argument is a valid protocol (e.g. http:, tel:).
 */
function isValidProtocol(protocol) {
  if (!protocol) {
    return false;
  }

  return /^[a-z\-.\+]+[0-9]*:$/i.test(protocol);
}


/***/ }),

/***/ "./node_modules/@gechiui/url/build-module/is-valid-query-string.js":
/*!*************************************************************************!*\
  !*** ./node_modules/@gechiui/url/build-module/is-valid-query-string.js ***!
  \*************************************************************************/
/*! exports provided: isValidQueryString */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "isValidQueryString", function() { return isValidQueryString; });
/**
 * Checks for invalid characters within the provided query string.
 *
 * @param {string} queryString The query string.
 *
 * @example
 * ```js
 * const isValid = isValidQueryString( 'query=true&another=false' ); // true
 * const isNotValid = isValidQueryString( 'query=true?another=false' ); // false
 * ```
 *
 * @return {boolean} True if the argument contains a valid query string.
 */
function isValidQueryString(queryString) {
  if (!queryString) {
    return false;
  }

  return /^[^\s#?\/]+$/.test(queryString);
}


/***/ }),

/***/ "./node_modules/@gechiui/url/build-module/normalize-path.js":
/*!******************************************************************!*\
  !*** ./node_modules/@gechiui/url/build-module/normalize-path.js ***!
  \******************************************************************/
/*! exports provided: normalizePath */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "normalizePath", function() { return normalizePath; });
/**
 * Given a path, returns a normalized path where equal query parameter values
 * will be treated as identical, regardless of order they appear in the original
 * text.
 *
 * @param {string} path Original path.
 *
 * @return {string} Normalized path.
 */
function normalizePath(path) {
  const splitted = path.split('?');
  const query = splitted[1];
  const base = splitted[0];

  if (!query) {
    return base;
  } // 'b=1&c=2&a=5'


  return base + '?' + query // [ 'b=1', 'c=2', 'a=5' ]
  .split('&') // [ [ 'b, '1' ], [ 'c', '2' ], [ 'a', '5' ] ]
  .map(entry => entry.split('=')) // [ [ 'a', '5' ], [ 'b, '1' ], [ 'c', '2' ] ]
  .sort((a, b) => a[0].localeCompare(b[0])) // [ 'a=5', 'b=1', 'c=2' ]
  .map(pair => pair.join('=')) // 'a=5&b=1&c=2'
  .join('&');
}


/***/ }),

/***/ "./node_modules/@gechiui/url/build-module/prepend-http.js":
/*!****************************************************************!*\
  !*** ./node_modules/@gechiui/url/build-module/prepend-http.js ***!
  \****************************************************************/
/*! exports provided: prependHTTP */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "prependHTTP", function() { return prependHTTP; });
/* harmony import */ var _is_email__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./is-email */ "./node_modules/@gechiui/url/build-module/is-email.js");
/**
 * Internal dependencies
 */

const USABLE_HREF_REGEXP = /^(?:[a-z]+:|#|\?|\.|\/)/i;
/**
 * Prepends "http://" to a url, if it looks like something that is meant to be a TLD.
 *
 * @param {string} url The URL to test.
 *
 * @example
 * ```js
 * const actualURL = prependHTTP( 'www.gechiui.com' ); // http://www.gechiui.com
 * ```
 *
 * @return {string} The updated URL.
 */

function prependHTTP(url) {
  if (!url) {
    return url;
  }

  url = url.trim();

  if (!USABLE_HREF_REGEXP.test(url) && !Object(_is_email__WEBPACK_IMPORTED_MODULE_0__["isEmail"])(url)) {
    return 'http://' + url;
  }

  return url;
}


/***/ }),

/***/ "./node_modules/@gechiui/url/build-module/remove-query-args.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@gechiui/url/build-module/remove-query-args.js ***!
  \*********************************************************************/
/*! exports provided: removeQueryArgs */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "removeQueryArgs", function() { return removeQueryArgs; });
/* harmony import */ var _get_query_args__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./get-query-args */ "./node_modules/@gechiui/url/build-module/get-query-args.js");
/* harmony import */ var _build_query_string__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./build-query-string */ "./node_modules/@gechiui/url/build-module/build-query-string.js");
/**
 * Internal dependencies
 */


/**
 * Removes arguments from the query string of the url
 *
 * @param {string}    url  URL.
 * @param {...string} args Query Args.
 *
 * @example
 * ```js
 * const newUrl = removeQueryArgs( 'https://www.gechiui.com?foo=bar&bar=baz&baz=foobar', 'foo', 'bar' ); // https://www.gechiui.com?baz=foobar
 * ```
 *
 * @return {string} Updated URL.
 */

function removeQueryArgs(url) {
  const queryStringIndex = url.indexOf('?');

  if (queryStringIndex === -1) {
    return url;
  }

  const query = Object(_get_query_args__WEBPACK_IMPORTED_MODULE_0__["getQueryArgs"])(url);
  const baseURL = url.substr(0, queryStringIndex);

  for (var _len = arguments.length, args = new Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
    args[_key - 1] = arguments[_key];
  }

  args.forEach(arg => delete query[arg]);
  const queryString = Object(_build_query_string__WEBPACK_IMPORTED_MODULE_1__["buildQueryString"])(query);
  return queryString ? baseURL + '?' + queryString : baseURL;
}


/***/ }),

/***/ "./node_modules/@gechiui/url/build-module/safe-decode-uri-component.js":
/*!*****************************************************************************!*\
  !*** ./node_modules/@gechiui/url/build-module/safe-decode-uri-component.js ***!
  \*****************************************************************************/
/*! exports provided: safeDecodeURIComponent */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "safeDecodeURIComponent", function() { return safeDecodeURIComponent; });
/**
 * Safely decodes a URI component with `decodeURIComponent`. Returns the URI component unmodified if
 * `decodeURIComponent` throws an error.
 *
 * @param {string} uriComponent URI component to decode.
 *
 * @return {string} Decoded URI component if possible.
 */
function safeDecodeURIComponent(uriComponent) {
  try {
    return decodeURIComponent(uriComponent);
  } catch (uriComponentError) {
    return uriComponent;
  }
}


/***/ }),

/***/ "./node_modules/@gechiui/url/build-module/safe-decode-uri.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@gechiui/url/build-module/safe-decode-uri.js ***!
  \*******************************************************************/
/*! exports provided: safeDecodeURI */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "safeDecodeURI", function() { return safeDecodeURI; });
/**
 * Safely decodes a URI with `decodeURI`. Returns the URI unmodified if
 * `decodeURI` throws an error.
 *
 * @param {string} uri URI to decode.
 *
 * @example
 * ```js
 * const badUri = safeDecodeURI( '%z' ); // does not throw an Error, simply returns '%z'
 * ```
 *
 * @return {string} Decoded URI if possible.
 */
function safeDecodeURI(uri) {
  try {
    return decodeURI(uri);
  } catch (uriError) {
    return uri;
  }
}


/***/ }),

/***/ "lodash":
/*!*************************!*\
  !*** external "lodash" ***!
  \*************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["lodash"]; }());

/***/ })

/******/ });
//# sourceMappingURL=url.js.map