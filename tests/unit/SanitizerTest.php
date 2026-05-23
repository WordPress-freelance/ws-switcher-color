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
			array( '--awb-color8', 'awb-custom15' ),
			array( 'Fond', 'Footer' ),
			array( '#222222', '#111111' ),
			array( '#aaaaaa', '#bbbbbb' )
		);

		$this->assertCount( 2, $out );
		$this->assertSame( '--awb-color8', $out[0]['var'] );
		$this->assertSame( '--awb-custom15', $out[1]['var'], 'Le préfixe -- est forcé.' );
		$this->assertSame( 'Fond', $out[0]['label'] );
	}

	public function test_mappings_skips_empty_variable_names() {
		$out = WS_Switcher_Color_Sanitizer::mappings(
			array( '', '   ', '--awb-color1' ),
			array( 'a', 'b', 'c' ),
			array( '#000000', '#000000', '#000000' ),
			array( '#ffffff', '#ffffff', '#ffffff' )
		);
		$this->assertCount( 1, $out );
		$this->assertSame( '--awb-color1', $out[0]['var'] );
	}

	public function test_mappings_strips_invalid_chars_in_var() {
		$out = WS_Switcher_Color_Sanitizer::mappings(
			array( 'awb-color8; color:red' ),
			array( 'x' ),
			array( '#000000' ),
			array( '#ffffff' )
		);
		$this->assertSame( '--awb-color8colorred', $out[0]['var'] );
	}

	public function test_mappings_preserves_order() {
		$out = WS_Switcher_Color_Sanitizer::mappings(
			array( '--awb-color8', '--awb-color1' ),
			array( 'Huit', 'Un' ),
			array( '#222222', '#111111' ),
			array( '#aaaaaa', '#bbbbbb' )
		);
		$this->assertSame( '--awb-color8', $out[0]['var'] );
		$this->assertSame( '--awb-color1', $out[1]['var'] );
	}

	public function test_mappings_falls_back_on_empty_colors() {
		$out = WS_Switcher_Color_Sanitizer::mappings(
			array( '--awb-color1' ),
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
				'light_class'     => 'clair',
				'default_mode'    => 'light',
				'toggle_position' => 'top-left',
				'toggle_enabled'  => '1',
			)
		);
		$this->assertSame( 'clair', $out['light_class'] );
		$this->assertSame( 'light', $out['default_mode'] );
		$this->assertSame( 'top-left', $out['toggle_position'] );
		$this->assertTrue( $out['toggle_enabled'] );
		$this->assertArrayNotHasKey( 'var_prefix', $out );
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

	public function test_settings_force_important_enabled_when_present() {
		$out = WS_Switcher_Color_Sanitizer::settings( array( 'force_important' => '1' ) );
		$this->assertTrue( $out['force_important'] );
	}

	public function test_settings_force_important_disabled_when_absent() {
		$out = WS_Switcher_Color_Sanitizer::settings( array() );
		$this->assertFalse( $out['force_important'] );
	}
}
