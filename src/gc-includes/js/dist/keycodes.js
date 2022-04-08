this["gc"] = this["gc"] || {}; this["gc"]["keycodes"] =
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
/******/ 	return __webpack_require__(__webpack_require__.s = "./node_modules/@gechiui/keycodes/build-module/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/@gechiui/keycodes/build-module/index.js":
/*!**************************************************************!*\
  !*** ./node_modules/@gechiui/keycodes/build-module/index.js ***!
  \**************************************************************/
/*! exports provided: BACKSPACE, TAB, ENTER, ESCAPE, SPACE, PAGEUP, PAGEDOWN, END, HOME, LEFT, UP, RIGHT, DOWN, DELETE, F10, ALT, CTRL, COMMAND, SHIFT, ZERO, modifiers, rawShortcut, displayShortcutList, displayShortcut, shortcutAriaLabel, isKeyboardEvent */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "BACKSPACE", function() { return BACKSPACE; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "TAB", function() { return TAB; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "ENTER", function() { return ENTER; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "ESCAPE", function() { return ESCAPE; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "SPACE", function() { return SPACE; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "PAGEUP", function() { return PAGEUP; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "PAGEDOWN", function() { return PAGEDOWN; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "END", function() { return END; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "HOME", function() { return HOME; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "LEFT", function() { return LEFT; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "UP", function() { return UP; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "RIGHT", function() { return RIGHT; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "DOWN", function() { return DOWN; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "DELETE", function() { return DELETE; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "F10", function() { return F10; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "ALT", function() { return ALT; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "CTRL", function() { return CTRL; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "COMMAND", function() { return COMMAND; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "SHIFT", function() { return SHIFT; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "ZERO", function() { return ZERO; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "modifiers", function() { return modifiers; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "rawShortcut", function() { return rawShortcut; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "displayShortcutList", function() { return displayShortcutList; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "displayShortcut", function() { return displayShortcut; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "shortcutAriaLabel", function() { return shortcutAriaLabel; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "isKeyboardEvent", function() { return isKeyboardEvent; });
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _gechiui_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @gechiui/i18n */ "@gechiui/i18n");
/* harmony import */ var _gechiui_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _platform__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./platform */ "./node_modules/@gechiui/keycodes/build-module/platform.js");
/**
 * Note: The order of the modifier keys in many of the [foo]Shortcut()
 * functions in this file are intentional and should not be changed. They're
 * designed to fit with the standard menu keyboard shortcuts shown in the
 * user's platform.
 *
 * For example, on MacOS menu shortcuts will place Shift before Command, but
 * on Windows Control will usually come first. So don't provide your own
 * shortcut combos directly to keyboardShortcut().
 */

/**
 * External dependencies
 */

/**
 * GeChiUI dependencies
 */


/**
 * Internal dependencies
 */


/** @typedef {typeof ALT | CTRL | COMMAND | SHIFT } GCModifierPart */

/** @typedef {'primary' | 'primaryShift' | 'primaryAlt' | 'secondary' | 'access' | 'ctrl' | 'alt' | 'ctrlShift' | 'shift' | 'shiftAlt' | 'undefined'} GCKeycodeModifier */

/**
 * An object of handler functions for each of the possible modifier
 * combinations. A handler will return a value for a given key.
 *
 * @template T
 *
 * @typedef {Record<GCKeycodeModifier, T>} GCModifierHandler
 */

/**
 * @template T
 *
 * @typedef {(character: string, isApple?: () => boolean) => T} GCKeyHandler
 */

/** @typedef {(event: KeyboardEvent, character: string, isApple?: () => boolean) => boolean} GCEventKeyHandler */

/**
 * Keycode for BACKSPACE key.
 */

const BACKSPACE = 8;
/**
 * Keycode for TAB key.
 */

const TAB = 9;
/**
 * Keycode for ENTER key.
 */

const ENTER = 13;
/**
 * Keycode for ESCAPE key.
 */

const ESCAPE = 27;
/**
 * Keycode for SPACE key.
 */

const SPACE = 32;
/**
 * Keycode for PAGEUP key.
 */

const PAGEUP = 33;
/**
 * Keycode for PAGEDOWN key.
 */

const PAGEDOWN = 34;
/**
 * Keycode for END key.
 */

const END = 35;
/**
 * Keycode for HOME key.
 */

const HOME = 36;
/**
 * Keycode for LEFT key.
 */

const LEFT = 37;
/**
 * Keycode for UP key.
 */

const UP = 38;
/**
 * Keycode for RIGHT key.
 */

const RIGHT = 39;
/**
 * Keycode for DOWN key.
 */

const DOWN = 40;
/**
 * Keycode for DELETE key.
 */

const DELETE = 46;
/**
 * Keycode for F10 key.
 */

const F10 = 121;
/**
 * Keycode for ALT key.
 */

const ALT = 'alt';
/**
 * Keycode for CTRL key.
 */

const CTRL = 'ctrl';
/**
 * Keycode for COMMAND/META key.
 */

const COMMAND = 'meta';
/**
 * Keycode for SHIFT key.
 */

const SHIFT = 'shift';
/**
 * Keycode for ZERO key.
 */

const ZERO = 48;
/**
 * Object that contains functions that return the available modifier
 * depending on platform.
 *
 * @type {GCModifierHandler< ( isApple: () => boolean ) => GCModifierPart[]>}
 */

const modifiers = {
  primary: _isApple => _isApple() ? [COMMAND] : [CTRL],
  primaryShift: _isApple => _isApple() ? [SHIFT, COMMAND] : [CTRL, SHIFT],
  primaryAlt: _isApple => _isApple() ? [ALT, COMMAND] : [CTRL, ALT],
  secondary: _isApple => _isApple() ? [SHIFT, ALT, COMMAND] : [CTRL, SHIFT, ALT],
  access: _isApple => _isApple() ? [CTRL, ALT] : [SHIFT, ALT],
  ctrl: () => [CTRL],
  alt: () => [ALT],
  ctrlShift: () => [CTRL, SHIFT],
  shift: () => [SHIFT],
  shiftAlt: () => [SHIFT, ALT],
  undefined: () => []
};
/**
 * An object that contains functions to get raw shortcuts.
 *
 * These are intended for user with the KeyboardShortcuts.
 *
 * @example
 * ```js
 * // Assuming macOS:
 * rawShortcut.primary( 'm' )
 * // "meta+m""
 * ```
 *
 * @type {GCModifierHandler<GCKeyHandler<string>>} Keyed map of functions to raw
 *                                                 shortcuts.
 */

const rawShortcut = Object(lodash__WEBPACK_IMPORTED_MODULE_0__["mapValues"])(modifiers, modifier => {
  return (
    /** @type {GCKeyHandler<string>} */
    function (character) {
      let _isApple = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : _platform__WEBPACK_IMPORTED_MODULE_2__["isAppleOS"];

      return [...modifier(_isApple), character.toLowerCase()].join('+');
    }
  );
});
/**
 * Return an array of the parts of a keyboard shortcut chord for display.
 *
 * @example
 * ```js
 * // Assuming macOS:
 * displayShortcutList.primary( 'm' );
 * // [ "⌘", "M" ]
 * ```
 *
 * @type {GCModifierHandler<GCKeyHandler<string[]>>} Keyed map of functions to
 *                                                   shortcut sequences.
 */

const displayShortcutList = Object(lodash__WEBPACK_IMPORTED_MODULE_0__["mapValues"])(modifiers, modifier => {
  return (
    /** @type {GCKeyHandler<string[]>} */
    function (character) {
      let _isApple = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : _platform__WEBPACK_IMPORTED_MODULE_2__["isAppleOS"];

      const isApple = _isApple();

      const replacementKeyMap = {
        [ALT]: isApple ? '⌥' : 'Alt',
        [CTRL]: isApple ? '⌃' : 'Ctrl',
        // Make sure ⌃ is the U+2303 UP ARROWHEAD unicode character and not the caret character.
        [COMMAND]: '⌘',
        [SHIFT]: isApple ? '⇧' : 'Shift'
      };
      const modifierKeys = modifier(_isApple).reduce((accumulator, key) => {
        const replacementKey = Object(lodash__WEBPACK_IMPORTED_MODULE_0__["get"])(replacementKeyMap, key, key); // If on the Mac, adhere to platform convention and don't show plus between keys.

        if (isApple) {
          return [...accumulator, replacementKey];
        }

        return [...accumulator, replacementKey, '+'];
      },
      /** @type {string[]} */
      []);
      const capitalizedCharacter = Object(lodash__WEBPACK_IMPORTED_MODULE_0__["capitalize"])(character);
      return [...modifierKeys, capitalizedCharacter];
    }
  );
});
/**
 * An object that contains functions to display shortcuts.
 *
 * @example
 * ```js
 * // Assuming macOS:
 * displayShortcut.primary( 'm' );
 * // "⌘M"
 * ```
 *
 * @type {GCModifierHandler<GCKeyHandler<string>>} Keyed map of functions to
 *                                                 display shortcuts.
 */

const displayShortcut = Object(lodash__WEBPACK_IMPORTED_MODULE_0__["mapValues"])(displayShortcutList, shortcutList => {
  return (
    /** @type {GCKeyHandler<string>} */
    function (character) {
      let _isApple = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : _platform__WEBPACK_IMPORTED_MODULE_2__["isAppleOS"];

      return shortcutList(character, _isApple).join('');
    }
  );
});
/**
 * An object that contains functions to return an aria label for a keyboard
 * shortcut.
 *
 * @example
 * ```js
 * // Assuming macOS:
 * shortcutAriaLabel.primary( '.' );
 * // "Command + Period"
 * ```
 *
 * @type {GCModifierHandler<GCKeyHandler<string>>} Keyed map of functions to
 *                                                 shortcut ARIA labels.
 */

const shortcutAriaLabel = Object(lodash__WEBPACK_IMPORTED_MODULE_0__["mapValues"])(modifiers, modifier => {
  return (
    /** @type {GCKeyHandler<string>} */
    function (character) {
      let _isApple = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : _platform__WEBPACK_IMPORTED_MODULE_2__["isAppleOS"];

      const isApple = _isApple();

      const replacementKeyMap = {
        [SHIFT]: 'Shift',
        [COMMAND]: isApple ? 'Command' : 'Control',
        [CTRL]: 'Control',
        [ALT]: isApple ? 'Option' : 'Alt',

        /* translators: comma as in the character ',' */
        ',': Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('逗号'),

        /* translators: period as in the character '.' */
        '.': Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('句号'),

        /* translators: backtick as in the character '`' */
        '`': Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('反引号')
      };
      return [...modifier(_isApple), character].map(key => Object(lodash__WEBPACK_IMPORTED_MODULE_0__["capitalize"])(Object(lodash__WEBPACK_IMPORTED_MODULE_0__["get"])(replacementKeyMap, key, key))).join(isApple ? ' ' : ' + ');
    }
  );
});
/**
 * From a given KeyboardEvent, returns an array of active modifier constants for
 * the event.
 *
 * @param {KeyboardEvent} event Keyboard event.
 *
 * @return {Array<GCModifierPart>} Active modifier constants.
 */

function getEventModifiers(event) {
  return (
    /** @type {GCModifierPart[]} */
    [ALT, CTRL, COMMAND, SHIFT].filter(key => event[
    /** @type {'altKey' | 'ctrlKey' | 'metaKey' | 'shiftKey'} */
    `${key}Key`])
  );
}
/**
 * An object that contains functions to check if a keyboard event matches a
 * predefined shortcut combination.
 *
 * @example
 * ```js
 * // Assuming an event for ⌘M key press:
 * isKeyboardEvent.primary( event, 'm' );
 * // true
 * ```
 *
 * @type {GCModifierHandler<GCEventKeyHandler>} Keyed map of functions
 *                                                       to match events.
 */


const isKeyboardEvent = Object(lodash__WEBPACK_IMPORTED_MODULE_0__["mapValues"])(modifiers, getModifiers => {
  return (
    /** @type {GCEventKeyHandler} */
    function (event, character) {
      let _isApple = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : _platform__WEBPACK_IMPORTED_MODULE_2__["isAppleOS"];

      const mods = getModifiers(_isApple);
      const eventMods = getEventModifiers(event);

      if (Object(lodash__WEBPACK_IMPORTED_MODULE_0__["xor"])(mods, eventMods).length) {
        return false;
      }

      let key = event.key.toLowerCase();

      if (!character) {
        return Object(lodash__WEBPACK_IMPORTED_MODULE_0__["includes"])(mods, key);
      }

      if (event.altKey && character.length === 1) {
        key = String.fromCharCode(event.keyCode).toLowerCase();
      } // For backwards compatibility.


      if (character === 'del') {
        character = 'delete';
      }

      return key === character.toLowerCase();
    }
  );
});


/***/ }),

/***/ "./node_modules/@gechiui/keycodes/build-module/platform.js":
/*!*****************************************************************!*\
  !*** ./node_modules/@gechiui/keycodes/build-module/platform.js ***!
  \*****************************************************************/
/*! exports provided: isAppleOS */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "isAppleOS", function() { return isAppleOS; });
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_0__);
/**
 * External dependencies
 */

/**
 * Return true if platform is MacOS.
 *
 * @param {Window?} _window window object by default; used for DI testing.
 *
 * @return {boolean} True if MacOS; false otherwise.
 */

function isAppleOS() {
  let _window = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;

  if (!_window) {
    if (typeof window === 'undefined') {
      return false;
    }

    _window = window;
  }

  const {
    platform
  } = _window.navigator;
  return platform.indexOf('Mac') !== -1 || Object(lodash__WEBPACK_IMPORTED_MODULE_0__["includes"])(['iPad', 'iPhone'], platform);
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

/***/ "lodash":
/*!*************************!*\
  !*** external "lodash" ***!
  \*************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["lodash"]; }());

/***/ })

/******/ });
//# sourceMappingURL=keycodes.js.map