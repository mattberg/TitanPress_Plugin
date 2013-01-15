<?php

class TitanPress_Attachment {

	function __construct( $obj = null ) {

		if (is_object( $obj )) {
			$this->import( $obj );
		} elseif (is_int( $obj )) {
			$this->import( get_post($obj) );
		}

	}

	private function import( $obj ) {

		$this->id = $obj->ID;
		$this->guid = $obj->guid;
		$this->slug = $obj->post_name;
		$this->title = apply_filters( 'the_title', $obj->post_title );
		$this->type = $obj->post_mime_type;
		$this->thumbnail = $this->get_image_object($obj->ID, 'thumbnail');
		$this->medium = $this->get_image_object($obj->ID, 'medium');
		$this->large = $this->get_image_object($obj->ID, 'large');
		$this->full = $this->get_image_object($obj->ID, 'full');

	}

	private function get_image_object( $id, $size ) {

		$image = new stdClass;

		$image_src = wp_get_attachment_image_src($id, $size);
		if ($image_src) {
			$image->url = $image_src[0];
			$image->width = $image_src[1];
			$image->height = $image_src[2];
		}

		return $image;

	}

}