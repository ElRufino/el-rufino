<?php
/*
Plugin Name: El Rufino - Control Panel v4.2
Description: Panel de gestión editorial (Fase 2). Modo Fullscreen y corrección de contraste de menú.
Version: 4.2.0
Author: El Rufino
*/

// Evitar acceso directo
if (!defined('ABSPATH')) exit;

add_action('admin_menu', function(){
    add_menu_page(
        'El Rufino', 
        'El Rufino v4', 
        'manage_options', 
        'el-rufino-panel', 
        'render_er_panel', 
        'dashicons-database-view', 
        2
    );
});

function render_er_panel() {
    ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Source+Serif+4:wght@300;400;600&family=JetBrains+Mono:wght@400;700&display=swap');
        
        :root { 
            --rojo: #c0271b; 
            --negro: #1a1a1a; 
            --crema: #f5f0e8; 
            --blanco: #ffffff; 
            --borde: #ddd8ce;
            --texto-sidebar: #d1d1d1;
        }

        /* RESET PARA MODO PANTALLA COMPLETA (OCULTAR WP) */
        #adminmenuback, #adminmenuwrap, #wpadminbar, #wpfooter { display: none !important; }
        #wpcontent, #wpbody { margin-left: 0 !important; padding: 0 !important; }
        .update-nag, .notice, #screen-meta-links { display: none !important; }

        .er-wrap { 
            height: 100vh; 
            width: 100vw; 
            display: flex; 
            background: var(--crema); 
            font-family: 'Source Serif 4', serif;
            overflow: hidden;
            color: var(--negro);
        }

        /* SIDEBAR DE ALTO CONTRASTE */
        .er-side { 
            width: 260px; 
            background: var(--negro); 
            color: var(--texto-sidebar); 
            padding: 40px 25px; 
            display: flex; 
            flex-direction: column; 
            gap: 10px;
            border-right: 1px solid #333;
        }

        .er-logo { 
            font-family: 'Playfair Display', serif; 
            color: var(--crema); 
            font-size: 24px; 
            font-weight: 900; 
            margin-bottom: 30px; 
            letter-spacing: -1px;
        }
        .er-logo span { color: var(--rojo); }

        .er-nav-label { 
            font-family: 'JetBrains Mono'; 
            font-size: 10px; 
            text-transform: uppercase; 
            color: #555; 
            margin: 20px 0 5px 5px;
            letter-spacing: 1px;
        }
        
        .er-nav-item { 
            font-family: 'JetBrains Mono', monospace; 
            font-size: 12px; 
            padding: 10px 15px; 
            border-radius: 6px; 
            cursor: pointer; 
            color: var(--texto-sidebar);
            transition: all 0.2s ease;
        }
        .er-nav-item:hover { background: #2a2a2a; color: #fff; }
        .er-nav-item.active { background: var(--rojo); color: #fff; font-weight: 700; }

        .er-exit { 
            margin-top: auto; 
            padding: 12px; 
            border: 1px solid #444; 
            color: #888; 
            text-align: center; 
            text-decoration: none; 
            font-size: 10px; 
            font-family: 'JetBrains Mono';
            border-radius: 6px;
            transition: all 0.3s;
        }
        .er-exit:hover { background: var(--rojo); color: white; border-color: var(--rojo); }

        /* AREA PRINCIPAL */
        .er-main { 
            flex: 1; 
            padding: 60px; 
            overflow-y: auto; 
            background: var(--crema); 
        }

        .er-header { 
            max-width: 1100px; 
            margin: 0 auto 50px; 
            border-bottom: 2px solid var(--rojo); 
            padding-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .er-header h1 { 
            font-family: 'Playfair Display', serif; 
            font-size: 48px; 
            margin: 0; 
            color: var(--negro); 
            line-height: 1;
        }

        /* GRILLA DE MODULOS */
        .er-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
            gap: 25px; 
            max-width: 1100px; 
            margin: 0 auto; 
        }

        .er-card { 
            background: var(--blanco); 
            border: 1px solid var(--borde); 
            border-radius: 12px; 
            padding: 30px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.03); 
        }

        .er-card h3 { 
            font-family: 'Playfair Display', serif; 
            color: var(--rojo); 
            margin: 0 0 15px 0; 
            font-size: 22px; 
        }

        .er-badge {
            display: inline-block;
            background: #eef2f3;
            color: #555;
            font-family: 'JetBrains Mono';
            font-size: 10px;
            padding: 4px 8px;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .er-ctx-box { 
            background: #1a1a1a; 
            color: #a9dc76; 
            padding: 20px; 
            border-radius: 8px; 
            font-family: 'JetBrains Mono', monospace; 
            font-size: 12px; 
            border-left: 4px solid var(--rojo);
            line-height: 1.6;
        }
    </style>

    <div class="er-wrap">
        <div class="er-side">
            <div class="er-logo"><span>EL</span> RUFINO</div>
            
            <div class="er-nav-label">Base</div>
            <div class="er-nav-item active">Dashboard</div>
            <div class="er-nav-item">Infraestructura</div>
            
            <div class="er-nav-label">Editorial</div>
            <div class="er-nav-item">Promesas (M10)</div>
            <div class="er-nav-item">Suscripciones</div>
            
            <div class="er-nav-label">Inteligencia</div>
            <div class="er-nav-item">Prompt Maestro</div>

            <a href="<?php echo admin_url(); ?>" class="er-exit">← SALIR A WORDPRESS</a>
        </div>
        
        <div class="er-main">
            <div class="er-header">
                <div>
                    <h1>Panel de Control</h1>
                    <p style="font-family:'JetBrains Mono'; font-size:12px; color: #666; margin-top:10px;">PROYECTO EL RUFINO | v4.2.2026</p>
                </div>
                <div style="text-align: right;">
                    <span class="er-badge" style="background: #c0271b; color: #fff;">LIVE: PRUEBA.INFOCONECTADOS.COM</span>
                </div>
            </div>

            <div class="er-grid">
                <div class="er-card">
                    <span class="er-badge">MÓDULO 10</span>
                    <h3>Registro de Promesas</h3>
                    <p>Seguimiento de compromisos gubernamentales y sociales de la región.</p>
                    <div style="margin-top: 20px; font-family: 'JetBrains Mono'; font-size: 12px; color: var(--rojo);">
                        > Estado: Pendiente de Lógica
                    </div>
                </div>

                <div class="er-card">
                    <span class="er-badge">MAQUINARIA IA</span>
                    <h3>Contexto de Transferencia</h3>
                    <div class="er-ctx-box">
                        PROYECTO = EL RUFINO<br>
                        ESTADO = AUDITORÍA OK<br>
                        UI_MODE = FULLSCREEN_OS
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}