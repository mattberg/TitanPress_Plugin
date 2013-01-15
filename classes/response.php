<?php

class TitanPress_Response {

	function __construct() {
	}

	function send( $data ) {

		$json = $this->encode($data);

		$this->output($json);

	}

	function encode( $data ) {

		$data = json_encode($data);

		return $data;

	}

	function output( $json, $status = '200' ) {

		$charset = get_option('blog_charset');

		if (!headers_sent()) {
			header('HTTP/1.1 ' . $this->get_http_status($status), true);
			header('Content-Type: application/json; charset=' . $charset, true);
		}

		echo $json;

	}

	function get_http_status( $code ) {

		$codes = array(
			'200' => '200 OK',
			'201' => '201 Created',
			'202' => '202 Accepted',
			'400' => '400 Bad Request',
			'401' => '401 Unauthorized',
			'404' => '401 Not Found',
			'500' => '500 Internal Server Error',
		);

	}

	function set_data( $loop ) {

		$data = array();

		$data['count'] = $loop->post_count;
		$data['total'] = (int) $loop->found_posts;
		$data['paged'] = 1;
		if (isset($_GET['paged']) && is_numeric($_GET['paged']))
			$data['paged'] = (int) $args->page;
		$data['pages'] = $loop->max_num_pages;

		return $data;

	}

	function get_query_vars() {

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