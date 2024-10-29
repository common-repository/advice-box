<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.mapo-dev.com
 * @since             1.0.0
 * @package           Advice_Box
 *
 * @wordpress-plugin
 * Plugin Name:       Advice Box
 * Plugin URI:        http://www.mapo-dev.com
 * Description:       Shortcodes for using advice boxes like positive, negative and info boxes.
 * Version:           1.0.2
 * Author:            Marcin Poholski
 * Author URI:        http://www.mapo-dev.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       advice-box
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-advice-box-activator.php
 */
function activate_advice_box() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-advice-box-activator.php';
	Advice_Box_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-advice-box-deactivator.php
 */
function deactivate_advice_box() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-advice-box-deactivator.php';
	Advice_Box_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_advice_box' );
register_deactivation_hook( __FILE__, 'deactivate_advice_box' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-advice-box.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_advice_box() {

	$plugin = new Advice_Box();
	$plugin->run();

}
run_advice_box();
