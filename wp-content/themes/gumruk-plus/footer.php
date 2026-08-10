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

	<footer id="colophon" class="site-footer gp-footer" role="contentinfo" style="padding: 60px 0;">
		<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 40px;">

			<!-- Top Trust Highlights Bar (Vector SVGs with Solid Brand Colors) -->
			<div class="gp-footer__trust-bar" style="display: flex; gap: 32px; justify-content: center; padding-bottom: 40px; margin-bottom: 40px;">
				<div class="gp-footer__trust-item" style="display: flex; align-items: center; gap: 12px;">
					<i class="ti ti-shield-check" style="font-size: 24px; color: var(--teal);"></i>
					<span style="font-size: 14px; font-weight: 500;">Özel Gümrük & İthalat Fırsatları</span>
				</div>
				<div class="gp-footer__trust-item" style="display: flex; align-items: center; gap: 12px;">
					<i class="ti ti-truck" style="font-size: 24px; color: var(--teal);"></i>
					<span style="font-size: 14px; font-weight: 500;">Türkiye Geneli Hızlı Kargo</span>
				</div>
				<div class="gp-footer__trust-item" style="display: flex; align-items: center; gap: 12px;">
					<i class="ti ti-headset" style="font-size: 24px; color: var(--teal);"></i>
					<span style="font-size: 14px; font-weight: 500;">7/24 Müşteri Destek Hattı</span>
				</div>
			</div>

			<div class="gp-footer__columns" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; margin-top: 20px; border-top: 1px solid var(--border-subtle); padding-top: 50px;">

				<!-- Column 1: Branding -->
				<div class="gp-footer__column gp-footer__column--brand" style="grid-column: span 2;">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo" rel="home" style="font-size: 32px; margin-bottom: 20px; display: inline-block; font-weight: 800; letter-spacing: -1px; text-decoration: none;">
						Gümrük<span class="logo-plus" style="color: var(--teal);">+</span>
					</a>
					<p class="gp-footer__tagline" style="font-size: 15px; line-height: 1.7; max-width: 350px; color: var(--ink-secondary);">
						<?php esc_html_e( 'Gerçek Mağaza, Gerçek Fiyat. Güvenilir duty free ve gümrük ürünlerini en uygun fiyatlarla, hızlı ve güvenli bir şekilde sunuyoruz.', 'gumruk-plus' ); ?>
					</p>
				</div>

				<!-- Column 2: Navigation Links -->
				<div class="gp-footer__column">
					<h3 class="gp-footer__heading" style="font-size: 14px; font-weight: 700; margin-bottom: 24px; color: var(--ink); text-transform: uppercase; letter-spacing: 1px;"><?php esc_html_e( 'Hızlı Erişim', 'gumruk-plus' ); ?></h3>
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
					<h3 class="gp-footer__heading" style="font-size: 14px; font-weight: 700; margin-bottom: 24px; color: var(--ink); text-transform: uppercase; letter-spacing: 1px;"><?php esc_html_e( 'İletişim', 'gumruk-plus' ); ?></h3>
					<ul class="gp-footer__info-list" style="list-style: none; padding: 0; margin: 0; font-size: 14px; color: var(--ink-secondary);">
						<li style="display: flex; align-items: center; gap: 14px; margin-bottom: 18px;">
							<div style="width: 36px; height: 36px; border-radius: 50%; background: var(--paper); display: flex; align-items: center; justify-content: center; border: 1px solid var(--border-subtle); transition: background 0.2s;">
								<i class="ti ti-phone" style="font-size: 18px; color: var(--teal);"></i>
							</div>
							<a href="tel:+901234567890" style="color: inherit; text-decoration: none; font-weight: 500; transition: color 0.2s;">+90 123 456 7890</a>
						</li>
						<li style="display: flex; align-items: center; gap: 14px; margin-bottom: 18px;">
							<div style="width: 36px; height: 36px; border-radius: 50%; background: var(--paper); display: flex; align-items: center; justify-content: center; border: 1px solid var(--border-subtle); transition: background 0.2s;">
								<i class="ti ti-mail" style="font-size: 18px; color: var(--teal);"></i>
							</div>
							<a href="mailto:info@gumrukplus.com" style="color: inherit; text-decoration: none; font-weight: 500; transition: color 0.2s;">info@gumrukplus.com</a>
						</li>
					</ul>
				</div>

				<!-- Column 4: Store Info -->
				<div class="gp-footer__column">
					<h3 class="gp-footer__heading" style="font-size: 14px; font-weight: 700; margin-bottom: 24px; color: var(--ink); text-transform: uppercase; letter-spacing: 1px;"><?php esc_html_e( 'Mağaza', 'gumruk-plus' ); ?></h3>
					<ul class="gp-footer__info-list" style="list-style: none; padding: 0; margin: 0; font-size: 14px; color: var(--ink-secondary);">
						<li style="display: flex; align-items: flex-start; gap: 14px; margin-bottom: 18px;">
							<div style="width: 36px; height: 36px; border-radius: 50%; background: var(--paper); display: flex; align-items: center; justify-content: center; border: 1px solid var(--border-subtle); flex-shrink: 0; transition: background 0.2s;">
								<i class="ti ti-map-pin" style="font-size: 18px; color: var(--red);"></i>
							</div>
							<span style="line-height: 1.5; padding-top: 8px;"><?php echo esc_html( get_theme_mod( 'gp_store_location', __( '123 Gümrük Sokak, Kurtköy, Pendik — İstanbul', 'gumruk-plus' ) ) ); ?></span>
						</li>
						<li style="display: flex; align-items: center; gap: 14px; margin-bottom: 18px;">
							<div style="width: 36px; height: 36px; border-radius: 50%; background: var(--paper); display: flex; align-items: center; justify-content: center; border: 1px solid var(--border-subtle); flex-shrink: 0; transition: background 0.2s;">
								<i class="ti ti-clock" style="font-size: 18px; color: var(--red);"></i>
							</div>
							<span style="font-weight: 500;"><?php echo esc_html( get_theme_mod( 'gp_store_hours', __( '09:00–21:00 her gün', 'gumruk-plus' ) ) ); ?></span>
						</li>
					</ul>
				</div>

			</div><!-- .gp-footer__columns -->

			<div class="gp-footer__bottom" style="margin-top: 60px; padding-top: 24px; display: flex; justify-content: space-between; align-items: center; font-size: 13px;">
				<p class="gp-footer__copyright" style="margin: 0;">
					&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'Tüm hakları saklıdır.', 'gumruk-plus' ); ?>
				</p>
				<div class="gp-footer__socials" style="display: flex; gap: 16px;">
					<a href="#" style="font-size: 20px;"><i class="ti ti-brand-instagram"></i></a>
					<a href="#" style="font-size: 20px;"><i class="ti ti-brand-facebook"></i></a>
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
