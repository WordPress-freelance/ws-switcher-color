<?php
/**
 * Bootstrap de la suite d'intégration BDD (vraie WordPress + MySQL).
 *
 * Tourne uniquement en CI (GitHub Actions). Nécessite la test suite WP
 * installée via bin/install-wp-tests.sh.
 *
 * @package WS_Switcher_Color
 */

$_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $_tests_dir ) {
	$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( "{$_tests_dir}/includes/functions.php" ) ) {
	echo "Impossible de trouver {$_tests_dir}/includes/functions.php" . PHP_EOL;
	echo "Lance d'abord bin/install-wp-tests.sh." . PHP_EOL;
	exit( 1 );
}

// Requis depuis WP 6.1, sinon "The PHPUnit Polyfills library is a requirement".
if ( ! defined( 'WP_TESTS_PHPUNIT_POLYFILLS_PATH' ) ) {
	$polyfills = dirname( __DIR__, 2 ) . '/vendor/yoast/phpunit-polyfills';
	if ( is_dir( $polyfills ) ) {
		define( 'WP_TESTS_PHPUNIT_POLYFILLS_PATH', $polyfills );
	}
}

require_once "{$_tests_dir}/includes/functions.php";

/**
 * Charge le plugin avant le démarrage de WordPress.
 */
tests_add_filter(
	'muplugins_loaded',
	static function () {
		require dirname( __DIR__, 2 ) . '/ws-switcher-color.php';
	}
);

require "{$_tests_dir}/includes/bootstrap.php";
