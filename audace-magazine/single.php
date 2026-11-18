<?php
/**
 * Article : la page où arrivent la plupart des lecteurs. Colonne de lecture étroite,
 * partage WhatsApp en premier, plan pour les longs textes, renvoi vers le magazine.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
get_header();
while ( have_posts() ) :
    the_post();
    $corps   = apply_filters( 'the_content', get_the_content() );
    $plan    = am_plan( (string) $corps );
    $chapo   = am_chapo( null, true );
    $auteur  = get_the_author();
    $anonyme = ! $auteur || false !== stripos( $auteur, 'audace' ) || false !== stripos( $auteur, 'admin' );
    $numeros = am_numeros( 1 );
    $legende = get_the_post_thumbnail_caption();
    ?>
<div class="progression" aria-hidden="true"></div>
<article class="article"><div class="cadre">
    <header class="tete">
        <?php echo am_surtitre( null, true ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
        <h1 class="<?php echo esc_attr( am_palier( get_the_title() ) ); ?>"><?php the_title(); ?></h1>
        <?php if ( $chapo ) : ?><p class="chapo"><?php echo esc_html( $chapo ); ?></p><?php endif; ?>
        <div class="signature">
            <b><?php echo esc_html( $anonyme ? 'La rédaction' : $auteur ); ?></b><span>·</span>
            <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( am_date() ); ?></time><span>·</span>
            <span><?php echo esc_html( am_temps_lecture() ); ?> min</span>
        </div>
        <?php echo am_partage(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
    </header>
    <?php if ( has_post_thumbnail() ) : ?>
    <figure>
        <?php
        // L'image n'est jamais affichée plus grande que le fichier d'origine : le navigateur choisit
        // parmi les tailles disponibles, et la feuille de style ne l'étire pas.
        echo '<div class="vignette">' . get_the_post_thumbnail( null, 'am-carte', array( 'sizes' => '(min-width: 760px) 720px, 100vw', 'loading' => 'eager', 'fetchpriority' => 'high' ) ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput
        ?>
        <?php if ( $legende ) : ?><figcaption><?php echo esc_html( $legende ); ?></figcaption><?php endif; ?>
    </figure>
    <?php endif; ?>
    <?php if ( $plan ) : ?>
    <div class="tete">
        <details class="plan">
            <summary>Dans cet article</summary>
            <ol>
                <?php foreach ( $plan as $partie ) : ?>
                    <li><a href="#<?php echo esc_attr( $partie['id'] ); ?>"><?php echo esc_html( $partie['titre'] ); ?></a></li>
                <?php endforeach; ?>
            </ol>
        </details>
    </div>
    <?php endif; ?>
    <div class="corps"><?php echo $corps; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
    <div class="fin">
        <?php echo am_partage(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
        <?php if ( $numeros ) : $n = $numeros[0]; ?>
        <a class="renvoi" href="<?php echo esc_url( $n['url'] ); ?>">
            <div class="couverture"><?php echo am_couverture( $n['image'], '92px' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
            <div><span class="surtitre">Le magazine</span><b>Les dossiers de fond sont dans le magazine</b><span class="lien">Voir le dernier numéro<?php echo $n['numero'] ? ' (' . esc_html( $n['numero'] ) . ')' : ''; ?></span></div>
        </a>
        <?php endif; ?>
    </div>
</div>
<?php
    // À lire aussi : trois articles de la même rubrique, sinon les plus récents.
    $rubrique = am_rubrique();
    $proches  = get_posts( array(
        'numberposts'         => 3,
        'post__not_in'        => array( get_the_ID() ),
        'category__in'        => $rubrique ? array( $rubrique->term_id ) : array(),
        'ignore_sticky_posts' => true,
    ) );
    if ( count( $proches ) < 3 ) {
        $proches = array_merge( $proches, get_posts( array(
            'numberposts'         => 3 - count( $proches ),
            'post__not_in'        => array_merge( array( get_the_ID() ), wp_list_pluck( $proches, 'ID' ) ),
            'ignore_sticky_posts' => true,
        ) ) );
    }
    if ( $proches ) :
        ?>
<section><div class="cadre etroit">
    <?php am_tete_section( 'À lire aussi' ); ?>
    <div class="lignes"><?php foreach ( $proches as $p ) { am_ligne( $p ); } ?></div>
</div></section>
    <?php endif; ?>
</article>
<div class="barre-partage">
    <a class="bouton" href="<?php echo esc_url( 'https://wa.me/?text=' . rawurlencode( html_entity_decode( wp_strip_all_tags( get_the_title() ), ENT_QUOTES, 'UTF-8' ) . ' ' . get_permalink() ) ); ?>" target="_blank" rel="noopener"><?php echo am_icone( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>Partager sur WhatsApp</a>
    <button class="bouton contour" type="button" data-copier="<?php echo esc_url( get_permalink() ); ?>" data-partage="<?php echo esc_attr( wp_strip_all_tags( get_the_title() ) ); ?>" aria-label="Partager ou copier le lien"><?php echo am_icone( 'lien' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span>Lien</span></button>
</div>
    <?php
endwhile;
get_footer();
