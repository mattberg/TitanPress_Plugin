<?php

class TitanPress_Response {

	function __construct() {

		$this->errors = array();

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
			header('HTTP/1.1 400 Bad Request', true);
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

	function add_error($message, $code = null) {

		$error = new stdClass();
		$error->message = $message;
		if (!is_null($code))
			$error->code = $code;

		$this->errors[] = $error;

	}

	function process_errors() {

		if (count($this->errors) > 0) {
			
			$this->send(array(
				'errors' => $this->errors
			));

			$this->errors = array();

			exit;

		}

		$this->errors = array();

	}
	
}