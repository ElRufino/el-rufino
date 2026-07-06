<?php
/**
 * Script de Auditoría Técnica - El Rufino v4.1
 * Ejecutar en: https://prueba.infoconectados.com/wp-content/plugins/el-rufino/auditoria-rufino.php
 */

header('Content-Type: application/json');

$root_dir = __DIR__;
$results = [
    "timestamp" => date("Y-m-d H:i:s"),
    "entorno" => $_SERVER['HTTP_HOST'],
    "archivos_presentes" => [],
    "verificacion_php" => [
        "version" => PHP_VERSION,
        "plugin_file" => file_exists($root_dir . '/el-rufino.php') ? "EXISTE" : "FALTA",
        "html_file" => file_exists($root_dir . '/el-rufino_plugin_v4.html') ? "EXISTE" : "FALTA"
    ],
    "analisis_interno_plugin" => ""
];

// Leer los primeros 500 caracteres del plugin para verificar cabeceras
if ($results["verificacion_php"]["plugin_file"] === "EXISTE") {
    $content = file_get_contents($root_dir . '/el-rufino.php', false, null, 0, 500);
    $results["analisis_interno_plugin"] = (strpos($content, 'Plugin Name: El Rufino') !== false) ? "CABECERA OK" : "CABECERA INVALIDA O CORRUPTA";
}

// Listar estructura de la carpeta
if ($handle = opendir($root_dir)) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
            $results["archivos_presentes"][] = $entry;
        }
    }
    closedir($handle);
}

echo json_encode($results, JSON_PRETTY_PRINT);