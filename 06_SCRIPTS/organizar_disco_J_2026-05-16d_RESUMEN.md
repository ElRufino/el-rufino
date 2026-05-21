# Reorganización Disco J:\ — Resumen sesión 2026-05-16d

## Estado al cierre

Disco: J:\ (backup servidor 192.168.120.40, afectado por ransomware STOP/DJVU .ooxa)
Log de operaciones: `J:\_organizar_disco_log_2026-05-16d.txt`
Espacio libre: 186.82 GB / 292.97 GB

---

## Pasos ejecutados esta sesión

### PASO 1 — Consolidar backups
Script: `organizar_disco_J_2026-05-16d_PASO1.ps1`
Resultado: 6/6 carpetas movidas a `J:\BACKUPS\`

| Origen (raíz) | Destino |
|---|---|
| J:\BACK 21-06-2024 | J:\BACKUPS\2024-06-21_PC-INFOCONECTADOS |
| J:\BACKUP - 08-03-2023 | J:\BACKUPS\2023-03-08_PC-CONECTADOS |
| J:\18-04-2023 - BACKUP | J:\BACKUPS\2023-04-18_PC-DANILO |
| J:\BACK TELEFONO 03-08-2023 | J:\BACKUPS\2023-08-03_TELEFONO |
| J:\BACK SSD - WIN11 | J:\BACKUPS\SSD-WIN11 |
| J:\CONECTADOS-INFO | J:\BACKUPS\CONECTADOS-INFO_WEB |

### PASO 2 — Consolidar software
Script: inline
Resultado: 3/3 movidos a `J:\SOFTWARE\`

| Origen | Destino |
|---|---|
| J:\OFFICE (16198.8 MB) | J:\SOFTWARE\Oficina\OFFICE |
| J:\WINDOWS 7 (14894.5 MB) | J:\SOFTWARE\Sistemas\WINDOWS 7 |
| J:\COREL DRAW 2018 ALBERLOBO (657.9 MB) | J:\SOFTWARE\Diseno\COREL DRAW 2018 ALBERLOBO |

### PASO 3 — Consolidar multimedia
Script: inline
Resultado: 4/4 movidos a `J:\MULTIMEDIA\`

| Origen | Destino |
|---|---|
| J:\VIDEOS GOOGLE (2440.1 MB) | J:\MULTIMEDIA\Videos\VIDEOS GOOGLE |
| J:\8M (233.6 MB) | J:\MULTIMEDIA\Audiovisual\8M |
| J:\MATERIAL AUDIOVISUAL (9.4 MB) | J:\MULTIMEDIA\Audiovisual\MATERIAL AUDIOVISUAL |
| J:\Kuschelrock 11-20.rar (3268.9 MB) | J:\MULTIMEDIA\Musica\Kuschelrock 11-20.rar |

### PASO 4 — Documentos personales
Script: `organizar_disco_J_2026-05-16d_PASO4.ps1`
Resultado: 1/1 movido

| Origen | Destino |
|---|---|
| J:\AFIP\ (AFIP - PLAN DE PAGOS.pdf, 0.1 MB) | J:\DOCUMENTOS\Personal\AFIP\ |

### Corrección post-PASO3
Carpeta `J:\Kuschelrock 11-20\` (3608 MB) quedó en raíz sin mover (el script solo movió el .rar).
Movida manualmente vía script inline a `J:\MULTIMEDIA\Musica\Kuschelrock 11-20\`

---

## Estructura final de J:\

```
J:\
├── BACKUPS\
│   ├── 2024-06-21_PC-INFOCONECTADOS\
│   ├── 2023-03-08_PC-CONECTADOS\
│   ├── 2023-04-18_PC-DANILO\
│   ├── 2023-08-03_TELEFONO\
│   ├── SSD-WIN11\
│   └── CONECTADOS-INFO_WEB\
├── SOFTWARE\
│   ├── Oficina\OFFICE\
│   ├── Sistemas\WINDOWS 7\
│   ├── Diseno\COREL DRAW 2018 ALBERLOBO\
│   └── Diseño\  ← carpeta de sesión anterior (_c), contiene: 13BITS-IPS10.rar,
│                   Adb Phtshp CS3 Prtbl.rar, COREL DRAW 2018 ALBERLOBO.rar, CORELDRAW_X7.rar
├── MULTIMEDIA\
│   ├── Videos\VIDEOS GOOGLE\
│   │   └── DANILO\, DANILO.zip, DANILO 1.zip, MAS PATRIA - LA PLATA.zip, Videos-001/002/003.zip
│   ├── Audiovisual\8M\
│   ├── Audiovisual\MATERIAL AUDIOVISUAL\
│   └── Musica\
│       ├── Kuschelrock 11-20.rar  (3268.9 MB)
│       └── Kuschelrock 11-20\     (3608 MB) ← probable duplicado del .rar
├── RADIO_TV\          (13057 MB — de sesión anterior)
├── DOCUMENTOS\
│   └── Personal\
│       ├── AFIP\AFIP - PLAN DE PAGOS.pdf
│       └── ANSES_Constancia_CUIL20190820.pdf
├── GESTION DE MEDIOS COMUNITARIOS\  (334.2 MB — sin tocar)
├── COMPRIMIDOS\       (vacía)
├── _PAPELERA_DISCO\
├── Program Files\     (sistema, sin tocar)
├── WindowsApps\       (sistema, sin tocar)
├── auditoria_disco_E.csv  (13.1 MB — archivo suelto)
├── _organizar_disco_log_2026-05-16.txt
└── _organizar_disco_log_2026-05-16d.txt
```

---

## Pendientes al cierre

| Item | Prioridad | Acción sugerida |
|---|---|---|
| Kuschelrock 11-20\ vs .rar | Media | Verificar si mismo contenido; eliminar duplicado |
| SOFTWARE\Diseño\ vs SOFTWARE\Diseno\ | Baja | Unificar: mover contenido de Diseño\ a Diseno\ |
| COMPRIMIDOS\ vacía | Baja | Eliminar carpeta cuando se confirme innecesaria |
| auditoria_disco_E.csv en raíz | Baja | Mover a J:\DOCUMENTOS\ o mantener como referencia |
| VIDEOS GOOGLE\DANILO*.zip | Media | Verificar si DANILO\ y DANILO.zip tienen mismo contenido |
| Servidor1.650b.zip | Verificado | No estaba en COMPRIMIDOS — puede haber sido movido en sesión anterior |

---

## Tareas WordPress EL RUFINO (diferidas)

- Comparar single.php y home.php con versión Hostinger
- Resolver PARTIDO_JUSTICIALISTA duplicados
- Fase B: prefijos Unicode en nombres de archivo
