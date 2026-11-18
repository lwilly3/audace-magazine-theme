<?php
/**
 * Champ de recherche.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<form class="champ-recherche" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <input type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="Un mot, un nom, un sujet" aria-label="Rechercher dans <?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
    <button class="bouton" type="submit">Chercher</button>
</form>
