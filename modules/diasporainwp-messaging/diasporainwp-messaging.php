<?php
	add_action('generate_rewrite_rules', array('DiwMessaging', 'generate_rewrite_rules'));
	add_action('parse_request', array('DiwMessaging', 'parse_request'));
	add_action('diasporainwp_activation_hook', array('DiwMessaging', 'activation_hook'));

	add_filter('query_vars', array('DiwMessaging', 'query_vars'));

	class DiwMessaging {
		
		function activation_hook() {
			global $wpdb;
			
			$table_name = $wpdb->prefix . "diw_contacts";
			$sql = "CREATE TABLE " . $table_name . " (
				id INT NOT NULL AUTO_INCREMENT,
				diaspora_handle VARCHAR(255) DEFAULT '' NOT NULL,
				full_name VARCHAR(255) DEFAULT '' NOT NULL,
				image_url VARCHAR(255) DEFAULT '' NOT NULL,
				pub_key text NOT NULL,
				profile_page VARCHAR(255) DEFAULT '' NOT NULL,
				guid VARCHAR(255) DEFAULT '' NOT NULL,
				seed_location VARCHAR(255) DEFAULT '' NOT NULL,
				hcard_url VARCHAR(255) DEFAULT '' NOT NULL,
				UNIQUE KEY id (id)
			 );";
			 
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
		
		function generate_rewrite_rules($wp_rewrite) {
			$rules = array(
				'receive/(.*)' => 'index.php?receive',
			);
			$wp_rewrite->rules = $rules + $wp_rewrite->rules;
		}

		function query_vars($vars) {
			$vars[] = 'receive';
			return $vars;
		}
		
		function parse_request() {
			global $wp;
			if( isset($wp->query_vars['receive']) ) {
				$post = file_get_contents('php://input');
				file_put_contents('req.txt', 'post: ' . $post . "<br/>\n", FILE_APPEND);
				include("receive.php");	
				exit;
			}
		}

	}

	
?>