# Procedencia de la candidata PWA v1.1.0

## Base

- Base funcional: prototipo local v1.0.0 del 2026-07-18.
- Preparación: workspace ignorado `SYNC\CODEX\PWA_EL_RUFINO`.
- `_ACTIVO`: no modificado.
- Git: no modificado.

## Identidad

Fuente del ícono:

`03_IDENTIDAD_MARCA\favico\favicon-el-rufino-512.png`

Las variantes 192×192, 180×180 y maskable 512×512 se generaron mediante
redimensionado determinista de alta calidad. La variante maskable se compuso
sobre fondo sólido oficial `#c0271b`. No se utilizó generación creativa.

## Fuentes

Repositorio oficial:

`https://github.com/google/fonts`

Familias:

- `ofl/playfairdisplay`;
- `ofl/sourceserif4`.

Se incorporaron:

- fuente variable normal de Playfair Display;
- fuente variable normal de Source Serif 4;
- fuente variable itálica de Source Serif 4;
- licencia OFL de cada familia;
- metadatos de cada familia.

No se instalaron fuentes en Windows y no se conservan dependencias de Google
Fonts remoto en `index.html`.

## Incidencia ER-PWA-BUILD-001

- Acción: generación determinista de variantes del favicon.
- Error: el primer intento utilizó `input` como parámetro de PowerShell, en
  conflicto con una variable automática.
- Efecto: sólo `icon-512.png` fue sustituido; las otras tres variantes
  conservaron temporalmente sus hashes anteriores dentro del staging.
- Resolución: se renombró el parámetro y se regeneraron las cuatro variantes.
- Verificación: dimensiones y SHA-256 calculados para los cuatro resultados.
- Efecto sobre el favicon fuente, `_ACTIVO` y Git: ninguno.

## Estado

`CANDIDATA_EN_WORKSPACE — NO PUBLICABLE TODAVÍA`
