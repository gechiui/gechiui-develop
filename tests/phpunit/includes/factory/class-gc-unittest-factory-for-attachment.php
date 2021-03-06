<?php

class GC_UnitTest_Factory_For_Attachment extends GC_UnitTest_Factory_For_Post {

	/**
	 * Create an attachment fixture.
	 *
	 * @param array $args {
	 *     Array of arguments. Accepts all arguments that can be passed to
	 *     gc_insert_attachment(), in addition to the following:
	 *     @type int    $post_parent ID of the post to which the attachment belongs.
	 *     @type string $file        Path of the attached file.
	 * }
	 * @param int   $legacy_parent Deprecated.
	 * @param array $legacy_args   Deprecated.
	 *
	 * @return int|GC_Error The attachment ID on success. The value 0 or GC_Error on failure.
	 */
	public function create_object( $args, $legacy_parent = 0, $legacy_args = array() ) {
		// Backward compatibility for legacy argument format.
		if ( is_string( $args ) ) {
			$file                = $args;
			$args                = $legacy_args;
			$args['post_parent'] = $legacy_parent;
			$args['file']        = $file;
		}

		$r = array_merge(
			array(
				'file'        => '',
				'post_parent' => 0,
			),
			$args
		);

		return gc_insert_attachment( $r, $r['file'], $r['post_parent'] );
	}

	/**
	 * Saves an attachment.
	 *
	 * @param string $file   The file name to create attachment object for.
	 * @param int    $parent ID of the post to attach the file to.
	 *
	 * @return int|GC_Error The attachment ID on success. The value 0 or GC_Error on failure.
	 */
	public function create_upload_object( $file, $parent = 0 ) {
		$contents = file_get_contents( $file );
		$upload   = gc_upload_bits( gc_basename( $file ), null, $contents );

		$type = '';
		if ( ! empty( $upload['type'] ) ) {
			$type = $upload['type'];
		} else {
			$mime = gc_check_filetype( $upload['file'] );
			if ( $mime ) {
				$type = $mime['type'];
			}
		}

		$attachment = array(
			'post_title'     => gc_basename( $upload['file'] ),
			'post_content'   => '',
			'post_type'      => 'attachment',
			'post_parent'    => $parent,
			'post_mime_type' => $type,
			'guid'           => $upload['url'],
		);

		// Save the data.
		$id = gc_insert_attachment( $attachment, $upload['file'], $parent );
		gc_update_attachment_metadata( $id, gc_generate_attachment_metadata( $id, $upload['file'] ) );

		return $id;
	}
}
