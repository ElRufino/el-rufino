// ==============================================================================
// ARCHIVO: reorganizar_drive_el_rufino.gs
// PROYECTO: EL RUFINO · Reorganización Google Drive
// FECHA: 2026-04-18
// PROPÓSITO: Mover y organizar archivos de Drive según auditoría
// CÓMO USAR:
//   1. Abrí https://script.google.com
//   2. Nuevo proyecto → pegá este código completo
//   3. Ejecutá primero: ejecutarAuditoria() — solo lee, no mueve nada
//   4. Revisá el log (Ver → Registros)
//   5. Si todo se ve bien, ejecutá: ejecutarReorganizacion()
// ==============================================================================

// IDs de carpetas destino (obtenidos de la auditoría)
const IDS = {
  // Carpetas existentes
  EL_RUFINO_08PROYECTOS:  '1_-h63ftP2EiiaYAIaPH__q8zZ0zihgJ1', // "El Rufino" en 08_Proyectos
  EL_RUFINO_VERIFICAR:    '1iJ7FJyuchYCmTSIOGkYb29ZN2_ajAwhQ', // "EL_RUFINO" en Verificar
  VERIFICAR:              '1mwwQo0woU9iRqdySWWqQeMsfcc5PlmD-', // carpeta Verificar (raíz)
  ARCHIVO_DENTRO_RUFINO:  '1GnA3aM2zHkpuGas_h9Ln5P3bMfMW4VXr', // _ARCHIVO dentro EL_RUFINO
  FILES_DENTRO_ARCHIVO:   '1I7TQmEj3P8suts6iwuhtRRHCFJpt-3iN', // files dentro _ARCHIVO

  // Archivos a mover / archivar
  PANEL_IA_HTML:          '1zqpPu04DtIw0fiTuLTCWIycUeDfqW4mh',
  INFORME_V4:             '1me51XRTNssBavHO8AuOUzqGcutPfKyQC',
  INFORME_V3:             '15-m0hNbkXvCBFsy7ShP7G_dzhT67fxcY',
  INFORME_V2:             '1RgC47eD2tjr3bYYeUMDSIdDj8BLTAf-x',
  INFORME_V1:             '1D22VGENHi_W38JnR78x9DGS1r6-LDxRx',
  PROXIMAMENTE_HTML:      '1pgs9BmAmULTpfCQbllM8_SJe4CcsYL3t',
  DASHBOARD_HTML:         '1kJOnAyYNKoUNcQkObgMzwr9yoGiGzC08',
  INDEX_HTML:             '1K71Is7khuQz43K0lniLpZ9BiEoH8i0s9',
  SER_PARTE_PDF:          '1tOsvlXCjaVrrCgIk3Aaaq1DLQlIc-Ct6',
  AUDITORIA_MD:           '1qs-zRgHBv_iyNlw7gGW2iy6pcGpi-fA2',
  CHANGELOG_MD:           '1bIO0O1-2zpdQfec-_GeoeefgtNHEEIIT',
  ANALIZADOR_HTML:        '1npQDsmAYFFhcINnG3mSUwDJr8XdXbhn6',
  LIMPIEZA_18H:           '1c510OILWj2Jx39PBP7FRj3TfpsosP3mt',
  LIMPIEZA_17H:           '1zZTbAnuvTXjQ7THBhEcv7DKOr-3K5oht',
  LIMPIEZA_06H:           '1XxcErYXeJ0XcWgcd_mGu6Z1APiaOXVPH',
  FIX_SCRIPT:             '1aKyL58I37Z-RApGP6hgxN9pnMQBWWseE',
  PANEL_PHP:              '1blMVzwomQhkzfiwB7rNEPyx-GI4nlTpA',

  // Carpetas internas de git (no deberían estar en Drive)
  GIT_REFS:               '17Ab5KycLPVvcUZcZ1ekKnxtZM7uyyBXK',
  GIT_REMOTES_1:          '1D1SPBYsJI2hkxs2Y_xU6EA9z6I4LRkVD',
  GIT_REMOTES_2:          '1RuzCXjSmN74jaxc2ALovrpCGxHDyCu2J',
  GIT_ORIGIN_1:           '1MaFh1o1QrNuxpajLtouURKZXeT1I2gxP',
  GIT_ORIGIN_2:           '1BCMiMAsZr-hSws2xrcFm4Pq6uzR-HKqB',
  GIT_HEADS_1:            '16WhdW9pwtcZag5IO_feO_7TBIOMFwsKF',
  GIT_HEADS_2:            '15mubHimnkKQiuFCKQN_PguBDVSCP8yfT',
  GIT_TAGS:               '1r1GDv_R0vKKd_UE6N5vnF8tzqvRYxMYz',
};

// ==============================================================================
// PASO 1 — AUDITORÍA (solo lectura, no mueve nada)
// Ejecutá esto primero para verificar que los IDs son correctos
// ==============================================================================
function ejecutarAuditoria() {
  Logger.log('=== AUDITORÍA DRIVE · EL RUFINO ===');
  Logger.log('');

  const checks = [
    { label: 'El Rufino (08_Proyectos)',    id: IDS.EL_RUFINO_08PROYECTOS },
    { label: 'EL_RUFINO (Verificar)',       id: IDS.EL_RUFINO_VERIFICAR },
    { label: 'Carpeta Verificar',           id: IDS.VERIFICAR },
    { label: 'informe-memoria v4 (keep)',   id: IDS.INFORME_V4 },
    { label: 'informe-memoria v3 (arch)',   id: IDS.INFORME_V3 },
    { label: 'limpieza-descargas 18h',      id: IDS.LIMPIEZA_18H },
    { label: 'limpieza-descargas 17h (dup)',id: IDS.LIMPIEZA_17H },
    { label: 'el-rufino-panel.php',         id: IDS.PANEL_PHP },
    { label: 'refs git (borrar)',           id: IDS.GIT_REFS },
  ];

  checks.forEach(c => {
    try {
      const f = DriveApp.getFileById(c.id);
      Logger.log('OK  · ' + c.label + ' → ' + f.getName());
    } catch(e) {
      try {
        const folder = DriveApp.getFolderById(c.id);
        Logger.log('OK  · ' + c.label + ' → [carpeta] ' + folder.getName());
      } catch(e2) {
        Logger.log('ERR · ' + c.label + ' → ID no encontrado: ' + c.id);
      }
    }
  });

  Logger.log('');
  Logger.log('Si todos dicen OK, ejecutá ejecutarReorganizacion()');
}

// ==============================================================================
// PASO 2 — REORGANIZACIÓN
// Ejecutá esto después de verificar la auditoría
// ==============================================================================
function ejecutarReorganizacion() {
  Logger.log('=== REORGANIZACIÓN DRIVE · ' + new Date().toISOString() + ' ===');
  Logger.log('');

  // Crear carpeta _ARCHIVO_DRIVE si no existe (para papelera controlada)
  const archivoFolder = obtenerOCrearCarpeta('_ARCHIVO_DRIVE_2026-04-18', IDS.EL_RUFINO_08PROYECTOS);
  const basuraFolder  = obtenerOCrearCarpeta('_BASURA_GIT_2026-04-18',    IDS.EL_RUFINO_08PROYECTOS);
  Logger.log('Carpetas destino listas.');
  Logger.log('');

  // --- ARCHIVAR versiones viejas de informes ---
  moverArchivo(IDS.INFORME_V3, archivoFolder, 'informe-memoria v3');
  moverArchivo(IDS.INFORME_V2, archivoFolder, 'informe-memoria v2');
  moverArchivo(IDS.INFORME_V1, archivoFolder, 'informe-memoria v1');

  // --- ARCHIVAR auditoría vieja ---
  moverArchivo(IDS.AUDITORIA_MD, archivoFolder, 'AUDITORIA_2026-04-08.md');

  // --- ARCHIVAR CHANGELOG viejo (versión Drive, no la del repo) ---
  moverArchivo(IDS.CHANGELOG_MD, archivoFolder, 'CHANGELOG.md (versión Drive vieja)');

  // --- ARCHIVAR scripts de limpieza duplicados (solo conservar el más reciente) ---
  moverArchivo(IDS.LIMPIEZA_17H, archivoFolder, 'limpieza-descargas 17:57 (duplicado)');
  moverArchivo(IDS.LIMPIEZA_06H, archivoFolder, 'limpieza-descargas 06:18 (viejo)');

  // --- MOVER a basura: carpetas internas de git ---
  moverCarpeta(IDS.GIT_REFS,      basuraFolder, 'refs (git interno)');
  moverCarpeta(IDS.GIT_REMOTES_1, basuraFolder, 'remotes (git interno)');
  moverCarpeta(IDS.GIT_REMOTES_2, basuraFolder, 'remotes 2 (git interno)');
  moverCarpeta(IDS.GIT_ORIGIN_1,  basuraFolder, 'origin 1 (git interno)');
  moverCarpeta(IDS.GIT_ORIGIN_2,  basuraFolder, 'origin 2 (git interno)');
  moverCarpeta(IDS.GIT_HEADS_1,   basuraFolder, 'heads 1 (git interno)');
  moverCarpeta(IDS.GIT_HEADS_2,   basuraFolder, 'heads 2 (git interno)');
  moverCarpeta(IDS.GIT_TAGS,      basuraFolder, 'tags (git interno)');

  Logger.log('');
  Logger.log('=== FIN · Revisá _ARCHIVO_DRIVE y _BASURA_GIT ===');
  Logger.log('Si todo está bien, podés mover esas carpetas a la papelera de Drive manualmente.');
  Logger.log('');
  Logger.log('PENDIENTE MANUAL:');
  Logger.log('- Decidir qué hacer con carpeta "Verificar" (contiene archivos mezclados)');
  Logger.log('- Decidir qué hacer con "El Rufino" vs "EL_RUFINO" (consolidar en una)');
  Logger.log('- Revisar el-rufino-panel.php: ¿es la versión vigente del plugin?');
  Logger.log('- Revisar ElRufino_Ser_Parte.pdf: ¿dónde corresponde?');
}

// ==============================================================================
// HELPERS
// ==============================================================================
function moverArchivo(fileId, destFolder, label) {
  try {
    const file = DriveApp.getFileById(fileId);
    const parents = file.getParents();
    while (parents.hasNext()) {
      file.removeFromFolder(parents.next());
    }
    file.addToFolder(destFolder);
    Logger.log('MOVIDO · ' + label + ' → ' + destFolder.getName());
  } catch(e) {
    Logger.log('ERROR  · ' + label + ': ' + e.message);
  }
}

function moverCarpeta(folderId, destFolder, label) {
  try {
    const folder = DriveApp.getFolderById(folderId);
    const parents = folder.getParents();
    while (parents.hasNext()) {
      folder.removeFromFolder(parents.next());
    }
    folder.addToFolder(destFolder);
    Logger.log('MOVIDO · [carpeta] ' + label + ' → ' + destFolder.getName());
  } catch(e) {
    Logger.log('ERROR  · [carpeta] ' + label + ': ' + e.message);
  }
}

function obtenerOCrearCarpeta(nombre, parentFolder) {
  const parent = DriveApp.getFolderById(parentFolder);
  const existing = parent.getFoldersByName(nombre);
  if (existing.hasNext()) {
    Logger.log('Carpeta existente: ' + nombre);
    return existing.next();
  }
  const nueva = parent.createFolder(nombre);
  Logger.log('Carpeta creada: ' + nombre);
  return nueva;
}
