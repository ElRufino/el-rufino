<?php
/*
Plugin Name: El Rufino - Plugin v4
Description: Panel de gestión editorial con Registro de Promesas, Contexto IA y Checklist interactivo.
Version: 4.0
Author: El Rufino
*/

function el_rufino_admin_menu() {
    add_menu_page(
        'El Rufino v4',          // Título de la página
        'El Rufino v4',          // Título del menú
        'manage_options',        // Capacidad requerida
        'el-rufino-panel',       // Slug
        'el_rufino_render_page', // Función que muestra el contenido
        'dashicons-media-document', // Icono
        2                        // Posición
    );
}
add_action('admin_menu', 'el_rufino_admin_menu');

function el_rufino_render_page() {
    // Aquí incluimos el archivo HTML que recibiste
    include(plugin_dir_path(__FILE__) . 'el-rufino_plugin_v4.html');
}