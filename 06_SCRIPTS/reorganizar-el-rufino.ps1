# EL RUFINO - Reorganizacion del repositorio GitHub
# Ejecutar: Set-ExecutionPolicy Bypass -Scope Process -Force
# Luego: .\reorganizar-el-rufino.ps1

$REPO   = "F:\HERRAMIENTAS DE IA\CLAUDE\EL RUFINO"
$FUENTE = "F:\HERRAMIENTAS DE IA\CLAUDE\EL RUFINO"

Write-Host ""
Write-Host "================================================" -ForegroundColor Cyan
Write-Host "  EL RUFINO - Reorganizacion del repositorio" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

if (-not (Test-Path $REPO)) {
    Write-Host "ERROR: No se encontro el repositorio en:" -ForegroundColor Red
    Write-Host "  $REPO" -ForegroundColor Red
    Write-Host ""
    Write-Host "Ejecuta: dir '$FUENTE' para ver las carpetas disponibles" -ForegroundColor Yellow
    Write-Host "Luego edita la variable REPO al inicio del script" -ForegroundColor Yellow
    exit 1
}

Set-Location $REPO
Write-Host "Repositorio: $REPO" -ForegroundColor Green
Write-Host ""

# PASO 1 - Crear carpeta 00_CONTEXTO_IA
Write-Host "PASO 1 - Crear carpeta 00_CONTEXTO_IA" -ForegroundColor White
$carpeta = "$REPO\00_CONTEXTO_IA"
if (-not (Test-Path $carpeta)) {
    New-Item -ItemType Directory -Path $carpeta | Out-Null
    Write-Host "  OK Carpeta creada: 00_CONTEXTO_IA" -ForegroundColor Green
} else {
    Write-Host "  -- Ya existe: 00_CONTEXTO_IA" -ForegroundColor Yellow
}

# PASO 2 - Mover archivos de contexto IA
Write-Host ""
Write-Host "PASO 2 - Mover archivos de contexto IA a 00_CONTEXTO_IA" -ForegroundColor White

$archivos = @(
    "el-rufino_agentes-ia_v1.md",
    "el-rufino_prompt-maestro_v1_1-VIGENTE.md",
    "el-rufino_repositorio-ia_v1_1-VIGENTE.md",
    "el-rufino_prompt-maestro_v1.0.md",
    "el-rufino_prompt-maestro_v1.1.md",
    "el-rufino_prompt-maestro_v1.2.md",
    "el-rufino_repositorio-ia_v1.1.md",
    "el-rufino_repositorio-ia_v1.2.md",
    "el-rufino_repositorio-ia_v1.1-VIGENTE.md"
)

foreach ($archivo in $archivos) {
    $origen  = "$REPO\$archivo"
    $destino = "$carpeta\$archivo"
    if (Test-Path $origen) {
        if (Test-Path $destino) {
            Write-Host "  -- Ya esta en carpeta: $archivo" -ForegroundColor Yellow
        } else {
            Move-Item -Path $origen -Destination $destino
            Write-Host "  OK Movido: $archivo" -ForegroundColor Green
        }
    } elseif (Test-Path $destino) {
        Write-Host "  -- Ya en carpeta: $archivo" -ForegroundColor Yellow
    }
}

# PASO 3 - Copiar README a raiz
Write-Host ""
Write-Host "PASO 3 - Copiar README.md a raiz" -ForegroundColor White
$src = "$FUENTE\README_v2_PARA_GITHUB.md"
$dst = "$REPO\README.md"
if (Test-Path $src) {
    Copy-Item -Path $src -Destination $dst -Force
    Write-Host "  OK README.md copiado" -ForegroundColor Green
} else {
    Write-Host "  AVISO: No encontre README_v2_PARA_GITHUB.md en $FUENTE" -ForegroundColor Yellow
}

# PASO 4 - Copiar CHANGELOG a raiz
Write-Host ""
Write-Host "PASO 4 - Copiar CHANGELOG.md a raiz" -ForegroundColor White
$src = "$FUENTE\CHANGELOG_v2_PARA_GITHUB.md"
$dst = "$REPO\CHANGELOG.md"
if (Test-Path $src) {
    Copy-Item -Path $src -Destination $dst -Force
    Write-Host "  OK CHANGELOG.md copiado" -ForegroundColor Green
} else {
    Write-Host "  AVISO: No encontre CHANGELOG_v2_PARA_GITHUB.md en $FUENTE" -ForegroundColor Yellow
}

# PASO 5 - Copiar .gitignore a raiz
Write-Host ""
Write-Host "PASO 5 - Copiar .gitignore a raiz" -ForegroundColor White
$src = "$FUENTE\gitignore_PARA_GITHUB.txt"
$dst = "$REPO\.gitignore"
if (Test-Path $src) {
    Copy-Item -Path $src -Destination $dst -Force
    Write-Host "  OK .gitignore copiado" -ForegroundColor Green
} else {
    Write-Host "  AVISO: No encontre gitignore_PARA_GITHUB.txt en $FUENTE" -ForegroundColor Yellow
}

# PASO 6 - Copiar auditoria a 00_CONTEXTO_IA
Write-Host ""
Write-Host "PASO 6 - Copiar auditoria a 00_CONTEXTO_IA" -ForegroundColor White
$src = "$FUENTE\AUDITORIA_EL-RUFINO_2026-04-08.md"
$dst = "$carpeta\AUDITORIA_EL-RUFINO_2026-04-08.md"
if (Test-Path $src) {
    Copy-Item -Path $src -Destination $dst -Force
    Write-Host "  OK Auditoria copiada" -ForegroundColor Green
} else {
    Write-Host "  -- Omitido (no encontrado)" -ForegroundColor Yellow
}

# PASO 7 - Crear carpetas faltantes
Write-Host ""
Write-Host "PASO 7 - Crear carpetas faltantes" -ForegroundColor White

$carpetas = @("02_WORDPRESS_TEST", "03_IDENTIDAD_MARCA", "01_DOCUMENTOS_VIGENTES", "_ARCHIVO")

foreach ($c in $carpetas) {
    $ruta = "$REPO\$c"
    if (-not (Test-Path $ruta)) {
        New-Item -ItemType Directory -Path $ruta | Out-Null
        New-Item -ItemType File -Path "$ruta\.gitkeep" -Force | Out-Null
        Write-Host "  OK Carpeta creada: $c" -ForegroundColor Green
    } else {
        Write-Host "  -- Ya existe: $c" -ForegroundColor Yellow
    }
}

# RESUMEN FINAL
Write-Host ""
Write-Host "================================================" -ForegroundColor Cyan
Write-Host "  ESTRUCTURA RESULTANTE" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

Get-ChildItem $REPO | ForEach-Object {
    if ($_.PSIsContainer) {
        Write-Host "  [DIR] $($_.Name)" -ForegroundColor Yellow
        Get-ChildItem $_.FullName | Where-Object { $_.Name -ne ".gitkeep" } | ForEach-Object {
            Write-Host "        $($_.Name)" -ForegroundColor Gray
        }
    } else {
        Write-Host "  [   ] $($_.Name)" -ForegroundColor White
    }
}

Write-Host ""
Write-Host "================================================" -ForegroundColor Cyan
Write-Host "  PROXIMOS PASOS" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "  1. Abri GitHub Desktop" -ForegroundColor White
Write-Host "  2. Verifica los cambios en la lista" -ForegroundColor White
Write-Host "  3. Summary: Reorganizar estructura + archivos base" -ForegroundColor Green
Write-Host "  4. Commit to main -> Push origin" -ForegroundColor White
Write-Host ""
Write-Host "  Listo!" -ForegroundColor Green
Write-Host ""
