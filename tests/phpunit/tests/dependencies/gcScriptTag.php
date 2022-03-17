<?php

/**
 * Test gc_get_script_tag() and gc_print_script_tag().
 *
 * @group dependencies
 * @group scripts
 */
class Tests_Functions_gcScriptTag extends GC_UnitTestCase {

	public function get_script_tag_type_set() {
		add_theme_support( 'html5', array( 'script' ) );

		$this->assertSame(
			'<script src="https://localhost/PATH/FILE.js" type="application/javascript" nomodule></script>' . "\n",
			gc_get_script_tag(
				array(
					'type'     => 'application/javascript',
					'src'      => 'https://localhost/PATH/FILE.js',
					'async'    => false,
					'nomodule' => true,
				)
			)
		);

		remove_theme_support( 'html5' );

		$this->assertSame(
			'<script src="https://localhost/PATH/FILE.js" type="application/javascript" nomodule></script>' . "\n",
			gc_get_script_tag(
				array(
					'src'      => 'https://localhost/PATH/FILE.js',
					'type'     => 'application/javascript',
					'async'    => false,
					'nomodule' => true,
				)
			)
		);
	}

	/**
	 * @covers ::gc_get_script_tag
	 */
	public function test_get_script_tag_type_not_set() {
		add_theme_support( 'html5', array( 'script' ) );

		$this->assertSame(
			'<script src="https://localhost/PATH/FILE.js" nomodule></script>' . "\n",
			gc_get_script_tag(
				array(
					'src'      => 'https://localhost/PATH/FILE.js',
					'async'    => false,
					'nomodule' => true,
				)
			)
		);

		remove_theme_support( 'html5' );
	}

	/**
	 * @covers ::gc_print_script_tag
	 */
	public function test_print_script_tag_prints_get_script_tag() {
		add_filter(
			'gc_script_attributes',
			static function ( $attributes ) {
				if ( isset( $attributes['id'] ) && 'utils-js-extra' === $attributes['id'] ) {
					$attributes['async'] = true;
				}
				return $attributes;
			}
		);

		add_theme_support( 'html5', array( 'script' ) );

		$attributes = array(
			'src'      => 'https://localhost/PATH/FILE.js',
			'id'       => 'utils-js-extra',
			'nomodule' => true,
		);

		$this->assertSame(
			gc_get_script_tag( $attributes ),
			get_echo(
				'gc_print_script_tag',
				array( $attributes )
			)
		);

		remove_theme_support( 'html5' );

		$this->assertSame(
			gc_get_script_tag( $attributes ),
			get_echo(
				'gc_print_script_tag',
				array( $attributes )
			)
		);
	}
}
