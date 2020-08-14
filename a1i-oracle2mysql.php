<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/a1iraxa/
 * @since             1.0.0
 * @package           Oracle2mysql
 *
 * @wordpress-plugin
 * Plugin Name:       a1i Oracle To Mysql
 * Plugin URI:        https://github.com/a1iraxa/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            a1iraxa
 * Author URI:        https://github.com/a1iraxa/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       oracle2mysql
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ORACLE2MYSQL_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-oracle2mysql-activator.php
 */
function activate_oracle2mysql() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-oracle2mysql-activator.php';
	Oracle2mysql_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-oracle2mysql-deactivator.php
 */
function deactivate_oracle2mysql() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-oracle2mysql-deactivator.php';
	Oracle2mysql_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_oracle2mysql' );
register_deactivation_hook( __FILE__, 'deactivate_oracle2mysql' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-oracle2mysql.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_oracle2mysql() {

	$plugin = new Oracle2mysql();
	$plugin->run();

}
run_oracle2mysql();
