<?php

class TitanPress_Posts_Controller {

	function __construct() {
	}

	function get() {

		global $titanpress;

		$posts = array();

		$args = $titanpress->response->get_query_vars();

		$loop = new WP_Query( $args );

		while( $loop->have_posts() ) {

			$loop->the_post();

			$posts[] = new TitanPress_Post($loop->post);

		}

		wp_reset_postdata();

		$data = $titanpress->response->set_data( $loop );
		$data['posts'] = $posts;

		return $data;

	}

}