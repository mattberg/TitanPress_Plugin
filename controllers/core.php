<?php

class TitanPress_Core_Controller {

	function __construct() {
	}

	function nonce() {

		global $titanpress;

		$controller = null;
		if (isset($_GET['controller']))
			$controller = $_GET['controller'];
		if (!$controller)
			$titanpress->error('Missing "controller" parameter');

		$method = null;
		if (isset($_GET['method']))
			$method = $_GET['method'];
		if (!$method)
			$titanpress->error('Missing "method" parameter');

		$nonce = wp_create_nonce( $titanpress->get_nonce_id($controller, $method) );

		$data = array();
		$data['nonce'] = $nonce;

		return $data;

	}

}