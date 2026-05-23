<?php
/**
 * Tests de WS_Switcher_Color_Public.
 *
 * @package WS_Switcher_Color
 */

namespace WS_Switcher_Color\Tests\Unit;

use WP_Mock;
use WS_Switcher_Color_Public;
use WS_Switcher_Color_Defaults;

/**
 * @covers WS_Switcher_Color_Public
 */
class PublicTest extends WebStrategyTestCase {

	private function pub() {
		return new WS_Switcher_Color_Public( 'ws-switcher-color', '1.1.0' );
	}

	/**
	 * Mock get_option pour renvoyer mappings/settings selon la clé.
	 *
	 * @param array $settings Réglages à renvoyer.
	 * @param array $mappings Mappings à renvoyer.
	 */
	private function mock_options( array $settings, array $mappings = array() ) {
		WP_Mock::userFunction(
			'get_option',
			array(
				'return' => function ( $key, $default = false ) use ( $settings, $mappings ) {
					if ( 'ws_switcher_settings' === $key ) {
						return $settings;
					}
					if ( 'ws_switcher_mappings' === $key ) {
						return $mappings;
					}
					return $default;
				},
			)
		);
	}

	public function test_anti_fouc_outputs_localstorage_check() {
		$this->mock_options( WS_Switcher_Color_Defaults::settings() );
		ob_start();
		$this->pub()->print_anti_fouc();
		$out = ob_get_clean();
		$this->assertStringContainsString( 'localStorage.getItem', $out );
		$this->assertStringContainsString( 'ws-theme', $out );
		$this->assertStringContainsString( 'ws-light', $out );
	}

	public function test_anti_fouc_respects_default_light() {
		$this->mock_options( array( 'light_class' => 'clair', 'default_mode' => 'light' ) );
		ob_start();
		$this->pub()->print_anti_fouc();
		$out = ob_get_clean();
		$this->assertStringContainsString( "||'light'", $out );
		$this->assertStringContainsString( 'clair', $out );
	}

	public function test_enqueue_styles_registers_and_injects_css() {
		$this->mock_options(
			WS_Switcher_Color_Defaults::settings(),
			array( array( 'number' => 1, 'light' => '#ffffff' ) )
		);
		WP_Mock::userFunction( 'wp_enqueue_style', array( 'times' => 1 ) );

		$captured = null;
		WP_Mock::userFunction(
			'wp_add_inline_style',
			array(
				'return' => function ( $handle, $css ) use ( &$captured ) {
					$captured = $css;
				},
			)
		);

		$this->pub()->enqueue_styles();
		$this->assertStringContainsString( '--awb-color1: #ffffff;', $captured );
	}

	public function test_enqueue_styles_no_inline_when_no_mappings() {
		$this->mock_options( WS_Switcher_Color_Defaults::settings(), array() );
		WP_Mock::userFunction( 'wp_enqueue_style', array( 'times' => 1 ) );
		// wp_add_inline_style non enregistré → ne doit pas être appelé.
		$this->pub()->enqueue_styles();
		$this->assertConditionsMet();
	}

	public function test_enqueue_scripts_localizes_config() {
		$this->mock_options( WS_Switcher_Color_Defaults::settings() );
		WP_Mock::userFunction( 'wp_enqueue_script', array( 'times' => 1 ) );

		$captured = null;
		WP_Mock::userFunction(
			'wp_localize_script',
			array(
				'return' => function ( $handle, $name, $data ) use ( &$captured ) {
					$captured = $data;
				},
			)
		);

		$this->pub()->enqueue_scripts();
		$this->assertSame( 'ws-theme', $captured['key'] );
		$this->assertSame( 'ws-light', $captured['lightClass'] );
		$this->assertSame( 'dark', $captured['defaultMode'] );
	}

	public function test_toggle_button_rendered_when_enabled() {
		$this->mock_options(
			array(
				'toggle_enabled'  => true,
				'toggle_position' => 'bottom-right',
				'light_class'     => 'ws-light',
				'default_mode'    => 'dark',
			)
		);
		ob_start();
		$this->pub()->print_toggle_button();
		$out = ob_get_clean();
		$this->assertStringContainsString( 'ws-theme-toggle--bottom-right', $out );
	}

	public function test_toggle_button_hidden_when_disabled() {
		$this->mock_options(
			array(
				'toggle_enabled'  => false,
				'toggle_position' => 'bottom-right',
			)
		);
		ob_start();
		$this->pub()->print_toggle_button();
		$this->assertSame( '', ob_get_clean() );
	}

	public function test_toggle_button_hidden_when_position_hidden() {
		$this->mock_options(
			array(
				'toggle_enabled'  => true,
				'toggle_position' => 'hidden',
			)
		);
		ob_start();
		$this->pub()->print_toggle_button();
		$this->assertSame( '', ob_get_clean() );
	}

	public function test_shortcode_returns_inline_button() {
		$html = $this->pub()->render_toggle_shortcode();
		$this->assertStringContainsString( 'ws-theme-toggle--inline', $html );
		$this->assertStringContainsString( '<button', $html );
	}
}
