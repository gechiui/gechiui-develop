this["gc"] = this["gc"] || {}; this["gc"]["apiFetch"] =
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
/******/ 	return __webpack_require__(__webpack_require__.s = "./node_modules/@gechiui/api-fetch/build-module/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/@gechiui/api-fetch/build-module/index.js":
/*!***************************************************************!*\
  !*** ./node_modules/@gechiui/api-fetch/build-module/index.js ***!
  \***************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _gechiui_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @gechiui/i18n */ "@gechiui/i18n");
/* harmony import */ var _gechiui_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _middlewares_nonce__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./middlewares/nonce */ "./node_modules/@gechiui/api-fetch/build-module/middlewares/nonce.js");
/* harmony import */ var _middlewares_root_url__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./middlewares/root-url */ "./node_modules/@gechiui/api-fetch/build-module/middlewares/root-url.js");
/* harmony import */ var _middlewares_preloading__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./middlewares/preloading */ "./node_modules/@gechiui/api-fetch/build-module/middlewares/preloading.js");
/* harmony import */ var _middlewares_fetch_all_middleware__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./middlewares/fetch-all-middleware */ "./node_modules/@gechiui/api-fetch/build-module/middlewares/fetch-all-middleware.js");
/* harmony import */ var _middlewares_namespace_endpoint__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./middlewares/namespace-endpoint */ "./node_modules/@gechiui/api-fetch/build-module/middlewares/namespace-endpoint.js");
/* harmony import */ var _middlewares_http_v1__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./middlewares/http-v1 */ "./node_modules/@gechiui/api-fetch/build-module/middlewares/http-v1.js");
/* harmony import */ var _middlewares_user_locale__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./middlewares/user-locale */ "./node_modules/@gechiui/api-fetch/build-module/middlewares/user-locale.js");
/* harmony import */ var _middlewares_media_upload__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./middlewares/media-upload */ "./node_modules/@gechiui/api-fetch/build-module/middlewares/media-upload.js");
/* harmony import */ var _utils_response__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./utils/response */ "./node_modules/@gechiui/api-fetch/build-module/utils/response.js");
/**
 * GeChiUI dependencies
 */

/**
 * Internal dependencies
 */










/**
 * Default set of header values which should be sent with every request unless
 * explicitly provided through apiFetch options.
 *
 * @type {Record<string, string>}
 */

const DEFAULT_HEADERS = {
  // The backend uses the Accept header as a condition for considering an
  // incoming request as a REST request.
  //
  // See: https://core.trac.gechiui.com/ticket/44534
  Accept: 'application/json, */*;q=0.1'
};
/**
 * Default set of fetch option values which should be sent with every request
 * unless explicitly provided through apiFetch options.
 *
 * @type {Object}
 */

const DEFAULT_OPTIONS = {
  credentials: 'include'
};
/** @typedef {import('./types').APIFetchMiddleware} APIFetchMiddleware */

/** @typedef {import('./types').APIFetchOptions} APIFetchOptions */

/**
 * @type {import('./types').APIFetchMiddleware[]}
 */

const middlewares = [_middlewares_user_locale__WEBPACK_IMPORTED_MODULE_7__["default"], _middlewares_namespace_endpoint__WEBPACK_IMPORTED_MODULE_5__["default"], _middlewares_http_v1__WEBPACK_IMPORTED_MODULE_6__["default"], _middlewares_fetch_all_middleware__WEBPACK_IMPORTED_MODULE_4__["default"]];
/**
 * Register a middleware
 *
 * @param {import('./types').APIFetchMiddleware} middleware
 */

function registerMiddleware(middleware) {
  middlewares.unshift(middleware);
}
/**
 * Checks the status of a response, throwing the Response as an error if
 * it is outside the 200 range.
 *
 * @param {Response} response
 * @return {Response} The response if the status is in the 200 range.
 */


const checkStatus = response => {
  if (response.status >= 200 && response.status < 300) {
    return response;
  }

  throw response;
};
/** @typedef {(options: import('./types').APIFetchOptions) => Promise<any>} FetchHandler*/

/**
 * @type {FetchHandler}
 */


const defaultFetchHandler = nextOptions => {
  const {
    url,
    path,
    data,
    parse = true,
    ...remainingOptions
  } = nextOptions;
  let {
    body,
    headers
  } = nextOptions; // Merge explicitly-provided headers with default values.

  headers = { ...DEFAULT_HEADERS,
    ...headers
  }; // The `data` property is a shorthand for sending a JSON body.

  if (data) {
    body = JSON.stringify(data);
    headers['Content-Type'] = 'application/json';
  }

  const responsePromise = window.fetch( // fall back to explicitly passing `window.location` which is the behavior if `undefined` is passed
  url || path || window.location.href, { ...DEFAULT_OPTIONS,
    ...remainingOptions,
    body,
    headers
  });
  return responsePromise.then(value => Promise.resolve(value).then(checkStatus).catch(response => Object(_utils_response__WEBPACK_IMPORTED_MODULE_9__["parseAndThrowError"])(response, parse)).then(response => Object(_utils_response__WEBPACK_IMPORTED_MODULE_9__["parseResponseAndNormalizeError"])(response, parse)), err => {
    // Re-throw AbortError for the users to handle it themselves.
    if (err && err.name === 'AbortError') {
      throw err;
    } // Otherwise, there is most likely no network connection.
    // Unfortunately the message might depend on the browser.


    throw {
      code: 'fetch_error',
      message: Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('????????????????????????')
    };
  });
};
/** @type {FetchHandler} */


let fetchHandler = defaultFetchHandler;
/**
 * Defines a custom fetch handler for making the requests that will override
 * the default one using window.fetch
 *
 * @param {FetchHandler} newFetchHandler The new fetch handler
 */

function setFetchHandler(newFetchHandler) {
  fetchHandler = newFetchHandler;
}
/**
 * @template T
 * @param {import('./types').APIFetchOptions} options
 * @return {Promise<T>} A promise representing the request processed via the registered middlewares.
 */


function apiFetch(options) {
  // creates a nested function chain that calls all middlewares and finally the `fetchHandler`,
  // converting `middlewares = [ m1, m2, m3 ]` into:
  // ```
  // opts1 => m1( opts1, opts2 => m2( opts2, opts3 => m3( opts3, fetchHandler ) ) );
  // ```
  const enhancedHandler = middlewares.reduceRight((
  /** @type {FetchHandler} */
  next, middleware) => {
    return workingOptions => middleware(workingOptions, next);
  }, fetchHandler);
  return enhancedHandler(options).catch(error => {
    if (error.code !== 'rest_cookie_invalid_nonce') {
      return Promise.reject(error);
    } // If the nonce is invalid, refresh it and try again.


    return window // @ts-ignore
    .fetch(apiFetch.nonceEndpoint).then(checkStatus).then(data => data.text()).then(text => {
      // @ts-ignore
      apiFetch.nonceMiddleware.nonce = text;
      return apiFetch(options);
    });
  });
}

apiFetch.use = registerMiddleware;
apiFetch.setFetchHandler = setFetchHandler;
apiFetch.createNonceMiddleware = _middlewares_nonce__WEBPACK_IMPORTED_MODULE_1__["default"];
apiFetch.createPreloadingMiddleware = _middlewares_preloading__WEBPACK_IMPORTED_MODULE_3__["default"];
apiFetch.createRootURLMiddleware = _middlewares_root_url__WEBPACK_IMPORTED_MODULE_2__["default"];
apiFetch.fetchAllMiddleware = _middlewares_fetch_all_middleware__WEBPACK_IMPORTED_MODULE_4__["default"];
apiFetch.mediaUploadMiddleware = _middlewares_media_upload__WEBPACK_IMPORTED_MODULE_8__["default"];
/* harmony default export */ __webpack_exports__["default"] = (apiFetch);


/***/ }),

/***/ "./node_modules/@gechiui/api-fetch/build-module/middlewares/fetch-all-middleware.js":
/*!******************************************************************************************!*\
  !*** ./node_modules/@gechiui/api-fetch/build-module/middlewares/fetch-all-middleware.js ***!
  \******************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _gechiui_url__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @gechiui/url */ "@gechiui/url");
/* harmony import */ var _gechiui_url__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_gechiui_url__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var ___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! .. */ "./node_modules/@gechiui/api-fetch/build-module/index.js");
/**
 * GeChiUI dependencies
 */

/**
 * Internal dependencies
 */


/**
 * Apply query arguments to both URL and Path, whichever is present.
 *
 * @param {import('../types').APIFetchOptions} props
 * @param {Record<string, string | number>}    queryArgs
 * @return {import('../types').APIFetchOptions} The request with the modified query args
 */

const modifyQuery = (_ref, queryArgs) => {
  let {
    path,
    url,
    ...options
  } = _ref;
  return { ...options,
    url: url && Object(_gechiui_url__WEBPACK_IMPORTED_MODULE_0__["addQueryArgs"])(url, queryArgs),
    path: path && Object(_gechiui_url__WEBPACK_IMPORTED_MODULE_0__["addQueryArgs"])(path, queryArgs)
  };
};
/**
 * Duplicates parsing functionality from apiFetch.
 *
 * @param {Response} response
 * @return {Promise<any>} Parsed response json.
 */


const parseResponse = response => response.json ? response.json() : Promise.reject(response);
/**
 * @param {string | null} linkHeader
 * @return {{ next?: string }} The parsed link header.
 */


const parseLinkHeader = linkHeader => {
  if (!linkHeader) {
    return {};
  }

  const match = linkHeader.match(/<([^>]+)>; rel="next"/);
  return match ? {
    next: match[1]
  } : {};
};
/**
 * @param {Response} response
 * @return {string | undefined} The next page URL.
 */


const getNextPageUrl = response => {
  const {
    next
  } = parseLinkHeader(response.headers.get('link'));
  return next;
};
/**
 * @param {import('../types').APIFetchOptions} options
 * @return {boolean} True if the request contains an unbounded query.
 */


const requestContainsUnboundedQuery = options => {
  const pathIsUnbounded = !!options.path && options.path.indexOf('per_page=-1') !== -1;
  const urlIsUnbounded = !!options.url && options.url.indexOf('per_page=-1') !== -1;
  return pathIsUnbounded || urlIsUnbounded;
};
/**
 * The REST API enforces an upper limit on the per_page option. To handle large
 * collections, apiFetch consumers can pass `per_page=-1`; this middleware will
 * then recursively assemble a full response array from all available pages.
 *
 * @type {import('../types').APIFetchMiddleware}
 */


const fetchAllMiddleware = async (options, next) => {
  if (options.parse === false) {
    // If a consumer has opted out of parsing, do not apply middleware.
    return next(options);
  }

  if (!requestContainsUnboundedQuery(options)) {
    // If neither url nor path is requesting all items, do not apply middleware.
    return next(options);
  } // Retrieve requested page of results.


  const response = await Object(___WEBPACK_IMPORTED_MODULE_1__["default"])({ ...modifyQuery(options, {
      per_page: 100
    }),
    // Ensure headers are returned for page 1.
    parse: false
  });
  const results = await parseResponse(response);

  if (!Array.isArray(results)) {
    // We have no reliable way of merging non-array results.
    return results;
  }

  let nextPage = getNextPageUrl(response);

  if (!nextPage) {
    // There are no further pages to request.
    return results;
  } // Iteratively fetch all remaining pages until no "next" header is found.


  let mergedResults =
  /** @type {any[]} */
  [].concat(results);

  while (nextPage) {
    const nextResponse = await Object(___WEBPACK_IMPORTED_MODULE_1__["default"])({ ...options,
      // Ensure the URL for the next page is used instead of any provided path.
      path: undefined,
      url: nextPage,
      // Ensure we still get headers so we can identify the next page.
      parse: false
    });
    const nextResults = await parseResponse(nextResponse);
    mergedResults = mergedResults.concat(nextResults);
    nextPage = getNextPageUrl(nextResponse);
  }

  return mergedResults;
};

/* harmony default export */ __webpack_exports__["default"] = (fetchAllMiddleware);


/***/ }),

/***/ "./node_modules/@gechiui/api-fetch/build-module/middlewares/http-v1.js":
/*!*****************************************************************************!*\
  !*** ./node_modules/@gechiui/api-fetch/build-module/middlewares/http-v1.js ***!
  \*****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/**
 * Set of HTTP methods which are eligible to be overridden.
 *
 * @type {Set<string>}
 */
const OVERRIDE_METHODS = new Set(['PATCH', 'PUT', 'DELETE']);
/**
 * Default request method.
 *
 * "A request has an associated method (a method). Unless stated otherwise it
 * is `GET`."
 *
 * @see  https://fetch.spec.whatwg.org/#requests
 *
 * @type {string}
 */

const DEFAULT_METHOD = 'GET';
/**
 * API Fetch middleware which overrides the request method for HTTP v1
 * compatibility leveraging the REST API X-HTTP-Method-Override header.
 *
 * @type {import('../types').APIFetchMiddleware}
 */

const httpV1Middleware = (options, next) => {
  const {
    method = DEFAULT_METHOD
  } = options;

  if (OVERRIDE_METHODS.has(method.toUpperCase())) {
    options = { ...options,
      headers: { ...options.headers,
        'X-HTTP-Method-Override': method,
        'Content-Type': 'application/json'
      },
      method: 'POST'
    };
  }

  return next(options);
};

/* harmony default export */ __webpack_exports__["default"] = (httpV1Middleware);


/***/ }),

/***/ "./node_modules/@gechiui/api-fetch/build-module/middlewares/media-upload.js":
/*!**********************************************************************************!*\
  !*** ./node_modules/@gechiui/api-fetch/build-module/middlewares/media-upload.js ***!
  \**********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _gechiui_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @gechiui/i18n */ "@gechiui/i18n");
/* harmony import */ var _gechiui_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _utils_response__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../utils/response */ "./node_modules/@gechiui/api-fetch/build-module/utils/response.js");
/**
 * GeChiUI dependencies
 */

/**
 * Internal dependencies
 */


/**
 * @param {import('../types').APIFetchOptions} options
 * @return {boolean} True if the request is for media upload.
 */

function isMediaUploadRequest(options) {
  const isCreateMethod = !!options.method && options.method === 'POST';
  const isMediaEndpoint = !!options.path && options.path.indexOf('/gc/v2/media') !== -1 || !!options.url && options.url.indexOf('/gc/v2/media') !== -1;
  return isMediaEndpoint && isCreateMethod;
}
/**
 * Middleware handling media upload failures and retries.
 *
 * @type {import('../types').APIFetchMiddleware}
 */


const mediaUploadMiddleware = (options, next) => {
  if (!isMediaUploadRequest(options)) {
    return next(options);
  }

  let retries = 0;
  const maxRetries = 5;
  /**
   * @param {string} attachmentId
   * @return {Promise<any>} Processed post response.
   */

  const postProcess = attachmentId => {
    retries++;
    return next({
      path: `/gc/v2/media/${attachmentId}/post-process`,
      method: 'POST',
      data: {
        action: 'create-image-subsizes'
      },
      parse: false
    }).catch(() => {
      if (retries < maxRetries) {
        return postProcess(attachmentId);
      }

      next({
        path: `/gc/v2/media/${attachmentId}?force=true`,
        method: 'DELETE'
      });
      return Promise.reject();
    });
  };

  return next({ ...options,
    parse: false
  }).catch(response => {
    const attachmentId = response.headers.get('x-gc-upload-attachment-id');

    if (response.status >= 500 && response.status < 600 && attachmentId) {
      return postProcess(attachmentId).catch(() => {
        if (options.parse !== false) {
          return Promise.reject({
            code: 'post_process',
            message: Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('???????????????????????????????????????????????????????????????????????????????????????????????????')
          });
        }

        return Promise.reject(response);
      });
    }

    return Object(_utils_response__WEBPACK_IMPORTED_MODULE_1__["parseAndThrowError"])(response, options.parse);
  }).then(response => Object(_utils_response__WEBPACK_IMPORTED_MODULE_1__["parseResponseAndNormalizeError"])(response, options.parse));
};

/* harmony default export */ __webpack_exports__["default"] = (mediaUploadMiddleware);


/***/ }),

/***/ "./node_modules/@gechiui/api-fetch/build-module/middlewares/namespace-endpoint.js":
/*!****************************************************************************************!*\
  !*** ./node_modules/@gechiui/api-fetch/build-module/middlewares/namespace-endpoint.js ***!
  \****************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/**
 * @type {import('../types').APIFetchMiddleware}
 */
const namespaceAndEndpointMiddleware = (options, next) => {
  let path = options.path;
  let namespaceTrimmed, endpointTrimmed;

  if (typeof options.namespace === 'string' && typeof options.endpoint === 'string') {
    namespaceTrimmed = options.namespace.replace(/^\/|\/$/g, '');
    endpointTrimmed = options.endpoint.replace(/^\//, '');

    if (endpointTrimmed) {
      path = namespaceTrimmed + '/' + endpointTrimmed;
    } else {
      path = namespaceTrimmed;
    }
  }

  delete options.namespace;
  delete options.endpoint;
  return next({ ...options,
    path
  });
};

/* harmony default export */ __webpack_exports__["default"] = (namespaceAndEndpointMiddleware);


/***/ }),

/***/ "./node_modules/@gechiui/api-fetch/build-module/middlewares/nonce.js":
/*!***************************************************************************!*\
  !*** ./node_modules/@gechiui/api-fetch/build-module/middlewares/nonce.js ***!
  \***************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/**
 * @param {string} nonce
 * @return {import('../types').APIFetchMiddleware & { nonce: string }} A middleware to enhance a request with a nonce.
 */
function createNonceMiddleware(nonce) {
  /**
   * @type {import('../types').APIFetchMiddleware & { nonce: string }}
   */
  const middleware = (options, next) => {
    const {
      headers = {}
    } = options; // If an 'X-GC-Nonce' header (or any case-insensitive variation
    // thereof) was specified, no need to add a nonce header.

    for (const headerName in headers) {
      if (headerName.toLowerCase() === 'x-gc-nonce' && headers[headerName] === middleware.nonce) {
        return next(options);
      }
    }

    return next({ ...options,
      headers: { ...headers,
        'X-GC-Nonce': middleware.nonce
      }
    });
  };

  middleware.nonce = nonce;
  return middleware;
}

/* harmony default export */ __webpack_exports__["default"] = (createNonceMiddleware);


/***/ }),

/***/ "./node_modules/@gechiui/api-fetch/build-module/middlewares/preloading.js":
/*!********************************************************************************!*\
  !*** ./node_modules/@gechiui/api-fetch/build-module/middlewares/preloading.js ***!
  \********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _gechiui_url__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @gechiui/url */ "@gechiui/url");
/* harmony import */ var _gechiui_url__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_gechiui_url__WEBPACK_IMPORTED_MODULE_0__);
/**
 * GeChiUI dependencies
 */

/**
 * @param {Record<string, any>} preloadedData
 * @return {import('../types').APIFetchMiddleware} Preloading middleware.
 */

function createPreloadingMiddleware(preloadedData) {
  const cache = Object.keys(preloadedData).reduce((result, path) => {
    result[Object(_gechiui_url__WEBPACK_IMPORTED_MODULE_0__["normalizePath"])(path)] = preloadedData[path];
    return result;
  },
  /** @type {Record<string, any>} */
  {});
  return (options, next) => {
    const {
      parse = true
    } = options;
    /** @type {string | void} */

    let rawPath = options.path;

    if (!rawPath && options.url) {
      const pathFromQuery = Object(_gechiui_url__WEBPACK_IMPORTED_MODULE_0__["getQueryArg"])(options.url, 'rest_route');

      if (typeof pathFromQuery === 'string') {
        rawPath = pathFromQuery;
      }
    }

    if (typeof rawPath === 'string') {
      const method = options.method || 'GET';
      const path = Object(_gechiui_url__WEBPACK_IMPORTED_MODULE_0__["normalizePath"])(rawPath);

      if ('GET' === method && cache[path]) {
        const cacheData = cache[path]; // Unsetting the cache key ensures that the data is only used a single time

        delete cache[path];
        return Promise.resolve(parse ? cacheData.body : new window.Response(JSON.stringify(cacheData.body), {
          status: 200,
          statusText: 'OK',
          headers: cacheData.headers
        }));
      } else if ('OPTIONS' === method && cache[method] && cache[method][path]) {
        const cacheData = cache[method][path]; // Unsetting the cache key ensures that the data is only used a single time

        delete cache[method][path];
        return Promise.resolve(parse ? cacheData.body : cacheData);
      }
    }

    return next(options);
  };
}

/* harmony default export */ __webpack_exports__["default"] = (createPreloadingMiddleware);


/***/ }),

/***/ "./node_modules/@gechiui/api-fetch/build-module/middlewares/root-url.js":
/*!******************************************************************************!*\
  !*** ./node_modules/@gechiui/api-fetch/build-module/middlewares/root-url.js ***!
  \******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _namespace_endpoint__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./namespace-endpoint */ "./node_modules/@gechiui/api-fetch/build-module/middlewares/namespace-endpoint.js");
/**
 * Internal dependencies
 */

/**
 * @param {string} rootURL
 * @return {import('../types').APIFetchMiddleware} Root URL middleware.
 */

const createRootURLMiddleware = rootURL => (options, next) => {
  return Object(_namespace_endpoint__WEBPACK_IMPORTED_MODULE_0__["default"])(options, optionsWithPath => {
    let url = optionsWithPath.url;
    let path = optionsWithPath.path;
    let apiRoot;

    if (typeof path === 'string') {
      apiRoot = rootURL;

      if (-1 !== rootURL.indexOf('?')) {
        path = path.replace('?', '&');
      }

      path = path.replace(/^\//, ''); // API root may already include query parameter prefix if site is
      // configured to use plain permalinks.

      if ('string' === typeof apiRoot && -1 !== apiRoot.indexOf('?')) {
        path = path.replace('?', '&');
      }

      url = apiRoot + path;
    }

    return next({ ...optionsWithPath,
      url
    });
  });
};

/* harmony default export */ __webpack_exports__["default"] = (createRootURLMiddleware);


/***/ }),

/***/ "./node_modules/@gechiui/api-fetch/build-module/middlewares/user-locale.js":
/*!*********************************************************************************!*\
  !*** ./node_modules/@gechiui/api-fetch/build-module/middlewares/user-locale.js ***!
  \*********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _gechiui_url__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @gechiui/url */ "@gechiui/url");
/* harmony import */ var _gechiui_url__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_gechiui_url__WEBPACK_IMPORTED_MODULE_0__);
/**
 * GeChiUI dependencies
 */

/**
 * @type {import('../types').APIFetchMiddleware}
 */

const userLocaleMiddleware = (options, next) => {
  if (typeof options.url === 'string' && !Object(_gechiui_url__WEBPACK_IMPORTED_MODULE_0__["hasQueryArg"])(options.url, '_locale')) {
    options.url = Object(_gechiui_url__WEBPACK_IMPORTED_MODULE_0__["addQueryArgs"])(options.url, {
      _locale: 'user'
    });
  }

  if (typeof options.path === 'string' && !Object(_gechiui_url__WEBPACK_IMPORTED_MODULE_0__["hasQueryArg"])(options.path, '_locale')) {
    options.path = Object(_gechiui_url__WEBPACK_IMPORTED_MODULE_0__["addQueryArgs"])(options.path, {
      _locale: 'user'
    });
  }

  return next(options);
};

/* harmony default export */ __webpack_exports__["default"] = (userLocaleMiddleware);


/***/ }),

/***/ "./node_modules/@gechiui/api-fetch/build-module/utils/response.js":
/*!************************************************************************!*\
  !*** ./node_modules/@gechiui/api-fetch/build-module/utils/response.js ***!
  \************************************************************************/
/*! exports provided: parseResponseAndNormalizeError, parseAndThrowError */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "parseResponseAndNormalizeError", function() { return parseResponseAndNormalizeError; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "parseAndThrowError", function() { return parseAndThrowError; });
/* harmony import */ var _gechiui_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @gechiui/i18n */ "@gechiui/i18n");
/* harmony import */ var _gechiui_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_0__);
/**
 * GeChiUI dependencies
 */

/**
 * Parses the apiFetch response.
 *
 * @param {Response} response
 * @param {boolean}  shouldParseResponse
 *
 * @return {Promise<any> | null | Response} Parsed response.
 */

const parseResponse = function (response) {
  let shouldParseResponse = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;

  if (shouldParseResponse) {
    if (response.status === 204) {
      return null;
    }

    return response.json ? response.json() : Promise.reject(response);
  }

  return response;
};
/**
 * Calls the `json` function on the Response, throwing an error if the response
 * doesn't have a json function or if parsing the json itself fails.
 *
 * @param {Response} response
 * @return {Promise<any>} Parsed response.
 */


const parseJsonAndNormalizeError = response => {
  const invalidJsonError = {
    code: 'invalid_json',
    message: Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('????????????????????????JSON?????????')
  };

  if (!response || !response.json) {
    throw invalidJsonError;
  }

  return response.json().catch(() => {
    throw invalidJsonError;
  });
};
/**
 * Parses the apiFetch response properly and normalize response errors.
 *
 * @param {Response} response
 * @param {boolean}  shouldParseResponse
 *
 * @return {Promise<any>} Parsed response.
 */


const parseResponseAndNormalizeError = function (response) {
  let shouldParseResponse = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;
  return Promise.resolve(parseResponse(response, shouldParseResponse)).catch(res => parseAndThrowError(res, shouldParseResponse));
};
/**
 * Parses a response, throwing an error if parsing the response fails.
 *
 * @param {Response} response
 * @param {boolean}  shouldParseResponse
 * @return {Promise<any>} Parsed response.
 */

function parseAndThrowError(response) {
  let shouldParseResponse = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;

  if (!shouldParseResponse) {
    throw response;
  }

  return parseJsonAndNormalizeError(response).then(error => {
    const unknownError = {
      code: 'unknown_error',
      message: Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_0__["__"])('????????????????????????')
    };
    throw error || unknownError;
  });
}


/***/ }),

/***/ "@gechiui/i18n":
/*!******************************!*\
  !*** external ["gc","i18n"] ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["gc"]["i18n"]; }());

/***/ }),

/***/ "@gechiui/url":
/*!*****************************!*\
  !*** external ["gc","url"] ***!
  \*****************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["gc"]["url"]; }());

/***/ })

/******/ })["default"];
//# sourceMappingURL=api-fetch.js.map