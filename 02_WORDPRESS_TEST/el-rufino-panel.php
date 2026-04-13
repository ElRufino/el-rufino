<?php
/**
 * Plugin Name:       El Rufino — Panel IA
 * Description:       Panel operativo v8.1. Pantalla completa, 4 pantallas, importador de notas demo, proxy Claude API, checklist actualizable, exportar promesas CSV.
 * Version:           8.1.2
 * Author:            El Rufino
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'ER_VERSION', '8.1.2' );
define( 'ER_DIR',     plugin_dir_path( __FILE__ ) );
define( 'ER_URL',     plugin_dir_url( __FILE__ ) );

/* ── Activación ── */
register_activation_hook( __FILE__, 'er_activate' );
function er_activate() {
    global $wpdb;
    $t = $wpdb->prefix . 'er_promesas';
    $c = $wpdb->get_charset_collate();
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( "CREATE TABLE IF NOT EXISTS $t (
        id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        codigo     VARCHAR(10)  NOT NULL,
        promesa    TEXT         NOT NULL,
        quien      VARCHAR(255) NOT NULL,
        fecha      DATE         NOT NULL,
        pilar      VARCHAR(10)  NOT NULL DEFAULT 'P05',
        fuente     VARCHAR(500) DEFAULT '',
        evidencia  TEXT         DEFAULT '',
        estado     VARCHAR(50)  NOT NULL DEFAULT 'Abierta',
        created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $c;" );
    add_option( 'er_version', ER_VERSION );
    // checklist inicial
    if ( ! get_option('er_checklist') ) {
        update_option( 'er_checklist', [
            'cats'   => false,
            'logo'   => true,
            'schema' => false,
            'notas'  => false,
            'wa'     => false,
        ]);
    }
}

/* ── Menú ── */
add_action( 'admin_menu', function() {
    add_menu_page( 'El Rufino', 'El Rufino', 'manage_options', 'el-rufino-panel',
        'er_render_page',
        'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect width="20" height="20" rx="3" fill="#c0271b"/><text x="3" y="15" font-family="serif" font-size="11" font-weight="900" fill="#fff">ER</text></svg>'),
        3
    );
});

/* ── Enqueue ── */
add_action( 'admin_enqueue_scripts', function( $hook ) {
    if ( $hook !== 'toplevel_page_el-rufino-panel' ) return;
    wp_enqueue_style( 'er-fonts', 'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Source+Serif+4:wght@400;600&display=swap', [], null );
    wp_enqueue_script( 'er-react',     'https://cdnjs.cloudflare.com/ajax/libs/react/18.2.0/umd/react.production.min.js',     [], '18.2.0', false );
    wp_enqueue_script( 'er-react-dom', 'https://cdnjs.cloudflare.com/ajax/libs/react-dom/18.2.0/umd/react-dom.production.min.js', ['er-react'], '18.2.0', false );
    wp_enqueue_script( 'er-babel',     'https://cdnjs.cloudflare.com/ajax/libs/babel-standalone/7.23.5/babel.min.js', [], '7.23.5', false );
    wp_add_inline_script( 'er-react', 'window.ER = ' . json_encode([
        'ajax'      => admin_url('admin-ajax.php'),
        'nonce'     => wp_create_nonce('er_nonce'),
        'version'   => ER_VERSION,
        'checklist' => get_option('er_checklist', []),
        'siteurl'   => get_site_url(),
    ]) . ';' );
    wp_enqueue_style( 'er-css', ER_URL . 'assets/panel.css', [], ER_VERSION );
});

/* ── Render ── */
function er_render_page() { ?>
    <div id="er-root"></div>
    <script type="text/babel" data-presets="react" src="<?= esc_url( ER_URL . 'assets/panel.jsx' ) ?>"></script>
<?php }

/* ── AJAX: Promesas CRUD ── */
add_action( 'wp_ajax_er_get_promesas',    'er_get_promesas' );
add_action( 'wp_ajax_er_save_promesa',    'er_save_promesa' );
add_action( 'wp_ajax_er_update_estado',   'er_update_estado' );
add_action( 'wp_ajax_er_delete_promesa',  'er_delete_promesa' );
add_action( 'wp_ajax_er_export_promesas', 'er_export_promesas' );

function er_check() {
    check_ajax_referer('er_nonce','nonce');
    if ( ! current_user_can('manage_options') ) wp_send_json_error('Sin permiso',403);
}
function er_get_promesas() {
    er_check();
    global $wpdb;
    wp_send_json_success( $wpdb->get_results("SELECT * FROM {$wpdb->prefix}er_promesas ORDER BY id DESC") );
}
function er_save_promesa() {
    er_check();
    global $wpdb; $t = $wpdb->prefix.'er_promesas';
    $d = [
        'codigo'   => sanitize_text_field($_POST['codigo']??''),
        'promesa'  => sanitize_textarea_field($_POST['promesa']??''),
        'quien'    => sanitize_text_field($_POST['quien']??''),
        'fecha'    => sanitize_text_field($_POST['fecha']??''),
        'pilar'    => sanitize_text_field($_POST['pilar']??'P05'),
        'fuente'   => sanitize_text_field($_POST['fuente']??''),
        'evidencia'=> sanitize_textarea_field($_POST['evidencia']??''),
        'estado'   => sanitize_text_field($_POST['estado']??'Abierta'),
    ];
    if ( empty($d['promesa'])||empty($d['quien'])||empty($d['fecha']) ) wp_send_json_error('Campos vacíos');
    $id = intval($_POST['id']??0);
    if ($id) { $wpdb->update($t,$d,['id'=>$id]); wp_send_json_success(['id'=>$id]); }
    else { $wpdb->insert($t,$d); wp_send_json_success(['id'=>$wpdb->insert_id]); }
}
function er_update_estado() {
    er_check(); global $wpdb;
    $wpdb->update($wpdb->prefix.'er_promesas',
        ['estado'=>sanitize_text_field($_POST['estado'])],
        ['id'=>intval($_POST['id'])]
    );
    wp_send_json_success();
}
function er_delete_promesa() {
    er_check(); global $wpdb;
    $wpdb->delete($wpdb->prefix.'er_promesas',['id'=>intval($_POST['id'])]);
    wp_send_json_success();
}
function er_export_promesas() {
    er_check(); global $wpdb;
    $rows = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}er_promesas ORDER BY id DESC", ARRAY_A);
    $csv = "ID,Código,Promesa,Quién,Fecha,Pilar,Fuente,Estado\n";
    foreach ($rows as $r) {
        $csv .= implode(',', array_map(fn($v) => '"'.str_replace('"','""',$v).'"', [
            $r['id'],$r['codigo'],$r['promesa'],$r['quien'],$r['fecha'],$r['pilar'],$r['fuente'],$r['estado']
        ]))."\n";
    }
    wp_send_json_success(['csv'=>$csv]);
}

/* ── AJAX: Checklist ── */
add_action('wp_ajax_er_update_checklist','er_update_checklist');
function er_update_checklist() {
    er_check();
    $cl = get_option('er_checklist',[]);
    $key = sanitize_key($_POST['key']??'');
    $val = ($_POST['val']==='true');
    if ($key) { $cl[$key]=$val; update_option('er_checklist',$cl); }
    wp_send_json_success($cl);
}

/* ── AJAX: Proxy Claude API ── */
add_action('wp_ajax_er_claude_proxy','er_claude_proxy');
function er_claude_proxy() {
    er_check();
    $apikey = get_option('er_claude_apikey','');
    if ( ! $apikey ) { wp_send_json_error('API key no configurada. Configurala en el Dashboard.'); }

    // Leer payload JSON del campo POST 'payload' (enviado como string JSON)
    $payload_raw = $_POST['payload'] ?? '';
    $body = $payload_raw ? json_decode(wp_unslash($payload_raw), true) : null;

    // Fallback: construir desde campos individuales
    if ( ! $body || ! isset($body['messages']) ) {
        $messages_raw = $_POST['messages'] ?? '';
        $messages = $messages_raw ? json_decode(wp_unslash($messages_raw), true) : [];
        if ( empty($messages) ) {
            $content = sanitize_textarea_field($_POST['content'] ?? '');
            $messages = $content ? [['role'=>'user','content'=>$content]] : [];
        }
        $system = sanitize_textarea_field($_POST['system'] ?? '');
        $body = ['model'=>'claude-sonnet-4-20250514','max_tokens'=>1500,'messages'=>$messages];
        if ($system) $body['system'] = $system;
    }

    if ( empty($body['messages']) ) {
        wp_send_json_error('No se recibió ningún mensaje para enviar a Claude.');
    }

    $resp = wp_remote_post('https://api.anthropic.com/v1/messages',[
        'timeout' => 90,
        'headers' => [
            'Content-Type'      => 'application/json',
            'x-api-key'         => $apikey,
            'anthropic-version' => '2023-06-01',
        ],
        'body' => json_encode($body),
    ]);
    if ( is_wp_error($resp) ) wp_send_json_error($resp->get_error_message());
    $code = wp_remote_retrieve_response_code($resp);
    $data = json_decode(wp_remote_retrieve_body($resp), true);
    if ($code !== 200) {
        $err = $data['error']['message'] ?? "Error HTTP $code";
        wp_send_json_error($err);
    }
    wp_send_json_success($data);
}

/* ── AJAX: Guardar API key ── */
add_action('wp_ajax_er_save_apikey','er_save_apikey');
function er_save_apikey() {
    er_check();
    $key = sanitize_text_field($_POST['apikey'] ?? '');
    if (empty($key)) { wp_send_json_error('Clave vacía'); }
    update_option('er_claude_apikey', $key);
    wp_send_json_success(['masked' => substr($key, 0, 8) . '...' . substr($key, -4)]);
}

/* ── AJAX: Estado API key ── */
add_action('wp_ajax_er_apikey_status','er_apikey_status');
function er_apikey_status() {
    er_check();
    $key = get_option('er_claude_apikey','');
    if (empty($key)) {
        wp_send_json_success(['configured' => false]);
    } else {
        wp_send_json_success(['configured' => true, 'masked' => substr($key, 0, 8) . '...' . substr($key, -4)]);
    }
}

/* ── AJAX: Eliminar API key ── */
add_action('wp_ajax_er_delete_apikey','er_delete_apikey');
function er_delete_apikey() {
    er_check();
    delete_option('er_claude_apikey');
    wp_send_json_success();
}

/* ── AJAX: Importar notas demo ── */
add_action('wp_ajax_er_import_demo','er_import_demo');
function er_import_demo() {
    er_check();
    if ( get_option('er_demo_imported') ) { wp_send_json_error('Las notas demo ya fueron importadas.'); }

    $notas = er_get_demo_notas();
    $created = 0;
    foreach ($notas as $nota) {
        $cat = get_term_by('slug', $nota['cat_slug'], 'category');
        $cat_id = $cat ? [$cat->term_id] : [];
        $pid = wp_insert_post([
            'post_title'   => wp_strip_all_tags($nota['titulo']),
            'post_content' => $nota['contenido'],
            'post_excerpt' => $nota['bajada'],
            'post_status'  => 'draft',
            'post_category'=> $cat_id,
            'meta_input'   => [
                '_er_pilar'     => $nota['pilar'],
                '_er_img_url'   => $nota['img'],
                '_er_demo'      => '1',
                '_er_fuente'    => $nota['fuente'],
            ],
        ]);
        if ($pid && !is_wp_error($pid) && !empty($nota['img'])) {
            update_post_meta($pid,'_er_featured_url',$nota['img']);
        }
        if ($pid && !is_wp_error($pid)) $created++;
    }
    update_option('er_demo_imported', true);
    update_option('er_demo_count', $created);
    // marcar checklist notas
    $cl = get_option('er_checklist',[]);
    $cl['notas'] = true;
    update_option('er_checklist',$cl);
    wp_send_json_success(['created'=>$created]);
}

/* ── NOTAS DEMO — 48 borradores (8 × 6 pilares) ── */
function er_get_demo_notas() {
    return [
        // ── P01 Lo que pasa ──
        ['pilar'=>'P01','cat_slug'=>'lo-que-pasa','fuente'=>'Radio SOL FM / canal8rufino.com.ar',
         'img'=>'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=780&h=440&fit=crop',
         'titulo'=>'Rufino lanzó el Plan de Pavimentación 2026 — y los vecinos todavía esperan saber cuánto van a pagar',
         'bajada'=>'El intendente Lattanzi anunció 50 cuadras de asfalto. Los trabajos comenzaron en Ayacucho y Moreno, pero el detalle de costos para los frentistas sigue siendo la pregunta sin respuesta.',
         'contenido'=>'<p>El intendente de Rufino, Natalio Lattanzi, confirmó en febrero de 2026 el inicio del Plan de Pavimentación más ambicioso de los últimos años: 50 cuadras proyectadas, con la primera etapa en las calles Ayacucho y Moreno.</p><p>En la reunión con vecinos, el municipio detalló que existe posibilidad de iniciar los trabajos antes del pago de la primera cuota. Sin embargo, los costos exactos para los frentistas no fueron precisados públicamente.</p><p><strong>Lo que significa:</strong> Rufino tiene una deuda histórica con el asfalto. Vecinos de varios barrios esperan desde hace más de 50 años. El plan existe, los trabajos arrancaron — pero sin transparencia en los números, la obra puede convertirse en una fuente de conflicto antes de terminarse.</p><p><em>Fuente: Radio SOL FM / canal8rufino.com.ar — Febrero 2026</em></p>'],

        ['pilar'=>'P01','cat_slug'=>'lo-que-pasa','fuente'=>'canal8rufino.com.ar',
         'img'=>'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=780&h=440&fit=crop',
         'titulo'=>'Las obras no paran en calle Garín — pero el cronograma completo sigue sin publicarse',
         'bajada'=>'El 23 de marzo comenzó la pavimentación en Fernando Garín entre Rosa Boussy y Ayacucho. La Municipalidad habla de un "plan estratégico" pero los vecinos de otras zonas no saben cuándo les toca.',
         'contenido'=>'<p>El lunes 23 de marzo, cuadrillas municipales iniciaron trabajos de asfalto en calle Fernando Garín, en el tramo comprendido entre Rosa Boussy y Ayacucho. Maquinaria pesada trabajó desde las primeras horas del día.</p><p>Desde el Ejecutivo local reafirmaron que "las obras no paran" y que el ritmo se mantendrá durante toda la semana, siempre que las condiciones climáticas lo permitan.</p><p><strong>Lo que significa:</strong> El avance es real y visible. Pero Rufino tiene decenas de cuadras sin asfaltar y los vecinos de barrios periféricos necesitan saber si están en el cronograma — y en qué posición. La transparencia del plan es tan importante como el plan mismo.</p><p><em>Fuente: canal8rufino.com.ar — 23 marzo 2026</em></p>'],

        ['pilar'=>'P01','cat_slug'=>'lo-que-pasa','fuente'=>'Ministerio Público de la Acusación / sur24.com.ar',
         'img'=>'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?w=780&h=440&fit=crop',
         'titulo'=>'Femicidio en Rufino: un adolescente detenido y la ciudad que no puede mirar para otro lado',
         'bajada'=>'El 31 de marzo fue encontrada muerta una joven de 17 años en una vivienda de Eva Perón al 1100. Su pareja, también menor, fue detenido en Trenque Lauquen. La causa se investiga como femicidio.',
         'contenido'=>'<p>El martes 31 de marzo, una adolescente de 17 años fue encontrada muerta en una vivienda precaria ubicada en calle Eva Perón al 1100 de Rufino. En un primer momento la causa se investigó como presunto suicidio.</p><p>Dos días después, el joven investigado — también de 17 años y pareja de la víctima — fue detenido en Trenque Lauquen, provincia de Buenos Aires. Trasladado a Santa Fe, la fiscal Marianela Montemarani le imputó homicidio calificado: femicidio.</p><p>El 8 de abril el juez dispuso su alojamiento en un instituto de menores de puertas cerradas por 90 días.</p><p><strong>Lo que significa:</strong> Rufino no es ajena a la violencia de género. Este caso, que involucra a dos adolescentes, pone en evidencia la necesidad de políticas concretas de prevención y acompañamiento. La ciudad tiene que poder hablar de esto.</p><p><em>Fuente: MPA / sur24.com.ar / conclusión.com.ar — Abril 2026</em></p>'],

        ['pilar'=>'P01','cat_slug'=>'lo-que-pasa','fuente'=>'canal8rufino.com.ar',
         'img'=>'https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?w=780&h=440&fit=crop',
         'titulo'=>'Reclamos policiales en Rufino: trabajadores y jubilados se manifestaron en Plaza Sarmiento',
         'bajada'=>'La ola de protestas del personal policial que recorrió la provincia llegó a Rufino. Trabajadores y jubilados de la fuerza se concentraron en la plaza principal para exigir mejoras salariales.',
         'contenido'=>'<p>En el marco de la ola de reclamos que protagonizó el personal policial en distintas ciudades de Santa Fe, Rufino tuvo su propia concentración en Plaza Sarmiento. Trabajadores activos y jubilados de la fuerza reclamaron mejoras salariales y condiciones laborales.</p><p>El conflicto se enmarca en la negociación paritaria provincial, donde los gremios policiales cuestionaron los incrementos ofrecidos por el gobierno de Maximiliano Pullaro.</p><p><strong>Lo que significa:</strong> La seguridad pública es una de las principales preocupaciones de los rufinenses. Cuando el personal que debería garantizarla sale a reclamar, la pregunta que queda es: ¿quién negocia, cuándo y con qué resultado?</p><p><em>Fuente: canal8rufino.com.ar — Marzo 2026</em></p>'],

        ['pilar'=>'P01','cat_slug'=>'lo-que-pasa','fuente'=>'santafe.gob.ar',
         'img'=>'https://images.unsplash.com/photo-1543286386-713bdd548da4?w=780&h=440&fit=crop',
         'titulo'=>'La provincia inauguró obras en General López — Rufino mira y espera',
         'bajada'=>'Pullaro encabezó la inauguración de 26 km de ruta 14 repavimentada y la pista de atletismo de Venado Tuerto. El departamento avanza, pero Rufino quiere saber cuándo llega su turno.',
         'contenido'=>'<p>El gobernador Maximiliano Pullaro encabezó en el departamento General López la inauguración de la repavimentación de 26 kilómetros de la ruta Provincial N°14, hasta la intersección con la ruta Nacional 7, y la nueva pista de atletismo "Yolanda Cantoni" en el Parque Municipal de Venado Tuerto.</p><p>También quedó habilitado el sistema de desagües cloacales de Diego de Alvear. Pullaro destacó que "cuando no se roba en un Estado la plata alcanza" y remarcó que la obra pública provincial se triplicó.</p><p><strong>Lo que significa:</strong> Venado Tuerto, Diego de Alvear y San Gregorio suman infraestructura. Rufino tiene sus propios pendientes: obras viales, acceso sur, infraestructura barrial. La pregunta que los rufinenses se hacen es concreta: ¿cuándo viene la provincia acá?</p><p><em>Fuente: santafe.gob.ar — Abril 2026</em></p>'],

        ['pilar'=>'P01','cat_slug'=>'lo-que-pasa','fuente'=>'rufinoweb.com.ar',
         'img'=>'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=780&h=440&fit=crop',
         'titulo'=>'Importantes lluvias en Rufino — el campo respira pero las calles de tierra vuelven a quedar intransitables',
         'bajada'=>'Las precipitaciones registradas durante la noche del viernes y la mañana del sábado aportaron agua necesaria para los cultivos, pero dejaron en evidencia el estado de los barrios sin pavimento.',
         'contenido'=>'<p>Importantes lluvias se registraron en Rufino y la región durante la noche del viernes, continuando por la mañana del sábado. El registro acumulado fue significativo para la campaña agrícola, que venía con necesidad hídrica en algunos lotes.</p><p>Sin embargo, las mismas lluvias dejaron al descubierto una realidad que los vecinos de barrios periféricos conocen de sobra: las calles de tierra se vuelven intransitables con pocas horas de lluvia.</p><p><strong>Lo que significa:</strong> Lo que es buena noticia para el campo puede ser mala noticia para el barrio. Cada lluvia es un recordatorio de que el Plan de Pavimentación 2026 no llega a todos al mismo tiempo — y que mientras tanto, hay rufinenses que no pueden salir de sus casas.</p><p><em>Fuente: rufinoweb.com.ar — Marzo 2026</em></p>'],

        ['pilar'=>'P01','cat_slug'=>'lo-que-pasa','fuente'=>'canal8rufino.com.ar / RENATRE',
         'img'=>'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=780&h=440&fit=crop',
         'titulo'=>'RENATRE habilitó plan de facilidades de pago — qué significa para los trabajadores rurales de la región',
         'bajada'=>'La delegación Santa Fe Sur del RENATRE informó que el organismo nacional aprobó un plan de cuotas para empleadores con deuda. Los trabajadores rurales del sur santafesino son los principales beneficiados.',
         'contenido'=>'<p>La delegación Santa Fe Sur del Registro Nacional de Trabajadores y Empleadores Agrarios (RENATRE) comunicó que el organismo nacional aprobó un Plan de Facilidades de Pago para empleadores con deudas pendientes.</p><p>El sur de Santa Fe — con Rufino como nodo central de la región — concentra una importante cantidad de trabajadores rurales que dependen de la regularización de sus empleadores para acceder a prestaciones sociales.</p><p><strong>Lo que significa:</strong> El trabajo rural en General López es estructural. Pero la informalidad también lo es. Cada plan de regularización es una oportunidad — el seguimiento de cuántos empleadores se adhieren y cuántos trabajadores quedan cubiertos es información que El Rufino va a rastrear.</p><p><em>Fuente: rufinoweb.com.ar / RENATRE — 2026</em></p>'],

        ['pilar'=>'P01','cat_slug'=>'lo-que-pasa','fuente'=>'puebloregional.com.ar',
         'img'=>'https://images.unsplash.com/photo-1540910419892-4a36d2c3266c?w=780&h=440&fit=crop',
         'titulo'=>'Resultados electorales en el Departamento General López — cómo votó Rufino en junio 2025',
         'bajada'=>'Con el 100% de las mesas escrutadas, Unidos Para Cambiar Santa Fe se impuso en la mayoría de las localidades del departamento. En Rufino, los números definitivos marcaron el mapa político para los próximos años.',
         'contenido'=>'<p>Con el 100% de las mesas escrutadas en las elecciones de junio de 2025, el Departamento General López definió su mapa político. Unidos Para Cambiar Santa Fe, la fuerza del gobernador Maximiliano Pullaro, se impuso en la mayoría de las localidades del departamento.</p><p>En Amenábar, por ejemplo, la fórmula de UPCSF obtuvo el 73,22% contra el 26,78% de Más Para Santa Fe. El patrón se repitió en distintas proporciones a lo largo del departamento.</p><p><strong>Lo que significa:</strong> Los resultados de 2025 definieron qué fuerzas controlan los recursos políticos en la región durante los próximos años. Entender ese mapa es entender quién toma decisiones sobre obras, presupuesto y servicios en Rufino.</p><p><em>Fuente: puebloregional.com.ar — Junio 2025 / Febrero 2026</em></p>'],

        // ── P02 El campo habla ──
        ['pilar'=>'P02','cat_slug'=>'el-campo-habla','fuente'=>'infocampo.com.ar',
         'img'=>'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=780&h=440&fit=crop',
         'titulo'=>'La soja llegó a $440.000 la tonelada en Rosario — qué significa para el productor de General López',
         'bajada'=>'Al 31 de marzo de 2026, la cotización de la soja en el mercado de Rosario se fijó en $440.000 por tonelada. Para el campo rufinense, el precio tiene dos caras: ingresos más altos, pero costos que también corrieron.',
         'contenido'=>'<p>Al 31 de marzo de 2026, el precio de la soja en el mercado de Rosario se estableció en $440.000 por tonelada. El valor refleja una combinación de factores: tensiones geopolíticas globales, demanda sostenida de China y el impacto del conflicto en el estrecho de Ormuz sobre los precios de la energía.</p><p>Para los productores del sur de Santa Fe, el número es relevante. General López es uno de los departamentos con mayor producción sojera de la provincia.</p><p><strong>Lo que significa:</strong> Precio alto no equivale automáticamente a rentabilidad. Con costos de cosecha que según FACMA superan los $138.000 por hectárea y arrendamientos que consumen entre 18 y 20 quintales por hectárea, el margen real depende de los rindes que logre cada lote. El campo rufinense sabe que los números buenos en Rosario no siempre llegan enteros al bolsillo.</p><p><em>Fuente: infocampo.com.ar — 31 marzo 2026</em></p>'],

        ['pilar'=>'P02','cat_slug'=>'el-campo-habla','fuente'=>'FACMA / rosario3.com',
         'img'=>'https://images.unsplash.com/photo-1574943320219-553eb213f72d?w=780&h=440&fit=crop',
         'titulo'=>'Cosechar soja en 2026 cuesta hasta US$ 150 por hectárea — y eso es solo el servicio de maquinaria',
         'bajada'=>'La Federación Argentina de Contratistas de Maquinaria Agrícola actualizó sus tarifas orientativas. Para un rinde base de 24 quintales, el costo operativo total supera los $138.000 por hectárea.',
         'contenido'=>'<p>La Federación Argentina de Contratistas de Maquinaria Agrícola (FACMA) publicó sus nuevos precios orientativos para la cosecha 2025/26. Para un rinde base de 24 quintales por hectárea, el precio orientativo se ubica en $138.949 por hectárea, equivalente a aproximadamente US$ 97.</p><p>El principal componente es el costo de propiedad — amortización e intereses de maquinaria — que representa el 37% del total. Le siguen personal (17%), combustible (13%) y conservación (10%).</p><p><strong>Lo que significa:</strong> En el campo de General López, donde muchos productores alquilan tierra a 18-20 quintales por hectárea, la ecuación se ajusta mucho. Con rindes de 24 qq/ha en campo alquilado, la rentabilidad es prácticamente nula. La apuesta está en llegar a 40 qq/ha o más.</p><p><em>Fuente: FACMA / rosario3.com — Febrero 2026</em></p>'],

        ['pilar'=>'P02','cat_slug'=>'el-campo-habla','fuente'=>'Bolsa de Comercio de Rosario / bichosdecampo.com',
         'img'=>'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=780&h=440&fit=crop',
         'titulo'=>'El precio de la soja resistió la presión bajista — pero Brasil y Trump siguen siendo la variable que el campo mira',
         'bajada'=>'Los futuros de soja Rosario Mayo 2026 cerraron en torno a US$ 317 la tonelada. La cosecha récord brasileña de hasta 182 millones de toneladas presiona a la baja, pero el aceite y la harina sostienen los valores.',
         'contenido'=>'<p>El contrato Soja Rosario Mayo 2026 en el mercado de futuros terminó en un valor promedio ponderado de 317 dólares por tonelada. Desde la Bolsa de Comercio de Rosario señalaron que el precio no se derrumbó gracias a la firmeza de los valores de la harina y el aceite de soja.</p><p>Brasil proyecta una cosecha de entre 176 y 182 millones de toneladas, lo que genera presión bajista sobre el poroto. Sin embargo, las tensiones geopolíticas — especialmente el conflicto en el estrecho de Ormuz — mantienen los precios de la energía elevados y sostienen los valores de las oleaginosas.</p><p><strong>Lo que significa:</strong> El campo rufinense está atado a variables que se deciden en Chicago, Brasilia y Washington. Entenderlas no es un lujo — es parte del trabajo de cualquier productor que quiera planificar la campaña que viene.</p><p><em>Fuente: BCR / bichosdecampo.com — Febrero 2026</em></p>'],

        ['pilar'=>'P02','cat_slug'=>'el-campo-habla','fuente'=>'rufinoweb.com.ar / INTA',
         'img'=>'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=780&h=440&fit=crop',
         'titulo'=>'Las lluvias de marzo recargaron los lotes — pero el yuyo colorado ya apareció antes de lo esperado',
         'bajada'=>'Las precipitaciones de marzo aportaron humedad necesaria para los cultivos de verano tardío. El problema: las lluvias de invierno adelantaron al menos 15 días la aparición de malezas resistentes.',
         'contenido'=>'<p>Las lluvias registradas en Rufino y la región durante marzo de 2026 recargaron los perfiles de suelo y aportaron la humedad necesaria para los cultivos en etapas finales del ciclo productivo.</p><p>Sin embargo, técnicos del sector advierten que las lluvias de invierno adelantaron la aparición del yuyo colorado — una de las malezas más problemáticas para la soja — en al menos quince días respecto a años anteriores. Esto obliga a sumar aplicaciones de herbicidas, con un costo adicional de entre 20 y 40 dólares por hectárea.</p><p><strong>Lo que significa:</strong> El clima da y el clima quita. Para el productor de General López, una campaña con buena humedad pero con mayor presión de malezas es una campaña que requiere más inversión y más decisiones técnicas en tiempo real.</p><p><em>Fuente: rufinoweb.com.ar / BCR — Marzo 2026</em></p>'],

        ['pilar'=>'P02','cat_slug'=>'el-campo-habla','fuente'=>'ISP N°19 Rufino',
         'img'=>'https://images.unsplash.com/photo-1464226184884-fa280b87c399?w=780&h=440&fit=crop',
         'titulo'=>'El ISP N°19 de Rufino abrió en 2026 la carrera de Técnico Superior en Alimentos — y el campo tiene algo que ver',
         'bajada'=>'El Instituto Superior de Profesorado N°19 incorporó para 2026 la tecnicatura en alimentos. En una región donde la producción primaria es el corazón de la economía, agregar valor local es una oportunidad concreta.',
         'contenido'=>'<p>El Instituto Superior de Profesorado N°19 de Rufino abrió en 2026 el primer año de la carrera de Técnico Superior en Alimentos, sumándola a la oferta existente de profesorados de nivel primario y secundario.</p><p>La decisión no es casual. Rufino y el departamento General López producen enormes volúmenes de materias primas agropecuarias, pero el valor agregado se genera mayormente fuera de la región.</p><p><strong>Lo que significa:</strong> Formar técnicos en alimentos en Rufino es una apuesta a romper esa lógica. Si la región logra procesar aunque sea una fracción de lo que produce, la economía local cambia de escala. El ISP abrió una puerta — la pregunta es si el sector productivo la va a aprovechar.</p><p><em>Fuente: ISP N°19 Rufino — 2026</em></p>'],

        ['pilar'=>'P02','cat_slug'=>'el-campo-habla','fuente'=>'santafe.gob.ar',
         'img'=>'https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=780&h=440&fit=crop',
         'titulo'=>'Provincia repavimentó 26 km de ruta 14 — el corredor productivo del sur santafesino gana conectividad',
         'bajada'=>'La ruta Provincial N°14, eje del movimiento de cargas entre localidades del sur de Santa Fe, tuvo 26 kilómetros repavimentados. Para el campo de General López, mejor ruta significa menores costos de flete.',
         'contenido'=>'<p>El gobernador Pullaro encabezó la inauguración de la repavimentación de 26 kilómetros de la ruta Provincial N°14, hasta la intersección con la ruta Nacional 7. La senadora Leticia Di Gregorio agradeció la obra calificándola como "una necesidad de muchos años".</p><p>Los presidentes comunales de Diego de Alvear, San Gregorio y Christophersen destacaron que las mejoras significan más seguridad, mejor conectividad y más desarrollo para las comunidades rurales del sur del departamento.</p><p><strong>Lo que significa:</strong> El flete es uno de los costos más pesados para el productor del sur provincial. Cada kilómetro de ruta en buen estado es un ahorro real. La ruta 14 conecta localidades pequeñas con los centros de acopio y comercialización — su estado impacta directamente en los márgenes del campo.</p><p><em>Fuente: santafe.gob.ar — Abril 2026</em></p>'],

        ['pilar'=>'P02','cat_slug'=>'el-campo-habla','fuente'=>'INDEC / infocampo.com.ar',
         'img'=>'https://images.unsplash.com/photo-1560493676-04071c5f467b?w=780&h=440&fit=crop',
         'titulo'=>'El sector privado no registrado fue el único salario que le ganó a la inflación de enero 2026',
         'bajada'=>'Según datos del INDEC, en enero de 2026 el salario del sector privado no registrado fue el único que superó la inflación del mes. En el campo de General López, donde la informalidad laboral es alta, el dato tiene peso.',
         'contenido'=>'<p>El INDEC informó que en enero de 2026 el sector privado no registrado fue el único segmento salarial que logró superar la inflación del mes. Los salarios registrados — tanto privados como públicos — quedaron por debajo del índice de precios.</p><p>En el departamento General López, donde el trabajo rural informal tiene una presencia significativa, el dato tiene una lectura particular. Los trabajadores no registrados del campo no cuentan con los beneficios de los convenios colectivos pero sí pudieron actualizar sus ingresos por negociación directa.</p><p><strong>Lo que significa:</strong> La informalidad laboral en el agro es un problema estructural que el RENATRE intenta abordar. Que los salarios informales le ganen a la inflación no justifica la informalidad — revela que el mercado laboral rural opera con una lógica propia que merece atención.</p><p><em>Fuente: INDEC / sucesosrufino.com.ar — Enero 2026</em></p>'],

        ['pilar'=>'P02','cat_slug'=>'el-campo-habla','fuente'=>'Bolsa de Comercio de Rosario',
         'img'=>'https://images.unsplash.com/photo-1473973266408-ed4e27abdd47?w=780&h=440&fit=crop',
         'titulo'=>'Campaña soja 2025/26: arrancó con expectativas de 40 qq/ha — cómo viene el sur de Santa Fe',
         'bajada'=>'La siembra arrancó con el 75% de los lotes con agua adecuada. La apuesta del sector es superar los 40 quintales por hectárea. En campo propio, eso significa una renta de más de 385 dólares por hectárea.',
         'contenido'=>'<p>Según la Bolsa de Comercio de Rosario, la campaña soja 2025/26 arrancó la siembra con el 75% de los lotes con niveles de agua adecuados. La rentabilidad neta en campo propio se ubica en 385 dólares por hectárea para un rinde objetivo de 40 qq/ha.</p><p>En campo alquilado — la realidad de muchos productores del sur provincial — el escenario cambia: con un costo de arrendamiento de 18 qq/ha, la renta cae a terreno negativo con rindes de 40 qq/ha. Para ser rentable en campo alquilado se necesitan al menos 41 qq/ha.</p><p><strong>Lo que significa:</strong> El campo de General López juega en los márgenes. Quien tiene tierra propia tiene oxígeno. Quien arrienda necesita tecnología, clima y mercado a favor al mismo tiempo. La campaña 2025/26 es una prueba de qué tan resiliente es el modelo productivo regional.</p><p><em>Fuente: Bolsa de Comercio de Rosario — Octubre 2025 / actualización 2026</em></p>'],

        // ── P03 Barrio a barrio ──
        ['pilar'=>'P03','cat_slug'=>'barrio-a-barrio','fuente'=>'canal8rufino.com.ar',
         'img'=>'https://images.unsplash.com/photo-1601459427108-47e20d579a35?w=780&h=440&fit=crop',
         'titulo'=>'Calle Fernando Garín: los vecinos esperaban el asfalto hace años — llegó, pero con cortes de tránsito',
         'bajada'=>'Las obras en Fernando Garín entre Rosa Boussy y Ayacucho transforman la zona. Mientras duran los trabajos, el municipio pide circular con cuidado. Los frentistas calculan cuánto van a pagar.',
         'contenido'=>'<p>La calle Fernando Garín, en el tramo comprendido entre Rosa Boussy y Ayacucho, fue el foco de los trabajos de pavimentación del 23 de marzo. Cuadrillas municipales y maquinaria pesada avanzaron desde las primeras horas del día.</p><p>Los vecinos del sector reciben la obra con alivio — la tierra se volvía intransitable con cada lluvia — pero también con preguntas sobre los costos que deberán afrontar como frentistas.</p><p><strong>Lo que significa:</strong> Cada cuadra pavimentada en Rufino tiene una historia de espera detrás. Garín no es la excepción. La obra es un avance real y concreto — el seguimiento está en saber si los plazos y los costos se cumplen como se prometió en la reunión de vecinos de febrero.</p><p><em>Fuente: canal8rufino.com.ar — 23 marzo 2026</em></p>'],

        ['pilar'=>'P03','cat_slug'=>'barrio-a-barrio','fuente'=>'Municipalidad de Rufino',
         'img'=>'https://images.unsplash.com/photo-1545558014-8692077e9b5c?w=780&h=440&fit=crop',
         'titulo'=>'Parque Balneario Municipal: 500 chicos en la Escuela de Verano y obras que avanzan',
         'bajada'=>'El Parque Balneario Municipal fue sede de la Escuela de Verano con 500 participantes. Al mismo tiempo, la provincia avanza con obras de infraestructura en el espacio verde más importante de la ciudad.',
         'contenido'=>'<p>El Parque Balneario Municipal Ángel Bulgheroni fue escenario de la Escuela de Verano, con la participación de 500 niños y niñas de Rufino. La actividad se desarrolló mientras la provincia llevaba adelante obras de mejora en las instalaciones del predio.</p><p>El gobernador Pullaro visitó el parque en enero de 2026 durante una recorrida por obras del departamento General López.</p><p><strong>Lo que significa:</strong> El parque balneario es el pulmón verde de Rufino y el espacio de encuentro de distintos barrios. Que tenga 500 chicos en verano dice algo de su vitalidad. Lo que dice sobre el estado de las instalaciones y el mantenimiento que necesita también merece seguimiento.</p><p><em>Fuente: santafe.gob.ar / rufinoweb.com.ar — Enero 2026</em></p>'],

        ['pilar'=>'P03','cat_slug'=>'barrio-a-barrio','fuente'=>'rufinoweb.com.ar',
         'img'=>'https://images.unsplash.com/photo-1558618047-f4e80e5af85c?w=780&h=440&fit=crop',
         'titulo'=>'Aguas Santafesinas trabaja en la red de Rufino — cortes programados que los vecinos necesitan saber',
         'bajada'=>'La empresa realizó mantenimiento de cañerías en distintas zonas de la ciudad. Los trabajos de purgado de la red generaron la salida de agua por hidrantes, lo que puede confundir a los vecinos.',
         'contenido'=>'<p>Aguas Santafesinas informó a los usuarios de Rufino que continúa trabajando en el mantenimiento programado de cañerías de agua potable en diferentes zonas de la ciudad. Las tareas consisten en el purgado sectorizado de las redes para eliminar obstrucciones.</p><p>Durante las tareas es posible ver agua saliendo de bocas de hidrantes — una imagen que puede generar alarma en vecinos que no fueron previamente informados.</p><p><strong>Lo que significa:</strong> El servicio de agua potable es un derecho básico. Rufino no tiene problemas de provisión generalizados, pero el mantenimiento preventivo de la red es lo que garantiza que así siga siendo. La comunicación previa a los vecinos es parte del servicio — y cuando no llega, genera conflictos innecesarios.</p><p><em>Fuente: rufinoweb.com.ar — 2026</em></p>'],

        ['pilar'=>'P03','cat_slug'=>'barrio-a-barrio','fuente'=>'rufinoweb.com.ar / Gestiones Enrico',
         'img'=>'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=780&h=440&fit=crop',
         'titulo'=>'Centro de Salud Bulgheroni: gestiones para refaccionarlo avanzan — pero el barrio sigue esperando',
         'bajada'=>'Gestiones realizadas ante el gobierno provincial y la municipalidad buscan fondos para refaccionar el Centro de Desarrollo Integral para la Familia y la Mujer (Cedeifam) de Rufino. El barrio espera obras concretas.',
         'contenido'=>'<p>El Centro de Salud Ángel Bulgheroni de Rufino — un espacio clave para el acompañamiento social, la promoción de derechos y la prevención de la violencia de género — es objeto de gestiones para obtener fondos de refacción ante el gobierno provincial y la municipalidad.</p><p>La Provincia invirtió en la ampliación del Cedeifam como espacio de prevención de violencia de género. Sin embargo, las instalaciones del Bulgheroni en Rufino requieren mejoras que todavía no tienen fecha concreta de inicio.</p><p><strong>Lo que significa:</strong> El Bulgheroni atiende a vecinos de los barrios más vulnerables de Rufino. Un centro de salud con infraestructura deteriorada no puede dar el servicio que esos vecinos necesitan. Las gestiones son un primer paso — el seguimiento es cuándo se convierten en obra.</p><p><em>Fuente: rufinoweb.com.ar — 2026</em></p>'],

        ['pilar'=>'P03','cat_slug'=>'barrio-a-barrio','fuente'=>'Municipalidad de Rufino',
         'img'=>'https://images.unsplash.com/photo-1510146758428-e5e4b17b8b6a?w=780&h=440&fit=crop',
         'titulo'=>'Castraciones en los barrios: el municipio lleva el quirófano móvil a Plaza Rawson',
         'bajada'=>'La Municipalidad de Rufino instaló el quirófano móvil en Plaza Rawson para realizar castraciones de mascotas. La iniciativa busca el control responsable de la población animal en los barrios.',
         'contenido'=>'<p>El municipio de Rufino instaló su quirófano móvil en Plaza Rawson para realizar jornadas de castración de mascotas en el barrio. En la jornada se colocaron 35 vacunas antirrábicas y se realizaron intervenciones quirúrgicas.</p><p>La iniciativa forma parte de un programa de tenencia responsable de animales que la gestión Lattanzi viene implementando desde principios de año.</p><p><strong>Lo que significa:</strong> El control de la población animal callejera es un tema que aparece permanentemente en los reclamos vecinales de Rufino. Llevar el quirófano al barrio en lugar de esperar que los vecinos vayan al municipio es un modelo de gestión que vale la pena seguir de cerca — y replicar en más zonas de la ciudad.</p><p><em>Fuente: Municipalidad de Rufino / rufinoweb.com.ar — 2026</em></p>'],

        ['pilar'=>'P03','cat_slug'=>'barrio-a-barrio','fuente'=>'santafe.gob.ar',
         'img'=>'https://images.unsplash.com/photo-1579154204601-01588f351e67?w=780&h=440&fit=crop',
         'titulo'=>'Diego de Alvear estrena cloacas — el modelo de gestión provincial que Rufino puede reclamar',
         'bajada'=>'La habilitación del sistema de desagües cloacales de Diego de Alvear fue parte del paquete de obras inauguradas por Pullaro en el departamento. Rufino tiene barrios sin cloacas — el caso de Alvear muestra que es posible.',
         'contenido'=>'<p>El gobernador Pullaro dejó habilitado el sistema de desagües cloacales de la comuna de Diego de Alvear durante su visita al departamento General López en abril de 2026. La obra fue celebrada por el presidente comunal Pablo Sosa.</p><p>Diego de Alvear es una localidad de menor tamaño que Rufino. Sin embargo, logró la inversión provincial en infraestructura cloacal — un servicio que varios barrios de Rufino todavía no tienen.</p><p><strong>Lo que significa:</strong> Las cloacas no son un lujo. Son salud pública. Rufino tiene sectores sin red cloacal que llevan años reclamándola. El caso de Diego de Alvear demuestra que la provincia puede y actúa cuando hay gestión. La pregunta es qué está haciendo Rufino para estar en la lista.</p><p><em>Fuente: santafe.gob.ar — Abril 2026</em></p>'],

        ['pilar'=>'P03','cat_slug'=>'barrio-a-barrio','fuente'=>'Municipalidad de Rufino',
         'img'=>'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=780&h=440&fit=crop',
         'titulo'=>'API digitalizó trámites: ahora se puede pagar desde el celular — pero no todos los vecinos lo saben',
         'bajada'=>'La Administración Provincial de Impuestos simplificó y digitalizó trámites que antes requerían presencia en oficinas. Para los rufinenses, significa menos viajes a Venado Tuerto o Santa Fe.',
         'contenido'=>'<p>La Administración Provincial de Impuestos (API) digitalizó y simplificó trámites que previamente requerían gestión presencial. Los contribuyentes de Rufino — alejados de las sedes provinciales — son de los más beneficiados por esta medida.</p><p>Los trámites ahora disponibles online incluyen consultas de deuda, pagos y algunos certificados tributarios.</p><p><strong>Lo que significa:</strong> La digitalización del Estado tiene un valor concreto para las ciudades del interior. Cada trámite que se puede hacer desde Rufino sin viajar a Santa Fe o Venado Tuerto es tiempo y plata ahorrada. El desafío es que los vecinos lo sepan y puedan usarlo — no todos tienen acceso digital ni manejo de plataformas.</p><p><em>Fuente: rufinoweb.com.ar / API — 2026</em></p>'],

        ['pilar'=>'P03','cat_slug'=>'barrio-a-barrio','fuente'=>'Municipalidad de Rufino / rufinoweb.com.ar',
         'img'=>'https://images.unsplash.com/photo-1571902943202-507ec2618e8f?w=780&h=440&fit=crop',
         'titulo'=>'Nuevos cestos en el Parque Balneario — una mejora pequeña que hace diferencia en el espacio público',
         'bajada'=>'El municipio instaló nuevos cestos de residuos en el Parque Balneario Municipal. La intervención es parte de un plan de mantenimiento y mejora del espacio verde más visitado de Rufino.',
         'contenido'=>'<p>La Municipalidad de Rufino instaló nuevos cestos de residuos en todo el predio del Parque Balneario Municipal. La mejora se enmarca en un plan de mantenimiento del espacio verde más importante de la ciudad.</p><p>El parque es el punto de encuentro de vecinos de distintos barrios de Rufino, especialmente durante el verano, cuando las piletas municipales concentran gran parte de la actividad recreativa.</p><p><strong>Lo que significa:</strong> Las mejoras pequeñas y concretas en los espacios públicos son las que más impactan en la vida cotidiana. Un parque limpio, con infraestructura adecuada, es una señal del tipo de ciudad que Rufino quiere ser. El seguimiento está en que el mantenimiento sea sostenido y no solo estético.</p><p><em>Fuente: rufinoweb.com.ar — 2026</em></p>'],

        // ── P04 Generación Rufino ──
        ['pilar'=>'P04','cat_slug'=>'generacion-rufino','fuente'=>'ISP N°19 / isp19-sfe.infd.edu.ar',
         'img'=>'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=780&h=440&fit=crop',
         'titulo'=>'El ISP N°19 abrió 4 carreras nuevas en 2026 — estudiar en Rufino ya no es solo una opción secundaria',
         'bajada'=>'Para 2026 el Instituto Superior de Profesorado N°19 abre el 1° año de Técnico en Alimentos, Profesorado de Primaria, y Profesorados de Biología e Historia. Sin salir de Rufino.',
         'contenido'=>'<p>El Instituto Superior de Profesorado N°19 de Rufino abre en 2026 cuatro carreras de primer año: Técnico Superior en Alimentos, Profesorado de Educación Primaria, Profesorado de Educación Secundaria en Biología y Profesorado de Educación Secundaria en Historia.</p><p>El ISP19 funciona en la esquina de Av. Presidente Perón y Chacabuco, compartiendo edificio con la Escuela N°6031 de Jornada Completa. Su característica es la oferta de carreras cíclicas para no saturar el mercado laboral local.</p><p><strong>Lo que significa:</strong> Poder estudiar en Rufino sin mudarse a Rosario, Santa Fe o Buenos Aires cambia la ecuación para muchos jóvenes de la ciudad y la región. El ISP19 lleva décadas siendo esa opción — lo que cambia en 2026 es que amplía la oferta hacia el sector productivo con la tecnicatura en alimentos.</p><p><em>Fuente: ISP N°19 Rufino — 2026</em></p>'],

        ['pilar'=>'P04','cat_slug'=>'generacion-rufino','fuente'=>'canal8rufino.com.ar',
         'img'=>'https://images.unsplash.com/photo-1508739773434-c26b3d09e071?w=780&h=440&fit=crop',
         'titulo'=>'El SAMCO de Rufino renovó sus autoridades — y abrió las puertas a instituciones para participar',
         'bajada'=>'El 26 de marzo el hospital SAMCO llamó a las instituciones de Rufino a participar en la renovación de su conducción. La convocatoria es pública y las organizaciones deben presentar su Certificado de Subsistencia.',
         'contenido'=>'<p>El Hospital SAMCo de Rufino convocó a las instituciones de la ciudad a participar en el proceso de renovación de autoridades. La reunión fue fijada para el jueves 26 de marzo a las 20 horas en las instalaciones del hospital.</p><p>Para participar, las instituciones debían presentar su Certificado de Subsistencia vigente. La convocatoria es parte del modelo de gestión participativa que caracteriza a los hospitales SAMCo de la provincia.</p><p><strong>Lo que significa:</strong> El SAMCo de Rufino es el principal efector de salud pública de la ciudad. Que su conducción se renueve con participación institucional es una fortaleza del modelo — pero también requiere que las organizaciones de la ciudad se involucren activamente. ¿Cuántas lo hacen?</p><p><em>Fuente: canal8rufino.com.ar — Marzo 2026</em></p>'],

        ['pilar'=>'P04','cat_slug'=>'generacion-rufino','fuente'=>'Municipalidad de Rufino / rufino.gob.ar',
         'img'=>'https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=780&h=440&fit=crop',
         'titulo'=>'Espacio INCAA en Rufino: cine nacional todos los fines de semana con tecnología de primera',
         'bajada'=>'La Sala Cultural Municipal Hispano alberga el Espacio INCAA con equipamiento moderno para proyección cinematográfica. Los viernes, sábados y domingos, Rufino tiene cine — y no tiene por qué viajar para verlo.',
         'contenido'=>'<p>En la Sala Cultural Municipal Hispano — ex Sociedad Española — funciona el Espacio INCAA de Rufino. El espacio cuenta con equipamiento moderno para proyección cinematográfica y es administrado por una cooperativa de trabajo.</p><p>La programación incluye películas nacionales e internacionales de viernes a domingo, además de shows musicales, teatro, charlas y cursos.</p><p><strong>Lo que significa:</strong> El acceso a la cultura no debería ser un privilegio de las ciudades grandes. Rufino tiene cine, tiene teatro, tiene conferencias. Lo que falta es que más jóvenes lo sepan y lo usen. El Espacio INCAA es uno de los activos culturales más importantes de la ciudad — y de los menos conocidos por las nuevas generaciones.</p><p><em>Fuente: rufino.gob.ar — 2026</em></p>'],

        ['pilar'=>'P04','cat_slug'=>'generacion-rufino','fuente'=>'santafe.gob.ar / Municipalidad',
         'img'=>'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=780&h=440&fit=crop',
         'titulo'=>'Escuela de Verano en el Parque Balneario: 500 chicos y una ciudad que invierte en sus pibes',
         'bajada'=>'Quinientos niños y niñas de Rufino participaron de la Escuela de Verano en el Parque Balneario Municipal. La actividad gratuita organizada por la provincia mostró que la demanda existe — y que la oferta puede crecer.',
         'contenido'=>'<p>La Escuela de Verano organizada en el Parque Balneario Municipal de Rufino convocó a 500 niños y niñas de la ciudad. La actividad se desarrolló mientras la provincia avanzaba con obras de infraestructura en el predio.</p><p>El gobernador Pullaro visitó el espacio durante su recorrida por el departamento General López en enero de 2026.</p><p><strong>Lo que significa:</strong> Quinientos pibes en el parque en verano es un número que habla. Habla de familias que necesitan actividades para sus hijos. Habla de una demanda de espacios recreativos gratuitos y seguros. Y habla de que cuando la oferta existe, Rufino responde. La pregunta es qué pasa en los meses que no son verano.</p><p><em>Fuente: santafe.gob.ar — Enero 2026</em></p>'],

        ['pilar'=>'P04','cat_slug'=>'generacion-rufino','fuente'=>'rufino.gob.ar',
         'img'=>'https://images.unsplash.com/photo-1526676037777-05a232554f77?w=780&h=440&fit=crop',
         'titulo'=>'Club de Aviación de Rufino: una escuela de pilotos privados en el interior santafesino',
         'bajada'=>'Rufino tiene un club aeronáutico con dos escuelas habilitadas. La carrera de Piloto Privado de Avión (PPA) se puede cursar localmente. Un dato que pocos conocen de una ciudad que apunta al desarrollo.',
         'contenido'=>'<p>Rufino cuenta con un Club de Aviación que tiene dos escuelas aeronáuticas habilitadas. Entre sus ofertas se encuentra la carrera de Piloto Privado de Avión (PPA), con 40 horas de instrucción. Los interesados deben realizar un psicofísico previo a la inscripción.</p><p>El club está ubicado en General López 242 y cuenta con Personería Jurídica activa.</p><p><strong>Lo que significa:</strong> Rufino tiene una oferta formativa que muchas ciudades más grandes no tienen. Un club de aviación con escuelas habilitadas es un activo de la ciudad que habla de historia aeronáutica y de la vinculación de la región con el transporte aéreo. Que los jóvenes lo conozcan es el primer paso para que lo aprovechen.</p><p><em>Fuente: rufino.gob.ar — 2026</em></p>'],

        ['pilar'=>'P04','cat_slug'=>'generacion-rufino','fuente'=>'rufinoweb.com.ar',
         'img'=>'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=780&h=440&fit=crop',
         'titulo'=>'Deporte y barrio: Rufino tiene fútbol, básquet, vóley, hockey, tenis, boxeo y más — gratis o casi',
         'bajada'=>'La oferta deportiva municipal de Rufino incluye múltiples disciplinas con opciones gratuitas o aranceladas. Para los jóvenes que buscan actividad física, la ciudad tiene más de lo que parece.',
         'contenido'=>'<p>La ciudad de Rufino ofrece una variada gama de actividades deportivas y recreativas, en forma gratuita o con arancel accesible. Entre las disciplinas disponibles se encuentran fútbol, básquet, vóley, hockey, tenis, gimnasia, pelota paleta y boxeo.</p><p>Varias de estas actividades se desarrollan en el Centro Recreativo y Cultural Unión del Norte, que también alberga la Biblioteca Popular J.B. Alberdi.</p><p><strong>Lo que significa:</strong> Una ciudad que ofrece deporte accesible a sus jóvenes es una ciudad que invierte en salud, en convivencia y en futuro. El desafío de Rufino no es la falta de oferta — es la comunicación y el acceso real para los pibes de los barrios más alejados del centro. ¿Llega la información a todos por igual?</p><p><em>Fuente: rufino.gob.ar — 2026</em></p>'],

        ['pilar'=>'P04','cat_slug'=>'generacion-rufino','fuente'=>'rufinoweb.com.ar',
         'img'=>'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=780&h=440&fit=crop',
         'titulo'=>'Rotaract Rufino: jóvenes de 18 a 30 años que hacen servicio comunitario — y necesitan más visibilidad',
         'bajada'=>'El club Rotaract de Rufino agrupa a jóvenes entre 18 y 30 años con foco en servicio humanitario, salud, educación y medio ambiente. Una organización que trabaja pero que pocos conocen en profundidad.',
         'contenido'=>'<p>El club Rotaract de Rufino — auspiciado por Rotary — reúne a jóvenes de ambos sexos de entre 18 y 30 años. Su foco está en el servicio humanitario en la comunidad, con áreas de trabajo en salud, educación y medio ambiente.</p><p>Entre sus actividades periódicas se incluyen el programa "Ver bien para aprender mejor", el banco de elementos ortopédicos y menciones al mejor compañero.</p><p><strong>Lo que significa:</strong> Rufino tiene jóvenes que eligen quedarse y hacer. El Rotaract es una de las expresiones de eso. Darles visibilidad no es solo hablar bien de ellos — es mostrar que el compromiso con la ciudad es posible y que hay otros jóvenes que lo eligen. Un modelo para replicar.</p><p><em>Fuente: rufino.gob.ar — 2026</em></p>'],

        ['pilar'=>'P04','cat_slug'=>'generacion-rufino','fuente'=>'rufinoweb.com.ar / Homenaje Cabezas',
         'img'=>'https://images.unsplash.com/photo-1551818255-e6e10975bc17?w=780&h=440&fit=crop',
         'titulo'=>'29° aniversario del asesinato de Cabezas: Rufino homenajeó al fotógrafo en Plaza Sarmiento',
         'bajada'=>'Como cada año, Rufino recordó a José Luis Cabezas en Plaza Sarmiento al cumplirse un nuevo aniversario de su asesinato. Un homenaje que las nuevas generaciones necesitan entender.',
         'contenido'=>'<p>Rufino realizó el acto homenaje a José Luis Cabezas en Plaza Sarmiento, al cumplirse el 29° aniversario de su asesinato. El fotógrafo, emblema del periodismo argentino y símbolo de la lucha contra la impunidad, es recordado cada año en distintas ciudades del país.</p><p>El homenaje en la plaza principal de Rufino es una tradición que mantiene viva la memoria del periodista y el mensaje que su caso representa para el periodismo libre.</p><p><strong>Lo que significa:</strong> Para los jóvenes rufinenses que no vivieron aquella época, el caso Cabezas puede parecer lejano. No lo es. Es una historia sobre qué pasa cuando el poder se siente impune y el periodismo decide no mirar para otro lado. Un caso que tiene todo el sentido recordar en una ciudad que hoy está construyendo un medio propio.</p><p><em>Fuente: rufinoweb.com.ar — 2026</em></p>'],

        // ── P05 Seguimiento de promesas ──
        ['pilar'=>'P05','cat_slug'=>'seguimiento-promesas','fuente'=>'canal8rufino.com.ar / Municipalidad',
         'img'=>'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=780&h=440&fit=crop',
         'titulo'=>'50 cuadras prometidas: el Plan de Pavimentación 2026 arrancó — ¿cuántas van y cuánto falta?',
         'bajada'=>'En febrero el intendente Lattanzi prometió 50 cuadras de asfalto. En marzo los trabajos comenzaron en Garín y Ayacucho-Moreno. El Rufino abre la ficha de seguimiento.',
         'contenido'=>'<p><strong>La promesa:</strong> El intendente Natalio Lattanzi anunció en febrero de 2026 el inicio del Plan de Pavimentación 2026, con una meta de 50 cuadras. La primera reunión con vecinos se realizó en las calles Ayacucho y Moreno.</p><p><strong>Estado actual:</strong> Al 23 de marzo, los trabajos avanzaban en calle Fernando Garín (Rosa Boussy - Ayacucho). No hay cronograma público detallado sobre el resto de las cuadras incluidas en el plan.</p><p><strong>Lo que falta saber:</strong> ¿Cuál es el listado completo de las 50 cuadras? ¿En qué orden se van a ejecutar? ¿Cuánto va a costar para cada frentista? ¿Hay posibilidad de cuotas? El municipio debe publicar estos datos de forma accesible para todos los vecinos involucrados.</p><p><em>El Rufino va a actualizar esta ficha con cada avance de obra. Fuente: canal8rufino.com.ar — Febrero/Marzo 2026</em></p>'],

        ['pilar'=>'P05','cat_slug'=>'seguimiento-promesas','fuente'=>'santafe.gob.ar / MPA',
         'img'=>'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=780&h=440&fit=crop',
         'titulo'=>'Justicia para las víctimas de femicidio: el Estado prometió respuesta — ¿qué pasó con cada caso?',
         'bajada'=>'La provincia estableció recompensas y protocolos ante femicidios. En Rufino, el caso de la adolescente asesinada el 31 de marzo abre una ficha de seguimiento judicial que El Rufino va a mantener activa.',
         'contenido'=>'<p><strong>La promesa:</strong> El Estado provincial tiene protocolos de respuesta ante femicidios: asistencia a la familia, investigación expedita y medidas cautelares. El gobernador Pullaro estableció recompensas de hasta 10 millones de pesos para casos sin resolver.</p><p><strong>Estado actual:</strong> En el caso de la adolescente asesinada el 31 de marzo en Rufino, el joven investigado fue imputado por homicidio calificado (femicidio) y alojado en instituto de menores por 90 días. La investigación sigue abierta con "otras líneas investigativas".</p><p><strong>Lo que falta saber:</strong> ¿Cómo avanza la causa? ¿Recibió asistencia la familia? ¿Se están aplicando los protocolos provinciales de género? El Rufino va a seguir esta causa como parte de su cobertura de accountability.</p><p><em>Fuente: MPA / sur24.com.ar / conclusión.com.ar — Abril 2026</em></p>'],

        ['pilar'=>'P05','cat_slug'=>'seguimiento-promesas','fuente'=>'santafe.gob.ar',
         'img'=>'https://images.unsplash.com/photo-1575505586569-646b2ca898fc?w=780&h=440&fit=crop',
         'titulo'=>'Ruta 14 repavimentada: 26 km inaugurados — ¿el mantenimiento está asegurado?',
         'bajada'=>'La provincia inauguró 26 km de ruta 14. La obra es real y verificable. Lo que El Rufino va a seguir es si el mantenimiento se sostiene y si las localidades del corredor tienen resueltos los accesos prometidos.',
         'contenido'=>'<p><strong>La promesa:</strong> La repavimentación de la ruta Provincial N°14 fue parte del programa de obra pública provincial anunciado para el departamento General López.</p><p><strong>Estado actual:</strong> Los 26 kilómetros entre el ingreso a Diego de Alvear y la intersección con ruta Nacional 7 fueron inaugurados en abril de 2026. Los presidentes comunales de Diego de Alvear, San Gregorio y Christophersen confirmaron la ejecución.</p><p><strong>Lo que falta saber:</strong> La inauguración es el inicio, no el final. ¿Está contemplado el plan de mantenimiento? ¿Se mejoraron los accesos a las comunas laterales como estaba previsto? ¿Hay obras pendientes en el mismo corredor que no se ejecutaron?</p><p><em>Fuente: santafe.gob.ar — Abril 2026</em></p>'],

        ['pilar'=>'P05','cat_slug'=>'seguimiento-promesas','fuente'=>'rufinoweb.com.ar',
         'img'=>'https://images.unsplash.com/photo-1586864387967-d02ef85d93e8?w=780&h=440&fit=crop',
         'titulo'=>'Centro de Salud Bulgheroni: la refacción prometida que todavía no tiene fecha',
         'bajada'=>'Gestiones ante la provincia y el municipio buscan fondos para refaccionar el Bulgheroni. La promesa existe. El presupuesto, no. El Rufino abre la ficha.',
         'contenido'=>'<p><strong>La promesa:</strong> Autoridades municipales y el diputado Enrico realizaron gestiones ante el gobierno provincial para obtener fondos destinados a refaccionar el Centro de Salud Ángel Bulgheroni de Rufino.</p><p><strong>Estado actual:</strong> Las gestiones están en curso pero sin confirmación de presupuesto asignado ni fecha de inicio de obras. El centro sigue funcionando con sus instalaciones actuales.</p><p><strong>Lo que falta saber:</strong> ¿Cuánto dinero se solicitó? ¿Qué organismo provincial lo evaluaría? ¿Hay un proyecto técnico de obra? ¿En qué plazo esperan una respuesta? El Bulgheroni atiende a los vecinos más vulnerables de Rufino — su estado edilicio no puede seguir siendo una pregunta sin respuesta.</p><p><em>Fuente: rufinoweb.com.ar — 2026</em></p>'],

        ['pilar'=>'P05','cat_slug'=>'seguimiento-promesas','fuente'=>'Municipalidad de Rufino',
         'img'=>'https://images.unsplash.com/photo-1453928582365-b6ad33cbcf64?w=780&h=440&fit=crop',
         'titulo'=>'Cloacas en barrios de Rufino: una deuda que cada gestión hereda y ninguna termina de saldar',
         'bajada'=>'Diego de Alvear inauguró su red cloacal en 2026. Rufino tiene barrios sin cloacas desde hace décadas. ¿Cuándo llega el turno de los vecinos que siguen usando pozo ciego?',
         'contenido'=>'<p><strong>La promesa histórica:</strong> Distintas gestiones municipales y provinciales han mencionado la extensión de la red cloacal en Rufino como objetivo. Sin embargo, varios barrios de la ciudad continúan sin acceso a este servicio básico.</p><p><strong>Estado actual:</strong> No existe un plan público detallado con cronograma, presupuesto y zonas priorizadas para la extensión de la red cloacal en Rufino. La inauguración de la red en Diego de Alvear demuestra que la provincia puede ejecutar estas obras.</p><p><strong>Lo que falta saber:</strong> ¿Qué barrios de Rufino están en el mapa para recibir extensión cloacal? ¿Hay proyectos técnicos elaborados? ¿Se solicitaron fondos provinciales o nacionales? Los vecinos que pagan tasas municipales tienen derecho a saber cuándo van a tener el servicio.</p><p><em>El Rufino va a seguir este tema. — Abril 2026</em></p>'],

        ['pilar'=>'P05','cat_slug'=>'seguimiento-promesas','fuente'=>'puebloregional.com.ar',
         'img'=>'https://images.unsplash.com/photo-1494172961521-33799ddd43a5?w=780&h=440&fit=crop',
         'titulo'=>'Promesas de campaña 2025 en General López: qué dijeron los candidatos y qué pasó después',
         'bajada'=>'En junio de 2025 se eligieron autoridades en el departamento. Los candidatos ganadores hicieron promesas. El Rufino abre el registro de cuáles se están cumpliendo y cuáles esperan.',
         'contenido'=>'<p><strong>El contexto:</strong> En las elecciones de junio de 2025, Unidos Para Cambiar Santa Fe se impuso en la mayoría de las localidades del departamento General López. Los candidatos ganadores llegaron al cargo con compromisos específicos ante sus comunidades.</p><p><strong>Lo que sabemos:</strong> En Rufino, la continuidad de la gestión Lattanzi implica que las promesas de su plataforma electoral están en ejecución o pendientes. El Plan de Pavimentación 2026 es una de ellas.</p><p><strong>Lo que falta sistematizar:</strong> El Rufino va a construir un registro público de las promesas electorales de los representantes elegidos en 2025 para el departamento. Si prometieron y ganaron, rinden cuentas. Esa es la regla.</p><p><em>Fuente: puebloregional.com.ar — Junio 2025 / construcción propia — 2026</em></p>'],

        ['pilar'=>'P05','cat_slug'=>'seguimiento-promesas','fuente'=>'Concejo Deliberante Rufino',
         'img'=>'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=780&h=440&fit=crop',
         'titulo'=>'Presupuesto municipal 2026: ¿cuánto se asignó a obras, salud y educación en Rufino?',
         'bajada'=>'El presupuesto municipal es el documento donde las promesas se convierten en números. El Rufino solicita acceso a los datos del presupuesto 2026 de la Municipalidad de Rufino.',
         'contenido'=>'<p><strong>Por qué importa:</strong> El presupuesto municipal es el instrumento de planificación más importante de una gestión. En él se ve con claridad cuánto se destina a cada área: obras, salud, educación, administración, deuda.</p><p><strong>Lo que pedimos:</strong> El Rufino solicitará al Concejo Deliberante de Rufino y a la Municipalidad el presupuesto aprobado para el ejercicio 2026, con apertura por programas y proyectos.</p><p><strong>Lo que vamos a publicar:</strong> Un análisis accesible del presupuesto municipal 2026: qué porcentaje va a obras, qué a salud, qué a administración, y cómo se compara con años anteriores. La transparencia fiscal es un derecho de todos los rufinenses.</p><p><em>El Rufino — Nota en construcción — Abril 2026</em></p>'],

        ['pilar'=>'P05','cat_slug'=>'seguimiento-promesas','fuente'=>'santafe.gob.ar / Legislatura',
         'img'=>'https://images.unsplash.com/photo-1591829588563-a12efa2e0f63?w=780&h=440&fit=crop',
         'titulo'=>'Ley de Crimen Organizado en Santa Fe: el gobierno la apoya — ¿qué cambia para el sur provincial?',
         'bajada'=>'El Ejecutivo santafesino expresó apoyo al proyecto de adhesión a la Ley de Crimen Organizado N° 27.786. Si se aprueba, fortalece la persecución de organizaciones criminales en toda la provincia, incluyendo el sur.',
         'contenido'=>'<p><strong>La propuesta:</strong> El gobierno de Santa Fe expresó su apoyo al proyecto presentado en la Legislatura provincial para adherir a la Ley de Crimen Organizado N° 27.786. La norma nacional permite herramientas más potentes para la persecución de organizaciones delictivas.</p><p><strong>Por qué importa para el sur provincial:</strong> El departamento General López, por su ubicación geográfica en la intersección entre Santa Fe, Córdoba y Buenos Aires, es una zona de tránsito de rutas comerciales — lo que también lo hace potencialmente vulnerable al crimen organizado.</p><p><strong>Lo que hay que seguir:</strong> ¿Se aprobó la adhesión? ¿Qué recursos concretos se destinaron al sur provincial? ¿Qué dice la jefatura policial regional sobre el impacto de la medida en el departamento?</p><p><em>Fuente: sucesosrufino.com.ar / santafe.gob.ar — Marzo 2026</em></p>'],

        // ── P06 Contexto y datos ──
        ['pilar'=>'P06','cat_slug'=>'contexto-datos','fuente'=>'INDEC / Censo 2022',
         'img'=>'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=780&h=440&fit=crop',
         'titulo'=>'Rufino en números: 19.211 habitantes, 7.200 hogares y 14.000 usuarios activos de Facebook',
         'bajada'=>'El Censo Nacional 2022 fijó la población de Rufino en 19.211 habitantes. Pero los números que definen a la ciudad van más allá del censo: hogares, conectividad, economía y demografía tienen su propia historia.',
         'contenido'=>'<p>Rufino tiene, según el Censo Nacional de Población, Hogares y Viviendas 2022, 19.211 habitantes distribuidos en aproximadamente 7.200 hogares. Con una densidad moderada para los estándares del sur santafesino, la ciudad es el centro urbano más importante del departamento General López.</p><p>En términos de conectividad digital, se estima que alrededor de 14.000 usuarios de Rufino tienen presencia activa en Facebook — un indicador que refleja tanto la penetración tecnológica como los hábitos de consumo de información local.</p><p><strong>Lo que significa:</strong> Una ciudad de 19.000 habitantes con 14.000 usuarios de Facebook activos es una ciudad conectada. Lo que no tiene es un medio digital local que le dé la calidad informativa que merece. Eso es exactamente lo que El Rufino viene a construir.</p><p><em>Fuente: INDEC Censo 2022 / estimación propia — 2026</em></p>'],

        ['pilar'=>'P06','cat_slug'=>'contexto-datos','fuente'=>'infocampo.com.ar / INDEC',
         'img'=>'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=780&h=440&fit=crop',
         'titulo'=>'Inflación enero 2026: cómo le fue al bolsillo rufinense y qué dice el INDEC',
         'bajada'=>'El INDEC publicó los datos de inflación de enero 2026. El sector privado no registrado fue el único salario que superó el índice. Para los rufinenses, los números nacionales tienen traducción local.',
         'contenido'=>'<p>El INDEC informó los datos de inflación correspondientes a enero de 2026. El sector privado no registrado fue el único segmento salarial que logró superar la inflación del mes — los salarios registrados tanto privados como públicos quedaron por debajo del índice de precios.</p><p>Para Rufino, una ciudad cuya economía se sustenta en el comercio, los servicios y el agro, la inflación tiene efectos concretos: en el poder adquisitivo del comerciante, del empleado público, del jubilado y del productor agropecuario.</p><p><strong>Lo que significa:</strong> Ganarle a la inflación no es solo un dato macroeconómico — es una realidad que determina si una familia puede llegar a fin de mes. En Rufino, con una economía local fuertemente ligada al sector informal y al agro, los datos del INDEC merecen una lectura contextualizada. El Rufino se propone hacerla cada mes.</p><p><em>Fuente: INDEC / sucesosrufino.com.ar — Enero 2026</em></p>'],

        ['pilar'=>'P06','cat_slug'=>'contexto-datos','fuente'=>'INDEC / INTA / BCR',
         'img'=>'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=780&h=440&fit=crop',
         'titulo'=>'General López en el mapa productivo de Santa Fe: tercer departamento más poblado, primer cinturón sojero',
         'bajada'=>'El Departamento General López tiene 191.024 habitantes y 11.558 km² de superficie. Es el tercer más poblado y uno de los más productivos de la provincia. Rufino es su nodo central.',
         'contenido'=>'<p>El Departamento General López es el tercer departamento más poblado de Santa Fe, con 191.024 habitantes, y el cuarto más grande con 11.558 km² de superficie. Como la mayoría de los departamentos del sur provincial, su economía se sustenta en una intensa actividad agropecuaria.</p><p>Rufino, ubicada sobre la Ruta Nacional N°7 y la vía férrea BAP-San Martín que une Buenos Aires con Mendoza y el paso Cristo Redentor, es el nodo central de este territorio. Su posición estratégica la convierte en punto de paso obligado del corredor más importante del Mercosur.</p><p><strong>Lo que significa:</strong> Rufino no es un pueblo del interior. Es el corazón de un departamento que produce buena parte de los alimentos que consume Argentina y exporta al mundo. Entender ese contexto es entender por qué las decisiones que se toman acá — sobre infraestructura, educación y salud — tienen importancia regional.</p><p><em>Fuente: INDEC / Senado Santa Fe / isp19-sfe.infd.edu.ar — 2026</em></p>'],

        ['pilar'=>'P06','cat_slug'=>'contexto-datos','fuente'=>'FACMA / BCR / infocampo.com.ar',
         'img'=>'https://images.unsplash.com/photo-1543393716-375f47996a77?w=780&h=440&fit=crop',
         'titulo'=>'Costos del campo en números: cuánto cuesta producir soja en General López en 2026',
         'bajada'=>'FACMA, la BCR y el INTA publican datos que los productores usan para tomar decisiones. Acá los ponemos en contexto local: cuánto cuesta cosechar, cuánto pesa el arrendamiento, cuánto queda de margen.',
         'contenido'=>'<p>Según datos de FACMA actualizados para la campaña 2025/26, cosechar soja con un rinde base de 24 quintales por hectárea tiene un costo operativo total de $138.949 por hectárea, equivalente a US$ 97. El componente más pesado es la maquinaria (37%), seguido por personal (17%) y combustible (13%).</p><p>Para un productor de General López que arrienda campo a 18-20 quintales por hectárea — la realidad de muchos — la ecuación se ajusta al límite: con 24 qq/ha de rinde, no hay rentabilidad en campo alquilado. Se necesitan al menos 41 quintales para cubrir costos.</p><p>La soja cotizó a $440.000 la tonelada en Rosario al 31 de marzo de 2026, con futuros Mayo 2026 en torno a US$ 317 la tonelada.</p><p><strong>Lo que significa:</strong> El campo del sur santafesino opera con márgenes que dependen del clima, del tipo de cambio y de las decisiones en Chicago. Publicar estos números en forma accesible es parte de lo que El Rufino entiende por periodismo de servicio para la región.</p><p><em>Fuente: FACMA / BCR / infocampo.com.ar — 2026</em></p>'],

        ['pilar'=>'P06','cat_slug'=>'contexto-datos','fuente'=>'rufinoweb.com.ar / registro propio',
         'img'=>'https://images.unsplash.com/photo-1504639725590-34d0984388bd?w=780&h=440&fit=crop',
         'titulo'=>'Registro de lluvias en Rufino: cuánto cayó y qué dice el campo sobre el año hídrico 2026',
         'bajada'=>'Las lluvias de marzo y las precipitaciones acumuladas en 2026 fueron significativas para los cultivos. El Rufino publica el registro disponible y lo contextualiza con lo que necesita la campaña.',
         'contenido'=>'<p>Rufino registró importantes precipitaciones durante la noche del viernes y la mañana del sábado de la última semana de marzo de 2026. El registro de lluvias acumuladas muestra un año hídrico que aportó humedad necesaria para los cultivos de verano tardío.</p><p>El año agrícola 2025/26 arrancó con el 75% de los lotes con niveles de agua adecuados, según datos de la Bolsa de Comercio de Rosario. Las lluvias de marzo reforzaron ese panorama positivo para la finalización de la campaña sojera.</p><p><strong>Lo que significa:</strong> El agua es el insumo más importante y el más incontrolable del campo. Un registro sistemático de las lluvias en Rufino — publicado con contexto sobre lo que necesita cada cultivo en cada etapa — es información de servicio real para productores y vecinos. El Rufino se compromete a publicarlo con regularidad.</p><p><em>Fuente: rufinoweb.com.ar / registro propio — Marzo 2026</em></p>'],

        ['pilar'=>'P06','cat_slug'=>'contexto-datos','fuente'=>'SAMCO / Municipalidad de Rufino',
         'img'=>'https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=780&h=440&fit=crop',
         'titulo'=>'El SAMCO de Rufino: qué servicios tiene, a quién atiende y qué le falta',
         'bajada'=>'El Hospital SAMCo de Rufino es un hospital de autogestión de mediano riesgo. Atiende a pacientes de alta vulnerabilidad, obras sociales y particulares. Esta es la radiografía completa de lo que tiene y lo que necesita.',
         'contenido'=>'<p>El Hospital SAMCo de Rufino, ubicado en Alem Vieyra 1261, es un establecimiento de salud con internación general y capacidad de mediano riesgo. Entre sus prestaciones se incluyen clínica médica, pediatría, tocoginecología, cardiología, traumatología, psiquiatría, kinesiología, nutrición y laboratorio.</p><p>Atiende a pacientes de mayor vulnerabilidad, obras sociales y particulares, de lunes a viernes de 7 a 19 horas. En marzo de 2026 el hospital renovó sus autoridades con una nueva conducción.</p><p><strong>Lo que significa:</strong> El SAMCo es la columna vertebral de la salud pública de Rufino. Para los vecinos sin cobertura social, es el único recurso disponible. Conocer qué puede y qué no puede resolver el hospital — y qué necesita para mejorar — es información que los rufinenses necesitan y que El Rufino va a construir a través del diálogo con su comunidad hospitalaria.</p><p><em>Fuente: rufino.gob.ar / SAMCO Rufino — 2026</em></p>'],

        ['pilar'=>'P06','cat_slug'=>'contexto-datos','fuente'=>'Cámara de Senadores Santa Fe / INDEC',
         'img'=>'https://images.unsplash.com/photo-1533073526757-2c8ca1df9f1c?w=780&h=440&fit=crop',
         'titulo'=>'La economía de General López: agro, comercio y servicios — cuánto pesa cada sector',
         'bajada'=>'El departamento General López es el tercero más poblado de Santa Fe con una economía basada en la producción agropecuaria intensiva. Aquí los datos que definen la estructura económica de la región.',
         'contenido'=>'<p>El Departamento General López sustenta su economía en una intensa actividad agropecuaria. Con 11.558 km² de superficie productiva, es uno de los principales productores de soja, maíz y girasol de la provincia de Santa Fe.</p><p>El 60% del territorio se destina a agricultura — principalmente soja de primera y segunda — mientras que el sur del departamento mantiene una tradición ganadera de cría e invernada que, aunque reducida respecto a décadas anteriores, sigue siendo relevante.</p><p>Rufino, como nodo urbano central, concentra el comercio, los servicios y la agroindustria del departamento. Su posición sobre la RN7 la convierte en un punto de logística y distribución para toda la zona.</p><p><strong>Lo que significa:</strong> Entender la estructura económica del departamento es entender de dónde vienen los recursos que financian los servicios públicos de Rufino. Y entender qué tan dependiente es esa economía de la soja es entender su principal vulnerabilidad.</p><p><em>Fuente: Cámara de Senadores Santa Fe / INDEC — 2026</em></p>'],

        ['pilar'=>'P06','cat_slug'=>'contexto-datos','fuente'=>'ISP N°19 / Municipalidad',
         'img'=>'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=780&h=440&fit=crop',
         'titulo'=>'Educación en Rufino: escuelas primarias, secundarias, ISP y lo que todavía no hay',
         'bajada'=>'Rufino tiene escuelas primarias y secundarias públicas y privadas, un instituto de profesorado, club aeronáutico y espacios culturales. También tiene brechas que vale la pena nombrar.',
         'contenido'=>'<p>La oferta educativa de Rufino abarca todos los niveles: inicial, primario, secundario y superior no universitario. El Instituto Superior de Profesorado N°19 ofrece carreras docentes y técnicas, con apertura cíclica para no saturar el mercado laboral local.</p><p>Para 2026, el ISP19 abre cuatro carreras de primer año: Técnico en Alimentos, Profesorado de Primaria, Profesorado de Biología y Profesorado de Historia. La inscripción para el primer año 2026 cerró entre el 22 de noviembre y el 15 de diciembre de 2025.</p><p><strong>Lo que no hay:</strong> Rufino no tiene sede universitaria. Los jóvenes que quieren acceder a títulos universitarios deben migrar a Rosario, Santa Fe, Córdoba o Buenos Aires. Una ciudad de 19.000 habitantes con una economía productiva como la de General López merece una discusión seria sobre acceso universitario descentralizado.</p><p><em>Fuente: ISP N°19 / rufino.gob.ar — 2026</em></p>'],
    ];
}
