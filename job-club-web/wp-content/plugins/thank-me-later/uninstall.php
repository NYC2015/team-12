<?php

/**
 * Uninstall the plugin
 */

if (!defined("WP_UNINSTALL_PLUGIN")) {
	exit;
}

if (!defined("BBPP_THANKMELATER_PLUGIN_PATH")) {
	define("BBPP_THANKMELATER_PLUGIN_PATH", plugin_dir_path(__FILE__));
}

if (!class_exists("Bbpp_ThankMeLater_Upgrader")) {
	require_once BBPP_THANKMELATER_PLUGIN_PATH . "upgrade/Upgrader.php";
}

$upgrader = new Bbpp_ThankMeLater_Upgrader();
$upgrader->upgrade(0);