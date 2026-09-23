<?php
/**
 * Stérimar Theme Functions & WooCommerce Support
 *
 * @package Sterimar
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Setup Theme Support
 */
function sterimar_setup_theme() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'sterimar_setup_theme' );

/**
 * Enqueue Theme Assets
 */
function sterimar_enqueue_assets() {
    $theme_uri = untrailingslashit( get_template_directory_uri() );
    
    wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800&display=swap', array(), null );
    wp_enqueue_style( 'sterimar-style', $theme_uri . '/style.css', array(), '1.2' );
    wp_enqueue_script( 'sterimar-app', $theme_uri . '/app.js', array(), '1.2', true );
}
add_action( 'wp_enqueue_scripts', 'sterimar_enqueue_assets' );

/**
 * Ensure WordPress uses clean permalinks (/%postname%/)
 */
function sterimar_ensure_permalinks() {
    $current = get_option( 'permalink_structure' );
    if ( $current !== '/%postname%/' ) {
        global $wp_rewrite;
        if ( is_object( $wp_rewrite ) ) {
            $wp_rewrite->set_permalink_structure( '/%postname%/' );
            $wp_rewrite->flush_rules( false );
        }
    }
}
add_action( 'init', 'sterimar_ensure_permalinks', 1 );

/**
 * Prevent 404 errors on clean theme HTML routes
 */
function sterimar_handle_clean_routes() {
    $theme_dir = get_template_directory();
    $request_uri = trim( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' );
    $path_parts  = array_filter( explode( '/', $request_uri ) );
    $last_part   = end( $path_parts );
    
    if ( ! empty( $last_part ) ) {
        $candidate_html = preg_match( '/\.html$/i', $last_part ) ? $last_part : $last_part . '.html';
        if ( file_exists( $theme_dir . '/' . $candidate_html ) ) {
            global $wp_query;
            if ( is_object( $wp_query ) ) {
                $wp_query->is_404 = false;
            }
            status_header( 200 );
        }
    }
}
add_action( 'template_redirect', 'sterimar_handle_clean_routes', 1 );

