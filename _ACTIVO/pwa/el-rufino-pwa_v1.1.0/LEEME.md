# El Rufino — PWA candidata v1.1.0

## Estado

`CANDIDATA_EN_WORKSPACE — NO PUBLICABLE TODAVÍA`

Esta carpeta es una candidata preparada bajo las decisiones:

- `DEC-20260726-005` — alcance funcional;
- `DEC-20260726-006` — privacidad e identidad;
- `DEC-20260726-007` — Git y composición;
- `DEC-20260726-008` — plan de pruebas.

No es un release, no está desplegada y no forma parte de Git.

## Alcance aprobado

- Sitio web: `https://elrufino.com.ar`.
- Suscripción: WhatsApp oficial `+54 9 3382 51-1670`.
- Login administrativo: retirado de la interfaz.
- En vivo y Podcast: visibles, deshabilitados y sin enlaces.
- Ubicación conceptual futura: `https://elrufino.com.ar/app/`.
- Proyecto propietario: EL_RUFINO.
- Proyecto independiente: no.

## Identidad

- Símbolo: `R` oficial.
- Paleta: rojo `#c0271b`, negro `#1a1a1a`, crema `#f5f0e8`.
- Títulos: Playfair Display.
- Cuerpo e interfaz: Source Serif 4.
- Inter: retirada.
- Google Fonts remoto: retirado.

Las fuentes se conservan localmente junto con sus licencias. Los íconos de esta
candidata se derivan determinísticamente del favicon oficial; no son una
reinterpretación generativa de la marca.

## Estructura

- `index.html`: interfaz.
- `manifest.json`: configuración instalable.
- `sw.js`: caché del shell.
- `privacy.html`: borrador de privacidad.
- `icons/`: variantes de la `R` oficial.
- `fonts/`: fuentes autoalojadas y licencias.
- `docs/assetlinks.template.json`: plantilla no activa.
- `PLAY_STORE_DEPLOY.md`: guía provisional.

## Bloqueos antes de probar

- validar sintaxis y referencias;
- congelar inventario y hashes;
- comprobar que no existan placeholders desplegables;
- verificar licencias y metadatos de fuentes;
- aprobar el plan de ejecución local;
- identificación legal responsable documentada en `privacy.html`.

## Prohibiciones

No copiar esta carpeta a producción, `_ACTIVO`, Git o Google Play sin una
autorización posterior y verificación conforme al Plan P4.
