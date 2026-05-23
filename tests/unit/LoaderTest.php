<?php
/**
 * Tests de WS_Switcher_Color_Loader.
 *
 * @package WS_Switcher_Color
 */

namespace WS_Switcher_Color\Tests\Unit;

use WP_Mock;
use WS_Switcher_Color_Loader;

/**
 * @covers WS_Switcher_Color_Loader
 */
class LoaderTest extends WebStrategyTestCase {

	public function test_add_action_stores_hook() {
		$loader = new WS_Switcher_Color_Loader();
		$loader->add_action( 'init', $this, 'noop' );
		$actions = $this->get_property( $loader, 'actions' );
		$this->assertCount( 1, $actions );
		$this->assertSame( 'init', $actions[0]['hook'] );
		$this->assertSame( 10, $actions[0]['priority'] );
	}

	public function test_add_filter_stores_hook_with_priority() {
		$loader = new WS_Switcher_Color_Loader();
		$loader->add_filter( 'the_content', $this, 'noop', 20, 2 );
		$filters = $this->get_property( $loader, 'filters' );
		$this->assertSame( 20, $filters[0]['priority'] );
		$this->assertSame( 2, $filters[0]['accepted_args'] );
	}

	public function test_add_shortcode_stores_tag() {
		$loader = new WS_Switcher_Color_Loader();
		$loader->add_shortcode( 'my_tag', $this, 'noop' );
		$shortcodes = $this->get_property( $loader, 'shortcodes' );
		$this->assertSame( 'my_tag', $shortcodes[0]['tag'] );
	}

	public function test_run_registers_everything_with_wordpress() {
		$loader = new WS_Switcher_Color_Loader();
		$loader->add_action( 'init', $this, 'noop' );
		$loader->add_filter( 'the_content', $this, 'noop' );
		$loader->add_shortcode( 'tag', $this, 'noop' );

		WP_Mock::expectActionAdded( 'init', array( $this, 'noop' ), 10, 1 );
		WP_Mock::expectFilterAdded( 'the_content', array( $this, 'noop' ), 10, 1 );
		WP_Mock::userFunction( 'add_shortcode', array( 'times' => 1 ) );

		$loader->run();
		$this->assertConditionsMet();
	}

	/** Callback factice. */
	public function noop() {}
}
