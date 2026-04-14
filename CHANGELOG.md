# CHANGELOG — EL RUFINO

---

## [v1.3] — 14 de abril de 2026

### Sesión: Auditoría técnica completa + actualización de repositorio

---

### Plugin actualizado: v7.0.5 → v8.1.2

**Arquitectura rediseñada:**
- v7: 14 módulos en una sola pantalla
- v8: 4 pantallas (BASE / Producción / Inteligencia / Seguimiento)
- 12 agentes (era 11)
- Kanban integrado en Seguimiento
- Importador demo: 48 notas de ejemplo
- Claude API conectada directamente desde el panel

**Entorno activo:** prueba.infoconectados.com  
**Producción (elrufino.com.ar):** pendiente actualizar de v7.0.5 a v8.1.2

---

### Estado Fase 2 verificado desde panel (40% completado)

| Item | Estado |
|---|---|
| Logo/favicon/OG subidos | ✅ OK |
| 20 notas publicadas (demo) | ✅ OK |
| 6 categorías P01-P06 | ❌ Pendiente |
| Schema NewsMediaOrganization | ❌ Pendiente |
| 500 suscriptores WhatsApp | ❌ Pendiente |

---

### Limpieza de carpeta Descargas

- 162 archivos movidos a `F:\downloads\_BACKUP_20260414_031726` (filtro 90 días)
- Script original (365 días) falló por caracteres especiales en nombres de archivo — resuelto con PowerShell nativo usando `-LiteralPath`
- Carpeta `F:\downloads\Viejos\` auditada: archivos clasificados en críticos / conservar / eliminar

### Archivos críticos recuperados de Viejos/

| Archivo | Acción |
|---|---|
| `el-rufino-panel-v8.1.2.zip` | Identificado como versión más reciente |
| `Imagenes El Rufino/` | Logos + favicons + OG images (confirmados subidos en panel) |
| `el-rufino-child-v1.1.zip` | Child theme disponible · pendiente instalar |
| `panel.html / panel.jsx / panel.css` | Fuentes del panel v8 |
| `el-rufino_rankmath.php` | Configuración SEO disponible |

---

### Seguridad

- `sk-or-v1-7c9c7b953c80f0852fa0e3b416.txt` (API key OpenRouter) encontrada en texto plano en Viejos/ — **revocada y renovada**

---

### Decisiones tomadas en esta versión

| Decisión | Valor |
|---|---|
| Versión activa del plugin | v8.1.2 |
| Entorno de test | prueba.infoconectados.com |
| Entorno obsoleto | elrufino.infoconectados.com.ar (retirado) |
| Semáforo Logo/favicon/OG | VERDE (confirmado desde panel) |
| Semáforo Notas demo | VERDE (20 notas OK) |
| Plugin en producción | AMARILLO (pendiente migrar v7→v8) |

---

## [v1.2] — 12 de abril de 2026

- Plugin actualizado a v7.0.5
- Hosting migrado a Hostinger
- `elrufino.com.ar` delegado y activo
- Arquitectura técnica real documentada desde código fuente
- 14 módulos del panel documentados
- 11 agentes integrados documentados
- Tabla `wp_er_promesas` con CRUD completo vía AJAX

---

## [v1.1] — Fecha anterior

- Identidad visual cerrada (Variante B)
- Paleta B confirmada
- Pilares editoriales P01–P06 definidos
- Estrategia de plataformas documentada

---

## [v1.0] — Inicio del proyecto

- Documento inicial creado
- Nombre, claim y marco simbólico definidos
- Primera versión del repositorio IA
