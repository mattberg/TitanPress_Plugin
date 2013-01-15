<?php

class TitanPress_Admin {

	function __construct() {

		add_action( 'admin_menu', array($this, 'admin_menu') );
		add_action( 'admin_init', array($this, 'admin_init') );

	}

	function admin_menu() {

		add_menu_page( 'TitanPress', 'TitanPress', 'manage_options', 'titanpress', array($this, 'settings_main') );

	}

	function admin_init() {

		register_setting( 'titanpress_main', 'titanpress_base_url', array($this, 'settings_base_url_validate') );
		register_setting( 'titanpress_main', 'titanpress_api_key', array($this, 'settings_api_key_validate') );

		add_settings_section('titanpress_main', 'Main Settings', array($this, 'settings_desc'), 'titanpress');
		add_settings_field('titanpress_base_url', 'Base URL', array($this, 'settings_base_url'), 'titanpress', 'titanpress_main');
		add_settings_field('titanpress_api_key', 'API Key', array($this, 'settings_api_key'), 'titanpress', 'titanpress_main');

		if ( isset( $_GET['page'], $_GET['action'] ) && $_GET['page'] == 'titanpress' && $_GET['action'] == 'reset-api-key' )
			$this->settings_reset_api_key();

	}

	function settings_desc() {
	}

	function settings_base_url() {

		$base_url = get_option( 'titanpress_base_url' );
		if (!$base_url)
			$base_url = 'titanpress';

		echo '<input id="titanpress_base_url" name="titanpress_base_url" size="30" type="text" value="' . $base_url . '" />';
		echo '<p class="description">Enter the base address where you want TitanPress to run.</p>';
		
	}

	function settings_base_url_validate($input) {

		global $titanpress;

		$valid_input = 'titanpress';

		if ($input)
			$valid_input = $input;

		$titanpress->flush_rules();

		return $valid_input;

	}

	function settings_api_key() {

		global $plugin_page;

		$api_key = get_option( 'titanpress_api_key' );
		if (!$api_key)
			$api_key = $this->settings_set_api_key();

		$args = array(
			'action' => 'reset-api-key',
			'_wpnonce' => wp_create_nonce( 'reset-api-key' ),
		);
		$reset_url = add_query_arg( $args, menu_page_url( $plugin_page, false ) );

		echo '<input id="titanpress_api_key_disabled" name="titanpress_api_key_disabled" size="50" type="text" disabled="disabled" value="' . esc_attr( $api_key ) . '" />';
		echo ' <a href="' . esc_url( $reset_url ) . '" class="button-secondary">Reset</a>';
		echo '<p class="description">API Key for TitanPress requests.</p>';
		
	}

	function settings_api_key_validate($input) {

		if (!$input)
			return get_option( 'titanpress_api_key' );

		return $input;

	}

	function settings_set_api_key() {

		$api_key = sha1(uniqid(mt_rand(), true));

		$update = update_option('titanpress_api_key', $api_key);

		return $api_key;

	}

	function settings_reset_api_key() {

		global $plugin_page;

		check_admin_referer( 'reset-api-key' );
		if (!current_user_can('manage_options'))
			wp_die( __('You do not have sufficient permissions to access this page.') );

		$api_key = $this->settings_set_api_key();

		$args = array(
			'message' => 'reset-api-key-success',
		);
		wp_safe_redirect( add_query_arg( $args, menu_page_url( $plugin_page, false ) ) );

		exit;

	}

	function settings_main() {

		if (!current_user_can('manage_options'))
			wp_die( __('You do not have sufficient permissions to access this page.') );

	    ?>
		<div class="wrap">

			<div class="icon32 icon-settings"><br /></div>
			<h2>TitanPress Settings</h2>

			<form method="post" action="options.php">

				<?php settings_fields( 'titanpress_main' ); ?>
				<?php do_settings_sections( 'titanpress' ); ?>

				<?php submit_button(); ?>

			</form>

	    </div>
	    <?php

	}

}

$titanpress_admin = new TitanPress_Admin();