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
    
    wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700&display=swap', array(), null );
    wp_enqueue_style( 'sterimar-style', $theme_uri . '/style.css', array(), '2.0' );
    wp_enqueue_script( 'sterimar-app', $theme_uri . '/app.js', array(), '2.0', true );
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

/**
 * Disable conflicting external chatbot plugins (e.g. Rapls AI Chatbot)
 * to ensure only the official Stérimar AI Assistant is active.
 */
function sterimar_disable_external_chatbots() {
    wp_dequeue_script( 'raplsaich-chatbot-js' );
    wp_deregister_script( 'raplsaich-chatbot-js' );
    wp_dequeue_style( 'raplsaich-chatbot-css' );
    wp_deregister_style( 'raplsaich-chatbot-css' );
}
add_action( 'wp_enqueue_scripts', 'sterimar_disable_external_chatbots', 999 );
add_action( 'wp_print_scripts', 'sterimar_disable_external_chatbots', 999 );
add_action( 'wp_print_styles', 'sterimar_disable_external_chatbots', 999 );

/**
 * Disable WordPress Emojis for Performance
 */
function sterimar_disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', 'sterimar_disable_emojis_tinymce' );
    add_filter( 'wp_resource_hints', 'sterimar_disable_emojis_remove_dns', 10, 2 );
}
add_action( 'init', 'sterimar_disable_emojis' );

function sterimar_disable_emojis_tinymce( $plugins ) {
    return is_array( $plugins ) ? array_diff( $plugins, array( 'wpemoji' ) ) : array();
}

function sterimar_disable_emojis_remove_dns( $urls, $relation_type ) {
    if ( 'dns-prefetch' === $relation_type ) {
        $emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/' );
        $urls = array_diff( $urls, array( $emoji_svg_url ) );
    }
    return $urls;
}

/**
 * --------------------------------------------------------------------------
 * Rendre le Numéro de Téléphone OBLIGATOIRE au Checkout / Validation Paiement
 * --------------------------------------------------------------------------
 */
add_filter( 'woocommerce_billing_fields', 'sterimar_billing_phone_mandatory', 999 );
function sterimar_billing_phone_mandatory( $fields ) {
    if ( isset( $fields['billing_phone'] ) ) {
        $fields['billing_phone']['required']    = true;
        $fields['billing_phone']['label']       = __( 'Numéro de téléphone', 'woocommerce' );
        $fields['billing_phone']['placeholder'] = __( 'Ex: 29 550 043', 'woocommerce' );
        $fields['billing_phone']['class'][]     = 'validate-required';
        $fields['billing_phone']['class'][]     = 'validate-phone';
    }
    return $fields;
}

add_filter( 'woocommerce_checkout_fields', 'sterimar_checkout_phone_mandatory', 999 );
function sterimar_checkout_phone_mandatory( $fields ) {
    if ( isset( $fields['billing']['billing_phone'] ) ) {
        $fields['billing']['billing_phone']['required']    = true;
        $fields['billing']['billing_phone']['label']       = __( 'Numéro de téléphone', 'woocommerce' );
        $fields['billing']['billing_phone']['placeholder'] = __( 'Ex: 29 550 043', 'woocommerce' );
        $fields['billing']['billing_phone']['class'][]     = 'validate-required';
        $fields['billing']['billing_phone']['class'][]     = 'validate-phone';
    }
    return $fields;
}

add_action( 'woocommerce_checkout_process', 'sterimar_validate_phone_checkout' );
function sterimar_validate_phone_checkout() {
    $phone = isset( $_POST['billing_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_phone'] ) ) : '';
    if ( empty( trim( $phone ) ) ) {
        wc_add_notice( __( 'Le <strong>numéro de téléphone</strong> est obligatoire pour valider la commande et assurer la livraison.', 'woocommerce' ), 'error' );
        return;
    }
    $digits = preg_replace( '/\D/', '', $phone );
    if ( strlen( $digits ) < 8 ) {
        wc_add_notice( __( 'Veuillez saisir un <strong>numéro de téléphone valide</strong> (minimum 8 chiffres).', 'woocommerce' ), 'error' );
    }
}

/**
 * Script de renforcement dynamique du champ téléphone obligatoire côté client au Checkout
 */
function sterimar_checkout_phone_client_script() {
    if ( function_exists( 'is_checkout' ) && is_checkout() ) {
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            function enforcePhoneMandatory() {
                var phoneInput = document.getElementById('billing_phone');
                var phoneField = document.getElementById('billing_phone_field');
                if (phoneInput) {
                    phoneInput.required = true;
                    phoneInput.setAttribute('required', 'required');
                    phoneInput.setAttribute('aria-required', 'true');
                }
                if (phoneField) {
                    phoneField.classList.add('validate-required');
                    var optionalSpan = phoneField.querySelector('.optional');
                    if (optionalSpan) {
                        optionalSpan.remove();
                    }
                    var label = phoneField.querySelector('label');
                    if (label && !label.querySelector('.required')) {
                        var requiredAbbr = document.createElement('abbr');
                        requiredAbbr.className = 'required';
                        requiredAbbr.title = 'obligatoire';
                        requiredAbbr.innerText = ' *';
                        requiredAbbr.style.color = '#dc2626';
                        label.appendChild(requiredAbbr);
                    }
                }
            }
            enforcePhoneMandatory();
            document.body.addEventListener('updated_checkout', enforcePhoneMandatory);
        });
        </script>
        <?php
    }
}
add_action( 'wp_footer', 'sterimar_checkout_phone_client_script', 99 );


