<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Dinjob
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function dinjob_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'dinjob_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function dinjob_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'dinjob_pingback_header' );

function rtp_rssv_scripts() {
    global $wp_scripts;
    if (!is_a($wp_scripts, 'WP_Scripts'))
        return;
    foreach ($wp_scripts->registered as $handle => $script)
        $wp_scripts->registered[$handle]->ver = null;
}

function rtp_rssv_styles() {
    global $wp_styles;
    if (!is_a($wp_styles, 'WP_Styles'))
        return;
    foreach ($wp_styles->registered as $handle => $style)
        $wp_styles->registered[$handle]->ver = null;
}

add_action('wp_print_scripts', 'rtp_rssv_scripts', 999);
add_action('wp_print_footer_scripts', 'rtp_rssv_scripts', 999);

add_action('admin_print_styles', 'rtp_rssv_styles', 999);
add_action('wp_print_styles', 'rtp_rssv_styles', 999);


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

