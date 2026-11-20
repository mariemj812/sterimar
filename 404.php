<?php
/**
 * 404 Template - Stérimar Theme
 * Redirects all 404 Not Found errors to homepage
 */
status_header( 301 );
wp_safe_redirect( home_url( '/' ), 301 );
exit;
