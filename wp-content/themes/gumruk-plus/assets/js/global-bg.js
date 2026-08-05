( function () {
	'use strict';

	var prefersReduced = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	/* ── City coordinates (lon/lat) ───────────────────────────────── */
	var CITIES = [
		{ name: 'Istanbul',   lon:  28.98, lat:  41.01 },
		{ name: 'Dubai',      lon:  55.27, lat:  25.20 },
		{ name: 'London',     lon:  -0.12, lat:  51.51 },
		{ name: 'Paris',      lon:   2.35, lat:  48.85 },
		{ name: 'Singapore',  lon: 103.82, lat:   1.35 },
		{ name: 'Tokyo',      lon: 139.69, lat:  35.69 },
		{ name: 'New York',   lon: -74.00, lat:  40.71 },
		{ name: 'Frankfurt',  lon:   8.68, lat:  50.11 },
		{ name: 'Amsterdam',  lon:   4.90, lat:  52.37 },
		{ name: 'Hong Kong',  lon: 114.16, lat:  22.32 },
		{ name: 'Zurich',     lon:   8.54, lat:  47.37 },
		{ name: 'Milan',      lon:   9.19, lat:  45.46 },
		{ name: 'Seoul',      lon: 126.97, lat:  37.57 },
		{ name: 'Sydney',     lon: 151.21, lat: -33.87 },
		{ name: 'Moscow',     lon:  37.62, lat:  55.75 },
	];

	var ROUTES = [
		[0,1],[0,2],[0,3],[0,6],[0,7],[0,8],
		[1,9],[1,4],[2,3],[2,6],[3,7],[4,9],
		[6,7],[7,8],[9,10],[10,11],[11,3],[5,9],[5,12],
		[12,9],[13,9],[14,0],[14,7],[4,5],
	];

	var CONTINENTS = [
		[[-168,72],[-140,70],[-135,60],[-138,56],[-130,54],[-124,48],[-124,45],[-117,32],[-105,20],[-88,15],[-83,10],[-77,8],[-77,5],[-73,12],[-63,10],[-60,5],[-50,5],[-52,12],[-57,16],[-60,16],[-62,17],[-63,18],[-75,20],[-80,23],[-80,25],[-81,30],[-80,32],[-76,35],[-75,38],[-74,40],[-70,43],[-66,44],[-60,46],[-58,48],[-55,50],[-53,53],[-55,58],[-60,62],[-65,65],[-67,70],[-68,73],[-95,75],[-100,73],[-110,74],[-120,72],[-130,70],[-145,72],[-158,70],[-162,68],[-168,72]],
		[[-80,12],[-75,10],[-70,12],[-60,5],[-52,4],[-50,0],[-50,-10],[-40,-20],[-42,-22],[-44,-24],[-48,-28],[-50,-30],[-52,-33],[-58,-38],[-63,-42],[-65,-46],[-67,-52],[-68,-55],[-65,-58],[-58,-52],[-53,-48],[-50,-42],[-48,-35],[-50,-30],[-54,-25],[-58,-20],[-58,-15],[-60,-10],[-65,-5],[-70,0],[-75,5],[-77,10],[-80,12]],
		[[-10,36],[2,37],[5,43],[8,44],[12,44],[15,42],[20,38],[25,37],[30,40],[32,44],[30,46],[25,47],[20,50],[15,54],[10,55],[5,58],[0,55],[-5,54],[-8,52],[-10,48],[-5,44],[-2,42],[-5,38],[-8,36],[-10,36]],
		[[-18,15],[-15,10],[-10,5],[-5,5],[0,4],[5,5],[10,5],[15,5],[20,5],[25,5],[30,5],[35,10],[40,12],[43,12],[42,15],[40,20],[36,22],[35,25],[34,30],[32,32],[30,31],[28,30],[25,22],[18,15],[15,10],[10,8],[5,5],[0,5],[-5,5],[-10,5],[-15,10],[-18,15]],
		[[25,40],[30,42],[35,42],[40,38],[45,35],[50,30],[55,25],[60,22],[65,20],[70,22],[75,25],[80,28],[85,28],[90,25],[95,20],[100,15],[105,10],[110,5],[115,3],[120,5],[125,10],[130,15],[135,18],[140,22],[145,25],[145,40],[140,45],[135,48],[130,50],[125,50],[120,55],[115,58],[110,60],[105,55],[100,55],[95,56],[90,52],[85,50],[80,48],[75,45],[70,45],[65,42],[60,42],[55,40],[50,42],[45,42],[40,40],[35,40],[30,40],[25,40]],
		[[114,-22],[116,-20],[120,-18],[125,-14],[132,-12],[136,-12],[138,-15],[140,-18],[142,-20],[144,-20],[148,-20],[152,-24],[154,-28],[152,-32],[150,-36],[148,-38],[144,-38],[142,-36],[138,-35],[134,-33],[130,-32],[126,-34],[118,-32],[114,-28],[114,-22]],
	];

	function project( lon, lat, w, h ) {
		var scale = w > 1200 ? 1 : 1.5;
		var x = ( lon + 180 ) / 360 * w * scale - (w * (scale - 1) * 0.5);
		var latRad = lat * Math.PI / 180;
		var mercN  = Math.log( Math.tan( Math.PI / 4 + latRad / 2 ) );
		var y      = h / 2 - ( mercN / ( 2 * Math.PI ) ) * h * 0.9 * scale;
		return { x: x, y: y };
	}

	function isDark() {
		return document.body.classList.contains( 'gp-dark' );
	}

	function init() {
		var canvas = document.getElementById( 'gp-global-canvas' );
		if ( ! canvas ) return;

		var ctx = canvas.getContext( '2d', { alpha: true } );
		var W = 0, H = 0;

		function resize() {
			W = canvas.width  = window.innerWidth;
			H = canvas.height = window.innerHeight;
		}
		resize();
		window.addEventListener( 'resize', resize, { passive: true } );

		/* ── Particles ───────────────────────────────────────────── */
		var PARTICLE_COUNT = prefersReduced ? 10 : 80;
		var particles = [];
		for ( var i = 0; i < PARTICLE_COUNT; i++ ) {
			particles.push( {
				x: Math.random(), y: Math.random(),
				size:  0.5 + Math.random() * 1.5,
				vx:    ( Math.random() - 0.5 ) * 0.0001,
				vy:    ( Math.random() - 0.5 ) * 0.0001 - 0.0001, // mostly upwards
				alpha: 0.1 + Math.random() * 0.4,
				phase: Math.random() * Math.PI * 2,
			} );
		}

		/* ── Route animation state ──────────────────────────────── */
		var routeStates = ROUTES.map( function () {
			return { progress: Math.random(), speed: 0.00015 + Math.random() * 0.0002 };
		} );

		/* ── Mouse parallax ─────────────────────────────────────── */
		var mouseX = 0.5, mouseY = 0.5;
		var smoothX = 0.5, smoothY = 0.5;
		document.addEventListener( 'mousemove', function ( e ) {
			if ( prefersReduced ) return;
			mouseX = e.clientX / window.innerWidth;
			mouseY = e.clientY / window.innerHeight;
		}, { passive: true } );

		function cityPos( city ) {
			var p = project( city.lon, city.lat, W, H );
			var offX = prefersReduced ? 0 : ( smoothX - 0.5 ) * 40;
			var offY = prefersReduced ? 0 : ( smoothY - 0.5 ) * 20;
			return { x: p.x + offX, y: p.y + offY };
		}

		/* ── Palette helpers ─────────────────────────────────────── */
		function palette() {
			if ( isDark() ) {
				return {
					/* Dark Emerald/Duty Free Premium */
					map:         'rgba(40, 200, 180, 0.05)',
					mapStroke:   1.0,
					routeLine:   'rgba(0, 210, 255, 0.08)',
					packet:      [ 0, 210, 255 ],
					node:        [ 40, 200, 180 ],
					particle:    [ 100, 255, 230 ],
					blobs: [
						{ cx:0.15, cy:0.3, r:0.50, c:[0, 210, 255], a:0.04, p:0.0 }, /* Cyan */
						{ cx:0.80, cy:0.25,r:0.45, c:[40, 200, 180], a:0.05, p:1.8 }, /* Emerald */
						{ cx:0.50, cy:0.75,r:0.42, c:[8, 20, 20], a:0.1, p:3.2 },     /* Deep core */
						{ cx:0.88, cy:0.65,r:0.38, c:[0, 150, 130], a:0.03, p:5.0 },
					],
					spotlight:   'rgba(0, 210, 255, 0.03)',
					spotlightMid:'rgba(40, 200, 180, 0.01)',
				};
			} else {
				return {
					/* Light Mint/Cream for readability */
					map:         'rgba(20, 80, 70, 0.06)',
					mapStroke:   0.8,
					routeLine:   'rgba(40, 120, 110, 0.07)',
					packet:      [ 40, 120, 110 ],
					node:        [ 20, 150, 130 ],
					particle:    [ 80, 160, 140 ],
					blobs: [
						{ cx:0.15, cy:0.3, r:0.50, c:[220, 240, 230], a:0.3, p:0.0 },
						{ cx:0.80, cy:0.25,r:0.45, c:[200, 230, 220], a:0.2, p:1.8 },
						{ cx:0.50, cy:0.75,r:0.42, c:[240, 245, 240], a:0.2, p:3.2 },
						{ cx:0.88, cy:0.65,r:0.38, c:[180, 220, 200], a:0.1, p:5.0 },
					],
					spotlight:   'rgba(200, 230, 220, 0.1)',
					spotlightMid:'rgba(220, 240, 230, 0.05)',
				};
			}
		}

		/* ── Draw helpers ────────────────────────────────────────── */
		function drawBlobs( t, pal ) {
			pal.blobs.forEach( function ( b ) {
				var cx = ( b.cx + ( prefersReduced ? 0 : Math.sin( t * 0.005 + b.p ) * 0.1 ) ) * W;
				var cy = ( b.cy + ( prefersReduced ? 0 : Math.cos( t * 0.004 + b.p ) * 0.1 ) ) * H;
				var r  = b.r * Math.max( W, H );
				var g  = ctx.createRadialGradient( cx, cy, 0, cx, cy, r );
				g.addColorStop( 0,   'rgba('+b.c[0]+','+b.c[1]+','+b.c[2]+','+b.a+')' );
				g.addColorStop( 0.6, 'rgba('+b.c[0]+','+b.c[1]+','+b.c[2]+','+(b.a*0.3)+')' );
				g.addColorStop( 1,   'rgba('+b.c[0]+','+b.c[1]+','+b.c[2]+',0)' );
				ctx.save();
				ctx.fillStyle = g;
				ctx.beginPath();
				ctx.arc( cx, cy, r, 0, Math.PI * 2 );
				ctx.fill();
				ctx.restore();
			} );
		}

		function drawSpotlight( pal ) {
			/* Focus spot behind the hero */
			var cx = W * 0.5, cy = H * 0.4;
			var r  = Math.min( W, H ) * 0.7;
			var g  = ctx.createRadialGradient( cx, cy, 0, cx, cy, r );
			g.addColorStop( 0,   pal.spotlight );
			g.addColorStop( 0.5, pal.spotlightMid );
			g.addColorStop( 1,   'rgba(0,0,0,0)' );
			ctx.fillStyle = g;
			ctx.beginPath();
			ctx.arc( cx, cy, r, 0, Math.PI * 2 );
			ctx.fill();
		}

		function drawMap( pal ) {
			ctx.save();
			ctx.globalAlpha = 1;
			ctx.strokeStyle = pal.map;
			ctx.lineWidth   = pal.mapStroke;
			var offX = prefersReduced ? 0 : ( smoothX - 0.5 ) * 40;
			var offY = prefersReduced ? 0 : ( smoothY - 0.5 ) * 20;
			CONTINENTS.forEach( function ( poly ) {
				ctx.beginPath();
				poly.forEach( function ( pt, idx ) {
					var p = project( pt[0], pt[1], W, H );
					if ( idx === 0 ) ctx.moveTo( p.x + offX, p.y + offY );
					else             ctx.lineTo( p.x + offX, p.y + offY );
				} );
				ctx.closePath();
				ctx.stroke();
			} );
			ctx.restore();
		}

		function drawRoutes( pal ) {
			if ( prefersReduced ) return;
			var pc = pal.packet;
			ROUTES.forEach( function ( route, idx ) {
				var a = cityPos( CITIES[ route[0] ] );
				var b = cityPos( CITIES[ route[1] ] );
				var state = routeStates[ idx ];
				var mx = ( a.x + b.x ) / 2;
				var my = ( a.y + b.y ) / 2 - Math.abs( b.x - a.x ) * 0.18;

				/* Faint arc */
				ctx.save();
				ctx.globalAlpha = 1;
				ctx.strokeStyle = pal.routeLine;
				ctx.lineWidth   = 0.8;
				ctx.beginPath();
				ctx.moveTo( a.x, a.y );
				ctx.quadraticCurveTo( mx, my, b.x, b.y );
				ctx.stroke();
				ctx.restore();

				/* Travelling packet */
				state.progress += state.speed;
				if ( state.progress > 1 ) state.progress -= 1;
				var tp = state.progress;
				var px = (1-tp)*(1-tp)*a.x + 2*(1-tp)*tp*mx + tp*tp*b.x;
				var py = (1-tp)*(1-tp)*a.y + 2*(1-tp)*tp*my + tp*tp*b.y;

				ctx.save();
				ctx.globalAlpha = 0.8;
				var glow = ctx.createRadialGradient( px, py, 0, px, py, 6 );
				glow.addColorStop( 0,   'rgba('+pc[0]+','+pc[1]+','+pc[2]+',1)' );
				glow.addColorStop( 0.4, 'rgba('+pc[0]+','+pc[1]+','+pc[2]+',0.4)' );
				glow.addColorStop( 1,   'rgba(0,0,0,0)' );
				ctx.fillStyle = glow;
				ctx.beginPath();
				ctx.arc( px, py, 6, 0, Math.PI * 2 );
				ctx.fill();
				ctx.restore();
			} );
		}

		function drawNodes( t, pal ) {
			var nc = pal.node;
			CITIES.forEach( function ( city ) {
				var pos   = cityPos( city );
				var pulse = 0.5 + 0.5 * Math.sin( t * 0.03 + city.lon * 0.04 );

				/* Outer glowing ring */
				ctx.save();
				ctx.globalAlpha = 0.15 * pulse;
				var ring = ctx.createRadialGradient( pos.x, pos.y, 0, pos.x, pos.y, 12 );
				ring.addColorStop( 0, 'rgba('+nc[0]+','+nc[1]+','+nc[2]+',1)' );
				ring.addColorStop( 1, 'rgba('+nc[0]+','+nc[1]+','+nc[2]+',0)' );
				ctx.fillStyle = ring;
				ctx.beginPath();
				ctx.arc( pos.x, pos.y, 12, 0, Math.PI * 2 );
				ctx.fill();
				ctx.restore();

				/* Core node */
				ctx.save();
				ctx.globalAlpha = 0.5 + 0.3 * pulse;
				ctx.fillStyle = 'rgb('+nc[0]+','+nc[1]+','+nc[2]+')';
				ctx.beginPath();
				ctx.arc( pos.x, pos.y, 2.5, 0, Math.PI * 2 );
				ctx.fill();
				ctx.restore();
			} );
		}

		function drawParticles( t, pal ) {
			var pc = pal.particle;
			particles.forEach( function ( p ) {
				p.x += p.vx; p.y += p.vy;
				if ( p.x < 0 ) p.x = 1;
				if ( p.x > 1 ) p.x = 0;
				if ( p.y < 0 ) p.y = 1;
				if ( p.y > 1 ) p.y = 0;
				var flicker = 0.5 + 0.5 * Math.sin( t * 0.02 + p.phase );
				ctx.save();
				
				/* Parallax offset for particles */
				var offX = prefersReduced ? 0 : ( smoothX - 0.5 ) * 80 * p.size;
				var offY = prefersReduced ? 0 : ( smoothY - 0.5 ) * 40 * p.size;
				
				var px = p.x * W + offX;
				var py = p.y * H + offY;

				ctx.globalAlpha = p.alpha * flicker;
				ctx.fillStyle = 'rgb('+pc[0]+','+pc[1]+','+pc[2]+')';
				ctx.beginPath();
				ctx.arc( px, py, p.size, 0, Math.PI * 2 );
				ctx.fill();
				ctx.restore();
			} );
		}

		/* ── Render loop ─────────────────────────────────────────── */
		var frame = 0;
		var raf;

		function render() {
			frame++;
			smoothX += ( mouseX - smoothX ) * 0.04;
			smoothY += ( mouseY - smoothY ) * 0.04;
			ctx.clearRect( 0, 0, W, H );

			var pal = palette();
			drawBlobs( frame, pal );
			drawSpotlight( pal );
			drawMap( pal );
			drawRoutes( pal );
			drawNodes( frame, pal );
			drawParticles( frame, pal );

			raf = requestAnimationFrame( render );
		}

		raf = requestAnimationFrame( render );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
} )();
