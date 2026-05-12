<?php
/**
 * El Rufino — footer.php v1.0.0
 * Footer completo con grilla de columnas
 * Design System v1.1 · Mayo 2026
 */
$wa_url = get_option('er_whatsapp_canal', 'https://whatsapp.com/channel/elrufino');
?>

<footer class="er-footer">
  <div class="er-footer-inner">
    <div class="er-footer-grid">

      <div class="er-footer-col-marca">
        <div class="er-footer-logo-row">
          <div class="er-footer-logo-r"><span>R</span></div>
          <span class="er-footer-logo-name">El Rufino</span>
        </div>
        <div class="er-footer-claim">Lo que pasa y lo que significa.</div>
        <div class="er-footer-desc">Medio digital local de Rufino, Santa Fe, Argentina. Noticias verificadas, contexto y seguimiento desde 2026.</div>
      </div>

      <div>
        <div class="er-footer-col-title">Pilares</div>
        <ul class="er-footer-links">
          <?php
          $pilares = [
            'rufino-real'          => 'Rufino real',
            'el-campo-habla'       => 'El campo habla',
            'barrio-a-barrio'      => 'Barrio a barrio',
            'generacion-rufino'    => 'Generación Rufino',
            'seguimiento-promesas' => 'Seguimiento promesas',
            'contexto-datos'       => 'Rufino en datos',
          ];
          foreach ($pilares as $slug => $nombre) :
            $cat = get_category_by_slug($slug);
            $url = $cat ? get_category_link($cat->term_id) : '#';
          ?>
            <li><a href="<?php echo esc_url($url); ?>"><?php echo esc_html($nombre); ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div>
        <div class="er-footer-col-title">El medio</div>
        <ul class="er-footer-links">
          <li><a href="<?php echo esc_url(home_url('/quienes-somos')); ?>">Quiénes somos</a></li>
          <li><a href="<?php echo esc_url(home_url('/como-trabajamos')); ?>">Cómo trabajamos</a></li>
          <li><a href="<?php echo esc_url(home_url('/promesas-politicas')); ?>">Promesas políticas</a></li>
          <li><a href="<?php echo esc_url(home_url('/contacto')); ?>">Contacto</a></li>
          <li><a href="<?php echo esc_url(home_url('/publicidad')); ?>">Publicidad</a></li>
        </ul>
      </div>

      <div>
        <div class="er-footer-col-title">Seguinos</div>
        <ul class="er-footer-links">
          <li><a href="<?php echo esc_url($wa_url); ?>" target="_blank" rel="noopener">WhatsApp</a></li>
          <li><a href="https://instagram.com/elrufino" target="_blank" rel="noopener">Instagram</a></li>
          <li><a href="https://facebook.com/elrufino" target="_blank" rel="noopener">Facebook</a></li>
          <li><a href="https://tiktok.com/@elrufino" target="_blank" rel="noopener">TikTok</a></li>
        </ul>
      </div>

    </div>

    <div class="er-footer-bottom">
      <span>© <?php echo date('Y'); ?> El Rufino · Rufino, Santa Fe, Argentina</span>
      <span>elrufino.ar</span>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
