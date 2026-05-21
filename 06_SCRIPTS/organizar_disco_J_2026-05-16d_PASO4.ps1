# organizar_disco_J_2026-05-16d_PASO4.ps1
# PASO 4: Mover contenido de J:\AFIP\ a J:\DOCUMENTOS\Personal\
# Ejecutar como Administrador en PowerShell

Set-StrictMode -Version Latest
$ErrorActionPreference = "Continue"

$LOG = "J:\_organizar_disco_log_2026-05-16d.txt"

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
            $script:movidos++
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

Log ""
Log "=== INICIO PASO 4: Mover contenido AFIP a DOCUMENTOS\Personal\ ==="

$movidos = 0

# Mostrar contenido de AFIP antes de mover
Log ""
Log "=== Contenido actual de J:\AFIP\ ==="
if (Test-Path "J:\AFIP\") {
    Get-ChildItem "J:\AFIP\" -Recurse | ForEach-Object {
        $sz = SizeMB $_.FullName
        Log "  [$sz MB] $($_.FullName.Replace('J:\AFIP\', ''))"
    }
} else {
    Log "  NO ENCONTRADO: J:\AFIP\"
}

Log ""
Log "=== Moviendo archivos de J:\AFIP\ ==="

# Mover el contenido de la carpeta AFIP a DOCUMENTOS\Personal\AFIP
# Si AFIP tiene subcarpetas/archivos, los movemos dentro de DOCUMENTOS\Personal\AFIP
Mover "J:\AFIP"  "J:\DOCUMENTOS\Personal\AFIP"

Log ""
Log "=== Verificacion: J:\DOCUMENTOS\Personal\ ==="
if (Test-Path "J:\DOCUMENTOS\Personal\") {
    Get-ChildItem "J:\DOCUMENTOS\Personal\" | ForEach-Object {
        $sz = SizeMB $_.FullName
        Log "  [$sz MB] $($_.Name)"
    }
}

Log ""
Log "=== Verificacion: J:\AFIP\ eliminada de raiz ==="
if (Test-Path "J:\AFIP\") {
    Log "  ATENCION: J:\AFIP\ sigue existiendo en raiz"
} else {
    Log "  OK: J:\AFIP\ eliminada de raiz"
}

# Listar raiz de J:\ para estado final
Log ""
Log "=== Estado actual de J:\ (raiz) ==="
Get-ChildItem "J:\" | ForEach-Object {
    $sz = SizeMB $_.FullName
    Log "  [$sz MB] $($_.Name)"
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
Log "RESUMEN PASO 4: $movidos elementos movidos"
Log "=== FIN PASO 4 ==="
