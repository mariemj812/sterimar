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
 * Meta Pixel Tracking Code (Base + PageView)
 */
function sterimar_add_meta_pixel() {
    ?>
<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1060943890086095');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1060943890086095&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
    <?php
}
add_action( 'wp_head', 'sterimar_add_meta_pixel', 1 );

/**
 * Automatic Meta Pixel Purchase Event on WooCommerce Order Received
 */
function sterimar_track_wc_purchase( $order_id ) {
    if ( ! $order_id ) {
        return;
    }
    $order = wc_get_order( $order_id );
    if ( ! $order ) {
        return;
    }
    ?>
<!-- Meta Pixel Purchase Event -->
<script>
if (typeof fbq === 'function') {
    fbq('track', 'Purchase', {
        value: <?php echo esc_js( $order->get_total() ); ?>,
        currency: '<?php echo esc_js( $order->get_currency() ); ?>'
    });
}
</script>
    <?php
}
add_action( 'woocommerce_thankyou', 'sterimar_track_wc_purchase', 20 );

