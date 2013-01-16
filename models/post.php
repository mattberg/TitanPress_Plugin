<?php

class TitanPress_Post {

	function __construct( $obj = null ) {

		if (is_object( $obj )) {
			$this->import( $obj );
		}

	}

	public function add( $values = null ) {

		unset($values['id']);
		$this->save($values);

	}

	private function save( $values = null ) {

		$data = array();

		$data['post_title'] = 'test';
		$data['post_content'] = 'body';

		$id = wp_insert_post( $data );

		$this->import( get_post($id) );

		return $this->id;

	}

	private function import( $obj ) {

		$this->id = $obj->ID;
		$this->guid = $obj->guid;
		$this->parentId = $obj->post_parent;
		$this->slug = $obj->post_name;
		$this->type = $obj->post_type;
		$this->status = $obj->post_status;
		$this->title = apply_filters( 'the_title', get_the_title() );
		$this->titlePlain = apply_filters( 'the_title_plain', get_the_title() );
		$this->content = apply_filters( 'the_content', get_the_content() );
		$this->contentPlain = apply_filters( 'the_content_plain', get_the_content() );
		$this->excerpt = apply_filters( 'the_excerpt',  get_the_excerpt() );
		$this->excerptPlain = apply_filters( 'the_excerpt_plain',  get_the_excerpt() );

		$this->authors = array();
		if (function_exists('get_coauthors')) {
			$coauthors = get_coauthors();
			foreach ($coauthors as $coauthor) {
				$this->authors[] = new TitanPress_User( $coauthor );
			}
		} else {
			$this->authors[] = new TitanPress_User( $obj->post_author );
		}

		$this->categories = array();
		$categories = get_the_category( $this->id );
		foreach ($categories as $category) {
			$this->categories[] = new TitanPress_Term( $category, 'category' );
		}

		$this->tags = array();
		$tags = get_the_tags( $this->id );
		if ($tags) {
			foreach ($tags as $tag) {
				$this->tags[] = new TitanPress_Term( $tag, 'tags' );
			}
		}

		$this->attachments = array();
		$attachments = get_children(array(
			'post_parent' => $obj->ID,
			'post_type' => 'attachment',
			'numberposts' => -1,
			'post_status' => 'publish',
		));
		if ($attachments) {
			foreach ($attachments as $attachment) {
				$this->attachments[] = new TitanPress_Attachment( $attachment );
			}
		}

		$this->featuredImage = new stdClass;
		if (has_post_thumbnail()) {
			$this->featuredImage = new TitanPress_Attachment( (int) get_post_thumbnail_id() );
		}

		$this->fields = array();
		$keys = get_post_custom_keys( $this->id );
		if ($keys) {
			foreach ($keys as $key) {
				$this->fields[] = new TitanPress_Field( $key );
			}
		}

		$this->commentCount = (int) $obj->comment_count;

	}

}