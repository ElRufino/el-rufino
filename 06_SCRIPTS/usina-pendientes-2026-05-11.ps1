# USINA DE IDEAS — Pendientes post-reorganizacion (2026-05-11)
# Mover zip suelto de USINA_DE_IDEAS a su _RELEASES\
# Y registrar archivo maestro cuando sea provisto

$ErrorActionPreference = 'Continue'
$BASE = "F:\HERRAMIENTAS DE IA"

function Log($msg) {
    $line = "$(Get-Date -Format 'HH:mm:ss') $msg"
    Write-Host $line
}

function Move-Safe {
    param([string]$From, [string]$To)
    if (-not (Test-Path -LiteralPath $From)) { Log "  [SKIP-NE] $From"; return }
    $toDir = Split-Path $To -Parent
    if (-not (Test-Path $toDir)) { New-Item -ItemType Directory -Path $toDir -Force | Out-Null }
    if (Test-Path -LiteralPath $To) { Log "  [SKIP-EX] $To"; return }
    Move-Item -LiteralPath $From -Destination $To -Force
    Log "  [OK] $From -> $To"
}

Log "=== PENDIENTES USINA DE IDEAS === $(Get-Date -Format 'yyyy-MM-dd HH:mm') ==="

# 1 - Mover zip suelto en USINA_DE_IDEAS\00_CONTEXTO_IA\ a _RELEASES\
$UI = "$BASE\PROYECTOS\USINA_DE_IDEAS"
$zip = "$UI\00_CONTEXTO_IA\REORGANIZACION_USINA_DE_IDEAS_2026-04-16.zip"
Move-Safe $zip "$UI\_RELEASES\REORGANIZACION_USINA_DE_IDEAS_2026-04-16.zip"

Log ""
Log "=== LISTO ==="
