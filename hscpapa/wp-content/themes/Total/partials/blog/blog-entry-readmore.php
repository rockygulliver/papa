<?php
/**
 * Blog entry layout
 *
 * @package		Total
 * @subpackage	Partials/Blog
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2015, WPExplorer.com
 * @link		http://www.wpexplorer.com
 * @since		1.6.0
 * @version		2.1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Return if readmore is disabled
if ( ! wpex_has_readmore() ) {
	return;
}

// Vars
$post_id	= get_the_ID();
$format		= get_post_format( $post_id );
$text		= wpex_get_mod( 'blog_entry_readmore_text' );
$text		= $text ? $text : __( 'Read More', 'wpex' );

// Translate readmore text with WPML
$text = wpex_translate_theme_mod( 'blog_entry_readmore_text', $text );

// Apply filters for child theming
$text = apply_filters( 'wpex_post_readmore_link_text', $text ); ?>

<div class="blog-entry-readmore clr">
	<a href="<?php the_permalink(); ?>" class="theme-button" title="<?php echo $text ?>">
		<?php echo $text ?><span class="readmore-rarr hidden">&rarr;</span>
	</a>
</div>