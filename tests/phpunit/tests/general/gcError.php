<?php
/**
 * Tests for error handling and the GC_Error class.
 *
 * @group general
 * @group errors
 *
 * @covers GC_Error
 * @coversDefaultClass GC_Error
 */
class Tests_General_gcError extends GC_UnitTestCase {

	/**
	 * GC_Error fixture.
	 *
	 * @var GC_Error
	 */
	public $gc_error;

	/**
	 * Set up.
	 */
	public function set_up() {
		parent::set_up();

		$this->gc_error = new GC_Error();
	}

	/**
	 * @covers ::__construct
	 */
	public function test_GC_Error_should_be_of_type_GC_Error() {
		$this->assertWPError( $this->gc_error );
	}

	/**
	 * @covers ::__construct
	 */
	public function test_GC_Error_with_default_empty_parameters_should_add_no_errors() {
		$this->assertEmpty( $this->gc_error->errors );
	}

	/**
	 * @covers ::__construct
	 * @covers ::get_error_code
	 */
	public function test_GC_Error_with_empty_code_should_add_no_code() {
		$this->assertSame( '', $this->gc_error->get_error_code() );
	}

	/**
	 * @covers ::__construct
	 * @covers ::get_error_message
	 */
	public function test_GC_Error_with_empty_code_should_add_no_message() {
		$this->assertSame( '', $this->gc_error->get_error_message() );
	}

	/**
	 * @covers ::__construct
	 */
	public function test_GC_Error_with_empty_code_should_add_no_error_data() {
		$this->assertEmpty( $this->gc_error->error_data );
	}

	/**
	 * @covers ::__construct
	 * @covers ::get_error_code
	 */
	public function test_GC_Error_with_code_and_empty_message_should_add_error_with_that_code() {
		$gc_error = new GC_Error( 'code' );

		$this->assertSame( 'code', $gc_error->get_error_code() );
	}

	/**
	 * @covers ::__construct
	 * @covers ::get_error_message
	 */
	public function test_GC_Error_with_code_and_empty_message_should_add_error_with_that_code_and_empty_message() {
		$gc_error = new GC_Error( 'code' );

		$this->assertSame( '', $gc_error->get_error_message( 'code' ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::get_error_data
	 */
	public function test_GC_Error_with_code_and_empty_message_and_empty_data_should_add_error_but_not_associated_data() {
		$gc_error = new GC_Error( 'code' );

		$this->assertNull( $gc_error->get_error_data( 'code' ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::get_error_data
	 */
	public function test_GC_Error_with_code_and_empty_message_and_non_empty_data_should_add_error_with_empty_message_and_that_stored_data() {
		$gc_error = new GC_Error( 'code', '', 'data' );

		$this->assertSame( 'data', $gc_error->get_error_data( 'code' ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::get_error_code
	 */
	public function test_GC_Error_with_code_and_message_should_add_error_with_that_code() {
		$gc_error = new GC_Error( 'code', 'message' );

		$this->assertSame( 'code', $gc_error->get_error_code() );
	}

	/**
	 * @covers ::__construct
	 * @covers ::get_error_message
	 */
	public function test_GC_Error_with_code_and_message_should_add_error_with_that_message() {
		$gc_error = new GC_Error( 'code', 'message' );

		$this->assertSame( 'message', $gc_error->get_error_message( 'code' ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::get_error_code
	 */
	public function test_GC_Error_with_code_and_message_and_data_should_add_error_with_that_code() {
		$gc_error = new GC_Error( 'code', 'message', 'data' );

		$this->assertSame( 'code', $gc_error->get_error_code() );
	}

	/**
	 * @covers ::__construct
	 * @covers ::get_error_message
	 */
	public function test_GC_Error_with_code_and_message_and_data_should_add_error_with_that_message() {
		$gc_error = new GC_Error( 'code', 'message', 'data' );

		$this->assertSame( 'message', $gc_error->get_error_message( 'code' ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::get_error_data
	 */
	public function test_GC_Error_with_code_and_message_and_data_should_add_error_with_that_data() {
		$gc_error = new GC_Error( 'code', 'message', 'data' );

		$this->assertSame( 'data', $gc_error->get_error_data( 'code' ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::get_error_codes
	 */
	public function test_get_error_codes_with_no_errors_should_return_empty_array() {
		$this->assertEmpty( $this->gc_error->get_error_codes() );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_codes
	 */
	public function test_get_error_codes_with_one_error_should_return_an_array_with_only_that_code() {
		$this->gc_error->add( 'code', 'message' );

		$this->assertSameSets( array( 'code' ), $this->gc_error->get_error_codes() );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_codes
	 */
	public function test_get_error_codes_with_multiple_errors_should_return_an_array_of_those_codes() {
		$this->gc_error->add( 'code', 'message' );
		$this->gc_error->add( 'code2', 'message2' );

		$expected = array( 'code', 'code2' );

		$this->assertSameSets( $expected, $this->gc_error->get_error_codes() );
	}

	/**
	 * @covers ::__construct
	 * @covers ::get_error_code
	 */
	public function test_get_error_code_with_no_errors_should_return_an_empty_string() {
		$this->assertSame( '', $this->gc_error->get_error_code() );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_code
	 */
	public function test_get_error_code_with_one_error_should_return_that_error_code() {
		$this->gc_error->add( 'code', 'message' );

		$this->assertSame( 'code', $this->gc_error->get_error_code() );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_code
	 */
	public function test_get_error_code_with_multiple_errors_should_return_only_the_first_error_code() {
		$this->gc_error->add( 'code', 'message' );
		$this->gc_error->add( 'code2', 'message2' );

		$this->assertSame( 'code', $this->gc_error->get_error_code() );
	}

	/**
	 * @covers ::__construct
	 * @covers ::get_error_messages
	 */
	public function test_get_error_messages_with_empty_code_and_no_errors_should_return_an_empty_array() {
		$this->assertEmpty( $this->gc_error->get_error_messages() );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_messages
	 */
	public function test_get_error_messages_with_empty_code_one_error_should_return_an_array_with_that_message() {
		$this->gc_error->add( 'code', 'message' );

		$this->assertSameSets( array( 'message' ), $this->gc_error->get_error_messages() );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_messages
	 */
	public function test_get_error_messages_with_empty_code_multiple_errors_should_return_an_array_of_messages() {
		$this->gc_error->add( 'code', 'message' );
		$this->gc_error->add( 'code2', 'message2' );

		$this->assertSameSets( array( 'message', 'message2' ), $this->gc_error->get_error_messages() );
	}

	/**
	 * @covers ::__construct
	 * @covers ::get_error_messages
	 */
	public function test_get_error_messages_with_an_invalid_code_should_return_an_empty_array() {
		$this->assertEmpty( $this->gc_error->get_error_messages( 'code' ) );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_messages
	 */
	public function test_get_error_messages_with_one_error_should_return_an_array_with_that_message() {
		$this->gc_error->add( 'code', 'message' );

		$this->assertSameSets( array( 'message' ), $this->gc_error->get_error_messages( 'code' ) );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_messages
	 */
	public function test_get_error_messages_with_multiple_errors_same_code_should_return_an_array_with_all_messages() {
		$this->gc_error->add( 'code', 'message' );
		$this->gc_error->add( 'code', 'message2' );

		$this->assertSameSets( array( 'message', 'message2' ), $this->gc_error->get_error_messages( 'code' ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::get_error_message
	 */
	public function test_get_error_message_with_empty_code_and_no_errors_should_return_an_empty_string() {
		$this->assertSame( '', $this->gc_error->get_error_message() );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_message
	 */
	public function test_get_error_message_with_empty_code_and_one_error_should_return_that_message() {
		$this->gc_error->add( 'code', 'message' );

		$this->assertSame( 'message', $this->gc_error->get_error_message() );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_message
	 */
	public function test_get_error_message_with_empty_code_and_multiple_errors_should_return_the_first_message() {
		$this->gc_error->add( 'code', 'message' );
		$this->gc_error->add( 'code2', 'message2' );

		$this->assertSame( 'message', $this->gc_error->get_error_message() );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_message
	 */
	public function test_get_error_message_with_empty_code_and_multiple_errors_multiple_codes_should_return_the_first_message() {
		$this->gc_error->add( 'code', 'message' );
		$this->gc_error->add( 'code2', 'message2' );
		$this->gc_error->add( 'code', 'message2' );

		$this->assertSame( 'message', $this->gc_error->get_error_message() );
	}

	/**
	 * @covers ::__construct
	 * @covers ::get_error_message
	 */
	public function test_get_error_message_with_invalid_code_and_no_errors_should_return_empty_string() {
		$this->assertSame( '', $this->gc_error->get_error_message( 'invalid' ) );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_message
	 */
	public function test_get_error_message_with_invalid_code_and_one_error_should_return_an_empty_string() {
		$this->gc_error->add( 'code', 'message' );

		$this->assertSame( '', $this->gc_error->get_error_message( 'invalid' ) );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_message
	 */
	public function test_get_error_message_with_invalid_code_and_multiple_errors_should_return_an_empty_string() {
		$this->gc_error->add( 'code', 'message' );
		$this->gc_error->add( 'code2', 'message2' );

		$this->assertSame( '', $this->gc_error->get_error_message( 'invalid' ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::get_error_data
	 */
	public function test_get_error_data_with_empty_code_and_no_errors_should_evaluate_as_null() {
		$this->assertNull( $this->gc_error->get_error_data() );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_data
	 */
	public function test_get_error_data_with_empty_code_one_error_no_data_should_evaluate_as_null() {
		$this->gc_error->add( 'code', 'message' );

		$this->assertNull( $this->gc_error->get_error_data() );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_data
	 */
	public function test_get_error_data_with_empty_code_multiple_errors_no_data_should_evaluate_as_null() {
		$this->gc_error->add( 'code', 'message' );
		$this->gc_error->add( 'code2', 'message2' );

		$this->assertNull( $this->gc_error->get_error_data() );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_data
	 */
	public function test_get_error_data_with_empty_code_and_one_error_with_data_should_return_that_data() {
		$expected = array( 'data-key' => 'data-value' );
		$this->gc_error->add( 'code', 'message', $expected );

		$this->assertSameSetsWithIndex( $expected, $this->gc_error->get_error_data() );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_data
	 */
	public function test_get_error_data_with_empty_code_and_multiple_errors_different_codes_should_return_the_last_data_of_the_first_code() {
		$expected = array( 'data-key' => 'data-value' );
		$this->gc_error->add( 'code', 'message', $expected );
		$this->gc_error->add( 'code2', 'message2', 'data2' );

		$this->assertSameSetsWithIndex( $expected, $this->gc_error->get_error_data() );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_data
	 */
	public function test_get_error_data_with_empty_code_and_multiple_errors_same_code_should_return_the_last_data_of_the_first_code() {
		$this->gc_error->add( 'code', 'message', 'data' );
		$this->gc_error->add( 'code', 'message2', 'data2' );
		$this->gc_error->add( 'code2', 'message2', 'data3' );

		$this->assertSame( 'data2', $this->gc_error->get_error_data() );
	}

	/**
	 * @covers ::__construct
	 * @covers ::get_error_data
	 */
	public function test_get_error_data_with_code_and_no_errors_should_evaluate_as_null() {
		$this->assertNull( $this->gc_error->get_error_data( 'code' ) );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_data
	 */
	public function test_get_error_data_with_code_and_one_error_with_no_data_should_evaluate_as_null() {
		$this->gc_error->add( 'code', 'message' );

		$this->assertNull( $this->gc_error->get_error_data( 'code' ) );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_data
	 */
	public function test_get_error_data_with_code_and_one_error_with_data_should_return_that_data() {
		$expected = array( 'data-key' => 'data-value' );
		$this->gc_error->add( 'code', 'message', $expected );

		$this->assertSameSetsWithIndex( $expected, $this->gc_error->get_error_data( 'code' ) );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_data
	 */
	public function test_get_error_data_with_code_and_multiple_errors_different_codes_should_return_the_last_stored_data_of_the_code() {
		$expected = array( 'data3' );
		$this->gc_error->add( 'code', 'message', 'data' );
		$this->gc_error->add( 'code2', 'message2', 'data2' );
		$this->gc_error->add( 'code', 'message3', $expected );

		$this->assertSameSetsWithIndex( $expected, $this->gc_error->get_error_data( 'code' ) );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_data
	 */
	public function test_get_error_data_with_code_and_multiple_errors_same_code_should_return_the_last_stored_data() {
		$this->gc_error->add( 'code', 'message', 'data' );
		$this->gc_error->add( 'code', 'message2', 'data2' );
		$this->gc_error->add( 'code2', 'message3', 'data3' );

		$this->assertSame( 'data2', $this->gc_error->get_error_data( 'code' ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::get_all_error_data
	 */
	public function test_get_all_error_data_with_code_and_no_errors_should_evaluate_as_empty_array() {
		$this->assertSame( array(), $this->gc_error->get_all_error_data( 'code' ) );
	}

	/**
	 * @covers ::add
	 * @covers ::get_all_error_data
	 */
	public function test_get_all_error_data_with_code_and_one_error_with_no_data_should_evaluate_as_empty_array() {
		$this->gc_error->add( 'code', 'message' );

		$this->assertSame( array(), $this->gc_error->get_all_error_data( 'code' ) );
	}

	/**
	 * @covers ::add
	 * @covers ::get_all_error_data
	 */
	public function test_get_all_error_data_with_code_and_one_error_with_data_should_return_that_data() {
		$expected = array( 'data-key' => 'data-value' );
		$this->gc_error->add( 'code', 'message', $expected );

		$actual = $this->gc_error->get_all_error_data( 'code' );
		$this->assertCount( 1, $actual );
		$this->assertSameSetsWithIndex( $expected, $actual[0] );
	}

	/**
	 * @covers ::add
	 * @covers ::get_all_error_data
	 */
	public function test_get_all_error_data_with_code_and_multiple_errors_same_code_should_return_all_data() {
		$this->gc_error->add( 'code', 'message', 'data' );
		$this->gc_error->add( 'code', 'message2', 'data2' );
		$this->gc_error->add( 'code2', 'message3', 'data3' );

		$this->assertSame( array( 'data', 'data2' ), $this->gc_error->get_all_error_data( 'code' ) );
	}

	/**
	 * @covers ::add
	 * @covers ::get_all_error_data
	 */
	public function test_get_all_error_data_should_handle_manipulation_of_error_data_property() {
		$this->gc_error->add_data( 'data1', 'code' );
		$this->gc_error->add_data( 'data2', 'code' );

		$this->gc_error->error_data['code'] = 'dataX';

		$this->assertSame( 'dataX', $this->gc_error->get_error_data( 'code' ) );
		$this->assertSame( array( 'data1', 'dataX' ), $this->gc_error->get_all_error_data( 'code' ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::has_errors
	 */
	public function test_has_errors_with_no_errors_returns_false() {
		$this->assertFalse( $this->gc_error->has_errors() );
	}

	/**
	 * @covers ::add
	 * @covers ::has_errors
	 */
	public function test_has_errors_with_errors_returns_true() {
		$this->gc_error->add( 'code', 'message', 'data' );
		$this->assertTrue( $this->gc_error->has_errors() );
	}

	/**
	 * @covers ::add
	 */
	public function test_add_with_empty_code_empty_message_empty_data_should_add_empty_key_to_errors_array() {
		$this->gc_error->add( '', '', 'data' );

		$this->assertArrayHasKey( '', $this->gc_error->errors );
	}

	/**
	 * @covers ::add
	 */
	public function test_add_with_empty_code_empty_message_empty_data_should_add_empty_message_to_errors_array_under_empty_key() {
		$this->gc_error->add( '', '', 'data' );

		$this->assertSameSetsWithIndex( array( '' => array( '' ) ), $this->gc_error->errors );
	}

	/**
	 * @covers ::add
	 */
	public function test_add_with_empty_code_empty_message_empty_data_should_not_alter_data() {
		$this->gc_error->add( '', '', '' );

		$this->assertEmpty( $this->gc_error->error_data );
	}

	/**
	 * @covers ::add
	 */
	public function test_add_with_empty_code_empty_message_non_empty_data_should_store_data_under_an_empty_code_key() {
		$this->gc_error->add( '', '', 'data' );

		$this->assertSameSetsWithIndex( array( '' => 'data' ), $this->gc_error->error_data );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_code
	 */
	public function test_add_with_code_empty_message_empty_data_should_add_error_with_code() {
		$this->gc_error->add( 'code', '' );

		$this->assertSame( 'code', $this->gc_error->get_error_code() );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_message
	 */
	public function test_add_with_code_empty_message_empty_data_should_add_error_with_empty_message() {
		$this->gc_error->add( 'code', '' );

		$this->assertSame( '', $this->gc_error->get_error_message( 'code' ) );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_data
	 */
	public function test_add_with_code_empty_message_empty_data_should_not_add_error_data() {
		$this->gc_error->add( 'code', '' );

		$this->assertNull( $this->gc_error->get_error_data( 'code' ) );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_message
	 */
	public function test_add_with_code_and_message_and_empty_data_should_should_add_error_with_that_message() {
		$this->gc_error->add( 'code', 'message' );

		$this->assertSame( 'message', $this->gc_error->get_error_message( 'code' ) );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_data
	 */
	public function test_add_with_code_and_message_and_empty_data_should_not_alter_stored_data() {
		$this->gc_error->add( 'code', 'message' );

		$this->assertNull( $this->gc_error->get_error_data( 'code' ) );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_code
	 */
	public function test_add_with_code_and_empty_message_and_data_should_add_error_with_that_code() {
		$this->gc_error->add( 'code', '', 'data' );

		$this->assertSame( 'code', $this->gc_error->get_error_code() );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_data
	 */
	public function test_add_with_code_and_empty_message_and_data_should_store_that_data() {
		$this->gc_error->add( 'code', '', 'data' );

		$this->assertSame( 'data', $this->gc_error->get_error_data( 'code' ) );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_code
	 */
	public function test_add_with_code_and_message_and_data_should_add_an_error_with_that_code() {
		$this->gc_error->add( 'code', 'message', 'data' );

		$this->assertSame( 'code', $this->gc_error->get_error_code() );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_message
	 */
	public function test_add_with_code_and_message_and_data_should_add_an_error_with_that_message() {
		$this->gc_error->add( 'code', 'message', 'data' );

		$this->assertSame( 'message', $this->gc_error->get_error_message( 'code' ) );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_data
	 */
	public function test_add_with_code_and_message_and_data_should_store_that_data() {
		$this->gc_error->add( 'code', 'message', 'data' );

		$this->assertSame( 'data', $this->gc_error->get_error_data( 'code' ) );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_messages
	 */
	public function test_add_multiple_times_with_the_same_code_should_add_additional_messages_for_that_code() {
		$this->gc_error->add( 'code', 'message' );
		$this->gc_error->add( 'code', 'message2' );

		$expected = array( 'message', 'message2' );

		$this->assertSameSets( $expected, $this->gc_error->get_error_messages( 'code' ) );
	}

	/**
	 * @covers ::add
	 * @covers ::get_error_data
	 */
	public function test_add_multiple_times_with_the_same_code_and_different_data_should_store_only_the_last_added_data() {
		$this->gc_error->add( 'code', 'message', 'data-bar' );
		$this->gc_error->add( 'code', 'message2', 'data-baz' );

		$this->assertSame( 'data-baz', $this->gc_error->get_error_data( 'code' ) );
	}

	/**
	 * @covers ::add_data
	 */
	public function test_add_data_with_empty_data_empty_code_should_create_orphaned_data_with_no_error() {
		$this->gc_error->add_data( '' );

		$this->assertEmpty( $this->gc_error->errors );
	}

	/**
	 * @covers ::add_data
	 */
	public function test_add_data_with_empty_data_empty_code_no_errors_should_create_data_under_an_empty_code_key() {
		$this->gc_error->add_data( '' );

		$this->assertSameSets( array( '' => '' ), $this->gc_error->error_data );
	}

	/**
	 * @covers ::add_data
	 * @covers ::get_error_data
	 */
	public function test_add_data_with_data_empty_code_and_one_error_should_store_the_data_under_that_code() {
		$this->gc_error->add( 'code', 'message' );
		$this->gc_error->add_data( 'data' );

		$this->assertSame( 'data', $this->gc_error->get_error_data( 'code' ) );
	}

	/**
	 * @covers ::add_data
	 * @covers ::get_error_data
	 */
	public function test_add_data_with_data_empty_code_and_multiple_errors_with_different_codes_should_store_it_under_the_first_code() {
		$this->gc_error->add( 'code', 'message' );
		$this->gc_error->add( 'code2', 'message2' );

		$this->gc_error->add_data( 'data' );

		$this->assertSame( 'data', $this->gc_error->get_error_data( 'code' ) );
	}

	/**
	 * @covers ::add_data
	 * @covers ::get_error_data
	 */
	public function test_add_data_with_data_empty_code_and_multiple_errors_with_same_code_should_store_it_under_the_first_code() {
		$this->gc_error->add( 'code', 'message' );
		$this->gc_error->add( 'code2', 'message2' );
		$this->gc_error->add( 'code', 'message3' );

		$this->gc_error->add_data( 'data' );

		$this->assertSame( 'data', $this->gc_error->get_error_data( 'code' ) );
	}

	/**
	 * @covers ::add_data
	 */
	public function test_add_data_with_data_and_code_and_no_errors_should_create_orphaned_data_with_no_error() {
		$this->gc_error->add_data( 'data', 'code' );

		$this->assertEmpty( $this->gc_error->errors );
	}

	/**
	 * @covers ::add_data
	 */
	public function test_add_data_with_data_and_code_no_errors_should_create_data_under_that_code_key() {
		$this->gc_error->add_data( 'data', 'code' );

		$this->assertSameSets( array( 'code' => 'data' ), $this->gc_error->error_data );
	}

	/**
	 * @covers ::add_data
	 */
	public function test_add_data_with_data_and_code_one_error_different_code_should_create_orphaned_data_with_no_error() {
		$this->gc_error->add( 'code', 'message' );

		$this->gc_error->add_data( 'data', 'code2' );

		$this->assertSameSetsWithIndex( array( 'code' => array( 'message' ) ), $this->gc_error->errors );
	}

	/**
	 * @covers ::add_data
	 */
	public function test_add_data_with_data_and_code_one_error_different_code_should_create_data_under_that_code_key() {
		$this->gc_error->add( 'code', 'message' );

		$this->gc_error->add_data( 'data', 'code2' );

		$this->assertSameSetsWithIndex( array( 'code2' => 'data' ), $this->gc_error->error_data );
	}

	/**
	 * @covers ::add_data
	 * @covers ::get_error_data
	 */
	public function test_add_data_with_data_and_code_should_add_data() {
		$this->gc_error->add( 'code', 'message' );

		$this->gc_error->add_data( 'data', 'code' );

		$this->assertSame( 'data', $this->gc_error->get_error_data( 'code' ) );
	}

	/**
	 * @covers ::remove
	 */
	public function test_remove_with_no_errors_should_affect_nothing() {
		$before = $this->gc_error->errors;

		$this->gc_error->remove( 'code' );

		$after = $this->gc_error->errors;

		$this->assertSameSetsWithIndex( $before, $after );
	}

	/**
	 * @covers ::remove
	 */
	public function test_remove_empty_code_no_errors_should_affect_nothing() {
		$before = $this->gc_error->errors;

		$this->gc_error->remove( '' );

		$after = $this->gc_error->errors;

		$this->assertSameSetsWithIndex( $before, $after );
	}

	/**
	 * @covers ::remove
	 */
	public function test_remove_empty_code_and_one_error_with_empty_string_code_should_remove_error() {
		$before = $this->gc_error->errors;

		$this->gc_error->add( '', 'message' );

		$this->gc_error->remove( '' );

		$after = $this->gc_error->errors;

		$this->assertSameSetsWithIndex( $before, $after );
	}

	/**
	 * @covers ::remove
	 */
	public function test_remove_empty_code_and_one_error_with_empty_string_code_should_remove_error_data() {
		$this->gc_error->add( '', 'message', 'data' );

		$this->gc_error->remove( '' );

		$after = $this->gc_error->error_data;

		$this->assertEmpty( $this->gc_error->error_data );
	}

	/**
	 * @covers ::remove
	 */
	public function test_remove_should_remove_the_error_with_the_given_code() {
		$this->gc_error->add( 'code', 'message' );

		$this->gc_error->remove( 'code' );

		$this->assertEmpty( $this->gc_error->errors );
	}

	/**
	 * @covers ::remove
	 * @covers ::get_error_data
	 * @covers ::get_all_error_data
	 */
	public function test_remove_should_remove_the_error_data_associated_with_the_given_code() {
		$this->gc_error->add( 'code', 'message', 'data' );
		$this->gc_error->add( 'code', 'message', 'data2' );

		$this->gc_error->remove( 'code' );

		$this->assertEmpty( $this->gc_error->error_data );
		$this->assertEmpty( $this->gc_error->get_error_data( 'code' ) );
		$this->assertEmpty( $this->gc_error->get_all_error_data( 'code' ) );
	}

	/**
	 * @covers ::merge_from
	 * @covers ::get_error_messages
	 * @covers ::get_error_data
	 * @covers ::get_all_error_data
	 * @covers ::get_error_message
	 */
	public function test_merge_from_should_copy_other_error_into_instance() {
		$this->gc_error->add( 'code1', 'message1', 'data1' );

		$other = new GC_Error( 'code1', 'message2', 'data2' );
		$other->add( 'code2', 'message3' );
		$this->gc_error->merge_from( $other );

		$this->assertSame( array( 'message1', 'message2' ), $this->gc_error->get_error_messages( 'code1' ) );
		$this->assertSame( 'data2', $this->gc_error->get_error_data( 'code1' ) );
		$this->assertSame( array( 'data1', 'data2' ), $this->gc_error->get_all_error_data( 'code1' ) );
		$this->assertSame( 'message3', $this->gc_error->get_error_message( 'code2' ) );
	}

	/**
	 * @covers ::merge_from
	 * @covers ::has_errors
	 */
	public function test_merge_from_with_no_errors_should_not_add_to_instance() {
		$other = new GC_Error();

		$this->gc_error->merge_from( $other );

		$this->assertFalse( $this->gc_error->has_errors() );
	}

	/**
	 * @covers ::export_to
	 * @covers ::get_error_messages
	 * @covers ::get_error_data
	 * @covers ::get_all_error_data
	 * @covers ::get_error_message
	 */
	public function test_export_to_should_copy_instance_into_other_error() {
		$other = new GC_Error();
		$other->add( 'code1', 'message1', 'data1' );

		$this->gc_error->add( 'code1', 'message2', 'data2' );
		$this->gc_error->add( 'code2', 'message3' );

		$this->gc_error->export_to( $other );

		$this->assertSame( array( 'message1', 'message2' ), $other->get_error_messages( 'code1' ) );
		$this->assertSame( 'data2', $other->get_error_data( 'code1' ) );
		$this->assertSame( array( 'data1', 'data2' ), $other->get_all_error_data( 'code1' ) );
		$this->assertSame( 'message3', $other->get_error_message( 'code2' ) );
	}

	/**
	 * @covers ::export_to
	 * @covers ::has_errors
	 */
	public function test_export_to_with_no_errors_should_not_add_to_other_error() {
		$other = new GC_Error();

		$this->gc_error->export_to( $other );

		$this->assertFalse( $other->has_errors() );
	}
}
