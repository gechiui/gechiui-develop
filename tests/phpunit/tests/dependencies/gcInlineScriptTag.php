<?php

/**
 * Test gc_get_inline_script_tag() and gc_print_inline_script_tag().
 *
 * @group dependencies
 * @group scripts
 * @covers ::gc_get_inline_script_tag
 * @covers ::gc_print_inline_script_tag
 */
class Tests_Functions_gcInlineScriptTag extends GC_UnitTestCase {

	private $event_handler = <<<'JS'
document.addEventListener( 'DOMContentLoaded', function () {
	document.getElementById( 'elementID' )
			.addEventListener( 'click', function( event ) {
				event.preventDefault();
			});
});
JS;

	public function get_inline_script_tag_type_set() {
		add_theme_support( 'html5', array( 'script' ) );

		$this->assertSame(
			'<script type="application/javascript" nomodule>' . "\n{$this->event_handler}\n</script>\n",
			gc_get_inline_script_tag(
				$this->event_handler,
				array(
					'type'     => 'application/javascript',
					'async'    => false,
					'nomodule' => true,
				)
			)
		);

		remove_theme_support( 'html5' );

		$this->assertSame(
			'<script type="application/javascript" nomodule>' . "\n{$this->event_handler}\n</script>\n",
			gc_get_inline_script_tag(
				$this->event_handler,
				array(
					'type'     => 'application/javascript',
					'async'    => false,
					'nomodule' => true,
				)
			)
		);
	}

	public function test_get_inline_script_tag_type_not_set() {
		add_theme_support( 'html5', array( 'script' ) );

		$this->assertSame(
			"<script nomodule>\n{$this->event_handler}\n</script>\n",
			gc_get_inline_script_tag(
				$this->event_handler,
				array(
					'async'    => false,
					'nomodule' => true,
				)
			)
		);

		remove_theme_support( 'html5' );
	}

	public function test_get_inline_script_tag_unescaped_src() {
		add_theme_support( 'html5', array( 'script' ) );

		$this->assertSame(
			"<script>\n{$this->event_handler}\n</script>\n",
			gc_get_inline_script_tag( $this->event_handler )
		);

		remove_theme_support( 'html5' );
	}

	public function test_print_script_tag_prints_get_inline_script_tag() {
		add_filter(
			'gc_inline_script_attributes',
			static function ( $attributes ) {
				if ( isset( $attributes['id'] ) && 'utils-js-extra' === $attributes['id'] ) {
					$attributes['async'] = true;
				}
				return $attributes;
			}
		);

		add_theme_support( 'html5', array( 'script' ) );

		$attributes = array(
			'id'       => 'utils-js-before',
			'nomodule' => true,
		);

		$this->assertSame(
			gc_get_inline_script_tag( $this->event_handler, $attributes ),
			get_echo(
				'gc_print_inline_script_tag',
				array(
					$this->event_handler,
					$attributes,
				)
			)
		);

		remove_theme_support( 'html5' );

		$this->assertSame(
			gc_get_inline_script_tag( $this->event_handler, $attributes ),
			get_echo(
				'gc_print_inline_script_tag',
				array(
					$this->event_handler,
					$attributes,
				)
			)
		);
	}
}
