# EL RUFINO — LOG DE SESIONES

---

## SESIÓN 2026-07-12 — Incidente de seguridad: credencial FTP expuesta
**Contexto generado:** v1.8 (sin nueva versión de contexto)

### Detección
- Durante relevamiento de archivos sueltos en el repo, se detectó `.claude/settings.local.json` modificado con contraseña FTP del hosting (Hostinger, cuenta `u369796910.elrufino.com.ar`) en texto plano, embebida en comandos `curl` guardados como permisos.
- Verificado que la versión anterior del archivo ya estaba commiteada en el historial (commit `724de19`, 2026-06-26) — la contraseña vieja también quedaba expuesta ahí, ya pusheada a GitHub (repo privado).

### Respuesta
- Confirmado por el operador: contraseña FTP rotada en Hostinger. Tanto la vieja (en `724de19`) como la que estaba en el archivo quedan inválidas.
- `724de19` no se toca (reescribir historial queda para otra sesión, no urgente con la credencial ya rotada).
- `.claude/settings.local.json` sacado del tracking (`git rm --cached`, el archivo sigue en disco) y agregado a `.gitignore` — commit `ab14788` (2026-07-12 14:28).
- Verificado: 0 collaborators en el repo de GitHub, sin evidencia de acceso no autorizado.

### Otros cambios de esta sesión
- Identidad git del repo corregida: `user.email` local `elrufino@usina.ar` → `cristianobermeller@gmail.com` (no vinculado antes a ninguna cuenta real de GitHub).
- `PANEL_CONTROL_EL_RUFINO.html` (dashboard local de navegación del proyecto) creado y commiteado — `988f51c` (2026-07-12 14:29), ya con la identidad corregida.

---

## SESIÓN 2026-07-09 — Prompt-maestro actualizado + gitignore SYNC/
**Contexto generado:** v1.8 (sin nueva versión de contexto)

- `00_CONTEXTO_IA/el-rufino_prompt-maestro_v1.8-VIGENTE.md` actualizado con la estructura real de `home.php` v2.3.6 (sidebar: Lo más leído, Pilares editoriales, Seguinos/WhatsApp) y bump de versión de theme en la tabla de stack técnico — commit `91159b3` (13:40).
- `SYNC/` (workspace temporal de auditoría, no es entregable del proyecto) agregado a `.gitignore` — commit `a80e1ea` (13:40).

---

## SESIÓN 2026-07-06 — Fix submódulo roto de _ACTIVO/plugin + sidebar home v2.3.6
**Contexto generado:** v1.8 (sin nueva versión de contexto)

### Reconciliación de versión de theme (documentación vs. producción real)
- Detectado: producción servía theme v2.3.6, pero el doc VIGENTE seguía marcando v2.3.5.
- `el-rufino_tema-child_v2.3.5-VIGENTE.md` archivado (superado por v2.3.6 en producción) — `aeec7b3` (19:37).
- `el-rufino_theme-v2.3.6-deploy.md` commiteado (estaba pendiente desde el deploy real) — `7ae97c3` (19:38) — y promovido a VIGENTE — `9012b4c` (19:40).
- `el-rufino_theme-v2.3.5-deploy.md` (registro retroactivo del deploy v2.3.5 y el incidente de nav con bullets, con el fix en dos capas: `style.css` de producción + Custom CSS de WP) commiteado — `685cac4` (20:03).

### Limpieza de artifacts `.vs/`
- `.vs/` (artefactos de Visual Studio) agregado a `.gitignore` — `257cd65` (19:42).
- 12 archivos `.vs/` que ya estaban trackeados sacados del índice (siguen en disco) — `a82fdcc` (19:58).

### Sidebar home v2.3.6
- `home.php` y `style.css` actualizados: sidebar nuevo (widget Lo más leído por `comment_count`, widget Pilares editoriales con los 6 pilares, widget Seguinos/WhatsApp) y fix de los slugs de `er_pilares_todos()` que no coincidían con la decisión cerrada de pilares (`seguimiento-promesas`→`poder-y-gestion`, `contexto-datos`→`rufino-en-datos`, P05/P06) — `64932a0` (20:06).

### Fix de submódulo roto en `_ACTIVO/plugin`
- `_ACTIVO/plugin` era un gitlink (submódulo) sin `.gitmodules`, apuntando a un commit desactualizado; el repo anidado no tenía remoto propio, por lo que el trabajo de Royal MCP y archivos legacy vivía solo en este disco, sin backup remoto.
- Respaldo del historial completo del repo anidado en `_ARCHIVO/2026-07-06/plugin-historial-anidado.bundle` antes de tocar nada.
- Anidamiento eliminado, contenido fusionado como archivos normales del repo raíz (incluye el trabajo de Royal MCP y los archivos legacy ya archivados en `_ACTIVO/plugin/_ARCHIVO/2026-07-05/`) — `adf2914` (20:20).

---

## SESIÓN 2026-06-27/2026-06-28 — Archivado de docs de plugin/tema, limpieza de conflict markers
**Contexto generado:** v1.8 (sin nueva versión de contexto)

- Conflict markers sin resolver en `repositorio-ia v1.8` y `prompt-maestro v1.7 ARCHIVO` limpiados — `e532f2f` (2026-06-27 10:16).
- Docs de tracking de plugin v8.7.5 y tema v2.3.5 agregados; `_ACTIVO` sincronizado — `15c8e12` (2026-06-27 12:37).
- Doc de plugin v8.6.0 archivado a `_ARCHIVO/2026-06-27` — `7ca7c54` (2026-06-27 12:39).
- `el-rufino_tema-child_v2.3.4.md` archivado — tema-child v2.3.4 superado por v2.3.5, confirmado en producción — `17166fc` (2026-06-28 04:25).
- Deploy de theme v2.3.5 documentado, incluyendo el incidente de nav con bullets — `7f45690` (2026-06-28 13:03).

---

## SESIÓN 2026-06-09 — SEO + Formato HTML embebido
**Contexto generado:** v1.8

### SEO e Indexación
- Diagnóstico Search Console: 1 clic / 9 impresiones / posición media 49.8
- Home aparecía como "Próximamente" (dato viejo en caché Google)
- 8 URLs con noindex identificadas y resueltas:
  - HTTP sin redirect → Hostinger Force HTTPS ✅
  - www sin redirect → Hostinger nivel servidor ✅
  - Rank Math config global verificada como correcta ✅
  - Nota soja con noindex → era dato viejo (ya estaba indexada) ✅
  - rufino-en-datos → reindexación solicitada ⏳
  - 4 tags vacíos → toggle Rank Math correcto, se indexarán con contenido ✅
- `.htaccess` NO modificado

### Reformateo nota post 26 (soja)
- Contenido original: bloques Gutenberg nativos (`<!-- wp:paragraph -->`)
- Convertido a: bloque `<!-- wp:html -->` con HTML inline estilo editorial
- Elementos agregados:
  - Bajada con borde rojo izquierdo
  - Badge "Capa 1 — Lo que pasó" (fondo #1a1a1a)
  - Badge "Capa 2 — Lo que significa" (fondo #c0271b)
  - Tabla IPEC 6 filas con colores de variación
  - Iframe Google Maps zona Gran Rosario (-32.5,-60.7, z=9)
  - Párrafos con `<strong>` al inicio de cada idea
  - Cierre con pregunta abierta (retención valor en Gral. López)
- Modificado: 2026-06-09 17:04:09

### Estándar de formato establecido
Ver sección "FORMATO DE NOTA — ESTÁNDAR ACTIVO" en v1.8.
Referencia: post 63 (Barrio Pablo Vargas) como template original.

---

## SESIÓN 2026-06-01 — Single.php y Home.php
*(registrada en v1.7)*

---

## SESIÓN 2026-05-25 — Primera nota publicada
*(registrada en v1.7)*
- Post 63: Barrio Pablo Vargas — primera nota con formato HTML embebido
- iframe Google Maps, línea de tiempo 2018–2026
- Layout single.php: columna 70/30 con sidebar sticky

---

## SESIÓN 2026-05-21 — Setup inicial y plugin v8.7.5
*(registrada en v1.6/v1.7)*

---
