$ErrorActionPreference = "Stop"

$RutaProyecto = "F:\HERRAMIENTAS DE IA\PROYECTOS\EL RUFINO"
$ReportesDir = "F:\HERRAMIENTAS DE IA\01-panel inteligente\reportes"
$Fecha = Get-Date -Format "yyyyMMdd_HHmmss"

if (!(Test-Path $RutaProyecto)) {
    throw "No existe la ruta del proyecto: $RutaProyecto"
}

if (!(Test-Path $ReportesDir)) {
    New-Item -ItemType Directory -Path $ReportesDir -Force | Out-Null
}

$SalidaCsv = Join-Path $ReportesDir "el_rufino_inventario_$Fecha.csv"

Get-ChildItem -Path $RutaProyecto -Recurse -File -Force |
    Select-Object `
        @{Name='Nombre';Expression={$_.Name}},
        @{Name='RutaCompleta';Expression={$_.FullName}},
        @{Name='Carpeta';Expression={$_.DirectoryName}},
        @{Name='Extension';Expression={$_.Extension.ToLower()}},
        @{Name='TamanoBytes';Expression={[Int64]$_.Length}},
        @{Name='FechaModificacion';Expression={$_.LastWriteTime}} |
    Export-Csv -NoTypeInformation -Encoding UTF8 -Path $SalidaCsv

Write-Host "Inventario actualizado:" -ForegroundColor Green
Write-Host $SalidaCsv -ForegroundColor Cyan