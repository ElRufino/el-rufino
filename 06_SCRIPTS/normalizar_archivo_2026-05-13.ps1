# normalizar_archivo_2026-05-13.ps1
# EL_RUFINO - Bloques 2 y 3: mover VIGENTEs huerfanos + sueltos de raiz
# Generado por agente normalizacion USINA · 2026-05-13
# Actualizado: ejecucion parcial manual previa · 3 archivos ya en destino
# Solo ASCII en el codigo

$raiz    = "F:\HERRAMIENTAS DE IA\PROYECTOS\EL_RUFINO"
$sistema = "F:\HERRAMIENTAS DE IA"
$destino = "$raiz\_ARCHIVO\2026-05-13"

# --- ESTADO DE EJECUCION PREVIA (manual) ---
# Ya copiados a destino por agente IA (sus stubs quedan en origen):
#   el-rufino_prompt-maestro_v1.1.md     (destino existe -- ERR esperado, ignorar)
#   el-rufino_repositorio-ia_v1.1.md     (destino existe -- ERR esperado, ignorar)
#   el-rufino_prompt-maestro_v1.2.md     (destino existe -- ERR esperado, ignorar)
# Pendientes: todos los demas (10 archivos en _ARCHIVO + 3 sueltos en raiz sistema)

# Crear carpeta destino si no existe
if (-not (Test-Path $destino)) {
    New-Item -ItemType Directory -Path $destino | Out-Null
    Write-Host "Carpeta creada: $destino"
} else {
    Write-Host "Carpeta destino: $destino (ya existe)"
}

# --- BLOQUE 2: VIGENTEs huerfanos en _ARCHIVO ---
Write-Host ""
Write-Host "=== BLOQUE 2: VIGENTEs huerfanos en _ARCHIVO ==="

$b2 = @(
    @{ Src = "$raiz\_ARCHIVO\el-rufino_prompt-maestro_v1.1-VIGENTE.md";
       Dst = "$destino\el-rufino_prompt-maestro_v1.1.md" },
    @{ Src = "$raiz\_ARCHIVO\el-rufino_repositorio-ia_v1.1-VIGENTE.md";
       Dst = "$destino\el-rufino_repositorio-ia_v1.1.md" },
    @{ Src = "$raiz\_ARCHIVO\el-rufino_prompt-maestro_v1.2-VIGENTE.md";
       Dst = "$destino\el-rufino_prompt-maestro_v1.2.md" },
    @{ Src = "$raiz\_ARCHIVO\el-rufino_repositorio-ia_v1.2-VIGENTE.md";
       Dst = "$destino\el-rufino_repositorio-ia_v1.2.md" },
    @{ Src = "$raiz\_ARCHIVO\usina-de-ideas_prompt-maestro_v1.5-VIGENTE.md";
       Dst = "$destino\usina-de-ideas_prompt-maestro_v1.5.md" },
    @{ Src = "$raiz\_ARCHIVO\usina-de-ideas_repositorio-ia_v1.5-VIGENTE.md";
       Dst = "$destino\usina-de-ideas_repositorio-ia_v1.5.md" },
    @{ Src = "$raiz\_ARCHIVO\usina-de-ideas_agentes-ia_v1.5-VIGENTE.md";
       Dst = "$destino\usina-de-ideas_agentes-ia_v1.5.md" },
    @{ Src = "$raiz\_ARCHIVO\VERSIONES_ANTERIORES\el-rufino_prompt-maestro_v1.3-VIGENTE.md";
       Dst = "$destino\el-rufino_prompt-maestro_v1.3.md" },
    @{ Src = "$raiz\_ARCHIVO\VERSIONES_ANTERIORES\el-rufino_repositorio-ia_v1.3-VIGENTE.md";
       Dst = "$destino\el-rufino_repositorio-ia_v1.3.md" },
    @{ Src = "$raiz\_ARCHIVO\VERSIONES_ANTERIORES\el-rufino_agentes-ia_v1.3-VIGENTE.md";
       Dst = "$destino\el-rufino_agentes-ia_v1.3.md" },
    @{ Src = "$raiz\_ARCHIVO\VERSIONES_ANTERIORES\usina-de-ideas_prompt-maestro_v1.4-VIGENTE.md";
       Dst = "$destino\usina-de-ideas_prompt-maestro_v1.4.md" },
    @{ Src = "$raiz\_ARCHIVO\VERSIONES_ANTERIORES\usina-de-ideas_repositorio-ia_v1.4-VIGENTE.md";
       Dst = "$destino\usina-de-ideas_repositorio-ia_v1.4.md" },
    @{ Src = "$raiz\_ARCHIVO\VERSIONES_ANTERIORES\usina-de-ideas_agentes-ia_v1.4-VIGENTE.md";
       Dst = "$destino\usina-de-ideas_agentes-ia_v1.4.md" }
)

# --- BLOQUE 3: Sueltos en raiz del sistema ---
Write-Host ""
Write-Host "=== BLOQUE 3: Sueltos en raiz del sistema ==="

$b3 = @(
    @{ Src = "$sistema\usina-de-ideas_prompt-maestro_v1.6-VIGENTE.md";
       Dst = "$destino\usina-de-ideas_prompt-maestro_v1.6.md" },
    @{ Src = "$sistema\usina-de-ideas_repositorio-ia_v1.6-VIGENTE.md";
       Dst = "$destino\usina-de-ideas_repositorio-ia_v1.6.md" },
    @{ Src = "$sistema\usina-de-ideas_agentes-ia_v1.6-VIGENTE.md";
       Dst = "$destino\usina-de-ideas_agentes-ia_v1.6.md" }
)

$todos = $b2 + $b3
$ok = 0
$err = 0
$noexiste = 0
$yafecha = 0

foreach ($item in $todos) {
    if (Test-Path $item.Src) {
        if (Test-Path $item.Dst) {
            Write-Host "YA  $(Split-Path $item.Src -Leaf) (destino ya existe -- manual previo)"
            $yafecha++
        } else {
            try {
                Move-Item -Path $item.Src -Destination $item.Dst -ErrorAction Stop
                Write-Host "OK  $(Split-Path $item.Src -Leaf)"
                $ok++
            } catch {
                Write-Host "ERR $(Split-Path $item.Src -Leaf): $_"
                $err++
            }
        }
    } else {
        Write-Host "N/A $(Split-Path $item.Src -Leaf) (no encontrado)"
        $noexiste++
    }
}

Write-Host ""
Write-Host "RESULTADO: $ok movidos · $yafecha ya en destino · $err errores · $noexiste no encontrados"
Write-Host "Destino final: $destino"
