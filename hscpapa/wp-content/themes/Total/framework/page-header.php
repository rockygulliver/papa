<?php
/**
 * All page header functions
 *
 * @package    Total
 * @subpackage Functions
 * @author     Alexander Clarke
 * @copyright  Copyright (c) 2015, WPExplorer.com
 * @link       http://www.wpexplorer.com
 * @since      1.0.0
 * @version    2.1.3
 */

/**
 * Checks if the page header (title) should display
 *
 * @since 1.5.2
 */
function wpex_has_page_header() {

	// Return true by default
	$return = true;

	// Get global object
	$obj = wpex_global_obj();

	// Return if page header is disabled via custom field
	if ( $obj->post_id ) {

		// Return if page header is disabled and there isn't a page header background defined
		if ( 'on' == get_post_meta( $obj->post_id, 'wpex_disable_title', true )
			&& 'background-image' != $obj->page_header_style ) {
			$return	= false;
		}

	}

	// Display if page header style is set to Hidden
	if ( 'hidden' == $obj->page_header_style ) {
		$return = false;
	}

	// Apply filters
	$return	= apply_filters( 'wpex_display_page_header', $return );

	// Return bool
	return $return;
}

/**
 * Get current page header style
 * Needs to be added first because it's used in multiple functions
 *
 * @since 1.5.4
 */
function wpex_page_header_style( $post_id = '' ) {

	// Get default page header style defined in Customizer
	$style = wpex_get_mod( 'page_header_style' );

	// Get for header style defined in page settings
	if ( $meta = get_post_meta( $post_id, 'wpex_post_title_style', true ) ) {
		$style = $meta;
	}
	
	// Apply filters for child theming
	$style = apply_filters( 'wpex_page_header_style', $style );

	// Sanitize data
	$style = ( 'default' == $style ) ? '' : $style;

	// Return page header style
	return $style;

}

/**
 * Adds correct classes to the page header
 *
 * @since 2.0.0
 */
function wpex_page_header_classes() {

	// Get global object
	$obj = wpex_global_obj();

	// Define main class
	$classes = array( 'page-header' );

	// Add classes for title style
	if ( $obj->page_header_style ) {
		$classes[] = $obj->page_header_style .'-page-header';
	}

	// Apply filters
	apply_filters( 'wpex_page_header_classes', $classes );

	// Turn into comma seperated list
	$classes = implode( ' ', $classes );

	// Return classes
	return $classes;
	

}

/**
 * Checks if the page has a page header
 *
 * @since 1.0.0
 */
function wpex_has_page_header_title() {

	// Get global object
	$obj = wpex_global_obj();

	// True by default
	$return = true;

	// Disable title if the page header is disabled via meta
	if ( 'on' == get_post_meta( $obj->post_id, 'wpex_disable_title', true ) ) {
		$return = false;
	}

	// Apply filters
	$return = apply_filters( 'wpex_has_page_header_title', $return );

	// Return
	return $return;

}

/**
 * Check if current post has subheading
 *
 * @since 1.0.0
 */
function wpex_has_page_header_subheading( $post_id ) {
	if ( wpex_get_page_subheading( $post_id ) ) {
		return true;
	}
}

/**
 * Get the post subheading
 *
 * @since 1.0.0
 */
function wpex_get_page_subheading( $post_id = '' ) {

	// Subheading is NULL by default
	$subheading = NULL;

	// Posts & Pages
	if ( $post_id ) {

		// Get subheading
		if ( get_post_meta( $post_id, 'wpex_post_subheading', true ) ) {
			$subheading = get_post_meta( $post_id, 'wpex_post_subheading', true );
		}

	}

	// Search
	elseif ( is_search() ) {

		$subheading = __( 'You searched for:', 'wpex' ) .' &quot;'. esc_html( get_search_query( false ) ) .'&quot;';

	}

	// Categories
	elseif ( is_category() ) {
		if ( wpex_get_mod( 'category_descriptions', true ) && 'under_title' == wpex_get_mod( 'category_description_position', 'under_title' ) ) {
			$subheading = term_description();
		}
	}

	// Author
	elseif ( is_author() ) {
		$subheading = __( 'This author has written', 'wpex' ) .' '. get_the_author_posts() .' '. __( 'articles', 'wpex' );
	}

	// All other Taxonomies
	elseif ( is_tax() && ! wpex_has_term_description_above_loop() ) {
		$subheading = term_description();
	}

	// Apply filters
	$subheading = apply_filters( 'wpex_post_subheading', $subheading );

	// Return subheading
	return $subheading;

}

/**
 * Get page header background image URL
 *
 * @since Total 1.5.4
 */
function wpex_page_header_background_image( $post_id = '' ) {

	// Return NULL by default
	$image = null;

	// Get post background
	if ( $post_id ) {

		// Get background image
		$new_meta = get_post_meta( $post_id, 'wpex_post_title_background_redux', true );

		// Sanitize data
		if ( $new_meta ) {
			if ( is_array( $new_meta ) && ! empty( $new_meta['url'] ) ) {
				$image = $new_meta['url'];
			} else {
				$image = $new_meta;
			}
		} else {
			$image = get_post_meta( $post_id, 'wpex_post_title_background', true );
		}

	}

	// Apply filters
	$image = apply_filters( 'wpex_page_header_background_image', $image );

	// Generate image URL if using ID
	if ( is_numeric( $image ) ) {
		$image = wp_get_attachment_image_src( $image, 'full' );
		$image = $image[0];
	}

	// Return URL
	return $image;
}

/**
 * Outputs Custom CSS for the page title
 *
 * @since 1.5.3
 */
function wpex_page_header_overlay() {

	// Define return
	$return = '';

	// Get global object
	$obj = wpex_global_obj();

	// Only needed for the background-image style so return otherwise
	if ( 'background-image' != $obj->page_header_style ) {
		return;
	}

	// Set default overlay for tax archives
	if ( is_tax() || is_tag() || is_category() ) {
		$overlay       = 1;
		$opacity       = '';
		$overlay_style = 'solid';
	}

	// Get options from post meta
	else {
		$overlay       = get_post_meta( $obj->post_id, 'wpex_post_title_background_overlay', true );
		$opacity       = get_post_meta( $obj->post_id, 'wpex_post_title_background_overlay_opacity', true );
		$overlay_style = get_post_meta( $obj->post_id, 'wpex_post_title_background_overlay', true );
	}

	// Apply filters
	$overlay       = apply_filters( 'wpex_page_header_overlay_enabled', $overlay );
	$overlay_style = apply_filters( 'wpex_page_header_overlay_style', $overlay_style );
	$opacity       = apply_filters( 'wpex_page_header_overlay_opacity', $opacity );

	// Check that overlay style isn't set to none
	if ( $overlay && 'none' != $overlay && $overlay_style ) {

		// Add opacity style if opacity is defined
		if ( $opacity ) {
			$opacity = 'style="opacity:'. $opacity .'"';
		}

		// Return overlay element
		$return = '<span class="background-image-page-header-overlay style-'. $overlay_style .'" '. $opacity .'></span>';
		
	}

	// Apply filters for child theming
	$return = apply_filters( 'wpex_page_header_overlay', $return );

	// Return
	echo $return;
}

/**
 * Outputs Custom CSS for the page title
 *
 * @since 1.5.3
 */
function wpex_page_header_css( $output ) {

	// Get global object
	$obj = wpex_global_obj();

	// Return output if page header is disabled
	if ( ! $obj->has_page_header ) {
		return $output;
	}

	// Return if there isn't a page header style defined
	if ( ! $obj->page_header_style ) {
		return $output;
	}

	// Define var
	$css = '';

	// Check if a header style is defined and make header style dependent tweaks
	if ( $obj->page_header_style ) {

		// Customize background color
		if ( $obj->page_header_style == 'solid-color' || $obj->page_header_style == 'background-image' ) {
			$bg_color = get_post_meta( $obj->post_id, 'wpex_post_title_background_color', true );
			if ( $bg_color ) {
				$css .='background-color: '. $bg_color .' !important;';
			}
		}

		// Background image Style
		if ( $obj->page_header_style == 'background-image' ) {

			// Add background image
			$bg_img = wpex_page_header_background_image( $obj->post_id );

			if ( $bg_img ) {

				// Add css for background image
				$css .= 'background-image: url('. $bg_img .' ) !important;
						background-position: 50% 0;
						-webkit-background-size: cover;
						-moz-background-size: cover;
						-o-background-size: cover;
						background-size: cover;';

				// Custom height
				$title_height	= get_post_meta( $obj->post_id, 'wpex_post_title_height', true );
				$title_height	= $title_height ? $title_height : '400';
				if ( $title_height ) {
					$css .= 'height:'. wpex_sanitize_data( $title_height, 'px' ) .' !important;';
				}
			}

		}

	}

	// Apply all css to the page-header class
	if ( ! empty( $css ) ) {
		$css = '.page-header { '. $css .' }';
	}

	// Overlay Color
	if ( ! empty( $bg_img ) ) {
		$overlay_color = get_post_meta( $obj->post_id, 'wpex_post_title_background_overlay', true );
		if ( 'bg_color' == $overlay_color && $obj->page_header_style == 'background-image' && isset( $bg_color ) ) {
			$css .= '.background-image-page-header-overlay { background-color: '. $bg_color .' !important; }';
		}
	}

	// If css var isn't empty add to custom css output
	if ( ! empty( $css ) ) {
		$output .= wpex_minify_css( $css );
	}

	// Return output
	return $output;

}
add_filter( 'wpex_head_css', 'wpex_page_header_css' );