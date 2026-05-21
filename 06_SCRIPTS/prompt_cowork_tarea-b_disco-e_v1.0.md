# PROMPT COWORK — TAREA B
## Organización y limpieza del disco E (servidor 192.168.120.40)
## Fecha de uso: pegar cuando el disco esté montado como J:\ o similar

---

Sos el agente de organización del sistema HERRAMIENTAS DE IA.

Tu tarea es analizar, organizar y limpiar el contenido del disco de backup
del servidor de radio (192.168.120.40), montado localmente como J:\ (o la
letra de unidad disponible).

## CONTEXTO PREVIO (ya auditado en sesión 2026-05-16)

El disco contiene un backup tomado el 21/06/2024 de un equipo infectado con
ransomware STOP/DJVU (variante .ooxa). El análisis completo está en:
`F:\HERRAMIENTAS DE IA\PROYECTOS\EL_RUFINO\reporte_auditoria_disco_E_2026-05-16.md`

Datos clave del incidente:
- 3.160 archivos de música cifrados con extensión .ooxa — IRRECUPERABLES
- Vector de infección: Stremio+4.4.168.exe (descargado 20/06/2024 21:10)
- Tipo de clave: ONLINE — sin posibilidad de descifrado gratuito actual

Archivos peligrosos confirmados (NO ejecutar, NO mover a equipo nuevo):
- J:\BACK 21-06-2024\Desktop\Stremio+4.4.168.exe  ← DROPPER RANSOMWARE
- J:\...\VM1X.PR0.22.AP.ZENTINELS\Crack\vMix.exe  ← crack con malware
- J:\...\VM1X.PR0.22.AP.ZENTINELS\Crack\vMix64.exe
- J:\BACKUP\DANILO\Crack\fifa14-3dm.exe            ← crack con malware

## TAREA B — ORGANIZACIÓN DEL SOFTWARE REUTILIZABLE

### PASO 1 — Verificar rutas

Confirmar que las siguientes rutas existen en el disco montado y reportar
si alguna no se encuentra:

Software Hardata/Dinesat/HDX:
- J:\BACK 21-06-2024\Desktop\Hardata\Dinesat 9 Classic.iso
- J:\INFOCONECTADOS\DinesatRadio9.exe
- J:\INFOCONECTADOS\DinesatServer9.exe
- J:\BACK 21-06-2024\Desktop\Hardata\DinesatProRadio11Soft_v11.0.5.8.zip
- J:\BACK 21-06-2024\Desktop\Hardata\DinesatProRadio11Soft_v11.0.1.2.zip
- J:\BACK 21-06-2024\Desktop\Hardata\DinesatVisualRadioSoft_v4.0.9.0\
- J:\BACK 21-06-2024\Desktop\Hardata\HardataHdxRadio3_v3.0.38.5\Crack\
- J:\BACK 21-06-2024\Desktop\Hardata\HdxRadio3 3.0.25.2\PARCHES DINESAT\
- J:\BACK 21-06-2024\Desktop\Hardata\Hardata hdxVideo Pro 64 bits.rar
- J:\BACK 21-06-2024\Desktop\Hardata\Licencia digital\
- J:\BACK 21-06-2024\Desktop\Hardata\Llave Digital\emul\
- J:\AUTOMATIZACION RADIO\CLAVES.txt
- J:\INFOCONECTADOS\HdxServerImporter.exe

### PASO 2 — Crear estructura de carpetas en destino

Crear la siguiente estructura en C:\RadioTV\ del equipo local
(NO en el disco J:\ — solo lectura):

```
C:\RadioTV\
├── Dinesat9\           ← instalación Dinesat Radio 9
├── DinesatPro11\       ← instalación Dinesat Pro Radio 11
├── HDX_Radio3\         ← HDX Radio 3.0.38.5
├── HDX_Video\          ← HDX Video Pro 64 bits
├── Instaladores\       ← ISOs y ZIPs originales
├── Licencias\          ← CLAVES.txt y seriales
└── Documentacion\      ← guías y manuales PDF
```

### PASO 3 — Copiar archivos útiles de J:\ a C:\RadioTV\

Ejecutar las siguientes copias (robocopy o xcopy según disponibilidad):

**Prioridad INMEDIATA:**
```
J:\AUTOMATIZACION RADIO\CLAVES.txt  →  C:\RadioTV\Licencias\CLAVES.txt
```

**Prioridad ALTA — Dinesat 9:**
```
J:\BACK 21-06-2024\Desktop\Hardata\Dinesat 9 Classic.iso
  →  C:\RadioTV\Instaladores\Dinesat9Classic.iso

J:\INFOCONECTADOS\DinesatRadio9.exe
  →  C:\RadioTV\Dinesat9\DinesatRadio9.exe

J:\INFOCONECTADOS\DinesatServer9.exe
  →  C:\RadioTV\Dinesat9\DinesatServer9.exe
```

**Prioridad ALTA — HDX Radio 3:**
```
J:\BACK 21-06-2024\Desktop\Hardata\HardataHdxRadio3_v3.0.38.5\Crack\
  →  C:\RadioTV\HDX_Radio3\  (carpeta completa)
```

**Prioridad MEDIA — HDX Video:**
```
J:\BACK 21-06-2024\Desktop\Hardata\Hardata hdxVideo Pro 64 bits.rar
  →  C:\RadioTV\Instaladores\HardataHdxVideoPro64.rar

J:\BACK 21-06-2024\Desktop\Hardata\Licencia digital\
  →  C:\RadioTV\HDX_Video\Licencia digital\  (carpeta completa)

J:\BACK 21-06-2024\Desktop\Hardata\Llave Digital\emul\
  →  C:\RadioTV\HDX_Video\emul\  (carpeta completa)
```

**Prioridad MEDIA — Dinesat Pro 11:**
```
J:\BACK 21-06-2024\Desktop\Hardata\DinesatProRadio11Soft_v11.0.5.8.zip
  →  C:\RadioTV\Instaladores\DinesatProRadio11Soft_v11.0.5.8.zip
```

**Documentación:**
```
J:\BACK 21-06-2024\Desktop\Hardata\Hardata Hdx Video Quick Guide Spanish.pdf
  →  C:\RadioTV\Documentacion\HardataHdxVideoQuickGuideSpanish.pdf
```

### PASO 4 — Informe de duplicados en disco J:\

Buscar y reportar duplicados en el disco J:\ (sin moverlos ni borrarlos):
- Archivos con nombre idéntico en múltiples carpetas
- ISOs o ZIPs del mismo producto en más de una ubicación
- Cualquier carpeta que aparezca duplicada bajo rutas distintas

Presentar como tabla: Archivo · Ruta 1 · Ruta 2 · Tamaño · Acción sugerida

### PASO 5 — Inventario final

Generar un archivo de texto con el inventario de C:\RadioTV\ una vez
completadas las copias:
- Ruta del archivo, tamaño, fecha de origen en disco J:\
- Guardar como: C:\RadioTV\Documentacion\inventario_radioTV_YYYY-MM-DD.txt

## REGLAS

- NO borrar nada del disco J:\ — solo lectura
- NO ejecutar ningún archivo .exe, .bat, .vbs del disco J:\
- NO copiar los archivos marcados como peligrosos (Stremio+, vMix, fifa14)
- Confirmar cada paso antes de ejecutar si involucra escritura en C:\
- Reportar rutas no encontradas en vez de asumir ubicaciones alternativas
- Registrar toda operación ejecutada al final del reporte

## ENTREGA ESPERADA

1. Lista de rutas verificadas (encontradas / no encontradas)
2. Confirmación de estructura C:\RadioTV\ creada
3. Log de archivos copiados con tamaños
4. Tabla de duplicados encontrados en J:\
5. Inventario final de C:\RadioTV\

---
*Prompt generado: 2026-05-16 · Sesión auditoría disco E*
*Contexto: reporte_auditoria_disco_E_2026-05-16.md*
*Guía de instalación: guia_instalacion_hardata-dinesat-hdx_v1.0.docx*
