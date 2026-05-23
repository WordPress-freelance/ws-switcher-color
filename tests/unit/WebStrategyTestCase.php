<?php
/**
 * Classe de base des tests unitaires WebStrategy.
 *
 * @package WS_Switcher_Color
 */

namespace WS_Switcher_Color\Tests\Unit;

use WP_Mock;
use WP_Mock\Tools\TestCase as WPMockTestCase;
use ReflectionClass;

/**
 * Setup/teardown communs + helpers Reflection.
 */
abstract class WebStrategyTestCase extends WPMockTestCase {

	/**
	 * Initialise WP_Mock et reset des superglobales.
	 */
	public function setUp(): void {
		parent::setUp();
		WP_Mock::setUp();
		$_GET    = array();
		$_POST   = array();
		$_SERVER = array_merge(
			$_SERVER,
			array( 'REQUEST_METHOD' => 'GET' )
		);
	}

	/**
	 * Ferme WP_Mock et Mockery.
	 */
	public function tearDown(): void {
		WP_Mock::tearDown();
		\Mockery::close();
		$_GET  = array();
		$_POST = array();
		parent::tearDown();
	}

	/**
	 * Invoque une méthode privée/protégée statique via Reflection.
	 *
	 * @param string $class  Nom de classe.
	 * @param string $method Nom de méthode.
	 * @param array  $args   Arguments.
	 * @return mixed
	 */
	protected function invoke_static( $class, $method, array $args = array() ) {
		$ref = new ReflectionClass( $class );
		$m   = $ref->getMethod( $method );
		$m->setAccessible( true );
		return $m->invokeArgs( null, $args );
	}

	/**
	 * Invoque une méthode privée/protégée d'instance via Reflection.
	 *
	 * @param object $instance Instance cible.
	 * @param string $method   Nom de méthode.
	 * @param array  $args     Arguments.
	 * @return mixed
	 */
	protected function invoke_method( $instance, $method, array $args = array() ) {
		$ref = new ReflectionClass( get_class( $instance ) );
		$m   = $ref->getMethod( $method );
		$m->setAccessible( true );
		return $m->invokeArgs( $instance, $args );
	}

	/**
	 * Lit une propriété privée/protégée.
	 *
	 * @param object $instance Instance cible.
	 * @param string $name     Nom de propriété.
	 * @return mixed
	 */
	protected function get_property( $instance, $name ) {
		$ref = new ReflectionClass( get_class( $instance ) );
		$p   = $ref->getProperty( $name );
		$p->setAccessible( true );
		return $p->getValue( $instance );
	}

	/**
	 * Écrit une propriété privée/protégée.
	 *
	 * @param object $instance Instance cible.
	 * @param string $name     Nom de propriété.
	 * @param mixed  $value    Valeur.
	 */
	protected function set_property( $instance, $name, $value ) {
		$ref = new ReflectionClass( get_class( $instance ) );
		$p   = $ref->getProperty( $name );
		$p->setAccessible( true );
		$p->setValue( $instance, $value );
	}
}
