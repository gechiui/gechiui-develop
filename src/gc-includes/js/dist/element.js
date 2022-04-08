this["gc"] = this["gc"] || {}; this["gc"]["element"] =
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
/******/ 	return __webpack_require__(__webpack_require__.s = "./node_modules/@gechiui/element/build-module/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/@gechiui/element/build-module/create-interpolate-element.js":
/*!**********************************************************************************!*\
  !*** ./node_modules/@gechiui/element/build-module/create-interpolate-element.js ***!
  \**********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./react */ "./node_modules/@gechiui/element/build-module/react.js");
/**
 * Internal dependencies
 */

/** @typedef {import('./react').GCElement} GCElement */

let indoc, offset, output, stack;
/**
 * Matches tags in the localized string
 *
 * This is used for extracting the tag pattern groups for parsing the localized
 * string and along with the map converting it to a react element.
 *
 * There are four references extracted using this tokenizer:
 *
 * match: Full match of the tag (i.e. <strong>, </strong>, <br/>)
 * isClosing: The closing slash, it it exists.
 * name: The name portion of the tag (strong, br) (if )
 * isSelfClosed: The slash on a self closing tag, if it exists.
 *
 * @type {RegExp}
 */

const tokenizer = /<(\/)?(\w+)\s*(\/)?>/g;
/**
 * The stack frame tracking parse progress.
 *
 * @typedef Frame
 *
 * @property {GCElement}   element            A parent element which may still have
 * @property {number}      tokenStart         Offset at which parent element first
 *                                            appears.
 * @property {number}      tokenLength        Length of string marking start of parent
 *                                            element.
 * @property {number}      [prevOffset]       Running offset at which parsing should
 *                                            continue.
 * @property {number}      [leadingTextStart] Offset at which last closing element
 *                                            finished, used for finding text between
 *                                            elements.
 * @property {GCElement[]} children           Children.
 */

/**
 * Tracks recursive-descent parse state.
 *
 * This is a Stack frame holding parent elements until all children have been
 * parsed.
 *
 * @private
 * @param {GCElement} element            A parent element which may still have
 *                                       nested children not yet parsed.
 * @param {number}    tokenStart         Offset at which parent element first
 *                                       appears.
 * @param {number}    tokenLength        Length of string marking start of parent
 *                                       element.
 * @param {number}    [prevOffset]       Running offset at which parsing should
 *                                       continue.
 * @param {number}    [leadingTextStart] Offset at which last closing element
 *                                       finished, used for finding text between
 *                                       elements.
 *
 * @return {Frame} The stack frame tracking parse progress.
 */

function createFrame(element, tokenStart, tokenLength, prevOffset, leadingTextStart) {
  return {
    element,
    tokenStart,
    tokenLength,
    prevOffset,
    leadingTextStart,
    children: []
  };
}
/**
 * This function creates an interpolated element from a passed in string with
 * specific tags matching how the string should be converted to an element via
 * the conversion map value.
 *
 * @example
 * For example, for the given string:
 *
 * "This is a <span>string</span> with <a>a link</a> and a self-closing
 * <CustomComponentB/> tag"
 *
 * You would have something like this as the conversionMap value:
 *
 * ```js
 * {
 *     span: <span />,
 *     a: <a href={ 'https://github.com' } />,
 *     CustomComponentB: <CustomComponent />,
 * }
 * ```
 *
 * @param {string} interpolatedString The interpolation string to be parsed.
 * @param {Object} conversionMap      The map used to convert the string to
 *                                    a react element.
 * @throws {TypeError}
 * @return {GCElement}  A gc element.
 */


const createInterpolateElement = (interpolatedString, conversionMap) => {
  indoc = interpolatedString;
  offset = 0;
  output = [];
  stack = [];
  tokenizer.lastIndex = 0;

  if (!isValidConversionMap(conversionMap)) {
    throw new TypeError('The conversionMap provided is not valid. It must be an object with values that are GCElements');
  }

  do {// twiddle our thumbs
  } while (proceed(conversionMap));

  return Object(_react__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_react__WEBPACK_IMPORTED_MODULE_0__["Fragment"], null, ...output);
};
/**
 * Validate conversion map.
 *
 * A map is considered valid if it's an object and every value in the object
 * is a GCElement
 *
 * @private
 *
 * @param {Object} conversionMap The map being validated.
 *
 * @return {boolean}  True means the map is valid.
 */


const isValidConversionMap = conversionMap => {
  const isObject = typeof conversionMap === 'object';
  const values = isObject && Object.values(conversionMap);
  return isObject && values.length && values.every(element => Object(_react__WEBPACK_IMPORTED_MODULE_0__["isValidElement"])(element));
};
/**
 * This is the iterator over the matches in the string.
 *
 * @private
 *
 * @param {Object} conversionMap The conversion map for the string.
 *
 * @return {boolean} true for continuing to iterate, false for finished.
 */


function proceed(conversionMap) {
  const next = nextToken();
  const [tokenType, name, startOffset, tokenLength] = next;
  const stackDepth = stack.length;
  const leadingTextStart = startOffset > offset ? offset : null;

  if (!conversionMap[name]) {
    addText();
    return false;
  }

  switch (tokenType) {
    case 'no-more-tokens':
      if (stackDepth !== 0) {
        const {
          leadingTextStart: stackLeadingText,
          tokenStart
        } = stack.pop();
        output.push(indoc.substr(stackLeadingText, tokenStart));
      }

      addText();
      return false;

    case 'self-closed':
      if (0 === stackDepth) {
        if (null !== leadingTextStart) {
          output.push(indoc.substr(leadingTextStart, startOffset - leadingTextStart));
        }

        output.push(conversionMap[name]);
        offset = startOffset + tokenLength;
        return true;
      } // otherwise we found an inner element


      addChild(createFrame(conversionMap[name], startOffset, tokenLength));
      offset = startOffset + tokenLength;
      return true;

    case 'opener':
      stack.push(createFrame(conversionMap[name], startOffset, tokenLength, startOffset + tokenLength, leadingTextStart));
      offset = startOffset + tokenLength;
      return true;

    case 'closer':
      // if we're not nesting then this is easy - close the block
      if (1 === stackDepth) {
        closeOuterElement(startOffset);
        offset = startOffset + tokenLength;
        return true;
      } // otherwise we're nested and we have to close out the current
      // block and add it as a innerBlock to the parent


      const stackTop = stack.pop();
      const text = indoc.substr(stackTop.prevOffset, startOffset - stackTop.prevOffset);
      stackTop.children.push(text);
      stackTop.prevOffset = startOffset + tokenLength;
      const frame = createFrame(stackTop.element, stackTop.tokenStart, stackTop.tokenLength, startOffset + tokenLength);
      frame.children = stackTop.children;
      addChild(frame);
      offset = startOffset + tokenLength;
      return true;

    default:
      addText();
      return false;
  }
}
/**
 * Grabs the next token match in the string and returns it's details.
 *
 * @private
 *
 * @return {Array}  An array of details for the token matched.
 */


function nextToken() {
  const matches = tokenizer.exec(indoc); // we have no more tokens

  if (null === matches) {
    return ['no-more-tokens'];
  }

  const startedAt = matches.index;
  const [match, isClosing, name, isSelfClosed] = matches;
  const length = match.length;

  if (isSelfClosed) {
    return ['self-closed', name, startedAt, length];
  }

  if (isClosing) {
    return ['closer', name, startedAt, length];
  }

  return ['opener', name, startedAt, length];
}
/**
 * Pushes text extracted from the indoc string to the output stack given the
 * current rawLength value and offset (if rawLength is provided ) or the
 * indoc.length and offset.
 *
 * @private
 */


function addText() {
  const length = indoc.length - offset;

  if (0 === length) {
    return;
  }

  output.push(indoc.substr(offset, length));
}
/**
 * Pushes a child element to the associated parent element's children for the
 * parent currently active in the stack.
 *
 * @private
 *
 * @param {Frame} frame The Frame containing the child element and it's
 *                      token information.
 */


function addChild(frame) {
  const {
    element,
    tokenStart,
    tokenLength,
    prevOffset,
    children
  } = frame;
  const parent = stack[stack.length - 1];
  const text = indoc.substr(parent.prevOffset, tokenStart - parent.prevOffset);

  if (text) {
    parent.children.push(text);
  }

  parent.children.push(Object(_react__WEBPACK_IMPORTED_MODULE_0__["cloneElement"])(element, null, ...children));
  parent.prevOffset = prevOffset ? prevOffset : tokenStart + tokenLength;
}
/**
 * This is called for closing tags. It creates the element currently active in
 * the stack.
 *
 * @private
 *
 * @param {number} endOffset Offset at which the closing tag for the element
 *                           begins in the string. If this is greater than the
 *                           prevOffset attached to the element, then this
 *                           helps capture any remaining nested text nodes in
 *                           the element.
 */


function closeOuterElement(endOffset) {
  const {
    element,
    leadingTextStart,
    prevOffset,
    tokenStart,
    children
  } = stack.pop();
  const text = endOffset ? indoc.substr(prevOffset, endOffset - prevOffset) : indoc.substr(prevOffset);

  if (text) {
    children.push(text);
  }

  if (null !== leadingTextStart) {
    output.push(indoc.substr(leadingTextStart, tokenStart - leadingTextStart));
  }

  output.push(Object(_react__WEBPACK_IMPORTED_MODULE_0__["cloneElement"])(element, null, ...children));
}

/* harmony default export */ __webpack_exports__["default"] = (createInterpolateElement);


/***/ }),

/***/ "./node_modules/@gechiui/element/build-module/index.js":
/*!*************************************************************!*\
  !*** ./node_modules/@gechiui/element/build-module/index.js ***!
  \*************************************************************/
/*! exports provided: createInterpolateElement, Children, cloneElement, Component, createContext, createElement, createRef, forwardRef, Fragment, isValidElement, memo, StrictMode, useCallback, useContext, useDebugValue, useEffect, useImperativeHandle, useLayoutEffect, useMemo, useReducer, useRef, useState, lazy, Suspense, concatChildren, switchChildrenNodeName, createPortal, findDOMNode, render, unmountComponentAtNode, isEmptyElement, Platform, renderToString, RawHTML */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _create_interpolate_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./create-interpolate-element */ "./node_modules/@gechiui/element/build-module/create-interpolate-element.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "createInterpolateElement", function() { return _create_interpolate_element__WEBPACK_IMPORTED_MODULE_0__["default"]; });

/* harmony import */ var _react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./react */ "./node_modules/@gechiui/element/build-module/react.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "Children", function() { return _react__WEBPACK_IMPORTED_MODULE_1__["Children"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "cloneElement", function() { return _react__WEBPACK_IMPORTED_MODULE_1__["cloneElement"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "Component", function() { return _react__WEBPACK_IMPORTED_MODULE_1__["Component"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "createContext", function() { return _react__WEBPACK_IMPORTED_MODULE_1__["createContext"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "createElement", function() { return _react__WEBPACK_IMPORTED_MODULE_1__["createElement"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "createRef", function() { return _react__WEBPACK_IMPORTED_MODULE_1__["createRef"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "forwardRef", function() { return _react__WEBPACK_IMPORTED_MODULE_1__["forwardRef"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "Fragment", function() { return _react__WEBPACK_IMPORTED_MODULE_1__["Fragment"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "isValidElement", function() { return _react__WEBPACK_IMPORTED_MODULE_1__["isValidElement"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "memo", function() { return _react__WEBPACK_IMPORTED_MODULE_1__["memo"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "StrictMode", function() { return _react__WEBPACK_IMPORTED_MODULE_1__["StrictMode"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "useCallback", function() { return _react__WEBPACK_IMPORTED_MODULE_1__["useCallback"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "useContext", function() { return _react__WEBPACK_IMPORTED_MODULE_1__["useContext"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "useDebugValue", function() { return _react__WEBPACK_IMPORTED_MODULE_1__["useDebugValue"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "useEffect", function() { return _react__WEBPACK_IMPORTED_MODULE_1__["useEffect"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "useImperativeHandle", function() { return _react__WEBPACK_IMPORTED_MODULE_1__["useImperativeHandle"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "useLayoutEffect", function() { return _react__WEBPACK_IMPORTED_MODULE_1__["useLayoutEffect"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "useMemo", function() { return _react__WEBPACK_IMPORTED_MODULE_1__["useMemo"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "useReducer", function() { return _react__WEBPACK_IMPORTED_MODULE_1__["useReducer"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "useRef", function() { return _react__WEBPACK_IMPORTED_MODULE_1__["useRef"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "useState", function() { return _react__WEBPACK_IMPORTED_MODULE_1__["useState"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "lazy", function() { return _react__WEBPACK_IMPORTED_MODULE_1__["lazy"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "Suspense", function() { return _react__WEBPACK_IMPORTED_MODULE_1__["Suspense"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "concatChildren", function() { return _react__WEBPACK_IMPORTED_MODULE_1__["concatChildren"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "switchChildrenNodeName", function() { return _react__WEBPACK_IMPORTED_MODULE_1__["switchChildrenNodeName"]; });

/* harmony import */ var _react_platform__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./react-platform */ "./node_modules/@gechiui/element/build-module/react-platform.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "createPortal", function() { return _react_platform__WEBPACK_IMPORTED_MODULE_2__["createPortal"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "findDOMNode", function() { return _react_platform__WEBPACK_IMPORTED_MODULE_2__["findDOMNode"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _react_platform__WEBPACK_IMPORTED_MODULE_2__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "unmountComponentAtNode", function() { return _react_platform__WEBPACK_IMPORTED_MODULE_2__["unmountComponentAtNode"]; });

/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./utils */ "./node_modules/@gechiui/element/build-module/utils.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "isEmptyElement", function() { return _utils__WEBPACK_IMPORTED_MODULE_3__["isEmptyElement"]; });

/* harmony import */ var _platform__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./platform */ "./node_modules/@gechiui/element/build-module/platform.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "Platform", function() { return _platform__WEBPACK_IMPORTED_MODULE_4__["default"]; });

/* harmony import */ var _serialize__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./serialize */ "./node_modules/@gechiui/element/build-module/serialize.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "renderToString", function() { return _serialize__WEBPACK_IMPORTED_MODULE_5__["default"]; });

/* harmony import */ var _raw_html__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./raw-html */ "./node_modules/@gechiui/element/build-module/raw-html.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "RawHTML", function() { return _raw_html__WEBPACK_IMPORTED_MODULE_6__["default"]; });










/***/ }),

/***/ "./node_modules/@gechiui/element/build-module/platform.js":
/*!****************************************************************!*\
  !*** ./node_modules/@gechiui/element/build-module/platform.js ***!
  \****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/**
 * Parts of this source were derived and modified from react-native-web,
 * released under the MIT license.
 *
 * Copyright (c) 2016-present, Nicolas Gallagher.
 * Copyright (c) 2015-present, Facebook, Inc.
 *
 */
const Platform = {
  OS: 'web',
  select: spec => 'web' in spec ? spec.web : spec.default,
  isWeb: true
};
/**
 * Component used to detect the current Platform being used.
 * Use Platform.OS === 'web' to detect if running on web enviroment.
 *
 * This is the same concept as the React Native implementation.
 *
 * @see https://facebook.github.io/react-native/docs/platform-specific-code#platform-module
 *
 * Here is an example of how to use the select method:
 * @example
 * ```js
 * import { Platform } from '@gechiui/element';
 *
 * const placeholderLabel = Platform.select( {
 *   native: __( '添加媒体' ),
 *   web: __( '拖动图片，上传新图片或从您的媒体库中选择文件。' ),
 * } );
 * ```
 */

/* harmony default export */ __webpack_exports__["default"] = (Platform);


/***/ }),

/***/ "./node_modules/@gechiui/element/build-module/raw-html.js":
/*!****************************************************************!*\
  !*** ./node_modules/@gechiui/element/build-module/raw-html.js ***!
  \****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return RawHTML; });
/* harmony import */ var _react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./react */ "./node_modules/@gechiui/element/build-module/react.js");
/**
 * Internal dependencies
 */
 // Disable reason: JSDoc linter doesn't seem to parse the union (`&`) correctly.

/** @typedef {{children: string} & import('react').ComponentPropsWithoutRef<'div'>} RawHTMLProps */

/**
 * Component used as equivalent of Fragment with unescaped HTML, in cases where
 * it is desirable to render dangerous HTML without needing a wrapper element.
 * To preserve additional props, a `div` wrapper _will_ be created if any props
 * aside from `children` are passed.
 *
 * @param {RawHTMLProps} props Children should be a string of HTML or an array
 *                             of strings. Other props will be passed through
 *                             to the div wrapper.
 *
 * @return {JSX.Element} Dangerously-rendering component.
 */

function RawHTML(_ref) {
  let {
    children,
    ...props
  } = _ref;
  let rawHtml = ''; // Cast children as an array, and concatenate each element if it is a string.

  _react__WEBPACK_IMPORTED_MODULE_0__["Children"].toArray(children).forEach(child => {
    if (typeof child === 'string' && child.trim() !== '') {
      rawHtml += child;
    }
  }); // The `div` wrapper will be stripped by the `renderElement` serializer in
  // `./serialize.js` unless there are non-children props present.

  return Object(_react__WEBPACK_IMPORTED_MODULE_0__["createElement"])('div', {
    dangerouslySetInnerHTML: {
      __html: rawHtml
    },
    ...props
  });
}


/***/ }),

/***/ "./node_modules/@gechiui/element/build-module/react-platform.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@gechiui/element/build-module/react-platform.js ***!
  \**********************************************************************/
/*! exports provided: createPortal, findDOMNode, render, unmountComponentAtNode */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react_dom__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react-dom */ "react-dom");
/* harmony import */ var react_dom__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react_dom__WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "createPortal", function() { return react_dom__WEBPACK_IMPORTED_MODULE_0__["createPortal"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "findDOMNode", function() { return react_dom__WEBPACK_IMPORTED_MODULE_0__["findDOMNode"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return react_dom__WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "unmountComponentAtNode", function() { return react_dom__WEBPACK_IMPORTED_MODULE_0__["unmountComponentAtNode"]; });

/**
 * External dependencies
 */

/**
 * Creates a portal into which a component can be rendered.
 *
 * @see https://github.com/facebook/react/issues/10309#issuecomment-318433235
 *
 * @param {import('./react').GCElement} child     Any renderable child, such as an element,
 *                                                string, or fragment.
 * @param {HTMLElement}                 container DOM node into which element should be rendered.
 */


/**
 * Finds the dom node of a React component.
 *
 * @param {import('./react').GCComponent} component Component's instance.
 */


/**
 * Renders a given element into the target DOM node.
 *
 * @param {import('./react').GCElement} element Element to render.
 * @param {HTMLElement}                 target  DOM node into which element should be rendered.
 */


/**
 * Removes any mounted element from the target DOM node.
 *
 * @param {Element} target DOM node in which element is to be removed
 */




/***/ }),

/***/ "./node_modules/@gechiui/element/build-module/react.js":
/*!*************************************************************!*\
  !*** ./node_modules/@gechiui/element/build-module/react.js ***!
  \*************************************************************/
/*! exports provided: Children, cloneElement, Component, createContext, createElement, createRef, forwardRef, Fragment, isValidElement, memo, StrictMode, useCallback, useContext, useDebugValue, useEffect, useImperativeHandle, useLayoutEffect, useMemo, useReducer, useRef, useState, lazy, Suspense, concatChildren, switchChildrenNodeName */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "concatChildren", function() { return concatChildren; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "switchChildrenNodeName", function() { return switchChildrenNodeName; });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "Children", function() { return react__WEBPACK_IMPORTED_MODULE_0__["Children"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "cloneElement", function() { return react__WEBPACK_IMPORTED_MODULE_0__["cloneElement"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "Component", function() { return react__WEBPACK_IMPORTED_MODULE_0__["Component"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "createContext", function() { return react__WEBPACK_IMPORTED_MODULE_0__["createContext"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "createElement", function() { return react__WEBPACK_IMPORTED_MODULE_0__["createElement"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "createRef", function() { return react__WEBPACK_IMPORTED_MODULE_0__["createRef"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "forwardRef", function() { return react__WEBPACK_IMPORTED_MODULE_0__["forwardRef"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "Fragment", function() { return react__WEBPACK_IMPORTED_MODULE_0__["Fragment"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "isValidElement", function() { return react__WEBPACK_IMPORTED_MODULE_0__["isValidElement"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "memo", function() { return react__WEBPACK_IMPORTED_MODULE_0__["memo"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "StrictMode", function() { return react__WEBPACK_IMPORTED_MODULE_0__["StrictMode"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "useCallback", function() { return react__WEBPACK_IMPORTED_MODULE_0__["useCallback"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "useContext", function() { return react__WEBPACK_IMPORTED_MODULE_0__["useContext"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "useDebugValue", function() { return react__WEBPACK_IMPORTED_MODULE_0__["useDebugValue"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "useEffect", function() { return react__WEBPACK_IMPORTED_MODULE_0__["useEffect"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "useImperativeHandle", function() { return react__WEBPACK_IMPORTED_MODULE_0__["useImperativeHandle"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "useLayoutEffect", function() { return react__WEBPACK_IMPORTED_MODULE_0__["useLayoutEffect"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "useMemo", function() { return react__WEBPACK_IMPORTED_MODULE_0__["useMemo"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "useReducer", function() { return react__WEBPACK_IMPORTED_MODULE_0__["useReducer"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "useRef", function() { return react__WEBPACK_IMPORTED_MODULE_0__["useRef"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "useState", function() { return react__WEBPACK_IMPORTED_MODULE_0__["useState"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "lazy", function() { return react__WEBPACK_IMPORTED_MODULE_0__["lazy"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "Suspense", function() { return react__WEBPACK_IMPORTED_MODULE_0__["Suspense"]; });

/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_1__);
/**
 * External dependencies
 */
// eslint-disable-next-line no-restricted-imports


/**
 * Object containing a React element.
 *
 * @typedef {import('react').ReactElement} GCElement
 */

/**
 * Object containing a React component.
 *
 * @typedef {import('react').ComponentType} GCComponent
 */

/**
 * Object containing a React synthetic event.
 *
 * @typedef {import('react').SyntheticEvent} GCSyntheticEvent
 */

/**
 * Object that provides utilities for dealing with React children.
 */


/**
 * Creates a copy of an element with extended props.
 *
 * @param {GCElement} element Element
 * @param {?Object}   props   Props to apply to cloned element
 *
 * @return {GCElement} Cloned element.
 */


/**
 * A base class to create GeChiUI Components (Refs, state and lifecycle hooks)
 */


/**
 * Creates a context object containing two components: a provider and consumer.
 *
 * @param {Object} defaultValue A default data stored in the context.
 *
 * @return {Object} Context object.
 */


/**
 * Returns a new element of given type. Type can be either a string tag name or
 * another function which itself returns an element.
 *
 * @param {?(string|Function)} type     Tag name or element creator
 * @param {Object}             props    Element properties, either attribute
 *                                      set to apply to DOM node or values to
 *                                      pass through to element creator
 * @param {...GCElement}       children Descendant elements
 *
 * @return {GCElement} Element.
 */


/**
 * Returns an object tracking a reference to a rendered element via its
 * `current` property as either a DOMElement or Element, dependent upon the
 * type of element rendered with the ref attribute.
 *
 * @return {Object} Ref object.
 */


/**
 * Component enhancer used to enable passing a ref to its wrapped component.
 * Pass a function argument which receives `props` and `ref` as its arguments,
 * returning an element using the forwarded ref. The return value is a new
 * component which forwards its ref.
 *
 * @param {Function} forwarder Function passed `props` and `ref`, expected to
 *                             return an element.
 *
 * @return {GCComponent} Enhanced component.
 */


/**
 * A component which renders its children without any wrapping element.
 */


/**
 * Checks if an object is a valid GCElement.
 *
 * @param {Object} objectToCheck The object to be checked.
 *
 * @return {boolean} true if objectToTest is a valid GCElement and false otherwise.
 */


/**
 * @see https://reactjs.org/docs/react-api.html#reactmemo
 */


/**
 * Component that activates additional checks and warnings for its descendants.
 */


/**
 * @see https://reactjs.org/docs/hooks-reference.html#usecallback
 */


/**
 * @see https://reactjs.org/docs/hooks-reference.html#usecontext
 */


/**
 * @see https://reactjs.org/docs/hooks-reference.html#usedebugvalue
 */


/**
 * @see https://reactjs.org/docs/hooks-reference.html#useeffect
 */


/**
 * @see https://reactjs.org/docs/hooks-reference.html#useimperativehandle
 */


/**
 * @see https://reactjs.org/docs/hooks-reference.html#uselayouteffect
 */


/**
 * @see https://reactjs.org/docs/hooks-reference.html#usememo
 */


/**
 * @see https://reactjs.org/docs/hooks-reference.html#usereducer
 */


/**
 * @see https://reactjs.org/docs/hooks-reference.html#useref
 */


/**
 * @see https://reactjs.org/docs/hooks-reference.html#usestate
 */


/**
 * @see https://reactjs.org/docs/react-api.html#reactlazy
 */


/**
 * @see https://reactjs.org/docs/react-api.html#reactsuspense
 */


/**
 * Concatenate two or more React children objects.
 *
 * @param {...?Object} childrenArguments Array of children arguments (array of arrays/strings/objects) to concatenate.
 *
 * @return {Array} The concatenated value.
 */

function concatChildren() {
  for (var _len = arguments.length, childrenArguments = new Array(_len), _key = 0; _key < _len; _key++) {
    childrenArguments[_key] = arguments[_key];
  }

  return childrenArguments.reduce((accumulator, children, i) => {
    react__WEBPACK_IMPORTED_MODULE_0__["Children"].forEach(children, (child, j) => {
      if (child && 'string' !== typeof child) {
        child = Object(react__WEBPACK_IMPORTED_MODULE_0__["cloneElement"])(child, {
          key: [i, j].join()
        });
      }

      accumulator.push(child);
    });
    return accumulator;
  }, []);
}
/**
 * Switches the nodeName of all the elements in the children object.
 *
 * @param {?Object} children Children object.
 * @param {string}  nodeName Node name.
 *
 * @return {?Object} The updated children object.
 */

function switchChildrenNodeName(children, nodeName) {
  return children && react__WEBPACK_IMPORTED_MODULE_0__["Children"].map(children, (elt, index) => {
    if (Object(lodash__WEBPACK_IMPORTED_MODULE_1__["isString"])(elt)) {
      return Object(react__WEBPACK_IMPORTED_MODULE_0__["createElement"])(nodeName, {
        key: index
      }, elt);
    }

    const {
      children: childrenProp,
      ...props
    } = elt.props;
    return Object(react__WEBPACK_IMPORTED_MODULE_0__["createElement"])(nodeName, {
      key: index,
      ...props
    }, childrenProp);
  });
}


/***/ }),

/***/ "./node_modules/@gechiui/element/build-module/serialize.js":
/*!*****************************************************************!*\
  !*** ./node_modules/@gechiui/element/build-module/serialize.js ***!
  \*****************************************************************/
/*! exports provided: hasPrefix, renderElement, renderNativeComponent, renderComponent, renderAttributes, renderStyle, default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "hasPrefix", function() { return hasPrefix; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "renderElement", function() { return renderElement; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "renderNativeComponent", function() { return renderNativeComponent; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "renderComponent", function() { return renderComponent; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "renderAttributes", function() { return renderAttributes; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "renderStyle", function() { return renderStyle; });
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _gechiui_escape_html__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @gechiui/escape-html */ "@gechiui/escape-html");
/* harmony import */ var _gechiui_escape_html__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_gechiui_escape_html__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _react__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./react */ "./node_modules/@gechiui/element/build-module/react.js");
/* harmony import */ var _raw_html__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./raw-html */ "./node_modules/@gechiui/element/build-module/raw-html.js");
/**
 * Parts of this source were derived and modified from fast-react-render,
 * released under the MIT license.
 *
 * https://github.com/alt-j/fast-react-render
 *
 * Copyright (c) 2016 Andrey Morozov
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
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



/** @typedef {import('./react').GCElement} GCElement */

const {
  Provider,
  Consumer
} = Object(_react__WEBPACK_IMPORTED_MODULE_2__["createContext"])(undefined);
const ForwardRef = Object(_react__WEBPACK_IMPORTED_MODULE_2__["forwardRef"])(() => {
  return null;
});
/**
 * Valid attribute types.
 *
 * @type {Set<string>}
 */

const ATTRIBUTES_TYPES = new Set(['string', 'boolean', 'number']);
/**
 * Element tags which can be self-closing.
 *
 * @type {Set<string>}
 */

const SELF_CLOSING_TAGS = new Set(['area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input', 'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr']);
/**
 * Boolean attributes are attributes whose presence as being assigned is
 * meaningful, even if only empty.
 *
 * See: https://html.spec.whatwg.org/multipage/common-microsyntaxes.html#boolean-attributes
 * Extracted from: https://html.spec.whatwg.org/multipage/indices.html#attributes-3
 *
 * Object.keys( [ ...document.querySelectorAll( '#attributes-1 > tbody > tr' ) ]
 *     .filter( ( tr ) => tr.lastChild.textContent.indexOf( 'Boolean attribute' ) !== -1 )
 *     .reduce( ( result, tr ) => Object.assign( result, {
 *         [ tr.firstChild.textContent.trim() ]: true
 *     } ), {} ) ).sort();
 *
 * @type {Set<string>}
 */

const BOOLEAN_ATTRIBUTES = new Set(['allowfullscreen', 'allowpaymentrequest', 'allowusermedia', 'async', 'autofocus', 'autoplay', 'checked', 'controls', 'default', 'defer', 'disabled', 'download', 'formnovalidate', 'hidden', 'ismap', 'itemscope', 'loop', 'multiple', 'muted', 'nomodule', 'novalidate', 'open', 'playsinline', 'readonly', 'required', 'reversed', 'selected', 'typemustmatch']);
/**
 * Enumerated attributes are attributes which must be of a specific value form.
 * Like boolean attributes, these are meaningful if specified, even if not of a
 * valid enumerated value.
 *
 * See: https://html.spec.whatwg.org/multipage/common-microsyntaxes.html#enumerated-attribute
 * Extracted from: https://html.spec.whatwg.org/multipage/indices.html#attributes-3
 *
 * Object.keys( [ ...document.querySelectorAll( '#attributes-1 > tbody > tr' ) ]
 *     .filter( ( tr ) => /^("(.+?)";?\s*)+/.test( tr.lastChild.textContent.trim() ) )
 *     .reduce( ( result, tr ) => Object.assign( result, {
 *         [ tr.firstChild.textContent.trim() ]: true
 *     } ), {} ) ).sort();
 *
 * Some notable omissions:
 *
 *  - `alt`: https://blog.whatwg.org/omit-alt
 *
 * @type {Set<string>}
 */

const ENUMERATED_ATTRIBUTES = new Set(['autocapitalize', 'autocomplete', 'charset', 'contenteditable', 'crossorigin', 'decoding', 'dir', 'draggable', 'enctype', 'formenctype', 'formmethod', 'http-equiv', 'inputmode', 'kind', 'method', 'preload', 'scope', 'shape', 'spellcheck', 'translate', 'type', 'wrap']);
/**
 * Set of CSS style properties which support assignment of unitless numbers.
 * Used in rendering of style properties, where `px` unit is assumed unless
 * property is included in this set or value is zero.
 *
 * Generated via:
 *
 * Object.entries( document.createElement( 'div' ).style )
 *     .filter( ( [ key ] ) => (
 *         ! /^(webkit|ms|moz)/.test( key ) &&
 *         ( e.style[ key ] = 10 ) &&
 *         e.style[ key ] === '10'
 *     ) )
 *     .map( ( [ key ] ) => key )
 *     .sort();
 *
 * @type {Set<string>}
 */

const CSS_PROPERTIES_SUPPORTS_UNITLESS = new Set(['animation', 'animationIterationCount', 'baselineShift', 'borderImageOutset', 'borderImageSlice', 'borderImageWidth', 'columnCount', 'cx', 'cy', 'fillOpacity', 'flexGrow', 'flexShrink', 'floodOpacity', 'fontWeight', 'gridColumnEnd', 'gridColumnStart', 'gridRowEnd', 'gridRowStart', 'lineHeight', 'opacity', 'order', 'orphans', 'r', 'rx', 'ry', 'shapeImageThreshold', 'stopOpacity', 'strokeDasharray', 'strokeDashoffset', 'strokeMiterlimit', 'strokeOpacity', 'strokeWidth', 'tabSize', 'widows', 'x', 'y', 'zIndex', 'zoom']);
/**
 * Returns true if the specified string is prefixed by one of an array of
 * possible prefixes.
 *
 * @param {string}   string   String to check.
 * @param {string[]} prefixes Possible prefixes.
 *
 * @return {boolean} Whether string has prefix.
 */

function hasPrefix(string, prefixes) {
  return prefixes.some(prefix => string.indexOf(prefix) === 0);
}
/**
 * Returns true if the given prop name should be ignored in attributes
 * serialization, or false otherwise.
 *
 * @param {string} attribute Attribute to check.
 *
 * @return {boolean} Whether attribute should be ignored.
 */

function isInternalAttribute(attribute) {
  return 'key' === attribute || 'children' === attribute;
}
/**
 * Returns the normal form of the element's attribute value for HTML.
 *
 * @param {string} attribute Attribute name.
 * @param {*}      value     Non-normalized attribute value.
 *
 * @return {*} Normalized attribute value.
 */


function getNormalAttributeValue(attribute, value) {
  switch (attribute) {
    case 'style':
      return renderStyle(value);
  }

  return value;
}
/**
 * Returns the normal form of the element's attribute name for HTML.
 *
 * @param {string} attribute Non-normalized attribute name.
 *
 * @return {string} Normalized attribute name.
 */


function getNormalAttributeName(attribute) {
  switch (attribute) {
    case 'htmlFor':
      return 'for';

    case 'className':
      return 'class';
  }

  return attribute.toLowerCase();
}
/**
 * Returns the normal form of the style property name for HTML.
 *
 * - Converts property names to kebab-case, e.g. 'backgroundColor' → 'background-color'
 * - Leaves custom attributes alone, e.g. '--myBackgroundColor' → '--myBackgroundColor'
 * - Converts vendor-prefixed property names to -kebab-case, e.g. 'MozTransform' → '-moz-transform'
 *
 * @param {string} property Property name.
 *
 * @return {string} Normalized property name.
 */


function getNormalStylePropertyName(property) {
  if (Object(lodash__WEBPACK_IMPORTED_MODULE_0__["startsWith"])(property, '--')) {
    return property;
  }

  if (hasPrefix(property, ['ms', 'O', 'Moz', 'Webkit'])) {
    return '-' + Object(lodash__WEBPACK_IMPORTED_MODULE_0__["kebabCase"])(property);
  }

  return Object(lodash__WEBPACK_IMPORTED_MODULE_0__["kebabCase"])(property);
}
/**
 * Returns the normal form of the style property value for HTML. Appends a
 * default pixel unit if numeric, not a unitless property, and not zero.
 *
 * @param {string} property Property name.
 * @param {*}      value    Non-normalized property value.
 *
 * @return {*} Normalized property value.
 */


function getNormalStylePropertyValue(property, value) {
  if (typeof value === 'number' && 0 !== value && !CSS_PROPERTIES_SUPPORTS_UNITLESS.has(property)) {
    return value + 'px';
  }

  return value;
}
/**
 * Serializes a React element to string.
 *
 * @param {import('react').ReactNode} element         Element to serialize.
 * @param {Object}                    [context]       Context object.
 * @param {Object}                    [legacyContext] Legacy context object.
 *
 * @return {string} Serialized element.
 */


function renderElement(element, context) {
  let legacyContext = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};

  if (null === element || undefined === element || false === element) {
    return '';
  }

  if (Array.isArray(element)) {
    return renderChildren(element, context, legacyContext);
  }

  switch (typeof element) {
    case 'string':
      return Object(_gechiui_escape_html__WEBPACK_IMPORTED_MODULE_1__["escapeHTML"])(element);

    case 'number':
      return element.toString();
  }

  const {
    type,
    props
  } =
  /** @type {{type?: any, props?: any}} */
  element;

  switch (type) {
    case _react__WEBPACK_IMPORTED_MODULE_2__["StrictMode"]:
    case _react__WEBPACK_IMPORTED_MODULE_2__["Fragment"]:
      return renderChildren(props.children, context, legacyContext);

    case _raw_html__WEBPACK_IMPORTED_MODULE_3__["default"]:
      const {
        children,
        ...wrapperProps
      } = props;
      return renderNativeComponent(Object(lodash__WEBPACK_IMPORTED_MODULE_0__["isEmpty"])(wrapperProps) ? null : 'div', { ...wrapperProps,
        dangerouslySetInnerHTML: {
          __html: children
        }
      }, context, legacyContext);
  }

  switch (typeof type) {
    case 'string':
      return renderNativeComponent(type, props, context, legacyContext);

    case 'function':
      if (type.prototype && typeof type.prototype.render === 'function') {
        return renderComponent(type, props, context, legacyContext);
      }

      return renderElement(type(props, legacyContext), context, legacyContext);
  }

  switch (type && type.$$typeof) {
    case Provider.$$typeof:
      return renderChildren(props.children, props.value, legacyContext);

    case Consumer.$$typeof:
      return renderElement(props.children(context || type._currentValue), context, legacyContext);

    case ForwardRef.$$typeof:
      return renderElement(type.render(props), context, legacyContext);
  }

  return '';
}
/**
 * Serializes a native component type to string.
 *
 * @param {?string} type            Native component type to serialize, or null if
 *                                  rendering as fragment of children content.
 * @param {Object}  props           Props object.
 * @param {Object}  [context]       Context object.
 * @param {Object}  [legacyContext] Legacy context object.
 *
 * @return {string} Serialized element.
 */

function renderNativeComponent(type, props, context) {
  let legacyContext = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : {};
  let content = '';

  if (type === 'textarea' && props.hasOwnProperty('value')) {
    // Textarea children can be assigned as value prop. If it is, render in
    // place of children. Ensure to omit so it is not assigned as attribute
    // as well.
    content = renderChildren(props.value, context, legacyContext);
    props = Object(lodash__WEBPACK_IMPORTED_MODULE_0__["omit"])(props, 'value');
  } else if (props.dangerouslySetInnerHTML && typeof props.dangerouslySetInnerHTML.__html === 'string') {
    // Dangerous content is left unescaped.
    content = props.dangerouslySetInnerHTML.__html;
  } else if (typeof props.children !== 'undefined') {
    content = renderChildren(props.children, context, legacyContext);
  }

  if (!type) {
    return content;
  }

  const attributes = renderAttributes(props);

  if (SELF_CLOSING_TAGS.has(type)) {
    return '<' + type + attributes + '/>';
  }

  return '<' + type + attributes + '>' + content + '</' + type + '>';
}
/** @typedef {import('./react').GCComponent} GCComponent */

/**
 * Serializes a non-native component type to string.
 *
 * @param {GCComponent} Component       Component type to serialize.
 * @param {Object}      props           Props object.
 * @param {Object}      [context]       Context object.
 * @param {Object}      [legacyContext] Legacy context object.
 *
 * @return {string} Serialized element
 */

function renderComponent(Component, props, context) {
  let legacyContext = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : {};
  const instance = new
  /** @type {import('react').ComponentClass} */
  Component(props, legacyContext);

  if (typeof // Ignore reason: Current prettier reformats parens and mangles type assertion
  // prettier-ignore

  /** @type {{getChildContext?: () => unknown}} */
  instance.getChildContext === 'function') {
    Object.assign(legacyContext,
    /** @type {{getChildContext?: () => unknown}} */
    instance.getChildContext());
  }

  const html = renderElement(instance.render(), context, legacyContext);
  return html;
}
/**
 * Serializes an array of children to string.
 *
 * @param {import('react').ReactNodeArray} children        Children to serialize.
 * @param {Object}                         [context]       Context object.
 * @param {Object}                         [legacyContext] Legacy context object.
 *
 * @return {string} Serialized children.
 */

function renderChildren(children, context) {
  let legacyContext = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
  let result = '';
  children = Object(lodash__WEBPACK_IMPORTED_MODULE_0__["castArray"])(children);

  for (let i = 0; i < children.length; i++) {
    const child = children[i];
    result += renderElement(child, context, legacyContext);
  }

  return result;
}
/**
 * Renders a props object as a string of HTML attributes.
 *
 * @param {Object} props Props object.
 *
 * @return {string} Attributes string.
 */


function renderAttributes(props) {
  let result = '';

  for (const key in props) {
    const attribute = getNormalAttributeName(key);

    if (!Object(_gechiui_escape_html__WEBPACK_IMPORTED_MODULE_1__["isValidAttributeName"])(attribute)) {
      continue;
    }

    let value = getNormalAttributeValue(key, props[key]); // If value is not of serializeable type, skip.

    if (!ATTRIBUTES_TYPES.has(typeof value)) {
      continue;
    } // Don't render internal attribute names.


    if (isInternalAttribute(key)) {
      continue;
    }

    const isBooleanAttribute = BOOLEAN_ATTRIBUTES.has(attribute); // Boolean attribute should be omitted outright if its value is false.

    if (isBooleanAttribute && value === false) {
      continue;
    }

    const isMeaningfulAttribute = isBooleanAttribute || hasPrefix(key, ['data-', 'aria-']) || ENUMERATED_ATTRIBUTES.has(attribute); // Only write boolean value as attribute if meaningful.

    if (typeof value === 'boolean' && !isMeaningfulAttribute) {
      continue;
    }

    result += ' ' + attribute; // Boolean attributes should write attribute name, but without value.
    // Mere presence of attribute name is effective truthiness.

    if (isBooleanAttribute) {
      continue;
    }

    if (typeof value === 'string') {
      value = Object(_gechiui_escape_html__WEBPACK_IMPORTED_MODULE_1__["escapeAttribute"])(value);
    }

    result += '="' + value + '"';
  }

  return result;
}
/**
 * Renders a style object as a string attribute value.
 *
 * @param {Object} style Style object.
 *
 * @return {string} Style attribute value.
 */

function renderStyle(style) {
  // Only generate from object, e.g. tolerate string value.
  if (!Object(lodash__WEBPACK_IMPORTED_MODULE_0__["isPlainObject"])(style)) {
    return style;
  }

  let result;

  for (const property in style) {
    const value = style[property];

    if (null === value || undefined === value) {
      continue;
    }

    if (result) {
      result += ';';
    } else {
      result = '';
    }

    const normalName = getNormalStylePropertyName(property);
    const normalValue = getNormalStylePropertyValue(property, value);
    result += normalName + ':' + normalValue;
  }

  return result;
}
/* harmony default export */ __webpack_exports__["default"] = (renderElement);


/***/ }),

/***/ "./node_modules/@gechiui/element/build-module/utils.js":
/*!*************************************************************!*\
  !*** ./node_modules/@gechiui/element/build-module/utils.js ***!
  \*************************************************************/
/*! exports provided: isEmptyElement */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "isEmptyElement", function() { return isEmptyElement; });
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_0__);
/**
 * External dependencies
 */

/**
 * Checks if the provided GC element is empty.
 *
 * @param {*} element GC element to check.
 * @return {boolean} True when an element is considered empty.
 */

const isEmptyElement = element => {
  if (Object(lodash__WEBPACK_IMPORTED_MODULE_0__["isNumber"])(element)) {
    return false;
  }

  if (Object(lodash__WEBPACK_IMPORTED_MODULE_0__["isString"])(element) || Object(lodash__WEBPACK_IMPORTED_MODULE_0__["isArray"])(element)) {
    return !element.length;
  }

  return !element;
};


/***/ }),

/***/ "@gechiui/escape-html":
/*!************************************!*\
  !*** external ["gc","escapeHtml"] ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["gc"]["escapeHtml"]; }());

/***/ }),

/***/ "lodash":
/*!*************************!*\
  !*** external "lodash" ***!
  \*************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["lodash"]; }());

/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["React"]; }());

/***/ }),

/***/ "react-dom":
/*!***************************!*\
  !*** external "ReactDOM" ***!
  \***************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["ReactDOM"]; }());

/***/ })

/******/ });
//# sourceMappingURL=element.js.map