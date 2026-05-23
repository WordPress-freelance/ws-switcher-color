<?php
/**
 * Vue admin : conteneur, en-tête, onglets et routing.
 *
 * Variables disponibles : $mappings, $settings, $generated, $active_tab, $saved, $version.
 *
 * @package WS_Switcher_Color
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

$ws_tabs = array(
	'mappings' => __( 'Mappings couleurs', 'ws-switcher-color' ),
	'settings' => __( 'Réglages', 'ws-switcher-color' ),
	'css'      => __( 'CSS généré', 'ws-switcher-color' ),
);
?>
<div class="wrap ws-switcher-color-wrap">

	<header class="ws-head">
		<div class="ws-head-title">
			<span class="ws-logo-dot"></span>
			<h1><?php esc_html_e( 'WS Color Switcher', 'ws-switcher-color' ); ?></h1>
			<span class="ws-version">v<?php echo esc_html( $version ); ?></span>
		</div>
		<p class="ws-subtitle">
			<?php esc_html_e( 'Bascule dark / light pour Avada par remappage des variables CSS.', 'ws-switcher-color' ); ?>
		</p>
	</header>

	<?php if ( $saved ) : ?>
		<div class="ws-notice ws-notice-success">
			<?php esc_html_e( 'Paramètres enregistrés.', 'ws-switcher-color' ); ?>
		</div>
	<?php endif; ?>

	<nav class="ws-tabs">
		<?php foreach ( $ws_tabs as $ws_slug => $ws_label ) : ?>
			<a
				href="<?php echo esc_url( add_query_arg( array( 'page' => 'ws-switcher-color', 'tab' => $ws_slug ), admin_url( 'tools.php' ) ) ); ?>"
				class="ws-tab <?php echo $active_tab === $ws_slug ? 'is-active' : ''; ?>">
				<?php echo esc_html( $ws_label ); ?>
			</a>
		<?php endforeach; ?>
	</nav>

	<form method="post" action="" class="ws-panel">
		<?php wp_nonce_field( 'ws_switcher_nonce_action', 'ws_switcher_nonce' ); ?>
		<input type="hidden" name="ws_save_tab" value="<?php echo esc_attr( $active_tab ); ?>">

		<?php
		switch ( $active_tab ) {
			case 'settings':
				require WS_SWITCHER_COLOR_PATH . 'admin/partials/ws-switcher-color-admin-settings.php';
				break;
			case 'css':
				require WS_SWITCHER_COLOR_PATH . 'admin/partials/ws-switcher-color-admin-css.php';
				break;
			case 'mappings':
			default:
				require WS_SWITCHER_COLOR_PATH . 'admin/partials/ws-switcher-color-admin-mappings.php';
				break;
		}
		?>

		<?php if ( 'css' !== $active_tab ) : ?>
			<div class="ws-actions">
				<button type="submit" name="ws_switcher_save" class="ws-btn ws-btn-primary">
					<?php esc_html_e( 'Enregistrer', 'ws-switcher-color' ); ?>
				</button>
			</div>
		<?php endif; ?>
	</form>

</div>
