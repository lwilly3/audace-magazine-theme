<?php
/**
 * Pied de page.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$am_maison  = am_maison();
$am_kiosque = am_kiosque_url();
$am_legales = array_filter( array(
    get_privacy_policy_url() ? array( 'Données personnelles', get_privacy_policy_url() ) : null,
) );
?>
</main>
<footer class="pied"><div class="cadre">
    <div class="marque">
        <a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/logo.png' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="1024" height="399" loading="lazy"></a>
        <p><?php echo esc_html( $am_maison['accroche'] ); ?></p>
        <?php if ( $am_maison['radio'] ) : ?>
            <a class="lien-radio" href="<?php echo esc_url( $am_maison['radio'] ); ?>" target="_blank" rel="noopener">Radio Audace 106.8 FM <span aria-hidden="true">↗</span></a>
        <?php endif; ?>
    </div>
    <div>
        <h2>Rubriques</h2>
        <ul>
            <?php foreach ( am_rubriques_menu() as $r ) : ?>
                <li><a href="<?php echo esc_url( $r['url'] ); ?>"><?php echo esc_html( $r['nom'] ); ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php if ( $am_kiosque ) : ?>
        <div>
            <h2>Le magazine</h2>
            <ul>
                <li><a href="<?php echo esc_url( $am_kiosque ); ?>">Tous les numéros</a></li>
                <li><a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">Mon compte</a></li>
            </ul>
        </div>
    <?php endif; ?>
    <div>
        <h2>La rédaction</h2>
        <ul>
            <?php if ( $am_maison['courriel'] ) : ?>
                <li><a href="mailto:<?php echo esc_attr( antispambot( $am_maison['courriel'] ) ); ?>">Écrire à la rédaction</a></li>
            <?php endif; ?>
            <?php foreach ( $am_legales as $l ) : ?>
                <li><a href="<?php echo esc_url( $l[1] ); ?>"><?php echo esc_html( $l[0] ); ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="bas">© <?php echo esc_html( wp_date( 'Y' ) . ' ' . $am_maison['editeur'] ); ?> · Audace Magazine et Radio Audace 106.8 FM</div>
</div></footer>
<?php wp_footer(); ?>
</body>
</html>
