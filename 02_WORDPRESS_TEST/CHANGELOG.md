# Changelog — El Rufino Panel

Todas las versiones del plugin WordPress del Panel IA de El Rufino.

---

## [8.1.2] — 2026-04-13

### Fixed
- **Chat IA / "Sin respuesta":** `callClaude()` enviaba solo `action` y `nonce` al proxy PHP. El payload JSON de Claude no llegaba porque `admin-ajax.php` recibe `application/x-www-form-urlencoded` y el PHP leía `php://input` (vacío en ese caso). Solución: payload enviado como campo POST `payload` (JSON string), PHP lo lee con `$_POST['payload']`.
- **API Key — campo no se desactiva:** Al guardar la key no había feedback de estado. El input quedaba habilitado y sin indicar si había una key activa en el servidor.

### Added
- `er_apikey_status` — nuevo AJAX endpoint. Al montar el Dashboard verifica si hay key configurada y devuelve versión enmascarada (`sk-ant-ap...a3f2`).
- `er_delete_apikey` — nuevo AJAX endpoint. Elimina la key desde la UI sin tocar el servidor.
- UI de API key con 3 estados: `Verificando...` → `✓ Activa: sk-ant-...` con botones Cambiar/Eliminar → formulario de ingreso.
- `er_save_apikey` ahora devuelve la máscara en la respuesta para actualizar la UI sin recargar.

### Changed
- Proxy PHP: timeout aumentado de 60 a 90 segundos.
- Proxy PHP: manejo de errores mejorado — devuelve el mensaje de error real de la API de Anthropic.
- Badge "OpenRouter integrado" → "Claude API" (el proxy siempre apuntó a api.anthropic.com).
- `max_tokens` en `callClaude()`: 1000 → 1500.

---

## [8.1.1] — 2026-04-12

### Added
- Agente 12: Crisis — protocolo tragedia, fake news, desmentida
- Agentes 6A (TikTok) y 6B (Reels) separados con prompts diferenciados
- Roadmap con semáforos de estado en Pantalla 4

---

## [8.1.0] — 2026-04-12

### Added
- 4 pantallas: Dashboard · Producción · Inteligencia · Seguimiento
- 12 agentes con prompts operativos
- 48 notas demo (8 × 6 pilares) basadas en noticias reales de Rufino marzo-abril 2026
- Base de datos `wp_er_promesas` con CRUD completo via AJAX
- Importador de notas demo como borradores WordPress
- Checklist Fase 2 persistente
- Métricas Mes 0
- Kanban editorial (drag & drop)
- Plantillas WhatsApp con preview
- Contexto IA exportable (bloque de transferencia rápida)
