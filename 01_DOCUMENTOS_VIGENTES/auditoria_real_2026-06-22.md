# Auditoría Real — El Rufino
**Fecha:** 2026-06-22  
**Método:** Verificación en vivo (git log, WP REST API, HTML scraping, NIC.ar)  
**Roles:** A08-Stack + A01-SEO  

---

## Tabla comparativa

| # | Dato afirmado en docs/panel | Dato verificado | Fuente de verificación | Discrepancia |
|---|---|---|---|---|
| 1 | **32 commits** (panel v1.6, log línea 21) | **44 commits** | `git log --oneline \| wc -l` en EL_RUFINO | +12 commits sin registrar. Último: `fedb33c` 2026-05-21 14:43 |
| 2 | **27 commits** (CHANGELOG v1.5) | **44 commits** | mismo | +17 desde el registro v1.5 |
| 3 | Working tree limpio (implícito) | **Dirty** — 4 archivos borrados de `00_CONTEXTO_IA/`, `.gitignore` modificado | `git status` | Sin commit pendiente documentado |
| 4 | Categorías: **5/6 creadas · falta Rufino real** | **6/6 creadas** + Uncategorized (7 total) | `GET /wp-json/wp/v2/categories` | Rufino real EXISTS (id:14). Panel semáforo `categorias:ROJO` incorrecto → debería ser VERDE |
| 5 | **"corregir acento en Poder y gestión"** | Nombre en producción: **"Poder y gestión"** (tilde correcta, ó=ó) | API REST WP, campo `name` | Ya corregido o nunca estuvo mal en prod. No requiere acción |
| 6 | Schema: **ROJO** (semáforo panel v1.6) | **PRESENTE** — 2 instancias en HTML homepage | `curl https://elrufino.com.ar/ \| grep NewsMediaOrganization` | Panel semáforo `schema:ROJO` incorrecto → debería ser VERDE |
| 7 | Plugin activo en prod: **Control Panel v4.3** (project instructions) | **No verificable** sin SSH/FTP/WP admin | Namespace `royal-mcp/v1` visible en wp-json (sugiere plugin propio activo) | Versión exacta requiere acceso manual. `er-status` endpoint no devuelve respuesta pública |
| 8 | Plugin v8.1.2 activo en test (log panel) | **No verificable** desde este entorno | — | Registrado como dato histórico de sesión 3 (2026-04-18), no como estado actual |
| 9 | WordPress versión: no documentada | **No expuesta** | Headers HTTP + HTML (sin meta generator, sin X-WP-Version) | Hardening activo. PHP 8.3.30 / LiteSpeed confirmados |
| 10 | Child theme: activo (semáforo AMARILLO) | **el-rufino-theme v2.3.4** activo en producción | CSS link en HTML: `themes/el-rufino-theme/style.css?ver=2.3.4` | Parent: newsup v5.4.4. Semáforo debería ser VERDE |
| 11 | `elrufino.ar` — no registrado (semáforo ROJO) | **No verificable** desde este entorno | RDAP bloqueado por proxy sandbox, whois no disponible, NIC.ar client-rendered | Requiere verificación manual en nic.ar/whois o cliente whois externo |
| 12 | `elrufino.com.ar` — exp. EX-2026-36480557 vence 06/04/2027 | Sitio **activo y respondiendo** | `curl -I https://elrufino.com.ar/` — HTTP 200, LiteSpeed, PHP 8.3.30 | Vencimiento documental no verificable via WHOIS desde sandbox |

---

## Resumen de discrepancias críticas

### 🔴 Datos incorrectos en panel/docs que requieren corrección
- **Commits**: 44 (real) vs 32 (panel) vs 27 (CHANGELOG) — diferencia de +12/+17
- **Categorías**: 6/6 completas en prod. Semáforo `categorias:ROJO` → cambiar a VERDE
- **Schema**: Presente en prod (2 instancias). Semáforo `schema:ROJO` → cambiar a VERDE
- **Child theme**: Confirmado activo en prod v2.3.4. Semáforo `childTheme:AMARILLO` → revisar → probablemente VERDE

### 🟡 No verificables desde este entorno (requieren acción manual)
- Plugin versión exacta en producción → SSH/FTP Hostinger o WP admin
- Estado de `elrufino.ar` en NIC.ar → verificar en nic.ar/whois con cliente externo
- Vencimiento expediente `elrufino.com.ar` → panel Hostinger o NIC.ar autenticado

### 🟢 Confirmados correctos
- Sitio activo y respondiendo (elrufino.com.ar)
- API REST WordPress pública y funcional
- Schema NewsMediaOrganization presente (RankMath + plugin propio)
- PHP 8.3.30 / LiteSpeed

---

## Working tree — archivos pendientes de commit

```
M  .gitignore
D  00_CONTEXTO_IA/el-rufino_prompt-maestro_v1.6-VIGENTE.md
D  00_CONTEXTO_IA/el-rufino_repositorio-ia_v1.6-VIGENTE.md
D  00_CONTEXTO_IA/usina-de-ideas_agentes-ia_v1.6-VIGENTE.md
D  00_CONTEXTO_IA/usina-de-ideas_prompt-maestro_v1.6-VIGENTE.md
```
4 archivos eliminados de `00_CONTEXTO_IA/` y `.gitignore` modificado sin commit.  
**Acción sugerida:** revisar si la eliminación es intencional y hacer commit o revertir.

---

*Auditoría generada automáticamente · Herramienta: Claude Cowork · Sesión 2026-06-22*
