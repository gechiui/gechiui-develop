this["gc"] = this["gc"] || {}; this["gc"]["serverSideRender"] =
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
/******/ 	return __webpack_require__(__webpack_require__.s = "./node_modules/@gechiui/server-side-render/build-module/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/@babel/runtime/helpers/esm/extends.js":
/*!************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/extends.js ***!
  \************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return _extends; });
function _extends() {
  _extends = Object.assign || function (target) {
    for (var i = 1; i < arguments.length; i++) {
      var source = arguments[i];

      for (var key in source) {
        if (Object.prototype.hasOwnProperty.call(source, key)) {
          target[key] = source[key];
        }
      }
    }

    return target;
  };

  return _extends.apply(this, arguments);
}

/***/ }),

/***/ "./node_modules/@gechiui/server-side-render/build-module/index.js":
/*!************************************************************************!*\
  !*** ./node_modules/@gechiui/server-side-render/build-module/index.js ***!
  \************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/extends */ "./node_modules/@babel/runtime/helpers/esm/extends.js");
/* harmony import */ var _gechiui_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @gechiui/element */ "@gechiui/element");
/* harmony import */ var _gechiui_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _gechiui_data__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @gechiui/data */ "@gechiui/data");
/* harmony import */ var _gechiui_data__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_gechiui_data__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _gechiui_deprecated__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @gechiui/deprecated */ "@gechiui/deprecated");
/* harmony import */ var _gechiui_deprecated__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_gechiui_deprecated__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _server_side_render__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./server-side-render */ "./node_modules/@gechiui/server-side-render/build-module/server-side-render.js");



/**
 * GeChiUI dependencies
 */



/**
 * Internal dependencies
 */


/**
 * Constants
 */

const EMPTY_OBJECT = {};
const ExportedServerSideRender = Object(_gechiui_data__WEBPACK_IMPORTED_MODULE_2__["withSelect"])(select => {
  // FIXME: @gechiui/server-side-render should not depend on @gechiui/editor.
  // It is used by blocks that can be loaded into a *non-post* block editor.
  // eslint-disable-next-line @gechiui/data-no-store-string-literals
  const coreEditorSelect = select('core/editor');

  if (coreEditorSelect) {
    const currentPostId = coreEditorSelect.getCurrentPostId(); // For templates and template parts we use a custom ID format.
    // Since they aren't real posts, we don't want to use their ID
    // for server-side rendering. Since they use a string based ID,
    // we can assume real post IDs are numbers.

    if (currentPostId && typeof currentPostId === 'number') {
      return {
        currentPostId
      };
    }
  }

  return EMPTY_OBJECT;
})(_ref => {
  let {
    urlQueryArgs = EMPTY_OBJECT,
    currentPostId,
    ...props
  } = _ref;
  const newUrlQueryArgs = Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["useMemo"])(() => {
    if (!currentPostId) {
      return urlQueryArgs;
    }

    return {
      post_id: currentPostId,
      ...urlQueryArgs
    };
  }, [currentPostId, urlQueryArgs]);
  return Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])(_server_side_render__WEBPACK_IMPORTED_MODULE_4__["default"], Object(_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__["default"])({
    urlQueryArgs: newUrlQueryArgs
  }, props));
});

if (window && window.gc && window.gc.components) {
  window.gc.components.ServerSideRender = Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["forwardRef"])((props, ref) => {
    _gechiui_deprecated__WEBPACK_IMPORTED_MODULE_3___default()('gc.components.ServerSideRender', {
      since: '5.3',
      alternative: 'gc.serverSideRender'
    });
    return Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])(ExportedServerSideRender, Object(_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__["default"])({}, props, {
      ref: ref
    }));
  });
}

/* harmony default export */ __webpack_exports__["default"] = (ExportedServerSideRender);


/***/ }),

/***/ "./node_modules/@gechiui/server-side-render/build-module/server-side-render.js":
/*!*************************************************************************************!*\
  !*** ./node_modules/@gechiui/server-side-render/build-module/server-side-render.js ***!
  \*************************************************************************************/
/*! exports provided: rendererPath, default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "rendererPath", function() { return rendererPath; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return ServerSideRender; });
/* harmony import */ var _babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/extends */ "./node_modules/@babel/runtime/helpers/esm/extends.js");
/* harmony import */ var _gechiui_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @gechiui/element */ "@gechiui/element");
/* harmony import */ var _gechiui_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _gechiui_compose__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @gechiui/compose */ "@gechiui/compose");
/* harmony import */ var _gechiui_compose__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_gechiui_compose__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _gechiui_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @gechiui/i18n */ "@gechiui/i18n");
/* harmony import */ var _gechiui_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @gechiui/api-fetch */ "@gechiui/api-fetch");
/* harmony import */ var _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _gechiui_url__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @gechiui/url */ "@gechiui/url");
/* harmony import */ var _gechiui_url__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_gechiui_url__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _gechiui_components__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @gechiui/components */ "@gechiui/components");
/* harmony import */ var _gechiui_components__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_gechiui_components__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var _gechiui_blocks__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @gechiui/blocks */ "@gechiui/blocks");
/* harmony import */ var _gechiui_blocks__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_gechiui_blocks__WEBPACK_IMPORTED_MODULE_8__);



/**
 * External dependencies
 */

/**
 * GeChiUI dependencies
 */








function rendererPath(block) {
  let attributes = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
  let urlQueryArgs = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
  return Object(_gechiui_url__WEBPACK_IMPORTED_MODULE_6__["addQueryArgs"])(`/gc/v2/block-renderer/${block}`, {
    context: 'edit',
    ...(null !== attributes ? {
      attributes
    } : {}),
    ...urlQueryArgs
  });
}

function DefaultEmptyResponsePlaceholder(_ref) {
  let {
    className
  } = _ref;
  return Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])(_gechiui_components__WEBPACK_IMPORTED_MODULE_7__["Placeholder"], {
    className: className
  }, Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_4__["__"])('区块渲染为空。'));
}

function DefaultErrorResponsePlaceholder(_ref2) {
  let {
    response,
    className
  } = _ref2;
  const errorMessage = Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_4__["sprintf"])( // translators: %s: error message describing the problem
  Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_4__["__"])('载入区块时发生错误：%s'), response.errorMsg);
  return Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])(_gechiui_components__WEBPACK_IMPORTED_MODULE_7__["Placeholder"], {
    className: className
  }, errorMessage);
}

function DefaultLoadingResponsePlaceholder(_ref3) {
  let {
    children,
    showLoader
  } = _ref3;
  return Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])("div", {
    style: {
      position: 'relative'
    }
  }, showLoader && Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])("div", {
    style: {
      position: 'absolute',
      top: '50%',
      left: '50%',
      marginTop: '-9px',
      marginLeft: '-9px'
    }
  }, Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])(_gechiui_components__WEBPACK_IMPORTED_MODULE_7__["Spinner"], null)), Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])("div", {
    style: {
      opacity: showLoader ? '0.3' : 1
    }
  }, children));
}

function ServerSideRender(props) {
  const {
    attributes,
    block,
    className,
    httpMethod = 'GET',
    urlQueryArgs,
    EmptyResponsePlaceholder = DefaultEmptyResponsePlaceholder,
    ErrorResponsePlaceholder = DefaultErrorResponsePlaceholder,
    LoadingResponsePlaceholder = DefaultLoadingResponsePlaceholder
  } = props;
  const isMountedRef = Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["useRef"])(true);
  const [showLoader, setShowLoader] = Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["useState"])(false);
  const fetchRequestRef = Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["useRef"])();
  const [response, setResponse] = Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["useState"])(null);
  const prevProps = Object(_gechiui_compose__WEBPACK_IMPORTED_MODULE_3__["usePrevious"])(props);
  const [isLoading, setIsLoading] = Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["useState"])(false);

  function fetchData() {
    if (!isMountedRef.current) {
      return;
    }

    setIsLoading(true);

    const sanitizedAttributes = attributes && Object(_gechiui_blocks__WEBPACK_IMPORTED_MODULE_8__["__experimentalSanitizeBlockAttributes"])(block, attributes); // If httpMethod is 'POST', send the attributes in the request body instead of the URL.
    // This allows sending a larger attributes object than in a GET request, where the attributes are in the URL.


    const isPostRequest = 'POST' === httpMethod;
    const urlAttributes = isPostRequest ? null : sanitizedAttributes !== null && sanitizedAttributes !== void 0 ? sanitizedAttributes : null;
    const path = rendererPath(block, urlAttributes, urlQueryArgs);
    const data = isPostRequest ? {
      attributes: sanitizedAttributes !== null && sanitizedAttributes !== void 0 ? sanitizedAttributes : null
    } : null; // Store the latest fetch request so that when we process it, we can
    // check if it is the current request, to avoid race conditions on slow networks.

    const fetchRequest = fetchRequestRef.current = _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_5___default()({
      path,
      data,
      method: isPostRequest ? 'POST' : 'GET'
    }).then(fetchResponse => {
      if (isMountedRef.current && fetchRequest === fetchRequestRef.current && fetchResponse) {
        setResponse(fetchResponse.rendered);
      }
    }).catch(error => {
      if (isMountedRef.current && fetchRequest === fetchRequestRef.current) {
        setResponse({
          error: true,
          errorMsg: error.message
        });
      }
    }).finally(() => {
      if (isMountedRef.current && fetchRequest === fetchRequestRef.current) {
        setIsLoading(false);
      }
    });
    return fetchRequest;
  }

  const debouncedFetchData = Object(_gechiui_compose__WEBPACK_IMPORTED_MODULE_3__["useDebounce"])(fetchData, 500); // When the component unmounts, set isMountedRef to false. This will
  // let the async fetch callbacks know when to stop.

  Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["useEffect"])(() => () => {
    isMountedRef.current = false;
  }, []);
  Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["useEffect"])(() => {
    // Don't debounce the first fetch. This ensures that the first render
    // shows data as soon as possible
    if (prevProps === undefined) {
      fetchData();
    } else if (!Object(lodash__WEBPACK_IMPORTED_MODULE_2__["isEqual"])(prevProps, props)) {
      debouncedFetchData();
    }
  });
  /**
   * Effect to handle showing the loading placeholder.
   * Show it only if there is no previous response or
   * the request takes more than one second.
   */

  Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["useEffect"])(() => {
    if (!isLoading) {
      return;
    }

    const timeout = setTimeout(() => {
      setShowLoader(true);
    }, 1000);
    return () => clearTimeout(timeout);
  }, [isLoading]);
  const hasResponse = !!response;
  const hasEmptyResponse = response === '';
  const hasError = response === null || response === void 0 ? void 0 : response.error;

  if (isLoading) {
    return Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])(LoadingResponsePlaceholder, Object(_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__["default"])({}, props, {
      showLoader: showLoader
    }), hasResponse && Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["RawHTML"], {
      className: className
    }, response));
  }

  if (hasEmptyResponse || !hasResponse) {
    return Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])(EmptyResponsePlaceholder, props);
  }

  if (hasError) {
    return Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])(ErrorResponsePlaceholder, Object(_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__["default"])({
      response: response
    }, props));
  }

  return Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["RawHTML"], {
    className: className
  }, response);
}


/***/ }),

/***/ "@gechiui/api-fetch":
/*!**********************************!*\
  !*** external ["gc","apiFetch"] ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["gc"]["apiFetch"]; }());

/***/ }),

/***/ "@gechiui/blocks":
/*!********************************!*\
  !*** external ["gc","blocks"] ***!
  \********************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["gc"]["blocks"]; }());

/***/ }),

/***/ "@gechiui/components":
/*!************************************!*\
  !*** external ["gc","components"] ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["gc"]["components"]; }());

/***/ }),

/***/ "@gechiui/compose":
/*!*********************************!*\
  !*** external ["gc","compose"] ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["gc"]["compose"]; }());

/***/ }),

/***/ "@gechiui/data":
/*!******************************!*\
  !*** external ["gc","data"] ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["gc"]["data"]; }());

/***/ }),

/***/ "@gechiui/deprecated":
/*!************************************!*\
  !*** external ["gc","deprecated"] ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["gc"]["deprecated"]; }());

/***/ }),

/***/ "@gechiui/element":
/*!*********************************!*\
  !*** external ["gc","element"] ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["gc"]["element"]; }());

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

/***/ }),

/***/ "lodash":
/*!*************************!*\
  !*** external "lodash" ***!
  \*************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["lodash"]; }());

/***/ })

/******/ })["default"];
//# sourceMappingURL=server-side-render.js.map