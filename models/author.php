<?php

class TitanPress_Author {

	function __construct($id) {

		$this->id = $id;
		$this->username = get_the_author_meta('user_login', $this->id);
		$this->email = get_the_author_meta('user_email', $this->id);
		$this->displayName = get_the_author_meta('display_name', $this->id);
		$this->nickname = get_the_author_meta('nickname', $this->id);

	}

}