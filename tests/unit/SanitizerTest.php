<?php
/**
 * Tests de WS_Switcher_Color_Sanitizer.
 *
 * @package WS_Switcher_Color
 */

namespace WS_Switcher_Color\Tests\Unit;

use WS_Switcher_Color_Sanitizer;

/**
 * @covers WS_Switcher_Color_Sanitizer
 */
class SanitizerTest extends WebStrategyTestCase {

	public function test_mappings_builds_clean_rows() {
		$out = WS_Switcher_Color_Sanitizer::mappings(
			array( '2', '1' ),
			array( 'Deux', 'Un' ),
			array( '#222222', '#111111' ),
			array( '#aaaaaa', '#bbbbbb' )
		);

		$this->assertCount( 2, $out );
		// Trié par numéro.
		$this->assertSame( 1, $out[0]['number'] );
		$this->assertSame( 2, $out[1]['number'] );
		$this->assertSame( 'Un', $out[0]['label'] );
	}

	public function test_mappings_skips_zero_and_negative_numbers() {
		$out = WS_Switcher_Color_Sanitizer::mappings(
			array( '0', '-3', '4' ),
			array( 'a', 'b', 'c' ),
			array( '#000000', '#000000', '#000000' ),
			array( '#ffffff', '#ffffff', '#ffffff' )
		);
		$this->assertCount( 1, $out );
		$this->assertSame( 4, $out[0]['number'] );
	}

	public function test_mappings_falls_back_on_empty_colors() {
		$out = WS_Switcher_Color_Sanitizer::mappings(
			array( '1' ),
			array( 'x' ),
			array( '' ),
			array( '' )
		);
		$this->assertSame( '#000000', $out[0]['dark'] );
		$this->assertSame( '#ffffff', $out[0]['light'] );
	}

	public function test_settings_keeps_valid_values() {
		$out = WS_Switcher_Color_Sanitizer::settings(
			array(
				'var_prefix'      => 'my-color',
				'light_class'     => 'clair',
				'default_mode'    => 'light',
				'toggle_position' => 'top-left',
				'toggle_enabled'  => '1',
			)
		);
		$this->assertSame( 'my-color', $out['var_prefix'] );
		$this->assertSame( 'clair', $out['light_class'] );
		$this->assertSame( 'light', $out['default_mode'] );
		$this->assertSame( 'top-left', $out['toggle_position'] );
		$this->assertTrue( $out['toggle_enabled'] );
	}

	public function test_settings_rejects_invalid_mode() {
		$out = WS_Switcher_Color_Sanitizer::settings( array( 'default_mode' => 'rainbow' ) );
		$this->assertSame( 'dark', $out['default_mode'] );
	}

	public function test_settings_rejects_invalid_position() {
		$out = WS_Switcher_Color_Sanitizer::settings( array( 'toggle_position' => 'middle' ) );
		$this->assertSame( 'bottom-right', $out['toggle_position'] );
	}

	public function test_settings_toggle_disabled_when_absent() {
		$out = WS_Switcher_Color_Sanitizer::settings( array() );
		$this->assertFalse( $out['toggle_enabled'] );
	}
}
