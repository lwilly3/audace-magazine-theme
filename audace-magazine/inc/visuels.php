<?php
/**
 * Images de partage composées par le thème (1200 × 630, le format des fiches de WhatsApp,
 * Facebook et X) :
 *  - le kiosque : les couvertures des derniers numéros, en éventail ;
 *  - l'accueil : le logo, le titre de l'article à la une et son image.
 *
 * Chaque image est fabriquée une fois, rangée dans wp-content/uploads/audace-magazine/, puis
 * refaite toute seule quand son contenu change (nouveau numéro, nouvel article à la une).
 * Si le serveur ne sait pas dessiner (extension GD absente) ou si quelque chose échoue, le
 * thème se rabat sur son image fixe : rien ne casse.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Image de partage d'une page : « kiosque » ou « accueil ».
 *
 * @return array{url:string, largeur:int, hauteur:int}|null
 */
function am_visuel( string $type ): ?array {
    if ( ! function_exists( 'imagecreatetruecolor' ) || ! function_exists( 'imagettftext' ) || get_transient( 'am_visuel_echec_' . $type ) ) {
        return null;
    }
    $donnees = 'kiosque' === $type ? am_visuel_donnees_kiosque() : am_visuel_donnees_accueil();
    if ( ! $donnees ) {
        return null;
    }
    $envoi   = wp_upload_dir();
    $dossier = trailingslashit( $envoi['basedir'] ) . 'audace-magazine';
    $nom     = 'partage-' . $type . '-' . substr( md5( AUDACE_MAGAZINE_THEME_VERSION . wp_json_encode( $donnees ) ), 0, 12 ) . '.jpg';
    $fichier = $dossier . '/' . $nom;
    if ( ! file_exists( $fichier ) ) {
        $image = null;
        try {
            if ( wp_mkdir_p( $dossier ) ) {
                $image = 'kiosque' === $type ? am_visuel_dessiner_kiosque( $donnees ) : am_visuel_dessiner_accueil( $donnees );
            }
        } catch ( Throwable $e ) {
            $image = null;
        }
        if ( ! $image || ! imagejpeg( $image, $fichier, 84 ) ) {
            set_transient( 'am_visuel_echec_' . $type, 1, HOUR_IN_SECONDS ); // On ne réessaie pas à chaque visite.
            return null;
        }
        // Les anciennes versions de cette image ne servent plus.
        foreach ( (array) glob( $dossier . '/partage-' . $type . '-*.jpg' ) as $ancien ) {
            if ( $ancien !== $fichier ) {
                wp_delete_file( $ancien );
            }
        }
    }
    return array(
        'url'     => trailingslashit( $envoi['baseurl'] ) . 'audace-magazine/' . $nom,
        'largeur' => 1200,
        'hauteur' => 630,
    );
}

/** Chemin du fichier d'une image de la médiathèque, dans une taille raisonnable (jamais l'original géant). */
function am_visuel_chemin( int $id ): string {
    $envoi = wp_upload_dir();
    foreach ( array( 'large', 'medium_large', 'am-carte' ) as $taille ) {
        $t = image_get_intermediate_size( $id, $taille );
        if ( $t && ! empty( $t['path'] ) && file_exists( trailingslashit( $envoi['basedir'] ) . $t['path'] ) ) {
            return trailingslashit( $envoi['basedir'] ) . $t['path'];
        }
    }
    $original = (string) get_attached_file( $id );
    return ( $original && file_exists( $original ) && filesize( $original ) < 6 * MB_IN_BYTES ) ? $original : '';
}

/** Ce dont dépend l'image du kiosque : les cinq derniers numéros et leurs couvertures. */
function am_visuel_donnees_kiosque(): ?array {
    $couvertures = array();
    foreach ( am_numeros( 5 ) as $n ) {
        $chemin = $n['image'] ? am_visuel_chemin( $n['image'] ) : '';
        if ( $chemin ) {
            $couvertures[] = array( $chemin, (int) filemtime( $chemin ) );
        }
    }
    return count( $couvertures ) >= 2 ? array( 'couvertures' => $couvertures ) : null;
}

/** Ce dont dépend l'image de l'accueil : l'article à la une et les trois suivants. */
function am_visuel_donnees_accueil(): ?array {
    $accueil = am_accueil();
    $une     = $accueil['une'];
    if ( ! $une ) {
        return null;
    }
    $rubrique = am_rubrique( $une );
    $image    = has_post_thumbnail( $une ) ? am_visuel_chemin( (int) get_post_thumbnail_id( $une ) ) : '';
    $suite    = array();
    foreach ( $accueil['suite'] as $p ) {
        $r       = am_rubrique( $p );
        $suite[] = array( $r ? am_nom_rubrique( $r->name ) : '', am_titre_propre( $p->post_title ) );
    }
    return array(
        'titre'    => am_titre_propre( $une->post_title ),
        'rubrique' => $rubrique ? am_nom_rubrique( $rubrique->name ) : '',
        'image'    => $image,
        'date'     => $image ? (int) filemtime( $image ) : 0,
        'suite'    => $suite,
    );
}

/** Ouvre une image, quel que soit son format. */
function am_visuel_ouvrir( string $chemin ) {
    $octets = @file_get_contents( $chemin ); // phpcs:ignore WordPress.PHP.NoSilencedErrors, WordPress.WP.AlternativeFunctions
    $image  = $octets ? @imagecreatefromstring( $octets ) : false; // phpcs:ignore WordPress.PHP.NoSilencedErrors
    if ( ! $image ) {
        return null;
    }
    imagepalettetotruecolor( $image );
    return $image;
}

/** Une toile de 1200 × 630 de la couleur donnée. */
function am_visuel_toile( array $rvb ) {
    $toile = imagecreatetruecolor( 1200, 630 );
    imagealphablending( $toile, true );
    imagefilledrectangle( $toile, 0, 0, 1200, 630, imagecolorallocate( $toile, $rvb[0], $rvb[1], $rvb[2] ) );
    return $toile;
}

/** Pose le logo du magazine, à la largeur voulue. Retourne sa hauteur. */
function am_visuel_logo( $toile, int $x, int $y, int $largeur ): int {
    $logo = am_visuel_ouvrir( get_template_directory() . '/assets/img/logo.png' );
    if ( ! $logo ) {
        return 0;
    }
    $hauteur = (int) round( $largeur * imagesy( $logo ) / imagesx( $logo ) );
    imagecopyresampled( $toile, $logo, $x, $y, 0, 0, $largeur, $hauteur, imagesx( $logo ), imagesy( $logo ) );
    return $hauteur;
}

/** Texte prêt à dessiner : la police embarquée n'a pas les espaces fines ni le trait d'union insécable. */
function am_visuel_texte_simple( string $texte ): string {
    $texte = html_entity_decode( wp_strip_all_tags( $texte ), ENT_QUOTES, 'UTF-8' );
    return trim( strtr( $texte, array( "\u{202F}" => ' ', "\u{00A0}" => ' ', "\u{2011}" => '-', "\u{2009}" => ' ' ) ) );
}

/**
 * Écrit un texte sur plusieurs lignes, dans une largeur donnée. Retourne l'ordonnée sous la dernière ligne.
 * Au-delà de $max lignes, le texte est coupé et terminé par des points de suspension.
 */
function am_visuel_ecrire( $toile, string $texte, int $x, int $y, int $largeur, float $taille, array $rvb, int $max = 6, float $interligne = 1.12 ): int {
    $police  = get_template_directory() . '/assets/fonts/bricolage-grotesque-800.ttf';
    $couleur = imagecolorallocate( $toile, $rvb[0], $rvb[1], $rvb[2] );
    $mesure  = function ( string $t ) use ( $taille, $police ): int {
        $b = imagettfbbox( $taille, 0, $police, $t );
        return (int) abs( $b[2] - $b[0] );
    };
    $lignes = array();
    $ligne  = '';
    foreach ( preg_split( '~\s+~u', am_visuel_texte_simple( $texte ) ) as $mot ) {
        $essai = '' === $ligne ? $mot : $ligne . ' ' . $mot;
        if ( '' !== $ligne && $mesure( $essai ) > $largeur ) {
            $lignes[] = $ligne;
            $ligne    = $mot;
        } else {
            $ligne = $essai;
        }
    }
    if ( '' !== $ligne ) {
        $lignes[] = $ligne;
    }
    if ( count( $lignes ) > $max ) {
        $lignes = array_slice( $lignes, 0, $max );
        $fin    = $lignes[ $max - 1 ];
        while ( '' !== $fin && $mesure( $fin . '…' ) > $largeur ) {
            $fin = (string) preg_replace( '~\s*\S+$~u', '', $fin );
        }
        $lignes[ $max - 1 ] = rtrim( $fin, ' :,;' ) . '…';
    }
    $pas = (int) round( $taille * 1.333 * $interligne ); // La taille est en points : 1 pt = 1,333 px.
    foreach ( $lignes as $l ) {
        $y += $pas;
        imagettftext( $toile, $taille, 0, $x, $y, $couleur, $police, $l );
    }
    return $y;
}

/** Pose une image tournée, centrée sur un point, avec une ombre portée. */
function am_visuel_poser( $toile, $source, int $cx, int $cy, int $largeur, int $hauteur, float $angle ): void {
    $carte = imagecreatetruecolor( $largeur, $hauteur );
    imagecopyresampled( $carte, $source, 0, 0, 0, 0, $largeur, $hauteur, imagesx( $source ), imagesy( $source ) );
    $ombre = imagecreatetruecolor( $largeur, $hauteur );
    imagealphablending( $ombre, false );
    imagefilledrectangle( $ombre, 0, 0, $largeur, $hauteur, imagecolorallocatealpha( $ombre, 0, 0, 0, 96 ) );
    foreach ( array( array( $ombre, 10, 14 ), array( $ombre, 4, 6 ), array( $carte, 0, 0 ) ) as $couche ) {
        $tournee = imagerotate( $couche[0], $angle, imagecolorallocatealpha( $couche[0], 0, 0, 0, 127 ) );
        imagealphablending( $toile, true );
        imagecopy( $toile, $tournee, (int) ( $cx - imagesx( $tournee ) / 2 + $couche[1] ), (int) ( $cy - imagesy( $tournee ) / 2 + $couche[2] ), 0, 0, imagesx( $tournee ), imagesy( $tournee ) );
    }
}

/** Le kiosque : le logo et un titre à gauche, les couvertures en éventail à droite, la plus récente devant. */
function am_visuel_dessiner_kiosque( array $donnees ) {
    $toile = am_visuel_toile( array( 244, 242, 238 ) );
    imagefilledrectangle( $toile, 0, 0, 1200, 12, imagecolorallocate( $toile, 226, 7, 28 ) );
    $bas = 78 + am_visuel_logo( $toile, 60, 78, 300 );
    $y   = am_visuel_ecrire( $toile, 'Le kiosque', 62, $bas + 34, 380, 46, array( 15, 15, 15 ), 2 );
    am_visuel_ecrire( $toile, 'Tous les numéros du magazine', 64, $y + 10, 340, 19, array( 90, 88, 82 ), 3, 1.25 );

    // Du plus ancien au plus récent, de gauche à droite : le dernier numéro est posé en dernier, donc devant.
    $couvertures = array_reverse( $donnees['couvertures'] );
    $n           = count( $couvertures );
    $milieu      = ( $n - 1 ) / 2;
    $pas         = $n > 1 ? min( 104, (int) ( 416 / ( $n - 1 ) ) ) : 0;
    foreach ( $couvertures as $i => $c ) {
        $source = am_visuel_ouvrir( $c[0] );
        if ( ! $source ) {
            continue;
        }
        $ecart   = $i - $milieu;
        $hauteur = 372;
        $largeur = (int) round( $hauteur * imagesx( $source ) / imagesy( $source ) );
        am_visuel_poser( $toile, $source, (int) ( 790 + $ecart * $pas ), (int) ( 318 + $ecart * $ecart * 12 ), $largeur, $hauteur, -$ecart * 6 );
    }
    return $toile;
}

/**
 * L'accueil en miniature, comme la page elle-même : le logo, la rubrique et le titre de la une
 * à gauche, son image à droite, puis les trois articles suivants sous un filet.
 */
function am_visuel_dessiner_accueil( array $donnees ) {
    $toile  = am_visuel_toile( array( 255, 255, 255 ) );
    $noir   = imagecolorallocate( $toile, 15, 15, 15 );
    $source = $donnees['image'] ? am_visuel_ouvrir( $donnees['image'] ) : null;
    $suite  = array_slice( $donnees['suite'], 0, 3 );
    $bas    = $suite ? 408 : 600;        // Où s'arrête la partie haute.
    $texte  = $source ? 452 : 1080;      // Sans image, le titre court sur toute la largeur.
    imagefilledrectangle( $toile, 0, 0, 1200, 12, imagecolorallocate( $toile, 226, 7, 28 ) );
    if ( $source ) {
        // L'image de la une, recadrée au centre dans son cadre.
        $pl = 596; $ph = $bas - 62;
        $r  = max( $pl / imagesx( $source ), $ph / imagesy( $source ) );
        $sl = (int) round( $pl / $r ); $sh = (int) round( $ph / $r );
        imagecopyresampled( $toile, $source, 548, 44, (int) ( ( imagesx( $source ) - $sl ) / 2 ), (int) ( ( imagesy( $source ) - $sh ) / 2 ), $pl, $ph, $sl, $sh );
    }
    $y = 44 + am_visuel_logo( $toile, 52, 44, 210 ) + 22;
    if ( $donnees['rubrique'] ) {
        $y = am_visuel_ecrire( $toile, mb_strtoupper( $donnees['rubrique'] ), 58, $y, $texte, 13, array( 226, 7, 28 ), 1 ) + 2;
    }
    $long   = mb_strlen( $donnees['titre'] );
    $taille = $source ? ( $long > 95 ? 23 : ( $long > 60 ? 27 : 33 ) ) : ( $long > 95 ? 36 : 46 );
    am_visuel_ecrire( $toile, $donnees['titre'], 56, $y, $texte, $taille, array( 15, 15, 15 ), $source ? 6 : 4, 1.1 );
    if ( $suite ) {
        // Les articles suivants, en trois colonnes sous un filet noir, comme sur la page.
        imagefilledrectangle( $toile, 56, $bas + 14, 1144, $bas + 16, $noir );
        $colonne = (int) ( ( 1088 - 2 * 36 ) / 3 );
        foreach ( $suite as $i => $article ) {
            $x = 56 + $i * ( $colonne + 36 );
            $y = $bas + 26;
            if ( $article[0] ) {
                $y = am_visuel_ecrire( $toile, mb_strtoupper( $article[0] ), $x + 1, $y, $colonne, 11, array( 226, 7, 28 ), 1 ) + 2;
            }
            am_visuel_ecrire( $toile, $article[1], $x, $y, $colonne, 16, array( 15, 15, 15 ), 4, 1.14 );
        }
    }
    return $toile;
}
