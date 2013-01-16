<?php

class TitanPress_General_Controller {

	function __construct() {
	}

	function nonce() {

		global $titanpress;

		$controller = null;
		if (isset($_GET['controller']))
			$controller = $_GET['controller'];
		if (!$controller)
			$titanpress->response->add_error('Missing "controller" parameter');

		$method = null;
		if (isset($_GET['method']))
			$method = $_GET['method'];
		if (!$method)
			$titanpress->response->add_error('Missing "method" parameter');

		$titanpress->response->process_errors();

		$nonce = wp_create_nonce( $titanpress->get_nonce_id($controller, $method) );

		$data = array();
		$data['kind'] = 'nonce';
		$data['items'] = array($nonce);

		return $data;

	}

}