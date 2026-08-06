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
		if ( lampToggle ) {
			var savedDark = localStorage.getItem( 'gp_dark' ) === '1' ||
				( ! localStorage.getItem( 'gp_dark' ) && window.matchMedia( '(prefers-color-scheme: dark)' ).matches );
			if ( savedDark ) {
				document.body.classList.add( 'gp-dark' );
			}
			lampToggle.addEventListener( 'click', function () {
				var isDark = document.body.classList.toggle( 'gp-dark' );
				localStorage.setItem( 'gp_dark', isDark ? '1' : '0' );
			} );
		}

		var header = document.querySelector( '.gp-header' );
		if ( header ) {
			var handleScroll = function () {
				if ( window.scrollY > 50 ) {
					header.classList.add( 'is-sticky' );
				} else {
					header.classList.remove( 'is-sticky' );
				}
			};
			window.addEventListener( 'scroll', handleScroll, { passive: true } );
			handleScroll();
		}

		/* ── Default mini-cart hover removed for custom drawer ──────────────────── */


		var hero = document.querySelector( '.hero' );

		var orbs = document.querySelectorAll( '.ag-orb' );
		var tag1 = document.querySelector( '.ag-tag-1' );
		var tag2 = document.querySelector( '.ag-tag-2' );

		if ( hero && orbs.length > 0 ) {
			var mouseX = 0;
			var mouseY = 0;
			var currentX = 0;
			var currentY = 0;

			// Track mouse position relative to center of screen
			document.addEventListener('mousemove', function(e) {
				mouseX = (e.clientX / window.innerWidth) - 0.5;
				mouseY = (e.clientY / window.innerHeight) - 0.5;
			});

			var seedTrajectory = [];
			orbs.forEach( function ( orb, i ) {
				// Random distribution for orbs
				var radius = 200 + Math.random() * 400;
				var angle = Math.random() * Math.PI * 2;
				seedTrajectory.push( {
					baseX: Math.cos( angle ) * radius,
					baseY: Math.sin( angle ) * radius * 0.8,
					parallax: 0.2 + (Math.random() * 1.5),
					phase: Math.random() * Math.PI * 2,
					speed: 0.5 + Math.random() * 0.5,
					scale: 0.5 + Math.random() * 1.5
				} );
			} );

			var renderLoop = function () {
				var time = Date.now() * 0.001;
				var scrollY = window.scrollY || window.pageYOffset || 0;

				// Smooth easing for mouse parallax
				currentX += (mouseX - currentX) * 0.05;
				currentY += (mouseY - currentY) * 0.05;

				orbs.forEach( function ( orb, i ) {
					var t = seedTrajectory[ i ];

					// Gentle continuous floating drift
					var driftX = Math.sin( time * t.speed + t.phase ) * 40;
					var driftY = Math.cos( time * (t.speed * 0.8) + t.phase ) * 40;

					// Mouse parallax effect (shapes move in opposite direction of mouse)
					var pX = currentX * -300 * t.parallax;
					var pY = currentY * -300 * t.parallax;

					// Scroll parallax
					var sY = scrollY * (t.parallax * 0.6);

					var tx = t.baseX + driftX + pX;
					var ty = t.baseY + driftY + pY + sY;

					orb.style.transform = 'translate(' + tx.toFixed(1) + 'px, ' + ty.toFixed(1) + 'px) scale(' + t.scale.toFixed(2) + ')';
					orb.style.opacity = '1';
				} );

				if ( tag1 ) {
					var t1Wave = Math.sin( time * 1.2 ) * 15;
					var t1X = -250 + (currentX * -150) + (scrollY * 0.2);
					var t1Y = -120 + t1Wave + (currentY * -150) + (scrollY * 0.5);
					tag1.style.transform = 'translate(' + t1X.toFixed(1) + 'px, ' + t1Y.toFixed(1) + 'px) rotate(-12deg)';
					tag1.style.opacity = '1';
				}
				if ( tag2 ) {
					var t2Wave = Math.cos( time * 1.1 ) * 15;
					var t2X = 250 + (currentX * -180) - (scrollY * 0.1);
					var t2Y = 140 + t2Wave + (currentY * -180) + (scrollY * 0.3);
					tag2.style.transform = 'translate(' + t2X.toFixed(1) + 'px, ' + t2Y.toFixed(1) + 'px) rotate(8deg)';
					tag2.style.opacity = '1';
				}

				requestAnimationFrame( renderLoop );
			};

			requestAnimationFrame( renderLoop );
		}

		/* ── Custom WooCommerce Sorting Dropdown ─────────────────────── */
		var orderbySelects = document.querySelectorAll( '.woocommerce-ordering select.orderby' );
		orderbySelects.forEach( function ( select ) {
			// Hide the native select
			select.style.display = 'none';

			// Create the custom container
			var customContainer = document.createElement( 'div' );
			customContainer.className = 'gp-custom-orderby';
			customContainer.setAttribute( 'tabindex', '0' ); // Make focusable

			// Find the currently selected option to show its text
			var selectedOption = select.options[ select.selectedIndex ];
			var currentText = selectedOption ? selectedOption.text : '';

			// Create the trigger button
			var trigger = document.createElement( 'div' );
			trigger.className = 'gp-orderby-trigger';
			trigger.innerHTML = '<span class="gp-orderby-label">' + currentText + '</span>' +
				'<svg class="gp-chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>';

			// Create the dropdown menu
			var dropdown = document.createElement( 'div' );
			dropdown.className = 'gp-orderby-dropdown';

			// Populate custom options from native options
			Array.from( select.options ).forEach( function ( option, index ) {
				var optDiv = document.createElement( 'div' );
				optDiv.className = 'gp-orderby-option';
				if ( option.selected ) {
					optDiv.classList.add( 'is-selected' );
				}
				optDiv.setAttribute( 'data-value', option.value );
				optDiv.textContent = option.text;

				optDiv.addEventListener( 'click', function ( e ) {
					e.stopPropagation(); // prevent bubbling to trigger or document
					// Update native select value
					select.value = option.value;
					// Update trigger text
					trigger.querySelector( '.gp-orderby-label' ).textContent = option.text;
					// Remove is-selected from all, add to this
					dropdown.querySelectorAll( '.gp-orderby-option' ).forEach( function ( el ) {
						el.classList.remove( 'is-selected' );
					} );
					optDiv.classList.add( 'is-selected' );
					// Close dropdown
					customContainer.classList.remove( 'is-open' );
					// Submit form to trigger WooCommerce reload
					var form = select.closest( 'form' );
					if ( form ) {
						form.submit();
					}
				} );

				dropdown.appendChild( optDiv );
			} );

			customContainer.appendChild( trigger );
			customContainer.appendChild( dropdown );
			
			// Insert the custom container right after the native select
			select.parentNode.insertBefore( customContainer, select.nextSibling );

			// Toggle dropdown on trigger click
			trigger.addEventListener( 'click', function ( e ) {
				e.stopPropagation();
				customContainer.classList.toggle( 'is-open' );
			} );

			// Keyboard accessibility
			customContainer.addEventListener( 'keydown', function ( e ) {
				if ( e.key === 'Enter' || e.key === ' ' ) {
					e.preventDefault();
					customContainer.classList.toggle( 'is-open' );
				}
			} );

			// Close dropdown when clicking outside
			document.addEventListener( 'click', function ( e ) {
				if ( !customContainer.contains( e.target ) ) {
					customContainer.classList.remove( 'is-open' );
				}
			} );
		} );

	} );
} )();
