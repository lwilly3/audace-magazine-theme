<?php
/**
 * Le magazine : intégration de WooCommerce.
 *
 * Les numéros sont des produits WooCommerce. Le thème habille la boutique, la fiche
 * d'un numéro, le panier, la commande et le compte, sans toucher au parcours d'achat.
 * Sans WooCommerce, tout ce qui concerne le magazine disparaît simplement.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/** Vrai si WooCommerce est actif. */
function am_kiosque_actif(): bool {
    return function_exists( 'wc_get_products' ) && function_exists( 'wc_get_page_permalink' );
}

/** Adresse du kiosque (la page boutique de WooCommerce). Chaîne vide sans WooCommerce. */
function am_kiosque_url(): string {
    if ( ! am_kiosque_actif() ) {
        return '';
    }
    $url = wc_get_page_permalink( 'shop' );
    return $url ? (string) $url : '';
}

/**
 * Titre d'un numéro, découpé : « Audace Magazine N°005 – Présidentielle… » donne
 * le numéro (« N°005 ») et le titre (« Présidentielle… »).
 *
 * @return array{numero:string, titre:string}
 */
function am_titre_numero( string $nom ): array {
    $nom = trim( html_entity_decode( wp_strip_all_tags( $nom ), ENT_QUOTES, 'UTF-8' ) );
    $nom = (string) preg_replace( '~^Audace Magazine\s+~iu', '', $nom );
    if ( preg_match( '~^(.{2,24}?)\s+[–—-]\s+(.+)$~u', $nom, $m ) ) {
        return array( 'numero' => $m[1], 'titre' => $m[2] );
    }
    return array( 'numero' => '', 'titre' => $nom );
}

/**
 * Les derniers numéros, du plus récent au plus ancien.
 *
 * @return array<int, array{numero:string, titre:string, url:string, image:int, resume:string}>
 */
function am_numeros( int $combien = 4 ): array {
    if ( ! am_kiosque_actif() ) {
        return array();
    }
    $numeros = array();
    $produits = wc_get_products( array( 'limit' => $combien, 'status' => 'publish', 'orderby' => 'date', 'order' => 'DESC' ) );
    foreach ( (array) $produits as $p ) {
        if ( ! is_object( $p ) || ! $p->is_visible() ) {
            continue;
        }
        $t         = am_titre_numero( $p->get_name() );
        $numeros[] = array(
            'numero' => $t['numero'],
            'titre'  => $t['titre'],
            'url'    => (string) $p->get_permalink(),
            'image'  => (int) $p->get_image_id(),
            'resume' => trim( wp_strip_all_tags( $p->get_short_description() ) ),
        );
    }
    return $numeros;
}

/** Couverture d'un numéro, entière (jamais recadrée en carré). */
function am_couverture( int $image, string $sizes = '(min-width: 720px) 220px, 40vw', bool $differe = true ): string {
    if ( ! $image ) {
        return '';
    }
    return (string) wp_get_attachment_image( $image, 'large', false, array(
        'sizes'    => $sizes,
        'loading'  => $differe ? 'lazy' : 'eager',
        'decoding' => 'async',
    ) );
}

if ( ! function_exists( 'am_reglages_woocommerce' ) ) {
    function am_reglages_woocommerce(): void {
        if ( ! am_kiosque_actif() ) {
            return;
        }
        // Le thème fournit son propre cadre (woocommerce.php) : ni colonne latérale ni fil d'Ariane.
        remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
        remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
        // Dans la grille, la couverture entière au lieu de la vignette carrée de 300 px.
        remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
        add_action( 'woocommerce_before_shop_loop_item_title', function () {
            global $product;
            if ( $product ) {
                echo '<span class="couverture">' . am_couverture( (int) $product->get_image_id(), '(min-width: 1000px) 300px, 45vw' ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput
            }
        }, 10 );
        // Ni tri ni compteur pour une poignée de numéros.
        remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
        remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
    }
}
add_action( 'wp', 'am_reglages_woocommerce' );

// Dans la grille, le numéro en surtitre puis le titre, sans le « Audace Magazine » répété sur chaque couverture.
add_action( 'init', function () {
    if ( ! am_kiosque_actif() ) {
        return;
    }
    remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
    add_action( 'woocommerce_shop_loop_item_title', function () {
        $t = am_titre_numero( get_the_title() );
        if ( $t['numero'] ) {
            echo '<span class="surtitre">' . esc_html( $t['numero'] ) . '</span>';
        }
        echo '<h2 class="woocommerce-loop-product__title">' . esc_html( am_insecables( $t['titre'], true ) ) . '</h2>';
    }, 10 );
} );

// Le kiosque montre les numéros du plus récent au plus ancien.
add_filter( 'woocommerce_default_catalog_orderby', function () {
    return 'date';
} );

/** Retire les émojis d'un texte (les fiches des numéros en sont parsemées). */
function am_sans_emojis( string $html ): string {
    return (string) preg_replace( '~[\x{1F000}-\x{1FAFF}\x{2600}-\x{27BF}\x{2B00}-\x{2BFF}\x{FE0F}]\s?~u', '', $html );
}
add_filter( 'the_content', function ( $html ) {
    return ( function_exists( 'is_product' ) && is_product() ) ? am_sans_emojis( (string) $html ) : $html;
}, 12 );
add_filter( 'woocommerce_short_description', 'am_sans_emojis' );

add_filter( 'loop_shop_columns', function () {
    return 3;
} );
add_filter( 'loop_shop_per_page', function () {
    return 24;
} );

// Un numéro numérique s'achète à l'unité : pas de champ « quantité ».
add_filter( 'woocommerce_is_sold_individually', function ( $unite, $produit ) {
    return ( is_object( $produit ) && $produit->is_downloadable() ) ? true : $unite;
}, 10, 2 );

// Les styles de WooCommerce ne servent que sur ses pages : inutile de les charger sur les articles.
add_action( 'wp_enqueue_scripts', function () {
    if ( ! am_kiosque_actif() || ! function_exists( 'is_woocommerce' ) ) {
        return;
    }
    if ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) {
        return;
    }
    foreach ( array( 'woocommerce-general', 'woocommerce-layout', 'woocommerce-smallscreen', 'wc-blocks-style', 'wc-blocks-vendors-style', 'brands-styles' ) as $style ) {
        wp_dequeue_style( $style );
    }
    wp_dequeue_script( 'wc-cart-fragments' );
}, 100 );
