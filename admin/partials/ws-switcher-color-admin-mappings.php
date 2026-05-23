<?php
/**
 * Vue admin : onglet « Mappings couleurs ».
 *
 * Variables disponibles : $mappings, $settings.
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
			/* translators: %s: préfixe des variables CSS. */
			esc_html__( 'Chaque ligne mappe une variable CSS %s. La colonne Dark est la valeur actuelle du site, la colonne Light la remplace quand le mode light est actif.', 'ws-switcher-color' ),
			'<code>--' . esc_html( $settings['var_prefix'] ) . 'N</code>'
		);
		?>
	</p>

	<table class="ws-table" id="ws-mappings-table">
		<thead>
			<tr>
				<th class="ws-col-num"><?php esc_html_e( 'N°', 'ws-switcher-color' ); ?></th>
				<th><?php esc_html_e( 'Label', 'ws-switcher-color' ); ?></th>
				<th class="ws-col-color"><?php esc_html_e( 'Dark (valeur actuelle)', 'ws-switcher-color' ); ?></th>
				<th class="ws-col-color"><?php esc_html_e( 'Light (override)', 'ws-switcher-color' ); ?></th>
				<th class="ws-col-action"></th>
			</tr>
		</thead>
		<tbody id="ws-mappings-body">
			<?php foreach ( $mappings as $ws_m ) : ?>
				<tr>
					<td>
						<input type="number" name="ws_mapping_number[]" value="<?php echo esc_attr( (int) $ws_m['number'] ); ?>" min="1" max="999" class="ws-input ws-input-num">
					</td>
					<td>
						<input type="text" name="ws_mapping_label[]" value="<?php echo esc_attr( $ws_m['label'] ); ?>" class="ws-input ws-input-label">
					</td>
					<td><?php WS_Switcher_Color_Admin::color_field( 'ws_mapping_dark[]', $ws_m['dark'] ); ?></td>
					<td><?php WS_Switcher_Color_Admin::color_field( 'ws_mapping_light[]', $ws_m['light'] ); ?></td>
					<td class="ws-col-action">
						<button type="button" class="ws-remove-row" title="<?php esc_attr_e( 'Supprimer', 'ws-switcher-color' ); ?>">&times;</button>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div class="ws-table-foot">
		<button type="button" id="ws-add-row" class="ws-btn ws-btn-ghost">
			<?php esc_html_e( '+ Ajouter une variable', 'ws-switcher-color' ); ?>
		</button>
		<span class="ws-count">
			<?php
			printf(
				/* translators: %d: nombre de variables. */
				esc_html( _n( '%d variable configurée', '%d variables configurées', count( $mappings ), 'ws-switcher-color' ) ),
				count( $mappings )
			);
			?>
		</span>
	</div>
</div>
