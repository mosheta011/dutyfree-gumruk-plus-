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

			<div class="gp-footer__columns">

				<div class="gp-footer__column">
					<h2 class="gp-footer__heading"><?php bloginfo( 'name' ); ?></h2>
					<p class="gp-footer__tagline"><?php bloginfo( 'description' ); ?></p>
				</div>

				<div class="gp-footer__column">
					<h2 class="gp-footer__heading"><?php esc_html_e( 'Quick Links', 'gumruk-plus' ); ?></h2>
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

				<?php
				foreach ( array( 'footer-1', 'footer-2', 'footer-3' ) as $gp_footer_area ) :
					if ( is_active_sidebar( $gp_footer_area ) ) :
						?>
						<div class="gp-footer__column">
							<?php dynamic_sidebar( $gp_footer_area ); ?>
						</div>
						<?php
					endif;
				endforeach;
				?>

			</div><!-- .gp-footer__columns -->

			<div class="gp-footer__bottom">
				<p class="gp-footer__copyright">
					&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>.
					<?php esc_html_e( 'All rights reserved.', 'gumruk-plus' ); ?>
				</p>
			</div>

		</div><!-- .col-full -->
	</footer><!-- #colophon -->

	<?php do_action( 'storefront_after_footer' ); ?>

	<?php gp_whatsapp_button(); ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
