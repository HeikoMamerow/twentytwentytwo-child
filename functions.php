<?php
/**
 * Twenty Twenty-Two Child functions and definitions
 */

// Remove fonts - we use only system fonts.
function child_remove_parent_function() {
	remove_action( 'wp_head', 'twentytwentytwo_preload_webfonts' );
}

add_action( 'wp_loaded', 'child_remove_parent_function' );

/**
 * Disable embeds.
 */
function disable_embeds_code_init() {

	// Remove the REST API endpoint.
	remove_action( 'rest_api_init', 'wp_oembed_register_route' );

	// Turn off oEmbed auto discovery.
	add_filter( 'embed_oembed_discover', '__return_false' );

	// Don't filter oEmbed results.
	remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );

	// Remove oEmbed discovery links.
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

	// Remove oEmbed-specific JavaScript from the front-end and back-end.
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	add_filter( 'tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin' );

	// Remove all embeds rewrite rules.
	add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );

	// Remove filter of the oEmbed result before any HTTP requests are made.
	remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result', 10 );
}

add_action( 'init', 'disable_embeds_code_init', 9999 );

function disable_embeds_tiny_mce_plugin( $plugins ) {
	return array_diff( $plugins, [ 'wpembed' ] );
}

function disable_embeds_rewrites( $rules ) {
	foreach ( $rules as $rule => $rewrite ) {
		if ( false !== strpos( $rewrite, 'embed=true' ) ) {
			unset( $rules[ $rule ] );
		}
	}

	return $rules;
}

/**
 * Favicons
 */

function hm_favicons() {
	echo '<link rel="icon" href="' . esc_url( home_url( '/favicon.ico' ) ) . '" sizes="any">';
	echo '<link rel="icon" href="' . esc_url( home_url( '/icon.svg' ) ) . '" type="image/svg+xml">';
}

add_action( 'wp_head', 'hm_favicons' );

/**
 * Enqueue stylesheet
 */
function twentytwentytwo_child_style() {
	$theme_version  = wp_get_theme()->get( 'Version' );
	$version_string = is_string( $theme_version ) ? $theme_version : false;
	wp_register_style(
		'twentytwentytwo-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[],
		$version_string
	);
	wp_enqueue_style( 'twentytwentytwo-child-style' );
}

add_action( 'wp_enqueue_scripts', 'twentytwentytwo_child_style' );
