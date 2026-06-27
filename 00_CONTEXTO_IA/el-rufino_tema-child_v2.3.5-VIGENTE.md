# El Rufino — Child Theme v2.3.5 · Documentación funcional operativa

> **Archivo de referencia — LISTO PERO NO DESPLEGADO**
> Generado el 2026-06-27 a partir del código fuente en `_ACTIVO/theme/el-rufino-theme-v2.3.5/`.
> Tema hijo de: **Newsup** (Template: newsup en style.css)
>
> ⚠️ **ESTADO DE DESPLIEGUE**: El tema v2.3.5 está completo en `_ACTIVO/theme/el-rufino-theme-v2.3.5/` pero **NO está activo en producción**. La versión activa en elrufino.com.ar sigue siendo v2.3.4. El deploy a producción es una decisión separada que requiere autorización explícita. No activar en wp-admin sin confirmación.
>
> Ver también: `el-rufino_tema-child_v2.3.4-VIGENTE.md` — documentación de la versión activa en producción.

---

## Índice

1. [Qué cambió respecto a v2.3.4](#1-qué-cambió-respecto-a-v234)
2. [Arquitectura general](#2-arquitectura-general)
3. [Design System — tokens CSS](#3-design-system--tokens-css)
4. [functions.php — funcionalidades PHP](#4-functionsphp--funcionalidades-php)
5. [header.php — estructura del head](#5-headerphp--estructura-del-head)
6. [home.php — portada T3A](#6-homephp--portada-t3a)
7. [single.php — nota individual](#7-singlephp--nota-individual)
8. [footer.php — pie de página](#8-footerphp--pie-de-página)
9. [style.css — estructura](#9-stylecss--estructura)
10. [Pilares editoriales en el tema](#10-pilares-editoriales-en-el-tema)
11. [Post meta fields personalizados](#11-post-meta-fields-personalizados)
12. [Shortcodes disponibles](#12-shortcodes-disponibles)
13. [Seguridad y performance](#13-seguridad-y-performance)

---

## 1. Qué cambió respecto a v2.3.4

v2.3.5 es una **reescritura de portada y estilos**, no un patch acumulativo sobre v2.3.4. Los cambios son amplios:

### CSS — Reescritura completa (1028 → 568 líneas)

- **Eliminado**: el bloque `:root` completo con los tokens `--er-*` del Design System v1.1. Todos los colores, tipografías y espaciados pasaron a valores hardcodeados inline.
- **Eliminados**: todos los bloques `/* PATCH */` acumulativos (v2.1.1 a v2.3.1). La cobertura de Newsup se resuelve directamente en los nuevos selectores.
- **Nuevas clases de portada**: `er-banner-t3a`, `er-grilla-secundaria`, `er-card-secundaria`, `er-card-link`, `er-card-img`, `er-card-body`, `er-card-titulo`, `er-card-meta`, `er-tag-pilar`.
- **Footer**: reescrito como componente autónomo con nuevas clases `.er-footer-*` (más completo que v2.3.4).

### home.php — Layout T3A (reescritura total)

- **Antes (v2.3.4)**: portada con héroe + secciones P01-P06 por pilar + sidebar de 3 columnas (1fr sidebar 300px).
- **Ahora (v2.3.5)**: layout T3A — una nota destacada horizontal (banner de imagen 44% + contenido 56%) + grilla de 3 columnas secundarias debajo. Sin sidebar. Sin secciones por pilar.

### functions.php — Fix de carga duplicada de CSS

Nueva línea que desencolaba 3 handles con los que Newsup podría duplicar el stylesheet del child theme:
```php
wp_dequeue_style('newsup-child');
wp_dequeue_style('newsup-style-child');
wp_dequeue_style('newsup-child-style');
```

### Prioridades en `wp_body_open`

- v2.3.4: masthead priority 1, ticker priority 2
- v2.3.5: masthead priority 5, ticker priority 10

### Colores de pilares en home.php

v2.3.5 define una nueva función local `er_pilar_color()` con colores distintos a los de v2.3.4 (ver [sección 10](#10-pilares-editoriales-en-el-tema)).

### footer.php — WhatsApp hardcodeado

v2.3.4 leía el link de WA desde `wp_options.er_whatsapp_canal`. v2.3.5 lo hardcodea: `https://wa.me/5493382511670`.

### single.php — Versión v2.2.0

Layout de 2 columnas (70/30) documentado en el comment header. La estructura general es similar a v2.3.4.

---

## 2. Arquitectura general

### Stack

| Capa | Tecnología |
|---|---|
| Tema padre | **Newsup** (tema de noticias de WordPress) |
| Tema hijo | `el-rufino-theme` v2.3.5 |
| Tipografías | Playfair Display (local, archivos TTF) + Source Serif 4 (Google Fonts) |
| JavaScript | Vanilla JS inline en single.php (TTS + control de fuente) |
| Facebook SDK | Cargado en single.php para comentarios |

### Filosofía de diseño v2.3.5

- **Sin variables CSS**: A diferencia de v2.3.4 (que tenía un Design System con tokens `--er-*`), v2.3.5 usa valores hardcodeados directamente. Mayor velocidad de carga, menor mantenibilidad a futuro si hay que cambiar colores globalmente.
- **Portada T3A simplificada**: En lugar de 6 secciones por pilar + sidebar, la portada muestra solo las últimas 4 notas (1 banner + 3 grilla), sin filtrar por categoría. Más simple, más visual, menos dependiente de que haya contenido en cada pilar.
- **Home.php sin sidebar**: Layout de columna central full-width (`max-width: 1200px`), sin panel lateral.
- **Footer autónomo**: El footer ya no depende de patches sobre `.site-footer` de Newsup; tiene sus propias clases `.er-footer-*` que no chocan.

### Archivos y responsabilidades

| Archivo | Responsabilidad | Versión interna |
|---|---|---|
| `style.css` | Cabecera del tema, todos los estilos (sin tokens `:root`) | v2.3.5 |
| `functions.php` | Enqueue, theme setup, hooks, shortcodes, meta boxes, filtros | v2.0.0 |
| `header.php` | `<html>`, `<head>`, `<body>`, `wp_body_open()` → topbar + masthead + ticker + nav | v2.0.0 |
| `home.php` | Portada T3A: banner destacado + grilla 3 columnas | v1.0 |
| `single.php` | Nota individual: 2 columnas 70/30, TTS, FB Comments | v2.2.0 |
| `footer.php` | Footer 4 columnas: identidad + pilares + el medio + redes | v1.0 |

---

## 3. Design System — tokens CSS

### Sin tokens en v2.3.5

A diferencia de v2.3.4, v2.3.5 **no define un bloque `:root`**. Los valores se usan directamente:

| Valor | Uso |
|---|---|
| `#c0271b` | Rojo institucional (masthead, links hover, tag pilares) |
| `#1a1a1a` | Negro (topbar, ticker, footer, texto base) |
| `rgba(255,255,255,0.55)` | Texto secundario sobre fondo oscuro |
| `#e0dbd2` | Bordes sutiles (cards, secciones) |
| `#ffffff` | Fondos de cards |
| `#7a7a7a` | Meta (fechas, tiempos de lectura) |

### Tipografías (igual que v2.3.4)

| Familia | Uso |
|---|---|
| `'Playfair Display', Georgia, serif` | Títulos (banner, cards, logo) |
| `'Source Serif 4', Georgia, serif` | Bajada del banner |
| `'Inter', sans-serif` | UI, meta, labels, ticker, topbar, footer |

Playfair Display desde archivos locales. Source Serif 4 y Inter desde Google Fonts (enqueued en functions.php con el handle `er-fonts`).

---

## 4. functions.php — funcionalidades PHP

### Enqueue de estilos (`er_theme_enqueue`)

Carga en orden:
1. `parent-style` → CSS del tema Newsup
2. `child-style` → `style.css` del tema hijo
3. `er-fonts` → Google Fonts: `Source Serif 4`
4. **Nuevo en v2.3.5**: Desencola 3 handles con los que Newsup podría duplicar el child CSS: `newsup-child`, `newsup-style-child`, `newsup-child-style`

### Theme setup (`er_theme_setup`)

Idéntico a v2.3.4:
- Post-thumbnails, title-tag, html5, custom-logo, customize-selective-refresh-widgets
- Paleta Gutenberg con los 7 colores del Design System
- `disable-custom-gradients`
- Tamaños de imagen: `er-featured` (780×440), `er-card` (400×250), `er-thumbnail` (160×100), `er-wide` (1200×500)
- Menús: `primary`, `secondary`, `footer`

### Masthead — `er_render_masthead` (hook: `wp_body_open`, priority **5**)

Renderiza topbar + masthead. Igual que v2.3.4 en estructura y contenido. Cambió la prioridad de 1 a 5.

### Ticker — `er_render_ticker` (hook: `wp_body_open`, priority **10**)

Igual que v2.3.4. Cambió la prioridad de 2 a 10. El ticker tiene `animation: er-ticker-scroll 40s` (era 35s en v2.3.4).

### Shortcodes `[er_wa_subscribe]` y `[er_seguimiento]`

Idénticos a v2.3.4.

### `er_get_pilar_color($post_id)` (en functions.php)

Idéntico a v2.3.4. Usada en single.php.

**Nota**: home.php define su propia función local `er_pilar_color($post_id)` con un mapping de colores diferente (ver sección 10).

### Recordatorio editorial, headers de seguridad, excerpt, meta boxes

Idénticos a v2.3.4.

### Filtros de localización y traducción

Idénticos a v2.3.4 (`er_fecha_espanol`, `er_fix_fecha_portada`, `er_reformat_fecha_portada`, `er_traducir_newsup`).

### Avatar personalizado

Idéntico a v2.3.4.

---

## 5. header.php — estructura del head

Idéntico a v2.3.4 en estructura. Cambios menores:
- Agrega `menu_class: 'er-nav-list'` al `wp_nav_menu`
- Comentario actualizado: prioridades ahora 5 y 10 (eran 1 y 2)

```html
<!DOCTYPE html>
<html [lang]>
<head>
    <meta charset="...">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body [class]>
<?php wp_body_open(); ?>
<!-- priority  5 → er_render_masthead() — topbar + masthead -->
<!-- priority 10 → er_render_ticker()   — separador rojo + ticker negro -->

<nav class="er-nav" ...>
    <?php wp_nav_menu(['theme_location' => 'primary', 'menu_class' => 'er-nav-list', 'container' => false, 'depth' => 1]); ?>
</nav>
```

Tags eliminados del `<head>`: mismos que v2.3.4 (`rsd_link`, `wlwmanifest_link`, `wp_generator`, `wp_shortlink_wp_head`, emojis).

---

## 6. home.php — portada T3A

Template de portada completamente reescrito. Versión interna: v1.0, mayo 2026.

### Layout T3A: Banner + Grilla 3

```
[TOPBAR + MASTHEAD + TICKER + NAV]   ← via header.php
er-home-wrap (max-width: 1200px, padding: 24px 20px 40px)
  ├── er-seccion-header
  │     ├── "Edición de hoy · Lo más importante"
  │     └── "Ver todas las notas →" (link a posts)
  ├── er-banner-t3a (nota más reciente)
  │     ├── er-banner-img (44% ancho, 148px alto, background-image)
  │     └── er-banner-contenido (56%)
  │           ├── er-tag-pilar (etiqueta con border-bottom del color del pilar)
  │           ├── er-banner-titulo (Playfair Display 18px)
  │           ├── er-banner-bajada (Source Serif 4 13px, 2 líneas max)
  │           └── er-banner-meta (fecha · tiempo · "Por autor")
  └── er-grilla-secundaria (3 columnas)
        └── [x3] er-card-secundaria
                  ├── er-card-img (100% ancho, 72px alto, background-image)
                  └── er-card-body
                        ├── er-tag-pilar
                        ├── er-card-titulo (Playfair Display 13px)
                        └── er-card-meta (fecha · tiempo)
[FOOTER]   ← via footer.php
```

### Queries

| Query | Parámetros | Propósito |
|---|---|---|
| `$q_destacada` | `posts_per_page: 1, post_status: publish` | Nota destacada (banner) |
| `$q_secundarias` | `posts_per_page: 3, post_status: publish, post__not_in: [id_destacada]` | 3 cards de la grilla |

No filtra por categoría. Muestra las últimas 4 notas publicadas, sin importar el pilar.

### `er_pilar_color($post_id)` local

Función helper definida **dentro** de home.php (diferente a la del mismo nombre en functions.php):

```php
$mapa = [
    'rufino-real'          => '#c0271b',
    'el-campo-habla'       => '#2a5f82',
    'barrio-a-barrio'      => '#4e7232',
    'generacion-rufino'    => '#b8760a',
    'seguimiento-promesas' => '#7a3d9a',
    'contexto-datos'       => '#1a6b5a',
];
```

Retorna un array `['color' => '#hex', 'nombre' => 'Nombre del pilar']`.

### `er_tiempo_lectura($post_id)` local

```php
$palabras = str_word_count(wp_strip_all_tags(get_post_field('post_content', $post_id)));
$minutos  = max(1, round($palabras / 200));
return $minutos . ' min lectura';
```

### Etiqueta de pilar — estilo Línea

El tag de pilar usa `border-bottom` en lugar de fondo de color:
```css
.er-tag-pilar {
    border-bottom: 1.5px solid currentColor;
    color: var(pilar_color);
    font-size: 9px; font-weight: 700; text-transform: uppercase;
}
```

---

## 7. single.php — nota individual

Versión interna: v2.2.0. Layout documentado como "2 columnas 70/30".

### Estructura

```
[HEADER — topbar + masthead + ticker + nav]
er-single-wrap
  er-article (article#post-ID)
    er-article-fullwidth
      ├── CATEGORÍA — er-cat-badge con color del pilar (usa er_get_pilar_color de functions.php)
      ├── TÍTULO — h1.er-article-title (Playfair Display)
      ├── META — autor (avatar 28px) · fecha en español · tiempo de lectura
      ├── CONTROLES — A− · A+ · Escuchar · compartir (FB, X, WA, TG)
      ├── IMAGEN DESTACADA — er-featured (780×440) + figcaption (pie + fuente)
      └── CUERPO — div#er-article-body (the_content())
    ├── TAGS — lista tipo hashtag (#tag)
    ├── NOTAS RELACIONADAS — 3 posts random del mismo pilar
    └── COMENTARIOS FACEBOOK
[FOOTER]
```

### Fecha en español (generada localmente)

Single.php genera la fecha manualmente con un array de meses (no depende del filtro `er_fecha_espanol`):
```php
$ts = get_the_date('U');
$meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
echo intval(date('j',$ts)) . ' de ' . $meses[intval(date('n',$ts))-1] . ' de ' . date('Y',$ts);
```

### Controles de lectura

Botones de fuente (A−/A+), TTS (`SpeechSynthesis`, lang: `es-AR`, rate: 0.95), compartir en 4 redes. Idénticos en funcionalidad a v2.3.4.

### Imagen destacada

Tamaño `er-featured` (780×440). Meta `_er_pie_foto` y `_er_fuente_foto` en `<figcaption>`.

### Notas relacionadas

3 posts por `category__in` (primera categoría), `orderby: rand`. Grid 3 columnas con imagen `er-thumbnail` (160×100).

### Comentarios Facebook

SDK `appId: 1314916506819026`. Igual que v2.3.4.

---

## 8. footer.php — pie de página

Reescrito en v2.3.5. Grid 4 columnas sobre fondo `#1a1a1a`, borde superior 3px `#c0271b`.

### Diferencias respecto a v2.3.4

- **WhatsApp hardcodeado**: `https://wa.me/5493382511670` en lugar de leer `wp_options.er_whatsapp_canal`
- **Columna 1** — agrega párrafo: "Toda nota tiene dos capas: lo que pasó y lo que significa."
- **Estructura idéntica** en columnas 2-4 (pilares, el medio, redes)
- **No requiere patch** para ocultar el footer nativo de Newsup — el CSS v2.3.5 lo maneja directamente con los selectores `.er-footer`

### Grid (igual que v2.3.4: `1.6fr 1fr 1fr 1fr`)

- **Columna 1**: Logo "R" (cuadrado rojo 40×40) + nombre + claim + descripción del medio
- **Columna 2**: Pilares editoriales (6 links, URLs directas a `/categoria/slug`)
- **Columna 3**: El medio (Quiénes somos, Cómo trabajamos, Promesas políticas, Contacto, Publicidad)
- **Columna 4**: Redes (WA, IG, FB, TikTok) con iconos en cuadrado `er-red-icono`

### Footer bottom

`© [año actual] El Rufino · Rufino, Santa Fe, Argentina` | `elrufino.com.ar`

---

## 9. style.css — estructura

v2.3.5 tiene 568 líneas vs 1028 de v2.3.4. Sin tokens `:root`, sin bloques PATCH numerados.

### Bloques principales

| Bloque | Contenido |
|---|---|
| Header (líneas 1-10) | Cabecera del tema con metadatos |
| TOPBAR | `.er-topbar`, `.er-topbar-loc` |
| MASTHEAD | `.er-masthead`, `.er-logo-r`, `.er-logo-texto`, `.er-logo-nombre`, `.er-logo-claim`, `.er-btn-wa` |
| TICKER | `.er-ticker`, `.er-ticker-label`, `.er-ticker-track`, `.er-ticker-inner`, `.er-ticker-item`, `.er-ticker-dot`, `@keyframes er-ticker-scroll` (40s) |
| HOME WRAP — T3A | `.er-home-wrap`, `.er-seccion-header`, `.er-seccion-label`, `.er-ver-todas`, `.er-tag-pilar`, `.er-banner-t3a`, `.er-banner-link`, `.er-banner-img`, `.er-banner-contenido`, `.er-banner-titulo`, `.er-banner-bajada`, `.er-banner-meta` |
| Grilla secundaria | `.er-grilla-secundaria`, `.er-card-secundaria`, `.er-card-link`, `.er-card-img`, `.er-card-body`, `.er-card-titulo`, `.er-card-meta` |
| FOOTER INSTITUCIONAL | `.er-footer`, `.er-footer-inner`, `.er-footer-col`, `.er-footer-logo`, `.er-footer-logo-r`, `.er-footer-nombre`, `.er-footer-claim`, `.er-footer-desc`, `.er-footer-heading`, `.er-footer-list`, `.er-footer-redes`, `.er-red-icono`, `.er-footer-bottom` |
| RESPONSIVE | `@media (max-width: 767px)` — banner apilado verticalmente, grilla de 1 columna, cards horizontales de 80px |

### Responsive (mobile ≤767px)

- Banner T3A: `flex-direction: column`, imagen a 100% de ancho, 180px de alto
- Grilla secundaria: `grid-template-columns: 1fr` (1 columna)
- Cards secundarias: `flex-direction: row`, 80px de alto (imagen lateral)
- Footer: `grid-template-columns: 1fr` (1 columna apilada)

---

## 10. Pilares editoriales en el tema

v2.3.5 mantiene 6 slugs como v2.3.4, pero con colores diferentes en `home.php`:

| Slug | Nombre | Color v2.3.4 | Color v2.3.5 (home.php) | Color er_get_pilar_color (functions.php) |
|---|---|---|---|---|
| `rufino-real` | Rufino real | `#c0271b` | `#c0271b` | `#c0271b` |
| `el-campo-habla` | El campo habla | `#4a7c59` | `#2a5f82` | `#4a7c59` |
| `barrio-a-barrio` | Barrio a barrio | `#2d5f8a` | `#4e7232` | `#2d5f8a` |
| `generacion-rufino` | Generación Rufino | `#7b4fa6` | `#b8760a` | `#7b4fa6` |
| `seguimiento-promesas` | Poder y gestión | `#1a1a1a` | `#7a3d9a` | — |
| `contexto-datos` | Rufino en datos | `#c8600a` | `#1a6b5a` | `#c8600a` |
| `poder-y-gestion` | Poder y gestión | — | — | `#1a1a1a` |

**Inconsistencia**: los colores en `er_pilar_color()` de home.php difieren de los de `er_get_pilar_color()` en functions.php (que usa single.php). Esto significa que el color del pilar puede variar entre portada y nota individual.

---

## 11. Post meta fields personalizados

### Meta box en el editor: "Imagen destacada — datos"

Igual que v2.3.4.

| Meta key | Tipo | Descripción | Usado en |
|---|---|---|---|
| `_er_pie_foto` | string | Descripción de la imagen destacada | `single.php` en `<figcaption>` |
| `_er_fuente_foto` | string | Crédito/fuente de la imagen | `single.php` junto al pie de foto |

Guardado con nonce `er_foto_nonce`, sanitizado con `sanitize_text_field()`.

### Post meta de pilar y datos (igual que v2.3.4)

| Meta key | Tipo | Descripción |
|---|---|---|
| `_er_estado_promesa` | string | Estado de promesa política |
| `_er_dato_num` | string | Número grande de dato estadístico |
| `_er_dato_label` | string | Etiqueta del dato |

**Nota**: en v2.3.5, el home.php T3A ya no usa estas metas (no muestra secciones específicas P05/P06). Solo `_er_pie_foto` y `_er_fuente_foto` siguen activos en `single.php`.

---

## 12. Shortcodes disponibles

Idénticos a v2.3.4.

### `[er_wa_subscribe]`

```
[er_wa_subscribe 
  texto="Recibí las noticias de Rufino directo en tu celular." 
  link="https://whatsapp.com/channel/elrufino" 
  btn="Unirme al canal"
]
```

### `[er_seguimiento]`

```
[er_seguimiento estado="cumplida"]
[er_seguimiento estado="incumplida"]
[er_seguimiento estado="en proceso"]
[er_seguimiento estado="pendiente"]
[er_seguimiento estado="en seguimiento"]
```

---

## 13. Seguridad y performance

Idénticos a v2.3.4:

- XML-RPC deshabilitado
- Headers HTTP de seguridad (`X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy`)
- Head limpio (removidos: `rsd_link`, `wlwmanifest_link`, `wp_generator`, emojis)
- `WP_Query` con `no_found_rows: true` en ticker (sin sidebar en portada, menos queries)
- Supresión de errores PHP en frontend cuando `!WP_DEBUG`

---

## Estado de archivos en `_ACTIVO`

```
F:\HERRAMIENTAS DE IA\PROYECTOS\EL_RUFINO\_ACTIVO\theme\
├── el-rufino-child\           ← Child theme ACTIVO en producción (v2.3.4)
├── el-rufino-theme-v2.3.4\   ← Tema padre (Newsup) en producción
├── el-rufino-theme-v2.3.5\   ← ← ← ESTE TEMA — listo, NO desplegado
└── el-rufino-theme-v2.3.5.zip
```

**Para desplegar v2.3.5**: reemplazar el contenido de `el-rufino-child/` con los archivos de `el-rufino-theme-v2.3.5/`, o instalar el zip desde WP Admin → Apariencia → Temas. Requiere autorización explícita de Cristian antes de ejecutar.

---

*Documentación generada desde el código fuente vigente. v2.3.4 sigue siendo la versión activa en producción — no archivar este doc hasta que v2.3.5 esté efectivamente desplegado.*
