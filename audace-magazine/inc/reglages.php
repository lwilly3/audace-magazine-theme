<?php
/**
 * Supports du thème, feuilles de style, scripts, menu et accueil.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'after_setup_theme', function () {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'custom-logo', array( 'flex-width' => true, 'flex-height' => true ) );
    add_theme_support( 'woocommerce' );
    add_image_size( 'am-carte', 800, 0 );
    add_image_size( 'am-une', 1600, 0 );
    register_nav_menus( array(
        'principal' => 'Rubriques (au moins trois rubriques, sinon le menu automatique)',
    ) );
} );

/** Coordonnées de la maison. Un seul endroit, filtrable. Un lien laissé vide n'est pas affiché. */
function am_maison(): array {
    return apply_filters( 'am_maison', array(
        'devise'   => 'L’Afrique autrement',
        'accroche' => 'L’Afrique autrement : politique, économie et société, du Cameroun et du continent.',
        'courriel' => 'magazine@audacemediagroup.com',
        'radio'    => 'https://radioaudace.com/ecouter/',
        'editeur'  => 'Audace Media Group',
        // Rubriques qui ne sont ni au menu automatique ni utilisées comme surtitre.
        'rubriques_ecartees' => array( 'non-classe', 'nos-articles', 'derniere-heure', 'uncategorized' ),
    ) );
}

add_action( 'wp_enqueue_scripts', function () {
    $v   = AUDACE_MAGAZINE_THEME_VERSION;
    $uri = get_template_directory_uri();
    wp_enqueue_style( 'audace-magazine-polices', $uri . '/assets/css/polices.css', array(), $v );
    wp_enqueue_style( 'audace-magazine', $uri . '/assets/css/theme.css', array( 'audace-magazine-polices' ), $v );
    wp_enqueue_script( 'audace-magazine', $uri . '/assets/js/theme.js', array(), $v, array( 'in_footer' => true, 'strategy' => 'defer' ) );
} );

remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'wp_generator' );

add_action( 'wp_head', function () {
    // Mode clair ou sombre, posé avant le premier affichage pour éviter un éclair blanc.
    echo "<script>(function(){var t=null;try{t=localStorage.getItem('audace-theme')}catch(e){}" .
        "document.documentElement.dataset.theme=t||(matchMedia('(prefers-color-scheme: dark)').matches?'sombre':'clair')})();</script>\n";
    // La police de texte, préchargée : évite un saut de mise en page au premier affichage.
    printf(
        '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
        esc_url( get_template_directory_uri() . '/assets/fonts/source-serif-4-latin.woff2' )
    );
}, 1 );

add_filter( 'body_class', function ( array $classes ): array {
    $classes[] = 'am';
    return $classes;
} );

/**
 * Vrai sur la racine du site, première page.
 *
 * Leçon du thème radio : les réglages de lecture hérités d'Extra font que WordPress
 * peut ne pas reconnaître la racine comme « page d'accueil ». On s'appuie donc aussi
 * sur la requête : liste des articles, première page, sans objet ni recherche.
 */
function am_est_accueil(): bool {
    if ( isset( $_GET['liste'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification -- « Tous les articles » : la liste, pas l'accueil.
        return false;
    }
    if ( is_front_page() && ! is_paged() ) {
        return true;
    }
    return is_home() && ! is_paged() && ! get_queried_object_id() && ! is_search();
}

add_filter( 'template_include', function ( string $gabarit ): string {
    if ( am_est_accueil() ) {
        $accueil = locate_template( 'front-page.php' );
        if ( $accueil ) {
            return $accueil;
        }
    } elseif ( is_home() || isset( $_GET['liste'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
        $liste = locate_template( 'index.php' );
        if ( $liste ) {
            return $liste;
        }
    }
    return $gabarit;
}, 20 );

// Listes : douze articles par page ; la recherche ne porte que sur les articles.
add_action( 'pre_get_posts', function ( WP_Query $requete ) {
    if ( is_admin() || ! $requete->is_main_query() ) {
        return;
    }
    if ( $requete->is_home() || $requete->is_search() || $requete->is_category() || $requete->is_tag() || $requete->is_author() || $requete->is_date() ) {
        $requete->set( 'posts_per_page', 12 );
        $requete->set( 'ignore_sticky_posts', true ); // Les articles épinglés figeraient les listes.
    }
    if ( $requete->is_search() && ! $requete->get( 'post_type' ) ) {
        $requete->set( 'post_type', 'post' );
    }
} );

add_filter( 'excerpt_length', function () {
    return 30;
} );
add_filter( 'excerpt_more', function () {
    return '…';
} );

/**
 * Rubriques du menu : le menu « Rubriques » s'il est réglé, sinon les rubriques
 * les plus fournies (au moins trois articles, sept au plus).
 *
 * @return array<int, array{nom:string, url:string, courant:bool}>
 */
function am_rubriques_menu(): array {
    static $menu = null;
    if ( null !== $menu ) {
        return $menu;
    }
    $menu         = array();
    $ecartees     = am_maison()['rubriques_ecartees'];
    $emplacements = get_nav_menu_locations();
    if ( ! empty( $emplacements['principal'] ) ) {
        // Du menu réglé, on ne garde que les rubriques, sous-éléments compris : le kiosque et le
        // compte ont leur place ailleurs dans l'en-tête. En changeant de thème, WordPress affecte
        // souvent tout seul l'ancien menu à cet emplacement ; s'il ne contient pas au moins trois
        // rubriques, c'est le menu automatique qui s'affiche.
        foreach ( (array) wp_get_nav_menu_items( $emplacements['principal'] ) as $e ) {
            if ( 'category' !== $e->object ) {
                continue;
            }
            $terme = get_term( (int) $e->object_id, 'category' );
            if ( ! $terme || is_wp_error( $terme ) || in_array( $terme->slug, $ecartees, true ) ) {
                continue;
            }
            $menu[] = array(
                'nom'     => am_nom_rubrique( $e->title ),
                'url'     => $e->url,
                'courant' => is_category( (int) $e->object_id ),
            );
        }
        if ( count( $menu ) >= 3 ) {
            return $menu;
        }
        $menu = array();
    }
    $rubriques = get_categories( array( 'orderby' => 'count', 'order' => 'DESC', 'hide_empty' => true ) );
    foreach ( $rubriques as $r ) {
        if ( in_array( $r->slug, $ecartees, true ) || $r->count < 3 || count( $menu ) >= 7 ) {
            continue;
        }
        $menu[] = array(
            'nom'     => am_nom_rubrique( $r->name ),
            'url'     => get_category_link( $r ),
            'courant' => is_category( $r->term_id ),
        );
    }
    return $menu;
}

/** Nom d'une rubrique tel qu'on l'affiche : « Economie » saisi sans accent devient « Économie ». */
function am_nom_rubrique( string $nom ): string {
    $corrections = apply_filters( 'am_noms_rubriques', array(
        'Economie'        => 'Économie',
        'Societe'         => 'Société',
        'Sante'           => 'Santé',
        'Dernières Heure' => 'Dernière heure',
    ) );
    return $corrections[ $nom ] ?? $nom;
}
