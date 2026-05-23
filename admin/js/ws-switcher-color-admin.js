/**
 * WS Color Switcher — interactions admin.
 */
( function () {
	'use strict';

	var L = window.wsSwitcherColorAdmin || {
		copied: '\u2713 Copi\u00e9 !',
		copyLabel: 'Copier le CSS',
		newVar: 'Nouvelle variable'
	};

	var HEX_RE = /^#[0-9a-fA-F]{6}$/;

	/* Sync picker <-> texte <-> swatch. */
	document.addEventListener( 'input', function ( e ) {
		var el = e.target;

		if ( el.classList.contains( 'ws-color-picker' ) ) {
			var wrap = el.closest( '.ws-color-wrap' );
			if ( ! wrap ) {
				return;
			}
			wrap.querySelector( '.ws-color-text' ).value = el.value;
			wrap.querySelector( '.ws-color-swatch' ).style.background = el.value;
		}

		if ( el.classList.contains( 'ws-color-text' ) ) {
			var w = el.closest( '.ws-color-wrap' );
			if ( ! w || ! HEX_RE.test( el.value ) ) {
				return;
			}
			w.querySelector( '.ws-color-picker' ).value = el.value;
			w.querySelector( '.ws-color-swatch' ).style.background = el.value;
		}
	} );

	/* Supprimer une ligne. */
	var body = document.getElementById( 'ws-mappings-body' );
	if ( body ) {
		body.addEventListener( 'click', function ( e ) {
			if ( ! e.target.classList.contains( 'ws-remove-row' ) ) {
				return;
			}
			if ( body.querySelectorAll( 'tr' ).length <= 1 ) {
				return;
			}
			e.target.closest( 'tr' ).remove();
		} );
	}

	/* Ajouter une ligne. */
	var addBtn = document.getElementById( 'ws-add-row' );
	if ( addBtn && body ) {
		addBtn.addEventListener( 'click', function () {
			var rows = body.querySelectorAll( 'tr' );
			var last = rows[ rows.length - 1 ];
			var lastN = last ? ( parseInt( last.querySelector( 'input[type=number]' ).value, 10 ) || 0 ) : 0;
			var next = lastN + 1;

			var tr = document.createElement( 'tr' );
			tr.innerHTML =
				'<td><input type="number" name="ws_mapping_number[]" value="' + next + '" min="1" max="999" class="ws-input ws-input-num"></td>' +
				'<td><input type="text" name="ws_mapping_label[]" value="' + L.newVar + '" class="ws-input ws-input-label"></td>' +
				'<td>' + colorField( 'ws_mapping_dark[]', '#000000' ) + '</td>' +
				'<td>' + colorField( 'ws_mapping_light[]', '#ffffff' ) + '</td>' +
				'<td class="ws-col-action"><button type="button" class="ws-remove-row">&times;</button></td>';
			body.appendChild( tr );
		} );
	}

	function colorField( name, value ) {
		return '<div class="ws-color-wrap">' +
			'<input type="color" class="ws-color-picker" value="' + value + '">' +
			'<input type="text" name="' + name + '" value="' + value + '" class="ws-color-text" maxlength="7">' +
			'<span class="ws-color-swatch" style="background:' + value + ';"></span>' +
			'</div>';
	}

	/* Copier le CSS. */
	var copyBtn = document.getElementById( 'ws-copy-css' );
	if ( copyBtn ) {
		copyBtn.addEventListener( 'click', function () {
			var ta = document.getElementById( 'ws-css-preview' );
			if ( ! ta ) {
				return;
			}
			navigator.clipboard.writeText( ta.value ).then( function () {
				copyBtn.textContent = L.copied;
				setTimeout( function () {
					copyBtn.textContent = L.copyLabel;
				}, 2000 );
			} );
		} );
	}
} )();
