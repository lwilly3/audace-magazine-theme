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
    $titre  = html_entity_decode( wp_get_document_title(), ENT_QUOTES, 'UTF-8' );
    $desc   = am_maison()['accroche']; // À défaut de mieux : l'accroche du magazine.
    $url    = home_url( '/' );
    $image  = '';
    $taille = array( 0, 0 );
    $type   = 'website';
    if ( am_est_accueil() ) {
        // L'accueil se présente par le nom du magazine et son accroche, pas par le slogan du site seul.
        $titre = get_bloginfo( 'name' );
        $desc  = am_maison()['accroche'];
    } elseif ( is_singular() ) {
        $post = get_queried_object();
        $url  = (string) get_permalink( $post );
        if ( 'post' === $post->post_type ) {
            $type = 'article';
            $desc = wp_strip_all_tags( am_chapo( $post ) );
            // Le nom du site est déjà affiché sous la fiche : inutile de le répéter après le titre.
            $titre = html_entity_decode( wp_strip_all_tags( get_the_title( $post ) ), ENT_QUOTES, 'UTF-8' );
        } elseif ( has_excerpt( $post ) ) {
            $desc = wp_strip_all_tags( get_the_excerpt( $post ) );
        }
        if ( has_post_thumbnail( $post ) ) {
            $src = wp_get_attachment_image_src( (int) get_post_thumbnail_id( $post ), 'large' );
            if ( $src ) {
                $image  = (string) $src[0];
                $taille = array( (int) $src[1], (int) $src[2] );
            }
        }
    } elseif ( is_category() || is_tag() ) {
        $terme = get_queried_object();
        $url   = (string) get_term_link( $terme );
        $nom   = is_category() ? am_nom_rubrique( $terme->name ) : $terme->name;
        $titre = $nom . ' – ' . get_bloginfo( 'name' );
        $desc  = $terme->description ? wp_strip_all_tags( $terme->description ) : 'Tous les articles : ' . $nom . '.';
    } elseif ( function_exists( 'is_shop' ) && is_shop() ) {
        $url  = am_kiosque_url();
        $desc = 'Tous les numéros d’Audace Magazine.';
    }
    if ( ! $image ) {
        // Sans image propre, une image au format des fiches de partage (1200 × 630) : le logo seul,
        // qui n'a pas ces proportions, y serait rogné.
        $image  = get_template_directory_uri() . '/assets/img/partage.png';
        $taille = array( 1200, 630 );
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
        'og:image:width'  => $taille[0] ? (string) $taille[0] : '',
        'og:image:height' => $taille[1] ? (string) $taille[1] : '',
    );
    foreach ( $balises as $propriete => $valeur ) {
        if ( '' !== (string) $valeur ) {
            echo '<meta property="' . esc_attr( $propriete ) . '" content="' . esc_attr( $valeur ) . '">' . "\n";
        }
    }
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
}, 5 );
