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
	 * @param array $mappings Liste de mappings (var|number, label, dark, light).
	 * @param array $settings Réglages (light_class, force_important...).
	 * @return string CSS, ou chaîne vide si aucun mapping.
	 */
	public static function generate( array $mappings, array $settings ) {
		if ( empty( $mappings ) ) {
			return '';
		}

		$prefix = isset( $settings['var_prefix'] ) ? $settings['var_prefix'] : 'awb-color';
		$class  = isset( $settings['light_class'] ) ? $settings['light_class'] : 'ws-light';
		$bang   = ! empty( $settings['force_important'] ) ? ' !important' : '';

		$decls = array();
		foreach ( $mappings as $m ) {
			if ( ! isset( $m['light'] ) ) {
				continue;
			}
			$var = WS_Switcher_Color_Defaults::resolve_var( $m, $prefix );
			if ( '' === $var ) {
				continue;
			}
			$decls[] = "  {$var}: {$m['light']}{$bang};";
		}

		if ( empty( $decls ) ) {
			return '';
		}

		$lines   = array();
		$lines[] = "html.{$class} {";
		$lines   = array_merge( $lines, $decls );
		$lines[] = '}';
		$lines[] = '';
		$lines[] = '*, *::before, *::after {';
		$lines[] = '  transition: background-color 0.25s ease, color 0.2s ease, border-color 0.2s ease;';
		$lines[] = '}';

		return implode( "\n", $lines );
	}
}
