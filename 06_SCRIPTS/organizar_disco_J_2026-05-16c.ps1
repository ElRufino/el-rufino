# organizar_disco_J_2026-05-16c.ps1
# Ejecutar como Administrador en PowerShell
# Acciones: vaciar papelera 16b, limpiar COMPRIMIDOS, verificar espacio

Set-StrictMode -Version Latest
$ErrorActionPreference = "Continue"

$LOG = "J:\_organizar_disco_log_2026-05-16c.txt"

function Log($msg) {
    $ts = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
    $line = "[$ts] $msg"
    Write-Host $line
    Add-Content -Path $LOG -Value $line -Encoding UTF8
}

function SizeMB($path) {
    if (Test-Path $path -PathType Container) {
        $sz = (Get-ChildItem $path -Recurse -File -ErrorAction SilentlyContinue | Measure-Object -Property Length -Sum).Sum
    } elseif (Test-Path $path -PathType Leaf) {
        $sz = (Get-Item $path).Length
    } else { return 0 }
    return [math]::Round($sz / 1MB, 1)
}

function Eliminar($path) {
    if (-not (Test-Path $path)) {
        Log "  NO ENCONTRADO: $path"
        return
    }
    $sz = SizeMB $path
    try {
        Remove-Item -Path $path -Recurse -Force
        if (-not (Test-Path $path)) {
            Log "  ELIMINADO OK [$sz MB] Test-Path: False — $path"
            $script:eliminados_mb += $sz
            $script:eliminados_count++
        } else {
            Log "  ERROR: sigue existiendo tras Remove-Item: $path"
        }
    } catch {
        Log "  ERROR al eliminar $path : $_"
    }
}

function Mover($origen, $destino) {
    if (-not (Test-Path $origen)) {
        Log "  NO ENCONTRADO: $origen"
        return
    }
    $sz = SizeMB $origen
    $parent = Split-Path $destino -Parent
    if (-not (Test-Path $parent)) {
        New-Item -Path $parent -ItemType Directory -Force | Out-Null
        Log "  Carpeta creada: $parent"
    }
    try {
        Move-Item -Path $origen -Destination $destino -Force
        if (Test-Path $destino) {
            Log "  MOVIDO OK [$sz MB]: $origen -> $destino"
            $script:movidos_count++
        } else {
            Log "  ERROR: destino no existe tras Move-Item: $destino"
        }
    } catch {
        Log "  ERROR al mover $origen : $_"
    }
}

if (-not (Test-Path "J:\")) {
    Write-Host "ERROR: J:\ no encontrado."
    exit 1
}

New-Item -Path $LOG -ItemType File -Force | Out-Null
Log "=== INICIO organizar_disco_J_2026-05-16c.ps1 ==="

$eliminados_mb = 0.0
$eliminados_count = 0
$movidos_count = 0

# =====================================================================
# BLOQUE 1 — VACIAR PAPELERA 2026-05-16b
# =====================================================================

Log ""
Log "=== BLOQUE 1: Vaciar papelera J:\_PAPELERA_DISCO\2026-05-16b\ ==="

$papelera16b = "J:\_PAPELERA_DISCO\2026-05-16b"
if (Test-Path $papelera16b) {
    $sz = SizeMB $papelera16b
    Remove-Item -Path $papelera16b -Recurse -Force
    if (-not (Test-Path $papelera16b)) {
        Log "  PAPELERA ELIMINADA OK [$sz MB] Test-Path: False"
        $eliminados_mb += $sz
    } else {
        Log "  ERROR: papelera sigue existiendo"
    }
} else {
    Log "  Papelera 2026-05-16b no encontrada (ya eliminada)"
}

# =====================================================================
# BLOQUE 2 — ELIMINAR archivos de COMPRIMIDOS
# =====================================================================

Log ""
Log "=== BLOQUE 2: Eliminar instaladores obsoletos de J:\COMPRIMIDOS\ ==="

Eliminar "J:\COMPRIMIDOS\TXE_Win_64_1.1.5.1162.zip"
Eliminar "J:\COMPRIMIDOS\PuntoDeVenta.zip"
Eliminar "J:\COMPRIMIDOS\ITACTIL  Líder Bar y Restaurante v8.7.rar"
Eliminar "J:\COMPRIMIDOS\Punto de Venta en Excel Update Julio 2019.rar"
Eliminar "J:\COMPRIMIDOS\22000.160_amd64_es-mx_professional_8457f286_convert.zip"

# =====================================================================
# BLOQUE 3 — MOVER a J:\SOFTWARE\Diseño\
# =====================================================================

Log ""
Log "=== BLOQUE 3: Mover software de diseno a J:\SOFTWARE\Diseno\ ==="

Mover "J:\COMPRIMIDOS\CORELDRAW_X7.rar"                 "J:\SOFTWARE\Diseno\CORELDRAW_X7.rar"
Mover "J:\COMPRIMIDOS\Adb Phtshp CS3 Prtbl.rar"         "J:\SOFTWARE\Diseno\Adb Phtshp CS3 Prtbl.rar"

# =====================================================================
# BLOQUE 4 — MOVER a J:\SOFTWARE\Utilitarios\
# =====================================================================

Log ""
Log "=== BLOQUE 4: Mover utilitarios a J:\SOFTWARE\Utilitarios\ ==="

Mover "J:\COMPRIMIDOS\Ultra iso.rar"                    "J:\SOFTWARE\Utilitarios\Ultra iso.rar"
Mover "J:\COMPRIMIDOS\dsynchronize.zip"                 "J:\SOFTWARE\Utilitarios\dsynchronize.zip"
Mover "J:\COMPRIMIDOS\DSynchronize Spanish Pack.zip"    "J:\SOFTWARE\Utilitarios\DSynchronize Spanish Pack.zip"
Mover "J:\COMPRIMIDOS\rufus-3.17.exe"                   "J:\SOFTWARE\Utilitarios\rufus-3.17.exe"

# =====================================================================
# BLOQUE 5 — MOVER documento personal ANSES
# =====================================================================

Log ""
Log "=== BLOQUE 5: Mover documento ANSES a J:\DOCUMENTOS\Personal\ ==="

Mover "J:\COMPRIMIDOS\ANSES_Constancia_CUIL20190820.pdf" "J:\DOCUMENTOS\Personal\ANSES_Constancia_CUIL20190820.pdf"

# =====================================================================
# BLOQUE 6 — VERIFICACION FINAL
# =====================================================================

Log ""
Log "=== BLOQUE 6: Verificacion final ==="

# Contenido restante en COMPRIMIDOS
Log ""
Log "Contenido restante en J:\COMPRIMIDOS\ :"
if (Test-Path "J:\COMPRIMIDOS\") {
    $restantes = Get-ChildItem "J:\COMPRIMIDOS\" -ErrorAction SilentlyContinue
    if ($restantes) {
        foreach ($r in $restantes) {
            $sz = SizeMB $r.FullName
            Log "  [$sz MB] $($r.Name)"
        }
    } else {
        Log "  (vacia)"
    }
} else {
    Log "  Carpeta COMPRIMIDOS no encontrada"
}

# Estructura J:\SOFTWARE\
Log ""
Log "Estructura J:\SOFTWARE\ :"
if (Test-Path "J:\SOFTWARE\") {
    Get-ChildItem "J:\SOFTWARE\" -Recurse -File | ForEach-Object {
        $sz = [math]::Round($_.Length/1MB, 1)
        Log "  [$sz MB] $($_.FullName.Replace('J:\SOFTWARE\',''))"
    }
}

# Verificar .ooxa intactos
$ooxa = (Get-ChildItem "J:\" -Recurse -Filter "*.ooxa" -File -ErrorAction SilentlyContinue).Count
Log ""
Log "Archivos .ooxa en disco (no tocados): $ooxa"

# Verificar BACK SSD - WIN11 intacta
$ssd_count = (Get-ChildItem "J:\BACK SSD - WIN11\" -Recurse -File -ErrorAction SilentlyContinue).Count
Log "Archivos en BACK SSD - WIN11 (no tocados): $ssd_count"

# Verificar SuiteCRM-8.3.0.zip intacto
if (Test-Path "J:\GESTION DE MEDIOS COMUNITARIOS\SuiteCRM-8.3.0.zip") {
    Log "SuiteCRM-8.3.0.zip: PRESENTE OK"
} else {
    Log "SuiteCRM-8.3.0.zip: NO ENCONTRADO — VERIFICAR"
}

# Espacio libre
$disco = Get-PSDrive J -ErrorAction SilentlyContinue
if ($disco) {
    $libreGB = [math]::Round($disco.Free / 1GB, 2)
    $totalGB = [math]::Round(($disco.Free + $disco.Used) / 1GB, 2)
    Log ""
    Log "Espacio libre en J:\: $libreGB GB / $totalGB GB totales"
}

Log ""
Log "RESUMEN: $eliminados_count items eliminados (~$([math]::Round($eliminados_mb,1)) MB) / $movidos_count items movidos"
Log "=== FIN SCRIPT ==="
