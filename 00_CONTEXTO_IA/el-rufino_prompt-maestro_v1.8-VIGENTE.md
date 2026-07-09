# EL RUFINO — CONTEXTO IA v1.8-VIGENTE
**Última actualización:** 2026-06-09
**Reemplaza:** v1.7

---

## IDENTIDAD DEL PROYECTO

**El Rufino** — medio digital local de Rufino, Santa Fe, Argentina.
**Claim:** "Lo que pasa y lo que significa"
**Dominio producción:** elrufino.com.ar (Hostinger · WordPress · DNS delegado)
**Comunidad:** ~19.211 habitantes

---

## STACK TÉCNICO ACTIVO

| Componente | Estado |
|---|---|
| WordPress 7.0 | Producción |
| Child theme Newsup v2.3.6 (slug: el-rufino-theme) | Activo |
| Plugin El Rufino Panel IA v8.7.5 | Activo |
| Royal MCP v1.4.23 | Activo en producción |
| Rank Math SEO v1.0.271.1 | Activo |
| LiteSpeed Cache v7.8.1 | Activo |
| Meta Pixel / Facebook Comments | Activos |

**Plugin Royal MCP endpoint:** `https://elrufino.com.ar/wp-json/royal-mcp/v1/mcp`
**Plugin panel IA:** 5 pantallas (Dashboard / Producción / Inteligencia / Seguimiento / Asistente)

---

## TEMA Y ESTILOS

**Paleta activa (Paleta B):**
| Nombre | Hex | Uso |
|---|---|---|
| Terracota Cívico | #b55233 | P03 Barrio |
| Azul Ruta | #2f6484 | P06 Datos |
| Verde Campo | #617a45 | P02 Campo (verde) |
| Ámbar Alerta | #c58a2b | P04 Generación |
| Tinta Sur | #1f2a30 | P05 Poder |
| Papel Rufino | #f5f1e8 | Fondo |
| Rojo brand principal | #c0271b | Acento / badges |
| Negro | #1a1a1a | Texto principal |

**Tipografía:**
- Títulos: Playfair Display (local: `wp-content/themes/el-rufino-theme/fonts/`)
- Cuerpo: Source Serif 4 (local + Google Fonts fallback)
- UI/badges: Arial/sans-serif

**Variables CSS del tema:**
```css
--papel: #f5f1e8
--tinta: #1a1a1a
--terra: #c0271b
--pilar: #2d5f8a (P03 actual)
```

---

## PILARES EDITORIALES

| Código | Nombre | Slug | Color |
|---|---|---|---|
| P01 | Rufino real | rufino-real | #c0271b |
| P02 | El campo habla | el-campo-habla | #4a7c59 |
| P03 | Barrio a barrio | barrio-a-barrio | #b55233 |
| P04 | Generación Rufino | generacion-rufino | #c58a2b |
| P05 | Poder y gestión | poder-y-gestion | #1f2a30 |
| P06 | Rufino en datos | rufino-en-datos | #2f6484 |

**Regla madre:** toda pieza tiene dos capas — lo que pasó + lo que significa.

---

## FORMATO DE NOTA — ESTÁNDAR ACTIVO (desde sesión 2026-06-09)

El formato estándar usa **HTML embebido dentro de un bloque `<!-- wp:html -->`**. No bloques Gutenberg nativos para el cuerpo de la nota.

### Estructura del HTML embebido:

```html
<!-- wp:html -->
<!-- 1. BAJADA: párrafo de apertura en cursiva con borde rojo izquierdo -->
<p style="font-size:1.05rem;font-style:italic;color:#3a3a3a;line-height:1.6;
   margin-bottom:0.5rem;border-left:3px solid #c0271b;padding-left:1rem;">
   [bajada]
</p>

<!-- 2. BADGE CAPA 1 -->
<span style="display:inline-block;font-family:Arial,sans-serif;font-size:0.68rem;
   font-weight:bold;letter-spacing:0.08em;text-transform:uppercase;
   padding:3px 9px;margin-bottom:0.8rem;background:#1a1a1a;color:#fff;">
   Capa 1 — Lo que pasó
</span>

<!-- 3. PÁRRAFOS CAPA 1: p con strong + line-height:1.75 -->
<p style="margin-bottom:1rem;line-height:1.75;"><strong>[lead]</strong> [desarrollo]</p>

<!-- 4. ELEMENTO VISUAL: tabla de datos, cronología o iframe mapa -->
<div style="background:#f5f0e8;border-left:3px solid #c0271b;padding:1.2rem 1.2rem 0.8rem;
   margin:1rem 0;font-family:Arial,sans-serif;">
  <p style="font-size:0.68rem;font-weight:bold;letter-spacing:0.1em;text-transform:uppercase;
     color:#c0271b;margin-bottom:1rem;">[título tabla/datos]</p>
  <table style="width:100%;border-collapse:collapse;font-size:0.85rem;">
    <tr style="background:#1a1a1a;color:#f5f0e8;">
      <th style="padding:8px 10px;text-align:left;font-weight:400;letter-spacing:0.06em;
         font-size:0.7rem;text-transform:uppercase;">Indicador</th>
      <th style="padding:8px 10px;text-align:right;...">Valor</th>
      <th style="padding:8px 10px;text-align:right;...">Variación</th>
    </tr>
    <!-- filas alternadas: faf8f4 / blanco -->
  </table>
  <p style="font-size:0.7rem;color:#888;margin-top:0.6rem;margin-bottom:0;">Fuente: ...</p>
</div>

<!-- 5. IFRAME GOOGLE MAPS (cuando aplica) -->
<div style="position:relative;width:100%;height:200px;overflow:hidden;margin:0 0 0.4rem;">
  <iframe src="https://maps.google.com/maps?q=[lat],[lng]&z=[zoom]&output=embed"
    width="100%" height="230" style="border:none;margin-top:-30px;display:block;"
    loading="lazy"></iframe>
</div>
<p style="font-family:Arial,sans-serif;font-size:0.75rem;color:#888;
   margin-bottom:1rem;text-align:center;">[epígrafe mapa]</p>

<!-- 6. BADGE CAPA 2 -->
<span style="display:inline-block;font-family:Arial,sans-serif;font-size:0.68rem;
   font-weight:bold;letter-spacing:0.08em;text-transform:uppercase;
   padding:3px 9px;margin-bottom:0.8rem;background:#c0271b;color:#fff;">
   Capa 2 — Lo que significa
</span>

<!-- 7. PÁRRAFOS CAPA 2 -->
<p style="margin-bottom:1rem;line-height:1.75;"><strong>[análisis]</strong> [desarrollo]</p>
<p style="margin-bottom:1.5rem;line-height:1.75;"><strong>[cierre/pregunta]</strong></p>
<!-- /wp:html -->
```

### Notas de aplicación:
- Tablas de datos: fondo #f5f0e8, border-left 3px #c0271b, header #1a1a1a
- Filas alternas: blanco / #faf8f4
- Variaciones positivas: color #2f7a3a · negativas: color #c0271b
- Cronologías: misma estructura de tabla con columna fecha en #c0271b bold
- Iframe maps: `q=[lat],[lng]&z=[zoom]&output=embed`, height 200px contenedor, 230px iframe, margin-top -30px
- Colores variación: verde #2f7a3a (positivo), rojo #c0271b (negativo), gris #888 (neutro)

---

## NOTAS PUBLICADAS EN PRODUCCIÓN

| ID | Título corto | Pilar | Estado |
|---|---|---|---|
| 63 | Barrio Pablo Vargas — calles 2024/2026 | P03 | Publicado — formato HTML embebido |
| 26 | Soja Santa Fe 2025 — récord volumen, precio bajo | P02+P06 | Publicado — **reformateado 2026-06-09** con HTML embebido |

**Nota post 26 — elementos incorporados en el reformateo:**
- Bajada con borde rojo
- Badge Capa 1 / Capa 2
- Tabla IPEC con 6 filas (volumen, valor, precio/t, biodiesel valor, biodiesel volumen, manufactura vs. poroto)
- Iframe Google Maps zona Gran Rosario (-32.5,-60.7, z=9)
- Cierre con pregunta abierta sobre retención de valor en Gral. López

---

## SEO / INDEXACIÓN — ESTADO 2026-06-09

**Trabajo realizado:**

| Problema | Solución | Estado |
|---|---|---|
| HTTP → HTTPS sin redirect | Force HTTPS activo en Hostinger hPanel | ✅ Resuelto |
| www → non-www sin redirect | Hostinger lo maneja a nivel servidor | ✅ Resuelto |
| Rank Math config global | Índice marcado, noindex desmarcado en meta global, home, categorías | ✅ Confirmado |
| Nota soja con noindex | Era dato viejo de Search Console (mayo 2026 ya estaba indexada) | ✅ Sin acción |
| rufino-en-datos noindex | Reindexación solicitada en Search Console | ⏳ Pendiente rastreo Google |
| 4 tags vacíos (noindex) | Toggle "Archivos vacíos noindex" activo en Rank Math — correcto, se indexarán con contenido | ✅ Dejar |

**Métricas actuales (Search Console):**
- 1 clic / 9 impresiones en 3 meses · Posición media 49.8
- Home indexada con título correcto
- Nota soja: indexada ✅

**Nota:** `.htaccess` no modificado — Hostinger maneja redirects a nivel servidor.

---

## ESTRUCTURA SINGLE.PHP

Layout columna 70/30 con sidebar sticky:
- Columna principal: imagen destacada, titular, meta, cuerpo (wp:html), tags, compartir, notas relacionadas
- Sidebar: Lo más leído, banner WA, últimas noticias
- Fecha en español, tiempo lectura, A+/A-, TTS, Facebook Comments, sharing, autor

---

## ESTRUCTURA HOME.PHP (v2.3.6)

Layout: `er-layout` CSS Grid `1fr 280px` (dos columnas). Colapsa a 1fr en ≤1023px.

**Columna principal (er-layout-main):**
- Nota destacada: banner T3A imagen 44% / contenido 56%, altura 148px
- Grilla secundaria: 3 columnas, imagen 72px, etiqueta pilar, título, fecha + tiempo

**Sidebar (er-sidebar, 280px):**
- Widget Lo más leído (5 posts por comment_count)
- Widget Pilares editoriales (6 pilares con dot de color)
- Widget Seguinos / WhatsApp → wa.me/5493382511670

**Funciones PHP nuevas:** `er_pilares_todos()`, `$q_mas_leido`

---

## COBERTURAS ACTIVAS (junio 2026)

1. **Terminal ómnibus RN7** — financiamiento provincial (Ministerio OP/Enrico), $720M, UTE Designio Hábitat + Hormigonera Litorrallas, tierra municipal Ord. 3402/2024. Borrador V2 pendiente.
2. **25 viviendas (misma UTE)** — disputa interna Unidos: Lattanzi vs. Carballeira (señal candidatura 2027). Pendiente publicar.
3. **Acuerdo MSU Agro/LALCEC** — diferido hasta obtener número de ordenanza.

---

## COLABORADORES

| Nombre | Rol |
|---|---|
| Fabián Longo | "El Diferente" |
| Adriana Giménez | "Voces, otra mirada" — columna perspectiva de género |
| Bárbara Gorosito | Potencial — fotógrafa/creadora de contenido local |
| Carlos Borgna | Noticiero Cumpa — referente metodológico, contacto FESTRAM/Red MERCOSUR |

---

## CUENTAS Y ACCESOS

- **WordPress admin:** cristianobermeller@gmail.com
- **Facebook App ID:** 1314916506819026 (dominio elrufino.com.ar)
- **Facebook página:** medioelrufino@gmail.com
- **Cuenta operadora IA:** rufino.paspatria@gmail.com (Cuenta 9 de 9)
- **9 cuentas Google activas**

---

## RUTAS LOCALES (Windows)

```
Raíz proyectos:    F:\HERRAMIENTAS DE IA\PROYECTOS\
El Rufino activo:  F:\HERRAMIENTAS DE IA\PROYECTOS\EL_RUFINO\_ACTIVO\
Plugin:            F:\HERRAMIENTAS DE IA\PROYECTOS\EL_RUFINO\_ACTIVO\plugin\
Releases:          F:\HERRAMIENTAS DE IA\PROYECTOS\EL_RUFINO\_RELEASES\
Contexto IA:       F:\HERRAMIENTAS DE IA\PROYECTOS\EL_RUFINO\00_CONTEXTO_IA\
rclone:            F:\Downloads\rclone-v1.73.5-windows-amd64\...
```

**Fuente de verdad:** siempre disco local. GitHub puede quedar desactualizado.

---

## PENDIENTES FASE 2

- [ ] Registrar `elrufino.ar` en NIC Argentina
- [ ] Lanzar canal WhatsApp (0/500 suscriptores)
- [ ] Financiar créditos Anthropic API en producción
- [ ] Actualizar docs GitHub a v1.8 (este archivo)
- [ ] Completar borrador V2 cobertura terminal ómnibus
- [ ] Publicar cobertura viviendas (ángulo Lattanzi/Carballecha)
- [ ] Esperar número de ordenanza para nota MSU Agro/LALCEC
- [ ] Verificar en ~1 semana indexación de rufino-en-datos en Search Console

---

## REGLAS OPERATIVAS

- No reabrir decisiones cerradas · no suponer · confirmar antes de ejecutar en producción
- Nomenclatura: sin espacios, guiones bajos, prefijos numéricos
- Nunca borrar: mover a `_ARCHIVO` con fecha
- Código completo y listo para usar, no fragmentos
- CSS en child theme Newsup: requiere `!important` para overrides
- ZIP para Hostinger: generar con barras `/` no `\`
- Claude Code: invocar con `npx @anthropic-ai/claude-code` (PATH no persistente)
- `git safe.directory` requiere config global por diferencia de usuario Windows
- Royal MCP no da acceso de lectura a archivos PHP del servidor → usar FTP o hPanel para backup

---

## HERRAMIENTAS

| Herramienta | Uso |
|---|---|
| WordPress + Royal MCP | Operaciones CMS |
| Claude Code (`npx @anthropic-ai/claude-code`) | Filesystem, rebuild plugin, commits |
| GitHub (org ElRufino, repo `el-rufino`) | Versionado |
| Hostinger hPanel | Backup PHP, operaciones que MCP no puede |
| rclone (`gdrive:`) | Sync local ↔ Google Drive |
| XAMPP | SISMUIF local |
| PowerShell | Operaciones de archivo |

**Fuentes editoriales clave:** IPEC, MATE, CEPA, IDE Agro, MAGYP Monitor, infoleg.gob.ar, Boletín Oficial Santa Fe, farmaciasrufino.com, Museo de Rufino

---

*Versión generada automáticamente — sesión 2026-06-09 · El Rufino / USINA DE IDEAS*
