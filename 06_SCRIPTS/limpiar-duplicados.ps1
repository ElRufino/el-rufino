# USINA DE IDEAS - Limpieza de duplicados (2026-05-11)
# Eliminacion confirmada por el usuario

$ErrorActionPreference = 'Continue'
$BASE = "F:\HERRAMIENTAS DE IA\PROYECTOS"
$LOG  = "F:\HERRAMIENTAS DE IA\limpieza_$(Get-Date -Format 'yyyyMMdd_HHmm').log"

function Del($path) {
    if (Test-Path $path) {
        Remove-Item -Path $path -Recurse -Force
        $msg = "[DEL] $path"
    } else {
        $msg = "[SKIP] No existe: $path"
    }
    Write-Host $msg
    Add-Content -Path $LOG -Value "$(Get-Date -Format 'HH:mm:ss') $msg" -Encoding UTF8
}

Write-Host "=== LIMPIEZA DUPLICADOS === $(Get-Date -Format 'yyyy-MM-dd HH:mm') ==="

# --- GRUPO 1: SISMUIF duplicados exactos ---
Write-Host "`n-- SISMUIF"
$SM = "$BASE\SISMUIF\_RELEASES"
Del "$SM\sismuif-connector-v3.0 (1).zip"
Del "$SM\sismuif-connector-v3.3 (1).zip"
Del "$SM\sismuif-connector-v3.3 (2).zip"
Del "$SM\SISMUIF-Framework-v3.7 (1).zip"
Del "$SM\SISMUIF-Framework-v3.7 (2).zip"
Del "$SM\visual-sismuif-theme (1).zip"
Del "$SM\SISMUIF_INSTALLER_v1.0 (1).zip"
Del "$SM\sismuif-digesto (1).zip"
Del "$SM\11__SISMUIF-Framework-v3.7.zip"
Del "$SM\11__visual-sismuif-theme.zip"

# --- GRUPO 1: GESTION COMERCIAL ---
Write-Host "`n-- GESTION COMERCIAL"
$gcNombre = "GESTI" + [char]0x00D3 + "N COMERCIAL"
$GC = "$BASE\$gcNombre\_RELEASES"
Del "$GC\gestionpro-v193-FINAL (1).zip"
Del "$GC\gp-landing-plugin (1).zip"
Del "$GC\gp-landing-plugin (2).zip"

# --- GRUPO 1: RADIOTV ---
Write-Host "`n-- RADIOTV"
Del "$BASE\RADIOTV\_RELEASES\RadioAuto_Fase1 (1).zip"

# --- GRUPO 1: PARTIDO JUSTICIALISTA ---
Write-Host "`n-- PARTIDO JUSTICIALISTA"
$PJ = "$BASE\PARTIDO_JUSTICIALISTA\_RELEASES"
Del "$PJ\Fuerza_Patria__Chat de WhatsApp con CAMPAÑA FUERZA PATRIA .zip"
Del "$PJ\Fuerza_Patria__Chat de WhatsApp con PJ Distrito Rufino 2024.zip"
Del "$PJ\Fuerza_Patria__Elecciones_Rufino_2025_Paquete_Oficial.zip"

# --- GRUPO 2: Archivo mal ubicado ---
Write-Host "`n-- Archivo mal ubicado"
Del "$GC\infoconectados-theme-v2.zip"

# --- GRUPO 3: Binarios .NET compilados ---
Write-Host "`n-- Binarios .NET (BASURA_TECNICA y COMPILADOS)"
Del "$BASE\$gcNombre\05_MATERIAL_HISTORICO\_ARCHIVO\BASURA_TECNICA_2026-04-17_0456"
Del "$BASE\RADIOTV\_ARCHIVO\COMPILADOS_NET_2026-05-11"
Del "$BASE\SOCIEDAD_ITALIANA\_ARCHIVO\COMPILADOS_NET_2026-05-11"

Write-Host "`n=== COMPLETADO. Log: $LOG ==="
