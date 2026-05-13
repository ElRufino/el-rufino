# regenerar-zip-v8.6.0.ps1
# Genera el ZIP del plugin v8.6.0 con la estructura correcta para WordPress
# Ejecutar desde PowerShell (no ISE): .\06_SCRIPTS\regenerar-zip-v8.6.0.ps1

$ErrorActionPreference = 'Stop'

$raiz     = 'F:\HERRAMIENTAS DE IA\PROYECTOS\EL_RUFINO'
$fuente   = "$raiz\_ACTIVO\plugin"
$destino  = "$raiz\_RELEASES\el-rufino-panel-v8.6.0.zip"
$tmp      = "$env:TEMP\el-rufino-panel-build"
$carpeta  = "$tmp\el-rufino-panel"

Write-Host "Preparando estructura..."

# Limpiar y crear directorio temporal
if (Test-Path $tmp) { Remove-Item $tmp -Recurse -Force }
New-Item -ItemType Directory -Path "$carpeta\assets" -Force | Out-Null

# Copiar archivos del plugin
Copy-Item "$fuente\el-rufino-panel.php" "$carpeta\el-rufino-panel.php"
Copy-Item "$fuente\assets\panel.js"     "$carpeta\assets\panel.js"

Write-Host "Archivos:"
Write-Host "  el-rufino-panel/el-rufino-panel.php"
Write-Host "  el-rufino-panel/assets/panel.js"

# Comprimir
if (Test-Path $destino) { Remove-Item $destino -Force }
Compress-Archive -Path "$carpeta" -DestinationPath $destino -CompressionLevel Optimal

Write-Host ""
Write-Host "ZIP generado: $destino"
Write-Host "Tamano: $([math]::Round((Get-Item $destino).Length / 1KB, 1)) KB"

# Limpiar tmp
Remove-Item $tmp -Recurse -Force

Write-Host ""
Write-Host "Listo. Subir a Hostinger: Plugins > Anadir nuevo > Subir plugin."
