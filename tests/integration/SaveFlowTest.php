<?php
/**
 * Tests d'intégration BDD : flow de sauvegarde de bout en bout.
 *
 * Reproduit le contrat de WS_Switcher_Color_Admin::handle_save() (la méthode
 * elle-même se termine par wp_safe_redirect + exit, non testable en process) :
 * un POST nettoyé par le Sanitizer est persisté via update_option sur une vraie
 * base, relu, puis transformé en CSS par le générateur. Valide la chaîne
 * complète sanitize → MySQL → lecture → rendu.
 *
 * @package WS_Switcher_Color
 */

class SaveFlowTest extends WP_UnitTestCase {

	public function set_up() {
		parent::set_up();
		delete_option( WS_SWITCHER_COLOR_OPT_MAPPINGS );
		delete_option( WS_SWITCHER_COLOR_OPT_SETTINGS );
	}

	/**
	 * Mappings : POST brut → Sanitizer → update_option → relecture identique.
	 */
	public function test_mappings_save_flow_persists_sanitized_data() {
		$numbers = array( '2', '1', '0' ); // le 0 doit être rejeté, tri par numéro.
		$labels  = array( 'Deux', 'Un', 'Zéro' );
		$darks   = array( '#222222', '#111111', '#000000' );
		$lights  = array( '#eeeeee', '#ffffff', '#cccccc' );

		$mappings = WS_Switcher_Color_Sanitizer::mappings( $numbers, $labels, $darks, $lights );
		update_option( WS_SWITCHER_COLOR_OPT_MAPPINGS, $mappings );
		wp_cache_flush();

		$stored = get_option( WS_SWITCHER_COLOR_OPT_MAPPINGS );

		$this->assertCount( 2, $stored, 'Le mapping numéro 0 doit être écarté.' );
		$this->assertSame( 1, $stored[0]['number'], 'Tri ascendant par numéro.' );
		$this->assertSame( 'Un', $stored[0]['label'] );
		$this->assertSame( 2, $stored[1]['number'] );
	}

	/**
	 * Settings : valeurs invalides ramenées aux valeurs sûres puis persistées.
	 */
	public function test_settings_save_flow_falls_back_on_invalid_values() {
		$raw = array(
			'var_prefix'      => 'my-color',
			'light_class'     => 'light-mode',
			'default_mode'    => 'neon',        // invalide → dark.
			'toggle_position' => 'middle',      // invalide → bottom-right.
			'toggle_enabled'  => '1',
		);

		$settings = WS_Switcher_Color_Sanitizer::settings( $raw );
		update_option( WS_SWITCHER_COLOR_OPT_SETTINGS, $settings );
		wp_cache_flush();

		$stored = get_option( WS_SWITCHER_COLOR_OPT_SETTINGS );
		$this->assertSame( 'my-color', $stored['var_prefix'] );
		$this->assertSame( 'dark', $stored['default_mode'] );
		$this->assertSame( 'bottom-right', $stored['toggle_position'] );
		$this->assertTrue( $stored['toggle_enabled'] );
	}

	/**
	 * Round-trip complet : données persistées → CSS généré exploitable.
	 */
	public function test_persisted_data_feeds_css_generator() {
		$mappings = WS_Switcher_Color_Sanitizer::mappings(
			array( '1' ),
			array( 'Fond' ),
			array( '#14121c' ),
			array( '#f5f3ef' )
		);
		$settings = WS_Switcher_Color_Sanitizer::settings(
			array(
				'var_prefix'   => 'awb-color',
				'light_class'  => 'ws-light',
				'default_mode' => 'dark',
			)
		);
		update_option( WS_SWITCHER_COLOR_OPT_MAPPINGS, $mappings );
		update_option( WS_SWITCHER_COLOR_OPT_SETTINGS, $settings );
		wp_cache_flush();

		$css = WS_Switcher_Color_CSS_Generator::generate(
			get_option( WS_SWITCHER_COLOR_OPT_MAPPINGS ),
			get_option( WS_SWITCHER_COLOR_OPT_SETTINGS )
		);

		$this->assertStringContainsString( '--awb-color1', $css );
		$this->assertStringContainsString( '#f5f3ef', $css );
		$this->assertStringContainsString( 'ws-light', $css );
	}

	/**
	 * Le réglage force_important persisté se répercute dans le CSS généré.
	 */
	public function test_force_important_persisted_reaches_css() {
		$mappings = WS_Switcher_Color_Sanitizer::mappings(
			array( '1' ),
			array( 'Fond' ),
			array( '#14121c' ),
			array( '#ffffff' )
		);
		$settings = WS_Switcher_Color_Sanitizer::settings(
			array(
				'var_prefix'      => 'awb-color',
				'force_important' => '1',
			)
		);
		update_option( WS_SWITCHER_COLOR_OPT_MAPPINGS, $mappings );
		update_option( WS_SWITCHER_COLOR_OPT_SETTINGS, $settings );
		wp_cache_flush();

		$stored = get_option( WS_SWITCHER_COLOR_OPT_SETTINGS );
		$this->assertTrue( $stored['force_important'] );

		$css = WS_Switcher_Color_CSS_Generator::generate(
			get_option( WS_SWITCHER_COLOR_OPT_MAPPINGS ),
			$stored
		);
		$this->assertStringContainsString( '#ffffff !important;', $css );
	}
}
