<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render the scrolling deals marquee seeded via the Customizer
 * (theme_mods_gumruk-plus -> gp_deals_marquee, comma-separated items).
 */
function gp_deals_marquee() {
	$raw = get_theme_mod( 'gp_deals_marquee', '' );
	if ( empty( $raw ) ) {
		return;
	}

	$items = array_filter( array_map( 'trim', explode( ',', $raw ) ) );
	if ( empty( $items ) ) {
		return;
	}

	echo '<div class="ticker-content">';
	// Duplicate the item list so the CSS scroll animation loops seamlessly.
	for ( $pass = 0; $pass < 2; $pass++ ) {
		foreach ( $items as $item ) {
			echo '<div class="ticker-item"><div class="ticker-dot"></div>' . esc_html( $item ) . '</div>';
		}
	}
	echo '</div>';
}

/**
 * Render a floating WhatsApp contact button using the number seeded via the
 * Customizer (theme_mods_gumruk-plus -> gp_whatsapp_number, digits only,
 * country code included, e.g. 905000000000).
 */
function gp_whatsapp_button() {
	$number = get_theme_mod( 'gp_whatsapp_number', '' );
	if ( empty( $number ) ) {
		return;
	}

	$digits = preg_replace( '/[^0-9]/', '', (string) $number );
	if ( empty( $digits ) ) {
		return;
	}

	printf(
		'<a class="gp-whatsapp-button" href="%1$s" target="_blank" rel="noopener noreferrer" aria-label="%2$s">%3$s</a>',
		esc_url( 'https://wa.me/' . $digits ),
		esc_attr__( 'WhatsApp üzerinden bize ulaşın', 'gumruk-plus' ),
		'<span class="gp-whatsapp-button__icon" aria-hidden="true">&#9743;</span>'
	);
}

/**
 * The branded topbar: store location, hours, and a TR/EN toggle.
 *
 * The language toggle is presentational only — there's no multilingual
 * plugin (WPML/Polylang) installed, so it doesn't switch content yet.
 */
function gp_topbar() {
	$location = get_theme_mod( 'gp_store_location', __( 'Kurtköy, Pendik — İstanbul', 'gumruk-plus' ) );
	$hours    = get_theme_mod( 'gp_store_hours', __( '09:00–21:00 her gün', 'gumruk-plus' ) );
	?>
		<div class="info-item">
			<i class="ti ti-clock"></i>
			<span><?php echo esc_html( $hours ); ?></span>
		</div>
		<div class="info-item">
			<i class="ti ti-map-pin"></i>
			<span><?php echo esc_html( $location ); ?></span>
		</div>
	<?php
}

function gp_split_letters( $text, $extra_class = '' ) {
	$chars = mb_str_split( $text );
	$output = '<span class="ag-line ' . esc_attr( $extra_class ) . '">';
	$index = 0;
	foreach ( $chars as $char ) {
		if ( $char === ' ' ) {
			$output .= '<span class="ag-space">&nbsp;</span>';
		} else {
			$color_class = 'ag-c-' . ( ( $index % 4 ) + 1 );
			$output .= '<span class="ag-letter ' . esc_attr( $color_class ) . '" data-index="' . $index . '">' . esc_html( $char ) . '</span>';
			$index++;
		}
	}
	$output .= '</span>';
	return $output;
}

function gp_hero_section() {
	$whatsapp_digits = preg_replace( '/[^0-9]/', '', (string) get_theme_mod( 'gp_whatsapp_number', '' ) );
	?>
	<section class="hero-section">
		<div class="container">
			<h1 class="anton"><span class="highlight-blue"><?php esc_html_e( 'Orijinal Gümrük ve Duty Free Ürünleri', 'gumruk-plus' ); ?></span></h1>
			<p><?php esc_html_e( 'Ayakkabı, çanta, takı, ev dekorasyonu ve daha fazlası — doğrudan Kurtköy\'den kapınıza. Orijinal, kaliteli, güvenilir.', 'gumruk-plus' ); ?></p>
			
			<div class="hero-actions" style="margin-top: 24px;">
				<a class="btn btn-primary" href="#gp-products">
					<?php esc_html_e( 'Hemen Alışverişe Başla', 'gumruk-plus' ); ?>
				</a>
				<?php if ( $whatsapp_digits ) : ?>
					<a class="btn btn-outline" href="<?php echo esc_url( 'https://wa.me/' . $whatsapp_digits ); ?>" target="_blank" rel="noopener noreferrer">
						<i class="ti ti-brand-whatsapp"></i> <?php esc_html_e( 'WhatsApp', 'gumruk-plus' ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</section><!-- .hero-section -->
	<?php
}

/**
 * Prints post meta (date, author) for single posts.
 */
function gp_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

	$time_string = sprintf(
		$time_string,
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() )
	);

	printf(
		'<span class="posted-on">%1$s</span><span class="byline"> %2$s <span class="author vcard"><a class="url fn n" href="%3$s">%4$s</a></span></span>',
		$time_string, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		esc_html__( 'yazan', 'gumruk-plus' ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_html( get_the_author() )
	);
}

/**
 * Prints category/tag list and edit link for single posts.
 */
function gp_entry_footer() {
	if ( 'post' !== get_post_type() ) {
		return;
	}

	$categories_list = get_the_category_list( esc_html__( ', ', 'gumruk-plus' ) );
	if ( $categories_list ) {
		printf( '<span class="cat-links">%1$s %2$s</span>', esc_html__( 'Kategori:', 'gumruk-plus' ), $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	$tags_list = get_the_tag_list( '', esc_html__( ', ', 'gumruk-plus' ) );
	if ( $tags_list && ! is_wp_error( $tags_list ) ) {
		printf( '<span class="tags-links">%1$s %2$s</span>', esc_html__( 'Etiketler:', 'gumruk-plus' ), $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	edit_post_link(
		sprintf(
			/* translators: %s: post title */
			esc_html__( 'Düzenle %s', 'gumruk-plus' ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		),
		'<span class="edit-link">',
		'</span>'
	);
}
