<?php
/**
 * Logique spécifique au frontend.
 *
 * @package WS_Switcher_Color
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Injection du CSS d'override, anti-FOUC, bouton flottant et shortcode.
 */
class WS_Switcher_Color_Public {

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
	 * Récupère les réglages courants (avec défauts).
	 *
	 * @return array
	 */
	private function get_settings() {
		return get_option( WS_SWITCHER_COLOR_OPT_SETTINGS, WS_Switcher_Color_Defaults::settings() );
	}

	/**
	 * Récupère les mappings courants (avec défauts).
	 *
	 * @return array
	 */
	private function get_mappings() {
		return get_option( WS_SWITCHER_COLOR_OPT_MAPPINGS, WS_Switcher_Color_Defaults::mappings() );
	}

	/**
	 * Applique la classe light très tôt pour éviter le flash (FOUC).
	 */
	public function print_anti_fouc() {
		$settings = $this->get_settings();
		$class    = esc_js( $settings['light_class'] );
		$default  = esc_js( $settings['default_mode'] );
		$key      = esc_js( WS_SWITCHER_COLOR_STORAGE_KEY );

		echo "<script>(function(){var s=localStorage.getItem('{$key}')||'{$default}';if(s==='light')document.documentElement.classList.add('{$class}');})();</script>\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Enqueue le CSS frontend + inject les variables d'override.
	 */
	public function enqueue_styles() {
		wp_enqueue_style(
			$this->plugin_name,
			WS_SWITCHER_COLOR_URL . 'public/css/ws-switcher-color-public.css',
			array(),
			$this->version,
			'all'
		);

		$css = WS_Switcher_Color_CSS_Generator::generate( $this->get_mappings(), $this->get_settings() );
		if ( ! empty( $css ) ) {
			wp_add_inline_style( $this->plugin_name, $css );
		}
	}

	/**
	 * Enqueue le JS frontend et passe la config.
	 */
	public function enqueue_scripts() {
		$settings = $this->get_settings();

		wp_enqueue_script(
			$this->plugin_name,
			WS_SWITCHER_COLOR_URL . 'public/js/ws-switcher-color-public.js',
			array(),
			$this->version,
			true
		);
		wp_localize_script(
			$this->plugin_name,
			'wsSwitcherColor',
			array(
				'key'         => WS_SWITCHER_COLOR_STORAGE_KEY,
				'lightClass'  => $settings['light_class'],
				'defaultMode' => $settings['default_mode'],
			)
		);
	}

	/**
	 * Affiche le bouton flottant en footer si activé.
	 */
	public function print_toggle_button() {
		$settings = $this->get_settings();

		if ( empty( $settings['toggle_enabled'] ) ) {
			return;
		}
		if ( ! in_array( $settings['toggle_position'], array( 'bottom-right', 'bottom-left', 'top-right', 'top-left' ), true ) ) {
			return;
		}

		printf(
			'<button class="ws-theme-toggle ws-theme-toggle--%1$s" aria-label="%2$s">🌙</button>',
			esc_attr( $settings['toggle_position'] ),
			esc_attr__( 'Basculer le thème clair / sombre', 'ws-switcher-color' )
		);
	}

	/**
	 * Rend le bouton via shortcode [ws_theme_toggle] (inline, non fixe).
	 *
	 * @return string
	 */
	public function render_toggle_shortcode() {
		return sprintf(
			'<button class="ws-theme-toggle ws-theme-toggle--inline" aria-label="%s">🌙</button>',
			esc_attr__( 'Basculer le thème clair / sombre', 'ws-switcher-color' )
		);
	}
}
