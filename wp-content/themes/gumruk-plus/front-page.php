<?php
/**
 * The front page template.
 *
 * NOT USED for this site's current configuration: the static front page
 * (Settings -> Reading) is set to the WooCommerce Shop page, and
 * WC_Template_Loader::template_loader() overrides WordPress's
 * template_include result whenever is_page( wc_get_page_id( 'shop' ) ) is
 * true — regardless of is_front_page() — replacing it with whatever it
 * resolves via woocommerce.php / archive-product.php. See woocommerce.php,
 * which is what actually renders the homepage right now.
 *
 * This file stays in place for the general case: if the front page is ever
 * pointed at a different (non-Shop) static page, WordPress's normal
 * template hierarchy picks this up directly.
 *
 * @package gumruk-plus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header(); ?>

	<div id="primary" class="content-area gp-front-page">
		<main id="main" class="site-main" role="main">

			<?php gp_hero_section(); ?>

			<?php
			while ( have_posts() ) :
				the_post();
				if ( trim( get_the_content() ) ) {
					?>
					<div class="gp-front-page__intro entry-content">
						<?php the_content(); ?>
					</div>
					<?php
				}
			endwhile;
			?>

			<?php if ( class_exists( 'WooCommerce' ) ) : ?>

				<?php
				$gp_product_cats = get_terms(
					array(
						'taxonomy'   => 'product_cat',
						'hide_empty' => true,
						'number'     => 1,
					)
				);
				if ( ! is_wp_error( $gp_product_cats ) && ! empty( $gp_product_cats ) ) :
					?>
				<section class="gp-section gp-section--categories">
					<h2 class="gp-section__title"><?php esc_html_e( 'Categories', 'gumruk-plus' ); ?></h2>
					<?php echo do_shortcode( '[product_categories number="0" parent="0" columns="4"]' ); ?>
				</section>
				<?php endif; ?>

				<section id="gp-products" class="gp-section gp-section--products">
					<h2 class="gp-section__title"><?php esc_html_e( 'Products', 'gumruk-plus' ); ?></h2>
					<?php echo do_shortcode( '[products limit="12" columns="4" orderby="date" order="DESC"]' ); ?>
				</section>

			<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
