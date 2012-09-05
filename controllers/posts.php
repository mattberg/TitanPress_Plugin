<?php

class TitanPress_Posts_Controller {

	function __construct() {
	}

	function get() {

		global $post;

		$data = array();

		$posts = get_posts( $this->get_args() );

		foreach( $posts as $post ) {

			setup_postdata($post);

			$data[] = new TitanPress_Post($post);

		}

		return array(
			'posts' => $data
		);

	}

	function get_args() {

		$args = array();

		if (isset($_GET['post_id']))
			$args['p'] = $_GET['post_id'];

		if (isset($_GET['post_parent_id']))
			$args['post_parent'] = $_GET['post_parent_id'];

		if (isset($_GET['post_slug']))
			$args['name'] = $_GET['post_slug'];

		if (isset($_GET['post_type']))
			$args['post_type'] = $_GET['post_type'];

		if (isset($_GET['author_id']))
			$args['author'] = $_GET['author_id'];

		if (isset($_GET['author_slug']))
			$args['author_name'] = $_GET['author_slug'];

		if (isset($_GET['category_id']))
			$args['cat'] = $_GET['category_id'];

		if (isset($_GET['category_slug']))
			$args['category_name'] = $_GET['category_slug'];

		if (isset($_GET['tag_id']))
			$args['tag_id'] = $_GET['tag_id'];

		if (isset($_GET['tag_slug']))
			$args['tag'] = $_GET['tag_slug'];

		if (isset($_GET['count']))
			$args['posts_per_page'] = $_GET['count'];

		if (isset($_GET['page']))
			$args['paged'] = $_GET['page'];

		return $args;

	}

}