<?php

class TitanPress_Term {

	function __construct( $obj = null, $taxonomy ) {

		if (is_object( $obj )) {
			$this->import( $obj );
		} elseif (is_int( $obj )) {
			$this->import( get_term($obj, $taxonomy) );
		}

	}

	private function import( $obj ) {

		$this->id = (int) $obj->term_id;
		$this->parentId = (int) $obj->parent;
		$this->name = $obj->name;
		$this->slug = $obj->slug;

		$this->description = null;
		if ($obj->description) {
			$this->description = $obj->description;
		}

	}

}