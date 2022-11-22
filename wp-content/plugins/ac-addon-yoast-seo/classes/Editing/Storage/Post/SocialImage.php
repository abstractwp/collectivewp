<?php

namespace ACA\YoastSeo\Editing\Storage\Post;

use ACP;

class SocialImage implements ACP\Editing\Storage {

	/**
	 * @var string
	 */
	private $meta_key_id;

	/**
	 * @var string
	 */
	private $meta_key_url;

	public function __construct( $meta_key_id, $meta_key_url ) {
		$this->meta_key_id = $meta_key_id;
		$this->meta_key_url = $meta_key_url;
	}

	public function get( $id ) {
		return get_post_meta( $id, $this->meta_key_id, true );
	}

	public function update( $id, $value ) {
		update_post_meta( $id, $this->meta_key_id, $value );

		return update_post_meta( $id, $this->meta_key_url, $value ? wp_get_attachment_url( $value ) : '' );
	}

}