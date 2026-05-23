<?php
/**
 * Tests de WS_Switcher_Color_CSS_Generator.
 *
 * @package WS_Switcher_Color
 */

namespace WS_Switcher_Color\Tests\Unit;

use WS_Switcher_Color_CSS_Generator;
use WS_Switcher_Color_Defaults;

/**
 * @covers WS_Switcher_Color_CSS_Generator
 */
class CssGeneratorTest extends WebStrategyTestCase {

	private function settings( array $over = array() ) {
		return array_merge(
			array( 'light_class' => 'ws-light' ),
			$over
		);
	}

	public function test_empty_mappings_return_empty_string() {
		$this->assertSame( '', WS_Switcher_Color_CSS_Generator::generate( array(), $this->settings() ) );
	}

	public function test_generates_selector_with_light_class() {
		$css = WS_Switcher_Color_CSS_Generator::generate(
			array( array( 'var' => '--awb-color1', 'light' => '#ffffff' ) ),
			$this->settings()
		);
		$this->assertStringContainsString( 'html.ws-light {', $css );
	}

	public function test_custom_light_class_is_used() {
		$css = WS_Switcher_Color_CSS_Generator::generate(
			array( array( 'var' => '--awb-color1', 'light' => '#fff' ) ),
			$this->settings( array( 'light_class' => 'mode-clair' ) )
		);
		$this->assertStringContainsString( 'html.mode-clair {', $css );
	}

	public function test_explicit_var_name_is_emitted() {
		$css = WS_Switcher_Color_CSS_Generator::generate(
			array( array( 'var' => '--awb-color7', 'light' => '#abcdef' ) ),
			$this->settings()
		);
		$this->assertStringContainsString( '--awb-color7: #abcdef;', $css );
	}

	public function test_custom_avada_variables_are_supported() {
		$css = WS_Switcher_Color_CSS_Generator::generate(
			array(
				array( 'var' => '--awb-custom15', 'light' => '#f5f3ef' ),
				array( 'var' => '--awb-custom_color_1', 'light' => '#ffffff' ),
			),
			$this->settings()
		);
		$this->assertStringContainsString( '--awb-custom15: #f5f3ef;', $css );
		$this->assertStringContainsString( '--awb-custom_color_1: #ffffff;', $css );
	}

	public function test_var_name_is_normalized() {
		$css = WS_Switcher_Color_CSS_Generator::generate(
			array( array( 'var' => 'awb-color2', 'light' => '#123456' ) ),
			$this->settings()
		);
		$this->assertStringContainsString( '--awb-color2: #123456;', $css );
	}

	public function test_legacy_number_format_still_works() {
		$css = WS_Switcher_Color_CSS_Generator::generate(
			array( array( 'number' => 8, 'light' => '#123456' ) ),
			$this->settings()
		);
		$this->assertStringContainsString( '--awb-color8: #123456;', $css );
	}

	public function test_legacy_custom_prefix_still_works() {
		$css = WS_Switcher_Color_CSS_Generator::generate(
			array( array( 'number' => 2, 'light' => '#123456' ) ),
			$this->settings( array( 'var_prefix' => 'my-color' ) )
		);
		$this->assertStringContainsString( '--my-color2: #123456;', $css );
	}

	public function test_skips_mapping_without_usable_var() {
		$css = WS_Switcher_Color_CSS_Generator::generate(
			array(
				array( 'var' => '--awb-color1', 'light' => '#000000' ),
				array( 'label' => 'orphan', 'light' => '#fff' ),
			),
			$this->settings()
		);
		$this->assertSame( 1, substr_count( $css, '--awb-color' ) );
	}

	public function test_returns_empty_when_no_usable_declaration() {
		$css = WS_Switcher_Color_CSS_Generator::generate(
			array( array( 'label' => 'orphan', 'light' => '#fff' ) ),
			$this->settings()
		);
		$this->assertSame( '', $css );
	}

	public function test_includes_transition_rule() {
		$css = WS_Switcher_Color_CSS_Generator::generate(
			WS_Switcher_Color_Defaults::mappings(),
			$this->settings()
		);
		$this->assertStringContainsString( 'transition: background-color', $css );
	}

	public function test_generates_all_default_variables() {
		$css = WS_Switcher_Color_CSS_Generator::generate(
			WS_Switcher_Color_Defaults::mappings(),
			$this->settings()
		);
		$this->assertSame( 8, substr_count( $css, '--awb-color' ) );
	}

	public function test_fallback_light_class_when_missing() {
		$css = WS_Switcher_Color_CSS_Generator::generate(
			array( array( 'var' => '--awb-color1', 'light' => '#fff' ) ),
			array()
		);
		$this->assertStringContainsString( '--awb-color1', $css );
		$this->assertStringContainsString( 'html.ws-light', $css );
	}

	public function test_no_important_by_default() {
		$css = WS_Switcher_Color_CSS_Generator::generate(
			array( array( 'var' => '--awb-color1', 'light' => '#abcdef' ) ),
			$this->settings()
		);
		$this->assertStringContainsString( '--awb-color1: #abcdef;', $css );
		$this->assertStringNotContainsString( '!important', $css );
	}

	public function test_force_important_appends_bang_on_every_variable() {
		$css = WS_Switcher_Color_CSS_Generator::generate(
			array(
				array( 'var' => '--awb-color1', 'light' => '#111111' ),
				array( 'var' => '--awb-color2', 'light' => '#222222' ),
			),
			$this->settings( array( 'force_important' => true ) )
		);
		$this->assertStringContainsString( '--awb-color1: #111111 !important;', $css );
		$this->assertStringContainsString( '--awb-color2: #222222 !important;', $css );
		$this->assertSame( 2, substr_count( $css, '!important' ) );
	}

	public function test_force_important_false_keeps_plain_declarations() {
		$css = WS_Switcher_Color_CSS_Generator::generate(
			array( array( 'var' => '--awb-color1', 'light' => '#abcdef' ) ),
			$this->settings( array( 'force_important' => false ) )
		);
		$this->assertStringContainsString( '--awb-color1: #abcdef;', $css );
		$this->assertStringNotContainsString( '!important', $css );
	}
}
