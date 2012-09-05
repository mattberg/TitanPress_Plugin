<?php

class TitanPress {

	function __construct() {

		$this->response = new TitanPress_Response();

		$this->setup_models();

	}

	function init() {

		if (phpversion() < 5) {
			add_action( 'admin_notices', array( $this, 'php_version_warning' ) );
			return;
		}

		$this->options = $this->get_options();

	}

	function template_redirect() {

		global $wp_query;

		$controller = $wp_query->get('titanpress_controller');
		$method = $wp_query->get('titanpress_method');

		if ($controller && $method) {

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

	function error($messages = array(), $status = '400') {

		if (!$messages) {
			$messages = array('Unknown error');
		} elseif (!is_array($messages)) {
			$messages = array($messages);
		}

		$data = array(
			'errors' => $messages
		);

		$this->response->send($data);

		exit;

	}

	function get_options() {

		$defaults = array(
			'base_url' => 'titanpress'
		);

		$options = get_option('titanpress');
		if (!is_array($options))
			$options = array();

		return array_merge($defaults, $options);

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

		if (empty($this->options['base_url']))
			return $wp_rules;

		$new_rules = array();
		$new_rules[$this->options['base_url'] . '/?$'] = 'index.php?titanpress_controller=default&titanpress_method=default';
		$new_rules[$this->options['base_url'] . '/([^/]+)/([^/]+)/?$'] = 'index.php?titanpress_controller=$matches[1]&titanpress_method=$matches[2]';

		return $new_rules + $wp_rules;

	}

	function query_vars($q) {

		$q[] = 'titanpress_controller';
		$q[] = 'titanpress_method';
    	return $q;

	}

	function php_version_warning() {
		echo '<div id="titanpress-warning" class="error"><p>Sorry, JSON API requires PHP version 5.0 or greater.</p></div>';
	}

}