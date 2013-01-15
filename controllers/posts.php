<?php

class TitanPress_Posts_Controller {

	function __construct() {
	}

	function get() {

		$posts = array();

		$args = $this->get_query_vars();

		$loop = new WP_Query( $args );

		while( $loop->have_posts() ) {

			$loop->the_post();

			$posts[] = new TitanPress_Post($loop->post);

		}

		wp_reset_postdata();

		$data = $this->set_data( $loop );
		$data['posts'] = $posts;

		return $data;

	}

	private function set_data( $loop ) {

		$data = array();

		$data['count'] = $loop->post_count;
		$data['total'] = (int) $loop->found_posts;
		$data['paged'] = 1;
		if (isset($_GET['paged']) && is_numeric($_GET['paged']))
			$data['paged'] = (int) $args->page;
		$data['pages'] = $loop->max_num_pages;

		return $data;

	}

	private function get_query_vars() {

		$query_vars = array();

		if (isset($_GET['post_id']))
			$query_vars['p'] = $_GET['post_id'];

		if (isset($_GET['post_parent_id']))
			$query_vars['post_parent_id'] = $_GET['post_parent_id'];

		if (isset($_GET['post_slug']))
			$query_vars['post_slug'] = $_GET['post_slug'];

		if (isset($_GET['post_type']))
			$query_vars['post_type'] = $_GET['post_type'];
		else
			$query_vars['post_type'] = 'post';

		if (isset($_GET['author_id']))
			$query_vars['author_id'] = $_GET['author_id'];

		if (isset($_GET['author_slug']))
			$query_vars['author_slug'] = $_GET['author_slug'];

		if (isset($_GET['category_id']))
			$query_vars['category_id'] = $_GET['category_id'];

		if (isset($_GET['category_slug']))
			$query_vars['category_slug'] = $_GET['category_slug'];

		if (isset($_GET['tag_id']))
			$query_vars['tag_id'] = $_GET['tag_id'];

		if (isset($_GET['tag_slug']))
			$query_vars['tag_slug'] = $_GET['tag_slug'];

		if (isset($_GET['posts_per_page']))
			$query_vars['posts_per_page'] = $_GET['posts_per_page'];

		if (isset($_GET['paged']))
			$query_vars['paged'] = $_GET['paged'];

		return $query_vars;

	}

}