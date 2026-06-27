<?php
/**
 * Plugin Name:       El Rufino — Panel IA
 * Description:       Panel operativo v8.6.0. 5 pantallas (Dashboard, Producción, Inteligencia, Seguimiento, Asistente). Importador notas demo, proxy Claude API, checklist actualizable, exportar promesas CSV, categorías editoriales con colores, escritorio WP personalizado, Schema NewsMediaOrganization, Asistente de noticia con YouTube + imagen + transcripción automática vía captions.
 * Version:           8.7.5
 * Author:            El Rufino
 */

defined('ABSPATH') || exit;

define('ER_VERSION',    '8.7.5');
define('ER_PLUGIN_URL', plugin_dir_url(__FILE__));
define('ER_PLUGIN_DIR', plugin_dir_path(__FILE__));

/* ═══════════════════════════════════════════════════════════
   1. CATEGORÍAS EDITORIALES
═══════════════════════════════════════════════════════════ */
register_activation_hook(__FILE__, 'er_crear_categorias');

function er_crear_categorias() {
    $pilares = [
        'Barrio a barrio'         => ['slug' => 'barrio-a-barrio',        'color' => '#b55233'],
        'Rufino en datos'        => ['slug' => 'rufino-en-datos',         'color' => '#2f6484'],
        'El campo habla'          => ['slug' => 'el-campo-habla',         'color' => '#617a45'],
        'Generación Rufino'       => ['slug' => 'generacion-rufino',      'color' => '#c58a2b'],
        'Poder y gestion' => ['slug' => 'poder-y-gestion',   'color' => '#1f2a30'],
    ];
    foreach ($pilares as $nombre => $datos) {
        if (!term_exists($nombre, 'category')) {
            wp_insert_term($nombre, 'category', ['slug' => $datos['slug']]);
        }
    }
}

/* ═══════════════════════════════════════════════════════════
   2. MENÚ ADMIN
═══════════════════════════════════════════════════════════ */
add_action('admin_menu', function () {
    add_menu_page(
        'El Rufino',
        'El Rufino',
        'manage_options',
        'el-rufino-panel',
        'er_render_panel',
        'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect width="20" height="20" rx="4" fill="#c0271b"/><text x="10" y="15" text-anchor="middle" font-size="12" font-weight="bold" fill="white" font-family="Arial">ER</text></svg>'),
        3
    );
});

/* ═══════════════════════════════════════════════════════════
   3. ENQUEUE ASSETS
═══════════════════════════════════════════════════════════ */
add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook !== 'toplevel_page_el-rufino-panel') return;

    wp_enqueue_script('react',     'https://unpkg.com/react@18/umd/react.production.min.js',     [], '18', true);
    wp_enqueue_script('react-dom', 'https://unpkg.com/react-dom@18/umd/react-dom.production.min.js', ['react'], '18', true);
    wp_enqueue_script('er-panel',  ER_PLUGIN_URL . 'assets/panel.js', ['react', 'react-dom'], ER_VERSION, true);

    wp_localize_script('er-panel', 'erData', [
        'ajaxUrl'   => admin_url('admin-ajax.php'),
        'nonce'     => wp_create_nonce('er_nonce'),
        'siteUrl'   => get_site_url(),
        'adminUrl'  => admin_url(),
        'version'   => ER_VERSION,
        'userName'  => wp_get_current_user()->display_name,
    ]);

    wp_add_inline_style('wp-admin', '
        #wpcontent { padding: 0 !important; }
        #wpbody-content { padding-bottom: 0 !important; }
        .er-panel-wrap { margin: 0 !important; }
        #er-root { min-height: 100vh; }
    ');
});

/* ═══════════════════════════════════════════════════════════
   3b. PANTALLA COMPLETA — ocultar UI de WordPress
═══════════════════════════════════════════════════════════ */
add_action('admin_head', function () {
    if (!isset($_GET['page']) || $_GET['page'] !== 'el-rufino-panel') return;
    echo '<style>
        /* Barra superior negra de WP */
        #wpadminbar { display: none !important; }
        html { margin-top: 0 !important; }

        /* Sidebar izquierdo de WP */
        #adminmenuback,
        #adminmenuwrap { display: none !important; }

        /* Área de contenido sin márgenes */
        #wpcontent,
        #wpbody-content { margin-left: 0 !important; padding: 0 !important; }
        #wpbody { padding-top: 0 !important; }
        #wpfooter { display: none !important; }

        /* El panel ocupa todo el viewport */
        #er-root {
            position: fixed !important;
            inset: 0 !important;
            z-index: 9999 !important;
            overflow: auto;
        }
    </style>';
});

/* ═══════════════════════════════════════════════════════════
   4. RENDER PANEL
═══════════════════════════════════════════════════════════ */
function er_render_panel() {
    echo '<div class="er-panel-wrap"><div id="er-root"></div></div>';
}

/* ═══════════════════════════════════════════════════════════
   5. AJAX — ESTADÍSTICAS
═══════════════════════════════════════════════════════════ */
add_action('wp_ajax_er_stats', function () {
    check_ajax_referer('er_nonce', 'nonce');
    $updates = get_site_transient('update_plugins');
    $pending = $updates ? count((array)($updates->response ?? [])) : 0;
    wp_send_json_success([
        'published'  => (int)wp_count_posts()->publish,
        'drafts'     => (int)wp_count_posts()->draft,
        'comments'   => (int)get_comments(['status' => 'approve', 'count' => true]),
        'updates'    => $pending,
    ]);
});

/* ═══════════════════════════════════════════════════════════
   6. AJAX — CHECKLIST
═══════════════════════════════════════════════════════════ */
add_action('wp_ajax_er_get_checklist', function () {
    check_ajax_referer('er_nonce', 'nonce');
    $ver     = '3';
    $default = [
        ['id' =>  1, 'texto' => 'Logo/favicon/OG subidos',                        'ok' => true],
        ['id' =>  2, 'texto' => 'Child theme Newsup instalado y activo',           'ok' => true],
        ['id' =>  3, 'texto' => 'Layout dos columnas (single.php v2.2.0)',         'ok' => true],
        ['id' =>  4, 'texto' => 'Primera nota real publicada',                     'ok' => true],
        ['id' =>  5, 'texto' => 'elrufino.com.ar activo con DNS en Hostinger',    'ok' => true],
        ['id' =>  6, 'texto' => 'SSL/HTTPS activo',                                'ok' => true],
        ['id' =>  7, 'texto' => '6 categorías P01-P06',                            'ok' => false],
        ['id' =>  8, 'texto' => 'Schema NewsMediaOrganization (Rank Math)',        'ok' => false],
        ['id' =>  9, 'texto' => 'Canal WhatsApp (0/500)',                          'ok' => false],
        ['id' => 10, 'texto' => 'elrufino.ar pendiente registro NIC',             'ok' => false],
    ];
    if (get_option('er_checklist_ver') !== $ver) {
        update_option('er_checklist', $default);
        update_option('er_checklist_ver', $ver);
    }
    $saved = get_option('er_checklist', $default);
    wp_send_json_success(array_values((array)$saved));
});

add_action('wp_ajax_er_save_checklist', function () {
    check_ajax_referer('er_nonce', 'nonce');
    $items = json_decode(stripslashes($_POST['items'] ?? '[]'), true);
    update_option('er_checklist', $items);
    wp_send_json_success(['saved' => true]);
});

/* ═══════════════════════════════════════════════════════════
   7. AJAX — PROXY IA (Anthropic)
═══════════════════════════════════════════════════════════ */
add_action('wp_ajax_er_claude', function () {
    check_ajax_referer('er_nonce', 'nonce');

    $messages   = json_decode(stripslashes($_POST['messages'] ?? '[]'), true);
    $system     = sanitize_text_field($_POST['system'] ?? '');
    $max_tokens = (int)($_POST['max_tokens'] ?? 1024);

    $api_key = get_option('er_claude_key', '');
    if (!$api_key) {
        wp_send_json_error(['msg' => 'API key de Claude no configurada. Configurala en Dashboard → Proveedor IA.']);
        return;
    }

    $body = ['model' => 'claude-sonnet-4-6', 'max_tokens' => $max_tokens, 'messages' => $messages];
    if ($system) $body['system'] = $system;

    $response = wp_remote_post('https://api.anthropic.com/v1/messages', [
        'timeout' => 90,
        'headers' => [
            'x-api-key'         => $api_key,
            'anthropic-version' => '2023-06-01',
            'content-type'      => 'application/json',
        ],
        'body' => wp_json_encode($body),
    ]);

    if (is_wp_error($response)) {
        wp_send_json_error(['msg' => 'Error de conexión con Anthropic: ' . $response->get_error_message()]);
        return;
    }
    $data = json_decode(wp_remote_retrieve_body($response), true);
    if (!empty($data['error'])) {
        wp_send_json_error(['msg' => 'Error Claude: ' . ($data['error']['message'] ?? 'desconocido')]);
        return;
    }
    wp_send_json_success(['content' => $data['content'][0]['text'] ?? '']);
});

/* — Guardar key Anthropic — */
add_action('wp_ajax_er_save_key', function () {
    check_ajax_referer('er_nonce', 'nonce');
    $key = trim(sanitize_text_field($_POST['key'] ?? ''));
    update_option('er_claude_key', $key);
    wp_send_json_success(['saved' => true]);
});

/* — Estado key Anthropic — */
add_action('wp_ajax_er_key_status', function () {
    check_ajax_referer('er_nonce', 'nonce');
    $key      = get_option('er_claude_key', '');
    $provider = get_option('er_ai_provider', 'anthropic');
    wp_send_json_success([
        'configured' => !empty($key),
        'masked'     => $key ? substr($key, 0, 8) . '...' : '',
        'provider'   => $provider,
    ]);
});


/* ═══════════════════════════════════════════════════════════
   8. AJAX — IMPORTADOR NOTAS DEMO — progresivo (una por llamada)
   er_import_demo_one  → genera e inserta UNA nota (índice 0-9)
   er_import_demo_info → devuelve el total de notas disponibles
═══════════════════════════════════════════════════════════ */

// ── Auxiliar compartida ──
function er_ia_provider_config() {
    $provider = get_option('er_ai_provider', '');
    $or_key   = get_option('er_or_api_key', '');
    $ant_key  = get_option('er_claude_key', '');
    if (!$provider) {
        $provider = (str_starts_with($or_key, 'sk-or-') || str_starts_with($ant_key, 'sk-or-'))
            ? 'openrouter' : 'anthropic';
    }
    if ($provider === 'anthropic' && str_starts_with($ant_key, 'sk-or-')) {
        $provider = 'openrouter';
        if (!$or_key) { $or_key = $ant_key; }
    }
    $api_key = ($provider === 'openrouter') ? ($or_key ?: $ant_key) : $ant_key;
    $model   = ($provider === 'openrouter')
        ? get_option('er_or_model', 'anthropic/claude-sonnet-4')
        : 'claude-sonnet-4-6';
    return compact('provider', 'api_key', 'model');
}

function er_llamar_ia($provider, $api_key, $model, $system, $prompt) {
    if ($provider === 'openrouter') {
        $resp = wp_remote_post('https://openrouter.ai/api/v1/chat/completions', [
            'timeout' => 90,
            'headers' => ['Authorization'=>'Bearer '.$api_key,'Content-Type'=>'application/json','HTTP-Referer'=>get_site_url(),'X-Title'=>'El Rufino Panel IA'],
            'body'    => wp_json_encode(['model'=>$model,'max_tokens'=>800,'messages'=>[['role'=>'system','content'=>$system],['role'=>'user','content'=>$prompt]]]),
        ]);
        if (is_wp_error($resp)) return null;
        $data = json_decode(wp_remote_retrieve_body($resp), true);
        return $data['choices'][0]['message']['content'] ?? null;
    }
    $resp = wp_remote_post('https://api.anthropic.com/v1/messages', [
        'timeout' => 90,
        'headers' => ['x-api-key'=>$api_key,'anthropic-version'=>'2023-06-01','content-type'=>'application/json'],
        'body'    => wp_json_encode(['model'=>$model,'max_tokens'=>800,'system'=>$system,'messages'=>[['role'=>'user','content'=>$prompt]]]),
    ]);
    if (is_wp_error($resp)) return null;
    $data = json_decode(wp_remote_retrieve_body($resp), true);
    return $data['content'][0]['text'] ?? null;
}

function er_notas_demo() {
    return [
        ['titulo'=>'Las obras del barrio norte siguen demoradas',          'pilar'=>'barrio-a-barrio',      'prompt'=>'Escribí una nota periodística completa sobre las obras viales demoradas en el barrio norte de Rufino. El municipio prometió terminarlas en marzo 2026 y siguen incompletas. Incluí: TÍTULO (el mismo que te doy), BAJADA (1 oración con el dato clave), CUERPO (3 párrafos, pirámide invertida), LO QUE SIGNIFICA (1 párrafo de análisis). Máximo 400 palabras.'],
        ['titulo'=>'Nuevo parque de juegos en el barrio oeste: qué falta', 'pilar'=>'barrio-a-barrio',      'prompt'=>'Escribí una nota sobre el nuevo parque de juegos en el barrio oeste de Rufino. Está instalado pero faltan luminarias y cercado perimetral. Los vecinos piden que lo habiliten. Incluí: TÍTULO, BAJADA, CUERPO (3 párrafos), LO QUE SIGNIFICA. Máximo 400 palabras.'],
        ['titulo'=>'Cómo creció la población de Rufino en la última década','pilar'=>'rufino-en-datos',      'prompt'=>'Escribí una nota con datos sobre el crecimiento poblacional de Rufino, Santa Fe. Según el INDEC 2022 son 19.211 habitantes. Analizá qué significa ese crecimiento para los servicios locales. Incluí: TÍTULO, BAJADA, CUERPO (3 párrafos con datos), LO QUE SIGNIFICA. Máximo 400 palabras.'],
        ['titulo'=>'Estadísticas de empleo en Rufino: primer semestre 2026','pilar'=>'rufino-en-datos',      'prompt'=>'Escribí una nota de contexto sobre el empleo en Rufino y el sur de Santa Fe en el primer semestre 2026. Referenciá datos del INDEC y del mercado laboral regional. Incluí: TÍTULO, BAJADA, CUERPO (3 párrafos), LO QUE SIGNIFICA. Máximo 400 palabras.'],
        ['titulo'=>'Los productores locales anticipan una campaña difícil', 'pilar'=>'el-campo-habla',      'prompt'=>'Escribí una nota sobre la situación del campo en Rufino y la zona sur de Santa Fe. Los productores enfrentan costos altos, precios bajos y clima incierto para la campaña 2026/2027. Incluí: TÍTULO, BAJADA, CUERPO (3 párrafos), LO QUE SIGNIFICA para la economía local. Máximo 400 palabras.'],
        ['titulo'=>'La sequía afecta la producción en el sur de Santa Fe',  'pilar'=>'el-campo-habla',      'prompt'=>'Escribí una nota sobre el impacto de la sequía en la producción agropecuaria del sur de Santa Fe, zona Rufino. Mencioná soja, girasol y ganadería. Incluí voz de al menos un productor ficticio pero verosímil. Incluí: TÍTULO, BAJADA, CUERPO (3 párrafos), LO QUE SIGNIFICA. Máximo 400 palabras.'],
        ['titulo'=>'Jóvenes rufinenses destacan en el torneo provincial',   'pilar'=>'generacion-rufino',   'prompt'=>'Escribí una nota sobre jóvenes de Rufino que participaron y destacaron en un torneo deportivo provincial. Podés elegir vóley, básquet o atletismo. Incluí nombres ficticios pero verosímiles. Incluí: TÍTULO, BAJADA, CUERPO (3 párrafos), LO QUE SIGNIFICA para la juventud local. Máximo 400 palabras.'],
        ['titulo'=>'Egresados 2025: el camino después del secundario',      'pilar'=>'generacion-rufino',   'prompt'=>'Escribí una nota sobre los egresados 2025 de los colegios secundarios de Rufino y qué opciones tienen: estudiar, trabajar, emigrar o quedarse. Analizá la falta de oferta universitaria local. Incluí: TÍTULO, BAJADA, CUERPO (3 párrafos), LO QUE SIGNIFICA. Máximo 400 palabras.'],
        ['titulo'=>'El municipio prometió pavimentar tres calles del sur',  'pilar'=>'poder-y-gestion','prompt'=>'Escribí una nota de seguimiento: el intendente de Rufino prometió pavimentar tres calles del barrio sur en el presupuesto 2026. Ya pasaron 4 meses y no empezaron. Incluí: TÍTULO, BAJADA, CUERPO (3 párrafos con seguimiento), LO QUE SIGNIFICA. Máximo 400 palabras.'],
        ['titulo'=>'Semáforo de promesas: qué se cumplió y qué no en 2025','pilar'=>'poder-y-gestion','prompt'=>'Escribí una nota de balance: repasá 5 promesas del gobierno municipal de Rufino en 2025 y evaluá su estado (cumplida, parcial, incumplida). Usá el formato "semáforo". Incluí: TÍTULO, BAJADA, CUERPO (lista de promesas con estado), LO QUE SIGNIFICA. Máximo 450 palabras.'],
    ];
}

// ── Auxiliar: descarga una imagen de Picsum y la sube a la Media Library ──
// Usa una semilla fija por pilar para imágenes temáticas y reproducibles.
function er_attach_demo_image($post_id, $pilar, $index) {
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    // Semillas de Picsum elegidas manualmente por temática:
    // barrio-a-barrio → ciudad/arquitectura
    // rufino-en-datos  → estadísticas/ciudad
    // el-campo-habla  → campo/naturaleza
    // generacion-rufino → jóvenes/deporte
    // poder-y-gestion → gobierno/ciudad
    $semillas = [
        'barrio-a-barrio'        => [10, 1029],
        'rufino-en-datos'         => [325, 373],
        'el-campo-habla'         => [107, 289],
        'generacion-rufino'      => [28,  42],
        'poder-y-gestion'   => [274, 431],
    ];

    $lista   = $semillas[$pilar] ?? [100, 200];
    $semilla = $lista[$index % count($lista)];
    $url     = "https://picsum.photos/seed/{$semilla}/1200/675";

    // Descarga temporal
    $tmp = download_url($url, 30);
    if (is_wp_error($tmp)) return false;

    $file = [
        'name'     => "demo-{$pilar}-{$semilla}.jpg",
        'type'     => 'image/jpeg',
        'tmp_name' => $tmp,
        'error'    => 0,
        'size'     => filesize($tmp),
    ];

    $attach_id = media_handle_sideload($file, $post_id, sanitize_title($pilar));
    @unlink($tmp);

    if (is_wp_error($attach_id)) return false;

    set_post_thumbnail($post_id, $attach_id);
    return $attach_id;
}

// ── Auxiliar: descarga una imagen de Unsplash y la adjunta como imagen destacada ──
function er_attach_unsplash_image($post_id, $query, $titulo) {
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    // URL de imagen aleatoria de Unsplash según la temática del pilar
    $encoded = urlencode($query);
    $img_url = "https://source.unsplash.com/1200x675/?{$encoded}";

    // Descargar la imagen temporalmente
    $tmp = download_url($img_url, 15);
    if (is_wp_error($tmp)) return false;

    $ext      = 'jpg';
    $slug     = sanitize_title($titulo);
    $filename = "demo-{$slug}.{$ext}";

    $file_array = [
        'name'     => $filename,
        'tmp_name' => $tmp,
    ];

    $attach_id = media_handle_sideload($file_array, $post_id, $titulo);
    @unlink($tmp);

    if (is_wp_error($attach_id)) return false;

    set_post_thumbnail($post_id, $attach_id);
    return $attach_id;
}

// ── Palabras clave de imagen por pilar ──
function er_unsplash_query_for_pilar($pilar) {
    $map = [
        'barrio-a-barrio'      => 'neighborhood street argentina',
        'rufino-en-datos'       => 'data statistics chart argentina',
        'el-campo-habla'       => 'soybean farm argentina campo',
        'generacion-rufino'    => 'youth sport argentina students',
        'poder-y-gestion' => 'city hall government argentina',
    ];
    return $map[$pilar] ?? 'rufino santa fe argentina';
}

// ── Endpoint principal: genera UNA nota por llamada ──
add_action('wp_ajax_er_import_demo_one', function () {
    check_ajax_referer('er_nonce', 'nonce');

    $cfg   = er_ia_provider_config();
    if (!$cfg['api_key']) {
        wp_send_json_error(['msg' => 'API key no configurada. Configurala en Dashboard → Proveedor IA.']);
        return;
    }

    $index  = (int)($_POST['index'] ?? 0);
    $notas  = er_notas_demo();
    if (!isset($notas[$index])) {
        wp_send_json_error(['msg' => 'Índice fuera de rango.']);
        return;
    }
    $nota = $notas[$index];

    $system = 'Sos el redactor de El Rufino, medio digital local de Rufino, Santa Fe, Argentina (19.211 habitantes). '
            . 'Escribís en español rioplatense, directo, sin adjetivos vacíos, con contexto local siempre. '
            . 'Usás la regla de dos capas: primero el hecho concreto, luego su significado para la comunidad. '
            . 'Nunca escribís "En el marco de" ni clichés periodísticos.';

    $contenido = er_llamar_ia($cfg['provider'], $cfg['api_key'], $cfg['model'], $system, $nota['prompt']);

    if (!$contenido) {
        $contenido = '<p><em>⚠ No se pudo generar contenido con IA. Verificá la API key.</em></p>';
    }

    // Markdown → HTML básico
    $html = '';
    foreach (explode("\n", trim($contenido)) as $linea) {
        $linea = trim($linea);
        if (!$linea) continue;
        $linea = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $linea);
        $html .= '<p>' . $linea . '</p>' . "\n";
    }

    $cat     = get_term_by('slug', $nota['pilar'], 'category');
    $post_id = wp_insert_post([
        'post_title'    => $nota['titulo'],
        'post_content'  => $html,
        'post_status'   => 'draft',
        'post_author'   => get_current_user_id(),
        'post_category' => $cat ? [$cat->term_id] : [],
    ]);

    if (is_wp_error($post_id)) {
        wp_send_json_error(['msg' => 'Error al guardar: ' . $post_id->get_error_message()]);
        return;
    }

    // ── Imagen destacada desde Unsplash ──
    $img_query = er_unsplash_query_for_pilar($nota['pilar']);
    $attach_id = er_attach_unsplash_image($post_id, $img_query, $nota['titulo']);

    wp_send_json_success([
        'index'   => $index,
        'titulo'  => $nota['titulo'],
        'post_id' => $post_id,
        'imagen'  => $attach_id ? true : false,
    ]);
});

// ── Endpoint legacy er_import_demo (compatibilidad, ya no lo usa el JS) ──
add_action('wp_ajax_er_import_demo', function () {
    check_ajax_referer('er_nonce', 'nonce');

    // ── Configuración del proveedor (mismo patrón que sección 7) ──
    $provider = get_option('er_ai_provider', '');
    $or_key   = get_option('er_or_api_key', '');
    $ant_key  = get_option('er_claude_key', '');

    if (!$provider) {
        $provider = (str_starts_with($or_key, 'sk-or-') || str_starts_with($ant_key, 'sk-or-'))
            ? 'openrouter' : 'anthropic';
    }
    if ($provider === 'anthropic' && str_starts_with($ant_key, 'sk-or-')) {
        $provider = 'openrouter';
        if (!$or_key) { $or_key = $ant_key; }
    }

    $api_key = ($provider === 'openrouter') ? ($or_key ?: $ant_key) : $ant_key;
    if (!$api_key) {
        wp_send_json_error(['msg' => 'API key no configurada. Configurala en Dashboard → Proveedor IA.']);
        return;
    }
    $model = ($provider === 'openrouter')
        ? get_option('er_or_model', 'anthropic/claude-sonnet-4')
        : 'claude-sonnet-4-6';

    // ── System prompt del redactor ──
    $system = 'Sos el redactor de El Rufino, medio digital local de Rufino, Santa Fe, Argentina (19.211 habitantes). '
            . 'Escribís en español rioplatense, directo, sin adjetivos vacíos, con contexto local siempre. '
            . 'Usás la regla de dos capas: primero el hecho concreto, luego su significado para la comunidad. '
            . 'Nunca escribís "En el marco de" ni clichés periodísticos.';

    // ── 10 notas distribuidas en los 5 pilares ──
    $notas_demo = [
        [
            'titulo' => 'Las obras del barrio norte siguen demoradas',
            'pilar'  => 'barrio-a-barrio',
            'prompt' => 'Escribí una nota periodística completa sobre las obras viales demoradas en el barrio norte de Rufino. '
                      . 'El municipio prometió terminarlas en marzo 2026 y siguen incompletas. '
                      . 'Incluí: TÍTULO (el mismo que te doy), BAJADA (1 oración con el dato clave), '
                      . 'CUERPO (3 párrafos, pirámide invertida), LO QUE SIGNIFICA (1 párrafo de análisis). '
                      . 'Máximo 400 palabras.',
        ],
        [
            'titulo' => 'Nuevo parque de juegos en el barrio oeste: qué falta',
            'pilar'  => 'barrio-a-barrio',
            'prompt' => 'Escribí una nota sobre el nuevo parque de juegos en el barrio oeste de Rufino. '
                      . 'Está instalado pero faltan luminarias y cercado perimetral. Los vecinos piden que lo habiliten. '
                      . 'Incluí: TÍTULO, BAJADA, CUERPO (3 párrafos), LO QUE SIGNIFICA. Máximo 400 palabras.',
        ],
        [
            'titulo' => 'Cómo creció la población de Rufino en la última década',
            'pilar'  => 'rufino-en-datos',
            'prompt' => 'Escribí una nota con datos sobre el crecimiento poblacional de Rufino, Santa Fe. '
                      . 'Según el INDEC 2022 son 19.211 habitantes. Analizá qué significa ese crecimiento para los servicios locales. '
                      . 'Incluí: TÍTULO, BAJADA, CUERPO (3 párrafos con datos), LO QUE SIGNIFICA. Máximo 400 palabras.',
        ],
        [
            'titulo' => 'Estadísticas de empleo en Rufino: primer semestre 2026',
            'pilar'  => 'rufino-en-datos',
            'prompt' => 'Escribí una nota de Rufino en datos sobre el empleo en Rufino y el sur de Santa Fe en el primer semestre 2026. '
                      . 'Referenciá datos del INDEC y del mercado laboral regional. '
                      . 'Incluí: TÍTULO, BAJADA, CUERPO (3 párrafos), LO QUE SIGNIFICA. Máximo 400 palabras.',
        ],
        [
            'titulo' => 'Los productores locales anticipan una campaña difícil',
            'pilar'  => 'el-campo-habla',
            'prompt' => 'Escribí una nota sobre la situación del campo en Rufino y la zona sur de Santa Fe. '
                      . 'Los productores enfrentan costos altos, precios bajos y clima incierto para la campaña 2026/2027. '
                      . 'Incluí: TÍTULO, BAJADA, CUERPO (3 párrafos), LO QUE SIGNIFICA para la economía local. Máximo 400 palabras.',
        ],
        [
            'titulo' => 'La sequía afecta la producción en el sur de Santa Fe',
            'pilar'  => 'el-campo-habla',
            'prompt' => 'Escribí una nota sobre el impacto de la sequía en la producción agropecuaria del sur de Santa Fe, zona Rufino. '
                      . 'Mencioná soja, girasol y ganadería. Incluí voz de al menos un productor ficticio pero verosímil. '
                      . 'Incluí: TÍTULO, BAJADA, CUERPO (3 párrafos), LO QUE SIGNIFICA. Máximo 400 palabras.',
        ],
        [
            'titulo' => 'Jóvenes rufinenses destacan en el torneo provincial',
            'pilar'  => 'generacion-rufino',
            'prompt' => 'Escribí una nota sobre jóvenes de Rufino que participaron y destacaron en un torneo deportivo provincial. '
                      . 'Podés elegir vóley, básquet o atletismo. Incluí nombres ficticios pero verosímiles. '
                      . 'Incluí: TÍTULO, BAJADA, CUERPO (3 párrafos), LO QUE SIGNIFICA para la juventud local. Máximo 400 palabras.',
        ],
        [
            'titulo' => 'Egresados 2025: el camino después del secundario en Rufino',
            'pilar'  => 'generacion-rufino',
            'prompt' => 'Escribí una nota sobre los egresados 2025 de los colegios secundarios de Rufino y qué opciones tienen: '
                      . 'estudiar, trabajar, emigrar o quedarse. Analizá la falta de oferta universitaria local. '
                      . 'Incluí: TÍTULO, BAJADA, CUERPO (3 párrafos), LO QUE SIGNIFICA. Máximo 400 palabras.',
        ],
        [
            'titulo' => 'El municipio prometió pavimentar tres calles del barrio sur',
            'pilar'  => 'poder-y-gestion',
            'prompt' => 'Escribí una nota de Poder y gestion: el intendente de Rufino prometió pavimentar tres calles '
                      . 'del barrio sur en el presupuesto 2026. Ya pasaron 4 meses y no empezaron. '
                      . 'Incluí: TÍTULO, BAJADA, CUERPO (3 párrafos con seguimiento), LO QUE SIGNIFICA. Máximo 400 palabras.',
        ],
        [
            'titulo' => 'Semáforo de promesas: qué se cumplió y qué no en 2025',
            'pilar'  => 'poder-y-gestion',
            'prompt' => 'Escribí una nota de balance: repasá 5 promesas del gobierno municipal de Rufino en 2025 '
                      . 'y evaluá su estado (cumplida, parcial, incumplida). Usá el formato "semáforo". '
                      . 'Incluí: TÍTULO, BAJADA, CUERPO (lista de promesas con estado), LO QUE SIGNIFICA. Máximo 450 palabras.',
        ],
    ];

    // ── Importar nota por nota (usa er_llamar_ia global) ──
    $count   = 0;
    $errores = 0;

    foreach ($notas_demo as $nota) {
        $contenido_ia = er_llamar_ia($provider, $api_key, $model, $system, $nota['prompt']);

        // Si la IA falla, usar placeholder con aviso (no bloquea el resto)
        if (!$contenido_ia) {
            $contenido_ia = '<p><em>⚠ No se pudo generar contenido con IA para esta nota. '
                          . 'Verificá la API key en Dashboard → Proveedor IA.</em></p>';
            $errores++;
        }

        // Convertir texto plano a HTML básico
        $html = '';
        foreach (explode("\n", trim($contenido_ia)) as $linea) {
            $linea = trim($linea);
            if (!$linea) continue;
            // Negritas markdown → strong
            $linea = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $linea);
            $html .= '<p>' . $linea . '</p>' . "\n";
        }

        $cat     = get_term_by('slug', $nota['pilar'], 'category');
        $post_id = wp_insert_post([
            'post_title'    => $nota['titulo'],
            'post_content'  => $html,
            'post_status'   => 'draft',
            'post_author'   => get_current_user_id(),
            'post_category' => $cat ? [$cat->term_id] : [],
        ]);

        if (!is_wp_error($post_id)) {
            $count++;
            // ── Imagen destacada desde Unsplash ──
            $img_query = er_unsplash_query_for_pilar($nota['pilar']);
            er_attach_unsplash_image($post_id, $img_query, $nota['titulo']);
        }
    }

    wp_send_json_success([
        'imported' => $count,
        'errores'  => $errores,
        'msg'      => $errores > 0
            ? "$count notas importadas con IA. $errores con placeholder (revisá la API key)."
            : "$count notas generadas con IA y guardadas como borrador.",
    ]);
});
/* ═══════════════════════════════════════════════════════════
   9. AJAX — PROMESAS / SEGUIMIENTO
═══════════════════════════════════════════════════════════ */
add_action('wp_ajax_er_get_promesas', function () {
    check_ajax_referer('er_nonce', 'nonce');
    wp_send_json_success(get_option('er_promesas', []));
});

add_action('wp_ajax_er_save_promesa', function () {
    check_ajax_referer('er_nonce', 'nonce');
    $promesas   = get_option('er_promesas', []);
    $promesas[] = [
        'id'       => uniqid(),
        'texto'    => sanitize_text_field($_POST['texto'] ?? ''),
        'fuente'   => sanitize_text_field($_POST['fuente'] ?? ''),
        'fecha'    => sanitize_text_field($_POST['fecha'] ?? date('Y-m-d')),
        'estado'   => 'pendiente',
        'created'  => date('Y-m-d H:i:s'),
    ];
    update_option('er_promesas', $promesas);
    wp_send_json_success(['saved' => true]);
});

add_action('wp_ajax_er_update_promesa', function () {
    check_ajax_referer('er_nonce', 'nonce');
    $id      = sanitize_text_field($_POST['id'] ?? '');
    $estado  = sanitize_text_field($_POST['estado'] ?? '');
    $promesas = get_option('er_promesas', []);
    foreach ($promesas as &$p) {
        if ($p['id'] === $id) { $p['estado'] = $estado; break; }
    }
    update_option('er_promesas', $promesas);
    wp_send_json_success(['saved' => true]);
});

add_action('wp_ajax_er_export_promesas', function () {
    check_ajax_referer('er_nonce', 'nonce');
    $promesas = get_option('er_promesas', []);
    $csv = "ID,Texto,Fuente,Fecha,Estado\n";
    foreach ($promesas as $p) {
        $csv .= '"' . ($p['id'] ?? '') . '","' . ($p['texto'] ?? '') . '","' . ($p['fuente'] ?? '') . '","' . ($p['fecha'] ?? '') . '","' . ($p['estado'] ?? '') . '"' . "\n";
    }
    wp_send_json_success(['csv' => $csv]);
});

/* ═══════════════════════════════════════════════════════════
   10. AJAX — ASISTENTE DE NOTICIA — YouTube API Key
═══════════════════════════════════════════════════════════ */
add_action('wp_ajax_er_save_ytkey', function () {
    check_ajax_referer('er_nonce', 'nonce');
    $key = sanitize_text_field($_POST['key'] ?? '');
    update_option('er_yt_api_key', $key);
    wp_send_json_success(['saved' => true]);
});

add_action('wp_ajax_er_ytkey_status', function () {
    check_ajax_referer('er_nonce', 'nonce');
    $key = get_option('er_yt_api_key', '');
    wp_send_json_success(['configured' => !empty($key), 'masked' => $key ? substr($key, 0, 8) . '...' : '']);
});

/* ═══════════════════════════════════════════════════════════
   11. AJAX — ASISTENTE — Obtener datos del video YouTube
═══════════════════════════════════════════════════════════ */
add_action('wp_ajax_er_yt_info', function () {
    check_ajax_referer('er_nonce', 'nonce');

    $yt_key = get_option('er_yt_api_key', '');
    if (!$yt_key) {
        wp_send_json_error(['msg' => 'YouTube API key no configurada. Configurala en el Asistente.']);
        return;
    }

    $url_video = sanitize_text_field($_POST['url'] ?? '');
    $video_id = '';
    if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|v\/))([a-zA-Z0-9_-]{11})/', $url_video, $m)) {
        $video_id = $m[1];
    }
    if (!$video_id) {
        wp_send_json_error(['msg' => 'URL de YouTube inválida. Formatos aceptados: youtu.be/ID o youtube.com/watch?v=ID']);
        return;
    }

    $api_url = add_query_arg([
        'part'  => 'snippet,contentDetails',
        'id'    => $video_id,
        'key'   => $yt_key,
    ], 'https://www.googleapis.com/youtube/v3/videos');

    $resp = wp_remote_get($api_url, ['timeout' => 15]);
    if (is_wp_error($resp)) {
        wp_send_json_error(['msg' => 'Error de conexión con YouTube: ' . $resp->get_error_message()]);
        return;
    }

    $data = json_decode(wp_remote_retrieve_body($resp), true);
    if (empty($data['items'])) {
        wp_send_json_error(['msg' => 'Video no encontrado. Verificá que el link sea correcto y el video esté disponible.']);
        return;
    }

    $snippet = $data['items'][0]['snippet'];
    wp_send_json_success([
        'video_id'    => $video_id,
        'titulo'      => $snippet['title'] ?? '',
        'descripcion' => $snippet['description'] ?? '',
        'canal'       => $snippet['channelTitle'] ?? '',
        'fecha'       => substr($snippet['publishedAt'] ?? '', 0, 10),
        'thumbnail'   => $snippet['thumbnails']['medium']['url'] ?? '',
    ]);
});

/* ═══════════════════════════════════════════════════════════
   11b. AJAX — ASISTENTE — Transcripción automática (Captions)
═══════════════════════════════════════════════════════════ */
add_action('wp_ajax_er_yt_captions', function () {
    check_ajax_referer('er_nonce', 'nonce');

    $video_id = sanitize_text_field($_POST['video_id'] ?? '');
    if (!$video_id || !preg_match('/^[a-zA-Z0-9_-]{11}$/', $video_id)) {
        wp_send_json_error(['msg' => 'ID de video inválido.']);
        return;
    }

    /*
     * Estrategia: endpoint público de timedtext de YouTube.
     * La Captions API oficial requiere OAuth y además falla con videos "No listados"
     * de otros canales (devuelve "videoId not found" aunque el video exista).
     * El endpoint timedtext es público, no necesita API key, y funciona con
     * cualquier video que tenga subtítulos automáticos generados por YouTube.
     *
     * Probamos en orden: es → es-419 → es-AR → en → lista automática del video.
     */

    $headers = ['user-agent' => 'Mozilla/5.0 (compatible; ElRufino/1.0)'];

    // Idiomas a intentar en orden de prioridad
    $langs = ['es', 'es-419', 'es-AR', 'en'];

    foreach ($langs as $lang) {
        $url  = 'https://www.youtube.com/api/timedtext?v=' . urlencode($video_id)
              . '&lang=' . urlencode($lang) . '&fmt=json3';
        $resp = wp_remote_get($url, ['timeout' => 15, 'headers' => $headers]);
        if (is_wp_error($resp)) continue;

        $body = wp_remote_retrieve_body($resp);
        if (!$body) continue;

        $data = json_decode($body, true);
        if (empty($data['events'])) continue;

        $texto = er_parse_timedtext_json3($data);
        if (!$texto) continue;

        wp_send_json_success(['transcripcion' => $texto, 'fuente' => 'timedtext-' . $lang]);
        return;
    }

    /*
     * Si los idiomas fijos no funcionaron, intentamos obtener la lista de tracks
     * disponibles usando el endpoint de lista (sin API key, devuelve XML).
     * Ejemplo: https://www.youtube.com/api/timedtext?v=ID&type=list
     */
    $list_url  = 'https://www.youtube.com/api/timedtext?v=' . urlencode($video_id) . '&type=list';
    $list_resp = wp_remote_get($list_url, ['timeout' => 15, 'headers' => $headers]);

    if (!is_wp_error($list_resp)) {
        $xml_body = wp_remote_retrieve_body($list_resp);
        if ($xml_body) {
            // Extraer atributos lang_code del XML: <track id="0" name="" lang_code="es" .../>
            preg_match_all('/lang_code=["\']([^"\']+)["\']/', $xml_body, $matches);
            $available_langs = $matches[1] ?? [];

            foreach ($available_langs as $al) {
                if (in_array($al, $langs)) continue; // ya lo intentamos
                $url  = 'https://www.youtube.com/api/timedtext?v=' . urlencode($video_id)
                      . '&lang=' . urlencode($al) . '&fmt=json3';
                $resp = wp_remote_get($url, ['timeout' => 15, 'headers' => $headers]);
                if (is_wp_error($resp)) continue;
                $body = wp_remote_retrieve_body($resp);
                if (!$body) continue;
                $data = json_decode($body, true);
                if (empty($data['events'])) continue;
                $texto = er_parse_timedtext_json3($data);
                if (!$texto) continue;
                wp_send_json_success(['transcripcion' => $texto, 'fuente' => 'timedtext-' . $al]);
                return;
            }
        }
    }

    // Nada funcionó
    wp_send_json_error([
        'msg' => 'El video no tiene subtítulos automáticos disponibles públicamente. '
               . 'Para activarlos: en YouTube Studio → seleccioná el video → Subtítulos → "Agregar idioma" → Español → "Agregar" en la columna Subtítulos automáticos. '
               . 'Puede tardar unos minutos en generarse. O usá la transcripción manual.'
    ]);
});

/**
 * Parsear formato VTT y devolver texto limpio.
 */
function er_parse_vtt(string $vtt): string {
    // Eliminar cabecera WEBVTT y líneas de tiempo (00:00:00.000 --> 00:00:05.000)
    $lines = explode("\n", $vtt);
    $text_lines = [];
    $prev = '';
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;
        if ($line === 'WEBVTT') continue;
        if (preg_match('/^\d{2}:\d{2}[\d:,.]+\s*-->\s*\d{2}:\d{2}/', $line)) continue;
        if (preg_match('/^NOTE\b/', $line)) continue;
        if (is_numeric($line)) continue; // número de bloque SRT
        // Limpiar tags HTML y de estilo VTT
        $clean = preg_replace('/<[^>]+>/', '', $line);
        $clean = trim($clean);
        if ($clean && $clean !== $prev) {
            $text_lines[] = $clean;
            $prev = $clean;
        }
    }
    // Unir y limpiar duplicados consecutivos
    $text = implode(' ', $text_lines);
    $text = preg_replace('/\s+/', ' ', $text);
    return trim($text);
}

/**
 * Parsear formato json3 del endpoint timedtext público.
 */
function er_parse_timedtext_json3(array $data): string {
    $parts = [];
    foreach ($data['events'] ?? [] as $event) {
        if (empty($event['segs'])) continue;
        $seg_text = '';
        foreach ($event['segs'] as $seg) {
            $seg_text .= $seg['utf8'] ?? '';
        }
        $seg_text = trim(str_replace("\n", ' ', $seg_text));
        if ($seg_text) $parts[] = $seg_text;
    }
    $text = implode(' ', $parts);
    $text = preg_replace('/\s+/', ' ', $text);
    return trim($text);
}

/* ═══════════════════════════════════════════════════════════
   12. AJAX — ASISTENTE — Generar borrador (provider-aware)
═══════════════════════════════════════════════════════════ */
add_action('wp_ajax_er_asistente_generar', function () {
    check_ajax_referer('er_nonce', 'nonce');

    $provider = get_option('er_ai_provider', '');
    $or_key   = get_option('er_or_api_key', '');
    $ant_key  = get_option('er_claude_key', '');

    if (!$provider) {
        $provider = (str_starts_with($or_key, 'sk-or-') || str_starts_with($ant_key, 'sk-or-'))
            ? 'openrouter' : 'anthropic';
    }
    if ($provider === 'anthropic' && str_starts_with($ant_key, 'sk-or-')) {
        $provider = 'openrouter';
        if (!$or_key) { $or_key = $ant_key; }
    }

    $pilar        = sanitize_text_field($_POST['pilar'] ?? '');
    $keywords     = sanitize_text_field($_POST['keywords'] ?? '');
    $transcripcion = sanitize_textarea_field($_POST['transcripcion'] ?? '');
    $video_titulo = sanitize_text_field($_POST['video_titulo'] ?? '');
    $video_desc   = sanitize_text_field($_POST['video_desc'] ?? '');
    $video_canal  = sanitize_text_field($_POST['video_canal'] ?? '');
    $video_fecha  = sanitize_text_field($_POST['video_fecha'] ?? '');
    $imagen_b64   = $_POST['imagen_b64'] ?? '';
    $imagen_type  = sanitize_text_field($_POST['imagen_type'] ?? 'image/jpeg');

    $contexto_video = '';
    if ($video_titulo) {
        $contexto_video .= "INFORMACIÓN DEL VIDEO YOUTUBE:\n";
        $contexto_video .= "- Título: {$video_titulo}\n";
        if ($video_canal) $contexto_video .= "- Canal: {$video_canal}\n";
        if ($video_fecha) $contexto_video .= "- Fecha: {$video_fecha}\n";
        if ($video_desc)  $contexto_video .= "- Descripción: " . substr($video_desc, 0, 800) . "\n";
    }

    $system = <<<SYSTEM
Sos el redactor de El Rufino, medio digital de Rufino, Santa Fe, Argentina.
Tu estilo: directo, preciso, sin adjetivos vacíos, con contexto local siempre.
Usás la "regla de dos capas": primero el hecho concreto, luego "Lo que significa".
Escribís en español rioplatense, segunda persona del plural (ustedes/los rufinenses).
Evitás clichés periodísticos y frases de relleno.
SYSTEM;

    $prompt = "Generá una nota periodística completa para El Rufino.\n\n";
    if ($contexto_video) $prompt .= $contexto_video . "\n";
    if ($transcripcion)  $prompt .= "TRANSCRIPCIÓN / LO QUE DIJO EL ENTREVISTADO:\n" . $transcripcion . "\n\n";
    $prompt .= "PILAR EDITORIAL: {$pilar}\n";
    if ($keywords) $prompt .= "PALABRAS CLAVE / CONTEXTO: {$keywords}\n\n";
    $prompt .= "La nota debe tener esta estructura EXACTA (usá estos títulos en negrita):\n";
    $prompt .= "**TÍTULO:** (directo, sin punto final, máx 12 palabras)\n";
    $prompt .= "**BAJADA:** (1 oración, el dato más importante, máx 30 palabras)\n";
    $prompt .= "**CUERPO:** (3-4 párrafos, 500-700 palabras, pirámide invertida)\n";
    $prompt .= "**LO QUE SIGNIFICA:** (1 párrafo de análisis, qué implica para Rufino)\n";
    $prompt .= "**CITA DESTACADA:** (si hay declaración relevante en el video, en comillas)\n";
    $prompt .= "**FUENTE:** (fuente sugerida para verificación)\n\n";
    $prompt .= "Escribí como si fueras a publicarlo hoy.";

    /* ── OpenRouter ─────────────────────────────────────── */
    if ($provider === 'openrouter') {
        $api_key = $or_key ?: $ant_key;
        if (!$api_key) {
            wp_send_json_error(['msg' => 'API key de OpenRouter no configurada.']);
            return;
        }
        $model    = get_option('er_or_model', 'anthropic/claude-sonnet-4');
        $messages = [
            ['role' => 'system', 'content' => $system],
            ['role' => 'user',   'content' => $prompt],
        ];
        $response = wp_remote_post('https://openrouter.ai/api/v1/chat/completions', [
            'timeout' => 120,
            'headers' => [
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type'  => 'application/json',
                'HTTP-Referer'  => get_site_url(),
                'X-Title'       => 'El Rufino Panel IA',
            ],
            'body' => wp_json_encode(['model' => $model, 'max_tokens' => 2000, 'messages' => $messages]),
        ]);
        if (is_wp_error($response)) {
            wp_send_json_error(['msg' => 'Error OpenRouter: ' . $response->get_error_message()]);
            return;
        }
        $data = json_decode(wp_remote_retrieve_body($response), true);
        if (!empty($data['error'])) {
            wp_send_json_error(['msg' => 'Error OpenRouter: ' . ($data['error']['message'] ?? 'desconocido')]);
            return;
        }
        wp_send_json_success(['borrador' => $data['choices'][0]['message']['content'] ?? '']);
        return;
    }

    /* ── Anthropic directo ──────────────────────────────── */
    $api_key = $ant_key;
    if (!$api_key) {
        wp_send_json_error(['msg' => 'API key de Claude no configurada.']);
        return;
    }
    $message_content = [];
    if ($imagen_b64) {
        $b64_clean = preg_replace('/^data:[^;]+;base64,/', '', $imagen_b64);
        $message_content[] = ['type' => 'image', 'source' => ['type' => 'base64', 'media_type' => $imagen_type, 'data' => $b64_clean]];
        $message_content[] = ['type' => 'text', 'text' => "Esta es la imagen de la nota.\n\n" . $prompt];
    } else {
        $message_content[] = ['type' => 'text', 'text' => $prompt];
    }
    $response = wp_remote_post('https://api.anthropic.com/v1/messages', [
        'timeout' => 120,
        'headers' => ['x-api-key' => $api_key, 'anthropic-version' => '2023-06-01', 'content-type' => 'application/json'],
        'body'    => wp_json_encode(['model' => 'claude-sonnet-4-6', 'max_tokens' => 2000, 'system' => $system, 'messages' => [['role' => 'user', 'content' => $message_content]]]),
    ]);
    if (is_wp_error($response)) {
        wp_send_json_error(['msg' => 'Error de conexión: ' . $response->get_error_message()]);
        return;
    }
    $data = json_decode(wp_remote_retrieve_body($response), true);
    if (!empty($data['error'])) {
        wp_send_json_error(['msg' => 'Error Claude: ' . ($data['error']['message'] ?? 'desconocido')]);
        return;
    }
    wp_send_json_success(['borrador' => $data['content'][0]['text'] ?? '']);
});

/* ═══════════════════════════════════════════════════════════
   13. AJAX — ASISTENTE — Guardar borrador en WordPress
═══════════════════════════════════════════════════════════ */
add_action('wp_ajax_er_asistente_guardar', function () {
    check_ajax_referer('er_nonce', 'nonce');

    $titulo    = sanitize_text_field($_POST['titulo'] ?? 'Borrador sin título');
    $contenido = wp_kses_post($_POST['contenido'] ?? '');
    $pilar_slug = sanitize_text_field($_POST['pilar_slug'] ?? '');

    $cat_id = 0;
    if ($pilar_slug) {
        $cat = get_term_by('slug', $pilar_slug, 'category');
        if ($cat) $cat_id = $cat->term_id;
    }

    $post_id = wp_insert_post([
        'post_title'    => $titulo,
        'post_content'  => $contenido,
        'post_status'   => 'draft',
        'post_author'   => get_current_user_id(),
        'post_category' => $cat_id ? [$cat_id] : [],
    ]);

    if (is_wp_error($post_id)) {
        wp_send_json_error(['msg' => $post_id->get_error_message()]);
        return;
    }

    wp_send_json_success([
        'post_id'   => $post_id,
        'edit_url'  => admin_url('post.php?post=' . $post_id . '&action=edit'),
    ]);
});

/* ═══════════════════════════════════════════════════════════
   14. SCHEMA NewsMediaOrganization
═══════════════════════════════════════════════════════════ */
add_action('wp_head', function () {
    $site_url  = get_site_url();
    $logo_url  = $site_url . '/wp-content/uploads/2026/04/cropped-logo-el-rufino-blanco-1-300x113.png';

    $schema = [
        '@context'     => 'https://schema.org',
        '@type'        => 'NewsMediaOrganization',
        'name'         => 'El Rufino',
        'url'          => $site_url,
        'logo'         => ['@type' => 'ImageObject', 'url' => $logo_url, 'width' => 300, 'height' => 113],
        'foundingDate' => '2026',
        'inLanguage'   => 'es-AR',
        'areaServed'   => [
            ['@type' => 'City',    'name' => 'Rufino'],
            ['@type' => 'State',   'name' => 'Santa Fe'],
            ['@type' => 'Country', 'name' => 'Argentina'],
        ],
        'sameAs' => [
            'https://www.facebook.com/elrufino',
            'https://www.instagram.com/elrufino',
        ],
    ];

    if (is_single()) {
        global $post;
        setup_postdata($post);
        $thumb = get_the_post_thumbnail_url($post, 'large');
        $cat   = get_the_category($post->ID);
        $schema_article = [
            '@context'       => 'https://schema.org',
            '@type'          => 'NewsArticle',
            'headline'       => get_the_title($post),
            'description'    => get_the_excerpt($post),
            'datePublished'  => get_the_date('c', $post),
            'dateModified'   => get_the_modified_date('c', $post),
            'articleSection' => $cat ? $cat[0]->name : '',
            'author'         => ['@type' => 'Person', 'name' => get_the_author_meta('display_name', $post->post_author)],
            'publisher'      => $schema,
            'image'          => $thumb ? ['@type' => 'ImageObject', 'url' => $thumb] : null,
        ];
        if (!$schema_article['image']) unset($schema_article['image']);
        echo '<script type="application/ld+json">' . wp_json_encode($schema_article, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
    }

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}, 10);

/* ═══════════════════════════════════════════════════════════
   15. ESCRITORIO WORDPRESS PERSONALIZADO
═══════════════════════════════════════════════════════════ */
add_action('wp_dashboard_setup', function () {
    global $wp_meta_boxes;
    $wp_meta_boxes['dashboard'] = [];

    wp_add_dashboard_widget('er_bienvenida', 'El Rufino — Bienvenida',         'er_widget_bienvenida');
    wp_add_dashboard_widget('er_estado',     'El Rufino — Estado del sitio',   'er_widget_estado');
    wp_add_dashboard_widget('er_pilares',    'El Rufino — Entradas por pilar', 'er_widget_pilares');
    wp_add_dashboard_widget('er_ultimas',    'El Rufino — Últimas entradas',   'er_widget_ultimas');
});

function er_widget_bienvenida() {
    $user      = wp_get_current_user();
    $panel_url = admin_url('admin.php?page=el-rufino-panel');
    $fecha     = date_i18n('l j \d\e F \d\e Y');
    echo '<div style="font-family:sans-serif">';
    echo "<p>Hola, <strong>{$user->display_name}</strong>. Hoy es {$fecha}.</p>";
    echo "<p>
        <a href='{$panel_url}' style='background:#c0271b;color:#fff;padding:8px 18px;border-radius:6px;text-decoration:none;margin-right:8px;display:inline-block'>🚀 Abrir Panel IA</a>
        <a href='" . admin_url('post-new.php') . "' style='background:#1d4ed8;color:#fff;padding:8px 18px;border-radius:6px;text-decoration:none;display:inline-block'>✏️ Nueva entrada</a>
    </p>";
    echo "<p style='color:#666;font-size:0.85em'>Panel IA v" . ER_VERSION . " · Fase 2 en ejecución</p>";
    echo '</div>';
}

function er_widget_estado() {
    $published = (int)wp_count_posts()->publish;
    $drafts    = (int)wp_count_posts()->draft;
    $comments  = (int)get_comments(['status' => 'approve', 'count' => true]);
    $updates   = get_site_transient('update_plugins');
    $pending   = $updates ? count((array)($updates->response ?? [])) : 0;
    $items = [
        ['📝 Publicadas',      $published, '#15803d'],
        ['📄 Borradores',      $drafts,    '#1d4ed8'],
        ['💬 Comentarios',     $comments,  '#7c3aed'],
        ['🔧 Actualizaciones', $pending,   $pending > 0 ? '#d97706' : '#15803d'],
    ];
    echo '<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">';
    foreach ($items as [$label, $val, $color]) {
        echo "<div style='border-left:4px solid {$color};padding:8px 12px;background:#f8f9fa'>
            <div style='font-size:1.4em;font-weight:900;color:{$color}'>{$val}</div>
            <div style='font-size:0.8em;color:#555'>{$label}</div>
        </div>";
    }
    echo '</div>';
}

function er_widget_pilares() {
    $pilares = [
        ['Barrio a barrio',         'barrio-a-barrio',        '#b55233'],
        ['Rufino en datos',        'rufino-en-datos',         '#2f6484'],
        ['El campo habla',          'el-campo-habla',         '#617a45'],
        ['Generación Rufino',       'generacion-rufino',      '#c58a2b'],
        ['Poder y gestion', 'poder-y-gestion',   '#1f2a30'],
    ];
    $max = 1;
    $counts = [];
    foreach ($pilares as [$nombre, $slug, $color]) {
        $cat = get_term_by('slug', $slug, 'category');
        $c   = $cat ? (int)$cat->count : 0;
        $counts[$slug] = $c;
        if ($c > $max) $max = $c;
    }
    echo '<div style="display:flex;flex-direction:column;gap:8px">';
    foreach ($pilares as [$nombre, $slug, $color]) {
        $c   = $counts[$slug];
        $pct = round(($c / $max) * 100);
        $url = admin_url("edit.php?category_name={$slug}");
        echo "<a href='{$url}' style='text-decoration:none;color:inherit'>
            <div style='display:flex;align-items:center;gap:10px'>
                <div style='font-size:0.78em;color:#444;width:140px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis'>{$nombre}</div>
                <div style='flex:1;background:#eee;border-radius:4px;height:14px'>
                    <div style='height:14px;background:{$color};border-radius:4px;width:{$pct}%;transition:width 0.3s'></div>
                </div>
                <div style='font-weight:700;font-size:0.85em;color:{$color};width:24px;text-align:right'>{$c}</div>
            </div>
        </a>";
    }
    echo '</div>';
}

function er_widget_ultimas() {
    $pilares_color = [
        'barrio-a-barrio'        => '#b55233',
        'rufino-en-datos'         => '#2f6484',
        'el-campo-habla'         => '#617a45',
        'generacion-rufino'      => '#c58a2b',
        'poder-y-gestion'   => '#1f2a30',
    ];
    $posts = get_posts(['numberposts' => 8, 'post_status' => ['publish', 'draft'], 'orderby' => 'modified', 'order' => 'DESC']);
    if (!$posts) { echo '<p style="color:#999">Sin entradas todavía.</p>'; return; }
    echo '<ul style="margin:0;padding:0;list-style:none">';
    foreach ($posts as $p) {
        $cats    = get_the_category($p->ID);
        $slug    = $cats ? $cats[0]->slug : '';
        $color   = $pilares_color[$slug] ?? '#999';
        $age     = human_time_diff(strtotime($p->post_modified), time());
        $badge   = $p->post_status === 'publish' ? 'Pub' : 'Bor';
        $badge_c = $p->post_status === 'publish' ? '#15803d' : '#d97706';
        $url     = admin_url('post.php?post=' . $p->ID . '&action=edit');
        echo "<li style='border-bottom:1px solid #f0f0f0;padding:6px 0;display:flex;align-items:center;gap:8px'>
            <span style='width:10px;height:10px;border-radius:50%;background:{$color};flex-shrink:0;display:inline-block'></span>
            <a href='{$url}' style='flex:1;text-decoration:none;color:#222;font-size:0.9em'>" . esc_html(get_the_title($p)) . "</a>
            <span style='font-size:0.72em;background:{$badge_c};color:#fff;padding:2px 6px;border-radius:4px'>{$badge}</span>
            <span style='font-size:0.72em;color:#999'>hace {$age}</span>
        </li>";
    }
    echo '</ul>';
}

/* ═══════════════════════════════════════════════════════════
   16. SEGURIDAD — XML-RPC + HTTPS
═══════════════════════════════════════════════════════════ */
add_filter('xmlrpc_enabled', '__return_false');
add_action('init', function () {
    if (!is_ssl() && !is_admin() && php_sapi_name() !== 'cli') {
        wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301);
        exit;
    }
});

/* ═══════════════════════════════════════════════════════════
   17. AJAX — COBERTURAS
═══════════════════════════════════════════════════════════ */
add_action('wp_ajax_er_get_coberturas', function () {
    check_ajax_referer('er_nonce', 'nonce');
    wp_send_json_success(get_option('er_coberturas', []));
});

add_action('wp_ajax_er_save_cobertura', function () {
    check_ajax_referer('er_nonce', 'nonce');
    $tema = sanitize_text_field($_POST['tema'] ?? '');
    if (!$tema) {
        wp_send_json_error(['msg' => 'El campo tema es requerido.']);
        return;
    }
    $tipos_validos   = ['Legislativo', 'Municipal', 'Judicial', 'Ejecutivo', 'Otro'];
    $estados_validos = ['activo', 'en-espera', 'cerrado'];
    $pilares_validos = ['barrio-a-barrio', 'rufino-en-datos', 'el-campo-habla', 'generacion-rufino', 'poder-y-gestion'];

    $tipo   = in_array($_POST['tipo']   ?? '', $tipos_validos,   true) ? $_POST['tipo']   : 'Municipal';
    $estado = in_array($_POST['estado'] ?? '', $estados_validos, true) ? $_POST['estado'] : 'activo';
    $pilar  = in_array($_POST['pilar']  ?? '', $pilares_validos, true) ? $_POST['pilar']  : '';

    $coberturas   = get_option('er_coberturas', []);
    $coberturas[] = [
        'id'             => uniqid('cob_'),
        'tema'           => $tema,
        'tipo'           => $tipo,
        'pilar'          => $pilar,
        'descripcion'    => sanitize_textarea_field($_POST['descripcion']    ?? ''),
        'proxima_accion' => sanitize_text_field($_POST['proxima_accion']     ?? ''),
        'fecha_revision' => sanitize_text_field($_POST['fecha_revision']     ?? ''),
        'nota_url'       => esc_url_raw($_POST['nota_url']                  ?? ''),
        'estado'         => $estado,
        'fecha_apertura' => date('Y-m-d'),
    ];
    update_option('er_coberturas', $coberturas);
    wp_send_json_success(['saved' => true]);
});

add_action('wp_ajax_er_update_cobertura', function () {
    check_ajax_referer('er_nonce', 'nonce');
    $id    = sanitize_text_field($_POST['id']    ?? '');
    $campo = sanitize_text_field($_POST['campo'] ?? '');
    $valor = $_POST['valor'] ?? '';

    $campos_permitidos = ['tema', 'tipo', 'pilar', 'descripcion', 'proxima_accion', 'fecha_revision', 'nota_url', 'estado'];
    if (!$id || !in_array($campo, $campos_permitidos, true)) {
        wp_send_json_error(['msg' => 'Datos inválidos.']);
        return;
    }

    $coberturas = get_option('er_coberturas', []);
    foreach ($coberturas as &$c) {
        if ($c['id'] === $id) {
            if ($campo === 'descripcion') {
                $c[$campo] = sanitize_textarea_field($valor);
            } elseif ($campo === 'nota_url') {
                $c[$campo] = esc_url_raw($valor);
            } else {
                $c[$campo] = sanitize_text_field($valor);
            }
            break;
        }
    }
    update_option('er_coberturas', $coberturas);
    wp_send_json_success(['saved' => true]);
});

add_action('wp_ajax_er_delete_cobertura', function () {
    check_ajax_referer('er_nonce', 'nonce');
    $id = sanitize_text_field($_POST['id'] ?? '');
    if (!$id) {
        wp_send_json_error(['msg' => 'ID requerido.']);
        return;
    }
    $coberturas = get_option('er_coberturas', []);
    $coberturas = array_values(array_filter($coberturas, function ($c) use ($id) {
        return $c['id'] !== $id;
    }));
    update_option('er_coberturas', $coberturas);
    wp_send_json_success(['deleted' => true]);
});

/* ═══════════════════════════════════════════════════════════
   18. AJAX — PRODUCCIÓN
═══════════════════════════════════════════════════════════ */
add_action('wp_ajax_er_get_posts', function () {
    check_ajax_referer('er_nonce', 'nonce');

    $status_param = sanitize_text_field($_POST['status'] ?? 'all');
    if ($status_param === 'publish') {
        $statuses = ['publish'];
    } elseif ($status_param === 'draft') {
        $statuses = ['draft'];
    } else {
        $statuses = ['publish', 'draft'];
    }

    $query = new WP_Query([
        'post_type'      => 'post',
        'post_status'    => $statuses,
        'posts_per_page' => 100,
        'orderby'        => 'modified',
        'order'          => 'DESC',
    ]);

    $result = [];
    foreach ($query->posts as $p) {
        $cats     = get_the_category($p->ID);
        $cat      = $cats ? $cats[0] : null;
        $result[] = [
            'id'       => $p->ID,
            'title'    => $p->post_title ?: '(sin título)',
            'status'   => $p->post_status,
            'date'     => $p->post_date ? substr($p->post_date, 0, 10) : '',
            'cat_name' => $cat ? $cat->name : '',
            'cat_slug' => $cat ? $cat->slug : '',
            'edit_url' => admin_url('post.php?post=' . $p->ID . '&action=edit'),
            'view_url' => $p->post_status === 'publish' ? get_permalink($p->ID) : '',
        ];
    }
    wp_reset_postdata();
    wp_send_json_success($result);
});

add_action('wp_ajax_er_published_count', function () {
    check_ajax_referer('er_nonce', 'nonce');
    wp_send_json_success(['count' => (int)wp_count_posts()->publish]);
});

/* ═══════════════════════════════════════════════════════════
   19. ROYAL MCP — INTEGRACION
   Registra opciones y theme mods del plugin para que Claude
   pueda leerlos y escribirlos via Royal MCP sin AJAX propio.
   Compatible con Royal MCP v1.4+
═══════════════════════════════════════════════════════════ */

/**
 * 19a. Opciones escribibles via Royal MCP
 * Claude puede actualizar configuracion del plugin directamente.
 */
add_filter('royal_mcp_writable_options', function (array $allowed): array {
    return array_merge($allowed, [
        // API keys y proveedor IA
        'er_anthropic_key',
        'er_openrouter_key',
        'er_ia_provider',
        // YouTube
        'er_yt_key',
        // Checklist Fase 2
        'er_checklist',
        // Promesas (tabla de seguimiento)
        'er_promesas',
        // Coberturas activas
        'er_coberturas',
        // Configuracion general del panel
        'er_panel_config',
    ]);
});

/**
 * 19b. Theme mods escribibles via Royal MCP
 * Claude puede ajustar paleta y tipografia del child theme.
 */
add_filter('royal_mcp_writable_theme_mods', function (array $allowed): array {
    return array_merge($allowed, [
        // Paleta B — colores institucionales
        'er_color_primario',
        'er_color_negro',
        'er_color_crema',
        // Tipografia
        'er_fuente_titulos',
        'er_fuente_cuerpo',
        // Header
        'er_header_style',
        // Custom CSS activo
        'custom_css_post_id',
    ]);
});

/**
 * 19c. Endpoint MCP — estado del plugin
 * Expone un resumen del estado operativo del Panel IA
 * accesible via GET /wp-json/royal-mcp/v1/er-status
 */
add_action('rest_api_init', function () {
    register_rest_route('royal-mcp/v1', '/er-status', [
        'methods'             => 'GET',
        'callback'            => 'er_mcp_status',
        'permission_callback' => function (WP_REST_Request $req) {
            $key = $req->get_header('X-Royal-MCP-API-Key');
            $settings = get_option('royal_mcp_settings', []); $stored = $settings['api_key'] ?? ''; return $key && $stored && hash_equals($stored, $key);
        },
    ]);
});

function er_mcp_status(WP_REST_Request $request): WP_REST_Response {
    $checklist = get_option('er_checklist', []);
    $promesas  = get_option('er_promesas', []);
    $provider  = get_option('er_ia_provider', 'anthropic');
    $ant_key   = get_option('er_anthropic_key', '');
    $or_key    = get_option('er_openrouter_key', '');

    $active_key = ($provider === 'openrouter') ? $or_key : $ant_key;

    return new WP_REST_Response([
        'plugin_version'  => ER_VERSION,
        'ia_provider'     => $provider,
        'ia_key_status'   => $active_key ? 'configurada' : 'sin_configurar',
        'checklist'       => $checklist,
        'promesas_total'  => count($promesas),
        'promesas_estado' => array_count_values(array_column($promesas, 'estado')),
        'fase2_completado' => er_mcp_fase2_pct($checklist),
        'timestamp'       => current_time('c'),
    ], 200);
}

function er_mcp_fase2_pct(array $checklist): string {
    if (empty($checklist)) return '0%';
    $total     = count($checklist);
    $completos = count(array_filter($checklist, fn($v) => (bool)$v));
    return round(($completos / $total) * 100) . '%';
}

/**
 * 19d. Endpoint MCP — promesas
 * GET /wp-json/royal-mcp/v1/er-promesas
 * Permite a Claude leer y filtrar la tabla de promesas.
 */
add_action('rest_api_init', function () {
    register_rest_route('royal-mcp/v1', '/er-promesas', [
        'methods'             => 'GET',
        'callback'            => 'er_mcp_get_promesas',
        'permission_callback' => function (WP_REST_Request $req) {
            $key = $req->get_header('X-Royal-MCP-API-Key');
            $settings = get_option('royal_mcp_settings', []); $stored = $settings['api_key'] ?? ''; return $key && $stored && hash_equals($stored, $key);
        },
    ]);
});

function er_mcp_get_promesas(WP_REST_Request $request): WP_REST_Response {
    $promesas = get_option('er_promesas', []);
    $estado   = $request->get_param('estado');

    if ($estado) {
        $promesas = array_values(
            array_filter($promesas, fn($p) => ($p['estado'] ?? '') === $estado)
        );
    }

    return new WP_REST_Response([
        'total'    => count($promesas),
        'promesas' => $promesas,
    ], 200);
}
