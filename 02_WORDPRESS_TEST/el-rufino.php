<?php
/*
Plugin Name: El Rufino - Control Panel v4.1
Description: Panel de gestión editorial (Fase 2). UI de alto contraste (Variante B) auditada.
Version: 4.1.1
Author: El Rufino
*/

add_action('admin_menu', function(){
    add_menu_page('El Rufino', 'El Rufino v4', 'manage_options', 'el-rufino-panel', 'render_er_panel', 'dashicons-database-view', 2);
});

function render_er_panel() {
    ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Source+Serif+4:wght@300;400;600&family=JetBrains+Mono:wght@400;700&display=swap');
        :root { --rojo: #c0271b; --negro: #1a1a1a; --crema: #f5f0e8; --blanco: #ffffff; --borde: #ddd8ce; }
        #wpbody-content { padding-bottom: 0 !important; }
        .er-wrap { margin: 20px 20px 0 0; font-family: 'Source Serif 4', serif; background: var(--crema); border: 1px solid var(--borde); border-radius: 12px; display: flex; min-height: 85vh; overflow: hidden; color: var(--negro); }
        .er-side { width: 240px; background: var(--negro); color: #999; padding: 30px 20px; display: flex; flex-direction: column; gap: 20px; }
        .er-logo { font-family: 'Playfair Display', serif; color: var(--crema); font-size: 22px; font-weight: 900; border-bottom: 1px solid #333; padding-bottom: 15px; }
        .er-logo span { color: var(--rojo); }
        .er-nav-item { font-family: 'JetBrains Mono', monospace; font-size: 12px; padding: 10px; border-radius: 6px; cursor: pointer; }
        .er-nav-item.active { background: var(--rojo); color: #fff; }
        .er-main { flex: 1; padding: 40px; overflow-y: auto; }
        .er-header { margin-bottom: 40px; border-bottom: 2px solid var(--rojo); padding-bottom: 10px; display: flex; justify-content: space-between; align-items: flex-end; }
        .er-header h1 { font-family: 'Playfair Display', serif; font-size: 38px; margin: 0; color: var(--negro); }
        .er-card { background: var(--blanco); border: 1px solid var(--borde); border-radius: 10px; padding: 25px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .er-card h3 { font-family: 'Playfair Display', serif; color: var(--rojo); margin: 0 0 15px 0; }
        .er-ctx-box { background: #1a1a1a; color: #a9dc76; padding: 15px; border-radius: 6px; font-family: 'JetBrains Mono', monospace; font-size: 11px; border-left: 4px solid var(--rojo); }
    </style>

    <div class="er-wrap">
        <div class="er-side">
            <div class="er-logo"><span>EL</span> RUFINO</div>
            <div class="er-nav-item active">Dashboard</div>
            <div class="er-nav-item">Módulo 10: Promesas</div>
            <div style="margin-top: auto; font-size: 10px; font-family: 'JetBrains Mono';">FASE 2: PRODUCTO MÍNIMO<br>v4.1.2026</div>
        </div>
        <div class="er-main">
            <div class="er-header"><h1>Panel de Control</h1><span style="font-family:'JetBrains Mono'; font-size:10px;">Entorno: prueba.infoconectados.com</span></div>
            <div class="er-grid">
                <div class="er-card">
                    <h3>Contexto IA Editable</h3>
                    <div class="er-ctx-box">PROYECTO = EL RUFINO<br>PALETA = VARIANTE B (CREMA/ROJO)<br>AUDITORÍA = OK (08/04/26)</div>
                </div>
            </div>
        </div>
    </div>
    <?php
}