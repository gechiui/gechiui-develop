<?php

/**
 * @group formatting
 */
class Tests_Formatting_gcBasename extends GC_UnitTestCase {

	public function test_gc_basename_unix() {
		$this->assertSame(
			'file',
			gc_basename( '/home/test/file' )
		);
	}

	public function test_gc_basename_unix_utf8_support() {
		$this->assertSame(
			'žluťoučký kůň.txt',
			gc_basename( '/test/žluťoučký kůň.txt' )
		);
	}

	/**
	 * @ticket 22138
	 */
	public function test_gc_basename_windows() {
		$this->assertSame(
			'file.txt',
			gc_basename( 'C:\Documents and Settings\User\file.txt' )
		);
	}

	/**
	 * @ticket 22138
	 */
	public function test_gc_basename_windows_utf8_support() {
		$this->assertSame(
			'щипцы.txt',
			gc_basename( 'C:\test\щипцы.txt' )
		);
	}

}
