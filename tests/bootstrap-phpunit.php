<?php
/**
 * Bootstrap des tests unitaires (WP_Mock + Patchwork).
 *
 * Ordre critique : constantes → autoload (Patchwork lib) → WP_Mock::bootstrap()
 * (active Patchwork) → stubs natifs jamais mockés → classes stubs → classes plugin.
 *
 * @package WS_Switcher_Color
 */

// 1. Constantes WP + plugin.
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', '/tmp/wordpress/' );
}
if ( ! defined( 'WPINC' ) ) {
	define( 'WPINC', 'wp-includes' );
}
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', true );
}
if ( ! defined( 'OBJECT' ) ) {
	define( 'OBJECT', 'OBJECT' );
}
if ( ! defined( 'ARRAY_A' ) ) {
	define( 'ARRAY_A', 'ARRAY_A' );
}
if ( ! defined( 'DAY_IN_SECONDS' ) ) {
	define( 'DAY_IN_SECONDS', 86400 );
}
if ( ! defined( 'WEEK_IN_SECONDS' ) ) {
	define( 'WEEK_IN_SECONDS', 604800 );
}

define( 'WS_SWITCHER_COLOR_VERSION', '1.1.0' );
define( 'WS_SWITCHER_COLOR_SLUG', 'ws-switcher-color' );
define( 'WS_SWITCHER_COLOR_FILE', dirname( __DIR__ ) . '/ws-switcher-color.php' );
define( 'WS_SWITCHER_COLOR_PATH', dirname( __DIR__ ) . '/' );
define( 'WS_SWITCHER_COLOR_URL', 'https://example.test/wp-content/plugins/ws-switcher-color/' );
define( 'WS_SWITCHER_COLOR_OPT_MAPPINGS', 'ws_switcher_mappings' );
define( 'WS_SWITCHER_COLOR_OPT_SETTINGS', 'ws_switcher_settings' );
define( 'WS_SWITCHER_COLOR_STORAGE_KEY', 'ws-theme' );

// 2. Autoload (charge Patchwork côté lib).
require_once dirname( __DIR__ ) . '/vendor/autoload.php';

// 3. Active Patchwork.
WP_Mock::bootstrap();

// 4. Stubs natifs des fonctions WP JAMAIS mockées.
if ( ! function_exists( 'esc_attr' ) ) {
	function esc_attr( $t ) {
		return htmlspecialchars( (string) $t, ENT_QUOTES );
	}
}
if ( ! function_exists( 'esc_html' ) ) {
	function esc_html( $t ) {
		return htmlspecialchars( (string) $t, ENT_QUOTES );
	}
}
if ( ! function_exists( 'esc_js' ) ) {
	function esc_js( $t ) {
		return addslashes( (string) $t );
	}
}
if ( ! function_exists( 'esc_url' ) ) {
	function esc_url( $t ) {
		return (string) $t;
	}
}
if ( ! function_exists( 'esc_textarea' ) ) {
	function esc_textarea( $t ) {
		return htmlspecialchars( (string) $t, ENT_QUOTES );
	}
}
if ( ! function_exists( 'esc_attr__' ) ) {
	function esc_attr__( $t, $d = 'default' ) {
		return htmlspecialchars( (string) $t, ENT_QUOTES );
	}
}
if ( ! function_exists( 'esc_attr_e' ) ) {
	function esc_attr_e( $t, $d = 'default' ) {
		echo htmlspecialchars( (string) $t, ENT_QUOTES );
	}
}
if ( ! function_exists( 'esc_html__' ) ) {
	function esc_html__( $t, $d = 'default' ) {
		return htmlspecialchars( (string) $t, ENT_QUOTES );
	}
}
if ( ! function_exists( 'esc_html_e' ) ) {
	function esc_html_e( $t, $d = 'default' ) {
		echo htmlspecialchars( (string) $t, ENT_QUOTES );
	}
}
if ( ! function_exists( '__' ) ) {
	function __( $t, $d = 'default' ) {
		return $t;
	}
}
if ( ! function_exists( '_e' ) ) {
	function _e( $t, $d = 'default' ) {
		echo $t;
	}
}
if ( ! function_exists( '_n' ) ) {
	function _n( $single, $plural, $number, $d = 'default' ) {
		return 1 === (int) $number ? $single : $plural;
	}
}
if ( ! function_exists( 'sanitize_text_field' ) ) {
	function sanitize_text_field( $t ) {
		return trim( wp_strip_all_tags( (string) $t ) );
	}
}
if ( ! function_exists( 'wp_strip_all_tags' ) ) {
	function wp_strip_all_tags( $t ) {
		return preg_replace( '/<[^>]*>/', '', (string) $t );
	}
}
if ( ! function_exists( 'sanitize_key' ) ) {
	function sanitize_key( $k ) {
		return preg_replace( '/[^a-z0-9_\-]/', '', strtolower( (string) $k ) );
	}
}
if ( ! function_exists( 'sanitize_html_class' ) ) {
	function sanitize_html_class( $c ) {
		return preg_replace( '/[^A-Za-z0-9_\-]/', '', (string) $c );
	}
}
if ( ! function_exists( 'sanitize_hex_color' ) ) {
	function sanitize_hex_color( $c ) {
		$c = (string) $c;
		return preg_match( '/^#([A-Fa-f0-9]{3}){1,2}$/', $c ) ? $c : '';
	}
}
if ( ! function_exists( 'absint' ) ) {
	function absint( $n ) {
		return abs( (int) $n );
	}
}
if ( ! function_exists( 'wp_unslash' ) ) {
	function wp_unslash( $v ) {
		return is_array( $v ) ? array_map( 'wp_unslash', $v ) : stripslashes( (string) $v );
	}
}
if ( ! function_exists( 'wp_json_encode' ) ) {
	function wp_json_encode( $d, $o = 0, $depth = 512 ) {
		return json_encode( $d, $o, $depth );
	}
}
if ( ! function_exists( 'plugin_basename' ) ) {
	function plugin_basename( $file ) {
		return basename( dirname( $file ) ) . '/' . basename( $file );
	}
}
if ( ! function_exists( 'plugin_dir_path' ) ) {
	function plugin_dir_path( $file ) {
		return rtrim( dirname( $file ), '/' ) . '/';
	}
}
if ( ! function_exists( 'plugin_dir_url' ) ) {
	function plugin_dir_url( $file ) {
		return 'https://example.test/wp-content/plugins/' . basename( dirname( $file ) ) . '/';
	}
}
if ( ! function_exists( 'trailingslashit' ) ) {
	function trailingslashit( $s ) {
		return rtrim( (string) $s, '/' ) . '/';
	}
}
if ( ! function_exists( 'selected' ) ) {
	function selected( $a, $b = true, $echo = true ) {
		$r = ( (string) $a === (string) $b ) ? ' selected="selected"' : '';
		if ( $echo ) {
			echo $r;
		}
		return $r;
	}
}
if ( ! function_exists( 'checked' ) ) {
	function checked( $a, $b = true, $echo = true ) {
		$r = ( (string) $a === (string) $b ) ? ' checked="checked"' : '';
		if ( $echo ) {
			echo $r;
		}
		return $r;
	}
}
if ( ! function_exists( 'add_query_arg' ) ) {
	function add_query_arg( $args, $url = '' ) {
		return $url . '?' . http_build_query( $args );
	}
}

// 5. Classes stubs.
if ( ! class_exists( 'WP_Error' ) ) {
	class WP_Error {
		public $errors = array();
		public function __construct( $code = '', $message = '' ) {
			if ( $code ) {
				$this->errors[ $code ][] = $message;
			}
		}
		public function get_error_message() {
			foreach ( $this->errors as $msgs ) {
				return $msgs[0];
			}
			return '';
		}
	}
}
if ( ! function_exists( 'is_wp_error' ) ) {
	function is_wp_error( $thing ) {
		return $thing instanceof WP_Error;
	}
}
if ( ! class_exists( 'WP_Screen' ) ) {
	class WP_Screen {
		public $id = '';
		public function __construct( $id = '' ) {
			$this->id = $id;
		}
	}
}

// 6. Classes du plugin à tester.
require_once WS_SWITCHER_COLOR_PATH . 'includes/class-ws-switcher-color-loader.php';
require_once WS_SWITCHER_COLOR_PATH . 'includes/class-ws-switcher-color-i18n.php';
require_once WS_SWITCHER_COLOR_PATH . 'includes/class-ws-switcher-color-defaults.php';
require_once WS_SWITCHER_COLOR_PATH . 'includes/class-ws-switcher-color-css-generator.php';
require_once WS_SWITCHER_COLOR_PATH . 'includes/class-ws-switcher-color-sanitizer.php';
require_once WS_SWITCHER_COLOR_PATH . 'admin/class-ws-switcher-color-admin.php';
require_once WS_SWITCHER_COLOR_PATH . 'public/class-ws-switcher-color-public.php';
require_once WS_SWITCHER_COLOR_PATH . 'includes/class-ws-switcher-color.php';
