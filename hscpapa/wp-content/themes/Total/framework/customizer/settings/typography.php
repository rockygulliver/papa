<?php
/**
 * Adds all Typography options to the Customizer and outputs the custom CSS for them
 * 
 * @package    Total
 * @subpackage Customizer
 * @author     Alexander Clarke
 * @copyright  Copyright (c) 2015, WPExplorer.com
 * @link       http://www.wpexplorer.com
 * @since      1.6.0
 * @version    2.1.3
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start class
if ( ! class_exists( 'WPEX_Theme_Customizer_Typography' ) ) {

	class WPEX_Theme_Customizer_Typography {

		/**
		 * Main constructor
		 *
		 * @access public
		 * @since  1.6.0
		 */
		public function __construct() {

			// Actions
			add_action( 'customize_register', array( $this , 'register' ) );
			add_action( 'customize_save_after', array( $this, 'reset_cache' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'load_fonts' ) );
			add_action( 'after_setup_theme', array( $this, 'mce_scripts' ) );

			// Filters
			add_filter( 'tiny_mce_before_init', array( $this, 'mce_fonts' ) );
			add_filter( 'wpex_head_css', array( $this, 'head_css' ), 99 );
		}

		/**
		 * Array of Typography settings to add to the customizer
		 *
		 * @access public
		 * @since  1.6.0
		 */
		public function elements() {
			$array = array(
				'body'  => array(
					'label'     =>  __( 'Body', 'wpex' ),
					'target'    =>  'body',
					'defaults'  => array(
						'font-family'   => 'Open Sans',
					),
				),
				'logo'  => array(
					'label'     => __( 'Logo', 'wpex' ),
					'target'    => '#site-logo a',
					'exclude'   => array( 'font-color' ),
				),
				'top_menu'  => array(
					'label'     => __( 'Top Bar', 'wpex' ),
					'target'    => '#top-bar-content',
					'exclude'   => array( 'font-color' ),
				),
				'menu'  => array(
					'label'     => __( 'Main Menu', 'wpex' ),
					'target'    => '#site-navigation .dropdown-menu a',
					'exclude'   => array( 'font-color' ),
				),
				'menu_dropdown' => array(
					'label'     => __( 'Main Menu: Dropdowns', 'wpex' ),
					'target'    => '#site-navigation .dropdown-menu ul a',
					'exclude'   => array( 'font-color' ),
				),
				'mobile_menu'  => array(
					'label'     => __( 'Mobile Menu', 'wpex' ),
					'target'    => '#sidr-main',
					'exclude'   => array( 'font-color' ),
				),
				'page_title'    => array(
					'label'     => __( 'Page Title', 'wpex' ),
					'target'    => '.page-header-title',
					'exclude'   => array( 'font-color' ),
				),
				'blog_entry_title'  => array(
					'label'         => __( 'Blog Entry Title', 'wpex' ),
					'target'        => '.blog-entry-title a',
				),
				'blog_post_title'   => array(
					'label'         => __( 'Blog Post Title', 'wpex' ),
					'target'        => '.single-post-title',
				),
				'breadcrumbs'   => array(
					'label'     => __( 'Breadcrumbs', 'wpex' ),
					'target'    => '.site-breadcrumbs',
					'exclude'   => array( 'font-color' ),
				),
				'headings'  => array(
					'label'     => __( 'Headings', 'wpex' ),
					'target'    => 'h1,h2,h3,h4,h5,h6,.theme-heading,.heading-typography,.widget-title,.wpex-widget-recent-posts-title,.comment-reply-title',
					'exclude'   => array( 'font-color', 'line-height', 'font-size' ),
				),
				'sidebar_widget_title'  => array(
					'label'     => __( 'Sidebar Widget Heading', 'wpex' ),
					'target'    => '.sidebar-box .widget-title',
				),
				'entry_h2'      => array(
					'label'     => __( 'Post H2', 'wpex' ),
					'target'    => '.entry h2'
				),
				'entry_h3'      => array(
					'label'     => __( 'Post H3', 'wpex' ),
					'target'    => '.entry h3'
				),
				'footer_widget_title'   => array(
					'label'     => __( 'Footer Widget Heading', 'wpex' ),
					'target'    => '.footer-widget .widget-title',
				),
				'callout'   => array(
					'label'     => __( 'Footer Callout', 'wpex' ),
					'target'    => '.footer-callout-content',
					'exclude'   => array( 'font-color' ),
				),
				'copyright' => array(
					'label'     => __( 'Footer Copyright', 'wpex' ),
					'target'    => '#copyright',
					'exclude'   => array( 'font-color' ),
				),
				'footer_menu'   => array(
					'label'     => __( 'Footer Menu', 'wpex' ),
					'target'    => '#footer-bottom-menu',
					'exclude'   => array( 'font-color' ),
				),
				'load_custom_font_1'    => array(
					'label'             => __( 'Load Custom Font', 'wpex' ),
					'settings'          => array( 'font-family' ),
				),
			);
			return apply_filters( 'wpex_typography_settings', $array );
		}

		/**
		 * Register typography options to the Customizer
		 *
		 * @access public
		 * @since  1.6.0
		 */
		public function register ( $wp_customize ) {

			// Get enabled customizer panels
			$enabled_panels = array( 'typography' => true );
			$enabled_panels = get_option( 'wpex_customizer_panels', $enabled_panels );
			if ( empty( $enabled_panels['typography'] ) ) {
				return;
			}

			// Get elements
			$elements = $this->elements();

			// Return if elements are empty. This check is needed due to the filter added above
			if ( empty( $elements ) ) {
				return;
			}

			// Add General Panel
			$wp_customize->add_panel( 'wpex_typography', array(
				'priority'      => 146,
				'capability'    => 'edit_theme_options',
				'title'         => __( 'Typography', 'wpex' ),
			) );

			// Add General Tab with font smoothing
			$wp_customize->add_section( 'wpex_typography_general' , array(
				'title'     => __( 'General', 'wpex' ),
				'priority'  => 1,
				'panel'     => 'wpex_typography',
			) );

			// Font Smoothing
			$wp_customize->add_setting( 'enable_font_smoothing', array(
				'type'              => 'theme_mod',
				'sanitize_callback' => false,
			) );
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'enable_font_smoothing',
					array(
						'label'         => __( 'Font Smoothing', 'wpex' ),
						'section'       => 'wpex_typography_general',
						'settings'      => 'enable_font_smoothing',
						'priority'      => 1,
						'type'          => 'checkbox',
						'description'   => __( 'Enable font-smoothing site wide. This makes fonts look a little "skinner".', 'wpex' ),
					)
				)
			);

			// Lopp through elements
			$count = '1';
			foreach( $elements as $element => $array ) {
				$count++;

				// Get label
				$label              = ! empty( $array['label'] ) ? $array['label'] : '';
				$exclude_settings   = ! empty( $array['exclude'] ) ? $array['exclude'] : '';

				// Get settings
				if ( ! isset ( $array['settings'] ) ) {
					$settings = array(
						'font-family',
						'font-weight',
						'font-style',
						'text-transform',
						'font-size',
						'line-height',
						'letter-spacing',
						'font-color',
					);
				} else {
					$settings = $array['settings'];
				}

				// Set keys equal to vals
				$settings = array_combine( $settings, $settings );

				// Exclude options
				if ( $exclude_settings ) {
					foreach ( $exclude_settings as $key => $val ) {
						unset( $settings[ $val ] );
					}
				}

				// Register new setting if label isn't empty
				if ( $label ) {

					// Define Section
					$wp_customize->add_section( 'wpex_typography_'. $element , array(
						'title'     => $label,
						'priority'  => $count,
						'panel'     => 'wpex_typography',
					) );

					// Font Family
					if ( in_array( 'font-family', $settings ) ) {

						// Get default
						$default = isset( $array['defaults']['font-family'] ) ? $array['defaults']['font-family'] : NULL;

						// Add setting
						$wp_customize->add_setting( $element .'_typography[font-family]', array(
							'type'              => 'theme_mod',
							'default'           => $default,
							'sanitize_callback' => false,
						) );

						// Add Control
						$wp_customize->add_control(
							new WPEX_Fonts_Dropdown_Custom_Control(
								$wp_customize,
								$element .'_typography[font-family]',
								array(
									'label'    => __( 'Font Family', 'wpex' ),
									'section'  => 'wpex_typography_'. $element,
									'settings' => $element .'_typography[font-family]',
									'priority' => 1,
								)
							)
						);

					}

					// Font Weight
					if ( in_array( 'font-weight', $settings ) ) {
						$wp_customize->add_setting( $element .'_typography[font-weight]', array(
							'type'              => 'theme_mod',
							'description'       => __( 'Note: Not all Fonts support every font weight style.', 'wpex' ),
							'sanitize_callback' => false,
						) );
						$wp_customize->add_control( $element .'_typography[font-weight]', array(
							'label'         => __( 'Font Weight', 'wpex' ),
							'section'       => 'wpex_typography_'. $element,
							'settings'      => $element .'_typography[font-weight]',
							'priority'      => 2,
							'type'          => 'select',
							'choices'   => array (
								''      => __( 'Default', 'wpex' ),
								'100'   => __( 'Extra Light: 100', 'wpex' ),
								'200'   => __( 'Light: 200', 'wpex' ),
								'300'   => __( 'Book: 300', 'wpex' ),
								'400'   => __( 'Normal: 400', 'wpex' ),
								'600'   => __( 'Semibold: 600', 'wpex' ),
								'700'   => __( 'Bold: 700', 'wpex' ),
								'800'   => __( 'Extra Bold: 800', 'wpex' ),
							),
							'description'   => __( 'Important: Not all fonts support every font-weight.', 'wpex' ),
						) );
					}

					// Font Style
					if ( in_array( 'font-style', $settings ) ) {
						$wp_customize->add_setting( $element .'_typography[font-style]', array(
							'type'              => 'theme_mod',
							'sanitize_callback' => false,
						) );
						$wp_customize->add_control( $element .'_typography[font-style]', array(
							'label'     => __( 'Font Style', 'wpex' ),
							'section'   => 'wpex_typography_'. $element,
							'settings'  => $element .'_typography[font-style]',
							'priority'  => 3,
							'type'      => 'select',
							'choices'   => array (
								''          => __( 'Default', 'wpex' ),
								'normal'    => __( 'Normal', 'wpex' ),
								'italic'    => __( 'Italic', 'wpex' ),
							),
						) );
					}

					// Text-Transform
					if ( in_array( 'text-transform', $settings ) ) {
						$wp_customize->add_setting( $element .'_typography[text-transform]', array(
							'type'              => 'theme_mod',
							'sanitize_callback' => false,
						) );
						$wp_customize->add_control( $element .'_typography[text-transform]', array(
							'label'     => __( 'Text Transform', 'wpex' ),
							'section'   => 'wpex_typography_'. $element,
							'settings'  => $element .'_typography[text-transform]',
							'priority'  => 4,
							'type'      => 'select',
							'choices'   => array (
								''              => __( 'Default', 'wpex' ),
								'capitalize'    => __( 'Capitalize', 'wpex' ),
								'lowercase'     => __( 'Lowercase', 'wpex' ),
								'uppercase'     => __( 'Uppercase', 'wpex' ),
							),
						) );
					}

					// Font Size
					if ( in_array( 'font-size', $settings ) ) {
						$wp_customize->add_setting( $element .'_typography[font-size]', array(
							'type'              => 'theme_mod',
							'sanitize_callback' => false,
						) );
						$wp_customize->add_control( $element .'_typography[font-size]', array(
							'label'         => __( 'Font Size', 'wpex' ),
							'section'       => 'wpex_typography_'. $element,
							'settings'      => $element .'_typography[font-size]',
							'priority'      => 5,
							'type'          => 'text',
							'description'   => __( 'Value in pixels.', 'wpex' ),
						) );
					}

					// Font Color
					if ( in_array( 'font-color', $settings ) ) {
						$wp_customize->add_setting( $element .'_typography[color]', array(
							'type'              => 'theme_mod',
							'default'           => '',
							'sanitize_callback' => false,
						) );
						$wp_customize->add_control(
							new WP_Customize_Color_Control(
								$wp_customize,
								$element .'_typography_color',
								array(
									'label'     => __( 'Font Color', 'wpex' ),
									'section'   => 'wpex_typography_'. $element,
									'settings'  => $element .'_typography[color]',
									'priority'  => 6,
								)
							)
						);
					}

					// Line Height
					if ( in_array( 'line-height', $settings ) ) {
						$wp_customize->add_setting( $element .'_typography[line-height]', array(
							'type'              => 'theme_mod',
							'sanitize_callback' => false,
						) );
						$wp_customize->add_control( $element .'_typography[line-height]',
							array(
								'label'     => __( 'Line Height', 'wpex' ),
								'section'   => 'wpex_typography_'. $element,
								'settings'  => $element .'_typography[line-height]',
								'priority'  => 7,
								'type'      => 'text',
						) );
					}

					// Letter Spacing
					if ( in_array( 'letter-spacing', $settings ) ) {
						$wp_customize->add_setting( $element .'_typography[letter-spacing]', array(
							'type'              => 'theme_mod',
							'sanitize_callback' => false,
						) );
						$wp_customize->add_control(
							new WP_Customize_Control(
								$wp_customize,
								$element .'_typography_letter_spacing',
								array(
									'label'     => __( 'Letter Spacing', 'wpex' ),
									'section'   => 'wpex_typography_'. $element,
									'settings'  => $element .'_typography[letter-spacing]',
									'priority'  => 8,
									'type'      => 'text',
								)
							)
						);
					}

				}
			}
		}

		/**
		 * Clear CSS cache when the customizer is saved
		 *
		 * @access public
		 * @since  1.6.0
		 */
		public function reset_cache() {
			remove_theme_mod( 'wpex_customizer_typography_cache' );
		}

		/**
		 * Loop through settings and store typography options into a cached theme mod
		 *
		 * @access public
		 * @since  1.6.0
		 */
		public function loop( $return = 'css' ) {

			// Get typography data cache
			$data = get_theme_mod( 'wpex_customizer_typography_cache', false );

			// If theme mod cache empty or is live customizer loop through elements and set output
			if ( empty( $data ) || is_customize_preview() ) {
				// Define Vars
				$css      = '';
				$fonts    = array();
				$elements = $this->elements();

				// Loop through each elements that need typography styling applied to them
				foreach( $elements as $element => $array ) {

					// Attributes to loop through
					if ( ! empty( $array['settings'] ) ) {
						$attributes = $array['settings'];
					} else {
						$attributes = array( 'font-family', 'font-weight', 'font-style', 'font-size', 'color', 'line-height', 'letter-spacing', 'text-transform' );
					}
					$add_css = '';
					$target  = isset( $array['target'] ) ? $array['target'] : '';
					$get_mod = get_theme_mod( $element .'_typography' );

					// Loop through attributes
					foreach ( $attributes as $attribute ) {

						// Define val
						$default = isset( $array['defaults'][$attribute] ) ? $array['defaults'][$attribute] : NULL;
						$val     = isset ( $get_mod[$attribute] ) ? $get_mod[$attribute] : $default;

						// If there is a value lets do something
						if ( $val ) {

							// Sanitize
							$val = str_replace( '"', '', $val );

							// Sanitize data
							$val = ( 'font-size' == $attribute ) ? wpex_sanitize_data( $val, 'font_size' ) : $val;
							$val = ( 'letter-spacing' == $attribute ) ? wpex_sanitize_data( $val, 'px' ) : $val;

							// Add quotes around font-family && font family to scripts array
							if ( 'font-family' == $attribute ) {
								$fonts[] = $val;
								$val     = $val;
							}

							// Add custom CSS
							$add_css .= $attribute .':'. $val .';';

						}
					}

					// If there is CSS to add, then add it
					if ( $add_css ) {
						$css .= $target .'{'. $add_css .'}';
					}

				}

				// If $css or $fonts vars aren't empty
				if ( $css || $fonts ) {

					// Only load 1 of each font
					if ( ! empty( $fonts ) ) {
						array_unique( $fonts );
					}

				}

			}

			// Set cache or get cache if not in customizer
			if ( ! is_customize_preview() ) {

				// Get Cache vars
				if ( $data ) {
					$css   = isset( $data['css'] ) ? $data['css'] : '';
					$fonts = isset( $data['fonts'] ) ? $data['fonts'] : '';
				}

				// Set Cache
				else {
					set_theme_mod( 'wpex_customizer_typography_cache', array (
						'css'   => $css,
						'fonts' => $fonts,
					) );
				}

			}

			// Return CSS
			if ( 'css' == $return && $css ) {
				$css = '/*TYPOGRAPHY*/'. $css;
				return $css;
			}

			// Return Fonts Array
			if ( 'fonts' == $return && ! empty( $fonts ) ) {
				return $fonts;
			}

		}

		/**
		 * Outputs the typography custom CSS
		 *
		 * @access public
		 * @since  1.6.0
		 */
		public function head_css( $output ) {
			if ( $this->loop( 'css' ) ) {
				$output .= $this->loop( 'css' );
			}
			return $output;
		}

		/**
		 * Loads Google fonts via wp_enqueue_style
		 *
		 * @access public
		 * @since  1.6.0
		 */
		public function load_fonts() {

			// Get fonts
			$fonts = $this->loop( 'fonts' );

			// Loop through and enqueue fonts
			if ( ! empty( $fonts ) && is_array( $fonts ) ) {
				foreach ( $fonts as $font ) {
					wpex_enqueue_google_font( $font );
				}
			}

		}

		/**
		 * Add loaded fonts into the TinyMCE
		 *
		 * @access public
		 * @since  1.6.0
		 */
		public function mce_fonts( $initArray ) {

			// Get fonts
			$fonts       = $this->loop( 'fonts' );
			$fonts       = apply_filters( 'wpex_mce_font_formats_array', $fonts );
			$fonts_array = array();

			// Add custom fonts
			if  ( function_exists( 'wpex_add_custom_fonts' ) ) {
				$custom_fonts = wpex_add_custom_fonts();
				if ( $custom_fonts && is_array( $custom_fonts ) ) {
					$fonts = array_merge( $fonts, $custom_fonts );
				}
			}

			// Loop through fonts
			if ( $fonts && is_array( $fonts ) ) {

				// Create new array of fonts
				foreach ( $fonts as $font ) {
					$fonts_array[] = $font .'=' . $font;
				}

				// Implode fonts array into a semicolon seperated list
				$fonts = implode( ';', $fonts_array );

				// Add Fonts To MCE
				if ( $fonts ) {

					$initArray['font_formats'] = $fonts .';Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats';

				}

			}

			// Return hook array
			return $initArray;

		}

		/**
		 * Add loaded fonts to the sourcode in the admin so it can display in the editor
		 *
		 * @access public
		 * @since  1.6.0
		 */
		public function mce_scripts() {

			// Get fonts
			$fonts = $this->loop( 'fonts' );

			// Add fonts to tinymce
			if ( ! empty( $fonts ) && is_array( $fonts ) ) {
				foreach ( $fonts as $font ) {
					if ( ! in_array( $font, wpex_google_fonts_array() ) ) {
						continue;
					}
					$font  = 'https://fonts.googleapis.com/css?family='. str_replace(' ', '%20', $font ) .':300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&amp;subset=latin,cyrillic-ext,greek-ext,greek,vietnamese,latin-ext,cyrillic';
					$style = str_replace( ',', '%2C', $font );
					add_editor_style( $style );
				}
			}
		}

	}
}
new WPEX_Theme_Customizer_Typography();