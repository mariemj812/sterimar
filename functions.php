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
