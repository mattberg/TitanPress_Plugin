<?php

class TitanPress_Field {

	function __construct( $key = null ) {

		if ($key) {
			$this->import( $key, get_post_custom_values($key) );
		}

	}

	private function import( $key, $values ) {

		$this->key = $key;
		$this->values = $values;

	}

}