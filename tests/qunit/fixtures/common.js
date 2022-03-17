/**
 * Mock the deprecateL10nObject() for tests.
 *
 * deprecateL10nObject() is part of gc-admin/js/common.js which requires
 * some HTML markup to exist. Instead of adding all the markup this adds
 * a noop version of deprecateL10nObject(). This makes it possible
 * to test gc-admin/js/dashboard.js.
 */
window.gc = window.gc || {};
window.gc.deprecateL10nObject = function () {};
