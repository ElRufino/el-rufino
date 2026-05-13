# generar-releases.ps1 v3
# Crea ZIPs con entradas de forward slashes (requerido por PHP/Linux)
# Ejecutar: .\06_SCRIPTS\generar-releases.ps1

$ErrorActionPreference = 'Stop'
Add-Type -AssemblyName System.IO.Compression
Add-Type -AssemblyName System.IO.Compression.FileSystem

$raiz    = 'F:\HERRAMIENTAS DE IA\PROYECTOS\EL_RUFINO'
$src     = "$raiz\_ACTIVO"
$out     = "$raiz\_RELEASES"

function New-ZipEntry($zip, $filePath, $entryName) {
    $entry   = $zip.CreateEntry($entryName, [System.IO.Compression.CompressionLevel]::Optimal)
    $writer  = $entry.Open()
    $reader  = [System.IO.File]::OpenRead($filePath)
    $reader.CopyTo($writer)
    $reader.Close()
    $writer.Close()
    Write-Host "    $entryName"
}

# ============================================================
# PLUGIN
# ============================================================
$pZip = "$out\el-rufino-panel.zip"
if (Test-Path $pZip) { Remove-Item $pZip -Force }

$zip = [System.IO.Compression.ZipFile]::Open($pZip, 'Create')
New-ZipEntry $zip "$src\plugin\el-rufino-panel.php" "el-rufino-panel/el-rufino-panel.php"
New-ZipEntry $zip "$src\plugin\assets\panel.js"     "el-rufino-panel/assets/panel.js"
$zip.Dispose()

Write-Host "Plugin: $pZip ($([math]::Round((Get-Item $pZip).Length/1KB,1)) KB)"

# ============================================================
# THEME
# ============================================================
$tZip = "$out\el-rufino-theme.zip"
if (Test-Path $tZip) { Remove-Item $tZip -Force }
$tSrc = "$src\theme\el-rufino-theme-v2.3.4\el-rufino-theme"

$zip = [System.IO.Compression.ZipFile]::Open($tZip, 'Create')
foreach ($f in @('style.css','functions.php','header.php','home.php','single.php','footer.php','og-elrufino.png')) {
    if (Test-Path "$tSrc\$f") {
        New-ZipEntry $zip "$tSrc\$f" "el-rufino-theme/$f"
    }
}
$zip.Dispose()

Write-Host "Theme:  $tZip ($([math]::Round((Get-Item $tZip).Length/1KB,1)) KB)"
Write-Host "Listo."
