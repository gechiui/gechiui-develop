<?php
/**
 * Unit tests covering GC_Widget_Media_Audio functionality.
 *
 * @package    GeChiUI
 * @subpackage widgets
 */

/**
 * Test gc-includes/widgets/class-gc-widget-media-audio.php
 *
 * @group widgets
 */
class Tests_Widgets_gcWidgetMediaAudio extends GC_UnitTestCase {

	/**
	 * Clean up global scope.
	 *
	 * @global GC_Scripts $gc_scripts
	 * @global GC_Styles $gc_styles
	 */
	public function clean_up_global_scope() {
		global $gc_scripts, $gc_styles;
		parent::clean_up_global_scope();
		$gc_scripts = null;
		$gc_styles  = null;
	}

	/**
	 * Test get_instance_schema method.
	 *
	 * @covers GC_Widget_Media_Audio::get_instance_schema
	 */
	public function test_get_instance_schema() {
		$gc_widget_audio = new GC_Widget_Media_Audio();
		$schema          = $gc_widget_audio->get_instance_schema();

		$this->assertSameSets(
			array_merge(
				array(
					'attachment_id',
					'preload',
					'loop',
					'title',
					'url',
				),
				gc_get_audio_extensions()
			),
			array_keys( $schema )
		);
	}

	/**
	 * Test get_instance_schema filtering.
	 *
	 * @covers GC_Widget_Media_Audio::get_instance_schema
	 *
	 * @ticket 45029
	 */
	public function test_get_instance_schema_filtering() {
		$gc_widget_audio = new GC_Widget_Media_Audio();
		$schema          = $gc_widget_audio->get_instance_schema();

		add_filter( 'widget_media_audio_instance_schema', array( $this, 'filter_instance_schema' ), 10, 2 );
		$schema = $gc_widget_audio->get_instance_schema();

		$this->assertTrue( $schema['loop']['default'] );
	}

	/**
	 * Filters instance schema.
	 *
	 * @since 5.2.0
	 *
	 * @param array                 $schema Schema.
	 * @param GC_Widget_Media_Audio $widget Widget.
	 * @return array
	 */
	public function filter_instance_schema( $schema, $widget ) {
		// Override the default loop value (false).
		$schema['loop']['default'] = true;
		return $schema;
	}

	/**
	 * Test constructor.
	 *
	 * @covers GC_Widget_Media_Audio::__construct
	 */
	public function test_constructor() {
		$widget = new GC_Widget_Media_Audio();

		$this->assertArrayHasKey( 'mime_type', $widget->widget_options );
		$this->assertArrayHasKey( 'customize_selective_refresh', $widget->widget_options );
		$this->assertArrayHasKey( 'description', $widget->widget_options );
		$this->assertTrue( $widget->widget_options['customize_selective_refresh'] );
		$this->assertSame( 'audio', $widget->widget_options['mime_type'] );
		$this->assertSameSets(
			array(
				'add_to_widget',
				'replace_media',
				'edit_media',
				'media_library_state_multi',
				'media_library_state_single',
				'missing_attachment',
				'no_media_selected',
				'add_media',
				'unsupported_file_type',
			),
			array_keys( $widget->l10n )
		);
	}

	/**
	 * Test get_instance_schema method.
	 *
	 * @covers GC_Widget_Media_Audio::update
	 */
	public function test_update() {
		$widget   = new GC_Widget_Media_Audio();
		$instance = array();

		// Should return valid attachment ID.
		$expected = array(
			'attachment_id' => 1,
		);
		$result   = $widget->update( $expected, $instance );
		$this->assertSame( $expected, $result );

		// Should filter invalid attachment ID.
		$result = $widget->update(
			array(
				'attachment_id' => 'media',
			),
			$instance
		);
		$this->assertSame( $result, $instance );

		// Should return valid attachment url.
		$expected = array(
			'url' => 'https://chickenandribs.org',
		);
		$result   = $widget->update( $expected, $instance );
		$this->assertSame( $expected, $result );

		// Should filter invalid attachment url.
		$result = $widget->update(
			array(
				'url' => 'not_a_url',
			),
			$instance
		);
		$this->assertNotSame( $result, $instance );
		$this->assertStringStartsWith( 'http://', $result['url'] );

		// Should return loop setting.
		$expected = array(
			'loop' => true,
		);
		$result   = $widget->update( $expected, $instance );
		$this->assertSame( $expected, $result );

		// Should filter invalid loop setting.
		$result = $widget->update(
			array(
				'loop' => 'not-boolean',
			),
			$instance
		);
		$this->assertSame( $result, $instance );

		// Should return valid attachment title.
		$expected = array(
			'title' => 'An audio sample of parrots',
		);
		$result   = $widget->update( $expected, $instance );
		$this->assertSame( $expected, $result );

		// Should filter invalid attachment title.
		$result = $widget->update(
			array(
				'title' => '<h1>Cute Baby Goats</h1>',
			),
			$instance
		);
		$this->assertNotSame( $result, $instance );

		// Should return valid preload setting.
		$expected = array(
			'preload' => 'none',
		);
		$result   = $widget->update( $expected, $instance );
		$this->assertSame( $expected, $result );

		// Should filter invalid preload setting.
		$result = $widget->update(
			array(
				'preload' => 'nope',
			),
			$instance
		);
		$this->assertSame( $result, $instance );

		// Should filter invalid key.
		$result = $widget->update(
			array(
				'h4x' => 'value',
			),
			$instance
		);
		$this->assertSame( $result, $instance );
	}

	/**
	 * Test render_media method.
	 *
	 * @covers GC_Widget_Media_Audio::render_media
	 */
	public function test_render_media() {
		$test_audio_file = __FILE__ . '../../data/uploads/small-audio.mp3';
		$widget          = new GC_Widget_Media_Audio();
		$attachment_id   = self::factory()->attachment->create_object(
			array(
				'file'           => $test_audio_file,
				'post_parent'    => 0,
				'post_mime_type' => 'audio/mp3',
				'post_title'     => 'Test Audio',
			)
		);
		gc_update_attachment_metadata( $attachment_id, gc_generate_attachment_metadata( $attachment_id, $test_audio_file ) );

		// Should be empty when there is no attachment_id.
		ob_start();
		$widget->render_media( array() );
		$output = ob_get_clean();
		$this->assertEmpty( $output );

		// Should be empty when there is an invalid attachment_id.
		ob_start();
		$widget->render_media(
			array(
				'attachment_id' => 777,
			)
		);
		$output = ob_get_clean();
		$this->assertEmpty( $output );

		// Tests with audio from library.
		ob_start();
		$widget->render_media(
			array(
				'attachment_id' => $attachment_id,
			)
		);
		$output = ob_get_clean();

		// Check default outputs.
		$this->assertStringContainsString( 'preload="none"', $output );
		$this->assertStringContainsString( 'class="gc-audio-shortcode"', $output );
		$this->assertStringContainsString( 'small-audio.mp3', $output );

		ob_start();
		$widget->render_media(
			array(
				'attachment_id' => $attachment_id,
				'title'         => 'Funny',
				'preload'       => 'auto',
				'loop'          => true,
			)
		);
		$output = ob_get_clean();

		// Custom attributes.
		$this->assertStringContainsString( 'preload="auto"', $output );
		$this->assertStringContainsString( 'loop="1"', $output );
	}

	/**
	 * Test enqueue_preview_scripts method.
	 *
	 * @global GC_Scripts $gc_scripts
	 * @global GC_Styles $gc_styles
	 * @covers GC_Widget_Media_Audio::enqueue_preview_scripts
	 */
	public function test_enqueue_preview_scripts() {
		global $gc_scripts, $gc_styles;
		$gc_scripts = null;
		$gc_styles  = null;
		$widget     = new GC_Widget_Media_Audio();

		$this->assertFalse( gc_script_is( 'gc-mediaelement' ) );
		$this->assertFalse( gc_style_is( 'gc-mediaelement' ) );

		$widget->enqueue_preview_scripts();

		$this->assertTrue( gc_script_is( 'gc-mediaelement' ) );
		$this->assertTrue( gc_style_is( 'gc-mediaelement' ) );
	}

	/**
	 * Test enqueue_admin_scripts method.
	 *
	 * @covers GC_Widget_Media_Audio::enqueue_admin_scripts
	 */
	public function test_enqueue_admin_scripts() {
		set_current_screen( 'widgets.php' );
		$widget = new GC_Widget_Media_Audio();
		$widget->enqueue_admin_scripts();

		$this->assertTrue( gc_script_is( 'media-audio-widget' ) );
	}

	/**
	 * Test render_control_template_scripts method.
	 *
	 * @covers GC_Widget_Media_Audio::render_control_template_scripts
	 */
	public function test_render_control_template_scripts() {
		$widget = new GC_Widget_Media_Audio();

		ob_start();
		$widget->render_control_template_scripts();
		$output = ob_get_clean();

		$this->assertStringContainsString( '<script type="text/html" id="tmpl-gc-media-widget-audio-preview">', $output );
	}
}
