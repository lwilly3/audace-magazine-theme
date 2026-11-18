<?php
/**
 * Fragments d'affichage partagés par les gabarits.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/** Rubrique principale d'un article : la première qui n'est pas une rubrique écartée. */
function am_rubrique( $post = null ): ?WP_Term {
    $rubriques = get_the_category( get_post( $post )->ID ?? 0 );
    if ( ! $rubriques ) {
        return null;
    }
    $ecartees = am_maison()['rubriques_ecartees'];
    foreach ( $rubriques as $r ) {
        if ( ! in_array( $r->slug, $ecartees, true ) ) {
            return $r;
        }
    }
    return null;
}

/** Surtitre : le nom de la rubrique, lié ou non. Rien si l'article n'a pas de vraie rubrique. */
function am_surtitre( $post = null, bool $lien = false ): string {
    $r = am_rubrique( $post );
    if ( ! $r ) {
        return '';
    }
    $nom = esc_html( am_nom_rubrique( $r->name ) );
    return $lien
        ? '<a class="surtitre" href="' . esc_url( get_category_link( $r ) ) . '">' . $nom . '</a>'
        : '<span class="surtitre">' . $nom . '</span>';
}

/** Date en toutes lettres. */
function am_date( $post = null, bool $annee = true ): string {
    return (string) wp_date( $annee ? 'j F Y' : 'j F', get_post_timestamp( $post ) );
}

/**
 * Ligne d'information d'une liste : la date seulement si l'article a moins de trente
 * jours (au-delà, elle souligne le rythme de parution), puis le temps de lecture.
 */
function am_meta_liste( $post = null ): string {
    $post    = get_post( $post );
    $lecture = am_temps_lecture( $post ) . ' min de lecture';
    $age     = time() - (int) get_post_timestamp( $post );
    return $age < 30 * DAY_IN_SECONDS ? am_date( $post, false ) . ' · ' . $lecture : $lecture;
}

/** Palier de taille d'un grand titre, selon sa longueur. */
function am_palier( string $titre ): string {
    $n = mb_strlen( wp_strip_all_tags( $titre ) );
    return $n <= 55 ? 'court' : ( $n <= 90 ? 'moyen' : 'long' );
}

/**
 * Image à la une, dans un cadre. Chaîne vide si l'article n'en a pas : la mise en page
 * est faite pour s'en passer.
 */
function am_vignette( $post = null, string $taille = 'am-carte', string $sizes = '(min-width: 1000px) 400px, 100vw', bool $differe = true ): string {
    $post = get_post( $post );
    if ( ! $post || ! has_post_thumbnail( $post ) ) {
        return '';
    }
    $img = get_the_post_thumbnail( $post, $taille, array(
        'alt'      => '',
        'sizes'    => $sizes,
        'loading'  => $differe ? 'lazy' : 'eager',
        'decoding' => 'async',
    ) );
    return $img ? '<div class="vignette">' . $img . '</div>' : '';
}

/** Une ligne d'article : surtitre, titre, information, vignette facultative. */
function am_ligne( $post, bool $image = true, bool $dates = false, string $extrait = '', bool $surtitre = true ): void {
    $post = get_post( $post );
    if ( ! $post ) {
        return;
    }
    $meta = $dates ? am_date( $post ) : am_meta_liste( $post );
    echo '<a class="ligne" href="' . esc_url( get_permalink( $post ) ) . '"><div>' . ( $surtitre ? am_surtitre( $post ) : '' ); // phpcs:ignore WordPress.Security.EscapeOutput
    echo '<h3>' . get_the_title( $post ) . '</h3>'; // phpcs:ignore WordPress.Security.EscapeOutput -- déjà échappé par le filtre the_title.
    if ( '' !== $extrait ) {
        echo '<p class="extrait">' . esc_html( $extrait ) . '</p>';
    }
    echo '<span class="meta">' . esc_html( $meta ) . '</span></div>';
    if ( $image ) {
        echo am_vignette( $post, 'thumbnail', '132px' ); // phpcs:ignore WordPress.Security.EscapeOutput
    }
    echo '</a>';
}

/** En-tête de section : un titre, un lien facultatif vers la suite. */
function am_tete_section( string $titre, string $url = '', string $lien = 'Tout voir' ): void {
    echo '<div class="tete-section"><h2>' . esc_html( $titre ) . '</h2>';
    if ( $url ) {
        echo '<a href="' . esc_url( $url ) . '">' . esc_html( $lien ) . ' <span aria-hidden="true">→</span></a>';
    }
    echo '</div>';
}

/** Icônes en ligne. */
function am_icone( string $nom ): string {
    $traits = array(
        'loupe' => '<circle cx="11" cy="11" r="7"/><path d="M20 20l-3.500-3.500"/>',
        'menu'  => '<path d="M4 8h16M4 16h16"/>',
        'croix' => '<path d="M6 6l12 12M18 6L6 18"/>',
        'lune'  => '<path d="M20 14.500A8 8 0 0 1 9.500 4 8 8 0 1 0 20 14.500z"/>',
    );
    $pleins = array(
        'whatsapp' => '<path d="M12 2a10 10 0 0 0-8.600 15.100L2 22l5-1.300A10 10 0 1 0 12 2zm0 2a8 8 0 1 1-4.200 14.800l-.400-.200-2.600.700.700-2.500-.300-.400A8 8 0 0 1 12 4zm-3 4c-.300 0-.600.100-.800.400-.500.600-.900 1.300-.900 2.200 0 2 2.900 5.300 6.500 5.900 1 .100 1.900-.400 2.400-1.100.200-.300.200-.700 0-.900l-1.600-1c-.300-.200-.600-.100-.800.100l-.500.600c-1.300-.600-2.300-1.600-2.900-2.900l.600-.500c.200-.200.300-.500.100-.800L10 8.400c-.200-.300-.600-.400-1-.400z"/>',
        'lien'     => '<path d="M10.600 13.400a1 1 0 0 1 0-1.400l3-3a1 1 0 1 1 1.400 1.400l-3 3a1 1 0 0 1-1.400 0zM7 17a4 4 0 0 1 0-5.700l2-2a1 1 0 0 1 1.400 1.400l-2 2a2 2 0 1 0 2.900 2.900l2-2a1 1 0 0 1 1.400 1.400l-2 2A4 4 0 0 1 7 17zm6.600-8.300a1 1 0 0 1 0-1.400l2-2A4 4 0 1 1 21.300 11l-2 2a1 1 0 0 1-1.400-1.400l2-2a2 2 0 1 0-2.900-2.900l-2 2a1 1 0 0 1-1.400 0z"/>',
    );
    if ( isset( $traits[ $nom ] ) ) {
        return '<svg class="trait" viewBox="0 0 24 24" aria-hidden="true">' . $traits[ $nom ] . '</svg>';
    }
    return isset( $pleins[ $nom ] ) ? '<svg class="plein" viewBox="0 0 24 24" aria-hidden="true">' . $pleins[ $nom ] . '</svg>' : '';
}

/** Boutons de partage d'un article : WhatsApp d'abord. */
function am_partage( $post = null ): string {
    $url   = get_permalink( $post );
    $titre = html_entity_decode( wp_strip_all_tags( get_the_title( $post ) ), ENT_QUOTES, 'UTF-8' );
    $liens = array(
        'whatsapp' => array( 'WhatsApp', 'https://wa.me/?text=' . rawurlencode( $titre . ' ' . $url ) ),
        'facebook' => array( 'Facebook', 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode( $url ) ),
        'x'        => array( 'X', 'https://x.com/intent/post?text=' . rawurlencode( $titre ) . '&url=' . rawurlencode( $url ) ),
    );
    $html = '<div class="partage">';
    foreach ( $liens as $cle => $l ) {
        $html .= '<a class="' . $cle . '" href="' . esc_url( $l[1] ) . '" target="_blank" rel="noopener">' . ( 'whatsapp' === $cle ? am_icone( 'whatsapp' ) : '' ) . esc_html( $l[0] ) . '</a>';
    }
    $html .= '<button type="button" data-copier="' . esc_url( $url ) . '">' . am_icone( 'lien' ) . '<span class="long">Copier le lien</span><span class="court">Lien</span></button></div>';
    return $html;
}

/** Pagination d'une liste. */
function am_pagination(): void {
    $liens = paginate_links( array(
        'mid_size'  => 1,
        'prev_text' => '← Plus récents',
        'next_text' => 'Plus anciens →',
        'type'      => 'array',
    ) );
    if ( $liens ) {
        echo '<nav class="pagination" aria-label="Pages">' . implode( '', $liens ) . '</nav>'; // phpcs:ignore WordPress.Security.EscapeOutput
    }
}

/**
 * Les articles de l'accueil, répartis sans aucun doublon.
 *
 * La une est le dernier article publié ; rien n'est à régler à la main. Les articles
 * épinglés sont ignorés : ils figeraient la page.
 *
 * @return array{une:?WP_Post, suite:WP_Post[], rubriques:array<int, array{terme:WP_Term, articles:WP_Post[]}>, reste:WP_Post[]}
 */
function am_accueil(): array {
    $articles = get_posts( array(
        'numberposts'         => 40,
        'post_status'         => 'publish',
        'ignore_sticky_posts' => true,
        'suppress_filters'    => false,
    ) );
    // Le site contient des articles publiés deux fois : deux articles de même titre n'en font qu'un.
    $vus      = array();
    $articles = array_values( array_filter( $articles, function ( $p ) use ( &$vus ) {
        $cle = mb_strtolower( trim( wp_strip_all_tags( $p->post_title ) ) );
        if ( '' === $cle || isset( $vus[ $cle ] ) ) {
            return false; // Un article sans titre n'a pas sa place sur l'accueil.
        }
        $vus[ $cle ] = true;
        return true;
    } ) );
    $pris = array();
    $prendre = function ( array $candidats, int $n ) use ( &$pris ): array {
        $lot = array();
        foreach ( $candidats as $p ) {
            if ( count( $lot ) >= $n ) {
                break;
            }
            if ( ! isset( $pris[ $p->ID ] ) ) {
                $pris[ $p->ID ] = true;
                $lot[]          = $p;
            }
        }
        return $lot;
    };
    $une   = $prendre( $articles, 1 );
    $suite = $prendre( $articles, 3 );

    // Trois rubriques : les plus fournies parmi les articles récents restants.
    $compte = array();
    foreach ( $articles as $p ) {
        if ( isset( $pris[ $p->ID ] ) ) {
            continue;
        }
        $r = am_rubrique( $p );
        if ( $r ) {
            $compte[ $r->term_id ]['terme']      = $r;
            $compte[ $r->term_id ]['articles'][] = $p;
        }
    }
    uasort( $compte, function ( $a, $b ) {
        return count( $b['articles'] ) <=> count( $a['articles'] );
    } );
    $rubriques = array();
    foreach ( array_slice( $compte, 0, 3 ) as $i => $c ) {
        $lot = $prendre( $c['articles'], 0 === count( $rubriques ) ? 4 : 3 );
        if ( count( $lot ) >= 2 ) {
            $rubriques[] = array( 'terme' => $c['terme'], 'articles' => $lot );
        }
    }
    return array(
        'une'       => $une[0] ?? null,
        'suite'     => $suite,
        'rubriques' => $rubriques,
        'reste'     => $prendre( $articles, 2 ),
    );
}

/**
 * Affiche les articles de la requête en cours, rangés par mois.
 * $sauter : nombre d'articles déjà montrés en tête de page.
 */
function am_liste_par_mois( int $sauter = 0 ): void {
    global $wp_query;
    $mois  = '';
    $ouvert = false;
    foreach ( array_slice( $wp_query->posts, $sauter ) as $p ) {
        $m = (string) wp_date( 'F Y', get_post_timestamp( $p ) );
        if ( $m !== $mois ) {
            if ( $ouvert ) {
                echo '</div>';
            }
            echo '<h2 class="mois">' . esc_html( ucfirst( $m ) ) . '</h2><div class="lignes">';
            $mois   = $m;
            $ouvert = true;
        }
        $post_date = am_date( $p, false );
        // Dans une rubrique, répéter son nom sur chaque ligne n'apprend rien.
        $rubrique  = am_rubrique( $p );
        $surtitre  = ( $rubrique && is_category( $rubrique->term_id ) ) ? '' : am_surtitre( $p );
        echo '<a class="ligne" href="' . esc_url( get_permalink( $p ) ) . '"><div>' . $surtitre; // phpcs:ignore WordPress.Security.EscapeOutput
        echo '<h3>' . get_the_title( $p ) . '</h3><span class="meta">' . esc_html( $post_date ) . '</span></div>'; // phpcs:ignore WordPress.Security.EscapeOutput
        echo am_vignette( $p, 'thumbnail', '132px' ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput
    }
    if ( $ouvert ) {
        echo '</div>';
    }
}
