this["gc"] = this["gc"] || {}; this["gc"]["primitives"] =
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
/******/ 	return __webpack_require__(__webpack_require__.s = "./node_modules/@gechiui/primitives/build-module/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/@gechiui/primitives/build-module/block-quotation/index.js":
/*!********************************************************************************!*\
  !*** ./node_modules/@gechiui/primitives/build-module/block-quotation/index.js ***!
  \********************************************************************************/
/*! exports provided: BlockQuotation */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "BlockQuotation", function() { return BlockQuotation; });
const BlockQuotation = 'blockquote';


/***/ }),

/***/ "./node_modules/@gechiui/primitives/build-module/horizontal-rule/index.js":
/*!********************************************************************************!*\
  !*** ./node_modules/@gechiui/primitives/build-module/horizontal-rule/index.js ***!
  \********************************************************************************/
/*! exports provided: HorizontalRule */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "HorizontalRule", function() { return HorizontalRule; });
const HorizontalRule = 'hr';


/***/ }),

/***/ "./node_modules/@gechiui/primitives/build-module/index.js":
/*!****************************************************************!*\
  !*** ./node_modules/@gechiui/primitives/build-module/index.js ***!
  \****************************************************************/
/*! exports provided: Circle, G, Path, Polygon, Rect, Defs, RadialGradient, LinearGradient, Stop, SVG, HorizontalRule, BlockQuotation, View */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _svg__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./svg */ "./node_modules/@gechiui/primitives/build-module/svg/index.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "Circle", function() { return _svg__WEBPACK_IMPORTED_MODULE_0__["Circle"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "G", function() { return _svg__WEBPACK_IMPORTED_MODULE_0__["G"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "Path", function() { return _svg__WEBPACK_IMPORTED_MODULE_0__["Path"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "Polygon", function() { return _svg__WEBPACK_IMPORTED_MODULE_0__["Polygon"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "Rect", function() { return _svg__WEBPACK_IMPORTED_MODULE_0__["Rect"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "Defs", function() { return _svg__WEBPACK_IMPORTED_MODULE_0__["Defs"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "RadialGradient", function() { return _svg__WEBPACK_IMPORTED_MODULE_0__["RadialGradient"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "LinearGradient", function() { return _svg__WEBPACK_IMPORTED_MODULE_0__["LinearGradient"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "Stop", function() { return _svg__WEBPACK_IMPORTED_MODULE_0__["Stop"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "SVG", function() { return _svg__WEBPACK_IMPORTED_MODULE_0__["SVG"]; });

/* harmony import */ var _horizontal_rule__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./horizontal-rule */ "./node_modules/@gechiui/primitives/build-module/horizontal-rule/index.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "HorizontalRule", function() { return _horizontal_rule__WEBPACK_IMPORTED_MODULE_1__["HorizontalRule"]; });

/* harmony import */ var _block_quotation__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./block-quotation */ "./node_modules/@gechiui/primitives/build-module/block-quotation/index.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "BlockQuotation", function() { return _block_quotation__WEBPACK_IMPORTED_MODULE_2__["BlockQuotation"]; });

/* harmony import */ var _view__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./view */ "./node_modules/@gechiui/primitives/build-module/view/index.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "View", function() { return _view__WEBPACK_IMPORTED_MODULE_3__["View"]; });







/***/ }),

/***/ "./node_modules/@gechiui/primitives/build-module/svg/index.js":
/*!********************************************************************!*\
  !*** ./node_modules/@gechiui/primitives/build-module/svg/index.js ***!
  \********************************************************************/
/*! exports provided: Circle, G, Path, Polygon, Rect, Defs, RadialGradient, LinearGradient, Stop, SVG */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "Circle", function() { return Circle; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "G", function() { return G; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "Path", function() { return Path; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "Polygon", function() { return Polygon; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "Rect", function() { return Rect; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "Defs", function() { return Defs; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "RadialGradient", function() { return RadialGradient; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "LinearGradient", function() { return LinearGradient; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "Stop", function() { return Stop; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "SVG", function() { return SVG; });
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _gechiui_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @gechiui/element */ "@gechiui/element");
/* harmony import */ var _gechiui_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__);
/**
 * External dependencies
 */

/**
 * GeChiUI dependencies
 */


/** @typedef {{isPressed?: boolean} & import('react').ComponentPropsWithoutRef<'svg'>} SVGProps */

/**
 * @param {import('react').ComponentPropsWithoutRef<'circle'>} props
 *
 * @return {JSX.Element} Circle component
 */

const Circle = props => Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])('circle', props);
/**
 * @param {import('react').ComponentPropsWithoutRef<'g'>} props
 *
 * @return {JSX.Element} G component
 */

const G = props => Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])('g', props);
/**
 * @param {import('react').ComponentPropsWithoutRef<'path'>} props
 *
 * @return {JSX.Element} Path component
 */

const Path = props => Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])('path', props);
/**
 * @param {import('react').ComponentPropsWithoutRef<'polygon'>} props
 *
 * @return {JSX.Element} Polygon component
 */

const Polygon = props => Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])('polygon', props);
/**
 * @param {import('react').ComponentPropsWithoutRef<'rect'>} props
 *
 * @return {JSX.Element} Rect component
 */

const Rect = props => Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])('rect', props);
/**
 * @param {import('react').ComponentPropsWithoutRef<'defs'>} props
 *
 * @return {JSX.Element} Defs component
 */

const Defs = props => Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])('defs', props);
/**
 * @param {import('react').ComponentPropsWithoutRef<'radialGradient'>} props
 *
 * @return {JSX.Element} RadialGradient component
 */

const RadialGradient = props => Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])('radialGradient', props);
/**
 * @param {import('react').ComponentPropsWithoutRef<'linearGradient'>} props
 *
 * @return {JSX.Element} LinearGradient component
 */

const LinearGradient = props => Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])('linearGradient', props);
/**
 * @param {import('react').ComponentPropsWithoutRef<'stop'>} props
 *
 * @return {JSX.Element} Stop component
 */

const Stop = props => Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])('stop', props);
/**
 *
 * @param {SVGProps} props isPressed indicates whether the SVG should appear as pressed.
 *                         Other props will be passed through to svg component.
 *
 * @return {JSX.Element} Stop component
 */

const SVG = _ref => {
  let {
    className,
    isPressed,
    ...props
  } = _ref;
  const appliedProps = { ...props,
    className: classnames__WEBPACK_IMPORTED_MODULE_0___default()(className, {
      'is-pressed': isPressed
    }) || undefined,
    role: 'img',
    'aria-hidden': true,
    focusable: false
  }; // Disable reason: We need to have a way to render HTML tag for web.
  // eslint-disable-next-line react/forbid-elements

  return Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])("svg", appliedProps);
};


/***/ }),

/***/ "./node_modules/@gechiui/primitives/build-module/view/index.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@gechiui/primitives/build-module/view/index.js ***!
  \*********************************************************************/
/*! exports provided: View */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "View", function() { return View; });
const View = 'div';


/***/ }),

/***/ "./node_modules/classnames/index.js":
/*!******************************************!*\
  !*** ./node_modules/classnames/index.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
  Copyright (c) 2018 Jed Watson.
  Licensed under the MIT License (MIT), see
  http://jedwatson.github.io/classnames
*/
/* global define */

(function () {
	'use strict';

	var hasOwn = {}.hasOwnProperty;

	function classNames() {
		var classes = [];

		for (var i = 0; i < arguments.length; i++) {
			var arg = arguments[i];
			if (!arg) continue;

			var argType = typeof arg;

			if (argType === 'string' || argType === 'number') {
				classes.push(arg);
			} else if (Array.isArray(arg)) {
				if (arg.length) {
					var inner = classNames.apply(null, arg);
					if (inner) {
						classes.push(inner);
					}
				}
			} else if (argType === 'object') {
				if (arg.toString === Object.prototype.toString) {
					for (var key in arg) {
						if (hasOwn.call(arg, key) && arg[key]) {
							classes.push(key);
						}
					}
				} else {
					classes.push(arg.toString());
				}
			}
		}

		return classes.join(' ');
	}

	if ( true && module.exports) {
		classNames.default = classNames;
		module.exports = classNames;
	} else if (true) {
		// register as 'classnames', consistent with npm package name
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_RESULT__ = (function () {
			return classNames;
		}).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else {}
}());


/***/ }),

/***/ "@gechiui/element":
/*!*********************************!*\
  !*** external ["gc","element"] ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["gc"]["element"]; }());

/***/ })

/******/ });
//# sourceMappingURL=primitives.js.map