this["gc"] = this["gc"] || {}; this["gc"]["viewport"] =
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
/******/ 	return __webpack_require__(__webpack_require__.s = "./node_modules/@gechiui/viewport/build-module/index.js");
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

/***/ "./node_modules/@gechiui/viewport/build-module/if-viewport-matches.js":
/*!****************************************************************************!*\
  !*** ./node_modules/@gechiui/viewport/build-module/if-viewport-matches.js ***!
  \****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _gechiui_compose__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @gechiui/compose */ "@gechiui/compose");
/* harmony import */ var _gechiui_compose__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_gechiui_compose__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _with_viewport_match__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./with-viewport-match */ "./node_modules/@gechiui/viewport/build-module/with-viewport-match.js");
/**
 * GeChiUI dependencies
 */

/**
 * Internal dependencies
 */


/**
 * Higher-order component creator, creating a new component which renders if
 * the viewport query is satisfied.
 *
 * @see withViewportMatches
 *
 * @param {string} query Viewport query.
 *
 * @example
 *
 * ```jsx
 * function MyMobileComponent() {
 * 	return <div>I'm only rendered on mobile viewports!</div>;
 * }
 *
 * MyMobileComponent = ifViewportMatches( '< small' )( MyMobileComponent );
 * ```
 *
 * @return {Function} Higher-order component.
 */

const ifViewportMatches = query => Object(_gechiui_compose__WEBPACK_IMPORTED_MODULE_0__["createHigherOrderComponent"])(Object(_gechiui_compose__WEBPACK_IMPORTED_MODULE_0__["compose"])([Object(_with_viewport_match__WEBPACK_IMPORTED_MODULE_1__["default"])({
  isViewportMatch: query
}), Object(_gechiui_compose__WEBPACK_IMPORTED_MODULE_0__["ifCondition"])(props => props.isViewportMatch)]), 'ifViewportMatches');

/* harmony default export */ __webpack_exports__["default"] = (ifViewportMatches);


/***/ }),

/***/ "./node_modules/@gechiui/viewport/build-module/index.js":
/*!**************************************************************!*\
  !*** ./node_modules/@gechiui/viewport/build-module/index.js ***!
  \**************************************************************/
/*! exports provided: store, ifViewportMatches, withViewportMatch */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _listener__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./listener */ "./node_modules/@gechiui/viewport/build-module/listener.js");
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./store */ "./node_modules/@gechiui/viewport/build-module/store/index.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "store", function() { return _store__WEBPACK_IMPORTED_MODULE_1__["store"]; });

/* harmony import */ var _if_viewport_matches__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./if-viewport-matches */ "./node_modules/@gechiui/viewport/build-module/if-viewport-matches.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "ifViewportMatches", function() { return _if_viewport_matches__WEBPACK_IMPORTED_MODULE_2__["default"]; });

/* harmony import */ var _with_viewport_match__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./with-viewport-match */ "./node_modules/@gechiui/viewport/build-module/with-viewport-match.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "withViewportMatch", function() { return _with_viewport_match__WEBPACK_IMPORTED_MODULE_3__["default"]; });

/**
 * Internal dependencies
 */




/**
 * Hash of breakpoint names with pixel width at which it becomes effective.
 *
 * @see _breakpoints.scss
 *
 * @type {Object}
 */

const BREAKPOINTS = {
  huge: 1440,
  wide: 1280,
  large: 960,
  medium: 782,
  small: 600,
  mobile: 480
};
/**
 * Hash of query operators with corresponding condition for media query.
 *
 * @type {Object}
 */

const OPERATORS = {
  '<': 'max-width',
  '>=': 'min-width'
};
Object(_listener__WEBPACK_IMPORTED_MODULE_0__["default"])(BREAKPOINTS, OPERATORS);


/***/ }),

/***/ "./node_modules/@gechiui/viewport/build-module/listener.js":
/*!*****************************************************************!*\
  !*** ./node_modules/@gechiui/viewport/build-module/listener.js ***!
  \*****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _gechiui_data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @gechiui/data */ "@gechiui/data");
/* harmony import */ var _gechiui_data__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_gechiui_data__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./store */ "./node_modules/@gechiui/viewport/build-module/store/index.js");
/**
 * External dependencies
 */

/**
 * GeChiUI dependencies
 */


/**
 * Internal dependencies
 */



const addDimensionsEventListener = (breakpoints, operators) => {
  /**
   * Callback invoked when media query state should be updated. Is invoked a
   * maximum of one time per call stack.
   */
  const setIsMatching = Object(lodash__WEBPACK_IMPORTED_MODULE_0__["debounce"])(() => {
    const values = Object(lodash__WEBPACK_IMPORTED_MODULE_0__["mapValues"])(queries, query => query.matches);
    Object(_gechiui_data__WEBPACK_IMPORTED_MODULE_1__["dispatch"])(_store__WEBPACK_IMPORTED_MODULE_2__["store"]).setIsMatching(values);
  }, {
    leading: true
  });
  /**
   * Hash of breakpoint names with generated MediaQueryList for corresponding
   * media query.
   *
   * @see https://developer.mozilla.org/en-US/docs/Web/API/Window/matchMedia
   * @see https://developer.mozilla.org/en-US/docs/Web/API/MediaQueryList
   *
   * @type {Object<string,MediaQueryList>}
   */

  const queries = Object(lodash__WEBPACK_IMPORTED_MODULE_0__["reduce"])(breakpoints, (result, width, name) => {
    Object(lodash__WEBPACK_IMPORTED_MODULE_0__["forEach"])(operators, (condition, operator) => {
      const list = window.matchMedia(`(${condition}: ${width}px)`);
      list.addListener(setIsMatching);
      const key = [operator, name].join(' ');
      result[key] = list;
    });
    return result;
  }, {});
  window.addEventListener('orientationchange', setIsMatching); // Set initial values

  setIsMatching();
  setIsMatching.flush();
};

/* harmony default export */ __webpack_exports__["default"] = (addDimensionsEventListener);


/***/ }),

/***/ "./node_modules/@gechiui/viewport/build-module/store/actions.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@gechiui/viewport/build-module/store/actions.js ***!
  \**********************************************************************/
/*! exports provided: setIsMatching */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "setIsMatching", function() { return setIsMatching; });
/**
 * Returns an action object used in signalling that viewport queries have been
 * updated. Values are specified as an object of breakpoint query keys where
 * value represents whether query matches.
 *
 * @param {Object} values Breakpoint query matches.
 *
 * @return {Object} Action object.
 */
function setIsMatching(values) {
  return {
    type: 'SET_IS_MATCHING',
    values
  };
}


/***/ }),

/***/ "./node_modules/@gechiui/viewport/build-module/store/index.js":
/*!********************************************************************!*\
  !*** ./node_modules/@gechiui/viewport/build-module/store/index.js ***!
  \********************************************************************/
/*! exports provided: store */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "store", function() { return store; });
/* harmony import */ var _gechiui_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @gechiui/data */ "@gechiui/data");
/* harmony import */ var _gechiui_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_gechiui_data__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _reducer__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./reducer */ "./node_modules/@gechiui/viewport/build-module/store/reducer.js");
/* harmony import */ var _actions__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./actions */ "./node_modules/@gechiui/viewport/build-module/store/actions.js");
/* harmony import */ var _selectors__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./selectors */ "./node_modules/@gechiui/viewport/build-module/store/selectors.js");
/**
 * GeChiUI dependencies
 */

/**
 * Internal dependencies
 */




const STORE_NAME = 'core/viewport';
/**
 * Store definition for the viewport namespace.
 *
 * @see https://github.com/GeChiUI/gutenberg/blob/HEAD/packages/data/README.md#createReduxStore
 *
 * @type {Object}
 */

const store = Object(_gechiui_data__WEBPACK_IMPORTED_MODULE_0__["createReduxStore"])(STORE_NAME, {
  reducer: _reducer__WEBPACK_IMPORTED_MODULE_1__["default"],
  actions: _actions__WEBPACK_IMPORTED_MODULE_2__,
  selectors: _selectors__WEBPACK_IMPORTED_MODULE_3__
});
Object(_gechiui_data__WEBPACK_IMPORTED_MODULE_0__["register"])(store);


/***/ }),

/***/ "./node_modules/@gechiui/viewport/build-module/store/reducer.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@gechiui/viewport/build-module/store/reducer.js ***!
  \**********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/**
 * Reducer returning the viewport state, as keys of breakpoint queries with
 * boolean value representing whether query is matched.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */
function reducer() {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  let action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'SET_IS_MATCHING':
      return action.values;
  }

  return state;
}

/* harmony default export */ __webpack_exports__["default"] = (reducer);


/***/ }),

/***/ "./node_modules/@gechiui/viewport/build-module/store/selectors.js":
/*!************************************************************************!*\
  !*** ./node_modules/@gechiui/viewport/build-module/store/selectors.js ***!
  \************************************************************************/
/*! exports provided: isViewportMatch */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "isViewportMatch", function() { return isViewportMatch; });
/**
 * Returns true if the viewport matches the given query, or false otherwise.
 *
 * @param {Object} state Viewport state object.
 * @param {string} query Query string. Includes operator and breakpoint name,
 *                       space separated. Operator defaults to >=.
 *
 * @example
 *
 * ```js
 * isViewportMatch( state, '< huge' );
 * isViewPortMatch( state, 'medium' );
 * ```
 *
 * @return {boolean} Whether viewport matches query.
 */
function isViewportMatch(state, query) {
  // Default to `>=` if no operator is present.
  if (query.indexOf(' ') === -1) {
    query = '>= ' + query;
  }

  return !!state[query];
}


/***/ }),

/***/ "./node_modules/@gechiui/viewport/build-module/with-viewport-match.js":
/*!****************************************************************************!*\
  !*** ./node_modules/@gechiui/viewport/build-module/with-viewport-match.js ***!
  \****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/extends */ "./node_modules/@babel/runtime/helpers/esm/extends.js");
/* harmony import */ var _gechiui_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @gechiui/element */ "@gechiui/element");
/* harmony import */ var _gechiui_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _gechiui_compose__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @gechiui/compose */ "@gechiui/compose");
/* harmony import */ var _gechiui_compose__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_gechiui_compose__WEBPACK_IMPORTED_MODULE_3__);



/**
 * External dependencies
 */

/**
 * GeChiUI dependencies
 */


/**
 * Higher-order component creator, creating a new component which renders with
 * the given prop names, where the value passed to the underlying component is
 * the result of the query assigned as the object's value.
 *
 * @see isViewportMatch
 *
 * @param {Object} queries Object of prop name to viewport query.
 *
 * @example
 *
 * ```jsx
 * function MyComponent( { isMobile } ) {
 * 	return (
 * 		<div>Currently: { isMobile ? 'Mobile' : 'Not Mobile' }</div>
 * 	);
 * }
 *
 * MyComponent = withViewportMatch( { isMobile: '< small' } )( MyComponent );
 * ```
 *
 * @return {Function} Higher-order component.
 */

const withViewportMatch = queries => {
  const useViewPortQueriesResult = () => Object(lodash__WEBPACK_IMPORTED_MODULE_2__["mapValues"])(queries, query => {
    let [operator, breakpointName] = query.split(' ');

    if (breakpointName === undefined) {
      breakpointName = operator;
      operator = '>=';
    } // Hooks should unconditionally execute in the same order,
    // we are respecting that as from the static query of the HOC we generate
    // a hook that calls other hooks always in the same order (because the query never changes).
    // eslint-disable-next-line react-hooks/rules-of-hooks


    return Object(_gechiui_compose__WEBPACK_IMPORTED_MODULE_3__["useViewportMatch"])(breakpointName, operator);
  });

  return Object(_gechiui_compose__WEBPACK_IMPORTED_MODULE_3__["createHigherOrderComponent"])(WrappedComponent => {
    return Object(_gechiui_compose__WEBPACK_IMPORTED_MODULE_3__["pure"])(props => {
      const queriesResult = useViewPortQueriesResult();
      return Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])(WrappedComponent, Object(_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__["default"])({}, props, queriesResult));
    });
  }, 'withViewportMatch');
};

/* harmony default export */ __webpack_exports__["default"] = (withViewportMatch);


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

/***/ "@gechiui/element":
/*!*********************************!*\
  !*** external ["gc","element"] ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["gc"]["element"]; }());

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
//# sourceMappingURL=viewport.js.map