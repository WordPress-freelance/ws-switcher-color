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
			array(
				'var_prefix'  => 'awb-color',
				'light_class' => 'ws-light',
			),
			$over
		);
	}

	public function test_empty_mappings_return_empty_string() {
		$this->assertSame( '', WS_Switcher_Color_CSS_Generator::generate( array(), $this->settings() ) );
	}

	public function test_generates_selector_with_light_class() {
		$css = WS_Switcher_Color_CSS_Generator::generate(
			array( array( 'number' => 1, 'light' => '#ffffff' ) ),
			$this->settings()
		);
		$this->assertStringContainsString( 'html.ws-light {', $css );
	}

	public function test_custom_light_class_is_used() {
		$css = WS_Switcher_Color_CSS_Generator::generate(
			array( array( 'number' => 1, 'light' => '#fff' ) ),
			$this->settings( array( 'light_class' => 'mode-clair' ) )
		);
		$this->assertStringContainsString( 'html.mode-clair {', $css );
	}

	public function test_variable_uses_prefix_and_number() {
		$css = WS_Switcher_Color_CSS_Generator::generate(
			array( array( 'number' => 7, 'light' => '#abcdef' ) ),
			$this->settings()
		);
		$this->assertStringContainsString( '--awb-color7: #abcdef;', $css );
	}

	public function test_custom_prefix_is_used() {
		$css = WS_Switcher_Color_CSS_Generator::generate(
			array( array( 'number' => 2, 'light' => '#123456' ) ),
			$this->settings( array( 'var_prefix' => 'my-color' ) )
		);
		$this->assertStringContainsString( '--my-color2: #123456;', $css );
	}

	public function test_skips_mapping_without_required_keys() {
		$css = WS_Switcher_Color_CSS_Generator::generate(
			array(
				array( 'number' => 1, 'light' => '#000000' ),
				array( 'label' => 'orphan' ),
			),
			$this->settings()
		);
		$this->assertSame( 1, substr_count( $css, '--awb-color' ) );
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
		$this->assertSame( 15, substr_count( $css, '--awb-color' ) );
	}

	public function test_fallback_prefix_when_missing() {
		$css = WS_Switcher_Color_CSS_Generator::generate(
			array( array( 'number' => 1, 'light' => '#fff' ) ),
			array()
		);
		$this->assertStringContainsString( '--awb-color1', $css );
		$this->assertStringContainsString( 'html.ws-light', $css );
	}
}
