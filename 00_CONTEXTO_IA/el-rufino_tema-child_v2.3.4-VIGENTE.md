# El Rufino — Child Theme v2.3.4 · Documentación funcional operativa

> **Archivo de referencia vigente** — Generado el 2026-05-20 a partir del código fuente en `_ACTIVO/theme/el-rufino-theme-v2.3.4/el-rufino-theme/`.
> Tema hijo de: **Newsup** (Template: newsup en style.css)

---

## Índice

1. [Arquitectura general](#1-arquitectura-general)
2. [Design System — tokens CSS](#2-design-system--tokens-css)
3. [functions.php — funcionalidades PHP](#3-functionsphp--funcionalidades-php)
4. [header.php — estructura del head](#4-headerphp--estructura-del-head)
5. [home.php — portada](#5-homephp--portada)
6. [single.php — nota individual](#6-singlephp--nota-individual)
7. [footer.php — pie de página](#7-footerphp--pie-de-página)
8. [style.css — capas y patches](#8-stylecss--capas-y-patches)
9. [Pilares editoriales en el tema](#9-pilares-editoriales-en-el-tema)
10. [Post meta fields personalizados](#10-post-meta-fields-personalizados)
11. [Shortcodes disponibles](#11-shortcodes-disponibles)
12. [Seguridad y performance](#12-seguridad-y-performance)
13. [Historial de patches en style.css](#13-historial-de-patches-en-stylecss)

---

## 1. Arquitectura general

### Stack

| Capa | Tecnología |
|---|---|
| Tema padre | **Newsup** (tema de noticias de WordPress) |
| Tema hijo | `el-rufino-theme` v2.3.4 |
| Tipografías | Playfair Display (local, archivos TTF) + Source Serif 4 (Google Fonts) |
| JavaScript | Vanilla JS inline en single.php (TTS + control de fuente) |
| Facebook SDK | Cargado en single.php para comentarios |

### Filosofía de diseño

- **Patch-over-Newsup**: El child theme no reemplaza el tema padre limpiamente; lo parchea. El `style.css` contiene múltiples bloques `/* PATCH */` que ocultan o corrigen elementos del padre con `display: none !important` y overrides de `!important`.
- **Render a través de `wp_body_open`**: La topbar, masthead y ticker se inyectan vía hooks de `wp_body_open` en lugar de ir en `header.php`, lo que permite que el padre no interfiera con el layout de El Rufino.
- **Design System propio**: Variables CSS definidas en `:root` con prefijo `--er-*`. No usa las variables del tema padre.
- **Home custom**: Se usa `home.php` (no `index.php` del padre) para la portada, lo que da control total sobre el layout sin depender del loop de Newsup.

### Archivos y responsabilidades

| Archivo | Responsabilidad |
|---|---|
| `style.css` | Cabecera del tema, Design System, todos los estilos globales y patches |
| `functions.php` | Enqueue, tema setup, hooks, shortcodes, meta boxes, filtros de traducción |
| `header.php` | `<html>`, `<head>`, `<body>`, dispara `wp_body_open()` → topbar + masthead + ticker + nav |
| `home.php` | Template exclusivo de la portada. Layout héroe + grillas por pilar + sidebar |
| `single.php` | Template de nota individual. Autor, meta, controles de lectura, imagen, cuerpo, tags, relacionadas, FB Comments |
| `footer.php` | Footer con grilla de columnas: marca, pilares, el medio, redes sociales + copyright |

---

## 2. Design System — tokens CSS

Definidos en `:root` en `style.css`. Todos los componentes los consumen via `var(--er-*)`.

### Colores de marca

| Token | Valor | Uso |
|---|---|---|
| `--er-rojo-500` / `--er-brand-primary` | `#c0271b` | Color principal: botones, bordes top de cards, badges, hover |
| `--er-rojo-700` / `--er-brand-hover` | `#aa211a` | Hover state de elementos rojos |
| `--er-rojo-50` / `--er-rojo-200` | `#fdf0ef` / `#e8908b` | Fondos y bordes de estado "incumplida" |
| `--er-negro` / `--er-bg-footer` / `--er-bg-nav` | `#1a1a1a` | Sidebar nav, ticker, footer, texto principal |
| `--er-crema-200` / `--er-bg-base` | `#f5f0e8` | Fondo general del sitio |
| `--er-gris` / `--er-text-secondary` | `#6b6b6b` | Texto secundario, fechas, meta |

### Colores de pilares (P01–P06)

En el tema el mapeo de pilares usa colores **diferentes** a los del plugin:

| Token | Valor | Pilar |
|---|---|---|
| `--er-p01` | `#c0271b` | Rufino real |
| `--er-p02` | `#4a7c59` | El campo habla |
| `--er-p03` | `#2d5f8a` | Barrio a barrio |
| `--er-p04` | `#7b4fa6` | Generación Rufino |
| `--er-p05` | `#1a1a1a` | Poder y gestión / Seguimiento promesas |
| `--er-p06` | `#c8600a` | Rufino en datos / Contexto datos |

> **Nota de inconsistencia**: El plugin v8.6.0 usa colores distintos para los mismos pilares (ej: Barrio a barrio es `#b55233` en el plugin y `#2d5f8a` en el tema). Esto se debe a que el tema maneja 6 pilares (P01-P06) mientras el plugin maneja 5 con distintos slugs. Ver [sección 9](#9-pilares-editoriales-en-el-tema).

### Tipografías

| Token | Familia | Uso |
|---|---|---|
| `--er-font-titulo` | `'Playfair Display', Georgia, serif` | H1-H6, títulos de cards, logo |
| `--er-font-cuerpo` | `'Source Serif 4', Georgia, serif` | Cuerpo de nota, excerpts |
| `--er-font-ui` | `'Source Serif 4', Georgia, serif` | Labels, badges, nav, meta, botones |

Playfair Display se carga desde archivos TTF locales (en `el-rufino-v8.1-COMPLETO`). Source Serif 4 se carga desde Google Fonts en `functions.php`.

### Espaciado y radios

Escala de 4px: `--er-space-1` (4px) → `--er-space-12` (48px).  
Radios: `none` (0), `sm` (2px), `md` (4px). El diseño es predominantemente cuadrado.

### Sombras

- `--er-shadow-sm`: `0 1px 4px rgba(26,26,26,.06)` — sutil, cards en reposo
- `--er-shadow-md`: `0 4px 16px rgba(26,26,26,.10)` — hover de cards

---

## 3. functions.php — funcionalidades PHP

### Enqueue de estilos (`er_theme_enqueue`)

Carga en orden:
1. `parent-style` → CSS del tema Newsup
2. `child-style` → `style.css` del tema hijo (depende de parent-style)
3. `er-fonts` → Google Fonts: `Source+Serif+4:ital,wght@0,300;0,400;0,600;1,300;1,400`

### Theme setup (`er_theme_setup`)

Habilita:
- `post-thumbnails`
- `title-tag`
- `html5` (formularios, comentarios, galería, caption)
- `custom-logo` (height: 80, width: 300)
- `customize-selective-refresh-widgets`
- **Editor Gutenberg — paleta de colores** con los 7 colores del Design System
- **`disable-custom-gradients`** — desactiva gradientes en Gutenberg

Tamaños de imagen registrados:
| Nombre | Dimensiones | Crop | Uso |
|---|---|---|---|
| `er-featured` | 780 × 440 | Sí | Cards principales, imagen héroe |
| `er-card` | 400 × 250 | Sí | Cards grilla, P04 fotos |
| `er-thumbnail` | 160 × 100 | Sí | Notas relacionadas |
| `er-wide` | 1200 × 500 | Sí | Imagen héroe (formato panorámico) |

Menús registrados: `primary` (Menú principal), `secondary` (Menú secundario), `footer` (Menú footer).

### Masthead — `er_render_masthead` (hook: `wp_body_open`, priority 1)

Renderiza **dos bloques HTML** directamente en `<body>` con priority 1 (antes que cualquier otra cosa):

**Topbar** (`.er-topbar`):
- Fecha del día en español: `date_i18n('l j \d\e F \d\e Y')` (ej: "miércoles 20 de mayo de 2026")
- Texto estático: "Rufino · Santa Fe · Argentina"

**Masthead** (`.er-masthead`):
- Logo: cuadrado rojo con letra "R" (`er-logo-r`) + texto "El Rufino" + claim "Lo que pasa y lo que significa"
- Enlace al home (`home_url('/')`)
- Botón "📲 WhatsApp" → URL guardada en `wp_options.er_whatsapp_canal` (fallback: `https://whatsapp.com/channel/elrufino`)

### Ticker — `er_render_ticker` (hook: `wp_body_open`, priority 2)

Renderiza inmediatamente después del masthead:
- Separador rojo (`.er-nav-divider`, 2px, `#c0271b`)
- Ticker negro (`.er-ticker`) con label rojo "Último momento"
- Consulta los últimos 6 posts publicados (`WP_Query` con `no_found_rows: true` para performance)
- Los items se duplican en el HTML (`$items . $items`) para crear el efecto de scroll infinito
- La animación CSS `er-ticker-scroll` hace `translateX(0)` → `translateX(-50%)` en 35s linear infinite

### Shortcode `[er_wa_subscribe]`

Renderiza un banner negro de suscripción a WhatsApp.

**Atributos** (todos opcionales con defaults):
- `texto` — "Recibí las noticias de Rufino directo en tu celular."
- `link` — URL del canal (lee `er_whatsapp_canal` de options, fallback `#`)
- `btn` — "Unirme al canal"

**Output HTML**: `.er-wa-banner` con `.er-wa-texto` + enlace `.er-btn-wa`.

### Shortcode `[er_seguimiento]`

Renderiza un badge de estado de promesa en línea dentro del contenido de una nota.

**Atributo**: `estado` (string). Valores aceptados:
| Valor | Clase CSS | Color |
|---|---|---|
| `cumplida` | `es-cumplida` | Verde |
| `incumplida` | `es-incumplida` | Rojo |
| `en proceso` | `es-en-curso` | Ámbar |
| `en seguimiento` | `es-seguimiento` | Gris |
| `pendiente` | `es-pendiente` | Gris (default) |

**Uso**: `[er_seguimiento estado="cumplida"]` → `<span class="er-p05-estado es-cumplida">cumplida</span>`

### Función `er_get_pilar_color($post_id)`

Helper global que devuelve el color hexadecimal del pilar según la primera categoría del post. Usada en `single.php` para colorear el badge de categoría.

Mapeo:
```php
'rufino-real'       => '#c0271b'
'el-campo-habla'    => '#4a7c59'
'barrio-a-barrio'   => '#2d5f8a'
'generacion-rufino' => '#7b4fa6'
'poder-y-gestion'   => '#1a1a1a'
'rufino-en-datos'   => '#c8600a'
```
Fallback: `#c0271b`

### Recordatorio editorial en editor — `er_editor_reminder`

Hook: `admin_footer-post.php` y `admin_footer-post-new.php`.

Inyecta una barra roja fija (`position: fixed; bottom: 0`) en el editor de WordPress que dice:
> **REGLA DE 2 CAPAS:** ¿La nota tiene (1) lo que pasó + (2) lo que significa? Sin segunda capa, no se publica.

### Headers de seguridad — `er_security_headers`

Hook: `send_headers`. Agrega tres headers HTTP en cada respuesta:
- `X-Content-Type-Options: nosniff`
- `X-Frame-Options: SAMEORIGIN`
- `Referrer-Policy: strict-origin-when-cross-origin`

### Filtros de localización y traducción

**`er_fecha_espanol`** (filtros `get_the_date`, `the_date`):  
Reemplaza nombres de meses en inglés por español (en minúsculas y mayúsculas).

**`er_fix_fecha_portada`** (filtro `date_i18n`):  
Corrige el formato de fecha de Newsup cuando usa `date_i18n()` internamente.

**`er_reformat_fecha_portada`** (filtros `the_time`, `get_the_time`):  
Reescribe el formato "Month D, YYYY" → "D de mes de YYYY". Ej: "May 3, 2026" → "3 de mayo de 2026".

**`er_traducir_newsup`** (filtro `gettext`):  
Traduce strings en inglés hardcodeados en Newsup:

| Original | Traducción |
|---|---|
| You missed | También te puede interesar |
| Read More | Leer más |
| Leave a Reply | Dejá tu comentario |
| Search | Buscar |
| Recent Posts | Últimas notas |
| Categories | Categorías |
| Tags | Etiquetas |
| Comments | Comentarios |
| Posted in | En |
| by | por |
| Posted on | (vacío) |
| min read | min de lectura |

### Avatar personalizado — `er_custom_avatar`

Filtro `get_avatar`. Reemplaza el avatar de Gravatar por:
1. `/wp-content/uploads/er-avatar.png` (imagen subida manualmente)
2. Fallback: `/wp-content/themes/el-rufino-theme/assets/favicon-512.png`

Siempre retorna una imagen local sin petición externa a gravatar.com.

### Excerpt length

- Longitud: 30 palabras
- Sufijo: `...` (tres puntos simples, no ellipsis)

---

## 4. header.php — estructura del head

Mínimo y limpio. Solo aporta la estructura HTML:

```html
<!DOCTYPE html>
<html [lang]>
<head>
    <meta charset="...">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>   <!-- CSS, meta tags, etc. -->
</head>
<body [class]>
<?php wp_body_open(); ?>
<!-- priority 1 → er_render_masthead() — topbar + masthead -->
<!-- priority 2 → er_render_ticker()  — separador rojo + ticker negro -->

<nav class="er-nav" style="display:flex;flex-direction:row;flex-wrap:wrap;">
    <?php wp_nav_menu(['theme_location' => 'primary', 'container' => false, 'depth' => 1]); ?>
</nav>
```

La nav muestra el menú registrado como `primary`. Sin contenedor (`container: false`), profundidad 1 (no dropdown).

### Tags eliminados del `<head>`

Por `remove_action` en functions.php, no aparecen:
- `rsd_link` (Really Simple Discovery)
- `wlwmanifest_link` (Windows Live Writer)
- `wp_generator` (versión de WordPress — seguridad)
- `wp_shortlink_wp_head`
- Scripts y estilos de emojis de WordPress

---

## 5. home.php — portada

Template personalizado para la portada. Reemplaza el index.php del padre (WordPress lo elige automáticamente cuando existe `home.php`).

### Helpers locales definidos en home.php

| Función | Qué hace |
|---|---|
| `er_get_posts($slug, $n)` | `get_posts()` por `category_name`, N posts, solo publicados, sin found_rows |
| `er_fecha($post_id)` | Devuelve fecha en español: "20 mayo" (día + mes minúscula) |
| `er_lectura($post_id)` | Calcula tiempo de lectura: `str_word_count / 200` redondeado, mínimo 1 min |
| `er_color($slug)` | Devuelve color hex del pilar por slug (mapeo P01-P06 + poder-y-gestion) |
| `er_card_featured($post, $slug, $color)` | Renderiza card grande con imagen 16:9, overline, título, bajada, meta |
| `er_card_compact($post, $color, $border_style)` | Renderiza card compacta (sin imagen) con overline, título, meta |
| `er_pilar_header($nombre, $color, $cat_slug)` | Encabezado de sección de pilar con "Ver todo →" a la categoría |

### Estructura de la portada

```
[TOPBAR + MASTHEAD + TICKER + NAV]   ← via header.php
er-container (max-width: 1200px)
  ├── HÉROE (post sticky o más reciente)
  └── er-page-layout (grid: main 1fr + sidebar 300px)
        ├── MAIN
        │     ├── P01 — Rufino real         (featured + 2 compactas)
        │     ├── [separador]
        │     ├── P02 — El campo habla      (featured + 2 compactas)
        │     ├── [separador]
        │     ├── BANNER WHATSAPP
        │     ├── P03 — Barrio a barrio     (grid 3 compactas)
        │     ├── [separador]
        │     ├── P04 — Generación Rufino   (grid 3 cards con foto 4:3)
        │     ├── [separador]
        │     ├── P05 — Seguimiento promesas (lista con estado)
        │     ├── [separador]
        │     └── P06 — Rufino en datos     (grid 2 cards con número)
        └── SIDEBAR
              ├── Última hora (5 posts recientes, con hora H:i)
              ├── Lo más leído (5 posts por comment_count, proxy de popularidad)
              ├── Pilares editoriales (6 links con dot de color + código P0X)
              └── WhatsApp (banner negro con botón rojo)
[FOOTER]   ← via footer.php
```

### HÉROE

- Fuente: `get_posts(['numberposts' => 1, 'ignore_sticky_posts' => 0])` — respeta sticky posts
- Layout grid: `3fr` imagen / `2fr` cuerpo
- Imagen: `er-wide` (1200×500, panorámico)
- Muestra: badge de pilar con color, H1 con título, bajada en cursiva (Playfair), fecha, tiempo de lectura, botón "Leer nota completa →"

### P01 — Rufino real (3 posts)

Grid `2fr 1fr`: card featured grande a la izquierda + stack de 2 compactas a la derecha.  
Imagen: `er-featured` (780×440).

### P02 — El campo habla (3 posts)

Igual que P01, mismo layout.

### Banner WhatsApp

Bloque negro entre P02 y P03. Texto + botón "📲 UNIRME AL CANAL". URL desde `er_whatsapp_canal`.

### P03 — Barrio a barrio (3 posts)

Grid de 3 columnas iguales con cards compactas (sin imagen). `er-grid-3`.

### P04 — Generación Rufino (3 posts)

Grid de 3 columnas iguales. Cards con foto en ratio 4:3 (`er-card` 400×250) + cuerpo compacto. `er-grid-fotos`.

### P05 — Seguimiento de promesas (hasta 5 posts)

Lista vertical (`.er-p05-lista`). Por cada item:
- Fecha (formato "D mes")
- Título enlazado
- Badge de estado (`.er-p05-estado`) leído del post meta `_er_estado_promesa`

Fallback: si no hay posts de `seguimiento-promesas`, usa `poder-y-gestion`.

### P06 — Rufino en datos (2 posts)

Grid 2 columnas. Cards especiales con:
- Label (post meta `_er_dato_label`)
- Número grande (post meta `_er_dato_num`)
- Título enlazado
- Excerpt

Fallback: si no hay posts de `contexto-datos`, usa `rufino-en-datos`.

---

## 6. single.php — nota individual

Template para cualquier post individual. Layout de columna central (`max-width: 740px` centrado dentro de `max-width: 1200px`).

### Estructura de la nota

```
[HEADER — topbar + masthead + ticker + nav]
er-single-wrap (max-width: 1200px)
  er-single-main (max-width: 740px, centrado)
    ├── CATEGORÍA — badge con color del pilar
    ├── TÍTULO — H1 con Playfair Display, clamp(24px, 4vw, 38px)
    ├── META — autor (avatar + nombre) · fecha EN ESPAÑOL · tiempo de lectura
    ├── CONTROLES DE LECTURA — A− · A+ · Escuchar · compartir
    ├── IMAGEN DESTACADA — er-featured (780×440) + pie de foto + fuente
    ├── CUERPO — div#er-article-body con the_content()
    ├── TAGS — lista de etiquetas tipo hashtag (#tag)
    ├── NOTAS RELACIONADAS — 3 posts random del mismo pilar
    └── COMENTARIOS FACEBOOK — Facebook Comments Plugin
[FOOTER]
```

### Meta de la nota

- **Autor**: Avatar (28px) + "Por [nombre]" con enlace al archivo del autor
- **Fecha**: Generada manualmente con `get_the_date('U')` → `j de mes de YYYY` en español (no usa `get_the_date()` para evitar la traducción automática de Newsup)
- **Tiempo de lectura**: `str_word_count(strip_tags(get_the_content())) / 200`, mínimo 1 min

### Controles de lectura

**Botones de tamaño de fuente** (A− / A+):
- Función JS `erFontSize(dir)` que ajusta `erFontBase` (14px–22px, base 17px)
- Aplica `fontSize` al div `#er-article-body` directamente

**Botón "Escuchar" (Text-to-Speech)**:
- Función JS `erToggleTTS()` que usa la API Web `SpeechSynthesis`
- Lee el `innerText` de `#er-article-body`
- `lang: 'es-AR'`, `rate: 0.95`
- Toggle: al activar cambia a ícono de pausa y clase `er-tts-playing` (fondo rojo)
- Si el navegador no soporta TTS, cambia el texto del botón a "No disponible"

**Botones de compartir** (en línea, 30×30px):
- Facebook (`er-share-fb`, azul `#1877f2`) → `facebook.com/sharer/sharer.php?u=URL`
- X/Twitter (`er-share-x`, negro) → `twitter.com/intent/tweet?url=URL&text=TITULO`
- WhatsApp (`er-share-wa`, verde `#25d366`) → `wa.me/?text=TITULO%20URL`
- Telegram (`er-share-tg`, azul `#229ed9`) → `t.me/share/url?url=URL&text=TITULO`

### Imagen destacada

Usa el tamaño `er-featured` (780×440). Si tiene pie de foto o fuente guardados en post meta:
- `_er_pie_foto` → descripción de la imagen
- `_er_fuente_foto` → "Fuente: [crédito]"

Se muestran en `<figcaption class="er-figcaption">`.

### Notas relacionadas

Query: `WP_Query` por `category__in` (primera categoría del post), excluye el post actual, `posts_per_page: 3`, `orderby: rand`.  
Layout: grid de 3 columnas con imagen `er-thumbnail` (160×100) + título.

### Comentarios Facebook

Usa el Facebook Comments Plugin (`fb-comments`). Parámetros:
- `data-href`: URL del post actual
- `data-width`: 100%
- `data-numposts`: 10
- `data-colorscheme`: light
- `data-order-by`: social

Facebook SDK cargado con `appId: 1314916506819026`.

---

## 7. footer.php — pie de página

Footer de 4 columnas sobre fondo negro (`#1a1a1a`), con borde superior de 3px rojo.

### Estructura grid (2fr 1fr 1fr 1fr)

**Columna 1 — Marca**:
- Logo cuadrado rojo (36×36) con "R" + nombre "El Rufino"
- Claim en cursiva: "Lo que pasa y lo que significa."
- Descripción: "Medio digital local de Rufino, Santa Fe, Argentina. Noticias verificadas, contexto y seguimiento desde 2026."

**Columna 2 — Pilares** (lista dinámica):
Links a categorías por slug. Los 6 pilares del footer:
- rufino-real → "Rufino real"
- el-campo-habla → "El campo habla"
- barrio-a-barrio → "Barrio a barrio"
- generacion-rufino → "Generación Rufino"
- seguimiento-promesas → "Seguimiento promesas"
- contexto-datos → "Rufino en datos"

Usa `get_category_by_slug()` + `get_category_link()`. Si la categoría no existe, el href es `#`.

**Columna 3 — El medio** (links estáticos):
- Quiénes somos
- Cómo trabajamos
- Promesas políticas
- Contacto
- Publicidad

Todos apuntan a `home_url('/[slug]')`.

**Columna 4 — Seguinos** (redes sociales):
- WhatsApp (URL de `er_whatsapp_canal`)
- Instagram → `https://instagram.com/elrufino`
- Facebook → `https://facebook.com/elrufino`
- TikTok → `https://tiktok.com/@elrufino`

**Footer bottom** (borde superior fino):
- Izquierda: `© [año actual] El Rufino · Rufino, Santa Fe, Argentina`
- Derecha: `elrufino.com.ar`

### Ocultar footer de Newsup

El footer nativo de Newsup (`.site-footer`, `footer.site-footer`, `#colophon`) se oculta con `display: none !important` en el patch v2.3.1 de style.css para que no aparezca debajo del footer personalizado.

---

## 8. style.css — capas y patches

El CSS está organizado en bloques comentados. Algunos son el Design System base; otros son patches que corrigen el tema padre.

### Bloques principales

| Bloque | Contenido |
|---|---|
| TOKENS | Variables `:root` (Design System v1.1) |
| RESET | `box-sizing: border-box`, `body` con font/color/bg |
| PATCH — Ocultar Newsup | Oculta `.mg-nav-widget-area-back`, `.site-branding`, `.navbar-header`, `.site-logo` con `!important` |
| TOPBAR | `.er-topbar` — flex, padding, font, color |
| MASTHEAD | `.er-masthead`, `.er-logo-r`, `.er-logo-nombre`, `.er-logo-claim` |
| NAVEGACIÓN | `.er-nav` overrides + `.main-navigation ul li a` overrides con `!important` |
| TICKER | `.er-ticker`, animación `er-ticker-scroll` (35s linear infinite) |
| TIPOGRAFÍA | `h1-h6`, `.entry-title`, `.entry-content` |
| CARDS | `.post, article.post` — border-top 3px rojo, hover shadow |
| LABELS DE CATEGORÍA | `.cat-links a` — badge rojo con text uppercase |
| BADGES DE ESTADO | `.er-p05-estado` + variantes por estado |
| BLOCKQUOTE | Borde izquierdo rojo, fondo crema |
| WHATSAPP | `.er-wa-banner`, `.er-btn-wa` |
| SIDEBAR | `.widget-title` — border-bottom rojo, uppercase |
| FOOTER | `.site-footer` overrides + `.er-footer-*` |
| RESPONSIVE | Media queries @900px y @600px |
| SINGLE | Todos los estilos de `single.php` — categoría badge, título, meta, controles de lectura, share, figura, body, tags, relacionadas, FB comments |
| HOME | Todos los estilos de `home.php` — héroe, cards featured/compact, grillas P01-P06, sidebar widgets |

### Patches acumulativos (numerados en el CSS)

| Patch | Qué corrige |
|---|---|
| v2.1.1 | Fuerza `text-align: left` en cuerpo de nota; oculta sección "You missed" de Newsup |
| v2.1.2 | Oculta `.missed-section` y `.container-fluid.missed-section` |
| v2.1.3 | Corrige `.mg-blog-date` que aparecía en uppercase |
| v2.2.2 | Oculta `.mg-home-wrapper` y blocks de Newsup que aparecían debajo del home.php custom |
| v2.3.1 | Oculta `.site-footer`, `footer.site-footer`, `#colophon` (footer nativo de Newsup) |

---

## 9. Pilares editoriales en el tema

El tema maneja **6 slugs** de categorías mientras el plugin v8.6.0 maneja **5**. Hay solapamiento parcial pero no es 1 a 1.

| Slug (tema) | Nombre (tema) | Color (tema) | Slug (plugin) | Color (plugin) |
|---|---|---|---|---|
| `rufino-real` | Rufino real | `#c0271b` | — | — |
| `el-campo-habla` | El campo habla | `#4a7c59` | `el-campo-habla` | `#617a45` |
| `barrio-a-barrio` | Barrio a barrio | `#2d5f8a` | `barrio-a-barrio` | `#b55233` |
| `generacion-rufino` | Generación Rufino | `#7b4fa6` | `generacion-rufino` | `#c58a2b` |
| `seguimiento-promesas` | Seguimiento promesas | `#1a1a1a` | `poder-y-gestion` | `#1f2a30` |
| `contexto-datos` / `rufino-en-datos` | Rufino en datos | `#c8600a` | `rufino-en-datos` | `#2f6484` |

**Fallbacks en home.php**: Si no hay posts de `seguimiento-promesas`, usa `poder-y-gestion`. Si no hay posts de `contexto-datos`, usa `rufino-en-datos`. Esto hace la portada compatible con ambos sets de slugs.

---

## 10. Post meta fields personalizados

### Meta box en el editor: "Imagen destacada — datos"

Registrado via `add_meta_box` en `functions.php`. Aparece en todos los posts (posición: `normal`, prioridad: `high`).

Campos:
| Meta key | Tipo | Descripción | Usado en |
|---|---|---|---|
| `_er_pie_foto` | string | Descripción de la imagen destacada | `single.php` en `<figcaption>` |
| `_er_fuente_foto` | string | Crédito/fuente de la imagen | `single.php` junto al pie de foto |

Guardado con nonce `er_foto_nonce`. Sanitizado con `sanitize_text_field()`.

### Post meta adicionales (leídos en home.php, sin meta box propio)

| Meta key | Tipo | Descripción | Usado en |
|---|---|---|---|
| `_er_estado_promesa` | string | Estado de una promesa política: `cumplida`, `incumplida`, `en-proceso`, `pendiente` | Sección P05 en portada |
| `_er_dato_num` | string | Número grande de un dato estadístico (ej: "19.211") | Sección P06 en portada |
| `_er_dato_label` | string | Etiqueta del dato (ej: "Habitantes de Rufino") | Sección P06 en portada |

Estos tres meta no tienen meta box definido en el tema; se ingresarían manualmente en el editor o via un plugin de custom fields.

---

## 11. Shortcodes disponibles

### `[er_wa_subscribe]`

Banner de suscripción a WhatsApp. Parámetros opcionales:

```
[er_wa_subscribe 
  texto="Recibí las noticias de Rufino directo en tu celular." 
  link="https://whatsapp.com/channel/elrufino" 
  btn="Unirme al canal"
]
```

Output: `.er-wa-banner > .er-wa-texto + .er-btn-wa`

### `[er_seguimiento]`

Badge de estado en línea dentro del contenido de una nota.

```
[er_seguimiento estado="cumplida"]
[er_seguimiento estado="incumplida"]
[er_seguimiento estado="en proceso"]
[er_seguimiento estado="pendiente"]
[er_seguimiento estado="en seguimiento"]
```

Output: `<span class="er-p05-estado es-cumplida">cumplida</span>`

---

## 12. Seguridad y performance

### XML-RPC deshabilitado

```php
add_filter('xmlrpc_enabled', '__return_false');
```
(También deshabilitado en el plugin v8.6.0 — es redundante pero no hace daño.)

### Security headers HTTP

Agregados en cada respuesta via `send_headers`:
- `X-Content-Type-Options: nosniff`
- `X-Frame-Options: SAMEORIGIN`
- `Referrer-Policy: strict-origin-when-cross-origin`

### Head limpio

Removidos: `rsd_link`, `wlwmanifest_link`, `wp_generator` (oculta versión WP), `wp_shortlink_wp_head`, scripts y estilos de emojis.

### Queries eficientes

Todas las `WP_Query` en el ticker y sidebar usan `'no_found_rows' => true` para no calcular el total de resultados (más rápido en tablas grandes).

### Supresión de errores PHP en frontend

```php
if (!WP_DEBUG) {
    error_reporting(E_ERROR | E_PARSE);
    @ini_set('display_errors', 0);
}
```

---

## 13. Historial de patches en style.css

El CSS lleva un registro de versiones de fixes acumulativos que permite entender qué problema resolvió cada bloque:

| Versión patch | Problema resuelto |
|---|---|
| v2.1.1 | Cuerpo de notas salía centrado (Newsup default). Sección "You missed" en inglés aparecía debajo de notas. |
| v2.1.2 | Variante CSS de "You missed" con clases distintas que el patch v2.1.1 no cubría. |
| v2.1.3 | Fechas en portada aparecían en MAYÚSCULAS (`.mg-blog-date` tenía `text-transform: uppercase` del padre). |
| v2.2.2 | El loop nativo de Newsup se renderizaba debajo del home.php custom, duplicando contenido. |
| v2.3.1 | El footer nativo de Newsup aparecía debajo del footer personalizado `.er-footer`. |

---

*Documentación generada desde el código fuente vigente. Actualizar si se modifica cualquier archivo en `_ACTIVO/theme/el-rufino-theme-v2.3.4/el-rufino-theme/`.*
