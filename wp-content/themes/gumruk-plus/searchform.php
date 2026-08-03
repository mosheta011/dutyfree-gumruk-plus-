<?php
/**
 * The template for displaying the search form.
 *
 * @package gumruk-plus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$gp_unique_id = wp_unique_id( 'search-form-' );
?>

<form role="search" method="get" class="gp-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo esc_attr( $gp_unique_id ); ?>" class="screen-reader-text">
		<?php echo esc_html_x( 'Search for:', 'label', 'gumruk-plus' ); ?>
	</label>
	<input
		type="search"
		id="<?php echo esc_attr( $gp_unique_id ); ?>"
		class="gp-search-form__input"
		placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'gumruk-plus' ); ?>"
		value="<?php echo get_search_query(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"
		name="s"
	/>
	<button type="submit" class="gp-search-form__submit">
		<?php echo esc_html_x( 'Search', 'submit button', 'gumruk-plus' ); ?>
	</button>
</form>
