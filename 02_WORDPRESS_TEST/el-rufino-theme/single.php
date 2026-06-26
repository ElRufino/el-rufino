<?php
/**
 * single.php — Nota individual El Rufino
 * Child theme de Newsup · v1.0
 */

$er_pilares = [
    'rufino-real'       => [ 'nombre' => 'Rufino real',       'color' => '#c0271b', 'code' => 'P01' ],
    'el-campo-habla'    => [ 'nombre' => 'El campo habla',     'color' => '#4a7c59', 'code' => 'P02' ],
    'barrio-a-barrio'   => [ 'nombre' => 'Barrio a barrio',    'color' => '#2d5f8a', 'code' => 'P03' ],
    'generacion-rufino' => [ 'nombre' => 'Generación Rufino',  'color' => '#7b4fa6', 'code' => 'P04' ],
    'poder-y-gestion'   => [ 'nombre' => 'Poder y gestión',    'color' => '#1a1a1a', 'code' => 'P05' ],
    'rufino-en-datos'   => [ 'nombre' => 'Rufino en datos',    'color' => '#c8600a', 'code' => 'P06' ],
];

the_post();

// Datos del post
$post_id    = get_the_ID();
$cats       = get_the_category($post_id);
$cat        = $cats[0] ?? null;
$cat_slug   = $cat ? $cat->slug : '';
$pilar      = $er_pilares[$cat_slug] ?? null;
$color      = $pilar['color'] ?? '#c0271b';
$code       = $pilar['code']  ?? '';
$cat_nombre = $cat ? $cat->name : '';
$cat_url    = $cat ? get_category_link($cat->term_id) : '#';
$words      = str_word_count(strip_tags(get_the_content()));
$min_read   = max(1, round($words / 200));
$wa_url     = get_option('er_whatsapp_canal', '#');
$post_url   = get_permalink($post_id);

// Populares
$populares = get_posts(['numberposts'=>5,'post_status'=>'publish','orderby'=>'comment_count','order'=>'DESC']);
// Más notas del mismo pilar
$mas_notas = get_posts(['numberposts'=>3,'category_name'=>$cat_slug,'post_status'=>'publish','post__not_in'=>[$post_id]]);
// Recientes para ticker
$recientes = get_posts(['numberposts'=>5,'post_status'=>'publish','post__not_in'=>[$post_id]]);
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
<body <?php body_class('single er-single'); ?>>
<?php wp_body_open(); ?>

<style>
*, *::before, *::after { box-sizing: border-box; }
:root {
    --papel:  #f5f1e8;
    --tinta:  #1a1a1a;
    --terra:  #c0271b;
    --pilar:  <?php echo esc_attr($color); ?>;
}

/* ── Ocultar header/footer del padre ── */
.site-header, #masthead, .site-footer, #colophon { display:none !important; }
body { padding-top:0 !important; background:var(--papel); }

/* ── Topbar ── */
.er-topbar { background:#ece7df; font-family:'Source Serif 4',serif; font-size:11px; font-weight:300; letter-spacing:.06em; text-transform:uppercase; color:#6b6b6b; padding:6px 20px; display:flex; justify-content:space-between; align-items:center; }

/* ── Masthead ── */
.er-masthead { background:var(--papel); padding:18px 20px 15px; display:flex; align-items:center; justify-content:space-between; gap:20px; border-bottom:3px solid var(--terra); }
.er-masthead-logo { display:flex; align-items:center; gap:16px; text-decoration:none; }
.er-logo-r { width:64px; height:64px; background:var(--terra); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.er-logo-r span { font-family:'Playfair Display',Georgia,serif; font-size:40px; font-weight:900; color:#fff; line-height:1; margin-top:2px; }
.er-logo-texto { display:flex; flex-direction:column; gap:3px; }
.er-logo-nombre { font-family:'Playfair Display',Georgia,serif; font-size:40px; font-weight:700; color:var(--tinta); line-height:1; letter-spacing:-.5px; }
.er-logo-claim { font-family:'Source Serif 4',serif; font-size:10px; font-weight:300; letter-spacing:.22em; text-transform:uppercase; color:var(--terra); }
.er-btn-wa { background:var(--terra); color:#fff; border:none; padding:10px 18px; border-radius:2px; font-family:'Source Serif 4',serif; font-size:11px; font-weight:300; letter-spacing:.1em; text-transform:uppercase; text-decoration:none; display:inline-flex; align-items:center; gap:6px; white-space:nowrap; }

/* ── Nav ── */
.er-nav { background:var(--tinta); }
.er-nav ul { display:flex; list-style:none; overflow-x:auto; overflow-y:hidden; padding:0 8px; margin:0; }
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
.er-breadcrumb { background:var(--papel); border-bottom:1px solid #e8e0d0; padding:10px 20px; font-family:'Source Serif 4',serif; font-size:11px; font-weight:300; letter-spacing:.06em; color:#6b6b6b; display:flex; align-items:center; gap:8px; }
.er-breadcrumb a { color:#6b6b6b; text-decoration:none; }
.er-breadcrumb a:hover { color:var(--pilar); }
.er-breadcrumb-sep { opacity:.4; }
.er-breadcrumb-current { color:var(--pilar); }

/* ── Layout de nota ── */
.er-nota-wrap { max-width:1200px; margin:0 auto; padding:28px 20px; }
.er-nota-layout { display:grid; grid-template-columns:1fr 300px; gap:40px; align-items:start; }

/* ── Encabezado ── */
.er-nota-header { margin-bottom:28px; }
.er-pilar-badge { display:inline-flex; align-items:center; gap:6px; background:var(--pilar); color:#fff; font-family:'Source Serif 4',serif; font-size:9.5px; font-weight:300; letter-spacing:.18em; text-transform:uppercase; padding:5px 12px; border-radius:2px; margin-bottom:16px; }
.er-pilar-badge-dot { width:5px; height:5px; border-radius:50%; background:rgba(255,255,255,.7); flex-shrink:0; }
.er-nota-titulo { font-family:'Playfair Display',Georgia,serif; font-size:clamp(28px,4vw,48px); font-weight:900; line-height:1.1; color:var(--tinta); letter-spacing:-.02em; margin-bottom:16px; }
.er-nota-bajada { font-family:'Playfair Display',Georgia,serif; font-style:italic; font-size:clamp(17px,2vw,22px); font-weight:400; line-height:1.45; color:#4a4a4a; margin-bottom:18px; }
.er-nota-meta { display:flex; align-items:center; gap:10px; flex-wrap:wrap; font-family:'Source Serif 4',serif; font-size:12px; font-weight:300; letter-spacing:.04em; color:#6b6b6b; margin-bottom:14px; }
.er-nota-meta-sep { width:3px; height:3px; border-radius:50%; background:#aaa; }

/* ── Imagen destacada ── */
.er-nota-imagen { width:100%; margin:20px 0 0; background:#ddd8d0; aspect-ratio:16/9; position:relative; overflow:hidden; }
.er-nota-imagen img { width:100%; height:100%; object-fit:cover; display:block; position:absolute; top:0; left:0; }
.er-nota-caption { font-family:'Source Serif 4',serif; font-size:11px; font-weight:300; letter-spacing:.04em; color:#6b6b6b; padding:8px 0 0; border-top:1px solid #e8e0d0; margin-top:6px; }

/* ── Cuerpo ── */
.er-nota-cuerpo .er-drop-cap::first-letter { font-family:'Playfair Display',Georgia,serif; font-size:4.5em; font-weight:900; line-height:.75; float:left; margin:4px 10px 0 0; color:var(--pilar); }
.er-nota-cuerpo p { font-family:'Source Serif 4',serif; font-size:17px; font-weight:400; line-height:1.8; color:var(--tinta); margin-bottom:22px; }
.er-nota-cuerpo h2,
.er-nota-cuerpo h3 { font-family:'Playfair Display',Georgia,serif; font-size:22px; font-weight:700; line-height:1.25; color:var(--tinta); margin:32px 0 14px; }
.er-nota-cuerpo h4 { font-family:'Source Serif 4',serif; font-size:13px; font-weight:300; letter-spacing:.16em; text-transform:uppercase; color:var(--pilar); margin:24px 0 10px; }
.er-nota-cuerpo blockquote { border-left:4px solid var(--pilar); padding:16px 24px; margin:28px 0; background:#fff; }
.er-nota-cuerpo blockquote p { font-family:'Playfair Display',Georgia,serif; font-size:20px; font-style:italic; font-weight:400; line-height:1.45; margin-bottom:8px; }
.er-nota-cuerpo blockquote cite { font-family:'Source Serif 4',serif; font-size:11px; font-weight:300; letter-spacing:.1em; text-transform:uppercase; color:#6b6b6b; font-style:normal; }
.er-nota-cuerpo table { width:100%; border-collapse:collapse; margin:24px 0; background:#fff; }
.er-nota-cuerpo table th { background:var(--tinta); color:var(--papel); font-family:'Source Serif 4',serif; font-size:10px; font-weight:300; letter-spacing:.14em; text-transform:uppercase; padding:10px 14px; text-align:left; }
.er-nota-cuerpo table td { padding:10px 14px; font-family:'Source Serif 4',serif; font-size:14px; border-bottom:1px solid #e8e0d0; vertical-align:top; }
.er-nota-cuerpo table tr:last-child td { border-bottom:none; }
.er-nota-cuerpo table tr:nth-child(even) td { background:#faf8f4; }
.er-nota-cuerpo img { max-width:100%; height:auto; display:block; margin:24px 0; }

/* ── Segunda capa (clase WP custom) ── */
.er-segunda-capa { background:var(--papel); border-left:2px solid var(--pilar); padding:20px 24px; margin:28px 0; }
.er-segunda-capa-titulo { font-family:'Source Serif 4',serif; font-size:10px; font-weight:300; letter-spacing:.2em; text-transform:uppercase; color:var(--pilar); margin-bottom:10px; }
.er-segunda-capa-texto { font-family:'Source Serif 4',serif; font-size:15px; line-height:1.7; color:var(--tinta); }

/* ── Post-nota ── */
.er-post-nota { margin-top:40px; }
.er-tags { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:20px; }
.er-tag { font-family:'Source Serif 4',serif; font-size:10px; font-weight:300; letter-spacing:.12em; text-transform:uppercase; padding:4px 10px; border-radius:2px; border:1px solid #ddd8d0; color:#6b6b6b; text-decoration:none; }
.er-tag:hover { border-color:var(--pilar); color:var(--pilar); }
.er-compartir { display:flex; align-items:center; gap:12px; margin-bottom:28px; font-family:'Source Serif 4',serif; font-size:11px; font-weight:300; letter-spacing:.1em; text-transform:uppercase; color:#6b6b6b; flex-wrap:wrap; }
.er-share-btn { display:inline-flex; align-items:center; gap:5px; padding:7px 14px; border-radius:2px; border:none; font-family:'Source Serif 4',serif; font-size:10.5px; font-weight:300; letter-spacing:.08em; text-transform:uppercase; cursor:pointer; text-decoration:none; }
.er-share-wa { background:#25D366; color:#fff; }
.er-share-tw { background:#000; color:#fff; }
.er-share-fb { background:#1877F2; color:#fff; }
.er-share-link { background:var(--papel); color:var(--tinta); border:1px solid #ddd8d0; }
.er-divider { height:3px; background:var(--pilar); border:none; margin:24px 0; }
.er-mas-notas-titulo { font-family:'Source Serif 4',serif; font-size:10px; font-weight:300; letter-spacing:.2em; text-transform:uppercase; color:var(--tinta); margin-bottom:16px; }
.er-mas-notas-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; }
.er-card-mini { background:#fff; border-top:3px solid var(--pilar); padding:14px; transition:box-shadow .2s; }
.er-card-mini:hover { box-shadow:0 4px 16px rgba(26,26,26,.10); }
.er-card-mini-overline { font-family:'Source Serif 4',serif; font-size:9px; font-weight:300; letter-spacing:.16em; text-transform:uppercase; color:var(--pilar); display:block; margin-bottom:6px; }
.er-card-mini-titulo { font-family:'Playfair Display',Georgia,serif; font-size:15px; font-weight:700; line-height:1.3; color:var(--tinta); text-decoration:none; display:block; margin-bottom:8px; transition:color .15s; }
.er-card-mini-titulo:hover { color:var(--pilar); }
.er-card-mini-meta { font-family:'Source Serif 4',serif; font-size:10.5px; font-weight:300; color:#aaa; }

/* ── Sidebar ── */
.er-sidebar { position:sticky; top:20px; }
.er-widget { background:#fff; border-top:3px solid var(--tinta); padding:18px; margin-bottom:20px; }
.er-widget-title { font-family:'Source Serif 4',serif; font-size:9.5px; font-weight:300; letter-spacing:.2em; text-transform:uppercase; color:var(--tinta); padding-bottom:10px; border-bottom:1px solid #ddd8d0; margin-bottom:14px; }
.er-widget-list { list-style:none; padding:0; margin:0; }
.er-widget-item { padding:10px 0; border-bottom:1px solid #e8e0d0; display:flex; gap:10px; align-items:baseline; }
.er-widget-item:last-child { border-bottom:none; padding-bottom:0; }
.er-widget-num { font-family:'Playfair Display',Georgia,serif; font-size:18px; font-weight:900; color:var(--pilar); opacity:.25; flex-shrink:0; min-width:22px; line-height:1; }
.er-widget-item-title { font-family:'Playfair Display',Georgia,serif; font-size:14px; font-weight:700; line-height:1.3; color:var(--tinta); text-decoration:none; transition:color .15s; }
.er-widget-item-title:hover { color:var(--pilar); }
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
@media (max-width:900px) {
    .er-nota-layout { grid-template-columns:1fr; }
    .er-sidebar { position:static; }
    .er-mas-notas-grid { grid-template-columns:1fr 1fr; }
    .er-footer-grid { grid-template-columns:1fr 1fr; }
}
@media (max-width:600px) {
    .er-masthead { flex-wrap:wrap; }
    .er-logo-r { width:48px; height:48px; }
    .er-logo-r span { font-size:30px; }
    .er-logo-nombre { font-size:30px; }
    .er-logo-claim { display:none; }
    .er-nota-cuerpo .er-drop-cap::first-letter { float:none; font-size:1em; color:inherit; }
    .er-mas-notas-grid { grid-template-columns:1fr; }
    .er-footer-grid { grid-template-columns:1fr; }
    .er-compartir { flex-wrap:wrap; }
}
</style>

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
        <?php foreach ($er_pilares as $slug => $pilar_item) :
            $c = get_category_by_slug($slug);
            $u = $c ? esc_url(get_category_link($c->term_id)) : '#';
            $active = ($slug === $cat_slug) ? ' class="current"' : '';
        ?>
        <li><a href="<?php echo $u; ?>"<?php echo $active; ?>><?php echo esc_html($pilar_item['nombre']); ?></a></li>
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
                foreach ($recientes as $tp) : ?>
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
    <?php if ($cat) : ?>
    <span class="er-breadcrumb-sep">›</span>
    <a href="<?php echo esc_url($cat_url); ?>"><?php echo esc_html($cat_nombre); ?></a>
    <?php endif; ?>
    <span class="er-breadcrumb-sep">›</span>
    <span class="er-breadcrumb-current"><?php echo esc_html(wp_trim_words(get_the_title(), 8)); ?></span>
</div>

<!-- ═══ NOTA ═══ -->
<div class="er-nota-wrap">
<div class="er-nota-layout">

    <main>
        <header class="er-nota-header">

            <!-- Badge pilar -->
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:16px;">
                <?php if ($pilar) : ?>
                <span class="er-pilar-badge">
                    <span class="er-pilar-badge-dot"></span>
                    <?php echo esc_html($code . ' ' . $cat_nombre); ?>
                </span>
                <?php endif; ?>
            </div>

            <h1 class="er-nota-titulo"><?php the_title(); ?></h1>

            <?php if (has_excerpt()) : ?>
            <h2 class="er-nota-bajada"><?php echo esc_html(get_the_excerpt()); ?></h2>
            <?php endif; ?>

            <div class="er-nota-meta">
                <span>Por <?php the_author(); ?></span>
                <span class="er-nota-meta-sep"></span>
                <span><?php echo date_i18n('j \d\e F \d\e Y', strtotime(get_the_date('Y-m-d'))); ?></span>
                <span class="er-nota-meta-sep"></span>
                <span><?php echo $min_read; ?> min de lectura</span>
            </div>

            <!-- Imagen destacada -->
            <?php if (has_post_thumbnail()) : ?>
            <div class="er-nota-imagen">
                <?php the_post_thumbnail('large', ['style'=>'width:100%;height:100%;object-fit:cover;display:block;position:absolute;top:0;left:0;']); ?>
            </div>
            <?php $caption = get_the_post_thumbnail_caption(); if ($caption) : ?>
            <div class="er-nota-caption"><?php echo esc_html($caption); ?></div>
            <?php endif; ?>
            <?php endif; ?>

        </header>

        <!-- ═══ CUERPO ═══ -->
        <article class="er-nota-cuerpo">
            <?php the_content(); ?>

            <!-- Post-nota -->
            <div class="er-post-nota">

                <!-- Tags -->
                <?php $tags = get_the_tags(); if ($tags) : ?>
                <div class="er-tags">
                    <?php foreach ($tags as $tag) : ?>
                    <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="er-tag"><?php echo esc_html($tag->name); ?></a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Compartir -->
                <div class="er-compartir">
                    <span>Compartir</span>
                    <a href="https://api.whatsapp.com/send?text=<?php echo urlencode(get_the_title() . ' ' . $post_url); ?>" class="er-share-btn er-share-wa" target="_blank" rel="noopener">📲 WhatsApp</a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($post_url); ?>&text=<?php echo urlencode(get_the_title()); ?>" class="er-share-btn er-share-tw" target="_blank" rel="noopener">✕ Twitter</a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($post_url); ?>" class="er-share-btn er-share-fb" target="_blank" rel="noopener">f Facebook</a>
                    <a href="#" class="er-share-btn er-share-link" onclick="navigator.clipboard.writeText('<?php echo esc_js($post_url); ?>');this.textContent='✓ Copiado';return false;">🔗 Copiar link</a>
                </div>

                <!-- Más notas del pilar -->
                <?php if (!empty($mas_notas)) : ?>
                <hr class="er-divider">
                <div class="er-mas-notas-titulo">Más notas de <?php echo esc_html($cat_nombre); ?></div>
                <div class="er-mas-notas-grid">
                    <?php foreach ($mas_notas as $mn) :
                        $mn_cats = get_the_category($mn->ID);
                        $mn_cat  = $mn_cats[0] ?? null;
                        $mn_slug = $mn_cat ? $mn_cat->slug : '';
                        $mn_code = $er_pilares[$mn_slug]['code'] ?? '';
                    ?>
                    <div class="er-card-mini">
                        <span class="er-card-mini-overline"><?php echo esc_html($mn_code . ' ' . ($mn_cat ? $mn_cat->name : '')); ?></span>
                        <a href="<?php echo esc_url(get_permalink($mn->ID)); ?>" class="er-card-mini-titulo"><?php echo esc_html(get_the_title($mn->ID)); ?></a>
                        <div class="er-card-mini-meta"><?php echo date_i18n('j M', strtotime($mn->post_date)); ?> · <?php echo max(1, round(str_word_count(strip_tags($mn->post_content)) / 200)); ?> min</div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

            </div>
        </article>
    </main>

    <!-- ═══ SIDEBAR ═══ -->
    <aside class="er-sidebar">

        <!-- Lo más leído -->
        <div class="er-widget">
            <div class="er-widget-title">Lo más leído</div>
            <ul class="er-widget-list">
                <?php foreach ($populares as $i => $p) : ?>
                <li class="er-widget-item">
                    <span class="er-widget-num"><?php echo $i + 1; ?></span>
                    <a href="<?php echo esc_url(get_permalink($p->ID)); ?>" class="er-widget-item-title"><?php echo esc_html(get_the_title($p->ID)); ?></a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Pilares editoriales -->
        <div class="er-widget">
            <div class="er-widget-title">Pilares editoriales</div>
            <?php foreach ($er_pilares as $slug => $pilar_item) :
                $c = get_category_by_slug($slug);
                $u = $c ? get_category_link($c->term_id) : '#';
                $is_current = ($slug === $cat_slug);
            ?>
            <a href="<?php echo esc_url($u); ?>" class="er-pilar-link<?php echo $is_current ? ' current' : ''; ?>">
                <span class="er-pilar-dot" style="background:<?php echo esc_attr($pilar_item['color']); ?>;"></span>
                <span class="er-pilar-name"><?php echo esc_html($pilar_item['nombre']); ?></span>
                <span class="er-pilar-code"><?php echo esc_html($pilar_item['code']); ?></span>
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
</div><!-- /nota-layout -->
</div><!-- /nota-wrap -->

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
                    <?php foreach ($er_pilares as $slug => $pilar_item) :
                        $c = get_category_by_slug($slug);
                        $u = $c ? get_category_link($c->term_id) : '#';
                    ?>
                    <li><a href="<?php echo esc_url($u); ?>"><?php echo esc_html($pilar_item['nombre']); ?></a></li>
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
