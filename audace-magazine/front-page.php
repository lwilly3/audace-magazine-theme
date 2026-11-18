<?php
/**
 * Accueil : un sujet dominant porté par son titre, puis des sections de formes différentes.
 * Aucun article n'apparaît deux fois ; rien n'est à régler à la main.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
get_header();
$am      = am_accueil();
$numeros = am_numeros( 4 );
$kiosque = am_kiosque_url();
?>
<?php if ( $am['une'] ) : $une = $am['une']; ?>
<section class="une"><div class="cadre">
    <a href="<?php echo esc_url( get_permalink( $une ) ); ?>">
        <?php echo am_surtitre( $une ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
        <h1 class="<?php echo esc_attr( am_palier( get_the_title( $une ) ) ); ?>"><?php echo get_the_title( $une ); // phpcs:ignore WordPress.Security.EscapeOutput ?></h1>
        <div class="grille">
            <div>
                <?php $chapo = am_chapo( $une ); if ( $chapo ) : ?><p class="chapo"><?php echo esc_html( $chapo ); ?></p><?php endif; ?>
                <span class="meta"><?php echo esc_html( am_date( $une ) . ' · ' . am_temps_lecture( $une ) . ' min de lecture' ); ?></span>
            </div>
            <?php echo am_vignette( $une, 'am-une', '(min-width: 1000px) 680px, 100vw', false ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
        </div>
    </a>
    <?php if ( $am['suite'] ) : ?>
    <div class="suite">
        <?php foreach ( $am['suite'] as $p ) : ?>
            <article><a href="<?php echo esc_url( get_permalink( $p ) ); ?>">
                <?php echo am_surtitre( $p ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
                <h2><?php echo get_the_title( $p ); // phpcs:ignore WordPress.Security.EscapeOutput ?></h2>
                <span class="meta"><?php echo esc_html( am_meta_liste( $p ) ); ?></span>
            </a></article>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div></section>
<?php endif; ?>

<?php
// Première rubrique : un article en tête avec son image, puis des lignes de texte.
// Sous le nom de la rubrique, les lignes n'ont pas de surtitre : ce serait une répétition.
if ( ! empty( $am['rubriques'][0] ) ) :
    $bloc = $am['rubriques'][0];
    $tete = array_shift( $bloc['articles'] );
    ?>
<section><div class="cadre">
    <?php am_tete_section( am_nom_rubrique( $bloc['terme']->name ), get_category_link( $bloc['terme'] ), 'Toute la rubrique' ); ?>
    <div class="rubrique">
        <a class="tete" href="<?php echo esc_url( get_permalink( $tete ) ); ?>">
            <?php echo am_vignette( $tete, 'am-carte', '(min-width: 1000px) 660px, 100vw' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
            <h3><?php echo get_the_title( $tete ); // phpcs:ignore WordPress.Security.EscapeOutput ?></h3>
            <p class="chapo"><?php echo esc_html( am_chapo( $tete, false, 24 ) ); ?></p>
            <span class="meta"><?php echo esc_html( am_meta_liste( $tete ) ); ?></span>
        </a>
        <div class="lignes"><?php foreach ( $bloc['articles'] as $p ) { am_ligne( $p, false, false, '', false ); } ?></div>
    </div>
</div></section>
<?php endif; ?>

<?php if ( $numeros ) : $dernier = array_shift( $numeros ); ?>
<section class="magazine" aria-label="Le magazine"><div class="cadre"><div class="grille">
    <div class="dernier">
        <a class="couverture" href="<?php echo esc_url( $dernier['url'] ); ?>"><?php echo am_couverture( $dernier['image'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
        <div>
            <span class="surtitre">Le magazine · dernier numéro</span>
            <h2><?php echo esc_html( am_insecables( $dernier['titre'], true ) ); ?></h2>
            <p><?php echo esc_html( trim( $dernier['numero'] . ( $dernier['resume'] ? ' · ' . wp_trim_words( $dernier['resume'], 22, '…' ) : '' ), ' ·' ) ); ?></p>
            <a class="bouton" href="<?php echo esc_url( $dernier['url'] ); ?>">Voir le numéro</a>
        </div>
    </div>
    <?php if ( $numeros ) : ?>
    <div class="archives">
        <div class="haut"><h3>Les numéros précédents</h3><?php if ( $kiosque ) : ?><a class="lien-fleche" href="<?php echo esc_url( $kiosque ); ?>">Tous les numéros</a><?php endif; ?></div>
        <div class="defile">
            <?php foreach ( $numeros as $n ) : ?>
                <a href="<?php echo esc_url( $n['url'] ); ?>"><div class="couverture"><?php echo am_couverture( $n['image'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div><b><?php echo esc_html( $n['numero'] ?: $n['titre'] ); ?></b></a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div></div></section>
<?php endif; ?>

<?php
// Deux rubriques côte à côte : un grand titre avec image, puis des lignes.
$duo = array_slice( $am['rubriques'], 1, 2 );
if ( $duo ) :
    ?>
<section><div class="cadre"><div class="duo">
    <?php foreach ( $duo as $bloc ) : $tete = array_shift( $bloc['articles'] ); ?>
    <div>
        <?php am_tete_section( am_nom_rubrique( $bloc['terme']->name ), get_category_link( $bloc['terme'] ) ); ?>
        <a class="grand-titre" href="<?php echo esc_url( get_permalink( $tete ) ); ?>">
            <?php echo am_vignette( $tete, 'am-carte', '(min-width: 1000px) 560px, 100vw' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
            <h3><?php echo get_the_title( $tete ); // phpcs:ignore WordPress.Security.EscapeOutput ?></h3>
            <span class="meta"><?php echo esc_html( am_meta_liste( $tete ) ); ?></span>
        </a>
        <div class="lignes separe"><?php foreach ( $bloc['articles'] as $p ) { am_ligne( $p, false, false, '', false ); } ?></div>
    </div>
    <?php endforeach; ?>
</div></div></section>
<?php endif; ?>

<?php if ( $am['reste'] ) : ?>
<section><div class="cadre">
    <?php am_tete_section( 'À lire aussi', add_query_arg( 'liste', '', home_url( '/' ) ), 'Tous les articles' ); ?>
    <div class="rangees">
        <?php foreach ( $am['reste'] as $p ) : ?>
            <article class="rangee"><a href="<?php echo esc_url( get_permalink( $p ) ); ?>">
                <?php echo am_vignette( $p, 'am-carte', '(min-width: 1000px) 240px, 40vw' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
                <div>
                    <?php echo am_surtitre( $p ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
                    <h3><?php echo get_the_title( $p ); // phpcs:ignore WordPress.Security.EscapeOutput ?></h3>
                    <p class="chapo"><?php echo esc_html( am_chapo( $p, false, 20 ) ); ?></p>
                    <span class="meta"><?php echo esc_html( am_meta_liste( $p ) ); ?></span>
                </div>
            </a></article>
        <?php endforeach; ?>
    </div>
</div></section>
<?php endif; ?>
<?php
get_footer();
