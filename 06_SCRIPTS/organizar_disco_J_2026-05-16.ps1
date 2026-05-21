# organizar_disco_J_2026-05-16.ps1
# Ejecutar como Administrador en PowerShell
# Disco: J:\ (192.168.120.40 - backup 21/06/2024)
# Fecha: 2026-05-16
# ADVERTENCIA: Este script elimina archivos definitivamente en el PASO 1.
#              Los pasos 2 y 3 solo mueven, no eliminan.

Set-StrictMode -Version Latest
$ErrorActionPreference = "Stop"

$LOG = "J:\_organizar_disco_log_2026-05-16.txt"

function Log($msg) {
    $ts = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
    $line = "[$ts] $msg"
    Write-Host $line
    Add-Content -Path $LOG -Value $line -Encoding UTF8
}

function SizeMB($path) {
    if (Test-Path $path -PathType Container) {
        $sz = (Get-ChildItem $path -Recurse -File | Measure-Object -Property Length -Sum).Sum
    } elseif (Test-Path $path -PathType Leaf) {
        $sz = (Get-Item $path).Length
    } else { return "0" }
    return [math]::Round($sz / 1MB, 1)
}

# Verificar que J:\ existe
if (-not (Test-Path "J:\")) {
    Write-Host "ERROR: J:\ no encontrado. Montar el disco antes de ejecutar."
    exit 1
}

New-Item -Path $LOG -ItemType File -Force | Out-Null
Log "=== INICIO organizar_disco_J_2026-05-16.ps1 ==="
Log "Disco: J:\"

# =====================================================================
# PASO 1 - ELIMINAR MALWARE CONFIRMADO
# =====================================================================

Log ""
Log "==================================================================="
Log "PASO 1 - ELIMINAR MALWARE CONFIRMADO"
Log "==================================================================="

$malware = @(
    "J:\BACK 21-06-2024\Desktop\Stremio+4.4.168.exe",
    "J:\BACK 21-06-2024\Desktop\Stremio.lnk",
    "J:\AUTOMATIZACION RADIO\VM1X.PR0.22.AP.ZENTINELS.rar",
    "J:\AUTOMATIZACION RADIO\vMix Pro 22.0.0.66.rar",
    "J:\COMPRIMIDOS\c0rDR4W2K18.AP.ZENTINELS.P0RT4BL3.rar",
    "J:\BACKUP - 08-03-2023\DANILO\Desktop\fifa14-3dm.exe",
    "J:\BACKUP - 08-03-2023\DANILO\Desktop\Crack\fifa14-3dm.exe",
    "J:\BACKUP - 08-03-2023\DANILO\Desktop\Crack\fifa14.exe"
)

$malwareFolders = @(
    "J:\AUTOMATIZACION RADIO\VM1X.PR0.22.AP.ZENTINELS",
    "J:\AUTOMATIZACION RADIO\vMix Pro 22.0.0.66"
)

$paso1_eliminados = 0
$paso1_errores = 0

foreach ($f in $malware) {
    if (Test-Path $f) {
        $sz = SizeMB $f
        try {
            Remove-Item -Path $f -Force
            if (-not (Test-Path $f)) {
                Log "  ELIMINADO [$sz MB]: $f"
                $paso1_eliminados++
            } else {
                Log "  ERROR: Test-Path sigue True: $f"
                $paso1_errores++
            }
        } catch {
            Log "  ERROR al eliminar $f : $_"
            $paso1_errores++
        }
    } else {
        Log "  NO ENCONTRADO (ya eliminado o ruta incorrecta): $f"
    }
}

foreach ($d in $malwareFolders) {
    if (Test-Path $d) {
        $sz = SizeMB $d
        try {
            Remove-Item -Path $d -Recurse -Force
            if (-not (Test-Path $d)) {
                Log "  ELIMINADA CARPETA [$sz MB]: $d"
                $paso1_eliminados++
            } else {
                Log "  ERROR: Test-Path sigue True: $d"
                $paso1_errores++
            }
        } catch {
            Log "  ERROR al eliminar carpeta $d : $_"
            $paso1_errores++
        }
    } else {
        Log "  NO ENCONTRADO (ya eliminado o ruta incorrecta): $d"
    }
}

Log "PASO 1 COMPLETO: $paso1_eliminados operaciones OK / $paso1_errores errores"

# =====================================================================
# PASO 2 - MOVER DUPLICADOS A PAPELERA
# =====================================================================

Log ""
Log "==================================================================="
Log "PASO 2 - MOVER DUPLICADOS A PAPELERA"
Log "==================================================================="

$papelera = "J:\_PAPELERA_DISCO\2026-05-16"
New-Item -Path $papelera -ItemType Directory -Force | Out-Null
Log "Papelera creada: $papelera"

$paso2_movidos = 0
$paso2_errores = 0
$paso2_mb_total = 0.0

function MoverItem($origen, $destino) {
    if (-not (Test-Path $origen)) {
        Log "  NO ENCONTRADO: $origen"
        return
    }
    $sz = SizeMB $origen
    $script:paso2_mb_total += [float]$sz
    $destinoParent = Split-Path $destino -Parent
    if (-not (Test-Path $destinoParent)) {
        New-Item -Path $destinoParent -ItemType Directory -Force | Out-Null
    }
    try {
        Move-Item -Path $origen -Destination $destino -Force
        if (Test-Path $destino) {
            Log "  MOVIDO [$sz MB]: $origen -> $destino"
            $script:paso2_movidos++
        } else {
            Log "  ERROR: destino no encontrado tras Move-Item: $destino"
            $script:paso2_errores++
        }
    } catch {
        Log "  ERROR al mover $origen : $_"
        $script:paso2_errores++
    }
}

MoverItem "J:\09-02-24" "$papelera\09-02-24"
MoverItem "J:\OFFICE\Office 2019 ES\Office 2019 ES\Office 2019 ES" "$papelera\Office_dup"
MoverItem "J:\Kuschelrock 11-20.rar" "$papelera\Kuschelrock 11-20.rar"
MoverItem "J:\AUTOMATIZACION RADIO\HD-Tuto" "$papelera\HD-Tuto"
MoverItem "J:\AUTOMATIZACION RADIO\AUTOMATV\AUTOMATV\HARDATA-VIDEO" "$papelera\HARDATA-VIDEO_dup"
MoverItem "J:\COMPRIMIDOS\HdxRadio3  3.0.25.2.rar" "$papelera\HdxRadio3  3.0.25.2.rar"
MoverItem "J:\COMPRIMIDOS\DinesatProRadio11Soft_v11.0.5.8.zip" "$papelera\DinesatProRadio11Soft_v11.0.5.8.zip"
MoverItem "J:\VIDEOS GOOGLE\DANILO 1" "$papelera\DANILO_1_dup"
MoverItem "J:\WINDOWS 7\WinSetupFromUSB-1-7\WinSetupFromUSB-1-7" "$papelera\WinSetup_dup"

$paso2_gb = [math]::Round($paso2_mb_total / 1024, 2)
Log "PASO 2 COMPLETO: $paso2_movidos operaciones OK / $paso2_errores errores / ~$paso2_gb GB movidos a papelera"

# =====================================================================
# PASO 3 - CREAR J:\RADIO_TV\ Y MOVER SOFTWARE DE RADIO
# =====================================================================

Log ""
Log "==================================================================="
Log "PASO 3 - CREAR J:\RADIO_TV\ Y MOVER SOFTWARE DE RADIO"
Log "==================================================================="

$dirs = @(
    "J:\RADIO_TV\Dinesat9",
    "J:\RADIO_TV\DinesatPro11",
    "J:\RADIO_TV\HDX_Radio3",
    "J:\RADIO_TV\HDX_Video",
    "J:\RADIO_TV\Horarias_Audio",
    "J:\RADIO_TV\Licencias",
    "J:\RADIO_TV\Documentacion",
    "J:\RADIO_TV\_ARCHIVO"
)

foreach ($d in $dirs) {
    New-Item -Path $d -ItemType Directory -Force | Out-Null
    Log "  Carpeta creada: $d"
}

$paso3_movidos = 0
$paso3_errores = 0
$paso3_mb_total = 0.0

function MoverRadio($origen, $destino) {
    if (-not (Test-Path $origen)) {
        Log "  NO ENCONTRADO: $origen"
        return
    }
    $sz = SizeMB $origen
    $script:paso3_mb_total += [float]$sz
    $destinoParent = Split-Path $destino -Parent
    if (-not (Test-Path $destinoParent)) {
        New-Item -Path $destinoParent -ItemType Directory -Force | Out-Null
    }
    try {
        Move-Item -Path $origen -Destination $destino -Force
        if (Test-Path $destino) {
            Log "  MOVIDO [$sz MB]: $origen -> $destino"
            $script:paso3_movidos++
        } else {
            Log "  ERROR: destino no encontrado: $destino"
            $script:paso3_errores++
        }
    } catch {
        Log "  ERROR al mover $origen : $_"
        $script:paso3_errores++
    }
}

# Licencias
MoverRadio "J:\AUTOMATIZACION RADIO\CLAVES.txt" "J:\RADIO_TV\Licencias\CLAVES.txt"

# Dinesat9
MoverRadio "J:\BACK 21-06-2024\Desktop\INFOCONECTADOS\DinesatRadio9.exe" "J:\RADIO_TV\Dinesat9\DinesatRadio9.exe"
MoverRadio "J:\BACK 21-06-2024\Desktop\INFOCONECTADOS\DinesatServer9.exe" "J:\RADIO_TV\Dinesat9\DinesatServer9.exe"

# HDX Radio 3
MoverRadio "J:\BACK 21-06-2024\Desktop\INFOCONECTADOS\HdxServerImporter.exe" "J:\RADIO_TV\HDX_Radio3\HdxServerImporter.exe"
MoverRadio "J:\AUTOMATIZACION RADIO\HardataHdxRadio3_v3.0.38.5" "J:\RADIO_TV\HDX_Radio3\HardataHdxRadio3_v3.0.38.5"

# HDX Video
MoverRadio "J:\AUTOMATIZACION RADIO\Hardata hdxVideo Pro 64 bits.rar" "J:\RADIO_TV\HDX_Video\Hardata hdxVideo Pro 64 bits.rar"
MoverRadio "J:\AUTOMATIZACION RADIO\Hardata hdxVideo Pro 64 bitsB" "J:\RADIO_TV\HDX_Video\Licencia_digital"

# DinesatPro11
MoverRadio "J:\AUTOMATIZACION RADIO\DinesatProRadio11Soft_v11.0.5.8.zip" "J:\RADIO_TV\DinesatPro11\DinesatProRadio11Soft_v11.0.5.8.zip"
MoverRadio "J:\AUTOMATIZACION RADIO\DinesatVisualRadioSoft.exe" "J:\RADIO_TV\DinesatPro11\DinesatVisualRadioSoft.exe"
MoverRadio "J:\AUTOMATIZACION RADIO\DinesatProRadio11Soft.exe" "J:\RADIO_TV\DinesatPro11\DinesatProRadio11Soft.exe"

# Archivo historico
MoverRadio "J:\AUTOMATIZACION RADIO\HARDATA-VIDEO.nrg" "J:\RADIO_TV\_ARCHIVO\HARDATA-VIDEO.nrg"
MoverRadio "J:\AUTOMATIZACION RADIO\PARCHES DINESAT" "J:\RADIO_TV\_ARCHIVO\HdxRadio3_v3.0.25.2"

# Audio horario - mover MP3 de horas y minutos sueltos en AUTOMATIZACION RADIO
$mp3Patroness = @("HORA*.mp3", "HRS*.mp3", "MINUTOS*.mp3")
foreach ($patron in $mp3Patroness) {
    $mp3s = Get-ChildItem "J:\AUTOMATIZACION RADIO\" -Filter $patron -File -ErrorAction SilentlyContinue
    foreach ($mp3 in $mp3s) {
        MoverRadio $mp3.FullName "J:\RADIO_TV\Horarias_Audio\$($mp3.Name)"
    }
}

# HORARIAS_IVAN_LOSCHER si existe
MoverRadio "J:\AUTOMATIZACION RADIO\HORARIAS_IVAN_LOSCHER" "J:\RADIO_TV\Horarias_Audio\HORARIAS_IVAN_LOSCHER"

# Documentacion
MoverRadio "J:\AUTOMATIZACION RADIO\Hardata Hdx Video Quick Guide Spanish.pdf" "J:\RADIO_TV\Documentacion\Hardata Hdx Video Quick Guide Spanish.pdf"
MoverRadio "J:\AUTOMATIZACION RADIO\INSTALACION HDX.mp4" "J:\RADIO_TV\Documentacion\INSTALACION HDX.mp4"
MoverRadio "J:\AUTOMATIZACION RADIO\INSTRUCCIONES.txt" "J:\RADIO_TV\Documentacion\INSTRUCCIONES.txt"

$paso3_gb = [math]::Round($paso3_mb_total / 1024, 2)
Log "PASO 3 MOVIMIENTOS: $paso3_movidos OK / $paso3_errores errores / ~$paso3_gb GB movidos a RADIO_TV"

# =====================================================================
# VERIFICACION FINAL
# =====================================================================

Log ""
Log "==================================================================="
Log "VERIFICACION FINAL - LISTADO J:\RADIO_TV\"
Log "==================================================================="

Get-ChildItem "J:\RADIO_TV\" -Recurse | ForEach-Object {
    $tipo = if ($_.PSIsContainer) { "[DIR]" } else { "[   ]" }
    $sz = if (-not $_.PSIsContainer) { "$([math]::Round($_.Length/1MB,1)) MB" } else { "" }
    Log "  $tipo $($_.FullName.Replace('J:\RADIO_TV\','')) $sz"
}

# Contar archivos .ooxa (solo verificar que no fueron tocados)
$ooxa = (Get-ChildItem "J:\" -Recurse -Filter "*.ooxa" -File -ErrorAction SilentlyContinue).Count
Log ""
Log "Archivos .ooxa en disco (no tocados): $ooxa"

# Espacio libre disco J
$disco = Get-PSDrive J -ErrorAction SilentlyContinue
if ($disco) {
    $libreGB = [math]::Round($disco.Free / 1GB, 2)
    Log "Espacio libre en J:\ tras operaciones: $libreGB GB"
}

Log ""
Log "=== FIN SCRIPT - Revisar $LOG para detalles completos ==="
Log "Papelera: $papelera (revisar antes de eliminar definitivamente)"
