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

// 0.1 Handle WooCommerce Cart Sync & Checkout Redirect
if ( isset( $_POST['sterimar_wc_checkout'] ) || isset( $_GET['sterimar_wc_checkout'] ) ) {
    header( 'Content-Type: application/json; charset=UTF-8' );
    
    $cart_json = isset( $_POST['cart'] ) ? wp_unslash( $_POST['cart'] ) : ( isset( $_GET['cart'] ) ? wp_unslash( $_GET['cart'] ) : '' );
    $cart_data = json_decode( $cart_json, true );
    
    if ( function_exists( 'WC' ) && ! empty( $cart_data ) && is_array( $cart_data ) ) {
        if ( is_null( WC()->cart ) ) {
            wc_load_cart();
        }
        
        WC()->cart->empty_cart();
        
        foreach ( $cart_data as $item ) {
            $product_name = isset( $item['name'] ) ? sanitize_text_field( $item['name'] ) : '';
            $qty = isset( $item['quantity'] ) ? intval( $item['quantity'] ) : 1;
            
            $wc_product_id = 0;
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
            
            if ( $wc_product_id > 0 ) {
                WC()->cart->add_to_cart( $wc_product_id, $qty );
            }
        }
        
        $checkout_url = wc_get_checkout_url();
        echo json_encode( array(
            'success'      => true,
            'woocommerce'  => true,
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

// 1. Determine which page to display
$page_file = 'index.html';

if ( isset( $_GET['page_file'] ) ) {
    $clean_page = basename( sanitize_file_name( $_GET['page_file'] ) );
    if ( file_exists( $theme_dir . '/' . $clean_page ) && preg_match( '/\.html$/i', $clean_page ) ) {
        $page_file = $clean_page;
    }
} else {
    // Check if the current URL path matches any HTML file
    $request_uri = trim( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' );
    $path_parts = explode( '/', $request_uri );
    $last_part = end( $path_parts );
    if ( ! empty( $last_part ) ) {
        if ( ! preg_match( '/\.html$/i', $last_part ) ) {
            $last_part .= '.html';
        }
        if ( file_exists( $theme_dir . '/' . $last_part ) ) {
            $page_file = $last_part;
        }
    }
}

if ( ! file_exists( $theme_dir . '/' . $page_file ) ) {
    $page_file = 'index.html';
}

$html = file_get_contents( $theme_dir . '/' . $page_file );

// 2. Rewrite relative CSS files: href="style.css..." -> href="THEME_URI/style.css..."
$html = preg_replace( '/href=["\'](style\.css[^"\']*)["\']/i', 'href="' . $theme_uri . '/$1"', $html );

// 3. Rewrite relative JS files: src="app.js..." -> src="THEME_URI/app.js..."
$html = preg_replace( '/src=["\'](app\.js[^"\']*)["\']/i', 'src="' . $theme_uri . '/$1"', $html );

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

// 5. Rewrite inline style url('...') for local files
$html = preg_replace_callback( '/url\(\s*[\'"]?([^\'")]+)[\'"]?\s*\)/i', function( $matches ) use ( $theme_uri ) {
    $url = trim( $matches[1] );
    if ( preg_match( '/^(https?:\/\/|\/\/|data:)/i', $url ) ) {
        return $matches[0];
    }
    return "url('" . $theme_uri . '/' . ltrim( $url, '/' ) . "')";
}, $html );

// 6. Rewrite relative HTML page links: href="boutique.html#rhume" -> href="SITE_URL/?page_file=boutique.html#rhume"
$home_url = home_url();
$html = preg_replace_callback( '/href=["\']([a-zA-Z0-9_\-]+\.html)(#[^"\']*)?["\']/i', function( $matches ) use ( $home_url ) {
    $page = basename( $matches[1] );
    $hash = isset( $matches[2] ) ? $matches[2] : '';
    if ( $page === 'index.html' ) {
        return 'href="' . esc_url( trailingslashit( $home_url ) ) . $hash . '"';
    }
    return 'href="' . esc_url( add_query_arg( 'page_file', $page, $home_url ) ) . $hash . '"';
}, $html );

// 7. Output page
echo $html;
