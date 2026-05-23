<?php
/**
 * Tests de WS_Switcher_Color_Admin.
 *
 * @package WS_Switcher_Color
 */

namespace WS_Switcher_Color\Tests\Unit;

use WP_Mock;
use WP_Screen;
use WS_Switcher_Color_Admin;

/**
 * @covers WS_Switcher_Color_Admin
 */
class AdminTest extends WebStrategyTestCase {

	private function admin() {
		return new WS_Switcher_Color_Admin( 'ws-switcher-color', '1.1.0' );
	}

	public function test_add_menu_page_registers_under_tools() {
		WP_Mock::userFunction(
			'add_management_page',
			array(
				'times' => 1,
				'args'  => array(
					\WP_Mock\Functions::type( 'string' ),
					\WP_Mock\Functions::type( 'string' ),
					'manage_options',
					'ws-switcher-color',
					\WP_Mock\Functions::type( 'array' ),
				),
			)
		);
		$this->admin()->add_menu_page();
		$this->assertConditionsMet();
	}

	public function test_enqueue_styles_skips_other_pages() {
		// Aucune userFunction enregistrée → si wp_enqueue_style était appelé, échec.
		$this->admin()->enqueue_styles( 'index.php' );
		$this->assertConditionsMet();
	}

	public function test_enqueue_styles_loads_on_plugin_page() {
		WP_Mock::userFunction( 'wp_enqueue_style', array( 'times' => 1 ) );
		$this->admin()->enqueue_styles( 'tools_page_ws-switcher-color' );
		$this->assertConditionsMet();
	}

	public function test_enqueue_scripts_loads_and_localizes() {
		WP_Mock::userFunction( 'wp_enqueue_script', array( 'times' => 1 ) );
		WP_Mock::userFunction( 'wp_localize_script', array( 'times' => 1 ) );
		$this->admin()->enqueue_scripts( 'tools_page_ws-switcher-color' );
		$this->assertConditionsMet();
	}

	public function test_enqueue_scripts_skips_other_pages() {
		$this->admin()->enqueue_scripts( 'edit.php' );
		$this->assertConditionsMet();
	}

	public function test_add_admin_body_class_on_plugin_page() {
		WP_Mock::userFunction(
			'get_current_screen',
			array( 'return' => new WP_Screen( 'tools_page_ws-switcher-color' ) )
		);
		$classes = $this->admin()->add_admin_body_class( 'foo' );
		$this->assertStringContainsString( 'ws-switcher-color-page', $classes );
	}

	public function test_add_admin_body_class_elsewhere_untouched() {
		WP_Mock::userFunction(
			'get_current_screen',
			array( 'return' => new WP_Screen( 'edit-post' ) )
		);
		$classes = $this->admin()->add_admin_body_class( 'foo' );
		$this->assertSame( 'foo', $classes );
	}

	public function test_inline_reset_css_outputs_on_plugin_page() {
		WP_Mock::userFunction(
			'get_current_screen',
			array( 'return' => new WP_Screen( 'tools_page_ws-switcher-color' ) )
		);
		ob_start();
		$this->admin()->inline_reset_css();
		$out = ob_get_clean();
		$this->assertStringContainsString( '#14121C', $out );
		$this->assertStringContainsString( '.ws-switcher-color-page', $out );
	}

	public function test_inline_reset_css_silent_elsewhere() {
		WP_Mock::userFunction(
			'get_current_screen',
			array( 'return' => new WP_Screen( 'edit-post' ) )
		);
		ob_start();
		$this->admin()->inline_reset_css();
		$this->assertSame( '', ob_get_clean() );
	}

	public function test_color_field_outputs_picker_and_swatch() {
		ob_start();
		WS_Switcher_Color_Admin::color_field( 'ws_mapping_dark[]', '#7c5cbf' );
		$out = ob_get_clean();
		$this->assertStringContainsString( 'type="color"', $out );
		$this->assertStringContainsString( 'value="#7c5cbf"', $out );
		$this->assertStringContainsString( 'ws-color-swatch', $out );
		$this->assertStringContainsString( 'name="ws_mapping_dark[]"', $out );
	}

	public function test_handle_save_returns_early_without_post() {
		$_POST = array();
		// Aucune fonction WP attendue → toute interaction lèverait une erreur.
		$this->admin()->handle_save();
		$this->assertConditionsMet();
	}

	public function test_handle_save_persists_settings() {
		$_POST = array(
			'ws_switcher_save' => '1',
			'ws_save_tab'      => 'settings',
			'ws_light_class'   => 'ws-light',
			'ws_default_mode'  => 'light',
			'ws_toggle_position' => 'top-left',
			'ws_toggle_enabled'  => '1',
		);

		WP_Mock::userFunction( 'check_admin_referer', array( 'return' => true ) );
		WP_Mock::userFunction( 'current_user_can', array( 'return' => true ) );

		$captured = null;
		WP_Mock::userFunction(
			'update_option',
			array(
				'return' => function ( $key, $value ) use ( &$captured ) {
					if ( 'ws_switcher_settings' === $key ) {
						$captured = $value;
					}
					return true;
				},
			)
		);
		WP_Mock::userFunction( 'admin_url', array( 'return' => 'https://x/wp-admin/tools.php' ) );
		WP_Mock::userFunction(
			'wp_safe_redirect',
			array(
				'return' => function () {
					throw new \RuntimeException( 'redirect' );
				},
			)
		);

		try {
			$this->admin()->handle_save();
			$this->fail( 'Le redirect aurait dû interrompre.' );
		} catch ( \RuntimeException $e ) {
			$this->assertSame( 'redirect', $e->getMessage() );
		}

		$this->assertSame( 'light', $captured['default_mode'] );
		$this->assertSame( 'top-left', $captured['toggle_position'] );
		$this->assertTrue( $captured['toggle_enabled'] );
	}

	public function test_handle_save_persists_mappings() {
		$_POST = array(
			'ws_switcher_save'  => '1',
			'ws_save_tab'       => 'mappings',
			'ws_mapping_var'    => array( '--awb-color8' ),
			'ws_mapping_label'  => array( 'Fond' ),
			'ws_mapping_dark'   => array( '#14121c' ),
			'ws_mapping_light'  => array( '#f5f3ef' ),
		);

		WP_Mock::userFunction( 'check_admin_referer', array( 'return' => true ) );
		WP_Mock::userFunction( 'current_user_can', array( 'return' => true ) );

		$captured = null;
		WP_Mock::userFunction(
			'update_option',
			array(
				'return' => function ( $key, $value ) use ( &$captured ) {
					if ( 'ws_switcher_mappings' === $key ) {
						$captured = $value;
					}
					return true;
				},
			)
		);
		WP_Mock::userFunction( 'admin_url', array( 'return' => 'https://x/wp-admin/tools.php' ) );
		WP_Mock::userFunction(
			'wp_safe_redirect',
			array(
				'return' => function () {
					throw new \RuntimeException( 'redirect' );
				},
			)
		);

		try {
			$this->admin()->handle_save();
		} catch ( \RuntimeException $e ) {
			$this->assertSame( 'redirect', $e->getMessage() );
		}

		$this->assertCount( 1, $captured );
		$this->assertSame( '--awb-color8', $captured[0]['var'] );
		$this->assertSame( '#f5f3ef', $captured[0]['light'] );
	}

	public function test_handle_save_aborts_on_bad_nonce() {
		$_POST = array( 'ws_switcher_save' => '1' );
		WP_Mock::userFunction( 'check_admin_referer', array( 'return' => false ) );
		// update_option non enregistré → ne doit jamais être appelé.
		$this->admin()->handle_save();
		$this->assertConditionsMet();
	}
}
