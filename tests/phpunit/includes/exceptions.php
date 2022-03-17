<?php

class GC_Tests_Exception extends PHPUnit_Framework_Exception {

}

/**
 * General exception for gc_die().
 */
class GCDieException extends Exception {}

/**
 * Exception for cases of gc_die(), for Ajax tests.
 *
 * This means there was an error (no output, and a call to gc_die).
 *
 * @package    GeChiUI
 * @subpackage Unit Tests
 * @since      3.4.0
 */
class GCAjaxDieStopException extends GCDieException {}

/**
 * Exception for cases of gc_die(), for Ajax tests.
 *
 * This means the execution of the Ajax function should be halted, but the unit test
 * can continue. The function finished normally and there was no error (output happened,
 * but gc_die was called to end execution). This is used with GC_Ajax_Response::send().
 *
 * @package    GeChiUI
 * @subpackage Unit Tests
 * @since      3.4.0
 */
class GCAjaxDieContinueException extends GCDieException {}
