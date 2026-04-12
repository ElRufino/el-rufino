# ============================================================
# SCRIPT DE REORGANIZACION - PROYECTO EL RUFINO
# Version: 2.1 - Abril 2026
# Ejecutar: powershell -ExecutionPolicy Bypass -File "ruta\reorganizar-el-rufino-v2.ps1"
# Ruta base: F:\HERRAMIENTAS DE IA\CLAUDE\EL RUFINO
# ============================================================

$base     = "F:\HERRAMIENTAS DE IA\CLAUDE\EL RUFINO"
$material = "$base\MATERIAL\HTML"

Write-Host ""
Write-Host "=== REORGANIZACION PROYECTO EL RUFINO v2.1 ===" -ForegroundColor Cyan
Write-Host "Base: $base"
Write-Host ""

$carpetas = @(
    "$base\00_CONTEXTO_IA",
    "$base\01_DOCUMENTOS_VIGENTES",
    "$base\02_WORDPRESS_TEST",
    "$base\03_IDENTIDAD_MARCA",
    "$base\04_DASHBOARDS_REFERENCIA",
    "$base\05_MATERIAL_HISTORICO",
    "$base\_ARCHIVO"
)

foreach ($c in $carpetas) {
    if (-not (Test-Path $c)) {
        New-Item -ItemType Directory -Path $c -Force | Out-Null
        Write-Host "  [CREADA] $c" -ForegroundColor Green
    } else {
        Write-Host "  [YA EXISTE] $c" -ForegroundColor Yellow
    }
}

Write-Host ""

function Mover {
    param($origen, $destino, $nuevoNombre = "")
    if (Test-Path $origen) {
        $dest = if ($nuevoNombre) { "$destino\$nuevoNombre" } else { $destino }
        Move-Item -Path $origen -Destination $dest -Force
        $nombre = if ($nuevoNombre) { $nuevoNombre } else { Split-Path $origen -Leaf }
        Write-Host "  [OK] $nombre" -ForegroundColor Green
    } else {
        Write-Host "  [NO ENCONTRADO] $(Split-Path $origen -Leaf)" -ForegroundColor DarkGray
    }
}

Write-Host ">> 00_CONTEXTO_IA" -ForegroundColor Cyan
Mover "$base\el-rufino_prompt-maestro_v1.1.md"    "$base\00_CONTEXTO_IA" "el-rufino_prompt-maestro_v1.1-VIGENTE.md"
Mover "$base\el-rufino_repositorio-ia_v1.1.md"    "$base\00_CONTEXTO_IA" "el-rufino_repositorio-ia_v1.1-VIGENTE.md"
Mover "$base\agentes-ia-el-rufino.md"             "$base\00_CONTEXTO_IA" "el-rufino_agentes-ia_v1.md"
Mover "$material\rufino_prompt_maestro.md"        "$base\00_CONTEXTO_IA" "el-rufino_prompt-maestro_v1.0.md"

Write-Host ""
Write-Host ">> 01_DOCUMENTOS_VIGENTES" -ForegroundColor Cyan
Mover "$base\el-rufino_dashboard-operativo_v5.html"      "$base\01_DOCUMENTOS_VIGENTES" "el-rufino_dashboard-operativo_v5-VIGENTE.html"
Mover "$material\ElRufino_Dossier_Fase0_v3.html"         "$base\01_DOCUMENTOS_VIGENTES" "el-rufino_dossier-fase0_v3-VIGENTE.html"
Mover "$material\el_rufino_dossier_maestro.pdf"          "$base\01_DOCUMENTOS_VIGENTES" "el-rufino_dossier-maestro_VIGENTE.pdf"
Mover "$material\rufino_plan_fundacional.html"           "$base\01_DOCUMENTOS_VIGENTES" "el-rufino_plan-fundacional.html"
Mover "$material\el_rufino_dossier_fundacional.html"     "$base\01_DOCUMENTOS_VIGENTES" "el-rufino_dossier-fundacional.html"

Write-Host ""
Write-Host ">> 02_WORDPRESS_TEST" -ForegroundColor Cyan
Mover "$base\el-rufino-child-theme.zip"   "$base\02_WORDPRESS_TEST" "el-rufino_child-theme_ACTIVO.zip"
Mover "$base\el-rufino-plugin-v4.zip"     "$base\02_WORDPRESS_TEST" "el-rufino_plugin_v4-FINAL.zip"

Write-Host ""
Write-Host ">> 03_IDENTIDAD_MARCA" -ForegroundColor Cyan
Mover "$material\manual_identidad_el_rufino_dashboard_v1_1.html"           "$base\03_IDENTIDAD_MARCA" "el-rufino_manual-identidad_v1.1.html"
Mover "$base\WhatsApp Image 2026-04-04 at 23.36.39.jpeg"                   "$base\03_IDENTIDAD_MARCA" "el-rufino_imagen-referencia_2026-04-04.jpeg"
Mover "$base\MATERIAL\IMAGENES\WhatsApp Image 2026-03-30 at 23.55.10.jpeg" "$base\03_IDENTIDAD_MARCA" "el-rufino_imagen-referencia_2026-03-30.jpeg"
Mover "$material\ChatGPT Image 31 mar 2026, 12_13_50 a.m..png"             "$base\03_IDENTIDAD_MARCA" "el-rufino_imagen-referencia-chatgpt_2026-03-31.png"
Mover "$material\page-3.png"   "$base\03_IDENTIDAD_MARCA" "el-rufino_captura-nic-page3.png"
Mover "$material\page-5.png"   "$base\03_IDENTIDAD_MARCA" "el-rufino_captura-nic-page5.png"

Write-Host ""
Write-Host ">> 04_DASHBOARDS_REFERENCIA" -ForegroundColor Cyan
Mover "$material\dashboard_analitico_el_rufino.html"               "$base\04_DASHBOARDS_REFERENCIA" "el-rufino_dash-analitico.html"
Mover "$material\dashboard_ecosistema_rufino_light.html"           "$base\04_DASHBOARDS_REFERENCIA" "el-rufino_dash-ecosistema.html"
Mover "$material\dashboard_estrategico_medio_rufino.html"          "$base\04_DASHBOARDS_REFERENCIA" "el-rufino_dash-estrategico.html"
Mover "$material\dashboard_operativo_el_rufino.html"               "$base\04_DASHBOARDS_REFERENCIA" "el-rufino_dash-operativo.html"
Mover "$material\dashboard_sentimiento_politico_rufino.html"       "$base\04_DASHBOARDS_REFERENCIA" "el-rufino_dash-sentimiento-politico.html"
Mover "$material\dashboard_sentimiento_politico_rufino_v1_1.html"  "$base\04_DASHBOARDS_REFERENCIA" "el-rufino_dash-sentimiento-politico_v1.1.html"
Mover "$material\planificacion_operativa_dashboard.html"           "$base\04_DASHBOARDS_REFERENCIA" "el-rufino_dash-planificacion-operativa.html"
Mover "$material\planificacion_operativa_dashboard (1).html"       "$base\04_DASHBOARDS_REFERENCIA" "el-rufino_dash-planificacion-operativa_v2.html"

Write-Host ""
Write-Host ">> 05_MATERIAL_HISTORICO" -ForegroundColor Cyan
Mover "$base\MATERIAL\EL RUFINO.txt"          "$base\05_MATERIAL_HISTORICO" "el-rufino_brief-original_v1.txt"
Mover "$base\BASES EL RUFINO - 02-04-26.txt"  "$base\05_MATERIAL_HISTORICO" "el-rufino_brief-bases_2026-04-02.txt"
Mover "$material\preview.html"                "$base\05_MATERIAL_HISTORICO" "el-rufino_preview-exploracion.html"

Write-Host ""
Write-Host ">> _ARCHIVO (versiones obsoletas)" -ForegroundColor Cyan
Mover "$base\el-rufino-dashboard.html"            "$base\_ARCHIVO" "el-rufino_dashboard-operativo_v4-OBSOLETO.html"
Mover "$material\ElRufino_Dossier_Fase0.html"     "$base\_ARCHIVO" "el-rufino_dossier-fase0_v1-OBSOLETO.html"
Mover "$material\ElRufino_Dossier_Fase0_v2.html"  "$base\_ARCHIVO" "el-rufino_dossier-fase0_v2-OBSOLETO.html"
Mover "$base\el-rufino-plugin-v2.zip"             "$base\_ARCHIVO" "el-rufino_plugin_v2-OBSOLETO.zip"
Mover "$base\el-rufino-plugin-v2-fix.zip"         "$base\_ARCHIVO" "el-rufino_plugin_v2fix-OBSOLETO.zip"
Mover "$base\el-rufino-plugin-v3.zip"             "$base\_ARCHIVO" "el-rufino_plugin_v3-OBSOLETO.zip"
Mover "$material\el-rufino-tema-wp.zip"               "$base\_ARCHIVO" "el-rufino_tema-wp_OBSOLETO.zip"
Mover "$material\el-rufino-tema-v2.zip"               "$base\_ARCHIVO" "el-rufino_tema-v2_OBSOLETO.zip"
Mover "$material\el-rufino-bootstrap-installable.zip" "$base\_ARCHIVO" "el-rufino_bootstrap_OBSOLETO.zip"
Mover "$material\el-rufino-ui-installable.zip"        "$base\_ARCHIVO" "el-rufino_ui_OBSOLETO.zip"
Mover "$material\el-rufino-ui-patch-installable.zip"  "$base\_ARCHIVO" "el-rufino_ui-patch_OBSOLETO.zip"
Mover "$material\el-rufino-demo-content-installable.zip" "$base\_ARCHIVO" "el-rufino_demo-content_OBSOLETO.zip"
Mover "$base\reorganizar-el-rufino (1).ps1"          "$base\_ARCHIVO" "reorganizar-el-rufino_v1-OBSOLETO.ps1"

Write-Host ""
Write-Host ">> Limpiando carpetas vacias..." -ForegroundColor Cyan
$carpetasVacias = @(
    "$base\MATERIAL\HTML",
    "$base\MATERIAL\IMAGENES",
    "$base\MATERIAL",
    "$base\Plug in"
)
foreach ($cv in $carpetasVacias) {
    if ((Test-Path $cv) -and ((Get-ChildItem $cv -Recurse | Measure-Object).Count -eq 0)) {
        Remove-Item $cv -Force -Recurse
        Write-Host "  [ELIMINADA VACIA] $cv" -ForegroundColor DarkGray
    } elseif (Test-Path $cv) {
        Write-Host "  [NO VACIA, conservada] $cv" -ForegroundColor Yellow
        Get-ChildItem $cv -Recurse | ForEach-Object { Write-Host "    $($_.FullName)" -ForegroundColor DarkYellow }
    }
}

Write-Host ""
Write-Host "=== REORGANIZACION COMPLETADA ===" -ForegroundColor Cyan
Write-Host ""
Write-Host "  00_CONTEXTO_IA           - prompt maestro v1.1 / repositorio-ia v1.1 / agentes" -ForegroundColor Gray
Write-Host "  01_DOCUMENTOS_VIGENTES   - dossier v3 / dashboard v5 / PDF maestro / plan fundacional" -ForegroundColor Gray
Write-Host "  02_WORDPRESS_TEST        - plugin v4 + child theme (infoconectados)" -ForegroundColor Gray
Write-Host "  03_IDENTIDAD_MARCA       - manual visual v1.1 + imagenes de referencia" -ForegroundColor Gray
Write-Host "  04_DASHBOARDS_REFERENCIA - dashboards HTML de referencia" -ForegroundColor Gray
Write-Host "  05_MATERIAL_HISTORICO    - briefs y exploracion inicial" -ForegroundColor Gray
Write-Host "  _ARCHIVO                 - versiones obsoletas (no borrar)" -ForegroundColor Gray
Write-Host ""
Write-Host "Dominio objetivo : elrufino.ar (pendiente registro NIC)" -ForegroundColor Yellow
Write-Host "Entorno test     : elrufino.infoconectados.com.ar" -ForegroundColor Yellow
Write-Host ""
