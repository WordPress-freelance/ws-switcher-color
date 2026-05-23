<?php
/**
 * Nettoyage des données soumises depuis l'admin.
 *
 * @package WS_Switcher_Color
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Sanitize les mappings et réglages avant persistance.
 */
class WS_Switcher_Color_Sanitizer {

	/**
	 * Nettoie un tableau de mappings issu du POST.
	 *
	 * @param array $vars   Noms de variables CSS (ex. « --awb-color8 »).
	 * @param array $labels Labels.
	 * @param array $darks  Valeurs dark.
	 * @param array $lights Valeurs light.
	 * @return array Mappings nettoyés (lignes sans variable valide ignorées).
	 */
	public static function mappings( array $vars, array $labels, array $darks, array $lights ) {
		$labels = array_map( 'sanitize_text_field', $labels );

		$mappings = array();
		foreach ( $vars as $i => $raw_var ) {
			$var = WS_Switcher_Color_Defaults::normalize_var( $raw_var );
			if ( '' === $var ) {
				continue;
			}
			$dark  = isset( $darks[ $i ] ) ? sanitize_hex_color( $darks[ $i ] ) : '';
			$light = isset( $lights[ $i ] ) ? sanitize_hex_color( $lights[ $i ] ) : '';
			$mappings[] = array(
				'var'   => $var,
				'label' => isset( $labels[ $i ] ) ? $labels[ $i ] : '',
				'dark'  => ! empty( $dark ) ? $dark : '#000000',
				'light' => ! empty( $light ) ? $light : '#ffffff',
			);
		}

		return $mappings;
	}

	/**
	 * Nettoie le tableau de réglages issu du POST.
	 *
	 * @param array $raw Données brutes ($_POST décomposé).
	 * @return array Réglages validés.
	 */
	public static function settings( array $raw ) {
		$mode_in     = isset( $raw['default_mode'] ) ? $raw['default_mode'] : '';
		$position_in = isset( $raw['toggle_position'] ) ? $raw['toggle_position'] : '';

		return array(
			'light_class'     => sanitize_html_class( isset( $raw['light_class'] ) ? $raw['light_class'] : 'ws-light' ),
			'default_mode'    => in_array( $mode_in, WS_Switcher_Color_Defaults::MODES, true ) ? $mode_in : 'dark',
			'toggle_position' => in_array( $position_in, WS_Switcher_Color_Defaults::POSITIONS, true ) ? $position_in : 'bottom-right',
			'toggle_enabled'  => ! empty( $raw['toggle_enabled'] ),
			'force_important' => ! empty( $raw['force_important'] ),
		);
	}
}
