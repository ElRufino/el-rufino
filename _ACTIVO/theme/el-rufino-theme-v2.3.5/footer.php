<?php
/**
 * El Rufino — footer.php
 * Footer institucional: descripción + pilares + contacto + redes
 * v1.0 — Mayo 2026
 */
?>

</main><!-- #main -->

<footer class="er-footer" role="contentinfo">
  <div class="er-footer-inner">

    <!-- COL 1: Identidad del medio -->
    <div class="er-footer-col er-footer-identidad">
      <div class="er-footer-logo">
        <span class="er-footer-logo-r">R</span>
        <div>
          <span class="er-footer-nombre">El Rufino</span>
          <span class="er-footer-claim">Lo que pasa y lo que significa</span>
        </div>
      </div>
      <p class="er-footer-desc">
        Medio digital local de Rufino, Santa Fe, Argentina.
        Noticias verificadas, contexto y seguimiento desde 2026.
      </p>
      <p class="er-footer-desc">
        Toda nota tiene dos capas: lo que pasó y lo que significa.
      </p>
    </div>

    <!-- COL 2: Pilares editoriales -->
    <div class="er-footer-col">
      <h3 class="er-footer-heading">Pilares editoriales</h3>
      <ul class="er-footer-list">
        <li><a href="<?php echo esc_url(home_url('/categoria/rufino-real')); ?>">Rufino real</a></li>
        <li><a href="<?php echo esc_url(home_url('/categoria/el-campo-habla')); ?>">El campo habla</a></li>
        <li><a href="<?php echo esc_url(home_url('/categoria/barrio-a-barrio')); ?>">Barrio a barrio</a></li>
        <li><a href="<?php echo esc_url(home_url('/categoria/generacion-rufino')); ?>">Generación Rufino</a></li>
        <li><a href="<?php echo esc_url(home_url('/categoria/seguimiento-promesas')); ?>">Poder y gestión</a></li>
        <li><a href="<?php echo esc_url(home_url('/categoria/contexto-datos')); ?>">Rufino en datos</a></li>
      </ul>
    </div>

    <!-- COL 3: El medio -->
    <div class="er-footer-col">
      <h3 class="er-footer-heading">El medio</h3>
      <ul class="er-footer-list">
        <li><a href="<?php echo esc_url(home_url('/quienes-somos')); ?>">Quiénes somos</a></li>
        <li><a href="<?php echo esc_url(home_url('/como-trabajamos')); ?>">Cómo trabajamos</a></li>
        <li><a href="<?php echo esc_url(home_url('/promesas-politicas')); ?>">Promesas políticas</a></li>
        <li><a href="<?php echo esc_url(home_url('/contacto')); ?>">Contacto</a></li>
        <li><a href="<?php echo esc_url(home_url('/publicidad')); ?>">Publicidad</a></li>
      </ul>
    </div>

    <!-- COL 4: Redes + contacto -->
    <div class="er-footer-col">
      <h3 class="er-footer-heading">Seguinos</h3>
      <ul class="er-footer-redes">
        <li>
          <a href="https://wa.me/5493382511670" target="_blank" rel="noopener" class="er-red-wa">
            <span class="er-red-icono">WA</span> WhatsApp
          </a>
        </li>
        <li>
          <a href="https://instagram.com/elrufino" target="_blank" rel="noopener">
            <span class="er-red-icono">IG</span> Instagram
          </a>
        </li>
        <li>
          <a href="https://facebook.com/elrufino" target="_blank" rel="noopener">
            <span class="er-red-icono">FB</span> Facebook
          </a>
        </li>
        <li>
          <a href="https://tiktok.com/@elrufino" target="_blank" rel="noopener">
            <span class="er-red-icono">TK</span> TikTok
          </a>
        </li>
      </ul>

      <div class="er-footer-contacto">
        <h3 class="er-footer-heading" style="margin-top:1.2rem">Contacto</h3>
        <a href="mailto:medioelrufino@gmail.com" class="er-footer-email">
          medioelrufino@gmail.com
        </a>
      </div>
    </div>

  </div><!-- .er-footer-inner -->

  <!-- Barra inferior -->
  <div class="er-footer-bottom">
    <span>&copy; <?php echo date('Y'); ?> El Rufino &middot; Rufino, Santa Fe, Argentina &middot; <a href="<?php echo esc_url(home_url('/')); ?>">elrufino.com.ar</a></span>
    <span class="er-footer-bottom-right">
      <a href="<?php echo esc_url(home_url('/aviso-legal')); ?>">Aviso legal</a>
      &middot;
      <a href="<?php echo esc_url(home_url('/politica-de-privacidad')); ?>">Privacidad</a>
    </span>
  </div>

</footer>

<?php wp_footer(); ?>
</body>
</html>
