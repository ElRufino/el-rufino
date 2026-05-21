# organizar_disco_J_2026-05-16d_PASO1.ps1
# PASO 1: Consolidar backups en J:\BACKUPS\
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

New-Item -Path $LOG -ItemType File -Force | Out-Null
Log "=== INICIO PASO 1: Consolidar backups en J:\BACKUPS\ ==="

$movidos = 0

# Crear carpeta raiz
New-Item -Path "J:\BACKUPS" -ItemType Directory -Force | Out-Null
Log "Carpeta creada: J:\BACKUPS\"

Log ""
Log "=== Moviendo carpetas de backup ==="

Mover "J:\BACK 21-06-2024"           "J:\BACKUPS\2024-06-21_PC-INFOCONECTADOS"
Mover "J:\BACKUP - 08-03-2023"       "J:\BACKUPS\2023-03-08_PC-CONECTADOS"
Mover "J:\18-04-2023 - BACKUP"       "J:\BACKUPS\2023-04-18_PC-DANILO"
Mover "J:\BACK TELEFONO 03-08-2023"  "J:\BACKUPS\2023-08-03_TELEFONO"
Mover "J:\BACK SSD - WIN11"          "J:\BACKUPS\SSD-WIN11"
Mover "J:\CONECTADOS-INFO"           "J:\BACKUPS\CONECTADOS-INFO_WEB"

Log ""
Log "=== Verificacion: contenido de J:\BACKUPS\ ==="
if (Test-Path "J:\BACKUPS\") {
    Get-ChildItem "J:\BACKUPS\" | ForEach-Object {
        $sz = SizeMB $_.FullName
        Log "  [$sz MB] $($_.Name)"
    }
}

# Verificar que las rutas origen ya no existen
Log ""
Log "=== Verificacion: origenes eliminados de raiz ==="
$origenes = @(
    "J:\BACK 21-06-2024",
    "J:\BACKUP - 08-03-2023",
    "J:\18-04-2023 - BACKUP",
    "J:\BACK TELEFONO 03-08-2023",
    "J:\BACK SSD - WIN11",
    "J:\CONECTADOS-INFO"
)
foreach ($o in $origenes) {
    if (Test-Path $o) {
        Log "  ATENCION: sigue existiendo en raiz: $o"
    } else {
        Log "  OK (eliminado de raiz): $o"
    }
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
Log "RESUMEN PASO 1: $movidos carpetas movidas"
Log "=== FIN PASO 1 ==="
