<?php

class TitanPress_Attachment {

	function __construct($attachment) {

		$this->id = $attachment->ID;
		$this->guid = $attachment->guid;
		$this->slug = $attachment->post_name;
		$this->title = apply_filters( 'the_title', $attachment->post_title );
		$this->thumbnail_url = $this->get_image_object($attachment->ID, 'thumbnail');
		$this->medium_url = $this->get_image_object($attachment->ID, 'medium');
		$this->large_url = $this->get_image_object($attachment->ID, 'large');
		$this->full_url = $this->get_image_object($attachment->ID, 'full');
		$this->mime_type = $attachment->post_mime_type;

	}

	private function get_image_object($id, $size) {

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