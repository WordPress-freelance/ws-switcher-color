<?php
/**
 * Génération du CSS d'override mode light.
 *
 * @package WS_Switcher_Color
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Construit le bloc CSS qui remappe les variables Avada en mode light.
 * Logique pure : prend les mappings et réglages en entrée, retourne une chaîne.
 */
class WS_Switcher_Color_CSS_Generator {

	/**
	 * Génère le CSS d'override mode light.
	 *
	 * @param array $mappings Liste de mappings (number, label, dark, light).
	 * @param array $settings Réglages (var_prefix, light_class...).
	 * @return string CSS, ou chaîne vide si aucun mapping.
	 */
	public static function generate( array $mappings, array $settings ) {
		if ( empty( $mappings ) ) {
			return '';
		}

		$prefix = isset( $settings['var_prefix'] ) ? $settings['var_prefix'] : 'awb-color';
		$class  = isset( $settings['light_class'] ) ? $settings['light_class'] : 'ws-light';
		$bang   = ! empty( $settings['force_important'] ) ? ' !important' : '';

		$lines   = array();
		$lines[] = "html.{$class} {";

		foreach ( $mappings as $m ) {
			if ( ! isset( $m['number'], $m['light'] ) ) {
				continue;
			}
			$number  = (int) $m['number'];
			$value   = $m['light'];
			$lines[] = "  --{$prefix}{$number}: {$value}{$bang};";
		}

		$lines[] = '}';
		$lines[] = '';
		$lines[] = '*, *::before, *::after {';
		$lines[] = '  transition: background-color 0.25s ease, color 0.2s ease, border-color 0.2s ease;';
		$lines[] = '}';

		return implode( "\n", $lines );
	}
}
