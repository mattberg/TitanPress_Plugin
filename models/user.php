<?php

class TitanPress_User {

	function __construct( $obj = null ) {

		if (is_object( $obj )) {
			$this->import( $obj );
		} elseif (is_int( $obj )) {
			$this->import( get_user_by('id', $obj) );
		}

	}

	private function import( $obj ) {

		$this->id = $obj->ID;
		$this->username = $obj->user_login;
		$this->email = $obj->user_email;
		$this->displayName = $obj->display_name;
		$this->nickname = $obj->nickname;

	}

}