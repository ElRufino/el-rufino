const { useState, useEffect, useRef, useCallback } = React;

// ── Config ──
const PALETA = { rojo:"#c0271b", negro:"#1a1a1a", crema:"#f5f0e8", blanco:"#ffffff", crema2:"#ece7dd", crema3:"#ddd8ce", verde:"#2e7d32", azul:"#1565c0", amber:"#e65100" };
const PILARES = [
  { code:"P01", name:"Lo que pasa",            slug:"lo-que-pasa",          fmt:"nota · hilo · infografía",           color:"#c0271b" },
  { code:"P02", name:"El campo habla",          slug:"el-campo-habla",       fmt:"entrevista · dato · WhatsApp",        color:"#1565c0" },
  { code:"P03", name:"Barrio a barrio",         slug:"barrio-a-barrio",      fmt:"seguimiento · mapa · vecinos",        color:"#2e7d32" },
  { code:"P04", name:"Generación Rufino",       slug:"generacion-rufino",    fmt:"reels · perfil · TikTok",             color:"#e65100" },
  { code:"P05", name:"Seguimiento de promesas", slug:"seguimiento-promesas", fmt:"fact-check · seguimiento · archivo",  color:"#6a1b9a" },
  { code:"P06", name:"Contexto y datos",        slug:"contexto-datos",       fmt:"datos · placas · explicador",         color:"#00838f" },
];
const AGENTES = [
  { id:1,    nombre:"Agente SEO",          rol:"Rank Math · Schema · Keywords P01–P06",       autonomia:"Alto",  prompt:"Actuá como Agente SEO de El Rufino (Rufino, Santa Fe, 19.211 hab). Generá la configuración completa de Rank Math: Schema NewsMediaOrganization en JSON-LD, Open Graph, sitemap y keywords semilla para los 6 pilares P01-P06. URL objetivo: elrufino.com.ar." },
  { id:2,    nombre:"Agente Arquitectura", rol:"Categorías, slugs y entradas base",           autonomia:"Medio", prompt:"Actuá como Agente Arquitectura de El Rufino. Generá la arquitectura editorial completa: 6 categorías P01–P06 con slugs, 3 entradas base por categoría con la regla de las 2 capas, y 5 entradas evergreen." },
  { id:3,    nombre:"Agente Tema Visual",  rol:"PHP/CSS del child theme",                     autonomia:"Bajo",  prompt:"Actuá como Agente Tema Visual de El Rufino. Generá el código del child theme: style.css con paleta (#c0271b rojo, #1a1a1a negro, #f5f0e8 crema), functions.php con Playfair Display + Source Serif 4, CSS para header masthead rojo, ticker, grilla responsive. Tema padre: Newsup." },
  { id:4,    nombre:"Agente Planificador", rol:"Calendario editorial semanal",                autonomia:"Medio", prompt:"Actuá como Agente Planificador de El Rufino. Generá el calendario editorial para la próxima semana: día, pilar P01-P06, título con las 2 capas, formato, plataforma, audiencia target, fuentes sugeridas y tiempo de producción. Incluir resumen WA diario 7:30 AM." },
  { id:5,    nombre:"Agente Redactor",     rol:"Notas periodísticas listas para publicar",    autonomia:"Medio", prompt:"Actuá como Agente Redactor de El Rufino. Esperá el tema, datos y pilar. Redactá la nota completa con regla de 2 capas: título (hecho + contexto), bajada, cuerpo 600-800 palabras, cierre y tags. Tono: directo sin agresivo, verificado, local sin localista." },
  { id:"6A", nombre:"Agente TikTok",       rol:"Guiones 60 seg · hook nativo · tendencia",    autonomia:"Alto",  prompt:"Actuá como Agente TikTok de El Rufino. Convertís noticias locales en guiones TikTok de 45-60 seg. Hook nativo primeros 2 seg, desarrollo con transiciones rápidas, CTA con sonido tendencia. Audiencia 14-24 años. Formato: [TEXTO PANTALLA] acción en corchetes. Esperá el tema." },
  { id:"6B", nombre:"Agente Reels",        rol:"Guiones 30-60 seg · estética editorial · IG", autonomia:"Alto",  prompt:"Actuá como Agente Reels de El Rufino. Convertís noticias locales en guiones Reels Instagram de 30-60 seg. Hook visual primeros 3 seg, estética editorial (Playfair + crema), CTA a bio/link. Audiencia 22-40 años. Más contexto que TikTok. Esperá el tema." },
  { id:7,    nombre:"Agente Accountability",rol:"Promesas — nota + hilo + WA",               autonomia:"Medio", prompt:"Actuá como Agente Accountability de El Rufino. Redactá: 1) nota 600-800 palabras de seguimiento de promesa, 2) hilo Facebook 5-7 posts, 3) resumen WhatsApp máx 3 líneas. Protocolo verificador no opositor. Primero preguntame qué promesa cubrir." },
  { id:8,    nombre:"Agente Stack",        rol:"WordPress y plugins · configuración técnica", autonomia:"Bajo",  prompt:"Actuá como Agente Stack de El Rufino. Sos desarrollador WordPress para medios digitales. Configurás plugins, child theme Newsup, Customizer, menús. Generás código PHP/CSS funcional. ¿Por dónde empezamos?" },
  { id:9,    nombre:"Agente Servidor",     rol:"Hosting Hostinger · velocidad · SSL",         autonomia:"Bajo",  prompt:"Actuá como Agente Servidor de El Rufino. Diagnosticá elrufino.com.ar en Hostinger: velocidad, PHP version, caché, SSL. Objetivo: carga menor a 3 segundos en mobile." },
  { id:10,   nombre:"Agente Datos",        rol:"Pilar P06 · INDEC · SAMCO · presupuesto",    autonomia:"Medio", prompt:"Actuá como Agente Agenda de Datos de El Rufino. Generá la agenda de datos: fuentes verificables (INDEC, municipio, SAMCO, INTA, provincia), métricas clave por área y estructura para el Pilar P06." },
  { id:11,   nombre:"Agente Imágenes",     rol:"Prompts visuales · Gemini · MJ",              autonomia:"Medio", prompt:"Actuá como Agente Imágenes de El Rufino. Generás prompts para IAs de imágenes. Pedís especificaciones y devolvés el prompt optimizado en español e inglés con specs técnicas exactas." },
  { id:12,   nombre:"Agente Crisis",       rol:"Tragedia · Fake news · Desmentida",           autonomia:"Medio", prompt:"Actuá como Agente Crisis de El Rufino. PROTOCOLO_TRAGEDIA: no imágenes explícitas · esperar 2 fuentes · enfoque servicio no morbo. Para fake news: identificá el original, rastreá la deformación, redactá la desmentida. Decime qué situación enfrentamos." },
];
const WA_TEMPLATES = [
  { nombre:"Resumen matutino 7:30", texto:"☀️ *EL RUFINO* — {FECHA}\n\n{NOTICIA_1}\n{NOTICIA_2}\n{NOTICIA_3}\n\n📍 elrufino.com.ar" },
  { nombre:"Alerta urgente",        texto:"🔴 *ALERTA EL RUFINO*\n\n{HECHO}\n\n📍 Seguimos informando → elrufino.com.ar" },
  { nombre:"Seguimiento promesa",   texto:"📋 *SEGUIMIENTO* | P05\n\nPromesa: {PROMESA}\nEstado: {ESTADO}\n\n📍 elrufino.com.ar/promesas" },
];
const METRICAS = [
  { key:"fb_likes",    label:"Facebook Likes",       meta:"5.000 al mes 6" },
  { key:"ig_seg",      label:"Instagram Seguidores",  meta:"2.000 al mes 6" },
  { key:"wa_subs",     label:"WhatsApp Suscriptores", meta:"500 al mes 3"   },
  { key:"visitas_dia", label:"Visitas/día",            meta:"baseline Mes 0" },
  { key:"notas_pub",   label:"Notas publicadas",       meta:"20 para cerrar F2" },
];

// ── WP AJAX ──
async function wpAjax(action, params={}) {
  const body = new URLSearchParams({ action, nonce: window.ER?.nonce||'', ...params });
  const r = await fetch(window.ER?.ajax||'/wp-admin/admin-ajax.php', { method:'POST', body });
  return r.json();
}

// ── Claude via proxy PHP ──
async function callClaude(messages, system='') {
  if (!window.ER?.ajax) throw new Error('Sin configuración AJAX');

  const payload = {
    model: 'claude-sonnet-4-20250514',
    max_tokens: 1500,
    messages: messages,
  };
  if (system) payload.system = system;

  const body = new URLSearchParams({
    action: 'er_claude_proxy',
    nonce:  window.ER.nonce,
    payload: JSON.stringify(payload),  // enviamos como campo POST string
  });

  const response = await fetch(window.ER.ajax, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: body.toString(),
  });

  const result = await response.json();

  if (result.success && result.data) {
    if (result.data.content && Array.isArray(result.data.content)) {
      const text = result.data.content.map(b => b.text || '').join('');
      return text || 'Respuesta vacía';
    }
    return 'Sin respuesta';
  }
  throw new Error(result.data || 'Error en la llamada a Claude');
}

// ── Estilos ──
const S = {
  shell:   { display:'flex', height:'100%', background:PALETA.crema, fontFamily:"'Source Serif 4',Georgia,serif", overflow:'hidden' },
  sidebar: { width:200, flexShrink:0, background:PALETA.blanco, display:'flex', flexDirection:'column', borderRight:`2px solid ${PALETA.crema3}`, overflow:'hidden' },
  main:    { flex:1, display:'flex', flexDirection:'column', minWidth:0, overflow:'hidden' },
  topbar:  { background:'#161616', color:PALETA.crema, padding:'8px 20px', display:'flex', alignItems:'center', gap:10, flexShrink:0 },
  content: { flex:1, overflowY:'auto', padding:'20px 24px' },
  navFt:   { padding:'10px 20px', background:PALETA.crema2, display:'flex', alignItems:'center', justifyContent:'space-between', borderTop:`1px solid ${PALETA.crema3}`, flexShrink:0 },
  card:    { background:PALETA.blanco, borderRadius:6, border:`1px solid ${PALETA.crema3}`, padding:14, boxShadow:'0 1px 4px rgba(0,0,0,.08)' },
  btn: v => {
    const vs={ p:{background:PALETA.rojo,color:'#fff',border:'none'}, o:{background:'transparent',color:PALETA.rojo,border:`1px solid ${PALETA.rojo}`}, g:{background:'transparent',color:'#888',border:`1px solid ${PALETA.crema3}`}, b:{background:PALETA.azul,color:'#fff',border:'none'}, gr:{background:'#1a1a1a',color:'#ccc',border:'none'} };
    return { fontSize:11, padding:'6px 12px', borderRadius:4, cursor:'pointer', fontFamily:"'Source Serif 4',serif", fontWeight:600, display:'inline-flex', alignItems:'center', gap:4, transition:'all .13s', ...vs[v||'p'] };
  },
  badge: c => {
    const cs={ g:{bg:'#e8f5e9',text:PALETA.verde}, y:{bg:'#fff3e0',text:PALETA.amber}, r:{bg:'#ffebee',text:'#c62828'}, b:{bg:'#e3f2fd',text:PALETA.azul}, gr:{bg:'#f5f5f5',text:'#555'} };
    const x=cs[c]||cs.gr;
    return { display:'inline-flex', alignItems:'center', fontSize:8, padding:'2px 7px', borderRadius:10, fontWeight:700, letterSpacing:'.8px', textTransform:'uppercase', fontFamily:'monospace', background:x.bg, color:x.text };
  },
};

// ── Componentes auxiliares ──
function SLabel({children,style}) { return <div style={{fontSize:9,letterSpacing:'2.5px',textTransform:'uppercase',color:PALETA.rojo,fontWeight:700,marginBottom:10,fontFamily:'monospace',...style}}>{children}</div>; }
function Divider() { return <div style={{height:1,background:PALETA.crema3,margin:'16px 0'}}/>; }
function Warn({title,children}) { return <div style={{background:'#fffbf0',border:'1px solid #ffe082',borderRadius:5,padding:'10px 12px',marginTop:8}}><div style={{fontSize:9,fontWeight:700,color:PALETA.amber,letterSpacing:'1.5px',textTransform:'uppercase',marginBottom:3,fontFamily:'monospace'}}>{title}</div><p style={{fontSize:11,color:'#5d4037',lineHeight:1.6,margin:0}}>{children}</p></div>; }
function Info({title,children}) { return <div style={{background:'#f4f8ff',border:'1px solid #c5d9f0',borderRadius:5,padding:'10px 12px',marginTop:8}}><div style={{fontSize:9,fontWeight:700,color:PALETA.azul,letterSpacing:'1.5px',textTransform:'uppercase',marginBottom:3,fontFamily:'monospace'}}>{title}</div><p style={{fontSize:11,color:PALETA.azul,lineHeight:1.6,margin:0}}>{children}</p></div>; }
function Toast({msg}) { if(!msg)return null; return <div style={{position:'fixed',bottom:20,left:'50%',transform:'translateX(-50%)',background:PALETA.negro,color:PALETA.crema,fontSize:11,padding:'8px 18px',borderRadius:20,zIndex:9999,border:`1px solid ${PALETA.rojo}`,boxShadow:'0 4px 16px rgba(0,0,0,.3)',whiteSpace:'nowrap',pointerEvents:'none'}}>{msg}</div>; }
function Loader({loading,label='Generando...'}) { if(!loading)return null; return <div style={{display:'flex',alignItems:'center',gap:10,padding:'10px 12px',background:'#fff',border:`1px solid ${PALETA.crema3}`,borderRadius:6,marginTop:8}}><div style={{display:'flex',gap:3}}>{[0,1,2].map(i=><span key={i} style={{width:6,height:6,borderRadius:'50%',background:PALETA.rojo,animation:`pulse 1.2s ease-in-out ${i*.2}s infinite`}}/>)}</div><span style={{fontSize:11,color:'#888',fontFamily:'monospace'}}>{label}</span><style>{`@keyframes pulse{0%,80%,100%{opacity:.3;transform:scale(.8)}40%{opacity:1;transform:scale(1)}}`}</style></div>; }
function H1({children}) { return <div style={{fontFamily:"'Playfair Display',serif",fontSize:26,color:PALETA.negro,lineHeight:1.1,marginBottom:8}}>{children}</div>; }
function Sub({children}) { return <div style={{fontSize:12,color:'#666',lineHeight:1.7,maxWidth:600,marginBottom:14}}>{children}</div>; }
function ModTag({n,section}) { return <div style={{fontSize:8,color:PALETA.rojo,letterSpacing:'3px',textTransform:'uppercase',fontWeight:700,marginBottom:4,fontFamily:'monospace'}}>{`PANTALLA ${n} · ${section}`}</div>; }

// ── Nav item ──
function NavItem({label,active,onClick,badge}) {
  return <div onClick={onClick} style={{display:'flex',alignItems:'center',gap:7,padding:'6px 12px',cursor:'pointer',borderLeft:active?`2px solid ${PALETA.rojo}`:'2px solid transparent',background:active?'#fff0ef':'transparent',color:active?PALETA.rojo:PALETA.negro,fontSize:11,fontWeight:active?600:400,transition:'all .12s'}}>
    <span style={{width:4,height:4,borderRadius:'50%',background:active?PALETA.rojo:'#ddd',flexShrink:0}}/>
    {label}
    {badge&&<span style={{marginLeft:'auto',fontSize:9,padding:'1px 5px',borderRadius:8,fontWeight:700,fontFamily:'monospace',background:'#fff0ef',color:PALETA.rojo}}>{badge}</span>}
  </div>;
}

// ══════════════════════════════════════════════
// PANTALLA 1: DASHBOARD
// ══════════════════════════════════════════════
function PantallaDashboard({showToast}) {
  const [checklist, setChecklist] = useState(window.ER?.checklist||{cats:false,logo:true,schema:false,notas:false,wa:false});
  const [metrics, setMetrics] = useState({});
  const [importing, setImporting] = useState(false);
  const [apikey, setApikey] = useState('');
  const [savingKey, setSavingKey] = useState(false);
  const [keyStatus, setKeyStatus] = useState(null); // null=loading, {configured,masked}

  // Verificar estado de la API key al montar
  useEffect(() => {
    if (window.ER?.ajax) {
      wpAjax('er_apikey_status').then(r => {
        if (r.success) setKeyStatus(r.data);
        else setKeyStatus({ configured: false });
      }).catch(() => setKeyStatus({ configured: false }));
    } else {
      setKeyStatus({ configured: false });
    }
  }, []);

  const saveApikey = async () => {
    if (!apikey.trim()) { showToast('Ingresá una API key válida'); return; }
    setSavingKey(true);
    try {
      const result = await wpAjax('er_save_apikey', { apikey });
      if (result.success) {
        showToast('✓ API key guardada');
        setKeyStatus({ configured: true, masked: result.data.masked });
        setApikey('');
      } else {
        showToast('Error: ' + (result.data || 'desconocido'));
      }
    } catch(e) { showToast('Error de conexión'); }
    finally { setSavingKey(false); }
  };

  const deleteApikey = async () => {
    if (!confirm('¿Eliminar la API key guardada?')) return;
    await wpAjax('er_delete_apikey');
    setKeyStatus({ configured: false });
    setApikey('');
    showToast('API key eliminada');
  };

  const items = [
    { key:'cats',   label:'6 categorías P01-P06' },
    { key:'logo',   label:'Logo/favicon/OG subidos' },
    { key:'schema', label:'Schema NewsMediaOrganization' },
    { key:'notas',  label:'20 notas publicadas' },
    { key:'wa',     label:'500 WA suscriptores' },
  ];
  const pct = Math.round(Object.values(checklist).filter(Boolean).length / items.length * 100);

  const toggle = async (key) => {
    const newVal = !checklist[key];
    setChecklist(p=>({...p,[key]:newVal}));
    if (window.ER?.ajax) {
      await wpAjax('er_update_checklist',{key,val:String(newVal)});
    }
  };

  const importDemo = async () => {
    if (!confirm('¿Importar 48 notas demo como borradores? Esta acción no se puede repetir.')) return;
    setImporting(true);
    try {
      const r = await wpAjax('er_import_demo');
      if (r.success) { showToast(`✓ ${r.data.created} notas importadas como borradores`); setChecklist(p=>({...p,notas:true})); }
      else showToast('Error: '+r.data);
    } catch(e) { showToast('Error de conexión'); }
    finally { setImporting(false); }
  };

  const saveApikey_old_removed = null; // eliminado, reemplazado por el de arriba

  return <div>
    <ModTag n="1" section="DASHBOARD"/>
    <H1>Panel El Rufino v8.1.2</H1>
    <div style={{background:PALETA.negro,borderRadius:8,padding:20,marginBottom:16,display:'flex',gap:16,alignItems:'center'}}>
      <div style={{width:52,height:52,flexShrink:0,background:PALETA.rojo,borderRadius:6,display:'flex',alignItems:'center',justifyContent:'center',fontFamily:"'Playfair Display',serif",fontWeight:900,fontSize:18,color:'#fff'}}>ER</div>
      <div>
        <div style={{fontFamily:"'Playfair Display',serif",color:PALETA.crema,fontSize:18,marginBottom:4}}>Sistema operativo de El Rufino</div>
        <div style={{color:'#aaa',fontSize:10,lineHeight:1.6}}>4 pantallas · 12 agentes · Kanban · Promesas · Importador demo</div>
        <div style={{display:'flex',gap:4,marginTop:8,flexWrap:'wrap'}}>
          <span style={S.badge('r')}>FASE 2 EN EJECUCIÓN</span>
          <span style={S.badge('b')}>Plugin v8.1.2</span>
          <span style={S.badge('g')}>Claude API</span>
        </div>
      </div>
    </div>

    <div style={{display:'grid',gridTemplateColumns:'repeat(4,1fr)',gap:8,marginBottom:16}}>
      {[{n:12,l:'Agentes'},{n:pct+'%',l:'Fase 2'},{n:4,l:'Pantallas'},{n:48,l:'Notas demo'}].map(({n,l})=>
        <div key={l} style={{...S.card,textAlign:'center'}}>
          <div style={{fontFamily:"'Playfair Display',serif",fontSize:20,color:PALETA.rojo,fontWeight:900}}>{n}</div>
          <div style={{fontSize:9,color:'#888',marginTop:2,textTransform:'uppercase',letterSpacing:1,fontFamily:'monospace'}}>{l}</div>
        </div>
      )}
    </div>

    <SLabel>Checklist Fase 2 — {pct}% completado</SLabel>
    <div style={{...S.card,marginBottom:14}}>
      {items.map(({key,label})=>
        <div key={key} style={{display:'flex',alignItems:'center',gap:10,padding:'8px 0',borderBottom:`1px solid ${PALETA.crema3}`}}>
          <div onClick={()=>toggle(key)} style={{width:20,height:20,borderRadius:3,display:'flex',alignItems:'center',justifyContent:'center',fontSize:11,fontWeight:700,flexShrink:0,cursor:'pointer',background:checklist[key]?'#e8f5e9':'#ffebee',color:checklist[key]?PALETA.verde:'#c62828'}}>
            {checklist[key]?'✓':'✗'}
          </div>
          <span style={{fontSize:11,flex:1}}>{label}</span>
          <span style={S.badge(checklist[key]?'g':'r')}>{checklist[key]?'OK':'Pendiente'}</span>
        </div>
      )}
    </div>

    <div style={{display:'grid',gridTemplateColumns:'1fr 1fr',gap:12,marginBottom:14}}>
      <div style={S.card}>
        <SLabel>Importar notas demo</SLabel>
        <p style={{fontSize:11,color:'#666',lineHeight:1.6,marginBottom:10}}>48 borradores reales (8 × 6 pilares) basados en noticias verificadas de Rufino y General López, marzo–abril 2026.</p>
        <button onClick={importDemo} disabled={importing} style={{...S.btn('p'),opacity:importing?.6:1}}>
          {importing?'Importando...':'↓ Importar 48 notas demo'}
        </button>
      </div>
      <div style={S.card}>
        <SLabel>API Key Claude (Anthropic)</SLabel>
        {keyStatus === null && <div style={{fontSize:11,color:'#aaa',fontFamily:'monospace',padding:'4px 0'}}>Verificando...</div>}
        {keyStatus?.configured ? (
          <div>
            <div style={{display:'flex',alignItems:'center',gap:8,padding:'7px 10px',background:'#e8f5e9',border:'1px solid #a5d6a7',borderRadius:5,marginBottom:10}}>
              <span style={{fontSize:13,color:'#2e7d32'}}>✓</span>
              <span style={{fontSize:11,color:'#2e7d32',fontFamily:'monospace',flex:1}}>Activa: {keyStatus.masked}</span>
            </div>
            <div style={{display:'flex',gap:6}}>
              <button onClick={()=>setKeyStatus({configured:false})} style={{...S.btn('o'),fontSize:10}}>Cambiar key</button>
              <button onClick={deleteApikey} style={{...S.btn('g'),fontSize:10,color:'#e53935'}}>✕ Eliminar</button>
            </div>
          </div>
        ) : keyStatus && !keyStatus.configured ? (
          <div>
            <p style={{fontSize:11,color:'#666',lineHeight:1.6,marginBottom:8}}>Pegá tu clave Anthropic (sk-ant-...) para activar el Chat IA.</p>
            <input value={apikey} onChange={e=>setApikey(e.target.value)} onKeyDown={e=>{if(e.key==='Enter')saveApikey();}} type="password" placeholder="sk-ant-api03-..." style={{width:'100%',padding:'6px 8px',border:`1px solid ${PALETA.crema3}`,borderRadius:4,fontSize:11,fontFamily:'monospace',marginBottom:8,boxSizing:'border-box'}}/>
            <button onClick={saveApikey} disabled={savingKey||!apikey.trim()} style={{...S.btn('b'),opacity:(savingKey||!apikey.trim())?.5:1}}>{savingKey?'Guardando...':'Guardar key'}</button>
          </div>
        ) : null}
      </div>
    </div>

    <SLabel>Métricas Mes 0</SLabel>
    <div style={{display:'grid',gridTemplateColumns:'1fr 1fr',gap:8,marginBottom:14}}>
      {METRICAS.map(m=>
        <div key={m.key} style={S.card}>
          <div style={{fontSize:9,color:'#aaa',fontFamily:'monospace',marginBottom:4}}>{m.label}</div>
          <input type="text" placeholder="Valor actual..." value={metrics[m.key]||''} onChange={e=>setMetrics(p=>({...p,[m.key]:e.target.value}))} style={{width:'100%',padding:'5px 8px',border:`1px solid ${PALETA.crema3}`,borderRadius:4,fontSize:11,fontFamily:'monospace'}}/>
          <div style={{fontSize:9,color:PALETA.rojo,marginTop:3,fontFamily:'monospace'}}>Meta: {m.meta}</div>
        </div>
      )}
    </div>

    <Info title="Claude API directo">El sistema usa Claude Sonnet 4 via API Anthropic. Ingresá tu clave sk-ant- para activar todos los agentes.</Info>
  </div>;
}

// ══════════════════════════════════════════════
// PANTALLA 2: PRODUCCIÓN
// ══════════════════════════════════════════════
function PantallaProduccion({showToast}) {
  const [tab, setTab] = useState('chat');
  const tabs = [{id:'chat',label:'Chat IA'},{id:'redactor',label:'Redactor'},{id:'kanban',label:'Kanban'},{id:'wa',label:'WhatsApp'}];
  return <div style={{height:'100%',display:'flex',flexDirection:'column'}}>
    <ModTag n="2" section="PRODUCCIÓN"/>
    <H1>Producción</H1>
    <div style={{display:'flex',gap:4,marginBottom:14,flexWrap:'wrap'}}>
      {tabs.map(t=><button key={t.id} onClick={()=>setTab(t.id)} style={{...S.btn(tab===t.id?'p':'g'),fontSize:10}}>{t.label}</button>)}
    </div>
    <div style={{flex:1,overflow:'hidden'}}>
      {tab==='chat'    && <TabChat showToast={showToast}/>}
      {tab==='redactor'&& <TabRedactor showToast={showToast}/>}
      {tab==='kanban'  && <TabKanban showToast={showToast}/>}
      {tab==='wa'      && <TabWA showToast={showToast}/>}
    </div>
  </div>;
}

function TabChat({showToast}) {
  const [messages,setMessages]=useState([]);
  const [input,setInput]=useState('');
  const [loading,setLoading]=useState(false);
  const [sys,setSys]=useState(`Sos el asistente editorial de El Rufino, medio digital local de Rufino, Santa Fe (19.211 hab). Claim: "Lo que pasa y lo que significa". Pilares P01-P06. Regla madre: toda pieza tiene 2 capas (hecho verificado + contexto). Voz: directa, verificada, local sin localista.`);
  const endRef=useRef(null);
  useEffect(()=>{ endRef.current?.scrollIntoView({behavior:'smooth'}); },[messages]);
  const send=async()=>{
    if(!input.trim()||loading)return;
    const um={role:'user',content:input}; setMessages(p=>[...p,um]); setInput(''); setLoading(true);
    try{ 
      const r=await callClaude([...messages,um],sys); 
      setMessages(p=>[...p,{role:'assistant',content:r}]); 
    }
    catch(e){ 
      showToast('Error: ' + e.message); 
    } 
    finally{ 
      setLoading(false); 
    }
  };
  return <div style={{display:'flex',flexDirection:'column',height:'100%'}}>
    <textarea value={sys} onChange={e=>setSys(e.target.value)} style={{width:'100%',padding:'6px 8px',border:`1px solid ${PALETA.crema3}`,borderRadius:4,fontSize:10,fontFamily:'monospace',background:'#f8f7f4',resize:'vertical',minHeight:48,marginBottom:8,color:'#2d5a27'}}/>
    <div style={{flex:1,overflowY:'auto',background:'#fff',border:`1px solid ${PALETA.crema3}`,borderRadius:6,padding:12,marginBottom:8,minHeight:120}}>
      {!messages.length&&<div style={{textAlign:'center',color:'#bbb',fontSize:12,fontFamily:'monospace',paddingTop:30}}>Empezá la conversación ↓</div>}
      {messages.map((m,i)=><div key={i} style={{marginBottom:10,display:'flex',justifyContent:m.role==='user'?'flex-end':'flex-start'}}>
        <div style={{maxWidth:'80%',padding:'8px 12px',borderRadius:8,fontSize:11,lineHeight:1.7,background:m.role==='user'?PALETA.rojo:'#f5f0e8',color:m.role==='user'?'#fff':PALETA.negro,whiteSpace:'pre-wrap'}}>{m.content}</div>
      </div>)}
      <Loader loading={loading} label="Claude respondiendo..."/>
      <div ref={endRef}/>
    </div>
    <div style={{display:'flex',gap:6}}>
      <textarea value={input} onChange={e=>setInput(e.target.value)} onKeyDown={e=>{if(e.key==='Enter'&&!e.shiftKey){e.preventDefault();send();}}} placeholder="Enter para enviar · Shift+Enter para nueva línea" style={{flex:1,padding:'7px 10px',border:`1px solid ${PALETA.crema3}`,borderRadius:4,fontSize:11,resize:'none',minHeight:40}}/>
      <button onClick={send} disabled={loading} style={{...S.btn('p'),alignSelf:'flex-end',opacity:loading?.5:1}}>Enviar →</button>
    </div>
    {messages.length>0&&<button onClick={()=>setMessages([])} style={{...S.btn('g'),marginTop:6,fontSize:10}}>Limpiar chat</button>}
  </div>;
}

function TabRedactor({showToast}) {
  const [tema,setTema]=useState(''); const [pilar,setPilar]=useState('P01'); const [datos,setDatos]=useState(''); const [resultado,setResultado]=useState(''); const [loading,setLoading]=useState(false);
  const generar=async()=>{
    if(!tema.trim()){showToast('Ingresá el tema');return;} setLoading(true); setResultado('');
    try{
      const r=await callClaude([{role:'user',content:`Actuá como Agente Redactor de El Rufino (Rufino, Santa Fe, 19.211 hab). Regla 2 capas OBLIGATORIA.\nTema: ${tema}\nPilar: ${pilar}\nDatos: ${datos||'Sin datos'}\nRedactá: título 2 capas, bajada, cuerpo 600-800 palabras, tags, versión WA 3 líneas.`}]);
      setResultado(r);
    }catch(e){showToast('Error: ' + e.message);}finally{setLoading(false);}
  };
  return <div>
    <div style={{display:'grid',gridTemplateColumns:'1fr 1fr',gap:8,marginBottom:10}}>
      <div><label style={{fontSize:9,fontFamily:'monospace',color:'#aaa',display:'block',marginBottom:3}}>TEMA / HECHO *</label><input value={tema} onChange={e=>setTema(e.target.value)} placeholder="Ej: El municipio anunció obras en calle Belgrano" style={{width:'100%',padding:'7px 9px',border:`1px solid ${PALETA.crema3}`,borderRadius:4,fontSize:11}}/></div>
      <div><label style={{fontSize:9,fontFamily:'monospace',color:'#aaa',display:'block',marginBottom:3}}>PILAR</label>
        <select value={pilar} onChange={e=>setPilar(e.target.value)} style={{width:'100%',padding:'7px 9px',border:`1px solid ${PALETA.crema3}`,borderRadius:4,fontSize:11}}>
          {PILARES.map(p=><option key={p.code} value={p.code}>{p.code} — {p.name}</option>)}
        </select>
      </div>
    </div>
    <textarea value={datos} onChange={e=>setDatos(e.target.value)} placeholder="Pegá datos, citas, declaraciones..." style={{width:'100%',padding:'7px 9px',border:`1px solid ${PALETA.crema3}`,borderRadius:4,fontSize:11,resize:'vertical',minHeight:70,marginBottom:8}}/>
    <button onClick={generar} disabled={loading} style={{...S.btn('p'),opacity:loading?.6:1}}>{loading?'Generando...':'Generar nota con IA →'}</button>
    <Loader loading={loading} label="Claude redactando con regla de 2 capas..."/>
    {resultado&&<div style={{marginTop:12}}>
      <SLabel>Nota generada</SLabel>
      <div style={{background:'#fff',border:`1px solid ${PALETA.crema3}`,borderRadius:6,padding:14,fontSize:11,lineHeight:1.8,whiteSpace:'pre-wrap',maxHeight:320,overflowY:'auto'}}>{resultado}</div>
      <div style={{display:'flex',gap:6,marginTop:8}}>
        <button onClick={()=>{navigator.clipboard.writeText(resultado);showToast('Copiado ✓');}} style={S.btn('o')}>Copiar</button>
        <button onClick={()=>setResultado('')} style={S.btn('g')}>Limpiar</button>
      </div>
    </div>}
  </div>;
}

function TabKanban({showToast}) {
  const COLS=["Idea","En producción","Edición","Publicado"];
  const [cards,setCards]=useState([
    {id:1,titulo:'Obras en acceso sur — estado real',pilar:'P01',formato:'nota',col:'Idea'},
    {id:2,titulo:'Precio soja en Rufino vs ROFEX',pilar:'P02',formato:'dato',col:'En producción'},
    {id:3,titulo:'Barrio Norte: promesa de asfalto 2024',pilar:'P05',formato:'seguimiento',col:'Edición'},
  ]);
  const [newCard,setNewCard]=useState({titulo:'',pilar:'P01',formato:'nota'});
  const [dragging,setDragging]=useState(null);
  const colColors={"Idea":"#f5f0e8","En producción":"#fff3e0","Edición":"#e3f2fd","Publicado":"#e8f5e9"};
  const pilColors=PILARES.reduce((a,p)=>{a[p.code]=p.color;return a},{});
  return <div>
    <div style={{...S.card,marginBottom:12,display:'grid',gridTemplateColumns:'1fr auto auto auto',gap:7,alignItems:'end'}}>
      <div><label style={{fontSize:9,fontFamily:'monospace',color:'#aaa',display:'block',marginBottom:3}}>TÍTULO</label><input value={newCard.titulo} onChange={e=>setNewCard(p=>({...p,titulo:e.target.value}))} placeholder="Nueva nota..." style={{width:'100%',padding:'6px 8px',border:`1px solid ${PALETA.crema3}`,borderRadius:4,fontSize:11}}/></div>
      <select value={newCard.pilar} onChange={e=>setNewCard(p=>({...p,pilar:e.target.value}))} style={{padding:'6px 8px',border:`1px solid ${PALETA.crema3}`,borderRadius:4,fontSize:11}}>{PILARES.map(p=><option key={p.code} value={p.code}>{p.code}</option>)}</select>
      <select value={newCard.formato} onChange={e=>setNewCard(p=>({...p,formato:e.target.value}))} style={{padding:'6px 8px',border:`1px solid ${PALETA.crema3}`,borderRadius:4,fontSize:11}}>{['nota','hilo','dato','video','seguimiento'].map(f=><option key={f}>{f}</option>)}</select>
      <button onClick={()=>{ if(!newCard.titulo.trim()){showToast('Ingresá título');return;} setCards(p=>[...p,{id:Date.now(),...newCard,col:'Idea'}]); setNewCard({titulo:'',pilar:'P01',formato:'nota'}); showToast('Agregado ✓'); }} style={S.btn('p')}>+ Agregar</button>
    </div>
    <div style={{display:'grid',gridTemplateColumns:'repeat(4,1fr)',gap:8}}>
      {COLS.map(col=><div key={col} onDragOver={e=>e.preventDefault()} onDrop={e=>{e.preventDefault();if(dragging)setCards(p=>p.map(c=>c.id===dragging?{...c,col}:c));setDragging(null);}} style={{background:colColors[col],borderRadius:6,padding:8,minHeight:160,border:`1px solid ${PALETA.crema3}`}}>
        <div style={{fontSize:8,fontWeight:700,color:'#888',textTransform:'uppercase',letterSpacing:'1.5px',fontFamily:'monospace',marginBottom:8,paddingBottom:6,borderBottom:`1px solid ${PALETA.crema3}`}}>{col} ({cards.filter(c=>c.col===col).length})</div>
        {cards.filter(c=>c.col===col).map(card=><div key={card.id} draggable onDragStart={()=>setDragging(card.id)} style={{background:'#fff',border:`1px solid ${PALETA.crema3}`,borderLeft:`3px solid ${pilColors[card.pilar]||PALETA.rojo}`,borderRadius:4,padding:'7px 8px',marginBottom:5,cursor:'grab'}}>
          <div style={{fontSize:10,color:PALETA.negro,lineHeight:1.4,marginBottom:4}}>{card.titulo}</div>
          <div style={{display:'flex',alignItems:'center',gap:4}}>
            <span style={{fontSize:7,padding:'1px 4px',borderRadius:6,background:`${pilColors[card.pilar]}18`,color:pilColors[card.pilar],fontWeight:700,fontFamily:'monospace'}}>{card.pilar}</span>
            <span style={{fontSize:9,color:'#aaa'}}>{card.formato}</span>
            <button onClick={()=>setCards(p=>p.filter(c=>c.id!==card.id))} style={{marginLeft:'auto',background:'none',border:'none',color:'#ccc',cursor:'pointer',fontSize:13}}>×</button>
          </div>
        </div>)}
      </div>)}
    </div>
  </div>;
}

function TabWA({showToast}) {
  const [tpl,setTpl]=useState(0);
  const [texto,setTexto]=useState(WA_TEMPLATES[0].texto);
  const [loading,setLoading]=useState(false);
  const gen=async()=>{
    setLoading(true);
    try{ const r=await callClaude([{role:'user',content:'Actuá como redactor de El Rufino (Rufino, Santa Fe). Generá el resumen matutino para WhatsApp: 3 noticias relevantes de una ciudad pampeana de 19k hab, 2 líneas por noticia, tono directo humano, sin markdown, listo para broadcast. Inventá noticias verosímiles.'}]); setTexto(r); }
    catch(e){showToast('Error: ' + e.message);}finally{setLoading(false);}
  };
  return <div style={{display:'grid',gridTemplateColumns:'1fr 1fr',gap:14}}>
    <div>
      <SLabel>Plantillas</SLabel>
      <div style={{display:'flex',gap:4,marginBottom:10,flexWrap:'wrap'}}>{WA_TEMPLATES.map((t,i)=><button key={i} onClick={()=>{setTpl(i);setTexto(t.texto);}} style={{...S.btn(i===tpl?'p':'g'),fontSize:10}}>{t.nombre}</button>)}</div>
      <textarea value={texto} onChange={e=>setTexto(e.target.value)} style={{width:'100%',padding:'8px 10px',border:`1px solid ${PALETA.crema3}`,borderRadius:5,fontSize:11,fontFamily:'monospace',background:'#f8f7f4',resize:'vertical',minHeight:160,lineHeight:1.7}}/>
      <div style={{display:'flex',gap:6,marginTop:8}}>
        <button onClick={gen} disabled={loading} style={{...S.btn('p'),opacity:loading?.6:1}}>IA → generar</button>
        <button onClick={()=>{navigator.clipboard.writeText(texto);showToast('Copiado ✓');}} style={S.btn('o')}>Copiar</button>
      </div>
      <Loader loading={loading} label="Generando resumen matutino..."/>
    </div>
    <div>
      <SLabel>Preview</SLabel>
      <div style={{background:'#e5ddd5',borderRadius:8,padding:12}}>
        <div style={{background:'#25d366',color:'#fff',borderRadius:'8px 8px 0 0',padding:'7px 12px',fontSize:11,fontWeight:700,display:'flex',alignItems:'center',gap:7}}>
          <div style={{width:24,height:24,borderRadius:'50%',background:PALETA.rojo,display:'flex',alignItems:'center',justifyContent:'center',fontSize:9,fontWeight:900,color:'#fff'}}>ER</div>
          EL RUFINO
        </div>
        <div style={{background:'#fff',borderRadius:'0 0 8px 8px',padding:'10px 12px',fontSize:11,lineHeight:1.7,whiteSpace:'pre-wrap',minHeight:100}}>{texto||<span style={{color:'#ccc'}}>Vista previa...</span>}</div>
      </div>
      <div style={{fontSize:9,color:'#aaa',fontFamily:'monospace',marginTop:6}}>{texto.length} caracteres</div>
    </div>
  </div>;
}

// ══════════════════════════════════════════════
// PANTALLA 3: INTELIGENCIA
// ══════════════════════════════════════════════
function PantallaInteligencia({showToast}) {
  const [tab,setTab]=useState('agentes');
  return <div>
    <ModTag n="3" section="INTELIGENCIA"/>
    <H1>Inteligencia IA</H1>
    <div style={{display:'flex',gap:4,marginBottom:14}}>
      {[{id:'agentes',label:'12 Agentes'},{id:'contexto',label:'Contexto IA'}].map(t=><button key={t.id} onClick={()=>setTab(t.id)} style={{...S.btn(tab===t.id?'p':'g'),fontSize:10}}>{t.label}</button>)}
    </div>
    {tab==='agentes' && <TabAgentes showToast={showToast}/>}
    {tab==='contexto'&& <TabContexto showToast={showToast}/>}
  </div>;
}

function TabAgentes({showToast}) {
  const [active,setActive]=useState(null); const [output,setOutput]=useState(''); const [loading,setLoading]=useState(false); const [extra,setExtra]=useState('');
  const activar=async(a)=>{
    setActive(a);setOutput('');setLoading(true);
    try{ const r=await callClaude([{role:'user',content:extra?`${a.prompt}\n\nDatos adicionales: ${extra}`:a.prompt}]); setOutput(r); }
    catch(e){showToast('Error: ' + e.message);}finally{setLoading(false);}
  };
  const cats=[{label:'Editoriales (1–7)',ids:[1,2,4,5,'6A','6B',7]},{label:'Técnicos (8–9)',ids:[8,9]},{label:'Contenido (10–12)',ids:[10,11,12]}];
  const autoColor={'Alto':PALETA.verde,'Medio':PALETA.amber,'Bajo':PALETA.azul};
  return <div>
    <textarea value={extra} onChange={e=>setExtra(e.target.value)} placeholder="Contexto adicional para el agente (opcional)..." style={{width:'100%',padding:'7px 9px',border:`1px solid ${PALETA.crema3}`,borderRadius:4,fontSize:11,fontFamily:"'Source Serif 4',serif",resize:'vertical',minHeight:44,marginBottom:12}}/>
    {cats.map(cat=><div key={cat.label}>
      <div style={{display:'flex',alignItems:'center',gap:8,margin:'14px 0 8px'}}>
        <div style={{flex:1,height:1,background:PALETA.crema3}}/>
        <span style={{fontSize:9,color:'#bbb',fontFamily:'monospace',letterSpacing:1,textTransform:'uppercase'}}>{cat.label}</span>
        <div style={{flex:1,height:1,background:PALETA.crema3}}/>
      </div>
      {AGENTES.filter(a=>cat.ids.includes(a.id)).map(a=><div key={a.id} style={{...S.card,marginBottom:6,borderLeft:`3px solid ${a.id===12?PALETA.rojo:(a.id>=8&&a.id<=9)?PALETA.azul:PALETA.crema3}`}}>
        <div style={{display:'flex',alignItems:'center',gap:8}}>
          <div style={{background:a.id===12?PALETA.rojo:(a.id>=8&&a.id<=9)?PALETA.azul:PALETA.negro,color:'#fff',fontSize:8,fontWeight:800,width:20,height:20,borderRadius:'50%',display:'flex',alignItems:'center',justifyContent:'center',flexShrink:0,fontFamily:'monospace'}}>{a.id}</div>
          <div style={{flex:1}}>
            <div style={{fontFamily:"'Playfair Display',serif",fontSize:12,color:PALETA.negro,fontWeight:700}}>{a.nombre}</div>
            <div style={{fontSize:9,color:PALETA.rojo}}>{a.rol}</div>
          </div>
          <span style={{fontSize:7,padding:'1px 5px',borderRadius:7,background:`${autoColor[a.autonomia]}18`,color:autoColor[a.autonomia],fontWeight:700,fontFamily:'monospace'}}>{a.autonomia}</span>
          <button onClick={()=>activar(a)} disabled={loading} style={{...S.btn('o'),fontSize:9,padding:'3px 8px',opacity:loading?.5:1}}>Activar ↗</button>
        </div>
      </div>)}
    </div>)}
    <Warn title="⏸ Fase 3 (no activar)">Agente SEO por nota y Agente Monetización — después de consolidar hábito de audiencia.</Warn>
    {(loading||output)&&<div style={{marginTop:14}}>
      {active&&<SLabel>Agente {active.id} — {active.nombre}</SLabel>}
      <Loader loading={loading} label={`Agente ${active?.id} trabajando...`}/>
      {output&&<><div style={{background:'#fff',border:`1px solid ${PALETA.crema3}`,borderRadius:6,padding:12,fontSize:11,lineHeight:1.8,whiteSpace:'pre-wrap',maxHeight:320,overflowY:'auto'}}>{output}</div>
        <div style={{display:'flex',gap:6,marginTop:8}}>
          <button onClick={()=>{navigator.clipboard.writeText(output);showToast('Copiado ✓');}} style={S.btn('o')}>Copiar</button>
          <button onClick={()=>{setOutput('');setActive(null);}} style={S.btn('g')}>Limpiar</button>
        </div></>}
    </div>}
  </div>;
}

function TabContexto({showToast}) {
  const def=`PROYECTO = EL RUFINO
CONTEXTO_IA_VIGENTE = v1.3 · 12-abr-2026
CIUDAD = Rufino, Santa Fe, Argentina · 19.211 hab INDEC 2022 · ~7.200 hogares · ~14.000 FB activos
CLAIM = "Lo que pasa y lo que significa"
NO_HACER = portal total prematuro · panfleto frontal · publicar por ansiedad
PALETA = #c0271b rojo · #1a1a1a negro · #f5f0e8 crema
VOZ_EDITORIAL = directa sin agresiva · verificado · local sin localista · humano sin amarillista
DOMINIO = elrufino.com.ar (Hostinger · WordPress)
PLUGIN = v8.1.1 · 4 pantallas · 12 agentes · 48 notas demo · OpenRouter · checklist activo
FASE_ACTUAL = Fase 2 EN EJECUCIÓN
FASE_2_OK_CUANDO = 6 cats P01-P06 ✓ + logo ✓ + schema ✓ + 20 notas + 500 WA subs
PILARES = P01 Lo que pasa · P02 El campo habla · P03 Barrio a barrio · P04 Generación Rufino · P05 Seguimiento promesas · P06 Contexto y datos
REGLA_DOS_CAPAS = OBLIGATORIA: Capa1 hecho verificado · Capa2 qué significa/contexto/pregunta
AGENTES = 12 operativos · 6A TikTok · 6B Reels · 12 Crisis
DECISIONES = NO reabrir sin indicación explícita`;
  const [ctx,setCtx]=useState(def);
  return <div>
    <Sub>Bloque de transferencia rápida. Copiá y pegá al inicio de cada sesión nueva de IA.</Sub>
    <div style={{background:'#f8f7f4',border:`1px solid ${PALETA.crema3}`,borderRadius:6,overflow:'hidden'}}>
      <div style={{background:'#111',padding:'7px 12px',display:'flex',alignItems:'center',justifyContent:'space-between'}}>
        <span style={{fontSize:9,color:'#888',fontFamily:'monospace'}}>EL RUFINO · CONTEXT BLOCK · v1.3</span>
        <div style={{display:'flex',gap:4}}>
          <button onClick={()=>setCtx(def)} style={{fontSize:9,padding:'2px 7px',background:'#1e1e1e',color:'#555',border:'1px solid #2a2a2a',borderRadius:3,cursor:'pointer'}}>Restaurar</button>
          <button onClick={()=>{navigator.clipboard.writeText(ctx);showToast('Copiado ✓');}} style={{fontSize:9,padding:'2px 7px',background:PALETA.rojo,color:'#fff',border:'none',borderRadius:3,cursor:'pointer'}}>Copiar todo</button>
        </div>
      </div>
      <textarea value={ctx} onChange={e=>setCtx(e.target.value)} spellCheck={false} style={{width:'100%',background:'#f8f7f4',border:'none',color:'#2d5a27',fontSize:10,fontFamily:'monospace',padding:12,lineHeight:1.7,resize:'vertical',minHeight:280,outline:'none'}}/>
    </div>
    <Info title="Cómo usarlo">Al inicio de cada sesión nueva, copiá este bloque y pegalo antes del pedido. Actualizá la versión (v1.3 → v1.4) cada vez que tomés una decisión nueva.</Info>
  </div>;
}

// ══════════════════════════════════════════════
// PANTALLA 4: SEGUIMIENTO
// ══════════════════════════════════════════════
function PantallaSeguimiento({showToast}) {
  const [tab,setTab]=useState('promesas');
  return <div>
    <ModTag n="4" section="SEGUIMIENTO"/>
    <H1>Seguimiento</H1>
    <div style={{display:'flex',gap:4,marginBottom:14}}>
      {[{id:'promesas',label:'Promesas'},{id:'redes',label:'Redes'},{id:'contenido',label:'Contenido'},{id:'roadmap',label:'Roadmap'}].map(t=><button key={t.id} onClick={()=>setTab(t.id)} style={{...S.btn(tab===t.id?'p':'g'),fontSize:10}}>{t.label}</button>)}
    </div>
    {tab==='promesas' && <TabPromesas showToast={showToast}/>}
    {tab==='redes'    && <TabRedes/>}
    {tab==='contenido'&& <TabContenido/>}
    {tab==='roadmap'  && <TabRoadmap/>}
  </div>;
}

function TabPromesas({showToast}) {
  const [promesas,setPromesas]=useState([]);
  const [showForm,setShowForm]=useState(false);
  const [form,setForm]=useState({promesa:'',quien:'',fecha:'',pilar:'P05',fuente:'',evidencia:''});
  const [loading,setLoading]=useState(false);
  const [nota,setNota]=useState('');
  useEffect(()=>{
    if(window.ER?.ajax) wpAjax('er_get_promesas').then(r=>{ if(r.success)setPromesas(r.data||[]); });
  },[]);
  const save=async()=>{
    if(!form.promesa||!form.quien||!form.fecha){showToast('Completá campos obligatorios');return;}
    const p={...form,id:Date.now(),estado:'Abierta',codigo:'P'+String(promesas.length+1).padStart(3,'0')};
    if(window.ER?.ajax){ const r=await wpAjax('er_save_promesa',{...form,codigo:p.codigo}); if(r.success)p.id=r.data.id; }
    setPromesas(prev=>[p,...prev]); setForm({promesa:'',quien:'',fecha:'',pilar:'P05',fuente:'',evidencia:''}); setShowForm(false); showToast('Ficha registrada ✓');
  };
  const updEstado=async(id,estado)=>{
    setPromesas(p=>p.map(x=>x.id===id?{...x,estado}:x));
    if(window.ER?.ajax) await wpAjax('er_update_estado',{id,estado});
  };
  const del=async(id)=>{
    setPromesas(p=>p.filter(x=>x.id!==id));
    if(window.ER?.ajax) await wpAjax('er_delete_promesa',{id});
    showToast('Ficha eliminada');
  };
  const exportCSV=async()=>{
    if(window.ER?.ajax){ const r=await wpAjax('er_export_promesas'); if(r.success){ const a=document.createElement('a'); a.href='data:text/csv;charset=utf-8,'+encodeURIComponent(r.data.csv); a.download='promesas-el-rufino.csv'; a.click(); } }
    else { const csv='ID,Promesa,Quién,Fecha,Pilar,Estado\n'+promesas.map(p=>`${p.id},"${p.promesa}","${p.quien}",${p.fecha},${p.pilar},${p.estado}`).join('\n'); const a=document.createElement('a'); a.href='data:text/csv;charset=utf-8,'+encodeURIComponent(csv); a.download='promesas-el-rufino.csv'; a.click(); }
  };
  const genNota=async()=>{
    if(!promesas.length){showToast('Registrá al menos una promesa');return;} setLoading(true);
    try{ const csv=promesas.map(p=>`${p.codigo}: ${p.promesa} | ${p.quien} | ${p.fecha} | ${p.estado}`).join('\n'); const r=await callClaude([{role:'user',content:`Actuá como Agente Accountability de El Rufino. Con este registro:\n\n${csv}\n\nRedactá: 1) Nota 600-800 palabras de la promesa más relevante, 2) Hilo Facebook 5-7 posts, 3) Resumen WA máx 3 líneas. Protocolo verificador no opositor.`}]); setNota(r); }
    catch(e){showToast('Error: ' + e.message);}finally{setLoading(false);}
  };
  const estadoColor={'Abierta':'y','En curso':'b','Cumplida':'g','Incumplida':'r','Parcial':'b'};
  return <div>
    <div style={{display:'flex',alignItems:'center',justifyContent:'space-between',marginBottom:10}}>
      <SLabel style={{margin:0}}>Fichas activas ({promesas.length})</SLabel>
      <div style={{display:'flex',gap:6}}>
        <button onClick={exportCSV} style={S.btn('g')}>↓ CSV</button>
        <button onClick={()=>setShowForm(p=>!p)} style={S.btn('p')}>+ Nueva ficha</button>
      </div>
    </div>
    {showForm&&<div style={{...S.card,marginBottom:12,borderLeft:`3px solid ${PALETA.rojo}`}}>
      <SLabel>Nueva ficha</SLabel>
      <textarea value={form.promesa} onChange={e=>setForm(p=>({...p,promesa:e.target.value}))} placeholder="Qué se prometió *" style={{width:'100%',padding:'7px 9px',border:`1px solid ${PALETA.crema3}`,borderRadius:4,fontSize:11,resize:'vertical',minHeight:50,marginBottom:8}}/>
      <div style={{display:'grid',gridTemplateColumns:'1fr 1fr',gap:8,marginBottom:8}}>
        <input value={form.quien} onChange={e=>setForm(p=>({...p,quien:e.target.value}))} placeholder="Quién prometió *" style={{padding:'7px 9px',border:`1px solid ${PALETA.crema3}`,borderRadius:4,fontSize:11}}/>
        <input type="date" value={form.fecha} onChange={e=>setForm(p=>({...p,fecha:e.target.value}))} style={{padding:'7px 9px',border:`1px solid ${PALETA.crema3}`,borderRadius:4,fontSize:11}}/>
      </div>
      <div style={{display:'grid',gridTemplateColumns:'1fr 1fr',gap:8,marginBottom:8}}>
        <select value={form.pilar} onChange={e=>setForm(p=>({...p,pilar:e.target.value}))} style={{padding:'7px 9px',border:`1px solid ${PALETA.crema3}`,borderRadius:4,fontSize:11}}>{PILARES.map(p=><option key={p.code} value={p.code}>{p.code}</option>)}</select>
        <input value={form.fuente} onChange={e=>setForm(p=>({...p,fuente:e.target.value}))} placeholder="Fuente verificable" style={{padding:'7px 9px',border:`1px solid ${PALETA.crema3}`,borderRadius:4,fontSize:11}}/>
      </div>
      <div style={{display:'flex',gap:6}}><button onClick={save} style={S.btn('p')}>Guardar</button><button onClick={()=>setShowForm(false)} style={S.btn('g')}>Cancelar</button></div>
    </div>}
    {!promesas.length?<div style={{textAlign:'center',padding:24,color:'#bbb',fontSize:12,fontFamily:'monospace'}}>No hay fichas. Cada anuncio oficial abre una ficha.</div>:
      promesas.map(p=><div key={p.id} style={{...S.card,marginBottom:6}}>
        <div style={{display:'flex',alignItems:'flex-start',gap:7,marginBottom:5}}>
          <span style={{fontSize:9,fontFamily:'monospace',color:'#bbb',background:'#f5f5f5',padding:'1px 5px',borderRadius:3}}>{p.codigo}</span>
          <div style={{fontFamily:"'Playfair Display',serif",fontSize:12,color:PALETA.negro,flex:1}}>{p.promesa}</div>
          <span style={S.badge(estadoColor[p.estado]||'gr')}>{p.estado}</span>
        </div>
        <div style={{fontSize:9,color:'#888',fontFamily:'monospace',marginBottom:6}}>{p.quien} · {p.fecha} · {p.pilar}</div>
        <div style={{display:'flex',gap:4,flexWrap:'wrap'}}>
          {['En curso','Cumplida','Incumplida'].map(e=><button key={e} onClick={()=>updEstado(p.id,e)} style={{...S.btn('g'),fontSize:9,padding:'2px 7px'}}>{e}</button>)}
          <button onClick={()=>del(p.id)} style={{...S.btn('g'),fontSize:9,padding:'2px 7px',marginLeft:'auto',color:'#e53935'}}>×</button>
        </div>
      </div>)
    }
    <div style={{display:'flex',gap:7,marginTop:12}}>
      <button onClick={genNota} disabled={loading} style={{...S.btn('o'),opacity:loading?.6:1}}>Generar nota de seguimiento ↗</button>
    </div>
    <Loader loading={loading} label="Agente Accountability trabajando..."/>
    {nota&&<div style={{marginTop:10}}>
      <SLabel>Nota generada</SLabel>
      <div style={{background:'#fff',border:`1px solid ${PALETA.crema3}`,borderRadius:6,padding:12,fontSize:11,lineHeight:1.8,whiteSpace:'pre-wrap',maxHeight:280,overflowY:'auto'}}>{nota}</div>
      <button onClick={()=>{navigator.clipboard.writeText(nota);showToast('Copiado ✓');}} style={{...S.btn('o'),marginTop:8}}>Copiar</button>
    </div>}
  </div>;
}

function TabRedes() {
  const plats=[
    {name:'Facebook',    meta:'Base · 25–60 años · 3–5 posts/día',   status:'y',  sl:'CONFIGURAR', kpi:'5.000', kl:'likes · mes 6'},
    {name:'Instagram',   meta:'Marca visual · 18–40 años · 1/día',   status:'y',  sl:'CONFIGURAR', kpi:'2.000', kl:'seg · mes 6'},
    {name:'WhatsApp Canal',meta:'Distribución directa · 7:30 AM',    status:'r',  sl:'CRÍTICO',    kpi:'500',   kl:'subs · mes 3'},
    {name:'TikTok (6A)', meta:'14–24 años · hook nativo · 3-4/sem',  status:'gr', sl:'HORIZONTE',  kpi:'1',     kl:'viral · 60d'},
    {name:'Reels (6B)',  meta:'22–40 años · estética ER · 3-4/sem',  status:'gr', sl:'HORIZONTE',  kpi:'1',     kl:'viral · 60d'},
  ];
  return <div>
    {plats.map((p,i)=><div key={i} style={{...S.card,marginBottom:6,display:'grid',gridTemplateColumns:'1fr auto',gap:8,alignItems:'start'}}>
      <div>
        <div style={{fontFamily:"'Playfair Display',serif",fontSize:12,color:PALETA.negro,fontWeight:700,marginBottom:2}}>{p.name}</div>
        <div style={{fontSize:10,color:'#888',marginBottom:4}}>{p.meta}</div>
        <span style={S.badge(p.status)}>{p.sl}</span>
      </div>
      <div style={{textAlign:'right'}}>
        <div style={{fontFamily:"'Playfair Display',serif",fontSize:18,color:PALETA.rojo,fontWeight:900}}>{p.kpi}</div>
        <div style={{fontSize:9,color:'#aaa',fontFamily:'monospace'}}>{p.kl}</div>
      </div>
    </div>)}
    <Warn title="WhatsApp Canal — crítico">Sin canal WA no hay distribución directa. Activar antes de publicar la primera nota.</Warn>
  </div>;
}

function TabContenido() {
  return <div>
    <SLabel>6 Pilares Editoriales</SLabel>
    <div style={{display:'grid',gridTemplateColumns:'1fr 1fr',gap:7,marginBottom:14}}>
      {PILARES.map(p=><div key={p.code} style={{...S.card,borderLeft:`3px solid ${p.color}`}}>
        <div style={{fontSize:9,fontFamily:'monospace',color:p.color,fontWeight:700,marginBottom:3}}>{p.code}</div>
        <div style={{fontFamily:"'Playfair Display',serif",fontSize:12,color:PALETA.negro,fontWeight:700,marginBottom:2}}>{p.name}</div>
        <div style={{fontSize:10,color:'#888'}}>{p.fmt}</div>
      </div>)}
    </div>
    <SLabel>Calendario semanal</SLabel>
    <div style={{...S.card,marginBottom:12}}>
      <div style={{display:'grid',gridTemplateColumns:'repeat(7,1fr)',gap:3}}>
        {[{d:'LUN',p:'P01+P06'},{d:'MAR',p:'P02'},{d:'MIÉ',p:'P04'},{d:'JUE',p:'P05'},{d:'VIE',p:'P03'},{d:'SÁB',p:'P04'},{d:'DOM',p:'↓'}].map(({d,p})=><div key={d} style={{textAlign:'center',padding:'6px 2px',background:'#fff',borderRadius:4,border:`1px solid ${PALETA.crema3}`}}>
          <div style={{fontWeight:700,color:PALETA.rojo,marginBottom:2,fontFamily:'monospace',fontSize:8}}>{d}</div>
          <div style={{color:'#888',fontSize:9}}>{p}</div>
        </div>)}
      </div>
      <div style={{marginTop:8,fontSize:10,color:'#888',fontFamily:'monospace'}}>7:30 AM — Resumen WA todos los días · Sin segunda capa = no va</div>
    </div>
    <Info title="Regla de las 2 capas — OBLIGATORIA">Capa 1: hecho verificado. Capa 2: qué significa / contexto / pregunta abierta. Sin segunda capa no se publica.</Info>
  </div>;
}

function TabRoadmap() {
  const sems=[
    {l:'Identidad — Fase 1',    s:'g', t:'CERRADA'},
    {l:'Plugin v8.1.2',         s:'g', t:'activo'},
    {l:'Categorías P01-P06',    s:'g', t:'creadas ✓'},
    {l:'Schema NewsMediaOrg',   s:'g', t:'activo ✓'},
    {l:'WordPress / técnica',   s:'y', t:'configuración incompleta'},
    {l:'Imágenes institucionales',s:'g',t:'logo + favicon + OG ✓'},
    {l:'Canal WhatsApp',        s:'r', t:'sin configurar — BLOQUEA F2'},
    {l:'20 notas publicadas',   s:'y', t:'48 borradores demo importados'},
    {l:'500 WA suscriptores',   s:'r', t:'pendiente'},
    {l:'Monetización',          s:'p', t:'FASE 3'},
    {l:'Themes Región / RN33',  s:'p', t:'PAUSA FASE 3'},
  ];
  const sc={g:'#43a047',y:'#fb8c00',r:'#e53935',p:'#78909c'};
  return <div>
    <SLabel>Semáforos de estado</SLabel>
    {sems.map((s,i)=><div key={i} style={{display:'flex',alignItems:'center',gap:9,padding:'7px 0',borderBottom:`1px solid ${PALETA.crema3}`}}>
      <div style={{width:8,height:8,borderRadius:'50%',background:sc[s.s],boxShadow:`0 0 5px ${sc[s.s]}50`,flexShrink:0}}/>
      <span style={{fontSize:11,color:PALETA.negro,flex:1}}>{s.l}</span>
      <span style={{fontSize:9,color:'#999',fontFamily:'monospace'}}>{s.t}</span>
    </div>)}
    <Divider/>
    <SLabel>Objetivo 90 días</SLabel>
    <div style={{display:'grid',gridTemplateColumns:'1fr 1fr 1fr',gap:7}}>
      {[{b:'b',l:'Día 1–15',t:'Canal WA · primera nota publicada'},{b:'y',l:'Día 16–45',t:'Frecuencia sostenida · primera ficha pública · medir'},{b:'r',l:'Día 46–90',t:'Primera alianza local · preparar monetización'}].map(({b,l,t})=><div key={l} style={S.card}>
        <span style={{...S.badge(b),marginBottom:5,display:'inline-flex'}}>{l}</span>
        <div style={{fontSize:11,color:'#555',lineHeight:1.5}}>{t}</div>
      </div>)}
    </div>
  </div>;
}

// ══════════════════════════════════════════════
// APP PRINCIPAL
// ══════════════════════════════════════════════
const SCREENS = [
  { label:'Dashboard',    section:'BASE',         badge:'v8.1' },
  { label:'Producción',   section:'TRABAJO',      badge:'4 tools' },
  { label:'Inteligencia', section:'IA',            badge:'12' },
  { label:'Seguimiento',  section:'CONTROL',      badge:null },
];

function ElRufinoPanel() {
  const [cur,setCur]=useState(0);
  const [toast,setToast]=useState('');
  const [fullscreen,setFullscreen]=useState(false);
  const timer=useRef(null);

  const showToast=useCallback((msg)=>{
    setToast(msg); clearTimeout(timer.current);
    timer.current=setTimeout(()=>setToast(''),2600);
  },[]);

  const toggleFS=()=>{
    const next=!fullscreen; setFullscreen(next);
    if(next) document.body.classList.add('er-fullscreen');
    else document.body.classList.remove('er-fullscreen');
  };

  const renderScreen=()=>{
    switch(cur){
      case 0: return <PantallaDashboard showToast={showToast}/>;
      case 1: return <PantallaProduccion showToast={showToast}/>;
      case 2: return <PantallaInteligencia showToast={showToast}/>;
      case 3: return <PantallaSeguimiento showToast={showToast}/>;
      default: return null;
    }
  };

  return <div style={S.shell}>
    {/* Sidebar */}
    <div style={S.sidebar}>
      <div style={{padding:'14px 12px 12px',borderBottom:`1px solid ${PALETA.crema3}`}}>
        <div style={{background:PALETA.rojo,color:'#fff',fontFamily:"'Playfair Display',serif",fontWeight:900,fontSize:14,padding:'4px 9px',borderRadius:3,display:'inline-block',letterSpacing:.5}}>EL RUFINO</div>
        <div style={{fontSize:9,color:'#aaa',marginTop:4,fontFamily:'monospace',display:'flex',alignItems:'center',gap:4}}>
          <div style={{width:5,height:5,borderRadius:'50%',background:'#43a047'}}/>Plugin v8.1.2 · Panel IA
        </div>
      </div>
      <div style={{flex:1,overflowY:'auto',padding:'6px 0'}}>
        <div style={{fontSize:9,letterSpacing:'2.5px',textTransform:'uppercase',color:'#bbb',padding:'8px 12px 3px',fontWeight:700,fontFamily:'monospace'}}>PANTALLAS</div>
        {SCREENS.map((s,i)=><NavItem key={i} label={s.label} active={cur===i} onClick={()=>setCur(i)} badge={s.badge}/>)}
      </div>
      <div style={{padding:'8px 12px',borderTop:`1px solid ${PALETA.crema3}`}}>
        <button onClick={toggleFS} style={{...S.btn(fullscreen?'gr':'g'),width:'100%',justifyContent:'center',fontSize:10}}>
          {fullscreen?'✕ Salir pantalla completa':'⛶ Pantalla completa'}
        </button>
        <div style={{fontSize:9,background:'#f5f0e8',color:'#888',border:`1px solid ${PALETA.crema3}`,padding:'2px 6px',borderRadius:3,fontFamily:'monospace',textAlign:'center',marginTop:5}}>
          FASE 2 EN EJECUCIÓN
        </div>
      </div>
    </div>

    {/* Main */}
    <div style={S.main}>
      <div style={S.topbar}>
        <div style={{fontSize:9,color:'#888',fontFamily:'monospace',letterSpacing:1,textTransform:'uppercase'}}>{SCREENS[cur]?.section}</div>
        <div style={{width:1,height:12,background:'#222',flexShrink:0}}/>
        <div style={{fontFamily:"'Playfair Display',serif",fontSize:13,opacity:.75,flex:1}}>{SCREENS[cur]?.label}</div>
        <div style={{display:'flex',alignItems:'center',gap:7}}>
          <span style={{fontSize:9,color:'#888',fontFamily:'monospace'}}>{cur+1}/{SCREENS.length}</span>
          <div style={{width:60,height:2,background:'#333',borderRadius:1}}>
            <div style={{height:'100%',background:PALETA.rojo,borderRadius:1,width:`${(cur+1)/SCREENS.length*100}%`,transition:'width .3s'}}/>
          </div>
        </div>
        <div style={{fontSize:9,fontFamily:'monospace',color:'#aaa',padding:'2px 7px',border:'1px solid #444',borderRadius:3}}>elrufino.com.ar</div>
      </div>

      <div style={S.content}>{renderScreen()}</div>

      <div style={S.navFt}>
        <button onClick={()=>setCur(p=>Math.max(0,p-1))} disabled={cur===0} style={{fontSize:11,padding:'6px 16px',borderRadius:4,border:`1px solid ${PALETA.crema3}`,background:'transparent',color:'#888',cursor:cur===0?'default':'pointer',opacity:cur===0?.3:1,fontWeight:700}}>← Anterior</button>
        <span style={{fontSize:10,color:'#aaa',fontFamily:'monospace'}}>{cur+1} de {SCREENS.length}</span>
        <button onClick={()=>setCur(p=>Math.min(SCREENS.length-1,p+1))} disabled={cur===SCREENS.length-1} style={{fontSize:11,padding:'6px 16px',borderRadius:4,border:'none',background:PALETA.rojo,color:'#fff',cursor:cur===SCREENS.length-1?'default':'pointer',opacity:cur===SCREENS.length-1?.3:1,fontWeight:700}}>Siguiente →</button>
      </div>
    </div>

    <Toast msg={toast}/>
  </div>;
}

// Mount
const erRoot=document.getElementById('er-root');
if(erRoot){ const root=ReactDOM.createRoot(erRoot); root.render(<ElRufinoPanel/>); }
