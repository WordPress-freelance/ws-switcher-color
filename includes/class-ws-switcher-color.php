<?php
/**
 * Cœur du plugin : orchestration des dépendances et des hooks.
 *
 * @package WS_Switcher_Color
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Classe principale : charge les dépendances, l'i18n, et enregistre les hooks
 * admin et public via le loader.
 */
class WS_Switcher_Color {

	/**
	 * Loader des hooks.
	 *
	 * @var WS_Switcher_Color_Loader
	 */
	protected $loader;

	/**
	 * Identifiant unique du plugin.
	 *
	 * @var string
	 */
	protected $plugin_name;

	/**
	 * Version courante.
	 *
	 * @var string
	 */
	protected $version;

	/**
	 * Constructeur.
	 */
	public function __construct() {
		$this->version     = defined( 'WS_SWITCHER_COLOR_VERSION' ) ? WS_SWITCHER_COLOR_VERSION : '1.1.0';
		$this->plugin_name = 'ws-switcher-color';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Charge les classes requises et instancie le loader.
	 */
	private function load_dependencies() {
		require_once WS_SWITCHER_COLOR_PATH . 'includes/class-ws-switcher-color-loader.php';
		require_once WS_SWITCHER_COLOR_PATH . 'includes/class-ws-switcher-color-i18n.php';
		require_once WS_SWITCHER_COLOR_PATH . 'includes/class-ws-switcher-color-defaults.php';
		require_once WS_SWITCHER_COLOR_PATH . 'includes/class-ws-switcher-color-css-generator.php';
		require_once WS_SWITCHER_COLOR_PATH . 'includes/class-ws-switcher-color-sanitizer.php';
		require_once WS_SWITCHER_COLOR_PATH . 'admin/class-ws-switcher-color-admin.php';
		require_once WS_SWITCHER_COLOR_PATH . 'public/class-ws-switcher-color-public.php';

		$this->loader = new WS_Switcher_Color_Loader();
	}

	/**
	 * Définit l'internationalisation.
	 */
	private function set_locale() {
		$i18n = new WS_Switcher_Color_i18n();
		$this->loader->add_action( 'plugins_loaded', $i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Enregistre les hooks de l'admin.
	 */
	private function define_admin_hooks() {
		$admin = new WS_Switcher_Color_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_menu', $admin, 'add_menu_page' );
		$this->loader->add_action( 'admin_init', $admin, 'handle_save' );
		$this->loader->add_action( 'admin_enqueue_scripts', $admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $admin, 'enqueue_scripts' );
		$this->loader->add_filter( 'admin_body_class', $admin, 'add_admin_body_class' );
		$this->loader->add_action( 'admin_head', $admin, 'inline_reset_css' );
	}

	/**
	 * Enregistre les hooks du frontend.
	 */
	private function define_public_hooks() {
		$public = new WS_Switcher_Color_Public( $this->get_plugin_name(), $this->get_version() );

		// Anti-FOUC le plus tôt possible dans le head.
		$this->loader->add_action( 'wp_head', $public, 'print_anti_fouc', 1 );
		$this->loader->add_action( 'wp_enqueue_scripts', $public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_footer', $public, 'print_toggle_button' );
		$this->loader->add_shortcode( 'ws_theme_toggle', $public, 'render_toggle_shortcode' );
	}

	/**
	 * Lance le loader.
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * Nom du plugin.
	 *
	 * @return string
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Loader.
	 *
	 * @return WS_Switcher_Color_Loader
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Version.
	 *
	 * @return string
	 */
	public function get_version() {
		return $this->version;
	}
}
