<?php
/**
 * Output for the Image Carousel Visual Composer module
 *
 * @package   Total WordPress Theme
 * @author    Alexander Clarke
 * @copyright Copyright (c) 2015, WPExplorer.com
 * @link      http://www.wpexplorer.com
 * @since     2.0.0
 * @version   2.1.4
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Not needed in admin ever
if ( is_admin() ) {
	return;
}

// Extract shortcode attributes
extract( shortcode_atts( array(
	'unique_id'                 => '',
	'classes'                   => '',
	'style'                     => '',
	'image_ids'                 => '',
	'post_gallery'              => '',
	'randomize_images'          => '',
	'center'                    => 'false',
	'timeout_duration'          => '5000',
	'items'                     => '4',
	'items_margin'              => '15',
	'infinite_loop'             => 'true',
	'items_scroll'              => '1',
	'auto_play'                 => 'false',
	'arrows'                    => 'true',
	'dots'                      => '',
	'thumbnail_link'            => '',
	'gallery_lightbox'          => '',
	'custom_links'              => '',
	'custom_links_target'       => '',
	'img_size'                  => '',
	'img_crop'                  => '',
	'img_width'                 => '',
	'img_height'                => '',
	'overlay_style'             => '',
	'title'                     => 'false',
	'title_type'                => 'title',
	'img_filter'                => '',
	'rounded_image'             => '',
	'img_hover_style'           => '',
	'caption'                   => 'false',
	'content_background'        => '',
	'content_heading_margin'    => '',
	'content_heading_weight'    => '',
	'content_heading_transform' => '',
	'content_margin'            => '',
	'content_font_size'         => '',
	'content_padding'           => '',
	'content_border'            => '',
	'content_color'             => '',
	'content_opacity'           => '',
	'content_heading_color'     => '',
	'content_heading_size'      => '',
	'content_alignment'         => '',
	'tablet_items'              => '3',
	'mobile_landscape_items'    => '2',
	'mobile_portrait_items'     => '1',
	'lightbox_skin'             => '',
), $atts ) );

// Set image ids
$image_ids = ( 'true' == $post_gallery ) ? wpex_get_gallery_ids() : $image_ids;

// If there aren't any images lets display a notice
if ( empty( $image_ids ) ) {
	echo '<div class="vc-module-notice">'. __( 'Please select some images.', 'wpex' ) .'</div>';
	return;
}

// Otherwise if there are images lets turn it into an array
else {

	// Get image ID's
	if ( ! is_array( $image_ids ) ) {
		$attachment_ids = explode( ',', $image_ids );
	} else {
		$attachment_ids = $image_ids;
	}

}

// Get & sanitize variables
$gallery_lightbox = false;

// Remove duplicate images
$attachment_ids = array_unique( $attachment_ids );

// Turn links into array
if ( $custom_links ) {
	$custom_links = explode( ',', $custom_links );
} else {
	$custom_links = array();
}

// Count items
$attachment_ids_count = count( $attachment_ids );
$custom_links_count   = count( $custom_links );

// Add empty values to custom_links array for images without links
if ( $attachment_ids_count > $custom_links_count ) {
	$count = 0;
	foreach( $attachment_ids as $val ) {
		$count++;
		if ( ! isset( $custom_links[$count] ) ) {
			$custom_links[$count] = '#';
		}
	}
}

// New custom links count
$custom_links_count = count( $custom_links );

// Remove extra custom links
if ( $custom_links_count > $attachment_ids_count ) {
	$count = 0;
	foreach( $custom_links as $key => $val ) {
		$count ++;
		if ( $count > $attachment_ids_count ) {
			unset( $custom_links[$key] );
		}
	}
}

// Set links as the keys for the images
$images_links_array = array_combine( $attachment_ids, $custom_links );

// Return if no images
if ( ! $images_links_array ) {
	return;
}

// Randomize images
if ( 'true' == $randomize_images ) {
	$orderby = 'rand';
} else {
	$orderby = 'post__in';
}

// Lets create a new Query so the image grid can be paginated
$my_query = new WP_Query(
	array(
		'post_type'      => 'attachment',
		//'post_mime_type'    => 'image/jpeg,image/gif,image/jpg,image/png',
		'post_status'    => 'any',
		'posts_per_page' => -1,
		'paged'          => NULL,
		'no_found_rows'  => true,
		'post__in'       => $attachment_ids,
		'orderby'        => $orderby,
	)
);


// Display carousel if there are images
if ( $my_query->have_posts() ) :

	// Sanitize params
	$overlay_style = empty( $overlay_style ) ? 'none' : $overlay_style;

	// Image Classes
	$img_classes = array( 'wpex-carousel-entry-media', 'clr' );
	if ( 'yes' == $rounded_image ) {
		$img_classes[] = ' vcex-rounded-images';
	}
	if ( $overlay_style ) {
		$img_classes[] = wpex_overlay_classes( $overlay_style );
	}
	if ( $img_filter ) {
		$img_classes[] = wpex_image_filter_class( $img_filter );
	}
	if ( $img_hover_style ) {
		$img_classes[] = wpex_image_hover_classes( $img_hover_style );
	}
	$img_classes = implode( ' ', $img_classes );

	// Lightbox links
	if ( 'lightbox' == $thumbnail_link ) {
		vcex_enque_style( 'ilightbox', $lightbox_skin );
		$lightbox_data = ' data-type="image"';
		if ( $lightbox_skin ) {
			$lightbox_data .= ' data-skin="'. $lightbox_skin .'"';
			vcex_enque_style( 'ilightbox', $lightbox_skin );
		}
	}

	// Output js for front-end editor
	$inline_js = array( 'carousel' );
	if ( 'lightbox' == $thumbnail_link ) {
		$inline_js[] = 'ilightbox';
	}
	vcex_inline_js( $inline_js );

	// Prevent auto play in visual composer
	if ( wpex_is_front_end_composer() ) {
		$auto_play = 'false';
	}

	// Item Margin
	if ( 'no-margins' == $style ) {
		$items_margin = '0';
	}

	// Items to scroll fallback for old setting
	if ( 'page' == $items_scroll ) {
		$items_scroll = $items;
	}
	
	// Unique ID
	if ( $unique_id ) {
		$unique_id = ' id="'. $unique_id .'"';
	}

	// Title design
	if ( 'yes' == $title ) {
		$heading_style = vcex_inline_style( array(
			'margin'         => $content_heading_margin,
			'text_transform' => $content_heading_transform,
			'font_weight'    => $content_heading_weight,
			'font_size'      => $content_heading_size,
			'color'          => $content_heading_color,
		) );
	}

	// Content Design
	if ( 'yes' == $title || 'yes' == $caption ) {
		$content_style = vcex_inline_style( array(
			'background' => $content_background,
			'padding'    => $content_padding,
			'margin'     => $content_margin,
			'border'     => $content_border,
			'font_size'  => $content_font_size,
			'color'      => $content_color,
			'opacity'    => $content_opacity,
			'text_align' => $content_alignment,
		) );
	}

	// Main Classes
	$wrap_classes = array( 'wpex-carousel', 'wpex-carousel-images', 'clr', 'owl-carousel' );
	if ( $style ) {
		$wrap_classes[] = $style;
	}
	if ( $classes ) {
		$wrap_classes[] = $this->getExtraClass( $classes );
	}
	$wrap_classes = implode( ' ', $wrap_classes ); ?>

	<div class="<?php echo esc_attr( $wrap_classes ); ?>"<?php echo $unique_id; ?> data-items="<?php echo $items; ?>" data-slideby="<?php echo $items_scroll; ?>" data-nav="<?php echo $arrows; ?>" data-dots="<?php echo $dots; ?>" data-autoplay="<?php echo $auto_play; ?>" data-loop="<?php echo $infinite_loop; ?>" data-autoplay-timeout="<?php echo $timeout_duration ?>" data-center="<?php echo $center; ?>" data-margin="<?php echo intval( $items_margin ); ?>" data-items-tablet="<?php echo $tablet_items; ?>" data-items-mobile-landscape="<?php echo $mobile_landscape_items; ?>" data-items-mobile-portrait="<?php echo $mobile_portrait_items; ?>">
		
		<?php
		// Loop through images
		while ( $my_query->have_posts() ) :

			// Get post from query
			$my_query->the_post();

			// Create new post object.
			$post = new stdClass();

			// Get attachment ID
			$post->id = get_the_ID();

			// Attachment VARS
			$post->data    = wpex_get_attachment_data( $post->id );
			$post->link    = $post->data['url'];
			$post->alt     = esc_attr( $post->data['alt'] );
			$post->caption = $post->data['caption'];

			// Pluck array to see if item has custom link
			$post->url = $images_links_array[$post->id];

			// Validate URl
			$post->url = ( '#' !== $post->url ) ? esc_url( $post->url ) : '';

			// Get correct title
			if ( 'title' == $title_type ) {
				$attachment_title = get_the_title();
			} elseif ( 'alt' == $title_type ) {
				$attachment_title = esc_attr( $post->data['alt'] );
			}
			
			// Image output
			$image_output = wpex_get_post_thumbnail( array(
				'attachment' => $post->id,
				'img_crop'   => $img_crop,
				'size'       => $img_size,
				'width'      => $img_width,
				'height'     => $img_height,
				'alt'        => $post->alt,
			) ); ?>

			<div class="wpex-carousel-slide">

				<div class="<?php echo $img_classes; ?>">

					<?php
					// Lightbox
					if ( 'lightbox' == $thumbnail_link ) { ?>

						<a href="<?php wpex_lightbox_image( $post->id ); ?>" title="<?php echo esc_attr( $post->alt ); ?>" class="wpex-carousel-entry-img wpex-lightbox"<?php echo $lightbox_data; ?>>
							<?php echo $image_output; ?>
							<?php
							// Inner link overlay html
							wpex_overlay( 'inside_link', $overlay_style ); ?>
						</a><!-- .wpex-carousel-entry-img -->

					<?php }

					// Custom Link
					elseif ( 'custom_link' == $thumbnail_link && $post->url ) { ?>

						<a href="<?php echo $post->url; ?>" title="<?php echo esc_attr( $post->alt ); ?>" class="wpex-carousel-entry-img"<?php vcex_html( 'target_attr', $custom_links_target ); ?>>
							<?php echo $image_output; ?>
							<?php
							// Inner link overlay html
							wpex_overlay( 'inside_link', $overlay_style ); ?>
						</a>

					<?php }

					// No link
					else {
						echo $image_output;
					} ?>

					<?php
					// Outside link overlay html
					if ( $overlay_style ) {
						if ( 'custom_link' == $thumbnail_link && $post->url ) {
							$args = array( 'custom_link' => $post->url );
						} else {
							$args = '';
						}
						wpex_overlay( 'outside_link', $overlay_style, $args );
					} ?>

				</div><!-- .wpex-carousel-entry-media -->

				<?php
				// Display details
				if ( ( 'yes' == $title && $attachment_title ) || (  'yes' == $caption && $post->caption ) ) : ?>

					<div class="wpex-carousel-entry-details clr"<?php echo $content_style; ?>>

						<?php
						// Display title
						if ( 'yes' == $title && $attachment_title ) : ?>

							<div class="wpex-carousel-entry-title entry-title"<?php echo $heading_style; ?>>
								<?php echo $attachment_title; ?>
							</div><!-- .wpex-carousel-entry-title -->

						<?php endif; ?>

						<?php
						// Display caption
						if ( 'yes' == $caption && $post->caption ) : ?>

							<div class="wpex-carousel-entry-excerpt clr">
								<?php echo $post->caption; ?>
							</div><!-- .wpex-carousel-entry-excerpt -->

						<?php endif; ?>
					
					</div><!-- .wpex-carousel-entry-details -->

				<?php endif; ?>

			</div><!-- .wpex-carousel-slide -->

		<?php endwhile; ?>

	</div><!-- .wpex-carousel -->

<?php
// Remove post object from memory
$post = null;

// Reset the post data to prevent conflicts with WP globals
wp_reset_postdata(); ?>

<?php
// End Query
endif; ?>