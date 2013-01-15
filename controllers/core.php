<?php

class TitanPress_Core_Controller {

	function __construct() {
	}

	function get_nonce() {

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

		$nonce = $this->create_nonce($controller, $method);

		return $nonce;

	}

	private function create_nonce($controller, $method) {

		return wp_create_nonce('titanpress-' . strtolower($controller) . '-' . strtolower($method));

	}

}