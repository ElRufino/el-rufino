# REPORTE DE ORGANIZACIÓN — DISCO E (192.168.120.40)
## Fuente: auditoria_disco_E.csv — 75.561 registros · 67.623 archivos · ~127 GB
## Fecha: 2026-05-16

---

> **NOTA:** Este CSV es más completo que el analizado en la auditoría de seguridad
> (5.606 registros). Incluye la totalidad del disco montado como I:\ en el momento
> del escaneo. Las rutas aquí son I:\ — en J:\ son equivalentes.

---

## PASO 1 — DUPLICADOS EXACTOS (mismo nombre + mismo tamaño)

### Resumen global

| Métrica | Valor |
|---|---|
| Grupos de duplicados detectados | 6.601 |
| Archivos duplicados totales | 21.106 |
| Espacio desperdiciado estimado | ~14,7 GB |

### Categoría A — Drivers duplicados: 09-02-24 vs BACK 21-06-2024\Desktop\DRIVERS

1.142 grupos de archivos idénticos entre ambas carpetas. Son los mismos paquetes de drivers AMD/Realtek descomprimidos dos veces.

**Espacio desperdiciado: ~3,0 GB**

Top duplicados de esta categoría (por tamaño):

| Archivo | Tamaño | Ruta 1 | Ruta 2 |
|---|---|---|---|
| mb_driver_chipset_am1_18.30.18.zip | 856 MB | I:\09-02-24\ | I:\BACK 21-06-2024\Desktop\DRIVERS\ |
| mb_driver_audio_Kabini.zip | 227 MB | I:\09-02-24\ | I:\BACK 21-06-2024\Desktop\DRIVERS\ |
| ccc2_install.exe (WT6A) | 127 MB | I:\09-02-24\mb_driver_chipset...\ | I:\BACK 21-06-2024\Desktop\DRIVERS\mb_driver_chipset...\ |
| ccc2_install.exe (W7) | 107 MB | I:\09-02-24\mb_driver_chipset...\ | I:\BACK 21-06-2024\Desktop\DRIVERS\mb_driver_chipset...\ |
| RCORES.dat | 69 MB | I:\09-02-24\mb_driver_audio...\ | I:\BACK 21-06-2024\Desktop\DRIVERS\mb_driver_audio...\ |
| RCORES64.dat | 69 MB | I:\09-02-24\mb_driver_audio...\ | I:\BACK 21-06-2024\Desktop\DRIVERS\mb_driver_audio...\ |

**Recomendación:** Conservar solo I:\BACK 21-06-2024\Desktop\DRIVERS\ (más reciente). Eliminar I:\09-02-24\ completa (3,63 GB).

---

### Categoría B — COMPRIMIDOS vs AUTOMATIZACION RADIO

2 archivos grandes duplicados entre ambas carpetas:

| Archivo | Tamaño | Ruta 1 | Ruta 2 | Conservar |
|---|---|---|---|---|
| DinesatProRadio11Soft_v11.0.5.8.zip | 835 MB | I:\AUTOMATIZACION RADIO\ | I:\COMPRIMIDOS\ | AUTOMATIZACION RADIO |
| HdxRadio3  3.0.25.2.rar | 644 MB | I:\AUTOMATIZACION RADIO\ | I:\COMPRIMIDOS\ | AUTOMATIZACION RADIO |

**Espacio desperdiciado: ~1,4 GB**

---

### Categoría C — Office 2019 ES duplicado interno

La carpeta de Office tiene una subcarpeta duplicada dentro de sí misma:

| Ruta | Archivos | Tamaño |
|---|---|---|
| I:\OFFICE\Office 2019 ES\Office 2019 ES\Office\ | 23 | 4,5 GB |
| I:\OFFICE\Office 2019 ES\Office 2019 ES\Office 2019 ES\ | 30 | 4,5 GB |

Contenido idéntico (28 grupos de archivos comunes). La subcarpeta `Office 2019 ES\Office 2019 ES\Office 2019 ES\` es una copia redundante.

**Espacio desperdiciado: ~4,5 GB**

---

### Categoría D — Videos DANILO duplicados

8 videos duplicados entre I:\VIDEOS GOOGLE\DANILO\ y I:\VIDEOS GOOGLE\DANILO 1\

**Espacio desperdiciado: ~123 MB**

---

### Categoría E — Duplicados internos en AUTOMATIZACION RADIO

15 grupos de archivos duplicados dentro de la misma carpeta. Los más relevantes:

| Archivo | Tamaño | Ruta A | Ruta B |
|---|---|---|---|
| HARDATA-VIDEO.nrg | 1.950 MB | I:\AUTOMATIZACION RADIO\HARDATA-VIDEO\ | I:\AUTOMATIZACION RADIO\AUTOMATV\AUTOMATV\HARDATA-VIDEO\ |
| Hardata hdxVideo Pro 64 bits.rar | 1.934 MB | I:\AUTOMATIZACION RADIO\ | I:\AUTOMATIZACION RADIO\HD-Tuto\HD-Tuto\ |
| HDXAuditManager.exe | 5,9 MB | I:\AUTOMATIZACION RADIO\CRACK HDX VIDEO\...\ | I:\AUTOMATIZACION RADIO\AUTOMATV\...\  |
| HdxRadio3.exe | 4,9 MB | I:\AUTOMATIZACION RADIO\HardataHdxRadio3_v3.0.38.5\...\Crack\ | I:\AUTOMATIZACION RADIO\HdxRadio3 3.0.25.2\PARCHES DINESAT\ |
| HdxServer3.exe | 3,1 MB | I:\AUTOMATIZACION RADIO\HardataHdxRadio3_v3.0.38.5\...\Crack\ | I:\AUTOMATIZACION RADIO\HdxRadio3 3.0.25.2\PARCHES DINESAT\ |
| CLAVES.txt | — | I:\AUTOMATIZACION RADIO\ | I:\AUTOMATIZACION RADIO\AUTOMATV\AUTOMATV\ |

**Espacio desperdiciado: ~3,8 GB**
**Recomendación:** Conservar HARDATA-VIDEO.nrg en raíz; eliminar copia de AUTOMATV\AUTOMATV\. Conservar Hardata hdxVideo Pro 64 bits.rar en raíz; eliminar copia de HD-Tuto\.

---

### Categoría F — WINDOWS 7 duplicado interno

I:\WINDOWS 7\WinSetupFromUSB-1-7\WinSetupFromUSB-1-7\ es una copia de I:\WINDOWS 7\WinSetupFromUSB-1-7\
432 grupos duplicados · ~88 MB desperdiciados.

---

## PASO 2 — CONTENIDO REDUNDANTE (backups superpuestos)

### Mapa de carpetas raíz del disco

| Carpeta | Archivos | Tamaño | Naturaleza |
|---|---|---|---|
| I:\BACK 21-06-2024\ | 7.049 | 33,2 GB | Backup completo del día del ataque — REFERENCIA |
| I:\BACK SSD - WIN11\ | 30.429 | 5,3 GB | Backup de SSD Win11 — principalmente Desktop (30.384 archivos) |
| I:\BACKUP - 08-03-2023\ | 392 | 8,0 GB | Backup marzo 2023 — carpetas CONECTADOS + DANILO |
| I:\09-02-24\ | 1.645 | 3,6 GB | Snapshot drivers febrero 2024 — DUPLICADO de BACK 21-06-2024\Desktop\DRIVERS\ |
| I:\AUTOMATIZACION RADIO\ | 222 | 23,3 GB | Software de radio — consolidar en RADIO_TV |
| I:\OFFICE\ | 359 | 20,3 GB | Instaladores Office — tiene dup interno de 4,5 GB |
| I:\WINDOWS 7\ | 986 | 14,6 GB | ISO y herramientas Win7 — tiene dup interno de 88 MB |
| I:\COMPRIMIDOS\ | 27 | 4,0 GB | Instaladores varios — tiene dups con otras carpetas |
| I:\GESTION DE MEDIOS COMUNITARIOS\ | 25.182 | 0,6 GB | Documentos cursos — 25.182 archivos pequeños |
| I:\VIDEOS GOOGLE\ | 48 | 2,8 GB | Videos — tiene dup interno DANILO vs DANILO 1 |
| I:\18-04-2023 - BACKUP\ | 96 | 1,9 GB | Backup puntual |
| I:\BACK TELEFONO 03-08-2023\ | 274 | 0,9 GB | Backup teléfono |
| I:\CONECTADOS-INFO\ | 3 | 1,3 GB | Backup WordPress (ZIP + parcial) |
| I:\Kuschelrock 11-20\ | 367 | 3,5 GB | Colección musical descomprimida |
| I:\Kuschelrock 11-20.rar | 1 | 3,2 GB | DUPLICADO del anterior en formato comprimido |

### Análisis de superposición entre backups

**09-02-24 vs BACK 21-06-2024\Desktop\DRIVERS\**
- Son la misma colección de drivers AMD/Realtek
- 09-02-24 los tiene descomprimidos + el ZIP original
- BACK 21-06-2024 los tiene en ambas formas también
- 0 matches en path relativo (distintas fechas de backup)
- **Recomendación: eliminar 09-02-24 completa** — el backup de junio es más reciente y completo

**Kuschelrock 11-20 vs Kuschelrock 11-20.rar**
- 367 archivos descomprimidos (3,52 GB) + RAR origen (3,19 GB)
- Misma colección en doble formato
- **Recomendación: eliminar el RAR** si la colección descomprimida está completa, o viceversa

**BACK SSD - WIN11**
- 30.384 de sus 30.429 archivos están en Desktop — parece un backup de Desktop de una PC con Win11
- Contenido: fotos con prefijo PhotoRoom (logos para redes sociales), Downloads (598 MB)
- No se superpone significativamente con otros backups

---

## PASO 3 — CARPETA RADIO_TV (J:\RADIO_TV\)

### Estructura propuesta

```
J:\RADIO_TV\
├── Dinesat9\          ← binarios activos + ISO instalación
├── DinesatPro11\      ← ZIP instalador completo
├── HDX_Radio3\        ← ejecutables crackeados v3.0.38.5
├── HDX_Video\         ← instalador RAR + dongle emulator
├── Horarias_Audio\    ← MP3 de horas y minutos + HORARIAS_IVAN_LOSCHER
├── Licencias\         ← CLAVES.txt + documentación activación
├── Documentacion\     ← manuales PDF + video INSTALACION HDX.mp4
└── _ARCHIVO\          ← versiones anteriores y duplicados conservados
```

### Lista exacta de movimientos — Origen → Destino en J:\

#### Prioridad INMEDIATA — Archivos críticos

| Archivo | Origen en J:\ | Destino en J:\ |
|---|---|---|
| CLAVES.txt | J:\AUTOMATIZACION RADIO\CLAVES.txt | J:\RADIO_TV\Licencias\CLAVES.txt |
| DinesatRadio9.exe | J:\BACK 21-06-2024\Desktop\INFOCONECTADOS\ | J:\RADIO_TV\Dinesat9\ |
| DinesatServer9.exe | J:\BACK 21-06-2024\Desktop\INFOCONECTADOS\ | J:\RADIO_TV\Dinesat9\ |
| HdxServerImporter.exe | J:\BACK 21-06-2024\Desktop\INFOCONECTADOS\ | J:\RADIO_TV\HDX_Radio3\ |

#### Instaladores principales (mover carpeta completa)

| Origen en J:\ | Destino en J:\ | Tamaño |
|---|---|---|
| J:\AUTOMATIZACION RADIO\HardataHdxRadio3_v3.0.38.5\ | J:\RADIO_TV\HDX_Radio3\ | 651 MB |
| J:\AUTOMATIZACION RADIO\Hardata hdxVideo Pro 64 bits.rar | J:\RADIO_TV\HDX_Video\ | 1.934 MB |
| J:\AUTOMATIZACION RADIO\Hardata hdxVideo Pro 64 bitsB\ (subcarpeta completa) | J:\RADIO_TV\HDX_Video\Licencia_digital\ | 1.941 MB |
| J:\AUTOMATIZACION RADIO\DinesatProRadio11Soft_v11.0.5.8.zip | J:\RADIO_TV\DinesatPro11\ | 835 MB |
| J:\AUTOMATIZACION RADIO\DinesatVisualRadioSoft.exe | J:\RADIO_TV\DinesatPro11\ | 1.270 MB |
| J:\AUTOMATIZACION RADIO\DinesatProRadio11Soft.exe | J:\RADIO_TV\DinesatPro11\ | 837 MB |
| J:\AUTOMATIZACION RADIO\HARDATA-VIDEO.nrg | J:\RADIO_TV\_ARCHIVO\ | 1.950 MB |
| J:\AUTOMATIZACION RADIO\PARCHES DINESAT\ | J:\RADIO_TV\_ARCHIVO\HdxRadio3_v3.0.25.2\ | 9 MB |

#### Audio horario (automatización)

| Acción | Detalle |
|---|---|
| Mover | J:\AUTOMATIZACION RADIO\HORA_*.mp3 + HRS*.mp3 + MINUTOS*.mp3 → J:\RADIO_TV\Horarias_Audio\ |
| Nota | Son 109 MP3 de locución horaria (~6 MB total) — parte del sistema de automatización |

#### Documentación

| Archivo | Origen | Destino |
|---|---|---|
| Hardata Hdx Video Quick Guide Spanish.pdf | J:\AUTOMATIZACION RADIO\ | J:\RADIO_TV\Documentacion\ |
| Hdx Video Movie Spanish.pdf | J:\AUTOMATIZACION RADIO\Hardata hdxVideo Pro 64 bitsB\...\ | J:\RADIO_TV\Documentacion\ |
| INSTALACION HDX.mp4 | J:\AUTOMATIZACION RADIO\ | J:\RADIO_TV\Documentacion\ |
| INSTRUCCIONES.txt | J:\AUTOMATIZACION RADIO\ | J:\RADIO_TV\Documentacion\ |

#### NO incluir en RADIO_TV (dejar en origen o mover a _ELIMINAR)

| Archivo | Motivo |
|---|---|
| J:\AUTOMATIZACION RADIO\VM1X.PR0.22.AP.ZENTINELS\ (270 MB) | Crack vMix con malware — NO EJECUTAR |
| J:\AUTOMATIZACION RADIO\vMix Pro 22.0.0.66\ (549 MB) | Crack vMix — NO EJECUTAR |
| J:\AUTOMATIZACION RADIO\AUTOMATV\ (3.923 MB) | Contiene dups del .nrg y CLAVES — mover a _ARCHIVO |
| J:\AUTOMATIZACION RADIO\HD-Tuto\ (1.983 MB) | Contiene dup de Hardata hdxVideo Pro 64 bits.rar — mover a _ARCHIVO |
| J:\AUTOMATIZACION RADIO\CRACK HDX VIDEO\ | Ya incluido en carpeta Licencia digital — mover a _ARCHIVO |
| J:\COMPRIMIDOS\HdxRadio3  3.0.25.2.rar | Dup de HardataHdxRadio3 ya incluido — mover a _ARCHIVO |
| J:\COMPRIMIDOS\DinesatProRadio11Soft_v11.0.5.8.zip | Dup ya incluido en DinesatPro11 — mover a _ARCHIVO |

---

## PASO 4 — CANDIDATOS A ELIMINAR

> **Regla:** No se elimina nada sin confirmación. Los .ooxa NO están incluidos (son evidencia).
> Orden por impacto de espacio en disco.

### Grupo 1 — CRÍTICO: Malware confirmado

| Archivo | Tamaño | Ruta | Motivo |
|---|---|---|---|
| Stremio+4.4.168.exe | 112 MB | J:\BACK 21-06-2024\Desktop\ | DROPPER RANSOMWARE — vector del ataque |
| Stremio.lnk | — | J:\BACK 21-06-2024\Desktop\ | Acceso directo al dropper |
| vMix.exe | 6,1 MB | J:\AUTOMATIZACION RADIO\VM1X.PR0.22.AP.ZENTINELS\...\Crack\ | Crack ZENTINELS — malware |
| vMix64.exe | 6,1 MB | J:\AUTOMATIZACION RADIO\VM1X.PR0.22.AP.ZENTINELS\...\Crack\ | Idem |
| vMix Pro 22.00.66 x64 KG.exe | 8,5 MB | J:\AUTOMATIZACION RADIO\vMix Pro 22.0.0.66\...\CRACK\ | Keygen vMix — malware |
| fifa14-3dm.exe | 2,4 MB | J:\BACKUP - 08-03-2023\DANILO\Desktop\ | Crack 3DM con historial de malware |
| fifa14-3dm.exe | 2,4 MB | J:\BACKUP - 08-03-2023\DANILO\Desktop\Crack\ | Idem (duplicado) |
| fifa14.exe | 35,7 MB | J:\BACKUP - 08-03-2023\DANILO\Desktop\Crack\ | Idem |

### Grupo 2 — ALTO impacto: Duplicados grandes

| Archivo / Carpeta | Tamaño | Ruta a eliminar | Conservar en |
|---|---|---|---|
| Office 2019 ES (interna) | 4.500 MB | J:\OFFICE\Office 2019 ES\Office 2019 ES\Office 2019 ES\ | ...\Office\ |
| Hardata hdxVideo Pro 64 bits.rar (dup) | 1.934 MB | J:\AUTOMATIZACION RADIO\HD-Tuto\HD-Tuto\ | J:\RADIO_TV\HDX_Video\ |
| HARDATA-VIDEO.nrg (dup) | 1.950 MB | J:\AUTOMATIZACION RADIO\AUTOMATV\AUTOMATV\HARDATA-VIDEO\ | J:\RADIO_TV\_ARCHIVO\ |
| Carpeta 09-02-24 completa | 3.630 MB | J:\09-02-24\ | BACK 21-06-2024\Desktop\DRIVERS\ |

### Grupo 3 — MEDIO impacto: Instaladores obsoletos sin uso previsto

| Archivo | Tamaño | Ruta | Nota |
|---|---|---|---|
| 13BITS-IPS10.rar | 488 MB | J:\COMPRIMIDOS\ | Software de automatización radial antiguo |
| S6P12.rar | 138 MB | J:\COMPRIMIDOS\ | Idem |
| AUDICOM 10.rar | 77 MB | J:\COMPRIMIDOS\ | Competidor de Dinesat — obsoleto |
| audicom 9.zip | 45 MB | J:\COMPRIMIDOS\ | Idem |
| crm-application.zip | 123 MB | J:\COMPRIMIDOS\ | CRM genérico sin contexto de uso |
| worksuite-saas-3.9.2.zip | 67 MB | J:\COMPRIMIDOS\ | Idem |
| dolibarr-12.0.2.zip | 67 MB | J:\COMPRIMIDOS\ | ERP open source — sin uso aparente |
| floreantpos-1.4-build1707.zip | 43 MB | J:\COMPRIMIDOS\ | POS (punto de venta) — sin uso aparente |
| perfex-crm-2.7.0.zip | 39 MB | J:\COMPRIMIDOS\ | Idem crm-application |
| Mi-Punto-de-Venta-Qr-Version-Gratuita.zip | 38 MB | J:\COMPRIMIDOS\ | POS — sin uso aparente |
| Kuschelrock 11-20.rar | 3.190 MB | J:\ (raíz) | Dup de Kuschelrock 11-20\ (descomprimida) |
| c0rDR4W2K18.AP.ZENTINELS.P0RT4BL3.rar | 381 MB | J:\COMPRIMIDOS\ | Crack ZENTINELS CorelDraw — NO EJECUTAR |
| VM1X.PR0.22.AP.ZENTINELS.rar | 270 MB | J:\AUTOMATIZACION RADIO\ | Crack vMix comprimido — NO EJECUTAR |
| vMix Pro 22.0.0.66.rar | 271 MB | J:\AUTOMATIZACION RADIO\ | Idem — NO EJECUTAR |
| WinSetupFromUSB-1-7 (interna) | 88 MB | J:\WINDOWS 7\WinSetupFromUSB-1-7\WinSetupFromUSB-1-7\ | ...\WinSetupFromUSB-1-7\ |
| Videos duplicados DANILO 1 | ~123 MB | J:\VIDEOS GOOGLE\DANILO 1\ (8 videos) | J:\VIDEOS GOOGLE\DANILO\ |

### Grupo 4 — BAJO impacto: Archivos sueltos en raíz del disco

| Archivo | Tamaño | Nota |
|---|---|---|
| J:\COREL DRAW 2018 ALBERLOBO.rar | 551 MB | Dup de J:\COREL DRAW 2018 ALBERLOBO\ (descomprimida) |
| J:\msdia80.dll | 0,86 MB | DLL suelta en raíz — sin propósito |
| J:\04de83ffa8449773ae27b5bcec | — | Carpeta con nombre hash sin contenido útil |
| J:\Prohibido suicidarse en primavera.pdf | — | Archivo suelto en raíz — mover a lugar apropiado |

---

## RESUMEN DE AHORRO POTENCIAL

| Categoría | Espacio recuperable |
|---|---|
| Duplicados entre backups (cat A) | ~3,0 GB |
| Duplicados COMPRIMIDOS/AUTOMATIZACION RADIO | ~1,4 GB |
| Office 2019 ES duplicado interno | ~4,5 GB |
| Duplicados internos AUTOMATIZACION RADIO | ~3,8 GB |
| Carpeta 09-02-24 completa | ~3,6 GB |
| RAR grandes duplicados (Kuschelrock, CorelDraw) | ~3,7 GB |
| Instaladores obsoletos COMPRIMIDOS | ~0,5 GB |
| **TOTAL ESTIMADO** | **~20,5 GB** |

---

## ORDEN DE EJECUCIÓN RECOMENDADO

1. **CONFIRMAR** este reporte con el usuario antes de mover nada
2. Crear J:\RADIO_TV\ y subcarpetas
3. Copiar (no mover todavía) archivos clave: CLAVES.txt, Dinesat9 binarios, HDX Radio 3 Crack\
4. Verificar que las copias son correctas
5. Mover (no borrar) los duplicados a J:\_PAPELERA_DISCO\YYYY-MM-DD\
6. Verificar integridad de J:\RADIO_TV\ 
7. Solo después de verificación: eliminar papelera

---

*Reporte generado: 2026-05-16*
*Fuente: auditoria_disco_E.csv (75.561 registros)*
*Referencia: reporte_auditoria_disco_E_2026-05-16.md*
