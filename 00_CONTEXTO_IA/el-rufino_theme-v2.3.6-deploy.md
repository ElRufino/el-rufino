# EL RUFINO — Theme v2.3.6 · Tracking de deploy

**Fecha de deploy:** 2026-06-29
**Estado en producción:** ACTIVO ✅
**Archivo fuente:** `_ACTIVO\theme\el-rufino-theme-v2.3.5\` (mismo dir, versión bumpeada)
**Deploy method:** Ace Editor (File Browser v2.63.2-h2) — PUT directo, no ZIP

---

## Cambios respecto a v2.3.5

### Archivos modificados

| Archivo | Cambio principal |
|---|---|
| `style.css` | Version bump 2.3.5 → 2.3.6; bloque LAYOUT DOS COLUMNAS + SIDEBAR; widgets; badges |
| `home.php` | Layout er-layout (grid 2 col); función er_pilares_todos(); query $q_mas_leido; aside sidebar con 3 widgets |

### Archivos sin cambios
`header.php`, `footer.php`, `single.php`, `functions.php`

---

## Nuevas clases CSS

- `.er-layout` — CSS Grid `1fr 280px`, max-width 1200px
- `.er-layout-main` / `.er-sidebar` — columnas del grid
- `.er-widget` / `.er-widget-title` / `.er-widget-list` / `.er-widget-item` — widgets sidebar
- `.er-pilares-list` / `.er-pilar-link` / `.er-pilar-dot` — pilares editoriales con dot de color
- `.er-widget-wa` / `.er-widget-wa-btn` — widget WhatsApp verde (#25d366)
- `.er-badge-breaking` / `.er-badge-promesa` / `.er-badge-verificada` — badges de contenido
- `@media (max-width: 1023px)` → grid-template-columns: 1fr (sidebar debajo)

## Nuevas funciones PHP

- `er_pilares_todos()` — array de 6 pilares con slug, nombre, color y URL dinámica via get_term_by()
- `$q_mas_leido` — WP_Query orderby comment_count DESC, 5 posts

## Sidebar — 3 widgets

1. **Lo más leído** — 5 artículos por comment_count
2. **Pilares editoriales** — 6 pilares con dot de color
3. **Seguinos** — CTA WhatsApp → wa.me/5493382511670

---

## Verificación visual en producción

- ✅ Nav horizontal sin bullets
- ✅ Layout dos columnas desktop
- ✅ Widget Lo más leído (5 artículos)
- ✅ Widget Pilares con dots (rojo, azul, verde, ámbar, violeta, teal)
- ✅ Widget WhatsApp con botón verde
- ✅ Responsive: sidebar debajo en ≤1023px

---

## Nota técnica — problema de deploy y solución

**Problema:** ZIP buildado desde `/tmp/` tenía archivos cacheados de sesión anterior (mount Linux stale).
**Solución:** Usar el editor Ace del File Browser directamente:
1. Navegar a `srv1311-files.hstgr.io/.../el-rufino-theme/style.css`
2. `editor.setValue(newContent, -1)` via `javascript_tool`
3. Click Save (ref del botón en la toolbar)
**Regla:** Para deploys de archivos individuales, siempre Ace Editor > ZIP extract.
