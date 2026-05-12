# Eliminar 00_CENTRAL\ (carpeta vacia — reemplazada por USINA_DE_IDEAS\)
# Confirmado por usuario 2026-05-11

$target = "F:\HERRAMIENTAS DE IA\00_CENTRAL"

if (Test-Path -LiteralPath $target) {
    Remove-Item -LiteralPath $target -Recurse -Force
    Write-Host "[DEL] $target"
} else {
    Write-Host "[SKIP] No existe: $target"
}
