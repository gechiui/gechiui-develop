<?php

/**
 * @group admin
 */
class Tests_Admin_gcCommentsListTable extends GC_UnitTestCase {

	/**
	 * @var GC_Comments_List_Table
	 */
	protected $table;

	function set_up() {
		parent::set_up();
		$this->table = _get_list_table( 'GC_Comments_List_Table', array( 'screen' => 'edit-comments' ) );
	}

	/**
	 * @ticket 40188
	 *
	 * @covers GC_Comments_List_Table::extra_tablenav
	 */
	public function test_filter_button_should_not_be_shown_if_there_are_no_comments() {
		ob_start();
		$this->table->extra_tablenav( 'top' );
		$output = ob_get_clean();

		$this->assertStringNotContainsString( 'id="post-query-submit"', $output );
	}

	/**
	 * @ticket 40188
	 *
	 * @covers GC_Comments_List_Table::extra_tablenav
	 */
	public function test_filter_button_should_be_shown_if_there_are_comments() {
		$post_id    = self::factory()->post->create();
		$comment_id = self::factory()->comment->create(
			array(
				'comment_post_ID'  => $post_id,
				'comment_approved' => '1',
			)
		);

		$this->table->prepare_items();

		ob_start();
		$this->table->extra_tablenav( 'top' );
		$output = ob_get_clean();

		$this->assertStringContainsString( 'id="post-query-submit"', $output );
	}

	/**
	 * @ticket 40188
	 *
	 * @covers GC_Comments_List_Table::extra_tablenav
	 */
	public function test_filter_comment_type_dropdown_should_be_shown_if_there_are_comments() {
		$post_id    = self::factory()->post->create();
		$comment_id = self::factory()->comment->create(
			array(
				'comment_post_ID'  => $post_id,
				'comment_approved' => '1',
			)
		);

		$this->table->prepare_items();

		ob_start();
		$this->table->extra_tablenav( 'top' );
		$output = ob_get_clean();

		$this->assertStringContainsString( 'id="filter-by-comment-type"', $output );
		$this->assertStringContainsString( "<option value='comment'>", $output );
	}

	/**
	 * @ticket 38341
	 *
	 * @covers GC_Comments_List_Table::extra_tablenav
	 */
	public function test_empty_trash_button_should_not_be_shown_if_there_are_no_comments() {
		ob_start();
		$this->table->extra_tablenav( 'top' );
		$output = ob_get_clean();

		$this->assertStringNotContainsString( 'id="delete_all"', $output );
	}

	/**
	 * @ticket 19278
	 *
	 * @covers GC_Comments_List_Table::bulk_actions
	 */
	public function test_bulk_action_menu_supports_options_and_optgroups() {
		add_filter(
			'bulk_actions-edit-comments',
			static function() {
				return array(
					'delete'       => 'Delete',
					'Change State' => array(
						'feature' => 'Featured',
						'sale'    => 'On Sale',
					),
				);
			}
		);

		ob_start();
		$this->table->bulk_actions();
		$output = ob_get_clean();

		$expected = <<<'OPTIONS'
<option value="delete">Delete</option>
	<optgroup label="Change State">
		<option value="feature">Featured</option>
		<option value="sale">On Sale</option>
	</optgroup>
OPTIONS;
		$expected = str_replace( "\r\n", "\n", $expected );

		$this->assertStringContainsString( $expected, $output );
	}

	/**
	 * @ticket 45089
	 *
	 * @covers GC_Comments_List_Table::print_column_headers
	 */
	public function test_sortable_columns() {
		$override_sortable_columns = array(
			'author'   => array( 'comment_author', true ),
			'response' => 'comment_post_ID',
			'date'     => array( 'comment_date', 'dEsC' ), // The ordering support should be case-insensitive.
		);

		// Stub the get_sortable_columns() method.
		$object = $this->getMockBuilder( 'GC_Comments_List_Table' )
			->setConstructorArgs( array( array( 'screen' => 'edit-comments' ) ) )
			->setMethods( array( 'get_sortable_columns' ) )
			->getMock();

		// Change the null return value of the stubbed get_sortable_columns() method.
		$object->method( 'get_sortable_columns' )
			->willReturn( $override_sortable_columns );

		$output = get_echo( array( $object, 'print_column_headers' ) );

		$this->assertStringContainsString( '?orderby=comment_author&#038;order=desc', $output, 'Mismatch of the default link ordering for comment author column. Should be desc.' );
		$this->assertStringContainsString( 'column-author sortable asc', $output, 'Mismatch of CSS classes for the comment author column.' );

		$this->assertStringContainsString( '?orderby=comment_post_ID&#038;order=asc', $output, 'Mismatch of the default link ordering for comment response column. Should be asc.' );
		$this->assertStringContainsString( 'column-response sortable desc', $output, 'Mismatch of CSS classes for the comment post ID column.' );

		$this->assertStringContainsString( '?orderby=comment_date&#038;order=desc', $output, 'Mismatch of the default link ordering for comment date column. Should be asc.' );
		$this->assertStringContainsString( 'column-date sortable asc', $output, 'Mismatch of CSS classes for the comment date column.' );
	}

	/**
	 * @ticket 45089
	 *
	 * @covers GC_Comments_List_Table::print_column_headers
	 */
	public function test_sortable_columns_with_current_ordering() {
		$override_sortable_columns = array(
			'author'   => array( 'comment_author', false ),
			'response' => 'comment_post_ID',
			'date'     => array( 'comment_date', 'asc' ), // We will override this with current ordering.
		);

		// Current ordering.
		$_GET['orderby'] = 'comment_date';
		$_GET['order']   = 'desc';

		// Stub the get_sortable_columns() method.
		$object = $this->getMockBuilder( 'GC_Comments_List_Table' )
			->setConstructorArgs( array( array( 'screen' => 'edit-comments' ) ) )
			->setMethods( array( 'get_sortable_columns' ) )
			->getMock();

		// Change the null return value of the stubbed get_sortable_columns() method.
		$object->method( 'get_sortable_columns' )
			->willReturn( $override_sortable_columns );

		$output = get_echo( array( $object, 'print_column_headers' ) );

		$this->assertStringContainsString( '?orderby=comment_author&#038;order=asc', $output, 'Mismatch of the default link ordering for comment author column. Should be asc.' );
		$this->assertStringContainsString( 'column-author sortable desc', $output, 'Mismatch of CSS classes for the comment author column.' );

		$this->assertStringContainsString( '?orderby=comment_post_ID&#038;order=asc', $output, 'Mismatch of the default link ordering for comment response column. Should be asc.' );
		$this->assertStringContainsString( 'column-response sortable desc', $output, 'Mismatch of CSS classes for the comment post ID column.' );

		$this->assertStringContainsString( '?orderby=comment_date&#038;order=asc', $output, 'Mismatch of the current link ordering for comment date column. Should be asc.' );
		$this->assertStringContainsString( 'column-date sorted desc', $output, 'Mismatch of CSS classes for the comment date column.' );
	}

}
