<?php
/**
 * Page ordinaire, et pages du panier, de la commande et du compte.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
get_header();
while ( have_posts() ) :
    the_post();
    ?>
<article class="page-simple"><div class="cadre">
    <header class="tete-page"><h1 class="moyen-titre"><?php the_title(); ?></h1></header>
    <div class="corps contenu-page"><?php the_content(); ?></div>
</div></article>
    <?php
endwhile;
get_footer();
