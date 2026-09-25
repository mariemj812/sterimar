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
 * Only for native WooCommerce / WordPress pages (Checkout, Cart, Account).
 * Static HTML pages already include their own optimized stylesheets and deferred scripts.
 */
function sterimar_enqueue_assets() {
    if ( is_admin() ) {
        return;
    }

    $is_native = false;
    if ( ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) ||
         ( function_exists( 'is_checkout' ) && is_checkout() ) ||
         ( function_exists( 'is_cart' ) && is_cart() ) ||
         ( function_exists( 'is_account_page' ) && is_account_page() ) ) {
        $is_native = true;
    }

    if ( $is_native ) {
        $theme_uri = untrailingslashit( get_template_directory_uri() );
        wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700&display=swap', array(), null );
        wp_enqueue_style( 'sterimar-style', $theme_uri . '/style.css', array(), '25' );
        wp_enqueue_script( 'sterimar-app', $theme_uri . '/app.js', array(), '9', array( 'strategy' => 'defer', 'in_footer' => true ) );
    }

    // Remove Gutenberg Block CSS for non-post pages to improve performance
    if ( ! is_singular( 'post' ) ) {
        wp_dequeue_style( 'wp-block-library' );
        wp_dequeue_style( 'wp-block-library-theme' );
        wp_dequeue_style( 'wc-blocks-style' );
        wp_dequeue_style( 'classic-theme-styles' );
    }
}
add_action( 'wp_enqueue_scripts', 'sterimar_enqueue_assets', 100 );

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
 * Automatic Meta Pixel Purchase Event & Local Cart Clearance on Order Received
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
<!-- Clear Local Cart & Track Meta Pixel Purchase -->
<script>
try {
    localStorage.removeItem('sterimar_cart');
    if (typeof window.cart !== 'undefined') {
        window.cart = [];
    }
    var cartBadges = document.querySelectorAll('#cart-count, #mobile-cart-count, .cart-count');
    cartBadges.forEach(function(badge) {
        badge.textContent = '0';
    });
} catch(e) {}

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
 * Rendre le Numéro de Téléphone STRICTEMENT OBLIGATOIRE au Checkout
 * Support complet Classic Checkout + WooCommerce Blocks + Store API
 * --------------------------------------------------------------------------
 */

// 1. Forcer l'option WooCommerce en base de données
add_action( 'init', function() {
    if ( get_option( 'woocommerce_checkout_phone_field' ) !== 'required' ) {
        update_option( 'woocommerce_checkout_phone_field', 'required' );
    }
} );

// 2. Filtres sur les options WooCommerce
add_filter( 'option_woocommerce_checkout_phone_field', function() {
    return 'required';
}, 9999 );
add_filter( 'default_option_woocommerce_checkout_phone_field', function() {
    return 'required';
}, 9999 );

// 3. Champs d'adresse par défaut (utilisé par WC Core & Blocks AssetDataRegistry)
add_filter( 'woocommerce_default_address_fields', 'sterimar_force_default_phone_required', 9999 );
function sterimar_force_default_phone_required( $fields ) {
    if ( isset( $fields['phone'] ) ) {
        $fields['phone']['required']      = true;
        $fields['phone']['hidden']        = false;
        $fields['phone']['label']         = __( 'Numéro de téléphone', 'woocommerce' );
        $fields['phone']['optionalLabel'] = __( 'Numéro de téléphone', 'woocommerce' );
        $fields['phone']['class'][]       = 'validate-required';
        $fields['phone']['class'][]       = 'validate-phone';
    }
    return $fields;
}

// 4. Champs de facturation (Classic Checkout)
add_filter( 'woocommerce_billing_fields', 'sterimar_billing_phone_mandatory', 9999 );
function sterimar_billing_phone_mandatory( $fields ) {
    if ( isset( $fields['billing_phone'] ) ) {
        $fields['billing_phone']['required']      = true;
        $fields['billing_phone']['label']         = __( 'Numéro de téléphone', 'woocommerce' );
        $fields['billing_phone']['placeholder']   = __( 'Ex: 29 550 043', 'woocommerce' );
        $fields['billing_phone']['class'][]       = 'validate-required';
        $fields['billing_phone']['class'][]       = 'validate-phone';
    }
    return $fields;
}

add_filter( 'woocommerce_checkout_fields', 'sterimar_checkout_phone_mandatory', 9999 );
function sterimar_checkout_phone_mandatory( $fields ) {
    if ( isset( $fields['billing']['billing_phone'] ) ) {
        $fields['billing']['billing_phone']['required']      = true;
        $fields['billing']['billing_phone']['label']         = __( 'Numéro de téléphone', 'woocommerce' );
        $fields['billing']['billing_phone']['placeholder']   = __( 'Ex: 29 550 043', 'woocommerce' );
        $fields['billing']['billing_phone']['class'][]       = 'validate-required';
        $fields['billing']['billing_phone']['class'][]       = 'validate-phone';
    }
    return $fields;
}

// 5. Paramètres transmis à React dans WooCommerce Blocks
add_filter( 'woocommerce_blocks_asset_api_script_data', 'sterimar_force_blocks_phone_required', 9999 );
function sterimar_force_blocks_phone_required( $data ) {
    if ( isset( $data['defaultFields']['phone'] ) ) {
        $data['defaultFields']['phone']['required']      = true;
        $data['defaultFields']['phone']['optionalLabel'] = __( 'Numéro de téléphone', 'woocommerce' );
    }
    return $data;
}

// 6. Validation serveur Classic Checkout
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

// 7. Validation serveur Store API (WooCommerce Blocks Checkout)
add_action( 'woocommerce_store_api_checkout_update_order_from_request', 'sterimar_validate_blocks_phone_checkout', 10, 2 );
function sterimar_validate_blocks_phone_checkout( $order, $request ) {
    $billing_address = $request->get_param( 'billing_address' );
    $shipping_address = $request->get_param( 'shipping_address' );
    
    $phone = '';
    if ( is_array( $billing_address ) && ! empty( $billing_address['phone'] ) ) {
        $phone = $billing_address['phone'];
    } elseif ( is_array( $shipping_address ) && ! empty( $shipping_address['phone'] ) ) {
        $phone = $shipping_address['phone'];
    } elseif ( method_exists( $order, 'get_billing_phone' ) ) {
        $phone = $order->get_billing_phone();
    }
    
    if ( empty( trim( $phone ) ) ) {
        if ( class_exists( '\Automattic\WooCommerce\StoreApi\Exceptions\RouteException' ) ) {
            throw new \Automattic\WooCommerce\StoreApi\Exceptions\RouteException(
                'woocommerce_rest_checkout_missing_phone',
                __( 'Le numéro de téléphone est obligatoire pour valider la commande et la livraison.', 'woocommerce' ),
                400
            );
        } else {
            throw new \Exception( __( 'Le numéro de téléphone est obligatoire pour valider la commande.', 'woocommerce' ) );
        }
    }
}

// 8. Script client universel (Classic + Blocks MutationObserver)
function sterimar_checkout_phone_client_script() {
    if ( ( function_exists( 'is_checkout' ) && is_checkout() ) || ( function_exists( 'is_cart' ) && is_cart() ) ) {
        ?>
        <script>
        (function() {
            function enforcePhoneRequired() {
                var phoneInputs = document.querySelectorAll('input[type="tel"], input[autocomplete="tel"], input#phone, input#billing_phone, input#shipping-phone');
                phoneInputs.forEach(function(input) {
                    input.required = true;
                    input.setAttribute('required', 'required');
                    input.setAttribute('aria-required', 'true');
                    
                    var parentField = input.closest('.wc-block-components-text-input') || input.closest('.form-row') || input.parentElement;
                    if (parentField) {
                        // Masquer ou supprimer les mentions "(facultatif)"
                        var optionals = parentField.querySelectorAll('.optional, .wc-block-components-address-form__optional, .wc-block-components-form-field-optional');
                        optionals.forEach(function(el) { el.style.display = 'none'; });
                        
                        var label = parentField.querySelector('label');
                        if (label) {
                            if (label.innerHTML.indexOf('(facultatif)') !== -1) {
                                label.innerHTML = label.innerHTML.replace(/\(facultatif\)/gi, '');
                            }
                            if (!label.querySelector('.sterimar-phone-star')) {
                                var star = document.createElement('span');
                                star.className = 'sterimar-phone-star';
                                star.style.color = '#dc2626';
                                star.style.fontWeight = 'bold';
                                star.style.marginLeft = '4px';
                                star.textContent = ' *';
                                label.appendChild(star);
                            }
                        }
                    }
                });
            }

            // Exécution immédiate
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', enforcePhoneRequired);
            } else {
                enforcePhoneRequired();
            }

            // Écoute des mises à jour AJAX classiques et React Blocks
            document.body.addEventListener('updated_checkout', enforcePhoneRequired);

            // MutationObserver pour observer les rendus dynamiques React de WooCommerce Blocks
            if (window.MutationObserver) {
                var observer = new MutationObserver(function() {
                    enforcePhoneRequired();
                });
                observer.observe(document.body, { childList: true, subtree: true });
            }

            // Interception avant soumission pour bloquer si le téléphone est vide
            document.addEventListener('click', function(e) {
                var submitBtn = e.target.closest('.wc-block-components-checkout-place-order-button, #place_order, button[type="submit"]');
                if (submitBtn) {
                    var phoneInputs = document.querySelectorAll('input[type="tel"], input[autocomplete="tel"], input#phone, input#billing_phone, input#shipping-phone');
                    var emptyPhone = null;
                    phoneInputs.forEach(function(input) {
                        if (!emptyPhone && input.offsetParent !== null && !input.value.trim()) {
                            emptyPhone = input;
                        }
                    });
                    if (emptyPhone) {
                        e.preventDefault();
                        e.stopPropagation();
                        emptyPhone.focus();
                        emptyPhone.style.borderColor = '#dc2626';
                        emptyPhone.style.boxShadow = '0 0 0 3px rgba(220, 38, 38, 0.2)';
                        
                        // Notification toast ou alerte claire
                        alert('Le numéro de téléphone est obligatoire pour valider la commande et assurer la livraison.');
                        emptyPhone.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            }, true);
        })();
        </script>
        <?php
    }
}
add_action( 'wp_footer', 'sterimar_checkout_phone_client_script', 99 );

/**
 * Prevent WordPress from sending 404 status for theme static HTML templates.
 * Forces HTTP 200 OK so browsers, SEO crawlers and LiteSpeed Cache recognize and cache all subpages.
 */
add_action( 'template_redirect', function() {
    $request_uri = trim( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' );
    $path_parts = array_filter( explode( '/', $request_uri ) );
    $last_part = end( $path_parts );
    if ( ! empty( $last_part ) ) {
        $candidate_html = preg_match( '/\.html$/i', $last_part ) ? $last_part : $last_part . '.html';
        $theme_dir = get_template_directory();
        if ( file_exists( $theme_dir . '/' . $candidate_html ) ) {
            status_header( 200 );
            global $wp_query;
            if ( isset( $wp_query ) ) {
                $wp_query->is_404 = false;
            }
        }
    }
}, 1 );


