# El Rufino — Plugin Panel IA v8.7.5 · Documentación funcional operativa

> **Archivo de referencia vigente** — Generado el 2026-06-27 a partir del código fuente en `_ACTIVO/plugin/`.
> Fuente principal: `_ACTIVO/plugin/el-rufino-panel.php` + `_ACTIVO/plugin/assets/panel.js`
> ZIP de distribución: `02_WORDPRESS_TEST/el-rufino-panel-v8.7.5.zip`
> Confirmado en producción: `elrufino.com.ar` — 2026-06-27

---

## Índice

1. [Arquitectura general](#1-arquitectura-general)
2. [Inicialización y carga](#2-inicialización-y-carga)
3. [Pantalla 1 — Dashboard](#3-pantalla-1--dashboard)
4. [Pantalla 2 — Producción](#4-pantalla-2--producción)
5. [Pantalla 3 — Inteligencia editorial](#5-pantalla-3--inteligencia-editorial)
6. [Pantalla 4 — Seguimiento de promesas](#6-pantalla-4--seguimiento-de-promesas)
7. [Pantalla 5 — Asistente IA](#7-pantalla-5--asistente-ia)
8. [Escritorio WordPress personalizado](#8-escritorio-wordpress-personalizado)
9. [Registro de endpoints AJAX](#9-registro-de-endpoints-ajax)
10. [Pilares editoriales](#10-pilares-editoriales)
11. [Schema.org NewsMediaOrganization](#11-schemaorg-newsmediaorganization)
12. [Seguridad y hardening](#12-seguridad-y-hardening)
13. [Variables y opciones en base de datos](#13-variables-y-opciones-en-base-de-datos)
14. [Royal MCP — Integración](#14-royal-mcp--integración)

---

## 1. Arquitectura general

### Stack técnico

| Capa | Tecnología |
|---|---|
| Backend | PHP 8.x (WordPress plugin) |
| Frontend | React 18 (UMD, sin build step) cargado vía `wp_enqueue_script` desde CDN |
| Comunicación | WordPress AJAX (`admin-ajax.php`) con nonce de seguridad |
| APIs externas | Anthropic Claude API directo, OpenRouter API, YouTube Data API v3, YouTube timedtext público |
| Almacenamiento | WordPress `wp_options` (sin tablas propias) |
| Integración IA externa | Royal MCP v1.4+ (expone opciones del plugin para lectura/escritura directa desde Claude) |

### Filosofía de arquitectura

- **Sin build step**: `panel.js` es JavaScript puro (ES5+), React 18 se carga desde CDN de unpkg. No necesita Node, webpack ni compilación.
- **Pantalla completa inmersiva**: El plugin oculta completamente la UI de WordPress (adminbar, sidebar, footer) mediante CSS inyectado en `admin_head`. El panel ocupa `100vw × 100vh` con `position: fixed; inset: 0; z-index: 9999`.
- **Doble proveedor IA**: Soporta Anthropic directo o OpenRouter (permite usar cualquier modelo que OpenRouter disponibilice: Claude, GPT-4, Gemini, etc.). La selección es persistente en `wp_options`.
- **Single Page Application (SPA)**: Cinco pantallas navegables desde un sidebar fijo. El estado se mantiene en memoria mientras el usuario no recarga.
- **Royal MCP bridge**: Las opciones críticas del plugin y los theme mods del child theme se exponen a Claude vía Royal MCP para lectura y escritura directa sin AJAX propio.

### Estructura de archivos del plugin

```
el-rufino-panel/
├── el-rufino-panel.php    ← Plugin principal (PHP, 1.434 líneas)
└── assets/
    └── panel.js           ← Frontend React (1.125 líneas, ES5+)
```

---

## 2. Inicialización y carga

### Activación del plugin (`register_activation_hook`)

Al activar el plugin por primera vez, se ejecuta `er_crear_categorias()` que crea los 5 pilares editoriales como categorías de WordPress si no existen. Ver [sección 10](#10-pilares-editoriales).

### Registro del menú admin

```
Menú: "El Rufino" (posición 3, ícono SVG rojo ER, capability: manage_options)
URL: /wp-admin/admin.php?page=el-rufino-panel
```

### Enqueue de assets (solo en la página del panel)

Solo carga assets cuando `$hook === 'toplevel_page_el-rufino-panel'`:

1. `react@18` desde unpkg CDN
2. `react-dom@18` desde unpkg CDN
3. `panel.js` (versión fija por `ER_VERSION` para evitar caché stale)

### Objeto `erData` (JavaScript global)

El PHP pasa datos al frontend vía `wp_localize_script`:

| Clave | Contenido |
|---|---|
| `erData.ajaxUrl` | URL de `admin-ajax.php` |
| `erData.nonce` | Nonce WP (`er_nonce`) para autenticar todas las llamadas AJAX |
| `erData.siteUrl` | URL base del sitio |
| `erData.adminUrl` | URL de `/wp-admin/` |
| `erData.version` | `8.7.5` |
| `erData.userName` | `display_name` del usuario logueado |

### Helpers JavaScript

- **`wpAjax(action, data)`**: Envía POST a `admin-ajax.php` con nonce automático, retorna Promise.
- **`wpRest(path)`**: Llama a la WP REST API (`/wp-json/wp/v2/…`) con nonce en header, retorna Promise.

---

## 3. Pantalla 1 — Dashboard

**Componente React**: `Dashboard()`
**Ruta de navegación**: ícono ◉ en sidebar

### Qué muestra

Esta es la pantalla de control operativo del proyecto. Se carga primero por defecto al abrir el panel.

### Bloque A — Estadísticas del sitio (4 tarjetas de color)

Se cargan al montar el componente llamando a `er_stats`. Muestran:

| Tarjeta | Color | Qué cuenta |
|---|---|---|
| **Publicadas** | Azul `#2563eb` | Posts con `post_status = 'publish'` |
| **Borradores** | Violeta `#7c3aed` | Posts con `post_status = 'draft'` |
| **Comentarios** | Cian `#0891b2` | Comentarios aprobados |
| **Actualizaciones** | Naranja/verde según cantidad | Plugins de WP con actualizaciones pendientes |

Mientras carga muestra cuatro tarjetas grises con `…`.

### Bloque B — Checklist de lanzamiento

Lista de 8 ítems configurables con checkbox. Estado persistente en `wp_options` (`er_checklist`). Los ítems por defecto son:

1. 6 categorías P01-P06
2. Logo/favicon/OG subidos
3. Schema NewsMediaOrganization
4. 20 notas publicadas
5. Child theme Newsup instalado
6. Dominio .ar registrado en NIC
7. Canal WhatsApp creado
8. Plugin v8.5 en producción

Al marcar/desmarcar un ítem, guarda inmediatamente en el servidor. El texto de ítems marcados aparece tachado en gris.

### Bloque C — Importador de notas demo

**Propósito**: Poblar el sitio vacío con 10 notas de ejemplo generadas con IA para demostrar funcionamiento a clientes o testers.

**Flujo operativo**:
1. Usuario hace clic en "Generar 10 notas demo".
2. El JS llama secuencialmente a `er_import_demo_one` con `index = 0` hasta `9` (una por llamada, no en paralelo).
3. Por cada nota: el servidor llama a la IA, convierte Markdown a HTML, crea el post como borrador, descarga imagen de Unsplash y la establece como imagen destacada.
4. Una barra de progreso anima `width` del 0% al 100%.
5. Un log con ✓ (éxito) o ✗ (error) se construye ítem por ítem.
6. Al completar, refresca las estadísticas del bloque A.
7. Aparece botón "Re-generar" para repetir el proceso.

**Las 10 notas predefinidas** (distribuidas en los 5 pilares):

| # | Título | Pilar |
|---|---|---|
| 0 | Las obras del barrio norte siguen demoradas | Barrio a barrio |
| 1 | Nuevo parque de juegos en el barrio oeste: qué falta | Barrio a barrio |
| 2 | Cómo creció la población de Rufino en la última década | Rufino en datos |
| 3 | Estadísticas de empleo en Rufino: primer semestre 2026 | Rufino en datos |
| 4 | Los productores locales anticipan una campaña difícil | El campo habla |
| 5 | La sequía afecta la producción en el sur de Santa Fe | El campo habla |
| 6 | Jóvenes rufinenses destacan en el torneo provincial | Generación Rufino |
| 7 | Egresados 2025: el camino después del secundario | Generación Rufino |
| 8 | El municipio prometió pavimentar tres calles del sur | Poder y gestión |
| 9 | Semáforo de promesas: qué se cumplió y qué no en 2025 | Poder y gestión |

Cada nota tiene un prompt especializado que instruye a la IA a generar: TÍTULO, BAJADA, CUERPO (pirámide invertida, 3 párrafos), LO QUE SIGNIFICA. Máximo 400-450 palabras.

**Imágenes**: Descarga de Unsplash con palabras clave temáticas por pilar:
- Barrio a barrio → `neighborhood street argentina`
- Rufino en datos → `data statistics chart argentina`
- El campo habla → `soybean farm argentina campo`
- Generación Rufino → `youth sport argentina students`
- Poder y gestión → `city hall government argentina`

### Bloque D — Proveedor IA

Configuración del motor de IA. Dos columnas lado a lado:

#### Columna izquierda — Anthropic (Claude)

- Campo de password para ingresar clave `sk-ant-api03-…`
- Badge "ACTIVO" (verde) si es el proveedor seleccionado
- Badge azul con los primeros 8 caracteres de la clave si está configurada (enmascarada)
- Botón "Guardar": llama a `er_save_key` y luego `er_save_provider` con `provider = 'anthropic'`
- Modelo fijo: `claude-sonnet-4-6`

#### Columna derecha — OpenRouter

- Campo de password para ingresar clave `sk-or-v1-…`
- Campo de texto para especificar modelo (por defecto: `anthropic/claude-sonnet-4`)
- Badge "ACTIVO" si es el proveedor seleccionado
- Botón "Guardar": llama a `er_save_orkey` (guarda key + modelo) y luego `er_save_provider` con `provider = 'openrouter'`
- Permite usar cualquier modelo disponible en OpenRouter escribiendo su identificador

**Lógica de autodetección en el backend**: Si `er_ai_provider` no está guardado, el PHP detecta automáticamente por prefijo de la clave (`sk-or-` → OpenRouter, cualquier otro → Anthropic).

---

## 4. Pantalla 2 — Producción

**Componente React**: `Produccion()`
**Ruta de navegación**: ícono ✏️ en sidebar

### Qué hace

Lista las entradas del sitio con filtros y acceso directo a edición. Usa la WP REST API (no AJAX).

### Filtros de estado

Tres botones en la toolbar:

| Botón | Filtra |
|---|---|
| Todas | `post_status = publish,draft` |
| Publicadas | `post_status = publish` |
| Borradores | `post_status = draft` |

Al cambiar el filtro, recarga los posts vía `GET /wp-json/wp/v2/posts?per_page=30&status=…&_embed=1`.

### Lista de entradas

Por cada entrada muestra:
- **Barra de color** (4px de ancho) con el color del pilar editorial al que pertenece
- **Título** (clicable, abre editor de WordPress en nueva pestaña)
- **Nombre de categoría** debajo del título (gris pequeño)
- **Badge de estado**: "Pub" (verde) o "Bor" (amarillo)
- **Botón "Editar"** que abre `/wp-admin/post.php?post=ID&action=edit`

### Botón "Nueva entrada"

Abre `/wp-admin/post-new.php` en nueva pestaña.

---

## 5. Pantalla 3 — Inteligencia editorial

**Componente React**: `Inteligencia()`
**Ruta de navegación**: ícono 📊 en sidebar

### Qué hace

Análisis cuantitativo de la distribución editorial por pilar. Usa la WP REST API pública sin autenticación especial.

Fuente de datos: `GET /wp-json/wp/v2/categories?per_page=50` → filtra los 5 slugs de pilares.

### Bloque A — Distribución editorial por pilar (barras horizontales)

Por cada pilar:
- Nombre en el color del pilar
- Barra de progreso horizontal proporcional al máximo (la que más tiene = 100%)
- Contador de notas a la derecha
- Al pie: total de entradas publicadas

Animación CSS: `transition: width 0.5s` al cargar.

### Bloque B — Cobertura por pilar (grid de 5 cards)

5 tarjetas en grilla:
- Número grande (cantidad de notas) en color del pilar
- Nombre del pilar
- Porcentaje sobre el total (`count / total * 100`)
- Fondo con `opacity: 8%` del color del pilar
- Borde con `opacity: 40%` del color del pilar

---

## 6. Pantalla 4 — Seguimiento de promesas

**Componente React**: `Seguimiento()`
**Ruta de navegación**: ícono 📋 en sidebar

### Propósito

Herramienta periodística para trackear compromisos públicos de funcionarios (intendente, concejales, etc.) con seguimiento de su estado. Usa `wp_options`.

### Estados disponibles

| Estado | Color | Badge |
|---|---|---|
| Pendiente | Naranja `#d97706` | Fondo `#fef3c7` |
| Cumplida | Verde `#059669` | Fondo `#d1fae5` |
| Incumplida | Rojo `#dc2626` | Fondo `#fee2e2` |
| Parcial | Violeta `#7c3aed` | Fondo `#ede9fe` |

### Toolbar

- Contador de promesas registradas (ej: "12 promesas registradas")
- Botón "Exportar CSV" (visible solo si hay promesas)
- Botón "+ Nueva promesa" / "Cancelar" (toggle)

### Formulario de nueva promesa

- **Textarea**: Descripción de la promesa (requerido)
- **Fuente**: Quién prometió (texto libre)
- **Fecha**: Date picker, pre-cargado con la fecha de hoy
- Botón "Guardar" (deshabilitado si textarea vacío)

### Lista de promesas

Por cada promesa:
- Punto de color según estado
- Texto de la promesa (semibold)
- Fuente · Fecha (gris pequeño)
- `<select>` de estado (coloreado) — al cambiar, guarda inmediatamente y actualiza el estado local en memoria

### Exportar CSV

Genera y descarga `promesas-el-rufino.csv` con columnas: `ID, Texto, Fuente, Fecha, Estado`. Usa `Blob` + `URL.createObjectURL`.

---

## 7. Pantalla 5 — Asistente IA

**Componente React**: `Asistente()`
**Ruta de navegación**: ícono 🤖 en sidebar

### Propósito

Herramienta de redacción asistida por IA para generar borradores de notas periodísticas combinando: datos de un video de YouTube + transcripción automática + imagen de referencia + palabras clave + pilar editorial.

### Layout: dos columnas

**Columna izquierda** (contexto de entrada) · **Columna derecha** (borrador generado)

---

### Columna izquierda — Bloques de entrada

#### Bloque 1 — YouTube API Key (solo si no está configurada)

Aparece como aviso naranja solo cuando la key no está guardada:
- Campo password para `AIzaSy…`
- Botón "Guardar" → llama a `er_save_ytkey` → refresca estado
- Una vez guardada, el bloque desaparece

#### Bloque 2 — Video YouTube (opcional)

- Campo de texto para pegar URL del video
- Botón "Buscar" (deshabilitado si no hay YouTube API Key)
- Al buscar: llama a `er_yt_info` → YouTube Data API v3
- Si hay resultado: thumbnail, título, canal, fecha + botón "Obtener subtítulos automáticos"

#### Sub-bloque — Subtítulos automáticos

- Llama a `er_yt_captions` con el `video_id`
- Prueba endpoint público `youtube.com/api/timedtext` en orden: `es → es-419 → es-AR → en`
- El texto parseado pre-carga el campo de transcripción

#### Bloque 3 — Transcripción / contexto

Textarea editable (6 filas). Populable automáticamente desde subtítulos o manualmente.

#### Bloque 4 — Imagen de referencia (opcional)

- `<input type="file" accept="image/*">` oculto activado por botón
- Convierte a base64 via `FileReader`, muestra preview
- Solo funciona con Anthropic directo (visión multimodal)

#### Bloque 5 — Configurar nota + Generar

- Selector de pilar (dropdown con los 5 pilares)
- Palabras clave / contexto adicional
- Botón "Generar borrador con IA" (deshabilitado hasta tener al menos un input)

---

### Columna derecha — Borrador generado

Textarea editable (`height: 520px`, fuente monospace) con el texto generado.

**Toolbar del borrador**:
- Botón "Guardar en WordPress" → llama a `er_asistente_guardar`
- Badge verde "✓ Guardado como borrador en WordPress" tras guardar
- Botón "Ver en WP" (aparece tras guardar)

#### Estructura de nota que genera la IA

```
**TÍTULO:** (directo, sin punto final, máx 12 palabras)
**BAJADA:** (1 oración, el dato más importante, máx 30 palabras)
**CUERPO:** (3-4 párrafos, 500-700 palabras, pirámide invertida)
**LO QUE SIGNIFICA:** (1 párrafo de análisis para Rufino)
**CITA DESTACADA:** (declaración relevante del video, si la hay)
**FUENTE:** (fuente sugerida para verificación)
```

#### System prompt del Asistente

```
Sos el redactor de El Rufino, medio digital de Rufino, Santa Fe, Argentina.
Tu estilo: directo, preciso, sin adjetivos vacíos, con contexto local siempre.
Usás la "regla de dos capas": primero el hecho concreto, luego "Lo que significa".
Escribís en español rioplatense, segunda persona del plural (ustedes/los rufinenses).
Evitás clichés periodísticos y frases de relleno.
```

---

## 8. Escritorio WordPress personalizado

Cuando el usuario va al Dashboard nativo de WordPress (`/wp-admin/`), el plugin reemplaza todos los widgets nativos con 4 widgets propios.

### Widget 1 — Bienvenida

- Saludo con nombre del usuario y fecha de hoy en español
- Botón rojo "🚀 Abrir Panel IA"
- Botón azul "✏️ Nueva entrada"
- Pie: "Panel IA v8.7.5 · Fase 2 en ejecución"

### Widget 2 — Estado del sitio

Grid 2×2: Publicadas (verde), Borradores (azul), Comentarios (violeta), Actualizaciones (naranja/verde).

### Widget 3 — Entradas por pilar

Barras horizontales proporcionales para cada uno de los 5 pilares, enlazadas a `edit.php?category_name=SLUG`.

### Widget 4 — Últimas entradas

Lista de las 8 entradas más recientes con punto de color del pilar, título clicable, badge Pub/Bor y tiempo relativo.

---

## 9. Registro de endpoints AJAX

Todos los endpoints usan `wp_ajax_{action}` (solo usuarios logueados). Todos verifican nonce `er_nonce`.

### `er_stats`

**Retorna**: `{ published, drafts, comments, updates }`

---

### `er_get_checklist` / `er_save_checklist`

**Lee/guarda en**: `wp_options.er_checklist`

---

### `er_save_key` / `er_key_status`

**Guarda/lee**: `wp_options.er_claude_key` — Anthropic API key

### `er_save_orkey` / `er_orkey_status`

**Guarda/lee**: `wp_options.er_or_api_key`, `wp_options.er_or_model`

### `er_save_provider`

**Guarda en**: `wp_options.er_ai_provider` (`"anthropic"` | `"openrouter"`)

---

### `er_claude` (proxy IA general)

**Routing**: Lee `er_ai_provider` → Anthropic (`claude-sonnet-4-6`, timeout 90s) o OpenRouter (modelo guardado, timeout 90s).
**Retorna**: `{ content: "texto generado" }`

---

### `er_import_demo_one`

**Params**: `index` (0-9)
**Flujo**: Valida → llama IA → convierte Markdown a HTML → crea post borrador → descarga imagen Unsplash → asigna imagen destacada
**Retorna**: `{ index, titulo, post_id, imagen: bool }`

---

### `er_get_promesas` / `er_save_promesa` / `er_update_promesa` / `er_export_promesas`

Gestión completa del módulo de seguimiento de promesas en `wp_options.er_promesas`.

---

### `er_save_ytkey` / `er_ytkey_status`

**Guarda/lee**: `wp_options.er_yt_api_key`

### `er_yt_info`

Obtiene metadata de video YouTube vía Data API v3.

### `er_yt_captions`

Transcripción automática via `youtube.com/api/timedtext` (sin auth). Orden: `es → es-419 → es-AR → en`.

### `er_asistente_generar`

**Max tokens**: 2000 · **Timeout**: 120s · **Multimodal** (solo Anthropic): acepta `imagen_b64`

### `er_asistente_guardar`

Crea post en borrador, asigna categoría del pilar. **Retorna**: `{ post_id, edit_url }`

---

## 10. Pilares editoriales

Los 5 pilares son las categorías editoriales centrales del medio. Se crean automáticamente al activar el plugin.

| Nombre | Slug | Color hex | Temática |
|---|---|---|---|
| Barrio a barrio | `barrio-a-barrio` | `#b55233` | Obras, vecinos, infraestructura urbana |
| Rufino en datos | `rufino-en-datos` | `#2f6484` | Estadísticas, censos, economía local |
| El campo habla | `el-campo-habla` | `#617a45` | Agro, soja, ganadería, zona rural |
| Generación Rufino | `generacion-rufino` | `#c58a2b` | Jóvenes, deporte, educación, cultura |
| Poder y gestión | `poder-y-gestion` | `#1f2a30` | Municipio, política, rendición de cuentas |

---

## 11. Schema.org NewsMediaOrganization

El plugin inyecta JSON-LD en el `<head>` de **todas las páginas públicas**:

```json
{
  "@context": "https://schema.org",
  "@type": "NewsMediaOrganization",
  "name": "El Rufino",
  "url": "https://elrufino.ar",
  "foundingDate": "2026",
  "inLanguage": "es-AR",
  "areaServed": [
    { "@type": "City", "name": "Rufino" },
    { "@type": "State", "name": "Santa Fe" },
    { "@type": "Country", "name": "Argentina" }
  ]
}
```

En páginas individuales (`is_single()`), además inyecta `NewsArticle` con headline, datePublished, dateModified, articleSection, author, publisher e image destacada.

---

## 12. Seguridad y hardening

- **XML-RPC deshabilitado**: `add_filter('xmlrpc_enabled', '__return_false')`
- **Redirección HTTPS forzada**: 301 permanente en `init`, excluye admin y CLI
- **Nonce en todos los endpoints AJAX**: `check_ajax_referer('er_nonce', 'nonce')` como primera línea
- **Sanitización**: `sanitize_text_field()`, `sanitize_textarea_field()`, `wp_kses_post()`, cast a `(int)`, `json_decode(stripslashes(...))`
- **Aislamiento**: `defined('ABSPATH') || exit`, menú requiere `manage_options`, assets solo en el hook del panel

---

## 13. Variables y opciones en base de datos

| Option key | Tipo | Descripción | Default |
|---|---|---|---|
| `er_checklist` | array | Estado de los 8 ítems del checklist | 8 ítems con `ok: false` |
| `er_claude_key` | string | API key de Anthropic (`sk-ant-…`) | `''` |
| `er_or_api_key` | string | API key de OpenRouter (`sk-or-…`) | `''` |
| `er_or_model` | string | Modelo seleccionado para OpenRouter | `'anthropic/claude-sonnet-4'` |
| `er_ai_provider` | string | Proveedor activo: `'anthropic'` o `'openrouter'` | `''` (autodetecta) |
| `er_promesas` | array | Lista de promesas con id, texto, fuente, fecha, estado | `[]` |
| `er_yt_api_key` | string | YouTube Data API v3 key (`AIzaSy…`) | `''` |

### Constantes PHP

| Constante | Valor |
|---|---|
| `ER_VERSION` | `'8.7.5'` |
| `ER_PLUGIN_URL` | URL absoluta del directorio del plugin |
| `ER_PLUGIN_DIR` | Path absoluto del directorio del plugin |

---

## 14. Royal MCP — Integración

> **Sección añadida en v8.7.4, corregida en v8.7.5.**
> Compatible con Royal MCP v1.4+. Permite a Claude leer y escribir opciones y theme mods directamente sin AJAX propio.

### 14a. Opciones escribibles via Royal MCP

Expuestas mediante `add_filter('royal_mcp_writable_options', ...)`:

| Option key | Descripción |
|---|---|
| `er_anthropic_key` | API key Anthropic |
| `er_openrouter_key` | API key OpenRouter |
| `er_ia_provider` | Proveedor IA activo |
| `er_yt_key` | YouTube Data API key |
| `er_checklist` | Checklist Fase 2 |
| `er_promesas` | Tabla de seguimiento de promesas |
| `er_coberturas` | Coberturas activas |
| `er_panel_config` | Configuración general del panel |

### 14b. Theme mods escribibles via Royal MCP

Expuestos mediante `add_filter('royal_mcp_writable_theme_mods', ...)`:

| Theme mod | Descripción |
|---|---|
| `er_color_primario` | Color principal (Paleta B) |
| `er_color_negro` | Color negro institucional |
| `er_color_crema` | Color crema base |
| `er_fuente_titulos` | Tipografía de títulos |
| `er_fuente_cuerpo` | Tipografía de cuerpo |
| `er_header_style` | Estilo del header |
| `custom_css_post_id` | CSS personalizado activo |

### 14c. Validación de API key (corregido en v8.7.5)

En v8.7.4, la validación de la API key de Royal MCP usaba:
```php
return $key && $key === get_option('royal_mcp_api_key', '');
```

En v8.7.5 se corrigió para leer desde la estructura correcta y usar comparación segura:
```php
$settings = get_option('royal_mcp_settings', []);
$stored = $settings['api_key'] ?? '';
return $key && $stored && hash_equals($stored, $key);
```

Este fix aplica a los dos filtros de validación (`royal_mcp_writable_options` y `royal_mcp_writable_theme_mods`).

---

## Notas operativas y decisiones de diseño

### Por qué no hay build step

El panel usa React 18 UMD cargado desde CDN de unpkg. Esto permite modificar `panel.js` directamente en el servidor sin necesidad de Node.js, webpack, ni ninguna herramienta de compilación.

### Por qué el importador es progresivo (una nota por llamada)

Las llamadas a la IA pueden tardar 10-30 segundos cada una. El loop se delega al navegador: el JS llama 10 veces de forma secuencial con `er_import_demo_one`, permitiendo mostrar progreso y evitar timeouts del servidor.

### Por qué usar timedtext en lugar de la Captions API oficial

La YouTube Captions API oficial requiere OAuth 2.0. El endpoint `timedtext` es público, sin autenticación, y funciona con cualquier video que tenga subtítulos automáticos.

### Lógica de migración automática de keys

Si alguien guardó una key de OpenRouter (`sk-or-…`) en el campo de Anthropic, el backend lo detecta y migra automáticamente.

---

*Documentación generada desde el código fuente vigente. Actualizar cada vez que se modifique `_ACTIVO/plugin/el-rufino-panel.php` o `_ACTIVO/plugin/assets/panel.js`.*
