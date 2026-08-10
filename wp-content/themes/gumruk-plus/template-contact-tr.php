<?php
/**
 * Template Name: İletişim (TR)
 *
 * @package gumruk-plus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php
			while ( have_posts() ) :
				the_post();
				?>

				<article id="post-<?php the_ID(); ?>" <?php post_class( 'gp-entry gp-contact-page' ); ?>>

					<header class="entry-header">
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					</header>

					<?php if ( has_post_thumbnail() ) : ?>
						<div class="entry-thumbnail">
							<?php the_post_thumbnail( 'large' ); ?>
						</div>
					<?php endif; ?>

					<div class="entry-content">
						<?php the_content(); ?>

						<div class="gp-contact-section">
							<div class="gp-contact-info">
								<h3><?php esc_html_e( 'İletişime Geçin', 'gumruk-plus' ); ?></h3>
								<p><strong><?php esc_html_e( 'Adres:', 'gumruk-plus' ); ?></strong><br>
								<?php esc_html_e( '123 Gümrük Sokak', 'gumruk-plus' ); ?><br>
								<?php esc_html_e( 'İstanbul, Türkiye', 'gumruk-plus' ); ?></p>
								
								<p><strong><?php esc_html_e( 'Telefon:', 'gumruk-plus' ); ?></strong><br>
								<a href="tel:+901234567890">+90 123 456 7890</a></p>
								
								<p><strong><?php esc_html_e( 'E-posta:', 'gumruk-plus' ); ?></strong><br>
								<a href="mailto:info@gumrukplus.com">info@gumrukplus.com</a></p>
							</div>

							<div class="gp-contact-form-wrapper">
								<h3><?php esc_html_e( 'Bize Mesaj Gönderin', 'gumruk-plus' ); ?></h3>
								<form action="#" method="post" class="gp-contact-form">
									<div class="gp-form-row">
										<div class="gp-form-group">
											<label for="gp_name"><?php esc_html_e( 'İsim', 'gumruk-plus' ); ?> *</label>
											<input type="text" name="gp_name" id="gp_name" required>
										</div>
										<div class="gp-form-group">
											<label for="gp_email"><?php esc_html_e( 'E-posta', 'gumruk-plus' ); ?> *</label>
											<input type="email" name="gp_email" id="gp_email" required>
										</div>
									</div>
									<div class="gp-form-group">
										<label for="gp_subject"><?php esc_html_e( 'Konu', 'gumruk-plus' ); ?></label>
										<input type="text" name="gp_subject" id="gp_subject">
									</div>
									<div class="gp-form-group">
										<label for="gp_message"><?php esc_html_e( 'Mesaj', 'gumruk-plus' ); ?> *</label>
										<textarea name="gp_message" id="gp_message" rows="5" required></textarea>
									</div>
									<div class="gp-form-group gp-submit-group">
										<button type="submit" class="button alt"><?php esc_html_e( 'Mesaj Gönder', 'gumruk-plus' ); ?></button>
									</div>
								</form>
							</div>
						</div>
					</div>

				</article>

			<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
do_action( 'storefront_sidebar' );
get_footer();
