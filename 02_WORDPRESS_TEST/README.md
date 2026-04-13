# El Rufino — Panel IA

Plugin WordPress para el sistema operativo editorial de El Rufino, medio digital local de Rufino, Santa Fe, Argentina.

## Versión actual: 8.1.2

## Estructura

```
el-rufino-panel/
├── el-rufino-panel.php   # Plugin principal — AJAX handlers, DB, proxy Claude API
└── assets/
    ├── panel.jsx         # React app — 4 pantallas, 12 agentes, UI completa
    └── panel.css         # Estilos — reset WP admin, fullscreen, responsive
```

## Pantallas

| # | Pantalla | Contenido |
|---|---|---|
| 1 | Dashboard | Checklist Fase 2 · API Key · Métricas · Importador demo |
| 2 | Producción | Chat IA · Redactor · Kanban · WhatsApp |
| 3 | Inteligencia | 12 Agentes · Contexto IA |
| 4 | Seguimiento | Promesas · Redes · Contenido · Roadmap |

## AJAX endpoints (PHP)

| Action | Función | Descripción |
|---|---|---|
| `er_claude_proxy` | `er_claude_proxy()` | Proxy hacia API Anthropic (Claude Sonnet 4) |
| `er_save_apikey` | `er_save_apikey()` | Guardar API key encriptada |
| `er_apikey_status` | `er_apikey_status()` | Verificar si hay key configurada + máscara |
| `er_delete_apikey` | `er_delete_apikey()` | Eliminar API key |
| `er_get_promesas` | `er_get_promesas()` | Listar fichas de promesas |
| `er_save_promesa` | `er_save_promesa()` | Crear / actualizar ficha |
| `er_update_estado` | `er_update_estado()` | Cambiar estado de promesa |
| `er_delete_promesa` | `er_delete_promesa()` | Eliminar ficha |
| `er_export_promesas` | `er_export_promesas()` | Exportar CSV |
| `er_update_checklist` | `er_update_checklist()` | Toggle checklist Fase 2 |
| `er_import_demo` | `er_import_demo()` | Importar 48 notas demo como borradores |

## Instalación

1. Descargar el zip de Releases
2. WordPress → Plugins → Subir plugin → Activar
3. Ir a El Rufino (menú lateral) → Dashboard
4. Ingresar API key de Anthropic (sk-ant-...)
5. Configurar Rank Math + child theme Newsup

## Requisitos

- WordPress 6.x
- PHP 8.0+
- Plugin activo en: `prueba.infoconectados.com` (test) → `elrufino.com.ar` (producción)
- Tema padre: Newsup

## Changelog

### v8.1.2 (13-04-2026)
- **FIX:** Chat IA devolvía "Sin respuesta" — el payload JSON no llegaba al proxy PHP
- **FIX:** API key — campo no se desactivaba al guardar, sin opción de eliminar
- **NEW:** `er_apikey_status` — verifica y muestra clave enmascarada al cargar el Dashboard
- **NEW:** `er_delete_apikey` — eliminar clave desde la UI
- **NEW:** UI API key con estado: "Verificando..." → activa con máscara → formulario de carga

### v8.1.1 (12-04-2026)
- Agente Crisis (12) agregado
- Agente TikTok (6A) y Reels (6B) separados
- Roadmap con semáforos de estado

### v8.1.0 (12-04-2026)
- 4 pantallas: Dashboard, Producción, Inteligencia, Seguimiento
- 12 agentes operativos
- 48 notas demo (8 × 6 pilares)
- DB wp_er_promesas con CRUD completo
- Importador de notas demo

## Contexto del proyecto

Ver `00_CONTEXTO_IA/` en el repositorio principal para el prompt maestro y sistema de agentes.

- **Medio:** El Rufino — "Lo que pasa y lo que significa"
- **Ciudad:** Rufino, Santa Fe, Argentina — 19.211 hab.
- **Pilares:** P01 Lo que pasa · P02 El campo habla · P03 Barrio a barrio · P04 Generación Rufino · P05 Seguimiento promesas · P06 Contexto y datos
- **Fase actual:** Fase 2 — Producto mínimo en ejecución
