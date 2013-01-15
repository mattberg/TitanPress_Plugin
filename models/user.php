<?php

class TitanPress_User {

	function __construct( $id ) {

		$this->id = $id;
		$this->username = get_the_author_meta('user_login', $id);
		$this->email = get_the_author_meta('user_email', $id);
		$this->displayName = get_the_author_meta('display_name', $id);
		$this->nickname = get_the_author_meta('nickname', $id);

	}

}