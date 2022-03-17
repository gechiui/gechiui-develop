<?php

/**
 * @group image
 * @group media
 * @group upload
 * @group resize
 *
 * @requires function imagejpeg
 */
require_once __DIR__ . '/resize.php';

class Test_Image_Resize_GD extends GC_Tests_Image_Resize_UnitTestCase {

	/**
	 * Use the GD image editor engine
	 *
	 * @var string
	 */
	public $editor_engine = 'GC_Image_Editor_GD';

	public function set_up() {
		require_once ABSPATH . GCINC . '/class-gc-image-editor.php';
		require_once ABSPATH . GCINC . '/class-gc-image-editor-gd.php';

		// This needs to come after the mock image editor class is loaded.
		parent::set_up();
	}

	/**
	 * Try resizing a php file (bad image)
	 *
	 * @ticket 6821
	 */
	public function test_resize_bad_image() {

		$image = $this->resize_helper( DIR_TESTDATA . '/export/crazy-cdata.xml', 25, 25 );
		$this->assertInstanceOf( 'GC_Error', $image );
		$this->assertSame( 'invalid_image', $image->get_error_code() );
	}

}
