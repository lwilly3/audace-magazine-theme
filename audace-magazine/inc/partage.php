<?php
/**
 * Description de la page et fiche de partage (WhatsApp, Facebook, X).
 *
 * Si une extension de référencement est active, elle garde la main et le thème n'écrit rien.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_head', function () {
    if ( function_exists( 'aioseo' ) || defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'SEOPRESS_VERSION' ) ) {
        return;
    }
    $titre = wp_get_document_title();
    $desc  = get_bloginfo( 'description' );
    $url   = home_url( '/' );
    $image = '';
    $type  = 'website';
    if ( is_singular() ) {
        $post = get_queried_object();
        $url  = (string) get_permalink( $post );
        if ( 'post' === $post->post_type ) {
            $type = 'article';
            $desc = wp_strip_all_tags( am_chapo( $post ) );
        } elseif ( has_excerpt( $post ) ) {
            $desc = wp_strip_all_tags( get_the_excerpt( $post ) );
        }
        if ( has_post_thumbnail( $post ) ) {
            $image = (string) get_the_post_thumbnail_url( $post, 'large' );
        }
    } elseif ( is_category() || is_tag() ) {
        $terme = get_queried_object();
        $url   = (string) get_term_link( $terme );
        if ( $terme->description ) {
            $desc = wp_strip_all_tags( $terme->description );
        }
    }
    if ( ! $image ) {
        $image = get_template_directory_uri() . '/assets/img/logo.png';
    }
    $desc = trim( html_entity_decode( (string) $desc, ENT_QUOTES, 'UTF-8' ) );
    if ( $desc ) {
        echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
    }
    $balises = array(
        'og:type'        => $type,
        'og:site_name'   => get_bloginfo( 'name' ),
        'og:locale'      => 'fr_FR',
        'og:title'       => $titre,
        'og:description' => $desc,
        'og:url'         => $url,
        'og:image'       => $image,
    );
    foreach ( $balises as $propriete => $valeur ) {
        if ( '' !== (string) $valeur ) {
            echo '<meta property="' . esc_attr( $propriete ) . '" content="' . esc_attr( $valeur ) . '">' . "\n";
        }
    }
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
}, 5 );
