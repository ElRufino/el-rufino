# REPOSITORIO IA — EL RUFINO
## Documento de contexto para transferencia entre instancias
### Versión 1.4 · 17 de abril de 2026
### Cambios v1.4: renombrado repositorio EL RUFINO → USINA DE IDEAS · rutas actualizadas a EL_RUFINO · FASE 3 completada (07_VARIOS_ARCHIVO clasificado · repo git consolidado en EL_RUFINO · 27 commits · sincronizado origin/main) · archivos maestros renombrados usina-de-ideas_* · semáforo repo git agregado · typo corregido (la comunidad organizada.txt)

---

## SECCIÓN 1 — BLOQUE DE TRANSFERENCIA RÁPIDA

```
PROYECTO = EL RUFINO
CIUDAD = Rufino, Santa Fe, Argentina
HABITANTES = 19.211 (INDEC, 2022)
CLAIM = "Lo que pasa y lo que significa"
TIPO = medio digital local con noticias, contexto y seguimiento
SECUENCIA = medio confiable → portal útil → infraestructura territorial
SIMBOLICA = patriótica / territorial / argentina · guiño peronista sutil (VARIANTE B elegida)
PALETA_B = #c0271b rojo · #1a1a1a negro · #ffffff blanco · #f5f0e8 crema
TIPOGRAFIA = Playfair Display (títulos) + Source Serif 4 o Inter (cuerpo)
NO_HACER = portal total prematuro · panfleto frontal · comunicado sin segunda capa
VENTAJA = contexto + seguimiento + campo + lectura territorial
DOMINIO_PRODUCCION = elrufino.com.ar (ACTIVO · DNS delegado · WordPress en Hostinger)
DOMINIO_PENDIENTE = elrufino.ar (pendiente registro NIC Argentina)
HOSTING = Hostinger (dominio definitivo de producción)
ENTORNO_TEST = prueba.infoconectados.com (ACTIVO · plugin v8.1.2 instalado)
ENTORNO_OBSOLETO = elrufino.infoconectados.com.ar (ya no usar)
PLUGIN = v8.1.2 "El Rufino — Panel" · 4 pantallas · 12 agentes · Kanban · Importador demo · Claude API
PLUGIN_PANTALLAS = BASE / Producción (4 tools) / Inteligencia (12) / Seguimiento
TEMA_PADRE = Newsup
FASE_ACTUAL = Fase 2 · producto mínimo · 40% completado
FASE_1 = identidad cerrada (COMPLETADA)
CHECKLIST_OK = Logo/favicon/OG subidos · 20 notas publicadas
CHECKLIST_PENDIENTE = 6 categorías P01-P06 · Schema NewsMediaOrganization · 500 WA suscriptores
EXPANSION_REGIONAL = PAUSADA · corredor RN33 es horizonte futuro
ROLES = funcionales · no cargos rígidos
NOMBRES = Fabián Longo (El Diferente) · Adriana Giménez (Voces otra mirada)
NORMAS_CITAS = APA 7ma edición
DECISIONES = NO reabrir las listadas sin indicación explícita del usuario
REPOSITORIO_ORGANIZATIVO = USINA DE IDEAS · F:\HERRAMIENTAS DE IA\PROYECTOS\USINA_DE_IDEAS\
ARCHIVO_CONTEXTO = 00_CONTEXTO_IA/usina-de-ideas_repositorio-ia_v1.4-VIGENTE.md
ARCHIVO_AGENTES = 00_CONTEXTO_IA/usina-de-ideas_agentes-ia_v1.4-VIGENTE.md
```

---

## SECCIÓN 2 — ARQUITECTURA TÉCNICA REAL (v1.4)

### 2.1 Dominios y entornos

| Dominio | Rol | Estado |
|---|---|---|
| elrufino.com.ar | Producción principal | ACTIVO · DNS delegado · WordPress en Hostinger · expediente NIC EX-2026-36480557-APN-DNRDI#SLYT · vence 06/04/2027 |
| elrufino.ar | Dominio de marca preferido | Pendiente registro NIC Argentina |
| prueba.infoconectados.com | Entorno de test activo | ACTIVO · panel v8.1.2 instalado y funcionando |
| elrufino.infoconectados.com.ar | Entorno anterior | OBSOLETO · no usar |

### 2.2 Plugin v8.1.2 — "El Rufino — Panel"

**Instalación activa:** prueba.infoconectados.com
**Versión en producción (elrufino.com.ar):** v7.0.5 (pendiente actualizar a v8.1.2)
**Archivo disponible:** `F:\HERRAMIENTAS DE IA\PROYECTOS\EL_RUFINO\02_WORDPRESS_TEST\el-rufino-panel-v8.1.2.zip`

#### Estructura de pantallas (4 pantallas · rediseño total vs v7)

| # | Pantalla | Tools / Contenido |
|---|---|---|
| 1 | BASE | Dashboard principal · stats · checklist Fase 2 · accesos directos |
| 2 | Producción | 4 tools de producción de contenido |
| 3 | Inteligencia | 12 agentes IA integrados |
| 4 | Seguimiento | Kanban editorial · registro de promesas |

#### Novedades v8 respecto a v7

- Arquitectura rediseñada: 4 pantallas en lugar de 14 módulos
- 12 agentes (era 11 en v7)
- Kanban integrado en pantalla Seguimiento
- Importador demo: 48 notas de ejemplo precargadas
- Claude API conectada directamente desde el panel
- Checklist de Fase 2 visible en Dashboard

#### Estado del checklist Fase 2 (verificado desde panel · 14/04/2026)

| Item | Estado |
|---|---|
| Logo/favicon/OG subidos | ✅ OK |
| 20 notas publicadas (demo) | ✅ OK |
| 6 categorías P01-P06 | ❌ Pendiente |
| Schema NewsMediaOrganization | ❌ Pendiente |
| 500 suscriptores WhatsApp | ❌ Pendiente |

**Fase 2: 40% completado**

### 2.3 Sistema de agentes (12 agentes en v8)

| # | Agente | Tipo |
|---|---|---|
| 1 | SEO | Operativo |
| 2 | Arquitectura | Operativo |
| 3 | Tema Visual | Operativo |
| 4 | Planificador | Operativo |
| 5 | Redactor | Operativo |
| 6 | TikTok / Reel | Operativo |
| 7 | Accountability | Operativo |
| 8 | Stack | Técnico |
| 9 | Servidor | Técnico |
| 10 | Agenda de Datos | Contenido |
| 11 | Imágenes | Contenido |
| 12 | Expansión Regional | Estratégico · PAUSADO |

### 2.4 Estructura de archivos del proyecto

**Ruta base:** `F:\HERRAMIENTAS DE IA\PROYECTOS\EL_RUFINO\`

```
EL_RUFINO\
├── 00_CONTEXTO_IA\
│   ├── usina-de-ideas_agentes-ia_v1.4-VIGENTE.md
│   ├── usina-de-ideas_prompt-maestro_v1.4-VIGENTE.md
│   └── usina-de-ideas_repositorio-ia_v1.4-VIGENTE.md       este archivo
├── 01_DOCUMENTOS_VIGENTES\
│   ├── el-rufino_dashboard-operativo_v5-VIGENTE.html
│   ├── el-rufino_dossier-fase0_v3-VIGENTE.html
│   ├── el-rufino_dossier-fundacional.html
│   ├── el-rufino_dossier-maestro_VIGENTE.pdf
│   ├── el-rufino_informe-memoria_v4.0.html
│   ├── el-rufino_plan-fundacional.html
│   └── la comunidad organizada.txt
├── 02_WORDPRESS_TEST\
│   ├── el-rufino-plugin.zip
│   ├── auditoria-rufino.php
│   └── [archivos del plugin y child theme]
├── 03_IDENTIDAD_MARCA\
│   └── [logos · favicon · OG · manuales · imágenes de referencia]
├── 04_DASHBOARDS_REFERENCIA\
│   └── [11 HTMLs de dashboards]
├── 05_MATERIAL_HISTORICO\
├── 06_SCRIPTS\
│   ├── .elrufino_scan_latest.json
│   ├── REORGANIZACION_FASE3.ps1
│   └── [scripts PowerShell]
├── _ARCHIVO\
├── .git                                    repo git · 27 commits · sincronizado origin/main
└── CHANGELOG.md
```

### 2.5 Repositorio git

**Remote:** https://github.com/ElRufino/el-rufino.git
**Branch activa:** main
**Último commit:** reorganizacion FASE 3
**Estado:** sincronizado con origin/main

### 2.6 Checklist WordPress — estado actual

- [x] Plugin v8.1.2 disponible (activo en test, pendiente en producción)
- [x] DNS delegado · sitio resuelve en dominio definitivo
- [x] Logo, favicon y OG image subidos a Media Library (confirmado en panel)
- [x] 20 notas demo publicadas
- [ ] Actualizar plugin en elrufino.com.ar de v7.0.5 a v8.1.2
- [ ] Child theme Newsup instalado y activo con paleta B
- [ ] SEO Rank Math configurado (Schema NewsMediaOrganization)
- [ ] Categorías y slugs P01-P06 creados
- [ ] Canal WhatsApp broadcast configurado
- [ ] elrufino.ar registrado en NIC Argentina

---

## SECCIÓN 3 — CONTEXTO DE CIUDAD Y MERCADO

### 3.1 Rufino, Santa Fe

Rufino es una ciudad de 19.211 habitantes (INDEC, 2022) ubicada en el sudoeste de la provincia de Santa Fe, Argentina. Nodo agropecuario de la región pampeana, con economía basada en producción de soja, logística y comercio local. Pertenece al departamento General López.

**Datos clave del ecosistema digital:**
- ~7.200 hogares estimados
- ~14.000 usuarios de Facebook activos estimados
- +80% de alcance de Facebook vs. población total
- Rutas Nacionales 33 y 7 como eje logístico y tema de mayor debate digital

### 3.2 Ecosistema de medios local

| Medio | Likes FB | Fortaleza | Lo que deja libre |
|---|---|---|---|
| Sucesos | 29.731 | velocidad, volumen | seguimiento, archivo, campo, contexto |
| FM Rufino 106.3 | 28.712 | audiencia 45+, voz en vivo | texto, síntesis, clip, archivo |
| La Tribuna del Sur | 19.327 | tradición, reconocimiento | actualización explicativa |
| Municipalidad | 17.983 | canal oficial | lectura crítica, contraste |
| El Diferente | 11.011 | trayectoria, memoria | brecha disponible |
| Rufino Web | 10.634 | marca digital visible | espesor visual y analítico |

**Diagnóstico:** Todos los medios cubren el hecho. Ninguno construye arquitectura de contexto, seguimiento ni archivo útil.

### 3.3 Audiencias prioritarias

| # | Segmento | Canal principal | Característica |
|---|---|---|---|
| P1 | 25–44 años | Facebook | Activos políticamente. Debaten obras, tarifas, gestión. |
| P2 | 45–60 años | FB + WhatsApp | Consumidores y redistribuidores. Alta confianza requerida. |
| P3 | Campo / productores | WA + FB | Audiencia más rentable y fidelizable. Desatendida editorialmente. |
| P4 | 18–30 años | TikTok + Instagram | Segmento de crecimiento. Video corto. Marca futura. |

---

## SECCIÓN 4 — IDENTIDAD DEL MEDIO

### 4.1 Decisiones cerradas (no reabrir)

| Decisión | Valor | Estado |
|---|---|---|
| Nombre | EL RUFINO | Cerrado |
| Claim | "Lo que pasa y lo que significa" | Cerrado |
| Marco simbólico | Variante B · patriótica/territorial/argentina con guiño peronista sutil | Cerrado |
| Paleta | B: #c0271b · #1a1a1a · #ffffff · #f5f0e8 | Cerrado |
| Dominio de marca | elrufino.com.ar (producción) / elrufino.ar (objetivo) | En ejecución |
| Expansión regional | Corredor RN33 → horizonte futuro, no fase activa | Pausado |

### 4.2 Paleta de colores — Opción B

| Color | Hex | Uso |
|---|---|---|
| Rojo | #c0271b | Cabecera, accentos, masthead |
| Negro | #1a1a1a | Texto principal |
| Blanco | #ffffff | Fondos primarios |
| Crema | #f5f0e8 | Fondos secundarios, clima editorial |

### 4.3 Tipografía

- Títulos: Playfair Display (serif, periodismo clásico)
- Cuerpo: Source Serif 4 o Inter
- Código / monospace: JetBrains Mono

---

## SECCIÓN 5 — PILARES EDITORIALES

| # | Pilar | Descripción | Formatos |
|---|---|---|---|
| P01 | Rufino real | Noticias verificadas con contexto. Antídoto contra el rumor. | nota + hilo + infografía |
| P02 | El campo habla | Rutas, costos, cosecha, política agraria, logística. | entrevista + dato + WA |
| P03 | Barrio a barrio | Infraestructura, agua, cloacas, reclamos. Con continuidad y mapa. | seguimiento + mapa + vecinos |
| P04 | Generación Rufino | Jóvenes, artistas, emprendedores. Tensión entre quedarse e irse. | reels + perfil + TikTok |
| P05 | Poder y gestión | Promesas, obras, presupuesto, decisiones. Verificación sin editorializar. | fact-check + seguimiento + archivo |
| P06 | Rufino en datos | Números locales: INDEC, salud, educación, presupuesto. | datos + placas + explicador |

**Regla madre:** toda pieza debe tener dos capas: 1) lo que pasó (el hecho verificado) · 2) lo que significa / lo que falta saber / el contexto.

---

## SECCIÓN 6 — CRITERIOS EDITORIALES

**SÍ:** directo sin ser agresivo · verificado · local sin localismo · humano sin amarillismo · datos cuando hay datos · nunca comunicado sin segunda capa

**NO:** burocrático · sensacionalista · comunicado sin contexto · lenguaje técnico innecesario · opinión sin sustento

**Protocolo policiales:** versión policial ≠ verdad cerrada. Aclarar qué está confirmado. No usar imágenes humillantes como recurso automático.

**Protocolo promesas:** cada anuncio oficial → abrir ficha en pantalla Seguimiento del panel.

---

## SECCIÓN 7 — ESTRATEGIA DE PLATAFORMAS

| Canal | Rol | Frecuencia inicial | KPI mes 6 |
|---|---|---|---|
| Facebook | base + credibilidad | 3–5 posts/día | 5.000 likes, >3% interacción |
| Instagram | marca visual | 1 post + stories/día | 2.000 seguidores, >5% engagement |
| WhatsApp | distribución directa sin algoritmo | 1 resumen fijo 7:30 AM | 500 suscriptores, >60% apertura |
| TikTok/Reels | crecimiento joven | 3–4 videos/semana | 1 viral en 60 días, 1.000 seg. al mes 4 |

---

## SECCIÓN 8 — SEMÁFOROS DE ESTADO

| Eje | Estado | Nota |
|---|---|---|
| Identidad (Fase 1) | VERDE | Cerrada definitivamente |
| Plugin v8.1.2 | VERDE | Activo en prueba.infoconectados.com |
| Dominio .com.ar | VERDE | Activo · delegado · Hostinger |
| Logo/favicon/OG | VERDE | Subidos y confirmados desde panel |
| Notas demo | VERDE | 20 notas publicadas |
| Repo git | VERDE | Consolidado en EL_RUFINO\ · 27 commits · sincronizado origin/main |
| Dominio .ar | ROJO | Pendiente registro NIC Argentina |
| Plugin en producción | AMARILLO | v7.0.5 en elrufino.com.ar · actualizar a v8.1.2 |
| Child theme Newsup | AMARILLO | Archivo disponible en 02_WORDPRESS_TEST\ · falta instalar |
| SEO / Rank Math | ROJO | Schema NewsMediaOrganization pendiente |
| Categorías P01-P06 | ROJO | Sin crear aún |
| Canal WhatsApp broadcast | ROJO | Sin configurar |
| Monetización | ROJO | Esperar hábito y reputación |
| Expansión regional RN33 | PAUSA | Horizonte futuro, no fase activa |

---

## SECCIÓN 9 — VOCES ASOCIADAS

**Fabián Longo** — referencia vinculada a El Diferente (edición papel). Memoria de oficio, experiencia en soporte local.
**Adriana Giménez** — "Voces, otra mirada" y versión radial. Voz, sensibilidad, lectura de comunidad.

*Criterio: sí nombrar, no inventar cargo definitivo.*

---

## SECCIÓN 10 — RIESGOS ACTIVOS

| Riesgo | Nivel | Descripción |
|---|---|---|
| Portalitis | ALTO | Querer ser todo desde el día 1. Identidad diluida. |
| Simbolismo mal calibrado | ALTO | Pasarse de territorial a panfletario. |
| Dependencia de una sola voz | MEDIO | El proyecto necesita más de una persona funcional. |
| Publicar por ansiedad | ALTO | Intentar competir en velocidad con Sucesos. |
| Falta de calle | ALTO | Todo en pantalla, nada de territorio real. |
| Monetización precoz | MEDIO | Antes de construir hábito. |

---

## SECCIÓN 11 — METODOLOGÍA DE TRABAJO IA

1. **Inicio de sesión nueva:** pegar el bloque de la Sección 1
2. **Si la tarea es técnica (WP/plugin):** agregar Sección 2
3. **Si la tarea es editorial:** agregar Secciones 5 y 6
4. **Si la tarea es estratégica:** agregar Secciones 3, 4 y 7
5. **Para activar un agente:** referenciar por número (1-12) desde la pantalla Inteligencia del panel

**Versionado:** al tomar una decisión nueva → identificar sección afectada → actualizar → cambiar versión → subir al repositorio.

---

## REFERENCIAS

Instituto Nacional de Estadística y Censos. (2022). *Censo Nacional de Población, Hogares y Viviendas 2022*. Gobierno de Argentina. https://www.indec.gob.ar

NIC Argentina. (2026, abril). *Expediente EX-2026-36480557-APN-DNRDI#SLYT: registro elrufino.com.ar*. https://nic.ar

We Are Social & Hootsuite. (2024). *Digital 2024: Argentina*. DataReportal. https://datareportal.com/reports/digital-2024-argentina

---

*Documento vivo. Actualizar al tomar cada decisión relevante.*
*Firma institucional: PROYECTO EL RUFINO · Repositorio organizativo: USINA DE IDEAS*
*Versión 1.4 · 17 de abril de 2026*
