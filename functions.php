<?php
/**
 * Dinjob functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Dinjob
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

if ( ! function_exists( 'dinjob_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function dinjob_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Dinjob, use a find and replace
		 * to change 'dinjob' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'dinjob', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'dinjob' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'dinjob_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'dinjob_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function dinjob_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'dinjob_content_width', 640 );
}
add_action( 'after_setup_theme', 'dinjob_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function dinjob_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'dinjob' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'dinjob' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'dinjob_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function dinjob_scripts() {
	wp_enqueue_style( 'dinjob-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'dinjob-style', 'rtl', 'replace' );

	wp_enqueue_script( 'dinjob-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'dinjob_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}


add_filter('get_the_archive_title', 'taxonomy_title_filter');
function taxonomy_title_filter(){
    if ( is_tax() ) {
		$queried_object = get_queried_object();
		if ( $queried_object ) {
			$tax = get_taxonomy( $queried_object->taxonomy );
//                        var_dump($tax);
                        if($tax->name == 'location'){
                            /* translators: Taxonomy term archive title. 1: Taxonomy singular name, 2: Current taxonomy term. */
                            $title = sprintf( __( '%1$s %2$s' ), '<span class="by-who">Jobs from</span>', single_term_title( '', false ) );
                        }elseif($tax->name == 'company'){
                            /* translators: Taxonomy term archive title. 1: Taxonomy singular name, 2: Current taxonomy term. */
                            $title = sprintf( __( '%1$s %2$s' ), '<span class="by-who">Jobs offered by</span>', single_term_title( '', false ) );
                        }elseif($tax->name == 'job_industry' OR $tax->name == 'job_type'){
                            /* translators: Taxonomy term archive title. 1: Taxonomy singular name, 2: Current taxonomy term. */
                            $title = sprintf( __( '%1$s %2$s' ), '<span class="by-who">Jobs available for</span>', single_term_title( '', false ) );
                        }
		}
	}
        return $title;
}

function custom_excerpt_length( $length ) {
	return 20;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

// add filters to search query
add_action( 'pre_get_posts', 'advanced_search_query' );
function advanced_search_query( $query ) {

    if ( isset( $_REQUEST['search'] ) && $_REQUEST['search'] == 'advanced' && ! is_admin() && $query->is_search && $query->is_main_query() ) {

        $query->set( 'post_type', 'jobs' );

        $_location = $_GET['location'] != '' ? $_GET['location'] : '';

        $meta_query = array(
                            array(
                                'key'     => 'location', // assumed your meta_key is 'car_model'
                                'value'   => $_location,
                                'compare' => 'LIKE', // finds models that matches 'model' from the select field
                            )
                        );
        $query->set( 'meta_query', $meta_query );

    }
}

function get_excerpt(){
	$excerpt = get_the_content();
	$excerpt = preg_replace(" ([.*?])",'',$excerpt);
	$excerpt = strip_shortcodes($excerpt);
	$excerpt = strip_tags($excerpt);
	$excerpt = substr($excerpt, 0, 150);
	$excerpt = substr($excerpt, 0, strripos($excerpt, " "));
	$excerpt = trim(preg_replace( '/\s+/', ' ', $excerpt));
	$excerpt = $excerpt.'...';
	return $excerpt;
	}