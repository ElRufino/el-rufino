# reorganizar-contexto-ia.ps1
# El Rufino - Reorganizacion de 00_CONTEXTO_IA y raiz del proyecto
# Ejecutar: cd a la carpeta EL RUFINO, luego Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass

$base    = $PSScriptRoot
$ctx     = Join-Path $base "00_CONTEXTO_IA"
$archivo = Join-Path $base "_ARCHIVO"
$docs    = Join-Path $base "01_DOCUMENTOS_VIGENTES"
$scripts = Join-Path $base "06_SCRIPTS"

# Crear carpetas si no existen
foreach ($carpeta in @($archivo, $docs, $scripts)) {
    if (-not (Test-Path $carpeta)) {
        New-Item -ItemType Directory -Path $carpeta | Out-Null
        Write-Host "Carpeta creada: $carpeta"
    }
}

Write-Host ""
Write-Host "=== LIMPIEZA DE 00_CONTEXTO_IA ==="

# Archivar versiones obsoletas del repositorio-ia
$obsoletos_ctx = @(
    "el-rufino_repositorio-ia_v1.1.md",
    "el-rufino_repositorio-ia_v1.1-VIGENTE.md",
    "el-rufino_repositorio-ia_v1.2.md"
)
foreach ($f in $obsoletos_ctx) {
    $origen = Join-Path $ctx $f
    if (Test-Path $origen) {
        $destino = Join-Path $archivo $f
        Move-Item -Path $origen -Destination $destino -Force
        Write-Host "Archivado: $f"
    }
}

# Archivar versiones obsoletas del prompt-maestro
$obsoletos_pm = @(
    "el-rufino_prompt-maestro_v1.0.md",
    "el-rufino_prompt-maestro_v1.1.md",
    "el-rufino_prompt-maestro_v1.1-VIGENTE.md",
    "el-rufino_prompt-maestro_v1.2.md"
)
foreach ($f in $obsoletos_pm) {
    $origen = Join-Path $ctx $f
    if (Test-Path $origen) {
        $destino = Join-Path $archivo $f
        Move-Item -Path $origen -Destination $destino -Force
        Write-Host "Archivado: $f"
    }
}

# Mover AUDITORIA a 01_DOCUMENTOS_VIGENTES
$auditoria = Join-Path $ctx "AUDITORIA_EL-RUFINO_2026-04-08.md"
if (Test-Path $auditoria) {
    Move-Item -Path $auditoria -Destination (Join-Path $docs "AUDITORIA_EL-RUFINO_2026-04-08.md") -Force
    Write-Host "Movido a DOCUMENTOS_VIGENTES: AUDITORIA_EL-RUFINO_2026-04-08.md"
}

# Mover README de 00_CONTEXTO_IA a la raiz (pertenece ahi)
$readme_ctx = Join-Path $ctx "README.md"
if (Test-Path $readme_ctx) {
    Move-Item -Path $readme_ctx -Destination (Join-Path $base "README.md") -Force
    Write-Host "Movido a raiz: README.md"
}

Write-Host ""
Write-Host "=== LIMPIEZA DE RAIZ ==="

# Archivar README duplicado (quedarse con el v2 renombrado)
$readme_v2 = Join-Path $base "README_v2_PARA_GITHUB.md"
$readme_base = Join-Path $base "README.md"
if ((Test-Path $readme_v2) -and (Test-Path $readme_base)) {
    Move-Item -Path $readme_base -Destination (Join-Path $archivo "README_v1.md") -Force
    Rename-Item -Path $readme_v2 -NewName "README.md"
    Write-Host "README: v2 promovido como activo, v1 archivado"
} elseif (Test-Path $readme_v2) {
    Rename-Item -Path $readme_v2 -NewName "README.md"
    Write-Host "README: v2 renombrado a README.md"
}

# Archivar CHANGELOG duplicado
$changelog_v2   = Join-Path $base "CHANGELOG_v2_PARA_GITHUB.md"
$changelog_base = Join-Path $base "CHANGELOG.md"
if ((Test-Path $changelog_v2) -and (Test-Path $changelog_base)) {
    Move-Item -Path $changelog_base -Destination (Join-Path $archivo "CHANGELOG_v1.md") -Force
    Rename-Item -Path $changelog_v2 -NewName "CHANGELOG.md"
    Write-Host "CHANGELOG: v2 promovido como activo, v1 archivado"
} elseif (Test-Path $changelog_v2) {
    Rename-Item -Path $changelog_v2 -NewName "CHANGELOG.md"
    Write-Host "CHANGELOG: v2 renombrado a CHANGELOG.md"
}

# Limpiar gitignore duplicado
$gitignore_txt = Join-Path $base "gitignore_PARA_GITHUB.txt"
if (Test-Path $gitignore_txt) {
    Move-Item -Path $gitignore_txt -Destination (Join-Path $archivo "gitignore_PARA_GITHUB.txt") -Force
    Write-Host "Archivado: gitignore_PARA_GITHUB.txt (queda solo .gitignore)"
}

# Mover AUDITORIA de raiz a 01_DOCUMENTOS_VIGENTES si existe en raiz
$auditoria_raiz = Join-Path $base "AUDITORIA_EL-RUFINO_2026-04-08.md"
if (Test-Path $auditoria_raiz) {
    Move-Item -Path $auditoria_raiz -Destination (Join-Path $docs "AUDITORIA_EL-RUFINO_2026-04-08.md") -Force
    Write-Host "Movido a DOCUMENTOS_VIGENTES: AUDITORIA_EL-RUFINO_2026-04-08.md"
}

# Mover scripts .ps1 a 06_SCRIPTS
$ps1_files = Get-ChildItem -Path $base -Filter "*.ps1" -File
foreach ($ps1 in $ps1_files) {
    if ($ps1.Name -ne "reorganizar-contexto-ia.ps1") {
        Move-Item -Path $ps1.FullName -Destination (Join-Path $scripts $ps1.Name) -Force
        Write-Host "Movido a 06_SCRIPTS: $($ps1.Name)"
    }
}

Write-Host ""
Write-Host "=== RESULTADO FINAL DE 00_CONTEXTO_IA ==="
Get-ChildItem -Path $ctx | Format-Table Name, LastWriteTime, Length -AutoSize

Write-Host ""
Write-Host "=== RESULTADO FINAL DE RAIZ ==="
Get-ChildItem -Path $base -File | Format-Table Name, LastWriteTime -AutoSize

Write-Host ""
Write-Host "LISTO. Archivos vigentes en 00_CONTEXTO_IA esperados:"
Write-Host "  el-rufino_agentes-ia_v1.md"
Write-Host "  el-rufino_prompt-maestro_v1.2-VIGENTE.md"
Write-Host "  el-rufino_repositorio-ia_v1.2-VIGENTE.md"
Write-Host ""
Write-Host "PENDIENTE: copiar el archivo v1.2-VIGENTE generado hoy a 00_CONTEXTO_IA"
