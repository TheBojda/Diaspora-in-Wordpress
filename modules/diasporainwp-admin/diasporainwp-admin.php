<?php

	add_action('admin_menu', array('DiwSettings', 'admin_menu'));

	class DiwSettings {
		
		function admin_menu() {
			add_menu_page( 'Diaspora',  'Diaspora', 1, 'diaspora', array('DiwSettings', 'show_settings'), WP_PLUGIN_URL.'/diasporainwp/modules/diasporainwp-admin/diaspora.png');
			add_submenu_page( 'diaspora', 'Settings', 'Settings', 'manage_options', 'diaspora', array('DiwSettings', 'show_settings'));
			add_submenu_page( 'diaspora', 'Contacts', 'Contacts', 'manage_options', 'diaspora_contacts', array('DiwSettings', 'show_contacts'));
			add_submenu_page( 'diaspora', 'About', 'About', 'manage_options', 'diaspora_about', array('DiwSettings', 'show_about'));
		}
		
		function show_settings() {
			include('settings.php');
		}
		
		function show_about() {
			include('about.php');
		}
		
		function show_contacts() {
			include('contacts.php');
		}
		
	}
	
?>