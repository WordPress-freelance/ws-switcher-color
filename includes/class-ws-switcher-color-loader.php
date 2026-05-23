<?php
/**
 * Enregistrement de tous les hooks du plugin.
 *
 * @package WS_Switcher_Color
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Maintient et enregistre la liste des actions, filtres et shortcodes.
 */
class WS_Switcher_Color_Loader {

	/**
	 * Actions enregistrées avec WordPress.
	 *
	 * @var array
	 */
	protected $actions;

	/**
	 * Filtres enregistrés avec WordPress.
	 *
	 * @var array
	 */
	protected $filters;

	/**
	 * Shortcodes enregistrés avec WordPress.
	 *
	 * @var array
	 */
	protected $shortcodes;

	/**
	 * Initialise les collections.
	 */
	public function __construct() {
		$this->actions    = array();
		$this->filters    = array();
		$this->shortcodes = array();
	}

	/**
	 * Ajoute une action à la collection.
	 *
	 * @param string $hook          Nom du hook WordPress.
	 * @param object $component      Instance contenant le callback.
	 * @param string $callback       Méthode appelée.
	 * @param int    $priority       Priorité.
	 * @param int    $accepted_args  Nombre d'arguments.
	 */
	public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Ajoute un filtre à la collection.
	 *
	 * @param string $hook          Nom du hook WordPress.
	 * @param object $component      Instance contenant le callback.
	 * @param string $callback       Méthode appelée.
	 * @param int    $priority       Priorité.
	 * @param int    $accepted_args  Nombre d'arguments.
	 */
	public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Ajoute un shortcode à la collection.
	 *
	 * @param string $tag       Tag du shortcode.
	 * @param object $component  Instance contenant le callback.
	 * @param string $callback   Méthode appelée.
	 */
	public function add_shortcode( $tag, $component, $callback ) {
		$this->shortcodes[] = array(
			'tag'       => $tag,
			'component' => $component,
			'callback'  => $callback,
		);
	}

	/**
	 * Helper d'ajout dans une collection hooks.
	 *
	 * @param array  $hooks         Collection existante.
	 * @param string $hook          Nom du hook.
	 * @param object $component      Instance.
	 * @param string $callback       Méthode.
	 * @param int    $priority       Priorité.
	 * @param int    $accepted_args  Nombre d'arguments.
	 * @return array
	 */
	private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {
		$hooks[] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args,
		);
		return $hooks;
	}

	/**
	 * Enregistre tous les hooks auprès de WordPress.
	 */
	public function run() {
		foreach ( $this->filters as $hook ) {
			add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

		foreach ( $this->actions as $hook ) {
			add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

		foreach ( $this->shortcodes as $shortcode ) {
			add_shortcode( $shortcode['tag'], array( $shortcode['component'], $shortcode['callback'] ) );
		}
	}
}
