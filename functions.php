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



//echo apply_filters(
//	'hestia_blog_post_meta', sprintf(
//	/* translators: %1$s is Author name wrapped, %2$s is Time */
//		esc_html__( 'By %1$s, %2$s', 'hestia' ),
//		/* translators: %1$s is Author name, %2$s is author link */
//		sprintf(
//			'<a href="%2$s" title="%1$s" class="vcard author"><strong class="fn">%1$s</strong></a>',
//			esc_html( get_the_author() ),
//			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) )
//		),
//		sprintf(
//		/* translators: %1$s is Time since post, %2$s is author Close tag */
//			esc_html__( '%1$s ago %2$s', 'hestia' ),
//			sprintf(
//			/* translators: %1$s is Time since, %2$s is Link to post */
//				'<a href="%2$s"><time>%1$s</time>',
//				esc_html( human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ),
//				esc_url( get_permalink() )
//			),
//			'</a>'
//		)
//	)
//);

/**
 * Get the number of comments for a post
 *
 * @return string - number of comments
 */
function nystia_post_number_of_comments() {
	$comments_number = get_comments_number();
	if ( 1 === (int)$comments_number ) {
		return sprintf( _x( 'One Comment', 'comments title', 'nystia' ) );
	} else if ( 0 === (int)$comments_number ) {
		return sprintf( _x( 'No Comments', 'comments title', 'nystia' ) );
	} else {
		return sprintf(
			_nx(
				'%1$s Comment',
				'%1$s Comments',
				$comments_number,
				'comments title',
				'nystia'
			),
			number_format_i18n( $comments_number )
		);
	}
}

/*
 * Show metadata on Blog's post on Blog page.
 */
function nystia_show_blog_meta() {

	$author_name = get_the_author();
	$author_email = get_the_author_meta();
	$author_gravatar = get_avatar( $author_email, '30' );

	$posted_date = human_time_diff( get_the_time('U'), current_time( 'timestamp' ) );

	$number_of_comments = nystia_post_number_of_comments();
	/* 1 - author's image
	 * 2 - author's name
	 * 3 - number of comments for the post
	 * 4 - date */
	printf(
		__( '<span class="ath-gravatar">%1$s </span><span class="ath-name">%2$s </span><span class="nb-of-comm">%3$s </span><span class="posted-date">%4$s ago</span>', 'nystia' ),
		$author_gravatar,
		$author_name,
		$number_of_comments,
		$posted_date
	);
}
add_filter( 'hestia_blog_post_meta', 'nystia_show_blog_meta' );