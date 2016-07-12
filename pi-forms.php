<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://enriquechavez.co
 * @since             1.0.0
 * @package           Pi_Forms
 *
 * @wordpress-plugin
 * Plugin Name:       Personal Income Forms
 * Plugin URI:        http://www.personalincome.org/
 * Description:       Custom Plugin to create Lead Generation Forms for Personal Income.
 * Version:           1.0.0
 * Author:            Enrique Chavez
 * Author URI:        https://enriquechavez.co
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pi-forms
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pi-forms-activator.php
 */
function activate_pi_forms() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pi-forms-activator.php';
	Pi_Forms_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pi-forms-deactivator.php
 */
function deactivate_pi_forms() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pi-forms-deactivator.php';
	Pi_Forms_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_pi_forms' );
register_deactivation_hook( __FILE__, 'deactivate_pi_forms' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-pi-forms.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_pi_forms() {

	$plugin = new Pi_Forms();
	$plugin->run();

}

run_pi_forms();
