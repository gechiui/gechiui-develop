this["gc"] = this["gc"] || {}; this["gc"]["listReusableBlocks"] =
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
/******/ 	return __webpack_require__(__webpack_require__.s = "./node_modules/@gechiui/list-reusable-blocks/build-module/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/@gechiui/list-reusable-blocks/build-module/components/import-dropdown/index.js":
/*!*****************************************************************************************************!*\
  !*** ./node_modules/@gechiui/list-reusable-blocks/build-module/components/import-dropdown/index.js ***!
  \*****************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _gechiui_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @gechiui/element */ "@gechiui/element");
/* harmony import */ var _gechiui_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_gechiui_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _gechiui_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @gechiui/i18n */ "@gechiui/i18n");
/* harmony import */ var _gechiui_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _gechiui_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @gechiui/components */ "@gechiui/components");
/* harmony import */ var _gechiui_components__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_gechiui_components__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _import_form__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../import-form */ "./node_modules/@gechiui/list-reusable-blocks/build-module/components/import-form/index.js");


/**
 * External dependencies
 */

/**
 * GeChiUI dependencies
 */



/**
 * Internal dependencies
 */



function ImportDropdown(_ref) {
  let {
    onUpload
  } = _ref;
  return Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_gechiui_components__WEBPACK_IMPORTED_MODULE_3__["Dropdown"], {
    position: "bottom right",
    contentClassName: "list-reusable-blocks-import-dropdown__content",
    renderToggle: _ref2 => {
      let {
        isOpen,
        onToggle
      } = _ref2;
      return Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_gechiui_components__WEBPACK_IMPORTED_MODULE_3__["Button"], {
        "aria-expanded": isOpen,
        onClick: onToggle,
        variant: "primary"
      }, Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('从JSON导入'));
    },
    renderContent: _ref3 => {
      let {
        onClose
      } = _ref3;
      return Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_import_form__WEBPACK_IMPORTED_MODULE_4__["default"], {
        onUpload: Object(lodash__WEBPACK_IMPORTED_MODULE_1__["flow"])(onClose, onUpload)
      });
    }
  });
}

/* harmony default export */ __webpack_exports__["default"] = (ImportDropdown);


/***/ }),

/***/ "./node_modules/@gechiui/list-reusable-blocks/build-module/components/import-form/index.js":
/*!*************************************************************************************************!*\
  !*** ./node_modules/@gechiui/list-reusable-blocks/build-module/components/import-form/index.js ***!
  \*************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _gechiui_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @gechiui/element */ "@gechiui/element");
/* harmony import */ var _gechiui_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_gechiui_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _gechiui_compose__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @gechiui/compose */ "@gechiui/compose");
/* harmony import */ var _gechiui_compose__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_gechiui_compose__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _gechiui_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @gechiui/i18n */ "@gechiui/i18n");
/* harmony import */ var _gechiui_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _gechiui_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @gechiui/components */ "@gechiui/components");
/* harmony import */ var _gechiui_components__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_gechiui_components__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _utils_import__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../utils/import */ "./node_modules/@gechiui/list-reusable-blocks/build-module/utils/import.js");


/**
 * GeChiUI dependencies
 */




/**
 * Internal dependencies
 */



class ImportForm extends _gechiui_element__WEBPACK_IMPORTED_MODULE_0__["Component"] {
  constructor() {
    super(...arguments);
    this.state = {
      isLoading: false,
      error: null,
      file: null
    };
    this.isStillMounted = true;
    this.onChangeFile = this.onChangeFile.bind(this);
    this.onSubmit = this.onSubmit.bind(this);
  }

  componentWillUnmount() {
    this.isStillMounted = false;
  }

  onChangeFile(event) {
    this.setState({
      file: event.target.files[0],
      error: null
    });
  }

  onSubmit(event) {
    event.preventDefault();
    const {
      file
    } = this.state;
    const {
      onUpload
    } = this.props;

    if (!file) {
      return;
    }

    this.setState({
      isLoading: true
    });
    Object(_utils_import__WEBPACK_IMPORTED_MODULE_4__["default"])(file).then(reusableBlock => {
      if (!this.isStillMounted) {
        return;
      }

      this.setState({
        isLoading: false
      });
      onUpload(reusableBlock);
    }).catch(error => {
      if (!this.isStillMounted) {
        return;
      }

      let uiMessage;

      switch (error.message) {
        case 'Invalid JSON file':
          uiMessage = Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('无效的JSON文件');
          break;

        case '无效的可重用区块 JSON 文件':
          uiMessage = Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('无效的可重用区块 JSON 文件');
          break;

        default:
          uiMessage = Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('未知错误');
      }

      this.setState({
        isLoading: false,
        error: uiMessage
      });
    });
  }

  onDismissError() {
    this.setState({
      error: null
    });
  }

  render() {
    const {
      instanceId
    } = this.props;
    const {
      file,
      isLoading,
      error
    } = this.state;
    const inputId = 'list-reusable-blocks-import-form-' + instanceId;
    return Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("form", {
      className: "list-reusable-blocks-import-form",
      onSubmit: this.onSubmit
    }, error && Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_gechiui_components__WEBPACK_IMPORTED_MODULE_3__["Notice"], {
      status: "error",
      onRemove: () => this.onDismissError()
    }, error), Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("label", {
      htmlFor: inputId,
      className: "list-reusable-blocks-import-form__label"
    }, Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('文件')), Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("input", {
      id: inputId,
      type: "file",
      onChange: this.onChangeFile
    }), Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_gechiui_components__WEBPACK_IMPORTED_MODULE_3__["Button"], {
      type: "submit",
      isBusy: isLoading,
      disabled: !file || isLoading,
      variant: "secondary",
      className: "list-reusable-blocks-import-form__button"
    }, Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_2__["_x"])('Import', 'button label')));
  }

}

/* harmony default export */ __webpack_exports__["default"] = (Object(_gechiui_compose__WEBPACK_IMPORTED_MODULE_1__["withInstanceId"])(ImportForm));


/***/ }),

/***/ "./node_modules/@gechiui/list-reusable-blocks/build-module/index.js":
/*!**************************************************************************!*\
  !*** ./node_modules/@gechiui/list-reusable-blocks/build-module/index.js ***!
  \**************************************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _gechiui_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @gechiui/element */ "@gechiui/element");
/* harmony import */ var _gechiui_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_gechiui_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _gechiui_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @gechiui/i18n */ "@gechiui/i18n");
/* harmony import */ var _gechiui_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _utils_export__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./utils/export */ "./node_modules/@gechiui/list-reusable-blocks/build-module/utils/export.js");
/* harmony import */ var _components_import_dropdown__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./components/import-dropdown */ "./node_modules/@gechiui/list-reusable-blocks/build-module/components/import-dropdown/index.js");


/**
 * GeChiUI dependencies
 */


/**
 * Internal dependencies
 */


 // Setup Export Links

document.body.addEventListener('click', event => {
  if (!event.target.classList.contains('gc-list-reusable-blocks__export')) {
    return;
  }

  event.preventDefault();
  Object(_utils_export__WEBPACK_IMPORTED_MODULE_2__["default"])(event.target.dataset.id);
}); // Setup Import Form

document.addEventListener('DOMContentLoaded', () => {
  const button = document.querySelector('.page-title-action');

  if (!button) {
    return;
  }

  const showNotice = () => {
    const notice = document.createElement('div');
    notice.className = 'notice notice-success is-dismissible';
    notice.innerHTML = `<p>${Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('成功导入可重用区块！')}</p>`;
    const headerEnd = document.querySelector('.gc-header-end');

    if (!headerEnd) {
      return;
    }

    headerEnd.parentNode.insertBefore(notice, headerEnd);
  };

  const container = document.createElement('div');
  container.className = 'list-reusable-blocks__container';
  button.parentNode.insertBefore(container, button);
  Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_0__["render"])(Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_components_import_dropdown__WEBPACK_IMPORTED_MODULE_3__["default"], {
    onUpload: showNotice
  }), container);
});


/***/ }),

/***/ "./node_modules/@gechiui/list-reusable-blocks/build-module/utils/export.js":
/*!*********************************************************************************!*\
  !*** ./node_modules/@gechiui/list-reusable-blocks/build-module/utils/export.js ***!
  \*********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @gechiui/api-fetch */ "@gechiui/api-fetch");
/* harmony import */ var _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _file__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./file */ "./node_modules/@gechiui/list-reusable-blocks/build-module/utils/file.js");
/**
 * External dependencies
 */

/**
 * GeChiUI dependencies
 */


/**
 * Internal dependencies
 */


/**
 * Export a reusable block as a JSON file.
 *
 * @param {number} id
 */

async function exportReusableBlock(id) {
  const postType = await _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_1___default()({
    path: `/gc/v2/types/gc_block`
  });
  const post = await _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_1___default()({
    path: `/gc/v2/${postType.rest_base}/${id}?context=edit`
  });
  const title = post.title.raw;
  const content = post.content.raw;
  const fileContent = JSON.stringify({
    __file: 'gc_block',
    title,
    content
  }, null, 2);
  const fileName = Object(lodash__WEBPACK_IMPORTED_MODULE_0__["kebabCase"])(title) + '.json';
  Object(_file__WEBPACK_IMPORTED_MODULE_2__["download"])(fileName, fileContent, 'application/json');
}

/* harmony default export */ __webpack_exports__["default"] = (exportReusableBlock);


/***/ }),

/***/ "./node_modules/@gechiui/list-reusable-blocks/build-module/utils/file.js":
/*!*******************************************************************************!*\
  !*** ./node_modules/@gechiui/list-reusable-blocks/build-module/utils/file.js ***!
  \*******************************************************************************/
/*! exports provided: download, readTextFile */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "download", function() { return download; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "readTextFile", function() { return readTextFile; });
/**
 * Downloads a file.
 *
 * @param {string} fileName    File Name.
 * @param {string} content     File Content.
 * @param {string} contentType File mime type.
 */
function download(fileName, content, contentType) {
  const file = new window.Blob([content], {
    type: contentType
  }); // IE11 can't use the click to download technique
  // we use a specific IE11 technique instead.

  if (window.navigator.msSaveOrOpenBlob) {
    window.navigator.msSaveOrOpenBlob(file, fileName);
  } else {
    const a = document.createElement('a');
    a.href = URL.createObjectURL(file);
    a.download = fileName;
    a.style.display = 'none';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
  }
}
/**
 * Reads the textual content of the given file.
 *
 * @param {File} file File.
 * @return {Promise<string>}  Content of the file.
 */

function readTextFile(file) {
  const reader = new window.FileReader();
  return new Promise(resolve => {
    reader.onload = () => {
      resolve(reader.result);
    };

    reader.readAsText(file);
  });
}


/***/ }),

/***/ "./node_modules/@gechiui/list-reusable-blocks/build-module/utils/import.js":
/*!*********************************************************************************!*\
  !*** ./node_modules/@gechiui/list-reusable-blocks/build-module/utils/import.js ***!
  \*********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @gechiui/api-fetch */ "@gechiui/api-fetch");
/* harmony import */ var _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _file__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./file */ "./node_modules/@gechiui/list-reusable-blocks/build-module/utils/file.js");
/**
 * External dependencies
 */

/**
 * GeChiUI dependencies
 */


/**
 * Internal dependencies
 */


/**
 * Import a reusable block from a JSON file.
 *
 * @param {File} file File.
 * @return {Promise} Promise returning the imported reusable block.
 */

async function importReusableBlock(file) {
  const fileContent = await Object(_file__WEBPACK_IMPORTED_MODULE_2__["readTextFile"])(file);
  let parsedContent;

  try {
    parsedContent = JSON.parse(fileContent);
  } catch (e) {
    throw new Error('Invalid JSON file');
  }

  if (parsedContent.__file !== 'gc_block' || !parsedContent.title || !parsedContent.content || !Object(lodash__WEBPACK_IMPORTED_MODULE_0__["isString"])(parsedContent.title) || !Object(lodash__WEBPACK_IMPORTED_MODULE_0__["isString"])(parsedContent.content)) {
    throw new Error('无效的可重用区块 JSON 文件');
  }

  const postType = await _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_1___default()({
    path: `/gc/v2/types/gc_block`
  });
  const reusableBlock = await _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_1___default()({
    path: `/gc/v2/${postType.rest_base}`,
    data: {
      title: parsedContent.title,
      content: parsedContent.content,
      status: 'publish'
    },
    method: 'POST'
  });
  return reusableBlock;
}

/* harmony default export */ __webpack_exports__["default"] = (importReusableBlock);


/***/ }),

/***/ "@gechiui/api-fetch":
/*!**********************************!*\
  !*** external ["gc","apiFetch"] ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["gc"]["apiFetch"]; }());

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

/***/ "lodash":
/*!*************************!*\
  !*** external "lodash" ***!
  \*************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["lodash"]; }());

/***/ })

/******/ });
//# sourceMappingURL=list-reusable-blocks.js.map