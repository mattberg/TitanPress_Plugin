<?php

class TitanPress_Content_Controller {

	function __construct() {
	}

	public function get() {

		$items = array();

		$args = $this->get_query_args();
		$loop = new WP_Query( $args );

		while( $loop->have_posts() ) {
			$loop->the_post();
			$items[] = new TitanPress_Post($loop->post);
		}

		wp_reset_postdata();

		$data = $this->set_collection_properties( $loop );
		$data['items'] = $items;

		return $data;

	}

	public function post() {

		global $titanpress;

		$nonce = null;
		if (isset($_GET['nonce']))
			$nonce = $_GET['nonce'];
		if (!$nonce)
			$titanpress->error('Missing "nonce" parameter');
		elseif (!wp_verify_nonce( $nonce, $titanpress->get_nonce_id('posts', 'post') ))
			$titanpress->error('Invalid "nonce" parameter');

		$auth = null;
		if (isset($_GET['auth']))
			$auth = $_GET['auth'];
		if (!$auth)
			$titanpress->error('Missing "auth" parameter');

		$user_id = wp_validate_auth_cookie($auth, 'logged_in');
		if (!$user_id)
			$titanpress->error('Invalid authentication key');

		$post = new TitanPress_Post();
		$post_id = $post->add($_POST);

		if (!$post_id)
			$titanpress->error('Post not saved');

		$data = array();
		$data['items'][] = $post;

		return $data;

	}

	private function set_collection_properties( $loop ) {

		$data = array();

		$data['currentItemCount'] = $loop->post_count;
		$data['itemsPerPage'] = $loop->query_vars['posts_per_page'];
		$data['totalItems'] = (int) $loop->found_posts;
		$data['pageIndex'] = 1;
		if (isset($_GET['paged']) && is_numeric($_GET['paged']))
			$data['pageIndex'] = (int) $_GET['paged'];
		$data['totalPages'] = $loop->max_num_pages;
		$data['kind'] = $loop->query_vars['post_type'];

		return $data;

	}

	private function get_query_args() {

		$query_args = array();

		if (isset($_GET['id']))
			$query_args['p'] = $_GET['id'];

		if (isset($_GET['parent_id']))
			$query_args['post_parent_id'] = (int) $_GET['parent_id'];

		if (isset($_GET['slug']))
			$query_args['post_slug'] = $_GET['slug'];

		if (isset($_GET['type']))
			$query_args['post_type'] = $_GET['type'];
		else
			$query_args['post_type'] = 'post';

		if (isset($_GET['author_id']))
			$query_args['author_id'] = (int) $_GET['author_id'];

		if (isset($_GET['author_slug']))
			$query_args['author_slug'] = $_GET['author_slug'];

		if (isset($_GET['category_id']))
			$query_args['category_id'] = (int) $_GET['category_id'];

		if (isset($_GET['category_slug']))
			$query_args['category_slug'] = $_GET['category_slug'];

		if (isset($_GET['tag_id']))
			$query_args['tag_id'] = (int) $_GET['tag_id'];

		if (isset($_GET['tag_slug']))
			$query_args['tag_slug'] = $_GET['tag_slug'];

		if (isset($_GET['items_per_page']))
			$query_args['posts_per_page'] = (int) $_GET['items_per_page'];

		if (isset($_GET['page_index']))
			$query_args['paged'] = (int) $_GET['page_index'];

		return $query_args;

	}

}