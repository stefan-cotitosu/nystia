<?php
if ( !defined( 'ABSPATH' ) ) exit;

if ( !function_exists( 'nystia_parent_css' ) ):
    function nystia_parent_css() {
        wp_enqueue_style( 'nystia_parent', trailingslashit( get_template_directory_uri() ) . 'style.css', array( 'bootstrap' ) );
        if( is_rtl() ) {
            wp_enqueue_style( 'nystia_parent_rtl', trailingslashit( get_template_directory_uri() ) . 'style-rtl.css', array( 'bootstrap' ) );
        }

    }
endif;
add_action( 'wp_enqueue_scripts', 'nystia_parent_css', 10 );

/**
 * Change default fonts
 *
 * @since 1.0.0
 */
function nystia_change_defaults( $wp_customize ) {

    /* Change default fonts */
    $nystia_headings_font = $wp_customize->get_setting( 'hestia_headings_font' );
    if ( ! empty( $nystia_headings_font ) ) {
        $nystia_headings_font->default = nystia_font_default_frontend();
    }
    $nystia_body_font = $wp_customize->get_setting( 'hestia_body_font' );
    if ( ! empty( $nystia_body_font ) ) {
        $nystia_body_font->default = nystia_font_default_frontend();
    }
}
add_action( 'customize_register', 'nystia_change_defaults', 99 );

/**
 * Change defaults on frontend
 */
function nystia_font_default_frontend() {
    return 'Open Sans';
}

add_filter( 'hestia_headings_default', 'nystia_font_default_frontend' );
add_filter( 'hestia_body_font_default', 'nystia_font_default_frontend' );

/**
 * Import options from the parent theme
 *
 * @since 1.0.0
 */
function nystia_get_parent_options() {
    $hestia_mods = get_option( 'theme_mods_hestia' );
    if ( ! empty( $hestia_mods ) ) {
        foreach ( $hestia_mods as $hestia_mod_k => $hestia_mod_v ) {
            set_theme_mod( $hestia_mod_k, $hestia_mod_v );
        }
    }
}
add_action( 'after_switch_theme', 'nystia_get_parent_options' );

/**
 * Remove boxed layout control
 *
 * @since 1.0.0
 */
function nystia_remove_boxed_layout( $wp_customize ) {
    $wp_customize->remove_control( 'hestia_general_layout' );
}
add_action( 'customize_register', 'nystia_remove_boxed_layout', 100 );