<?php
/**
 * Logique spécifique à l'administration.
 *
 * @package WS_Switcher_Color
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Page de réglages, sauvegarde, enqueue des assets admin.
 */
class WS_Switcher_Color_Admin {

	/**
	 * Identifiant du plugin.
	 *
	 * @var string
	 */
	private $plugin_name;

	/**
	 * Version.
	 *
	 * @var string
	 */
	private $version;

	/**
	 * Slug de la page admin.
	 *
	 * @var string
	 */
	const PAGE_SLUG = 'ws-switcher-color';

	/**
	 * Constructeur.
	 *
	 * @param string $plugin_name Nom du plugin.
	 * @param string $version     Version.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Enregistre la page sous le menu Outils.
	 */
	public function add_menu_page() {
		add_management_page(
			__( 'WS Color Switcher', 'ws-switcher-color' ),
			__( 'WS Color Switcher', 'ws-switcher-color' ),
			'manage_options',
			self::PAGE_SLUG,
			array( $this, 'render_page' )
		);
	}

	/**
	 * True sur la page du plugin uniquement.
	 *
	 * @return bool
	 */
	private function is_plugin_page() {
		$screen = get_current_screen();
		return $screen && false !== strpos( $screen->id, self::PAGE_SLUG );
	}

	/**
	 * Enqueue le CSS admin (page du plugin uniquement).
	 *
	 * @param string $hook_suffix Hook de la page courante.
	 */
	public function enqueue_styles( $hook_suffix = '' ) {
		if ( 'tools_page_' . self::PAGE_SLUG !== $hook_suffix ) {
			return;
		}
		wp_enqueue_style(
			$this->plugin_name . '-admin',
			WS_SWITCHER_COLOR_URL . 'admin/css/ws-switcher-color-admin.css',
			array(),
			$this->version,
			'all'
		);
	}

	/**
	 * Enqueue le JS admin (page du plugin uniquement).
	 *
	 * @param string $hook_suffix Hook de la page courante.
	 */
	public function enqueue_scripts( $hook_suffix = '' ) {
		if ( 'tools_page_' . self::PAGE_SLUG !== $hook_suffix ) {
			return;
		}
		wp_enqueue_script(
			$this->plugin_name . '-admin',
			WS_SWITCHER_COLOR_URL . 'admin/js/ws-switcher-color-admin.js',
			array(),
			$this->version,
			true
		);
		wp_localize_script(
			$this->plugin_name . '-admin',
			'wsSwitcherColorAdmin',
			array(
				'copied'    => __( '✓ Copié !', 'ws-switcher-color' ),
				'copyLabel' => __( 'Copier le CSS', 'ws-switcher-color' ),
				'newVar'    => __( 'Nouvelle variable', 'ws-switcher-color' ),
			)
		);
	}

	/**
	 * Ajoute une classe body unique sur la page du plugin.
	 *
	 * @param string $classes Classes existantes.
	 * @return string
	 */
	public function add_admin_body_class( $classes ) {
		if ( $this->is_plugin_page() ) {
			$classes .= ' ws-switcher-color-page';
		}
		return $classes;
	}

	/**
	 * Reset CSS du cadre blanc WordPress (Avada + thèmes tiers).
	 */
	public function inline_reset_css() {
		if ( ! $this->is_plugin_page() ) {
			return;
		}
		echo '<style>
		.ws-switcher-color-page #wpwrap,
		.ws-switcher-color-page #wpcontent,
		.ws-switcher-color-page #wpbody,
		.ws-switcher-color-page #wpbody-content { background: #14121C !important; }
		.ws-switcher-color-page #wpbody,
		.ws-switcher-color-page #wpbody-content { padding: 0 !important; }
		.ws-switcher-color-page .wrap,
		.ws-switcher-color-page #wpcontent .wrap { margin: 0 !important; padding: 0 !important; background: #14121C !important; max-width: none !important; }
		</style>';
	}

	/**
	 * Traite la soumission du formulaire de réglages.
	 */
	public function handle_save() {
		if ( empty( $_POST['ws_switcher_save'] ) ) {
			return;
		}
		if ( ! check_admin_referer( 'ws_switcher_nonce_action', 'ws_switcher_nonce' ) ) {
			return;
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$tab = isset( $_POST['ws_save_tab'] ) ? sanitize_key( wp_unslash( $_POST['ws_save_tab'] ) ) : 'mappings';

		if ( 'mappings' === $tab ) {
			$numbers  = isset( $_POST['ws_mapping_number'] ) ? (array) wp_unslash( $_POST['ws_mapping_number'] ) : array();
			$labels   = isset( $_POST['ws_mapping_label'] ) ? (array) wp_unslash( $_POST['ws_mapping_label'] ) : array();
			$darks    = isset( $_POST['ws_mapping_dark'] ) ? (array) wp_unslash( $_POST['ws_mapping_dark'] ) : array();
			$lights   = isset( $_POST['ws_mapping_light'] ) ? (array) wp_unslash( $_POST['ws_mapping_light'] ) : array();
			$mappings = WS_Switcher_Color_Sanitizer::mappings( $numbers, $labels, $darks, $lights );
			update_option( WS_SWITCHER_COLOR_OPT_MAPPINGS, $mappings );
		}

		if ( 'settings' === $tab ) {
			$raw      = array(
				'var_prefix'      => isset( $_POST['ws_var_prefix'] ) ? wp_unslash( $_POST['ws_var_prefix'] ) : '',
				'light_class'     => isset( $_POST['ws_light_class'] ) ? wp_unslash( $_POST['ws_light_class'] ) : '',
				'default_mode'    => isset( $_POST['ws_default_mode'] ) ? wp_unslash( $_POST['ws_default_mode'] ) : '',
				'toggle_position' => isset( $_POST['ws_toggle_position'] ) ? wp_unslash( $_POST['ws_toggle_position'] ) : '',
				'toggle_enabled'  => isset( $_POST['ws_toggle_enabled'] ) ? $_POST['ws_toggle_enabled'] : '',
			);
			$settings = WS_Switcher_Color_Sanitizer::settings( $raw );
			update_option( WS_SWITCHER_COLOR_OPT_SETTINGS, $settings );
		}

		wp_safe_redirect(
			add_query_arg(
				array(
					'page'  => self::PAGE_SLUG,
					'tab'   => $tab,
					'saved' => '1',
				),
				admin_url( 'tools.php' )
			)
		);
		exit;
	}

	/**
	 * Affiche la page admin (routing par onglet vers les partials).
	 */
	public function render_page() {
		$mappings   = get_option( WS_SWITCHER_COLOR_OPT_MAPPINGS, WS_Switcher_Color_Defaults::mappings() );
		$settings   = get_option( WS_SWITCHER_COLOR_OPT_SETTINGS, WS_Switcher_Color_Defaults::settings() );
		$generated  = WS_Switcher_Color_CSS_Generator::generate( $mappings, $settings );
		$active_tab = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'mappings'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$saved      = ! empty( $_GET['saved'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$version    = $this->version;

		require WS_SWITCHER_COLOR_PATH . 'admin/partials/ws-switcher-color-admin-page.php';
	}

	/**
	 * Rend un champ couleur (picker + texte + swatch).
	 *
	 * @param string $name  Nom du champ.
	 * @param string $value Valeur hex.
	 */
	public static function color_field( $name, $value ) {
		$safe = esc_attr( $value );
		printf(
			'<div class="ws-color-wrap">
				<input type="color" class="ws-color-picker" value="%1$s">
				<input type="text" name="%2$s" value="%1$s" class="ws-color-text" maxlength="7">
				<span class="ws-color-swatch" style="background:%1$s;"></span>
			</div>',
			$safe,
			esc_attr( $name )
		);
	}
}
