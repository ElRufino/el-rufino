<?php
/**
 * El Rufino — Child Theme functions.php v2.0.0
 * Design System v1.1 · Abril 2026
 * Tema hijo para Newsup. Si el tema padre cambia, actualizar Template en style.css.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ── Enqueue parent + child styles ──
add_action( 'wp_enqueue_scripts', 'er_theme_enqueue' );
function er_theme_enqueue() {
    // Parent theme
    wp_enqueue_style(
        'parent-style',
        get_template_directory_uri() . '/style.css',
        [],
        wp_get_theme( get_template() )->get('Version')
    );
    // Child theme (overrides)
    wp_enqueue_style(
        'child-style',
        get_stylesheet_uri(),
        ['parent-style'],
        wp_get_theme()->get('Version')
    );
    // Google Fonts — solo Source Serif 4 (Playfair Display carga desde archivos locales)
    wp_enqueue_style(
        'er-fonts',
        'https://fonts.googleapis.com/css2?family=Source+Serif+4:ital,wght@0,300;0,400;0,600;1,300;1,400&display=swap',
        [],
        null
    );
}

// ── Theme supports ──
add_action( 'after_setup_theme', 'er_theme_setup' );
function er_theme_setup() {
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'html5', ['search-form','comment-form','comment-list','gallery','caption'] );
    add_theme_support( 'custom-logo', [
        'height'      => 80,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support( 'customize-selective-refresh-widgets' );

    // Gutenberg — paleta del Design System
    add_theme_support( 'editor-color-palette', [
        [ 'name' => 'Rojo El Rufino', 'slug' => 'er-rojo',  'color' => '#c0271b' ],
        [ 'name' => 'Negro',          'slug' => 'er-negro', 'color' => '#1a1a1a' ],
        [ 'name' => 'Crema',          'slug' => 'er-crema', 'color' => '#f5f0e8' ],
        [ 'name' => 'P02 Campo',      'slug' => 'er-p02',   'color' => '#4a7c59' ],
        [ 'name' => 'P03 Barrio',     'slug' => 'er-p03',   'color' => '#2d5f8a' ],
        [ 'name' => 'P04 Generación', 'slug' => 'er-p04',   'color' => '#7b4fa6' ],
        [ 'name' => 'P06 Datos',      'slug' => 'er-p06',   'color' => '#c8600a' ],
    ]);
    add_theme_support( 'disable-custom-gradients' );

    // Image sizes
    add_image_size( 'er-featured',   780, 440, true );
    add_image_size( 'er-card',       400, 250, true );
    add_image_size( 'er-thumbnail',  160, 100, true );
    add_image_size( 'er-wide',      1200, 500, true );

    // Navigation menus
    register_nav_menus([
        'primary'   => 'Menú principal',
        'secondary' => 'Menú secundario',
        'footer'    => 'Menú footer',
    ]);
}

// ── Disable XML-RPC (security) ──
add_filter( 'xmlrpc_enabled', '__return_false' );

// ── Remove unnecessary head tags ──
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

// ── Masthead crema — DS v1.1 ──
add_action( 'wp_body_open', 'er_render_masthead', 1 );
function er_render_masthead() {
    $wa = get_option( 'er_whatsapp_canal', 'https://whatsapp.com/channel/elrufino' );
    $fecha = date_i18n( 'l j \d\e F \d\e Y' );
    echo '<div class="er-topbar">
        <span>' . esc_html( $fecha ) . '</span>
        <span class="er-topbar-loc">Rufino · Santa Fe · Argentina</span>
    </div>
    <div class="er-masthead">
        <a href="' . esc_url( home_url('/') ) . '" class="er-masthead-logo">
            <div class="er-logo-r"><span>R</span></div>
            <div class="er-logo-texto">
                <span class="er-logo-nombre">El Rufino</span>
                <span class="er-logo-claim">Lo que pasa y lo que significa</span>
            </div>
        </a>
        <a href="' . esc_url( $wa ) . '" class="er-btn-wa" target="_blank" rel="noopener">📲 WhatsApp</a>
    </div>';
}

// ── Ticker negro con label rojo — DS v1.1 ──
add_action( 'wp_body_open', 'er_render_ticker', 2 );
function er_render_ticker() {
    $q = new WP_Query([
        'posts_per_page' => 6,
        'post_status'    => 'publish',
        'no_found_rows'  => true,
    ]);
    if ( ! $q->have_posts() ) return;
    $items = '';
    while ( $q->have_posts() ) {
        $q->the_post();
        $items .= '<span class="er-ticker-item">'
                . '<span class="er-ticker-dot"></span>'
                . esc_html( get_the_title() )
                . '</span>';
    }
    wp_reset_postdata();
    echo '<div class="er-nav-divider"></div>
    <div class="er-ticker">
        <div class="er-ticker-label">Último momento</div>
        <div class="er-ticker-track">
            <div class="er-ticker-inner">' . $items . $items . '</div>
        </div>
    </div>';
}

// ── Shortcode er_wa_subscribe — DS v1.1 ──
// Uso: [er_wa_subscribe texto="..." link="https://..." btn="Unirme"]
add_shortcode( 'er_wa_subscribe', 'er_wa_subscribe_shortcode' );
function er_wa_subscribe_shortcode( $atts ) {
    $atts = shortcode_atts([
        'texto' => 'Recibí las noticias de Rufino directo en tu celular.',
        'link'  => get_option( 'er_whatsapp_canal', '#' ),
        'btn'   => 'Unirme al canal',
    ], $atts );
    return '<div class="er-wa-banner">
        <div class="er-wa-texto">' . esc_html( $atts['texto'] ) . '</div>
        <a href="' . esc_url( $atts['link'] ) . '" class="er-btn-wa" target="_blank" rel="noopener">
            📲 ' . esc_html( $atts['btn'] ) . '
        </a>
    </div>';
}

// ── Shortcode er_seguimiento — DS v1.1 ──
// Uso: [er_seguimiento estado="pendiente"]
add_shortcode( 'er_seguimiento', 'er_seguimiento_shortcode' );
function er_seguimiento_shortcode( $atts ) {
    $atts   = shortcode_atts(['estado' => 'en seguimiento'], $atts);
    $clases = [
        'cumplida'       => 'es-cumplida',
        'incumplida'     => 'es-incumplida',
        'en proceso'     => 'es-en-curso',
        'en seguimiento' => 'es-seguimiento',
        'pendiente'      => 'es-pendiente',
    ];
    $cls = $clases[ strtolower( $atts['estado'] ) ] ?? 'es-pendiente';
    return '<span class="er-p05-estado ' . esc_attr( $cls ) . '">'
         . esc_html( $atts['estado'] ) . '</span>';
}

// ── Color de pilar según categoría ──
function er_get_pilar_color( $post_id = null ) {
    $cats = get_the_category( $post_id );
    if ( ! $cats ) return '#c0271b';
    $slug   = $cats[0]->slug;
    $colores = [
        'rufino-real'       => '#c0271b',
        'el-campo-habla'    => '#4a7c59',
        'barrio-a-barrio'   => '#2d5f8a',
        'generacion-rufino' => '#7b4fa6',
        'poder-y-gestion'   => '#1a1a1a',
        'rufino-en-datos'   => '#c8600a',
    ];
    return $colores[ $slug ] ?? '#c0271b';
}

// ── Two-cap rule reminder in editor ──
add_action( 'admin_footer-post.php', 'er_editor_reminder' );
add_action( 'admin_footer-post-new.php', 'er_editor_reminder' );
function er_editor_reminder() { ?>
    <script>
    (function(){
        var bar = document.createElement('div');
        bar.innerHTML = '<strong>REGLA DE 2 CAPAS:</strong> ¿La nota tiene (1) lo que pasó + (2) lo que significa? Sin segunda capa, no se publica.';
        bar.style.cssText = 'position:fixed;bottom:0;left:160px;right:0;background:#c0271b;color:#fff;padding:8px 20px;font-size:12px;z-index:9999;text-align:center';
        document.body.appendChild(bar);
    })();
    </script>
<?php }

// ── Security headers ──
add_action( 'send_headers', 'er_security_headers' );
function er_security_headers() {
    header( 'X-Content-Type-Options: nosniff' );
    header( 'X-Frame-Options: SAMEORIGIN' );
    header( 'Referrer-Policy: strict-origin-when-cross-origin' );
}

// ── Custom excerpt ──
add_filter( 'excerpt_length', function() { return 30; } );
add_filter( 'excerpt_more',   function() { return '...'; } );

// ── Suppress PHP notices en frontend ──
if ( ! WP_DEBUG ) {
    error_reporting( E_ERROR | E_PARSE );
    @ini_set( 'display_errors', 0 );
}

// ── Meta boxes: pie de foto + fuente ──
add_action('add_meta_boxes', 'er_add_foto_metabox');
function er_add_foto_metabox() {
    add_meta_box('er_foto_data', 'Imagen destacada — datos', 'er_foto_metabox_html', 'post', 'normal', 'high');
}
function er_foto_metabox_html($post) {
    wp_nonce_field('er_foto_nonce','er_foto_nonce');
    $pie    = get_post_meta($post->ID, '_er_pie_foto', true);
    $fuente = get_post_meta($post->ID, '_er_fuente_foto', true);
    echo '<p><label style="font-weight:600;display:block;margin-bottom:4px;">Pie de foto</label>';
    echo '<input type="text" name="er_pie_foto" value="' . esc_attr($pie) . '" style="width:100%" placeholder="Descripción de la imagen"></p>';
    echo '<p><label style="font-weight:600;display:block;margin-bottom:4px;">Fuente / Crédito</label>';
    echo '<input type="text" name="er_fuente_foto" value="' . esc_attr($fuente) . '" style="width:100%" placeholder="Ej: Municipalidad de Rufino / El Rufino"></p>';
}
add_action('save_post', 'er_save_foto_meta');
function er_save_foto_meta($post_id) {
    if (!isset($_POST['er_foto_nonce']) || !wp_verify_nonce($_POST['er_foto_nonce'],'er_foto_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (isset($_POST['er_pie_foto']))    update_post_meta($post_id,'_er_pie_foto',    sanitize_text_field($_POST['er_pie_foto']));
    if (isset($_POST['er_fuente_foto'])) update_post_meta($post_id,'_er_fuente_foto', sanitize_text_field($_POST['er_fuente_foto']));
}

// ── Fecha en español en portada y archivos ──
add_filter('get_the_date', 'er_fecha_espanol', 10, 2);
add_filter('the_date',     'er_fecha_espanol', 10, 2);
function er_fecha_espanol($date, $format) {
    $meses_en = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    $meses_es = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
    $meses_en_upper = array_map('strtoupper', $meses_en);
    $meses_es_upper = ['ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE'];
    $date = str_replace($meses_en_upper, $meses_es_upper, $date);
    $date = str_replace($meses_en, $meses_es, $date);
    return $date;
}

// ── Traducir strings en inglés del tema Newsup ──
add_filter('gettext', 'er_traducir_newsup', 20, 3);
function er_traducir_newsup($translated, $original, $domain) {
    $map = [
        'You missed'    => 'También te puede interesar',
        'Read More'     => 'Leer más',
        'Leave a Reply' => 'Dejá tu comentario',
        'Search'        => 'Buscar',
        'Recent Posts'  => 'Últimas notas',
        'Categories'    => 'Categorías',
        'Tags'          => 'Etiquetas',
        'Comments'      => 'Comentarios',
        'Posted in'     => 'En',
        'by'            => 'por',
        'Posted on'     => '',
        'min read'      => 'min de lectura',
    ];
    return $map[$original] ?? $translated;
}

// ── Fecha portada en español (date_i18n de Newsup) ──
add_filter('date_i18n', 'er_fix_fecha_portada', 10, 4);
function er_fix_fecha_portada($dateformatstring, $unixtimestamp, $gmt, $translate) {
    $meses_en = ['January','February','March','April','May','June','July','August','September','October','November','December','Jan','Feb','Mar','Apr','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    $meses_es = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre','ene','feb','mar','abr','jun','jul','ago','sep','oct','nov','dic'];
    $meses_up_en = array_map('strtoupper', $meses_en);
    $meses_up_es = ['ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE','ENE','FEB','MAR','ABR','JUN','JUL','AGO','SEP','OCT','NOV','DIC'];
    $dateformatstring = str_replace($meses_up_en, $meses_up_es, $dateformatstring);
    $dateformatstring = str_replace($meses_en, $meses_es, $dateformatstring);
    return $dateformatstring;
}

// ── Fecha portada: reescribir formato "mayo 3, 2026" → "3 de mayo de 2026" ──
add_filter('the_time', 'er_reformat_fecha_portada', 10, 2);
add_filter('get_the_time', 'er_reformat_fecha_portada', 10, 2);
function er_reformat_fecha_portada($time, $format = '') {
    $meses = [
        'January'=>'enero','February'=>'febrero','March'=>'marzo','April'=>'abril',
        'May'=>'mayo','June'=>'junio','July'=>'julio','August'=>'agosto',
        'September'=>'septiembre','October'=>'octubre','November'=>'noviembre','December'=>'diciembre'
    ];
    // Detectar formato "Month D, YYYY" o "Month D, YYYY"
    if (preg_match('/^([A-Za-z]+)\s+(\d{1,2}),\s+(\d{4})$/', $time, $m)) {
        $mes = $meses[$m[1]] ?? strtolower($m[1]);
        return $m[2] . ' de ' . $mes . ' de ' . $m[3];
    }
    return $time;
}

// ── Avatar override — usa favicon del sitio en lugar de Gravatar ──
add_filter('get_avatar', 'er_custom_avatar', 10, 6);
function er_custom_avatar($avatar, $id_or_email, $size, $default, $alt, $args) {
    $img = get_site_url() . '/wp-content/uploads/er-avatar.png';
    $fallback = get_site_url() . '/wp-content/themes/el-rufino-theme/assets/favicon-512.png';
    // Intentar avatar custom subido, sino favicon del tema
    $src = file_exists(get_stylesheet_directory() . '/assets/favicon-512.png') ? $fallback : $img;
    return '<img src="' . esc_url($src) . '" width="' . esc_attr($size) . '" height="' . esc_attr($size) . '" class="avatar avatar-' . esc_attr($size) . ' er-autor-avatar" alt="' . esc_attr($alt) . '" loading="lazy">';
}

