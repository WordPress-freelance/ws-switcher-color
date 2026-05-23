/**
 * WS Color Switcher — bascule de thème frontend.
 */
( function () {
	'use strict';

	var cfg = window.wsSwitcherColor || {
		key: 'ws-theme',
		lightClass: 'ws-light',
		defaultMode: 'dark'
	};

	var html = document.documentElement;

	function buttons() {
		return document.querySelectorAll( '.ws-theme-toggle' );
	}

	function setIcon( mode ) {
		var icon = mode === 'light' ? '\u2600\uFE0F' : '\uD83C\uDF19';
		buttons().forEach( function ( btn ) {
			btn.innerHTML = icon;
		} );
	}

	function apply( mode ) {
		if ( mode === 'light' ) {
			html.classList.add( cfg.lightClass );
		} else {
			html.classList.remove( cfg.lightClass );
		}
		try {
			localStorage.setItem( cfg.key, mode );
		} catch ( e ) {}
		setIcon( mode );
	}

	function current() {
		var stored;
		try {
			stored = localStorage.getItem( cfg.key );
		} catch ( e ) {}
		return stored || cfg.defaultMode;
	}

	function init() {
		apply( current() );
		buttons().forEach( function ( btn ) {
			btn.addEventListener( 'click', function () {
				apply( html.classList.contains( cfg.lightClass ) ? 'dark' : 'light' );
			} );
		} );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
} )();
