<?php
/**
 * Tests de WS_Switcher_Color_i18n.
 *
 * @package WS_Switcher_Color
 */

namespace WS_Switcher_Color\Tests\Unit;

use WP_Mock;
use WS_Switcher_Color_i18n;

/**
 * @covers WS_Switcher_Color_i18n
 */
class i18nTest extends WebStrategyTestCase {

	public function test_load_plugin_textdomain_uses_correct_slug_and_path() {
		WP_Mock::userFunction(
			'load_plugin_textdomain',
			array(
				'times' => 1,
				'args'  => array(
					'ws-switcher-color',
					false,
					\WP_Mock\Functions::type( 'string' ),
				),
			)
		);

		( new WS_Switcher_Color_i18n() )->load_plugin_textdomain();
		$this->assertConditionsMet();
	}
}
