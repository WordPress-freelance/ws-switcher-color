<?php
/**
 * Vue admin : onglet « CSS généré ».
 *
 * Variables disponibles : $generated, $mappings.
 *
 * @package WS_Switcher_Color
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div class="ws-section">
	<p class="ws-help">
		<?php
		printf(
			/* translators: %s: balise head. */
			esc_html__( 'CSS injecté automatiquement dans %s. Copiez-le si vous voulez le coller manuellement ailleurs.', 'ws-switcher-color' ),
			'<code>&lt;head&gt;</code>'
		);
		?>
	</p>

	<div class="ws-code-wrap">
		<textarea id="ws-css-preview" class="ws-code" readonly><?php echo esc_textarea( $generated ); ?></textarea>
		<button type="button" id="ws-copy-css" class="ws-btn ws-btn-ghost ws-copy-btn">
			<?php esc_html_e( 'Copier le CSS', 'ws-switcher-color' ); ?>
		</button>
	</div>

	<p class="ws-count">
		<?php
		printf(
			/* translators: %d: nombre de variables. */
			esc_html( _n( '%d variable — générée depuis les mappings enregistrés.', '%d variables — générées depuis les mappings enregistrés.', count( $mappings ), 'ws-switcher-color' ) ),
			count( $mappings )
		);
		?>
	</p>
</div>
