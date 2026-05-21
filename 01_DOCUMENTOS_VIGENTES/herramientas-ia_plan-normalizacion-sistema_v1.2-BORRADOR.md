# HERRAMIENTAS DE IA — Estándar de Normalización de Proyectos
## Documento técnico de proceso ampliado
### Versión 1.2 · 12 de mayo de 2026 · BORRADOR
### Cambios v1.2: sistema de colores con prefijos Unicode · archivos prioritarios para Cowork · prompt de tarea Cowork

---

## PARTE 1 — EL ESTÁNDAR

### 1.1 Principio general

Todo proyecto bajo `F:\HERRAMIENTAS DE IA\PROYECTOS\` debe cumplir un conjunto mínimo
de requisitos estructurales para ser considerado normalizado. EL_RUFINO es el proyecto
de referencia y define el modelo canónico.

Un proyecto normalizado puede ser auditado, transferido a una IA y continuado por
cualquier operador sin fricción.

---

### 1.2 Sistema de jerarquía visual — prefijos Unicode

Las carpetas se identifican visualmente por nivel de criticidad mediante prefijos Unicode.
No requiere software adicional. Funciona en cualquier PC con Windows.

| Prefijo | Nivel | Significado |
|---|---|---|
| 🔴 | CRÍTICA | sin esta carpeta el proyecto no puede operar con IA |
| 🟡 | OPERATIVA | necesaria para trabajar, no bloqueante el día 1 |
| 🟢 | DE SOPORTE | mejora la organización, no urgente |
| ⚪ | ADMINISTRATIVA | siempre presente, baja prioridad operativa |

**Regla:** el prefijo forma parte del nombre de la carpeta en disco.
Al crear o renombrar, respetar el prefijo correspondiente al nivel asignado.

---

### 1.3 Estructura canónica de carpetas

Todo proyecto debe tener exactamente esta estructura interna:

```
NOMBRE_PROYECTO\
├── 🔴 00_CONTEXTO_IA\          fuente de verdad para agentes IA
├── 🟡 01_DOCUMENTOS_VIGENTES\  documentos activos del proyecto
├── 🟡 02_DESARROLLO\           código, diseño, builds
├── 🟢 03_IDENTIDAD\            logos, paletas, marca (si aplica)
├── 🟢 04_REFERENCIA\           dashboards, materiales externos
├── 🟢 05_HISTORICO\            material no operativo
├── 🟢 06_SCRIPTS\              scripts de automatización
├── ⚪ _ACTIVO\                  versión activa en producción (si aplica)
└── ⚪ _ARCHIVO\                 versiones anteriores con fecha
```

**Adaptaciones permitidas:**
- Proyectos sin identidad visual: `🟢 03_IDENTIDAD\` puede omitirse
- Proyectos sin producción activa: `⚪ _ACTIVO\` puede omitirse
- Proyectos sin scripts: `🟢 06_SCRIPTS\` puede omitirse

**Obligatorias en todo proyecto activo sin excepción:**
```
🔴 00_CONTEXTO_IA\
🟡 01_DOCUMENTOS_VIGENTES\
⚪ _ARCHIVO\
```

---

### 1.4 Archivos obligatorios por carpeta

#### 🔴 00_CONTEXTO_IA\ — crítico

| Archivo | Descripción | Obligatorio |
|---|---|---|
| `[proyecto]_prompt-maestro_vX.X-VIGENTE.md` | contexto de transferencia + rol IA | sí |
| `[proyecto]_repositorio-ia_vX.X-VIGENTE.md` | contexto detallado técnico y editorial | sí |
| `[proyecto]_agentes-ia_vX.X-VIGENTE.md` | prompts por agente | si hay agentes definidos |

**Regla de sincronización:** los tres archivos deben tener la misma versión mayor.
Al versionar uno, actualizar los demás en la misma operación.

#### 🟡 01_DOCUMENTOS_VIGENTES\

| Archivo | Descripción |
|---|---|
| `CHANGELOG.md` | registro cronológico de cambios |
| Documentos propios | según naturaleza del proyecto |

#### ⚪ _ARCHIVO\

- Subcarpetas por fecha: `YYYY-MM-DD\`
- Nunca borrar archivos — siempre mover con fecha

---

### 1.5 Convenciones de nomenclatura

**Patrón:**
```
[proyecto]_[descriptor]_v[X.X]-[ESTADO].[ext]
```

**Ejemplos:**
```
el-rufino_prompt-maestro_v1.6-VIGENTE.md
sismuif_repositorio-ia_v1.0-BORRADOR.md
gestion-comercial_plan-auditoria_v1.0-FINAL.md
```

**Estados:**
| Estado | Uso |
|---|---|
| VIGENTE | activo en uso |
| BORRADOR | en elaboración |
| FINAL | cerrado |
| OBSOLETO | reemplazado, pendiente archivar |

**Reglas:**
- Sin espacios · sin tildes · sin caracteres especiales en nombres de archivo
- Siempre minúsculas
- Siempre versión y estado explícitos
- Nunca `_v1` — siempre `_v1.0`

---

### 1.6 Criterios de proyecto sano

| Criterio | Verificación |
|---|---|
| Estructura canónica presente | carpetas obligatorias con prefijo correcto |
| 🔴 00_CONTEXTO_IA\ completo | tres archivos maestros con misma versión |
| Sin duplicados VIGENTE | un solo archivo VIGENTE por serie |
| Sin versiones huérfanas | obsoletos en ⚪ _ARCHIVO\ con fecha |
| Nomenclatura correcta | patrón cumplido en todos los archivos |
| CHANGELOG actualizado | última entrada no mayor a 30 días |
| Sin carpetas obligatorias vacías | toda 🔴 y 🟡 con al menos un archivo |

---

## PARTE 2 — INVENTARIO DE PROYECTOS

### 2.1 Mapa del sistema

```
F:\HERRAMIENTAS DE IA\
├── 00_PANEL_IA\
├── PROYECTOS\
│   ├── EL_RUFINO\                  730 items   · MODELO DE REFERENCIA
│   ├── GESTION_COMERCIAL\          1.777 items · parcialmente documentado
│   ├── SISMUIF\                    24.335 items· mayor volumen · auditoría urgente
│   ├── SOCIEDAD_ITALIANA\          6.684 items · pendiente
│   ├── RADIOTV\                    1.762 items · pendiente
│   ├── DIGESTO\                    1.751 items · pendiente
│   ├── PJ\                         197 items   · pendiente
│   ├── INFOCONECTADOS\             63 items    · pendiente
│   ├── LA_COMUNIDAD_ORGANIZADA\    9 items     · pendiente
│   └── USINA_DE_IDEAS\             hub organizativo
└── _ARCHIVO\
```

### 2.2 Prioridad de intervención

| Proyecto | Items | Prioridad | Motivo |
|---|---|---|---|
| EL_RUFINO | 730 | MODELO | referencia canónica · normalización en curso |
| USINA_DE_IDEAS | — | HUB | núcleo del sistema · auditar primero |
| SISMUIF | 24.335 | URGENTE | mayor volumen · sin documentar |
| GESTION_COMERCIAL | 1.777 | ALTA | activo · parcialmente documentado |
| SOCIEDAD_ITALIANA | 6.684 | ALTA | alto volumen · sin documentar |
| RADIOTV | 1.762 | MEDIA | pendiente |
| DIGESTO | 1.751 | MEDIA | pendiente |
| PJ | 197 | BAJA | bajo volumen |
| INFOCONECTADOS | 63 | BAJA | bajo volumen |
| LA_COMUNIDAD_ORGANIZADA | 9 | BAJA | bajo volumen |

---

## PARTE 3 — PROCESO DE NORMALIZACIÓN

### 3.1 Orden de ejecución

```
FASE 0  →  Cowork lee el disco y genera reporte
FASE 1  →  Normalizar EL_RUFINO (modelo de referencia)
FASE 2  →  Auditar USINA_DE_IDEAS (hub)
FASE 3  →  Normalizar proyectos por prioridad
FASE 4  →  Commit GitHub por proyecto
FASE 5  →  Actualizar proyectos Claude
FASE 6  →  Verificación final
```

### 3.2 Plantilla de intervención por proyecto

```
Paso A — Diagnóstico
  · Comparar estructura real vs. canónica
  · Listar carpetas faltantes
  · Listar archivos faltantes o mal nombrados

Paso B — Intervención
  · Crear carpetas faltantes con prefijo correcto
  · Generar archivos 00_CONTEXTO_IA mínimos
  · Renombrar archivos fuera de convención
  · Mover obsoletos a _ARCHIVO\ con fecha

Paso C — Verificación
  · Confirmar criterios del apartado 1.6

Paso D — Registro
  · Actualizar CHANGELOG.md
  · Commit GitHub si corresponde
```

---

## PARTE 4 — COWORK

### 4.1 Archivos prioritarios que Cowork debe leer

En este orden:

| # | Archivo | Ruta | Propósito |
|---|---|---|---|
| 1 | `herramientas-ia_plan-normalizacion-sistema_v1.2-BORRADOR.md` | este documento | estándar y proceso completo |
| 2 | `usina-de-ideas_prompt-maestro_v1.6-VIGENTE.md` | `EL_RUFINO\00_CONTEXTO_IA\` | contexto del proyecto principal |
| 3 | `usina-de-ideas_repositorio-ia_v1.6-VIGENTE.md` | `EL_RUFINO\00_CONTEXTO_IA\` | arquitectura técnica detallada |
| 4 | `usina-de-ideas_agentes-ia_v1.6-VIGENTE.md` | `EL_RUFINO\00_CONTEXTO_IA\` | agentes disponibles |
| 5 | `scan_sistema_2026-05-12.csv` | `USINA_DE_IDEAS\` | estado real del disco (generar con script) |

**El archivo 5 es el punto de partida operativo. Sin él Cowork no puede auditar.**

---

### 4.2 Script para generar el CSV antes de invocar a Cowork

Ejecutar en PowerShell antes de iniciar la tarea:

```powershell
$raiz = "F:\HERRAMIENTAS DE IA"
$salida = "F:\HERRAMIENTAS DE IA\PROYECTOS\USINA_DE_IDEAS\scan_sistema_2026-05-12.csv"

Get-ChildItem -Path $raiz -Recurse |
  Select-Object `
    @{N="Ruta";E={$_.FullName}},
    @{N="Tipo";E={if($_.PSIsContainer){"Carpeta"}else{"Archivo"}}},
    @{N="Nombre";E={$_.Name}},
    @{N="Extension";E={$_.Extension}},
    @{N="TamanioKB";E={if(-not $_.PSIsContainer){[math]::Round($_.Length/1KB,1)}else{""}}},
    @{N="FechaModif";E={$_.LastWriteTime.ToString("yyyy-MM-dd HH:mm")}},
    @{N="Proyecto";E={
      $rel = $_.FullName.Replace($raiz,"").TrimStart("\")
      $partes = $rel -split "\\"
      if($partes.Count -ge 2 -and $partes[0] -eq "PROYECTOS"){$partes[1]}
      else{$partes[0]}
    }} |
  Export-Csv -Path $salida -NoTypeInformation -Encoding UTF8

Write-Host "Listo: $salida"
```

---

### 4.3 Prompt para Cowork

```
Sos el agente de normalización del sistema HERRAMIENTAS DE IA.

Tu tarea es auditar y normalizar la estructura de carpetas y archivos de
F:\HERRAMIENTAS DE IA\PROYECTOS\ según el estándar definido en el documento
técnico que se adjunta.

DOCUMENTOS DE CONTEXTO (leer en este orden):
1. herramientas-ia_plan-normalizacion-sistema_v1.2-BORRADOR.md  ← estándar completo
2. usina-de-ideas_prompt-maestro_v1.6-VIGENTE.md                ← contexto EL_RUFINO
3. scan_sistema_2026-05-12.csv                                  ← estado real del disco

TAREA — ejecutar en este orden:

PASO 1 — LECTURA
Leer el CSV completo. Para cada proyecto en PROYECTOS\ construir:
  · Lista de carpetas presentes
  · Lista de archivos en 00_CONTEXTO_IA\ (si existe)
  · Comparación contra estructura canónica del documento técnico

PASO 2 — DIAGNÓSTICO
Por cada proyecto generar una tabla con:
  · Carpetas faltantes (con el prefijo Unicode que deberían tener)
  · Archivos faltantes en carpetas críticas (🔴)
  · Archivos con nomenclatura incorrecta
  · Versiones huérfanas (VIGENTE duplicado o sin archivar)

PASO 3 — PLAN DE INTERVENCIÓN
Para cada proyecto con deficiencias listar las acciones concretas en orden:
  1. Crear carpeta X con prefijo Y
  2. Generar archivo Z en carpeta W
  3. Renombrar archivo A a B
  4. Mover archivo C a _ARCHIVO\YYYY-MM-DD\

PASO 4 — PRIORIZACIÓN
Ordenar las intervenciones según la tabla de prioridades del documento técnico.
Empezar siempre por EL_RUFINO (modelo de referencia) y USINA_DE_IDEAS (hub).

REGLAS:
· Nunca borrar archivos — siempre mover a _ARCHIVO\ con fecha
· Sin espacios ni tildes en nombres de archivos
· Scripts PowerShell: solo ASCII en el código
· Confirmar cada acción destructiva antes de ejecutar
· Registrar todo en CHANGELOG.md del proyecto correspondiente

ENTREGA ESPERADA:
Un reporte por proyecto con: estado actual · deficiencias · acciones a ejecutar · orden de prioridad.
```

---

*Documento técnico ampliado · Herramientas de IA · Usina de Ideas*
*Versión 1.2 · 12 de mayo de 2026 · BORRADOR*
