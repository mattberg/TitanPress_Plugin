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

		register_setting( 'titanpress_group', 'titanpress', array($this, 'settings_validate') );
		register_setting( 'titanpress_group', 'titanpress_controllers', array($this, 'settings_controllers_validate') );

		add_settings_section('titanpress_main', 'Main Settings', array($this, 'settings_desc'), 'titanpress');
		add_settings_field('titanpress_base_url', 'Base URL', array($this, 'settings_base_url'), 'titanpress', 'titanpress_main');
	}

	function settings_desc() {
		//print '<p>here</p>';
	}

	function settings_base_url() {

		global $titanpress;

		$options = $titanpress->get_options();

		echo '<input id="titanpress_main_base_url" name="titanpress[base_url]" size="40" type="text" value="' . $options['base_url'] . '" />';
		echo '<p class="description">Enter the base address where you want TitanPress to run.</p>';
		
	}

	function settings_validate($input) {

		global $titanpress;

		$valid_input = array();

		$valid_input['base_url'] = $input['base_url'];

		$titanpress->flush_rules();

		return $valid_input;

	}

	function settings_main() {

		if (!current_user_can('manage_options'))  {
	      wp_die( __('You do not have sufficient permissions to access this page.') );
	    }

	    ?>
		<div class="wrap">

			<div class="icon32 icon-settings"><br /></div>
			<h2>TitanPress Settings</h2>

			<form method="post" action="options.php">

				<?php settings_fields( 'titanpress_group' ); ?>
				<?php do_settings_sections( 'titanpress' ); ?>

				<?php submit_button(); ?>

			</form>

	    </div>
	    <?php

	}

}

$titanpress_admin = new TitanPress_Admin();