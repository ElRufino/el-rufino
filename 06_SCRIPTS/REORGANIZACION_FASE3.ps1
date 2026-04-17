# =============================================================================
# ARCHIVO: REORGANIZACION_FASE3.ps1
# PROYECTO: USINA DE IDEAS · Repositorio de herramientas IA
# FECHA: 2026-04-17
# PROPÓSITO: FASE 3 — Mover repo git a EL_RUFINO + clasificar 07_VARIOS_ARCHIVO
# GUARDAR EN: F:\HERRAMIENTAS DE IA\PROYECTOS\EL_RUFINO\06_SCRIPTS\
# =============================================================================

Set-StrictMode -Version Latest
$ErrorActionPreference = "Stop"

# --- RUTAS BASE ---
$raiz       = "F:\HERRAMIENTAS DE IA"
$proyectos  = "$raiz\PROYECTOS"
$elRufino   = "$proyectos\EL_RUFINO"
$usinaDocs  = "$proyectos\USINA_DE_IDEAS"
$repoViejo  = "$usinaDocs\EL RUFINO"
$varios     = "$elRufino\07_VARIOS_ARCHIVO"
$archivo    = "$elRufino\_ARCHIVO"
$fecha      = "2026-04-17"
$logFile    = "$raiz\reorganizacion_fase3_$fecha.log"

# --- FUNCIÓN DE LOG ---
function Log {
    param([string]$msg)
    $line = "[$(Get-Date -Format 'HH:mm:ss')] $msg"
    Write-Host $line
    Add-Content -Path $logFile -Value $line
}

# =============================================================================
# PASO 0 — Verificaciones previas
# =============================================================================
Log "=== REORGANIZACION FASE 3 — INICIO ==="
Log ""

$errores = 0

if (-not (Test-Path $repoViejo)) {
    Log "ERROR: No se encuentra $repoViejo"
    $errores++
}
if (-not (Test-Path "$repoViejo\.git")) {
    Log "ERROR: $repoViejo no contiene .git"
    $errores++
}
if (-not (Test-Path $elRufino)) {
    Log "ERROR: No se encuentra $elRufino"
    $errores++
}
if (Test-Path "$elRufino\.git") {
    Log "ADVERTENCIA: $elRufino ya tiene .git — revisar antes de continuar"
    $errores++
}
if (-not (Test-Path $varios)) {
    Log "ADVERTENCIA: No se encuentra $varios — se omite clasificación"
}

if ($errores -gt 0) {
    Log ""
    Log "Se encontraron $errores error(es). Corrija antes de continuar."
    exit 1
}

Log "Verificaciones OK"
Log ""

# =============================================================================
# PASO 1 — Mover repo git de USINA_DE_IDEAS\EL RUFINO\ a EL_RUFINO\
# =============================================================================
Log "--- PASO 1: Mover repo git a EL_RUFINO\ ---"

# Mover .git
Log "Moviendo .git..."
Move-Item -Path "$repoViejo\.git" -Destination "$elRufino\.git"
Log "  .git → $elRufino\.git  OK"

# Mover CHANGELOG.md si existe
if (Test-Path "$repoViejo\CHANGELOG.md") {
    # Si ya existe un CHANGELOG en EL_RUFINO, preservar el viejo en _ARCHIVO
    if (Test-Path "$elRufino\CHANGELOG.md") {
        $destArchivo = "$archivo\CHANGELOG_USINA_$fecha.md"
        Log "  CHANGELOG.md ya existe en EL_RUFINO — archivando versión de USINA_DE_IDEAS en _ARCHIVO\"
        Move-Item -Path "$repoViejo\CHANGELOG.md" -Destination $destArchivo
        Log "  CHANGELOG.md → $destArchivo  OK"
    } else {
        Move-Item -Path "$repoViejo\CHANGELOG.md" -Destination "$elRufino\CHANGELOG.md"
        Log "  CHANGELOG.md → $elRufino\CHANGELOG.md  OK"
    }
}

# Archivar carpeta vacía residual
$contenidoRestante = Get-ChildItem -Path $repoViejo -Force
if ($contenidoRestante.Count -eq 0) {
    Remove-Item -Path $repoViejo -Force
    Log "  Carpeta residual 'EL RUFINO' eliminada (estaba vacía)"
} else {
    $destResidual = "$raiz\_ARCHIVO\EL_RUFINO_RESIDUAL_$fecha"
    Log "  Carpeta no está vacía — archivando en _ARCHIVO\"
    Move-Item -Path $repoViejo -Destination $destResidual
    Log "  EL RUFINO\ → $destResidual  OK"
}

Log ""

# =============================================================================
# PASO 2 — Clasificar 07_VARIOS_ARCHIVO
# =============================================================================
Log "--- PASO 2: Clasificar 07_VARIOS_ARCHIVO ---"

if (-not (Test-Path $varios)) {
    Log "  07_VARIOS_ARCHIVO no encontrada — omitiendo PASO 2"
} else {

    # Destinos canónicos
    $dest = @{
        wordpress   = "$elRufino\02_WORDPRESS_TEST"
        identidad   = "$elRufino\03_IDENTIDAD_MARCA"
        dashboards  = "$elRufino\04_DASHBOARDS_REFERENCIA"
        scripts     = "$elRufino\06_SCRIPTS"
        docs        = "$elRufino\01_DOCUMENTOS_VIGENTES"
        noClasif    = "$archivo\VARIOS_NO_CLASIFICADO_$fecha"
    }

    # Crear destino para no clasificados si hace falta
    foreach ($d in $dest.Values) {
        if (-not (Test-Path $d)) {
            New-Item -ItemType Directory -Path $d -Force | Out-Null
        }
    }

    # Mover archivos conocidos
    $movimientos = @(
        @{ nombre = "el-rufino-plugin.zip";             destino = $dest.wordpress  },
        @{ nombre = "auditoria-rufino.php";             destino = $dest.wordpress  },
        @{ nombre = ".elrufino_scan_latest.json";       destino = $dest.scripts    },
        @{ nombre = "la comunidad organizada.txt";      destino = $dest.docs       }
    )

    foreach ($m in $movimientos) {
        $origen = "$varios\$($m.nombre)"
        if (Test-Path $origen) {
            Move-Item -Path $origen -Destination "$($m.destino)\$($m.nombre)"
            Log "  $($m.nombre) → $($m.destino)  OK"
        } else {
            Log "  $($m.nombre) — no encontrado (omitido)"
        }
    }

    # Dashboards HTML → 04_DASHBOARDS_REFERENCIA
    $htmls = Get-ChildItem -Path $varios -Filter "*.html" -File
    foreach ($html in $htmls) {
        Move-Item -Path $html.FullName -Destination "$($dest.dashboards)\$($html.Name)"
        Log "  $($html.Name) → 04_DASHBOARDS_REFERENCIA  OK"
    }

    # Imágenes → 03_IDENTIDAD_MARCA
    $imagenes = Get-ChildItem -Path $varios -Include "*.png","*.jpg","*.jpeg","*.webp","*.svg" -File
    foreach ($img in $imagenes) {
        Move-Item -Path $img.FullName -Destination "$($dest.identidad)\$($img.Name)"
        Log "  $($img.Name) → 03_IDENTIDAD_MARCA  OK"
    }

    # Lo que quede sin clasificar → _ARCHIVO
    $restantes = Get-ChildItem -Path $varios -File -Force
    if ($restantes.Count -gt 0) {
        Log ""
        Log "  Archivos no clasificados ($($restantes.Count)):"
        foreach ($r in $restantes) {
            Move-Item -Path $r.FullName -Destination "$($dest.noClasif)\$($r.Name)"
            Log "    $($r.Name) → VARIOS_NO_CLASIFICADO_$fecha"
        }
    }

    # Eliminar carpeta si quedó vacía
    $quedan = Get-ChildItem -Path $varios -Force
    if ($quedan.Count -eq 0) {
        Remove-Item -Path $varios -Force
        Log ""
        Log "  07_VARIOS_ARCHIVO eliminada (vacía tras clasificación)"
    } else {
        Log ""
        Log "  07_VARIOS_ARCHIVO conservada — revisar manualmente"
    }
}

Log ""

# =============================================================================
# RESUMEN FINAL
# =============================================================================
Log "=== FASE 3 COMPLETADA ==="
Log ""
Log "Acciones ejecutadas:"
Log "  · Repo git movido a EL_RUFINO\"
Log "  · 07_VARIOS_ARCHIVO clasificada"
Log ""
Log "Próximos pasos:"
Log "  · Verificar git status en EL_RUFINO\"
Log "  · FASE 4: renombrar archivos maestros el-rufino_* → usina-de-ideas_*"
Log "  · Actualizar CHANGELOG.md y REGISTRO_TRAZABILIDAD.md"
Log ""
Log "Log guardado en: $logFile"
Log "=== FIN ==="
