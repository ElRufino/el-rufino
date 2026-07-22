# El Rufino — Plugin Panel IA v8.6.0 · Documentación funcional operativa

> **Archivo de referencia vigente** — Generado el 2026-05-20 a partir del código fuente en `_ACTIVO/plugin/`.
> Fuente principal: `_ACTIVO/plugin/el-rufino-panel.php` + `_ACTIVO/plugin/assets/panel.js`
> ZIP de distribución: `_RELEASES/el-rufino-panel-v8.6.0.zip`

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

---

## 1. Arquitectura general

### Stack técnico

| Capa | Tecnología |
|---|---|
| Backend | PHP 8.x (WordPress plugin) |
| Frontend | React 18 (UMD, sin build step) cargado vía `wp_enqueue_script` desde CDN |
| Comunicación | WordPress AJAX (`admin-ajax.php`) con nonce de seguridad |
| APIs externas | Anthropic Claude API directo, OpenRouter API, YouTube Data API v3, YouTube timedtext público |
| Almacenamiento | WordPress `wp_options` (sin tablas propias en v8.6) |

### Filosofía de arquitectura

- **Sin build step**: `panel.js` es JavaScript puro (ES5+), React 18 se carga desde CDN de unpkg. No necesita Node, webpack ni compilación.
- **Pantalla completa inmersiva**: El plugin oculta completamente la UI de WordPress (adminbar, sidebar, footer) mediante CSS inyectado en `admin_head`. El panel ocupa `100vw × 100vh` con `position: fixed; inset: 0; z-index: 9999`.
- **Doble proveedor IA**: Soporta Anthropic directo o OpenRouter (permite usar cualquier modelo que OpenRouter disponibilice: Claude, GPT-4, Gemini, etc.). La selección es persistente en `wp_options`.
- **Single Page Application (SPA)**: Cinco pantallas navegables desde un sidebar fijo. El estado se mantiene en memoria mientras el usuario no recarga.

### Estructura de archivos del plugin

```
el-rufino-panel/
├── el-rufino-panel.php    ← Plugin principal (PHP, 1.265 líneas)
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
| `erData.version` | `8.6.0` |
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

Herramienta periodística para trackear compromisos públicos de funcionarios (intendente, concejales, etc.) con seguimiento de su estado. Reemplaza la tabla SQL de v4 usando `wp_options`.

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

Se despliega al hacer clic en "+ Nueva promesa":
- **Textarea**: Descripción de la promesa (requerido)
- **Fuente**: Quién prometió (texto libre)
- **Fecha**: Date picker, pre-cargado con la fecha de hoy
- Botón "Guardar" (deshabilitado si textarea vacío)

### Lista de promesas

Por cada promesa:
- Punto de color según estado
- Texto de la promesa (semibold)
- Fuente · Fecha (gris pequeño)
- `<select>` de estado (coloreado según estado actual) — al cambiar, guarda inmediatamente en el servidor Y actualiza el estado local en memoria (sin recargar la lista)

### Exportar CSV

Genera y descarga `promesas-el-rufino.csv` con columnas: `ID, Texto, Fuente, Fecha, Estado`. Usa `Blob` + `URL.createObjectURL` + click programático en `<a>`.

---

## 7. Pantalla 5 — Asistente IA

**Componente React**: `Asistente()`
**Ruta de navegación**: ícono 🤖 en sidebar

### Propósito

Herramienta de redacción asistida por IA específicamente diseñada para El Rufino. Permite generar un borrador de nota periodística combinando: datos de un video de YouTube + transcripción automática + imagen de referencia + palabras clave + pilar editorial.

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

- Campo de texto para pegar URL del video (formatos: `youtu.be/ID` o `youtube.com/watch?v=ID`)
- Botón "Buscar" (deshabilitado si no hay YouTube API Key configurada)
- Al buscar: llama a `er_yt_info` → YouTube Data API v3 (`/videos?part=snippet,contentDetails`)
- Si hay resultado, muestra debajo:
  - Thumbnail (90px de ancho)
  - Título del video
  - Canal · Fecha de publicación
  - Botón "Obtener subtítulos automáticos"

#### Sub-bloque — Subtítulos automáticos

Al hacer clic en "Obtener subtítulos automáticos":
- Llama a `er_yt_captions` con el `video_id`
- El servidor prueba el endpoint público `youtube.com/api/timedtext?v=ID&lang=LANG&fmt=json3` en orden de prioridad: `es → es-419 → es-AR → en`
- Si ningún idioma fijo funciona, obtiene la lista de tracks disponibles vía `?type=list` (XML) y prueba los idiomas encontrados
- El texto parseado aparece pre-cargado en el campo de transcripción (bloque 3)
- No requiere autenticación (endpoint público de YouTube)

#### Bloque 3 — Transcripción / contexto

Textarea editable (6 filas). Puede popularse:
- Automáticamente desde los subtítulos YouTube (bloque anterior)
- Manualmente pegando texto: declaraciones de fuentes, contexto adicional, notas del periodista

#### Bloque 4 — Imagen de referencia (opcional)

- Botón que activa `<input type="file" accept="image/*">` oculto
- Al seleccionar imagen: convierte a base64 via `FileReader`
- Muestra preview (50px alto)
- Solo funciona con Anthropic directo (vision multimodal). Con OpenRouter no se envía imagen (no todos los modelos soportan visión).

#### Bloque 5 — Configurar nota + Generar

- **Selector de pilar** (dropdown con los 5 pilares)
- **Palabras clave / contexto adicional** (campo de texto libre)
- **Botón "Generar borrador con IA"** (100% de ancho, 11px font, ancho completo)
  - Deshabilitado hasta que haya al menos uno: transcripción, info de video, o palabras clave
  - Al hacer clic: llama a `er_asistente_generar` con todos los datos
  - Muestra "Generando nota con IA…" durante la espera (15-30 seg estimados)

---

### Columna derecha — Borrador generado

#### Estado vacío (antes de generar)

Tarjeta centrada con emoji ✍️ y texto instructivo.

#### Estado cargando

Tarjeta centrada con "Generando nota…" y nota "Puede tardar 15-30 segundos".

#### Estado con borrador

Textarea editable (`height: 520px`, fuente monospace) con el texto generado. El usuario puede editarlo directamente antes de guardar.

**Toolbar del borrador**:
- Botón "Guardar en WordPress" → llama a `er_asistente_guardar`
  - Extrae el título del borrador buscando `**TÍTULO:** …` con regex
  - Guarda el contenido (saltos de línea → `<br>`) como post en borrador
  - Asigna la categoría del pilar seleccionado
- Badge verde "✓ Guardado como borrador en WordPress" tras guardar exitosamente
- Botón "Ver en WP" (aparece tras guardar) → enlace directo al editor del post

#### Estructura de nota que genera la IA

El prompt instruye este formato exacto:
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

- Saludo con nombre del usuario y fecha de hoy en español (`l j \d\e F \d\e Y` en español Argentina)
- Botón rojo "🚀 Abrir Panel IA" → enlace directo al panel
- Botón azul "✏️ Nueva entrada" → `post-new.php`
- Pie: "Panel IA v8.6.0 · Fase 2 en ejecución"

### Widget 2 — Estado del sitio

Grid 2×2 con 4 métricas:
- 📝 Publicadas (verde)
- 📄 Borradores (azul)
- 💬 Comentarios (violeta)
- 🔧 Actualizaciones (naranja si > 0, verde si = 0)

Cada métrica muestra número grande (1.4em, font-weight 900) y etiqueta pequeña.

### Widget 3 — Entradas por pilar

Barras horizontales proporcionales para cada uno de los 5 pilares:
- Nombre del pilar (140px, truncado con ellipsis)
- Barra de progreso proporcional al máximo
- Número a la derecha
- Todo el widget es un enlace a `edit.php?category_name=SLUG`

### Widget 4 — Últimas entradas

Lista de las 8 entradas más recientes (publicadas + borradores, ordenadas por fecha de modificación):
- Punto de color del pilar
- Título clicable → editor
- Badge "Pub" (verde) o "Bor" (naranja)
- "hace X tiempo" (tiempo humano relativo)

---

## 9. Registro de endpoints AJAX

Todos los endpoints usan `wp_ajax_{action}` (solo usuarios logueados). Todos verifican nonce `er_nonce`.

### `er_stats`

**Método**: POST  
**Retorna**: `{ published, drafts, comments, updates }`  
**Uso**: Dashboard bloque A

---

### `er_get_checklist`

**Método**: POST  
**Retorna**: Array de ítems `[{ id, texto, ok }]`  
**Default**: 8 ítems predefinidos si no hay nada guardado  
**Almacena en**: `wp_options.er_checklist`

### `er_save_checklist`

**Método**: POST · **Params**: `items` (JSON string del array completo)  
**Retorna**: `{ saved: true }`  
**Guarda en**: `wp_options.er_checklist`

---

### `er_save_key`

**Método**: POST · **Params**: `key` (API key Anthropic)  
**Retorna**: `{ saved: true }`  
**Guarda en**: `wp_options.er_claude_key`

### `er_key_status`

**Método**: POST  
**Retorna**: `{ configured: bool, masked: "sk-ant-api…", provider: "anthropic"|"openrouter" }`

### `er_save_orkey`

**Método**: POST · **Params**: `key`, `model`  
**Retorna**: `{ saved: true }`  
**Guarda en**: `wp_options.er_or_api_key`, `wp_options.er_or_model`

### `er_orkey_status`

**Método**: POST  
**Retorna**: `{ configured: bool, masked: "sk-or-v1-…", model: "anthropic/claude-sonnet-4" }`

### `er_save_provider`

**Método**: POST · **Params**: `provider` (`"anthropic"` | `"openrouter"`)  
**Retorna**: `{ provider }`  
**Guarda en**: `wp_options.er_ai_provider`

---

### `er_claude` (proxy IA general)

**Propósito**: Proxy genérico para llamadas IA desde el frontend (usado por el Asistente y potencial uso futuro)  
**Método**: POST  
**Params**: `messages` (JSON), `system` (string), `max_tokens` (int, default 1024)  

**Lógica de routing**:
1. Lee `er_ai_provider` de la DB
2. Si vacío, autodetecta por prefijo de key (`sk-or-` → OpenRouter)
3. Si Anthropic pero la key empieza con `sk-or-`, migra automáticamente a OpenRouter

**Ruta Anthropic**: POST a `https://api.anthropic.com/v1/messages` con modelo `claude-sonnet-4-6`, timeout 90s  
**Ruta OpenRouter**: POST a `https://openrouter.ai/api/v1/chat/completions` con modelo guardado, timeout 90s; el system se agrega como primer mensaje de rol `"system"`  
**Retorna**: `{ content: "texto generado" }`

---

### `er_import_demo_info`

*(Declarado en el código pero el JS actual no lo usa)* — Endpoint informativo de cantidad de notas disponibles.

### `er_import_demo_one`

**Propósito**: Genera e inserta UNA nota demo (usada secuencialmente por el Dashboard)  
**Método**: POST · **Params**: `index` (0-9)  
**Flujo**:
1. Valida que `index` esté en rango 0-9
2. Llama a la IA con el prompt específico de la nota
3. Convierte Markdown a HTML (negritas `**...**` → `<strong>`, cada línea en `<p>`)
4. Crea post en WordPress como borrador con categoría del pilar
5. Descarga imagen temática de Unsplash y la asigna como imagen destacada
6. **Retorna**: `{ index, titulo, post_id, imagen: bool }`

### `er_import_demo` (legacy)

Endpoint anterior que hacía las 10 notas en una sola llamada. Aún registrado para compatibilidad pero el JS actual no lo invoca (usa el progresivo `er_import_demo_one`).

---

### `er_get_promesas`

**Método**: POST  
**Retorna**: Array de promesas guardadas  
**Lee de**: `wp_options.er_promesas`

### `er_save_promesa`

**Método**: POST · **Params**: `texto`, `fuente`, `fecha`  
**Estructura que guarda**: `{ id: uniqid(), texto, fuente, fecha, estado: "pendiente", created: timestamp }`  
**Agrega al array en**: `wp_options.er_promesas`

### `er_update_promesa`

**Método**: POST · **Params**: `id`, `estado`  
**Actualiza**: solo el campo `estado` de la promesa con ese `id`

### `er_export_promesas`

**Método**: POST  
**Retorna**: `{ csv: "string CSV" }` con columnas `ID,Texto,Fuente,Fecha,Estado`  
**El JS descarga**: `promesas-el-rufino.csv` via Blob

---

### `er_save_ytkey`

**Método**: POST · **Params**: `key` (YouTube Data API key)  
**Guarda en**: `wp_options.er_yt_api_key`

### `er_ytkey_status`

**Método**: POST  
**Retorna**: `{ configured: bool, masked: "AIzaSy…" }`

### `er_yt_info`

**Propósito**: Obtener metadata de un video de YouTube  
**Método**: POST · **Params**: `url` (URL completa del video)  
**Extrae video_id** con regex (soporta `youtu.be/ID`, `youtube.com/watch?v=ID`, `youtube.com/embed/ID`, `youtube.com/v/ID`)  
**Llama a**: `https://www.googleapis.com/youtube/v3/videos?part=snippet,contentDetails&id=VIDEO_ID&key=YT_KEY`  
**Retorna**: `{ video_id, titulo, descripcion, canal, fecha, thumbnail }` (thumbnail de resolución `medium`)

### `er_yt_captions`

**Propósito**: Obtener transcripción automática de YouTube sin API key  
**Método**: POST · **Params**: `video_id`  
**Estrategia**: Endpoint público `youtube.com/api/timedtext` (no requiere autenticación, funciona con subtítulos automáticos generados por YouTube)  
**Orden de idiomas**: `es → es-419 → es-AR → en → idiomas disponibles del video`  
**Si ninguno funciona**: consulta `?type=list` para obtener XML con tracks disponibles y los prueba  
**Parsing**: `er_parse_timedtext_json3()` — concatena segmentos `segs[].utf8`, deduplica, limpia espacios  
**Retorna**: `{ transcripcion: "texto completo", fuente: "timedtext-es" }`  
**Error si no hay subtítulos**: mensaje con instrucciones para activarlos en YouTube Studio

### `er_asistente_generar`

**Propósito**: Generar borrador de nota periodística combinando todos los contextos disponibles  
**Método**: POST  
**Params**: `pilar`, `keywords`, `transcripcion`, `video_titulo`, `video_desc`, `video_canal`, `video_fecha`, `imagen_b64` (opcional), `imagen_type`  
**Max tokens**: 2000  
**Timeout**: 120s  
**Multimodal** (solo Anthropic directo): Si se envía `imagen_b64`, construye mensaje con contenido mixto `[{type:"image",...}, {type:"text",...}]`  
**Retorna**: `{ borrador: "texto markdown estructurado" }`

### `er_asistente_guardar`

**Propósito**: Crear post en WordPress a partir del borrador generado  
**Método**: POST · **Params**: `titulo`, `contenido`, `pilar_slug`  
**Extrae título**: el JS usa regex `/**T[IÍ]TULO:\** (.+)/` sobre el borrador antes de llamar  
**Sanitiza contenido**: `wp_kses_post()` (permite HTML seguro)  
**Crea post**: `draft`, autor = usuario actual, categoría = pilar  
**Retorna**: `{ post_id, edit_url }` — el JS usa `edit_url` para mostrar el botón "Ver en WP"

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

Los colores se usan consistentemente en todo el panel: barras de Inteligencia, puntos en Producción, puntos en el escritorio WP, cards del Asistente, etc.

---

## 11. Schema.org NewsMediaOrganization

El plugin inyecta JSON-LD en el `<head>` de **todas las páginas públicas** del sitio:

```json
{
  "@context": "https://schema.org",
  "@type": "NewsMediaOrganization",
  "name": "El Rufino",
  "url": "https://elrufino.ar",
  "logo": { "@type": "ImageObject", "url": "…/cropped-logo-el-rufino-blanco-1-300x113.png", "width": 300, "height": 113 },
  "foundingDate": "2026",
  "inLanguage": "es-AR",
  "areaServed": [
    { "@type": "City", "name": "Rufino" },
    { "@type": "State", "name": "Santa Fe" },
    { "@type": "Country", "name": "Argentina" }
  ],
  "sameAs": ["https://facebook.com/elrufino", "https://instagram.com/elrufino"]
}
```

En páginas de nota individual (`is_single()`), además inyecta un segundo script JSON-LD tipo `NewsArticle` con:
- `headline`, `description` (excerpt)
- `datePublished`, `dateModified` (formato ISO 8601)
- `articleSection` (nombre de la categoría)
- `author` (nombre del autor WP)
- `publisher` (el objeto NewsMediaOrganization anterior)
- `image` (imagen destacada, si existe)

---

## 12. Seguridad y hardening

### XML-RPC deshabilitado

```php
add_filter('xmlrpc_enabled', '__return_false');
```

Bloquea el vector de ataque XML-RPC de WordPress completamente.

### Redirección HTTPS forzada

```php
add_action('init', function () {
    if (!is_ssl() && !is_admin() && php_sapi_name() !== 'cli') {
        wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301);
        exit;
    }
});
```

Redirige cualquier request HTTP a HTTPS (301 permanente). Solo aplica al frontend, no al admin ni a CLI.

### Nonce en todos los endpoints AJAX

Todos los `wp_ajax_*` usan `check_ajax_referer('er_nonce', 'nonce')` como primera línea — aborta si el nonce es inválido o expirado.

### Sanitización de inputs

- Textos: `sanitize_text_field()`
- Textareas: `sanitize_textarea_field()`
- Contenido HTML: `wp_kses_post()`
- URLs: se validan con regex o `sanitize_text_field`
- Integers: cast explícito a `(int)`
- JSON: `json_decode(stripslashes(...))`

### Aislamiento del panel

- `defined('ABSPATH') || exit` al inicio del PHP
- El menú requiere `manage_options` capability (solo administradores)
- Assets y estilos del panel solo se cargan en `hook === 'toplevel_page_el-rufino-panel'`

---

## 13. Variables y opciones en base de datos

Todas las opciones se guardan en `wp_options` (no se crean tablas propias en v8.6).

| Option key | Tipo | Descripción | Default |
|---|---|---|---|
| `er_checklist` | array | Estado de los 8 ítems del checklist | 8 ítems con `ok: false` |
| `er_claude_key` | string | API key de Anthropic (`sk-ant-…`) | `''` |
| `er_or_api_key` | string | API key de OpenRouter (`sk-or-…`) | `''` |
| `er_or_model` | string | Modelo seleccionado para OpenRouter | `'anthropic/claude-sonnet-4'` |
| `er_ai_provider` | string | Proveedor activo: `'anthropic'` o `'openrouter'` | `''` (autodetecta) |
| `er_promesas` | array | Lista de promesas con id, texto, fuente, fecha, estado | `[]` |
| `er_yt_api_key` | string | YouTube Data API v3 key (`AIzaSy…`) | `''` |

### Constantes PHP definidas

| Constante | Valor |
|---|---|
| `ER_VERSION` | `'8.6.0'` |
| `ER_PLUGIN_URL` | URL absoluta del directorio del plugin |
| `ER_PLUGIN_DIR` | Path absoluto del directorio del plugin |

---

## Notas operativas y decisiones de diseño

### Por qué no hay build step

El panel usa React 18 UMD cargado desde CDN de unpkg. Esto permite modificar `panel.js` directamente en el servidor sin necesidad de Node.js, webpack, ni ninguna herramienta de compilación. La desventaja es el peso del CDN y que no hay TypeScript ni JSX. El archivo usa `React.createElement()` directamente (abreviado como `h()`).

### Por qué el importador es progresivo (una nota por llamada)

Las llamadas a la IA pueden tardar 10-30 segundos cada una. Hacer 10 en serie en una sola request PHP excedería fácilmente el timeout del servidor. La solución v8.6 delega el loop al navegador: el JS llama 10 veces de forma secuencial con `er_import_demo_one`, lo que permite mostrar progreso, no hay timeout del servidor, y si una falla el resto continúa.

### Por qué usar timedtext en lugar de la Captions API oficial

La YouTube Captions API oficial requiere OAuth 2.0 (no sirve API key simple) y falla con videos de otros canales. El endpoint `timedtext` es público, sin autenticación, y funciona con cualquier video que tenga subtítulos automáticos generados por YouTube. La única limitación es que no funciona con videos sin subtítulos.

### Por qué Unsplash en lugar de Picsum para imágenes demo

Las versiones iniciales usaban Picsum (semillas fijas por pilar). La v8.6 migró a Unsplash con queries temáticas (`soybean farm argentina`) para imágenes más representativas del contenido. La función `er_attach_unsplash_image()` usa `source.unsplash.com` (random por query), lo que significa que las imágenes no son reproducibles entre ejecuciones.

### Lógica de migración automática de keys

Si alguien guardó una key de OpenRouter (`sk-or-…`) en el campo de Anthropic, el backend lo detecta y migra automáticamente: cambia el proveedor a OpenRouter y, si no había key de OR, copia la clave al campo correcto. Esto evita errores silenciosos cuando el usuario configura mal.

---

*Documentación generada desde el código fuente vigente. Actualizar cada vez que se modifique `_ACTIVO/plugin/el-rufino-panel.php` o `_ACTIVO/plugin/assets/panel.js`.*
