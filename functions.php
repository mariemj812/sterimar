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
        wp_enqueue_style( 'sterimar-style', $theme_uri . '/style.min.css', array(), '38' );
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
    requestIdleCallback(function(){ setTimeout(loadPixel, 1000); }, {timeout: 4000});
} else {
    setTimeout(loadPixel, 1500);
}
// Scroll only: loading the pixel on click/touch would run fbevents.js during the interaction (hurts INP)
window.addEventListener('scroll', function(){ setTimeout(loadPixel, 300); }, {once:true, passive:true});
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

    $content_ids = array();
    $contents    = array();
    $num_items   = 0;

    foreach ( $order->get_items() as $item ) {
        $product_id = $item->get_product_id();
        $qty        = $item->get_quantity();
        $subtotal   = floatval( $item->get_total() );
        $price      = $qty > 0 ? round( $subtotal / $qty, 2 ) : 0;

        if ( $product_id > 0 ) {
            $id_str = (string) $product_id;
            if ( ! in_array( $id_str, $content_ids, true ) ) {
                $content_ids[] = $id_str;
                $content_ids[] = 'wc_post_id_' . $id_str;
            }
            $contents[] = array(
                'id'         => $id_str,
                'quantity'   => $qty,
                'item_price' => $price,
            );
            $num_items += $qty;
        }
    }

    if ( empty( $content_ids ) ) {
        $content_ids = array( '18', 'wc_post_id_18' );
        $contents    = array(
            array(
                'id'         => '18',
                'quantity'   => 1,
                'item_price' => floatval( $order->get_total() ),
            ),
        );
        $num_items   = 1;
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
        content_type: 'product',
        content_ids: <?php echo wp_json_encode( array_values( array_unique( $content_ids ) ) ); ?>,
        contents: <?php echo wp_json_encode( $contents ); ?>,
        value: <?php echo esc_js( floatval( $order->get_total() ) ); ?>,
        currency: '<?php echo esc_js( $order->get_currency() ?: 'TND' ); ?>',
        num_items: <?php echo intval( $num_items ); ?>
    });
}
</script>
    <?php
}
add_action( 'woocommerce_thankyou', 'sterimar_track_wc_purchase', 20 );

/**
 * Meta Product Catalog XML Feed (Facebook / Google Shopping RSS 2.0)
 * Accessible via https://sterimar.shop/?feed=meta-catalog
 */
function sterimar_render_meta_catalog_feed() {
    if ( ! isset( $_GET['feed'] ) || $_GET['feed'] !== 'meta-catalog' ) {
        return;
    }

    header( 'Content-Type: application/xml; charset=UTF-8' );
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    ?>
<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">
  <channel>
    <title>Stérimar Tunisie - Catalogue Officiel</title>
    <link>https://sterimar.shop/</link>
    <description>Catalogue officiel des produits Stérimar synchronisé avec Meta Commerce Manager</description>
    <?php
    $feed_products = array(
        array(
            'id'          => '18',
            'title'       => 'Stérimar Hygiène du Nez (100ml)',
            'description' => 'Spray nasal isotonique pour nettoyer, humidifier et protéger la muqueuse nasale au quotidien. Enrichi en oligo-éléments marins.',
            'link'        => 'https://sterimar.shop/produit-hygiene-du-nez/',
            'image'       => 'https://sterimar.shop/hygiene-adulte.webp',
            'price'       => '16.00 TND',
            'category'    => 'Santé et beauté > Soins de santé > Soins des voies respiratoires > Sprays nasaux',
        ),
        array(
            'id'          => '25',
            'title'       => 'Stérimar Nez sujet aux Rhumes (100ml)',
            'description' => 'Spray nasal préventif enrichi en soufre pour renforcer la résistance de la muqueuse nasale face aux agressions hivernales.',
            'link'        => 'https://sterimar.shop/produit-nez-sujet-aux-rhumes/',
            'image'       => 'https://sterimar.shop/cold-adulte.webp',
            'price'       => '16.00 TND',
            'category'    => 'Santé et beauté > Soins de santé > Soins des voies respiratoires > Sprays nasaux',
        ),
        array(
            'id'          => '36',
            'title'       => 'Stérimar Nez Bouché (100ml)',
            'description' => 'Spray hypertonique enrichi en cuivre pour décongestionner rapidement le nez bouché chez l\'adulte et l\'enfant dès 3 ans.',
            'link'        => 'https://sterimar.shop/produit-nez-bouche/',
            'image'       => 'https://sterimar.shop/nez-bouché-adulte.webp',
            'price'       => '16.00 TND',
            'category'    => 'Santé et beauté > Soins de santé > Soins des voies respiratoires > Sprays nasaux',
        ),
        array(
            'id'          => '35',
            'title'       => 'Stérimar Nez Allergique (100ml)',
            'description' => 'Spray enrichi en manganèse pour soulager les symptômes d\'allergie nasale : éternuements, nez qui coule, congestion. Élu Meilleur Produit Pharma.',
            'link'        => 'https://sterimar.shop/produit-nez-allergique/',
            'image'       => 'https://sterimar.shop/allergie.webp',
            'price'       => '16.00 TND',
            'category'    => 'Santé et beauté > Soins de santé > Soins des voies respiratoires > Sprays nasaux',
        ),
        array(
            'id'          => '32',
            'title'       => 'Stérimar Hygiène du Nez Bébé (100ml)',
            'description' => 'Spray nasal pour les petits de 0 à 3 ans destiné à nettoyer et hydrater le nez et éliminer les impuretés avec embout sécurité bébé.',
            'link'        => 'https://sterimar.shop/produit-hygiene-du-nez-bebe/',
            'image'       => 'https://sterimar.shop/hygiene-bébé.webp',
            'price'       => '16.00 TND',
            'category'    => 'Santé et beauté > Soins de santé > Soins des voies respiratoires > Sprays nasaux',
        ),
        array(
            'id'          => '34',
            'title'       => 'Stérimar Nez Bouché Bébé (100ml)',
            'description' => 'Spray hypertonique enrichi en cuivre pour décongestionner rapidement le nez de bébé dès 3 mois avec embout sécurité ergonomique.',
            'link'        => 'https://sterimar.shop/produit-nez-bouche-bebe/',
            'image'       => 'https://sterimar.shop/nez-bouché-bébé.webp',
            'price'       => '16.00 TND',
            'category'    => 'Santé et beauté > Soins de santé > Soins des voies respiratoires > Sprays nasaux',
        ),
    );

    foreach ( $feed_products as $p ) :
    ?>
    <item>
      <g:id><?php echo esc_html( $p['id'] ); ?></g:id>
      <g:title><?php echo esc_html( $p['title'] ); ?></g:title>
      <g:description><?php echo esc_html( $p['description'] ); ?></g:description>
      <g:link><?php echo esc_url( $p['link'] ); ?></g:link>
      <g:image_link><?php echo esc_url( $p['image'] ); ?></g:image_link>
      <g:brand>Stérimar</g:brand>
      <g:condition>new</g:condition>
      <g:availability>in stock</g:availability>
      <g:price><?php echo esc_html( $p['price'] ); ?></g:price>
      <g:google_product_category><?php echo esc_html( $p['category'] ); ?></g:google_product_category>
    </item>
    <?php endforeach; ?>
  </channel>
</rss>
    <?php
    exit;
}
add_action( 'init', 'sterimar_render_meta_catalog_feed' );

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

/**
 * Remove useless <head> tags (smaller HTML, fewer hints/requests).
 */
function sterimar_clean_wp_head() {
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'wp_generator' );
    remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
    remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );
    remove_action( 'template_redirect', 'rest_output_link_header', 11 );
}
add_action( 'init', 'sterimar_clean_wp_head' );

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
 * VALIDATION DE COMMANDE & LIVRAISON AUTOMATIQUE STÉRIMAR™
 * 
 * 1. FORMULAIRE ULTRA-SIMPLIFIÉ (STRICTEMENT 3 CHAMPS) :
 *    - Nom et Prénom (Obligatoire)
 *    - Adresse de livraison (Obligatoire - champ unique)
 *    - Numéro de téléphone (Obligatoire)
 *    -> Tous les autres champs sont supprimés (Société, adresse 2, ville, région, code postal, pays, e-mail, notes)
 * 
 * 2. LIVRAISON AUTOMATIQUE :
 *    - Ajout automatique de 7 DT
 *    - Gratuit si le montant de la commande (panier) est >= 100 DT
 * --------------------------------------------------------------------------
 */

// 1. Forcer l'option de téléphone requis en base WooCommerce
add_action( 'init', function() {
    if ( get_option( 'woocommerce_checkout_phone_field' ) !== 'required' ) {
        update_option( 'woocommerce_checkout_phone_field', 'required' );
    }
} );

// 2. Désactiver l'option d'expédition vers une adresse différente
add_filter( 'woocommerce_cart_needs_shipping_address', '__return_false', 9999 );
add_filter( 'woocommerce_ship_to_different_address_checked', '__return_false', 9999 );

// 3. Supprimer le champ Notes de commande (order_comments)
add_filter( 'woocommerce_enable_order_notes_field', '__return_false', 9999 );

// 4. Forcer le pays par défaut à la Tunisie (TN)
add_filter( 'default_checkout_billing_country', function() { return 'TN'; }, 9999 );
add_filter( 'default_checkout_shipping_country', function() { return 'TN'; }, 9999 );

// 5. Configuration des champs d'adresse par défaut (WooCommerce Core & Blocks Registry)
add_filter( 'woocommerce_default_address_fields', 'sterimar_configure_default_address_fields', 9999 );
function sterimar_configure_default_address_fields( $fields ) {
    // 1. Nom & Prénom (Obligatoire)
    if ( isset( $fields['first_name'] ) ) {
        $fields['first_name']['label']         = __( 'Nom et Prénom', 'woocommerce' );
        $fields['first_name']['placeholder']   = __( 'Votre nom et prénom (ex: Mohamed Trabelsi)', 'woocommerce' );
        $fields['first_name']['required']      = true;
        $fields['first_name']['class']         = array( 'form-row-wide' );
        $fields['first_name']['priority']      = 10;
    }

    // 2. Adresse unique (Obligatoire)
    if ( isset( $fields['address_1'] ) ) {
        $fields['address_1']['label']          = __( 'Adresse de livraison', 'woocommerce' );
        $fields['address_1']['placeholder']    = __( 'Rue, N°, quartier, ville...', 'woocommerce' );
        $fields['address_1']['required']       = true;
        $fields['address_1']['class']          = array( 'form-row-wide' );
        $fields['address_1']['priority']      = 20;
    }

    // 3. Téléphone (Obligatoire)
    if ( isset( $fields['phone'] ) ) {
        $fields['phone']['label']              = __( 'Numéro de téléphone', 'woocommerce' );
        $fields['phone']['placeholder']        = '';
        $fields['phone']['required']           = true;
        $fields['phone']['class']              = array( 'form-row-wide', 'validate-required', 'validate-phone' );
        $fields['phone']['priority']           = 30;
    }

    // Rendre tous les autres champs secondaires non obligatoires
    $unneeded_fields = array( 'email', 'last_name', 'company', 'address_2', 'city', 'state', 'postcode', 'country' );
    foreach ( $unneeded_fields as $key ) {
        if ( isset( $fields[ $key ] ) ) {
            $fields[ $key ]['required'] = false;
        }
    }

    return $fields;
}

// 6. Filtrer les champs de facturation (Classic Checkout + Store API) : Strictement les 3 champs
add_filter( 'woocommerce_billing_fields', 'sterimar_configure_billing_fields', 9999 );
function sterimar_configure_billing_fields( $fields ) {
    $clean_fields = array();

    // 1. Nom & Prénom (Obligatoire)
    $clean_fields['billing_first_name'] = array(
        'label'        => __( 'Nom et Prénom', 'woocommerce' ),
        'placeholder'  => __( 'Votre nom et prénom (ex: Mohamed Trabelsi)', 'woocommerce' ),
        'required'     => true,
        'class'        => array( 'form-row-wide', 'validate-required' ),
        'autocomplete' => 'name',
        'priority'     => 10,
        'type'         => 'text',
    );

    // 2. Adresse complète (Obligatoire - champ unique)
    $clean_fields['billing_address_1'] = array(
        'label'        => __( 'Adresse de livraison', 'woocommerce' ),
        'placeholder'  => __( 'Rue, N°, appartement, quartier, ville...', 'woocommerce' ),
        'required'     => true,
        'class'        => array( 'form-row-wide', 'validate-required' ),
        'autocomplete' => 'street-address',
        'priority'     => 20,
        'type'         => 'text',
    );

    // 3. Numéro de téléphone (Obligatoire)
    $clean_fields['billing_phone'] = array(
        'label'        => __( 'Numéro de téléphone', 'woocommerce' ),
        'placeholder'  => '',
        'required'     => true,
        'class'        => array( 'form-row-wide', 'validate-required', 'validate-phone' ),
        'autocomplete' => 'tel',
        'priority'     => 30,
        'type'         => 'tel',
    );

    return $clean_fields;
}

// 7. Filtrage global des champs au checkout
add_filter( 'woocommerce_checkout_fields', 'sterimar_configure_checkout_fields', 9999 );
function sterimar_configure_checkout_fields( $fields ) {
    // Appliquer nos 3 champs stricts pour la facturation
    $fields['billing'] = sterimar_configure_billing_fields( isset( $fields['billing'] ) ? $fields['billing'] : array() );

    // Supprimer tous les champs d'expédition séparée
    $fields['shipping'] = array();

    // Supprimer le champ notes de commande
    if ( isset( $fields['order']['order_comments'] ) ) {
        unset( $fields['order']['order_comments'] );
    }

    // Supprimer explicitement le champ email s'il subsiste
    if ( isset( $fields['billing']['billing_email'] ) ) {
        unset( $fields['billing']['billing_email'] );
    }

    return $fields;
}

// 8. Remplissage des données techniques requises par WooCommerce (pays, ville, séparation nom/prénom)
add_filter( 'woocommerce_checkout_posted_data', 'sterimar_prepare_checkout_posted_data', 9999 );
function sterimar_prepare_checkout_posted_data( $data ) {
    // 1. Nom et prénom
    if ( ! empty( $data['billing_first_name'] ) ) {
        $name_parts = explode( ' ', trim( $data['billing_first_name'] ), 2 );
        $first = $name_parts[0];
        $last  = isset( $name_parts[1] ) && ! empty( $name_parts[1] ) ? $name_parts[1] : $name_parts[0];
        $data['billing_first_name']  = $first;
        $data['billing_last_name']   = $last;
        $data['shipping_first_name'] = $first;
        $data['shipping_last_name']  = $last;
    } else {
        $data['billing_last_name']   = '.';
        $data['shipping_last_name']  = '.';
    }

    // 2. Adresse
    if ( ! empty( $data['billing_address_1'] ) ) {
        $data['shipping_address_1'] = $data['billing_address_1'];
    }

    // 3. Téléphone
    if ( ! empty( $data['billing_phone'] ) ) {
        $data['shipping_phone'] = $data['billing_phone'];
    }

    // 4. Email (Automatique - pas de saisie demandée au client)
    if ( empty( $data['billing_email'] ) ) {
        $data['billing_email'] = 'client@sterimar.shop';
    }
    $data['shipping_email'] = $data['billing_email'];

    // 5. Pays Tunisie (TN)
    $data['billing_country']  = 'TN';
    $data['shipping_country'] = 'TN';

    // 6. Ville déduite de l'adresse ou Tunisie
    if ( empty( $data['billing_city'] ) ) {
        $data['billing_city'] = 'Tunisie';
    }
    $data['shipping_city'] = $data['billing_city'];

    // 7. Code postal
    if ( empty( $data['billing_postcode'] ) ) {
        $data['billing_postcode'] = '1000';
    }
    $data['shipping_postcode'] = $data['billing_postcode'];

    return $data;
}

// 9. Synchronisation à la création de l'objet commande WC_Order
add_action( 'woocommerce_checkout_create_order', 'sterimar_finalize_order_customer_data', 10, 2 );
function sterimar_finalize_order_customer_data( $order, $data ) {
    $order->set_billing_country( 'TN' );
    $order->set_shipping_country( 'TN' );
    if ( ! $order->get_billing_city() ) {
        $order->set_billing_city( 'Tunisie' );
    }
    $order->set_shipping_city( $order->get_billing_city() );
    $order->set_shipping_first_name( $order->get_billing_first_name() );
    $order->set_shipping_last_name( $order->get_billing_last_name() );
    $order->set_shipping_address_1( $order->get_billing_address_1() );
    $order->set_shipping_phone( $order->get_billing_phone() );
    
    $email = $order->get_billing_email();
    if ( empty( $email ) ) {
        $email = 'client@sterimar.shop';
        $order->set_billing_email( $email );
    }
    $order->set_shipping_email( $email );
}

// 10. Validation serveur Classic Checkout (Nom, Adresse, Téléphone obligatoires ; Pas de saisie e-mail)
add_action( 'woocommerce_checkout_process', 'sterimar_validate_custom_checkout_fields' );
function sterimar_validate_custom_checkout_fields() {
    // 1. Nom & Prénom
    $first_name = isset( $_POST['billing_first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_first_name'] ) ) : '';
    if ( empty( trim( $first_name ) ) ) {
        wc_add_notice( __( 'Le <strong>Nom et Prénom</strong> est obligatoire pour valider la commande.', 'woocommerce' ), 'error' );
    }

    // 2. Adresse de livraison
    $address = isset( $_POST['billing_address_1'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_address_1'] ) ) : '';
    if ( empty( trim( $address ) ) ) {
        wc_add_notice( __( 'L\'<strong>adresse de livraison</strong> est obligatoire.', 'woocommerce' ), 'error' );
    }

    // 3. Téléphone
    $phone = isset( $_POST['billing_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_phone'] ) ) : '';
    if ( empty( trim( $phone ) ) ) {
        wc_add_notice( __( 'Le <strong>numéro de téléphone</strong> est obligatoire pour valider la commande et assurer la livraison.', 'woocommerce' ), 'error' );
    } else {
        $digits = preg_replace( '/\D/', '', $phone );
        if ( strlen( $digits ) < 8 ) {
            wc_add_notice( __( 'Veuillez saisir un <strong>numéro de téléphone valide</strong> (minimum 8 chiffres).', 'woocommerce' ), 'error' );
        }
    }
}

// 10b. Bypass toute erreur de validation WooCommerce core liée à l'email
add_action( 'woocommerce_after_checkout_validation', function( $data, $errors ) {
    if ( is_wp_error( $errors ) ) {
        $errors->remove( 'billing_email_required' );
        $errors->remove( 'billing_email' );
    }
}, 999, 2 );

// 11. Validation Store API (WooCommerce Blocks Checkout)
add_action( 'woocommerce_store_api_checkout_update_order_from_request', 'sterimar_validate_blocks_custom_checkout', 10, 2 );
function sterimar_validate_blocks_custom_checkout( $order, $request ) {
    $billing_address = $request->get_param( 'billing_address' );

    $phone   = '';
    $name    = '';
    $address = '';

    if ( is_array( $billing_address ) ) {
        $phone   = ! empty( $billing_address['phone'] ) ? $billing_address['phone'] : '';
        $name    = ! empty( $billing_address['first_name'] ) ? $billing_address['first_name'] : '';
        $address = ! empty( $billing_address['address_1'] ) ? $billing_address['address_1'] : '';
    }

    if ( empty( $phone ) && method_exists( $order, 'get_billing_phone' ) ) {
        $phone = $order->get_billing_phone();
    }
    if ( empty( $name ) && method_exists( $order, 'get_billing_first_name' ) ) {
        $name = $order->get_billing_first_name();
    }
    if ( empty( $address ) && method_exists( $order, 'get_billing_address_1' ) ) {
        $address = $order->get_billing_address_1();
    }

    if ( method_exists( $order, 'get_billing_email' ) && ! $order->get_billing_email() ) {
        $order->set_billing_email( 'client@sterimar.shop' );
    }

    $errors = array();
    if ( empty( trim( (string) $name ) ) ) {
        $errors[] = __( 'Le Nom et Prénom est obligatoire.', 'woocommerce' );
    }
    if ( empty( trim( (string) $address ) ) ) {
        $errors[] = __( 'L\'adresse de livraison est obligatoire.', 'woocommerce' );
    }
    if ( empty( trim( (string) $phone ) ) ) {
        $errors[] = __( 'Le numéro de téléphone est obligatoire pour assurer la livraison.', 'woocommerce' );
    }

    if ( ! empty( $errors ) ) {
        $msg = implode( ' ', $errors );
        if ( class_exists( '\Automattic\WooCommerce\StoreApi\Exceptions\RouteException' ) ) {
            throw new \Automattic\WooCommerce\StoreApi\Exceptions\RouteException(
                'woocommerce_rest_checkout_missing_fields',
                $msg,
                400
            );
        } elseif ( function_exists( 'wc_add_notice' ) ) {
            wc_add_notice( $msg, 'error' );
        }
    }
}

// 12. Paramètres transmis à React dans WooCommerce Blocks
add_filter( 'woocommerce_blocks_asset_api_script_data', 'sterimar_force_blocks_checkout_fields', 9999 );
function sterimar_force_blocks_checkout_fields( $data ) {
    if ( isset( $data['defaultFields'] ) ) {
        if ( isset( $data['defaultFields']['first_name'] ) ) {
            $data['defaultFields']['first_name']['label'] = __( 'Nom et Prénom', 'woocommerce' );
            $data['defaultFields']['first_name']['required'] = true;
        }
        if ( isset( $data['defaultFields']['address_1'] ) ) {
            $data['defaultFields']['address_1']['label'] = __( 'Adresse de livraison', 'woocommerce' );
            $data['defaultFields']['address_1']['required'] = true;
        }
        if ( isset( $data['defaultFields']['phone'] ) ) {
            $data['defaultFields']['phone']['label'] = __( 'Numéro de téléphone', 'woocommerce' );
            $data['defaultFields']['phone']['required'] = true;
        }
        foreach ( array( 'email', 'last_name', 'company', 'address_2', 'city', 'state', 'postcode', 'country' ) as $kf ) {
            if ( isset( $data['defaultFields'][ $kf ] ) ) {
                $data['defaultFields'][ $kf ]['required'] = false;
                $data['defaultFields'][ $kf ]['hidden'] = true;
            }
        }
    }
    return $data;
}

// 12b. Bypass WooCommerce Store API email validation (email is hidden)
add_filter( 'woocommerce_store_api_checkout_order_processed', function( $order ) {
    if ( method_exists( $order, 'get_billing_email' ) && ! $order->get_billing_email() ) {
        $order->set_billing_email( 'client@sterimar.shop' );
    }
}, 10, 1 );

// 12c. Intercept the checkout request in Store API to inject email BEFORE schema validation
add_filter( 'rest_pre_dispatch', function( $result, $server, $request ) {
    $path = $request->get_route();
    if ( stripos( $path, 'wc/store' ) !== false && stripos( $path, 'checkout' ) !== false ) {
        $billing = $request->get_param( 'billing_address' );
        if ( is_array( $billing ) ) {
            if ( empty( $billing['email'] ) ) {
                $billing['email'] = 'client@sterimar.shop';
                $request->set_param( 'billing_address', $billing );
            }
        }
    }
    return $result;
}, 10, 3 );

// 12d. If email is empty, set a placeholder so WooCommerce doesn't reject the order
add_filter( 'woocommerce_checkout_posted_data', function( $data ) {
    if ( empty( $data['billing_email'] ) ) {
        $data['billing_email'] = 'client@sterimar.shop';
    }
    return $data;
}, 99999 );

// 12e. Also intercept Store API billing address to inject placeholder email
add_action( 'woocommerce_store_api_checkout_update_order_from_request', function( $order, $request ) {
    $billing = $request->get_param( 'billing_address' );
    if ( ( is_array( $billing ) && empty( $billing['email'] ) ) || ( method_exists( $order, 'get_billing_email' ) && ! $order->get_billing_email() ) ) {
        $order->set_billing_email( 'client@sterimar.shop' );
    }
}, 5, 2 );

// 12f. Annuler instantanément l'envoi d'email au client fictif pour un temps de réponse immédiat (0 sec au lieu de 30s)
add_filter( 'pre_wp_mail', function( $null, $atts ) {
    $to = isset( $atts['to'] ) ? $atts['to'] : '';
    if ( is_array( $to ) ) {
        $to = implode( ',', $to );
    }
    if ( stripos( $to, 'client@sterimar' ) !== false && stripos( $to, 'commercial@' ) === false ) {
        return true; // Succès immédiat sans tentative de connexion SMTP/DNS
    }
    return null;
}, 10, 2 );

add_filter( 'woocommerce_email_recipient_customer_processing_order', 'sterimar_filter_dummy_email_recipient', 99, 2 );
add_filter( 'woocommerce_email_recipient_customer_completed_order', 'sterimar_filter_dummy_email_recipient', 99, 2 );
add_filter( 'woocommerce_email_recipient_customer_on_hold_order', 'sterimar_filter_dummy_email_recipient', 99, 2 );
function sterimar_filter_dummy_email_recipient( $recipient, $order ) {
    if ( is_string( $recipient ) && stripos( $recipient, 'client@sterimar' ) !== false ) {
        return '';
    }
    return $recipient;
}

/**
 * --------------------------------------------------------------------------
 * LIVRAISON AUTOMATIQUE : 7 DT (GRATUIT DÈS 100 DT)
 * --------------------------------------------------------------------------
 */

// A. Activer le calcul de livraison
add_filter( 'woocommerce_cart_needs_shipping', '__return_true', 9999 );

// B. Calcul automatique des tarifs de livraison
add_filter( 'woocommerce_package_rates', 'sterimar_auto_shipping_rates', 9999, 2 );
function sterimar_auto_shipping_rates( $rates, $package ) {
    $subtotal = 0;
    if ( ! empty( $package['contents'] ) ) {
        foreach ( $package['contents'] as $item ) {
            $subtotal += floatval( $item['line_total'] );
        }
    }
    if ( $subtotal <= 0 && WC()->cart ) {
        $subtotal = floatval( WC()->cart->get_subtotal() );
    }

    $is_free = ( $subtotal >= 100.0 );
    $cost    = $is_free ? 0.0 : 7.0;
    $label   = $is_free ? __( 'Livraison Gratuite (Dès 100 DT)', 'woocommerce' ) : __( 'Frais de livraison (7 DT)', 'woocommerce' );

    $filtered_rates = array();

    if ( ! empty( $rates ) ) {
        // Mettre à jour la première méthode existante et ne garder que celle-ci (automatique)
        foreach ( $rates as $rate_id => $rate ) {
            $rate->cost  = $cost;
            $rate->label = $label;
            if ( isset( $rate->taxes ) && is_array( $rate->taxes ) ) {
                foreach ( $rate->taxes as $tk => $tv ) {
                    $rate->taxes[ $tk ] = 0;
                }
            }
            $filtered_rates[ $rate_id ] = $rate;
            break;
        }
    } else {
        // Si aucune méthode n'est configurée dans les zones, injecter le tarif dynamique
        $method_id   = $is_free ? 'sterimar_free_shipping' : 'sterimar_flat_shipping';
        $custom_rate = new WC_Shipping_Rate(
            $method_id,
            $label,
            $cost,
            array(),
            'flat_rate'
        );
        $filtered_rates[ $method_id ] = $custom_rate;
    }

    return $filtered_rates;
}

// C. Sélectionner automatiquement la méthode calculée
// Hide payment methods section from checkout UI
remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
// Add inline CSS to hide shipping, payment method blocks and email fields (still calculated in backend)
add_action( 'wp_enqueue_scripts', function() {
    if ( is_checkout() ) {
        $custom_css = "\n            #billing_email_field,\n            .woocommerce form .form-row#billing_email_field,\n            .wc-block-checkout__contact-fields,\n            .wc-block-components-address-form__email,\n            .wc-block-components-text-input:has(input[type='email']),\n            .wc-block-components-text-input:has(input#email),\n            .woocommerce-checkout .woocommerce-shipping-methods,\n            .woocommerce-checkout .woocommerce-payment-methods,\n            .wc-block-checkout__shipping-option,\n            .wc-block-checkout__payment-method,\n            .wp-block-woocommerce-checkout-shipping-methods-block,\n            .wp-block-woocommerce-checkout-payment-block,\n            .wc-block-components-shipping-rates-control,\n            .wc-block-components-payment-method-label,\n            .wc-block-components-payment-method-icons,\n            .wc-block-components-radio-control--highlight-checked,\n            .wc-block-components-product-metadata__description,\n            .wc-block-components-order-summary-item__description .wc-block-components-product-metadata__description,\n            .wc-block-components-product-metadata .wc-block-components-product-metadata__description,\n            .woocommerce-checkout .wc-block-components-product-metadata__description,\n            .woocommerce-checkout .product-description,\n            .woocommerce-checkout .product-short-description {\n                display: none !important;\n                visibility: hidden !important;\n                height: 0 !important;\n                margin: 0 !important;\n                padding: 0 !important;\n                border: none !important;\n            }\n        ";
        wp_add_inline_style( 'sterimar-style', $custom_css );
    }
}, 101 );
add_filter( 'woocommerce_shipping_chosen_method', function( $default, $rates ) {
    if ( ! empty( $rates ) ) {
        $keys = array_keys( $rates );
        return reset( $keys );
    }
    return $default;
}, 9999, 2 );

// D. Filet de sécurité via les frais de panier (si le module shipping WooCommerce est inactif)
add_action( 'woocommerce_cart_calculate_fees', 'sterimar_fallback_shipping_fee', 9999, 1 );
function sterimar_fallback_shipping_fee( $cart ) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        return;
    }

    $subtotal = floatval( $cart->get_subtotal() );
    if ( $subtotal <= 0 || $subtotal >= 100.0 ) {
        return;
    }

    if ( floatval( $cart->get_shipping_total() ) > 0 ) {
        return;
    }

    $has_rate = false;
    if ( WC()->shipping ) {
        $packages = WC()->shipping->get_packages();
        if ( ! empty( $packages ) ) {
            foreach ( $packages as $p ) {
                if ( ! empty( $p['rates'] ) ) {
                    $has_rate = true;
                    break;
                }
            }
        }
    }

    if ( ! $has_rate ) {
        $cart->add_fee( __( 'Frais de livraison', 'woocommerce' ), 7.00, false );
    }
}




// 13. Script client universel (Classic + Blocks MutationObserver)
function sterimar_checkout_client_script() {
    if ( ( function_exists( 'is_checkout' ) && is_checkout() ) || ( function_exists( 'is_cart' ) && is_cart() ) ) {
        ?>
        <script>
        (function() {
            // Helper pour injecter la valeur dans le state React (évite blocage de validation React)
            function setReactValue(input, val) {
                if (!input) return;
                try {
                    var proto = window.HTMLInputElement.prototype;
                    var desc = Object.getOwnPropertyDescriptor(proto, 'value');
                    if (desc && desc.set) {
                        desc.set.call(input, val);
                    } else {
                        input.value = val;
                    }
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                } catch(e) {
                    input.value = val;
                }
            }

            function enforceCheckoutFields() {
                // 1. Nom & Prénom (Obligatoire)
                var nameInputs = document.querySelectorAll('input#billing_first_name, input[name="billing_first_name"], input#billing-first_name, input#shipping-first_name, input[autocomplete="name"], input[autocomplete="given-name"]');
                nameInputs.forEach(function(nameInput) {
                    nameInput.required = true;
                    nameInput.setAttribute('required', 'required');
                    if (!nameInput.placeholder) nameInput.placeholder = 'Votre nom et prénom (ex: Mohamed Trabelsi)';
                    var pName = nameInput.closest('.form-row') || nameInput.closest('.wc-block-components-text-input') || nameInput.parentElement;
                    if (pName) {
                        var lblName = pName.querySelector('label');
                        if (lblName && !lblName.querySelector('.sterimar-req-star')) {
                            lblName.innerHTML = 'Nom et Prénom <span class="sterimar-req-star" style="color:#dc2626; font-weight:bold;">*</span>';
                        }
                    }
                });

                // 2. Adresse de livraison (Obligatoire)
                var addrInputs = document.querySelectorAll('input#billing_address_1, input[name="billing_address_1"], input#billing-address_1, input#shipping-address_1, input[autocomplete="address-line1"], input[autocomplete="street-address"]');
                addrInputs.forEach(function(addrInput) {
                    addrInput.required = true;
                    addrInput.setAttribute('required', 'required');
                    if (!addrInput.placeholder) addrInput.placeholder = 'Rue, N°, appartement, quartier, ville...';
                    var pAddr = addrInput.closest('.form-row') || addrInput.closest('.wc-block-components-text-input') || addrInput.parentElement;
                    if (pAddr) {
                        var lblAddr = pAddr.querySelector('label');
                        if (lblAddr && !lblAddr.querySelector('.sterimar-req-star')) {
                            lblAddr.innerHTML = 'Adresse de livraison <span class="sterimar-req-star" style="color:#dc2626; font-weight:bold;">*</span>';
                        }
                    }
                });

                // 3. Téléphone (Obligatoire)
                var phoneInputs = document.querySelectorAll('input[type="tel"], input[autocomplete="tel"], input#phone, input#billing_phone, input#shipping-phone');
                phoneInputs.forEach(function(input) {
                    input.required = true;
                    input.setAttribute('required', 'required');
                    input.setAttribute('aria-required', 'true');
                    input.placeholder = '';
                    
                    var parentField = input.closest('.wc-block-components-text-input') || input.closest('.form-row') || input.parentElement;
                    if (parentField) {
                        var optionals = parentField.querySelectorAll('.optional, .wc-block-components-address-form__optional, .wc-block-components-form-field-optional');
                        optionals.forEach(function(el) { el.style.display = 'none'; });
                        
                        var label = parentField.querySelector('label');
                        if (label && !label.querySelector('.sterimar-phone-star')) {
                            label.innerHTML = 'Numéro de téléphone <span class="sterimar-phone-star" style="color:#dc2626; font-weight:bold;">*</span>';
                        }
                    }
                });

                // 4. Email (Masqué et prérempli dans React avec adresse valide instantanée)
                var emailInputs = document.querySelectorAll('input#billing_email, input[name="billing_email"], input[type="email"], input#email, input#billing-email');
                emailInputs.forEach(function(emailInput) {
                    emailInput.required = false;
                    emailInput.removeAttribute('required');
                    emailInput.removeAttribute('aria-required');
                    if (emailInput.value !== 'client@sterimar.shop') {
                        setReactValue(emailInput, 'client@sterimar.shop');
                    }
                    var pEmail = emailInput.closest('.form-row') || emailInput.closest('.wc-block-components-text-input') || emailInput.closest('.wc-block-checkout__contact-fields') || emailInput.parentElement;
                    if (pEmail) {
                        pEmail.style.setProperty('display', 'none', 'important');
                        pEmail.style.setProperty('visibility', 'hidden', 'important');
                        pEmail.style.setProperty('height', '0', 'important');
                    }
                });

                // 5. Cacher tous les autres champs superflus + Email
                var fieldsToHideSelectors = [
                    '#billing_email_field', '.woocommerce form .form-row#billing_email_field',
                    '.wc-block-checkout__contact-fields', '.wc-block-components-address-form__email',
                    '#billing_last_name_field', '#billing_company_field', '#billing_country_field',
                    '#billing_address_2_field', '#billing_city_field', '#billing_state_field',
                    '#billing_postcode_field', '.woocommerce-shipping-fields', '.woocommerce-additional-fields',
                    '#order_comments_field', '.wc-block-components-address-form__company',
                    '.wc-block-components-address-form__address_2', '.wc-block-components-address-form__city',
                    '.wc-block-components-address-form__state', '.wc-block-components-address-form__postcode',
                    '.wc-block-components-country-input', '.wc-block-checkout__shipping-address',
                    '.wc-block-checkout__use-address-for-billing', '.wc-block-checkout__add-note',
                    '.wc-block-components-order-notes',
                    '.wc-block-components-product-metadata__description',
                    '.wc-block-components-order-summary-item__description .wc-block-components-product-metadata__description',
                    '.woocommerce-checkout .product-short-description',
                    '.woocommerce-checkout .product-description',
                    // Hide shipping and payment method sections
                    '.shipping_method', '#shipping_method', '.woocommerce-shipping-totals',
                    '.payment_method', '#payment', '.wc-payment-method', '.payment_methods',
                    '.wc-block-checkout__shipping-option', '.wc-block-checkout__payment-method',
                    '.wp-block-woocommerce-checkout-shipping-methods-block',
                    '.wp-block-woocommerce-checkout-payment-block',
                    '.wc-block-components-shipping-rates-control',
                    '.wc-block-components-payment-method-label',
                    '.wc-block-components-payment-method-icons'
                ];
                fieldsToHideSelectors.forEach(function(sel) {
                    document.querySelectorAll(sel).forEach(function(el) {
                        el.style.setProperty('display', 'none', 'important');
                        el.style.setProperty('visibility', 'hidden', 'important');
                        el.style.setProperty('height', '0', 'important');
                    });
                });

                // Remplir les valeurs par défaut pour les champs cachés
                var cityInputs = document.querySelectorAll('input#billing_city, input[name="billing_city"], [id*="billing-city"] input, input#billing-city');
                cityInputs.forEach(function(ci) { if (!ci.value) setReactValue(ci, 'Tunisie'); });
                var postInputs = document.querySelectorAll('input#billing_postcode, input[name="billing_postcode"], [id*="billing-postcode"] input, input#billing-postcode');
                postInputs.forEach(function(pi) { if (!pi.value) setReactValue(pi, '1000'); });
                var lastInputs = document.querySelectorAll('input#billing_last_name, input[name="billing_last_name"], [id*="billing-last_name"] input, input#billing-last_name');
                lastInputs.forEach(function(li) { if (!li.value) setReactValue(li, '.'); });
            }

            // Exécution immédiate
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', enforceCheckoutFields);
            } else {
                enforceCheckoutFields();
            }

            // Écoute des mises à jour AJAX classiques et React Blocks
            document.body.addEventListener('updated_checkout', enforceCheckoutFields);

            // MutationObserver pour observer les rendus dynamiques React de WooCommerce Blocks
            if (window.MutationObserver) {
                var observer = new MutationObserver(function() {
                    enforceCheckoutFields();
                });
                observer.observe(document.body, { childList: true, subtree: true });
            }

            // Interception avant soumission pour validation sans alert() bloquante et préremplissage
            document.addEventListener('click', function(e) {
                var submitBtn = e.target.closest('.wc-block-components-checkout-place-order-button, #place_order, button[type="submit"]');
                if (submitBtn) {
                    // Préremplir l'email dans React immédiatement avant soumission
                    var emailInputs = document.querySelectorAll('input#billing_email, input[name="billing_email"], input[type="email"], input#email, input#billing-email');
                    emailInputs.forEach(function(em) {
                        if (!em.value || em.value !== 'client@sterimar.shop') {
                            setReactValue(em, 'client@sterimar.shop');
                        }
                    });

                    // 1. Vérifier Nom & Prénom
                    var nameInput = document.querySelector('input#billing_first_name, input[name="billing_first_name"], input#billing-first_name, input#shipping-first_name, input[autocomplete="name"], input[autocomplete="given-name"]');
                    if (nameInput && !nameInput.value.trim()) {
                        e.preventDefault();
                        nameInput.focus();
                        nameInput.style.borderColor = '#dc2626';
                        nameInput.style.boxShadow = '0 0 0 3px rgba(220, 38, 38, 0.2)';
                        nameInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        return;
                    }

                    // 2. Vérifier Adresse
                    var addrInput = document.querySelector('input#billing_address_1, input[name="billing_address_1"], input#billing-address_1, input#shipping-address_1, input[autocomplete="address-line1"], input[autocomplete="street-address"]');
                    if (addrInput && !addrInput.value.trim()) {
                        e.preventDefault();
                        addrInput.focus();
                        addrInput.style.borderColor = '#dc2626';
                        addrInput.style.boxShadow = '0 0 0 3px rgba(220, 38, 38, 0.2)';
                        addrInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        return;
                    }

                    // 3. Vérifier Téléphone
                    var phoneInputs = document.querySelectorAll('input[type="tel"], input[autocomplete="tel"], input#phone, input#billing_phone, input#billing-phone, input#shipping-phone');
                    var emptyPhone = null;
                    phoneInputs.forEach(function(input) {
                        if (!emptyPhone && input.offsetParent !== null && !input.value.trim()) {
                            emptyPhone = input;
                        }
                    });
                    if (emptyPhone) {
                        e.preventDefault();
                        emptyPhone.focus();
                        emptyPhone.style.borderColor = '#dc2626';
                        emptyPhone.style.boxShadow = '0 0 0 3px rgba(220, 38, 38, 0.2)';
                        emptyPhone.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        return;
                    }

                    // Remplir le nom de famille si caché
                    var lastNameInput = document.querySelector('input#billing_last_name, input[name="billing_last_name"], input#billing-last_name');
                    if (lastNameInput && !lastNameInput.value) {
                        if (nameInput && nameInput.value.trim()) {
                            var parts = nameInput.value.trim().split(' ');
                            setReactValue(lastNameInput, parts.length > 1 ? parts.slice(1).join(' ') : parts[0]);
                        } else {
                            setReactValue(lastNameInput, '.');
                        }
                    }
                }
            });
        })();
        </script>
        <?php
    }
}
add_action( 'wp_footer', 'sterimar_checkout_client_script', 99 );

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
 * Support des Prix Panier & Packs Stérimar™
 * --------------------------------------------------------------------------
 * Applique strictement et fidèlement les prix unitaires calculés par le panier.
 * La page validation des commandes affiche uniquement ces prix sans recalcul
 * ni réductions supplémentaires.
 */
add_action( 'woocommerce_before_calculate_totals', 'sterimar_apply_exact_cart_prices', 9999, 1 );
function sterimar_apply_exact_cart_prices( $cart ) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        return;
    }
    foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
        if ( isset( $cart_item['exact_price'] ) && floatval( $cart_item['exact_price'] ) > 0 ) {
            $price = floatval( $cart_item['exact_price'] );
            $cart_item['data']->set_price( $price );
            $cart_item['data']->set_regular_price( $price );
            $cart_item['data']->set_sale_price( '' );
        }
    }
}

add_filter( 'woocommerce_get_item_data', 'sterimar_display_pack_cart_item_meta', 10, 2 );
function sterimar_display_pack_cart_item_meta( $item_data, $cart_item ) {
    if ( ! empty( $cart_item['pack_name'] ) ) {
        $item_data[] = array(
            'key'   => __( 'Offre Pack', 'woocommerce' ),
            'value' => esc_html( $cart_item['pack_name'] ),
        );
    }
    return $item_data;
}

add_action( 'woocommerce_checkout_create_order_line_item', 'sterimar_save_pack_order_item_meta', 10, 4 );
function sterimar_save_pack_order_item_meta( $item, $cart_item_key, $values, $order ) {
    if ( ! empty( $values['pack_name'] ) ) {
        $item->add_meta_data( __( 'Offre Pack', 'woocommerce' ), $values['pack_name'] );
    }
}

/**
 * Supprimer l'extrait / description en gris des produits dans le récapitulatif Store API / WooCommerce Blocks
 */
add_filter( 'woocommerce_store_api_cart_item_response', function( $response, $item ) {
    if ( is_array( $response ) ) {
        $response['description'] = '';
        $response['short_description'] = '';
    }
    return $response;
}, 9999, 2 );

add_filter( 'woocommerce_product_get_description', function( $desc ) {
    if ( ( function_exists( 'is_checkout' ) && is_checkout() ) || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
        return '';
    }
    return $desc;
}, 9999 );

add_filter( 'woocommerce_product_get_short_description', function( $desc ) {
    if ( ( function_exists( 'is_checkout' ) && is_checkout() ) || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
        return '';
    }
    return $desc;
}, 9999 );


