this["gc"] = this["gc"] || {}; this["gc"]["notices"] =
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
/******/ 	return __webpack_require__(__webpack_require__.s = "./node_modules/@gechiui/notices/build-module/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/@gechiui/notices/build-module/index.js":
/*!*************************************************************!*\
  !*** ./node_modules/@gechiui/notices/build-module/index.js ***!
  \*************************************************************/
/*! exports provided: store */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./store */ "./node_modules/@gechiui/notices/build-module/store/index.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "store", function() { return _store__WEBPACK_IMPORTED_MODULE_0__["store"]; });




/***/ }),

/***/ "./node_modules/@gechiui/notices/build-module/store/actions.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@gechiui/notices/build-module/store/actions.js ***!
  \*********************************************************************/
/*! exports provided: createNotice, createSuccessNotice, createInfoNotice, createErrorNotice, createWarningNotice, removeNotice */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "createNotice", function() { return createNotice; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "createSuccessNotice", function() { return createSuccessNotice; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "createInfoNotice", function() { return createInfoNotice; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "createErrorNotice", function() { return createErrorNotice; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "createWarningNotice", function() { return createWarningNotice; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "removeNotice", function() { return removeNotice; });
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./constants */ "./node_modules/@gechiui/notices/build-module/store/constants.js");
/**
 * External dependencies
 */

/**
 * Internal dependencies
 */


/**
 * @typedef {Object} GCNoticeAction Object describing a user action option associated with a notice.
 *
 * @property {string}    label   Message to use as action label.
 * @property {?string}   url     Optional URL of resource if action incurs
 *                               browser navigation.
 * @property {?Function} onClick Optional function to invoke when action is
 *                               triggered by user.
 *
 */

/**
 * Returns an action object used in signalling that a notice is to be created.
 *
 * @param {string}                [status='info']              Notice status.
 * @param {string}                content                      Notice message.
 * @param {Object}                [options]                    Notice options.
 * @param {string}                [options.context='global']   Context under which to
 *                                                             group notice.
 * @param {string}                [options.id]                 Identifier for notice.
 *                                                             Automatically assigned
 *                                                             if not specified.
 * @param {boolean}               [options.isDismissible=true] Whether the notice can
 *                                                             be dismissed by user.
 * @param {string}                [options.type='default']     Type of notice, one of
 *                                                             `default`, or `snackbar`.
 * @param {boolean}               [options.speak=true]         Whether the notice
 *                                                             content should be
 *                                                             announced to screen
 *                                                             readers.
 * @param {Array<GCNoticeAction>} [options.actions]            User actions to be
 *                                                             presented with notice.
 * @param {Object}                [options.icon]               An icon displayed with the notice.
 * @param {boolean}               [options.explicitDismiss]    Whether the notice includes
 *                                                             an explict dismiss button and
 *                                                             can't be dismissed by clicking
 *                                                             the body of the notice.
 * @param {Function}              [options.onDismiss]          Called when the notice is dismissed.
 *
 * @return {Object} Action object.
 */

function createNotice() {
  let status = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : _constants__WEBPACK_IMPORTED_MODULE_1__["DEFAULT_STATUS"];
  let content = arguments.length > 1 ? arguments[1] : undefined;
  let options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
  const {
    speak = true,
    isDismissible = true,
    context = _constants__WEBPACK_IMPORTED_MODULE_1__["DEFAULT_CONTEXT"],
    id = Object(lodash__WEBPACK_IMPORTED_MODULE_0__["uniqueId"])(context),
    actions = [],
    type = 'default',
    __unstableHTML,
    icon = null,
    explicitDismiss = false,
    onDismiss
  } = options; // The supported value shape of content is currently limited to plain text
  // strings. To avoid setting expectation that e.g. a GCElement could be
  // supported, cast to a string.

  content = String(content);
  return {
    type: 'CREATE_NOTICE',
    context,
    notice: {
      id,
      status,
      content,
      spokenMessage: speak ? content : null,
      __unstableHTML,
      isDismissible,
      actions,
      type,
      icon,
      explicitDismiss,
      onDismiss
    }
  };
}
/**
 * Returns an action object used in signalling that a success notice is to be
 * created. Refer to `createNotice` for options documentation.
 *
 * @see createNotice
 *
 * @param {string} content   Notice message.
 * @param {Object} [options] Optional notice options.
 *
 * @return {Object} Action object.
 */

function createSuccessNotice(content, options) {
  return createNotice('success', content, options);
}
/**
 * Returns an action object used in signalling that an info notice is to be
 * created. Refer to `createNotice` for options documentation.
 *
 * @see createNotice
 *
 * @param {string} content   Notice message.
 * @param {Object} [options] Optional notice options.
 *
 * @return {Object} Action object.
 */

function createInfoNotice(content, options) {
  return createNotice('info', content, options);
}
/**
 * Returns an action object used in signalling that an error notice is to be
 * created. Refer to `createNotice` for options documentation.
 *
 * @see createNotice
 *
 * @param {string} content   Notice message.
 * @param {Object} [options] Optional notice options.
 *
 * @return {Object} Action object.
 */

function createErrorNotice(content, options) {
  return createNotice('error', content, options);
}
/**
 * Returns an action object used in signalling that a warning notice is to be
 * created. Refer to `createNotice` for options documentation.
 *
 * @see createNotice
 *
 * @param {string} content   Notice message.
 * @param {Object} [options] Optional notice options.
 *
 * @return {Object} Action object.
 */

function createWarningNotice(content, options) {
  return createNotice('warning', content, options);
}
/**
 * Returns an action object used in signalling that a notice is to be removed.
 *
 * @param {string} id                 Notice unique identifier.
 * @param {string} [context='global'] Optional context (grouping) in which the notice is
 *                                    intended to appear. Defaults to default context.
 *
 * @return {Object} Action object.
 */

function removeNotice(id) {
  let context = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : _constants__WEBPACK_IMPORTED_MODULE_1__["DEFAULT_CONTEXT"];
  return {
    type: 'REMOVE_NOTICE',
    id,
    context
  };
}


/***/ }),

/***/ "./node_modules/@gechiui/notices/build-module/store/constants.js":
/*!***********************************************************************!*\
  !*** ./node_modules/@gechiui/notices/build-module/store/constants.js ***!
  \***********************************************************************/
/*! exports provided: DEFAULT_CONTEXT, DEFAULT_STATUS */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "DEFAULT_CONTEXT", function() { return DEFAULT_CONTEXT; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "DEFAULT_STATUS", function() { return DEFAULT_STATUS; });
/**
 * Default context to use for notice grouping when not otherwise specified. Its
 * specific value doesn't hold much meaning, but it must be reasonably unique
 * and, more importantly, referenced consistently in the store implementation.
 *
 * @type {string}
 */
const DEFAULT_CONTEXT = 'global';
/**
 * Default notice status.
 *
 * @type {string}
 */

const DEFAULT_STATUS = 'info';


/***/ }),

/***/ "./node_modules/@gechiui/notices/build-module/store/index.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@gechiui/notices/build-module/store/index.js ***!
  \*******************************************************************/
/*! exports provided: store */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "store", function() { return store; });
/* harmony import */ var _gechiui_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @gechiui/data */ "@gechiui/data");
/* harmony import */ var _gechiui_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_gechiui_data__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _reducer__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./reducer */ "./node_modules/@gechiui/notices/build-module/store/reducer.js");
/* harmony import */ var _actions__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./actions */ "./node_modules/@gechiui/notices/build-module/store/actions.js");
/* harmony import */ var _selectors__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./selectors */ "./node_modules/@gechiui/notices/build-module/store/selectors.js");
/**
 * GeChiUI dependencies
 */

/**
 * Internal dependencies
 */




/**
 * Store definition for the notices namespace.
 *
 * @see https://github.com/GeChiUI/gutenberg/blob/HEAD/packages/data/README.md#createReduxStore
 *
 * @type {Object}
 */

const store = Object(_gechiui_data__WEBPACK_IMPORTED_MODULE_0__["createReduxStore"])('core/notices', {
  reducer: _reducer__WEBPACK_IMPORTED_MODULE_1__["default"],
  actions: _actions__WEBPACK_IMPORTED_MODULE_2__,
  selectors: _selectors__WEBPACK_IMPORTED_MODULE_3__
});
Object(_gechiui_data__WEBPACK_IMPORTED_MODULE_0__["register"])(store);


/***/ }),

/***/ "./node_modules/@gechiui/notices/build-module/store/reducer.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@gechiui/notices/build-module/store/reducer.js ***!
  \*********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _utils_on_sub_key__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./utils/on-sub-key */ "./node_modules/@gechiui/notices/build-module/store/utils/on-sub-key.js");
/**
 * External dependencies
 */

/**
 * Internal dependencies
 */


/**
 * Reducer returning the next notices state. The notices state is an object
 * where each key is a context, its value an array of notice objects.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */

const notices = Object(_utils_on_sub_key__WEBPACK_IMPORTED_MODULE_1__["default"])('context')(function () {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];
  let action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'CREATE_NOTICE':
      // Avoid duplicates on ID.
      return [...Object(lodash__WEBPACK_IMPORTED_MODULE_0__["reject"])(state, {
        id: action.notice.id
      }), action.notice];

    case 'REMOVE_NOTICE':
      return Object(lodash__WEBPACK_IMPORTED_MODULE_0__["reject"])(state, {
        id: action.id
      });
  }

  return state;
});
/* harmony default export */ __webpack_exports__["default"] = (notices);


/***/ }),

/***/ "./node_modules/@gechiui/notices/build-module/store/selectors.js":
/*!***********************************************************************!*\
  !*** ./node_modules/@gechiui/notices/build-module/store/selectors.js ***!
  \***********************************************************************/
/*! exports provided: getNotices */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getNotices", function() { return getNotices; });
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./constants */ "./node_modules/@gechiui/notices/build-module/store/constants.js");
/**
 * Internal dependencies
 */

/** @typedef {import('./actions').GCNoticeAction} GCNoticeAction */

/**
 * The default empty set of notices to return when there are no notices
 * assigned for a given notices context. This can occur if the getNotices
 * selector is called without a notice ever having been created for the
 * context. A shared value is used to ensure referential equality between
 * sequential selector calls, since otherwise `[] !== []`.
 *
 * @type {Array}
 */

const DEFAULT_NOTICES = [];
/**
 * @typedef {Object} GCNotice Notice object.
 *
 * @property {string}           id             Unique identifier of notice.
 * @property {string}           status         Status of notice, one of `success`,
 *                                             `info`, `error`, or `warning`. Defaults
 *                                             to `info`.
 * @property {string}           content        Notice message.
 * @property {string}           spokenMessage  Audibly announced message text used by
 *                                             assistive technologies.
 * @property {string}           __unstableHTML Notice message as raw HTML. Intended to
 *                                             serve primarily for compatibility of
 *                                             server-rendered notices, and SHOULD NOT
 *                                             be used for notices. It is subject to
 *                                             removal without notice.
 * @property {boolean}          isDismissible  Whether the notice can be dismissed by
 *                                             user. Defaults to `true`.
 * @property {string}           type           Type of notice, one of `default`,
 *                                             or `snackbar`. Defaults to `default`.
 * @property {boolean}          speak          Whether the notice content should be
 *                                             announced to screen readers. Defaults to
 *                                             `true`.
 * @property {GCNoticeAction[]} actions        User actions to present with notice.
 *
 */

/**
 * Returns all notices as an array, optionally for a given context. Defaults to
 * the global context.
 *
 * @param {Object}  state   Notices state.
 * @param {?string} context Optional grouping context.
 *
 * @return {GCNotice[]} Array of notices.
 */

function getNotices(state) {
  let context = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : _constants__WEBPACK_IMPORTED_MODULE_0__["DEFAULT_CONTEXT"];
  return state[context] || DEFAULT_NOTICES;
}


/***/ }),

/***/ "./node_modules/@gechiui/notices/build-module/store/utils/on-sub-key.js":
/*!******************************************************************************!*\
  !*** ./node_modules/@gechiui/notices/build-module/store/utils/on-sub-key.js ***!
  \******************************************************************************/
/*! exports provided: onSubKey, default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "onSubKey", function() { return onSubKey; });
/**
 * Higher-order reducer creator which creates a combined reducer object, keyed
 * by a property on the action object.
 *
 * @param {string} actionProperty Action property by which to key object.
 *
 * @return {Function} Higher-order reducer.
 */
const onSubKey = actionProperty => reducer => function () {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  let action = arguments.length > 1 ? arguments[1] : undefined;
  // Retrieve subkey from action. Do not track if undefined; useful for cases
  // where reducer is scoped by action shape.
  const key = action[actionProperty];

  if (key === undefined) {
    return state;
  } // Avoid updating state if unchanged. Note that this also accounts for a
  // reducer which returns undefined on a key which is not yet tracked.


  const nextKeyState = reducer(state[key], action);

  if (nextKeyState === state[key]) {
    return state;
  }

  return { ...state,
    [key]: nextKeyState
  };
};
/* harmony default export */ __webpack_exports__["default"] = (onSubKey);


/***/ }),

/***/ "@gechiui/data":
/*!******************************!*\
  !*** external ["gc","data"] ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["gc"]["data"]; }());

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
//# sourceMappingURL=notices.js.map