<?php
/**
 * Stérimar Theme - Main Template
 *
 * Automatically handles static HTML templates and resolves assets within WordPress.
 *
 * @package Sterimar
 */

// 0. Handle Contact Form Submission (Server-side sending to commercial@sterimar.shop)
if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['sterimar_contact'] ) ) {
    status_header( 200 );
    header( 'Content-Type: application/json; charset=UTF-8' );
    
    $name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
    $email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
    $phone   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
    $subject = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : 'Contact Stérimar';
    $message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
    
    $to = 'commercial@sterimar.shop';
    $email_subject = '[Contact Stérimar] ' . $subject . ' - ' . $name;
    $email_body = "Nouveau message de contact reçu depuis le site Stérimar™ :\n\n";
    $email_body .= "Nom & Prénom : " . $name . "\n";
    $email_body .= "Adresse E-mail : " . $email . "\n";
    $email_body .= "Téléphone : " . ( $phone ? $phone : 'Non renseigné' ) . "\n";
    $email_body .= "Objet : " . $subject . "\n\n";
    $email_body .= "Message :\n" . $message . "\n";
    
    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'From: Stérimar Contact <commercial@sterimar.shop>',
    );
    if ( ! empty( $email ) ) {
        $headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
    }
    
    $sent = false;
    if ( function_exists( 'wp_mail' ) ) {
        $sent = wp_mail( $to, $email_subject, $email_body, $headers );
    } else {
        $sent = @mail( $to, $email_subject, $email_body, implode( "\r\n", $headers ) );
    }
    
    echo json_encode( array( 'success' => (bool) $sent ) );
    exit;
}

// 0.05 Handle Express One-Page Order Submission (Landing Page COD)
if ( ( $_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'OPTIONS' ) && isset( $_REQUEST['sterimar_express_order'] ) ) {
    header( 'Access-Control-Allow-Origin: *' );
    header( 'Access-Control-Allow-Methods: POST, OPTIONS' );
    header( 'Access-Control-Allow-Headers: Content-Type' );
    if ( $_SERVER['REQUEST_METHOD'] === 'OPTIONS' ) {
        status_header( 200 );
        exit;
    }
    status_header( 200 );
    header( 'Content-Type: application/json; charset=UTF-8' );
    
    $name        = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
    $phone       = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
    $governorate = isset( $_POST['governorate'] ) ? sanitize_text_field( wp_unslash( $_POST['governorate'] ) ) : '';
    $address     = isset( $_POST['address'] ) ? sanitize_textarea_field( wp_unslash( $_POST['address'] ) ) : '';
    $pack_id     = isset( $_POST['pack_id'] ) ? intval( $_POST['pack_id'] ) : 8;
    $pack_qty    = isset( $_POST['pack_qty'] ) ? max( 1, intval( $_POST['pack_qty'] ) ) : 1;
    $notes       = isset( $_POST['notes'] ) ? sanitize_textarea_field( wp_unslash( $_POST['notes'] ) ) : '';
    
    if ( empty( $name ) || empty( $phone ) ) {
        echo json_encode( array( 'success' => false, 'message' => 'Veuillez renseigner votre nom et votre numéro de téléphone.' ) );
        exit;
    }
    
    $packs_info = array(
        7 => array(
            'name'     => 'Pack Famille (Adulte + Bébé)',
            'price'    => 28.80,
            'shipping' => 7.00,
            'wc_items' => array( array( 'id' => 18, 'qty' => 1 ), array( 'id' => 32, 'qty' => 1 ) ),
        ),
        8 => array(
            'name'     => 'Pack Hiver Serein (Trio 3-en-1)',
            'price'    => 43.20,
            'shipping' => 7.00,
            'wc_items' => array( array( 'id' => 18, 'qty' => 1 ), array( 'id' => 25, 'qty' => 1 ), array( 'id' => 34, 'qty' => 1 ) ),
        ),
        9 => array(
            'name'     => 'Pack Ultime 5 Sprays (Famille + Hiver)',
            'price'    => 72.00,
            'shipping' => 0.00,
            'wc_items' => array( array( 'id' => 18, 'qty' => 2 ), array( 'id' => 25, 'qty' => 1 ), array( 'id' => 32, 'qty' => 1 ), array( 'id' => 34, 'qty' => 1 ) ),
        ),
    );
    
    $selected_pack = isset( $packs_info[ $pack_id ] ) ? $packs_info[ $pack_id ] : $packs_info[8];
    $unit_price    = $selected_pack['price'];
    $shipping_fee  = ( ( $unit_price * $pack_qty ) >= 100 || $pack_id == 9 ) ? 0.00 : $selected_pack['shipping'];
    $total_amount  = ( $unit_price * $pack_qty ) + $shipping_fee;
    
    $order_id = 0;
    
    if ( function_exists( 'wc_create_order' ) ) {
        try {
            $order = wc_create_order();
            
            foreach ( $selected_pack['wc_items'] as $item ) {
                $product_obj = wc_get_product( $item['id'] );
                if ( $product_obj ) {
                    $order->add_product( $product_obj, $item['qty'] * $pack_qty, array(
                        'subtotal' => ( $unit_price / count( $selected_pack['wc_items'] ) ) * $item['qty'] * $pack_qty,
                        'total'    => ( $unit_price / count( $selected_pack['wc_items'] ) ) * $item['qty'] * $pack_qty,
                    ) );
                }
            }
            
            if ( $shipping_fee > 0 ) {
                $shipping_item = new WC_Order_Item_Shipping();
                $shipping_item->set_method_title( 'Livraison Standard Tunisie (24-48h)' );
                $shipping_item->set_total( $shipping_fee );
                $order->add_item( $shipping_item );
            }
            
            $clean_phone = preg_replace( '/\D/', '', $phone );
            $address_data = array(
                'first_name' => $name,
                'last_name'  => '',
                'phone'      => $phone,
                'email'      => 'client-' . $clean_phone . '@sterimar.shop',
                'address_1'  => $address,
                'city'       => $governorate,
                'state'      => $governorate,
                'postcode'   => '1000',
                'country'    => 'TN',
            );
            
            $order->set_address( $address_data, 'billing' );
            $order->set_address( $address_data, 'shipping' );
            $order->set_payment_method( 'cod' );
            $order->set_payment_method_title( 'Paiement à la livraison (Espèces)' );
            
            if ( ! empty( $notes ) ) {
                $order->add_order_note( 'Note client : ' . $notes );
            }
            $order->add_order_note( 'Commande express générée depuis la Landing Page (' . $selected_pack['name'] . ')' );
            
            $order->calculate_totals();
            $order->update_status( 'processing', 'Commande express confirmée via Landing Page.' );
            $order_id = $order->get_id();
        } catch ( Exception $e ) {
            error_log( 'Erreur création commande WC express : ' . $e->getMessage() );
        }
    }
    
    // Notification Email
    $to = 'commercial@sterimar.shop';
    $subject = 'Nouvelle Commande Express #' . ( $order_id ? $order_id : 'WEB' ) . ' - ' . $name . ' (' . $selected_pack['name'] . ')';
    $body = "Nouvelle commande express reçue depuis la Landing Page Stérimar™ :\n\n";
    $body .= "Numéro Commande : #" . ( $order_id ? $order_id : 'N/A' ) . "\n";
    $body .= "Client : " . $name . "\n";
    $body .= "Téléphone : " . $phone . "\n";
    $body .= "Gouvernorat : " . $governorate . "\n";
    $body .= "Adresse complète : " . $address . "\n";
    $body .= "Produit commandé : " . $selected_pack['name'] . " (Qté: " . $pack_qty . ")\n";
    $body .= "Frais de livraison : " . ( $shipping_fee == 0 ? "GRATUITE (0,00 DT)" : number_format( $shipping_fee, 2 ) . " DT" ) . "\n";
    $body .= "Total à encaisser : " . number_format( $total_amount, 2 ) . " DT (Paiement à la livraison)\n";
    if ( ! empty( $notes ) ) {
        $body .= "Remarques : " . $notes . "\n";
    }
    
    $headers = array( 'Content-Type: text/plain; charset=UTF-8', 'From: Stérimar Express <commercial@sterimar.shop>' );
    if ( function_exists( 'wp_mail' ) ) {
        wp_mail( $to, $subject, $body, $headers );
    } else {
        @mail( $to, $subject, $body, implode( "\r\n", $headers ) );
    }
    
    echo json_encode( array(
        'success'      => true,
        'order_id'     => $order_id ? $order_id : time(),
        'pack_name'    => $selected_pack['name'],
        'total'        => number_format( $total_amount, 2 ) . ' DT',
        'customer'     => $name,
        'phone'        => $phone,
    ) );
    exit;
}

// 0.1 Handle WooCommerce Cart Sync & Checkout Redirect
if ( isset( $_POST['sterimar_wc_checkout'] ) || isset( $_GET['sterimar_wc_checkout'] ) ) {
    status_header( 200 );
    header( 'Content-Type: application/json; charset=UTF-8' );
    
    $cart_json = isset( $_POST['cart'] ) ? wp_unslash( $_POST['cart'] ) : ( isset( $_GET['cart'] ) ? wp_unslash( $_GET['cart'] ) : '' );
    $cart_data = json_decode( $cart_json, true );
    
    if ( function_exists( 'WC' ) && ! empty( $cart_data ) && is_array( $cart_data ) ) {
        // Initialize WooCommerce Session handler if needed
        if ( is_null( WC()->session ) ) {
            $session_class = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );
            WC()->session = new $session_class();
            WC()->session->init();
        }

        // Set customer session cookie
        if ( ! WC()->session->has_session() ) {
            WC()->session->set_customer_session_cookie( true );
        }

        // Initialize Customer and Cart
        if ( is_null( WC()->customer ) ) {
            WC()->customer = new WC_Customer( get_current_user_id(), true );
        }

        if ( is_null( WC()->cart ) ) {
            wc_load_cart();
        }
        
        WC()->cart->empty_cart();
        
        // Exact WooCommerce Product IDs provided by Store Admin
        $wc_id_map = array(
            'Hygiène du Nez'        => 18,
            'Nez sujet aux Rhumes'  => 25,
            'Hygiène du Nez Bébé'   => 32,
            'Nez Bouché Bébé'       => 34,
            'Nez Allergique'        => 35,
            'Nez Bouché'            => 36,
            'Stop & Protect Rhume'  => 25,
            0                       => 18,
            1                       => 25,
            2                       => 36,
            3                       => 35,
            4                       => 32,
            5                       => 34,
            6                       => 25,
        );

        $pack_definitions = array(
            7 => array(
                'name'     => 'Pack Famille',
                'discount' => 0.33,
                'items'    => array(
                    array( 'id' => 18, 'name' => 'Hygiène du Nez', 'qty' => 1 ),
                    array( 'id' => 32, 'name' => 'Hygiène du Nez Bébé', 'qty' => 1 ),
                ),
            ),
            8 => array(
                'name'     => 'Pack Hiver Serein',
                'discount' => 0.33,
                'items'    => array(
                    array( 'id' => 18, 'name' => 'Hygiène du Nez', 'qty' => 1 ),
                    array( 'id' => 25, 'name' => 'Nez sujet aux Rhumes', 'qty' => 1 ),
                    array( 'id' => 34, 'name' => 'Nez Bouché Bébé', 'qty' => 1 ),
                ),
            ),
        );

        $added_count = 0;
        foreach ( $cart_data as $item ) {
            $item_id = isset( $item['id'] ) ? $item['id'] : null;
            $item_name = ! empty( $item['name'] ) ? $item['name'] : '';
            $qty = isset( $item['quantity'] ) ? max( 1, intval( $item['quantity'] ) ) : 1;

            // Check if this item is a recognized Pack
            $matched_pack = null;
            if ( isset( $pack_definitions[ $item_id ] ) ) {
                $matched_pack = $pack_definitions[ $item_id ];
            } elseif ( stripos( $item_name, 'Pack Famille' ) !== false ) {
                $matched_pack = $pack_definitions[7];
            } elseif ( stripos( $item_name, 'Hiver Serein' ) !== false ) {
                $matched_pack = $pack_definitions[8];
            }

            if ( $matched_pack ) {
                $pack_total_price = isset( $item['price'] ) && floatval( $item['price'] ) > 0 
                    ? floatval( $item['price'] ) 
                    : ( $item_id == 7 ? 28.80 : 43.20 );
                $items_count = count( $matched_pack['items'] );
                $unit_price  = round( $pack_total_price / $items_count, 2 );

                foreach ( $matched_pack['items'] as $bundle_item ) {
                    $sub_wc_id   = $bundle_item['id'];
                    $sub_qty     = $bundle_item['qty'] * $qty;
                    $custom_data = array(
                        'exact_price' => $unit_price,
                        'pack_name'   => $matched_pack['name'],
                    );
                    $cart_item_key = WC()->cart->add_to_cart( $sub_wc_id, $sub_qty, 0, array(), $custom_data );
                    if ( $cart_item_key ) {
                        $added_count++;
                    }
                }
                continue;
            }

            $wc_product_id = 0;
            
            // 1. Direct wcId if passed from frontend
            if ( ! empty( $item['wcId'] ) && intval( $item['wcId'] ) > 0 ) {
                $wc_product_id = intval( $item['wcId'] );
            }
            
            // 2. Map by product numerical index
            if ( ! $wc_product_id && isset( $item['id'] ) && isset( $wc_id_map[ $item['id'] ] ) ) {
                $wc_product_id = $wc_id_map[ $item['id'] ];
            }
            
            // 3. Map by exact product name
            if ( ! $wc_product_id && ! empty( $item['name'] ) && isset( $wc_id_map[ $item['name'] ] ) ) {
                $wc_product_id = $wc_id_map[ $item['name'] ];
            }
            
            // 4. Fallback search by title
            if ( ! $wc_product_id && ! empty( $item['name'] ) ) {
                $product_name = sanitize_text_field( $item['name'] );
                $found_product = get_page_by_title( $product_name, OBJECT, 'product' );
                if ( $found_product ) {
                    $wc_product_id = $found_product->ID;
                } else {
                    $args = array(
                        'post_type'      => 'product',
                        'posts_per_page' => 1,
                        's'              => $product_name,
                        'post_status'    => 'publish',
                    );
                    $query = new WP_Query( $args );
                    if ( $query->have_posts() ) {
                        $wc_product_id = $query->posts[0]->ID;
                    }
                }
            }
            
            $qty = isset( $item['quantity'] ) ? max( 1, intval( $item['quantity'] ) ) : 1;
            $unit_price = isset( $item['price'] ) && floatval( $item['price'] ) > 0 
                ? floatval( $item['price'] ) 
                : 16.00;
            $custom_data = array(
                'exact_price' => $unit_price,
            );
            
            if ( $wc_product_id > 0 ) {
                $cart_item_key = WC()->cart->add_to_cart( $wc_product_id, $qty, 0, array(), $custom_data );
                if ( $cart_item_key ) {
                    $added_count++;
                }
            }
        }
        
        // Recalculate totals and persist to WooCommerce session & database
        WC()->cart->calculate_totals();
        WC()->cart->set_session();
        WC()->cart->maybe_set_cart_cookies();
        
        if ( is_object( WC()->session ) && method_exists( WC()->session, 'save_data' ) ) {
            WC()->session->save_data();
        }
        
        $checkout_url = wc_get_checkout_url();
        echo json_encode( array(
            'success'      => true,
            'woocommerce'  => true,
            'added_count'  => $added_count,
            'cart_count'   => WC()->cart->get_cart_contents_count(),
            'checkout_url' => $checkout_url,
        ) );
        exit;
    }
    
    echo json_encode( array(
        'success'     => true,
        'woocommerce' => false,
        'message'     => 'WooCommerce standalone mode',
    ) );
    exit;
}

$theme_uri = untrailingslashit( get_template_directory_uri() );
$theme_dir = get_template_directory();

// Enable output compression (GZIP) if supported by client and not already started
if ( ! ini_get( 'zlib.output_compression' ) && ! ob_get_level() ) {
    @ob_start( 'ob_gzhandler' );
}

// 301 Permanent Redirect for any old URLs with ?page_file=*.html
if ( isset( $_GET['page_file'] ) ) {
    $clean_page = basename( sanitize_file_name( $_GET['page_file'] ) );
    if ( $clean_page === 'index.html' ) {
        wp_safe_redirect( home_url( '/' ), 301 );
        exit;
    }
    if ( file_exists( $theme_dir . '/' . $clean_page ) && preg_match( '/\.html$/i', $clean_page ) ) {
        wp_safe_redirect( trailingslashit( home_url() ) . $clean_page, 301 );
        exit;
    }
}

// 1. Determine which page to display (Static HTML Template vs Native WooCommerce / WordPress Page)
$is_native_wp = false;
$page_file = 'index.html';

// Extract requested path from URL
$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? trim( (string) parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' ) : '';
$path_parts = array_values( array_filter( explode( '/', $request_uri ) ) );
$last_part = ! empty( $path_parts ) ? end( $path_parts ) : '';
$candidate_html = '';
if ( ! empty( $last_part ) ) {
    $candidate_html = preg_match( '/\.html$/i', $last_part ) ? $last_part : $last_part . '.html';
}

// Check for boutique explicitly (supports /boutique/, /boutique, /boutique.html/, /boutique.html, or WooCommerce shop archive)
if ( $last_part === 'boutique' || $last_part === 'boutique.html' || ( function_exists( 'is_shop' ) && is_shop() ) ) {
    $page_file = 'boutique.html';
    $is_native_wp = false;
    status_header( 200 );
    global $wp_query;
    if ( isset( $wp_query ) ) {
        $wp_query->is_404 = false;
    }
} elseif ( ( function_exists( 'is_checkout' ) && is_checkout() ) ||
           ( function_exists( 'is_cart' ) && is_cart() && empty( $candidate_html ) ) ||
           ( function_exists( 'is_account_page' ) && is_account_page() ) ) {
    $is_native_wp = true;
} elseif ( ! empty( $candidate_html ) && file_exists( $theme_dir . '/' . $candidate_html ) ) {
    $page_file = $candidate_html;
    $is_native_wp = false;
    status_header( 200 );
    global $wp_query;
    if ( isset( $wp_query ) ) {
        $wp_query->is_404 = false;
    }
} elseif ( empty( $last_part ) ) {
    $page_file = 'index.html';
    $is_native_wp = false;
    status_header( 200 );
    global $wp_query;
    if ( isset( $wp_query ) ) {
        $wp_query->is_404 = false;
    }
} else {
    // Native WordPress route
    $is_native_wp = true;
}

// If it's a native WooCommerce or WordPress page, render the branded WordPress template
if ( $is_native_wp ) {
    $home_url = home_url();
    ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo esc_url( $theme_uri . '/style.min.css?v=38' ); ?>">
    <link rel="icon" href="<?php echo esc_url( $theme_uri . '/favicon-32x32.png' ); ?>" sizes="32x32">
    <?php wp_head(); ?>
</head>
<body <?php body_class( 'sterimar-wc-page' ); ?>>
    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <a href="<?php echo esc_url( home_url('/') ); ?>" class="nav-logo" id="nav-logo" aria-label="Stérimar Tunisie - Accueil">
                <img src="<?php echo esc_url( $theme_uri . '/logo.png' ); ?>" alt="Stérimar Logo" class="logo-img">
                <span class="logo-slogan">Mieux respirer, c'est mieux vivre</span>
            </a>
            <div class="nav-links" id="nav-links">
                <a href="<?php echo esc_url( home_url('/') ); ?>" class="nav-link">Accueil</a>
                <a href="<?php echo esc_url( trailingslashit( $home_url ) . 'boutique.html' ); ?>" class="nav-link">Boutique</a>
                <a href="<?php echo esc_url( trailingslashit( $home_url ) . 'blog.html' ); ?>" class="nav-link">Blog</a>
                <a href="<?php echo esc_url( trailingslashit( $home_url ) . 'qui-sommes-nous.html' ); ?>" class="nav-link">Qui sommes-nous</a>
                <a href="<?php echo esc_url( trailingslashit( $home_url ) . 'contact.html' ); ?>" class="nav-link">Contact</a>
                <a href="<?php echo esc_url( trailingslashit( $home_url ) . 'creation-compte.html' ); ?>" class="nav-link nav-account" id="nav-account" aria-label="Mon compte">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    <span id="nav-account-label">Compte</span>
                </a>
                <a href="<?php echo esc_url( trailingslashit( $home_url ) . 'panier.html' ); ?>" class="nav-link nav-cart" aria-label="Voir mon panier">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1" />
                        <circle cx="20" cy="21" r="1" />
                        <path d="m1 1 4 0 2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                    </svg>
                    <span class="cart-count" id="cart-count">0</span>
                    Panier
                </a>
            </div>
            <button class="nav-toggle" id="nav-toggle" aria-label="Menu de navigation" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>

    <!-- Header -->
    <section class="shop-header" style="background: linear-gradient(135deg, #0077B6 0%, #00B8E5 100%);">
        <div class="container">
            <span class="section-tag" style="background: rgba(255,255,255,0.2); color:#fff; border:1px solid rgba(255,255,255,0.3);">Paiement Sécurisé</span>
            <h1 class="page-title" style="color: #fff;">Finaliser ma Commande</h1>
            <p class="page-subtitle" style="color: rgba(255,255,255,0.9);">Veuillez renseigner vos coordonnées de livraison et confirmer votre paiement.</p>
        </div>
    </section>

    <!-- Main Native Content -->
    <main class="wc-main-wrapper">
        <div class="container wc-main-container">
            <?php
            if ( have_posts() ) {
                while ( have_posts() ) {
                    the_post();
                    the_content();
                }
            }
            ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer" id="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="<?php echo esc_url( home_url('/') ); ?>" aria-label="Stérimar Accueil">
                        <img src="<?php echo esc_url( $theme_uri . '/logo.png' ); ?>" alt="Stérimar Logo" class="footer-logo" style="height: 144px; margin-bottom: 1rem; cursor: pointer;">
                    </a>
                    <p>50 ans d'innovation naturellement efficace pour votre bien-être nasal.</p>
                </div>
                <div class="footer-links">
                    <h4>Navigation</h4>
                    <a href="<?php echo esc_url( home_url('/') ); ?>">Accueil</a>
                    <a href="<?php echo esc_url( trailingslashit( $home_url ) . 'boutique.html' ); ?>">Boutique</a>
                    <a href="<?php echo esc_url( trailingslashit( $home_url ) . 'blog.html' ); ?>">Blog</a>
                    <a href="<?php echo esc_url( trailingslashit( $home_url ) . 'qui-sommes-nous.html' ); ?>">Qui sommes-nous</a>
                    <a href="<?php echo esc_url( trailingslashit( $home_url ) . 'contact.html' ); ?>">Contact</a>
                    <a href="<?php echo esc_url( trailingslashit( $home_url ) . 'creation-compte.html' ); ?>">Mon Compte</a>
                    <a href="<?php echo esc_url( trailingslashit( $home_url ) . 'panier.html' ); ?>">Panier</a>
                </div>
                <div class="footer-links">
                    <h4>Informations</h4>
                    <a href="<?php echo esc_url( trailingslashit( $home_url ) . 'mentions-legales.html' ); ?>">Mentions légales</a>
                    <a href="<?php echo esc_url( trailingslashit( $home_url ) . 'politique-confidentialite.html' ); ?>">Politique de confidentialité</a>
                    <a href="<?php echo esc_url( trailingslashit( $home_url ) . 'conditions-generales.html' ); ?>">Conditions générales</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© 2026 Stérimar™ — Tous droits réservés. Dispositif médical.</p>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
    <script src="<?php echo esc_url( $theme_uri . '/app.js?v=31' ); ?>"></script>
</body>
</html>
    <?php
    exit;
}

if ( ! file_exists( $theme_dir . '/' . $page_file ) ) {
    $page_file = 'index.html';
}

$html = file_get_contents( $theme_dir . '/' . $page_file );

// 2. Rewrite relative CSS files: href="style.css..." -> href="THEME_URI/style.css..."
$html = preg_replace( '/href=["\'](style(?:\.min)?\.css[^"\']*)["\']/i', 'href="' . $theme_uri . '/$1"', $html );

// 3. Rewrite relative JS files: src="app.js..." -> src="THEME_URI/app.js..."
$html = preg_replace( '/src=["\'](app(?:\.min)?\.js[^"\']*)["\']/i', 'src="' . $theme_uri . '/$1"', $html );

// Inject WordPress standard hooks (required by Google Site Kit, Analytics, Tag Manager, SEO plugins)
wp_dequeue_script( 'sterimar-app' );
wp_dequeue_style( 'sterimar-style' );

ob_start();
wp_head();
$wp_head_output = ob_get_clean();

$wp_body_open_output = '';
if ( function_exists( 'wp_body_open' ) ) {
    ob_start();
    wp_body_open();
    $wp_body_open_output = ob_get_clean();
}

ob_start();
wp_footer();
$wp_footer_output = ob_get_clean();

// Strip conflicting external chatbot plugin (e.g. Rapls AI Chatbot)
$wp_head_output = preg_replace( '/<link[^>]*href=["\'][^"\']*rapls-ai-chatbot[^"\']*["\'][^>]*\/?>/is', '', $wp_head_output );
$wp_head_output = preg_replace( '/<link[^>]*id=["\']sterimar-style-css["\'][^>]*\/?>/is', '', $wp_head_output );
$wp_footer_output = preg_replace( '/<script[^>]*id=["\']sterimar-app-js["\'][^>]*>.*?<\/script>/is', '', $wp_footer_output );
$wp_footer_output = preg_replace( '/<div[^>]*id=["\']wp-ai-chatbot["\'][^>]*>.*?<\/div>/is', '', $wp_footer_output );
$wp_footer_output = preg_replace( '/<script[^>]*src=["\'][^"\']*rapls-ai-chatbot[^"\']*["\'][^>]*>.*?<\/script>/is', '', $wp_footer_output );
$wp_footer_output = preg_replace( '/<script[^>]*id=["\']raplsaich-[^"\']*["\'][^>]*>.*?<\/script>/is', '', $wp_footer_output );

// Remove jQuery and heavy WooCommerce scripts from static pages (all static pages use pure Vanilla JS)
$wp_head_output = preg_replace( '/<script[^>]*src=["\'][^"\']*(?:jquery|woocommerce|blockUI|sourcebuster)[^"\']*["\'][^>]*>.*?<\/script>/is', '', $wp_head_output );
$wp_head_output = preg_replace( '/<script[^>]*src=["\'][^"\']*(?:reddit|snapchat)[^"\']*["\'][^>]*>.*?<\/script>/is', '', $wp_head_output );
$wp_footer_output = preg_replace( '/<script[^>]*src=["\'][^"\']*(?:jquery|woocommerce|blockUI|sourcebuster)[^"\']*["\'][^>]*>.*?<\/script>/is', '', $wp_footer_output );
$wp_footer_output = preg_replace( '/<script[^>]*src=["\'][^"\']*(?:reddit|snapchat)[^"\']*["\'][^>]*>.*?<\/script>/is', '', $wp_footer_output );

// Defer any remaining third-party scripts so they never block HTML parsing or initial render
$wp_head_output = preg_replace( '/<script(?![^>]*(?:defer|async))([^>]*src=[^>]+)>/i', '<script defer$1>', $wp_head_output );
$wp_footer_output = preg_replace( '/<script(?![^>]*(?:defer|async))([^>]*src=[^>]+)>/i', '<script defer$1>', $wp_footer_output );

// Inject window.STERIMAR_THEME_URI and wp_head before </head>
$injected_head = '<script>window.STERIMAR_THEME_URI = "' . esc_js( $theme_uri ) . '";</script>' . "\n" . $wp_head_output;
if ( stripos( $html, '</head>' ) !== false ) {
    $html = str_ireplace( '</head>', $injected_head . '</head>', $html );
} else {
    $html = $injected_head . $html;
}

// Inject wp_body_open right after <body ...>
if ( ! empty( $wp_body_open_output ) && preg_match( '/<body[^>]*>/i', $html ) ) {
    $html = preg_replace( '/(<body[^>]*>)/i', '$1' . $wp_body_open_output, $html, 1 );
}

// Inject wp_footer before </body>
if ( stripos( $html, '</body>' ) !== false ) {
    $html = str_ireplace( '</body>', $wp_footer_output . '</body>', $html );
} else {
    $html = $html . $wp_footer_output;
}

// 4. Rewrite relative image/media src and srcset
$html = preg_replace_callback( '/(src|srcset)=["\']([^"\']+)["\']/i', function( $matches ) use ( $theme_uri ) {
    $attr = $matches[1];
    $val = $matches[2];
    
    // Ignore external URLs or data URIs
    if ( preg_match( '/^(https?:\/\/|\/\/|data:)/i', $val ) ) {
        return $matches[0];
    }
    
    // Handle srcset attributes with multiple values
    if ( strtolower( $attr ) === 'srcset' ) {
        $parts = explode( ',', $val );
        $new_parts = array();
        foreach ( $parts as $part ) {
            $part = trim( $part );
            if ( preg_match( '/^([^\s]+)(\s+.*)?$/', $part, $pmatches ) ) {
                $img_url = $pmatches[1];
                $descriptor = isset( $pmatches[2] ) ? $pmatches[2] : '';
                if ( ! preg_match( '/^(https?:\/\/|\/\/|data:)/i', $img_url ) ) {
                    $img_url = $theme_uri . '/' . ltrim( $img_url, '/' );
                }
                $new_parts[] = $img_url . $descriptor;
            } else {
                $new_parts[] = $part;
            }
        }
        return 'srcset="' . implode( ', ', $new_parts ) . '"';
    }
    
    return $attr . '="' . $theme_uri . '/' . ltrim( $val, '/' ) . '"';
}, $html );

// 4.1 Rewrite relative image preload and favicon links: <link ... href="banner-*.webp" ...>
$html = preg_replace_callback( '/<link\s+([^>]*href=["\'])([^"\']+\.(?:webp|jpg|jpeg|png|svg|ico))(["\'][^>]*)>/i', function( $matches ) use ( $theme_uri ) {
    $before = $matches[1];
    $val = $matches[2];
    $after = $matches[3];
    if ( preg_match( '/^(https?:\/\/|\/\/|data:)/i', $val ) ) {
        return $matches[0];
    }
    return '<link ' . $before . $theme_uri . '/' . ltrim( $val, '/' ) . $after . '>';
}, $html );

// 5. Rewrite inline style url('...') for local files
$html = preg_replace_callback( '/url\(\s*[\'"]?([^\'")]+)[\'"]?\s*\)/i', function( $matches ) use ( $theme_uri ) {
    $url = trim( $matches[1] );
    if ( preg_match( '/^(https?:\/\/|\/\/|data:)/i', $url ) ) {
        return $matches[0];
    }
    return "url('" . $theme_uri . '/' . ltrim( $url, '/' ) . "')";
}, $html );

// 6. Rewrite relative HTML page links in <a> tags to clean URLs: <a href="boutique.html#rhume"> -> <a href="SITE_URL/boutique.html#rhume">
$home_url = home_url();
$html = preg_replace_callback( '/(<a\s+[^>]*?)href=["\']([a-zA-Z0-9_\-]+\.html)(#[^"\']*)?["\']/i', function( $matches ) use ( $home_url ) {
    $prefix = $matches[1];
    $page = basename( $matches[2] );
    $hash = isset( $matches[3] ) ? $matches[3] : '';
    if ( $page === 'index.html' ) {
        return $prefix . 'href="' . esc_url( trailingslashit( $home_url ) ) . $hash . '"';
    }
    return $prefix . 'href="' . esc_url( trailingslashit( $home_url ) . $page ) . $hash . '"';
}, $html );

// 7. Output page with browser and edge cache headers
header( 'Cache-Control: public, max-age=3600, s-maxage=86400, stale-while-revalidate=604800' );
echo $html;
