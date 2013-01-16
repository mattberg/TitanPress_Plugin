<?php

class TitanPress_Auth_Controller {

	function __construct() {
	}

	function validate() {

		global $titanpress;

		$auth = null;
		if (isset($_GET['auth']))
			$auth = $_GET['auth'];
		if (!$auth)
			$titanpress->response->add_error('Missing "auth" parameter');

		$titanpress->response->process_errors();

		$user_id = wp_validate_auth_cookie($auth, 'logged_in');
		if (!$user_id)
			$titanpress->response->add_error('Invalid authentication key');

		$titanpress->response->process_errors();

		$data = array();
		$data['kind'] = 'user';
		$data['items'] = array(new TitanPress_User( $user_id ));

		return $data;

	}

	function generate() {

		global $titanpress;

		$nonce = null;
		if (isset($_GET['nonce']))
			$nonce = $_GET['nonce'];
		if (!$nonce)
			$titanpress->response->add_error('Missing "nonce" parameter');
		elseif (!wp_verify_nonce( $nonce, $titanpress->get_nonce_id('auth', 'generate') ))
			$titanpress->response->add_error('Invalid "nonce" parameter');

		$titanpress->response->process_errors();

		$username = null;
		if (isset($_GET['username']))
			$username = $_GET['username'];
		if (!$username)
			$titanpress->response->add_error('Missing "username" parameter');

		$password = null;
		if (isset($_GET['password']))
			$password = $_GET['password'];
		if (!$password)
			$titanpress->response->add_error('Missing "password" parameter');

		$titanpress->response->process_errors();

		$user = wp_authenticate($username, $password);
		if (is_wp_error($user)) {
			$titanpress->response->add_error('Invalid username and/or password');
		}

		$titanpress->response->process_errors();

		$expiration = time() + apply_filters('auth_expiration', 1209600, $user->ID, true);

		$user->auth = wp_generate_auth_cookie($user->ID, $expiration, 'logged_in');

		$data = array();
		$data['kind'] = 'user';
		$data['items'] = array(new TitanPress_User( $user ));

		return $data;

	}

}