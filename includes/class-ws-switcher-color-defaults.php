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
	 * Mapping par défaut : les 8 couleurs de la palette globale Avada
	 * (--awb-color1 à --awb-color8). Chaque ligne cible une variable CSS
	 * par son nom complet ; les couleurs custom (--awb-customN,
	 * --awb-custom_color_N) s'ajoutent au cas par cas via l'admin.
	 *
	 * @return array
	 */
	public static function mappings() {
		return array(
			array(
				'var'   => '--awb-color1',
				'label' => 'Texte principal',
				'dark'  => '#f0ede8',
				'light' => '#14121c',
			),
			array(
				'var'   => '--awb-color2',
				'label' => 'Texte secondaire',
				'dark'  => '#c4bfda',
				'light' => '#4a4260',
			),
			array(
				'var'   => '--awb-color3',
				'label' => 'Texte muted',
				'dark'  => '#9590a8',
				'light' => '#6f6a80',
			),
			array(
				'var'   => '--awb-color4',
				'label' => 'Bordure forte',
				'dark'  => '#4a4260',
				'light' => '#c4bfda',
			),
			array(
				'var'   => '--awb-color5',
				'label' => 'Bordure fine',
				'dark'  => '#2e2b38',
				'light' => '#e2e0ea',
			),
			array(
				'var'   => '--awb-color6',
				'label' => 'Card / hover',
				'dark'  => '#221d32',
				'light' => '#ece9f2',
			),
			array(
				'var'   => '--awb-color7',
				'label' => 'Surface sombre',
				'dark'  => '#1a1724',
				'light' => '#f2eff7',
			),
			array(
				'var'   => '--awb-color8',
				'label' => 'Fond principal',
				'dark'  => '#14121c',
				'light' => '#f5f3ef',
			),
		);
	}

	/**
	 * Normalise un nom de variable CSS : garde les caractères valides,
	 * force le préfixe « -- ». Renvoie une chaîne vide si rien d'exploitable.
	 *
	 * @param string $var Nom saisi (ex. « --awb-color8 », « awb-custom15 »).
	 * @return string Nom normalisé (ex. « --awb-color8 ») ou ''.
	 */
	public static function normalize_var( $var ) {
		$var = preg_replace( '/[^a-zA-Z0-9_-]/', '', (string) $var );
		$var = ltrim( $var, '-' );
		if ( '' === $var ) {
			return '';
		}
		return '--' . $var;
	}

	/**
	 * Résout le nom de variable d'une ligne de mapping, en gérant l'ancien
	 * format basé sur « number » + préfixe (rétrocompat <= 1.2.0).
	 *
	 * @param array  $row    Ligne de mapping.
	 * @param string $prefix Préfixe historique (ex. « awb-color »).
	 * @return string Nom de variable normalisé ou ''.
	 */
	public static function resolve_var( array $row, $prefix = 'awb-color' ) {
		if ( isset( $row['var'] ) && '' !== (string) $row['var'] ) {
			return self::normalize_var( $row['var'] );
		}
		if ( isset( $row['number'] ) && (int) $row['number'] > 0 ) {
			return self::normalize_var( $prefix . (int) $row['number'] );
		}
		return '';
	}

	/**
	 * Réglages par défaut.
	 *
	 * @return array
	 */
	public static function settings() {
		return array(
			'light_class'     => 'ws-light',
			'default_mode'    => 'dark',
			'toggle_position' => 'bottom-right',
			'toggle_enabled'  => true,
			'force_important' => false,
		);
	}
}
