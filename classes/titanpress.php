<?php

class TitanPress {

	function __construct() {

		$this->response = new TitanPress_Response();
		$this->html2text = new TitanPress_Html2Text();

		$this->setup_models();

	}

	function init() {

		if (phpversion() < 5) {
			add_action( 'admin_notices', array( $this, 'php_version_warning' ) );
			return;
		}

		$this->base_url = get_option( 'titanpress_base_url', 'titanpress' );
		
		$this->api_key = get_option( 'titanpress_api_key' );
		if (!$this->api_key)
			add_action( 'admin_notices', array( $this, 'api_key_warning' ) );

	}

	function template_redirect() {

		global $wp_query;

		$controller = $wp_query->get( 'titanpress_controller' );
		$method = $wp_query->get( 'titanpress_method' );

		if ($controller && $method) {

			// $api_key = $_GET['api_key'];
			// if (!$this->api_key || $api_key !== $this->api_key)
			// 	$this->error('Invalid API Key', '401');

			$controllers = $this->get_controllers();

			$controller_path = $this->get_controller_path($controller);
			if (file_exists($controller_path))
				require_once $controller_path;

			$controller_class = $this->get_controller_class($controller);
			if (!class_exists($controller_class))
				$this->error('Controller not found', '404');

			$controller_obj = new $controller_class();

			if (!method_exists($controller_obj, $method))
				$this->error('Method not found', '404');

			if (!$this->is_method_public($controller_obj, $method))
				$this->error('Method not available', '404');

			$data = $controller_obj->$method();

			$this->response->send($data);

			exit;

		}

	}

	function get_controllers() {

		$controllers = array();

		$dir = TITANPRESS_PLUGIN_PATH . 'controllers';

		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				while (($file = readdir($dh)) !== false) {
					if ($file != "." && $file != "..") {
						if (preg_match('/(.+)\.php$/', $file, $matches)) {
							array_push($controllers, strtolower($matches[1]));
						}
					}
				}
				closedir($dh);
			}
		}

		$controllers = apply_filters( 'titanpress_controllers', $controllers );

		return $controllers;

	}

	function get_controller_class($controller) {

		return 'TitanPress_' . $controller . '_Controller';

	}

	function get_controller_path($controller) {

		return TITANPRESS_PLUGIN_PATH . 'controllers/' . $controller . '.php';

	}

	function get_models() {

		$models = array();

		$dir = TITANPRESS_PLUGIN_PATH . 'models';

		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				while (($file = readdir($dh)) !== false) {
					if ($file != "." && $file != "..") {
						if (preg_match('/(.+)\.php$/', $file, $matches)) {
							array_push($models, strtolower($matches[1]));
						}
					}
				}
				closedir($dh);
			}
		}

		$models = apply_filters( 'titanpress_models', $models );

		return $models;

	}

	function get_model_path($model) {

		return TITANPRESS_PLUGIN_PATH . 'models/' . $model . '.php';

	}	

	function setup_models() {

		$models = $this->get_models();

		foreach ($models as $model) {
			$model_path = $this->get_model_path($model);
			if (file_exists($model_path))
				require_once $model_path;
		}

	}

	function get_nonce_id( $controller, $method ) {

		return 'titanpress-' . strtolower($controller) . '-' . strtolower($method);

	}

	function activate() {
		$this->flush_rules();
	}

	function deactivate() {
		$this->flush_rules();
	}	

	function flush_rules() {
		global $wp_rewrite;
	   	$wp_rewrite->flush_rules();
	}

	function rewrite_rules($wp_rules) {

		if (empty($this->base_url))
			return $wp_rules;

		$new_rules = array();
		$new_rules[$this->base_url . '/?$'] = 'index.php?titanpress_controller=default&titanpress_method=default';
		$new_rules[$this->base_url . '/([^/]+)/([^/]+)/?$'] = 'index.php?titanpress_controller=$matches[1]&titanpress_method=$matches[2]';

		return $new_rules + $wp_rules;

	}

	function query_vars($q) {

		$q[] = 'titanpress_controller';
		$q[] = 'titanpress_method';
    	return $q;

	}

	function save_post( $post_id ) {

		$post_content_filtered = null;

		$post = get_posts( array(
			'p' => 1,
		) );

		remove_action( 'save_post', array( $this, 'save_post' ) );
		wp_update_post( array('ID' => $post_id, 'post_content_filtered' => $post_content_filtered ) );
		add_action( 'save_post', array( $this, 'save_post' ) );

	}

	function convert_to_camel_case( $string ) {

		$str = str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));

		$str[0] = strtolower($str[0]);

		return $str;

	}

	function the_title_plain( $title ) {

		return html_entity_decode( strip_tags( $title ), ENT_QUOTES );

	}

	function the_content_plain( $content ) {

		return $this->html2text->convert( $content );

	}

	function the_excerpt_plain( $excerpt ) {

		return $this->html2text->convert( $excerpt );

	}

	function php_version_warning() {

		echo '<div id="titanpress-php-warning" class="error"><p>Sorry, TitanPress requires PHP version 5.0 or greater.</p></div>';

	}

	function api_key_warning() {

		echo '<div id="titanpress-api-key-warning" class="error"><p>TitanPress API Key must be set. <a href="' . esc_url( admin_url('admin.php?page=titanpress') ) . '">TitanPress Settings</a>.</p></div>';

	}

	function is_method_public($class, $method) {

		$refl = new ReflectionMethod($class, $method);

		return $refl->isPublic();

	}

}