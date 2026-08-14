<?php
/**
 * The footer for Gümrük Plus.
 *
 * Closes the #content / .col-full wrappers opened in header.php, then
 * renders the site footer. All layout is driven by CSS classes — no
 * inline styles — so responsive overrides work cleanly without !important.
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
		<div class="gp-footer__container">

			<!-- Top Trust Highlights Bar -->
			<div class="gp-footer__trust-bar">
				<div class="gp-footer__trust-item">
					<i class="ti ti-shield-check gp-footer__trust-icon gp-footer__trust-icon--teal"></i>
					<span class="gp-footer__trust-label"><?php esc_html_e( 'Özel Gümrük &amp; İthalat Fırsatları', 'gumruk-plus' ); ?></span>
				</div>
				<div class="gp-footer__trust-item">
					<i class="ti ti-truck gp-footer__trust-icon gp-footer__trust-icon--teal"></i>
					<span class="gp-footer__trust-label"><?php esc_html_e( 'Türkiye Geneli Hızlı Kargo', 'gumruk-plus' ); ?></span>
				</div>
				<div class="gp-footer__trust-item">
					<i class="ti ti-headset gp-footer__trust-icon gp-footer__trust-icon--teal"></i>
					<span class="gp-footer__trust-label"><?php esc_html_e( '7/24 Müşteri Destek Hattı', 'gumruk-plus' ); ?></span>
				</div>
			</div>

			<div class="gp-footer__columns">

				<!-- Column 1: Branding -->
				<div class="gp-footer__column gp-footer__column--brand">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="gp-footer__logo logo" rel="home">
						Gümrük<span class="logo-plus gp-footer__logo-plus">+</span>
					</a>
					<p class="gp-footer__tagline">
						<?php esc_html_e( 'Gerçek Mağaza, Gerçek Fiyat. Güvenilir duty free ve gümrük ürünlerini en uygun fiyatlarla, hızlı ve güvenli bir şekilde sunuyoruz.', 'gumruk-plus' ); ?>
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

				<!-- Column 3: Contact Us -->
				<div class="gp-footer__column">
					<h3 class="gp-footer__heading"><?php esc_html_e( 'İletişim', 'gumruk-plus' ); ?></h3>
					<ul class="gp-footer__info-list">
						<li class="gp-footer__info-item">
							<div class="gp-footer__info-icon gp-footer__info-icon--teal">
								<i class="ti ti-phone"></i>
							</div>
							<a href="tel:+901234567890" class="gp-footer__info-link">+90 123 456 7890</a>
						</li>
						<li class="gp-footer__info-item">
							<div class="gp-footer__info-icon gp-footer__info-icon--teal">
								<i class="ti ti-mail"></i>
							</div>
							<a href="mailto:info@gumrukplus.com" class="gp-footer__info-link">info@gumrukplus.com</a>
						</li>
					</ul>
				</div>

				<!-- Column 4: Store Info -->
				<div class="gp-footer__column">
					<h3 class="gp-footer__heading"><?php esc_html_e( 'Mağaza', 'gumruk-plus' ); ?></h3>
					<ul class="gp-footer__info-list">
						<li class="gp-footer__info-item gp-footer__info-item--top">
							<div class="gp-footer__info-icon gp-footer__info-icon--red">
								<i class="ti ti-map-pin"></i>
							</div>
							<span class="gp-footer__info-address"><?php echo esc_html( get_theme_mod( 'gp_store_location', __( '123 Gümrük Sokak, Kurtköy, Pendik — İstanbul', 'gumruk-plus' ) ) ); ?></span>
						</li>
						<li class="gp-footer__info-item">
							<div class="gp-footer__info-icon gp-footer__info-icon--red">
								<i class="ti ti-clock"></i>
							</div>
							<span class="gp-footer__info-hours"><?php echo esc_html( get_theme_mod( 'gp_store_hours', __( '09:00–21:00 her gün', 'gumruk-plus' ) ) ); ?></span>
						</li>
					</ul>
				</div>

			</div><!-- .gp-footer__columns -->

			<div class="gp-footer__bottom">
				<p class="gp-footer__copyright">
					&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'Tüm hakları saklıdır.', 'gumruk-plus' ); ?>
				</p>
				<div class="gp-footer__socials">
					<a href="#" class="gp-footer__social-link" aria-label="Instagram"><i class="ti ti-brand-instagram"></i></a>
					<a href="#" class="gp-footer__social-link" aria-label="Facebook"><i class="ti ti-brand-facebook"></i></a>
				</div>
			</div>

		</div><!-- .gp-footer__container -->
	</footer><!-- #colophon -->

	<?php do_action( 'storefront_after_footer' ); ?>

	<!-- Global Premium Canvas Background -->
	<canvas id="gp-global-canvas" aria-hidden="true"></canvas>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
