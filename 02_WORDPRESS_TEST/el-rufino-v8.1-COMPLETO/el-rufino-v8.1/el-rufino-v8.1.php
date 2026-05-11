<?php
/**
 * Plugin Name: El Rufino — Sistema Operativo
 * Description: v8.1 Setup Wizard fullscreen, capas por rol, Producción núcleo, solo Anthropic.
 * Version: 8.1.0
 * Author: El Rufino
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */

defined('ABSPATH') || exit;
define('ER_V8_VERSION', '8.1.0');
define('ER_V8_URL', plugin_dir_url(__FILE__));
define('ER_V8_DIR', plugin_dir_path(__FILE__));

register_activation_hook(__FILE__, function() {
    add_option('er_v8_redirect_wizard', true);
    if ($old = get_option('er_claude_key')) {
        update_option('er_anthropic_key', $old);
        delete_option('er_claude_key');
    }
});

add_action('admin_init', function() {
    if (get_option('er_v8_redirect_wizard')) {
        delete_option('er_v8_redirect_wizard');
        if (!isset($_GET['activate-multi'])) {
            wp_redirect(admin_url('admin.php?page=er-setup'));
            exit;
        }
    }
});

add_action('admin_menu', function() {
    add_menu_page('El Rufino','El Rufino','edit_posts','el-rufino','er_v8_panel','dashicons-media-document',2);
    add_submenu_page('el-rufino','Setup','Setup','manage_options','er-setup','er_v8_wizard');
});

add_action('admin_enqueue_scripts', function($h) {
    if (strpos($h,'el-rufino')===false && strpos($h,'er-setup')===false) return;
    wp_enqueue_style('er-css',ER_V8_URL.'assets/css/panel.css',[],ER_V8_VERSION);
    wp_enqueue_script('er-js',ER_V8_URL.'assets/js/panel.js',['jquery'],ER_V8_VERSION,true);
    wp_localize_script('er-js','erData',[
        'ajaxUrl'=>admin_url('admin-ajax.php'),
        'nonce'=>wp_create_nonce('er_nonce'),
        'isAdmin'=>current_user_can('manage_options'),
        'wizardDone'=>get_option('er_v8_wizard')==='done'
    ]);
});

add_action('admin_head', function() {
    $s=get_current_screen();
    if(!$s||(strpos($s->id,'el-rufino')===false&&strpos($s->id,'er-setup')===false))return;
    echo '<style>#wpadminbar,#adminmenuback,#adminmenuwrap,#wpfooter{display:none!important}html.wp-toolbar{padding-top:0!important}#wpcontent{margin:0!important;padding:0!important}#er-root{position:fixed;inset:0;z-index:99999;overflow:auto;background:#f5f0e8}</style>';
});

function er_v8_wizard(){echo '<div id="er-root" data-view="wizard"></div>';}
function er_v8_panel(){echo '<div id="er-root" data-view="panel"></div>';}

/* WIZARD */
add_action('wp_ajax_er_save_wizard',function(){
    check_ajax_referer('er_nonce','nonce');
    if(!current_user_can('manage_options'))wp_send_json_error();
    update_option('er_identidad',[
        'claim'=>sanitize_text_field($_POST['claim']??'LO QUE PASA Y LO QUE SIGNIFICA'),
        'color'=>sanitize_hex_color($_POST['color']??'#c0271b')
    ]);
    update_option('er_anthropic_key',sanitize_text_field($_POST['api_key']??''));
    update_option('er_pilares',array_map('sanitize_text_field',$_POST['pilares']??[]));
    update_option('er_v8_wizard','done');
    wp_send_json_success(['redirect'=>admin_url('admin.php?page=el-rufino')]);
});

/* PRODUCCIÓN: ASISTENTE */
add_action('wp_ajax_er_asistente_generar',function(){
    check_ajax_referer('er_nonce','nonce');
    if(!current_user_can('edit_posts'))wp_send_json_error(['msg'=>'Sin permisos']);
    $key=get_option('er_anthropic_key');
    if(!$key)wp_send_json_error(['msg'=>'API Key no configurada']);
    $titulo=sanitize_text_field($_POST['titulo']??'');
    $pilar=sanitize_text_field($_POST['pilar']??'');
    $youtube=sanitize_text_field($_POST['youtube']??'');
    $trans=sanitize_textarea_field($_POST['transcripcion']??'');
    $system='Sos el redactor de El Rufino, medio digital de Rufino, Santa Fe. Escribís en español rioplatense, directo, con contexto local. Regla de dos capas: hecho + significado. Nunca "En el marco de".';
    $prompt="Escribí una nota para el pilar $pilar. Título: $titulo. ";
    if($youtube)$prompt.="Video: $youtube. ";
    if($trans)$prompt.="Transcripción: $trans. ";
    $prompt.="Incluí: TÍTULO, BAJADA, CUERPO (3 párrafos), LO QUE SIGNIFICA. Máximo 400 palabras.";
    $res=wp_remote_post('https://api.anthropic.com/v1/messages',[
        'timeout'=>90,
        'headers'=>['x-api-key'=>$key,'anthropic-version'=>'2023-06-01','content-type'=>'application/json'],
        'body'=>json_encode(['model'=>'claude-sonnet-4-6','max_tokens'=>1024,'system'=>$system,'messages'=>[['role'=>'user','content'=>$prompt]]])
    ]);
    if(is_wp_error($res))wp_send_json_error(['msg'=>$res->get_error_message()]);
    $data=json_decode(wp_remote_retrieve_body($res),true);
    wp_send_json_success(['content'=>$data['content'][0]['text']??'']);
});

/* YOUTUBE */
add_action('wp_ajax_er_yt_info',function(){
    check_ajax_referer('er_nonce','nonce');
    $url=sanitize_text_field($_POST['url']??'');
    preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|v\/))([a-zA-Z0-9_-]{11})/',$url,$m);
    $id=$m[1]??'';if(!$id)wp_send_json_error(['msg'=>'URL inválida']);
    $api='https://www.googleapis.com/youtube/v3/videos?part=snippet&id='.$id.'&key='.get_option('er_yt_key','');
    $res=wp_remote_get($api,['timeout'=>15]);
    if(is_wp_error($res))wp_send_json_error(['msg'=>$res->get_error_message()]);
    $d=json_decode(wp_remote_retrieve_body($res),true);
    if(empty($d['items']))wp_send_json_error(['msg'=>'Video no encontrado']);
    $s=$d['items'][0]['snippet'];
    wp_send_json_success(['video_id'=>$id,'titulo'=>$s['title']??'','descripcion'=>$s['description']??'','canal'=>$s['channelTitle']??'','thumbnail'=>$s['thumbnails']['medium']['url']??'']);
});

add_action('wp_ajax_er_yt_captions',function(){
    check_ajax_referer('er_nonce','nonce');
    $id=sanitize_text_field($_POST['video_id']??'');
    if(!preg_match('/^[a-zA-Z0-9_-]{11}$/',$id))wp_send_json_error(['msg'=>'ID inválido']);
    foreach(['es','es-419','en'] as $lang){
        $url='https://www.youtube.com/api/timedtext?v='.urlencode($id).'&lang='.$lang.'&fmt=json3';
        $res=wp_remote_get($url,['timeout'=>15,'headers'=>['user-agent'=>'Mozilla/5.0']]);
        if(is_wp_error($res))continue;
        $data=json_decode(wp_remote_retrieve_body($res),true);
        if(empty($data['events']))continue;
        $txt='';foreach($data['events'] as $e){if(empty($e['segs']))continue;foreach($e['segs'] as $s)$txt.=$s['utf8']??'';}
        $txt=trim(preg_replace('/\s+/',' ',str_replace("\n",' ',$txt)));
        if($txt)wp_send_json_success(['transcripcion'=>$txt]);
    }
    wp_send_json_error(['msg'=>'Sin subtítulos automáticos disponibles']);
});

/* PROMESAS */
add_action('wp_ajax_er_save_promesa',function(){
    check_ajax_referer('er_nonce','nonce');
    if(!current_user_can('edit_posts'))wp_send_json_error();
    $p=get_option('er_promesas',[]);
    $p[]=['id'=>uniqid(),'texto'=>sanitize_text_field($_POST['texto']??''),'fuente'=>sanitize_text_field($_POST['fuente']??''),'fecha'=>sanitize_text_field($_POST['fecha']??date('Y-m-d')),'estado'=>'pendiente','pilar'=>sanitize_text_field($_POST['pilar']??'')];
    update_option('er_promesas',$p);
    wp_send_json_success(['saved'=>true]);
});

add_action('wp_ajax_er_get_promesas',function(){
    check_ajax_referer('er_nonce','nonce');
    wp_send_json_success(get_option('er_promesas',[]));
});

add_action('wp_ajax_er_update_promesa',function(){
    check_ajax_referer('er_nonce','nonce');
    $id=sanitize_text_field($_POST['id']??'');
    $estado=sanitize_text_field($_POST['estado']??'');
    $p=get_option('er_promesas',[]);
    foreach($p as &$pr){if($pr['id']===$id)$pr['estado']=$estado;}
    update_option('er_promesas',$p);
    wp_send_json_success();
});

/* DASHBOARD */
add_action('wp_ajax_er_save_key',function(){
    check_ajax_referer('er_nonce','nonce');
    if(!current_user_can('manage_options'))wp_send_json_error();
    update_option('er_anthropic_key',sanitize_text_field($_POST['key']??''));
    wp_send_json_success(['saved'=>true]);
});

add_action('wp_ajax_er_key_status',function(){
    check_ajax_referer('er_nonce','nonce');
    $k=get_option('er_anthropic_key','');
    wp_send_json_success(['configured'=>!empty($k),'masked'=>$k?substr($k,0,12).'...':'']);
});