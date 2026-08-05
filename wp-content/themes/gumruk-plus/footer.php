<?php
/**
 * The footer for Gümrük Plus.
 *
 * Closes the #content / .col-full wrappers opened in header.php, then
 * renders the site footer.
 *
 * @package gumruk-plus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

		</div><!-- .col-full -->
	</div><!-- #content -->

	<?php do_action( 'storefront_before_footer' ); ?>

	<footer id="colophon" class="site-footer gp-footer" role="contentinfo">
		<div class="col-full">

			<!-- Clean Top Trust Highlights Bar (Vector SVGs with Solid Brand Colors) -->
			<div class="gp-footer__trust-bar">
				<div class="gp-footer__trust-item gp-trust-shield">
					<svg class="gp-footer__trust-svg" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
						<path d="m9 12 2 2 4-4"/>
					</svg>
					<span>Özel Gümrük & İthalat Fırsatları</span>
				</div>
				<div class="gp-footer__trust-divider">•</div>
				<div class="gp-footer__trust-item gp-trust-truck">
					<svg class="gp-footer__trust-svg" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
						<rect x="1" y="3" width="15" height="13"/>
						<polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
						<circle cx="5.5" cy="18.5" r="2.5"/>
						<circle cx="18.5" cy="18.5" r="2.5"/>
					</svg>
					<span>Türkiye Geneli Hızlı Kargo</span>
				</div>
				<div class="gp-footer__trust-divider">•</div>
				<div class="gp-footer__trust-item gp-trust-chat">
					<svg class="gp-footer__trust-svg" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
					</svg>
					<span>7/24 Müşteri Destek Hattı</span>
				</div>
			</div>

			<div class="gp-footer__columns">

				<!-- Column 1: Branding -->
				<div class="gp-footer__column gp-footer__column--brand">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="gp-logo gp-footer__logo" rel="home">
						<span class="gp-logo__word"><?php esc_html_e( 'GÜMRÜK', 'gumruk-plus' ); ?></span><span class="gp-logo__plus">+</span>
					</a>
					<p class="gp-footer__tagline">
						<?php esc_html_e( 'Gerçek Mağaza, Gerçek Fiyat. Güvenilir duty free ve gümrük ürünlerini uygun fiyatlarla online sunuyoruz.', 'gumruk-plus' ); ?>
					</p>
				</div>

				<!-- Column 2: Navigation Links -->
				<div class="gp-footer__column">
					<h3 class="gp-footer__heading"><?php esc_html_e( 'Hızlı Erişim', 'gumruk-plus' ); ?></h3>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'container'      => false,
							'menu_class'     => 'gp-footer__links',
							'fallback_cb'    => false,
							'depth'          => 1,
						)
					);
					?>
				</div>

				<!-- Column 3: Store Info -->
				<div class="gp-footer__column">
					<h3 class="gp-footer__heading"><?php esc_html_e( 'Mağaza Bilgileri', 'gumruk-plus' ); ?></h3>
					<ul class="gp-footer__info-list">
						<li>
							<svg class="gp-footer__info-svg" viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
								<circle cx="12" cy="10" r="3"/>
							</svg>
							<span><?php echo esc_html( get_theme_mod( 'gp_store_location', __( 'Kurtköy, Pendik — İstanbul', 'gumruk-plus' ) ) ); ?></span>
						</li>
						<li>
							<svg class="gp-footer__info-svg" viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
								<circle cx="12" cy="12" r="10"/>
								<polyline points="12 6 12 12 16 14"/>
							</svg>
							<span><?php echo esc_html( get_theme_mod( 'gp_store_hours', __( '09:00–21:00 her gün', 'gumruk-plus' ) ) ); ?></span>
						</li>
					</ul>
				</div>

			</div><!-- .gp-footer__columns -->

			<div class="gp-footer__bottom">
				<p class="gp-footer__copyright">
					&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'Tüm hakları saklıdır.', 'gumruk-plus' ); ?>
				</p>
			</div>

		</div><!-- .col-full -->
	</footer><!-- #colophon -->

	<?php do_action( 'storefront_after_footer' ); ?>

	<?php gp_whatsapp_button(); ?>

	<!-- Global Premium Canvas Background -->
	<canvas id="gp-global-canvas" aria-hidden="true"></canvas>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
