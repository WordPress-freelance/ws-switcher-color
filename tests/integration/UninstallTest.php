<?php
/**
 * Tests d'intégration BDD : nettoyage à la désinstallation.
 *
 * Vérifie que uninstall.php retire bien les deux options du plugin de la base.
 *
 * @package WS_Switcher_Color
 */

class UninstallTest extends WP_UnitTestCase {

	public function set_up() {
		parent::set_up();
		update_option( WS_SWITCHER_COLOR_OPT_MAPPINGS, WS_Switcher_Color_Defaults::mappings() );
		update_option( WS_SWITCHER_COLOR_OPT_SETTINGS, WS_Switcher_Color_Defaults::settings() );
		wp_cache_flush();
	}

	/**
	 * uninstall.php supprime les deux options.
	 */
	public function test_uninstall_removes_all_options() {
		$this->assertNotFalse( get_option( WS_SWITCHER_COLOR_OPT_MAPPINGS ) );
		$this->assertNotFalse( get_option( WS_SWITCHER_COLOR_OPT_SETTINGS ) );

		// Simule le contexte de désinstallation WordPress.
		if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
			define( 'WP_UNINSTALL_PLUGIN', 'ws-switcher-color/ws-switcher-color.php' );
		}
		require dirname( __DIR__, 2 ) . '/uninstall.php';
		wp_cache_flush();

		$this->assertFalse( get_option( WS_SWITCHER_COLOR_OPT_MAPPINGS ) );
		$this->assertFalse( get_option( WS_SWITCHER_COLOR_OPT_SETTINGS ) );
	}
}
