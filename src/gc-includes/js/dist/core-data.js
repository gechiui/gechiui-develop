this["gc"] = this["gc"] || {}; this["gc"]["coreData"] =
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
/******/ 	return __webpack_require__(__webpack_require__.s = "./node_modules/@gechiui/core-data/build-module/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/@gechiui/core-data/build-module/actions.js":
/*!*****************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/actions.js ***!
  \*****************************************************************/
/*! exports provided: receiveUserQuery, receiveCurrentUser, addEntities, receiveEntityRecords, receiveCurrentTheme, __experimentalReceiveCurrentGlobalStylesId, __experimentalReceiveThemeBaseGlobalStyles, receiveThemeSupports, receiveEmbedPreview, deleteEntityRecord, editEntityRecord, undo, redo, __unstableCreateUndoLevel, saveEntityRecord, __experimentalBatch, saveEditedEntityRecord, __experimentalSaveSpecifiedEntityEdits, receiveUploadPermissions, receiveUserPermission, receiveAutosaves */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "receiveUserQuery", function() { return receiveUserQuery; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "receiveCurrentUser", function() { return receiveCurrentUser; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "addEntities", function() { return addEntities; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "receiveEntityRecords", function() { return receiveEntityRecords; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "receiveCurrentTheme", function() { return receiveCurrentTheme; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "__experimentalReceiveCurrentGlobalStylesId", function() { return __experimentalReceiveCurrentGlobalStylesId; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "__experimentalReceiveThemeBaseGlobalStyles", function() { return __experimentalReceiveThemeBaseGlobalStyles; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "receiveThemeSupports", function() { return receiveThemeSupports; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "receiveEmbedPreview", function() { return receiveEmbedPreview; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "deleteEntityRecord", function() { return deleteEntityRecord; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "editEntityRecord", function() { return editEntityRecord; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "undo", function() { return undo; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "redo", function() { return redo; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "__unstableCreateUndoLevel", function() { return __unstableCreateUndoLevel; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "saveEntityRecord", function() { return saveEntityRecord; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "__experimentalBatch", function() { return __experimentalBatch; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "saveEditedEntityRecord", function() { return saveEditedEntityRecord; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "__experimentalSaveSpecifiedEntityEdits", function() { return __experimentalSaveSpecifiedEntityEdits; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "receiveUploadPermissions", function() { return receiveUploadPermissions; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "receiveUserPermission", function() { return receiveUserPermission; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "receiveAutosaves", function() { return receiveAutosaves; });
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var uuid__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! uuid */ "./node_modules/uuid/dist/esm-browser/index.js");
/* harmony import */ var _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @gechiui/api-fetch */ "@gechiui/api-fetch");
/* harmony import */ var _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _gechiui_url__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @gechiui/url */ "@gechiui/url");
/* harmony import */ var _gechiui_url__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_gechiui_url__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _gechiui_deprecated__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @gechiui/deprecated */ "@gechiui/deprecated");
/* harmony import */ var _gechiui_deprecated__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_gechiui_deprecated__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _queried_data__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./queried-data */ "./node_modules/@gechiui/core-data/build-module/queried-data/index.js");
/* harmony import */ var _entities__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./entities */ "./node_modules/@gechiui/core-data/build-module/entities.js");
/* harmony import */ var _batch__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./batch */ "./node_modules/@gechiui/core-data/build-module/batch/index.js");
/* harmony import */ var _name__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./name */ "./node_modules/@gechiui/core-data/build-module/name.js");
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
 * Returns an action object used in signalling that authors have been received.
 *
 * @param {string}       queryID Query ID.
 * @param {Array|Object} users   Users received.
 *
 * @return {Object} Action object.
 */

function receiveUserQuery(queryID, users) {
  return {
    type: 'RECEIVE_USER_QUERY',
    users: Object(lodash__WEBPACK_IMPORTED_MODULE_0__["castArray"])(users),
    queryID
  };
}
/**
 * Returns an action used in signalling that the current user has been received.
 *
 * @param {Object} currentUser Current user object.
 *
 * @return {Object} Action object.
 */

function receiveCurrentUser(currentUser) {
  return {
    type: 'RECEIVE_CURRENT_USER',
    currentUser
  };
}
/**
 * Returns an action object used in adding new entities.
 *
 * @param {Array} entities Entities received.
 *
 * @return {Object} Action object.
 */

function addEntities(entities) {
  return {
    type: 'ADD_ENTITIES',
    entities
  };
}
/**
 * Returns an action object used in signalling that entity records have been received.
 *
 * @param {string}       kind            Kind of the received entity.
 * @param {string}       name            Name of the received entity.
 * @param {Array|Object} records         Records received.
 * @param {?Object}      query           Query Object.
 * @param {?boolean}     invalidateCache Should invalidate query caches.
 * @param {?Object}      edits           Edits to reset.
 * @return {Object} Action object.
 */

function receiveEntityRecords(kind, name, records, query) {
  let invalidateCache = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : false;
  let edits = arguments.length > 5 ? arguments[5] : undefined;

  // Auto drafts should not have titles, but some plugins rely on them so we can't filter this
  // on the server.
  if (kind === 'postType') {
    records = Object(lodash__WEBPACK_IMPORTED_MODULE_0__["castArray"])(records).map(record => record.status === 'auto-draft' ? { ...record,
      title: ''
    } : record);
  }

  let action;

  if (query) {
    action = Object(_queried_data__WEBPACK_IMPORTED_MODULE_5__["receiveQueriedItems"])(records, query, edits);
  } else {
    action = Object(_queried_data__WEBPACK_IMPORTED_MODULE_5__["receiveItems"])(records, edits);
  }

  return { ...action,
    kind,
    name,
    invalidateCache
  };
}
/**
 * Returns an action object used in signalling that the current theme has been received.
 *
 * @param {Object} currentTheme The current theme.
 *
 * @return {Object} Action object.
 */

function receiveCurrentTheme(currentTheme) {
  return {
    type: 'RECEIVE_CURRENT_THEME',
    currentTheme
  };
}
/**
 * Returns an action object used in signalling that the current global styles id has been received.
 *
 * @param {string} currentGlobalStylesId The current global styles id.
 *
 * @return {Object} Action object.
 */

function __experimentalReceiveCurrentGlobalStylesId(currentGlobalStylesId) {
  return {
    type: 'RECEIVE_CURRENT_GLOBAL_STYLES_ID',
    id: currentGlobalStylesId
  };
}
/**
 * Returns an action object used in signalling that the theme base global styles have been received
 *
 * @param {string} stylesheet   The theme's identifier
 * @param {Object} globalStyles The global styles object.
 *
 * @return {Object} Action object.
 */

function __experimentalReceiveThemeBaseGlobalStyles(stylesheet, globalStyles) {
  return {
    type: 'RECEIVE_THEME_GLOBAL_STYLES',
    stylesheet,
    globalStyles
  };
}
/**
 * Returns an action object used in signalling that the index has been received.
 *
 * @deprecated since GC 5.9, this is not useful anymore, use the selector direclty.
 *
 * @return {Object} Action object.
 */

function receiveThemeSupports() {
  _gechiui_deprecated__WEBPACK_IMPORTED_MODULE_4___default()("gc.data.dispatch( 'core' ).receiveThemeSupports", {
    since: '5.9'
  });
  return {
    type: 'DO_NOTHING'
  };
}
/**
 * Returns an action object used in signalling that the preview data for
 * a given URl has been received.
 *
 * @param {string} url     URL to preview the embed for.
 * @param {*}      preview Preview data.
 *
 * @return {Object} Action object.
 */

function receiveEmbedPreview(url, preview) {
  return {
    type: 'RECEIVE_EMBED_PREVIEW',
    url,
    preview
  };
}
/**
 * Action triggered to delete an entity record.
 *
 * @param {string}   kind                      Kind of the deleted entity.
 * @param {string}   name                      Name of the deleted entity.
 * @param {string}   recordId                  Record ID of the deleted entity.
 * @param {?Object}  query                     Special query parameters for the
 *                                             DELETE API call.
 * @param {Object}   [options]                 Delete options.
 * @param {Function} [options.__unstableFetch] Internal use only. Function to
 *                                             call instead of `apiFetch()`.
 *                                             Must return a promise.
 */

const deleteEntityRecord = function (kind, name, recordId, query) {
  let {
    __unstableFetch = _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_2___default.a
  } = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : {};
  return async _ref => {
    let {
      dispatch
    } = _ref;
    const entities = await dispatch(Object(_entities__WEBPACK_IMPORTED_MODULE_6__["getKindEntities"])(kind));
    const entity = Object(lodash__WEBPACK_IMPORTED_MODULE_0__["find"])(entities, {
      kind,
      name
    });
    let error;
    let deletedRecord = false;

    if (!entity || entity !== null && entity !== void 0 && entity.__experimentalNoFetch) {
      return;
    }

    const lock = await dispatch.__unstableAcquireStoreLock(_name__WEBPACK_IMPORTED_MODULE_8__["STORE_NAME"], ['entities', 'data', kind, name, recordId], {
      exclusive: true
    });

    try {
      dispatch({
        type: 'DELETE_ENTITY_RECORD_START',
        kind,
        name,
        recordId
      });

      try {
        let path = `${entity.baseURL}/${recordId}`;

        if (query) {
          path = Object(_gechiui_url__WEBPACK_IMPORTED_MODULE_3__["addQueryArgs"])(path, query);
        }

        deletedRecord = await __unstableFetch({
          path,
          method: 'DELETE'
        });
        await dispatch(Object(_queried_data__WEBPACK_IMPORTED_MODULE_5__["removeItems"])(kind, name, recordId, true));
      } catch (_error) {
        error = _error;
      }

      dispatch({
        type: 'DELETE_ENTITY_RECORD_FINISH',
        kind,
        name,
        recordId,
        error
      });
      return deletedRecord;
    } finally {
      dispatch.__unstableReleaseStoreLock(lock);
    }
  };
};
/**
 * Returns an action object that triggers an
 * edit to an entity record.
 *
 * @param {string}  kind               Kind of the edited entity record.
 * @param {string}  name               Name of the edited entity record.
 * @param {number}  recordId           Record ID of the edited entity record.
 * @param {Object}  edits              The edits.
 * @param {Object}  options            Options for the edit.
 * @param {boolean} options.undoIgnore Whether to ignore the edit in undo history or not.
 *
 * @return {Object} Action object.
 */

const editEntityRecord = function (kind, name, recordId, edits) {
  let options = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : {};
  return _ref2 => {
    let {
      select,
      dispatch
    } = _ref2;
    const entity = select.getEntity(kind, name);

    if (!entity) {
      throw new Error(`The entity being edited (${kind}, ${name}) does not have a loaded config.`);
    }

    const {
      transientEdits = {},
      mergedEdits = {}
    } = entity;
    const record = select.getRawEntityRecord(kind, name, recordId);
    const editedRecord = select.getEditedEntityRecord(kind, name, recordId);
    const edit = {
      kind,
      name,
      recordId,
      // Clear edits when they are equal to their persisted counterparts
      // so that the property is not considered dirty.
      edits: Object.keys(edits).reduce((acc, key) => {
        const recordValue = record[key];
        const editedRecordValue = editedRecord[key];
        const value = mergedEdits[key] ? { ...editedRecordValue,
          ...edits[key]
        } : edits[key];
        acc[key] = Object(lodash__WEBPACK_IMPORTED_MODULE_0__["isEqual"])(recordValue, value) ? undefined : value;
        return acc;
      }, {}),
      transientEdits
    };
    dispatch({
      type: 'EDIT_ENTITY_RECORD',
      ...edit,
      meta: {
        undo: !options.undoIgnore && { ...edit,
          // Send the current values for things like the first undo stack entry.
          edits: Object.keys(edits).reduce((acc, key) => {
            acc[key] = editedRecord[key];
            return acc;
          }, {})
        }
      }
    });
  };
};
/**
 * Action triggered to undo the last edit to
 * an entity record, if any.
 *
 * @return {undefined}
 */

const undo = () => _ref3 => {
  let {
    select,
    dispatch
  } = _ref3;
  const undoEdit = select.getUndoEdit();

  if (!undoEdit) {
    return;
  }

  dispatch({
    type: 'EDIT_ENTITY_RECORD',
    ...undoEdit,
    meta: {
      isUndo: true
    }
  });
};
/**
 * Action triggered to redo the last undoed
 * edit to an entity record, if any.
 *
 * @return {undefined}
 */

const redo = () => _ref4 => {
  let {
    select,
    dispatch
  } = _ref4;
  const redoEdit = select.getRedoEdit();

  if (!redoEdit) {
    return;
  }

  dispatch({
    type: 'EDIT_ENTITY_RECORD',
    ...redoEdit,
    meta: {
      isRedo: true
    }
  });
};
/**
 * Forces the creation of a new undo level.
 *
 * @return {Object} Action object.
 */

function __unstableCreateUndoLevel() {
  return {
    type: 'CREATE_UNDO_LEVEL'
  };
}
/**
 * Action triggered to save an entity record.
 *
 * @param {string}   kind                       Kind of the received entity.
 * @param {string}   name                       Name of the received entity.
 * @param {Object}   record                     Record to be saved.
 * @param {Object}   options                    Saving options.
 * @param {boolean}  [options.isAutosave=false] Whether this is an autosave.
 * @param {Function} [options.__unstableFetch]  Internal use only. Function to
 *                                              call instead of `apiFetch()`.
 *                                              Must return a promise.
 */

const saveEntityRecord = function (kind, name, record) {
  let {
    isAutosave = false,
    __unstableFetch = _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_2___default.a
  } = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : {};
  return async _ref5 => {
    let {
      select,
      resolveSelect,
      dispatch
    } = _ref5;
    const entities = await dispatch(Object(_entities__WEBPACK_IMPORTED_MODULE_6__["getKindEntities"])(kind));
    const entity = Object(lodash__WEBPACK_IMPORTED_MODULE_0__["find"])(entities, {
      kind,
      name
    });

    if (!entity || entity !== null && entity !== void 0 && entity.__experimentalNoFetch) {
      return;
    }

    const entityIdKey = entity.key || _entities__WEBPACK_IMPORTED_MODULE_6__["DEFAULT_ENTITY_KEY"];
    const recordId = record[entityIdKey];
    const lock = await dispatch.__unstableAcquireStoreLock(_name__WEBPACK_IMPORTED_MODULE_8__["STORE_NAME"], ['entities', 'data', kind, name, recordId || Object(uuid__WEBPACK_IMPORTED_MODULE_1__["v4"])()], {
      exclusive: true
    });

    try {
      // Evaluate optimized edits.
      // (Function edits that should be evaluated on save to avoid expensive computations on every edit.)
      for (const [key, value] of Object.entries(record)) {
        if (typeof value === 'function') {
          const evaluatedValue = value(select.getEditedEntityRecord(kind, name, recordId));
          dispatch.editEntityRecord(kind, name, recordId, {
            [key]: evaluatedValue
          }, {
            undoIgnore: true
          });
          record[key] = evaluatedValue;
        }
      }

      dispatch({
        type: 'SAVE_ENTITY_RECORD_START',
        kind,
        name,
        recordId,
        isAutosave
      });
      let updatedRecord;
      let error;

      try {
        const path = `${entity.baseURL}${recordId ? '/' + recordId : ''}`;
        const persistedRecord = select.getRawEntityRecord(kind, name, recordId);

        if (isAutosave) {
          // Most of this autosave logic is very specific to posts.
          // This is fine for now as it is the only supported autosave,
          // but ideally this should all be handled in the back end,
          // so the client just sends and receives objects.
          const currentUser = select.getCurrentUser();
          const currentUserId = currentUser ? currentUser.id : undefined;
          const autosavePost = resolveSelect.getAutosave(persistedRecord.type, persistedRecord.id, currentUserId); // Autosaves need all expected fields to be present.
          // So we fallback to the previous autosave and then
          // to the actual persisted entity if the edits don't
          // have a value.

          let data = { ...persistedRecord,
            ...autosavePost,
            ...record
          };
          data = Object.keys(data).reduce((acc, key) => {
            if (['title', 'excerpt', 'content'].includes(key)) {
              acc[key] = data[key];
            }

            return acc;
          }, {
            status: data.status === 'auto-draft' ? 'draft' : data.status
          });
          updatedRecord = await __unstableFetch({
            path: `${path}/autosaves`,
            method: 'POST',
            data
          }); // An autosave may be processed by the server as a regular save
          // when its update is requested by the author and the post had
          // draft or auto-draft status.

          if (persistedRecord.id === updatedRecord.id) {
            let newRecord = { ...persistedRecord,
              ...data,
              ...updatedRecord
            };
            newRecord = Object.keys(newRecord).reduce((acc, key) => {
              // These properties are persisted in autosaves.
              if (['title', 'excerpt', 'content'].includes(key)) {
                acc[key] = newRecord[key];
              } else if (key === 'status') {
                // Status is only persisted in autosaves when going from
                // "auto-draft" to "draft".
                acc[key] = persistedRecord.status === 'auto-draft' && newRecord.status === 'draft' ? newRecord.status : persistedRecord.status;
              } else {
                // These properties are not persisted in autosaves.
                acc[key] = persistedRecord[key];
              }

              return acc;
            }, {});
            dispatch.receiveEntityRecords(kind, name, newRecord, undefined, true);
          } else {
            dispatch.receiveAutosaves(persistedRecord.id, updatedRecord);
          }
        } else {
          let edits = record;

          if (entity.__unstablePrePersist) {
            edits = { ...edits,
              ...entity.__unstablePrePersist(persistedRecord, edits)
            };
          }

          updatedRecord = await __unstableFetch({
            path,
            method: recordId ? 'PUT' : 'POST',
            data: edits
          });
          dispatch.receiveEntityRecords(kind, name, updatedRecord, undefined, true, edits);
        }
      } catch (_error) {
        error = _error;
      }

      dispatch({
        type: 'SAVE_ENTITY_RECORD_FINISH',
        kind,
        name,
        recordId,
        error,
        isAutosave
      });
      return updatedRecord;
    } finally {
      dispatch.__unstableReleaseStoreLock(lock);
    }
  };
};
/**
 * Runs multiple core-data actions at the same time using one API request.
 *
 * Example:
 *
 * ```
 * const [ savedRecord, updatedRecord, deletedRecord ] =
 *   await dispatch( 'core' ).__experimentalBatch( [
 *     ( { saveEntityRecord } ) => saveEntityRecord( 'root', 'widget', widget ),
 *     ( { saveEditedEntityRecord } ) => saveEntityRecord( 'root', 'widget', 123 ),
 *     ( { deleteEntityRecord } ) => deleteEntityRecord( 'root', 'widget', 123, null ),
 *   ] );
 * ```
 *
 * @param {Array} requests Array of functions which are invoked simultaneously.
 *                         Each function is passed an object containing
 *                         `saveEntityRecord`, `saveEditedEntityRecord`, and
 *                         `deleteEntityRecord`.
 *
 * @return {Promise} A promise that resolves to an array containing the return
 *                   values of each function given in `requests`.
 */

const __experimentalBatch = requests => async _ref6 => {
  let {
    dispatch
  } = _ref6;
  const batch = Object(_batch__WEBPACK_IMPORTED_MODULE_7__["createBatch"])();
  const api = {
    saveEntityRecord(kind, name, record, options) {
      return batch.add(add => dispatch.saveEntityRecord(kind, name, record, { ...options,
        __unstableFetch: add
      }));
    },

    saveEditedEntityRecord(kind, name, recordId, options) {
      return batch.add(add => dispatch.saveEditedEntityRecord(kind, name, recordId, { ...options,
        __unstableFetch: add
      }));
    },

    deleteEntityRecord(kind, name, recordId, query, options) {
      return batch.add(add => dispatch.deleteEntityRecord(kind, name, recordId, query, { ...options,
        __unstableFetch: add
      }));
    }

  };
  const resultPromises = requests.map(request => request(api));
  const [, ...results] = await Promise.all([batch.run(), ...resultPromises]);
  return results;
};
/**
 * Action triggered to save an entity record's edits.
 *
 * @param {string} kind     Kind of the entity.
 * @param {string} name     Name of the entity.
 * @param {Object} recordId ID of the record.
 * @param {Object} options  Saving options.
 */

const saveEditedEntityRecord = (kind, name, recordId, options) => async _ref7 => {
  let {
    select,
    dispatch
  } = _ref7;

  if (!select.hasEditsForEntityRecord(kind, name, recordId)) {
    return;
  }

  const entities = await dispatch(Object(_entities__WEBPACK_IMPORTED_MODULE_6__["getKindEntities"])(kind));
  const entity = Object(lodash__WEBPACK_IMPORTED_MODULE_0__["find"])(entities, {
    kind,
    name
  });

  if (!entity) {
    return;
  }

  const entityIdKey = entity.key || _entities__WEBPACK_IMPORTED_MODULE_6__["DEFAULT_ENTITY_KEY"];
  const edits = select.getEntityRecordNonTransientEdits(kind, name, recordId);
  const record = {
    [entityIdKey]: recordId,
    ...edits
  };
  return await dispatch.saveEntityRecord(kind, name, record, options);
};
/**
 * Action triggered to save only specified properties for the entity.
 *
 * @param {string} kind        Kind of the entity.
 * @param {string} name        Name of the entity.
 * @param {Object} recordId    ID of the record.
 * @param {Array}  itemsToSave List of entity properties to save.
 * @param {Object} options     Saving options.
 */

const __experimentalSaveSpecifiedEntityEdits = (kind, name, recordId, itemsToSave, options) => async _ref8 => {
  let {
    select,
    dispatch
  } = _ref8;

  if (!select.hasEditsForEntityRecord(kind, name, recordId)) {
    return;
  }

  const edits = select.getEntityRecordNonTransientEdits(kind, name, recordId);
  const editsToSave = {};

  for (const edit in edits) {
    if (itemsToSave.some(item => item === edit)) {
      editsToSave[edit] = edits[edit];
    }
  }

  return await dispatch.saveEntityRecord(kind, name, editsToSave, options);
};
/**
 * Returns an action object used in signalling that Upload permissions have been received.
 *
 * @param {boolean} hasUploadPermissions Does the user have permission to upload files?
 *
 * @return {Object} Action object.
 */

function receiveUploadPermissions(hasUploadPermissions) {
  return {
    type: 'RECEIVE_USER_PERMISSION',
    key: 'create/media',
    isAllowed: hasUploadPermissions
  };
}
/**
 * Returns an action object used in signalling that the current user has
 * permission to perform an action on a REST resource.
 *
 * @param {string}  key       A key that represents the action and REST resource.
 * @param {boolean} isAllowed Whether or not the user can perform the action.
 *
 * @return {Object} Action object.
 */

function receiveUserPermission(key, isAllowed) {
  return {
    type: 'RECEIVE_USER_PERMISSION',
    key,
    isAllowed
  };
}
/**
 * Returns an action object used in signalling that the autosaves for a
 * post have been received.
 *
 * @param {number}       postId    The id of the post that is parent to the autosave.
 * @param {Array|Object} autosaves An array of autosaves or singular autosave object.
 *
 * @return {Object} Action object.
 */

function receiveAutosaves(postId, autosaves) {
  return {
    type: 'RECEIVE_AUTOSAVES',
    postId,
    autosaves: Object(lodash__WEBPACK_IMPORTED_MODULE_0__["castArray"])(autosaves)
  };
}


/***/ }),

/***/ "./node_modules/@gechiui/core-data/build-module/batch/create-batch.js":
/*!****************************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/batch/create-batch.js ***!
  \****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return createBatch; });
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _default_processor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./default-processor */ "./node_modules/@gechiui/core-data/build-module/batch/default-processor.js");
/**
 * External dependencies
 */

/**
 * Internal dependencies
 */


/**
 * Creates a batch, which can be used to combine multiple API requests into one
 * API request using the GeChiUI batch processing API (/v1/batch).
 *
 * ```
 * const batch = createBatch();
 * const dunePromise = batch.add( {
 *   path: '/v1/books',
 *   method: 'POST',
 *   data: { title: 'Dune' }
 * } );
 * const lotrPromise = batch.add( {
 *   path: '/v1/books',
 *   method: 'POST',
 *   data: { title: 'Lord of the Rings' }
 * } );
 * const isSuccess = await batch.run(); // Sends one POST to /v1/batch.
 * if ( isSuccess ) {
 *   console.log(
 *     'Saved two books:',
 *     await dunePromise,
 *     await lotrPromise
 *   );
 * }
 * ```
 *
 * @param {Function} [processor] Processor function. Can be used to replace the
 *                               default functionality which is to send an API
 *                               request to /v1/batch. Is given an array of
 *                               inputs and must return a promise that
 *                               resolves to an array of objects containing
 *                               either `output` or `error`.
 */

function createBatch() {
  let processor = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : _default_processor__WEBPACK_IMPORTED_MODULE_1__["default"];
  let lastId = 0;
  let queue = [];
  const pending = new ObservableSet();
  return {
    /**
     * Adds an input to the batch and returns a promise that is resolved or
     * rejected when the input is processed by `batch.run()`.
     *
     * You may also pass a thunk which allows inputs to be added
     * asychronously.
     *
     * ```
     * // Both are allowed:
     * batch.add( { path: '/v1/books', ... } );
     * batch.add( ( add ) => add( { path: '/v1/books', ... } ) );
     * ```
     *
     * If a thunk is passed, `batch.run()` will pause until either:
     *
     * - The thunk calls its `add` argument, or;
     * - The thunk returns a promise and that promise resolves, or;
     * - The thunk returns a non-promise.
     *
     * @param {any|Function} inputOrThunk Input to add or thunk to execute.
     *
     * @return {Promise|any} If given an input, returns a promise that
     *                       is resolved or rejected when the batch is
     *                       processed. If given a thunk, returns the return
     *                       value of that thunk.
     */
    add(inputOrThunk) {
      const id = ++lastId;
      pending.add(id);

      const add = input => new Promise((resolve, reject) => {
        queue.push({
          input,
          resolve,
          reject
        });
        pending.delete(id);
      });

      if (Object(lodash__WEBPACK_IMPORTED_MODULE_0__["isFunction"])(inputOrThunk)) {
        return Promise.resolve(inputOrThunk(add)).finally(() => {
          pending.delete(id);
        });
      }

      return add(inputOrThunk);
    },

    /**
     * Runs the batch. This calls `batchProcessor` and resolves or rejects
     * all promises returned by `add()`.
     *
     * @return {Promise} A promise that resolves to a boolean that is true
     *                   if the processor returned no errors.
     */
    async run() {
      if (pending.size) {
        await new Promise(resolve => {
          const unsubscribe = pending.subscribe(() => {
            if (!pending.size) {
              unsubscribe();
              resolve();
            }
          });
        });
      }

      let results;

      try {
        results = await processor(queue.map(_ref => {
          let {
            input
          } = _ref;
          return input;
        }));

        if (results.length !== queue.length) {
          throw new Error('run: Array returned by processor must be same size as input array.');
        }
      } catch (error) {
        for (const {
          reject
        } of queue) {
          reject(error);
        }

        throw error;
      }

      let isSuccess = true;

      for (const [result, {
        resolve,
        reject
      }] of Object(lodash__WEBPACK_IMPORTED_MODULE_0__["zip"])(results, queue)) {
        if (result !== null && result !== void 0 && result.error) {
          reject(result.error);
          isSuccess = false;
        } else {
          var _result$output;

          resolve((_result$output = result === null || result === void 0 ? void 0 : result.output) !== null && _result$output !== void 0 ? _result$output : result);
        }
      }

      queue = [];
      return isSuccess;
    }

  };
}

class ObservableSet {
  constructor() {
    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
      args[_key] = arguments[_key];
    }

    this.set = new Set(...args);
    this.subscribers = new Set();
  }

  get size() {
    return this.set.size;
  }

  add() {
    this.set.add(...arguments);
    this.subscribers.forEach(subscriber => subscriber());
    return this;
  }

  delete() {
    const isSuccess = this.set.delete(...arguments);
    this.subscribers.forEach(subscriber => subscriber());
    return isSuccess;
  }

  subscribe(subscriber) {
    this.subscribers.add(subscriber);
    return () => {
      this.subscribers.delete(subscriber);
    };
  }

}


/***/ }),

/***/ "./node_modules/@gechiui/core-data/build-module/batch/default-processor.js":
/*!*********************************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/batch/default-processor.js ***!
  \*********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return defaultProcessor; });
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @gechiui/api-fetch */ "@gechiui/api-fetch");
/* harmony import */ var _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_1__);
/**
 * External dependencies
 */

/**
 * GeChiUI dependencies
 */


/**
 * Maximum number of requests to place in a single batch request. Obtained by
 * sending a preflight OPTIONS request to /batch/v1/.
 *
 * @type {number?}
 */

let maxItems = null;
/**
 * Default batch processor. Sends its input requests to /batch/v1.
 *
 * @param {Array} requests List of API requests to perform at once.
 *
 * @return {Promise} Promise that resolves to a list of objects containing
 *                   either `output` (if that request was succesful) or `error`
 *                   (if not ).
 */

async function defaultProcessor(requests) {
  if (maxItems === null) {
    const preflightResponse = await _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_1___default()({
      path: '/batch/v1',
      method: 'OPTIONS'
    });
    maxItems = preflightResponse.endpoints[0].args.requests.maxItems;
  }

  const results = [];

  for (const batchRequests of Object(lodash__WEBPACK_IMPORTED_MODULE_0__["chunk"])(requests, maxItems)) {
    const batchResponse = await _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_1___default()({
      path: '/batch/v1',
      method: 'POST',
      data: {
        validation: 'require-all-validate',
        requests: batchRequests.map(request => ({
          path: request.path,
          body: request.data,
          // Rename 'data' to 'body'.
          method: request.method,
          headers: request.headers
        }))
      }
    });
    let batchResults;

    if (batchResponse.failed) {
      batchResults = batchResponse.responses.map(response => ({
        error: response === null || response === void 0 ? void 0 : response.body
      }));
    } else {
      batchResults = batchResponse.responses.map(response => {
        const result = {};

        if (response.status >= 200 && response.status < 300) {
          result.output = response.body;
        } else {
          result.error = response.body;
        }

        return result;
      });
    }

    results.push(...batchResults);
  }

  return results;
}


/***/ }),

/***/ "./node_modules/@gechiui/core-data/build-module/batch/index.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/batch/index.js ***!
  \*********************************************************************/
/*! exports provided: createBatch, defaultProcessor */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _create_batch__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./create-batch */ "./node_modules/@gechiui/core-data/build-module/batch/create-batch.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "createBatch", function() { return _create_batch__WEBPACK_IMPORTED_MODULE_0__["default"]; });

/* harmony import */ var _default_processor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./default-processor */ "./node_modules/@gechiui/core-data/build-module/batch/default-processor.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "defaultProcessor", function() { return _default_processor__WEBPACK_IMPORTED_MODULE_1__["default"]; });





/***/ }),

/***/ "./node_modules/@gechiui/core-data/build-module/entities.js":
/*!******************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/entities.js ***!
  \******************************************************************/
/*! exports provided: DEFAULT_ENTITY_KEY, defaultEntities, kinds, prePersistPostType, getMethodName, getKindEntities */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "DEFAULT_ENTITY_KEY", function() { return DEFAULT_ENTITY_KEY; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "defaultEntities", function() { return defaultEntities; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "kinds", function() { return kinds; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "prePersistPostType", function() { return prePersistPostType; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getMethodName", function() { return getMethodName; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getKindEntities", function() { return getKindEntities; });
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @gechiui/api-fetch */ "@gechiui/api-fetch");
/* harmony import */ var _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _gechiui_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @gechiui/i18n */ "@gechiui/i18n");
/* harmony import */ var _gechiui_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _actions__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./actions */ "./node_modules/@gechiui/core-data/build-module/actions.js");
/**
 * External dependencies
 */

/**
 * GeChiUI dependencies
 */



/**
 * Internal dependencies
 */


const DEFAULT_ENTITY_KEY = 'id';
const POST_RAW_ATTRIBUTES = ['title', 'excerpt', 'content'];
const defaultEntities = [{
  label: Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('根目录（Base）'),
  name: '__unstableBase',
  kind: 'root',
  baseURL: '/'
}, {
  label: Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('站点'),
  name: 'site',
  kind: 'root',
  baseURL: '/gc/v2/settings',
  getTitle: record => {
    return Object(lodash__WEBPACK_IMPORTED_MODULE_0__["get"])(record, ['title'], Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('站点标题'));
  }
}, {
  label: Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('文章类型'),
  name: 'postType',
  kind: 'root',
  key: 'slug',
  baseURL: '/gc/v2/types',
  baseURLParams: {
    context: 'edit'
  },
  rawAttributes: POST_RAW_ATTRIBUTES
}, {
  name: 'media',
  kind: 'root',
  baseURL: '/gc/v2/media',
  baseURLParams: {
    context: 'edit'
  },
  plural: 'mediaItems',
  label: Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('媒体')
}, {
  name: 'taxonomy',
  kind: 'root',
  key: 'slug',
  baseURL: '/gc/v2/taxonomies',
  baseURLParams: {
    context: 'edit'
  },
  plural: 'taxonomies',
  label: Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('分类法')
}, {
  name: 'sidebar',
  kind: 'root',
  baseURL: '/gc/v2/sidebars',
  plural: 'sidebars',
  transientEdits: {
    blocks: true
  },
  label: Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('小工具区域')
}, {
  name: 'widget',
  kind: 'root',
  baseURL: '/gc/v2/widgets',
  baseURLParams: {
    context: 'edit'
  },
  plural: 'widgets',
  transientEdits: {
    blocks: true
  },
  label: Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('小工具')
}, {
  name: 'widgetType',
  kind: 'root',
  baseURL: '/gc/v2/widget-types',
  baseURLParams: {
    context: 'edit'
  },
  plural: 'widgetTypes',
  label: Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('小工具类型')
}, {
  label: Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('用户'),
  name: 'user',
  kind: 'root',
  baseURL: '/gc/v2/users',
  baseURLParams: {
    context: 'edit'
  },
  plural: 'users'
}, {
  name: 'comment',
  kind: 'root',
  baseURL: '/gc/v2/comments',
  baseURLParams: {
    context: 'edit'
  },
  plural: 'comments',
  label: Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('评论')
}, {
  name: 'menu',
  kind: 'root',
  baseURL: '/gc/v2/menus',
  baseURLParams: {
    context: 'edit'
  },
  plural: 'menus',
  label: Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('菜单')
}, {
  name: 'menuItem',
  kind: 'root',
  baseURL: '/gc/v2/menu-items',
  baseURLParams: {
    context: 'edit'
  },
  plural: 'menuItems',
  label: Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('菜单项'),
  rawAttributes: ['title', 'content']
}, {
  name: 'menuLocation',
  kind: 'root',
  baseURL: '/gc/v2/menu-locations',
  baseURLParams: {
    context: 'edit'
  },
  plural: 'menuLocations',
  label: Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('菜单位置'),
  key: 'name'
}, {
  name: 'navigationArea',
  kind: 'root',
  baseURL: '/gc/v2/block-navigation-areas',
  baseURLParams: {
    context: 'edit'
  },
  plural: 'navigationAreas',
  label: Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('导航区'),
  key: 'name',
  getTitle: record => record === null || record === void 0 ? void 0 : record.description
}, {
  label: Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('全局样式'),
  name: 'globalStyles',
  kind: 'root',
  baseURL: '/gc/v2/global-styles',
  baseURLParams: {
    context: 'edit'
  },
  plural: 'globalStylesVariations',
  // should be different than name
  getTitle: record => {
    var _record$title;

    return (record === null || record === void 0 ? void 0 : (_record$title = record.title) === null || _record$title === void 0 ? void 0 : _record$title.rendered) || (record === null || record === void 0 ? void 0 : record.title);
  }
}, {
  label: Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('主题'),
  name: 'theme',
  kind: 'root',
  baseURL: '/gc/v2/themes',
  baseURLParams: {
    context: 'edit'
  },
  key: 'stylesheet'
}, {
  label: Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('插件'),
  name: 'plugin',
  kind: 'root',
  baseURL: '/gc/v2/plugins',
  baseURLParams: {
    context: 'edit'
  },
  key: 'plugin'
}];
const kinds = [{
  name: 'postType',
  loadEntities: loadPostTypeEntities
}, {
  name: 'taxonomy',
  loadEntities: loadTaxonomyEntities
}];
/**
 * Returns a function to be used to retrieve extra edits to apply before persisting a post type.
 *
 * @param {Object} persistedRecord Already persisted Post
 * @param {Object} edits           Edits.
 * @return {Object} Updated edits.
 */

const prePersistPostType = (persistedRecord, edits) => {
  const newEdits = {};

  if ((persistedRecord === null || persistedRecord === void 0 ? void 0 : persistedRecord.status) === 'auto-draft') {
    // Saving an auto-draft should create a draft by default.
    if (!edits.status && !newEdits.status) {
      newEdits.status = 'draft';
    } // Fix the auto-draft default title.


    if ((!edits.title || edits.title === 'Auto Draft') && !newEdits.title && (!(persistedRecord !== null && persistedRecord !== void 0 && persistedRecord.title) || (persistedRecord === null || persistedRecord === void 0 ? void 0 : persistedRecord.title) === 'Auto Draft')) {
      newEdits.title = '';
    }
  }

  return newEdits;
};
/**
 * Returns the list of post type entities.
 *
 * @return {Promise} Entities promise
 */

async function loadPostTypeEntities() {
  const postTypes = await _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_1___default()({
    path: '/gc/v2/types?context=edit'
  });
  return Object(lodash__WEBPACK_IMPORTED_MODULE_0__["map"])(postTypes, (postType, name) => {
    var _postType$rest_namesp;

    const isTemplate = ['gc_template', 'gc_template_part'].includes(name);
    const namespace = (_postType$rest_namesp = postType === null || postType === void 0 ? void 0 : postType.rest_namespace) !== null && _postType$rest_namesp !== void 0 ? _postType$rest_namesp : 'gc/v2';
    return {
      kind: 'postType',
      baseURL: `/${namespace}/${postType.rest_base}`,
      baseURLParams: {
        context: 'edit'
      },
      name,
      label: postType.labels.singular_name,
      transientEdits: {
        blocks: true,
        selection: true
      },
      mergedEdits: {
        meta: true
      },
      rawAttributes: POST_RAW_ATTRIBUTES,
      getTitle: record => {
        var _record$title2;

        return (record === null || record === void 0 ? void 0 : (_record$title2 = record.title) === null || _record$title2 === void 0 ? void 0 : _record$title2.rendered) || (record === null || record === void 0 ? void 0 : record.title) || (isTemplate ? Object(lodash__WEBPACK_IMPORTED_MODULE_0__["startCase"])(record.slug) : String(record.id));
      },
      __unstablePrePersist: isTemplate ? undefined : prePersistPostType,
      __unstable_rest_base: postType.rest_base
    };
  });
}
/**
 * Returns the list of the taxonomies entities.
 *
 * @return {Promise} Entities promise
 */


async function loadTaxonomyEntities() {
  const taxonomies = await _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_1___default()({
    path: '/gc/v2/taxonomies?context=edit'
  });
  return Object(lodash__WEBPACK_IMPORTED_MODULE_0__["map"])(taxonomies, (taxonomy, name) => {
    var _taxonomy$rest_namesp;

    const namespace = (_taxonomy$rest_namesp = taxonomy === null || taxonomy === void 0 ? void 0 : taxonomy.rest_namespace) !== null && _taxonomy$rest_namesp !== void 0 ? _taxonomy$rest_namesp : 'gc/v2';
    return {
      kind: 'taxonomy',
      baseURL: `/${namespace}/${taxonomy.rest_base}`,
      baseURLParams: {
        context: 'edit'
      },
      name,
      label: taxonomy.labels.singular_name
    };
  });
}
/**
 * Returns the entity's getter method name given its kind and name.
 *
 * @param {string}  kind      Entity kind.
 * @param {string}  name      Entity name.
 * @param {string}  prefix    Function prefix.
 * @param {boolean} usePlural Whether to use the plural form or not.
 *
 * @return {string} Method name
 */


const getMethodName = function (kind, name) {
  let prefix = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 'get';
  let usePlural = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : false;
  const entity = Object(lodash__WEBPACK_IMPORTED_MODULE_0__["find"])(defaultEntities, {
    kind,
    name
  });
  const kindPrefix = kind === 'root' ? '' : Object(lodash__WEBPACK_IMPORTED_MODULE_0__["upperFirst"])(Object(lodash__WEBPACK_IMPORTED_MODULE_0__["camelCase"])(kind));
  const nameSuffix = Object(lodash__WEBPACK_IMPORTED_MODULE_0__["upperFirst"])(Object(lodash__WEBPACK_IMPORTED_MODULE_0__["camelCase"])(name)) + (usePlural ? 's' : '');
  const suffix = usePlural && entity.plural ? Object(lodash__WEBPACK_IMPORTED_MODULE_0__["upperFirst"])(Object(lodash__WEBPACK_IMPORTED_MODULE_0__["camelCase"])(entity.plural)) : nameSuffix;
  return `${prefix}${kindPrefix}${suffix}`;
};
/**
 * Loads the kind entities into the store.
 *
 * @param {string} kind Kind
 *
 * @return {Array} Entities
 */

const getKindEntities = kind => async _ref => {
  let {
    select,
    dispatch
  } = _ref;
  let entities = select.getEntitiesByKind(kind);

  if (entities && entities.length !== 0) {
    return entities;
  }

  const kindConfig = Object(lodash__WEBPACK_IMPORTED_MODULE_0__["find"])(kinds, {
    name: kind
  });

  if (!kindConfig) {
    return [];
  }

  entities = await kindConfig.loadEntities();
  dispatch(Object(_actions__WEBPACK_IMPORTED_MODULE_3__["addEntities"])(entities));
  return entities;
};


/***/ }),

/***/ "./node_modules/@gechiui/core-data/build-module/entity-provider.js":
/*!*************************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/entity-provider.js ***!
  \*************************************************************************/
/*! exports provided: default, useEntityId, useEntityProp, useEntityBlockEditor */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return EntityProvider; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "useEntityId", function() { return useEntityId; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "useEntityProp", function() { return useEntityProp; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "useEntityBlockEditor", function() { return useEntityBlockEditor; });
/* harmony import */ var _gechiui_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @gechiui/element */ "@gechiui/element");
/* harmony import */ var _gechiui_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_gechiui_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _gechiui_data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @gechiui/data */ "@gechiui/data");
/* harmony import */ var _gechiui_data__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_gechiui_data__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _gechiui_blocks__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @gechiui/blocks */ "@gechiui/blocks");
/* harmony import */ var _gechiui_blocks__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_gechiui_blocks__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _name__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./name */ "./node_modules/@gechiui/core-data/build-module/name.js");
/* harmony import */ var _entities__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./entities */ "./node_modules/@gechiui/core-data/build-module/entities.js");


/**
 * GeChiUI dependencies
 */



/**
 * Internal dependencies
 */


const EMPTY_ARRAY = [];
/**
 * Internal dependencies
 */


const entities = { ..._entities__WEBPACK_IMPORTED_MODULE_4__["defaultEntities"].reduce((acc, entity) => {
    if (!acc[entity.kind]) {
      acc[entity.kind] = {};
    }

    acc[entity.kind][entity.name] = {
      context: Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_0__["createContext"])()
    };
    return acc;
  }, {}),
  ..._entities__WEBPACK_IMPORTED_MODULE_4__["kinds"].reduce((acc, kind) => {
    acc[kind.name] = {};
    return acc;
  }, {})
};

const getEntity = (kind, type) => {
  if (!entities[kind]) {
    throw new Error(`Missing entity config for kind: ${kind}.`);
  }

  if (!entities[kind][type]) {
    entities[kind][type] = {
      context: Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_0__["createContext"])()
    };
  }

  return entities[kind][type];
};
/**
 * Context provider component for providing
 * an entity for a specific entity type.
 *
 * @param {Object} props          The component's props.
 * @param {string} props.kind     The entity kind.
 * @param {string} props.type     The entity type.
 * @param {number} props.id       The entity ID.
 * @param {*}      props.children The children to wrap.
 *
 * @return {Object} The provided children, wrapped with
 *                   the entity's context provider.
 */


function EntityProvider(_ref) {
  let {
    kind,
    type,
    id,
    children
  } = _ref;
  const Provider = getEntity(kind, type).context.Provider;
  return Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(Provider, {
    value: id
  }, children);
}
/**
 * Hook that returns the ID for the nearest
 * provided entity of the specified type.
 *
 * @param {string} kind The entity kind.
 * @param {string} type The entity type.
 */

function useEntityId(kind, type) {
  return Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_0__["useContext"])(getEntity(kind, type).context);
}
/**
 * Hook that returns the value and a setter for the
 * specified property of the nearest provided
 * entity of the specified type.
 *
 * @param {string} kind  The entity kind.
 * @param {string} type  The entity type.
 * @param {string} prop  The property name.
 * @param {string} [_id] An entity ID to use instead of the context-provided one.
 *
 * @return {[*, Function, *]} An array where the first item is the
 *                            property value, the second is the
 *                            setter and the third is the full value
 * 							  object from REST API containing more
 * 							  information like `raw`, `rendered` and
 * 							  `protected` props.
 */

function useEntityProp(kind, type, prop, _id) {
  const providerId = useEntityId(kind, type);
  const id = _id !== null && _id !== void 0 ? _id : providerId;
  const {
    value,
    fullValue
  } = Object(_gechiui_data__WEBPACK_IMPORTED_MODULE_1__["useSelect"])(select => {
    const {
      getEntityRecord,
      getEditedEntityRecord
    } = select(_name__WEBPACK_IMPORTED_MODULE_3__["STORE_NAME"]);
    const entity = getEntityRecord(kind, type, id); // Trigger resolver.

    const editedEntity = getEditedEntityRecord(kind, type, id);
    return entity && editedEntity ? {
      value: editedEntity[prop],
      fullValue: entity[prop]
    } : {};
  }, [kind, type, id, prop]);
  const {
    editEntityRecord
  } = Object(_gechiui_data__WEBPACK_IMPORTED_MODULE_1__["useDispatch"])(_name__WEBPACK_IMPORTED_MODULE_3__["STORE_NAME"]);
  const setValue = Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_0__["useCallback"])(newValue => {
    editEntityRecord(kind, type, id, {
      [prop]: newValue
    });
  }, [kind, type, id, prop]);
  return [value, setValue, fullValue];
}
/**
 * Hook that returns block content getters and setters for
 * the nearest provided entity of the specified type.
 *
 * The return value has the shape `[ blocks, onInput, onChange ]`.
 * `onInput` is for block changes that don't create undo levels
 * or dirty the post, non-persistent changes, and `onChange` is for
 * peristent changes. They map directly to the props of a
 * `BlockEditorProvider` and are intended to be used with it,
 * or similar components or hooks.
 *
 * @param {string} kind         The entity kind.
 * @param {string} type         The entity type.
 * @param {Object} options
 * @param {string} [options.id] An entity ID to use instead of the context-provided one.
 *
 * @return {[GCBlock[], Function, Function]} The block array and setters.
 */

function useEntityBlockEditor(kind, type) {
  let {
    id: _id
  } = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
  const providerId = useEntityId(kind, type);
  const id = _id !== null && _id !== void 0 ? _id : providerId;
  const {
    content,
    blocks
  } = Object(_gechiui_data__WEBPACK_IMPORTED_MODULE_1__["useSelect"])(select => {
    const {
      getEditedEntityRecord
    } = select(_name__WEBPACK_IMPORTED_MODULE_3__["STORE_NAME"]);
    const editedEntity = getEditedEntityRecord(kind, type, id);
    return {
      blocks: editedEntity.blocks,
      content: editedEntity.content
    };
  }, [kind, type, id]);
  const {
    __unstableCreateUndoLevel,
    editEntityRecord
  } = Object(_gechiui_data__WEBPACK_IMPORTED_MODULE_1__["useDispatch"])(_name__WEBPACK_IMPORTED_MODULE_3__["STORE_NAME"]);
  Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_0__["useEffect"])(() => {
    // Load the blocks from the content if not already in state
    // Guard against other instances that might have
    // set content to a function already or the blocks are already in state.
    if (content && typeof content !== 'function' && !blocks) {
      const parsedContent = Object(_gechiui_blocks__WEBPACK_IMPORTED_MODULE_2__["parse"])(content);
      editEntityRecord(kind, type, id, {
        blocks: parsedContent
      }, {
        undoIgnore: true
      });
    }
  }, [content]);
  const onChange = Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_0__["useCallback"])((newBlocks, options) => {
    const {
      selection
    } = options;
    const edits = {
      blocks: newBlocks,
      selection
    };
    const noChange = blocks === edits.blocks;

    if (noChange) {
      return __unstableCreateUndoLevel(kind, type, id);
    } // We create a new function here on every persistent edit
    // to make sure the edit makes the post dirty and creates
    // a new undo level.


    edits.content = _ref2 => {
      let {
        blocks: blocksForSerialization = []
      } = _ref2;
      return Object(_gechiui_blocks__WEBPACK_IMPORTED_MODULE_2__["__unstableSerializeAndClean"])(blocksForSerialization);
    };

    editEntityRecord(kind, type, id, edits);
  }, [kind, type, id, blocks]);
  const onInput = Object(_gechiui_element__WEBPACK_IMPORTED_MODULE_0__["useCallback"])((newBlocks, options) => {
    const {
      selection
    } = options;
    const edits = {
      blocks: newBlocks,
      selection
    };
    editEntityRecord(kind, type, id, edits);
  }, [kind, type, id]);
  return [blocks !== null && blocks !== void 0 ? blocks : EMPTY_ARRAY, onInput, onChange];
}


/***/ }),

/***/ "./node_modules/@gechiui/core-data/build-module/fetch/__experimental-fetch-link-suggestions.js":
/*!*****************************************************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/fetch/__experimental-fetch-link-suggestions.js ***!
  \*****************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @gechiui/api-fetch */ "@gechiui/api-fetch");
/* harmony import */ var _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _gechiui_url__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @gechiui/url */ "@gechiui/url");
/* harmony import */ var _gechiui_url__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_gechiui_url__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _gechiui_html_entities__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @gechiui/html-entities */ "@gechiui/html-entities");
/* harmony import */ var _gechiui_html_entities__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_gechiui_html_entities__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _gechiui_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @gechiui/i18n */ "@gechiui/i18n");
/* harmony import */ var _gechiui_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_3__);
/**
 * GeChiUI dependencies
 */




/**
 * Filters the search by type
 *
 * @typedef { 'post' | 'term' | 'post-format' } GCLinkSearchType
 */

/**
 * A link with an id may be of kind post-type or taxonomy
 *
 * @typedef { 'post-type' | 'taxonomy' } GCKind
 */

/**
 * @typedef GCLinkSearchOptions
 *
 * @property {boolean}          [isInitialSuggestions] Displays initial search suggestions, when true.
 * @property {GCLinkSearchType} [type]                 Filters by search type.
 * @property {string}           [subtype]              Slug of the post-type or taxonomy.
 * @property {number}           [page]                 Which page of results to return.
 * @property {number}           [perPage]              Search results per page.
 */

/**
 * @typedef GCLinkSearchResult
 *
 * @property {number} id     Post or term id.
 * @property {string} url    Link url.
 * @property {string} title  Title of the link.
 * @property {string} type   The taxonomy or post type slug or type URL.
 * @property {GCKind} [kind] Link kind of post-type or taxonomy
 */

/**
 * @typedef GCEditorSettings
 *
 * @property {boolean} [ disablePostFormats ] Disables post formats, when true.
 */

/**
 * Fetches link suggestions from the API.
 *
 * @async
 * @param {string}              search
 * @param {GCLinkSearchOptions} [searchOptions]
 * @param {GCEditorSettings}    [settings]
 *
 * @example
 * ```js
 * import { __experimentalFetchLinkSuggestions as fetchLinkSuggestions } from '@gechiui/core-data';
 *
 * //...
 *
 * export function initialize( id, settings ) {
 *
 * settings.__experimentalFetchLinkSuggestions = (
 *     search,
 *     searchOptions
 * ) => fetchLinkSuggestions( search, searchOptions, settings );
 * ```
 * @return {Promise< GCLinkSearchResult[] >} List of search suggestions
 */

const fetchLinkSuggestions = async function (search) {
  let searchOptions = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
  let settings = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
  const {
    isInitialSuggestions = false,
    type = undefined,
    subtype = undefined,
    page = undefined,
    perPage = isInitialSuggestions ? 3 : 20
  } = searchOptions;
  const {
    disablePostFormats = false
  } = settings;
  const queries = [];

  if (!type || type === 'post') {
    queries.push(_gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_0___default()({
      path: Object(_gechiui_url__WEBPACK_IMPORTED_MODULE_1__["addQueryArgs"])('/gc/v2/search', {
        search,
        page,
        per_page: perPage,
        type: 'post',
        subtype
      })
    }).then(results => {
      return results.map(result => {
        return { ...result,
          meta: {
            kind: 'post-type',
            subtype
          }
        };
      });
    }).catch(() => []) // fail by returning no results
    );
  }

  if (!type || type === 'term') {
    queries.push(_gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_0___default()({
      path: Object(_gechiui_url__WEBPACK_IMPORTED_MODULE_1__["addQueryArgs"])('/gc/v2/search', {
        search,
        page,
        per_page: perPage,
        type: 'term',
        subtype
      })
    }).then(results => {
      return results.map(result => {
        return { ...result,
          meta: {
            kind: 'taxonomy',
            subtype
          }
        };
      });
    }).catch(() => []));
  }

  if (!disablePostFormats && (!type || type === 'post-format')) {
    queries.push(_gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_0___default()({
      path: Object(_gechiui_url__WEBPACK_IMPORTED_MODULE_1__["addQueryArgs"])('/gc/v2/search', {
        search,
        page,
        per_page: perPage,
        type: 'post-format',
        subtype
      })
    }).then(results => {
      return results.map(result => {
        return { ...result,
          meta: {
            kind: 'taxonomy',
            subtype
          }
        };
      });
    }).catch(() => []));
  }

  return Promise.all(queries).then(results => {
    return results.reduce((accumulator, current) => accumulator.concat(current), //flatten list
    []).filter(
    /**
     * @param {{ id: number }} result
     */
    result => {
      return !!result.id;
    }).slice(0, perPage).map(
    /**
     * @param {{ id: number, url:string, title?:string, subtype?: string, type?: string }} result
     */
    result => {
      var _result$meta;

      return {
        id: result.id,
        url: result.url,
        title: Object(_gechiui_html_entities__WEBPACK_IMPORTED_MODULE_2__["decodeEntities"])(result.title || '') || Object(_gechiui_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('（无标题）'),
        type: result.subtype || result.type,
        kind: result === null || result === void 0 ? void 0 : (_result$meta = result.meta) === null || _result$meta === void 0 ? void 0 : _result$meta.kind
      };
    });
  });
};

/* harmony default export */ __webpack_exports__["default"] = (fetchLinkSuggestions);


/***/ }),

/***/ "./node_modules/@gechiui/core-data/build-module/fetch/__experimental-fetch-url-data.js":
/*!*********************************************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/fetch/__experimental-fetch-url-data.js ***!
  \*********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @gechiui/api-fetch */ "@gechiui/api-fetch");
/* harmony import */ var _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _gechiui_url__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @gechiui/url */ "@gechiui/url");
/* harmony import */ var _gechiui_url__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_gechiui_url__WEBPACK_IMPORTED_MODULE_1__);
/**
 * GeChiUI dependencies
 */


/**
 * A simple in-memory cache for requests.
 * This avoids repeat HTTP requests which may be beneficial
 * for those wishing to preserve low-bandwidth.
 */

const CACHE = new Map();
/**
 * @typedef GCRemoteUrlData
 *
 * @property {string} title contents of the remote URL's `<title>` tag.
 */

/**
 * Fetches data about a remote URL.
 * eg: <title> tag, favicon...etc.
 *
 * @async
 * @param {string}  url     the URL to request details from.
 * @param {Object?} options any options to pass to the underlying fetch.
 * @example
 * ```js
 * import { __experimentalFetchUrlData as fetchUrlData } from '@gechiui/core-data';
 *
 * //...
 *
 * export function initialize( id, settings ) {
 *
 * settings.__experimentalFetchUrlData = (
 * url
 * ) => fetchUrlData( url );
 * ```
 * @return {Promise< GCRemoteUrlData[] >} Remote URL data.
 */

const fetchUrlData = async function (url) {
  let options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
  const endpoint = '/gc-block-editor/v1/url-details';
  const args = {
    url: Object(_gechiui_url__WEBPACK_IMPORTED_MODULE_1__["prependHTTP"])(url)
  };

  if (!Object(_gechiui_url__WEBPACK_IMPORTED_MODULE_1__["isURL"])(url)) {
    return Promise.reject(`${url} is not a valid URL.`);
  } // Test for "http" based URL as it is possible for valid
  // yet unusable URLs such as `tel:123456` to be passed.


  const protocol = Object(_gechiui_url__WEBPACK_IMPORTED_MODULE_1__["getProtocol"])(url);

  if (!Object(_gechiui_url__WEBPACK_IMPORTED_MODULE_1__["isValidProtocol"])(protocol) || !protocol.startsWith('http') || !/^https?:\/\/[^\/\s]/i.test(url)) {
    return Promise.reject(`${url} does not have a valid protocol. URLs must be "http" based`);
  }

  if (CACHE.has(url)) {
    return CACHE.get(url);
  }

  return _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_0___default()({
    path: Object(_gechiui_url__WEBPACK_IMPORTED_MODULE_1__["addQueryArgs"])(endpoint, args),
    ...options
  }).then(res => {
    CACHE.set(url, res);
    return res;
  });
};

/* harmony default export */ __webpack_exports__["default"] = (fetchUrlData);


/***/ }),

/***/ "./node_modules/@gechiui/core-data/build-module/fetch/index.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/fetch/index.js ***!
  \*********************************************************************/
/*! exports provided: __experimentalFetchLinkSuggestions, __experimentalFetchUrlData */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _experimental_fetch_link_suggestions__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./__experimental-fetch-link-suggestions */ "./node_modules/@gechiui/core-data/build-module/fetch/__experimental-fetch-link-suggestions.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "__experimentalFetchLinkSuggestions", function() { return _experimental_fetch_link_suggestions__WEBPACK_IMPORTED_MODULE_0__["default"]; });

/* harmony import */ var _experimental_fetch_url_data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./__experimental-fetch-url-data */ "./node_modules/@gechiui/core-data/build-module/fetch/__experimental-fetch-url-data.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "__experimentalFetchUrlData", function() { return _experimental_fetch_url_data__WEBPACK_IMPORTED_MODULE_1__["default"]; });





/***/ }),

/***/ "./node_modules/@gechiui/core-data/build-module/index.js":
/*!***************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/index.js ***!
  \***************************************************************/
/*! exports provided: store, EntityProvider, useEntityId, useEntityProp, useEntityBlockEditor, __experimentalFetchLinkSuggestions, __experimentalFetchUrlData */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "store", function() { return store; });
/* harmony import */ var _gechiui_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @gechiui/data */ "@gechiui/data");
/* harmony import */ var _gechiui_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_gechiui_data__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _reducer__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./reducer */ "./node_modules/@gechiui/core-data/build-module/reducer.js");
/* harmony import */ var _selectors__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./selectors */ "./node_modules/@gechiui/core-data/build-module/selectors.js");
/* harmony import */ var _actions__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./actions */ "./node_modules/@gechiui/core-data/build-module/actions.js");
/* harmony import */ var _resolvers__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./resolvers */ "./node_modules/@gechiui/core-data/build-module/resolvers.js");
/* harmony import */ var _locks_actions__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./locks/actions */ "./node_modules/@gechiui/core-data/build-module/locks/actions.js");
/* harmony import */ var _entities__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./entities */ "./node_modules/@gechiui/core-data/build-module/entities.js");
/* harmony import */ var _name__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./name */ "./node_modules/@gechiui/core-data/build-module/name.js");
/* harmony import */ var _entity_provider__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./entity-provider */ "./node_modules/@gechiui/core-data/build-module/entity-provider.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "EntityProvider", function() { return _entity_provider__WEBPACK_IMPORTED_MODULE_8__["default"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "useEntityId", function() { return _entity_provider__WEBPACK_IMPORTED_MODULE_8__["useEntityId"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "useEntityProp", function() { return _entity_provider__WEBPACK_IMPORTED_MODULE_8__["useEntityProp"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "useEntityBlockEditor", function() { return _entity_provider__WEBPACK_IMPORTED_MODULE_8__["useEntityBlockEditor"]; });

/* harmony import */ var _fetch__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./fetch */ "./node_modules/@gechiui/core-data/build-module/fetch/index.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "__experimentalFetchLinkSuggestions", function() { return _fetch__WEBPACK_IMPORTED_MODULE_9__["__experimentalFetchLinkSuggestions"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "__experimentalFetchUrlData", function() { return _fetch__WEBPACK_IMPORTED_MODULE_9__["__experimentalFetchUrlData"]; });

/**
 * GeChiUI dependencies
 */

/**
 * Internal dependencies
 */







 // The entity selectors/resolvers and actions are shortcuts to their generic equivalents
// (getEntityRecord, getEntityRecords, updateEntityRecord, updateEntityRecordss)
// Instead of getEntityRecord, the consumer could use more user-frieldly named selector: getPostType, getTaxonomy...
// The "kind" and the "name" of the entity are combined to generate these shortcuts.

const entitySelectors = _entities__WEBPACK_IMPORTED_MODULE_6__["defaultEntities"].reduce((result, entity) => {
  const {
    kind,
    name
  } = entity;

  result[Object(_entities__WEBPACK_IMPORTED_MODULE_6__["getMethodName"])(kind, name)] = (state, key, query) => _selectors__WEBPACK_IMPORTED_MODULE_2__["getEntityRecord"](state, kind, name, key, query);

  result[Object(_entities__WEBPACK_IMPORTED_MODULE_6__["getMethodName"])(kind, name, 'get', true)] = function (state) {
    for (var _len = arguments.length, args = new Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
      args[_key - 1] = arguments[_key];
    }

    return _selectors__WEBPACK_IMPORTED_MODULE_2__["getEntityRecords"](state, kind, name, ...args);
  };

  return result;
}, {});
const entityResolvers = _entities__WEBPACK_IMPORTED_MODULE_6__["defaultEntities"].reduce((result, entity) => {
  const {
    kind,
    name
  } = entity;

  result[Object(_entities__WEBPACK_IMPORTED_MODULE_6__["getMethodName"])(kind, name)] = (key, query) => _resolvers__WEBPACK_IMPORTED_MODULE_4__["getEntityRecord"](kind, name, key, query);

  const pluralMethodName = Object(_entities__WEBPACK_IMPORTED_MODULE_6__["getMethodName"])(kind, name, 'get', true);

  result[pluralMethodName] = function () {
    for (var _len2 = arguments.length, args = new Array(_len2), _key2 = 0; _key2 < _len2; _key2++) {
      args[_key2] = arguments[_key2];
    }

    return _resolvers__WEBPACK_IMPORTED_MODULE_4__["getEntityRecords"](kind, name, ...args);
  };

  result[pluralMethodName].shouldInvalidate = function (action) {
    for (var _len3 = arguments.length, args = new Array(_len3 > 1 ? _len3 - 1 : 0), _key3 = 1; _key3 < _len3; _key3++) {
      args[_key3 - 1] = arguments[_key3];
    }

    return _resolvers__WEBPACK_IMPORTED_MODULE_4__["getEntityRecords"].shouldInvalidate(action, kind, name, ...args);
  };

  return result;
}, {});
const entityActions = _entities__WEBPACK_IMPORTED_MODULE_6__["defaultEntities"].reduce((result, entity) => {
  const {
    kind,
    name
  } = entity;

  result[Object(_entities__WEBPACK_IMPORTED_MODULE_6__["getMethodName"])(kind, name, 'save')] = key => _actions__WEBPACK_IMPORTED_MODULE_3__["saveEntityRecord"](kind, name, key);

  result[Object(_entities__WEBPACK_IMPORTED_MODULE_6__["getMethodName"])(kind, name, 'delete')] = (key, query) => _actions__WEBPACK_IMPORTED_MODULE_3__["deleteEntityRecord"](kind, name, key, query);

  return result;
}, {});

const storeConfig = () => ({
  reducer: _reducer__WEBPACK_IMPORTED_MODULE_1__["default"],
  actions: { ..._actions__WEBPACK_IMPORTED_MODULE_3__,
    ...entityActions,
    ...Object(_locks_actions__WEBPACK_IMPORTED_MODULE_5__["default"])()
  },
  selectors: { ..._selectors__WEBPACK_IMPORTED_MODULE_2__,
    ...entitySelectors
  },
  resolvers: { ..._resolvers__WEBPACK_IMPORTED_MODULE_4__,
    ...entityResolvers
  },
  __experimentalUseThunks: true
});
/**
 * Store definition for the code data namespace.
 *
 * @see https://github.com/GeChiUI/gutenberg/blob/HEAD/packages/data/README.md#createReduxStore
 *
 * @type {Object}
 */


const store = Object(_gechiui_data__WEBPACK_IMPORTED_MODULE_0__["createReduxStore"])(_name__WEBPACK_IMPORTED_MODULE_7__["STORE_NAME"], storeConfig());
Object(_gechiui_data__WEBPACK_IMPORTED_MODULE_0__["register"])(store);





/***/ }),

/***/ "./node_modules/@gechiui/core-data/build-module/locks/actions.js":
/*!***********************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/locks/actions.js ***!
  \***********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return createLocksActions; });
/* harmony import */ var _engine__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./engine */ "./node_modules/@gechiui/core-data/build-module/locks/engine.js");
/**
 * Internal dependencies
 */

function createLocksActions() {
  const locks = Object(_engine__WEBPACK_IMPORTED_MODULE_0__["default"])();

  function __unstableAcquireStoreLock(store, path, _ref) {
    let {
      exclusive
    } = _ref;
    return () => locks.acquire(store, path, exclusive);
  }

  function __unstableReleaseStoreLock(lock) {
    return () => locks.release(lock);
  }

  return {
    __unstableAcquireStoreLock,
    __unstableReleaseStoreLock
  };
}


/***/ }),

/***/ "./node_modules/@gechiui/core-data/build-module/locks/engine.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/locks/engine.js ***!
  \**********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return createLocks; });
/* harmony import */ var _reducer__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./reducer */ "./node_modules/@gechiui/core-data/build-module/locks/reducer.js");
/* harmony import */ var _selectors__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./selectors */ "./node_modules/@gechiui/core-data/build-module/locks/selectors.js");
/**
 * Internal dependencies
 */


function createLocks() {
  let state = Object(_reducer__WEBPACK_IMPORTED_MODULE_0__["default"])(undefined, {
    type: '@@INIT'
  });

  function processPendingLockRequests() {
    for (const request of Object(_selectors__WEBPACK_IMPORTED_MODULE_1__["getPendingLockRequests"])(state)) {
      const {
        store,
        path,
        exclusive,
        notifyAcquired
      } = request;

      if (Object(_selectors__WEBPACK_IMPORTED_MODULE_1__["isLockAvailable"])(state, store, path, {
        exclusive
      })) {
        const lock = {
          store,
          path,
          exclusive
        };
        state = Object(_reducer__WEBPACK_IMPORTED_MODULE_0__["default"])(state, {
          type: 'GRANT_LOCK_REQUEST',
          lock,
          request
        });
        notifyAcquired(lock);
      }
    }
  }

  function acquire(store, path, exclusive) {
    return new Promise(resolve => {
      state = Object(_reducer__WEBPACK_IMPORTED_MODULE_0__["default"])(state, {
        type: 'ENQUEUE_LOCK_REQUEST',
        request: {
          store,
          path,
          exclusive,
          notifyAcquired: resolve
        }
      });
      processPendingLockRequests();
    });
  }

  function release(lock) {
    state = Object(_reducer__WEBPACK_IMPORTED_MODULE_0__["default"])(state, {
      type: 'RELEASE_LOCK',
      lock
    });
    processPendingLockRequests();
  }

  return {
    acquire,
    release
  };
}


/***/ }),

/***/ "./node_modules/@gechiui/core-data/build-module/locks/reducer.js":
/*!***********************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/locks/reducer.js ***!
  \***********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return locks; });
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./utils */ "./node_modules/@gechiui/core-data/build-module/locks/utils.js");
/**
 * Internal dependencies
 */

const DEFAULT_STATE = {
  requests: [],
  tree: {
    locks: [],
    children: {}
  }
};
/**
 * Reducer returning locks.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */

function locks() {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : DEFAULT_STATE;
  let action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'ENQUEUE_LOCK_REQUEST':
      {
        const {
          request
        } = action;
        return { ...state,
          requests: [request, ...state.requests]
        };
      }

    case 'GRANT_LOCK_REQUEST':
      {
        const {
          lock,
          request
        } = action;
        const {
          store,
          path
        } = request;
        const storePath = [store, ...path];
        const newTree = Object(_utils__WEBPACK_IMPORTED_MODULE_0__["deepCopyLocksTreePath"])(state.tree, storePath);
        const node = Object(_utils__WEBPACK_IMPORTED_MODULE_0__["getNode"])(newTree, storePath);
        node.locks = [...node.locks, lock];
        return { ...state,
          requests: state.requests.filter(r => r !== request),
          tree: newTree
        };
      }

    case 'RELEASE_LOCK':
      {
        const {
          lock
        } = action;
        const storePath = [lock.store, ...lock.path];
        const newTree = Object(_utils__WEBPACK_IMPORTED_MODULE_0__["deepCopyLocksTreePath"])(state.tree, storePath);
        const node = Object(_utils__WEBPACK_IMPORTED_MODULE_0__["getNode"])(newTree, storePath);
        node.locks = node.locks.filter(l => l !== lock);
        return { ...state,
          tree: newTree
        };
      }
  }

  return state;
}


/***/ }),

/***/ "./node_modules/@gechiui/core-data/build-module/locks/selectors.js":
/*!*************************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/locks/selectors.js ***!
  \*************************************************************************/
/*! exports provided: getPendingLockRequests, isLockAvailable */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getPendingLockRequests", function() { return getPendingLockRequests; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "isLockAvailable", function() { return isLockAvailable; });
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./utils */ "./node_modules/@gechiui/core-data/build-module/locks/utils.js");
/**
 * Internal dependencies
 */

function getPendingLockRequests(state) {
  return state.requests;
}
function isLockAvailable(state, store, path, _ref) {
  let {
    exclusive
  } = _ref;
  const storePath = [store, ...path];
  const locks = state.tree; // Validate all parents and the node itself

  for (const node of Object(_utils__WEBPACK_IMPORTED_MODULE_0__["iteratePath"])(locks, storePath)) {
    if (Object(_utils__WEBPACK_IMPORTED_MODULE_0__["hasConflictingLock"])({
      exclusive
    }, node.locks)) {
      return false;
    }
  } // iteratePath terminates early if path is unreachable, let's
  // re-fetch the node and check it exists in the tree.


  const node = Object(_utils__WEBPACK_IMPORTED_MODULE_0__["getNode"])(locks, storePath);

  if (!node) {
    return true;
  } // Validate all nested nodes


  for (const descendant of Object(_utils__WEBPACK_IMPORTED_MODULE_0__["iterateDescendants"])(node)) {
    if (Object(_utils__WEBPACK_IMPORTED_MODULE_0__["hasConflictingLock"])({
      exclusive
    }, descendant.locks)) {
      return false;
    }
  }

  return true;
}


/***/ }),

/***/ "./node_modules/@gechiui/core-data/build-module/locks/utils.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/locks/utils.js ***!
  \*********************************************************************/
/*! exports provided: deepCopyLocksTreePath, getNode, iteratePath, iterateDescendants, hasConflictingLock */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "deepCopyLocksTreePath", function() { return deepCopyLocksTreePath; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getNode", function() { return getNode; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "iteratePath", function() { return iteratePath; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "iterateDescendants", function() { return iterateDescendants; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "hasConflictingLock", function() { return hasConflictingLock; });
function deepCopyLocksTreePath(tree, path) {
  const newTree = { ...tree
  };
  let currentNode = newTree;

  for (const branchName of path) {
    currentNode.children = { ...currentNode.children,
      [branchName]: {
        locks: [],
        children: {},
        ...currentNode.children[branchName]
      }
    };
    currentNode = currentNode.children[branchName];
  }

  return newTree;
}
function getNode(tree, path) {
  let currentNode = tree;

  for (const branchName of path) {
    const nextNode = currentNode.children[branchName];

    if (!nextNode) {
      return null;
    }

    currentNode = nextNode;
  }

  return currentNode;
}
function* iteratePath(tree, path) {
  let currentNode = tree;
  yield currentNode;

  for (const branchName of path) {
    const nextNode = currentNode.children[branchName];

    if (!nextNode) {
      break;
    }

    yield nextNode;
    currentNode = nextNode;
  }
}
function* iterateDescendants(node) {
  const stack = Object.values(node.children);

  while (stack.length) {
    const childNode = stack.pop();
    yield childNode;
    stack.push(...Object.values(childNode.children));
  }
}
function hasConflictingLock(_ref, locks) {
  let {
    exclusive
  } = _ref;

  if (exclusive && locks.length) {
    return true;
  }

  if (!exclusive && locks.filter(lock => lock.exclusive).length) {
    return true;
  }

  return false;
}


/***/ }),

/***/ "./node_modules/@gechiui/core-data/build-module/name.js":
/*!**************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/name.js ***!
  \**************************************************************/
/*! exports provided: STORE_NAME */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "STORE_NAME", function() { return STORE_NAME; });
/**
 * The reducer key used by core data in store registration.
 * This is defined in a separate file to avoid cycle-dependency
 *
 * @type {string}
 */
const STORE_NAME = 'core';


/***/ }),

/***/ "./node_modules/@gechiui/core-data/build-module/queried-data/actions.js":
/*!******************************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/queried-data/actions.js ***!
  \******************************************************************************/
/*! exports provided: receiveItems, removeItems, receiveQueriedItems */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "receiveItems", function() { return receiveItems; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "removeItems", function() { return removeItems; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "receiveQueriedItems", function() { return receiveQueriedItems; });
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_0__);
/**
 * External dependencies
 */

/**
 * Returns an action object used in signalling that items have been received.
 *
 * @param {Array}   items Items received.
 * @param {?Object} edits Optional edits to reset.
 *
 * @return {Object} Action object.
 */

function receiveItems(items, edits) {
  return {
    type: 'RECEIVE_ITEMS',
    items: Object(lodash__WEBPACK_IMPORTED_MODULE_0__["castArray"])(items),
    persistedEdits: edits
  };
}
/**
 * Returns an action object used in signalling that entity records have been
 * deleted and they need to be removed from entities state.
 *
 * @param {string}       kind            Kind of the removed entities.
 * @param {string}       name            Name of the removed entities.
 * @param {Array|number} records         Record IDs of the removed entities.
 * @param {boolean}      invalidateCache Controls whether we want to invalidate the cache.
 * @return {Object} Action object.
 */

function removeItems(kind, name, records) {
  let invalidateCache = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : false;
  return {
    type: 'REMOVE_ITEMS',
    itemIds: Object(lodash__WEBPACK_IMPORTED_MODULE_0__["castArray"])(records),
    kind,
    name,
    invalidateCache
  };
}
/**
 * Returns an action object used in signalling that queried data has been
 * received.
 *
 * @param {Array}   items Queried items received.
 * @param {?Object} query Optional query object.
 * @param {?Object} edits Optional edits to reset.
 *
 * @return {Object} Action object.
 */

function receiveQueriedItems(items) {
  let query = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
  let edits = arguments.length > 2 ? arguments[2] : undefined;
  return { ...receiveItems(items, edits),
    query
  };
}


/***/ }),

/***/ "./node_modules/@gechiui/core-data/build-module/queried-data/get-query-parts.js":
/*!**************************************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/queried-data/get-query-parts.js ***!
  \**************************************************************************************/
/*! exports provided: getQueryParts, default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getQueryParts", function() { return getQueryParts; });
/* harmony import */ var _gechiui_url__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @gechiui/url */ "@gechiui/url");
/* harmony import */ var _gechiui_url__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_gechiui_url__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../utils */ "./node_modules/@gechiui/core-data/build-module/utils/index.js");
/**
 * GeChiUI dependencies
 */

/**
 * Internal dependencies
 */


/**
 * An object of properties describing a specific query.
 *
 * @typedef {Object} GCQueriedDataQueryParts
 *
 * @property {number}      page      The query page (1-based index, default 1).
 * @property {number}      perPage   Items per page for query (default 10).
 * @property {string}      stableKey An encoded stable string of all non-
 *                                   pagination, non-fields query parameters.
 * @property {?(string[])} fields    Target subset of fields to derive from
 *                                   item objects.
 * @property {?(number[])} include   Specific item IDs to include.
 * @property {string}      context   Scope under which the request is made;
 *                                   determines returned fields in response.
 */

/**
 * Given a query object, returns an object of parts, including pagination
 * details (`page` and `perPage`, or default values). All other properties are
 * encoded into a stable (idempotent) `stableKey` value.
 *
 * @param {Object} query Optional query object.
 *
 * @return {GCQueriedDataQueryParts} Query parts.
 */

function getQueryParts(query) {
  /**
   * @type {GCQueriedDataQueryParts}
   */
  const parts = {
    stableKey: '',
    page: 1,
    perPage: 10,
    fields: null,
    include: null,
    context: 'default'
  }; // Ensure stable key by sorting keys. Also more efficient for iterating.

  const keys = Object.keys(query).sort();

  for (let i = 0; i < keys.length; i++) {
    const key = keys[i];
    let value = query[key];

    switch (key) {
      case 'page':
        parts[key] = Number(value);
        break;

      case 'per_page':
        parts.perPage = Number(value);
        break;

      case 'context':
        parts.context = value;
        break;

      default:
        // While in theory, we could exclude "_fields" from the stableKey
        // because two request with different fields have the same results
        // We're not able to ensure that because the server can decide to omit
        // fields from the response even if we explicitely asked for it.
        // Example: Asking for titles in posts without title support.
        if (key === '_fields') {
          parts.fields = Object(_utils__WEBPACK_IMPORTED_MODULE_1__["getNormalizedCommaSeparable"])(value); // Make sure to normalize value for `stableKey`

          value = parts.fields.join();
        } // Two requests with different include values cannot have same results.


        if (key === 'include') {
          parts.include = Object(_utils__WEBPACK_IMPORTED_MODULE_1__["getNormalizedCommaSeparable"])(value).map(Number); // Normalize value for `stableKey`.

          value = parts.include.join();
        } // While it could be any deterministic string, for simplicity's
        // sake mimic querystring encoding for stable key.
        //
        // TODO: For consistency with PHP implementation, addQueryArgs
        // should accept a key value pair, which may optimize its
        // implementation for our use here, vs. iterating an object
        // with only a single key.


        parts.stableKey += (parts.stableKey ? '&' : '') + Object(_gechiui_url__WEBPACK_IMPORTED_MODULE_0__["addQueryArgs"])('', {
          [key]: value
        }).slice(1);
    }
  }

  return parts;
}
/* harmony default export */ __webpack_exports__["default"] = (Object(_utils__WEBPACK_IMPORTED_MODULE_1__["withWeakMapCache"])(getQueryParts));


/***/ }),

/***/ "./node_modules/@gechiui/core-data/build-module/queried-data/index.js":
/*!****************************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/queried-data/index.js ***!
  \****************************************************************************/
/*! exports provided: receiveItems, removeItems, receiveQueriedItems, getQueriedItems, reducer */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _actions__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./actions */ "./node_modules/@gechiui/core-data/build-module/queried-data/actions.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "receiveItems", function() { return _actions__WEBPACK_IMPORTED_MODULE_0__["receiveItems"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "removeItems", function() { return _actions__WEBPACK_IMPORTED_MODULE_0__["removeItems"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "receiveQueriedItems", function() { return _actions__WEBPACK_IMPORTED_MODULE_0__["receiveQueriedItems"]; });

/* harmony import */ var _selectors__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./selectors */ "./node_modules/@gechiui/core-data/build-module/queried-data/selectors.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "getQueriedItems", function() { return _selectors__WEBPACK_IMPORTED_MODULE_1__["getQueriedItems"]; });

/* harmony import */ var _reducer__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./reducer */ "./node_modules/@gechiui/core-data/build-module/queried-data/reducer.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "reducer", function() { return _reducer__WEBPACK_IMPORTED_MODULE_2__["default"]; });






/***/ }),

/***/ "./node_modules/@gechiui/core-data/build-module/queried-data/reducer.js":
/*!******************************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/queried-data/reducer.js ***!
  \******************************************************************************/
/*! exports provided: getMergedItemIds, items, itemIsComplete, default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getMergedItemIds", function() { return getMergedItemIds; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "items", function() { return items; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "itemIsComplete", function() { return itemIsComplete; });
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _gechiui_data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @gechiui/data */ "@gechiui/data");
/* harmony import */ var _gechiui_data__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_gechiui_data__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../utils */ "./node_modules/@gechiui/core-data/build-module/utils/index.js");
/* harmony import */ var _entities__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../entities */ "./node_modules/@gechiui/core-data/build-module/entities.js");
/* harmony import */ var _get_query_parts__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./get-query-parts */ "./node_modules/@gechiui/core-data/build-module/queried-data/get-query-parts.js");
/**
 * External dependencies
 */

/**
 * GeChiUI dependencies
 */


/**
 * Internal dependencies
 */





function getContextFromAction(action) {
  const {
    query
  } = action;

  if (!query) {
    return 'default';
  }

  const queryParts = Object(_get_query_parts__WEBPACK_IMPORTED_MODULE_4__["default"])(query);
  return queryParts.context;
}
/**
 * Returns a merged array of item IDs, given details of the received paginated
 * items. The array is sparse-like with `undefined` entries where holes exist.
 *
 * @param {?Array<number>} itemIds     Original item IDs (default empty array).
 * @param {number[]}       nextItemIds Item IDs to merge.
 * @param {number}         page        Page of items merged.
 * @param {number}         perPage     Number of items per page.
 *
 * @return {number[]} Merged array of item IDs.
 */


function getMergedItemIds(itemIds, nextItemIds, page, perPage) {
  const receivedAllIds = page === 1 && perPage === -1;

  if (receivedAllIds) {
    return nextItemIds;
  }

  const nextItemIdsStartIndex = (page - 1) * perPage; // If later page has already been received, default to the larger known
  // size of the existing array, else calculate as extending the existing.

  const size = Math.max(itemIds.length, nextItemIdsStartIndex + nextItemIds.length); // Preallocate array since size is known.

  const mergedItemIds = new Array(size);

  for (let i = 0; i < size; i++) {
    // Preserve existing item ID except for subset of range of next items.
    const isInNextItemsRange = i >= nextItemIdsStartIndex && i < nextItemIdsStartIndex + nextItemIds.length;
    mergedItemIds[i] = isInNextItemsRange ? nextItemIds[i - nextItemIdsStartIndex] : itemIds[i];
  }

  return mergedItemIds;
}
/**
 * Reducer tracking items state, keyed by ID. Items are assumed to be normal,
 * where identifiers are common across all queries.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Next state.
 */

function items() {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  let action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'RECEIVE_ITEMS':
      {
        const context = getContextFromAction(action);
        const key = action.key || _entities__WEBPACK_IMPORTED_MODULE_3__["DEFAULT_ENTITY_KEY"];
        return { ...state,
          [context]: { ...state[context],
            ...action.items.reduce((accumulator, value) => {
              var _state$context;

              const itemId = value[key];
              accumulator[itemId] = Object(_utils__WEBPACK_IMPORTED_MODULE_2__["conservativeMapItem"])(state === null || state === void 0 ? void 0 : (_state$context = state[context]) === null || _state$context === void 0 ? void 0 : _state$context[itemId], value);
              return accumulator;
            }, {})
          }
        };
      }

    case 'REMOVE_ITEMS':
      return Object(lodash__WEBPACK_IMPORTED_MODULE_0__["mapValues"])(state, contextState => Object(lodash__WEBPACK_IMPORTED_MODULE_0__["omit"])(contextState, action.itemIds));
  }

  return state;
}
/**
 * Reducer tracking item completeness, keyed by ID. A complete item is one for
 * which all fields are known. This is used in supporting `_fields` queries,
 * where not all properties associated with an entity are necessarily returned.
 * In such cases, completeness is used as an indication of whether it would be
 * safe to use queried data for a non-`_fields`-limited request.
 *
 * @param {Object<string,boolean>} state  Current state.
 * @param {Object}                 action Dispatched action.
 *
 * @return {Object<string,boolean>} Next state.
 */

function itemIsComplete() {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  let action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'RECEIVE_ITEMS':
      {
        const context = getContextFromAction(action);
        const {
          query,
          key = _entities__WEBPACK_IMPORTED_MODULE_3__["DEFAULT_ENTITY_KEY"]
        } = action; // An item is considered complete if it is received without an associated
        // fields query. Ideally, this would be implemented in such a way where the
        // complete aggregate of all fields would satisfy completeness. Since the
        // fields are not consistent across all entity types, this would require
        // introspection on the REST schema for each entity to know which fields
        // compose a complete item for that entity.

        const queryParts = query ? Object(_get_query_parts__WEBPACK_IMPORTED_MODULE_4__["default"])(query) : {};
        const isCompleteQuery = !query || !Array.isArray(queryParts.fields);
        return { ...state,
          [context]: { ...state[context],
            ...action.items.reduce((result, item) => {
              var _state$context2;

              const itemId = item[key]; // Defer to completeness if already assigned. Technically the
              // data may be outdated if receiving items for a field subset.

              result[itemId] = (state === null || state === void 0 ? void 0 : (_state$context2 = state[context]) === null || _state$context2 === void 0 ? void 0 : _state$context2[itemId]) || isCompleteQuery;
              return result;
            }, {})
          }
        };
      }

    case 'REMOVE_ITEMS':
      return Object(lodash__WEBPACK_IMPORTED_MODULE_0__["mapValues"])(state, contextState => Object(lodash__WEBPACK_IMPORTED_MODULE_0__["omit"])(contextState, action.itemIds));
  }

  return state;
}
/**
 * Reducer tracking queries state, keyed by stable query key. Each reducer
 * query object includes `itemIds` and `requestingPageByPerPage`.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Next state.
 */

const receiveQueries = Object(lodash__WEBPACK_IMPORTED_MODULE_0__["flowRight"])([// Limit to matching action type so we don't attempt to replace action on
// an unhandled action.
Object(_utils__WEBPACK_IMPORTED_MODULE_2__["ifMatchingAction"])(action => 'query' in action), // Inject query parts into action for use both in `onSubKey` and reducer.
Object(_utils__WEBPACK_IMPORTED_MODULE_2__["replaceAction"])(action => {
  // `ifMatchingAction` still passes on initialization, where state is
  // undefined and a query is not assigned. Avoid attempting to parse
  // parts. `onSubKey` will omit by lack of `stableKey`.
  if (action.query) {
    return { ...action,
      ...Object(_get_query_parts__WEBPACK_IMPORTED_MODULE_4__["default"])(action.query)
    };
  }

  return action;
}), Object(_utils__WEBPACK_IMPORTED_MODULE_2__["onSubKey"])('context'), // Queries shape is shared, but keyed by query `stableKey` part. Original
// reducer tracks only a single query object.
Object(_utils__WEBPACK_IMPORTED_MODULE_2__["onSubKey"])('stableKey')])(function () {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
  let action = arguments.length > 1 ? arguments[1] : undefined;
  const {
    type,
    page,
    perPage,
    key = _entities__WEBPACK_IMPORTED_MODULE_3__["DEFAULT_ENTITY_KEY"]
  } = action;

  if (type !== 'RECEIVE_ITEMS') {
    return state;
  }

  return getMergedItemIds(state || [], Object(lodash__WEBPACK_IMPORTED_MODULE_0__["map"])(action.items, key), page, perPage);
});
/**
 * Reducer tracking queries state.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Next state.
 */

const queries = function () {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  let action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'RECEIVE_ITEMS':
      return receiveQueries(state, action);

    case 'REMOVE_ITEMS':
      const removedItems = action.itemIds.reduce((result, itemId) => {
        result[itemId] = true;
        return result;
      }, {});
      return Object(lodash__WEBPACK_IMPORTED_MODULE_0__["mapValues"])(state, contextQueries => {
        return Object(lodash__WEBPACK_IMPORTED_MODULE_0__["mapValues"])(contextQueries, queryItems => {
          return Object(lodash__WEBPACK_IMPORTED_MODULE_0__["filter"])(queryItems, queryId => {
            return !removedItems[queryId];
          });
        });
      });

    default:
      return state;
  }
};

/* harmony default export */ __webpack_exports__["default"] = (Object(_gechiui_data__WEBPACK_IMPORTED_MODULE_1__["combineReducers"])({
  items,
  itemIsComplete,
  queries
}));


/***/ }),

/***/ "./node_modules/@gechiui/core-data/build-module/queried-data/selectors.js":
/*!********************************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/queried-data/selectors.js ***!
  \********************************************************************************/
/*! exports provided: getQueriedItems */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getQueriedItems", function() { return getQueriedItems; });
/* harmony import */ var rememo__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! rememo */ "./node_modules/rememo/es/rememo.js");
/* harmony import */ var equivalent_key_map__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! equivalent-key-map */ "./node_modules/equivalent-key-map/equivalent-key-map.js");
/* harmony import */ var equivalent_key_map__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(equivalent_key_map__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _get_query_parts__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./get-query-parts */ "./node_modules/@gechiui/core-data/build-module/queried-data/get-query-parts.js");
/**
 * External dependencies
 */



/**
 * Internal dependencies
 */


/**
 * Cache of state keys to EquivalentKeyMap where the inner map tracks queries
 * to their resulting items set. WeakMap allows garbage collection on expired
 * state references.
 *
 * @type {WeakMap<Object,EquivalentKeyMap>}
 */

const queriedItemsCacheByState = new WeakMap();
/**
 * Returns items for a given query, or null if the items are not known.
 *
 * @param {Object}  state State object.
 * @param {?Object} query Optional query.
 *
 * @return {?Array} Query items.
 */

function getQueriedItemsUncached(state, query) {
  var _state$queries, _state$queries$contex;

  const {
    stableKey,
    page,
    perPage,
    include,
    fields,
    context
  } = Object(_get_query_parts__WEBPACK_IMPORTED_MODULE_3__["default"])(query);
  let itemIds;

  if ((_state$queries = state.queries) !== null && _state$queries !== void 0 && (_state$queries$contex = _state$queries[context]) !== null && _state$queries$contex !== void 0 && _state$queries$contex[stableKey]) {
    itemIds = state.queries[context][stableKey];
  }

  if (!itemIds) {
    return null;
  }

  const startOffset = perPage === -1 ? 0 : (page - 1) * perPage;
  const endOffset = perPage === -1 ? itemIds.length : Math.min(startOffset + perPage, itemIds.length);
  const items = [];

  for (let i = startOffset; i < endOffset; i++) {
    var _state$items$context;

    const itemId = itemIds[i];

    if (Array.isArray(include) && !include.includes(itemId)) {
      continue;
    } // Having a target item ID doesn't guarantee that this object has been queried.


    if (!((_state$items$context = state.items[context]) !== null && _state$items$context !== void 0 && _state$items$context.hasOwnProperty(itemId))) {
      return null;
    }

    const item = state.items[context][itemId];
    let filteredItem;

    if (Array.isArray(fields)) {
      filteredItem = {};

      for (let f = 0; f < fields.length; f++) {
        const field = fields[f].split('.');
        const value = Object(lodash__WEBPACK_IMPORTED_MODULE_2__["get"])(item, field);
        Object(lodash__WEBPACK_IMPORTED_MODULE_2__["set"])(filteredItem, field, value);
      }
    } else {
      var _state$itemIsComplete;

      // If expecting a complete item, validate that completeness, or
      // otherwise abort.
      if (!((_state$itemIsComplete = state.itemIsComplete[context]) !== null && _state$itemIsComplete !== void 0 && _state$itemIsComplete[itemId])) {
        return null;
      }

      filteredItem = item;
    }

    items.push(filteredItem);
  }

  return items;
}
/**
 * Returns items for a given query, or null if the items are not known. Caches
 * result both per state (by reference) and per query (by deep equality).
 * The caching approach is intended to be durable to query objects which are
 * deeply but not referentially equal, since otherwise:
 *
 * `getQueriedItems( state, {} ) !== getQueriedItems( state, {} )`
 *
 * @param {Object}  state State object.
 * @param {?Object} query Optional query.
 *
 * @return {?Array} Query items.
 */


const getQueriedItems = Object(rememo__WEBPACK_IMPORTED_MODULE_0__["default"])(function (state) {
  let query = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
  let queriedItemsCache = queriedItemsCacheByState.get(state);

  if (queriedItemsCache) {
    const queriedItems = queriedItemsCache.get(query);

    if (queriedItems !== undefined) {
      return queriedItems;
    }
  } else {
    queriedItemsCache = new equivalent_key_map__WEBPACK_IMPORTED_MODULE_1___default.a();
    queriedItemsCacheByState.set(state, queriedItemsCache);
  }

  const items = getQueriedItemsUncached(state, query);
  queriedItemsCache.set(query, items);
  return items;
});


/***/ }),

/***/ "./node_modules/@gechiui/core-data/build-module/reducer.js":
/*!*****************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/reducer.js ***!
  \*****************************************************************/
/*! exports provided: terms, users, currentUser, taxonomies, currentTheme, currentGlobalStylesId, themeBaseGlobalStyles, entitiesConfig, entities, undo, embedPreviews, userPermissions, autosaves, default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "terms", function() { return terms; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "users", function() { return users; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "currentUser", function() { return currentUser; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "taxonomies", function() { return taxonomies; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "currentTheme", function() { return currentTheme; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "currentGlobalStylesId", function() { return currentGlobalStylesId; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "themeBaseGlobalStyles", function() { return themeBaseGlobalStyles; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "entitiesConfig", function() { return entitiesConfig; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "entities", function() { return entities; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "undo", function() { return undo; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "embedPreviews", function() { return embedPreviews; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "userPermissions", function() { return userPermissions; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "autosaves", function() { return autosaves; });
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _gechiui_data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @gechiui/data */ "@gechiui/data");
/* harmony import */ var _gechiui_data__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_gechiui_data__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _gechiui_is_shallow_equal__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @gechiui/is-shallow-equal */ "@gechiui/is-shallow-equal");
/* harmony import */ var _gechiui_is_shallow_equal__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_gechiui_is_shallow_equal__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./utils */ "./node_modules/@gechiui/core-data/build-module/utils/index.js");
/* harmony import */ var _queried_data__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./queried-data */ "./node_modules/@gechiui/core-data/build-module/queried-data/index.js");
/* harmony import */ var _entities__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./entities */ "./node_modules/@gechiui/core-data/build-module/entities.js");
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
 * Reducer managing terms state. Keyed by taxonomy slug, the value is either
 * undefined (if no request has been made for given taxonomy), null (if a
 * request is in-flight for given taxonomy), or the array of terms for the
 * taxonomy.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */

function terms() {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  let action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'RECEIVE_TERMS':
      return { ...state,
        [action.taxonomy]: action.terms
      };
  }

  return state;
}
/**
 * Reducer managing authors state. Keyed by id.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */

function users() {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {
    byId: {},
    queries: {}
  };
  let action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'RECEIVE_USER_QUERY':
      return {
        byId: { ...state.byId,
          ...Object(lodash__WEBPACK_IMPORTED_MODULE_0__["keyBy"])(action.users, 'id')
        },
        queries: { ...state.queries,
          [action.queryID]: Object(lodash__WEBPACK_IMPORTED_MODULE_0__["map"])(action.users, user => user.id)
        }
      };
  }

  return state;
}
/**
 * Reducer managing current user state.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */

function currentUser() {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  let action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'RECEIVE_CURRENT_USER':
      return action.currentUser;
  }

  return state;
}
/**
 * Reducer managing taxonomies.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */

function taxonomies() {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];
  let action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'RECEIVE_TAXONOMIES':
      return action.taxonomies;
  }

  return state;
}
/**
 * Reducer managing the current theme.
 *
 * @param {string} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {string} Updated state.
 */

function currentTheme() {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : undefined;
  let action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'RECEIVE_CURRENT_THEME':
      return action.currentTheme.stylesheet;
  }

  return state;
}
/**
 * Reducer managing the current global styles id.
 *
 * @param {string} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {string} Updated state.
 */

function currentGlobalStylesId() {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : undefined;
  let action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'RECEIVE_CURRENT_GLOBAL_STYLES_ID':
      return action.id;
  }

  return state;
}
/**
 * Reducer managing the theme base global styles.
 *
 * @param {string} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {string} Updated state.
 */

function themeBaseGlobalStyles() {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  let action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'RECEIVE_THEME_GLOBAL_STYLES':
      return { ...state,
        [action.stylesheet]: action.globalStyles
      };
  }

  return state;
}
/**
 * Higher Order Reducer for a given entity config. It supports:
 *
 *  - Fetching
 *  - Editing
 *  - Saving
 *
 * @param {Object} entityConfig Entity config.
 *
 * @return {Function} Reducer.
 */

function entity(entityConfig) {
  return Object(lodash__WEBPACK_IMPORTED_MODULE_0__["flowRight"])([// Limit to matching action type so we don't attempt to replace action on
  // an unhandled action.
  Object(_utils__WEBPACK_IMPORTED_MODULE_3__["ifMatchingAction"])(action => action.name && action.kind && action.name === entityConfig.name && action.kind === entityConfig.kind), // Inject the entity config into the action.
  Object(_utils__WEBPACK_IMPORTED_MODULE_3__["replaceAction"])(action => {
    return { ...action,
      key: entityConfig.key || _entities__WEBPACK_IMPORTED_MODULE_5__["DEFAULT_ENTITY_KEY"]
    };
  })])(Object(_gechiui_data__WEBPACK_IMPORTED_MODULE_1__["combineReducers"])({
    queriedData: _queried_data__WEBPACK_IMPORTED_MODULE_4__["reducer"],
    edits: function () {
      var _action$query$context, _action$query;

      let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
      let action = arguments.length > 1 ? arguments[1] : undefined;

      switch (action.type) {
        case 'RECEIVE_ITEMS':
          const context = (_action$query$context = action === null || action === void 0 ? void 0 : (_action$query = action.query) === null || _action$query === void 0 ? void 0 : _action$query.context) !== null && _action$query$context !== void 0 ? _action$query$context : 'default';

          if (context !== 'default') {
            return state;
          }

          const nextState = { ...state
          };

          for (const record of action.items) {
            const recordId = record[action.key];
            const edits = nextState[recordId];

            if (!edits) {
              continue;
            }

            const nextEdits = Object.keys(edits).reduce((acc, key) => {
              // If the edited value is still different to the persisted value,
              // keep the edited value in edits.
              if ( // Edits are the "raw" attribute values, but records may have
              // objects with more properties, so we use `get` here for the
              // comparison.
              !Object(lodash__WEBPACK_IMPORTED_MODULE_0__["isEqual"])(edits[key], Object(lodash__WEBPACK_IMPORTED_MODULE_0__["get"])(record[key], 'raw', record[key])) && ( // Sometimes the server alters the sent value which means
              // we need to also remove the edits before the api request.
              !action.persistedEdits || !Object(lodash__WEBPACK_IMPORTED_MODULE_0__["isEqual"])(edits[key], action.persistedEdits[key]))) {
                acc[key] = edits[key];
              }

              return acc;
            }, {});

            if (Object.keys(nextEdits).length) {
              nextState[recordId] = nextEdits;
            } else {
              delete nextState[recordId];
            }
          }

          return nextState;

        case 'EDIT_ENTITY_RECORD':
          const nextEdits = { ...state[action.recordId],
            ...action.edits
          };
          Object.keys(nextEdits).forEach(key => {
            // Delete cleared edits so that the properties
            // are not considered dirty.
            if (nextEdits[key] === undefined) {
              delete nextEdits[key];
            }
          });
          return { ...state,
            [action.recordId]: nextEdits
          };
      }

      return state;
    },
    saving: function () {
      let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
      let action = arguments.length > 1 ? arguments[1] : undefined;

      switch (action.type) {
        case 'SAVE_ENTITY_RECORD_START':
        case 'SAVE_ENTITY_RECORD_FINISH':
          return { ...state,
            [action.recordId]: {
              pending: action.type === 'SAVE_ENTITY_RECORD_START',
              error: action.error,
              isAutosave: action.isAutosave
            }
          };
      }

      return state;
    },
    deleting: function () {
      let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
      let action = arguments.length > 1 ? arguments[1] : undefined;

      switch (action.type) {
        case 'DELETE_ENTITY_RECORD_START':
        case 'DELETE_ENTITY_RECORD_FINISH':
          return { ...state,
            [action.recordId]: {
              pending: action.type === 'DELETE_ENTITY_RECORD_START',
              error: action.error
            }
          };
      }

      return state;
    }
  }));
}
/**
 * Reducer keeping track of the registered entities.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */


function entitiesConfig() {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : _entities__WEBPACK_IMPORTED_MODULE_5__["defaultEntities"];
  let action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'ADD_ENTITIES':
      return [...state, ...action.entities];
  }

  return state;
}
/**
 * Reducer keeping track of the registered entities config and data.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */

const entities = function () {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  let action = arguments.length > 1 ? arguments[1] : undefined;
  const newConfig = entitiesConfig(state.config, action); // Generates a dynamic reducer for the entities

  let entitiesDataReducer = state.reducer;

  if (!entitiesDataReducer || newConfig !== state.config) {
    const entitiesByKind = Object(lodash__WEBPACK_IMPORTED_MODULE_0__["groupBy"])(newConfig, 'kind');
    entitiesDataReducer = Object(_gechiui_data__WEBPACK_IMPORTED_MODULE_1__["combineReducers"])(Object.entries(entitiesByKind).reduce((memo, _ref) => {
      let [kind, subEntities] = _ref;
      const kindReducer = Object(_gechiui_data__WEBPACK_IMPORTED_MODULE_1__["combineReducers"])(subEntities.reduce((kindMemo, entityConfig) => ({ ...kindMemo,
        [entityConfig.name]: entity(entityConfig)
      }), {}));
      memo[kind] = kindReducer;
      return memo;
    }, {}));
  }

  const newData = entitiesDataReducer(state.data, action);

  if (newData === state.data && newConfig === state.config && entitiesDataReducer === state.reducer) {
    return state;
  }

  return {
    reducer: entitiesDataReducer,
    data: newData,
    config: newConfig
  };
};
/**
 * Reducer keeping track of entity edit undo history.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */

const UNDO_INITIAL_STATE = [];
UNDO_INITIAL_STATE.offset = 0;
let lastEditAction;
function undo() {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : UNDO_INITIAL_STATE;
  let action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'EDIT_ENTITY_RECORD':
    case 'CREATE_UNDO_LEVEL':
      let isCreateUndoLevel = action.type === 'CREATE_UNDO_LEVEL';
      const isUndoOrRedo = !isCreateUndoLevel && (action.meta.isUndo || action.meta.isRedo);

      if (isCreateUndoLevel) {
        action = lastEditAction;
      } else if (!isUndoOrRedo) {
        // Don't lose the last edit cache if the new one only has transient edits.
        // Transient edits don't create new levels so updating the cache would make
        // us skip an edit later when creating levels explicitly.
        if (Object.keys(action.edits).some(key => !action.transientEdits[key])) {
          lastEditAction = action;
        } else {
          lastEditAction = { ...action,
            edits: { ...(lastEditAction && lastEditAction.edits),
              ...action.edits
            }
          };
        }
      }

      let nextState;

      if (isUndoOrRedo) {
        nextState = [...state];
        nextState.offset = state.offset + (action.meta.isUndo ? -1 : 1);

        if (state.flattenedUndo) {
          // The first undo in a sequence of undos might happen while we have
          // flattened undos in state. If this is the case, we want execution
          // to continue as if we were creating an explicit undo level. This
          // will result in an extra undo level being appended with the flattened
          // undo values.
          // We also have to take into account if the `lastEditAction` had opted out
          // of being tracked in undo history, like the action that persists the latest
          // content right before saving. In that case we have to update the `lastEditAction`
          // to avoid returning early before applying the existing flattened undos.
          isCreateUndoLevel = true;

          if (!lastEditAction.meta.undo) {
            lastEditAction.meta.undo = {
              edits: {}
            };
          }

          action = lastEditAction;
        } else {
          return nextState;
        }
      }

      if (!action.meta.undo) {
        return state;
      } // Transient edits don't create an undo level, but are
      // reachable in the next meaningful edit to which they
      // are merged. They are defined in the entity's config.


      if (!isCreateUndoLevel && !Object.keys(action.edits).some(key => !action.transientEdits[key])) {
        nextState = [...state];
        nextState.flattenedUndo = { ...state.flattenedUndo,
          ...action.edits
        };
        nextState.offset = state.offset;
        return nextState;
      } // Clear potential redos, because this only supports linear history.


      nextState = nextState || state.slice(0, state.offset || undefined);
      nextState.offset = nextState.offset || 0;
      nextState.pop();

      if (!isCreateUndoLevel) {
        nextState.push({
          kind: action.meta.undo.kind,
          name: action.meta.undo.name,
          recordId: action.meta.undo.recordId,
          edits: { ...state.flattenedUndo,
            ...action.meta.undo.edits
          }
        });
      } // When an edit is a function it's an optimization to avoid running some expensive operation.
      // We can't rely on the function references being the same so we opt out of comparing them here.


      const comparisonUndoEdits = Object.values(action.meta.undo.edits).filter(edit => typeof edit !== 'function');
      const comparisonEdits = Object.values(action.edits).filter(edit => typeof edit !== 'function');

      if (!_gechiui_is_shallow_equal__WEBPACK_IMPORTED_MODULE_2___default()(comparisonUndoEdits, comparisonEdits)) {
        nextState.push({
          kind: action.kind,
          name: action.name,
          recordId: action.recordId,
          edits: isCreateUndoLevel ? { ...state.flattenedUndo,
            ...action.edits
          } : action.edits
        });
      }

      return nextState;
  }

  return state;
}
/**
 * Reducer managing embed preview data.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */

function embedPreviews() {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  let action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'RECEIVE_EMBED_PREVIEW':
      const {
        url,
        preview
      } = action;
      return { ...state,
        [url]: preview
      };
  }

  return state;
}
/**
 * State which tracks whether the user can perform an action on a REST
 * resource.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */

function userPermissions() {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  let action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'RECEIVE_USER_PERMISSION':
      return { ...state,
        [action.key]: action.isAllowed
      };
  }

  return state;
}
/**
 * Reducer returning autosaves keyed by their parent's post id.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */

function autosaves() {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  let action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'RECEIVE_AUTOSAVES':
      const {
        postId,
        autosaves: autosavesData
      } = action;
      return { ...state,
        [postId]: autosavesData
      };
  }

  return state;
}
/* harmony default export */ __webpack_exports__["default"] = (Object(_gechiui_data__WEBPACK_IMPORTED_MODULE_1__["combineReducers"])({
  terms,
  users,
  currentTheme,
  currentGlobalStylesId,
  currentUser,
  themeBaseGlobalStyles,
  taxonomies,
  entities,
  undo,
  embedPreviews,
  userPermissions,
  autosaves
}));


/***/ }),

/***/ "./node_modules/@gechiui/core-data/build-module/resolvers.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/resolvers.js ***!
  \*******************************************************************/
/*! exports provided: getAuthors, getCurrentUser, getEntityRecord, getRawEntityRecord, getEditedEntityRecord, getEntityRecords, getCurrentTheme, getThemeSupports, getEmbedPreview, canUser, canUserEditEntityRecord, getAutosaves, getAutosave, __experimentalGetTemplateForLink, __experimentalGetCurrentGlobalStylesId, __experimentalGetCurrentThemeBaseGlobalStyles */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getAuthors", function() { return getAuthors; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getCurrentUser", function() { return getCurrentUser; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getEntityRecord", function() { return getEntityRecord; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getRawEntityRecord", function() { return getRawEntityRecord; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getEditedEntityRecord", function() { return getEditedEntityRecord; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getEntityRecords", function() { return getEntityRecords; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getCurrentTheme", function() { return getCurrentTheme; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getThemeSupports", function() { return getThemeSupports; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getEmbedPreview", function() { return getEmbedPreview; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "canUser", function() { return canUser; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "canUserEditEntityRecord", function() { return canUserEditEntityRecord; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getAutosaves", function() { return getAutosaves; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getAutosave", function() { return getAutosave; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "__experimentalGetTemplateForLink", function() { return __experimentalGetTemplateForLink; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "__experimentalGetCurrentGlobalStylesId", function() { return __experimentalGetCurrentGlobalStylesId; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "__experimentalGetCurrentThemeBaseGlobalStyles", function() { return __experimentalGetCurrentThemeBaseGlobalStyles; });
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _gechiui_url__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @gechiui/url */ "@gechiui/url");
/* harmony import */ var _gechiui_url__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_gechiui_url__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @gechiui/api-fetch */ "@gechiui/api-fetch");
/* harmony import */ var _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _name__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./name */ "./node_modules/@gechiui/core-data/build-module/name.js");
/* harmony import */ var _entities__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./entities */ "./node_modules/@gechiui/core-data/build-module/entities.js");
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./utils */ "./node_modules/@gechiui/core-data/build-module/utils/index.js");
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
 * Requests authors from the REST API.
 *
 * @param {Object|undefined} query Optional object of query parameters to
 *                                 include with request.
 */

const getAuthors = query => async _ref => {
  let {
    dispatch
  } = _ref;
  const path = Object(_gechiui_url__WEBPACK_IMPORTED_MODULE_1__["addQueryArgs"])('/gc/v2/users/?who=authors&per_page=100', query);
  const users = await _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_2___default()({
    path
  });
  dispatch.receiveUserQuery(path, users);
};
/**
 * Requests the current user from the REST API.
 */

const getCurrentUser = () => async _ref2 => {
  let {
    dispatch
  } = _ref2;
  const currentUser = await _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_2___default()({
    path: '/gc/v2/users/me'
  });
  dispatch.receiveCurrentUser(currentUser);
};
/**
 * Requests an entity's record from the REST API.
 *
 * @param {string}           kind  Entity kind.
 * @param {string}           name  Entity name.
 * @param {number|string}    key   Record's key
 * @param {Object|undefined} query Optional object of query parameters to
 *                                 include with request.
 */

const getEntityRecord = function (kind, name) {
  let key = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : '';
  let query = arguments.length > 3 ? arguments[3] : undefined;
  return async _ref3 => {
    let {
      select,
      dispatch
    } = _ref3;
    const entities = await dispatch(Object(_entities__WEBPACK_IMPORTED_MODULE_4__["getKindEntities"])(kind));
    const entity = Object(lodash__WEBPACK_IMPORTED_MODULE_0__["find"])(entities, {
      kind,
      name
    });

    if (!entity || entity !== null && entity !== void 0 && entity.__experimentalNoFetch) {
      return;
    }

    const lock = await dispatch.__unstableAcquireStoreLock(_name__WEBPACK_IMPORTED_MODULE_3__["STORE_NAME"], ['entities', 'data', kind, name, key], {
      exclusive: false
    });

    try {
      if (query !== undefined && query._fields) {
        // If requesting specific fields, items and query association to said
        // records are stored by ID reference. Thus, fields must always include
        // the ID.
        query = { ...query,
          _fields: Object(lodash__WEBPACK_IMPORTED_MODULE_0__["uniq"])([...(Object(_utils__WEBPACK_IMPORTED_MODULE_5__["getNormalizedCommaSeparable"])(query._fields) || []), entity.key || _entities__WEBPACK_IMPORTED_MODULE_4__["DEFAULT_ENTITY_KEY"]]).join()
        };
      } // Disable reason: While true that an early return could leave `path`
      // unused, it's important that path is derived using the query prior to
      // additional query modifications in the condition below, since those
      // modifications are relevant to how the data is tracked in state, and not
      // for how the request is made to the REST API.
      // eslint-disable-next-line @gechiui/no-unused-vars-before-return


      const path = Object(_gechiui_url__WEBPACK_IMPORTED_MODULE_1__["addQueryArgs"])(entity.baseURL + (key ? '/' + key : ''), { ...entity.baseURLParams,
        ...query
      });

      if (query !== undefined) {
        query = { ...query,
          include: [key]
        }; // The resolution cache won't consider query as reusable based on the
        // fields, so it's tested here, prior to initiating the REST request,
        // and without causing `getEntityRecords` resolution to occur.

        const hasRecords = select.hasEntityRecords(kind, name, query);

        if (hasRecords) {
          return;
        }
      }

      const record = await _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_2___default()({
        path
      });
      dispatch.receiveEntityRecords(kind, name, record, query);
    } catch (error) {// We need a way to handle and access REST API errors in state
      // Until then, catching the error ensures the resolver is marked as resolved.
      // See similar implementation in `getEntityRecords()`.
    } finally {
      dispatch.__unstableReleaseStoreLock(lock);
    }
  };
};
/**
 * Requests an entity's record from the REST API.
 */

const getRawEntityRecord = Object(_utils__WEBPACK_IMPORTED_MODULE_5__["forwardResolver"])('getEntityRecord');
/**
 * Requests an entity's record from the REST API.
 */

const getEditedEntityRecord = Object(_utils__WEBPACK_IMPORTED_MODULE_5__["forwardResolver"])('getEntityRecord');
/**
 * Requests the entity's records from the REST API.
 *
 * @param {string}  kind  Entity kind.
 * @param {string}  name  Entity name.
 * @param {Object?} query Query Object.
 */

const getEntityRecords = function (kind, name) {
  let query = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
  return async _ref4 => {
    let {
      dispatch
    } = _ref4;
    const entities = await dispatch(Object(_entities__WEBPACK_IMPORTED_MODULE_4__["getKindEntities"])(kind));
    const entity = Object(lodash__WEBPACK_IMPORTED_MODULE_0__["find"])(entities, {
      kind,
      name
    });

    if (!entity || entity !== null && entity !== void 0 && entity.__experimentalNoFetch) {
      return;
    }

    const lock = await dispatch.__unstableAcquireStoreLock(_name__WEBPACK_IMPORTED_MODULE_3__["STORE_NAME"], ['entities', 'data', kind, name], {
      exclusive: false
    });

    try {
      var _query;

      if (query._fields) {
        // If requesting specific fields, items and query association to said
        // records are stored by ID reference. Thus, fields must always include
        // the ID.
        query = { ...query,
          _fields: Object(lodash__WEBPACK_IMPORTED_MODULE_0__["uniq"])([...(Object(_utils__WEBPACK_IMPORTED_MODULE_5__["getNormalizedCommaSeparable"])(query._fields) || []), entity.key || _entities__WEBPACK_IMPORTED_MODULE_4__["DEFAULT_ENTITY_KEY"]]).join()
        };
      }

      const path = Object(_gechiui_url__WEBPACK_IMPORTED_MODULE_1__["addQueryArgs"])(entity.baseURL, { ...entity.baseURLParams,
        ...query
      });
      let records = Object.values(await _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_2___default()({
        path
      })); // If we request fields but the result doesn't contain the fields,
      // explicitely set these fields as "undefined"
      // that way we consider the query "fullfilled".

      if (query._fields) {
        records = records.map(record => {
          query._fields.split(',').forEach(field => {
            if (!record.hasOwnProperty(field)) {
              record[field] = undefined;
            }
          });

          return record;
        });
      }

      dispatch.receiveEntityRecords(kind, name, records, query); // When requesting all fields, the list of results can be used to
      // resolve the `getEntityRecord` selector in addition to `getEntityRecords`.
      // See https://github.com/GeChiUI/gutenberg/pull/26575

      if (!((_query = query) !== null && _query !== void 0 && _query._fields) && !query.context) {
        const key = entity.key || _entities__WEBPACK_IMPORTED_MODULE_4__["DEFAULT_ENTITY_KEY"];
        const resolutionsArgs = records.filter(record => record[key]).map(record => [kind, name, record[key]]);
        dispatch({
          type: 'START_RESOLUTIONS',
          selectorName: 'getEntityRecord',
          args: resolutionsArgs
        });
        dispatch({
          type: 'FINISH_RESOLUTIONS',
          selectorName: 'getEntityRecord',
          args: resolutionsArgs
        });
      }
    } catch (error) {// We need a way to handle and access REST API errors in state
      // Until then, catching the error ensures the resolver is marked as resolved.
      // See similar implementation in `getEntityRecord()`.
    } finally {
      dispatch.__unstableReleaseStoreLock(lock);
    }
  };
};

getEntityRecords.shouldInvalidate = (action, kind, name) => {
  return (action.type === 'RECEIVE_ITEMS' || action.type === 'REMOVE_ITEMS') && action.invalidateCache && kind === action.kind && name === action.name;
};
/**
 * Requests the current theme.
 */


const getCurrentTheme = () => async _ref5 => {
  let {
    dispatch,
    resolveSelect
  } = _ref5;
  const activeThemes = await resolveSelect.getEntityRecords('root', 'theme', {
    status: 'active'
  });
  dispatch.receiveCurrentTheme(activeThemes[0]);
};
/**
 * Requests theme supports data from the index.
 */

const getThemeSupports = Object(_utils__WEBPACK_IMPORTED_MODULE_5__["forwardResolver"])('getCurrentTheme');
/**
 * Requests a preview from the from the Embed API.
 *
 * @param {string} url URL to get the preview for.
 */

const getEmbedPreview = url => async _ref6 => {
  let {
    dispatch
  } = _ref6;

  try {
    const embedProxyResponse = await _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_2___default()({
      path: Object(_gechiui_url__WEBPACK_IMPORTED_MODULE_1__["addQueryArgs"])('/oembed/1.0/proxy', {
        url
      })
    });
    dispatch.receiveEmbedPreview(url, embedProxyResponse);
  } catch (error) {
    // Embed API 404s if the URL cannot be embedded, so we have to catch the error from the apiRequest here.
    dispatch.receiveEmbedPreview(url, false);
  }
};
/**
 * Checks whether the current user can perform the given action on the given
 * REST resource.
 *
 * @param {string}  action   Action to check. One of: 'create', 'read', 'update',
 *                           'delete'.
 * @param {string}  resource REST resource to check, e.g. 'media' or 'posts'.
 * @param {?string} id       ID of the rest resource to check.
 */

const canUser = (action, resource, id) => async _ref7 => {
  let {
    dispatch
  } = _ref7;
  const methods = {
    create: 'POST',
    read: 'GET',
    update: 'PUT',
    delete: 'DELETE'
  };
  const method = methods[action];

  if (!method) {
    throw new Error(`'${action}' is not a valid action.`);
  }

  const path = id ? `/gc/v2/${resource}/${id}` : `/gc/v2/${resource}`;
  let response;

  try {
    response = await _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_2___default()({
      path,
      // Ideally this would always be an OPTIONS request, but unfortunately there's
      // a bug in the REST API which causes the Allow header to not be sent on
      // OPTIONS requests to /posts/:id routes.
      // https://core.trac.gechiui.com/ticket/45753
      method: id ? 'GET' : 'OPTIONS',
      parse: false
    });
  } catch (error) {
    // Do nothing if our OPTIONS request comes back with an API error (4xx or
    // 5xx). The previously determined isAllowed value will remain in the store.
    return;
  }

  let allowHeader;

  if (Object(lodash__WEBPACK_IMPORTED_MODULE_0__["hasIn"])(response, ['headers', 'get'])) {
    // If the request is fetched using the fetch api, the header can be
    // retrieved using the 'get' method.
    allowHeader = response.headers.get('allow');
  } else {
    // If the request was preloaded server-side and is returned by the
    // preloading middleware, the header will be a simple property.
    allowHeader = Object(lodash__WEBPACK_IMPORTED_MODULE_0__["get"])(response, ['headers', 'Allow'], '');
  }

  const key = Object(lodash__WEBPACK_IMPORTED_MODULE_0__["compact"])([action, resource, id]).join('/');
  const isAllowed = Object(lodash__WEBPACK_IMPORTED_MODULE_0__["includes"])(allowHeader, method);
  dispatch.receiveUserPermission(key, isAllowed);
};
/**
 * Checks whether the current user can perform the given action on the given
 * REST resource.
 *
 * @param {string} kind     Entity kind.
 * @param {string} name     Entity name.
 * @param {string} recordId Record's id.
 */

const canUserEditEntityRecord = (kind, name, recordId) => async _ref8 => {
  let {
    dispatch
  } = _ref8;
  const entities = await dispatch(Object(_entities__WEBPACK_IMPORTED_MODULE_4__["getKindEntities"])(kind));
  const entity = Object(lodash__WEBPACK_IMPORTED_MODULE_0__["find"])(entities, {
    kind,
    name
  });

  if (!entity) {
    return;
  }

  const resource = entity.__unstable_rest_base;
  await dispatch(canUser('update', resource, recordId));
};
/**
 * Request autosave data from the REST API.
 *
 * @param {string} postType The type of the parent post.
 * @param {number} postId   The id of the parent post.
 */

const getAutosaves = (postType, postId) => async _ref9 => {
  let {
    dispatch,
    resolveSelect
  } = _ref9;
  const {
    rest_base: restBase
  } = await resolveSelect.getPostType(postType);
  const autosaves = await _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_2___default()({
    path: `/gc/v2/${restBase}/${postId}/autosaves?context=edit`
  });

  if (autosaves && autosaves.length) {
    dispatch.receiveAutosaves(postId, autosaves);
  }
};
/**
 * Request autosave data from the REST API.
 *
 * This resolver exists to ensure the underlying autosaves are fetched via
 * `getAutosaves` when a call to the `getAutosave` selector is made.
 *
 * @param {string} postType The type of the parent post.
 * @param {number} postId   The id of the parent post.
 */

const getAutosave = (postType, postId) => async _ref10 => {
  let {
    resolveSelect
  } = _ref10;
  await resolveSelect.getAutosaves(postType, postId);
};
/**
 * Retrieve the frontend template used for a given link.
 *
 * @param {string} link Link.
 */

const __experimentalGetTemplateForLink = link => async _ref11 => {
  let {
    dispatch,
    resolveSelect
  } = _ref11;
  // Ideally this should be using an apiFetch call
  // We could potentially do so by adding a "filter" to the `gc_template` end point.
  // Also it seems the returned object is not a regular REST API post type.
  let template;

  try {
    template = await window.fetch(Object(_gechiui_url__WEBPACK_IMPORTED_MODULE_1__["addQueryArgs"])(link, {
      '_gc-find-template': true
    })).then(res => res.json()).then(_ref12 => {
      let {
        data
      } = _ref12;
      return data;
    });
  } catch (e) {// For non-FSE themes, it is possible that this request returns an error.
  }

  if (!template) {
    return;
  }

  const record = await resolveSelect.getEntityRecord('postType', 'gc_template', template.id);

  if (record) {
    dispatch.receiveEntityRecords('postType', 'gc_template', [record], {
      'find-template': link
    });
  }
};

__experimentalGetTemplateForLink.shouldInvalidate = action => {
  return (action.type === 'RECEIVE_ITEMS' || action.type === 'REMOVE_ITEMS') && action.invalidateCache && action.kind === 'postType' && action.name === 'gc_template';
};

const __experimentalGetCurrentGlobalStylesId = () => async _ref13 => {
  let {
    dispatch,
    resolveSelect
  } = _ref13;
  const activeThemes = await resolveSelect.getEntityRecords('root', 'theme', {
    status: 'active'
  });
  const globalStylesURL = Object(lodash__WEBPACK_IMPORTED_MODULE_0__["get"])(activeThemes, [0, '_links', 'gc:user-global-styles', 0, 'href']);

  if (globalStylesURL) {
    const globalStylesObject = await _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_2___default()({
      url: globalStylesURL
    });

    dispatch.__experimentalReceiveCurrentGlobalStylesId(globalStylesObject.id);
  }
};
const __experimentalGetCurrentThemeBaseGlobalStyles = () => async _ref14 => {
  let {
    resolveSelect,
    dispatch
  } = _ref14;
  const currentTheme = await resolveSelect.getCurrentTheme();
  const themeGlobalStyles = await _gechiui_api_fetch__WEBPACK_IMPORTED_MODULE_2___default()({
    path: `/gc/v2/global-styles/themes/${currentTheme.stylesheet}`
  });
  await dispatch.__experimentalReceiveThemeBaseGlobalStyles(currentTheme.stylesheet, themeGlobalStyles);
};


/***/ }),

/***/ "./node_modules/@gechiui/core-data/build-module/selectors.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/selectors.js ***!
  \*******************************************************************/
/*! exports provided: isRequestingEmbedPreview, getAuthors, getCurrentUser, getUserQueryResults, getEntitiesByKind, getEntity, getEntityRecord, __experimentalGetEntityRecordNoResolver, getRawEntityRecord, hasEntityRecords, getEntityRecords, __experimentalGetDirtyEntityRecords, __experimentalGetEntitiesBeingSaved, getEntityRecordEdits, getEntityRecordNonTransientEdits, hasEditsForEntityRecord, getEditedEntityRecord, isAutosavingEntityRecord, isSavingEntityRecord, isDeletingEntityRecord, getLastEntitySaveError, getLastEntityDeleteError, getUndoEdit, getRedoEdit, hasUndo, hasRedo, getCurrentTheme, __experimentalGetCurrentGlobalStylesId, getThemeSupports, getEmbedPreview, isPreviewEmbedFallback, canUser, canUserEditEntityRecord, getAutosaves, getAutosave, hasFetchedAutosaves, getReferenceByDistinctEdits, __experimentalGetTemplateForLink, __experimentalGetCurrentThemeBaseGlobalStyles */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "isRequestingEmbedPreview", function() { return isRequestingEmbedPreview; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getAuthors", function() { return getAuthors; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getCurrentUser", function() { return getCurrentUser; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getUserQueryResults", function() { return getUserQueryResults; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getEntitiesByKind", function() { return getEntitiesByKind; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getEntity", function() { return getEntity; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getEntityRecord", function() { return getEntityRecord; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "__experimentalGetEntityRecordNoResolver", function() { return __experimentalGetEntityRecordNoResolver; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getRawEntityRecord", function() { return getRawEntityRecord; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "hasEntityRecords", function() { return hasEntityRecords; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getEntityRecords", function() { return getEntityRecords; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "__experimentalGetDirtyEntityRecords", function() { return __experimentalGetDirtyEntityRecords; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "__experimentalGetEntitiesBeingSaved", function() { return __experimentalGetEntitiesBeingSaved; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getEntityRecordEdits", function() { return getEntityRecordEdits; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getEntityRecordNonTransientEdits", function() { return getEntityRecordNonTransientEdits; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "hasEditsForEntityRecord", function() { return hasEditsForEntityRecord; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getEditedEntityRecord", function() { return getEditedEntityRecord; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "isAutosavingEntityRecord", function() { return isAutosavingEntityRecord; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "isSavingEntityRecord", function() { return isSavingEntityRecord; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "isDeletingEntityRecord", function() { return isDeletingEntityRecord; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getLastEntitySaveError", function() { return getLastEntitySaveError; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getLastEntityDeleteError", function() { return getLastEntityDeleteError; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getUndoEdit", function() { return getUndoEdit; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getRedoEdit", function() { return getRedoEdit; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "hasUndo", function() { return hasUndo; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "hasRedo", function() { return hasRedo; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getCurrentTheme", function() { return getCurrentTheme; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "__experimentalGetCurrentGlobalStylesId", function() { return __experimentalGetCurrentGlobalStylesId; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getThemeSupports", function() { return getThemeSupports; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getEmbedPreview", function() { return getEmbedPreview; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "isPreviewEmbedFallback", function() { return isPreviewEmbedFallback; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "canUser", function() { return canUser; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "canUserEditEntityRecord", function() { return canUserEditEntityRecord; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getAutosaves", function() { return getAutosaves; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getAutosave", function() { return getAutosave; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "hasFetchedAutosaves", function() { return hasFetchedAutosaves; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getReferenceByDistinctEdits", function() { return getReferenceByDistinctEdits; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "__experimentalGetTemplateForLink", function() { return __experimentalGetTemplateForLink; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "__experimentalGetCurrentThemeBaseGlobalStyles", function() { return __experimentalGetCurrentThemeBaseGlobalStyles; });
/* harmony import */ var rememo__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! rememo */ "./node_modules/rememo/es/rememo.js");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _gechiui_data__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @gechiui/data */ "@gechiui/data");
/* harmony import */ var _gechiui_data__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_gechiui_data__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _gechiui_url__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @gechiui/url */ "@gechiui/url");
/* harmony import */ var _gechiui_url__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_gechiui_url__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _gechiui_deprecated__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @gechiui/deprecated */ "@gechiui/deprecated");
/* harmony import */ var _gechiui_deprecated__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_gechiui_deprecated__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _name__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./name */ "./node_modules/@gechiui/core-data/build-module/name.js");
/* harmony import */ var _queried_data__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./queried-data */ "./node_modules/@gechiui/core-data/build-module/queried-data/index.js");
/* harmony import */ var _entities__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./entities */ "./node_modules/@gechiui/core-data/build-module/entities.js");
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./utils */ "./node_modules/@gechiui/core-data/build-module/utils/index.js");
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
 * Shared reference to an empty object for cases where it is important to avoid
 * returning a new object reference on every invocation, as in a connected or
 * other pure component which performs `shouldComponentUpdate` check on props.
 * This should be used as a last resort, since the normalized data should be
 * maintained by the reducer result in state.
 */

const EMPTY_OBJECT = {};
/**
 * Shared reference to an empty array for cases where it is important to avoid
 * returning a new array reference on every invocation, as in a connected or
 * other pure component which performs `shouldComponentUpdate` check on props.
 * This should be used as a last resort, since the normalized data should be
 * maintained by the reducer result in state.
 *
 * @type {Array}
 */

const EMPTY_ARRAY = [];
/**
 * Returns true if a request is in progress for embed preview data, or false
 * otherwise.
 *
 * @param {Object} state Data state.
 * @param {string} url   URL the preview would be for.
 *
 * @return {boolean} Whether a request is in progress for an embed preview.
 */

const isRequestingEmbedPreview = Object(_gechiui_data__WEBPACK_IMPORTED_MODULE_2__["createRegistrySelector"])(select => (state, url) => {
  return select(_name__WEBPACK_IMPORTED_MODULE_5__["STORE_NAME"]).isResolving('getEmbedPreview', [url]);
});
/**
 * Returns all available authors.
 *
 * @deprecated since 11.3. Callers should use `select( 'core' ).getUsers({ who: 'authors' })` instead.
 *
 * @param {Object}           state Data state.
 * @param {Object|undefined} query Optional object of query parameters to
 *                                 include with request.
 * @return {Array} Authors list.
 */

function getAuthors(state, query) {
  _gechiui_deprecated__WEBPACK_IMPORTED_MODULE_4___default()("select( 'core' ).getAuthors()", {
    since: '5.9',
    alternative: "select( 'core' ).getUsers({ who: 'authors' })"
  });
  const path = Object(_gechiui_url__WEBPACK_IMPORTED_MODULE_3__["addQueryArgs"])('/gc/v2/users/?who=authors&per_page=100', query);
  return getUserQueryResults(state, path);
}
/**
 * Returns the current user.
 *
 * @param {Object} state Data state.
 *
 * @return {Object} Current user object.
 */

function getCurrentUser(state) {
  return state.currentUser;
}
/**
 * Returns all the users returned by a query ID.
 *
 * @param {Object} state   Data state.
 * @param {string} queryID Query ID.
 *
 * @return {Array} Users list.
 */

const getUserQueryResults = Object(rememo__WEBPACK_IMPORTED_MODULE_0__["default"])((state, queryID) => {
  const queryResults = state.users.queries[queryID];
  return Object(lodash__WEBPACK_IMPORTED_MODULE_1__["map"])(queryResults, id => state.users.byId[id]);
}, (state, queryID) => [state.users.queries[queryID], state.users.byId]);
/**
 * Returns whether the entities for the give kind are loaded.
 *
 * @param {Object} state Data state.
 * @param {string} kind  Entity kind.
 *
 * @return {Array<Object>} Array of entities with config matching kind.
 */

function getEntitiesByKind(state, kind) {
  return Object(lodash__WEBPACK_IMPORTED_MODULE_1__["filter"])(state.entities.config, {
    kind
  });
}
/**
 * Returns the entity object given its kind and name.
 *
 * @param {Object} state Data state.
 * @param {string} kind  Entity kind.
 * @param {string} name  Entity name.
 *
 * @return {Object} Entity
 */

function getEntity(state, kind, name) {
  return Object(lodash__WEBPACK_IMPORTED_MODULE_1__["find"])(state.entities.config, {
    kind,
    name
  });
}
/**
 * Returns the Entity's record object by key. Returns `null` if the value is not
 * yet received, undefined if the value entity is known to not exist, or the
 * entity object if it exists and is received.
 *
 * @param {Object}  state State tree
 * @param {string}  kind  Entity kind.
 * @param {string}  name  Entity name.
 * @param {number}  key   Record's key
 * @param {?Object} query Optional query.
 *
 * @return {Object?} Record.
 */

const getEntityRecord = Object(rememo__WEBPACK_IMPORTED_MODULE_0__["default"])((state, kind, name, key, query) => {
  var _query$context, _queriedState$items$c;

  const queriedState = Object(lodash__WEBPACK_IMPORTED_MODULE_1__["get"])(state.entities.data, [kind, name, 'queriedData']);

  if (!queriedState) {
    return undefined;
  }

  const context = (_query$context = query === null || query === void 0 ? void 0 : query.context) !== null && _query$context !== void 0 ? _query$context : 'default';

  if (query === undefined) {
    var _queriedState$itemIsC;

    // If expecting a complete item, validate that completeness.
    if (!((_queriedState$itemIsC = queriedState.itemIsComplete[context]) !== null && _queriedState$itemIsC !== void 0 && _queriedState$itemIsC[key])) {
      return undefined;
    }

    return queriedState.items[context][key];
  }

  const item = (_queriedState$items$c = queriedState.items[context]) === null || _queriedState$items$c === void 0 ? void 0 : _queriedState$items$c[key];

  if (item && query._fields) {
    const filteredItem = {};
    const fields = Object(_utils__WEBPACK_IMPORTED_MODULE_8__["getNormalizedCommaSeparable"])(query._fields);

    for (let f = 0; f < fields.length; f++) {
      const field = fields[f].split('.');
      const value = Object(lodash__WEBPACK_IMPORTED_MODULE_1__["get"])(item, field);
      Object(lodash__WEBPACK_IMPORTED_MODULE_1__["set"])(filteredItem, field, value);
    }

    return filteredItem;
  }

  return item;
}, (state, kind, name, recordId, query) => {
  var _query$context2;

  const context = (_query$context2 = query === null || query === void 0 ? void 0 : query.context) !== null && _query$context2 !== void 0 ? _query$context2 : 'default';
  return [Object(lodash__WEBPACK_IMPORTED_MODULE_1__["get"])(state.entities.data, [kind, name, 'queriedData', 'items', context, recordId]), Object(lodash__WEBPACK_IMPORTED_MODULE_1__["get"])(state.entities.data, [kind, name, 'queriedData', 'itemIsComplete', context, recordId])];
});
/**
 * Returns the Entity's record object by key. Doesn't trigger a resolver nor requests the entity from the API if the entity record isn't available in the local state.
 *
 * @param {Object} state State tree
 * @param {string} kind  Entity kind.
 * @param {string} name  Entity name.
 * @param {number} key   Record's key
 *
 * @return {Object|null} Record.
 */

function __experimentalGetEntityRecordNoResolver(state, kind, name, key) {
  return getEntityRecord(state, kind, name, key);
}
/**
 * Returns the entity's record object by key,
 * with its attributes mapped to their raw values.
 *
 * @param {Object} state State tree.
 * @param {string} kind  Entity kind.
 * @param {string} name  Entity name.
 * @param {number} key   Record's key.
 *
 * @return {Object?} Object with the entity's raw attributes.
 */

const getRawEntityRecord = Object(rememo__WEBPACK_IMPORTED_MODULE_0__["default"])((state, kind, name, key) => {
  const record = getEntityRecord(state, kind, name, key);
  return record && Object.keys(record).reduce((accumulator, _key) => {
    if (Object(_utils__WEBPACK_IMPORTED_MODULE_8__["isRawAttribute"])(getEntity(state, kind, name), _key)) {
      // Because edits are the "raw" attribute values,
      // we return those from record selectors to make rendering,
      // comparisons, and joins with edits easier.
      accumulator[_key] = Object(lodash__WEBPACK_IMPORTED_MODULE_1__["get"])(record[_key], 'raw', record[_key]);
    } else {
      accumulator[_key] = record[_key];
    }

    return accumulator;
  }, {});
}, (state, kind, name, recordId, query) => {
  var _query$context3;

  const context = (_query$context3 = query === null || query === void 0 ? void 0 : query.context) !== null && _query$context3 !== void 0 ? _query$context3 : 'default';
  return [state.entities.config, Object(lodash__WEBPACK_IMPORTED_MODULE_1__["get"])(state.entities.data, [kind, name, 'queriedData', 'items', context, recordId]), Object(lodash__WEBPACK_IMPORTED_MODULE_1__["get"])(state.entities.data, [kind, name, 'queriedData', 'itemIsComplete', context, recordId])];
});
/**
 * Returns true if records have been received for the given set of parameters,
 * or false otherwise.
 *
 * @param {Object}  state State tree
 * @param {string}  kind  Entity kind.
 * @param {string}  name  Entity name.
 * @param {?Object} query Optional terms query.
 *
 * @return {boolean} Whether entity records have been received.
 */

function hasEntityRecords(state, kind, name, query) {
  return Array.isArray(getEntityRecords(state, kind, name, query));
}
/**
 * Returns the Entity's records.
 *
 * @param {Object}  state State tree
 * @param {string}  kind  Entity kind.
 * @param {string}  name  Entity name.
 * @param {?Object} query Optional terms query.
 *
 * @return {?Array} Records.
 */

function getEntityRecords(state, kind, name, query) {
  // Queried data state is prepopulated for all known entities. If this is not
  // assigned for the given parameters, then it is known to not exist. Thus, a
  // return value of an empty array is used instead of `null` (where `null` is
  // otherwise used to represent an unknown state).
  const queriedState = Object(lodash__WEBPACK_IMPORTED_MODULE_1__["get"])(state.entities.data, [kind, name, 'queriedData']);

  if (!queriedState) {
    return EMPTY_ARRAY;
  }

  return Object(_queried_data__WEBPACK_IMPORTED_MODULE_6__["getQueriedItems"])(queriedState, query);
}
/**
 * Returns the  list of dirty entity records.
 *
 * @param {Object} state State tree.
 *
 * @return {[{ title: string, key: string, name: string, kind: string }]} The list of updated records
 */

const __experimentalGetDirtyEntityRecords = Object(rememo__WEBPACK_IMPORTED_MODULE_0__["default"])(state => {
  const {
    entities: {
      data
    }
  } = state;
  const dirtyRecords = [];
  Object.keys(data).forEach(kind => {
    Object.keys(data[kind]).forEach(name => {
      const primaryKeys = Object.keys(data[kind][name].edits).filter(primaryKey => // The entity record must exist (not be deleted),
      // and it must have edits.
      getEntityRecord(state, kind, name, primaryKey) && hasEditsForEntityRecord(state, kind, name, primaryKey));

      if (primaryKeys.length) {
        const entity = getEntity(state, kind, name);
        primaryKeys.forEach(primaryKey => {
          var _entity$getTitle;

          const entityRecord = getEditedEntityRecord(state, kind, name, primaryKey);
          dirtyRecords.push({
            // We avoid using primaryKey because it's transformed into a string
            // when it's used as an object key.
            key: entityRecord[entity.key || _entities__WEBPACK_IMPORTED_MODULE_7__["DEFAULT_ENTITY_KEY"]],
            title: (entity === null || entity === void 0 ? void 0 : (_entity$getTitle = entity.getTitle) === null || _entity$getTitle === void 0 ? void 0 : _entity$getTitle.call(entity, entityRecord)) || '',
            name,
            kind
          });
        });
      }
    });
  });
  return dirtyRecords;
}, state => [state.entities.data]);
/**
 * Returns the list of entities currently being saved.
 *
 * @param {Object} state State tree.
 *
 * @return {[{ title: string, key: string, name: string, kind: string }]} The list of records being saved.
 */

const __experimentalGetEntitiesBeingSaved = Object(rememo__WEBPACK_IMPORTED_MODULE_0__["default"])(state => {
  const {
    entities: {
      data
    }
  } = state;
  const recordsBeingSaved = [];
  Object.keys(data).forEach(kind => {
    Object.keys(data[kind]).forEach(name => {
      const primaryKeys = Object.keys(data[kind][name].saving).filter(primaryKey => isSavingEntityRecord(state, kind, name, primaryKey));

      if (primaryKeys.length) {
        const entity = getEntity(state, kind, name);
        primaryKeys.forEach(primaryKey => {
          var _entity$getTitle2;

          const entityRecord = getEditedEntityRecord(state, kind, name, primaryKey);
          recordsBeingSaved.push({
            // We avoid using primaryKey because it's transformed into a string
            // when it's used as an object key.
            key: entityRecord[entity.key || _entities__WEBPACK_IMPORTED_MODULE_7__["DEFAULT_ENTITY_KEY"]],
            title: (entity === null || entity === void 0 ? void 0 : (_entity$getTitle2 = entity.getTitle) === null || _entity$getTitle2 === void 0 ? void 0 : _entity$getTitle2.call(entity, entityRecord)) || '',
            name,
            kind
          });
        });
      }
    });
  });
  return recordsBeingSaved;
}, state => [state.entities.data]);
/**
 * Returns the specified entity record's edits.
 *
 * @param {Object} state    State tree.
 * @param {string} kind     Entity kind.
 * @param {string} name     Entity name.
 * @param {number} recordId Record ID.
 *
 * @return {Object?} The entity record's edits.
 */

function getEntityRecordEdits(state, kind, name, recordId) {
  return Object(lodash__WEBPACK_IMPORTED_MODULE_1__["get"])(state.entities.data, [kind, name, 'edits', recordId]);
}
/**
 * Returns the specified entity record's non transient edits.
 *
 * Transient edits don't create an undo level, and
 * are not considered for change detection.
 * They are defined in the entity's config.
 *
 * @param {Object} state    State tree.
 * @param {string} kind     Entity kind.
 * @param {string} name     Entity name.
 * @param {number} recordId Record ID.
 *
 * @return {Object?} The entity record's non transient edits.
 */

const getEntityRecordNonTransientEdits = Object(rememo__WEBPACK_IMPORTED_MODULE_0__["default"])((state, kind, name, recordId) => {
  const {
    transientEdits
  } = getEntity(state, kind, name) || {};
  const edits = getEntityRecordEdits(state, kind, name, recordId) || {};

  if (!transientEdits) {
    return edits;
  }

  return Object.keys(edits).reduce((acc, key) => {
    if (!transientEdits[key]) {
      acc[key] = edits[key];
    }

    return acc;
  }, {});
}, (state, kind, name, recordId) => [state.entities.config, Object(lodash__WEBPACK_IMPORTED_MODULE_1__["get"])(state.entities.data, [kind, name, 'edits', recordId])]);
/**
 * Returns true if the specified entity record has edits,
 * and false otherwise.
 *
 * @param {Object} state    State tree.
 * @param {string} kind     Entity kind.
 * @param {string} name     Entity name.
 * @param {number} recordId Record ID.
 *
 * @return {boolean} Whether the entity record has edits or not.
 */

function hasEditsForEntityRecord(state, kind, name, recordId) {
  return isSavingEntityRecord(state, kind, name, recordId) || Object.keys(getEntityRecordNonTransientEdits(state, kind, name, recordId)).length > 0;
}
/**
 * Returns the specified entity record, merged with its edits.
 *
 * @param {Object} state    State tree.
 * @param {string} kind     Entity kind.
 * @param {string} name     Entity name.
 * @param {number} recordId Record ID.
 *
 * @return {Object?} The entity record, merged with its edits.
 */

const getEditedEntityRecord = Object(rememo__WEBPACK_IMPORTED_MODULE_0__["default"])((state, kind, name, recordId) => ({ ...getRawEntityRecord(state, kind, name, recordId),
  ...getEntityRecordEdits(state, kind, name, recordId)
}), (state, kind, name, recordId, query) => {
  var _query$context4;

  const context = (_query$context4 = query === null || query === void 0 ? void 0 : query.context) !== null && _query$context4 !== void 0 ? _query$context4 : 'default';
  return [state.entities.config, Object(lodash__WEBPACK_IMPORTED_MODULE_1__["get"])(state.entities.data, [kind, name, 'queriedData', 'items', context, recordId]), Object(lodash__WEBPACK_IMPORTED_MODULE_1__["get"])(state.entities.data, [kind, name, 'queriedData', 'itemIsComplete', context, recordId]), Object(lodash__WEBPACK_IMPORTED_MODULE_1__["get"])(state.entities.data, [kind, name, 'edits', recordId])];
});
/**
 * Returns true if the specified entity record is autosaving, and false otherwise.
 *
 * @param {Object} state    State tree.
 * @param {string} kind     Entity kind.
 * @param {string} name     Entity name.
 * @param {number} recordId Record ID.
 *
 * @return {boolean} Whether the entity record is autosaving or not.
 */

function isAutosavingEntityRecord(state, kind, name, recordId) {
  const {
    pending,
    isAutosave
  } = Object(lodash__WEBPACK_IMPORTED_MODULE_1__["get"])(state.entities.data, [kind, name, 'saving', recordId], {});
  return Boolean(pending && isAutosave);
}
/**
 * Returns true if the specified entity record is saving, and false otherwise.
 *
 * @param {Object} state    State tree.
 * @param {string} kind     Entity kind.
 * @param {string} name     Entity name.
 * @param {number} recordId Record ID.
 *
 * @return {boolean} Whether the entity record is saving or not.
 */

function isSavingEntityRecord(state, kind, name, recordId) {
  return Object(lodash__WEBPACK_IMPORTED_MODULE_1__["get"])(state.entities.data, [kind, name, 'saving', recordId, 'pending'], false);
}
/**
 * Returns true if the specified entity record is deleting, and false otherwise.
 *
 * @param {Object} state    State tree.
 * @param {string} kind     Entity kind.
 * @param {string} name     Entity name.
 * @param {number} recordId Record ID.
 *
 * @return {boolean} Whether the entity record is deleting or not.
 */

function isDeletingEntityRecord(state, kind, name, recordId) {
  return Object(lodash__WEBPACK_IMPORTED_MODULE_1__["get"])(state.entities.data, [kind, name, 'deleting', recordId, 'pending'], false);
}
/**
 * Returns the specified entity record's last save error.
 *
 * @param {Object} state    State tree.
 * @param {string} kind     Entity kind.
 * @param {string} name     Entity name.
 * @param {number} recordId Record ID.
 *
 * @return {Object?} The entity record's save error.
 */

function getLastEntitySaveError(state, kind, name, recordId) {
  return Object(lodash__WEBPACK_IMPORTED_MODULE_1__["get"])(state.entities.data, [kind, name, 'saving', recordId, 'error']);
}
/**
 * Returns the specified entity record's last delete error.
 *
 * @param {Object} state    State tree.
 * @param {string} kind     Entity kind.
 * @param {string} name     Entity name.
 * @param {number} recordId Record ID.
 *
 * @return {Object?} The entity record's save error.
 */

function getLastEntityDeleteError(state, kind, name, recordId) {
  return Object(lodash__WEBPACK_IMPORTED_MODULE_1__["get"])(state.entities.data, [kind, name, 'deleting', recordId, 'error']);
}
/**
 * Returns the current undo offset for the
 * entity records edits history. The offset
 * represents how many items from the end
 * of the history stack we are at. 0 is the
 * last edit, -1 is the second last, and so on.
 *
 * @param {Object} state State tree.
 *
 * @return {number} The current undo offset.
 */

function getCurrentUndoOffset(state) {
  return state.undo.offset;
}
/**
 * Returns the previous edit from the current undo offset
 * for the entity records edits history, if any.
 *
 * @param {Object} state State tree.
 *
 * @return {Object?} The edit.
 */


function getUndoEdit(state) {
  return state.undo[state.undo.length - 2 + getCurrentUndoOffset(state)];
}
/**
 * Returns the next edit from the current undo offset
 * for the entity records edits history, if any.
 *
 * @param {Object} state State tree.
 *
 * @return {Object?} The edit.
 */

function getRedoEdit(state) {
  return state.undo[state.undo.length + getCurrentUndoOffset(state)];
}
/**
 * Returns true if there is a previous edit from the current undo offset
 * for the entity records edits history, and false otherwise.
 *
 * @param {Object} state State tree.
 *
 * @return {boolean} Whether there is a previous edit or not.
 */

function hasUndo(state) {
  return Boolean(getUndoEdit(state));
}
/**
 * Returns true if there is a next edit from the current undo offset
 * for the entity records edits history, and false otherwise.
 *
 * @param {Object} state State tree.
 *
 * @return {boolean} Whether there is a next edit or not.
 */

function hasRedo(state) {
  return Boolean(getRedoEdit(state));
}
/**
 * Return the current theme.
 *
 * @param {Object} state Data state.
 *
 * @return {Object} The current theme.
 */

function getCurrentTheme(state) {
  return getEntityRecord(state, 'root', 'theme', state.currentTheme);
}
/**
 * Return the ID of the current global styles object.
 *
 * @param {Object} state Data state.
 *
 * @return {string} The current global styles ID.
 */

function __experimentalGetCurrentGlobalStylesId(state) {
  return state.currentGlobalStylesId;
}
/**
 * Return theme supports data in the index.
 *
 * @param {Object} state Data state.
 *
 * @return {*} Index data.
 */

function getThemeSupports(state) {
  var _getCurrentTheme$them, _getCurrentTheme;

  return (_getCurrentTheme$them = (_getCurrentTheme = getCurrentTheme(state)) === null || _getCurrentTheme === void 0 ? void 0 : _getCurrentTheme.theme_supports) !== null && _getCurrentTheme$them !== void 0 ? _getCurrentTheme$them : EMPTY_OBJECT;
}
/**
 * Returns the embed preview for the given URL.
 *
 * @param {Object} state Data state.
 * @param {string} url   Embedded URL.
 *
 * @return {*} Undefined if the preview has not been fetched, otherwise, the preview fetched from the embed preview API.
 */

function getEmbedPreview(state, url) {
  return state.embedPreviews[url];
}
/**
 * Determines if the returned preview is an oEmbed link fallback.
 *
 * GeChiUI can be configured to return a simple link to a URL if it is not embeddable.
 * We need to be able to determine if a URL is embeddable or not, based on what we
 * get back from the oEmbed preview API.
 *
 * @param {Object} state Data state.
 * @param {string} url   Embedded URL.
 *
 * @return {boolean} Is the preview for the URL an oEmbed link fallback.
 */

function isPreviewEmbedFallback(state, url) {
  const preview = state.embedPreviews[url];
  const oEmbedLinkCheck = '<a href="' + url + '">' + url + '</a>';

  if (!preview) {
    return false;
  }

  return preview.html === oEmbedLinkCheck;
}
/**
 * Returns whether the current user can perform the given action on the given
 * REST resource.
 *
 * Calling this may trigger an OPTIONS request to the REST API via the
 * `canUser()` resolver.
 *
 * https://developer.gechiui.com/rest-api/reference/
 *
 * @param {Object}  state    Data state.
 * @param {string}  action   Action to check. One of: 'create', 'read', 'update', 'delete'.
 * @param {string}  resource REST resource to check, e.g. 'media' or 'posts'.
 * @param {string=} id       Optional ID of the rest resource to check.
 *
 * @return {boolean|undefined} Whether or not the user can perform the action,
 *                             or `undefined` if the OPTIONS request is still being made.
 */

function canUser(state, action, resource, id) {
  const key = Object(lodash__WEBPACK_IMPORTED_MODULE_1__["compact"])([action, resource, id]).join('/');
  return Object(lodash__WEBPACK_IMPORTED_MODULE_1__["get"])(state, ['userPermissions', key]);
}
/**
 * Returns whether the current user can edit the given entity.
 *
 * Calling this may trigger an OPTIONS request to the REST API via the
 * `canUser()` resolver.
 *
 * https://developer.gechiui.com/rest-api/reference/
 *
 * @param {Object} state    Data state.
 * @param {string} kind     Entity kind.
 * @param {string} name     Entity name.
 * @param {string} recordId Record's id.
 * @return {boolean|undefined} Whether or not the user can edit,
 * or `undefined` if the OPTIONS request is still being made.
 */

function canUserEditEntityRecord(state, kind, name, recordId) {
  const entity = getEntity(state, kind, name);

  if (!entity) {
    return false;
  }

  const resource = entity.__unstable_rest_base;
  return canUser(state, 'update', resource, recordId);
}
/**
 * Returns the latest autosaves for the post.
 *
 * May return multiple autosaves since the backend stores one autosave per
 * author for each post.
 *
 * @param {Object} state    State tree.
 * @param {string} postType The type of the parent post.
 * @param {number} postId   The id of the parent post.
 *
 * @return {?Array} An array of autosaves for the post, or undefined if there is none.
 */

function getAutosaves(state, postType, postId) {
  return state.autosaves[postId];
}
/**
 * Returns the autosave for the post and author.
 *
 * @param {Object} state    State tree.
 * @param {string} postType The type of the parent post.
 * @param {number} postId   The id of the parent post.
 * @param {number} authorId The id of the author.
 *
 * @return {?Object} The autosave for the post and author.
 */

function getAutosave(state, postType, postId, authorId) {
  if (authorId === undefined) {
    return;
  }

  const autosaves = state.autosaves[postId];
  return Object(lodash__WEBPACK_IMPORTED_MODULE_1__["find"])(autosaves, {
    author: authorId
  });
}
/**
 * Returns true if the REST request for autosaves has completed.
 *
 * @param {Object} state    State tree.
 * @param {string} postType The type of the parent post.
 * @param {number} postId   The id of the parent post.
 *
 * @return {boolean} True if the REST request was completed. False otherwise.
 */

const hasFetchedAutosaves = Object(_gechiui_data__WEBPACK_IMPORTED_MODULE_2__["createRegistrySelector"])(select => (state, postType, postId) => {
  return select(_name__WEBPACK_IMPORTED_MODULE_5__["STORE_NAME"]).hasFinishedResolution('getAutosaves', [postType, postId]);
});
/**
 * Returns a new reference when edited values have changed. This is useful in
 * inferring where an edit has been made between states by comparison of the
 * return values using strict equality.
 *
 * @example
 *
 * ```
 * const hasEditOccurred = (
 *    getReferenceByDistinctEdits( beforeState ) !==
 *    getReferenceByDistinctEdits( afterState )
 * );
 * ```
 *
 * @param {Object} state Editor state.
 *
 * @return {*} A value whose reference will change only when an edit occurs.
 */

const getReferenceByDistinctEdits = Object(rememo__WEBPACK_IMPORTED_MODULE_0__["default"])(() => [], state => [state.undo.length, state.undo.offset, state.undo.flattenedUndo]);
/**
 * Retrieve the frontend template used for a given link.
 *
 * @param {Object} state Editor state.
 * @param {string} link  Link.
 *
 * @return {Object?} The template record.
 */

function __experimentalGetTemplateForLink(state, link) {
  const records = getEntityRecords(state, 'postType', 'gc_template', {
    'find-template': link
  });
  const template = records !== null && records !== void 0 && records.length ? records[0] : null;

  if (template) {
    return getEditedEntityRecord(state, 'postType', 'gc_template', template.id);
  }

  return template;
}
/**
 * Retrieve the current theme's base global styles
 *
 * @param {Object} state Editor state.
 *
 * @return {Object?} The Global Styles object.
 */

function __experimentalGetCurrentThemeBaseGlobalStyles(state) {
  const currentTheme = getCurrentTheme(state);

  if (!currentTheme) {
    return null;
  }

  return state.themeBaseGlobalStyles[currentTheme.stylesheet];
}


/***/ }),

/***/ "./node_modules/@gechiui/core-data/build-module/utils/conservative-map-item.js":
/*!*************************************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/utils/conservative-map-item.js ***!
  \*************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return conservativeMapItem; });
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_0__);
/**
 * External dependencies
 */

/**
 * Given the current and next item entity, returns the minimally "modified"
 * result of the next item, preferring value references from the original item
 * if equal. If all values match, the original item is returned.
 *
 * @param {Object} item     Original item.
 * @param {Object} nextItem Next item.
 *
 * @return {Object} Minimally modified merged item.
 */

function conservativeMapItem(item, nextItem) {
  // Return next item in its entirety if there is no original item.
  if (!item) {
    return nextItem;
  }

  let hasChanges = false;
  const result = {};

  for (const key in nextItem) {
    if (Object(lodash__WEBPACK_IMPORTED_MODULE_0__["isEqual"])(item[key], nextItem[key])) {
      result[key] = item[key];
    } else {
      hasChanges = true;
      result[key] = nextItem[key];
    }
  }

  if (!hasChanges) {
    return item;
  } // Only at this point, backfill properties from the original item which
  // weren't explicitly set into the result above. This is an optimization
  // to allow `hasChanges` to return early.


  for (const key in item) {
    if (!result.hasOwnProperty(key)) {
      result[key] = item[key];
    }
  }

  return result;
}


/***/ }),

/***/ "./node_modules/@gechiui/core-data/build-module/utils/forward-resolver.js":
/*!********************************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/utils/forward-resolver.js ***!
  \********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/**
 * Higher-order function which forward the resolution to another resolver with the same arguments.
 *
 * @param {string} resolverName forwarded resolver.
 *
 * @return {Function} Enhanced resolver.
 */
const forwardResolver = resolverName => function () {
  for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
    args[_key] = arguments[_key];
  }

  return async _ref => {
    let {
      resolveSelect
    } = _ref;
    await resolveSelect[resolverName](...args);
  };
};

/* harmony default export */ __webpack_exports__["default"] = (forwardResolver);


/***/ }),

/***/ "./node_modules/@gechiui/core-data/build-module/utils/get-normalized-comma-separable.js":
/*!**********************************************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/utils/get-normalized-comma-separable.js ***!
  \**********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/**
 * Given a value which can be specified as one or the other of a comma-separated
 * string or an array, returns a value normalized to an array of strings, or
 * null if the value cannot be interpreted as either.
 *
 * @param {string|string[]|*} value
 *
 * @return {?(string[])} Normalized field value.
 */
function getNormalizedCommaSeparable(value) {
  if (typeof value === 'string') {
    return value.split(',');
  } else if (Array.isArray(value)) {
    return value;
  }

  return null;
}

/* harmony default export */ __webpack_exports__["default"] = (getNormalizedCommaSeparable);


/***/ }),

/***/ "./node_modules/@gechiui/core-data/build-module/utils/if-matching-action.js":
/*!**********************************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/utils/if-matching-action.js ***!
  \**********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/**
 * A higher-order reducer creator which invokes the original reducer only if
 * the dispatching action matches the given predicate, **OR** if state is
 * initializing (undefined).
 *
 * @param {Function} isMatch Function predicate for allowing reducer call.
 *
 * @return {Function} Higher-order reducer.
 */
const ifMatchingAction = isMatch => reducer => (state, action) => {
  if (state === undefined || isMatch(action)) {
    return reducer(state, action);
  }

  return state;
};

/* harmony default export */ __webpack_exports__["default"] = (ifMatchingAction);


/***/ }),

/***/ "./node_modules/@gechiui/core-data/build-module/utils/index.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/utils/index.js ***!
  \*********************************************************************/
/*! exports provided: conservativeMapItem, getNormalizedCommaSeparable, ifMatchingAction, forwardResolver, onSubKey, replaceAction, withWeakMapCache, isRawAttribute */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _conservative_map_item__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./conservative-map-item */ "./node_modules/@gechiui/core-data/build-module/utils/conservative-map-item.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "conservativeMapItem", function() { return _conservative_map_item__WEBPACK_IMPORTED_MODULE_0__["default"]; });

/* harmony import */ var _get_normalized_comma_separable__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./get-normalized-comma-separable */ "./node_modules/@gechiui/core-data/build-module/utils/get-normalized-comma-separable.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "getNormalizedCommaSeparable", function() { return _get_normalized_comma_separable__WEBPACK_IMPORTED_MODULE_1__["default"]; });

/* harmony import */ var _if_matching_action__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./if-matching-action */ "./node_modules/@gechiui/core-data/build-module/utils/if-matching-action.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "ifMatchingAction", function() { return _if_matching_action__WEBPACK_IMPORTED_MODULE_2__["default"]; });

/* harmony import */ var _forward_resolver__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./forward-resolver */ "./node_modules/@gechiui/core-data/build-module/utils/forward-resolver.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "forwardResolver", function() { return _forward_resolver__WEBPACK_IMPORTED_MODULE_3__["default"]; });

/* harmony import */ var _on_sub_key__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./on-sub-key */ "./node_modules/@gechiui/core-data/build-module/utils/on-sub-key.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "onSubKey", function() { return _on_sub_key__WEBPACK_IMPORTED_MODULE_4__["default"]; });

/* harmony import */ var _replace_action__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./replace-action */ "./node_modules/@gechiui/core-data/build-module/utils/replace-action.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "replaceAction", function() { return _replace_action__WEBPACK_IMPORTED_MODULE_5__["default"]; });

/* harmony import */ var _with_weak_map_cache__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./with-weak-map-cache */ "./node_modules/@gechiui/core-data/build-module/utils/with-weak-map-cache.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "withWeakMapCache", function() { return _with_weak_map_cache__WEBPACK_IMPORTED_MODULE_6__["default"]; });

/* harmony import */ var _is_raw_attribute__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./is-raw-attribute */ "./node_modules/@gechiui/core-data/build-module/utils/is-raw-attribute.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "isRawAttribute", function() { return _is_raw_attribute__WEBPACK_IMPORTED_MODULE_7__["default"]; });











/***/ }),

/***/ "./node_modules/@gechiui/core-data/build-module/utils/is-raw-attribute.js":
/*!********************************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/utils/is-raw-attribute.js ***!
  \********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return isRawAttribute; });
/**
 * Checks whether the attribute is a "raw" attribute or not.
 *
 * @param {Object} entity    Entity data.
 * @param {string} attribute Attribute name.
 *
 * @return {boolean} Is the attribute raw
 */
function isRawAttribute(entity, attribute) {
  return (entity.rawAttributes || []).includes(attribute);
}


/***/ }),

/***/ "./node_modules/@gechiui/core-data/build-module/utils/on-sub-key.js":
/*!**************************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/utils/on-sub-key.js ***!
  \**************************************************************************/
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

/***/ "./node_modules/@gechiui/core-data/build-module/utils/replace-action.js":
/*!******************************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/utils/replace-action.js ***!
  \******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/**
 * Higher-order reducer creator which substitutes the action object before
 * passing to the original reducer.
 *
 * @param {Function} replacer Function mapping original action to replacement.
 *
 * @return {Function} Higher-order reducer.
 */
const replaceAction = replacer => reducer => (state, action) => {
  return reducer(state, replacer(action));
};

/* harmony default export */ __webpack_exports__["default"] = (replaceAction);


/***/ }),

/***/ "./node_modules/@gechiui/core-data/build-module/utils/with-weak-map-cache.js":
/*!***********************************************************************************!*\
  !*** ./node_modules/@gechiui/core-data/build-module/utils/with-weak-map-cache.js ***!
  \***********************************************************************************/
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
 * Given a function, returns an enhanced function which caches the result and
 * tracks in WeakMap. The result is only cached if the original function is
 * passed a valid object-like argument (requirement for WeakMap key).
 *
 * @param {Function} fn Original function.
 *
 * @return {Function} Enhanced caching function.
 */

function withWeakMapCache(fn) {
  const cache = new WeakMap();
  return key => {
    let value;

    if (cache.has(key)) {
      value = cache.get(key);
    } else {
      value = fn(key); // Can reach here if key is not valid for WeakMap, since `has`
      // will return false for invalid key. Since `set` will throw,
      // ensure that key is valid before setting into cache.

      if (Object(lodash__WEBPACK_IMPORTED_MODULE_0__["isObjectLike"])(key)) {
        cache.set(key, value);
      }
    }

    return value;
  };
}

/* harmony default export */ __webpack_exports__["default"] = (withWeakMapCache);


/***/ }),

/***/ "./node_modules/equivalent-key-map/equivalent-key-map.js":
/*!***************************************************************!*\
  !*** ./node_modules/equivalent-key-map/equivalent-key-map.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


function _typeof(obj) {
  if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") {
    _typeof = function (obj) {
      return typeof obj;
    };
  } else {
    _typeof = function (obj) {
      return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
    };
  }

  return _typeof(obj);
}

function _classCallCheck(instance, Constructor) {
  if (!(instance instanceof Constructor)) {
    throw new TypeError("Cannot call a class as a function");
  }
}

function _defineProperties(target, props) {
  for (var i = 0; i < props.length; i++) {
    var descriptor = props[i];
    descriptor.enumerable = descriptor.enumerable || false;
    descriptor.configurable = true;
    if ("value" in descriptor) descriptor.writable = true;
    Object.defineProperty(target, descriptor.key, descriptor);
  }
}

function _createClass(Constructor, protoProps, staticProps) {
  if (protoProps) _defineProperties(Constructor.prototype, protoProps);
  if (staticProps) _defineProperties(Constructor, staticProps);
  return Constructor;
}

/**
 * Given an instance of EquivalentKeyMap, returns its internal value pair tuple
 * for a key, if one exists. The tuple members consist of the last reference
 * value for the key (used in efficient subsequent lookups) and the value
 * assigned for the key at the leaf node.
 *
 * @param {EquivalentKeyMap} instance EquivalentKeyMap instance.
 * @param {*} key                     The key for which to return value pair.
 *
 * @return {?Array} Value pair, if exists.
 */
function getValuePair(instance, key) {
  var _map = instance._map,
      _arrayTreeMap = instance._arrayTreeMap,
      _objectTreeMap = instance._objectTreeMap; // Map keeps a reference to the last object-like key used to set the
  // value, which can be used to shortcut immediately to the value.

  if (_map.has(key)) {
    return _map.get(key);
  } // Sort keys to ensure stable retrieval from tree.


  var properties = Object.keys(key).sort(); // Tree by type to avoid conflicts on numeric object keys, empty value.

  var map = Array.isArray(key) ? _arrayTreeMap : _objectTreeMap;

  for (var i = 0; i < properties.length; i++) {
    var property = properties[i];
    map = map.get(property);

    if (map === undefined) {
      return;
    }

    var propertyValue = key[property];
    map = map.get(propertyValue);

    if (map === undefined) {
      return;
    }
  }

  var valuePair = map.get('_ekm_value');

  if (!valuePair) {
    return;
  } // If reached, it implies that an object-like key was set with another
  // reference, so delete the reference and replace with the current.


  _map.delete(valuePair[0]);

  valuePair[0] = key;
  map.set('_ekm_value', valuePair);

  _map.set(key, valuePair);

  return valuePair;
}
/**
 * Variant of a Map object which enables lookup by equivalent (deeply equal)
 * object and array keys.
 */


var EquivalentKeyMap =
/*#__PURE__*/
function () {
  /**
   * Constructs a new instance of EquivalentKeyMap.
   *
   * @param {Iterable.<*>} iterable Initial pair of key, value for map.
   */
  function EquivalentKeyMap(iterable) {
    _classCallCheck(this, EquivalentKeyMap);

    this.clear();

    if (iterable instanceof EquivalentKeyMap) {
      // Map#forEach is only means of iterating with support for IE11.
      var iterablePairs = [];
      iterable.forEach(function (value, key) {
        iterablePairs.push([key, value]);
      });
      iterable = iterablePairs;
    }

    if (iterable != null) {
      for (var i = 0; i < iterable.length; i++) {
        this.set(iterable[i][0], iterable[i][1]);
      }
    }
  }
  /**
   * Accessor property returning the number of elements.
   *
   * @return {number} Number of elements.
   */


  _createClass(EquivalentKeyMap, [{
    key: "set",

    /**
     * Add or update an element with a specified key and value.
     *
     * @param {*} key   The key of the element to add.
     * @param {*} value The value of the element to add.
     *
     * @return {EquivalentKeyMap} Map instance.
     */
    value: function set(key, value) {
      // Shortcut non-object-like to set on internal Map.
      if (key === null || _typeof(key) !== 'object') {
        this._map.set(key, value);

        return this;
      } // Sort keys to ensure stable assignment into tree.


      var properties = Object.keys(key).sort();
      var valuePair = [key, value]; // Tree by type to avoid conflicts on numeric object keys, empty value.

      var map = Array.isArray(key) ? this._arrayTreeMap : this._objectTreeMap;

      for (var i = 0; i < properties.length; i++) {
        var property = properties[i];

        if (!map.has(property)) {
          map.set(property, new EquivalentKeyMap());
        }

        map = map.get(property);
        var propertyValue = key[property];

        if (!map.has(propertyValue)) {
          map.set(propertyValue, new EquivalentKeyMap());
        }

        map = map.get(propertyValue);
      } // If an _ekm_value exists, there was already an equivalent key. Before
      // overriding, ensure that the old key reference is removed from map to
      // avoid memory leak of accumulating equivalent keys. This is, in a
      // sense, a poor man's WeakMap, while still enabling iterability.


      var previousValuePair = map.get('_ekm_value');

      if (previousValuePair) {
        this._map.delete(previousValuePair[0]);
      }

      map.set('_ekm_value', valuePair);

      this._map.set(key, valuePair);

      return this;
    }
    /**
     * Returns a specified element.
     *
     * @param {*} key The key of the element to return.
     *
     * @return {?*} The element associated with the specified key or undefined
     *              if the key can't be found.
     */

  }, {
    key: "get",
    value: function get(key) {
      // Shortcut non-object-like to get from internal Map.
      if (key === null || _typeof(key) !== 'object') {
        return this._map.get(key);
      }

      var valuePair = getValuePair(this, key);

      if (valuePair) {
        return valuePair[1];
      }
    }
    /**
     * Returns a boolean indicating whether an element with the specified key
     * exists or not.
     *
     * @param {*} key The key of the element to test for presence.
     *
     * @return {boolean} Whether an element with the specified key exists.
     */

  }, {
    key: "has",
    value: function has(key) {
      if (key === null || _typeof(key) !== 'object') {
        return this._map.has(key);
      } // Test on the _presence_ of the pair, not its value, as even undefined
      // can be a valid member value for a key.


      return getValuePair(this, key) !== undefined;
    }
    /**
     * Removes the specified element.
     *
     * @param {*} key The key of the element to remove.
     *
     * @return {boolean} Returns true if an element existed and has been
     *                   removed, or false if the element does not exist.
     */

  }, {
    key: "delete",
    value: function _delete(key) {
      if (!this.has(key)) {
        return false;
      } // This naive implementation will leave orphaned child trees. A better
      // implementation should traverse and remove orphans.


      this.set(key, undefined);
      return true;
    }
    /**
     * Executes a provided function once per each key/value pair, in insertion
     * order.
     *
     * @param {Function} callback Function to execute for each element.
     * @param {*}        thisArg  Value to use as `this` when executing
     *                            `callback`.
     */

  }, {
    key: "forEach",
    value: function forEach(callback) {
      var _this = this;

      var thisArg = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : this;

      this._map.forEach(function (value, key) {
        // Unwrap value from object-like value pair.
        if (key !== null && _typeof(key) === 'object') {
          value = value[1];
        }

        callback.call(thisArg, value, key, _this);
      });
    }
    /**
     * Removes all elements.
     */

  }, {
    key: "clear",
    value: function clear() {
      this._map = new Map();
      this._arrayTreeMap = new Map();
      this._objectTreeMap = new Map();
    }
  }, {
    key: "size",
    get: function get() {
      return this._map.size;
    }
  }]);

  return EquivalentKeyMap;
}();

module.exports = EquivalentKeyMap;


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

/***/ "./node_modules/uuid/dist/esm-browser/index.js":
/*!*****************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/index.js ***!
  \*****************************************************/
/*! exports provided: v1, v3, v4, v5, NIL, version, validate, stringify, parse */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _v1_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./v1.js */ "./node_modules/uuid/dist/esm-browser/v1.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "v1", function() { return _v1_js__WEBPACK_IMPORTED_MODULE_0__["default"]; });

/* harmony import */ var _v3_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./v3.js */ "./node_modules/uuid/dist/esm-browser/v3.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "v3", function() { return _v3_js__WEBPACK_IMPORTED_MODULE_1__["default"]; });

/* harmony import */ var _v4_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./v4.js */ "./node_modules/uuid/dist/esm-browser/v4.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "v4", function() { return _v4_js__WEBPACK_IMPORTED_MODULE_2__["default"]; });

/* harmony import */ var _v5_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./v5.js */ "./node_modules/uuid/dist/esm-browser/v5.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "v5", function() { return _v5_js__WEBPACK_IMPORTED_MODULE_3__["default"]; });

/* harmony import */ var _nil_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./nil.js */ "./node_modules/uuid/dist/esm-browser/nil.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "NIL", function() { return _nil_js__WEBPACK_IMPORTED_MODULE_4__["default"]; });

/* harmony import */ var _version_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./version.js */ "./node_modules/uuid/dist/esm-browser/version.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "version", function() { return _version_js__WEBPACK_IMPORTED_MODULE_5__["default"]; });

/* harmony import */ var _validate_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./validate.js */ "./node_modules/uuid/dist/esm-browser/validate.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "validate", function() { return _validate_js__WEBPACK_IMPORTED_MODULE_6__["default"]; });

/* harmony import */ var _stringify_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./stringify.js */ "./node_modules/uuid/dist/esm-browser/stringify.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "stringify", function() { return _stringify_js__WEBPACK_IMPORTED_MODULE_7__["default"]; });

/* harmony import */ var _parse_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./parse.js */ "./node_modules/uuid/dist/esm-browser/parse.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "parse", function() { return _parse_js__WEBPACK_IMPORTED_MODULE_8__["default"]; });











/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/md5.js":
/*!***************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/md5.js ***!
  \***************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/*
 * Browser-compatible JavaScript MD5
 *
 * Modification of JavaScript MD5
 * https://github.com/blueimp/JavaScript-MD5
 *
 * Copyright 2011, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * https://opensource.org/licenses/MIT
 *
 * Based on
 * A JavaScript implementation of the RSA Data Security, Inc. MD5 Message
 * Digest Algorithm, as defined in RFC 1321.
 * Version 2.2 Copyright (C) Paul Johnston 1999 - 2009
 * Other contributors: Greg Holt, Andrew Kepert, Ydnar, Lostinet
 * Distributed under the BSD License
 * See http://pajhome.org.uk/crypt/md5 for more info.
 */
function md5(bytes) {
  if (typeof bytes === 'string') {
    var msg = unescape(encodeURIComponent(bytes)); // UTF8 escape

    bytes = new Uint8Array(msg.length);

    for (var i = 0; i < msg.length; ++i) {
      bytes[i] = msg.charCodeAt(i);
    }
  }

  return md5ToHexEncodedArray(wordsToMd5(bytesToWords(bytes), bytes.length * 8));
}
/*
 * Convert an array of little-endian words to an array of bytes
 */


function md5ToHexEncodedArray(input) {
  var output = [];
  var length32 = input.length * 32;
  var hexTab = '0123456789abcdef';

  for (var i = 0; i < length32; i += 8) {
    var x = input[i >> 5] >>> i % 32 & 0xff;
    var hex = parseInt(hexTab.charAt(x >>> 4 & 0x0f) + hexTab.charAt(x & 0x0f), 16);
    output.push(hex);
  }

  return output;
}
/**
 * Calculate output length with padding and bit length
 */


function getOutputLength(inputLength8) {
  return (inputLength8 + 64 >>> 9 << 4) + 14 + 1;
}
/*
 * Calculate the MD5 of an array of little-endian words, and a bit length.
 */


function wordsToMd5(x, len) {
  /* append padding */
  x[len >> 5] |= 0x80 << len % 32;
  x[getOutputLength(len) - 1] = len;
  var a = 1732584193;
  var b = -271733879;
  var c = -1732584194;
  var d = 271733878;

  for (var i = 0; i < x.length; i += 16) {
    var olda = a;
    var oldb = b;
    var oldc = c;
    var oldd = d;
    a = md5ff(a, b, c, d, x[i], 7, -680876936);
    d = md5ff(d, a, b, c, x[i + 1], 12, -389564586);
    c = md5ff(c, d, a, b, x[i + 2], 17, 606105819);
    b = md5ff(b, c, d, a, x[i + 3], 22, -1044525330);
    a = md5ff(a, b, c, d, x[i + 4], 7, -176418897);
    d = md5ff(d, a, b, c, x[i + 5], 12, 1200080426);
    c = md5ff(c, d, a, b, x[i + 6], 17, -1473231341);
    b = md5ff(b, c, d, a, x[i + 7], 22, -45705983);
    a = md5ff(a, b, c, d, x[i + 8], 7, 1770035416);
    d = md5ff(d, a, b, c, x[i + 9], 12, -1958414417);
    c = md5ff(c, d, a, b, x[i + 10], 17, -42063);
    b = md5ff(b, c, d, a, x[i + 11], 22, -1990404162);
    a = md5ff(a, b, c, d, x[i + 12], 7, 1804603682);
    d = md5ff(d, a, b, c, x[i + 13], 12, -40341101);
    c = md5ff(c, d, a, b, x[i + 14], 17, -1502002290);
    b = md5ff(b, c, d, a, x[i + 15], 22, 1236535329);
    a = md5gg(a, b, c, d, x[i + 1], 5, -165796510);
    d = md5gg(d, a, b, c, x[i + 6], 9, -1069501632);
    c = md5gg(c, d, a, b, x[i + 11], 14, 643717713);
    b = md5gg(b, c, d, a, x[i], 20, -373897302);
    a = md5gg(a, b, c, d, x[i + 5], 5, -701558691);
    d = md5gg(d, a, b, c, x[i + 10], 9, 38016083);
    c = md5gg(c, d, a, b, x[i + 15], 14, -660478335);
    b = md5gg(b, c, d, a, x[i + 4], 20, -405537848);
    a = md5gg(a, b, c, d, x[i + 9], 5, 568446438);
    d = md5gg(d, a, b, c, x[i + 14], 9, -1019803690);
    c = md5gg(c, d, a, b, x[i + 3], 14, -187363961);
    b = md5gg(b, c, d, a, x[i + 8], 20, 1163531501);
    a = md5gg(a, b, c, d, x[i + 13], 5, -1444681467);
    d = md5gg(d, a, b, c, x[i + 2], 9, -51403784);
    c = md5gg(c, d, a, b, x[i + 7], 14, 1735328473);
    b = md5gg(b, c, d, a, x[i + 12], 20, -1926607734);
    a = md5hh(a, b, c, d, x[i + 5], 4, -378558);
    d = md5hh(d, a, b, c, x[i + 8], 11, -2022574463);
    c = md5hh(c, d, a, b, x[i + 11], 16, 1839030562);
    b = md5hh(b, c, d, a, x[i + 14], 23, -35309556);
    a = md5hh(a, b, c, d, x[i + 1], 4, -1530992060);
    d = md5hh(d, a, b, c, x[i + 4], 11, 1272893353);
    c = md5hh(c, d, a, b, x[i + 7], 16, -155497632);
    b = md5hh(b, c, d, a, x[i + 10], 23, -1094730640);
    a = md5hh(a, b, c, d, x[i + 13], 4, 681279174);
    d = md5hh(d, a, b, c, x[i], 11, -358537222);
    c = md5hh(c, d, a, b, x[i + 3], 16, -722521979);
    b = md5hh(b, c, d, a, x[i + 6], 23, 76029189);
    a = md5hh(a, b, c, d, x[i + 9], 4, -640364487);
    d = md5hh(d, a, b, c, x[i + 12], 11, -421815835);
    c = md5hh(c, d, a, b, x[i + 15], 16, 530742520);
    b = md5hh(b, c, d, a, x[i + 2], 23, -995338651);
    a = md5ii(a, b, c, d, x[i], 6, -198630844);
    d = md5ii(d, a, b, c, x[i + 7], 10, 1126891415);
    c = md5ii(c, d, a, b, x[i + 14], 15, -1416354905);
    b = md5ii(b, c, d, a, x[i + 5], 21, -57434055);
    a = md5ii(a, b, c, d, x[i + 12], 6, 1700485571);
    d = md5ii(d, a, b, c, x[i + 3], 10, -1894986606);
    c = md5ii(c, d, a, b, x[i + 10], 15, -1051523);
    b = md5ii(b, c, d, a, x[i + 1], 21, -2054922799);
    a = md5ii(a, b, c, d, x[i + 8], 6, 1873313359);
    d = md5ii(d, a, b, c, x[i + 15], 10, -30611744);
    c = md5ii(c, d, a, b, x[i + 6], 15, -1560198380);
    b = md5ii(b, c, d, a, x[i + 13], 21, 1309151649);
    a = md5ii(a, b, c, d, x[i + 4], 6, -145523070);
    d = md5ii(d, a, b, c, x[i + 11], 10, -1120210379);
    c = md5ii(c, d, a, b, x[i + 2], 15, 718787259);
    b = md5ii(b, c, d, a, x[i + 9], 21, -343485551);
    a = safeAdd(a, olda);
    b = safeAdd(b, oldb);
    c = safeAdd(c, oldc);
    d = safeAdd(d, oldd);
  }

  return [a, b, c, d];
}
/*
 * Convert an array bytes to an array of little-endian words
 * Characters >255 have their high-byte silently ignored.
 */


function bytesToWords(input) {
  if (input.length === 0) {
    return [];
  }

  var length8 = input.length * 8;
  var output = new Uint32Array(getOutputLength(length8));

  for (var i = 0; i < length8; i += 8) {
    output[i >> 5] |= (input[i / 8] & 0xff) << i % 32;
  }

  return output;
}
/*
 * Add integers, wrapping at 2^32. This uses 16-bit operations internally
 * to work around bugs in some JS interpreters.
 */


function safeAdd(x, y) {
  var lsw = (x & 0xffff) + (y & 0xffff);
  var msw = (x >> 16) + (y >> 16) + (lsw >> 16);
  return msw << 16 | lsw & 0xffff;
}
/*
 * Bitwise rotate a 32-bit number to the left.
 */


function bitRotateLeft(num, cnt) {
  return num << cnt | num >>> 32 - cnt;
}
/*
 * These functions implement the four basic operations the algorithm uses.
 */


function md5cmn(q, a, b, x, s, t) {
  return safeAdd(bitRotateLeft(safeAdd(safeAdd(a, q), safeAdd(x, t)), s), b);
}

function md5ff(a, b, c, d, x, s, t) {
  return md5cmn(b & c | ~b & d, a, b, x, s, t);
}

function md5gg(a, b, c, d, x, s, t) {
  return md5cmn(b & d | c & ~d, a, b, x, s, t);
}

function md5hh(a, b, c, d, x, s, t) {
  return md5cmn(b ^ c ^ d, a, b, x, s, t);
}

function md5ii(a, b, c, d, x, s, t) {
  return md5cmn(c ^ (b | ~d), a, b, x, s, t);
}

/* harmony default export */ __webpack_exports__["default"] = (md5);

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/nil.js":
/*!***************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/nil.js ***!
  \***************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = ('00000000-0000-0000-0000-000000000000');

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/parse.js":
/*!*****************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/parse.js ***!
  \*****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _validate_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./validate.js */ "./node_modules/uuid/dist/esm-browser/validate.js");


function parse(uuid) {
  if (!Object(_validate_js__WEBPACK_IMPORTED_MODULE_0__["default"])(uuid)) {
    throw TypeError('Invalid UUID');
  }

  var v;
  var arr = new Uint8Array(16); // Parse ########-....-....-....-............

  arr[0] = (v = parseInt(uuid.slice(0, 8), 16)) >>> 24;
  arr[1] = v >>> 16 & 0xff;
  arr[2] = v >>> 8 & 0xff;
  arr[3] = v & 0xff; // Parse ........-####-....-....-............

  arr[4] = (v = parseInt(uuid.slice(9, 13), 16)) >>> 8;
  arr[5] = v & 0xff; // Parse ........-....-####-....-............

  arr[6] = (v = parseInt(uuid.slice(14, 18), 16)) >>> 8;
  arr[7] = v & 0xff; // Parse ........-....-....-####-............

  arr[8] = (v = parseInt(uuid.slice(19, 23), 16)) >>> 8;
  arr[9] = v & 0xff; // Parse ........-....-....-....-############
  // (Use "/" to avoid 32-bit truncation when bit-shifting high-order bytes)

  arr[10] = (v = parseInt(uuid.slice(24, 36), 16)) / 0x10000000000 & 0xff;
  arr[11] = v / 0x100000000 & 0xff;
  arr[12] = v >>> 24 & 0xff;
  arr[13] = v >>> 16 & 0xff;
  arr[14] = v >>> 8 & 0xff;
  arr[15] = v & 0xff;
  return arr;
}

/* harmony default export */ __webpack_exports__["default"] = (parse);

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/regex.js":
/*!*****************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/regex.js ***!
  \*****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = (/^(?:[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}|00000000-0000-0000-0000-000000000000)$/i);

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/rng.js":
/*!***************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/rng.js ***!
  \***************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return rng; });
// Unique ID creation requires a high quality random # generator. In the browser we therefore
// require the crypto API and do not support built-in fallback to lower quality random number
// generators (like Math.random()).
var getRandomValues;
var rnds8 = new Uint8Array(16);
function rng() {
  // lazy load so that environments that need to polyfill have a chance to do so
  if (!getRandomValues) {
    // getRandomValues needs to be invoked in a context where "this" is a Crypto implementation. Also,
    // find the complete implementation of crypto (msCrypto) on IE11.
    getRandomValues = typeof crypto !== 'undefined' && crypto.getRandomValues && crypto.getRandomValues.bind(crypto) || typeof msCrypto !== 'undefined' && typeof msCrypto.getRandomValues === 'function' && msCrypto.getRandomValues.bind(msCrypto);

    if (!getRandomValues) {
      throw new Error('crypto.getRandomValues() not supported. See https://github.com/uuidjs/uuid#getrandomvalues-not-supported');
    }
  }

  return getRandomValues(rnds8);
}

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/sha1.js":
/*!****************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/sha1.js ***!
  \****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
// Adapted from Chris Veness' SHA1 code at
// http://www.movable-type.co.uk/scripts/sha1.html
function f(s, x, y, z) {
  switch (s) {
    case 0:
      return x & y ^ ~x & z;

    case 1:
      return x ^ y ^ z;

    case 2:
      return x & y ^ x & z ^ y & z;

    case 3:
      return x ^ y ^ z;
  }
}

function ROTL(x, n) {
  return x << n | x >>> 32 - n;
}

function sha1(bytes) {
  var K = [0x5a827999, 0x6ed9eba1, 0x8f1bbcdc, 0xca62c1d6];
  var H = [0x67452301, 0xefcdab89, 0x98badcfe, 0x10325476, 0xc3d2e1f0];

  if (typeof bytes === 'string') {
    var msg = unescape(encodeURIComponent(bytes)); // UTF8 escape

    bytes = [];

    for (var i = 0; i < msg.length; ++i) {
      bytes.push(msg.charCodeAt(i));
    }
  } else if (!Array.isArray(bytes)) {
    // Convert Array-like to Array
    bytes = Array.prototype.slice.call(bytes);
  }

  bytes.push(0x80);
  var l = bytes.length / 4 + 2;
  var N = Math.ceil(l / 16);
  var M = new Array(N);

  for (var _i = 0; _i < N; ++_i) {
    var arr = new Uint32Array(16);

    for (var j = 0; j < 16; ++j) {
      arr[j] = bytes[_i * 64 + j * 4] << 24 | bytes[_i * 64 + j * 4 + 1] << 16 | bytes[_i * 64 + j * 4 + 2] << 8 | bytes[_i * 64 + j * 4 + 3];
    }

    M[_i] = arr;
  }

  M[N - 1][14] = (bytes.length - 1) * 8 / Math.pow(2, 32);
  M[N - 1][14] = Math.floor(M[N - 1][14]);
  M[N - 1][15] = (bytes.length - 1) * 8 & 0xffffffff;

  for (var _i2 = 0; _i2 < N; ++_i2) {
    var W = new Uint32Array(80);

    for (var t = 0; t < 16; ++t) {
      W[t] = M[_i2][t];
    }

    for (var _t = 16; _t < 80; ++_t) {
      W[_t] = ROTL(W[_t - 3] ^ W[_t - 8] ^ W[_t - 14] ^ W[_t - 16], 1);
    }

    var a = H[0];
    var b = H[1];
    var c = H[2];
    var d = H[3];
    var e = H[4];

    for (var _t2 = 0; _t2 < 80; ++_t2) {
      var s = Math.floor(_t2 / 20);
      var T = ROTL(a, 5) + f(s, b, c, d) + e + K[s] + W[_t2] >>> 0;
      e = d;
      d = c;
      c = ROTL(b, 30) >>> 0;
      b = a;
      a = T;
    }

    H[0] = H[0] + a >>> 0;
    H[1] = H[1] + b >>> 0;
    H[2] = H[2] + c >>> 0;
    H[3] = H[3] + d >>> 0;
    H[4] = H[4] + e >>> 0;
  }

  return [H[0] >> 24 & 0xff, H[0] >> 16 & 0xff, H[0] >> 8 & 0xff, H[0] & 0xff, H[1] >> 24 & 0xff, H[1] >> 16 & 0xff, H[1] >> 8 & 0xff, H[1] & 0xff, H[2] >> 24 & 0xff, H[2] >> 16 & 0xff, H[2] >> 8 & 0xff, H[2] & 0xff, H[3] >> 24 & 0xff, H[3] >> 16 & 0xff, H[3] >> 8 & 0xff, H[3] & 0xff, H[4] >> 24 & 0xff, H[4] >> 16 & 0xff, H[4] >> 8 & 0xff, H[4] & 0xff];
}

/* harmony default export */ __webpack_exports__["default"] = (sha1);

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/stringify.js":
/*!*********************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/stringify.js ***!
  \*********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _validate_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./validate.js */ "./node_modules/uuid/dist/esm-browser/validate.js");

/**
 * Convert array of 16 byte values to UUID string format of the form:
 * XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX
 */

var byteToHex = [];

for (var i = 0; i < 256; ++i) {
  byteToHex.push((i + 0x100).toString(16).substr(1));
}

function stringify(arr) {
  var offset = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;
  // Note: Be careful editing this code!  It's been tuned for performance
  // and works in ways you may not expect. See https://github.com/uuidjs/uuid/pull/434
  var uuid = (byteToHex[arr[offset + 0]] + byteToHex[arr[offset + 1]] + byteToHex[arr[offset + 2]] + byteToHex[arr[offset + 3]] + '-' + byteToHex[arr[offset + 4]] + byteToHex[arr[offset + 5]] + '-' + byteToHex[arr[offset + 6]] + byteToHex[arr[offset + 7]] + '-' + byteToHex[arr[offset + 8]] + byteToHex[arr[offset + 9]] + '-' + byteToHex[arr[offset + 10]] + byteToHex[arr[offset + 11]] + byteToHex[arr[offset + 12]] + byteToHex[arr[offset + 13]] + byteToHex[arr[offset + 14]] + byteToHex[arr[offset + 15]]).toLowerCase(); // Consistency check for valid UUID.  If this throws, it's likely due to one
  // of the following:
  // - One or more input array values don't map to a hex octet (leading to
  // "undefined" in the uuid)
  // - Invalid input values for the RFC `version` or `variant` fields

  if (!Object(_validate_js__WEBPACK_IMPORTED_MODULE_0__["default"])(uuid)) {
    throw TypeError('Stringified UUID is invalid');
  }

  return uuid;
}

/* harmony default export */ __webpack_exports__["default"] = (stringify);

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/v1.js":
/*!**************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/v1.js ***!
  \**************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _rng_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./rng.js */ "./node_modules/uuid/dist/esm-browser/rng.js");
/* harmony import */ var _stringify_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./stringify.js */ "./node_modules/uuid/dist/esm-browser/stringify.js");

 // **`v1()` - Generate time-based UUID**
//
// Inspired by https://github.com/LiosK/UUID.js
// and http://docs.python.org/library/uuid.html

var _nodeId;

var _clockseq; // Previous uuid creation time


var _lastMSecs = 0;
var _lastNSecs = 0; // See https://github.com/uuidjs/uuid for API details

function v1(options, buf, offset) {
  var i = buf && offset || 0;
  var b = buf || new Array(16);
  options = options || {};
  var node = options.node || _nodeId;
  var clockseq = options.clockseq !== undefined ? options.clockseq : _clockseq; // node and clockseq need to be initialized to random values if they're not
  // specified.  We do this lazily to minimize issues related to insufficient
  // system entropy.  See #189

  if (node == null || clockseq == null) {
    var seedBytes = options.random || (options.rng || _rng_js__WEBPACK_IMPORTED_MODULE_0__["default"])();

    if (node == null) {
      // Per 4.5, create and 48-bit node id, (47 random bits + multicast bit = 1)
      node = _nodeId = [seedBytes[0] | 0x01, seedBytes[1], seedBytes[2], seedBytes[3], seedBytes[4], seedBytes[5]];
    }

    if (clockseq == null) {
      // Per 4.2.2, randomize (14 bit) clockseq
      clockseq = _clockseq = (seedBytes[6] << 8 | seedBytes[7]) & 0x3fff;
    }
  } // UUID timestamps are 100 nano-second units since the Gregorian epoch,
  // (1582-10-15 00:00).  JSNumbers aren't precise enough for this, so
  // time is handled internally as 'msecs' (integer milliseconds) and 'nsecs'
  // (100-nanoseconds offset from msecs) since unix epoch, 1970-01-01 00:00.


  var msecs = options.msecs !== undefined ? options.msecs : Date.now(); // Per 4.2.1.2, use count of uuid's generated during the current clock
  // cycle to simulate higher resolution clock

  var nsecs = options.nsecs !== undefined ? options.nsecs : _lastNSecs + 1; // Time since last uuid creation (in msecs)

  var dt = msecs - _lastMSecs + (nsecs - _lastNSecs) / 10000; // Per 4.2.1.2, Bump clockseq on clock regression

  if (dt < 0 && options.clockseq === undefined) {
    clockseq = clockseq + 1 & 0x3fff;
  } // Reset nsecs if clock regresses (new clockseq) or we've moved onto a new
  // time interval


  if ((dt < 0 || msecs > _lastMSecs) && options.nsecs === undefined) {
    nsecs = 0;
  } // Per 4.2.1.2 Throw error if too many uuids are requested


  if (nsecs >= 10000) {
    throw new Error("uuid.v1(): Can't create more than 10M uuids/sec");
  }

  _lastMSecs = msecs;
  _lastNSecs = nsecs;
  _clockseq = clockseq; // Per 4.1.4 - Convert from unix epoch to Gregorian epoch

  msecs += 12219292800000; // `time_low`

  var tl = ((msecs & 0xfffffff) * 10000 + nsecs) % 0x100000000;
  b[i++] = tl >>> 24 & 0xff;
  b[i++] = tl >>> 16 & 0xff;
  b[i++] = tl >>> 8 & 0xff;
  b[i++] = tl & 0xff; // `time_mid`

  var tmh = msecs / 0x100000000 * 10000 & 0xfffffff;
  b[i++] = tmh >>> 8 & 0xff;
  b[i++] = tmh & 0xff; // `time_high_and_version`

  b[i++] = tmh >>> 24 & 0xf | 0x10; // include version

  b[i++] = tmh >>> 16 & 0xff; // `clock_seq_hi_and_reserved` (Per 4.2.2 - include variant)

  b[i++] = clockseq >>> 8 | 0x80; // `clock_seq_low`

  b[i++] = clockseq & 0xff; // `node`

  for (var n = 0; n < 6; ++n) {
    b[i + n] = node[n];
  }

  return buf || Object(_stringify_js__WEBPACK_IMPORTED_MODULE_1__["default"])(b);
}

/* harmony default export */ __webpack_exports__["default"] = (v1);

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/v3.js":
/*!**************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/v3.js ***!
  \**************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _v35_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./v35.js */ "./node_modules/uuid/dist/esm-browser/v35.js");
/* harmony import */ var _md5_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./md5.js */ "./node_modules/uuid/dist/esm-browser/md5.js");


var v3 = Object(_v35_js__WEBPACK_IMPORTED_MODULE_0__["default"])('v3', 0x30, _md5_js__WEBPACK_IMPORTED_MODULE_1__["default"]);
/* harmony default export */ __webpack_exports__["default"] = (v3);

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/v35.js":
/*!***************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/v35.js ***!
  \***************************************************/
/*! exports provided: DNS, URL, default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "DNS", function() { return DNS; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "URL", function() { return URL; });
/* harmony import */ var _stringify_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./stringify.js */ "./node_modules/uuid/dist/esm-browser/stringify.js");
/* harmony import */ var _parse_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./parse.js */ "./node_modules/uuid/dist/esm-browser/parse.js");



function stringToBytes(str) {
  str = unescape(encodeURIComponent(str)); // UTF8 escape

  var bytes = [];

  for (var i = 0; i < str.length; ++i) {
    bytes.push(str.charCodeAt(i));
  }

  return bytes;
}

var DNS = '6ba7b810-9dad-11d1-80b4-00c04fd430c8';
var URL = '6ba7b811-9dad-11d1-80b4-00c04fd430c8';
/* harmony default export */ __webpack_exports__["default"] = (function (name, version, hashfunc) {
  function generateUUID(value, namespace, buf, offset) {
    if (typeof value === 'string') {
      value = stringToBytes(value);
    }

    if (typeof namespace === 'string') {
      namespace = Object(_parse_js__WEBPACK_IMPORTED_MODULE_1__["default"])(namespace);
    }

    if (namespace.length !== 16) {
      throw TypeError('Namespace must be array-like (16 iterable integer values, 0-255)');
    } // Compute hash of namespace and value, Per 4.3
    // Future: Use spread syntax when supported on all platforms, e.g. `bytes =
    // hashfunc([...namespace, ... value])`


    var bytes = new Uint8Array(16 + value.length);
    bytes.set(namespace);
    bytes.set(value, namespace.length);
    bytes = hashfunc(bytes);
    bytes[6] = bytes[6] & 0x0f | version;
    bytes[8] = bytes[8] & 0x3f | 0x80;

    if (buf) {
      offset = offset || 0;

      for (var i = 0; i < 16; ++i) {
        buf[offset + i] = bytes[i];
      }

      return buf;
    }

    return Object(_stringify_js__WEBPACK_IMPORTED_MODULE_0__["default"])(bytes);
  } // Function#name is not settable on some platforms (#270)


  try {
    generateUUID.name = name; // eslint-disable-next-line no-empty
  } catch (err) {} // For CommonJS default export support


  generateUUID.DNS = DNS;
  generateUUID.URL = URL;
  return generateUUID;
});

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/v4.js":
/*!**************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/v4.js ***!
  \**************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _rng_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./rng.js */ "./node_modules/uuid/dist/esm-browser/rng.js");
/* harmony import */ var _stringify_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./stringify.js */ "./node_modules/uuid/dist/esm-browser/stringify.js");



function v4(options, buf, offset) {
  options = options || {};
  var rnds = options.random || (options.rng || _rng_js__WEBPACK_IMPORTED_MODULE_0__["default"])(); // Per 4.4, set bits for version and `clock_seq_hi_and_reserved`

  rnds[6] = rnds[6] & 0x0f | 0x40;
  rnds[8] = rnds[8] & 0x3f | 0x80; // Copy bytes to buffer, if provided

  if (buf) {
    offset = offset || 0;

    for (var i = 0; i < 16; ++i) {
      buf[offset + i] = rnds[i];
    }

    return buf;
  }

  return Object(_stringify_js__WEBPACK_IMPORTED_MODULE_1__["default"])(rnds);
}

/* harmony default export */ __webpack_exports__["default"] = (v4);

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/v5.js":
/*!**************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/v5.js ***!
  \**************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _v35_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./v35.js */ "./node_modules/uuid/dist/esm-browser/v35.js");
/* harmony import */ var _sha1_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./sha1.js */ "./node_modules/uuid/dist/esm-browser/sha1.js");


var v5 = Object(_v35_js__WEBPACK_IMPORTED_MODULE_0__["default"])('v5', 0x50, _sha1_js__WEBPACK_IMPORTED_MODULE_1__["default"]);
/* harmony default export */ __webpack_exports__["default"] = (v5);

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/validate.js":
/*!********************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/validate.js ***!
  \********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _regex_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./regex.js */ "./node_modules/uuid/dist/esm-browser/regex.js");


function validate(uuid) {
  return typeof uuid === 'string' && _regex_js__WEBPACK_IMPORTED_MODULE_0__["default"].test(uuid);
}

/* harmony default export */ __webpack_exports__["default"] = (validate);

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/version.js":
/*!*******************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/version.js ***!
  \*******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _validate_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./validate.js */ "./node_modules/uuid/dist/esm-browser/validate.js");


function version(uuid) {
  if (!Object(_validate_js__WEBPACK_IMPORTED_MODULE_0__["default"])(uuid)) {
    throw TypeError('Invalid UUID');
  }

  return parseInt(uuid.substr(14, 1), 16);
}

/* harmony default export */ __webpack_exports__["default"] = (version);

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

/***/ "@gechiui/html-entities":
/*!**************************************!*\
  !*** external ["gc","htmlEntities"] ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["gc"]["htmlEntities"]; }());

/***/ }),

/***/ "@gechiui/i18n":
/*!******************************!*\
  !*** external ["gc","i18n"] ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["gc"]["i18n"]; }());

/***/ }),

/***/ "@gechiui/is-shallow-equal":
/*!****************************************!*\
  !*** external ["gc","isShallowEqual"] ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["gc"]["isShallowEqual"]; }());

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

/******/ });
//# sourceMappingURL=core-data.js.map