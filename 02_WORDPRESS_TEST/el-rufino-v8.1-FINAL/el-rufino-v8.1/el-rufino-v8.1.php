<?php
/**
 * Plugin Name: El Rufino — Sistema Operativo
 * Description: v8.1 — Setup Wizard + Capas por rol + Producción + Seguimiento + Solo Anthropic API
 * Version: 8.1.0
 * Author: El Rufino
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */

defined('ABSPATH') || exit;

define('ER_V8_VERSION', '8.1.0');
define('ER_V8_URL',     plugin_dir_url(__FILE__));
define('ER_V8_DIR',     plugin_dir_path(__FILE__));

// ── Activación ──────────────────────────────────────────────
register_activation_hook(__FILE__, function() {
    add_option('er_v8_redirect_wizard', true);
    // Migrar key vieja si existe
    if ($old = get_option('er_claude_key')) {
        update_option('er_anthropic_key', $old);
        delete_option('er_claude_key');
    }
    if ($old2 = get_option('er_or_api_key')) {
        delete_option('er_or_api_key');
    }
    // Migrar promesas de tabla custom si existe
    global $wpdb;
    $tabla = $wpdb->prefix . 'er_promesas';
    if ($wpdb->get_var("SHOW TABLES LIKE '$tabla'") === $tabla) {
        $filas = $wpdb->get_results("SELECT * FROM $tabla", ARRAY_A);
        if ($filas) {
            $promesas = [];
            foreach ($filas as $f) {
                $promesas[] = [
                    'id'     => uniqid(),
                    'texto'  => $f['texto'] ?? '',
                    'fuente' => $f['fuente'] ?? '',
                    'fecha'  => $f['fecha'] ?? date('Y-m-d'),
                    'estado' => $f['estado'] ?? 'pendiente',
                    'pilar'  => $f['pilar'] ?? '',
                ];
            }
            update_option('er_promesas', $promesas);
        }
        $wpdb->query("DROP TABLE IF EXISTS $tabla");
    }
});

// ── Redirect al Wizard al activar ───────────────────────────
add_action('admin_init', function() {
    if (get_option('er_v8_redirect_wizard')) {
        delete_option('er_v8_redirect_wizard');
        if (!isset($_GET['activate-multi'])) {
            wp_redirect(admin_url('admin.php?page=er-setup'));
            exit;
        }
    }
});

// ── Menú ────────────────────────────────────────────────────
add_action('admin_menu', function() {
    add_menu_page(
        'El Rufino',
        'El Rufino',
        'edit_posts',
        'el-rufino',
        'er_v8_render_panel',
        'dashicons-media-document',
        2
    );
    add_submenu_page(
        'el-rufino',
        'Configuración',
        'Configuración',
        'manage_options',
        'er-setup',
        'er_v8_render_wizard'
    );
});

// ── Assets ──────────────────────────────────────────────────
add_action('admin_enqueue_scripts', function($hook) {
    if (strpos($hook, 'el-rufino') === false && strpos($hook, 'er-setup') === false) return;

    wp_enqueue_style(
        'er-v8-css',
        ER_V8_URL . 'assets/css/panel.css',
        [],
        ER_V8_VERSION
    );
    wp_enqueue_script(
        'er-v8-js',
        ER_V8_URL . 'assets/js/panel.js',
        ['jquery'],
        ER_V8_VERSION,
        true
    );

    // Categorías para el Wizard y Producción
    $cats = get_categories(['hide_empty' => false, 'orderby' => 'name']);
    $cats_data = array_map(function($c) {
        return ['id' => $c->term_id, 'name' => $c->name, 'slug' => $c->slug];
    }, $cats);

    // Pilares guardados
    $pilares = get_option('er_pilares', ['P01','P02','P03','P04','P05','P06']);

    wp_localize_script('er-v8-js', 'erData', [
        'ajaxUrl'     => admin_url('admin-ajax.php'),
        'nonce'       => wp_create_nonce('er_nonce'),
        'isAdmin'     => current_user_can('manage_options'),
        'wizardDone'  => get_option('er_v8_wizard') === 'done',
        'version'     => ER_V8_VERSION,
        'siteUrl'     => get_site_url(),
        'fontsUrl'    => ER_V8_URL . 'assets/fonts/',
        'identidad'   => get_option('er_identidad', ['claim' => 'LO QUE PASA Y LO QUE SIGNIFICA', 'color' => '#c0271b']),
        'pilares'     => $pilares,
        'categorias'  => $cats_data,
        'keyMasked'   => er_mask_key(get_option('er_anthropic_key', '')),
        'keyOk'       => !empty(get_option('er_anthropic_key', '')),
        'currentUser' => wp_get_current_user()->display_name,
    ]);
});

// ── Fullscreen CSS override ──────────────────────────────────
add_action('admin_head', function() {
    $s = get_current_screen();
    if (!$s) return;
    if (strpos($s->id, 'el-rufino') === false && strpos($s->id, 'er-setup') === false) return;
    echo '<style>
        #wpadminbar,#adminmenuback,#adminmenuwrap,#wpfooter { display:none!important }
        html.wp-toolbar { padding-top:0!important }
        #wpcontent,#wpbody,#wpbody-content { margin:0!important; padding:0!important }
        #er-root { position:fixed; inset:0; z-index:99999; overflow:auto; background:#f5f0e8 }
    </style>';
});

// ── Render pages ────────────────────────────────────────────
function er_v8_render_wizard() {
    echo '<div id="er-root" data-view="wizard"></div>';
}
function er_v8_render_panel() {
    echo '<div id="er-root" data-view="panel"></div>';
}

// ── Helper: mask API key ─────────────────────────────────────
function er_mask_key($key) {
    if (!$key) return '';
    return substr($key, 0, 14) . '...';
}

// ══════════════════════════════════════════════════════════════
// AJAX HANDLERS
// ══════════════════════════════════════════════════════════════

// ── Wizard: guardar configuración inicial ───────────────────
add_action('wp_ajax_er_save_wizard', function() {
    check_ajax_referer('er_nonce', 'nonce');
    if (!current_user_can('manage_options')) wp_send_json_error(['msg' => 'Sin permisos']);

    update_option('er_identidad', [
        'claim' => sanitize_text_field($_POST['claim'] ?? 'LO QUE PASA Y LO QUE SIGNIFICA'),
        'color' => sanitize_hex_color($_POST['color'] ?? '#c0271b'),
    ]);

    $key = sanitize_text_field($_POST['api_key'] ?? '');
    if ($key && strpos($key, 'sk-ant') === 0) {
        update_option('er_anthropic_key', $key);
    }

    $pilares = array_map('sanitize_text_field', $_POST['pilares'] ?? ['P01','P02','P03','P04','P05','P06']);
    update_option('er_pilares', $pilares);
    update_option('er_v8_wizard', 'done');

    wp_send_json_success(['redirect' => admin_url('admin.php?page=el-rufino')]);
});

// ── Dashboard: guardar key ───────────────────────────────────
add_action('wp_ajax_er_save_key', function() {
    check_ajax_referer('er_nonce', 'nonce');
    if (!current_user_can('manage_options')) wp_send_json_error();
    $key = sanitize_text_field($_POST['key'] ?? '');
    update_option('er_anthropic_key', $key);
    wp_send_json_success(['masked' => er_mask_key($key), 'configured' => !empty($key)]);
});

// ── Dashboard: estado key ────────────────────────────────────
add_action('wp_ajax_er_key_status', function() {
    check_ajax_referer('er_nonce', 'nonce');
    if (!current_user_can('manage_options')) wp_send_json_error();
    $key = get_option('er_anthropic_key', '');
    wp_send_json_success([
        'configured' => !empty($key),
        'masked'     => er_mask_key($key),
    ]);
});

// ── Producción: generar nota con IA ─────────────────────────
add_action('wp_ajax_er_asistente_generar', function() {
    check_ajax_referer('er_nonce', 'nonce');
    if (!current_user_can('edit_posts')) wp_send_json_error(['msg' => 'Sin permisos']);

    $key = get_option('er_anthropic_key', '');
    if (!$key) wp_send_json_error(['msg' => 'API Key no configurada. Configurala en el Dashboard.']);

    $titulo = sanitize_text_field($_POST['titulo'] ?? '');
    $pilar  = sanitize_text_field($_POST['pilar'] ?? '');
    $youtube = esc_url_raw($_POST['youtube'] ?? '');
    $trans  = sanitize_textarea_field($_POST['transcripcion'] ?? '');
    $contexto = sanitize_textarea_field($_POST['contexto'] ?? '');

    if (!$titulo) wp_send_json_error(['msg' => 'El título es obligatorio.']);

    $system = 'Sos el redactor principal de El Rufino, medio digital de Rufino, Santa Fe, Argentina (19.211 hab). '
        . 'Escribís en español rioplatense. Voz directa, humana, verificada. Sin sensacionalismo. '
        . 'REGLA OBLIGATORIA DE DOS CAPAS: toda nota debe tener (1) lo que pasó + (2) lo que significa. '
        . 'Sin segunda capa, la nota no existe. Nunca escribas "En el marco de". '
        . 'El claim del medio es: "Lo que pasa y lo que significa."';

    $prompt = "Escribí una nota periodística para el pilar $pilar.\n\n";
    $prompt .= "TÍTULO PROPUESTO: $titulo\n\n";
    if ($contexto) $prompt .= "CONTEXTO LOCAL: $contexto\n\n";
    if ($youtube)  $prompt .= "VIDEO REFERENCIA: $youtube\n\n";
    if ($trans)    $prompt .= "TRANSCRIPCIÓN/MATERIAL: $trans\n\n";
    $prompt .= "Entregá en este formato exacto:\n";
    $prompt .= "TÍTULO: [título definitivo]\n";
    $prompt .= "BAJADA: [2-3 oraciones que resumen el hecho]\n\n";
    $prompt .= "CUERPO:\n[3 párrafos del cuerpo de la nota]\n\n";
    $prompt .= "LO QUE SIGNIFICA:\n[párrafo de contexto y significado — la segunda capa]\n\n";
    $prompt .= "TAGS SUGERIDOS: [5-7 tags separados por comas]\n";
    $prompt .= "Máximo 500 palabras en total.";

    $response = wp_remote_post('https://api.anthropic.com/v1/messages', [
        'timeout' => 90,
        'headers' => [
            'x-api-key'         => $key,
            'anthropic-version' => '2023-06-01',
            'content-type'      => 'application/json',
        ],
        'body' => json_encode([
            'model'      => 'claude-sonnet-4-6',
            'max_tokens' => 1500,
            'system'     => $system,
            'messages'   => [['role' => 'user', 'content' => $prompt]],
        ]),
    ]);

    if (is_wp_error($response)) {
        wp_send_json_error(['msg' => 'Error de conexión: ' . $response->get_error_message()]);
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);

    if (isset($body['error'])) {
        wp_send_json_error(['msg' => $body['error']['message'] ?? 'Error API Anthropic']);
    }

    $content = $body['content'][0]['text'] ?? '';
    if (!$content) wp_send_json_error(['msg' => 'Respuesta vacía de la API']);

    wp_send_json_success(['content' => $content]);
});

// ── Producción: guardar nota como borrador ──────────────────
add_action('wp_ajax_er_guardar_borrador', function() {
    check_ajax_referer('er_nonce', 'nonce');
    if (!current_user_can('edit_posts')) wp_send_json_error(['msg' => 'Sin permisos']);

    $titulo   = sanitize_text_field($_POST['titulo'] ?? '');
    $cuerpo   = wp_kses_post($_POST['cuerpo'] ?? '');
    $cat_id   = intval($_POST['categoria'] ?? 0);
    $tags     = sanitize_text_field($_POST['tags'] ?? '');

    if (!$titulo) wp_send_json_error(['msg' => 'Título requerido']);

    $post_id = wp_insert_post([
        'post_title'   => $titulo,
        'post_content' => $cuerpo,
        'post_status'  => 'draft',
        'post_author'  => get_current_user_id(),
        'post_category'=> $cat_id ? [$cat_id] : [],
        'tags_input'   => $tags,
    ]);

    if (is_wp_error($post_id)) {
        wp_send_json_error(['msg' => $post_id->get_error_message()]);
    }

    wp_send_json_success([
        'post_id'  => $post_id,
        'edit_url' => get_edit_post_link($post_id, 'raw'),
        'msg'      => 'Borrador guardado',
    ]);
});

// ── YouTube: info del video ──────────────────────────────────
add_action('wp_ajax_er_yt_info', function() {
    check_ajax_referer('er_nonce', 'nonce');
    if (!current_user_can('edit_posts')) wp_send_json_error();

    $url = sanitize_text_field($_POST['url'] ?? '');
    preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|v\/))([a-zA-Z0-9_-]{11})/', $url, $m);
    $id = $m[1] ?? '';
    if (!$id) wp_send_json_error(['msg' => 'URL de YouTube inválida']);

    // Intentar oEmbed primero (sin API key)
    $oembed_url = 'https://www.youtube.com/oembed?url=' . urlencode('https://www.youtube.com/watch?v=' . $id) . '&format=json';
    $res = wp_remote_get($oembed_url, ['timeout' => 15]);

    if (!is_wp_error($res) && wp_remote_retrieve_response_code($res) === 200) {
        $data = json_decode(wp_remote_retrieve_body($res), true);
        wp_send_json_success([
            'video_id'    => $id,
            'titulo'      => $data['title'] ?? '',
            'canal'       => $data['author_name'] ?? '',
            'thumbnail'   => "https://img.youtube.com/vi/{$id}/mqdefault.jpg",
            'descripcion' => '',
            'embed_url'   => "https://www.youtube.com/embed/{$id}",
        ]);
    }

    wp_send_json_error(['msg' => 'No se pudo obtener información del video']);
});

// ── YouTube: captions ───────────────────────────────────────
add_action('wp_ajax_er_yt_captions', function() {
    check_ajax_referer('er_nonce', 'nonce');
    if (!current_user_can('edit_posts')) wp_send_json_error();

    $id = sanitize_text_field($_POST['video_id'] ?? '');
    if (!preg_match('/^[a-zA-Z0-9_-]{11}$/', $id)) {
        wp_send_json_error(['msg' => 'ID de video inválido']);
    }

    foreach (['es', 'es-419', 'es-AR', 'en'] as $lang) {
        $url = 'https://www.youtube.com/api/timedtext?v=' . urlencode($id) . '&lang=' . $lang . '&fmt=json3';
        $res = wp_remote_get($url, [
            'timeout' => 15,
            'headers' => ['user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'],
        ]);
        if (is_wp_error($res)) continue;

        $data = json_decode(wp_remote_retrieve_body($res), true);
        if (empty($data['events'])) continue;

        $txt = '';
        foreach ($data['events'] as $e) {
            if (empty($e['segs'])) continue;
            foreach ($e['segs'] as $s) $txt .= ($s['utf8'] ?? '');
        }
        $txt = trim(preg_replace('/\s+/', ' ', str_replace("\n", ' ', $txt)));
        if ($txt) wp_send_json_success(['transcripcion' => $txt, 'idioma' => $lang]);
    }

    wp_send_json_error(['msg' => 'No hay subtítulos disponibles para este video. Pegá el texto manualmente.']);
});

// ── Promesas: guardar ────────────────────────────────────────
add_action('wp_ajax_er_save_promesa', function() {
    check_ajax_referer('er_nonce', 'nonce');
    if (!current_user_can('edit_posts')) wp_send_json_error(['msg' => 'Sin permisos']);

    $promesas = get_option('er_promesas', []);
    $nueva = [
        'id'     => uniqid('prom_'),
        'texto'  => sanitize_text_field($_POST['texto'] ?? ''),
        'fuente' => sanitize_text_field($_POST['fuente'] ?? ''),
        'fecha'  => sanitize_text_field($_POST['fecha'] ?? date('Y-m-d')),
        'estado' => 'pendiente',
        'pilar'  => sanitize_text_field($_POST['pilar'] ?? ''),
        'nota_id'=> intval($_POST['nota_id'] ?? 0),
    ];

    if (!$nueva['texto']) wp_send_json_error(['msg' => 'El texto de la promesa es obligatorio']);

    $promesas[] = $nueva;
    update_option('er_promesas', $promesas);
    wp_send_json_success(['saved' => true, 'id' => $nueva['id']]);
});

// ── Promesas: obtener ────────────────────────────────────────
add_action('wp_ajax_er_get_promesas', function() {
    check_ajax_referer('er_nonce', 'nonce');
    if (!current_user_can('edit_posts')) wp_send_json_error();
    wp_send_json_success(get_option('er_promesas', []));
});

// ── Promesas: actualizar estado ──────────────────────────────
add_action('wp_ajax_er_update_promesa', function() {
    check_ajax_referer('er_nonce', 'nonce');
    if (!current_user_can('edit_posts')) wp_send_json_error();

    $id     = sanitize_text_field($_POST['id'] ?? '');
    $estado = sanitize_text_field($_POST['estado'] ?? '');
    $estados_validos = ['pendiente', 'cumplida', 'incumplida', 'en-proceso'];
    if (!in_array($estado, $estados_validos)) wp_send_json_error(['msg' => 'Estado inválido']);

    $promesas = get_option('er_promesas', []);
    foreach ($promesas as &$p) {
        if ($p['id'] === $id) { $p['estado'] = $estado; break; }
    }
    update_option('er_promesas', $promesas);
    wp_send_json_success(['updated' => true]);
});

// ── Promesas: eliminar ───────────────────────────────────────
add_action('wp_ajax_er_delete_promesa', function() {
    check_ajax_referer('er_nonce', 'nonce');
    if (!current_user_can('edit_posts')) wp_send_json_error();

    $id = sanitize_text_field($_POST['id'] ?? '');
    $promesas = get_option('er_promesas', []);
    $promesas = array_filter($promesas, fn($p) => $p['id'] !== $id);
    update_option('er_promesas', array_values($promesas));
    wp_send_json_success(['deleted' => true]);
});

// ── Borradores recientes ─────────────────────────────────────
add_action('wp_ajax_er_get_borradores', function() {
    check_ajax_referer('er_nonce', 'nonce');
    if (!current_user_can('edit_posts')) wp_send_json_error();

    $posts = get_posts([
        'post_status'    => 'draft',
        'posts_per_page' => 10,
        'author'         => current_user_can('manage_options') ? 0 : get_current_user_id(),
        'orderby'        => 'modified',
        'order'          => 'DESC',
    ]);

    $data = array_map(function($p) {
        $cats = get_the_category($p->ID);
        return [
            'id'       => $p->ID,
            'titulo'   => $p->post_title ?: '(sin título)',
            'fecha'    => get_the_modified_date('d/m/Y H:i', $p->ID),
            'edit_url' => get_edit_post_link($p->ID, 'raw'),
            'cat'      => $cats ? $cats[0]->name : '',
        ];
    }, $posts);

    wp_send_json_success($data);
});

// ── Buscar contexto en posts publicados ─────────────────────
add_action('wp_ajax_er_buscar_contexto', function() {
    check_ajax_referer('er_nonce', 'nonce');
    if (!current_user_can('edit_posts')) wp_send_json_error();

    $q = sanitize_text_field($_POST['query'] ?? '');
    if (!$q) wp_send_json_success([]);

    $posts = get_posts([
        'post_status'    => 'publish',
        'posts_per_page' => 5,
        's'              => $q,
        'orderby'        => 'relevance',
    ]);

    $data = array_map(function($p) {
        return [
            'titulo'  => $p->post_title,
            'excerpt' => wp_trim_words(strip_tags($p->post_content), 20),
            'url'     => get_permalink($p->ID),
            'fecha'   => get_the_date('d/m/Y', $p->ID),
        ];
    }, $posts);

    wp_send_json_success($data);
});
