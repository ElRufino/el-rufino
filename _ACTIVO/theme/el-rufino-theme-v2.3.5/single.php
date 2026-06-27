<?php
/**
 * El Rufino — single.php v2.2.0
 * Template nota individual: layout 2 columnas (70/30)
 * Mantiene: autor, fecha ES, lectura, A+/A-, TTS, FB Comments, notas relacionadas
 */
get_header(); ?>

<div class="er-single-wrap">

  <?php while ( have_posts() ) : the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class('er-article'); ?>>

      <!-- ZONA FULL WIDTH: categoría + título + meta + controles + imagen -->
      <div class="er-article-fullwidth">

        <!-- CATEGORÍA -->
        <div class="er-article-cat">
          <?php
          $cats = get_the_category();
          if ( $cats ) {
            $color = er_get_pilar_color( get_the_ID() );
            echo '<a href="' . esc_url( get_category_link( $cats[0]->term_id ) ) . '" class="er-cat-badge" style="background:' . esc_attr($color) . '">' . esc_html( strtoupper( $cats[0]->name ) ) . '</a>';
          }
          ?>
        </div>

        <!-- TÍTULO -->
        <h1 class="er-article-title"><?php the_title(); ?></h1>

        <!-- META: autor + fecha + lectura -->
        <div class="er-article-meta">
          <span class="er-meta-autor">
            <?php echo get_avatar( get_the_author_meta('ID'), 28, '', '', ['class'=>'er-autor-avatar'] ); ?>
            <span>Por <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta('ID') ) ); ?>"><?php the_author(); ?></a></span>
          </span>
          <span class="er-meta-sep">·</span>
          <span class="er-meta-fecha"><?php
            $ts = get_the_date('U');
            $meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
            echo intval(date('j',$ts)) . ' de ' . $meses[intval(date('n',$ts))-1] . ' de ' . date('Y',$ts);
          ?></span>
          <span class="er-meta-sep">·</span>
          <span class="er-meta-lectura"><?php
            $words = str_word_count( strip_tags( get_the_content() ) );
            $mins  = max(1, round($words / 200));
            echo $mins . ' min de lectura';
          ?></span>
        </div>

        <!-- CONTROLES DE LECTURA -->
        <div class="er-read-controls">
          <button class="er-btn-font" onclick="erFontSize(-1)" title="Reducir texto">A−</button>
          <button class="er-btn-font" onclick="erFontSize(1)"  title="Aumentar texto">A+</button>
          <button class="er-btn-tts" id="er-tts-btn" onclick="erToggleTTS()" title="Escuchar nota">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"/></svg>
            Escuchar
          </button>
          <div class="er-share-inline">
            <?php $url = urlencode(get_permalink()); $titulo = urlencode(get_the_title()); ?>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" target="_blank" class="er-share-btn er-share-fb" title="Compartir en Facebook">f</a>
            <a href="https://twitter.com/intent/tweet?url=<?php echo $url; ?>&text=<?php echo $titulo; ?>" target="_blank" class="er-share-btn er-share-x" title="Compartir en X">𝕏</a>
            <a href="https://wa.me/?text=<?php echo $titulo . '%20' . $url; ?>" target="_blank" class="er-share-btn er-share-wa" title="Compartir por WhatsApp">W</a>
            <a href="https://t.me/share/url?url=<?php echo $url; ?>&text=<?php echo $titulo; ?>" target="_blank" class="er-share-btn er-share-tg" title="Compartir en Telegram">✈</a>
          </div>
        </div>

        <!-- IMAGEN DESTACADA -->
        <?php if ( has_post_thumbnail() ) : ?>
          <figure class="er-article-figure">
            <?php the_post_thumbnail('er-featured', ['class'=>'er-article-img']); ?>
            <?php
            $pie_foto = get_post_meta( get_the_ID(), '_er_pie_foto', true );
            $fuente   = get_post_meta( get_the_ID(), '_er_fuente_foto', true );
            if ( $pie_foto || $fuente ) : ?>
              <figcaption class="er-figcaption">
                <?php if ($pie_foto) echo esc_html($pie_foto); ?>
                <?php if ($fuente) echo ' <span class="er-fuente">Fuente: ' . esc_html($fuente) . '</span>'; ?>
              </figcaption>
            <?php endif; ?>
          </figure>
        <?php endif; ?>

      </div><!-- /er-article-fullwidth -->

      <!-- ZONA DOS COLUMNAS: cuerpo + sidebar -->
      <div class="er-content-layout">

        <!-- COLUMNA PRINCIPAL (70%) -->
        <div class="er-content-main">

          <div class="er-article-body" id="er-article-body">
            <?php the_content(); ?>
          </div>

          <!-- TAGS -->
          <?php $tags = get_the_tags(); if ($tags) : ?>
            <div class="er-article-tags">
              <?php foreach ($tags as $tag) : ?>
                <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="er-tag">#<?php echo esc_html($tag->name); ?></a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <!-- NOTAS RELACIONADAS -->
          <?php
          $cats_rel = get_the_category();
          if ($cats_rel) {
            $rel = new WP_Query([
              'category__in'   => [$cats_rel[0]->term_id],
              'post__not_in'   => [get_the_ID()],
              'posts_per_page' => 3,
              'orderby'        => 'rand',
            ]);
            if ($rel->have_posts()) : ?>
              <section class="er-related">
                <h3 class="er-related-title">Notas relacionadas</h3>
                <div class="er-related-grid">
                  <?php while ($rel->have_posts()) : $rel->the_post(); ?>
                    <a href="<?php the_permalink(); ?>" class="er-related-card">
                      <?php if (has_post_thumbnail()) the_post_thumbnail('er-thumbnail', ['class'=>'er-related-img']); ?>
                      <span class="er-related-card-title"><?php the_title(); ?></span>
                    </a>
                  <?php endwhile; wp_reset_postdata(); ?>
                </div>
              </section>
            <?php endif;
          } ?>

          <!-- FACEBOOK COMMENTS -->
          <section class="er-comments">
            <h3 class="er-comments-title">Comentarios</h3>
            <div class="fb-comments"
                 data-href="<?php echo esc_url(get_permalink()); ?>"
                 data-width="100%"
                 data-numposts="10"
                 data-colorscheme="light"
                 data-order-by="social">
            </div>
          </section>

        </div><!-- /er-content-main -->

        <!-- SIDEBAR (30%) -->
        <aside class="er-sidebar">

          <!-- LO MÁS LEÍDO -->
          <div class="er-sidebar-widget">
            <h4 class="er-sidebar-widget-title">Lo más leído</h4>
            <?php
            $mas_leidos = new WP_Query([
              'posts_per_page' => 5,
              'orderby'        => 'comment_count',
              'order'          => 'DESC',
              'post__not_in'   => [get_the_ID()],
            ]);
            if ($mas_leidos->have_posts()) :
              $i = 1;
              while ($mas_leidos->have_posts()) : $mas_leidos->the_post(); ?>
                <a href="<?php the_permalink(); ?>" class="er-sidebar-nota">
                  <span class="er-sidebar-num"><?php echo $i; ?></span>
                  <span class="er-sidebar-nota-titulo"><?php the_title(); ?></span>
                </a>
              <?php $i++; endwhile; wp_reset_postdata();
            endif; ?>
          </div>

          <!-- BANNER PUBLICITARIO -->
          <div class="er-sidebar-widget er-sidebar-banner">
            <?php
            $banner_img = get_option('er_sidebar_banner_img', '');
            $banner_url = get_option('er_sidebar_banner_url', '#');
            $banner_alt = get_option('er_sidebar_banner_alt', 'Tu comercio aquí');
            if ($banner_img) : ?>
              <a href="<?php echo esc_url($banner_url); ?>" target="_blank" rel="noopener">
                <img src="<?php echo esc_url($banner_img); ?>" alt="<?php echo esc_attr($banner_alt); ?>" style="width:100%;height:auto;display:block;">
              </a>
            <?php else : ?>
              <a href="mailto:medioelrufino@gmail.com" class="er-banner-placeholder">
                <span class="er-banner-placeholder-titulo">Tu comercio aquí</span>
                <span class="er-banner-placeholder-sub">Publicitate en El Rufino</span>
                <span class="er-banner-placeholder-cta">Consultanos →</span>
              </a>
            <?php endif; ?>
          </div>

          <!-- ÚLTIMAS NOTICIAS -->
          <div class="er-sidebar-widget">
            <h4 class="er-sidebar-widget-title">Últimas noticias</h4>
            <?php
            $ultimas = new WP_Query([
              'posts_per_page' => 4,
              'orderby'        => 'date',
              'order'          => 'DESC',
              'post__not_in'   => [get_the_ID()],
            ]);
            if ($ultimas->have_posts()) :
              while ($ultimas->have_posts()) : $ultimas->the_post(); ?>
                <a href="<?php the_permalink(); ?>" class="er-sidebar-nota er-sidebar-nota-img">
                  <?php if (has_post_thumbnail()) : ?>
                    <div class="er-sidebar-nota-thumb">
                      <?php the_post_thumbnail('er-thumbnail', ['class'=>'er-sidebar-thumb-img']); ?>
                    </div>
                  <?php endif; ?>
                  <span class="er-sidebar-nota-titulo"><?php the_title(); ?></span>
                </a>
              <?php endwhile; wp_reset_postdata();
            endif; ?>
          </div>

        </aside><!-- /er-sidebar -->

      </div><!-- /er-content-layout -->

    </article>

  <?php endwhile; ?>

</div><!-- /er-single-wrap -->

<!-- CSS del layout de dos columnas -->
<style>
.er-single-wrap {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
}
.er-article-fullwidth {
  max-width: 100%;
}
.er-content-layout {
  display: flex;
  gap: 2rem;
  align-items: flex-start;
  margin-top: 1.5rem;
}
.er-content-main {
  flex: 0 0 68%;
  min-width: 0;
}
.er-article-body {
  font-size: 17px;
  line-height: 1.8;
}
.er-sidebar {
  flex: 0 0 29%;
  min-width: 0;
  position: sticky;
  top: 1.5rem;
}

/* Widget sidebar */
.er-sidebar-widget {
  border: 1px solid #d4cfc6;
  margin-bottom: 1.5rem;
  background: #fff;
}
.er-sidebar-widget-title {
  font-family: Arial, sans-serif;
  font-size: 0.72rem;
  font-weight: bold;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: #fff;
  background: #c0271b;
  padding: 0.5rem 0.8rem;
  margin: 0;
}

/* Notas en sidebar */
.er-sidebar-nota {
  display: flex;
  align-items: flex-start;
  gap: 0.6rem;
  padding: 0.6rem 0.8rem;
  border-bottom: 1px solid #f0ede6;
  text-decoration: none;
  color: #1a1a1a;
  font-family: Arial, sans-serif;
  font-size: 0.82rem;
  line-height: 1.4;
  transition: background 0.15s;
}
.er-sidebar-nota:last-child { border-bottom: none; }
.er-sidebar-nota:hover { background: #f5f0e8; }
.er-sidebar-num {
  flex-shrink: 0;
  width: 22px;
  height: 22px;
  background: #f5f0e8;
  color: #c0271b;
  font-weight: bold;
  font-size: 0.75rem;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 2px;
}
.er-sidebar-nota-titulo { flex: 1; }
.er-sidebar-nota-img { align-items: center; }
.er-sidebar-nota-thumb { flex-shrink: 0; width: 56px; height: 42px; overflow: hidden; }
.er-sidebar-thumb-img { width: 100%; height: 100%; object-fit: cover; display: block; }

/* Banner placeholder */
.er-sidebar-banner { padding: 0; overflow: hidden; }
.er-banner-placeholder {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 1.5rem 1rem;
  text-align: center;
  background: #f5f0e8;
  text-decoration: none;
  min-height: 160px;
  gap: 0.4rem;
}
.er-banner-placeholder-titulo {
  font-family: Arial, sans-serif;
  font-size: 1rem;
  font-weight: bold;
  color: #1a1a1a;
}
.er-banner-placeholder-sub {
  font-family: Arial, sans-serif;
  font-size: 0.78rem;
  color: #6a6a6a;
}
.er-banner-placeholder-cta {
  font-family: Arial, sans-serif;
  font-size: 0.75rem;
  font-weight: bold;
  color: #c0271b;
  margin-top: 0.4rem;
}

/* Mobile: una columna */
@media (max-width: 768px) {
  .er-content-layout {
    flex-direction: column;
  }
  .er-content-main,
  .er-sidebar {
    flex: 0 0 100%;
    width: 100%;
  }
  .er-sidebar {
    position: static;
  }
}
</style>

<!-- Facebook SDK -->
<div id="fb-root"></div>
<script async defer crossorigin="anonymous"
  src="https://connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v19.0&appId=1314916506819026">
</script>

<!-- TTS + Font size -->
<script>
var erFontBase = 17;
var erTTSactive = false;
var erUtterance = null;

function erFontSize(dir) {
  erFontBase = Math.min(22, Math.max(14, erFontBase + dir));
  document.getElementById('er-article-body').style.fontSize = erFontBase + 'px';
}

function erToggleTTS() {
  var btn = document.getElementById('er-tts-btn');
  if (!('speechSynthesis' in window)) {
    btn.textContent = 'No disponible';
    return;
  }
  if (erTTSactive) {
    window.speechSynthesis.cancel();
    erTTSactive = false;
    btn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"/></svg> Escuchar';
    btn.classList.remove('er-tts-playing');
  } else {
    var texto = document.getElementById('er-article-body').innerText;
    erUtterance = new SpeechSynthesisUtterance(texto);
    erUtterance.lang = 'es-AR';
    erUtterance.rate = 0.95;
    erUtterance.onend = function() {
      erTTSactive = false;
      btn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"/></svg> Escuchar';
      btn.classList.remove('er-tts-playing');
    };
    window.speechSynthesis.speak(erUtterance);
    erTTSactive = true;
    btn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg> Pausar';
    btn.classList.add('er-tts-playing');
  }
}
</script>

<?php get_footer(); ?>