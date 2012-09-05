<?php

class TitanPress_Post {

	function __construct($post) {

		print_r($post);

		$this->id = $post->ID;
		$this->parentId = $post->post_parent;
		$this->slug = $post->post_name;
		$this->type = $post->post_type;
		$this->title = $post->post_title;
		$this->content = $post->post_content;
		$this->excerpt = $post->post_excerpt;

		$this->author = array(new TitanPress_Author($post->post_author));

	}

}