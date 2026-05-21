<?php
/**
 * El Rufino — single.php v2.1.0
 * Template nota individual: autor, fecha ES, lectura, A+/A-, TTS, FB Comments
 */
get_header(); ?>

<div class="er-single-wrap">
  <main class="er-single-main">
    <?php while ( have_posts() ) : the_post(); ?>

      <article id="post-<?php the_ID(); ?>" <?php post_class('er-article'); ?>>

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

        <!-- IMAGEN DESTACADA + PIE DE FOTO -->
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

        <!-- CUERPO -->
        <div class="er-article-body" id="er-article-body" style="padding:0 40px;max-width:720px;margin:0 auto;box-sizing:border-box;">
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

      </article>

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

    <?php endwhile; ?>
  </main>
</div>

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
