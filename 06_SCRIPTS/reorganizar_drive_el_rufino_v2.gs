// ==============================================================================
// ARCHIVO: reorganizar_drive_el_rufino_v2.gs
// PROYECTO: EL RUFINO · Reorganización Google Drive
// FECHA: 2026-04-18
// VERSIÓN: v2 — corregido moveTo() para archivos y carpetas
// CÓMO USAR:
//   1. En script.google.com, reemplazá el código anterior con este
//   2. Ejecutá ejecutarReorganizacion()
//   3. Revisá el log (Ver → Registros)
// ==============================================================================

const IDS = {
  EL_RUFINO_08PROYECTOS:  '1_-h63ftP2EiiaYAIaPH__q8zZ0zihgJ1',

  INFORME_V3:             '15-m0hNbkXvCBFsy7ShP7G_dzhT67fxcY',
  INFORME_V2:             '1RgC47eD2tjr3bYYeUMDSIdDj8BLTAf-x',
  INFORME_V1:             '1D22VGENHi_W38JnR78x9DGS1r6-LDxRx',
  AUDITORIA_MD:           '1qs-zRgHBv_iyNlw7gGW2iy6pcGpi-fA2',
  CHANGELOG_MD:           '1bIO0O1-2zpdQfec-_GeoeefgtNHEEIIT',
  LIMPIEZA_17H:           '1zZTbAnuvTXjQ7THBhEcv7DKOr-3K5oht',
  LIMPIEZA_06H:           '1XxcErYXeJ0XcWgcd_mGu6Z1APiaOXVPH',

  GIT_REFS:               '17Ab5KycLPVvcUZcZ1ekKnxtZM7uyyBXK',
  GIT_REMOTES_1:          '1D1SPBYsJI2hkxs2Y_xU6EA9z6I4LRkVD',
  GIT_REMOTES_2:          '1RuzCXjSmN74jaxc2ALovrpCGxHDyCu2J',
  GIT_ORIGIN_1:           '1MaFh1o1QrNuxpajLtouURKZXeT1I2gxP',
  GIT_ORIGIN_2:           '1BCMiMAsZr-hSws2xrcFm4Pq6uzR-HKqB',
  GIT_HEADS_1:            '16WhdW9pwtcZag5IO_feO_7TBIOMFwsKF',
  GIT_HEADS_2:            '15mubHimnkKQiuFCKQN_PguBDVSCP8yfT',
  GIT_TAGS:               '1r1GDv_R0vKKd_UE6N5vnF8tzqvRYxMYz',
};

function ejecutarReorganizacion() {
  Logger.log('=== REORGANIZACIÓN DRIVE v2 · ' + new Date().toISOString() + ' ===\n');

  const raiz = DriveApp.getFolderById(IDS.EL_RUFINO_08PROYECTOS);

  const archivoFolder = obtenerOCrearCarpeta('_ARCHIVO_DRIVE_2026-04-18', raiz);
  const basuraFolder  = obtenerOCrearCarpeta('_BASURA_GIT_2026-04-18',    raiz);

  Logger.log('Carpetas destino listas.\n');

  // ARCHIVAR — versiones viejas de informes
  moverArchivo(IDS.INFORME_V3,    archivoFolder, 'informe-memoria v3');
  moverArchivo(IDS.INFORME_V2,    archivoFolder, 'informe-memoria v2');
  moverArchivo(IDS.INFORME_V1,    archivoFolder, 'informe-memoria v1');

  // ARCHIVAR — docs viejos
  moverArchivo(IDS.AUDITORIA_MD,  archivoFolder, 'AUDITORIA_2026-04-08.md');
  moverArchivo(IDS.CHANGELOG_MD,  archivoFolder, 'CHANGELOG.md (Drive viejo)');

  // ARCHIVAR — scripts duplicados de limpieza
  moverArchivo(IDS.LIMPIEZA_17H,  archivoFolder, 'limpieza-descargas 17:57');
  moverArchivo(IDS.LIMPIEZA_06H,  archivoFolder, 'limpieza-descargas 06:18');

  // BASURA — carpetas internas de git
  moverCarpeta(IDS.GIT_REFS,      basuraFolder, 'refs');
  moverCarpeta(IDS.GIT_REMOTES_1, basuraFolder, 'remotes 1');
  moverCarpeta(IDS.GIT_REMOTES_2, basuraFolder, 'remotes 2');
  moverCarpeta(IDS.GIT_ORIGIN_1,  basuraFolder, 'origin 1');
  moverCarpeta(IDS.GIT_ORIGIN_2,  basuraFolder, 'origin 2');
  moverCarpeta(IDS.GIT_HEADS_1,   basuraFolder, 'heads 1');
  moverCarpeta(IDS.GIT_HEADS_2,   basuraFolder, 'heads 2');
  moverCarpeta(IDS.GIT_TAGS,      basuraFolder, 'tags');

  Logger.log('\n=== FIN ===');
  Logger.log('Revisá _ARCHIVO_DRIVE_2026-04-18 y _BASURA_GIT_2026-04-18 en Drive.');
  Logger.log('Cuando confirmes que está todo bien, mandalas a la papelera manualmente.');
  Logger.log('\nPENDIENTE MANUAL:');
  Logger.log('· Consolidar "El Rufino" y "EL_RUFINO" en una sola carpeta');
  Logger.log('· Mover ElRufino_Ser_Parte.pdf a 01_DOCUMENTOS_VIGENTES');
  Logger.log('· Revisar carpeta Verificar y RADIO_AUTO');
  Logger.log('· Confirmar si el-rufino-panel.php es la versión vigente del plugin');
}

// ==============================================================================
// HELPERS — usan moveTo() que es el método correcto en Apps Script
// ==============================================================================
function moverArchivo(fileId, destFolder, label) {
  try {
    const file = DriveApp.getFileById(fileId);
    file.moveTo(destFolder);
    Logger.log('OK · ' + label + ' → ' + destFolder.getName());
  } catch(e) {
    Logger.log('ERROR · ' + label + ': ' + e.message);
  }
}

function moverCarpeta(folderId, destFolder, label) {
  try {
    const folder = DriveApp.getFolderById(folderId);
    folder.moveTo(destFolder);
    Logger.log('OK · [carpeta] ' + label + ' → ' + destFolder.getName());
  } catch(e) {
    Logger.log('ERROR · [carpeta] ' + label + ': ' + e.message);
  }
}

function obtenerOCrearCarpeta(nombre, parentFolder) {
  const existing = parentFolder.getFoldersByName(nombre);
  if (existing.hasNext()) {
    Logger.log('Carpeta existente: ' + nombre);
    return existing.next();
  }
  const nueva = parentFolder.createFolder(nombre);
  Logger.log('Carpeta creada: ' + nombre);
  return nueva;
}
