<?php
/**
 * Tests d'intégration BDD : CRUD des options sur une vraie base MySQL.
 *
 * Le plugin n'utilise pas de table custom : tout l'état persistant vit dans
 * wp_options (ws_switcher_mappings, ws_switcher_settings). Ces tests valident
 * le cycle create / read / update / delete réel, la préservation des types à
 * travers la sérialisation MySQL, et le comportement des valeurs par défaut.
 *
 * @package WS_Switcher_Color
 */

class OptionsCrudTest extends WP_UnitTestCase {

	public function set_up() {
		parent::set_up();
		delete_option( WS_SWITCHER_COLOR_OPT_MAPPINGS );
		delete_option( WS_SWITCHER_COLOR_OPT_SETTINGS );
	}

	/**
	 * Sans option enregistrée, get_option renvoie le défaut fourni.
	 */
	public function test_defaults_returned_when_option_absent() {
		$mappings = get_option( WS_SWITCHER_COLOR_OPT_MAPPINGS, WS_Switcher_Color_Defaults::mappings() );
		$settings = get_option( WS_SWITCHER_COLOR_OPT_SETTINGS, WS_Switcher_Color_Defaults::settings() );

		$this->assertCount( 8, $mappings );
		$this->assertSame( 'ws-light', $settings['light_class'] );
		$this->assertSame( 'dark', $settings['default_mode'] );
		$this->assertTrue( $settings['toggle_enabled'] );
	}

	/**
	 * Create : update_option persiste réellement la valeur en base.
	 */
	public function test_create_persists_to_database() {
		$mappings = WS_Switcher_Color_Defaults::mappings();
		$this->assertTrue( update_option( WS_SWITCHER_COLOR_OPT_MAPPINGS, $mappings ) );

		// Vide le cache d'objet pour forcer une relecture depuis MySQL.
		wp_cache_flush();

		$stored = get_option( WS_SWITCHER_COLOR_OPT_MAPPINGS );
		$this->assertSame( $mappings, $stored );
	}

	/**
	 * Read : la structure relue est strictement identique à celle écrite,
	 * y compris les clés et l'ordre des entrées.
	 */
	public function test_read_preserves_structure() {
		$mappings = array(
			array(
				'var'   => '--awb-color1',
				'label'  => 'Texte principal',
				'dark'   => '#f0ede8',
				'light'  => '#14121c',
			),
			array(
				'var'   => '--awb-color7',
				'label'  => 'Surface',
				'dark'   => '#1a1724',
				'light'  => '#ede9e0',
			),
		);
		update_option( WS_SWITCHER_COLOR_OPT_MAPPINGS, $mappings );
		wp_cache_flush();

		$stored = get_option( WS_SWITCHER_COLOR_OPT_MAPPINGS );
		$this->assertSame( $mappings, $stored );
		$this->assertSame( '--awb-color1', $stored[0]['var'] );
		$this->assertSame( '#14121c', $stored[0]['light'] );
	}

	/**
	 * Update : une seconde écriture remplace bien la première.
	 */
	public function test_update_overwrites_previous_value() {
		update_option( WS_SWITCHER_COLOR_OPT_SETTINGS, WS_Switcher_Color_Defaults::settings() );

		$modified                    = WS_Switcher_Color_Defaults::settings();
		$modified['default_mode']    = 'light';
		$modified['toggle_position'] = 'top-left';
		$modified['toggle_enabled']  = false;
		update_option( WS_SWITCHER_COLOR_OPT_SETTINGS, $modified );
		wp_cache_flush();

		$stored = get_option( WS_SWITCHER_COLOR_OPT_SETTINGS );
		$this->assertSame( 'light', $stored['default_mode'] );
		$this->assertSame( 'top-left', $stored['toggle_position'] );
		$this->assertFalse( $stored['toggle_enabled'] );
	}

	/**
	 * Les types scalaires (bool notamment) survivent au round-trip MySQL.
	 */
	public function test_boolean_type_preserved() {
		$settings                   = WS_Switcher_Color_Defaults::settings();
		$settings['toggle_enabled'] = false;
		update_option( WS_SWITCHER_COLOR_OPT_SETTINGS, $settings );
		wp_cache_flush();

		$stored = get_option( WS_SWITCHER_COLOR_OPT_SETTINGS );
		$this->assertIsBool( $stored['toggle_enabled'] );
		$this->assertFalse( $stored['toggle_enabled'] );
	}

	/**
	 * Les caractères spéciaux dans un label survivent à la persistance.
	 */
	public function test_special_chars_in_label_round_trip() {
		$mappings = array(
			array(
				'var'   => '--awb-color1',
				'label'  => "Accent é à — \"guillemets\" & <balise>",
				'dark'   => '#000000',
				'light'  => '#ffffff',
			),
		);
		update_option( WS_SWITCHER_COLOR_OPT_MAPPINGS, $mappings );
		wp_cache_flush();

		$stored = get_option( WS_SWITCHER_COLOR_OPT_MAPPINGS );
		$this->assertSame( $mappings[0]['label'], $stored[0]['label'] );
	}

	/**
	 * Delete : delete_option retire bien l'entrée de la base.
	 */
	public function test_delete_removes_option() {
		update_option( WS_SWITCHER_COLOR_OPT_MAPPINGS, WS_Switcher_Color_Defaults::mappings() );
		wp_cache_flush();
		$this->assertNotFalse( get_option( WS_SWITCHER_COLOR_OPT_MAPPINGS ) );

		$this->assertTrue( delete_option( WS_SWITCHER_COLOR_OPT_MAPPINGS ) );
		wp_cache_flush();

		$this->assertFalse( get_option( WS_SWITCHER_COLOR_OPT_MAPPINGS ) );
	}
}
