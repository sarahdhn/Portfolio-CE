<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

add_action( 'after_setup_theme', 'lalita_background_setup' );
/**
 * Overwrite parent theme background defaults and registers support for WordPress features.
 *
 */
function lalita_background_setup() {
	add_theme_support( "custom-background",
		array(
			'default-color' 		 => 'fee5ee',
			'default-image'          => '',
			'default-repeat'         => 'repeat',
			'default-position-x'     => 'left',
			'default-position-y'     => 'top',
			'default-size'           => 'auto',
			'default-attachment'     => '',
			'wp-head-callback'       => '_custom_background_cb',
			'admin-head-callback'    => '',
			'admin-preview-callback' => ''
		)
	);
}

/**
 * Overwrite theme URL
 *
 */
function lalita_theme_uri_link() {
	return 'https://wpkoi.com/agni-wpkoi-wordpress-theme/';
}

/**
 * Overwrite parent theme's blog header function
 *
 */
add_action( 'lalita_after_header', 'lalita_blog_header_image', 11 );
function lalita_blog_header_image() {

	if ( ( is_front_page() && is_home() ) || ( is_home() ) ) { 
		$blog_header_image 			=  lalita_get_setting( 'blog_header_image' ); 
		$blog_header_title 			=  lalita_get_setting( 'blog_header_title' ); 
		$blog_header_text 			=  lalita_get_setting( 'blog_header_text' ); 
		$blog_header_button_text 	=  lalita_get_setting( 'blog_header_button_text' ); 
		$blog_header_button_url 	=  lalita_get_setting( 'blog_header_button_url' ); 
		if ( $blog_header_image != '' ) { ?>
		<div class="page-header-image grid-parent page-header-blog" style="background-image: url('<?php echo esc_url($blog_header_image); ?>') !important;">
        	<div class="page-header-blog-inner">
                <div class="page-header-blog-content-h grid-container">
                    <div class="page-header-blog-content">
                    <?php if ( $blog_header_title != '' ) { ?>
                        <div class="page-header-blog-text">
                            <?php if ( $blog_header_title != '' ) { ?>
                            <h2><?php echo wp_kses_post( $blog_header_title ); ?></h2>
                            <div class="clearfix"></div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    </div>
                </div>
                <div class="page-header-blog-content page-header-blog-content-b">
                	<?php if ( $blog_header_text != '' ) { ?>
                	<div class="page-header-blog-text">
						<?php if ( $blog_header_title != '' ) { ?>
                        <p><?php echo wp_kses_post( $blog_header_text ); ?></p>
                        <div class="clearfix"></div>
                        <?php } ?>
                    </div>
                    <?php } ?>
                    <div class="page-header-blog-button">
                        <?php if ( $blog_header_button_text != '' ) { ?>
                        <a class="read-more button" href="<?php echo esc_url( $blog_header_button_url ); ?>"><?php echo esc_html( $blog_header_button_text ); ?></a>
                        <?php } ?>
                    </div>
                </div>
            </div>
		</div>
		<?php
		}
	}
}

if ( ! function_exists( 'agni_remove_parent_dynamic_css' ) ) {
	add_action( 'init', 'agni_remove_parent_dynamic_css' );
	/**
	 * The dynamic styles of the parent theme added inline to the parent stylesheet.
	 * For the customizer functions it is better to enqueue after the child theme stylesheet.
	 */
	function agni_remove_parent_dynamic_css() {
		remove_action( 'wp_enqueue_scripts', 'lalita_enqueue_dynamic_css', 50 );
	}
}

if ( ! function_exists( 'agni_enqueue_parent_dynamic_css' ) ) {
	add_action( 'wp_enqueue_scripts', 'agni_enqueue_parent_dynamic_css', 50 );
	/**
	 * Enqueue this CSS after the child stylesheet, not after the parent stylesheet.
	 *
	 */
	function agni_enqueue_parent_dynamic_css() {
		$css = lalita_base_css() . lalita_font_css() . lalita_advanced_css() . lalita_spacing_css() . lalita_no_cache_dynamic_css();

		// escaped secure before in parent theme
		wp_add_inline_style( 'lalita-child', $css );
	}
}

// Extra cutomizer functions
if ( ! function_exists( 'agni_customize_register' ) ) {
	add_action( 'customize_register', 'agni_customize_register' );
	function agni_customize_register( $wp_customize ) {
				
		// Add Agni customizer section
		$wp_customize->add_section(
			'agni_layout_effects',
			array(
				'title' => __( 'Agni Effects', 'agni' ),
				'priority' => 1,
				'panel' => 'lalita_layout_panel'
			)
		);
		
		
		// Container borders
		$wp_customize->add_setting(
			'agni_settings[agni_borders]',
			array(
				'default' => 'enable',
				'type' => 'option',
				'sanitize_callback' => 'agni_sanitize_choices'
			)
		);

		$wp_customize->add_control(
			'agni_settings[agni_borders]',
			array(
				'type' => 'select',
				'label' => __( 'Agni borders', 'agni' ),
				'choices' => array(
					'enable' => __( 'Enable', 'agni' ),
					'disable' => __( 'Disable', 'agni' )
				),
				'settings' => 'agni_settings[agni_borders]',
				'section' => 'agni_layout_effects',
				'priority' => 1
			)
		);
		
		
		// Navigation effect
		// Splitting characters and adds effect
		$wp_customize->add_setting(
			'agni_settings[nav_effect]',
			array(
				'default' => 'enable',
				'type' => 'option',
				'sanitize_callback' => 'agni_sanitize_choices'
			)
		);

		$wp_customize->add_control(
			'agni_settings[nav_effect]',
			array(
				'type' => 'select',
				'label' => __( 'Agni navigation effect', 'agni' ),
				'choices' => array(
					'enable' => __( 'Enable', 'agni' ),
					'disable' => __( 'Disable', 'agni' )
				),
				'settings' => 'agni_settings[nav_effect]',
				'section' => 'agni_layout_effects',
				'priority' => 1
			)
		);
		
		// Search style
		// Add unique style to search forms in widget and top bar area
		$wp_customize->add_setting(
			'agni_settings[agni_search]',
			array(
				'default' => 'enable',
				'type' => 'option',
				'sanitize_callback' => 'agni_sanitize_choices'
			)
		);

		$wp_customize->add_control(
			'agni_settings[agni_search]',
			array(
				'type' => 'select',
				'label' => __( 'Agni search style', 'agni' ),
				'choices' => array(
					'enable' => __( 'Enable', 'agni' ),
					'disable' => __( 'Disable', 'agni' )
				),
				'settings' => 'agni_settings[agni_search]',
				'section' => 'agni_layout_effects',
				'priority' => 2
			)
		);
		
		// Nicescroll
		$wp_customize->add_setting(
			'agni_settings[nicescroll]',
			array(
				'default' => 'enable',
				'type' => 'option',
				'sanitize_callback' => 'agni_sanitize_choices'
			)
		);

		$wp_customize->add_control(
			'agni_settings[nicescroll]',
			array(
				'type' => 'select',
				'label' => __( 'Scrollbar style', 'agni' ),
				'choices' => array(
					'enable' => __( 'Enable', 'agni' ),
					'disable' => __( 'Disable', 'agni' )
				),
				'settings' => 'agni_settings[nicescroll]',
				'section' => 'agni_layout_effects',
				'priority' => 20
			)
		);
		
	}
}

if ( ! function_exists( 'agni_sanitize_choices' ) ) {
	/**
	 * Sanitize choices.
	 *
	 */
	function agni_sanitize_choices( $input, $setting ) {
		// Ensure input is a slug
		$input = sanitize_key( $input );

		// Get list of choices from the control
		// associated with the setting
		$choices = $setting->manager->get_control( $setting->id )->choices;

		// If the input is a valid key, return it;
		// otherwise, return the default
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}
}

if ( ! function_exists( 'agni_body_classes' ) ) {
	add_filter( 'body_class', 'agni_body_classes' );
	/**
	 * Adds custom classes to the array of body classes.
	 *
	 */
	function agni_body_classes( $classes ) {
		// Get Customizer settings
		$agni_settings = get_option( 'agni_settings' );
		
		$agni_search  = 'enable';
		$nicescroll   = 'enable';
		$agni_borders = 'enable';
		if ( isset( $agni_settings['agni_search'] ) ) {
			$agni_search = $agni_settings['agni_search'];
		}
		if ( isset( $agni_settings['nicescroll'] ) ) {
			$nicescroll = $agni_settings['nicescroll'];
		}
		if ( isset( $agni_settings['agni_borders'] ) ) {
			$agni_borders = $agni_settings['agni_borders'];
		}
		
		// Search effect
		if ( $agni_search != 'disable' ) {
			$classes[] = 'agni-search-effect';
		}
		
		// Scrollbar style function
		if ( $nicescroll != 'disable' ) {
			$classes[] = 'agni-scrollbar-style';
		}
		
		// Agni borders
		if ( $agni_borders != 'disable' ) {
			$classes[] = 'agni-borders';
		}
		
		return $classes;
	}
}


if ( ! function_exists( 'agni_scripts' ) ) {
	add_action( 'wp_enqueue_scripts', 'agni_scripts' );
	/**
	 * Enqueue script
	 */
	function agni_scripts() {
		
		$agni_settings = get_option( 'agni_settings' );
		
		$nav_effect  = 'enable';
		if ( isset( $agni_settings['nav_effect'] ) ) {
			$nav_effect = $agni_settings['nav_effect'];
		}
		
		if ( $nav_effect != 'disable' ) {
			wp_enqueue_style( 'agni-splitting-css', esc_url( get_stylesheet_directory_uri() ) . "/css/splitting.min.css", false, LALITA_VERSION, 'all' );
			wp_enqueue_script( 'agni-splitting-js', esc_url( get_stylesheet_directory_uri() ) . "/js/splitting.min.js", array( 'jquery'), LALITA_VERSION, true );
		}
		
	}
}
