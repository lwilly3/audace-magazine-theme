<?php
/**
 * Recherche dans les articles.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
get_header();
global $wp_query;
$demande = trim( get_search_query( false ) );
$total   = (int) $wp_query->found_posts;
?>
<div class="cadre"><div class="colonne">
    <header class="tete-page">
        <h1 class="petit">Recherche</h1>
        <?php get_search_form(); ?>
        <?php if ( '' !== $demande ) : ?>
            <span class="compte"><?php echo esc_html( $total ? sprintf( _n( '%d article', '%d articles', $total, 'audace-magazine' ), $total ) : 'Aucun article ne correspond.' ); ?></span>
        <?php endif; ?>
    </header>
    <?php if ( '' !== $demande && have_posts() ) : ?>
        <div class="lignes resultats">
            <?php
            foreach ( $wp_query->posts as $p ) {
                am_ligne( $p, true, true, wp_strip_all_tags( am_chapo( $p, false, 26 ) ) );
            }
            ?>
        </div>
        <?php am_pagination(); ?>
    <?php elseif ( '' !== $demande ) : ?>
        <p class="vide">Essayez un autre mot, ou parcourez les rubriques.</p>
    <?php endif; ?>
</div></div>
<?php
get_footer();
