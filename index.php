<?php
/**
 * Stérimar Theme - Main Template
 *
 * Automatically handles static HTML templates and resolves assets within WordPress.
 *
 * @package Sterimar
 */

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
