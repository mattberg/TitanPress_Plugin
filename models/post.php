<?php

class TitanPress_Post {

	function __construct($post) {

		$this->id = $post->ID;
		$this->guid = $post->guid;
		$this->parentId = $post->post_parent;
		$this->slug = $post->post_name;
		$this->type = $post->post_type;
		$this->title = apply_filters( 'the_title', get_the_title() );
		$this->titlePlain = apply_filters( 'the_title_plain', get_the_title() );
		$this->content = apply_filters( 'the_content', get_the_content() );
		$this->contentPlain = apply_filters( 'the_content_plain', get_the_content() );
		$this->excerpt = apply_filters( 'the_excerpt',  get_the_excerpt() );
		$this->excerptPlain = apply_filters( 'the_excerpt_plain',  get_the_excerpt() );
		$this->commentCount = (int) $post->comment_count;

		$this->author = array();
		if (function_exists('get_coauthors')) {
			$coauthors = get_coauthors();
			foreach ($coauthors as $coauthor) {
				$this->author[] = new TitanPress_Author( $coauthor->ID );
			}
		} else {
			$this->author[] = new TitanPress_Author( $post->post_author );
		}

		$this->attachments = array();
		$attachments = get_children(array(
			'post_parent' => $post->ID,
			'post_type' => 'attachment',
			'numberposts' => -1,
			'post_status' => 'publish',
		));
		if (!empty($attachments)) {
			foreach ($attachments as $attachment) {
				$this->attachments[] = new TitanPress_Attachment( $attachment );
			}
		}

		$this->thumbnail = new stdClass;
		if (has_post_thumbnail( $post->ID )) {
			$thumbnail_id = get_post_thumbnail_id();
			$thumbnail = get_post($thumbnail_id);
			$this->thumbnail = new TitanPress_Attachment( $thumbnail );
		}

	}

}