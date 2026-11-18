<?php
/**
 * Cadre des pages de WooCommerce : le kiosque (la boutique) et la fiche d'un numéro.
 * Le contenu reste celui de WooCommerce ; le thème n'en change que l'habillage.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
get_header();
?>
<div class="cadre boutique">
    <?php woocommerce_content(); ?>
</div>
<?php
get_footer();
