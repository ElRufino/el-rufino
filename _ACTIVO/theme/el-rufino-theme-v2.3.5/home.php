<?php
/**
 * El Rufino — home.php
 * Layout T3A: Banner destacado + grilla 3 columnas
 * Specs: imagen 44% / 148px altura banner / 72px imagen secundaria
 * Etiqueta pilar: estilo Línea | Borde acento: Sutil
 * v1.0 — Mayo 2026
 */

get_header();

/* ------------------------------------------------------------------
   COLORES POR PILAR (slug de categoría → color)
------------------------------------------------------------------ */
function er_pilar_color( $post_id ) {
    $mapa = array(
        'rufino-real'          => '#c0271b',
        'el-campo-habla'       => '#2a5f82',
        'barrio-a-barrio'      => '#4e7232',
        'generacion-rufino'    => '#b8760a',
        'seguimiento-promesas' => '#7a3d9a',
        'contexto-datos'       => '#1a6b5a',
    );
    $cats = get_the_category( $post_id );
    if ( $cats ) {
        foreach ( $cats as $cat ) {
            if ( isset( $mapa[ $cat->slug ] ) ) {
                return array(
                    'color' => $mapa[ $cat->slug ],
                    'nombre' => $cat->name,
                );
            }
        }
    }
    return array( 'color' => '#888888', 'nombre' => '' );
}

/* ------------------------------------------------------------------
   PILARES EDITORIALES — lista completa con URL
------------------------------------------------------------------ */
function er_pilares_todos() {
    $pilares = array(
        array( 'slug' => 'rufino-real',          'nombre' => 'Rufino Real',             'color' => '#c0271b' ),
        array( 'slug' => 'el-campo-habla',        'nombre' => 'El Campo Habla',          'color' => '#2a5f82' ),
        array( 'slug' => 'barrio-a-barrio',       'nombre' => 'Barrio a Barrio',         'color' => '#4e7232' ),
        array( 'slug' => 'generacion-rufino',     'nombre' => 'Generación Rufino',       'color' => '#b8760a' ),
        array( 'slug' => 'poder-y-gestion',       'nombre' => 'Poder y Gestión',         'color' => '#7a3d9a' ),
        array( 'slug' => 'rufino-en-datos',       'nombre' => 'Rufino en Datos',         'color' => '#1a6b5a' ),
    );
    foreach ( $pilares as &$p ) {
        $term     = get_term_by( 'slug', $p['slug'], 'category' );
        $p['url'] = $term ? get_category_link( $term->term_id ) : get_home_url();
    }
    return $pilares;
}

/* ------------------------------------------------------------------
   TIEMPO DE LECTURA
------------------------------------------------------------------ */
function er_tiempo_lectura( $post_id ) {
    $contenido = get_post_field( 'post_content', $post_id );
    $palabras  = str_word_count( wp_strip_all_tags( $contenido ) );
    $minutos   = max( 1, round( $palabras / 200 ) );
    return $minutos . ' min lectura';
}

/* ------------------------------------------------------------------
   QUERY PRINCIPAL: última nota publicada
------------------------------------------------------------------ */
$q_destacada = new WP_Query( array(
    'posts_per_page' => 1,
    'post_status'    => 'publish',
) );

/* ------------------------------------------------------------------
   QUERY SECUNDARIA: siguientes 3 notas (excluye la destacada)
------------------------------------------------------------------ */
$excluir_id = 0;
if ( $q_destacada->have_posts() ) {
    $q_destacada->the_post();
    $excluir_id = get_the_ID();
    wp_reset_postdata();
}

$q_secundarias = new WP_Query( array(
    'posts_per_page' => 3,
    'post_status'    => 'publish',
    'post__not_in'   => array( $excluir_id ),
) );

/* ------------------------------------------------------------------
   QUERY LO MÁS LEÍDO: 5 posts por comment_count
------------------------------------------------------------------ */
$q_mas_leido = new WP_Query( array(
    'posts_per_page' => 5,
    'post_status'    => 'publish',
    'orderby'        => 'comment_count',
    'order'          => 'DESC',
) );
?>

<div class="er-layout">

  <div class="er-layout-main">

  <!-- ============================================================
       SECCIÓN: EDICIÓN DE HOY
  ============================================================ -->
  <div class="er-seccion-header">
    <span class="er-seccion-label">Edición de hoy &middot; Lo más importante</span>
    <a href="<?php echo get_permalink( get_option('page_for_posts') ); ?>" class="er-ver-todas">
      Ver todas las notas &rarr;
    </a>
  </div>

  <!-- ============================================================
       BLOQUE T3A: NOTA DESTACADA
  ============================================================ -->
  <?php
  $q_destacada->rewind_posts();
  if ( $q_destacada->have_posts() ) :
    $q_destacada->the_post();
    $pid       = get_the_ID();
    $pilar     = er_pilar_color( $pid );
    $tiempo    = er_tiempo_lectura( $pid );
    $autor     = get_the_author();
    $fecha     = get_the_date( 'j M Y' );
    $excerpt   = has_excerpt() ? get_the_excerpt() : wp_trim_words( get_the_content(), 22, '...' );
    $img_url   = get_the_post_thumbnail_url( $pid, 'large' );
  ?>

  <article class="er-banner-t3a">
    <a href="<?php the_permalink(); ?>" class="er-banner-link" aria-label="<?php the_title_attribute(); ?>">

      <!-- Imagen izquierda 44% -->
      <div class="er-banner-img" style="<?php echo $img_url ? 'background-image:url(' . esc_url($img_url) . ')' : ''; ?>">
        <?php if ( ! $img_url ) : ?>
          <div class="er-img-placeholder"></div>
        <?php endif; ?>
      </div>

      <!-- Contenido derecho 56% -->
      <div class="er-banner-contenido">
        <?php if ( $pilar['nombre'] ) : ?>
          <span class="er-tag-pilar" style="color:<?php echo esc_attr($pilar['color']); ?>; border-bottom-color:<?php echo esc_attr($pilar['color']); ?>">
            <?php echo esc_html($pilar['nombre']); ?>
          </span>
        <?php endif; ?>

        <h2 class="er-banner-titulo"><?php the_title(); ?></h2>

        <?php if ( $excerpt ) : ?>
          <p class="er-banner-bajada"><?php echo esc_html($excerpt); ?></p>
        <?php endif; ?>

        <div class="er-banner-meta">
          <span><?php echo esc_html($fecha); ?></span>
          <span class="er-meta-sep">&bull;</span>
          <span><?php echo esc_html($tiempo); ?></span>
          <span class="er-meta-sep">&bull;</span>
          <span>Por <?php echo esc_html($autor); ?></span>
        </div>
      </div>

    </a>
  </article>

  <?php wp_reset_postdata(); endif; ?>

  <!-- ============================================================
       GRILLA SECUNDARIA: 3 COLUMNAS
  ============================================================ -->
  <?php if ( $q_secundarias->have_posts() ) : ?>
  <div class="er-grilla-secundaria">

    <?php while ( $q_secundarias->have_posts() ) : $q_secundarias->the_post();
      $pid2    = get_the_ID();
      $pilar2  = er_pilar_color( $pid2 );
      $tiempo2 = er_tiempo_lectura( $pid2 );
      $img2    = get_the_post_thumbnail_url( $pid2, 'medium' );
      $fecha2  = get_the_date( 'j M' );
    ?>

    <article class="er-card-secundaria">
      <a href="<?php the_permalink(); ?>" class="er-card-link" aria-label="<?php the_title_attribute(); ?>">

        <!-- Imagen superior 72px -->
        <div class="er-card-img" style="<?php echo $img2 ? 'background-image:url(' . esc_url($img2) . ')' : ''; ?>"></div>

        <!-- Cuerpo -->
        <div class="er-card-body">
          <?php if ( $pilar2['nombre'] ) : ?>
            <span class="er-tag-pilar" style="color:<?php echo esc_attr($pilar2['color']); ?>; border-bottom-color:<?php echo esc_attr($pilar2['color']); ?>">
              <?php echo esc_html($pilar2['nombre']); ?>
            </span>
          <?php endif; ?>

          <h3 class="er-card-titulo"><?php the_title(); ?></h3>

          <div class="er-card-meta">
            <span><?php echo esc_html($fecha2); ?></span>
            <span class="er-meta-sep">&bull;</span>
            <span><?php echo esc_html($tiempo2); ?></span>
          </div>
        </div>

      </a>
    </article>

    <?php endwhile; wp_reset_postdata(); ?>
  </div>
  <?php endif; ?>

  </div><!-- .er-layout-main -->

  <!-- ============================================================
       SIDEBAR
  ============================================================ -->
  <aside class="er-sidebar">

    <!-- Widget: Lo más leído -->
    <div class="er-widget">
      <h3 class="er-widget-title">Lo más leído</h3>
      <ul class="er-widget-list">
        <?php
        if ( $q_mas_leido->have_posts() ) :
          while ( $q_mas_leido->have_posts() ) : $q_mas_leido->the_post();
        ?>
        <li class="er-widget-item">
          <a href="<?php the_permalink(); ?>">
            <p class="er-widget-item-titulo"><?php the_title(); ?></p>
            <span class="er-widget-item-meta"><?php the_date( 'j M Y' ); ?></span>
          </a>
        </li>
        <?php endwhile; wp_reset_postdata(); endif; ?>
      </ul>
    </div>

    <!-- Widget: Pilares editoriales -->
    <div class="er-widget">
      <h3 class="er-widget-title">Pilares editoriales</h3>
      <ul class="er-pilares-list">
        <?php foreach ( er_pilares_todos() as $pilar ) : ?>
        <li>
          <a href="<?php echo esc_url( $pilar['url'] ); ?>" class="er-pilar-link">
            <span class="er-pilar-dot" style="background:<?php echo esc_attr( $pilar['color'] ); ?>"></span>
            <?php echo esc_html( $pilar['nombre'] ); ?>
          </a>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <!-- Widget: WhatsApp -->
    <div class="er-widget">
      <h3 class="er-widget-title">Seguinos</h3>
      <div class="er-widget-wa">
        <p class="er-widget-wa-text">Recibí las noticias de Rufino directo en tu WhatsApp.</p>
        <a href="https://wa.me/5493382511670" class="er-widget-wa-btn" target="_blank" rel="noopener">
          Unirme al canal &rarr;
        </a>
      </div>
    </div>

  </aside>

</div><!-- .er-layout -->

<?php get_footer(); ?>
