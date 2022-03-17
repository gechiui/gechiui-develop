<?php
/**
 * Unit tests covering GC_Widget_Media_Gallery functionality.
 *
 * @package    GeChiUI
 * @subpackage widgets
 */

/**
 * Test gc-includes/widgets/class-gc-widget-media-gallery.php
 *
 * @group widgets
 */
class Tests_Widgets_gcWidgetMediaGallery extends GC_UnitTestCase {

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
	 * @covers GC_Widget_Media_Gallery::get_instance_schema
	 */
	public function test_get_instance_schema() {
		$widget = new GC_Widget_Media_Gallery();
		$schema = $widget->get_instance_schema();

		$this->assertSameSets(
			array(
				'title',
				'ids',
				'columns',
				'size',
				'link_type',
				'orderby_random',
			),
			array_keys( $schema )
		);
	}

	/**
	 * Test update() method.
	 *
	 * @covers GC_Widget_Media_Gallery::render_media
	 * @requires function imagejpeg
	 */
	public function test_render_media() {
		$widget = new GC_Widget_Media_Gallery();

		$attachments = array();
		foreach ( array( 'canola.jpg', 'waffles.jpg' ) as $filename ) {
			$test_image = get_temp_dir() . $filename;
			copy( DIR_TESTDATA . '/images/canola.jpg', $test_image );
			$attachment_id = self::factory()->attachment->create_object(
				array(
					'file'           => $test_image,
					'post_parent'    => 0,
					'post_mime_type' => 'image/jpeg',
					'post_title'     => 'Canola',
				)
			);
			gc_update_attachment_metadata( $attachment_id, gc_generate_attachment_metadata( $attachment_id, $test_image ) );
			$attachments[ $filename ] = $attachment_id;
		}

		$instance            = gc_list_pluck( $widget->get_instance_schema(), 'default' );
		$instance['size']    = 'thumbnail';
		$instance['columns'] = 3;
		$instance['ids']     = array_values( $attachments );
		ob_start();
		$widget->render_media( $instance );
		$output = ob_get_clean();

		$this->assertStringContainsString( 'gallery-columns-3', $output );
		$this->assertStringContainsString( 'gallery-size-thumbnail', $output );
		$this->assertStringContainsString( 'canola', $output );
		$this->assertStringContainsString( 'waffles', $output );
	}

	/**
	 * Test enqueue_admin_scripts() method.
	 *
	 * @covers GC_Widget_Media_Gallery::enqueue_admin_scripts
	 */
	public function test_enqueue_admin_scripts() {
		set_current_screen( 'widgets.php' );
		$widget = new GC_Widget_Media_Gallery();

		$this->assertFalse( gc_script_is( 'media-gallery-widget' ) );

		$widget->enqueue_admin_scripts();

		$this->assertTrue( gc_script_is( 'media-gallery-widget' ) );

		$after = implode( '', gc_scripts()->registered['media-gallery-widget']->extra['after'] );
		$this->assertStringContainsString( 'gc.mediaWidgets.modelConstructors[ "media_gallery" ].prototype', $after );
	}

	/**
	 * Test update() method.
	 *
	 * @covers GC_Widget_Media_Gallery::update
	 */
	public function test_update() {
		$widget   = new GC_Widget_Media_Gallery();
		$schema   = $widget->get_instance_schema();
		$instance = gc_list_pluck( $schema, 'default' );

		// Field: title.
		$instance['title'] = 'Hello <b>World</b> ';
		$instance          = $widget->update( $instance, array() );
		$this->assertSame( 'Hello World', $instance['title'] );

		// Field: ids.
		$instance['ids'] = '1,2,3';
		$instance        = $widget->update( $instance, array() );
		$this->assertSame( array( 1, 2, 3 ), $instance['ids'] );

		$instance['ids'] = array( 1, 2, '3' );
		$instance        = $widget->update( $instance, array() );
		$this->assertSame( array( 1, 2, 3 ), $instance['ids'] );

		$instance['ids'] = array( 'too', 'bad' );
		$instance        = $widget->update( $instance, array( 'ids' => array( 2, 3 ) ) );
		$this->assertSame( array( 2, 3 ), $instance['ids'] );

		// Field: columns.
		$instance['columns'] = 4;
		$instance            = $widget->update( $instance, array() );
		$this->assertSame( 4, $instance['columns'] );

		$instance['columns'] = '2';
		$instance            = $widget->update( $instance, array() );
		$this->assertSame( 2, $instance['columns'] );

		$instance['columns'] = -1; // Under min of 1.
		$instance            = $widget->update( $instance, array( 'columns' => 3 ) );
		$this->assertSame( 3, $instance['columns'] );

		$instance['columns'] = 10; // Over max of 9.
		$instance            = $widget->update( $instance, array( 'columns' => 3 ) );
		$this->assertSame( 3, $instance['columns'] );

		// Field: size.
		$instance['size'] = 'large';
		$instance         = $widget->update( $instance, array() );
		$this->assertSame( 'large', $instance['size'] );

		$instance['size'] = 'bad';
		$instance         = $widget->update( $instance, array( 'size' => 'thumbnail' ) );
		$this->assertSame( 'thumbnail', $instance['size'] );

		// Field: link_type.
		$instance['link_type'] = 'none';
		$instance              = $widget->update( $instance, array() );
		$this->assertSame( 'none', $instance['link_type'] );

		$instance['link_type'] = 'unknown';
		$instance              = $widget->update( $instance, array( 'link_type' => 'file' ) );
		$this->assertSame( 'file', $instance['link_type'] );

		// Field: orderby_random.
		$instance['orderby_random'] = '1';
		$instance                   = $widget->update( $instance, array() );
		$this->assertTrue( $instance['orderby_random'] );

		$instance['orderby_random'] = true;
		$instance                   = $widget->update( $instance, array() );
		$this->assertTrue( $instance['orderby_random'] );

		$instance['orderby_random'] = '';
		$instance                   = $widget->update( $instance, array() );
		$this->assertFalse( $instance['orderby_random'] );

		$instance['orderby_random'] = false;
		$instance                   = $widget->update( $instance, array() );
		$this->assertFalse( $instance['orderby_random'] );
	}

	/**
	 * Test render_control_template_scripts() method.
	 *
	 * @covers GC_Widget_Media_Gallery::render_control_template_scripts
	 */
	public function test_render_control_template_scripts() {
		$widget = new GC_Widget_Media_Gallery();

		ob_start();
		$widget->render_control_template_scripts();
		$output = ob_get_clean();

		$this->assertStringContainsString( '<script type="text/html" id="tmpl-gc-media-widget-gallery-preview">', $output );
	}
}
