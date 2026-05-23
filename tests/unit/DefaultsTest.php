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

	public function test_mappings_returns_fifteen_entries() {
		$mappings = WS_Switcher_Color_Defaults::mappings();
		$this->assertCount( 15, $mappings );
	}

	public function test_each_mapping_has_required_keys() {
		foreach ( WS_Switcher_Color_Defaults::mappings() as $m ) {
			$this->assertArrayHasKey( 'number', $m );
			$this->assertArrayHasKey( 'label', $m );
			$this->assertArrayHasKey( 'dark', $m );
			$this->assertArrayHasKey( 'light', $m );
		}
	}

	public function test_mapping_numbers_are_sequential() {
		$numbers = array_column( WS_Switcher_Color_Defaults::mappings(), 'number' );
		$this->assertSame( range( 1, 15 ), $numbers );
	}

	public function test_mapping_colors_are_valid_hex() {
		foreach ( WS_Switcher_Color_Defaults::mappings() as $m ) {
			$this->assertMatchesRegularExpression( '/^#[0-9a-f]{6}$/', $m['dark'] );
			$this->assertMatchesRegularExpression( '/^#[0-9a-f]{6}$/', $m['light'] );
		}
	}

	public function test_default_background_uses_charte_base() {
		$mappings = WS_Switcher_Color_Defaults::mappings();
		$bg       = array_values( array_filter( $mappings, fn( $m ) => 8 === $m['number'] ) )[0];
		$this->assertSame( '#14121c', $bg['dark'] );
	}

	public function test_settings_has_expected_defaults() {
		$settings = WS_Switcher_Color_Defaults::settings();
		$this->assertSame( 'awb-color', $settings['var_prefix'] );
		$this->assertSame( 'ws-light', $settings['light_class'] );
		$this->assertSame( 'dark', $settings['default_mode'] );
		$this->assertSame( 'bottom-right', $settings['toggle_position'] );
		$this->assertTrue( $settings['toggle_enabled'] );
	}

	public function test_positions_constant_includes_hidden() {
		$this->assertContains( 'hidden', WS_Switcher_Color_Defaults::POSITIONS );
		$this->assertContains( 'bottom-right', WS_Switcher_Color_Defaults::POSITIONS );
	}

	public function test_modes_constant() {
		$this->assertSame( array( 'dark', 'light' ), WS_Switcher_Color_Defaults::MODES );
	}
}
