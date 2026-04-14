# REPOSITORIO IA — EL RUFINO
## Documento de contexto para transferencia entre instancias
### Versión 1.2 · 12 de abril de 2026
### Cambios v1.2: plugin actualizado a v7.0.5 · hosting migrado a Hostinger · elrufino.com.ar delegado y activo · arquitectura técnica real documentada desde código fuente

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
DOMINIO_PRINCIPAL = elrufino.com.ar (ACTIVO · DNS delegado · WordPress instalado en Hostinger)
DOMINIO_AR = elrufino.ar (pendiente registro NIC Argentina)
HOSTING = Hostinger (dominio definitivo de producción)
ENTORNO_TEST = elrufino.infoconectados.com.ar (OBSOLETO como entorno principal)
PLUGIN = v7.0.5 "El Rufino — Panel" · instalado en elrufino.com.ar
PLUGIN_MODULOS = 14 módulos · 4 secciones (BASE / IDENTIDAD / EDITORIAL / INTELIGENCIA)
PLUGIN_AGENTES = 11 agentes integrados (1-7 operativos · 8-9 técnicos · 10-11 contenido)
PLUGIN_DB = tabla wp_er_promesas activa con CRUD completo vía AJAX
TEMA_PADRE = Newsup
EXPANSION_REGIONAL = PAUSADA · corredor RN33 es horizonte futuro
FASE_ACTUAL = Fase 0 · reconstrucción técnica
FASE_1 = identidad cerrada (COMPLETADA)
FASE_2 = producto mínimo (en ejecución)
ROLES = funcionales · no cargos rígidos
NOMBRES = Fabián Longo (El Diferente) · Adriana Giménez (Voces otra mirada)
NORMAS_CITAS = APA 7ma edición
DECISIONES = NO reabrir las listadas sin indicación explícita del usuario
```

---

## SECCIÓN 2 — ARQUITECTURA TÉCNICA REAL (v1.2)

### 2.1 Dominios

| Dominio | Rol | Estado |
|---|---|---|
| elrufino.com.ar | Producción principal | ACTIVO · DNS delegado · WordPress en Hostinger · expediente NIC EX-2026-36480557-APN-DNRDI#SLYT · vence 06/04/2027 |
| elrufino.ar | Dominio de marca preferido | Pendiente registro NIC Argentina |
| elrufino.infoconectados.com.ar | Entorno de test anterior | OBSOLETO como entorno principal |

**Próximo paso de dominios:** registrar elrufino.ar y configurar redirección 301 elrufino.ar → elrufino.com.ar (o viceversa según decisión).

### 2.2 Plugin v7.0.5 — "El Rufino — Panel"

**Instalación:** elrufino.com.ar (Hostinger)
**Archivos:** el-rufino-panel.php · panel.html · panel.css
**Activación:** crea tabla `wp_er_promesas` en base de datos al activar
**Acceso:** menú principal WP Admin → "El Rufino" (ícono dashicons-media-document, posición 2)
**Modo:** fullscreen — oculta barra de administración WP completamente al entrar al panel

#### Estructura de módulos (14 total)

| # | Módulo | Sección | Función principal |
|---|---|---|---|
| 0 | Inicio | BASE | Dashboard con stats, changelog y accesos directos |
| 1 | GitHub | BASE | Conexión y sincronización con repositorio de contexto |
| 2 | Infraestructura | BASE | Diagnóstico WordPress real (versión, PHP, SSL, plugins, actualizaciones) |
| 3 | Plugins | BASE | Estado de plugins · verificación al abrir el módulo · guía de instalación |
| 4 | Tema visual | IDENTIDAD | Configuración Customizer · child theme · paleta B |
| 5 | Themes Región | IDENTIDAD | Generación de Prompt Maestro para otras ciudades del corredor RN33 |
| 6 | Imágenes | IDENTIDAD | Subida a Media Library vía AJAX · logo · favicon · OG image |
| 7 | SEO | EDITORIAL | Aplicación automática de SEO: blogname, tagline, Rank Math, Schema, breadcrumbs |
| 8 | Contenido | EDITORIAL | Generación de entradas y calendario editorial vía agentes |
| 9 | Promesas | EDITORIAL | Registro CRUD completo · estados · códigos P001-N · carga automática al abrir |
| 10 | usInA (11 agentes) | INTELIGENCIA | Activación de los 11 agentes integrados con prompts adaptados a ciudad activa |
| 11 | Contexto IA | INTELIGENCIA | Editor del bloque de transferencia rápida · sincronización GitHub |
| 12 | Redes | INTELIGENCIA | Generación de resumen WhatsApp y contenido para redes |
| 13 | Roadmap | INTELIGENCIA | Semáforo de estado del proyecto |

#### Variables JS inyectadas en admin_footer

```javascript
var erAjax = {
    url:           // admin-ajax.php
    nonce:         // wp_create_nonce('er_nonce')
    mediaUrl:      // media-new.php
    customizerUrl: // customize.php
    siteUrl:       // get_site_url()
    adminUrl:      // admin_url()
    themeVersion:  // versión del tema activo
    themeName:     // nombre del tema activo
};
```

#### Handlers AJAX (todos requieren nonce + manage_options)

| Action | Función |
|---|---|
| er_save_promesa | Inserta nueva promesa en wp_er_promesas |
| er_get_promesas | Lista todas las promesas ordenadas por fecha DESC |
| er_update_estado | Actualiza estado de una promesa (Abierta / En curso / Cumplida / Incumplida / Parcial) |
| er_delete_promesa | Elimina promesa por ID |
| er_diagnostico | Devuelve estado real del servidor WP (versión, PHP, plugins, tema, SSL, memoria) |
| er_upload_image | Sube imagen a Media Library · devuelve attachment_id y URL |
| er_apply_seo | Aplica configuración SEO: blogname, tagline, Rank Math breadcrumbs, Schema NewsMediaOrganization |

#### Base de datos — tabla wp_er_promesas

```sql
id           mediumint(9)  AUTO_INCREMENT PRIMARY KEY
fecha        datetime      DEFAULT CURRENT_TIMESTAMP
funcionario  varchar(100)
promesa      text
estado       varchar(20)   DEFAULT 'Abierta'
pilar        varchar(10)
fuente       varchar(255)
evidencia    text
fecha_prom   date
```

Código de promesa: P + id con padding a 3 dígitos (P001, P002...)

#### Bugs conocidos en v7.0.5

- Export PDF falla con prompts de más de 10.000 tokens
- Rate limiting no respeta límites de la API correctamente
- Historial no pagina correctamente cuando hay más de 100 registros

### 2.3 Sistema de agentes integrados (11)

| # | Agente | Tipo | Rol |
|---|---|---|---|
| 1 | SEO | Operativo | Rank Math · Schema · Keywords P01–P06 |
| 2 | Arquitectura | Operativo | Categorías, slugs y entradas base con regla 2 capas |
| 3 | Tema Visual | Operativo | PHP/CSS del child theme Newsup |
| 4 | Planificador | Operativo | Calendario editorial semanal completo |
| 5 | Redactor | Operativo | Notas periodísticas 600-1000 palabras listas para publicar |
| 6 | TikTok / Reel | Operativo | Guiones 60-90 segundos · hook + desarrollo + CTA |
| 7 | Accountability | Operativo | Seguimiento de promesas · nota + hilo FB + resumen WA |
| 8 | Stack | Técnico | WordPress y plugins · configuración técnica · código funcional |
| 9 | Servidor | Técnico | Infraestructura · hosting · velocidad · migración |
| 10 | Agenda de Datos | Contenido | Pilar P06 · INDEC · SAMCO · presupuesto municipal |
| 11 | Imágenes | Contenido | Prompts para generación visual (Gemini · Midjourney) |

**Agentes en pausa (Fase 3):** Agente SEO por nota · Agente Monetización — activar cuando haya hábito de audiencia.

Los prompts de todos los agentes se adaptan automáticamente a la ciudad activa mediante la variable `{CIUDAD}`.

### 2.4 Checklist WordPress — estado actual

- [x] Plugin v7.0.5 instalado y activo en elrufino.com.ar
- [x] Tabla wp_er_promesas creada
- [x] DNS delegado · sitio resuelve en dominio definitivo
- [ ] Child theme Newsup instalado y activo
- [ ] SEO Rank Math configurado (Schema NewsMediaOrganization)
- [ ] Categorías y slugs P01-P06 creados
- [ ] Customizer: colores paleta B, tipografías, menús
- [ ] Canal WhatsApp broadcast configurado
- [ ] Logo, favicon y OG image subidos a Media Library
- [ ] elrufino.ar registrado en NIC Argentina

---

## SECCIÓN 3 — CONTEXTO DE CIUDAD Y MERCADO

### 3.1 Rufino, Santa Fe

Rufino es una ciudad de 19.211 habitantes (Instituto Nacional de Estadística y Censos [INDEC], 2022) ubicada en el sudoeste de la provincia de Santa Fe, Argentina. Nodo agropecuario de la región pampeana, con economía basada en producción de soja, logística y comercio local. Pertenece al departamento General López.

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

### 4.2 Marco simbólico — Variante B

Patriótica / territorial / argentina con guiño peronista sutil. Sin panfleto frontal en la capa principal. Memoria, trabajo, comunidad, justicia social, pertenencia territorial.

**Símbolos permitidos:** horizonte, ruta, cielo, ciudad, pantalla nocturna, trama de trabajo, origen desde abajo.

**Errores a evitar:** bandera literal como fondo, patrioterismo vacío, peronismo explícito en capa principal.

### 4.3 Paleta de colores — Opción B

| Color | Hex | Uso |
|---|---|---|
| Rojo | #c0271b | Cabecera, accentos, masthead |
| Negro | #1a1a1a | Texto principal |
| Blanco | #ffffff | Fondos primarios |
| Crema | #f5f0e8 | Fondos secundarios, clima editorial |

### 4.4 Tipografía

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

**Protocolo promesas:** cada anuncio oficial → abrir ficha en módulo 9 del panel con fecha, promesa, fuente, estado y evidencia.

---

## SECCIÓN 7 — ESTRATEGIA DE PLATAFORMAS

| Canal | Rol | Frecuencia inicial | KPI mes 6 |
|---|---|---|---|
| Facebook | base + credibilidad | 3–5 posts/día | 5.000 likes, >3% interacción |
| Instagram | marca visual | 1 post + stories/día | 2.000 seguidores, >5% engagement |
| WhatsApp | distribución directa sin algoritmo | 1 resumen fijo 7:30 AM | 500 suscriptores, >60% apertura |
| TikTok/Reels | crecimiento joven | 3–4 videos/semana | 1 viral en 60 días, 1.000 seg. al mes 4 |

### Calendario semanal tipo

| Día | Pilar | Formato |
|---|---|---|
| Lunes | P01 + P06 | Resumen + dato de contexto |
| Martes | P02 | El campo habla |
| Miércoles | P04 | TikTok / Reel joven |
| Jueves | P05 | Seguimiento promesa / gestión |
| Viernes | P03 | Barrio a barrio |
| Sábado | P04 | Generación Rufino — perfil |
| Domingo | Evergreen | Menor frecuencia |

---

## SECCIÓN 8 — SEMÁFOROS DE ESTADO

| Eje | Estado | Nota |
|---|---|---|
| Identidad (Fase 1) | VERDE | Cerrada definitivamente |
| Plugin / técnica | VERDE | v7.0.5 activo en producción |
| Dominio .com.ar | VERDE | Activo · delegado · Hostinger |
| Dominio .ar | ROJO | Pendiente registro NIC Argentina |
| Child theme Newsup | AMARILLO | Tema padre identificado, child theme pendiente de configuración |
| SEO / Rank Math | AMARILLO | Schema NewsMediaOrganization pendiente |
| Imágenes institucionales | AMARILLO | Logo, favicon y OG image pendientes de subir |
| Categorías P01-P06 | ROJO | Sin crear aún |
| Canal WhatsApp broadcast | ROJO | Sin configurar |
| Monetización | ROJO | Esperar hábito y reputación |
| Expansión regional RN33 | PAUSA | Horizonte futuro, no fase activa |

---

## SECCIÓN 9 — VOCES ASOCIADAS

**Fabián Longo** — referencia vinculada a El Diferente (edición papel). Memoria de oficio, experiencia en soporte local.
**Adriana Giménez** — "Voces, otra mirada" y versión radial. Voz, sensibilidad, lectura de comunidad.

*Criterio: sí nombrar, no inventar cargo definitivo. Constelación de trabajo y aporte posible.*

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
5. **Para activar un agente:** referenciar por número (1-11) desde el módulo 10 del panel

**Versionado:** al tomar una decisión nueva → identificar sección afectada → actualizar → cambiar versión (v1.2 → v1.3) → subir al repositorio.

---

## REFERENCIAS

Instituto Nacional de Estadística y Censos. (2022). *Censo Nacional de Población, Hogares y Viviendas 2022*. Gobierno de Argentina. https://www.indec.gob.ar/indec/web/Nivel4-Tema-2-41-165

NIC Argentina. (2026, abril). *Expediente EX-2026-36480557-APN-DNRDI#SLYT: registro elrufino.com.ar*. https://nic.ar

We Are Social & Hootsuite. (2024). *Digital 2024: Argentina*. DataReportal. https://datareportal.com/reports/digital-2024-argentina

---

*Documento vivo. Actualizar al tomar cada decisión relevante.*
*Firma institucional: PROYECTO EL RUFINO*
*Versión 1.2 · 12 de abril de 2026*
