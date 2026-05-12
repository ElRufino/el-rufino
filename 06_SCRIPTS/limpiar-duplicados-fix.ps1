# Limpieza final - duplicados con caracteres especiales
# Usa -LiteralPath para parentesis, tildes y enies

$BASE = "F:\HERRAMIENTAS DE IA\PROYECTOS"

function Del($path) {
    if (Test-Path -LiteralPath $path) {
        Remove-Item -LiteralPath $path -Force
        Write-Host "[DEL] $path"
    } else {
        Write-Host "[SKIP] No existe: $path"
    }
}

Write-Host "=== LIMPIEZA FINAL ==="

# SISMUIF
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

# GESTION COMERCIAL
$gcNombre = "GESTI" + [char]0x00D3 + "N COMERCIAL"
$GC = "$BASE\$gcNombre\_RELEASES"
Del "$GC\gestionpro-v193-FINAL (1).zip"
Del "$GC\gp-landing-plugin (1).zip"
Del "$GC\gp-landing-plugin (2).zip"
Del "$GC\infoconectados-theme-v2.zip"

# RADIOTV
Del "$BASE\RADIOTV\_RELEASES\RadioAuto_Fase1 (1).zip"

# PARTIDO JUSTICIALISTA - nombre con N con tilde
$cam = "Fuerza_Patria__Chat de WhatsApp con CAMPA" + [char]0x00D1 + "A FUERZA PATRIA .zip"
Del "$BASE\PARTIDO_JUSTICIALISTA\_RELEASES\$cam"
Del "$BASE\PARTIDO_JUSTICIALISTA\_RELEASES\Fuerza_Patria__Chat de WhatsApp con PJ Distrito Rufino 2024.zip"
Del "$BASE\PARTIDO_JUSTICIALISTA\_RELEASES\Fuerza_Patria__Elecciones_Rufino_2025_Paquete_Oficial.zip"

Write-Host "`n=== LISTO ==="
