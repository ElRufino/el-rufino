<?php
/**
 * El Rufino — home.php v1.0.0
 * Portada con layout: héroe + grillas por pilar + sidebar
 * Design System v1.1 · Mayo 2026
 */

// Helper: obtener N posts de una categoría
function er_get_posts($slug, $n = 3) {
    return get_posts([
        'category_name'  => $slug,
        'numberposts'    => $n,
        'post_status'    => 'publish',
        'no_found_rows'  => true,
    ]);
}

// Helper: fecha en español
function er_fecha($post_id) {
    $ts     = get_post_time('U', false, $post_id);
    $meses  = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
    return intval(date('j', $ts)) . ' ' . $meses[intval(date('n', $ts)) - 1];
}

// Helper: tiempo de lectura
function er_lectura($post_id) {
    $words = str_word_count(strip_tags(get_post_field('post_content', $post_id)));
    return max(1, round($words / 200)) . ' min';
}

// Helper: color de pilar por slug
function er_color($slug) {
    $map = [
        'rufino-real'           => '#c0271b',
        'el-campo-habla'        => '#4a7c59',
        'barrio-a-barrio'       => '#2d5f8a',
        'generacion-rufino'     => '#7b4fa6',
        'seguimiento-promesas'  => '#1a1a1a',
        'poder-y-gestion'       => '#1a1a1a',
        'contexto-datos'        => '#c8600a',
        'rufino-en-datos'       => '#c8600a',
    ];
    return $map[$slug] ?? '#c0271b';
}

// Helper: card featured
function er_card_featured($post, $slug, $color) {
    $thumb = get_the_post_thumbnail($post->ID, 'er-featured', ['class' => 'er-card-featured-img-src']);
    $cats  = get_the_category($post->ID);
    $cat_name = $cats ? $cats[0]->name : '';
    $excerpt  = get_the_excerpt($post->ID) ?: wp_trim_words(get_post_field('post_content', $post->ID), 20);
    ?>
    <div class="er-card-featured" style="border-top-color:<?php echo esc_attr($color); ?>">
        <div class="er-card-featured-img">
            <?php if ($thumb) echo $thumb; else echo '<span class="er-card-img-ph">Sin imagen</span>'; ?>
        </div>
        <div class="er-card-featured-body">
            <a href="<?php echo esc_url(get_category_link($cats[0]->term_id ?? 0)); ?>" class="er-card-overline" style="color:<?php echo esc_attr($color); ?>"><?php echo esc_html(strtoupper($cat_name)); ?></a>
            <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="er-card-titulo"><?php echo esc_html($post->post_title); ?></a>
            <p class="er-card-bajada"><?php echo esc_html($excerpt); ?></p>
            <div class="er-card-meta">
                <span><?php echo er_fecha($post->ID); ?></span>
                <span class="er-meta-sep"></span>
                <span><?php echo er_lectura($post->ID); ?></span>
            </div>
        </div>
    </div>
    <?php
}

// Helper: card compact
function er_card_compact($post, $color, $border_style = 'top') {
    $cats = get_the_category($post->ID);
    $cat_name = $cats ? $cats[0]->name : '';
    $border = $border_style === 'top'
        ? 'border-top:3px solid ' . $color
        : 'border-top:1px solid #e8e0d0';
    ?>
    <div class="er-card-compact" style="<?php echo esc_attr($border); ?>">
        <a href="<?php echo esc_url(get_category_link($cats[0]->term_id ?? 0)); ?>" class="er-card-overline" style="color:<?php echo esc_attr($color); ?>"><?php echo esc_html(strtoupper($cat_name)); ?></a>
        <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="er-card-compact-titulo"><?php echo esc_html($post->post_title); ?></a>
        <div class="er-card-meta" style="margin-top:8px;">
            <span><?php echo er_fecha($post->ID); ?></span>
            <span class="er-meta-sep"></span>
            <span><?php echo er_lectura($post->ID); ?></span>
        </div>
    </div>
    <?php
}

// Helper: encabezado de pilar
function er_pilar_header($nombre, $color, $cat_slug) {
    $cat = get_category_by_slug($cat_slug);
    $url = $cat ? esc_url(get_category_link($cat->term_id)) : '#';
    ?>
    <div class="er-pilar-header" style="border-bottom-color:<?php echo esc_attr($color); ?>">
        <span class="er-pilar-header-nombre" style="color:<?php echo esc_attr($color); ?>"><?php echo esc_html($nombre); ?></span>
        <a href="<?php echo $url; ?>" class="er-pilar-header-link">Ver todo →</a>
    </div>
    <?php
}

get_header();
$wa_url = get_option('er_whatsapp_canal', 'https://whatsapp.com/channel/elrufino');

// — Datos por pilar —
$p01 = er_get_posts('rufino-real', 3);
$p02 = er_get_posts('el-campo-habla', 3);
$p03 = er_get_posts('barrio-a-barrio', 3);
$p04 = er_get_posts('generacion-rufino', 3);
$p05 = er_get_posts('seguimiento-promesas', 4);
if (empty($p05)) $p05 = er_get_posts('poder-y-gestion', 4);
$p06 = er_get_posts('contexto-datos', 2);
if (empty($p06)) $p06 = er_get_posts('rufino-en-datos', 2);

// — Post héroe: sticky o más reciente —
$hero_posts = get_posts(['numberposts' => 1, 'post_status' => 'publish', 'ignore_sticky_posts' => 0]);
$hero = !empty($hero_posts) ? $hero_posts[0] : null;
$hero_cats = $hero ? get_the_category($hero->ID) : [];
$hero_color = $hero_cats ? er_color($hero_cats[0]->slug) : '#c0271b';
?>

<div class="er-container">

  <?php if ($hero) : ?>
  <!-- ══ HÉROE ══ -->
  <div style="padding-top:28px;">
    <div class="er-hero">
      <div class="er-hero-img">
        <?php if (has_post_thumbnail($hero->ID)) : ?>
          <?php echo get_the_post_thumbnail($hero->ID, 'er-wide', ['class' => 'er-hero-img-src']); ?>
        <?php else : ?>
          <div class="er-hero-img-placeholder">El Rufino</div>
        <?php endif; ?>
      </div>
      <div class="er-hero-body">
        <div class="er-hero-badges">
          <?php if ($hero_cats) : ?>
            <span class="er-badge-pilar" style="background:<?php echo esc_attr($hero_color); ?>1a; color:<?php echo esc_attr($hero_color); ?>; border:1px solid <?php echo esc_attr($hero_color); ?>66;">
              <?php echo esc_html(strtoupper($hero_cats[0]->name)); ?>
            </span>
          <?php endif; ?>
        </div>
        <h1 class="er-hero-titulo">
          <a href="<?php echo esc_url(get_permalink($hero->ID)); ?>" style="color:inherit;text-decoration:none;"><?php echo esc_html($hero->post_title); ?></a>
        </h1>
        <?php $hero_excerpt = get_the_excerpt($hero->ID) ?: wp_trim_words(get_post_field('post_content', $hero->ID), 25); ?>
        <p class="er-hero-bajada"><?php echo esc_html($hero_excerpt); ?></p>
        <div class="er-hero-meta">
          <span><?php echo er_fecha($hero->ID); ?></span>
          <span class="er-meta-sep"></span>
          <span><?php echo er_lectura($hero->ID); ?> de lectura</span>
        </div>
        <div class="er-hero-cta">
          <a href="<?php echo esc_url(get_permalink($hero->ID)); ?>" class="er-btn-leer">Leer nota completa →</a>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- ══ LAYOUT MAIN + SIDEBAR ══ -->
  <div class="er-page-layout">
    <main class="er-main">

      <?php if (!empty($p01)) : ?>
      <!-- P01 — Rufino real -->
      <div class="er-pilar-seccion">
        <?php er_pilar_header('Rufino real', '#c0271b', 'rufino-real'); ?>
        <div class="er-pilar-grid">
          <?php er_card_featured($p01[0], 'rufino-real', '#c0271b'); ?>
          <div class="er-compactas-stack">
            <?php if (!empty($p01[1])) er_card_compact($p01[1], '#c0271b', 'top'); ?>
            <?php if (!empty($p01[2])) er_card_compact($p01[2], '#c0271b', 'divider'); ?>
          </div>
        </div>
      </div>
      <div class="er-pilar-sep"></div>
      <?php endif; ?>

      <?php if (!empty($p02)) : ?>
      <!-- P02 — El campo habla -->
      <div class="er-pilar-seccion">
        <?php er_pilar_header('El campo habla', '#4a7c59', 'el-campo-habla'); ?>
        <div class="er-pilar-grid">
          <?php er_card_featured($p02[0], 'el-campo-habla', '#4a7c59'); ?>
          <div class="er-compactas-stack">
            <?php if (!empty($p02[1])) er_card_compact($p02[1], '#4a7c59', 'top'); ?>
            <?php if (!empty($p02[2])) er_card_compact($p02[2], '#4a7c59', 'divider'); ?>
          </div>
        </div>
      </div>
      <div class="er-pilar-sep"></div>
      <?php endif; ?>

      <!-- Bloque WhatsApp -->
      <div class="er-wa-banner">
        <div>
          <div class="er-wa-texto">Recibí las noticias de Rufino directo en tu celular</div>
          <div class="er-wa-sub">Sin algoritmos. Sin spam. Solo lo que importa en Rufino.</div>
        </div>
        <a href="<?php echo esc_url($wa_url); ?>" class="er-wa-btn" target="_blank" rel="noopener">📲 UNIRME AL CANAL</a>
      </div>

      <?php if (!empty($p03)) : ?>
      <!-- P03 — Barrio a barrio -->
      <div class="er-pilar-seccion">
        <?php er_pilar_header('Barrio a barrio', '#2d5f8a', 'barrio-a-barrio'); ?>
        <div class="er-grid-3">
          <?php foreach (array_slice($p03, 0, 3) as $p) : ?>
            <?php er_card_compact($p, '#2d5f8a', 'top'); ?>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="er-pilar-sep"></div>
      <?php endif; ?>

      <?php if (!empty($p04)) : ?>
      <!-- P04 — Generación Rufino -->
      <div class="er-pilar-seccion">
        <?php er_pilar_header('Generación Rufino', '#7b4fa6', 'generacion-rufino'); ?>
        <div class="er-grid-fotos">
          <?php foreach (array_slice($p04, 0, 3) as $p) :
            $cats = get_the_category($p->ID);
          ?>
            <div class="er-card-foto">
              <div class="er-card-foto-img">
                <?php if (has_post_thumbnail($p->ID)) : ?>
                  <?php echo get_the_post_thumbnail($p->ID, 'er-card', ['style' => 'width:100%;height:100%;object-fit:cover;']); ?>
                <?php else : ?>
                  <span style="font-size:12px;font-style:italic;color:#aaa;">Sin imagen</span>
                <?php endif; ?>
              </div>
              <div class="er-card-foto-body">
                <a href="<?php echo esc_url(get_category_link($cats[0]->term_id ?? 0)); ?>" class="er-card-overline" style="color:#7b4fa6;"><?php echo esc_html(strtoupper($cats[0]->name ?? '')); ?></a>
                <a href="<?php echo esc_url(get_permalink($p->ID)); ?>" class="er-card-compact-titulo"><?php echo esc_html($p->post_title); ?></a>
                <div class="er-card-meta" style="margin-top:8px;">
                  <span><?php echo er_fecha($p->ID); ?></span>
                  <span class="er-meta-sep"></span>
                  <span><?php echo er_lectura($p->ID); ?></span>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="er-pilar-sep"></div>
      <?php endif; ?>

      <?php if (!empty($p05)) : ?>
      <!-- P05 — Seguimiento de promesas -->
      <div class="er-pilar-seccion">
        <?php er_pilar_header('Seguimiento de promesas', '#1a1a1a', 'seguimiento-promesas'); ?>
        <div class="er-p05-lista">
          <?php foreach (array_slice($p05, 0, 5) as $p) :
            $estado_raw = get_post_meta($p->ID, '_er_estado_promesa', true);
            $estado_map = [
                'cumplida'   => ['label' => 'Cumplida',    'class' => 'es-cumplida'],
                'incumplida' => ['label' => 'Incumplida',  'class' => 'es-incumplida'],
                'en-proceso' => ['label' => 'En proceso',  'class' => 'es-en-curso'],
                'pendiente'  => ['label' => 'Pendiente',   'class' => 'es-pendiente'],
            ];
            $estado = $estado_map[$estado_raw] ?? ['label' => 'Pendiente', 'class' => 'es-pendiente'];
          ?>
            <div class="er-p05-item">
              <span class="er-p05-fecha"><?php echo er_fecha($p->ID); ?></span>
              <a href="<?php echo esc_url(get_permalink($p->ID)); ?>" class="er-p05-titulo"><?php echo esc_html($p->post_title); ?></a>
              <span class="er-p05-estado <?php echo esc_attr($estado['class']); ?>"><?php echo esc_html($estado['label']); ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="er-pilar-sep"></div>
      <?php endif; ?>

      <?php if (!empty($p06)) : ?>
      <!-- P06 — Rufino en datos -->
      <div class="er-pilar-seccion">
        <?php er_pilar_header('Rufino en datos', '#c8600a', 'contexto-datos'); ?>
        <div class="er-grid-datos">
          <?php foreach (array_slice($p06, 0, 2) as $p) :
            $dato_num = get_post_meta($p->ID, '_er_dato_num', true);
            $dato_label = get_post_meta($p->ID, '_er_dato_label', true);
            $excerpt = get_the_excerpt($p->ID) ?: wp_trim_words(get_post_field('post_content', $p->ID), 20);
          ?>
            <div class="er-card-dato">
              <?php if ($dato_label) : ?>
                <div class="er-dato-label"><?php echo esc_html($dato_label); ?></div>
              <?php endif; ?>
              <?php if ($dato_num) : ?>
                <div class="er-dato-num"><?php echo esc_html($dato_num); ?></div>
              <?php endif; ?>
              <a href="<?php echo esc_url(get_permalink($p->ID)); ?>" class="er-dato-titulo"><?php echo esc_html($p->post_title); ?></a>
              <p class="er-dato-contexto"><?php echo esc_html($excerpt); ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

    </main>

    <!-- ══ SIDEBAR ══ -->
    <aside class="er-sidebar">

      <!-- Última hora -->
      <div class="er-widget er-widget-rojo">
        <div class="er-widget-title">Última hora</div>
        <ul class="er-hora-list">
          <?php
          $recientes = get_posts(['numberposts' => 5, 'post_status' => 'publish', 'no_found_rows' => true]);
          foreach ($recientes as $r) : ?>
            <li class="er-hora-item">
              <span class="er-hora-time"><?php echo get_the_time('H:i', $r->ID); ?></span>
              <a href="<?php echo esc_url(get_permalink($r->ID)); ?>" class="er-hora-titulo"><?php echo esc_html($r->post_title); ?></a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- Lo más leído (por comentarios como proxy) -->
      <div class="er-widget">
        <div class="er-widget-title">Lo más leído</div>
        <ul class="er-leido-list">
          <?php
          $leidos = get_posts(['numberposts' => 5, 'post_status' => 'publish', 'orderby' => 'comment_count', 'no_found_rows' => true]);
          foreach ($leidos as $i => $l) : ?>
            <li class="er-leido-item">
              <span class="er-leido-num"><?php echo $i + 1; ?></span>
              <a href="<?php echo esc_url(get_permalink($l->ID)); ?>" class="er-leido-titulo"><?php echo esc_html($l->post_title); ?></a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- Pilares -->
      <div class="er-widget">
        <div class="er-widget-title">Pilares editoriales</div>
        <?php
        $pilares = [
            'rufino-real'          => ['Rufino real',          '#c0271b', 'P01'],
            'el-campo-habla'       => ['El campo habla',       '#4a7c59', 'P02'],
            'barrio-a-barrio'      => ['Barrio a barrio',      '#2d5f8a', 'P03'],
            'generacion-rufino'    => ['Generación Rufino',    '#7b4fa6', 'P04'],
            'seguimiento-promesas' => ['Seguimiento promesas', '#1a1a1a', 'P05'],
            'contexto-datos'       => ['Rufino en datos',      '#c8600a', 'P06'],
        ];
        foreach ($pilares as $slug => [$nombre, $color, $code]) :
            $cat = get_category_by_slug($slug);
            $url = $cat ? esc_url(get_category_link($cat->term_id)) : '#';
        ?>
          <a href="<?php echo $url; ?>" class="er-pilar-link">
            <span class="er-pilar-dot" style="background:<?php echo esc_attr($color); ?>;"></span>
            <span class="er-pilar-name"><?php echo esc_html($nombre); ?></span>
            <span class="er-pilar-code"><?php echo esc_html($code); ?></span>
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
  </div>
</div>



<?php get_footer(); ?>
