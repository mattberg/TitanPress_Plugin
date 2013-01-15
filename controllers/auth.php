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
			$titanpress->error('Missing "auth" parameter');

		$user_id = wp_validate_auth_cookie($auth, 'logged_in');
		if (!$user_id)
			$titanpress->error('Invalid authentication key');

		$data = array();
		$data['valid'] = true;
		$data['user'] = new TitanPress_User( $user_id );

		return $data;

	}

	function generate() {

		global $titanpress;

		$nonce = null;
		if (isset($_GET['nonce']))
			$nonce = $_GET['nonce'];
		if (!$nonce)
			$titanpress->error('Missing "nonce" parameter');
		elseif (!wp_verify_nonce( $nonce, $titanpress->get_nonce_id('auth', 'generate') ))
			$titanpress->error('Invalid "nonce" parameter');

		$username = null;
		if (isset($_GET['username']))
			$username = $_GET['username'];
		if (!$username)
			$titanpress->error('Missing "username" parameter');

		$password = null;
		if (isset($_GET['password']))
			$password = $_GET['password'];
		if (!$password)
			$titanpress->error('Missing "password" parameter');

		$user = wp_authenticate($username, $password);
		if (is_wp_error($user)) {
			$titanpress->error('Invalid username and/or password');
		}

		$expiration = time() + apply_filters('auth_cookie_expiration', 1209600, $user->ID, true);

		$auth = wp_generate_auth_cookie($user->ID, $expiration, 'logged_in');

		$data = array();
		$data['auth'] = $auth;
		$data['user'] = new TitanPress_User( $user->ID );

		return $data;

	}

}