<?php
/**
 * Blog entry meta
 *
 * @package     Total
 * @subpackage  Partials/Blog
 * @author      Alexander Clarke
 * @copyright   Copyright (c) 2015, WPExplorer.com
 * @link        http://www.wpexplorer.com
 * @since      	1.6.0
 * @version     2.1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Return if disabled
if ( ! wpex_get_mod( 'blog_entry_meta', true ) ) {
	return;
}

// Get meta sections
$sections = wpex_blog_entry_meta_sections();

// Return if sections are empty
if ( empty( $sections ) ) {
	return;
}

// Add class for meta with title
$classes = 'meta clr';
if ( 'custom_text' == wpex_get_mod( 'blog_single_header', 'custom_text' ) ) {
	$classes .= ' meta-with-title';
} ?>

	<?php
	// Loop through meta sections
	foreach ( $sections as $section ) : ?>

		<?php if ( 'date' == $section ) : ?>
			<div class="meta-date"><span class="updated"><?php echo get_the_date(); ?></span></div>
		<?php endif; ?>

		<?php if ( 'author' == $section ) : ?>
			<!-- <li class="meta-author"><span class="fa fa-user"></span><span class="vcard author"><?php the_author_posts_link(); ?></span></li> -->
		<?php endif; ?>

	<?php endforeach; ?>

	<div class="meta-categories-comments">

	<?php
	// Loop through meta sections
	foreach ( $sections as $section ) : ?>

		<?php if ( 'categories' == $section ) : ?>
		<div class="meta-category"><span class="fa fa-folder-o"></span><?php the_category( ', ', get_the_ID() ); ?></div>
		<?php endif; ?>

		<?php if ( 'comments' == $section && comments_open() && ! post_password_required() ): ?>
		<div class="meta-comments comment-scroll"><span class="fa fa-comment-o"></span><?php comments_popup_link( __( '0 Comments', 'wpex' ), __( '1 Comment',  'wpex' ), __( '% Comments', 'wpex' ), 'comments-link' ); ?></div>
		<?php endif; ?>

	<?php endforeach; ?>

	</div>