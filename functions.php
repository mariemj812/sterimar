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
 * Forcer le domaine canonique vers https://sterimar.shop (sans www)
 * Override les options WordPress pour empêcher la redirection vers www
 */
add_filter( 'option_home', function( $url ) {
    return 'https://sterimar.shop';
}, 1 );

add_filter( 'option_siteurl', function( $url ) {
    return 'https://sterimar.shop';
}, 1 );

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
        wp_enqueue_style( 'sterimar-style', $theme_uri . '/style.css', array(), '29' );
        wp_enqueue_script( 'sterimar-app', $theme_uri . '/app.js', array(), '9', array( 'strategy' => 'defer', 'in_footer' => true ) );
    }

    // Remove Gutenberg Block CSS for non-post pages to improve performance
    if ( ! is_singular( 'post' ) ) {
        wp_dequeue_style( 'wp-block-library' );
        wp_dequeue_style( 'wp-block-library-theme' );
        wp_dequeue_style( 'wc-blocks-style' );
        wp_dequeue_style( 'classic-theme-styles' );
    }

    // On static HTML pages (home, shop, blog, about, contact), dequeue heavy unused WooCommerce & jQuery scripts
    if ( ! $is_native ) {
        wp_dequeue_style( 'woocommerce-layout' );
        wp_dequeue_style( 'woocommerce-smallscreen' );
        wp_dequeue_style( 'woocommerce-general' );
        wp_dequeue_style( 'woocommerce-inline' );

        wp_dequeue_script( 'jquery' );
        wp_dequeue_script( 'jquery-core' );
        wp_dequeue_script( 'jquery-migrate' );
        wp_dequeue_script( 'jquery-blockui' );
        wp_dequeue_script( 'woocommerce' );
        wp_dequeue_script( 'wc-add-to-cart' );
        wp_dequeue_script( 'wc-cart-fragments' );
        wp_dequeue_script( 'sourcebuster-js' );
        wp_dequeue_script( 'wc-order-attribution' );
        wp_dequeue_script( 'js-cookie' );
        wp_dequeue_script( 'reddit-for-woocommerce-tracking' );
        wp_dequeue_script( 'snapchat-for-woocommerce-tracking' );
        wp_dequeue_script( 'google-sign-in-button' );
    }
}
add_action( 'wp_enqueue_scripts', 'sterimar_enqueue_assets', 100 );

/**
 * Meta Pixel Advanced Matching: build normalised customer data.
 * Values are hashed (SHA-256) automatically by the pixel in the browser.
 * Format rules: https://developers.facebook.com/docs/meta-pixel/advanced/advanced-matching
 *
 * @param WC_Order|null $order Order to read billing data from (thank-you page).
 * @return array
 */
function sterimar_get_pixel_user_data( $order = null ) {
    $raw = array();

    if ( $order && is_a( $order, 'WC_Order' ) ) {
        $raw = array(
            'em'      => $order->get_billing_email(),
            'ph'      => $order->get_billing_phone(),
            'fn'      => $order->get_billing_first_name(),
            'ln'      => $order->get_billing_last_name(),
            'ct'      => $order->get_billing_city(),
            'zp'      => $order->get_billing_postcode(),
            'country' => $order->get_billing_country(),
        );
        if ( $order->get_user_id() ) {
            $raw['external_id'] = (string) $order->get_user_id();
        }
    } elseif ( is_user_logged_in() ) {
        $user = wp_get_current_user();
        $raw  = array(
            'em'          => $user->user_email,
            'fn'          => get_user_meta( $user->ID, 'billing_first_name', true ) ?: $user->first_name,
            'ln'          => get_user_meta( $user->ID, 'billing_last_name', true ) ?: $user->last_name,
            'ph'          => get_user_meta( $user->ID, 'billing_phone', true ),
            'ct'          => get_user_meta( $user->ID, 'billing_city', true ),
            'zp'          => get_user_meta( $user->ID, 'billing_postcode', true ),
            'country'     => get_user_meta( $user->ID, 'billing_country', true ),
            'external_id' => (string) $user->ID,
        );
    }

    $data = array();
    foreach ( $raw as $key => $value ) {
        $value = trim( (string) $value );
        if ( '' === $value ) {
            continue;
        }
        switch ( $key ) {
            case 'em':
                $value = strtolower( $value );
                break;
            case 'ph':
                $value = preg_replace( '/\D+/', '', $value ); // digits only, with country code
                break;
            case 'ct':
                $value = preg_replace( '/[^\p{L}]+/u', '', mb_strtolower( $value, 'UTF-8' ) );
                break;
            case 'zp':
                $value = strtolower( preg_replace( '/\s+/', '', $value ) );
                break;
            case 'country':
                $value = strtolower( $value ); // ISO 3166-1 alpha-2
                break;
            case 'external_id':
                break;
            default: // fn, ln
                $value = mb_strtolower( $value, 'UTF-8' );
        }
        if ( '' !== $value ) {
            $data[ $key ] = $value;
        }
    }
    return $data;
}

/**
 * Meta Pixel Tracking Code (Base + PageView) - High Performance Defer
 */
function sterimar_add_meta_pixel() {
    ?>
<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];
var loaded=false;
function loadPixel(){
    if(loaded)return;loaded=true;
    t=b.createElement(e);t.async=true;t.src=v;
    s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s);
}
if('requestIdleCallback' in window){
    requestIdleCallback(function(){ setTimeout(loadPixel, 1000); });
} else {
    setTimeout(loadPixel, 1500);
}
['scroll','touchstart','click'].forEach(function(ev){
    window.addEventListener(ev, loadPixel, {once:true, passive:true});
});
}(window, document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1060943890086095'<?php
    $sterimar_pixel_user = sterimar_get_pixel_user_data();
    if ( ! empty( $sterimar_pixel_user ) ) {
        echo ', ' . wp_json_encode( $sterimar_pixel_user );
    }
?>);
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
<?php $sterimar_order_user = sterimar_get_pixel_user_data( $order ); if ( ! empty( $sterimar_order_user ) ) : ?>
    fbq('init', '1060943890086095', <?php echo wp_json_encode( $sterimar_order_user ); ?>);
<?php endif; ?>
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
    
    if ( empty( trim( (string) $phone ) ) ) {
        if ( class_exists( '\Automattic\WooCommerce\StoreApi\Exceptions\RouteException' ) ) {
            throw new \Automattic\WooCommerce\StoreApi\Exceptions\RouteException(
                'woocommerce_rest_checkout_missing_phone',
                __( 'Le numéro de téléphone est obligatoire pour valider la commande et la livraison.', 'woocommerce' ),
                400
            );
        } elseif ( function_exists( 'wc_add_notice' ) ) {
            wc_add_notice( __( 'Le numéro de téléphone est obligatoire pour valider la commande et assurer la livraison.', 'woocommerce' ), 'error' );
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
    $request_uri = isset( $_SERVER['REQUEST_URI'] ) ? trim( (string) parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' ) : '';
    $path_parts = array_values( array_filter( explode( '/', $request_uri ) ) );
    $last_part = ! empty( $path_parts ) ? end( $path_parts ) : '';
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

/**
 * Ensure /boutique/ and WooCommerce Shop archive use index.php to render the custom boutique.html template.
 */
add_filter( 'template_include', function( $template ) {
    $request_uri = isset( $_SERVER['REQUEST_URI'] ) ? trim( (string) parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' ) : '';
    $path_parts = array_values( array_filter( explode( '/', $request_uri ) ) );
    $last_part = ! empty( $path_parts ) ? end( $path_parts ) : '';

    if ( $last_part === 'boutique' || ( function_exists( 'is_shop' ) && is_shop() ) ) {
        return get_template_directory() . '/index.php';
    }
    return $template;
}, 999 );

/**
 * --------------------------------------------------------------------------
 * Support des Packs & Offres Combinées (-33% de remise immédiate)
 * --------------------------------------------------------------------------
 */
add_action( 'woocommerce_before_calculate_totals', 'sterimar_apply_pack_discount_wc', 20, 1 );
function sterimar_apply_pack_discount_wc( $cart ) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        return;
    }
    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) {
        return;
    }
    foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
        if ( ! empty( $cart_item['pack_discount'] ) ) {
            $discount_rate = floatval( $cart_item['pack_discount'] );
            $original_price = floatval( $cart_item['data']->get_regular_price() );
            if ( $original_price <= 0 ) {
                $original_price = floatval( $cart_item['data']->get_price() );
            }
            if ( $original_price > 0 && $discount_rate > 0 ) {
                $discounted_price = round( $original_price * ( 1 - $discount_rate ), 2 );
                $cart_item['data']->set_price( $discounted_price );
            }
        }
    }
}

add_filter( 'woocommerce_get_item_data', 'sterimar_display_pack_cart_item_meta', 10, 2 );
function sterimar_display_pack_cart_item_meta( $item_data, $cart_item ) {
    if ( ! empty( $cart_item['pack_name'] ) ) {
        $item_data[] = array(
            'key'   => __( 'Offre Pack Spéciale', 'woocommerce' ),
            'value' => esc_html( $cart_item['pack_name'] ) . ' (-33% appliqué)',
        );
    }
    return $item_data;
}

add_action( 'woocommerce_checkout_create_order_line_item', 'sterimar_save_pack_order_item_meta', 10, 4 );
function sterimar_save_pack_order_item_meta( $item, $cart_item_key, $values, $order ) {
    if ( ! empty( $values['pack_name'] ) ) {
        $item->add_meta_data( __( 'Offre Pack', 'woocommerce' ), $values['pack_name'] . ' (-33%)' );
    }
}
