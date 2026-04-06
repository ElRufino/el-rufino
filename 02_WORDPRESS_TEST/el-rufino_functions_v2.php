<?php
/**
 * El Rufino — functions.php
 * Tema hijo de Newsup
 * Versión 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* =====================================================
   1. ENQUEUE: ESTILOS PADRE + HIJO + TIPOGRAFÍAS
===================================================== */
add_action( 'wp_enqueue_scripts', 'er_enqueue_styles', 20 );
function er_enqueue_styles() {

    // Tema padre Newsup
    wp_enqueue_style(
        'newsup-parent',
        get_template_directory_uri() . '/style.css',
        array(),
        wp_get_theme( 'newsup' )->get( 'Version' )
    );

    // Tipografías Google Fonts
    wp_enqueue_style(
        'er-google-fonts',
        'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,400&family=Source+Serif+4:ital,wght@0,300;0,400;0,600;1,400&family=Inter:wght@400;500;600;700&display=swap',
        array(),
        null
    );

    // Child theme style.css
    wp_enqueue_style(
        'el-rufino-child',
        get_stylesheet_uri(),
        array( 'newsup-parent', 'er-google-fonts' ),
        wp_get_theme()->get( 'Version' )
    );
}

/* =====================================================
   2. TAMAÑOS DE IMAGEN
===================================================== */
add_action( 'after_setup_theme', 'er_image_sizes' );
function er_image_sizes() {
    add_image_size( 'er-card',      780, 440, true );   // Cards grilla
    add_image_size( 'er-featured', 1200, 630, true );   // Nota destacada / OG
    add_image_size( 'er-thumb',     400, 300, true );   // Thumbnail sidebar
    add_image_size( 'er-wide',     1440, 500, true );   // Banner ancho
}

/* =====================================================
   3. MENÚS
===================================================== */
add_action( 'after_setup_theme', 'er_register_menus' );
function er_register_menus() {
    register_nav_menus( array(
        'primary'   => __( 'Menú principal', 'el-rufino' ),
        'topbar'    => __( 'Topbar', 'el-rufino' ),
        'footer'    => __( 'Footer', 'el-rufino' ),
    ) );
}

/* =====================================================
   4. SOPORTE DEL TEMA
===================================================== */
add_action( 'after_setup_theme', 'er_theme_support' );
function er_theme_support() {
    add_theme_support( 'custom-logo', array(
        'height'      => 140,
        'width'       => 140,
        'flex-height' => true,
        'flex-width'  => true,
    ) );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'gallery', 'caption' ) );
    add_theme_support( 'editor-styles' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'responsive-embeds' );
}

/* =====================================================
   5. WIDGETS — ÁREAS DE SIDEBAR Y FOOTER
===================================================== */
add_action( 'widgets_init', 'er_widgets_init' );
function er_widgets_init() {

    $defaults = array(
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    );

    register_sidebar( array_merge( $defaults, array(
        'name'        => __( 'Sidebar principal', 'el-rufino' ),
        'id'          => 'sidebar-1',
        'description' => 'Sidebar derecho de la home y notas',
    ) ) );

    register_sidebar( array_merge( $defaults, array(
        'name'        => __( 'Footer — Columna 1', 'el-rufino' ),
        'id'          => 'footer-1',
    ) ) );

    register_sidebar( array_merge( $defaults, array(
        'name'        => __( 'Footer — Columna 2', 'el-rufino' ),
        'id'          => 'footer-2',
    ) ) );

    register_sidebar( array_merge( $defaults, array(
        'name'        => __( 'Footer — Columna 3', 'el-rufino' ),
        'id'          => 'footer-3',
    ) ) );
}

/* =====================================================
   6. TOPBAR CON FECHA Y BOTÓN WHATSAPP
===================================================== */
add_action( 'wp_body_open', 'er_render_topbar', 5 );
function er_render_topbar() {
    $meses = array(
        1=>'enero',2=>'febrero',3=>'marzo',4=>'abril',
        5=>'mayo',6=>'junio',7=>'julio',8=>'agosto',
        9=>'septiembre',10=>'octubre',11=>'noviembre',12=>'diciembre'
    );
    $dias = array(
        'Sunday'=>'Domingo','Monday'=>'Lunes','Tuesday'=>'Martes',
        'Wednesday'=>'Miércoles','Thursday'=>'Jueves','Friday'=>'Viernes','Saturday'=>'Sábado'
    );
    $dia_en  = date('l');
    $dia_es  = $dias[$dia_en];
    $fecha   = $dia_es . ' ' . date('j') . ' de ' . $meses[(int)date('n')] . ' de ' . date('Y');
    $wa_url  = get_option( 'er_whatsapp_canal', 'https://whatsapp.com/channel/elrufino' );
    ?>
    <div class="er-topbar">
        <span class="er-topbar-date"><?php echo esc_html( $fecha ); ?> · Rufino, Santa Fe</span>
        <a href="<?php echo esc_url( $wa_url ); ?>" class="er-topbar-wa" target="_blank" rel="noopener">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                <path d="M12 0C5.373 0 0 5.373 0 12c0 2.125.558 4.116 1.533 5.845L0 24l6.335-1.521A11.945 11.945 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.027-1.382l-.36-.214-3.76.902.938-3.67-.234-.376A9.818 9.818 0 1112 21.818z"/>
            </svg>
            Canal WA
        </a>
    </div>
    <?php
}

/* =====================================================
   7. MASTHEAD PERSONALIZADO (reemplaza el de Newsup)
===================================================== */
add_action( 'wp_body_open', 'er_render_masthead', 10 );
function er_render_masthead() {
    $logo_url = get_option( 'er_logo_url', get_stylesheet_directory_uri() . '/assets/el-rufino_logo-escudo_v2.svg' );
    $wa_url   = get_option( 'er_whatsapp_canal', 'https://whatsapp.com/channel/elrufino' );
    ?>
    <header class="er-masthead">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="er-masthead-logo" rel="home">
            <img src="<?php echo esc_url( $logo_url ); ?>"
                 alt="El Rufino"
                 class="er-logo-img"
                 width="68" height="68">
            <div class="er-logo-texto">
                <span class="er-logo-el">El</span>
                <span class="er-logo-rufino">Rufino</span>
                <span class="er-logo-claim">Lo que pasa y lo que significa</span>
            </div>
        </a>
        <div class="er-masthead-actions">
            <a href="<?php echo esc_url( $wa_url ); ?>" class="er-btn-wa" target="_blank" rel="noopener">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                    <path d="M12 0C5.373 0 0 5.373 0 12c0 2.125.558 4.116 1.533 5.845L0 24l6.335-1.521A11.945 11.945 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.027-1.382l-.36-.214-3.76.902.938-3.67-.234-.376A9.818 9.818 0 1112 21.818z"/>
                </svg>
                Suscribirse al WA
            </a>
        </div>
    </header>
    <?php
}

/* =====================================================
   8. TICKER DE ÚLTIMAS NOTICIAS
===================================================== */
add_action( 'wp_body_open', 'er_render_ticker', 15 );
function er_render_ticker() {
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => 6,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    );
    $query = new WP_Query( $args );
    if ( ! $query->have_posts() ) return;

    echo '<div class="er-ticker">';
    echo '<div class="er-ticker-label">Últimas</div>';
    echo '<div class="er-ticker-track"><div class="er-ticker-inner">';

    $items = array();
    while ( $query->have_posts() ) {
        $query->the_post();
        $items[] = '<span class="er-ticker-item"><span class="er-ticker-dot"></span>' . esc_html( get_the_title() ) . '</span>';
    }
    wp_reset_postdata();

    // Duplicar para loop continuo
    $all = implode( '', $items );
    echo $all . $all;

    echo '</div></div></div>';
}

/* =====================================================
   9. OPCIONES BÁSICAS EN CUSTOMIZER
===================================================== */
add_action( 'customize_register', 'er_customizer_settings' );
function er_customizer_settings( $wp_customize ) {

    $wp_customize->add_section( 'er_opciones', array(
        'title'    => 'El Rufino — Opciones',
        'priority' => 30,
    ) );

    // URL Logo SVG
    $wp_customize->add_setting( 'er_logo_url', array(
        'default'   => '',
        'transport' => 'refresh',
    ) );
    $wp_customize->add_control( 'er_logo_url', array(
        'label'   => 'URL del logo SVG',
        'section' => 'er_opciones',
        'type'    => 'text',
    ) );

    // URL Canal WhatsApp
    $wp_customize->add_setting( 'er_whatsapp_canal', array(
        'default'   => 'https://whatsapp.com/channel/elrufino',
        'transport' => 'refresh',
    ) );
    $wp_customize->add_control( 'er_whatsapp_canal', array(
        'label'   => 'URL Canal WhatsApp',
        'section' => 'er_opciones',
        'type'    => 'url',
    ) );
}

/* =====================================================
   10. EXCERPT LENGTH
===================================================== */
add_filter( 'excerpt_length', function() { return 28; }, 999 );
add_filter( 'excerpt_more',   function() { return '…'; } );

/* =====================================================
   11. SCHEMA.ORG — NewsMediaOrganization
===================================================== */
add_action( 'wp_head', 'er_schema_org', 1 );
function er_schema_org() {
    if ( ! is_front_page() && ! is_home() ) return;
    $schema = array(
        '@context'  => 'https://schema.org',
        '@type'     => 'NewsMediaOrganization',
        'name'      => 'El Rufino',
        'url'       => 'https://elrufino.ar',
        'logo'      => array(
            '@type' => 'ImageObject',
            'url'   => get_stylesheet_directory_uri() . '/assets/el-rufino_logo-escudo_v2.svg',
        ),
        'sameAs'    => array(
            'https://www.facebook.com/elrufino',
            'https://www.instagram.com/elrufino',
            'https://www.tiktok.com/@elrufino',
        ),
        'areaServed' => array(
            '@type' => 'City',
            'name'  => 'Rufino',
        ),
        'foundingDate' => '2026',
        'description'  => 'Lo que pasa y lo que significa. Medio digital local de Rufino, Santa Fe, Argentina.',
    );
    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
