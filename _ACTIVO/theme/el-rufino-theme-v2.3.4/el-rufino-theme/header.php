<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- ============================================================
     TOPBAR
============================================================ -->
<div class="er-topbar">
    <span><?php echo date_i18n('l j \d\e F \d\e Y'); ?></span>
    <span class="er-topbar-loc">Rufino &middot; Santa Fe &middot; Argentina</span>
</div>

<!-- ============================================================
     MASTHEAD
============================================================ -->
<header class="er-masthead" role="banner">
    <a href="<?php echo esc_url(home_url('/')); ?>" class="er-masthead-logo">
        <div class="er-logo-r"><span>R</span></div>
        <div class="er-logo-texto">
            <span class="er-logo-nombre">El Rufino</span>
            <span class="er-logo-claim">Lo que pasa y lo que significa</span>
        </div>
    </a>
    <a href="<?php echo esc_url(get_option('er_whatsapp_canal', 'https://wa.me/5493382511670')); ?>" class="er-btn-wa" target="_blank" rel="noopener">
        📲 WhatsApp
    </a>
</header>

<!-- ============================================================
     NAVEGACIÓN
============================================================ -->
<nav class="er-nav" role="navigation" aria-label="Menú principal">
    <div class="er-nav-inner">
        <?php
        wp_nav_menu(array(
            'theme_location' => 'primary',
            'container'      => false,
            'menu_class'     => 'er-nav-menu',
            'fallback_cb'    => 'er_nav_fallback',
        ));
        ?>
    </div>
</nav>

<!-- ============================================================
     TICKER
============================================================ -->
<?php
$q_ticker = new WP_Query(array(
    'posts_per_page' => 6,
    'post_status'    => 'publish',
    'no_found_rows'  => true,
));
if ($q_ticker->have_posts()) :
    $items = '';
    while ($q_ticker->have_posts()) {
        $q_ticker->the_post();
        $items .= '<a href="' . esc_url(get_permalink()) . '" class="er-ticker-item">'
                . '<span class="er-ticker-dot"></span>'
                . esc_html(get_the_title())
                . '</a>';
    }
    wp_reset_postdata();
?>
<div class="er-ticker">
    <div class="er-ticker-label">Último momento</div>
    <div class="er-ticker-track">
        <div class="er-ticker-inner"><?php echo $items . $items; ?></div>
    </div>
</div>
<?php endif; ?>

<!-- ============================================================
     CONTENIDO PRINCIPAL
============================================================ -->
<main id="main" class="er-main" role="main">
<?php
// Fallback para navegación sin menú asignado
function er_nav_fallback() {
    echo '<ul class="er-nav-menu">';
    echo '<li><a href="' . esc_url(home_url('/')) . '">Inicio</a></li>';
    $cats = array(
        'rufino-real'          => 'Rufino real',
        'el-campo-habla'       => 'El campo habla',
        'barrio-a-barrio'      => 'Barrio a barrio',
        'generacion-rufino'    => 'Generación Rufino',
        'seguimiento-promesas' => 'Poder y gestión',
        'contexto-datos'       => 'Rufino en datos',
    );
    foreach ($cats as $slug => $nombre) {
        $term = get_term_by('slug', $slug, 'category');
        $url  = $term ? get_category_link($term->term_id) : home_url('/categoria/' . $slug);
        echo '<li><a href="' . esc_url($url) . '">' . esc_html($nombre) . '</a></li>';
    }
    echo '</ul>';
}
?>
