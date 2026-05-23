<?php
/**
 * Désinstallation : nettoyage complet des options.
 *
 * @package WS_Switcher_Color
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'ws_switcher_mappings' );
delete_option( 'ws_switcher_settings' );
