<?php
/**
 * Nettoyage des titres et du corps des articles, à l'affichage.
 *
 * Rien de tout cela ne modifie la base : les articles restent tels que la rédaction
 * les a saisis, y compris les anciens articles construits avec Divi.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/** Retire les shortcodes de Divi et d'Extra, qui s'afficheraient en clair sans ces thèmes. */
function am_sans_shortcodes( string $html ): string {
    return (string) preg_replace( '~\[/?et_pb_[^\]]*\]~', '', $html );
}

/** Vrai si le texte est saisi tout en capitales. */
function am_en_capitales( string $texte ): bool {
    $lettres = (string) preg_replace( '~[^\p{L}]~u', '', $texte );
    if ( mb_strlen( $lettres ) < 16 ) {
        return false;
    }
    $capitales = (string) preg_replace( '~[^\p{Lu}]~u', '', $lettres );
    return mb_strlen( $capitales ) / mb_strlen( $lettres ) > 0.8;
}

/**
 * Remet en casse normale un titre saisi en capitales.
 *
 * C'est une approximation : les sigles et les noms propres connus retrouvent leur
 * forme, les autres mots passent en minuscules. La liste est filtrable ; le plus
 * sûr reste de corriger le titre dans WordPress.
 */
function am_casse_normale( string $titre ): string {
    $connus = apply_filters( 'am_mots_a_majuscule', array(
        'Cameroun', 'Afrique', 'Douala', 'Yaoundé', 'Tchad', 'France', 'Maroc', 'Sénégal', 'Gabon', 'Congo', 'Nigeria', 'Kribi',
        'Paul', 'Biya', 'Madame', 'Monsieur', 'Europe', 'Chine', 'Russie', 'Sahel', 'Centrafrique', 'Côte', 'Ivoire',
        'MRC', 'MINAT', 'CAN', 'CAF', 'CEMAC', 'BEAC', 'SCDP', 'ENEO', 'SOCADEL', 'FCFA', 'IPO', 'OMC', 'PIB', 'CRTV',
        'FNE', 'SGS', 'IA', 'ONU', 'UA', 'RDPC', 'FMI', 'USA', 'RDC', 'OMS', 'PME', 'TVA', 'CEDEAO', 'FIFA', 'MIFI',
    ) );
    $titre = mb_strtolower( $titre );
    foreach ( $connus as $mot ) {
        $titre = (string) preg_replace( '~(?<![\p{L}])' . preg_quote( mb_strtolower( $mot ), '~' ) . '(?![\p{L}])~u', $mot, $titre );
    }
    // Majuscule au début et après un point, un point d'interrogation ou d'exclamation (pas après un deux-points).
    $titre = (string) preg_replace_callback( '~(^|[.?!]\s+)(\p{Ll})~u', function ( $m ) {
        return $m[1] . mb_strtoupper( $m[2] );
    }, $titre );
    return $titre;
}

/**
 * Titre lisible : balises et astérisques retirés, caractères « gras » Unicode ramenés
 * à des lettres normales, émojis retirés, capitales remises en casse normale.
 */
function am_titre_propre( string $titre ): string {
    $titre = html_entity_decode( wp_strip_all_tags( $titre ), ENT_QUOTES, 'UTF-8' );
    if ( class_exists( 'Normalizer' ) ) {
        $n = Normalizer::normalize( $titre, Normalizer::FORM_KC );
        if ( is_string( $n ) ) {
            $titre = $n;
        }
    }
    $titre = (string) preg_replace( '~[\x{FFFC}\x{FFFD}\x{200B}-\x{200D}\x{FE0F}]~u', '', $titre );
    $titre = (string) preg_replace( '~[\x{1F000}-\x{1FAFF}\x{2600}-\x{27BF}]~u', '', $titre );
    $titre = (string) preg_replace( '~\*{2,}~', '', $titre );
    $titre = trim( (string) preg_replace( '~\s+~u', ' ', $titre ) );
    $titre = rtrim( $titre, '.' );
    if ( am_en_capitales( $titre ) ) {
        $titre = am_casse_normale( $titre );
    }
    return am_insecables( $titre, true );
}

add_filter( 'the_title', function ( $titre, $id = 0 ) {
    if ( is_admin() || ! $id || 'post' !== get_post_type( $id ) ) {
        return $titre;
    }
    return esc_html( am_titre_propre( (string) $titre ) );
}, 10, 2 );

// Le titre de l'onglet et des fiches de partage suit le même nettoyage.
add_filter( 'single_post_title', function ( $titre, $post = null ) {
    return $post && 'post' === $post->post_type ? am_titre_propre( (string) $titre ) : $titre;
}, 10, 2 );

/**
 * Espaces insécables de la typographie française, hors balises : aucune ligne ne
 * commence par « : », « ? », « ! », « ; » ou « » ». Dans un titre, les traits
 * d'union ne coupent pas la ligne non plus.
 */
function am_insecables( string $html, bool $titre = false ): string {
    $morceaux = preg_split( '~(<[^>]+>)~', $html, -1, PREG_SPLIT_DELIM_CAPTURE );
    $ignore   = 0;
    foreach ( $morceaux as $i => $m ) {
        if ( '' === $m ) {
            continue;
        }
        if ( '<' === $m[0] ) {
            if ( preg_match( '~^<(pre|code|script|style|textarea)\b~i', $m ) ) {
                $ignore++;
            } elseif ( preg_match( '~^</(pre|code|script|style|textarea)\b~i', $m ) ) {
                $ignore = max( 0, $ignore - 1 );
            }
            continue;
        }
        if ( $ignore ) {
            continue;
        }
        $m = preg_replace( '~[ \x{00A0}]([!?;])~u', "\u{202F}$1", $m );
        $m = preg_replace( '~ ([:»%])~u', "\u{00A0}$1", $m );
        $m = preg_replace( '~« ~u', "«\u{00A0}", $m );
        $m = preg_replace( '~(\d) (h|min|FCFA|milliards?|millions?|ans|morts|jours|MW)(?![\p{L}])~u', "$1\u{00A0}$2", $m );
        if ( $titre ) {
            $m = preg_replace( '~(?<=\S)-(?=\S)~u', "\u{2011}", $m );
        }
        $morceaux[ $i ] = $m;
    }
    return implode( '', $morceaux );
}

/**
 * Corps d'article.
 *  - shortcodes Divi retirés ;
 *  - signature répétée en tête du texte (« Audace Magazine | Rubrique | date ») retirée ;
 *  - h1 du corps (doublon du titre) rétrogradé en h2 ;
 *  - balises vides retirées ;
 *  - citation courte transformée en exergue ;
 *  - tableau rendu défilable sur téléphone ;
 *  - intertitres numérotés, pour le plan de l'article ;
 *  - espaces insécables.
 */
function am_corps( string $html ): string {
    $html = am_sans_shortcodes( $html );
    $html = preg_replace( '~<p\b[^>]*>\s*(?:<(?:strong|b|em)>\s*)*Audace Magazine\s*\|[^<]{0,160}(?:</(?:strong|b|em)>\s*)*</p>~iu', '', $html, 1 );
    $html = preg_replace( '~<(/?)h1\b~i', '<$1h2', (string) $html );
    for ( $i = 0; $i < 3; $i++ ) { // Plusieurs passes : un vide peut en révéler un autre.
        $html = preg_replace( '~<(p|h[2-6]|li|blockquote|div|span|strong|em)\b[^>]*>(?:\s|&nbsp;|\x{00A0}|<br\s*/?>)*</\1>~iu', '', (string) $html );
    }
    $html = preg_replace_callback( '~<blockquote\b([^>]*)>(.*?)</blockquote>~is', function ( $m ) {
        $texte = trim( wp_strip_all_tags( $m[2] ) );
        $court = mb_strlen( $texte ) <= 220 && substr_count( $m[2], '<p' ) <= 1 && false === stripos( $m[2], '<cite' );
        if ( $court && false === strpos( $m[1], 'exergue' ) ) {
            return '<blockquote class="exergue">' . $m[2] . '</blockquote>';
        }
        return $m[0];
    }, (string) $html );
    $html = preg_replace( '~(<table\b.*?</table>)~is', '<div class="tableau">$1</div>', (string) $html );
    $n    = 0;
    $html = preg_replace_callback( '~<h2\b([^>]*)>~i', function ( $m ) use ( &$n ) {
        $n++;
        return false !== stripos( $m[1], ' id=' ) ? $m[0] : '<h2 id="partie-' . $n . '"' . $m[1] . '>';
    }, (string) $html );
    return am_insecables( (string) $html );
}

add_filter( 'the_content', function ( $html ) {
    if ( is_admin() || is_feed() ) {
        return $html;
    }
    // Hors article, on se contente de retirer les restes de Divi : les pages du panier,
    // de la commande et du compte portent des shortcodes et des blocs qu'il ne faut pas toucher.
    return is_singular( 'post' ) ? am_corps( (string) $html ) : am_sans_shortcodes( (string) $html );
}, 20 );

// Sans Divi, ses shortcodes ne doivent apparaître nulle part (flux, extraits automatiques).
add_filter( 'the_content', 'am_sans_shortcodes', 1 );
add_filter( 'get_the_excerpt', 'am_sans_shortcodes', 1 );

/**
 * Plan d'un article, tiré de ses intertitres. Rien en dessous de trois intertitres.
 *
 * @return array<int, array{id:string, titre:string}>
 */
function am_plan( string $html ): array {
    if ( ! preg_match_all( '~<h2\b[^>]*\bid="([^"]+)"[^>]*>(.*?)</h2>~is', $html, $m, PREG_SET_ORDER ) || count( $m ) < 3 ) {
        return array();
    }
    $plan = array();
    foreach ( $m as $h ) {
        $titre = trim( wp_strip_all_tags( $h[2] ) );
        if ( '' !== $titre ) {
            $plan[] = array( 'id' => $h[1], 'titre' => $titre );
        }
    }
    return count( $plan ) >= 3 ? $plan : array();
}

/**
 * Chapô d'un article.
 *
 * En tête d'article ($strict), c'est l'extrait rédigé par la rédaction, s'il existe et
 * reste court ; sinon rien, et la page ne casse pas. Dans les listes, à défaut d'extrait,
 * on prend le début du texte.
 */
function am_chapo( $post = null, bool $strict = false, int $mots = 30 ): string {
    $post = get_post( $post );
    if ( ! $post ) {
        return '';
    }
    $extrait = trim( wp_strip_all_tags( am_sans_shortcodes( $post->post_excerpt ) ) );
    if ( $strict ) {
        if ( '' === $extrait || mb_strlen( $extrait ) > 240 ) {
            return '';
        }
    } elseif ( '' === $extrait ) {
        $texte   = preg_replace( '~<h[1-6]\b.*?</h[1-6]>~is', ' ', am_sans_shortcodes( $post->post_content ) );
        $texte   = preg_replace( '~Audace Magazine\s*\|[^<\n]{0,160}~u', ' ', wp_strip_all_tags( (string) $texte ) );
        $extrait = wp_trim_words( trim( (string) $texte ), $mots, '…' );
    }
    $extrait = html_entity_decode( $extrait, ENT_QUOTES, 'UTF-8' );
    $extrait = trim( (string) preg_replace( '~\s+~u', ' ', $extrait ) );
    if ( ! $strict ) {
        $extrait = wp_trim_words( $extrait, $mots, '…' );
    }
    return am_insecables( $extrait );
}

/** Temps de lecture, en minutes. */
function am_temps_lecture( $post = null ): int {
    $post = get_post( $post );
    if ( ! $post ) {
        return 1;
    }
    $mots = str_word_count( remove_accents( wp_strip_all_tags( am_sans_shortcodes( $post->post_content ) ) ) );
    return max( 1, (int) round( $mots / 200 ) );
}
