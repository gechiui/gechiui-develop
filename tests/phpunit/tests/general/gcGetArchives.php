<?php

/**
 * @group general
 * @group template
 * @covers ::gc_get_archives
 */
class Tests_General_gcGetArchives extends GC_UnitTestCase {
	public function set_up() {
		parent::set_up();

		gc_cache_delete( 'last_changed', 'posts' );
	}

	/**
	 * @ticket 23206
	 */
	public function test_get_archives_cache() {
		global $gcdb;

		self::factory()->post->create_many( 3, array( 'post_type' => 'post' ) );
		gc_cache_delete( 'last_changed', 'posts' );
		$this->assertFalse( gc_cache_get( 'last_changed', 'posts' ) );

		$num_queries = $gcdb->num_queries;

		// Cache is not primed, expect 1 query.
		$result = gc_get_archives(
			array(
				'type' => 'monthly',
				'echo' => false,
			)
		);
		$this->assertIsString( $result );
		$time1 = gc_cache_get( 'last_changed', 'posts' );
		$this->assertNotEmpty( $time1 );
		$this->assertSame( $num_queries + 1, $gcdb->num_queries );

		$num_queries = $gcdb->num_queries;

		// Cache is primed, expect no queries.
		$result = gc_get_archives(
			array(
				'type' => 'monthly',
				'echo' => false,
			)
		);
		$this->assertIsString( $result );
		$this->assertSame( $time1, gc_cache_get( 'last_changed', 'posts' ) );
		$this->assertSame( $num_queries, $gcdb->num_queries );

		// Change args, resulting in a different query string. Cache is not primed, expect 1 query.
		$result = gc_get_archives(
			array(
				'type'  => 'monthly',
				'echo'  => false,
				'order' => 'ASC',
			)
		);
		$this->assertIsString( $result );
		$this->assertSame( $time1, gc_cache_get( 'last_changed', 'posts' ) );
		$this->assertSame( $num_queries + 1, $gcdb->num_queries );

		$num_queries = $gcdb->num_queries;

		// Cache is primed, expect no queries.
		$result = gc_get_archives(
			array(
				'type'  => 'monthly',
				'echo'  => false,
				'order' => 'ASC',
			)
		);
		$this->assertIsString( $result );
		$this->assertSame( $time1, gc_cache_get( 'last_changed', 'posts' ) );
		$this->assertSame( $num_queries, $gcdb->num_queries );

		$num_queries = $gcdb->num_queries;

		// Change type. Cache is not primed, expect 1 query.
		$result = gc_get_archives(
			array(
				'type' => 'yearly',
				'echo' => false,
			)
		);
		$this->assertIsString( $result );
		$this->assertSame( $time1, gc_cache_get( 'last_changed', 'posts' ) );
		$this->assertSame( $num_queries + 1, $gcdb->num_queries );

		$num_queries = $gcdb->num_queries;

		// Cache is primed, expect no queries.
		$result = gc_get_archives(
			array(
				'type' => 'yearly',
				'echo' => false,
			)
		);
		$this->assertIsString( $result );
		$this->assertSame( $time1, gc_cache_get( 'last_changed', 'posts' ) );
		$this->assertSame( $num_queries, $gcdb->num_queries );

		// Change type. Cache is not primed, expect 1 query.
		$result = gc_get_archives(
			array(
				'type' => 'daily',
				'echo' => false,
			)
		);
		$this->assertIsString( $result );
		$this->assertSame( $time1, gc_cache_get( 'last_changed', 'posts' ) );
		$this->assertSame( $num_queries + 1, $gcdb->num_queries );

		$num_queries = $gcdb->num_queries;

		// Cache is primed, expect no queries.
		$result = gc_get_archives(
			array(
				'type' => 'daily',
				'echo' => false,
			)
		);
		$this->assertIsString( $result );
		$this->assertSame( $time1, gc_cache_get( 'last_changed', 'posts' ) );
		$this->assertSame( $num_queries, $gcdb->num_queries );

		// Change type. Cache is not primed, expect 1 query.
		$result = gc_get_archives(
			array(
				'type' => 'weekly',
				'echo' => false,
			)
		);
		$this->assertIsString( $result );
		$this->assertSame( $time1, gc_cache_get( 'last_changed', 'posts' ) );
		$this->assertSame( $num_queries + 1, $gcdb->num_queries );

		$num_queries = $gcdb->num_queries;

		// Cache is primed, expect no queries.
		$result = gc_get_archives(
			array(
				'type' => 'weekly',
				'echo' => false,
			)
		);
		$this->assertIsString( $result );
		$this->assertSame( $time1, gc_cache_get( 'last_changed', 'posts' ) );
		$this->assertSame( $num_queries, $gcdb->num_queries );

		// Change type. Cache is not primed, expect 1 query.
		$result = gc_get_archives(
			array(
				'type' => 'postbypost',
				'echo' => false,
			)
		);
		$this->assertIsString( $result );
		$this->assertSame( $time1, gc_cache_get( 'last_changed', 'posts' ) );
		$this->assertSame( $num_queries + 1, $gcdb->num_queries );

		$num_queries = $gcdb->num_queries;

		// Cache is primed, expect no queries.
		$result = gc_get_archives(
			array(
				'type' => 'postbypost',
				'echo' => false,
			)
		);
		$this->assertIsString( $result );
		$this->assertSame( $time1, gc_cache_get( 'last_changed', 'posts' ) );
		$this->assertSame( $num_queries, $gcdb->num_queries );
	}
}
