# EL RUFINO - Subida de archivos a GitHub
# OBSOLETO desde que el proyecto usa "git push" normal — script de la epoca
# previa al repo git (abril 2026), referencia rutas/versiones que ya no
# existen ($BASE, prompt-maestro v1.1). Se conserva solo como historial;
# no ejecutar sin revisar y actualizar rutas antes.
#
# Token retirado de este archivo el 2026-07-22 (estaba hardcodeado en texto
# plano y ya commiteado en el historial desde 0b0d43d, 2026-04-08 — pendiente
# de revocar/regenerar en GitHub, ver hallazgo de la sesion de cierre).
# Si se vuelve a usar este script, pasar el token por variable de entorno:
$TOKEN = $env:EL_RUFINO_GITHUB_PAT
$OWNER = "ElRufino"
$REPO  = "el-rufino"
$BASE  = "F:\HERRAMIENTAS DE IA\CLAUDE\EL RUFINO"

$HEADERS = @{
    Authorization = "Bearer $TOKEN"
    Accept = "application/vnd.github+json"
    "X-GitHub-Api-Version" = "2022-11-28"
}

function Subir-Archivo($RutaLocal, $RutaRepo) {
    if (-not (Test-Path $RutaLocal)) {
        Write-Host "  OMITIDO: $RutaLocal" -ForegroundColor Yellow
        return
    }
    $bytes  = [System.IO.File]::ReadAllBytes($RutaLocal)
    $base64 = [Convert]::ToBase64String($bytes)
    $url    = "https://api.github.com/repos/$OWNER/$REPO/contents/$RutaRepo"
    $sha    = $null
    try {
        $existing = Invoke-RestMethod -Uri $url -Headers $HEADERS -Method Get -ErrorAction Stop
        $sha = $existing.sha
    } catch {}
    $body = @{ message = "subida: $RutaRepo"; content = $base64 }
    if ($sha) { $body.sha = $sha }
    try {
        Invoke-RestMethod -Uri $url -Headers $HEADERS -Method Put -Body ($body | ConvertTo-Json -Depth 3) -ContentType "application/json" | Out-Null
        Write-Host "  OK: $RutaRepo" -ForegroundColor Green
    } catch {
        Write-Host "  ERROR: $RutaRepo - $_" -ForegroundColor Red
    }
}

Write-Host "EL RUFINO - GitHub: $OWNER/$REPO" -ForegroundColor White

Write-Host "" ; Write-Host "00_CONTEXTO_IA" -ForegroundColor Cyan
Subir-Archivo "$BASE\00_CONTEXTO_IA\el-rufino_prompt-maestro_v1.1.md" "00_CONTEXTO_IA/el-rufino_prompt-maestro_v1.1.md"
Subir-Archivo "$BASE\00_CONTEXTO_IA\el-rufino_agentes-ia_v1.md" "00_CONTEXTO_IA/el-rufino_agentes-ia_v1.md"
Subir-Archivo "$BASE\00_CONTEXTO_IA\el-rufino_repositorio-ia_v1.1.md" "00_CONTEXTO_IA/el-rufino_repositorio-ia_v1.1.md"

Write-Host "" ; Write-Host "01_DOCUMENTOS_VIGENTES" -ForegroundColor Cyan
$docs = Get-ChildItem "$BASE\01_DOCUMENTOS_VIGENTES" -File -Include "*.html","*.pdf","*.md" -ErrorAction SilentlyContinue
foreach ($f in $docs) { Subir-Archivo $f.FullName "01_DOCUMENTOS_VIGENTES/$($f.Name)" }
if (-not $docs) { Write-Host "  (sin archivos)" -ForegroundColor DarkGray }

Write-Host "" ; Write-Host "02_WORDPRESS_TEST" -ForegroundColor Cyan
$wp = Get-ChildItem "$BASE\02_WORDPRESS_TEST" -File -Include "*.html","*.md","*.zip" -ErrorAction SilentlyContinue
foreach ($f in $wp) { Subir-Archivo $f.FullName "02_WORDPRESS_TEST/$($f.Name)" }
if (-not $wp) { Write-Host "  (sin archivos)" -ForegroundColor DarkGray }

Write-Host "" ; Write-Host "03_IDENTIDAD_MARCA" -ForegroundColor Cyan
$marca = Get-ChildItem "$BASE\03_IDENTIDAD_MARCA" -File -Include "*.html","*.md","*.pdf" -ErrorAction SilentlyContinue
foreach ($f in $marca) { Subir-Archivo $f.FullName "03_IDENTIDAD_MARCA/$($f.Name)" }
if (-not $marca) { Write-Host "  (sin archivos)" -ForegroundColor DarkGray }

Write-Host "" ; Write-Host "README.md" -ForegroundColor Cyan
Subir-Archivo "$BASE\README.md" "README.md"

Write-Host ""
Write-Host "Listo. Ver: https://github.com/$OWNER/$REPO" -ForegroundColor Green
