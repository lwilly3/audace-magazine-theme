<?php
/**
 * En-tête : une seule ligne. Logo, rubriques, recherche, mode sombre, magazine, menu.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$am_rubriques = am_rubriques_menu();
$am_kiosque   = am_kiosque_url();
$am_maison    = am_maison();
$am_logo      = has_custom_logo()
    ? wp_get_attachment_image( (int) get_theme_mod( 'custom_logo' ), 'medium', false, array( 'alt' => get_bloginfo( 'name' ) ) )
    : '<img src="' . esc_url( get_template_directory_uri() . '/assets/img/logo.png' ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" width="1024" height="399">';
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="evitement" href="#contenu">Aller au contenu</a>
<header class="entete"><div class="cadre">
    <a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>, accueil"><?php echo $am_logo; // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
    <nav class="rubriques" aria-label="Rubriques"><ul>
        <?php foreach ( $am_rubriques as $r ) : ?>
            <li><a href="<?php echo esc_url( $r['url'] ); ?>"<?php echo $r['courant'] ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $r['nom'] ); ?></a></li>
        <?php endforeach; ?>
    </ul></nav>
    <a class="icone recherche" href="<?php echo esc_url( home_url( '/?s=' ) ); ?>" aria-label="Rechercher"><?php echo am_icone( 'loupe' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
    <button class="icone theme" type="button" data-theme-bascule aria-label="Passer en mode clair ou sombre"><?php echo am_icone( 'lune' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></button>
    <?php if ( $am_kiosque ) : ?>
        <a class="bouton" href="<?php echo esc_url( $am_kiosque ); ?>">Le magazine</a>
    <?php endif; ?>
    <button class="icone menu" type="button" aria-label="Ouvrir le menu" aria-controls="tiroir" aria-expanded="false"><?php echo am_icone( 'menu' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></button>
</div></header>
<div class="tiroir" id="tiroir" hidden>
    <div class="haut">
        <a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo $am_logo; // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
        <button class="icone fermer" type="button" aria-label="Fermer le menu"><?php echo am_icone( 'croix' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></button>
    </div>
    <ul>
        <?php foreach ( $am_rubriques as $r ) : ?>
            <li><a href="<?php echo esc_url( $r['url'] ); ?>"><?php echo esc_html( $r['nom'] ); ?></a></li>
        <?php endforeach; ?>
    </ul>
    <?php if ( $am_kiosque ) : ?>
        <div class="actions"><a class="bouton grand" href="<?php echo esc_url( $am_kiosque ); ?>">Le magazine</a></div>
    <?php endif; ?>
    <div class="secondaires">
        <?php if ( $am_maison['radio'] ) : ?>
            <a class="lien-radio" href="<?php echo esc_url( $am_maison['radio'] ); ?>" target="_blank" rel="noopener">Radio Audace 106.8 FM <span aria-hidden="true">↗</span></a>
        <?php endif; ?>
        <a href="<?php echo esc_url( home_url( '/?s=' ) ); ?>">Rechercher</a>
        <?php if ( am_kiosque_actif() ) : ?>
            <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">Mon compte</a>
        <?php endif; ?>
        <button type="button" data-theme-bascule>Mode clair / sombre</button>
    </div>
</div>
<main id="contenu">
