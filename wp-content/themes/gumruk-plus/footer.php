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

	<footer id="colophon" class="site-footer" role="contentinfo" style="background-color: var(--cream); border-top: 1px solid var(--border); padding: 60px 0;">
		<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 40px;">

			<!-- Top Trust Highlights Bar (Vector SVGs with Solid Brand Colors) -->
			<div class="gp-footer__trust-bar" style="display: flex; gap: 32px; justify-content: center; padding-bottom: 40px; border-bottom: 1px solid var(--border); margin-bottom: 40px;">
				<div class="gp-footer__trust-item" style="display: flex; align-items: center; gap: 12px; color: var(--ink);">
					<i class="ti ti-shield-check" style="font-size: 24px; color: var(--teal);"></i>
					<span style="font-size: 14px; font-weight: 500;">Özel Gümrük & İthalat Fırsatları</span>
				</div>
				<div class="gp-footer__trust-item" style="display: flex; align-items: center; gap: 12px; color: var(--ink);">
					<i class="ti ti-truck" style="font-size: 24px; color: var(--teal);"></i>
					<span style="font-size: 14px; font-weight: 500;">Türkiye Geneli Hızlı Kargo</span>
				</div>
				<div class="gp-footer__trust-item" style="display: flex; align-items: center; gap: 12px; color: var(--ink);">
					<i class="ti ti-headset" style="font-size: 24px; color: var(--teal);"></i>
					<span style="font-size: 14px; font-weight: 500;">7/24 Müşteri Destek Hattı</span>
				</div>
			</div>

			<div class="gp-footer__columns" style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 40px;">

				<!-- Column 1: Branding -->
				<div class="gp-footer__column gp-footer__column--brand">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo" rel="home" style="font-size: 28px; margin-bottom: 16px; display: inline-block;">
						Gümrük<span class="logo-plus">+</span>
					</a>
					<p class="gp-footer__tagline" style="color: var(--ink-secondary); font-size: 14px; line-height: 1.6; max-width: 400px;">
						<?php esc_html_e( 'Gerçek Mağaza, Gerçek Fiyat. Güvenilir duty free ve gümrük ürünlerini uygun fiyatlarla online sunuyoruz.', 'gumruk-plus' ); ?>
					</p>
				</div>

				<!-- Column 2: Navigation Links -->
				<div class="gp-footer__column">
					<h3 class="gp-footer__heading" style="font-family: 'Anton', sans-serif; font-size: 18px; margin-bottom: 20px;"><?php esc_html_e( 'Hızlı Erişim', 'gumruk-plus' ); ?></h3>
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
					<h3 class="gp-footer__heading" style="font-family: 'Anton', sans-serif; font-size: 18px; margin-bottom: 20px;"><?php esc_html_e( 'Mağaza Bilgileri', 'gumruk-plus' ); ?></h3>
					<ul class="gp-footer__info-list" style="list-style: none; padding: 0; margin: 0; color: var(--ink-secondary); font-size: 14px;">
						<li style="display: flex; align-items: flex-start; gap: 12px; margin-bottom: 12px;">
							<i class="ti ti-map-pin" style="font-size: 18px; color: var(--red); flex-shrink: 0;"></i>
							<span><?php echo esc_html( get_theme_mod( 'gp_store_location', __( 'Kurtköy, Pendik — İstanbul', 'gumruk-plus' ) ) ); ?></span>
						</li>
						<li style="display: flex; align-items: flex-start; gap: 12px; margin-bottom: 12px;">
							<i class="ti ti-clock" style="font-size: 18px; color: var(--red); flex-shrink: 0;"></i>
							<span><?php echo esc_html( get_theme_mod( 'gp_store_hours', __( '09:00–21:00 her gün', 'gumruk-plus' ) ) ); ?></span>
						</li>
					</ul>
				</div>

			</div><!-- .gp-footer__columns -->

			<div class="gp-footer__bottom" style="margin-top: 60px; padding-top: 24px; border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; color: var(--ink-secondary); font-size: 13px;">
				<p class="gp-footer__copyright" style="margin: 0;">
					&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'Tüm hakları saklıdır.', 'gumruk-plus' ); ?>
				</p>
				<div class="gp-footer__socials" style="display: flex; gap: 16px;">
					<a href="#" style="color: var(--ink-secondary); font-size: 20px;"><i class="ti ti-brand-instagram"></i></a>
					<a href="#" style="color: var(--ink-secondary); font-size: 20px;"><i class="ti ti-brand-facebook"></i></a>
				</div>
			</div>

		</div><!-- .container -->
	</footer><!-- #colophon -->

	<?php do_action( 'storefront_after_footer' ); ?>

	<!-- Global Premium Canvas Background -->
	<canvas id="gp-global-canvas" aria-hidden="true"></canvas>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
