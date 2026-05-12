## [2026-05-03] — Child theme v2.1.4 · single.php · Facebook App

### Agregado
- `single.php` — template nota individual completo:
  - "Por [nombre]" con avatar circular (override Gravatar con favicon)
  - Fecha en español: "3 de mayo de 2026"
  - Tiempo de lectura automático (palabras / 200)
  - Controles A− / A+ para tamaño de fuente
  - Botón Escuchar / Pausar (Web Speech API, voz es-AR)
  - Compartir: Facebook, X, WhatsApp, Telegram
  - Meta boxes "Pie de foto" y "Fuente / Crédito" en editor WP
  - Facebook Comments (App ID: 1314916506819026)
  - "Notas relacionadas" (reemplaza "Related Post")
  - Tags con estilo visual

### Modificado
- `functions.php`:
  - Meta boxes pie de foto y fuente (`_er_pie_foto`, `_er_fuente_foto`)
  - Override avatar con favicon del child theme
  - Filtro fecha español portada (`the_time`, `get_the_time`)
  - Filtro `date_i18n` para nombres de mes
  - Traducción strings Newsup vía `gettext`
- `style.css`:
  - Ocultar sección "You missed" (`.missed-section`)
  - `text-align: left` forzado en `.entry-content`
  - `text-transform: none` en `.mg-blog-date` (fecha portada)
  - Estilos completos nota individual (`.er-single-*`, `.er-article-*`)
  - Estilos controles de lectura, share inline, tags, relacionadas
  - Estilos Facebook Comments section

### Facebook App
- App creada en developers.facebook.com
- Nombre: El Rufino
- App ID: 1314916506819026
- Dominio registrado: prueba.infoconectados.com
- Estado: En desarrollo (activar cuando salga a producción)

### Notas demo
- 10 notas con cuerpo completo generadas y cargadas en WordPress
- Archivo fuente: `02_WORDPRESS_TEST/el-rufino_notas-demo_completas.md`

### Plugin
- `el-rufino-panel.php`: modelo corregido de `claude-sonnet-4-20250514` → `claude-sonnet-4-6`
- API key configurada (sin créditos activos — pendiente carga)

### Pendiente próxima sesión
- `home.php` — layout portada (hero + grillas por pilar + sidebar)
- Formato fecha portada: "mayo 3" → "3 de mayo de 2026"
- Subir `favicon-512.png` a `assets/` del tema en servidor
- Activar Facebook App cuando pase a producción
