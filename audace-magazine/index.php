<?php
/**
 * Liste de tous les articles (pages suivantes de l'accueil, « Tous les articles »),
 * et gabarit de repli de WordPress.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
get_header();
global $wp_query;
?>
<div class="cadre"><div class="colonne">
    <header class="tete-page">
        <h1 class="moyen-titre">Tous les articles</h1>
        <span class="compte"><?php echo esc_html( sprintf( _n( '%d article', '%d articles', (int) $wp_query->found_posts, 'audace-magazine' ), (int) $wp_query->found_posts ) ); ?></span>
    </header>
    <?php
    if ( have_posts() ) {
        am_liste_par_mois();
        am_pagination();
    } else {
        echo '<p class="vide">Aucun article pour le moment.</p>';
    }
    ?>
</div></div>
<?php
get_footer();
