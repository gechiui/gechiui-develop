this["gc"] = this["gc"] || {}; this["gc"]["keyboardShortcuts"] =
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
/******/ 	return __webpack_require__(__webpack_require__.s = "./node_modules/@gechiui/keyboard-shortcuts/build-module/index.js");
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

/***/ "./node_modules/@gechiui/keyboard-shortcuts/build-module/components/shortcut-provider.js":
/*!***********************************************************************************************!*\
  !*** ./node_modules/@gechiui/keyboard-shortcuts/build-module/components/shortcut-provider.js ***!
  \***********************************************************************************************/
/*! exports provided: ShortcutProvider */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "ShortcutProvider", function() { return ShortcutProvider; });
/* harmony import */ var _babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/extends */ "./node_modules/@babel/runtime/helpers/esm/extends.js");
/* harmony import */ var _gechiui_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @gechiui/element */ "@gechiui/element");
/* harmony import */ var _gechiui_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _context__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../context */ "./node_modules/@gechiui/keyboard-shortcuts/build-module/context.js");



/**
 * GeChiUI dependencies
 */

/**
 * Internal dependencies
 */


const {
  Provider
} = _context__WEBPACK_IMPORTED_MODULE_2__["context"];
/**
 * Handles callbacks added to context by `useShortcut`.
 *
 * @param {Object} props Props to pass to `div`.
 *
 * @return {import('@gechiui/element').GCElement} Component.
 */

function ShortcutProvider(props) {
  const keyboardShortcuts = Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["useRef"])(new Set());

  function onKeyDown(event) {
    if (props.onKeyDown) props.onKeyDown(event);

    for (const keyboardShortcut of keyboardShortcuts.current) {
      keyboardShortcut(event);
    }
  }
  /* eslint-disable jsx-a11y/no-static-element-interactions */


  return Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])(Provider, {
    value: keyboardShortcuts
  }, Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])("div", Object(_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__["default"])({}, props, {
    onKeyDown: onKeyDown
  })));
  /* eslint-enable jsx-a11y/no-static-element-interactions */
}


/***/ }),

/***/ "./node_modules/@gechiui/keyboard-shortcuts/build-module/context.js":
/*!**************************************************************************!*\
  !*** ./node_modules/@gechiui/keyboard-shortcuts/build-module/context.js ***!
  \**************************************************************************/
/*! exports provided: context */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "context", function() { return context; });
/* harmony import */ var _gechiui_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @gechiui/element */ "@gechiui/element");
/* harmony import */ var _gechiui_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_gechiui_element__WEBPACK_IMPORTED_MODULE_0__);
/**
 * GeChiUI dependencies
 */

const context = Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_0__["createContext"])();


/***/ }),

/***/ "./node_modules/@gechiui/keyboard-shortcuts/build-module/hooks/use-shortcut-event-match.js":
/*!*************************************************************************************************!*\
  !*** ./node_modules/@gechiui/keyboard-shortcuts/build-module/hooks/use-shortcut-event-match.js ***!
  \*************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return useShortcutEventMatch; });
/* harmony import */ var _gechiui_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @gechiui/data */ "@gechiui/data");
/* harmony import */ var _gechiui_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_gechiui_data__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _gechiui_keycodes__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @gechiui/keycodes */ "@gechiui/keycodes");
/* harmony import */ var _gechiui_keycodes__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_gechiui_keycodes__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../store */ "./node_modules/@gechiui/keyboard-shortcuts/build-module/store/index.js");
/**
 * GeChiUI dependencies
 */


/**
 * Internal dependencies
 */


/**
 * Returns a function to check if a keyboard event matches a shortcut name.
 *
 * @return {Function} A function to to check if a keyboard event matches a
 *                    predefined shortcut combination.
 */

function useShortcutEventMatch() {
  const {
    getAllShortcutKeyCombinations
  } = Object(_gechiui_data__WEBPACK_IMPORTED_MODULE_0__["useSelect"])(_store__WEBPACK_IMPORTED_MODULE_2__["store"]);
  /**
   * A function to check if a keyboard event matches a predefined shortcut
   * combination.
   *
   * @param {string}        name  Shortcut name.
   * @param {KeyboardEvent} event Event to check.
   *
   * @return {boolean} True if the event matches any shortcuts, false if not.
   */

  function isMatch(name, event) {
    return getAllShortcutKeyCombinations(name).some(_ref => {
      let {
        modifier,
        character
      } = _ref;
      return _gechiui_keycodes__WEBPACK_IMPORTED_MODULE_1__["isKeyboardEvent"][modifier](event, character);
    });
  }

  return isMatch;
}


/***/ }),

/***/ "./node_modules/@gechiui/keyboard-shortcuts/build-module/hooks/use-shortcut.js":
/*!*************************************************************************************!*\
  !*** ./node_modules/@gechiui/keyboard-shortcuts/build-module/hooks/use-shortcut.js ***!
  \*************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return useShortcut; });
/* harmony import */ var _gechiui_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @gechiui/element */ "@gechiui/element");
/* harmony import */ var _gechiui_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_gechiui_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _use_shortcut_event_match__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./use-shortcut-event-match */ "./node_modules/@gechiui/keyboard-shortcuts/build-module/hooks/use-shortcut-event-match.js");
/* harmony import */ var _context__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../context */ "./node_modules/@gechiui/keyboard-shortcuts/build-module/context.js");
/**
 * GeChiUI dependencies
 */

/**
 * Internal dependencies
 */



/**
 * Attach a keyboard shortcut handler.
 *
 * @param {string}   name               Shortcut name.
 * @param {Function} callback           Shortcut callback.
 * @param {Object}   options            Shortcut options.
 * @param {boolean}  options.isDisabled Whether to disable to shortut.
 */

function useShortcut(name, callback) {
  let {
    isDisabled
  } = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
  const shortcuts = Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_0__["useContext"])(_context__WEBPACK_IMPORTED_MODULE_2__["context"]);
  const isMatch = Object(_use_shortcut_event_match__WEBPACK_IMPORTED_MODULE_1__["default"])();
  const callbackRef = Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_0__["useRef"])();
  callbackRef.current = callback;
  Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_0__["useEffect"])(() => {
    if (isDisabled) {
      return;
    }

    function _callback(event) {
      if (isMatch(name, event)) {
        callbackRef.current(event);
      }
    }

    shortcuts.current.add(_callback);
    return () => {
      shortcuts.current.delete(_callback);
    };
  }, [name, isDisabled]);
}


/***/ }),

/***/ "./node_modules/@gechiui/keyboard-shortcuts/build-module/index.js":
/*!************************************************************************!*\
  !*** ./node_modules/@gechiui/keyboard-shortcuts/build-module/index.js ***!
  \************************************************************************/
/*! exports provided: store, useShortcut, ShortcutProvider, __unstableUseShortcutEventMatch */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./store */ "./node_modules/@gechiui/keyboard-shortcuts/build-module/store/index.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "store", function() { return _store__WEBPACK_IMPORTED_MODULE_0__["store"]; });

/* harmony import */ var _hooks_use_shortcut__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./hooks/use-shortcut */ "./node_modules/@gechiui/keyboard-shortcuts/build-module/hooks/use-shortcut.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "useShortcut", function() { return _hooks_use_shortcut__WEBPACK_IMPORTED_MODULE_1__["default"]; });

/* harmony import */ var _components_shortcut_provider__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./components/shortcut-provider */ "./node_modules/@gechiui/keyboard-shortcuts/build-module/components/shortcut-provider.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "ShortcutProvider", function() { return _components_shortcut_provider__WEBPACK_IMPORTED_MODULE_2__["ShortcutProvider"]; });

/* harmony import */ var _hooks_use_shortcut_event_match__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./hooks/use-shortcut-event-match */ "./node_modules/@gechiui/keyboard-shortcuts/build-module/hooks/use-shortcut-event-match.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "__unstableUseShortcutEventMatch", function() { return _hooks_use_shortcut_event_match__WEBPACK_IMPORTED_MODULE_3__["default"]; });







/***/ }),

/***/ "./node_modules/@gechiui/keyboard-shortcuts/build-module/store/actions.js":
/*!********************************************************************************!*\
  !*** ./node_modules/@gechiui/keyboard-shortcuts/build-module/store/actions.js ***!
  \********************************************************************************/
/*! exports provided: registerShortcut, unregisterShortcut */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "registerShortcut", function() { return registerShortcut; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "unregisterShortcut", function() { return unregisterShortcut; });
/** @typedef {import('@gechiui/keycodes').GCKeycodeModifier} GCKeycodeModifier */

/**
 * Keyboard key combination.
 *
 * @typedef {Object} GCShortcutKeyCombination
 *
 * @property {string}                      character Character.
 * @property {GCKeycodeModifier|undefined} modifier  Modifier.
 */

/**
 * Configuration of a registered keyboard shortcut.
 *
 * @typedef {Object} GCShortcutConfig
 *
 * @property {string}                     name           Shortcut name.
 * @property {string}                     category       Shortcut category.
 * @property {string}                     description    Shortcut description.
 * @property {GCShortcutKeyCombination}   keyCombination Shortcut key combination.
 * @property {GCShortcutKeyCombination[]} [aliases]      Shortcut aliases.
 */

/**
 * Returns an action object used to register a new keyboard shortcut.
 *
 * @param {GCShortcutConfig} config Shortcut config.
 *
 * @return {Object} action.
 */
function registerShortcut(_ref) {
  let {
    name,
    category,
    description,
    keyCombination,
    aliases
  } = _ref;
  return {
    type: 'REGISTER_SHORTCUT',
    name,
    category,
    keyCombination,
    aliases,
    description
  };
}
/**
 * Returns an action object used to unregister a keyboard shortcut.
 *
 * @param {string} name Shortcut name.
 *
 * @return {Object} action.
 */

function unregisterShortcut(name) {
  return {
    type: 'UNREGISTER_SHORTCUT',
    name
  };
}


/***/ }),

/***/ "./node_modules/@gechiui/keyboard-shortcuts/build-module/store/index.js":
/*!******************************************************************************!*\
  !*** ./node_modules/@gechiui/keyboard-shortcuts/build-module/store/index.js ***!
  \******************************************************************************/
/*! exports provided: store */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "store", function() { return store; });
/* harmony import */ var _gechiui_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @gechiui/data */ "@gechiui/data");
/* harmony import */ var _gechiui_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_gechiui_data__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _reducer__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./reducer */ "./node_modules/@gechiui/keyboard-shortcuts/build-module/store/reducer.js");
/* harmony import */ var _actions__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./actions */ "./node_modules/@gechiui/keyboard-shortcuts/build-module/store/actions.js");
/* harmony import */ var _selectors__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./selectors */ "./node_modules/@gechiui/keyboard-shortcuts/build-module/store/selectors.js");
/**
 * GeChiUI dependencies
 */

/**
 * Internal dependencies
 */




const STORE_NAME = 'core/keyboard-shortcuts';
/**
 * Store definition for the keyboard shortcuts namespace.
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

/***/ "./node_modules/@gechiui/keyboard-shortcuts/build-module/store/reducer.js":
/*!********************************************************************************!*\
  !*** ./node_modules/@gechiui/keyboard-shortcuts/build-module/store/reducer.js ***!
  \********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_0__);
/**
 * External dependencies
 */

/**
 * Reducer returning the registered shortcuts
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
    case 'REGISTER_SHORTCUT':
      return { ...state,
        [action.name]: {
          category: action.category,
          keyCombination: action.keyCombination,
          aliases: action.aliases,
          description: action.description
        }
      };

    case 'UNREGISTER_SHORTCUT':
      return Object(lodash__WEBPACK_IMPORTED_MODULE_0__["omit"])(state, action.name);
  }

  return state;
}

/* harmony default export */ __webpack_exports__["default"] = (reducer);


/***/ }),

/***/ "./node_modules/@gechiui/keyboard-shortcuts/build-module/store/selectors.js":
/*!**********************************************************************************!*\
  !*** ./node_modules/@gechiui/keyboard-shortcuts/build-module/store/selectors.js ***!
  \**********************************************************************************/
/*! exports provided: getShortcutKeyCombination, getShortcutRepresentation, getShortcutDescription, getShortcutAliases, getAllShortcutKeyCombinations, getAllShortcutRawKeyCombinations, getCategoryShortcuts */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getShortcutKeyCombination", function() { return getShortcutKeyCombination; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getShortcutRepresentation", function() { return getShortcutRepresentation; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getShortcutDescription", function() { return getShortcutDescription; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getShortcutAliases", function() { return getShortcutAliases; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getAllShortcutKeyCombinations", function() { return getAllShortcutKeyCombinations; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getAllShortcutRawKeyCombinations", function() { return getAllShortcutRawKeyCombinations; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getCategoryShortcuts", function() { return getCategoryShortcuts; });
/* harmony import */ var rememo__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! rememo */ "./node_modules/rememo/es/rememo.js");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _gechiui_keycodes__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @gechiui/keycodes */ "@gechiui/keycodes");
/* harmony import */ var _gechiui_keycodes__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_gechiui_keycodes__WEBPACK_IMPORTED_MODULE_2__);
/**
 * External dependencies
 */


/**
 * GeChiUI dependencies
 */


/** @typedef {import('./actions').GCShortcutKeyCombination} GCShortcutKeyCombination */

/** @typedef {import('@gechiui/keycodes').GCKeycodeHandlerByModifier} GCKeycodeHandlerByModifier */

/**
 * Shared reference to an empty array for cases where it is important to avoid
 * returning a new array reference on every invocation.
 *
 * @type {Array<any>}
 */

const EMPTY_ARRAY = [];
/**
 * Shortcut formatting methods.
 *
 * @property {GCKeycodeHandlerByModifier} display     Display formatting.
 * @property {GCKeycodeHandlerByModifier} rawShortcut Raw shortcut formatting.
 * @property {GCKeycodeHandlerByModifier} ariaLabel   ARIA label formatting.
 */

const FORMATTING_METHODS = {
  display: _gechiui_keycodes__WEBPACK_IMPORTED_MODULE_2__["displayShortcut"],
  raw: _gechiui_keycodes__WEBPACK_IMPORTED_MODULE_2__["rawShortcut"],
  ariaLabel: _gechiui_keycodes__WEBPACK_IMPORTED_MODULE_2__["shortcutAriaLabel"]
};
/**
 * Returns a string representing the key combination.
 *
 * @param {?GCShortcutKeyCombination} shortcut       Key combination.
 * @param {keyof FORMATTING_METHODS}  representation Type of representation
 *                                                   (display, raw, ariaLabel).
 *
 * @return {string?} Shortcut representation.
 */

function getKeyCombinationRepresentation(shortcut, representation) {
  if (!shortcut) {
    return null;
  }

  return shortcut.modifier ? FORMATTING_METHODS[representation][shortcut.modifier](shortcut.character) : shortcut.character;
}
/**
 * Returns the main key combination for a given shortcut name.
 *
 * @param {Object} state Global state.
 * @param {string} name  Shortcut name.
 *
 * @return {GCShortcutKeyCombination?} Key combination.
 */


function getShortcutKeyCombination(state, name) {
  return state[name] ? state[name].keyCombination : null;
}
/**
 * Returns a string representing the main key combination for a given shortcut name.
 *
 * @param {Object}                   state          Global state.
 * @param {string}                   name           Shortcut name.
 * @param {keyof FORMATTING_METHODS} representation Type of representation
 *                                                  (display, raw, ariaLabel).
 *
 * @return {string?} Shortcut representation.
 */

function getShortcutRepresentation(state, name) {
  let representation = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 'display';
  const shortcut = getShortcutKeyCombination(state, name);
  return getKeyCombinationRepresentation(shortcut, representation);
}
/**
 * Returns the shortcut description given its name.
 *
 * @param {Object} state Global state.
 * @param {string} name  Shortcut name.
 *
 * @return {string?} Shortcut description.
 */

function getShortcutDescription(state, name) {
  return state[name] ? state[name].description : null;
}
/**
 * Returns the aliases for a given shortcut name.
 *
 * @param {Object} state Global state.
 * @param {string} name  Shortcut name.
 *
 * @return {GCShortcutKeyCombination[]} Key combinations.
 */

function getShortcutAliases(state, name) {
  return state[name] && state[name].aliases ? state[name].aliases : EMPTY_ARRAY;
}
const getAllShortcutKeyCombinations = Object(rememo__WEBPACK_IMPORTED_MODULE_0__["default"])((state, name) => {
  return Object(lodash__WEBPACK_IMPORTED_MODULE_1__["compact"])([getShortcutKeyCombination(state, name), ...getShortcutAliases(state, name)]);
}, (state, name) => [state[name]]);
/**
 * Returns the raw representation of all the keyboard combinations of a given shortcut name.
 *
 * @param {Object} state Global state.
 * @param {string} name  Shortcut name.
 *
 * @return {string[]} Shortcuts.
 */

const getAllShortcutRawKeyCombinations = Object(rememo__WEBPACK_IMPORTED_MODULE_0__["default"])((state, name) => {
  return getAllShortcutKeyCombinations(state, name).map(combination => getKeyCombinationRepresentation(combination, 'raw'));
}, (state, name) => [state[name]]);
/**
 * Returns the shortcut names list for a given category name.
 *
 * @param {Object} state Global state.
 * @param {string} name  Category name.
 *
 * @return {string[]} Shortcut names.
 */

const getCategoryShortcuts = Object(rememo__WEBPACK_IMPORTED_MODULE_0__["default"])((state, categoryName) => {
  return Object.entries(state).filter(_ref => {
    let [, shortcut] = _ref;
    return shortcut.category === categoryName;
  }).map(_ref2 => {
    let [name] = _ref2;
    return name;
  });
}, state => [state]);


/***/ }),

/***/ "./node_modules/rememo/es/rememo.js":
/*!******************************************!*\
  !*** ./node_modules/rememo/es/rememo.js ***!
  \******************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);


var LEAF_KEY, hasWeakMap;

/**
 * Arbitrary value used as key for referencing cache object in WeakMap tree.
 *
 * @type {Object}
 */
LEAF_KEY = {};

/**
 * Whether environment supports WeakMap.
 *
 * @type {boolean}
 */
hasWeakMap = typeof WeakMap !== 'undefined';

/**
 * Returns the first argument as the sole entry in an array.
 *
 * @param {*} value Value to return.
 *
 * @return {Array} Value returned as entry in array.
 */
function arrayOf( value ) {
	return [ value ];
}

/**
 * Returns true if the value passed is object-like, or false otherwise. A value
 * is object-like if it can support property assignment, e.g. object or array.
 *
 * @param {*} value Value to test.
 *
 * @return {boolean} Whether value is object-like.
 */
function isObjectLike( value ) {
	return !! value && 'object' === typeof value;
}

/**
 * Creates and returns a new cache object.
 *
 * @return {Object} Cache object.
 */
function createCache() {
	var cache = {
		clear: function() {
			cache.head = null;
		},
	};

	return cache;
}

/**
 * Returns true if entries within the two arrays are strictly equal by
 * reference from a starting index.
 *
 * @param {Array}  a         First array.
 * @param {Array}  b         Second array.
 * @param {number} fromIndex Index from which to start comparison.
 *
 * @return {boolean} Whether arrays are shallowly equal.
 */
function isShallowEqual( a, b, fromIndex ) {
	var i;

	if ( a.length !== b.length ) {
		return false;
	}

	for ( i = fromIndex; i < a.length; i++ ) {
		if ( a[ i ] !== b[ i ] ) {
			return false;
		}
	}

	return true;
}

/**
 * Returns a memoized selector function. The getDependants function argument is
 * called before the memoized selector and is expected to return an immutable
 * reference or array of references on which the selector depends for computing
 * its own return value. The memoize cache is preserved only as long as those
 * dependant references remain the same. If getDependants returns a different
 * reference(s), the cache is cleared and the selector value regenerated.
 *
 * @param {Function} selector      Selector function.
 * @param {Function} getDependants Dependant getter returning an immutable
 *                                 reference or array of reference used in
 *                                 cache bust consideration.
 *
 * @return {Function} Memoized selector.
 */
/* harmony default export */ __webpack_exports__["default"] = (function( selector, getDependants ) {
	var rootCache, getCache;

	// Use object source as dependant if getter not provided
	if ( ! getDependants ) {
		getDependants = arrayOf;
	}

	/**
	 * Returns the root cache. If WeakMap is supported, this is assigned to the
	 * root WeakMap cache set, otherwise it is a shared instance of the default
	 * cache object.
	 *
	 * @return {(WeakMap|Object)} Root cache object.
	 */
	function getRootCache() {
		return rootCache;
	}

	/**
	 * Returns the cache for a given dependants array. When possible, a WeakMap
	 * will be used to create a unique cache for each set of dependants. This
	 * is feasible due to the nature of WeakMap in allowing garbage collection
	 * to occur on entries where the key object is no longer referenced. Since
	 * WeakMap requires the key to be an object, this is only possible when the
	 * dependant is object-like. The root cache is created as a hierarchy where
	 * each top-level key is the first entry in a dependants set, the value a
	 * WeakMap where each key is the next dependant, and so on. This continues
	 * so long as the dependants are object-like. If no dependants are object-
	 * like, then the cache is shared across all invocations.
	 *
	 * @see isObjectLike
	 *
	 * @param {Array} dependants Selector dependants.
	 *
	 * @return {Object} Cache object.
	 */
	function getWeakMapCache( dependants ) {
		var caches = rootCache,
			isUniqueByDependants = true,
			i, dependant, map, cache;

		for ( i = 0; i < dependants.length; i++ ) {
			dependant = dependants[ i ];

			// Can only compose WeakMap from object-like key.
			if ( ! isObjectLike( dependant ) ) {
				isUniqueByDependants = false;
				break;
			}

			// Does current segment of cache already have a WeakMap?
			if ( caches.has( dependant ) ) {
				// Traverse into nested WeakMap.
				caches = caches.get( dependant );
			} else {
				// Create, set, and traverse into a new one.
				map = new WeakMap();
				caches.set( dependant, map );
				caches = map;
			}
		}

		// We use an arbitrary (but consistent) object as key for the last item
		// in the WeakMap to serve as our running cache.
		if ( ! caches.has( LEAF_KEY ) ) {
			cache = createCache();
			cache.isUniqueByDependants = isUniqueByDependants;
			caches.set( LEAF_KEY, cache );
		}

		return caches.get( LEAF_KEY );
	}

	// Assign cache handler by availability of WeakMap
	getCache = hasWeakMap ? getWeakMapCache : getRootCache;

	/**
	 * Resets root memoization cache.
	 */
	function clear() {
		rootCache = hasWeakMap ? new WeakMap() : createCache();
	}

	// eslint-disable-next-line jsdoc/check-param-names
	/**
	 * The augmented selector call, considering first whether dependants have
	 * changed before passing it to underlying memoize function.
	 *
	 * @param {Object} source    Source object for derivation.
	 * @param {...*}   extraArgs Additional arguments to pass to selector.
	 *
	 * @return {*} Selector result.
	 */
	function callSelector( /* source, ...extraArgs */ ) {
		var len = arguments.length,
			cache, node, i, args, dependants;

		// Create copy of arguments (avoid leaking deoptimization).
		args = new Array( len );
		for ( i = 0; i < len; i++ ) {
			args[ i ] = arguments[ i ];
		}

		dependants = getDependants.apply( null, args );
		cache = getCache( dependants );

		// If not guaranteed uniqueness by dependants (primitive type or lack
		// of WeakMap support), shallow compare against last dependants and, if
		// references have changed, destroy cache to recalculate result.
		if ( ! cache.isUniqueByDependants ) {
			if ( cache.lastDependants && ! isShallowEqual( dependants, cache.lastDependants, 0 ) ) {
				cache.clear();
			}

			cache.lastDependants = dependants;
		}

		node = cache.head;
		while ( node ) {
			// Check whether node arguments match arguments
			if ( ! isShallowEqual( node.args, args, 1 ) ) {
				node = node.next;
				continue;
			}

			// At this point we can assume we've found a match

			// Surface matched node to head if not already
			if ( node !== cache.head ) {
				// Adjust siblings to point to each other.
				node.prev.next = node.next;
				if ( node.next ) {
					node.next.prev = node.prev;
				}

				node.next = cache.head;
				node.prev = null;
				cache.head.prev = node;
				cache.head = node;
			}

			// Return immediately
			return node.val;
		}

		// No cached value found. Continue to insertion phase:

		node = {
			// Generate the result from original function
			val: selector.apply( null, args ),
		};

		// Avoid including the source object in the cache.
		args[ 0 ] = null;
		node.args = args;

		// Don't need to check whether node is already head, since it would
		// have been returned above already if it was

		// Shift existing head down list
		if ( cache.head ) {
			cache.head.prev = node;
			node.next = cache.head;
		}

		cache.head = node;

		return node.val;
	}

	callSelector.getDependants = getDependants;
	callSelector.clear = clear;
	clear();

	return callSelector;
});


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

/***/ "@gechiui/keycodes":
/*!**********************************!*\
  !*** external ["gc","keycodes"] ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["gc"]["keycodes"]; }());

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
//# sourceMappingURL=keyboard-shortcuts.js.map