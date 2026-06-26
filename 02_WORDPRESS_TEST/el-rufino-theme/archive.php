<?php
/**
 * archive.php — Páginas de categoría/pilar El Rufino
 * Child theme de Newsup · v1.0
 */

// Mapa de pilares: slug → color
$er_pilares = [
    'rufino-real'       => [ 'nombre' => 'Rufino real',       'color' => '#c0271b', 'code' => 'P01' ],
    'el-campo-habla'    => [ 'nombre' => 'El campo habla',     'color' => '#4a7c59', 'code' => 'P02' ],
    'barrio-a-barrio'   => [ 'nombre' => 'Barrio a barrio',    'color' => '#2d5f8a', 'code' => 'P03' ],
    'generacion-rufino' => [ 'nombre' => 'Generación Rufino',  'color' => '#7b4fa6', 'code' => 'P04' ],
    'poder-y-gestion'   => [ 'nombre' => 'Poder y gestión',    'color' => '#1a1a1a', 'code' => 'P05' ],
    'rufino-en-datos'   => [ 'nombre' => 'Rufino en datos',    'color' => '#c8600a', 'code' => 'P06' ],
];

// Detectar categoría actual
$cat        = get_queried_object();
$cat_slug   = $cat->slug ?? '';
$cat_nombre = $cat->name ?? get_the_archive_title();
$pilar      = $er_pilares[$cat_slug] ?? null;
$color      = $pilar['color'] ?? '#c0271b';
$code       = $pilar['code']  ?? '';
$wa_url     = get_option('er_whatsapp_canal', '#');

// Helpers
function arc_thumb( $post_id, $size, $ratio_class = '' ) {
    $class = 'arc-img-wrap' . ($ratio_class ? ' ' . $ratio_class : '');
    echo '<div class="' . $class . '">';
    if ( has_post_thumbnail($post_id) ) {
        echo get_the_post_thumbnail($post_id, $size, ['style'=>'width:100%;height:100%;object-fit:cover;display:block;position:absolute;top:0;left:0;']);
    }
    echo '</div>';
}
function arc_meta( $post ) {
    $words = str_word_count(strip_tags($post->post_content));
    $min   = max(1, round($words / 200));
    echo '<div class="card-meta"><span>' . date_i18n('j M Y', strtotime($post->post_date)) . '</span><span class="meta-sep"></span><span>' . $min . ' min</span></div>';
}

// Posts de la categoría
$todos = get_posts([
    'numberposts'   => -1,
    'category_name' => $cat_slug,
    'post_status'   => 'publish',
    'paged'         => get_query_var('paged') ?: 1,
]);

$destacada = $todos[0] ?? null;
$grilla    = array_slice($todos, 1, 6);
$lista     = array_slice($todos, 7);
?>
<!DOCTYPE html>
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
<body <?php body_class('archive category er-category'); ?>>
<?php wp_body_open(); ?>

<style>
*, *::before, *::after { box-sizing: border-box; }
:root {
    --papel:      #f5f1e8;
    --tinta:      #1a1a1a;
    --terra:      #c0271b;
    --pilar:      <?php echo esc_attr($color); ?>;
}

/* ── Topbar ── */
.er-topbar { background:#ece7df; font-family:'Source Serif 4',serif; font-size:11px; font-weight:300; letter-spacing:.06em; text-transform:uppercase; color:#6b6b6b; padding:6px 20px; display:flex; justify-content:space-between; align-items:center; }

/* ── Ocultar header/footer del padre ── */
.site-header, #masthead, .site-footer, #colophon { display:none !important; }
body { padding-top:0 !important; background:var(--papel); }

/* ── Masthead ── */
.er-masthead { background:var(--papel); padding:18px 20px 15px; display:flex; align-items:center; justify-content:space-between; gap:20px; border-bottom:3px solid var(--terra); }
.er-masthead-logo { display:flex; align-items:center; gap:16px; text-decoration:none; }
.er-logo-r { width:64px; height:64px; background:var(--terra); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.er-logo-r span { font-family:'Playfair Display',Georgia,serif; font-size:40px; font-weight:900; color:#fff; line-height:1; margin-top:2px; }
.er-logo-texto { display:flex; flex-direction:column; gap:3px; }
.er-logo-nombre { font-family:'Playfair Display',Georgia,serif; font-size:40px; font-weight:700; color:var(--tinta); line-height:1; letter-spacing:-.5px; }
.er-logo-claim { font-family:'Source Serif 4',Georgia,serif; font-size:10px; font-weight:300; letter-spacing:.22em; text-transform:uppercase; color:var(--terra); }
.er-btn-wa { background:var(--terra); color:#fff; border:none; padding:10px 18px; border-radius:2px; font-family:'Source Serif 4',serif; font-size:11px; font-weight:300; letter-spacing:.1em; text-transform:uppercase; text-decoration:none; display:inline-flex; align-items:center; gap:6px; white-space:nowrap; }

/* ── Nav ── */
.er-nav { background:var(--tinta); }
.er-nav ul { display:flex; list-style:none; overflow-x:auto; padding:0 8px; margin:0; }
.er-nav a { display:block; padding:14px 14px; font-family:'Source Serif 4',serif; font-size:11px; font-weight:300; letter-spacing:.12em; text-transform:uppercase; color:var(--papel); opacity:.65; text-decoration:none; border-bottom:2px solid transparent; margin-bottom:-2px; white-space:nowrap; transition:all .2s; }
.er-nav a:hover, .er-nav a.current { opacity:1; color:#fff; border-bottom-color:var(--pilar); }
.er-nav-divider { height:2px; background:var(--terra); }

/* ── Ticker ── */
.er-ticker { background:var(--tinta); display:flex; align-items:center; height:34px; overflow:hidden; }
.er-ticker-label { background:var(--terra); color:#fff; font-family:'Source Serif 4',serif; font-size:9.5px; font-weight:300; letter-spacing:.18em; text-transform:uppercase; padding:0 14px; height:100%; display:flex; align-items:center; flex-shrink:0; }
.er-ticker-track { overflow:hidden; flex:1; }
.er-ticker-inner { display:flex; animation:ticker 40s linear infinite; white-space:nowrap; }
.er-ticker-item { font-family:'Source Serif 4',serif; font-size:12.5px; color:rgba(255,255,255,.85); padding:0 32px 0 0; display:inline-flex; align-items:center; gap:8px; }
.er-ticker-dot { width:4px; height:4px; background:rgba(255,255,255,.4); border-radius:50%; }
@keyframes ticker { 0%{ transform:translateX(0); } 100%{ transform:translateX(-50%); } }

/* ── Breadcrumb ── */
.er-breadcrumb { background:var(--papel); border-bottom:1px solid #e8e0d0; padding:10px 20px; font-family:'Source Serif 4',serif; font-size:11px; font-weight:300; letter-spacing:.06em; color:#6b6b6b; display:flex; align-items:center; gap:8px; max-width:100%; }
.er-breadcrumb a { color:#6b6b6b; text-decoration:none; }
.er-breadcrumb a:hover { color:var(--pilar); }
.er-breadcrumb-sep { opacity:.4; }
.er-breadcrumb-current { color:var(--pilar); font-weight:400; }

/* ── Layout ── */
.er-container { max-width:1200px; margin:0 auto; padding:0 20px; }
.er-page-layout { display:grid; grid-template-columns:1fr 300px; gap:32px; padding:28px 0; }

/* ── Sección header ── */
.sec-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; padding-bottom:10px; border-bottom:2px solid var(--pilar); }
.sec-title { font-family:'Source Serif 4',serif; font-size:10px; font-weight:300; letter-spacing:.22em; text-transform:uppercase; color:var(--pilar); }

/* ── Card featured ── */
.card-featured { background:#fff; border-top:3px solid var(--pilar); display:grid; grid-template-columns:3fr 2fr; overflow:hidden; margin-bottom:24px; transition:box-shadow .2s; }
.card-featured:hover { box-shadow:0 4px 16px rgba(26,26,26,.10); }
.arc-img-wrap { background:#ddd8d0; position:relative; overflow:hidden; }
.arc-img-wrap.ratio-featured { min-height:260px; }
.arc-img-wrap.ratio-3x2 { aspect-ratio:3/2; }
.card-body { padding:24px; display:flex; flex-direction:column; justify-content:center; }
.card-badge { display:inline-flex; align-items:center; background:var(--pilar); color:#fff; font-family:'Source Serif 4',serif; font-size:9px; font-weight:300; letter-spacing:.12em; text-transform:uppercase; padding:3px 9px; border-radius:2px; margin-bottom:10px; align-self:flex-start; }
.card-overline { font-family:'Source Serif 4',serif; font-size:9.5px; font-weight:300; letter-spacing:.16em; text-transform:uppercase; color:var(--pilar); margin-bottom:10px; display:block; text-decoration:none; }
.card-title { font-family:'Playfair Display',Georgia,serif; font-size:clamp(18px,2.2vw,26px); font-weight:700; line-height:1.2; color:var(--tinta); text-decoration:none; display:block; margin-bottom:10px; transition:color .15s; }
.card-title:hover { color:var(--pilar); }
.card-bajada { font-family:'Source Serif 4',serif; font-size:13px; line-height:1.65; color:#555; margin-bottom:12px; }
.card-meta { font-family:'Source Serif 4',serif; font-size:10.5px; font-weight:300; color:#aaa; letter-spacing:.04em; display:flex; gap:6px; align-items:center; margin-top:8px; }
.meta-sep { width:3px; height:3px; border-radius:50%; background:#ccc; }

/* ── Grilla 3 columnas ── */
.card-grid-3 { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:24px; }
.card-sm { background:#fff; border-top:3px solid var(--pilar); padding:16px; transition:box-shadow .2s; }
.card-sm:hover { box-shadow:0 2px 8px rgba(26,26,26,.08); }
.card-sm-title { font-family:'Playfair Display',Georgia,serif; font-size:15px; font-weight:700; line-height:1.3; color:var(--tinta); text-decoration:none; display:block; margin-bottom:8px; transition:color .15s; }
.card-sm-title:hover { color:var(--pilar); }

/* ── Lista numerada ── */
.card-list { background:#fff; border-top:3px solid var(--pilar); margin-bottom:24px; }
.card-list-item { display:flex; gap:16px; align-items:flex-start; padding:16px; border-bottom:1px solid #e8e0d0; transition:background .15s; }
.card-list-item:last-child { border-bottom:none; }
.card-list-item:hover { background:#faf8f4; }
.card-list-num { font-family:'Playfair Display',Georgia,serif; font-size:24px; font-weight:900; color:var(--pilar); opacity:.2; line-height:1; flex-shrink:0; width:28px; }
.card-list-body { flex:1; }
.card-list-title { font-family:'Playfair Display',Georgia,serif; font-size:16px; font-weight:700; line-height:1.3; color:var(--tinta); text-decoration:none; display:block; margin-bottom:6px; transition:color .15s; }
.card-list-title:hover { color:var(--pilar); }

/* ── Paginación ── */
.er-pagination { display:flex; align-items:center; justify-content:center; gap:4px; padding-top:8px; flex-wrap:wrap; }
.er-pagination a, .er-pagination span { font-family:'Source Serif 4',serif; font-size:12px; font-weight:300; letter-spacing:.06em; padding:8px 14px; border:1px solid #ddd8d0; background:#fff; color:#6b6b6b; text-decoration:none; transition:all .15s; }
.er-pagination a:hover, .er-pagination .current { background:var(--pilar); color:#fff; border-color:var(--pilar); }

/* ── Sin resultados ── */
.er-empty { padding:48px 0; text-align:center; color:#aaa; font-family:'Source Serif 4',serif; font-size:14px; }

/* ── Sidebar (reutilizado del front-page) ── */
.er-widget { background:#fff; border-top:3px solid var(--tinta); padding:18px; margin-bottom:20px; }
.er-widget-rojo { border-top-color:var(--terra); }
.er-widget-pilar { border-top-color:var(--pilar); }
.er-widget-title { font-family:'Source Serif 4',serif; font-size:9.5px; font-weight:300; letter-spacing:.2em; text-transform:uppercase; color:var(--tinta); padding-bottom:10px; border-bottom:1px solid #ddd8d0; margin-bottom:14px; }
.er-leido-list { list-style:none; padding:0; margin:0; }
.er-leido-item { display:flex; gap:10px; align-items:baseline; padding:9px 0; border-bottom:1px solid #e8e0d0; }
.er-leido-item:last-child { border-bottom:none; }
.er-leido-num { font-family:'Playfair Display',Georgia,serif; font-size:20px; font-weight:900; color:var(--pilar); opacity:.25; line-height:1; min-width:22px; flex-shrink:0; }
.er-leido-titulo { font-family:'Playfair Display',Georgia,serif; font-size:13.5px; font-weight:700; line-height:1.3; color:var(--tinta); text-decoration:none; transition:color .15s; }
.er-leido-titulo:hover { color:var(--pilar); }
.er-pilar-link { display:flex; align-items:center; gap:8px; text-decoration:none; padding:7px 0; border-bottom:1px solid #e8e0d0; }
.er-pilar-link:last-child { border-bottom:none; }
.er-pilar-link.current .er-pilar-name { font-weight:600; color:var(--pilar); }
.er-pilar-dot { width:8px; height:8px; border-radius:1px; flex-shrink:0; }
.er-pilar-name { font-family:'Source Serif 4',serif; font-size:12px; color:var(--tinta); }
.er-pilar-code { font-family:'Source Serif 4',serif; font-size:9px; font-weight:300; letter-spacing:.14em; text-transform:uppercase; color:#aaa; margin-left:auto; }
.er-widget-wa { background:var(--tinta); border-top-color:var(--terra); }
.er-widget-wa .er-widget-title { color:var(--papel); border-bottom-color:rgba(245,241,232,.12); }
.er-widget-wa-desc { font-family:'Source Serif 4',serif; font-size:13px; font-weight:300; color:#888; line-height:1.6; margin-bottom:14px; }
.er-widget-wa-btn { display:block; background:var(--terra); color:#fff; font-family:'Source Serif 4',serif; font-size:11px; font-weight:300; letter-spacing:.1em; text-transform:uppercase; padding:10px 16px; text-align:center; text-decoration:none; border-radius:2px; }
.er-widget-wa-btn:hover { background:#9e3f22; }

/* ── Footer ── */
.er-footer { background:var(--tinta); color:var(--papel); border-top:3px solid var(--terra); padding:36px 20px 20px; margin-top:40px; }
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
    .er-footer-grid { grid-template-columns:1fr 1fr; }
}
@media (max-width:700px) {
    .card-featured { grid-template-columns:1fr; }
    .card-grid-3 { grid-template-columns:1fr 1fr; }
    .er-logo-r { width:48px; height:48px; }
    .er-logo-r span { font-size:30px; }
    .er-logo-nombre { font-size:30px; }
    .er-logo-claim { display:none; }
    .er-footer-grid { grid-template-columns:1fr; }
}
@media (max-width:480px) {
    .card-grid-3 { grid-template-columns:1fr; }
}
</style>

<?php
$recientes = get_posts(['numberposts' => 5, 'post_status' => 'publish']);
$populares = get_posts(['numberposts' => 5, 'post_status' => 'publish', 'orderby' => 'comment_count', 'order' => 'DESC']);
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

<!-- ═══ NAV ═══ -->
<nav class="er-nav">
    <ul>
        <li><a href="<?php echo esc_url(home_url('/')); ?>">Inicio</a></li>
        <?php foreach ($er_pilares as $slug => $pilar) :
            $c = get_category_by_slug($slug);
            $u = $c ? esc_url(get_category_link($c->term_id)) : '#';
            $active = ($slug === $cat_slug) ? ' class="current"' : '';
        ?>
        <li><a href="<?php echo $u; ?>"<?php echo $active; ?>><?php echo esc_html($pilar['nombre']); ?></a></li>
        <?php endforeach; ?>
    </ul>
</nav>
<div class="er-nav-divider"></div>

<!-- ═══ TICKER ═══ -->
<div class="er-ticker">
    <div class="er-ticker-label">Último momento</div>
    <div class="er-ticker-track">
        <div class="er-ticker-inner">
            <?php for ($t = 0; $t < 2; $t++) :
                foreach (array_slice($recientes, 0, 5) as $tp) : ?>
                <span class="er-ticker-item">
                    <span class="er-ticker-dot"></span>
                    <a href="<?php echo esc_url(get_permalink($tp->ID)); ?>" style="color:inherit;text-decoration:none;"><?php echo esc_html(get_the_title($tp->ID)); ?></a>
                </span>
            <?php endforeach; endfor; ?>
        </div>
    </div>
</div>

<!-- ═══ BREADCRUMB ═══ -->
<div class="er-breadcrumb">
    <a href="<?php echo esc_url(home_url('/')); ?>">Inicio</a>
    <span class="er-breadcrumb-sep">›</span>
    <span class="er-breadcrumb-current"><?php echo esc_html($cat_nombre); ?></span>
</div>

<!-- ═══ CONTENIDO ═══ -->
<div class="er-container">
<div class="er-page-layout">
<main>

<?php if (empty($todos)) : ?>
    <div class="er-empty">No hay notas publicadas en esta sección todavía.</div>

<?php else : ?>

    <?php if ($destacada) : ?>
    <!-- DESTACADA -->
    <div class="sec-header"><span class="sec-title">Nota destacada</span></div>
    <div class="card-featured">
        <div class="arc-img-wrap ratio-featured">
            <?php if (has_post_thumbnail($destacada->ID)) :
                echo get_the_post_thumbnail($destacada->ID, 'large', ['style'=>'width:100%;height:100%;object-fit:cover;display:block;position:absolute;top:0;left:0;']);
            endif; ?>
        </div>
        <div class="card-body">
            <span class="card-badge"><?php echo esc_html($code . ' ' . $cat_nombre); ?></span>
            <a href="<?php echo esc_url(get_permalink($destacada->ID)); ?>" class="card-title"><?php echo esc_html(get_the_title($destacada->ID)); ?></a>
            <p class="card-bajada"><?php echo esc_html(wp_trim_words(get_the_excerpt($destacada->ID), 25)); ?></p>
            <?php arc_meta($destacada); ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($grilla)) : ?>
    <!-- ÚLTIMAS NOTICIAS -->
    <div class="sec-header"><span class="sec-title">Últimas noticias</span></div>
    <div class="card-grid-3">
        <?php foreach ($grilla as $p) : ?>
        <div class="card-sm">
            <div class="arc-img-wrap ratio-3x2" style="margin-bottom:12px;">
                <?php if (has_post_thumbnail($p->ID)) :
                    echo get_the_post_thumbnail($p->ID, 'medium', ['style'=>'width:100%;height:100%;object-fit:cover;display:block;position:absolute;top:0;left:0;']);
                endif; ?>
            </div>
            <a class="card-overline"><?php echo esc_html($code . ' ' . $cat_nombre); ?></a>
            <a href="<?php echo esc_url(get_permalink($p->ID)); ?>" class="card-sm-title"><?php echo esc_html(get_the_title($p->ID)); ?></a>
            <?php arc_meta($p); ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($lista)) : ?>
    <!-- MÁS NOTAS -->
    <div class="sec-header"><span class="sec-title">Más notas</span></div>
    <div class="card-list">
        <?php foreach ($lista as $i => $p) : ?>
        <div class="card-list-item">
            <span class="card-list-num"><?php echo $i + 8; ?></span>
            <div class="card-list-body">
                <a href="<?php echo esc_url(get_permalink($p->ID)); ?>" class="card-list-title"><?php echo esc_html(get_the_title($p->ID)); ?></a>
                <?php arc_meta($p); ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- PAGINACIÓN -->
    <?php
    $pagination = get_the_posts_pagination([
        'mid_size'  => 2,
        'prev_text' => '← Anterior',
        'next_text' => 'Siguiente →',
        'class'     => 'er-pagination',
    ]);
    if ($pagination) echo $pagination;
    ?>

<?php endif; ?>

</main>

<!-- ═══ SIDEBAR ═══ -->
<aside>

    <!-- Última hora -->
    <div class="er-widget er-widget-rojo">
        <div class="er-widget-title">Última hora</div>
        <ul class="er-leido-list">
            <?php foreach (array_slice($recientes, 0, 4) as $p) : ?>
            <li class="er-leido-item">
                <span class="er-leido-num" style="font-size:10px;opacity:.5;min-width:36px;"><?php echo date_i18n('H:i', strtotime($p->post_date)); ?></span>
                <a href="<?php echo esc_url(get_permalink($p->ID)); ?>" class="er-leido-titulo"><?php echo esc_html(get_the_title($p->ID)); ?></a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Lo más leído en este pilar -->
    <div class="er-widget er-widget-pilar">
        <div class="er-widget-title">Lo más leído en <?php echo esc_html($cat_nombre); ?></div>
        <ul class="er-leido-list">
            <?php
            $mas_leidos = get_posts(['numberposts'=>5,'category_name'=>$cat_slug,'post_status'=>'publish','orderby'=>'comment_count','order'=>'DESC']);
            foreach ($mas_leidos as $i => $p) : ?>
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
            $c = get_category_by_slug($slug);
            $u = $c ? get_category_link($c->term_id) : '#';
            $is_current = ($slug === $cat_slug);
        ?>
        <a href="<?php echo esc_url($u); ?>" class="er-pilar-link<?php echo $is_current ? ' current' : ''; ?>">
            <span class="er-pilar-dot" style="background:<?php echo esc_attr($pilar['color']); ?>;"></span>
            <span class="er-pilar-name"><?php echo esc_html($pilar['nombre']); ?></span>
            <span class="er-pilar-code"><?php echo esc_html($pilar['code']); ?></span>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- WhatsApp -->
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
                <div class="er-footer-desc">Medio digital local de Rufino, Santa Fe, Argentina.</div>
            </div>
            <div>
                <div class="er-footer-col-title">Pilares</div>
                <ul class="er-footer-links">
                    <?php foreach ($er_pilares as $slug => $pilar) :
                        $c = get_category_by_slug($slug);
                        $u = $c ? get_category_link($c->term_id) : '#';
                    ?>
                    <li><a href="<?php echo esc_url($u); ?>"><?php echo esc_html($pilar['nombre']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div>
                <div class="er-footer-col-title">El medio</div>
                <ul class="er-footer-links">
                    <li><a href="<?php echo esc_url(home_url('/quienes-somos')); ?>">Quiénes somos</a></li>
                    <li><a href="<?php echo esc_url(home_url('/contacto')); ?>">Contacto</a></li>
                    <li><a href="<?php echo esc_url(home_url('/publicidad')); ?>">Publicidad</a></li>
                </ul>
            </div>
            <div>
                <div class="er-footer-col-title">Seguinos</div>
                <ul class="er-footer-links">
                    <li><a href="<?php echo esc_url($wa_url); ?>" target="_blank">WhatsApp</a></li>
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
