<?php
/**
 * Données par défaut du plugin (mappings + réglages).
 *
 * @package WS_Switcher_Color
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Fournit les mappings et réglages par défaut. Logique pure, sans appel WordPress.
 */
class WS_Switcher_Color_Defaults {

	/**
	 * Positions autorisées pour le bouton flottant.
	 *
	 * @var array
	 */
	const POSITIONS = array( 'bottom-right', 'bottom-left', 'top-right', 'top-left', 'hidden' );

	/**
	 * Modes autorisés.
	 *
	 * @var array
	 */
	const MODES = array( 'dark', 'light' );

	/**
	 * Mapping par défaut des 15 variables de la charte WebStrategy.
	 *
	 * @return array
	 */
	public static function mappings() {
		return array(
			array(
				'number' => 1,
				'label'  => 'Texte principal',
				'dark'   => '#f0ede8',
				'light'  => '#14121c',
			),
			array(
				'number' => 2,
				'label'  => 'Texte secondaire',
				'dark'   => '#c4bfda',
				'light'  => '#2e2b38',
			),
			array(
				'number' => 3,
				'label'  => 'Texte muted',
				'dark'   => '#9590a8',
				'light'  => '#9590a8',
			),
			array(
				'number' => 4,
				'label'  => 'Bordure forte',
				'dark'   => '#4a4260',
				'light'  => '#b8b0d0',
			),
			array(
				'number' => 5,
				'label'  => 'Bordure fine',
				'dark'   => '#2e2b38',
				'light'  => '#d4ceca',
			),
			array(
				'number' => 6,
				'label'  => 'Card / hover',
				'dark'   => '#221d32',
				'light'  => '#e4dfd3',
			),
			array(
				'number' => 7,
				'label'  => 'Surface sombre',
				'dark'   => '#1a1724',
				'light'  => '#ede9e0',
			),
			array(
				'number' => 8,
				'label'  => 'Fond principal',
				'dark'   => '#14121c',
				'light'  => '#f5f3ef',
			),
			array(
				'number' => 9,
				'label'  => 'Violet accent',
				'dark'   => '#7c5cbf',
				'light'  => '#7c5cbf',
			),
			array(
				'number' => 10,
				'label'  => 'Violet clair',
				'dark'   => '#9b8ec4',
				'light'  => '#9b8ec4',
			),
			array(
				'number' => 11,
				'label'  => 'Lien / hover',
				'dark'   => '#a899d4',
				'light'  => '#6b4aaf',
			),
			array(
				'number' => 12,
				'label'  => 'Section alt.',
				'dark'   => '#221d32',
				'light'  => '#e4dfd3',
			),
			array(
				'number' => 13,
				'label'  => 'CTA crème',
				'dark'   => '#f0ede8',
				'light'  => '#14121c',
			),
			array(
				'number' => 14,
				'label'  => 'Footer bg',
				'dark'   => '#14121c',
				'light'  => '#ede9e0',
			),
			array(
				'number' => 15,
				'label'  => 'Copyright bg',
				'dark'   => '#0e0c15',
				'light'  => '#e4dfd3',
			),
		);
	}

	/**
	 * Réglages par défaut.
	 *
	 * @return array
	 */
	public static function settings() {
		return array(
			'var_prefix'      => 'awb-color',
			'light_class'     => 'ws-light',
			'default_mode'    => 'dark',
			'toggle_position' => 'bottom-right',
			'toggle_enabled'  => true,
		);
	}
}
