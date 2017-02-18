<?php
/**
 * Topbar social profiles
 *
 * @package		Total
 * @subpackage	Partials/Topbar
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2015, WPExplorer.com
 * @link		http://www.wpexplorer.com
 * @since		1.6.0
 * @version		1.0.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get social options array
$social_options = wpex_topbar_social_options();

// Return if $social_options array is empty
if ( empty( $social_options ) ) {
	return;
}

// Add classes based on topbar style
$classes 		= '';
$topbar_style	= wpex_get_mod( 'top_bar_style', 'one' );
if ( 'one' == $topbar_style ) {
	$classes = 'top-bar-right';
} elseif( 'two' == $topbar_style ) {
	$classes = 'top-bar-left';
} elseif( 'three' == $topbar_style ) {
	$classes = 'top-bar-centered';
}

// Display Social alternative
if ( $social_alt = wpex_get_mod( 'top_bar_social_alt' ) ) :
	// Translate alternative content
	$social_alt = wpex_translate_theme_mod( 'top_bar_social_alt', $social_alt ); ?>
	<div id="top-bar-social-alt" class="clr <?php echo $classes; ?>">
		<?php echo do_shortcode( $social_alt ); ?>
	</div><!-- #top-bar-social-alt -->

<?php return; endif; ?>

<?php
// Set defaults
$defaults = array(
	'twitter'		=> 'twitter.com',
	'facebook'		=> 'facebook.com',
	'pinterest'		=> 'pinterest.com',
	'linkedin'		=> 'linkedin.com',
	'instagram'		=> 'instagram.com',
	'googleplus'	=> 'googleplus.com',
	'rss'			=> 'feedburner.com',
);

// Return if there aren't any profiles defined
if( ! $profiles = wpex_get_mod( 'top_bar_social_profiles', $defaults ) ) {
	return;
}

// Get theme mods
$style				= wpex_get_mod( 'top_bar_social_style', 'font_icons' );
$colored_icons_url	= get_template_directory_uri() .'/images/social';
$link_target		= wpex_get_mod( 'top_bar_social_target', 'blank' );

// Define filter to alter the URL for the top bar social icon images
$colored_icons_url	= apply_filters( 'top_bar_social_img_url', $colored_icons_url );?>

<div id="top-bar-social" class="clr <?php echo $classes; ?> social-style-<?php echo $style; ?>">
	<?php
	// Loop through social options
	foreach ( $social_options as $key => $val ) : ?>
		<?php
		// Get URL from the theme mods
		$url = isset( $profiles[$key] ) ? $profiles[$key] : '';
		// Display if there is a value defined
		if ( $url ) {
			// Escape URL except for the following keys
			if ( ! in_array( $key, array( 'skype', 'email' ) ) ) {
				$url = esc_url( $url );
			} ?>
			<a href="<?php echo $url; ?>" title="<?php echo $val['label']; ?>" target="_<?php echo $link_target; ?>">
			<?php
			// Font Icon
			if ( $style == 'font_icons' ) { ?>
				<span class="<?php echo $val['icon_class']; ?>"></span>
			<?php }
			// Img Icons
			if ( $style == 'colored-icons' ) { ?>
				<img src="<?php echo $colored_icons_url; ?>/<?php echo $key; ?>.png" alt="<?php echo $val['label']; ?>" />
			<?php } ?>
			</a>
		<?php } ?>
	<?php endforeach; ?>
</div><!-- #top-bar-social -->