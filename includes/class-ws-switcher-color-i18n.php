<?php
/**
 * Définition de l'internationalisation.
 *
 * @package WS_Switcher_Color
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Charge le textdomain du plugin.
 */
class WS_Switcher_Color_i18n {

	/**
	 * Charge le fichier de traduction.
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'ws-switcher-color',
			false,
			dirname( plugin_basename( WS_SWITCHER_COLOR_FILE ) ) . '/languages/'
		);
	}
}
