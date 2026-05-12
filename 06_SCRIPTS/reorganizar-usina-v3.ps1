#Requires -Version 5.1
<#
.SYNOPSIS
    USINA DE IDEAS — Reorganización estructural completa
    Auditoría 2026-05-11 · Script v3

.DESCRIPTION
    Mueve zips a _RELEASES\, código activo a _ACTIVO\, docs a _DOCS\.
    NO elimina nada. Genera log de todo lo movido.
    Ejecutar desde PowerShell como usuario con permisos de escritura en F:\

.NOTES
    Autor: USINA DE IDEAS (generado por Claude 2026-05-11)
    REVISAR las secciones marcadas con [CONFIRMAR] antes de ejecutar.
#>

$ErrorActionPreference = 'Continue'
$BASE = "F:\HERRAMIENTAS DE IA"
$LOG  = "$BASE\reorganizacion_$(Get-Date -Format 'yyyyMMdd_HHmm').log"

function Log($msg) {
    $line = "$(Get-Date -Format 'HH:mm:ss') $msg"
    Write-Host $line
    Add-Content -Path $LOG -Value $line -Encoding UTF8
}

function Move-Safe {
    param([string]$From, [string]$To)
    if (-not (Test-Path $From)) {
        Log "  [SKIP] No existe: $From"
        return
    }
    $toDir = Split-Path $To -Parent
    if (-not (Test-Path $toDir)) {
        New-Item -ItemType Directory -Path $toDir -Force | Out-Null
    }
    if (Test-Path $To) {
        Log "  [SKIP] Destino ya existe: $To"
        return
    }
    Move-Item -Path $From -Destination $To -Force
    Log "  [OK] $From  →  $To"
}

Log "═══════════════════════════════════════════════════════"
Log "  USINA DE IDEAS — Reorganización · $(Get-Date -Format 'yyyy-MM-dd HH:mm')"
Log "═══════════════════════════════════════════════════════"

# ─────────────────────────────────────────────────────────
# PASO A — EL RUFINO: código activo → _ACTIVO\
# ─────────────────────────────────────────────────────────
Log ""
Log "── EL RUFINO: _ACTIVO\plugin\ ──────────────────────────"
$ER = "$BASE\PROYECTOS\EL_RUFINO"
$WT = "$ER\02_WORDPRESS_TEST"

# Plugin PHP sueltos en 02_WORDPRESS_TEST → _ACTIVO\plugin\
$pluginFiles = @(
    "el-rufino.php",
    "el-rufino-panel.php",
    "auditoria-rufino.php",
    "el-rufino_functions_v2.php",
    "header.php"
)
foreach ($f in $pluginFiles) {
    Move-Safe "$WT\$f" "$ER\_ACTIVO\plugin\$f"
}

# Último theme activo (v2.3.4) → _ACTIVO\theme\
Log ""
Log "── EL RUFINO: _ACTIVO\theme\ ───────────────────────────"
$themeDir = "$WT\el-rufino-theme-v2.3.4"
if (Test-Path $themeDir) {
    Move-Safe $themeDir "$ER\_ACTIVO\theme\el-rufino-theme-v2.3.4"
}

# Child theme activo → _ACTIVO\theme\
$childDir = "$WT\el-rufino_child-theme_ACTIVO"
if (Test-Path $childDir) {
    Move-Safe $childDir "$ER\_ACTIVO\theme\el-rufino-child"
}

# Assets de identidad → _ACTIVO\assets\
Log ""
Log "── EL RUFINO: _ACTIVO\assets\ ──────────────────────────"
$marca = "$ER\03_IDENTIDAD_MARCA"
Get-ChildItem -Path $marca -File | ForEach-Object {
    if ($_.Extension -in '.jpg','.jpeg','.png','.svg','.webp','.ico') {
        Move-Safe $_.FullName "$ER\_ACTIVO\assets\$($_.Name)"
    }
}

# ─────────────────────────────────────────────────────────
# PASO B — EL RUFINO: _DOCS\
# ─────────────────────────────────────────────────────────
Log ""
Log "── EL RUFINO: _DOCS\ ───────────────────────────────────"
# CHANGELOG ya está en raíz — mover a _DOCS\
Move-Safe "$ER\CHANGELOG.md" "$ER\_DOCS\CHANGELOG.md"
Move-Safe "$WT\README.md"    "$ER\_DOCS\README_WP.md"
Move-Safe "$WT\CHANGELOG.md" "$ER\_DOCS\CHANGELOG_WP.md"

# ─────────────────────────────────────────────────────────
# PASO C — EL RUFINO: todos los .zip → _RELEASES\
# ─────────────────────────────────────────────────────────
Log ""
Log "── EL RUFINO: _RELEASES\ ───────────────────────────────"
Get-ChildItem -Path $ER -Recurse -Filter "*.zip" |
    Where-Object { $_.FullName -notlike "*\_RELEASES\*" } |
    ForEach-Object {
        $destName = $_.Name
        $destPath = "$ER\_RELEASES\$destName"
        # Si el nombre ya existe en destino, prefijar con carpeta de origen
        if (Test-Path $destPath) {
            $parentFolder = $_.Directory.Name
            $destName = "${parentFolder}__$($_.Name)"
            $destPath = "$ER\_RELEASES\$destName"
        }
        Move-Safe $_.FullName $destPath
    }

# ─────────────────────────────────────────────────────────
# PASO D — INFOCONECTADOS: zips → _RELEASES\
# ─────────────────────────────────────────────────────────
Log ""
Log "── INFOCONECTADOS: _RELEASES\ ──────────────────────────"
$IC = "$BASE\PROYECTOS\INFOCONECTADOS"
Get-ChildItem -Path $IC -Recurse -Filter "*.zip" |
    Where-Object { $_.FullName -notlike "*\_RELEASES\*" } |
    ForEach-Object {
        $destName = $_.Name
        $destPath = "$IC\_RELEASES\$destName"
        if (Test-Path $destPath) {
            $parentFolder = $_.Directory.Name
            $destName = "${parentFolder}__$($_.Name)"
            $destPath = "$IC\_RELEASES\$destName"
        }
        Move-Safe $_.FullName $destPath
    }

# ─────────────────────────────────────────────────────────
# PASO E — SISMUIF: zips → _RELEASES\
# ─────────────────────────────────────────────────────────
Log ""
Log "── SISMUIF: _RELEASES\ ─────────────────────────────────"
$SM = "$BASE\PROYECTOS\SISMUIF"
Get-ChildItem -Path $SM -Recurse -Filter "*.zip" |
    Where-Object { $_.FullName -notlike "*\_RELEASES\*" } |
    ForEach-Object {
        $destName = $_.Name
        $destPath = "$SM\_RELEASES\$destName"
        if (Test-Path $destPath) {
            $parentFolder = $_.Directory.Name
            $destName = "${parentFolder}__$($_.Name)"
            $destPath = "$SM\_RELEASES\$destName"
        }
        Move-Safe $_.FullName $destPath
    }

# ─────────────────────────────────────────────────────────
# PASO F — DIGESTO: zips → _RELEASES\
# ─────────────────────────────────────────────────────────
Log ""
Log "── DIGESTO: _RELEASES\ ─────────────────────────────────"
$DG = "$BASE\PROYECTOS\DIGESTO"
Get-ChildItem -Path $DG -Recurse -Filter "*.zip" |
    Where-Object { $_.FullName -notlike "*\_RELEASES\*" } |
    ForEach-Object {
        Move-Safe $_.FullName "$DG\_RELEASES\$($_.Name)"
    }

# ─────────────────────────────────────────────────────────
# PASO G — SOCIEDAD ITALIANA: zips → _RELEASES\
# ─────────────────────────────────────────────────────────
Log ""
Log "── SOCIEDAD ITALIANA: _RELEASES\ ───────────────────────"
$SI = "$BASE\PROYECTOS\SOCIEDAD_ITALIANA"
Get-ChildItem -Path $SI -Recurse -Filter "*.zip" |
    Where-Object { $_.FullName -notlike "*\_RELEASES\*" } |
    ForEach-Object {
        Move-Safe $_.FullName "$SI\_RELEASES\$($_.Name)"
    }

# ─────────────────────────────────────────────────────────
# PASO H — GESTIÓN COMERCIAL: zips → _RELEASES\
# [CONFIRMAR] — gestionpro ya está en 05_MATERIAL_HISTORICO\_ARCHIVO
# ─────────────────────────────────────────────────────────
Log ""
Log "── GESTIÓN COMERCIAL: _RELEASES\ ──────────────────────"
$GC = "$BASE\PROYECTOS\GESTIÓN COMERCIAL"
Get-ChildItem -Path $GC -Recurse -Filter "*.zip" |
    Where-Object { $_.FullName -notlike "*\_RELEASES\*" } |
    ForEach-Object {
        $destName = $_.Name
        $destPath = "$GC\_RELEASES\$destName"
        if (Test-Path $destPath) {
            $parentFolder = $_.Directory.Name
            $destName = "${parentFolder}__$($_.Name)"
            $destPath = "$GC\_RELEASES\$destName"
        }
        Move-Safe $_.FullName $destPath
    }

# ─────────────────────────────────────────────────────────
# PASO I — PARTIDO JUSTICIALISTA: zips → _RELEASES\
# ─────────────────────────────────────────────────────────
Log ""
Log "── PARTIDO JUSTICIALISTA: _RELEASES\ ───────────────────"
$PJ = "$BASE\PROYECTOS\PARTIDO_JUSTICIALISTA"
Get-ChildItem -Path $PJ -Recurse -Filter "*.zip" |
    Where-Object { $_.FullName -notlike "*\_RELEASES\*" } |
    ForEach-Object {
        Move-Safe $_.FullName "$PJ\_RELEASES\$($_.Name)"
    }

# ─────────────────────────────────────────────────────────
# PASO J — RADIOTV: zips → _RELEASES\
# ─────────────────────────────────────────────────────────
Log ""
Log "── RADIOTV: _RELEASES\ ─────────────────────────────────"
$RTV = "$BASE\PROYECTOS\RADIOTV"
Get-ChildItem -Path $RTV -Recurse -Filter "*.zip" |
    Where-Object { $_.FullName -notlike "*\_RELEASES\*" } |
    ForEach-Object {
        Move-Safe $_.FullName "$RTV\_RELEASES\$($_.Name)"
    }

# ─────────────────────────────────────────────────────────
# PASO K — git init en _ACTIVO\plugin\ (repo separado para el plugin)
# ─────────────────────────────────────────────────────────
Log ""
Log "── GIT INIT _ACTIVO\plugin\ ────────────────────────────"
$pluginPath = "$ER\_ACTIVO\plugin"
if (Test-Path "$pluginPath\.git") {
    Log "  [OK] .git ya existe en $pluginPath"
} else {
    Push-Location $pluginPath
    git init
    Pop-Location
    Log "  [OK] git init en $pluginPath"
}

# ─────────────────────────────────────────────────────────
# FIN
# ─────────────────────────────────────────────────────────
Log ""
Log "═══════════════════════════════════════════════════════"
Log "  COMPLETADO. Log en: $LOG"
Log "  Los archivos originales NO fueron eliminados."
Log "  Revisá duplicados con el reporte de auditoría."
Log "═══════════════════════════════════════════════════════"
