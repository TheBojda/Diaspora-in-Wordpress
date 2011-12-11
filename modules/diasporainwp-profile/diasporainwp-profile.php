<?php
	
	add_action('admin_init', 'flush_rewrite_rules');
	add_action('generate_rewrite_rules', array('DiwProfile', 'generate_rewrite_rules'));
	add_action('parse_request', array('DiwProfile', 'parse_request'));

	add_filter('query_vars', array('DiwProfile', 'query_vars'));
	
	class DiwProfile {
	
		function query_vars($vars) {
			$vars[] = 'host-meta';
			$vars[] = 'webfinger';
			$vars[] = 'hcard';
			return $vars;
		}
		
		function generate_rewrite_rules($wp_rewrite) {
			$rules = array(
				'.well-known/host-meta' => 'index.php?host-meta',
			);
			$wp_rewrite->rules = $rules + $wp_rewrite->rules;
		}
		
		function parse_request() {
			global $wp;
			if( isset($wp->query_vars['host-meta']) ) {
				include("host-meta.php");
				exit;
			}
			if( isset($wp->query_vars['webfinger']) ) {
				$webfinger_user = $wp->query_vars['webfinger'];
				include("webfinger.php");
				exit;
			}
			if( isset($wp->query_vars['hcard']) ) {
				include("hcard.php");
				exit;
			}
		}
		
	}
	
?>