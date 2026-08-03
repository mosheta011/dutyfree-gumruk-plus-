( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		var navToggle = document.querySelector( '.gp-nav-toggle' );
		var nav = document.getElementById( 'gp-primary-navigation' );

		if ( navToggle && nav ) {
			navToggle.addEventListener( 'click', function () {
				var isOpen = nav.classList.toggle( 'is-open' );
				navToggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
			} );
		}

		var submenuToggles = document.querySelectorAll( '.gp-nav__toggle' );
		submenuToggles.forEach( function ( toggle ) {
			toggle.addEventListener( 'click', function ( event ) {
				event.preventDefault();
				var item = toggle.closest( '.gp-nav__item--has-children' );
				if ( ! item ) {
					return;
				}
				var isOpen = item.classList.toggle( 'is-open' );
				toggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
			} );
		} );

		document.addEventListener( 'click', function ( event ) {
			if ( ! nav || ! navToggle ) {
				return;
			}
			if ( nav.contains( event.target ) || navToggle.contains( event.target ) ) {
				return;
			}
			nav.classList.remove( 'is-open' );
			navToggle.setAttribute( 'aria-expanded', 'false' );
		} );

		document.addEventListener( 'keydown', function ( event ) {
			if ( 'Escape' !== event.key || ! nav || ! navToggle ) {
				return;
			}
			nav.classList.remove( 'is-open' );
			navToggle.setAttribute( 'aria-expanded', 'false' );
		} );

		var lampToggle = document.querySelector( '.js-dark-toggle' );
		var lampLabel = document.getElementById( 'lampLabel' );
		if ( lampToggle ) {
			var savedDark = localStorage.getItem( 'gp_dark' ) === '1' ||
				( ! localStorage.getItem( 'gp_dark' ) && window.matchMedia( '(prefers-color-scheme: dark)' ).matches );
			if ( savedDark ) {
				document.body.classList.add( 'gp-dark' );
				if ( lampLabel ) {
					lampLabel.textContent = 'KARANLIK';
				}
			}
			lampToggle.addEventListener( 'click', function () {
				var isDark = document.body.classList.toggle( 'gp-dark' );
				localStorage.setItem( 'gp_dark', isDark ? '1' : '0' );
				if ( lampLabel ) {
					lampLabel.textContent = isDark ? 'KARANLIK' : 'AYDINLIK';
				}
			} );
		}
	} );
} )();
