<?php
/**
 * front-page.php — Homepage El Rufino
 * Child theme de Newsup · v1.0
 */

// Configuración de pilares: slug → [ nombre, color, id ]
$er_pilares = [
    'rufino-real'       => [ 'nombre' => 'Rufino real',       'color' => '#c0271b', 'code' => 'P01' ],
    'el-campo-habla'    => [ 'nombre' => 'El campo habla',     'color' => '#4a7c59', 'code' => 'P02' ],
    'barrio-a-barrio'   => [ 'nombre' => 'Barrio a barrio',    'color' => '#2d5f8a', 'code' => 'P03' ],
    'generacion-rufino' => [ 'nombre' => 'Generación Rufino',  'color' => '#7b4fa6', 'code' => 'P04' ],
    'poder-y-gestion'   => [ 'nombre' => 'Poder y gestión',    'color' => '#1a1a1a', 'code' => 'P05' ],
    'rufino-en-datos'   => [ 'nombre' => 'Rufino en datos',    'color' => '#c8600a', 'code' => 'P06' ],
];

// Helper: obtener posts de una categoría
function er_get_pilar_posts( $slug, $num = 3 ) {
    return get_posts([
        'numberposts'      => $num,
        'category_name'    => $slug,
        'post_status'      => 'publish',
        'suppress_filters' => false,
    ]);
}

// Helper: thumbnail o placeholder
function er_thumb( $post_id, $size = 'large', $placeholder = '' ) {
    if ( has_post_thumbnail( $post_id ) ) {
        echo get_the_post_thumbnail( $post_id, $size, [ 'style' => 'width:100%;height:100%;object-fit:cover;display:block;position:absolute;top:0;left:0;' ] );
    } else {
        echo '<span style="font-family:Georgia,serif;font-size:13px;font-style:italic;color:#aaa;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);">' . esc_html( $placeholder ) . '</span>';
    }
}

// Helper: meta de fecha + tiempo de lectura estimado
function er_meta( $post ) {
    $words   = str_word_count( strip_tags( $post->post_content ) );
    $minutes = max( 1, round( $words / 200 ) );
    $fecha   = date_i18n( 'j M Y', strtotime( $post->post_date ) );
    echo '<div class="er-card-meta"><span>' . esc_html( $fecha ) . '</span><span class="er-meta-sep"></span><span>' . $minutes . ' min</span></div>';
}

// Desregistrar estilos conflictivos del tema padre en la home
add_action( 'wp_enqueue_scripts', function() {
    if ( is_front_page() ) {
        wp_dequeue_style( 'newsup-style' );
        wp_dequeue_style( 'newsup-block-style' );
    }
}, 99 );

// Emitir solo el <head> de WordPress sin el header.php del tema padre
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
<style>
@font-face { font-family:'Playfair Display'; src:url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/PlayfairDisplay-Regular.ttf') format('truetype'); font-weight:400; font-style:normal; font-display:swap; }
@font-face { font-family:'Playfair Display'; src:url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/PlayfairDisplay-Italic.ttf') format('truetype'); font-weight:400; font-style:italic; font-display:swap; }
@font-face { font-family:'Playfair Display'; src:url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/PlayfairDisplay-Bold.ttf') format('truetype'); font-weight:700; font-style:normal; font-display:swap; }
@font-face { font-family:'Playfair Display'; src:url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/PlayfairDisplay-BoldItalic.ttf') format('truetype'); font-weight:700; font-style:italic; font-display:swap; }
@font-face { font-family:'Playfair Display'; src:url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/PlayfairDisplay-Black.ttf') format('truetype'); font-weight:900; font-style:normal; font-display:swap; }
@font-face { font-family:'Playfair Display'; src:url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/PlayfairDisplay-BlackItalic.ttf') format('truetype'); font-weight:900; font-style:italic; font-display:swap; }
@font-face { font-family:'Source Serif 4'; src:url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/SourceSerif4-Light.ttf') format('truetype'); font-weight:300; font-style:normal; font-display:swap; }
@font-face { font-family:'Source Serif 4'; src:url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/SourceSerif4-Regular.ttf') format('truetype'); font-weight:400; font-style:normal; font-display:swap; }
@font-face { font-family:'Source Serif 4'; src:url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/SourceSerif4-LightItalic.ttf') format('truetype'); font-weight:300; font-style:italic; font-display:swap; }
</style>
<link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:ital,wght@0,300;0,400;1,300&display=swap" rel="stylesheet">
</head>
<body <?php body_class('home er-home'); ?>>
<?php wp_body_open(); ?>

<style>
/* ── Reset base ── */
*, *::before, *::after { box-sizing: border-box; }

/* ── Variables de color del design system ── */
:root {
    --papel:    #f5f1e8;
    --tinta:    #1a1a1a;
    --terra:    #c0271b;
    --azul:     #2d5f8a;
    --verde:    #4a7c59;
    --ambar:    #c8600a;
}

/* ── Topbar ── */
.er-topbar { background:#ece7df; font-family:'Source Serif 4',serif; font-size:11px; font-weight:300; letter-spacing:.06em; text-transform:uppercase; color:#6b6b6b; padding:6px 20px; display:flex; justify-content:space-between; align-items:center; }

/* ── Ocultar header del tema padre en la home ── */
.site-header, #masthead { display: none !important; }
body.home { padding-top: 0 !important; }

/* ── Masthead propio ── */
.er-masthead { background:var(--papel); padding:18px 20px 15px; display:flex; align-items:center; justify-content:space-between; gap:20px; border-bottom:3px solid var(--terra); }
.er-masthead-logo { display:flex; align-items:center; gap:16px; text-decoration:none; }
.er-logo-r { width:64px; height:64px; background:var(--terra); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.er-logo-r span { font-family:'Playfair Display',Georgia,serif; font-size:40px; font-weight:900; color:#fff; line-height:1; margin-top:2px; }
.er-logo-texto { display:flex; flex-direction:column; gap:3px; }
.er-logo-nombre { font-family:'Playfair Display',Georgia,serif; font-size:40px; font-weight:700; color:var(--tinta); line-height:1; letter-spacing:-.5px; }
.er-logo-claim { font-family:'Source Serif 4',Georgia,serif; font-size:10px; font-weight:300; letter-spacing:.22em; text-transform:uppercase; color:var(--terra); }
.er-btn-wa { background:var(--terra); color:#fff; border:none; padding:10px 18px; border-radius:2px; font-family:'Source Serif 4',serif; font-size:11px; font-weight:300; letter-spacing:.1em; text-transform:uppercase; text-decoration:none; display:inline-flex; align-items:center; gap:6px; cursor:pointer; white-space:nowrap; }

/* ── Menú de pilares ── */
.er-nav { background:var(--tinta); }
.er-nav ul { display:flex; list-style:none; overflow-x:auto; padding:0 8px; margin:0; }
.er-nav a { display:block; padding:14px 14px; font-family:'Source Serif 4',serif; font-size:11px; font-weight:300; letter-spacing:.12em; text-transform:uppercase; color:var(--papel); opacity:.65; text-decoration:none; border-bottom:2px solid transparent; margin-bottom:-2px; white-space:nowrap; transition:all .2s; }
.er-nav a:hover, .er-nav a.active { opacity:1; color:#fff; border-bottom-color:var(--terra); }
.er-nav-divider { height:2px; background:var(--terra); }

/* ── Ticker ── */
.er-ticker { background:var(--tinta); display:flex; align-items:center; height:34px; overflow:hidden; }
.er-ticker-label { background:var(--terra); color:#fff; font-family:'Source Serif 4',serif; font-size:9.5px; font-weight:300; letter-spacing:.18em; text-transform:uppercase; padding:0 14px; height:100%; display:flex; align-items:center; flex-shrink:0; }
.er-ticker-track { overflow:hidden; flex:1; }
.er-ticker-inner { display:flex; animation:ticker 40s linear infinite; white-space:nowrap; }
.er-ticker-item { font-family:'Source Serif 4',serif; font-size:12.5px; color:rgba(255,255,255,.85); padding:0 32px 0 0; display:inline-flex; align-items:center; gap:8px; }
.er-ticker-dot { width:4px; height:4px; background:rgba(255,255,255,.4); border-radius:50%; display:inline-block; }
@keyframes ticker { 0%{ transform:translateX(0); } 100%{ transform:translateX(-50%); } }

/* ── Layout global ── */
.er-container { max-width:1200px; margin:0 auto; padding:0 20px; }
.er-page-layout { display:grid; grid-template-columns:1fr 300px; gap:32px; padding:28px 0; }
.er-main { min-width:0; }

/* ── Héroe ── */
.er-hero { background:#fff; border-top:3px solid var(--terra); margin-bottom:32px; display:grid; grid-template-columns:3fr 2fr; height:420px; overflow:hidden; }
.er-hero-img { background:#ddd8d0; overflow:hidden; position:relative; }
.er-hero-img img { width:100%; height:100%; object-fit:cover; display:block; }
.er-hero-body { padding:28px 28px 24px; display:flex; flex-direction:column; justify-content:center; }
.er-hero-badges { display:flex; align-items:center; gap:8px; flex-wrap:wrap; margin-bottom:14px; }
.er-badge-pilar { display:inline-flex; align-items:center; gap:5px; font-family:'Source Serif 4',serif; font-size:9px; font-weight:300; letter-spacing:.14em; text-transform:uppercase; padding:4px 10px; border-radius:2px; }
.er-hero-titulo { font-family:'Playfair Display',Georgia,serif; font-size:clamp(22px,3vw,36px); font-weight:900; line-height:1.1; color:var(--tinta); letter-spacing:-.02em; margin-bottom:12px; }
.er-hero-bajada { font-family:'Playfair Display',Georgia,serif; font-size:clamp(14px,1.5vw,17px); font-style:italic; font-weight:400; line-height:1.5; color:#4a4a4a; margin-bottom:16px; }
.er-hero-meta { font-family:'Source Serif 4',serif; font-size:11px; font-weight:300; letter-spacing:.04em; color:#6b6b6b; display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
.er-meta-sep { width:3px; height:3px; border-radius:50%; background:#ccc; }
.er-hero-cta { margin-top:16px; }
.er-btn-leer { display:inline-flex; align-items:center; gap:6px; background:var(--terra); color:#fff; font-family:'Source Serif 4',serif; font-size:10.5px; font-weight:300; letter-spacing:.1em; text-transform:uppercase; padding:9px 18px; border-radius:2px; text-decoration:none; }
.er-btn-leer:hover { background:#9e3f22; }

/* ── Secciones pilar ── */
.er-pilar-seccion { margin-bottom:32px; }
.er-pilar-header { display:flex; align-items:center; gap:12px; margin-bottom:16px; padding-bottom:10px; border-bottom:2px solid #e8e8e8; }
.er-pilar-header-nombre { font-family:'Source Serif 4',serif; font-size:10px; font-weight:300; letter-spacing:.2em; text-transform:uppercase; color:var(--tinta); white-space:nowrap; }
.er-pilar-header-line { flex:1; }
.er-pilar-header-link { font-family:'Source Serif 4',serif; font-size:10px; font-weight:300; letter-spacing:.1em; text-transform:uppercase; color:var(--terra); text-decoration:none; white-space:nowrap; }
.er-pilar-header-link:hover { text-decoration:underline; }

/* ── Cards ── */
.er-pilar-grid { display:grid; grid-template-columns:2fr 1fr; gap:16px; }
.er-card-featured { background:#fff; border-top:3px solid var(--terra); overflow:hidden; transition:box-shadow .2s; }
.er-card-featured:hover { box-shadow:0 4px 16px rgba(26,26,26,.10); }
.er-card-featured-img { aspect-ratio:16/9; background:#ddd8d0; position:relative; overflow:hidden; }
.er-card-featured-img img { width:100%;height:100%;object-fit:cover;display:block;position:absolute;top:0;left:0; }
.er-card-featured-body { padding:16px; }
.er-card-overline { font-family:'Source Serif 4',serif; font-size:9.5px; font-weight:300; letter-spacing:.16em; text-transform:uppercase; color:var(--terra); display:block; margin-bottom:8px; text-decoration:none; }
.er-card-titulo { font-family:'Playfair Display',Georgia,serif; font-size:clamp(16px,2vw,22px); font-weight:700; line-height:1.25; color:var(--tinta); text-decoration:none; display:block; margin-bottom:8px; transition:color .15s; }
.er-card-titulo:hover { color:var(--terra); }
.er-card-bajada { font-family:'Source Serif 4',serif; font-size:13px; line-height:1.65; color:#555; margin-bottom:12px; }
.er-card-meta { font-family:'Source Serif 4',serif; font-size:10.5px; font-weight:300; color:#aaa; letter-spacing:.04em; display:flex; gap:6px; align-items:center; }
.er-compactas-stack { display:flex; flex-direction:column; gap:0; }
.er-card-compact { background:#fff; border-top:3px solid var(--tinta); padding:14px; flex:1; transition:box-shadow .2s; }
.er-card-compact + .er-card-compact { border-top-width:1px; border-top-color:#e8e0d0; }
.er-card-compact:hover { box-shadow:0 2px 8px rgba(26,26,26,.08); }
.er-card-compact-titulo { font-family:'Playfair Display',Georgia,serif; font-size:15px; font-weight:700; line-height:1.3; color:var(--tinta); text-decoration:none; display:block; margin-bottom:8px; transition:color .15s; }
.er-card-compact-titulo:hover { color:var(--terra); }
.er-grid-3 { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; }
.er-grid-fotos { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; }
.er-card-foto { background:#fff; border-top:3px solid #7b4fa6; overflow:hidden; transition:box-shadow .2s; }
.er-card-foto:hover { box-shadow:0 4px 16px rgba(26,26,26,.08); }
.er-card-foto-img { aspect-ratio:4/3; background:#e4ddd0; position:relative; overflow:hidden; }
.er-card-foto-img img { width:100%;height:100%;object-fit:cover;display:block;position:absolute;top:0;left:0; }
.er-card-foto-body { padding:12px; }
.er-pilar-sep { height:1px; background:#e8e8e8; margin-bottom:32px; }

/* ── P05 lista ── */
.er-p05-lista { background:#fff; border-top:3px solid var(--tinta); }
.er-p05-item { display:flex; align-items:baseline; gap:16px; padding:14px 16px; border-bottom:1px solid #e8e0d0; transition:background .15s; }
.er-p05-item:last-child { border-bottom:none; }
.er-p05-item:hover { background:#faf8f4; }
.er-p05-fecha { font-family:'Source Serif 4',serif; font-size:10px; font-weight:300; letter-spacing:.1em; text-transform:uppercase; color:#aaa; white-space:nowrap; min-width:60px; }
.er-p05-titulo { font-family:'Playfair Display',Georgia,serif; font-size:15px; font-weight:700; line-height:1.3; color:var(--tinta); text-decoration:none; flex:1; transition:color .15s; }
.er-p05-titulo:hover { color:var(--terra); }

/* ── P06 datos ── */
.er-grid-datos { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
.er-card-dato { background:#fff; border-top:3px solid var(--ambar); padding:24px 20px; transition:box-shadow .2s; }
.er-card-dato:hover { box-shadow:0 4px 16px rgba(26,26,26,.08); }
.er-dato-num { font-family:'Playfair Display',Georgia,serif; font-size:56px; font-weight:900; color:var(--ambar); line-height:1; margin-bottom:8px; letter-spacing:-.02em; }
.er-dato-label { font-family:'Source Serif 4',serif; font-size:10px; font-weight:300; letter-spacing:.18em; text-transform:uppercase; color:#6b6b6b; margin-bottom:10px; }
.er-dato-titulo { font-family:'Playfair Display',Georgia,serif; font-size:17px; font-weight:700; line-height:1.3; color:var(--tinta); text-decoration:none; display:block; margin-bottom:6px; transition:color .15s; }
.er-dato-titulo:hover { color:var(--terra); }
.er-dato-contexto { font-family:'Source Serif 4',serif; font-size:13px; font-weight:300; line-height:1.65; color:#555; }

/* ── Banner WhatsApp ── */
.er-wa-banner { background:var(--tinta); padding:32px 40px; margin-bottom:32px; display:flex; align-items:center; justify-content:space-between; gap:24px; flex-wrap:wrap; }
.er-wa-texto { font-family:'Playfair Display',Georgia,serif; font-size:22px; font-weight:700; color:#fff; line-height:1.3; }
.er-wa-sub { font-family:'Source Serif 4',serif; font-size:13px; font-weight:300; color:#888; margin-top:6px; }
.er-wa-btn { background:var(--terra); color:#fff; font-family:'Source Serif 4',serif; font-size:11px; font-weight:300; letter-spacing:.12em; text-transform:uppercase; padding:13px 28px; border-radius:2px; text-decoration:none; white-space:nowrap; }
.er-wa-btn:hover { background:#9e3f22; }

/* ── Sidebar ── */
.er-widget { background:#fff; border-top:3px solid var(--tinta); padding:18px; margin-bottom:20px; }
.er-widget-rojo { border-top-color:var(--terra); }
.er-widget-title { font-family:'Source Serif 4',serif; font-size:9.5px; font-weight:300; letter-spacing:.2em; text-transform:uppercase; color:var(--tinta); padding-bottom:10px; border-bottom:1px solid #ddd8d0; margin-bottom:14px; }
.er-leido-list { list-style:none; padding:0; margin:0; }
.er-leido-item { display:flex; gap:10px; align-items:baseline; padding:9px 0; border-bottom:1px solid #e8e0d0; }
.er-leido-item:last-child { border-bottom:none; padding-bottom:0; }
.er-leido-num { font-family:'Playfair Display',Georgia,serif; font-size:20px; font-weight:900; color:var(--terra); opacity:.2; line-height:1; min-width:22px; flex-shrink:0; }
.er-leido-titulo { font-family:'Playfair Display',Georgia,serif; font-size:13.5px; font-weight:700; line-height:1.3; color:var(--tinta); text-decoration:none; transition:color .15s; }
.er-leido-titulo:hover { color:var(--terra); }
.er-pilar-link { display:flex; align-items:center; gap:8px; text-decoration:none; padding:7px 0; border-bottom:1px solid #e8e0d0; }
.er-pilar-link:last-child { border-bottom:none; }
.er-pilar-dot { width:8px; height:8px; border-radius:1px; flex-shrink:0; }
.er-pilar-name { font-family:'Source Serif 4',serif; font-size:12px; font-weight:400; color:var(--tinta); }
.er-pilar-code { font-family:'Source Serif 4',serif; font-size:9px; font-weight:300; letter-spacing:.14em; text-transform:uppercase; color:#aaa; margin-left:auto; }
.er-hora-list { list-style:none; padding:0; margin:0; }
.er-hora-item { padding:9px 0; border-bottom:1px solid #e8e0d0; display:flex; gap:10px; align-items:baseline; }
.er-hora-item:last-child { border-bottom:none; padding-bottom:0; }
.er-hora-time { font-family:'Source Serif 4',serif; font-size:10px; font-weight:300; letter-spacing:.08em; color:var(--terra); white-space:nowrap; flex-shrink:0; }
.er-hora-titulo { font-family:'Playfair Display',Georgia,serif; font-size:13.5px; font-weight:700; line-height:1.3; color:var(--tinta); text-decoration:none; transition:color .15s; }
.er-hora-titulo:hover { color:var(--terra); }
.er-widget-wa { background:var(--tinta); border-top-color:var(--terra); }
.er-widget-wa .er-widget-title { color:var(--papel); border-bottom-color:rgba(245,241,232,.12); }
.er-widget-wa-desc { font-family:'Source Serif 4',serif; font-size:13px; font-weight:300; color:#888; line-height:1.6; margin-bottom:14px; }
.er-widget-wa-btn { display:block; background:var(--terra); color:#fff; font-family:'Source Serif 4',serif; font-size:11px; font-weight:300; letter-spacing:.1em; text-transform:uppercase; padding:10px 16px; text-align:center; text-decoration:none; border-radius:2px; }
.er-widget-wa-btn:hover { background:#9e3f22; }

/* ── Footer ── */
.er-footer { background:var(--tinta); color:var(--papel); border-top:3px solid var(--terra); padding:36px 20px 20px; }
.er-footer-inner { max-width:1200px; margin:0 auto; }
.er-footer-grid { display:grid; grid-template-columns:2fr 1fr 1fr 1fr; gap:32px; margin-bottom:28px; }
.er-footer-logo-row { display:flex; align-items:center; gap:10px; margin-bottom:12px; }
.er-footer-logo-name { font-family:'Playfair Display',Georgia,serif; font-size:24px; font-weight:900; color:#fff; }
.er-footer-claim { font-family:'Source Serif 4',serif; font-style:italic; font-size:12.5px; color:var(--papel); opacity:.6; margin-bottom:12px; }
.er-footer-desc { font-family:'Source Serif 4',serif; font-size:12px; font-weight:300; color:#888; line-height:1.65; }
.er-footer-col-title { font-family:'Source Serif 4',serif; font-size:9.5px; font-weight:300; letter-spacing:.18em; text-transform:uppercase; color:var(--papel); opacity:.45; padding-bottom:10px; border-bottom:1px solid rgba(245,241,232,.08); margin-bottom:12px; }
.er-footer-links { list-style:none; padding:0; display:flex; flex-direction:column; gap:8px; }
.er-footer-links a { font-family:'Source Serif 4',serif; font-size:12.5px; font-weight:300; color:var(--papel); opacity:.65; text-decoration:none; }
.er-footer-links a:hover { opacity:1; }
.er-footer-bottom { display:flex; justify-content:space-between; align-items:center; padding-top:16px; border-top:1px solid rgba(245,241,232,.08); font-family:'Source Serif 4',serif; font-size:10.5px; font-weight:300; color:#666; }

/* ── Responsive ── */
@media (max-width:1000px) {
    .er-page-layout { grid-template-columns:1fr; }
    .er-sidebar { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
    .er-footer-grid { grid-template-columns:1fr 1fr; }
}
@media (max-width:700px) {
    .er-hero { grid-template-columns:1fr; }
    .er-hero-img { min-height:200px; }
    .er-pilar-grid { grid-template-columns:1fr; }
    .er-grid-3, .er-grid-fotos, .er-grid-datos { grid-template-columns:1fr; }
    .er-wa-banner { flex-direction:column; align-items:flex-start; padding:24px 20px; }
    .er-masthead { flex-wrap:wrap; }
    .er-logo-r { width:48px; height:48px; }
    .er-logo-r span { font-size:30px; }
    .er-logo-nombre { font-size:30px; }
    .er-logo-claim { display:none; }
    .er-sidebar { grid-template-columns:1fr; }
    .er-footer-grid { grid-template-columns:1fr; }
}
</style>

<?php
// Obtener todos los posts para el ticker y widgets
$todos_posts = get_posts([ 'numberposts' => 10, 'post_status' => 'publish' ]);
$hero_post   = $todos_posts[0] ?? null;
$wa_url      = get_option('er_whatsapp_canal', '#');
?>

<!-- ═══ TOPBAR ═══ -->
<div class="er-topbar">
    <span><?php echo date_i18n('l, j \d\e F \d\e Y'); ?></span>
    <span style="opacity:.6">Rufino · Santa Fe · Argentina</span>
</div>

<!-- ═══ MASTHEAD ═══ -->
<div class="er-masthead">
    <a href="<?php echo esc_url(home_url('/')); ?>" class="er-masthead-logo">
        <div class="er-logo-r"><span>R</span></div>
        <div class="er-logo-texto">
            <span class="er-logo-nombre">El Rufino</span>
            <span class="er-logo-claim"><?php echo esc_html(get_bloginfo('description')); ?></span>
        </div>
    </a>
    <a href="<?php echo esc_url($wa_url); ?>" class="er-btn-wa" target="_blank" rel="noopener">📲 WhatsApp</a>
</div>

<!-- ═══ NAV PILARES ═══ -->
<nav class="er-nav">
    <ul>
        <li><a href="<?php echo esc_url(home_url('/')); ?>" class="active">Inicio</a></li>
        <?php foreach ( $er_pilares as $slug => $pilar ) :
            $cat = get_category_by_slug( $slug );
            $url = $cat ? esc_url(get_category_link($cat->term_id)) : '#';
        ?>
        <li><a href="<?php echo $url; ?>"><?php echo esc_html($pilar['nombre']); ?></a></li>
        <?php endforeach; ?>
    </ul>
</nav>
<div class="er-nav-divider"></div>

<!-- ═══ TICKER ═══ -->
<div class="er-ticker">
    <div class="er-ticker-label">Último momento</div>
    <div class="er-ticker-track">
        <div class="er-ticker-inner">
            <?php
            // Duplicamos los items para el loop continuo
            $ticker_posts = array_slice($todos_posts, 0, 5);
            for ($t = 0; $t < 2; $t++) :
                foreach ($ticker_posts as $tp) : ?>
                <span class="er-ticker-item">
                    <span class="er-ticker-dot"></span>
                    <a href="<?php echo esc_url(get_permalink($tp->ID)); ?>" style="color:inherit;text-decoration:none;">
                        <?php echo esc_html(get_the_title($tp->ID)); ?>
                    </a>
                </span>
            <?php endforeach; endfor; ?>
        </div>
    </div>
</div>

<!-- ═══ CONTENIDO ═══ -->
<div class="er-container">

<?php if ($hero_post) :
    $hero_cats = get_the_category($hero_post->ID);
    $hero_cat  = $hero_cats[0] ?? null;
    $hero_slug = $hero_cat ? $hero_cat->slug : '';
    $hero_color = $er_pilares[$hero_slug]['color'] ?? '#c0271b';
    $hero_code  = $er_pilares[$hero_slug]['code']  ?? '';
    $hero_words = str_word_count(strip_tags($hero_post->post_content));
    $hero_min   = max(1, round($hero_words / 200));
?>
<!-- ═══ HÉROE ═══ -->
<div style="padding-top:28px;">
    <div class="er-hero">
        <div class="er-hero-img">
            <?php er_thumb($hero_post->ID, 'large', 'Imagen principal'); ?>
        </div>
        <div class="er-hero-body">
            <div class="er-hero-badges">
                <?php if ($hero_cat) : ?>
                <span class="er-badge-pilar" style="background:<?php echo esc_attr($hero_color); ?>22; color:<?php echo esc_attr($hero_color); ?>; border:1px solid <?php echo esc_attr($hero_color); ?>88;">
                    <?php echo esc_html($hero_code . ' ' . $hero_cat->name); ?>
                </span>
                <?php endif; ?>
            </div>
            <h1 class="er-hero-titulo">
                <a href="<?php echo esc_url(get_permalink($hero_post->ID)); ?>" style="color:inherit;text-decoration:none;">
                    <?php echo esc_html(get_the_title($hero_post->ID)); ?>
                </a>
            </h1>
            <p class="er-hero-bajada"><?php echo esc_html(get_the_excerpt($hero_post->ID)); ?></p>
            <div class="er-hero-meta">
                <span><?php echo date_i18n('j M Y', strtotime($hero_post->post_date)); ?></span>
                <span class="er-meta-sep"></span>
                <span><?php echo $hero_min; ?> min de lectura</span>
            </div>
            <div class="er-hero-cta">
                <a href="<?php echo esc_url(get_permalink($hero_post->ID)); ?>" class="er-btn-leer">Leer nota completa →</a>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ═══ LAYOUT + SIDEBAR ═══ -->
<div class="er-page-layout">
<main class="er-main">

<?php
// ══ P01 RUFINO REAL ══
$p01 = er_get_pilar_posts('rufino-real', 3);
$p01_color = $er_pilares['rufino-real']['color'];
if ($p01) :
    $p01_cat = get_category_by_slug('rufino-real');
    $p01_url = $p01_cat ? get_category_link($p01_cat->term_id) : '#';
?>
<div class="er-pilar-seccion">
    <div class="er-pilar-header" style="border-bottom-color:<?php echo $p01_color; ?>;">
        <span class="er-pilar-header-nombre" style="color:<?php echo $p01_color; ?>;">Rufino real</span>
        <span class="er-pilar-header-line"></span>
        <a href="<?php echo esc_url($p01_url); ?>" class="er-pilar-header-link">Ver todo →</a>
    </div>
    <div class="er-pilar-grid">
        <div class="er-card-featured" style="border-top-color:<?php echo $p01_color; ?>;">
            <div class="er-card-featured-img"><?php er_thumb($p01[0]->ID, 'medium_large', 'Foto nota'); ?></div>
            <div class="er-card-featured-body">
                <a class="er-card-overline" style="color:<?php echo $p01_color; ?>;">P01 Rufino real</a>
                <a href="<?php echo esc_url(get_permalink($p01[0]->ID)); ?>" class="er-card-titulo"><?php echo esc_html(get_the_title($p01[0]->ID)); ?></a>
                <p class="er-card-bajada"><?php echo esc_html(wp_trim_words(get_the_excerpt($p01[0]->ID), 20)); ?></p>
                <?php er_meta($p01[0]); ?>
            </div>
        </div>
        <?php if (count($p01) > 1) : ?>
        <div class="er-compactas-stack">
            <?php foreach (array_slice($p01, 1) as $i => $p) : ?>
            <div class="er-card-compact" <?php echo $i > 0 ? 'style="border-top:1px solid #e8e0d0;"' : 'style="border-top-color:' . $p01_color . ';"'; ?>>
                <a class="er-card-overline" style="color:<?php echo $p01_color; ?>;">P01 Rufino real</a>
                <a href="<?php echo esc_url(get_permalink($p->ID)); ?>" class="er-card-compact-titulo"><?php echo esc_html(get_the_title($p->ID)); ?></a>
                <?php er_meta($p); ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<div class="er-pilar-sep"></div>
<?php endif; ?>

<?php
// ══ P02 EL CAMPO HABLA ══
$p02 = er_get_pilar_posts('el-campo-habla', 3);
$p02_color = $er_pilares['el-campo-habla']['color'];
if ($p02) :
    $p02_cat = get_category_by_slug('el-campo-habla');
    $p02_url = $p02_cat ? get_category_link($p02_cat->term_id) : '#';
?>
<div class="er-pilar-seccion">
    <div class="er-pilar-header" style="border-bottom-color:<?php echo $p02_color; ?>;">
        <span class="er-pilar-header-nombre" style="color:<?php echo $p02_color; ?>;">El campo habla</span>
        <span class="er-pilar-header-line"></span>
        <a href="<?php echo esc_url($p02_url); ?>" class="er-pilar-header-link">Ver todo →</a>
    </div>
    <div class="er-pilar-grid">
        <div class="er-card-featured" style="border-top-color:<?php echo $p02_color; ?>;">
            <div class="er-card-featured-img" style="background:#d4cab0;"><?php er_thumb($p02[0]->ID, 'medium_large', 'Foto campo'); ?></div>
            <div class="er-card-featured-body">
                <a class="er-card-overline" style="color:<?php echo $p02_color; ?>;">P02 El campo habla</a>
                <a href="<?php echo esc_url(get_permalink($p02[0]->ID)); ?>" class="er-card-titulo"><?php echo esc_html(get_the_title($p02[0]->ID)); ?></a>
                <p class="er-card-bajada"><?php echo esc_html(wp_trim_words(get_the_excerpt($p02[0]->ID), 20)); ?></p>
                <?php er_meta($p02[0]); ?>
            </div>
        </div>
        <?php if (count($p02) > 1) : ?>
        <div class="er-compactas-stack">
            <?php foreach (array_slice($p02, 1) as $i => $p) : ?>
            <div class="er-card-compact" <?php echo $i > 0 ? 'style="border-top:1px solid #e8e0d0;"' : 'style="border-top-color:' . $p02_color . ';"'; ?>>
                <a class="er-card-overline" style="color:<?php echo $p02_color; ?>;">P02 El campo habla</a>
                <a href="<?php echo esc_url(get_permalink($p->ID)); ?>" class="er-card-compact-titulo"><?php echo esc_html(get_the_title($p->ID)); ?></a>
                <?php er_meta($p); ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<div class="er-pilar-sep"></div>
<?php endif; ?>

<!-- ══ BANNER WHATSAPP ══ -->
<div class="er-wa-banner">
    <div>
        <div class="er-wa-texto">Recibí las noticias de Rufino directo en tu celular</div>
        <div class="er-wa-sub">Sin algoritmos. Sin spam. Solo lo que importa en Rufino.</div>
    </div>
    <a href="<?php echo esc_url($wa_url); ?>" class="er-wa-btn" target="_blank" rel="noopener">📲 UNIRME AL CANAL</a>
</div>

<?php
// ══ P03 BARRIO A BARRIO ══
$p03 = er_get_pilar_posts('barrio-a-barrio', 3);
$p03_color = $er_pilares['barrio-a-barrio']['color'];
if ($p03) :
    $p03_cat = get_category_by_slug('barrio-a-barrio');
    $p03_url = $p03_cat ? get_category_link($p03_cat->term_id) : '#';
?>
<div class="er-pilar-seccion">
    <div class="er-pilar-header" style="border-bottom-color:<?php echo $p03_color; ?>;">
        <span class="er-pilar-header-nombre" style="color:<?php echo $p03_color; ?>;">Barrio a barrio</span>
        <span class="er-pilar-header-line"></span>
        <a href="<?php echo esc_url($p03_url); ?>" class="er-pilar-header-link">Ver todo →</a>
    </div>
    <div class="er-grid-3">
        <?php foreach ($p03 as $p) : ?>
        <div class="er-card-compact" style="border-top-color:<?php echo $p03_color; ?>;">
            <a class="er-card-overline" style="color:<?php echo $p03_color; ?>;">P03 Barrio a barrio</a>
            <a href="<?php echo esc_url(get_permalink($p->ID)); ?>" class="er-card-compact-titulo"><?php echo esc_html(get_the_title($p->ID)); ?></a>
            <?php er_meta($p); ?>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<div class="er-pilar-sep"></div>
<?php endif; ?>

<?php
// ══ P04 GENERACIÓN RUFINO ══
$p04 = er_get_pilar_posts('generacion-rufino', 3);
$p04_color = $er_pilares['generacion-rufino']['color'];
if ($p04) :
    $p04_cat = get_category_by_slug('generacion-rufino');
    $p04_url = $p04_cat ? get_category_link($p04_cat->term_id) : '#';
?>
<div class="er-pilar-seccion">
    <div class="er-pilar-header" style="border-bottom-color:<?php echo $p04_color; ?>;">
        <span class="er-pilar-header-nombre" style="color:<?php echo $p04_color; ?>;">Generación Rufino</span>
        <span class="er-pilar-header-line"></span>
        <a href="<?php echo esc_url($p04_url); ?>" class="er-pilar-header-link">Ver todo →</a>
    </div>
    <div class="er-grid-fotos">
        <?php foreach ($p04 as $p) : ?>
        <div class="er-card-foto">
            <div class="er-card-foto-img"><?php er_thumb($p->ID, 'medium', 'Foto'); ?></div>
            <div class="er-card-foto-body">
                <a class="er-card-overline" style="color:<?php echo $p04_color; ?>;">P04 Generación Rufino</a>
                <a href="<?php echo esc_url(get_permalink($p->ID)); ?>" class="er-card-compact-titulo"><?php echo esc_html(get_the_title($p->ID)); ?></a>
                <?php er_meta($p); ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<div class="er-pilar-sep"></div>
<?php endif; ?>

<?php
// ══ P05 PODER Y GESTIÓN ══
$p05 = er_get_pilar_posts('poder-y-gestion', 4);
$p05_color = $er_pilares['poder-y-gestion']['color'];
if ($p05) :
    $p05_cat = get_category_by_slug('poder-y-gestion');
    $p05_url = $p05_cat ? get_category_link($p05_cat->term_id) : '#';
?>
<div class="er-pilar-seccion">
    <div class="er-pilar-header" style="border-bottom-color:<?php echo $p05_color; ?>;">
        <span class="er-pilar-header-nombre">Poder y gestión</span>
        <span class="er-pilar-header-line"></span>
        <a href="<?php echo esc_url($p05_url); ?>" class="er-pilar-header-link">Ver todo →</a>
    </div>
    <div class="er-p05-lista">
        <?php foreach ($p05 as $p) : ?>
        <div class="er-p05-item">
            <span class="er-p05-fecha"><?php echo date_i18n('j M', strtotime($p->post_date)); ?></span>
            <a href="<?php echo esc_url(get_permalink($p->ID)); ?>" class="er-p05-titulo"><?php echo esc_html(get_the_title($p->ID)); ?></a>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<div class="er-pilar-sep"></div>
<?php endif; ?>

<?php
// ══ P06 RUFINO EN DATOS ══
$p06 = er_get_pilar_posts('rufino-en-datos', 2);
$p06_color = $er_pilares['rufino-en-datos']['color'];
if ($p06) :
    $p06_cat = get_category_by_slug('rufino-en-datos');
    $p06_url = $p06_cat ? get_category_link($p06_cat->term_id) : '#';
?>
<div class="er-pilar-seccion">
    <div class="er-pilar-header" style="border-bottom-color:<?php echo $p06_color; ?>;">
        <span class="er-pilar-header-nombre" style="color:<?php echo $p06_color; ?>;">Rufino en datos</span>
        <span class="er-pilar-header-line"></span>
        <a href="<?php echo esc_url($p06_url); ?>" class="er-pilar-header-link">Ver todo →</a>
    </div>
    <div class="er-grid-datos">
        <?php foreach ($p06 as $p) :
            $words = str_word_count(strip_tags($p->post_content));
            $num_match = preg_match('/\b(\d[\d\.,%+]*)\b/', strip_tags($p->post_content), $matches);
            $dato_num = $num_match ? $matches[1] : '—';
        ?>
        <div class="er-card-dato">
            <div class="er-dato-label"><?php echo esc_html(date_i18n('j M Y', strtotime($p->post_date))); ?></div>
            <div class="er-dato-num"><?php echo esc_html($dato_num); ?></div>
            <a href="<?php echo esc_url(get_permalink($p->ID)); ?>" class="er-dato-titulo"><?php echo esc_html(get_the_title($p->ID)); ?></a>
            <p class="er-dato-contexto"><?php echo esc_html(wp_trim_words(get_the_excerpt($p->ID), 20)); ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

</main>

<!-- ═══ SIDEBAR ═══ -->
<aside class="er-sidebar">

    <!-- Última hora -->
    <div class="er-widget er-widget-rojo">
        <div class="er-widget-title">Última hora</div>
        <ul class="er-hora-list">
            <?php foreach (array_slice($todos_posts, 0, 3) as $p) : ?>
            <li class="er-hora-item">
                <span class="er-hora-time"><?php echo date_i18n('H:i', strtotime($p->post_date)); ?></span>
                <a href="<?php echo esc_url(get_permalink($p->ID)); ?>" class="er-hora-titulo"><?php echo esc_html(get_the_title($p->ID)); ?></a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Lo más leído (por comentarios como proxy) -->
    <div class="er-widget">
        <div class="er-widget-title">Lo más leído</div>
        <?php
        $populares = get_posts(['numberposts' => 5, 'post_status' => 'publish', 'orderby' => 'comment_count', 'order' => 'DESC']);
        ?>
        <ul class="er-leido-list">
            <?php foreach ($populares as $i => $p) : ?>
            <li class="er-leido-item">
                <span class="er-leido-num"><?php echo $i + 1; ?></span>
                <a href="<?php echo esc_url(get_permalink($p->ID)); ?>" class="er-leido-titulo"><?php echo esc_html(get_the_title($p->ID)); ?></a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Pilares editoriales -->
    <div class="er-widget">
        <div class="er-widget-title">Pilares editoriales</div>
        <?php foreach ($er_pilares as $slug => $pilar) :
            $cat = get_category_by_slug($slug);
            $url = $cat ? get_category_link($cat->term_id) : '#';
        ?>
        <a href="<?php echo esc_url($url); ?>" class="er-pilar-link">
            <span class="er-pilar-dot" style="background:<?php echo esc_attr($pilar['color']); ?>;"></span>
            <span class="er-pilar-name"><?php echo esc_html($pilar['nombre']); ?></span>
            <span class="er-pilar-code"><?php echo esc_html($pilar['code']); ?></span>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- WhatsApp widget -->
    <div class="er-widget er-widget-wa">
        <div class="er-widget-title">Seguinos en WhatsApp</div>
        <p class="er-widget-wa-desc">Recibí las noticias de Rufino directo en tu celular, sin algoritmos.</p>
        <a href="<?php echo esc_url($wa_url); ?>" class="er-widget-wa-btn" target="_blank" rel="noopener">📲 Unirme al canal</a>
    </div>

</aside>
</div><!-- /page-layout -->
</div><!-- /container -->

<!-- ═══ FOOTER ═══ -->
<footer class="er-footer">
    <div class="er-footer-inner">
        <div class="er-footer-grid">
            <div>
                <div class="er-footer-logo-row">
                    <div class="er-logo-r" style="width:38px;height:38px;"><span style="font-size:22px;">R</span></div>
                    <span class="er-footer-logo-name">El Rufino</span>
                </div>
                <div class="er-footer-claim">Lo que pasa y lo que significa.</div>
                <div class="er-footer-desc">Medio digital local de Rufino, Santa Fe, Argentina. Noticias verificadas, contexto y seguimiento desde 2026.</div>
            </div>
            <div>
                <div class="er-footer-col-title">Pilares</div>
                <ul class="er-footer-links">
                    <?php foreach ($er_pilares as $slug => $pilar) :
                        $cat = get_category_by_slug($slug);
                        $url = $cat ? get_category_link($cat->term_id) : '#';
                    ?>
                    <li><a href="<?php echo esc_url($url); ?>"><?php echo esc_html($pilar['nombre']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div>
                <div class="er-footer-col-title">El medio</div>
                <ul class="er-footer-links">
                    <li><a href="<?php echo esc_url(home_url('/quienes-somos')); ?>">Quiénes somos</a></li>
                    <li><a href="<?php echo esc_url(home_url('/como-trabajamos')); ?>">Cómo trabajamos</a></li>
                    <li><a href="<?php echo esc_url(home_url('/contacto')); ?>">Contacto</a></li>
                    <li><a href="<?php echo esc_url(home_url('/publicidad')); ?>">Publicidad</a></li>
                </ul>
            </div>
            <div>
                <div class="er-footer-col-title">Seguinos</div>
                <ul class="er-footer-links">
                    <li><a href="<?php echo esc_url($wa_url); ?>" target="_blank" rel="noopener">WhatsApp</a></li>
                    <li><a href="#">Instagram</a></li>
                    <li><a href="#">Facebook</a></li>
                </ul>
            </div>
        </div>
        <div class="er-footer-bottom">
            <span>© <?php echo date('Y'); ?> El Rufino · Rufino, Santa Fe, Argentina</span>
            <span>elrufino.com.ar</span>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
