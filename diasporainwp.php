<?php
/*
 Plugin Name: Diaspora in Wordpress
 Plugin URI: http://wordpress.org/extend/plugins/diasporainwp/
 Description: Diaspora Pod implementation for Wordpress
 Author: Laszlo Fazekas
 Author URI: https://plus.google.com/u/0/100023476653128296395/posts
 Version: 1.0
 Text Domain: diasporainwp
 License: GPL
 */

define("DIASPORAINWP_VERSION", "0.1");

$dh = opendir(WP_PLUGIN_DIR . '/diasporainwp/modules');
while (false !== ($file = readdir($dh))) {
	if(strpos($file, '.') === 0)
		continue;
	if(file_exists(WP_PLUGIN_DIR . "/diasporainwp/modules/$file/plugin.php")) 
	{
		include(WP_PLUGIN_DIR . "/diasporainwp/modules/$file/plugin.php");
	} 
	else if(file_exists(WP_PLUGIN_DIR . "/diasporainwp/modules/$file/$file.php"))
	{
		include(WP_PLUGIN_DIR . "/diasporainwp/modules/$file/$file.php");
	}
}
closedir($dh); 

register_activation_hook( __FILE__, 'activation_hook' );

function activation_hook() {
	//if( get_option("diasporainwp_version") != DIASPORAINWP_VERSION) {
		do_action("diasporainwp_activation_hook");
		update_option("diasporainwp_version", DIASPORAINWP_VERSION);
	//}
} 

?>