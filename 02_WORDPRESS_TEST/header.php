<?php
/**
 * Header — El Rufino Child Theme
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- TOPBAR -->
<div style="background:#1a1a1a; color:#f5f0e8; padding:6px 20px; font-size:13px;">
    <?php echo date_i18n('D. d \d\e F \d\e Y'); ?>
</div>

<!-- HEADER -->
<header style="background:#ffffff; padding:20px 30px; display:flex; align-items:center; gap:16px;">
    <?php if (has_custom_logo()): ?>
        <div style="max-height:80px;">
            <?php the_custom_logo(); ?>
        </div>
    <?php endif; ?>
    <div>
        <div style="font-family:'Playfair Display',serif; font-size:2rem; font-weight:700; color:#1a1a1a;">
            <?php bloginfo('name'); ?>
        </div>
        <div style="font-size:0.8rem; letter-spacing:0.15em; color:#c0271b; text-transform:uppercase;">
            <?php bloginfo('description'); ?>
        </div>
    </div>
</header>

<!-- NAV -->
<nav style="background:#c0271b; padding:0 20px;">
    <?php wp_nav_menu(['theme_location' => 'primary', 'menu_class' => 'er-nav']); ?>
</nav>