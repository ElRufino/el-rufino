# CHANGELOG — EL RUFINO
## Registro de normalizacion y reorganizacion

**Ruta de destino:** `F:\HERRAMIENTAS DE IA\PROYECTOS\EL_RUFINO\`

Cambios organizativos, de nomenclatura y estructura del proyecto.
Cambios de producto (plugin, WordPress) registrados en `_DOCS\CHANGELOG.md`.

---

## [Sin liberar] - 2026-05-21

### Theme child — single.php: layout del cuerpo de nota

- `er-article-body` div: agregado `style="padding:0 40px;max-width:720px;margin:0 auto;box-sizing:border-box;"`
- Archivo: `_ACTIVO\theme\el-rufino-theme-v2.3.4\el-rufino-theme\single.php`
- Subido por FTP a `wp-content/themes/el-rufino-theme/single.php` — verificado en producción
- Resultado: cuerpo de nota centrado, ancho máximo 720 px, padding lateral 40 px

**Agente:** Claude Code · sesión 2026-05-21
**Estado:** EJECUTADO

---

## [Sin liberar] - 2026-05-20

### Archivado archivos usina-de-ideas_ en 00_CONTEXTO_IA

- `usina-de-ideas_prompt-maestro_v1.6-VIGENTE.md` → `_ARCHIVO\usina-de-ideas_prompt-maestro_v1.6_2026-05-20.md`
- `usina-de-ideas_repositorio-ia_v1.6-VIGENTE.md` → `_ARCHIVO\usina-de-ideas_repositorio-ia_v1.6_2026-05-20.md`
- `usina-de-ideas_agentes-ia_v1.6-VIGENTE.md` → `_ARCHIVO\usina-de-ideas_agentes-ia_v1.6_2026-05-20.md`
- Sufijo -VIGENTE eliminado en nombre destino (convención existente)
- Archivos no borrados — solo movidos

**Agente:** Claude Code · sesión 2026-05-20
**Estado:** EJECUTADO

---

## [Sin liberar] - 2026-05-14

### Actualizacion theme — style.css

- `style.css` → `Version: 2.0.0-gf` corregido a `Version: 2.3.4`
- Archivo: `_ACTIVO\theme\el-rufino-theme-v2.3.4\el-rufino-theme\style.css`
- Tarea: alinear numero de version con nombre de carpeta del theme

**Agente:** Claude Cowork · sesion 2026-05-14
**Estado:** EJECUTADO

---

## [Sin liberar] - 2026-05-13

### Normalizacion del sistema — Bloques 1-3

#### BLOQUE 1 — Renombre archivos maestros en 00_CONTEXTO_IA
- `usina-de-ideas_prompt-maestro_v1.6-VIGENTE.md` → `el-rufino_prompt-maestro_v1.6-VIGENTE.md`
- `usina-de-ideas_repositorio-ia_v1.6-VIGENTE.md` → `el-rufino_repositorio-ia_v1.6-VIGENTE.md`
- `usina-de-ideas_agentes-ia_v1.6-VIGENTE.md` → `el-rufino_agentes-ia_v1.6-VIGENTE.md`
- Referencias internas actualizadas en el-rufino_prompt-maestro: ARCHIVO_CONTEXTO, ARCHIVO_AGENTES, secciones METODOLOGIA y ESTRUCTURA
- Archivos originales usina-de-ideas_* conservados con header de redireccion (regla: nunca borrar)

#### BLOQUE 2 — Limpieza VIGENTEs huerfanos en _ARCHIVO\ [EJECUTADO]
- `_ARCHIVO\2026-05-13\` creada
- 7 archivos movidos desde `_ARCHIVO\` (v1.1, v1.2, v1.5):
  - `el-rufino_prompt-maestro_v1.1.md`
  - `el-rufino_repositorio-ia_v1.1.md`
  - `el-rufino_prompt-maestro_v1.2.md`
  - `el-rufino_repositorio-ia_v1.2.md`
  - `usina-de-ideas_prompt-maestro_v1.5.md`
  - `usina-de-ideas_repositorio-ia_v1.5.md`
  - `usina-de-ideas_agentes-ia_v1.5.md`
- 6 archivos movidos desde `_ARCHIVO\VERSIONES_ANTERIORES\` (v1.3, v1.4):
  - `el-rufino_prompt-maestro_v1.3.md`
  - `el-rufino_repositorio-ia_v1.3.md`
  - `el-rufino_agentes-ia_v1.3.md`
  - `usina-de-ideas_prompt-maestro_v1.4.md`
  - `usina-de-ideas_repositorio-ia_v1.4.md`
  - `usina-de-ideas_agentes-ia_v1.4.md`
- Sufijo -VIGENTE eliminado de nombres en archivo fechado

#### BLOQUE 3 — Sueltos en raiz del sistema (F:\HERRAMIENTAS DE IA\) [EJECUTADO]
- `usina-de-ideas_prompt-maestro_v1.6-VIGENTE.md` → `_ARCHIVO\2026-05-13\usina-de-ideas_prompt-maestro_v1.6.md`
- `usina-de-ideas_repositorio-ia_v1.6-VIGENTE.md` → `_ARCHIVO\2026-05-13\usina-de-ideas_repositorio-ia_v1.6.md`
- `usina-de-ideas_agentes-ia_v1.6-VIGENTE.md` → `_ARCHIVO\2026-05-13\usina-de-ideas_agentes-ia_v1.6.md`
- Nota: existia ademas `F:\HERRAMIENTAS DE IA\_ARCHIVO\usina-de-ideas_agentes-ia_v1.5-VIGENTE.md` (fuera de alcance de este bloque)

**Agente normalizacion:** USINA
**Estandar de referencia:** herramientas-ia_plan-normalizacion-sistema_v1.2-BORRADOR.md

---

## Notas

- Los cambios de producto (plugin, WordPress, Git) se registran en `_DOCS\CHANGELOG.md`
- Este archivo registra exclusivamente cambios organizativos y de nomenclatura
- Ningun archivo fue borrado — solo movidos o renombrados

---

**Ultima actualizacion:** 2026-05-13 (Bloques 1-3 ejecutados y confirmados)
**Script ejecucion:** normalizar_archivo_2026-05-13.ps1 -- 13 movidos · 3 ya en destino · 0 errores
**Mantenido por:** Agente normalizacion USINA
