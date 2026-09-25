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
        
        foreach ( $cart_data as $item ) {
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

// 1. Determine which page to display (Static HTML Template vs Native WooCommerce / WordPress Page)
$is_native_wp = false;

if ( ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) ||
     ( function_exists( 'is_checkout' ) && is_checkout() ) ||
     ( function_exists( 'is_cart' ) && is_cart() ) ||
     ( function_exists( 'is_account_page' ) && is_account_page() ) ) {
    $is_native_wp = true;
}

$page_file = 'index.html';

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

if ( ! $is_native_wp ) {
    // Check if the current URL path matches any HTML file
    $request_uri = trim( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' );
    $path_parts = array_filter( explode( '/', $request_uri ) );
    $last_part = end( $path_parts );
    if ( ! empty( $last_part ) ) {
        $candidate_html = preg_match( '/\.html$/i', $last_part ) ? $last_part : $last_part . '.html';
        if ( file_exists( $theme_dir . '/' . $candidate_html ) ) {
            $page_file = $candidate_html;
            status_header( 200 );
            global $wp_query;
            if ( isset( $wp_query ) ) {
                $wp_query->is_404 = false;
            }
        } else {
            // It's a WordPress native route (like /commander/, /checkout/, /commande-recue/)
            $is_native_wp = true;
        }
    } else {
        status_header( 200 );
        global $wp_query;
        if ( isset( $wp_query ) ) {
            $wp_query->is_404 = false;
        }
    }
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
    <link rel="stylesheet" href="<?php echo esc_url( $theme_uri . '/style.css?v=20' ); ?>">
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
    <script src="<?php echo esc_url( $theme_uri . '/app.js?v=5' ); ?>"></script>
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
$html = preg_replace( '/href=["\'](style\.css[^"\']*)["\']/i', 'href="' . $theme_uri . '/$1"', $html );

// 3. Rewrite relative JS files: src="app.js..." -> src="THEME_URI/app.js..."
$html = preg_replace( '/src=["\'](app\.js[^"\']*)["\']/i', 'src="' . $theme_uri . '/$1"', $html );

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

// 5. Rewrite inline style url('...') for local files
$html = preg_replace_callback( '/url\(\s*[\'"]?([^\'")]+)[\'"]?\s*\)/i', function( $matches ) use ( $theme_uri ) {
    $url = trim( $matches[1] );
    if ( preg_match( '/^(https?:\/\/|\/\/|data:)/i', $url ) ) {
        return $matches[0];
    }
    return "url('" . $theme_uri . '/' . ltrim( $url, '/' ) . "')";
}, $html );

// 6. Rewrite relative HTML page links to clean URLs: href="boutique.html#rhume" -> href="SITE_URL/boutique.html#rhume"
$home_url = home_url();
$html = preg_replace_callback( '/href=["\']([a-zA-Z0-9_\-]+\.html)(#[^"\']*)?["\']/i', function( $matches ) use ( $home_url ) {
    $page = basename( $matches[1] );
    $hash = isset( $matches[2] ) ? $matches[2] : '';
    if ( $page === 'index.html' ) {
        return 'href="' . esc_url( trailingslashit( $home_url ) ) . $hash . '"';
    }
    return 'href="' . esc_url( trailingslashit( $home_url ) . $page ) . $hash . '"';
}, $html );

// 7. Output page
echo $html;
