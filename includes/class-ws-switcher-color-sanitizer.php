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
	 * @param array $numbers Numéros de variables.
	 * @param array $labels  Labels.
	 * @param array $darks   Valeurs dark.
	 * @param array $lights  Valeurs light.
	 * @return array Mappings nettoyés, triés par numéro.
	 */
	public static function mappings( array $numbers, array $labels, array $darks, array $lights ) {
		$numbers = array_map( 'intval', $numbers );
		$labels  = array_map( 'sanitize_text_field', $labels );
		$darks   = array_map( 'sanitize_hex_color', $darks );
		$lights  = array_map( 'sanitize_hex_color', $lights );

		$mappings = array();
		foreach ( $numbers as $i => $num ) {
			if ( $num < 1 ) {
				continue;
			}
			$mappings[] = array(
				'number' => $num,
				'label'  => isset( $labels[ $i ] ) ? $labels[ $i ] : '',
				'dark'   => ! empty( $darks[ $i ] ) ? $darks[ $i ] : '#000000',
				'light'  => ! empty( $lights[ $i ] ) ? $lights[ $i ] : '#ffffff',
			);
		}

		usort(
			$mappings,
			function ( $a, $b ) {
				return $a['number'] - $b['number'];
			}
		);

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
			'var_prefix'      => sanitize_text_field( isset( $raw['var_prefix'] ) ? $raw['var_prefix'] : 'awb-color' ),
			'light_class'     => sanitize_html_class( isset( $raw['light_class'] ) ? $raw['light_class'] : 'ws-light' ),
			'default_mode'    => in_array( $mode_in, WS_Switcher_Color_Defaults::MODES, true ) ? $mode_in : 'dark',
			'toggle_position' => in_array( $position_in, WS_Switcher_Color_Defaults::POSITIONS, true ) ? $position_in : 'bottom-right',
			'toggle_enabled'  => ! empty( $raw['toggle_enabled'] ),
		);
	}
}
