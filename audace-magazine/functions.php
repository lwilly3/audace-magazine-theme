<?php
/**
 * Thème Audace Magazine.
 *
 * Le thème n'attend rien de la rédaction au-delà d'un article ordinaire : titre,
 * texte, image à la une, rubrique. Tout ce qui dépend d'une extension (WooCommerce
 * pour le kiosque) se dégrade proprement si l'extension est absente.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Version : toujours identique à l'en-tête de style.css (release.sh le vérifie).
define( 'AUDACE_MAGAZINE_THEME_VERSION', '0.1.3' );

require get_template_directory() . '/inc/reglages.php';  // Supports, feuilles de style, scripts, accueil.
require get_template_directory() . '/inc/contenu.php';   // Nettoyage des titres et du corps des articles, restes de Divi.
require get_template_directory() . '/inc/gabarits.php';  // Fragments d'affichage partagés.
require get_template_directory() . '/inc/kiosque.php';   // Le magazine : intégration de WooCommerce.
require get_template_directory() . '/inc/partage.php';   // Description et fiche de partage (WhatsApp, Facebook, X).
require get_template_directory() . '/inc/miseajour.php'; // Mises à jour depuis les releases GitHub.
