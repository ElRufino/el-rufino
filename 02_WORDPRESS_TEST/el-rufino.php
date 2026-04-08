<?php
/*
Plugin Name: El Rufino - Control Panel v4.3
Description: Dashboard inmersivo (Fullscreen) y Motor de Base de Datos para Promesas.
Version: 4.3.0
Author: El Rufino
*/

if (!defined('ABSPATH')) exit;

/**
 * 1. MOTOR DE BASE DE DATOS - Creación de tabla de Promesas
 */
register_activation_hook(__FILE__, function() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'er_promesas';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        fecha datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        funcionario varchar(100) NOT NULL,
        promesa text NOT NULL,
        estado varchar(20) DEFAULT 'pendiente' NOT NULL,
        evidencia_url varchar(255) DEFAULT '',
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
});

/**
 * 2. OCULTAR INTERFAZ NATIVA DE WORDPRESS (FORZADO)
 */
add_action('admin_head', function() {
    // Solo aplicamos esto si estamos en la página del panel de El Rufino
    if (isset($_GET['page']) && $_GET['page'] === 'el-rufino-panel') {
        echo '
        <style>
            #adminmenuback, #adminmenuwrap, #wpadminbar, #wpfooter { display: none !important; }
            #wpcontent, #wpbody { margin-left: 0 !important; padding: 0 !important; background: #f5f0e8 !important; }
            html.wp-toolbar { padding-top: 0 !important; }
            #wpbody-content { padding-bottom: 0 !important; }
            .update-nag, .notice, #screen-meta-links { display: none !important; }
        </style>';
    }
});

/**
 * 3. MENÚ Y RENDERIZADO
 */
add_action('admin_menu', function(){
    add_menu_page('El Rufino', 'El Rufino v4', 'manage_options', 'el-rufino-panel', 'render_er_panel', 'dashicons-database-view', 2);
});

function render_er_panel() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'er_promesas';
    $conteo_promesas = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");

    ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Source+Serif+4:wght@300;400;600&family=JetBrains+Mono:wght@400;700&display=swap');
        
        :root { 
            --rojo: #c0271b; 
            --negro: #1a1a1a; 
            --crema: #f5f0e8; 
            --blanco: #ffffff; 
            --borde: #ddd8ce;
        }

        .er-wrap { 
            height: 100vh; width: 100vw; display: flex; 
            background: var(--crema); font-family: 'Source Serif 4', serif;
            overflow: hidden; color: var(--negro);
        }

        .er-side { 
            width: 260px; background: var(--negro); color: #d1d1d1; 
            padding: 40px 25px; display: flex; flex-direction: column; gap: 10px;
        }

        .er-logo { font-family: 'Playfair Display', serif; color: var(--crema); font-size: 24px; font-weight: 900; margin-bottom: 30px; }
        .er-logo span { color: var(--rojo); }

        .er-nav-label { font-family: 'JetBrains Mono'; font-size: 10px; text-transform: uppercase; color: #555; margin: 20px 0 5px 5px; }
        
        .er-nav-item { font-family: 'JetBrains Mono', monospace; font-size: 12px; padding: 10px 15px; border-radius: 6px; cursor: pointer; color: #d1d1d1; transition: 0.2s; text-decoration: none; display: block; }
        .er-nav-item:hover { background: #2a2a2a; color: #fff; }
        .er-nav-item.active { background: var(--rojo); color: #fff; font-weight: 700; }

        .er-exit { margin-top: auto; padding: 12px; border: 1px solid #444; color: #888; text-align: center; text-decoration: none; font-size: 10px; font-family: 'JetBrains Mono'; border-radius: 6px; }
        .er-exit:hover { background: var(--rojo); color: white; }

        .er-main { flex: 1; padding: 60px; overflow-y: auto; }
        .er-header { max-width: 1100px; margin: 0 auto 50px; border-bottom: 2px solid var(--rojo); padding-bottom: 20px; display: flex; justify-content: space-between; align-items: flex-end; }
        .er-header h1 { font-family: 'Playfair Display', serif; font-size: 48px; margin: 0; }

        .er-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 25px; max-width: 1100px; margin: 0 auto; }
        .er-card { background: var(--blanco); border: 1px solid var(--borde); border-radius: 12px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); }
        .er-card h3 { font-family: 'Playfair Display', serif; color: var(--rojo); margin: 0 0 15px 0; font-size: 22px; }
        
        .er-badge { display: inline-block; background: #eef2f3; color: #555; font-family: 'JetBrains Mono'; font-size: 10px; padding: 4px 8px; border-radius: 4px; margin-bottom: 15px; }
        .er-stat { font-size: 40px; font-weight: 700; font-family: 'JetBrains Mono'; color: var(--negro); }
    </style>

    <div class="er-wrap">
        <div class="er-side">
            <div class="er-logo"><span>EL</span> RUFINO</div>
            <div class="er-nav-label">Base</div>
            <a href="#" class="er-nav-item active">Dashboard</a>
            <div class="er-nav-label">Editorial</div>
            <a href="#" class="er-nav-item">Promesas</a>
            <a href="<?php echo admin_url(); ?>" class="er-exit">← SALIR A WORDPRESS</a>
        </div>
        
        <div class="er-main">
            <div class="er-header">
                <div>
                    <h1>Panel El Rufino</h1>
                    <p style="font-family:'JetBrains Mono'; font-size:12px; color: #666; margin-top:10px;">SISTEMA OPERATIVO v4.3 | STATUS: BASE DE DATOS ACTIVA</p>
                </div>
            </div>

            <div class="er-grid">
                <div class="er-card">
                    <span class="er-badge">MÓDULO 10</span>
                    <h3>Promesas Registradas</h3>
                    <div class="er-stat"><?php echo $conteo_promesas; ?></div>
                    <p style="font-size: 12px; color: #888; margin-top: 10px;">Compromisos detectados en Rufino y región.</p>
                </div>

                <div class="er-card">
                    <span class="er-badge">CONFIGURACIÓN</span>
                    <h3>Entorno de Test</h3>
                    <p style="font-family: 'JetBrains Mono'; font-size: 13px;">
                        URL: prueba.infoconectados.com<br>
                        MODO: Fullscreen Canvas
                    </p>
                </div>
            </div>
        </div>
    </div>
    <?php
}