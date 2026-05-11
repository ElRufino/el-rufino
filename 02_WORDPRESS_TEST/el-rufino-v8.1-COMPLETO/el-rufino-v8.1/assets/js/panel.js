(function($){
const r=document.getElementById('er-root');if(!r)return;
const v=r.dataset.view,a=erData.isAdmin,d=erData.wizardDone;
let promesas=[];
document.addEventListener('keydown',e=>{if(e.key==='Escape')location.href='/wp-admin/'});
if(v==='wizard'||!d){wizard()}else{panel()}
function wizard(){
r.innerHTML=`<div class="er-header"><div><h1>EL RUFINO</h1><div class="claim">LO QUE PASA Y LO QUE SIGNIFICA</div></div><a href="/wp-admin/" class="er-btn er-btn-outline">← Volver a WordPress</a></div><div class="er-wizard"><h2>Configuración inicial</h2><div class="step"><label>Paso 1 — Identidad</label><input type="text" id="claim" value="LO QUE PASA Y LO QUE SIGNIFICA"><input type="text" id="color" value="#c0271b" style="margin-top:10px"></div><div class="step"><label>Paso 2 — Anthropic API</label><input type="password" id="api" placeholder="sk-ant-..."></div><div class="step"><label>Paso 3 — Pilares</label>${['P01 Rufino Real','P02 El Campo Habla','P03 Barrio a Barrio','P04 Generación Rufino','P05 Poder y Gestión','P06 Rufino en Datos'].map(p=>`<label style="font-weight:400;display:block;margin:5px 0"><input type="checkbox" value="${p.split(' ')[0]}" checked> ${p}</label>`).join('')}</div><button class="er-btn" id="save" style="width:100%;margin-top:20px">Finalizar</button></div>`;
$('#save').on('click',()=>{const pilares=[];$('.step input:checked').each(function(){pilares.push($(this).val())});$.post(erData.ajaxUrl,{action:'er_save_wizard',nonce:erData.nonce,claim:$('#claim').val(),color:$('#color').val(),api_key:$('#api').val(),pilares:pilares},res=>{if(res.success)location.href=res.data.redirect})})
}
function panel(){
if(!a){editor();return}
r.innerHTML=`<div class="er-header"><div><h1>EL RUFINO</h1><div class="claim">SISTEMA OPERATIVO v8.1</div></div><a href="/wp-admin/" class="er-btn er-btn-outline">← WordPress</a></div><div class="er-grid"><div class="er-card" data-m="produccion"><h3>Producción</h3><p>Nueva nota, YouTube, transcripción, IA</p></div><div class="er-card" data-m="seguimiento"><h3>Seguimiento</h3><p>Auditar promesas, semáforo, CSV</p></div><div class="er-card" data-m="inteligencia"><h3>Inteligencia</h3><p>Contexto local, buscar archivo</p></div><div class="er-card" data-m="dashboard"><h3>Dashboard</h3><p>API Key, usuarios</p></div></div>`;
$('.er-card').on('click',function(){loadModule($(this).data('m'))})
}
function editor(){
r.innerHTML=`<div class="er-header"><div><h1>EL RUFINO</h1></div><a href="/wp-admin/admin.php?page=el-rufino" style="color:#fff;font-size:13px">Ver panel completo</a></div><div class="er-editor-view"><button class="er-editor-btn" onclick="loadModule('produccion')">Nueva<br>Nota</button><button class="er-editor-btn">Mis<br>Borradores</button><button class="er-editor-btn" onclick="loadModule('seguimiento')">Promesas</button></div>`
}
window.loadModule=function(m){
if(m==='produccion')produccion();
else if(m==='seguimiento')seguimiento();
else if(m==='inteligencia')inteligencia();
else if(m==='dashboard')dashboard();
}
function produccion(){
r.innerHTML=`<div class="er-header"><div><h1>PRODUCCIÓN</h1></div><a href="javascript:panel()" class="er-btn er-btn-outline">← Volver</a></div><div class="er-produccion"><div class="er-main"><h2 style="font-family:'Playfair',serif;margin:0 0 20px">Nueva Nota</h2><div class="er-form-group"><label>Título</label><input type="text" id="p-titulo"></div><div class="er-form-group"><label>Pilar</label><select id="p-pilar"><option>P01</option><option>P02</option><option>P03</option><option>P04</option><option>P05</option><option>P06</option></select></div><div class="er-form-group"><label>YouTube URL (opcional)</label><input type="text" id="p-yt" placeholder="https://youtu.be/..."><button class="er-btn" id="p-yt-btn" style="margin-top:8px">Cargar video</button></div><div class="er-form-group"><label>Imagen destacada</label><input type="file" id="p-img"></div><div class="er-form-group"><label>Transcripción</label><textarea id="p-trans" rows="4"></textarea><button class="er-btn" id="p-cap" style="margin-top:8px">Auto-transcribir</button></div><button class="er-btn" id="p-gen" style="width:100%;padding:15px;font-size:16px">Generar con IA</button><div id="p-out" style="margin-top:20px"></div></div><div class="er-sidebar"><h3 style="margin:0 0 15px">Inteligencia</h3><div class="er-form-group"><label>Contexto Local</label><input type="text" id="i-ctx" placeholder="Buscar en archivo..."><button class="er-btn" style="width:100%;margin-top:8px">Buscar</button></div><div class="er-form-group"><label>Promesas relacionadas</label><div id="i-prom"></div></div></div></div>`;
$('#p-yt-btn').on('click',()=>{$.post(erData.ajaxUrl,{action:'er_yt_info',nonce:erData.nonce,url:$('#p-yt').val()},res=>{if(res.success){$('#p-titulo').val(res.data.titulo);$('#p-trans').val(res.data.descripcion)}})});
$('#p-cap').on('click',()=>{const id=$('#p-yt').val().match(/(?:youtu\.be\/|v=)([a-zA-Z0-9_-]{11})/)?.[1];if(!id)return alert('URL inválida');$.post(erData.ajaxUrl,{action:'er_yt_captions',nonce:erData.nonce,video_id:id},res=>{if(res.success)$('#p-trans').val(res.data.transcripcion);else alert(res.data.msg)})});
$('#p-gen').on('click',()=>{$.post(erData.ajaxUrl,{action:'er_asistente_generar',nonce:erData.nonce,titulo:$('#p-titulo').val(),pilar:$('#p-pilar').val(),youtube:$('#p-yt').val(),transcripcion:$('#p-trans').val()},res=>{if(res.success)$('#p-out').html('<div style="background:#fff;padding:20px;border-radius:8px;white-space:pre-wrap">'+res.data.content+'</div>')})});
}
function seguimiento(){
$.post(erData.ajaxUrl,{action:'er_get_promesas',nonce:erData.nonce},res=>{
promesas=res.data||[];
r.innerHTML=`<div class="er-header"><div><h1>SEGUIMIENTO</h1></div><a href="javascript:panel()" class="er-btn er-btn-outline">← Volver</a></div><div style="padding:40px"><button class="er-btn" id="s-exp" style="margin-bottom:20px">Exportar CSV</button><table class="er-table"><thead><tr><th>Texto</th><th>Fuente</th><th>Fecha</th><th>Pilar</th><th>Estado</th><th>Acción</th></tr></thead><tbody>${promesas.map(p=>`<tr><td>${p.texto}</td><td>${p.fuente}</td><td>${p.fecha}</td><td>${p.pilar||''}</td><td><span class="er-badge er-badge-${p.estado}">${p.estado}</span></td><td><select class="s-est" data-id="${p.id}"><option value="pendiente"${p.estado==='pendiente'?' selected':''}>Pendiente</option><option value="cumplida"${p.estado==='cumplida'?' selected':''}>Cumplida</option><option value="incumplida"${p.estado==='incumplida'?' selected':''}>Incumplida</option></select></td></tr>`).join('')}</tbody></table></div>`;
$('.s-est').on('change',function(){$.post(erData.ajaxUrl,{action:'er_update_promesa',nonce:erData.nonce,id:$(this).data('id'),estado:$(this).val()},()=>seguimiento())});
$('#s-exp').on('click',()=>{let csv='Texto,Fuente,Fecha,Pilar,Estado\n';promesas.forEach(p=>{csv+=`"${p.texto}","${p.fuente}","${p.fecha}","${p.pilar||''}","${p.estado}"\n`});const b=new Blob([csv],{type:'text/csv'});const u=URL.createObjectURL(b);const a=document.createElement('a');a.href=u;a.download='promesas.csv';a.click()});
});
}
function inteligencia(){
r.innerHTML=`<div class="er-header"><div><h1>INTELIGENCIA</h1></div><a href="javascript:panel()" class="er-btn er-btn-outline">← Volver</a></div><div style="padding:40px;max-width:800px"><h2>Contexto Local</h2><input type="text" placeholder="Buscar en archivo..." style="width:100%;padding:12px;margin:20px 0"><button class="er-btn">Buscar</button></div>`;
}
function dashboard(){
$.post(erData.ajaxUrl,{action:'er_key_status',nonce:erData.nonce},res=>{
const k=res.data;
r.innerHTML=`<div class="er-header"><div><h1>DASHBOARD</h1></div><a href="javascript:panel()" class="er-btn er-btn-outline">← Volver</a></div><div style="padding:40px;max-width:600px"><h2>Configuración</h2><div class="er-form-group"><label>API Key Anthropic</label><input type="password" id="d-key" placeholder="${k.masked||'sk-ant-...'}"><button class="er-btn" id="d-save" style="margin-top:10px">Guardar</button></div><div class="er-form-group"><label>Estado</label><p>${k.configured?'✓ Configurada':'✗ Sin configurar'}</p></div></div>`;
$('#d-save').on('click',()=>{$.post(erData.ajaxUrl,{action:'er_save_key',nonce:erData.nonce,key:$('#d-key').val()},()=>dashboard())});
});
}
})(jQuery);