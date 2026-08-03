<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package gumruk-plus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<div class="error-404 not-found">

				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'Oops! That page can\'t be found.', 'gumruk-plus' ); ?></h1>
				</header>

				<div class="page-content">
					<p><?php esc_html_e( 'Nothing was found at this location. Try searching, or head back to the shop.', 'gumruk-plus' ); ?></p>

					<?php get_search_form(); ?>

					<p>
						<a class="gp-hero__cta" href="<?php echo esc_url( home_url( '/' ) ); ?>">
							<?php esc_html_e( 'Back to Shop', 'gumruk-plus' ); ?>
						</a>
					</p>
				</div>

			</div><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
