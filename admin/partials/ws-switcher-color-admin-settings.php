<?php
/**
 * Vue admin : onglet « Réglages ».
 *
 * Variables disponibles : $settings.
 *
 * @package WS_Switcher_Color
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

$ws_positions = array(
	'bottom-right' => __( 'Bas droite (fixe)', 'ws-switcher-color' ),
	'bottom-left'  => __( 'Bas gauche (fixe)', 'ws-switcher-color' ),
	'top-right'    => __( 'Haut droite (fixe)', 'ws-switcher-color' ),
	'top-left'     => __( 'Haut gauche (fixe)', 'ws-switcher-color' ),
	'hidden'       => __( 'Masqué — shortcode uniquement', 'ws-switcher-color' ),
);
?>
<div class="ws-section">
	<table class="ws-form">
		<tr>
			<th><label for="ws_var_prefix"><?php esc_html_e( 'Préfixe des variables CSS', 'ws-switcher-color' ); ?></label></th>
			<td>
				<input type="text" id="ws_var_prefix" name="ws_var_prefix" value="<?php echo esc_attr( $settings['var_prefix'] ); ?>" class="ws-input ws-input-text">
				<p class="ws-desc">
					<?php
					printf(
						/* translators: 1: exemple de préfixe, 2: variable générée. */
						esc_html__( 'Ex : %1$s génère %2$s, etc.', 'ws-switcher-color' ),
						'<code>awb-color</code>',
						'<code>--awb-color1</code>'
					);
					?>
				</p>
			</td>
		</tr>
		<tr>
			<th><label for="ws_light_class"><?php esc_html_e( 'Classe CSS mode light', 'ws-switcher-color' ); ?></label></th>
			<td>
				<input type="text" id="ws_light_class" name="ws_light_class" value="<?php echo esc_attr( $settings['light_class'] ); ?>" class="ws-input ws-input-text">
				<p class="ws-desc">
					<?php
					printf(
						/* translators: %s: sélecteur CSS ciblé. */
						esc_html__( 'Classe ajoutée sur la balise html en mode light. Le CSS cible %s.', 'ws-switcher-color' ),
						'<code>html.' . esc_html( $settings['light_class'] ) . '</code>'
					);
					?>
				</p>
			</td>
		</tr>
		<tr>
			<th><label for="ws_default_mode"><?php esc_html_e( 'Mode par défaut', 'ws-switcher-color' ); ?></label></th>
			<td>
				<select id="ws_default_mode" name="ws_default_mode" class="ws-select">
					<option value="dark" <?php selected( $settings['default_mode'], 'dark' ); ?>><?php esc_html_e( 'Dark (recommandé)', 'ws-switcher-color' ); ?></option>
					<option value="light" <?php selected( $settings['default_mode'], 'light' ); ?>><?php esc_html_e( 'Light', 'ws-switcher-color' ); ?></option>
				</select>
				<p class="ws-desc"><?php esc_html_e( 'Mode affiché aux nouveaux visiteurs sans préférence enregistrée.', 'ws-switcher-color' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'Bouton toggle', 'ws-switcher-color' ); ?></th>
			<td>
				<label class="ws-checkbox">
					<input type="checkbox" name="ws_toggle_enabled" value="1" <?php checked( ! empty( $settings['toggle_enabled'] ) ); ?>>
					<?php esc_html_e( 'Afficher le bouton flottant sur le frontend', 'ws-switcher-color' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th><label for="ws_toggle_position"><?php esc_html_e( 'Position du bouton', 'ws-switcher-color' ); ?></label></th>
			<td>
				<select id="ws_toggle_position" name="ws_toggle_position" class="ws-select">
					<?php foreach ( $ws_positions as $ws_val => $ws_lbl ) : ?>
						<option value="<?php echo esc_attr( $ws_val ); ?>" <?php selected( $settings['toggle_position'], $ws_val ); ?>><?php echo esc_html( $ws_lbl ); ?></option>
					<?php endforeach; ?>
				</select>
				<p class="ws-desc">
					<?php
					printf(
						/* translators: %s: shortcode. */
						esc_html__( 'Ou placez le bouton manuellement avec le shortcode %s (bloc HTML Avada par exemple).', 'ws-switcher-color' ),
						'<code>[ws_theme_toggle]</code>'
					);
					?>
				</p>
			</td>
		</tr>
	</table>
</div>
