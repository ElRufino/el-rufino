# USINA DE IDEAS - Fix script v3.1 (2026-05-11)
# Completa lo que v3 no pudo: themes, assets, zips de proyectos pendientes
# Ejecutar: & "F:\HERRAMIENTAS DE IA\PROYECTOS\EL_RUFINO\06_SCRIPTS\reorganizar-usina-v3-fix.ps1"

$ErrorActionPreference = 'Continue'
$BASE = "F:\HERRAMIENTAS DE IA"
$LOG  = "$BASE\reorganizacion_fix_$(Get-Date -Format 'yyyyMMdd_HHmm').log"

function Log($msg) {
    $line = "$(Get-Date -Format 'HH:mm:ss') $msg"
    Write-Host $line
    Add-Content -Path $LOG -Value $line -Encoding UTF8
}

function Move-Safe {
    param([string]$From, [string]$To)
    if (-not (Test-Path $From)) { Log "  [SKIP-NE] $From"; return }
    $toDir = Split-Path $To -Parent
    if (-not (Test-Path $toDir)) { New-Item -ItemType Directory -Path $toDir -Force | Out-Null }
    if (Test-Path $To) { Log "  [SKIP-EX] $To"; return }
    Move-Item -Path $From -Destination $To -Force
    Log "  [OK] $From -> $To"
}

function Move-Zips {
    param([string]$ProjectPath, [string]$ReleasesPath)
    if (-not (Test-Path $ProjectPath)) { Log "  [SKIP] Ruta no existe: $ProjectPath"; return }
    $zips = Get-ChildItem -Path $ProjectPath -Recurse -Filter "*.zip" -ErrorAction SilentlyContinue |
            Where-Object { $_.FullName -notlike "*\_RELEASES\*" }
    if (-not $zips) { Log "  [INFO] Sin zips pendientes en: $ProjectPath"; return }
    foreach ($z in $zips) {
        $dest = Join-Path $ReleasesPath $z.Name
        if (Test-Path $dest) {
            $dest = Join-Path $ReleasesPath ("$($z.Directory.Name)__" + $z.Name)
        }
        Move-Safe $z.FullName $dest
    }
}

Log "=== FIX v3.1 === $(Get-Date -Format 'yyyy-MM-dd HH:mm') ==="

# --- EL RUFINO: theme activo ---
Log ""
Log "--- EL RUFINO: theme activo ---"
$WT = "$BASE\PROYECTOS\EL_RUFINO\02_WORDPRESS_TEST"
$ER = "$BASE\PROYECTOS\EL_RUFINO"

# Theme v2.3.4 (la carpeta contiene subcarpeta el-rufino-theme)
$themeV = "$WT\el-rufino-theme-v2.3.4"
if (Test-Path $themeV) {
    Move-Safe $themeV "$ER\_ACTIVO\theme\el-rufino-theme-v2.3.4"
    Log "  [OK] Theme v2.3.4 movido"
} else {
    Log "  [INFO] No encontrado: $themeV"
}

# Child theme activo
$childSrc = "$WT\el-rufino_child-theme_ACTIVO"
if (Test-Path $childSrc) {
    Move-Safe $childSrc "$ER\_ACTIVO\theme\el-rufino-child"
} else {
    Log "  [INFO] No encontrado: $childSrc"
}

# --- EL RUFINO: assets ---
Log ""
Log "--- EL RUFINO: assets ---"
$marca = "$ER\03_IDENTIDAD_MARCA"
if (Test-Path $marca) {
    $imgs = Get-ChildItem -Path $marca -File -ErrorAction SilentlyContinue |
            Where-Object { $_.Extension -in '.jpg','.jpeg','.png','.svg','.webp','.ico' }
    foreach ($img in $imgs) {
        Move-Safe $img.FullName "$ER\_ACTIVO\assets\$($img.Name)"
    }
} else {
    Log "  [INFO] No encontrado: $marca"
}

# --- EL RUFINO: zips restantes ---
Log ""
Log "--- EL RUFINO: zips -> _RELEASES ---"
Move-Zips $ER "$ER\_RELEASES"

# --- SISMUIF ---
Log ""
Log "--- SISMUIF: zips -> _RELEASES ---"
$SM = "$BASE\PROYECTOS\SISMUIF"
Move-Zips $SM "$SM\_RELEASES"

# --- DIGESTO ---
Log ""
Log "--- DIGESTO: zips -> _RELEASES ---"
$DG = "$BASE\PROYECTOS\DIGESTO"
Move-Zips $DG "$DG\_RELEASES"

# --- SOCIEDAD ITALIANA ---
Log ""
Log "--- SOCIEDAD ITALIANA: zips -> _RELEASES ---"
$SI = "$BASE\PROYECTOS\SOCIEDAD_ITALIANA"
Move-Zips $SI "$SI\_RELEASES"

# --- PARTIDO JUSTICIALISTA ---
Log ""
Log "--- PARTIDO JUSTICIALISTA: zips -> _RELEASES ---"
$PJ = "$BASE\PROYECTOS\PARTIDO_JUSTICIALISTA"
Move-Zips $PJ "$PJ\_RELEASES"

# --- RADIOTV ---
Log ""
Log "--- RADIOTV: zips -> _RELEASES ---"
$RTV = "$BASE\PROYECTOS\RADIOTV"
Move-Zips $RTV "$RTV\_RELEASES"

# --- GESTION COMERCIAL (sin acento para evitar bug de codificacion) ---
Log ""
Log "--- GESTION COMERCIAL: zips -> _RELEASES ---"
$gcNombre = "GESTI" + [char]0x00D3 + "N COMERCIAL"
$GC = "$BASE\PROYECTOS\$gcNombre"
Log "  Ruta: $GC"
Log "  Existe: $(Test-Path $GC)"
Move-Zips $GC "$GC\_RELEASES"

# --- FIN ---
Log ""
Log "=== FIX COMPLETADO. Log: $LOG ==="
Log "    Archivos originales NO eliminados."
