# ==============================================================================
# ARCHIVO: fix_git_binarios.ps1
# PROYECTO: EL RUFINO · Repositorio git
# FECHA: 2026-04-18
# PROPÓSITO: Remover binarios del tracking git sin borrar los archivos físicos
# GUARDAR EN: F:\HERRAMIENTAS DE IA\PROYECTOS\EL_RUFINO\06_SCRIPTS\
# ==============================================================================

# PASO 1 — Pararse en la raíz del repo (donde está la carpeta .git)
Set-Location "F:\HERRAMIENTAS DE IA\PROYECTOS\EL_RUFINO"

# Verificar que estamos en el repo correcto
Write-Host "📁 Directorio actual: $(Get-Location)" -ForegroundColor Cyan
Write-Host "🔍 Verificando repo git..." -ForegroundColor Cyan
git status --short
if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ No es un repo git válido. Verificar ruta." -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "🔍 Buscando binarios trackeados (.zip .jpg .png .pdf)..." -ForegroundColor Yellow

# PASO 2 — Ver qué binarios están siendo trackeados
$binarios = git ls-files | Where-Object { $_ -match '\.(zip|jpg|jpeg|png|gif|pdf|psd|ai)$' }

if ($binarios.Count -eq 0) {
    Write-Host "✅ No hay binarios trackeados. El repo está limpio." -ForegroundColor Green
    exit 0
}

Write-Host ""
Write-Host "⚠️  Binarios encontrados en tracking:" -ForegroundColor Yellow
$binarios | ForEach-Object { Write-Host "   · $_" -ForegroundColor White }

Write-Host ""
$confirm = Read-Host "¿Remover del tracking? Los archivos físicos NO se borran. (s/n)"
if ($confirm -ne "s") {
    Write-Host "Cancelado." -ForegroundColor Gray
    exit 0
}

# PASO 3 — Remover del tracking (sin borrar archivo físico)
$binarios | ForEach-Object {
    Write-Host "🗑  Removiendo del tracking: $_" -ForegroundColor Yellow
    git rm --cached "$_"
}

# PASO 4 — Verificar/crear .gitignore con reglas para binarios
$gitignorePath = ".gitignore"
$reglasNecesarias = @("*.zip", "*.jpg", "*.jpeg", "*.png", "*.gif", "*.pdf", "*.psd", "*.ai")

if (Test-Path $gitignorePath) {
    $contenidoActual = Get-Content $gitignorePath -Raw
} else {
    $contenidoActual = ""
}

$reglasNuevas = $reglasNecesarias | Where-Object { $contenidoActual -notmatch [regex]::Escape($_) }

if ($reglasNuevas.Count -gt 0) {
    Write-Host ""
    Write-Host "📝 Agregando reglas al .gitignore..." -ForegroundColor Cyan
    Add-Content $gitignorePath "`n# Binarios — no trackear"
    $reglasNuevas | ForEach-Object {
        Add-Content $gitignorePath $_
        Write-Host "   + $_" -ForegroundColor Green
    }
}

# PASO 5 — Commit
Write-Host ""
Write-Host "💾 Haciendo commit..." -ForegroundColor Cyan
git add .gitignore
git commit -m "fix: remover binarios del tracking · actualizar .gitignore"

Write-Host ""
Write-Host "✅ Listo. Próximo paso: git push origin main" -ForegroundColor Green
Write-Host "   (verificar con 'git log --oneline -3' que el commit quedó bien)" -ForegroundColor Gray
