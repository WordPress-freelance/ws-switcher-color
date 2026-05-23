<?php
/**
 * Tests de WS_Switcher_Color_Defaults.
 *
 * @package WS_Switcher_Color
 */

namespace WS_Switcher_Color\Tests\Unit;

use WS_Switcher_Color_Defaults;

/**
 * @covers WS_Switcher_Color_Defaults
 */
class DefaultsTest extends WebStrategyTestCase {

	public function test_mappings_returns_palette_entries() {
		$mappings = WS_Switcher_Color_Defaults::mappings();
		$this->assertCount( 8, $mappings );
	}

	public function test_each_mapping_has_required_keys() {
		foreach ( WS_Switcher_Color_Defaults::mappings() as $m ) {
			$this->assertArrayHasKey( 'var', $m );
			$this->assertArrayHasKey( 'label', $m );
			$this->assertArrayHasKey( 'dark', $m );
			$this->assertArrayHasKey( 'light', $m );
		}
	}

	public function test_mapping_vars_target_awb_palette() {
		$vars = array_column( WS_Switcher_Color_Defaults::mappings(), 'var' );
		$expected = array();
		for ( $i = 1; $i <= 8; $i++ ) {
			$expected[] = '--awb-color' . $i;
		}
		$this->assertSame( $expected, $vars );
	}

	public function test_mapping_colors_are_valid_hex() {
		foreach ( WS_Switcher_Color_Defaults::mappings() as $m ) {
			$this->assertMatchesRegularExpression( '/^#[0-9a-f]{6}$/', $m['dark'] );
			$this->assertMatchesRegularExpression( '/^#[0-9a-f]{6}$/', $m['light'] );
		}
	}

	public function test_default_background_uses_charte_base() {
		$mappings = WS_Switcher_Color_Defaults::mappings();
		$bg       = array_values( array_filter( $mappings, fn( $m ) => '--awb-color8' === $m['var'] ) )[0];
		$this->assertSame( '#14121c', $bg['dark'] );
	}

	public function test_settings_has_expected_defaults() {
		$settings = WS_Switcher_Color_Defaults::settings();
		$this->assertArrayNotHasKey( 'var_prefix', $settings );
		$this->assertSame( 'ws-light', $settings['light_class'] );
		$this->assertSame( 'dark', $settings['default_mode'] );
		$this->assertSame( 'bottom-right', $settings['toggle_position'] );
		$this->assertTrue( $settings['toggle_enabled'] );
		$this->assertFalse( $settings['force_important'] );
	}

	public function test_normalize_var_forces_double_dash_prefix() {
		$this->assertSame( '--awb-color8', WS_Switcher_Color_Defaults::normalize_var( 'awb-color8' ) );
		$this->assertSame( '--awb-color8', WS_Switcher_Color_Defaults::normalize_var( '--awb-color8' ) );
		$this->assertSame( '--awb-custom_color_1', WS_Switcher_Color_Defaults::normalize_var( '--awb-custom_color_1' ) );
	}

	public function test_normalize_var_strips_invalid_chars() {
		$this->assertSame( '--awbcolor8', WS_Switcher_Color_Defaults::normalize_var( 'awb color8;}' ) );
		$this->assertSame( '', WS_Switcher_Color_Defaults::normalize_var( '   ' ) );
		$this->assertSame( '', WS_Switcher_Color_Defaults::normalize_var( '---' ) );
	}

	public function test_resolve_var_prefers_explicit_var() {
		$row = array( 'var' => '--awb-custom15', 'number' => 9 );
		$this->assertSame( '--awb-custom15', WS_Switcher_Color_Defaults::resolve_var( $row ) );
	}

	public function test_resolve_var_falls_back_on_legacy_number() {
		$row = array( 'number' => 8 );
		$this->assertSame( '--awb-color8', WS_Switcher_Color_Defaults::resolve_var( $row, 'awb-color' ) );
	}

	public function test_resolve_var_empty_when_nothing_usable() {
		$this->assertSame( '', WS_Switcher_Color_Defaults::resolve_var( array() ) );
		$this->assertSame( '', WS_Switcher_Color_Defaults::resolve_var( array( 'number' => 0 ) ) );
	}

	public function test_positions_constant_includes_hidden() {
		$this->assertContains( 'hidden', WS_Switcher_Color_Defaults::POSITIONS );
		$this->assertContains( 'bottom-right', WS_Switcher_Color_Defaults::POSITIONS );
	}

	public function test_modes_constant() {
		$this->assertSame( array( 'dark', 'light' ), WS_Switcher_Color_Defaults::MODES );
	}
}
