<?php
/**
 * Rubrique, étiquette, auteur, date : le nom en grand, un article en tête,
 * puis les articles rangés par mois.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
get_header();
global $wp_query;
$objet = get_queried_object();
$titre = is_category() ? am_nom_rubrique( single_cat_title( '', false ) ) : wp_strip_all_tags( get_the_archive_title() );
$titre = (string) preg_replace( '~^[^:]{3,20}\s:\s~u', '', $titre ); // « Étiquette : Énergie » devient « Énergie ».
$desc  = ( $objet instanceof WP_Term ) ? trim( wp_strip_all_tags( $objet->description ) ) : '';
$total = (int) $wp_query->found_posts;
$tete  = ( ! is_paged() && have_posts() ) ? $wp_query->posts[0] : null;
?>
<div class="cadre">
    <header class="tete-page">
        <h1><?php echo esc_html( $titre ); ?></h1>
        <?php if ( $desc ) : ?><p><?php echo esc_html( $desc ); ?></p><?php endif; ?>
        <span class="compte"><?php echo esc_html( sprintf( _n( '%d article', '%d articles', $total, 'audace-magazine' ), $total ) ); ?></span>
    </header>
    <?php if ( $tete ) : ?>
    <a class="premier" href="<?php echo esc_url( get_permalink( $tete ) ); ?>">
        <h2><?php echo get_the_title( $tete ); // phpcs:ignore WordPress.Security.EscapeOutput ?></h2>
        <div class="grille">
            <div>
                <p class="chapo"><?php echo esc_html( am_chapo( $tete ) ); ?></p>
                <span class="meta"><?php echo esc_html( am_date( $tete ) . ' · ' . am_temps_lecture( $tete ) . ' min de lecture' ); ?></span>
            </div>
            <?php echo am_vignette( $tete, 'am-une', '(min-width: 1000px) 680px, 100vw', false ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
        </div>
    </a>
    <?php endif; ?>
    <div class="colonne">
        <?php
        if ( have_posts() ) {
            am_liste_par_mois( $tete ? 1 : 0 );
            am_pagination();
        } else {
            echo '<p class="vide">Aucun article pour le moment.</p>';
        }
        ?>
    </div>
</div>
<?php
get_footer();
