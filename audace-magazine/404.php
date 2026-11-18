<?php
/**
 * Page introuvable.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
get_header();
$recents = get_posts( array( 'numberposts' => 4, 'ignore_sticky_posts' => true ) );
?>
<div class="cadre"><div class="colonne">
    <header class="tete-page">
        <h1 class="moyen-titre">Cette page n’existe pas, ou n’existe plus.</h1>
        <p>L’adresse a peut-être changé. Cherchez l’article, ou repartez des derniers publiés.</p>
        <?php get_search_form(); ?>
    </header>
    <?php if ( $recents ) : ?>
        <h2 class="mois">Les derniers articles</h2>
        <div class="lignes"><?php foreach ( $recents as $p ) { am_ligne( $p ); } ?></div>
    <?php endif; ?>
</div></div>
<?php
get_footer();
