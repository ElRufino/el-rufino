# EL RUFINO — Theme v2.3.5 · Tracking de deploy

**Fecha de deploy:** 2026-06-28
**Estado en producción:** ACTIVO ✅
**Archivo fuente:** `_ACTIVO\theme\el-rufino-theme-v2.3.5\`
**ZIP de deploy:** `_ACTIVO\theme\el-rufino-theme-v2.3.5-deploy.zip` (16.41 KiB, en Hostinger `/themes/`)

---

## Cambios respecto a v2.3.4

### Archivos modificados

| Archivo | Cambio principal |
|---|---|
| `style.css` | Version bump 2.3.4 → 2.3.5; agregado bloque NAV HORIZONTAL al final (ver nota abajo) |
| `single.php` | Layout two-column, TTS, controles de font size, comentarios Facebook |
| `functions.php` | Ajustes menores (diferencia de 1 línea vs v2.3.4) |
| `header.php` | Sin cambios respecto a v2.3.4 |
| `footer.php` | Sin cambios respecto a v2.3.4 |
| `home.php` | Sin cambios respecto a v2.3.4 |

### Archivos sin cambios
`header.php`, `footer.php`, `home.php` — port literal de v2.3.4.

---

## Incidente durante deploy: nav con bullets

### Síntoma
Después del deploy del ZIP v2.3.5, el menú de navegación principal renderizaba como lista vertical con bullets en lugar de nav horizontal.

### Causa raíz
El bloque CSS `.er-nav-list` que existía en v2.3.4 (líneas 467-480 de ese style.css) no estaba en el v2.3.5 inicial. El bloque fue identificado comparando ambas versiones.

### Fix aplicado

**Capa 1 — `style.css` de producción** (editado directo via Hostinger File Manager / Ace Editor):
```css
/* ================================================================
   NAV HORIZONTAL — portado desde v2.3.4
================================================================ */
.er-nav ul,
.er-nav .er-nav-list {
  display: flex !important;
  flex-direction: row !important;
  flex-wrap: wrap !important;
  list-style: none !important;
  margin: 0 !important;
  padding: 0 !important;
}
.er-nav ul li {
  display: block !important;
}
```
Guardado via PUT request confirmado (HTTP 200). Archivo: 594 líneas.

**Capa 2 — WordPress Custom CSS** (almacenado en DB, post_id 137):
Mismo bloque agregado al Custom CSS de WP Customizer. Razón: LiteSpeed CSS optimization sirve un CSS combinado cacheado; el Custom CSS se inyecta como `<style>` en el `<head>` del HTML, bypasseando completamente esa optimización.

### Por qué el fix vive en dos lugares
| Capa | Dónde vive | Ventaja |
|---|---|---|
| `style.css` | Archivo en servidor | Fuente canónica, se incluye en próximos ZIPs |
| Custom CSS WP | Base de datos WP (`wp_posts`, `post_type=custom_css`) | Bypasea LiteSpeed CSS combine; persiste a través de deploys de archivos |

El Custom CSS NO se pierde al actualizar el theme porque vive en la DB, no en archivos. Persiste mientras el slug del tema sea `el-rufino-theme`.

### Diagnóstico de LiteSpeed
LiteSpeed CSS/JS optimization estaba combinando los stylesheets en un archivo cacheado. Después de editar `style.css`, el cache no se actualizó correctamente pese a ejecutar "Purge All CSS/JS" y "Purge All" desde el admin bar. La solución fue inyectar el CSS directamente via Custom CSS de WP.

---

## Estado de archivos clave en producción

```
/wp-content/themes/el-rufino-theme/
├── style.css       14.12 KiB  ← v2.3.5 con nav fix (594 líneas)
├── functions.php   13.98 KiB
├── header.php        987 B    ← marcador de integridad del ZIP
├── home.php         6.43 KiB
├── single.php      14.04 KiB
└── footer.php       4.03 KiB
```

Custom CSS WP: 3188 bytes (post_id 137, slug `el-rufino-theme`).

---

## Verificación visual (2026-06-28)

- ✅ Nav horizontal sin bullets
- ✅ Layout home T3A: banner destacado + grilla 3 columnas
- ✅ Masthead rojo con logo + botón WhatsApp
- ✅ Ticker de noticias animado
- ✅ Footer institucional en 4 columnas

---

## Historial de versiones del theme

| Versión | Fecha | Estado |
|---|---|---|
| v2.3.4 | Pre 2026-06-28 | Archivado en `_ARCHIVO\2026-06-28\` |
| v2.3.5 | 2026-06-28 | **ACTIVO en producción** |

---

## Notas para próximo deploy

1. Antes de generar el ZIP de v2.3.x+1, verificar que el bloque NAV HORIZONTAL esté en `style.css` (buscar `.er-nav-list`)
2. El ZIP debe tener estructura `el-rufino-theme/` (directorio raíz dentro del ZIP)
3. Usar pipeline Read→Write→bash para evitar caché del mount Linux (problema conocido con `mnt/EL_RUFINO/`)
4. Después de subir ZIP: siempre hacer Purge All desde LiteSpeed admin bar
5. El Custom CSS de WP actúa como safety net — no remover
