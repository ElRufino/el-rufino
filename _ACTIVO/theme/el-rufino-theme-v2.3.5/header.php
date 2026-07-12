<?php
/**
 * Header — El Rufino Child Theme v2.0.0
 * Topbar, masthead y ticker se renderizan vía wp_body_open()
 * en functions.php (er_render_masthead priority 1, er_render_ticker priority 2)
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<!-- wp_body_open() dispara:
     priority 1 → er_render_masthead() — topbar + masthead
     priority 2 → er_render_ticker()   — ticker de noticias
-->
<nav class="er-nav" style="display:flex;flex-direction:row;flex-wrap:wrap;">
    <?php wp_nav_menu( [
        'theme_location' => 'primary',
        'container'      => false,
        'menu_class'     => 'er-nav-list',
        'depth'          => 1,
        'fallback_cb'    => false,
    ] ); ?>
</nav>
<main id="main" class="er-main" role="main">
