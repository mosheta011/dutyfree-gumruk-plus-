<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Custom primary-nav walker producing BEM-style classes (gp-nav__*) and an
 * accessible dropdown toggle button for items with children.
 */
class GP_Nav_Walker extends Walker_Nav_Menu {

	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "\n{$indent}<ul class=\"gp-nav__submenu\">\n";
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "{$indent}</ul>\n";
	}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$indent      = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$has_children = in_array( 'menu-item-has-children', $item->classes, true );

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'gp-nav__item';
		if ( $has_children ) {
			$classes[] = 'gp-nav__item--has-children';
		}
		if ( in_array( 'current-menu-item', $classes, true ) ) {
			$classes[] = 'gp-nav__item--current';
		}

		$class_names = implode( ' ', array_filter( array_map( 'sanitize_html_class', $classes ) ) );

		$output .= "{$indent}<li class=\"{$class_names}\">";

		$attributes  = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
		$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
		$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
		$attributes .= ! empty( $item->url ) ? ' href="' . esc_url( $item->url ) . '"' : '';

		$item_output  = isset( $args->before ) ? $args->before : '';
		$item_output .= '<a class="gp-nav__link"' . $attributes . '>';
		$item_output .= ( isset( $args->link_before ) ? $args->link_before : '' ) . apply_filters( 'the_title', $item->title, $item->ID ) . ( isset( $args->link_after ) ? $args->link_after : '' );
		$item_output .= '</a>';

		if ( $has_children ) {
			$item_output .= '<button class="gp-nav__toggle" type="button" aria-expanded="false" aria-label="' . esc_attr__( 'Open submenu', 'gumruk-plus' ) . '">';
			$item_output .= '<span class="gp-nav__toggle-icon" aria-hidden="true"></span>';
			$item_output .= '</button>';
		}

		$item_output .= isset( $args->after ) ? $args->after : '';

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= "</li>\n";
	}
}
