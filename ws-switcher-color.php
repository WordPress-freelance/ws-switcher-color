<?php
/**
 * Plugin Name:       WS Color Switcher
 * Plugin URI:        https://wordpress-freelance.com/plugins/ws-switcher-color/
 * Description:       Bascule dark/light pour Avada par remappage des variables CSS (--awb-colorN). Bouton flottant, shortcode, anti-FOUC, persistance localStorage.
 * Version:           1.1.0
 * Author:            WebStrategy
 * Author URI:        https://wordpress-freelance.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ws-switcher-color
 * Domain Path:       /languages
 * Requires at least: 5.6
 * Requires PHP:      7.4
 *
 * @package WS_Switcher_Color
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'WS_SWITCHER_COLOR_VERSION', '1.1.0' );
define( 'WS_SWITCHER_COLOR_SLUG', 'ws-switcher-color' );
define( 'WS_SWITCHER_COLOR_FILE', __FILE__ );
define( 'WS_SWITCHER_COLOR_PATH', plugin_dir_path( __FILE__ ) );
define( 'WS_SWITCHER_COLOR_URL', plugin_dir_url( __FILE__ ) );

// Clés d'options — strictement identiques entre FREE et PRO (rétrocompat).
define( 'WS_SWITCHER_COLOR_OPT_MAPPINGS', 'ws_switcher_mappings' );
define( 'WS_SWITCHER_COLOR_OPT_SETTINGS', 'ws_switcher_settings' );
define( 'WS_SWITCHER_COLOR_STORAGE_KEY', 'ws-theme' );

require WS_SWITCHER_COLOR_PATH . 'includes/class-ws-switcher-color.php';

/**
 * Démarrage du plugin.
 */
function ws_switcher_color_run() {
	$plugin = new WS_Switcher_Color();
	$plugin->run();
}
ws_switcher_color_run();
