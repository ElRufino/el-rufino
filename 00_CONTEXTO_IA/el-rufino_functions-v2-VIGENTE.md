# El Rufino — `el-rufino_functions_v2.php` · Documentación funcional operativa

> **Archivo de referencia vigente** — Generado el 2026-05-20.
> Ruta: `_ACTIVO/plugin/el-rufino_functions_v2.php`
> Versión declarada: `2.0.0` · "Tema hijo de Newsup"

---

## Contexto de este archivo

`el-rufino_functions_v2.php` es la copia de desarrollo del `functions.php` del child theme, almacenada en `_ACTIVO/plugin/` junto al plugin principal. **No es un plugin de WordPress** — no tiene cabecera `Plugin Name:` y no se activa por sí solo. Es el archivo fuente que se copia manualmente a `_ACTIVO/theme/el-rufino-theme-v2.3.4/el-rufino-theme/functions.php` al desplegar el tema.

**Relación con el theme:**
- Este archivo = fuente de trabajo / versión archivada
- `_ACTIVO/theme/el-rufino-theme-v2.3.4/el-rufino-theme/functions.php` = versión activa en el tema desplegado

El contenido es equivalente. La versión del tema tiene un encabezado de comentario más largo ("Design System v1.1 · Abril 2026"); este archivo tiene el encabezado mínimo.

---

## Índice

1. [Enqueue de estilos](#1-enqueue-de-estilos)
2. [Theme setup](#2-theme-setup)
3. [Hardening del head](#3-hardening-del-head)
4. [Masthead y topbar](#4-masthead-y-topbar)
5. [Ticker de últimas noticias](#5-ticker-de-últimas-noticias)
6. [Shortcodes](#6-shortcodes)
7. [Helper: color de pilar](#7-helper-color-de-pilar)
8. [Recordatorio editorial en el editor](#8-recordatorio-editorial-en-el-editor)
9. [Security headers HTTP](#9-security-headers-http)
10. [Excerpt personalizado](#10-excerpt-personalizado)
11. [Meta boxes — imagen destacada](#11-meta-boxes--imagen-destacada)
12. [Filtros de fecha en español](#12-filtros-de-fecha-en-español)
13. [Filtro de traducción de Newsup](#13-filtro-de-traducción-de-newsup)
14. [Avatar personalizado](#14-avatar-personalizado)
15. [Supresión de errores PHP en frontend](#15-supresión-de-errores-php-en-frontend)

---

## 1. Enqueue de estilos

**Hook**: `wp_enqueue_scripts` → función `er_theme_enqueue`

Carga tres recursos en orden de dependencia:

### `parent-style`
CSS del tema padre Newsup. Lee la versión instalada con `wp_get_theme(get_template())->get('Version')` para cachebust correcto.
```php
get_template_directory_uri() . '/style.css'
```

### `child-style`
`style.css` del child theme, declarado dependiente de `parent-style`. Se carga después, por lo que sus reglas sobreescriben las del padre.
```php
get_stylesheet_uri()   // → wp-content/themes/el-rufino-theme/style.css
```

### `er-fonts`
Google Fonts — solo **Source Serif 4** (Playfair Display se carga desde archivos TTF locales del tema, no desde Google Fonts):
```
Source+Serif+4:ital,wght@0,300;0,400;0,600;1,300;1,400
```
Sin número de versión (`null`) para que WP no agregue `?ver=` en la URL.

---

## 2. Theme setup

**Hook**: `after_setup_theme` → función `er_theme_setup`

### Soporte de funcionalidades WordPress

| Soporte | Parámetros |
|---|---|
| `post-thumbnails` | — |
| `title-tag` | — |
| `html5` | `search-form, comment-form, comment-list, gallery, caption` |
| `custom-logo` | `height: 80, width: 300, flex-height: true, flex-width: true` |
| `customize-selective-refresh-widgets` | — |
| `editor-color-palette` | 7 colores del Design System (ver tabla abajo) |
| `disable-custom-gradients` | Desactiva gradientes personalizados en Gutenberg |

### Paleta Gutenberg

| Nombre | Slug | Color |
|---|---|---|
| Rojo El Rufino | `er-rojo` | `#c0271b` |
| Negro | `er-negro` | `#1a1a1a` |
| Crema | `er-crema` | `#f5f0e8` |
| P02 Campo | `er-p02` | `#4a7c59` |
| P03 Barrio | `er-p03` | `#2d5f8a` |
| P04 Generación | `er-p04` | `#7b4fa6` |
| P06 Datos | `er-p06` | `#c8600a` |

### Tamaños de imagen registrados

| Nombre | Ancho | Alto | Crop | Uso |
|---|---|---|---|---|
| `er-featured` | 780 | 440 | Sí | Cards principales de pilares, imagen héroe |
| `er-card` | 400 | 250 | Sí | Cards de grilla P04 (fotos) |
| `er-thumbnail` | 160 | 100 | Sí | Miniaturas en notas relacionadas |
| `er-wide` | 1200 | 500 | Sí | Imagen del bloque héroe (panorámico) |

### Menús registrados

| `theme_location` | Etiqueta visible |
|---|---|
| `primary` | Menú principal |
| `secondary` | Menú secundario |
| `footer` | Menú footer |

---

## 3. Hardening del head

**XML-RPC deshabilitado**:
```php
add_filter('xmlrpc_enabled', '__return_false');
```

**Tags eliminados del `<head>` de WordPress** via `remove_action`:

| Función removida | Qué elimina |
|---|---|
| `rsd_link` | Enlace Really Simple Discovery |
| `wlwmanifest_link` | Enlace Windows Live Writer |
| `wp_generator` | Tag `<meta name="generator" content="WordPress X.X.X">` (seguridad) |
| `wp_shortlink_wp_head` | Tag `<link rel="shortlink">` |
| `print_emoji_detection_script` (priority 7) | Script JS de emojis |
| `print_emoji_styles` (en `wp_print_styles`) | CSS de emojis |

---

## 4. Masthead y topbar

**Hook**: `wp_body_open` · priority `1` → función `er_render_masthead`

Se ejecuta como primera acción dentro de `<body>`, antes que cualquier otro hook en `wp_body_open`. Renderiza dos bloques HTML consecutivos:

### Topbar (`.er-topbar`)
```html
<div class="er-topbar">
    <span>[fecha en español]</span>
    <span class="er-topbar-loc">Rufino · Santa Fe · Argentina</span>
</div>
```

La fecha usa `date_i18n('l j \d\e F \d\e Y')` — formato: "miércoles 20 de mayo de 2026".

### Masthead (`.er-masthead`)
```html
<div class="er-masthead">
    <a href="[home_url]" class="er-masthead-logo">
        <div class="er-logo-r"><span>R</span></div>
        <div class="er-logo-texto">
            <span class="er-logo-nombre">El Rufino</span>
            <span class="er-logo-claim">Lo que pasa y lo que significa</span>
        </div>
    </a>
    <a href="[er_whatsapp_canal]" class="er-btn-wa" target="_blank">📲 WhatsApp</a>
</div>
```

- **Logo**: cuadrado rojo 64×64 con "R" en Playfair Display 900 blanco + texto "El Rufino" (36px, 700) + claim (9px, uppercase, rojo, letra-spacing .22em)
- **URL WhatsApp**: lee `wp_options.er_whatsapp_canal`, fallback `https://whatsapp.com/channel/elrufino`
- **Comportamiento visual**: los estilos `.er-topbar { display: flex !important }` y `.er-masthead { display: flex !important }` en `style.css` aseguran que sean visibles aunque Newsup intente ocultarlos

---

## 5. Ticker de últimas noticias

**Hook**: `wp_body_open` · priority `2` → función `er_render_ticker`

Se ejecuta inmediatamente después del masthead. Renderiza:

```html
<div class="er-nav-divider"></div>   <!-- barra de 2px rojo -->
<div class="er-ticker">
    <div class="er-ticker-label">Último momento</div>
    <div class="er-ticker-track">
        <div class="er-ticker-inner">[items][items]</div>   <!-- duplicados para loop infinito -->
    </div>
</div>
```

**Query**: `WP_Query` con `posts_per_page: 6`, `post_status: publish`, `no_found_rows: true`.

**Duplicado**: Los items se concatenan dos veces (`$items . $items`) en el HTML. La animación CSS `er-ticker-scroll` desplaza el contenedor a `translateX(-50%)` en 35s, creando un loop visual sin corte.

**Cada item**:
```html
<span class="er-ticker-item">
    <span class="er-ticker-dot"></span>
    [título del post]
</span>
```

---

## 6. Shortcodes

### `[er_wa_subscribe]`

Renderiza un banner negro de suscripción a WhatsApp dentro del contenido de una nota o página.

**Parámetros** (todos opcionales):

| Atributo | Default | Descripción |
|---|---|---|
| `texto` | `"Recibí las noticias de Rufino directo en tu celular."` | Texto principal del banner |
| `link` | `get_option('er_whatsapp_canal', '#')` | URL destino del botón |
| `btn` | `"Unirme al canal"` | Texto del botón |

**Output**:
```html
<div class="er-wa-banner">
    <div class="er-wa-texto">[texto]</div>
    <a href="[link]" class="er-btn-wa" target="_blank" rel="noopener">📲 [btn]</a>
</div>
```

**Uso típico en nota**:
```
[er_wa_subscribe texto="Seguí el caso desde tu celular" btn="Unirme"]
```

---

### `[er_seguimiento]`

Badge de estado de una promesa política. Se inserta en línea dentro del contenido de la nota.

**Parámetro**: `estado` (string, obligatorio en la práctica).

**Mapeo de estado → clase CSS → color**:

| `estado=` | Clase CSS | Apariencia |
|---|---|---|
| `cumplida` | `es-cumplida` | Verde (`#2a7a2a` sobre `#e8f4e8`, borde `#a8d4a8`) |
| `incumplida` | `es-incumplida` | Rojo (`#c0271b` sobre `#fdf0ef`, borde `#e8908b`) |
| `en proceso` | `es-en-curso` | Ámbar (`#c07800` sobre `#fef3e2`, borde `#f5c842`) |
| `en seguimiento` | `es-seguimiento` | Gris neutro |
| `pendiente` (default) | `es-pendiente` | Gris neutro |

**Output**:
```html
<span class="er-p05-estado es-cumplida">cumplida</span>
```

**Uso en una nota sobre promesas**:
```
El intendente prometió pavimentar tres calles del barrio sur [er_seguimiento estado="incumplida"].
```

---

## 7. Helper: color de pilar

**Función**: `er_get_pilar_color($post_id = null)`

Devuelve el color hexadecimal del pilar editorial según la primera categoría del post. Usada en `single.php` para colorear el badge de categoría.

**Mapeo slug → hex**:

| Slug | Color |
|---|---|
| `rufino-real` | `#c0271b` |
| `el-campo-habla` | `#4a7c59` |
| `barrio-a-barrio` | `#2d5f8a` |
| `generacion-rufino` | `#7b4fa6` |
| `poder-y-gestion` | `#1a1a1a` |
| `rufino-en-datos` | `#c8600a` |

**Fallback**: `#c0271b` si el post no tiene categorías o el slug no está en el mapa.

---

## 8. Recordatorio editorial en el editor

**Hook**: `admin_footer-post.php` y `admin_footer-post-new.php` → `er_editor_reminder`

Inyecta una barra fija en la parte inferior de la pantalla de edición de posts:

```
REGLA DE 2 CAPAS: ¿La nota tiene (1) lo que pasó + (2) lo que significa? Sin segunda capa, no se publica.
```

**Estilos**: `position: fixed; bottom: 0; left: 160px; right: 0; background: #c0271b; color: #fff; padding: 8px 20px; font-size: 12px; z-index: 9999; text-align: center`

Aparece en ambas pantallas (editar post existente y nuevo post). No aparece en otros post types ni en páginas.

---

## 9. Security headers HTTP

**Hook**: `send_headers` → `er_security_headers`

Agrega tres headers de seguridad en cada respuesta HTTP del sitio:

| Header | Valor | Protección |
|---|---|---|
| `X-Content-Type-Options` | `nosniff` | Evita MIME sniffing — el navegador no adivina el tipo de archivo |
| `X-Frame-Options` | `SAMEORIGIN` | Evita que el sitio sea embebido en iframes de otros dominios (clickjacking) |
| `Referrer-Policy` | `strict-origin-when-cross-origin` | Envía el origen completo en requests del mismo sitio, solo el origen (sin path) en cross-origin |

---

## 10. Excerpt personalizado

Dos filtros de WordPress:

```php
add_filter('excerpt_length', function() { return 30; });
add_filter('excerpt_more',   function() { return '...'; });
```

- **Longitud**: 30 palabras (WP default: 55)
- **Sufijo**: `...` (tres puntos ASCII, no el carácter Unicode `…`)

---

## 11. Meta boxes — imagen destacada

### Meta box en el editor

**Función**: `er_add_foto_metabox` / `er_foto_metabox_html`  
**Hook registro**: `add_meta_boxes`  
**Título**: "Imagen destacada — datos"  
**Post type**: `post` | **Contexto**: `normal` | **Prioridad**: `high`

Dos campos de texto:

| Campo | Meta key | Placeholder | Uso en frontend |
|---|---|---|---|
| Pie de foto | `_er_pie_foto` | "Descripción de la imagen" | `<figcaption>` en single.php |
| Fuente / Crédito | `_er_fuente_foto` | "Ej: Municipalidad de Rufino / El Rufino" | `<span class="er-fuente">` junto al pie de foto |

### Guardado

**Función**: `er_save_foto_meta`  
**Hook**: `save_post`

Validaciones antes de guardar:
1. Verifica nonce `er_foto_nonce` con `wp_verify_nonce()`
2. Aborta si `DOING_AUTOSAVE`
3. Sanitiza con `sanitize_text_field()` antes de `update_post_meta()`

---

## 12. Filtros de fecha en español

El problema: Newsup renderiza fechas en inglés. Se corrige con cuatro filtros encadenados.

### `er_fecha_espanol` — filtros `get_the_date` y `the_date`

Reemplaza nombres de meses en inglés por español, en minúsculas y mayúsculas:
```
January → enero | JANUARY → ENERO
February → febrero | ... etc.
```

### `er_fix_fecha_portada` — filtro `date_i18n`

Mismo reemplazo pero sobre el resultado de `date_i18n()` que Newsup usa internamente. También cubre abreviaturas (Jan → ene, Feb → feb, etc.).

### `er_reformat_fecha_portada` — filtros `the_time` y `get_the_time`

Reescribe el formato anglosajón al formato argentino:
```
"May 3, 2026" → "3 de mayo de 2026"
"March 15, 2026" → "15 de marzo de 2026"
```

Detecta el patrón con regex: `/^([A-Za-z]+)\s+(\d{1,2}),\s+(\d{4})$/`

---

## 13. Filtro de traducción de Newsup

**Filtro**: `gettext` · priority `20` · función `er_traducir_newsup`

Intercepta 12 strings en inglés hardcodeados en el tema Newsup y los reemplaza:

| String original | Traducción |
|---|---|
| `You missed` | `También te puede interesar` |
| `Read More` | `Leer más` |
| `Leave a Reply` | `Dejá tu comentario` |
| `Search` | `Buscar` |
| `Recent Posts` | `Últimas notas` |
| `Categories` | `Categorías` |
| `Tags` | `Etiquetas` |
| `Comments` | `Comentarios` |
| `Posted in` | `En` |
| `by` | `por` |
| `Posted on` | *(vacío — oculta la etiqueta)* |
| `min read` | `min de lectura` |

El filtro aplica a **cualquier dominio** de traducción (tercer parámetro `$domain` no se verifica), lo que lo hace robusto ante cambios de versión de Newsup.

---

## 14. Avatar personalizado

**Filtro**: `get_avatar` → `er_custom_avatar`

Reemplaza el avatar de Gravatar (que hace una petición externa a gravatar.com) por una imagen local:

**Orden de prioridad**:
1. `/wp-content/uploads/er-avatar.png` — imagen subida manualmente al sitio
2. Fallback: `/wp-content/themes/el-rufino-theme/assets/favicon-512.png`

**Output**: `<img>` con clases `avatar avatar-{size} er-autor-avatar`, atributo `loading="lazy"`. Elimina la redirección a gravatar.com y el hash MD5 del email del autor que WP envía en el parámetro `d=`.

---

## 15. Supresión de errores PHP en frontend

```php
if (!WP_DEBUG) {
    error_reporting(E_ERROR | E_PARSE);
    @ini_set('display_errors', 0);
}
```

Solo activa cuando `WP_DEBUG` está desactivado (producción). En desarrollo, los errores se muestran normalmente. En producción:
- Solo reporta errores fatales y de parseo (no warnings, notices, deprecations)
- Desactiva la visualización de errores en pantalla

---

## Relación con otros archivos del proyecto

| Archivo | Relación |
|---|---|
| `_ACTIVO/theme/el-rufino-theme-v2.3.4/el-rufino-theme/functions.php` | Versión activa en el tema desplegado. Mismo contenido con encabezado más descriptivo. |
| `_ACTIVO/plugin/el-rufino-panel.php` | Plugin principal — completamente independiente. Comparte el mismo prefijo `er_` por convención. |
| `_ACTIVO/theme/el-rufino-theme-v2.3.4/el-rufino-theme/style.css` | Las clases CSS que este archivo genera (`.er-topbar`, `.er-masthead`, `.er-ticker`, `.er-wa-banner`, `.er-p05-estado`, etc.) están definidas en ese CSS. |
| `_ACTIVO/theme/el-rufino-theme-v2.3.4/el-rufino-theme/single.php` | Consume `er_get_pilar_color()` para el badge de categoría y los post meta `_er_pie_foto` / `_er_fuente_foto`. |
| `_ACTIVO/theme/el-rufino-theme-v2.3.4/el-rufino-theme/home.php` | Consume `_er_estado_promesa`, `_er_dato_num`, `_er_dato_label` (esos meta no tienen meta box en este archivo). |

---

*Documentación generada desde el código fuente vigente. Actualizar si se modifica `_ACTIVO/plugin/el-rufino_functions_v2.php` o su copia activa en el tema.*
