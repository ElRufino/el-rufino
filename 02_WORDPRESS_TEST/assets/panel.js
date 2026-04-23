/* EL RUFINO — PANEL IA v8.6.0 — JavaScript puro, sin JSX, sin compilador */

/* CAPTURADOR GLOBAL DE ERRORES */
window.onerror = function(msg, src, line, col, err) {
  var d = document.getElementById('er-err');
  if (!d) { d = document.createElement('div'); d.id='er-err'; d.style.cssText='position:fixed;top:0;left:220px;right:0;z-index:99999;background:#c0271b;color:#fff;padding:14px 18px;font-family:monospace;font-size:12px;white-space:pre-wrap;word-break:break-all'; document.body.appendChild(d); }
  d.textContent = 'JS ERROR: ' + msg + ' | linea ' + line + (err && err.stack ? '\n' + err.stack.slice(0,400) : '');
  return false;
};
window.onunhandledrejection = function(e) {
  var d = document.getElementById('er-err');
  if (!d) { d = document.createElement('div'); d.id='er-err'; d.style.cssText='position:fixed;top:0;left:220px;right:0;z-index:99999;background:#c0271b;color:#fff;padding:14px 18px;font-family:monospace;font-size:12px;white-space:pre-wrap;word-break:break-all'; document.body.appendChild(d); }
  d.textContent = 'PROMISE ERROR: ' + (e.reason ? String(e.reason) : 'desconocido');
};

(function() {
'use strict';

const { useState, useEffect, createElement: h, Component } = React;

/* ── ERROR BOUNDARY ── */
class ErrorBoundary extends Component {
  constructor(props) {
    super(props);
    this.state = { error: null };
  }
  static getDerivedStateFromError(err) {
    return { error: err };
  }
  componentDidCatch(err, info) {
    console.error('ER Panel error:', err, info);
  }
  render() {
    if (this.state.error) {
      return h('div', { style: { background:'#fef2f2', border:'2px solid #c0271b', borderRadius:'12px', padding:'24px', margin:'20px' } },
        h('div', { style: { fontWeight:900, color:'#c0271b', fontSize:'1rem', marginBottom:'12px' } }, '❌ Error en el Asistente'),
        h('div', { style: { fontFamily:'monospace', fontSize:'0.82rem', color:'#333', background:'#f8f8f8', padding:'12px', borderRadius:'8px', whiteSpace:'pre-wrap', wordBreak:'break-all' } },
          String(this.state.error)
        ),
        h('button', {
          onClick: function(){ window.location.reload(); },
          style: { marginTop:'14px', background:'#c0271b', color:'#fff', border:'none', borderRadius:'8px', padding:'10px 20px', fontWeight:700, cursor:'pointer' }
        }, '↺ Recargar panel')
      );
    }
    return this.props.children;
  }
}

const PILARES = [
  { nombre: 'Barrio a barrio',         slug: 'barrio-a-barrio',      color: '#b55233' },
  { nombre: 'Contexto y datos',        slug: 'contexto-datos',       color: '#2f6484' },
  { nombre: 'El campo habla',          slug: 'el-campo-habla',       color: '#617a45' },
  { nombre: 'Generación Rufino',       slug: 'generacion-rufino',    color: '#c58a2b' },
  { nombre: 'Seguimiento de promesas', slug: 'seguimiento-promesas', color: '#1f2a30' },
];

async function ajax(action, data) {
  const fd = new FormData();
  fd.append('action', action);
  fd.append('nonce', erData.nonce);
  if (data) Object.entries(data).forEach(([k, v]) => fd.append(k, v));
  const r = await fetch(erData.ajaxUrl, { method: 'POST', body: fd });
  return r.json();
}

const css = {
  wrap:    { fontFamily: "'Segoe UI',system-ui,sans-serif", background: '#f4f1ec', minHeight: '100vh', display: 'flex' },
  sidebar: { width: '220px', background: '#1a1a1a', display: 'flex', flexDirection: 'column', position: 'fixed', top: 0, left: 0, bottom: 0, zIndex: 100, overflowY: 'auto' },
  main:    { marginLeft: '220px', flex: 1, display: 'flex', flexDirection: 'column', minHeight: '100vh' },
  hdr:     { background: '#fff', borderBottom: '1px solid #e5e0d8', padding: '12px 28px', display: 'flex', alignItems: 'center', gap: '16px', position: 'sticky', top: 0, zIndex: 50 },
  body:    { padding: '28px', flex: 1 },
  card:    { background: '#fff', borderRadius: '12px', border: '1px solid #e5e0d8', padding: '24px', marginBottom: '20px' },
  btn:     function(c){ return { background: c||'#c0271b', color:'#fff', border:'none', borderRadius:'8px', padding:'10px 20px', fontWeight:700, cursor:'pointer', fontSize:'0.9rem' }; },
  btnO:    function(c){ return { background:'transparent', color:c||'#c0271b', border:'2px solid '+(c||'#c0271b'), borderRadius:'8px', padding:'8px 18px', fontWeight:700, cursor:'pointer', fontSize:'0.9rem' }; },
  inp:     { width:'100%', padding:'10px 14px', borderRadius:'8px', border:'1px solid #d1cdc6', fontSize:'0.95rem', outline:'none', boxSizing:'border-box' },
  lbl:     { display:'block', fontWeight:700, fontSize:'0.85rem', color:'#555', marginBottom:'6px' },
  badge:   function(c){ return { background:c+'20', color:c, border:'1px solid '+c+'40', borderRadius:'6px', padding:'3px 10px', fontSize:'0.75rem', fontWeight:700, display:'inline-block' }; },
  g2:      { display:'grid', gridTemplateColumns:'1fr 1fr', gap:'16px' },
  g4:      { display:'grid', gridTemplateColumns:'repeat(4,1fr)', gap:'16px' },
  stat:    function(c){ return { background:'#fff', borderLeft:'4px solid '+c, border:'1px solid '+c+'30', borderRadius:'10px', padding:'18px 20px' }; },
};

/* ── SIDEBAR ── */
function Sidebar({ screen, setScreen }) {
  const nav = [
    { id:'dashboard',    icon:'◈', label:'Dashboard'    },
    { id:'produccion',   icon:'✏', label:'Producción'   },
    { id:'inteligencia', icon:'⬡', label:'Inteligencia' },
    { id:'seguimiento',  icon:'◎', label:'Seguimiento'  },
    { id:'asistente',    icon:'⚡', label:'Asistente'    },
  ];
  return h('div', { style: css.sidebar },
    h('div', { style: { padding:'22px 20px 16px', borderBottom:'1px solid #333' } },
      h('div', { style: { background:'#c0271b', borderRadius:'8px', padding:'8px 14px', display:'inline-block', fontWeight:900, fontSize:'1.1rem', color:'#fff' } }, 'EL RUFINO'),
      h('div', { style: { color:'#666', fontSize:'0.72rem', marginTop:'6px' } }, 'PANEL IA · v8.6.0')
    ),
    h('nav', { style: { padding:'16px 10px', flex:1 } },
      h('div', { style: { color:'#555', fontSize:'0.68rem', letterSpacing:'0.1em', padding:'0 10px', marginBottom:'8px' } }, 'PANTALLAS'),
      nav.map(function(it) {
        const active = screen === it.id;
        return h('button', {
          key: it.id,
          onClick: function(){ setScreen(it.id); },
          style: { display:'flex', alignItems:'center', gap:'10px', width:'100%', padding:'10px 12px', borderRadius:'8px', border:'none', cursor:'pointer', background: active ? '#c0271b' : 'transparent', color: active ? '#fff' : '#999', fontWeight: active ? 700 : 400, fontSize:'0.9rem', textAlign:'left', marginBottom:'2px' }
        }, h('span', null, it.icon), it.label);
      })
    ),
    h('div', { style: { padding:'14px 16px', borderTop:'1px solid #333', fontSize:'0.72rem', color:'#555' } }, 'Fase 2 en ejecución')
  );
}

/* ── DASHBOARD ── */
function Dashboard() {
  const [stats, setStats] = useState(null);
  const [checklist, setChecklist] = useState([]);
  const [importing, setImporting] = useState(false);
  const [importMsg, setImportMsg] = useState('');
  const [importTotal] = useState(10);
  const [importCurrent, setImportCurrent] = useState(0);
  const [importLog, setImportLog] = useState([]);

  // Proveedor activo: 'anthropic' | 'openrouter'
  const [provider, setProvider] = useState('anthropic');
  // Anthropic
  const [keyInput, setKeyInput] = useState('');
  const [keyStatus, setKeyStatus] = useState(null);
  // OpenRouter
  const [orKeyInput, setOrKeyInput] = useState('');
  const [orModelInput, setOrModelInput] = useState('anthropic/claude-sonnet-4');
  const [orStatus, setOrStatus] = useState(null);
  const [savingProvider, setSavingProvider] = useState(false);

  useEffect(function() {
    ajax('er_stats').then(function(r){ if(r.success) setStats(r.data); });
    ajax('er_get_checklist').then(function(r){ if(r.success) setChecklist(Array.isArray(r.data) ? r.data : []); });
    ajax('er_key_status').then(function(r){
      if(r.success){
        setKeyStatus(r.data);
        setProvider(r.data.provider || 'anthropic');
      }
    });
    ajax('er_orkey_status').then(function(r){
      if(r.success){
        setOrStatus(r.data);
        if(r.data.model) setOrModelInput(r.data.model);
      }
    });
  }, []);

  function toggleItem(id) {
    const updated = checklist.map(function(i){ return i.id === id ? Object.assign({}, i, {ok: !i.ok}) : i; });
    setChecklist(updated);
    ajax('er_save_checklist', { items: JSON.stringify(updated) });
  }

  async function saveKey() {
    if (!keyInput.trim()) return;
    await ajax('er_save_key', { key: keyInput.trim() });
    const r = await ajax('er_key_status');
    if (r.success) setKeyStatus(r.data);
    setKeyInput('');
  }

  async function saveOrKey() {
    if (!orKeyInput.trim()) return;
    await ajax('er_save_orkey', { key: orKeyInput.trim(), model: orModelInput.trim() });
    const r = await ajax('er_orkey_status');
    if (r.success) setOrStatus(r.data);
    setOrKeyInput('');
  }

  async function saveOrModel() {
    await ajax('er_save_orkey', { key: '', model: orModelInput.trim() });
    const r = await ajax('er_orkey_status');
    if (r.success) setOrStatus(r.data);
  }

  async function switchProvider(p) {
    setSavingProvider(true);
    await ajax('er_save_provider', { provider: p });
    setProvider(p);
    setSavingProvider(false);
  }

  async function importarDemo() {
    setImporting(true);
    setImportMsg('');
    setImportCurrent(0);
    setImportLog([]);
    let ok = 0, err = 0;
    for (let i = 0; i < importTotal; i++) {
      setImportCurrent(i + 1);
      const r = await ajax('er_import_demo_one', { index: i });
      if (r.success) {
        ok++;
        setImportLog(function(prev){ return prev.concat('✓ ' + r.data.titulo); });
      } else {
        err++;
        setImportLog(function(prev){ return prev.concat('✗ Nota ' + (i+1) + ': ' + (r.data && r.data.msg ? r.data.msg : 'error')); });
      }
    }
    setImporting(false);
    setImportMsg(err > 0
      ? '⚠ ' + ok + ' importadas, ' + err + ' con error.'
      : '✅ ' + ok + ' notas generadas con IA y guardadas como borrador.');
  }

  const safeChecklist = Array.isArray(checklist) ? checklist : [];
  const oks = safeChecklist.filter(function(i){ return i.ok; }).length;
  const pct = safeChecklist.length ? Math.round((oks/safeChecklist.length)*100) : 0;
  const today = new Date().toLocaleDateString('es-AR',{weekday:'long',day:'numeric',month:'long'});

  return h('div', null,
    h('div', { style: css.card },
      h('div', { style: { display:'flex', alignItems:'center', gap:'16px' } },
        h('div', { style: { background:'#c0271b', borderRadius:'10px', width:'52px', height:'52px', display:'flex', alignItems:'center', justifyContent:'center', fontWeight:900, fontSize:'1.4rem', color:'#fff', flexShrink:0 } }, 'ER'),
        h('div', null,
          h('div', { style: { fontWeight:900, fontSize:'1.3rem', color:'#111' } }, 'Hola, ' + erData.userName),
          h('div', { style: { color:'#888', fontSize:'0.88rem' } }, 'Panel IA v8.6.0 · ' + today)
        ),
        h('div', { style: { marginLeft:'auto', display:'flex', gap:'8px' } },
          h('a', { href: erData.adminUrl+'post-new.php', style: Object.assign({}, css.btn(), {textDecoration:'none'}) }, '✏ Nueva entrada'),
          h('a', { href: erData.adminUrl+'media-new.php', style: Object.assign({}, css.btn('#1d4ed8'), {textDecoration:'none'}) }, '📷 Subir imagen')
        )
      )
    ),
    h('div', { style: css.g4 },
      [
        { label:'Publicadas',      val: stats ? stats.published : '—', color:'#15803d' },
        { label:'Borradores',      val: stats ? stats.drafts    : '—', color:'#1d4ed8' },
        { label:'Comentarios',     val: stats ? stats.comments  : '—', color:'#7c3aed' },
        { label:'Actualizaciones', val: stats ? stats.updates   : '—', color: (stats && stats.updates > 0) ? '#d97706' : '#15803d' },
      ].map(function(s) {
        return h('div', { key:s.label, style: css.stat(s.color) },
          h('div', { style: { fontSize:'2rem', fontWeight:900, color:s.color } }, s.val),
          h('div', { style: { fontSize:'0.8rem', color:'#777', marginTop:'4px' } }, s.label)
        );
      })
    ),
    h('div', { style: css.g2 },
      h('div', { style: css.card },
        h('div', { style: { display:'flex', justifyContent:'space-between', alignItems:'center', marginBottom:'16px' } },
          h('div', { style: { fontWeight:800 } }, 'Checklist Fase 2'),
          h('span', { style: css.badge('#c0271b') }, pct + '% completado')
        ),
        h('div', { style: { background:'#f4f1ec', borderRadius:'8px', height:'8px', marginBottom:'18px' } },
          h('div', { style: { background:'#c0271b', height:'8px', borderRadius:'8px', width:pct+'%' } })
        ),
        safeChecklist.map(function(item) {
          return h('div', { key:item.id, onClick:function(){ toggleItem(item.id); }, style: { display:'flex', alignItems:'center', gap:'10px', padding:'8px 0', borderBottom:'1px solid #f0ece6', cursor:'pointer' } },
            h('div', { style: { width:'20px', height:'20px', borderRadius:'5px', background: item.ok ? '#15803d' : '#fff', border:'2px solid '+(item.ok?'#15803d':'#ccc'), display:'flex', alignItems:'center', justifyContent:'center', flexShrink:0 } },
              item.ok ? h('span', { style: { color:'#fff', fontSize:'0.8rem', fontWeight:900 } }, '✓') : null
            ),
            h('span', { style: { fontSize:'0.88rem', color: item.ok ? '#888' : '#222', textDecoration: item.ok ? 'line-through' : 'none' } }, item.texto),
            item.ok ? h('span', { style: Object.assign({ marginLeft:'auto' }, css.badge('#15803d')) }, 'OK') : null
          );
        })
      ),
      h('div', null,
        h('div', { style: css.card },
          /* Título + proveedor activo */
          h('div', { style: { display:'flex', alignItems:'center', justifyContent:'space-between', marginBottom:'14px' } },
            h('div', { style: { fontWeight:800 } }, '⚙ Proveedor IA'),
            h('span', { style: css.badge(provider === 'openrouter' ? '#0e7490' : '#c0271b') },
              provider === 'openrouter' ? 'OpenRouter activo' : 'Anthropic activo'
            )
          ),
          /* Tabs selector */
          h('div', { style: { display:'flex', gap:'6px', marginBottom:'16px' } },
            ['anthropic', 'openrouter'].map(function(p) {
              const active = provider === p;
              const label  = p === 'anthropic' ? '🟣 Anthropic directo' : '🔀 OpenRouter';
              return h('button', {
                key: p,
                onClick: function(){ if(!savingProvider) switchProvider(p); },
                style: Object.assign({}, active ? css.btn(p === 'openrouter' ? '#0e7490' : '#c0271b') : css.btnO(p === 'openrouter' ? '#0e7490' : '#c0271b'), { flex:1, fontSize:'0.82rem', padding:'8px 10px', opacity: savingProvider ? 0.6 : 1 })
              }, label);
            })
          ),
          /* Panel Anthropic */
          provider === 'anthropic'
            ? h('div', null,
                keyStatus && keyStatus.configured
                  ? h('div', { style: { display:'flex', alignItems:'center', gap:'10px', padding:'10px 14px', background:'#f0fdf4', border:'1px solid #bbf7d0', borderRadius:'8px', marginBottom:'10px', fontSize:'0.85rem' } },
                      h('span', { style: { color:'#15803d', fontWeight:700 } }, '✓'),
                      h('span', { style: { color:'#166534' } }, 'Key configurada (' + (keyStatus.masked||'') + ')')
                    )
                  : h('div', { style: { padding:'10px 14px', background:'#fef2f2', border:'1px solid #fecaca', borderRadius:'8px', marginBottom:'10px', fontSize:'0.83rem', color:'#991b1b' } },
                      '⚠ API key de Anthropic no configurada.'
                    ),
                h('div', { style: { display:'flex', gap:'8px' } },
                  h('input', { type:'password', placeholder:'sk-ant-api03-...', value:keyInput, onChange:function(e){ setKeyInput(e.target.value); }, style: Object.assign({}, css.inp, {flex:1}) }),
                  h('button', { onClick:saveKey, style:css.btn() }, 'Guardar')
                ),
                h('div', { style: { fontSize:'0.75rem', color:'#999', marginTop:'6px' } }, 'Modelo: claude-sonnet-4-20250514 · Key guardada en WP, nunca en el chat.')
              )
            : null,
          /* Panel OpenRouter */
          provider === 'openrouter'
            ? h('div', null,
                orStatus && orStatus.configured
                  ? h('div', { style: { display:'flex', alignItems:'center', gap:'10px', padding:'10px 14px', background:'#f0fdf4', border:'1px solid #bbf7d0', borderRadius:'8px', marginBottom:'10px', fontSize:'0.85rem' } },
                      h('span', { style: { color:'#15803d', fontWeight:700 } }, '✓'),
                      h('span', { style: { color:'#166534' } }, 'Key configurada (' + (orStatus.masked||'') + ')')
                    )
                  : h('div', { style: { padding:'10px 14px', background:'#fef2f2', border:'1px solid #fecaca', borderRadius:'8px', marginBottom:'10px', fontSize:'0.83rem', color:'#991b1b' } },
                      '⚠ API key de OpenRouter no configurada. Conseguila en openrouter.ai'
                    ),
                h('label', { style: css.lbl }, 'API Key (sk-or-v1-...)'),
                h('div', { style: { display:'flex', gap:'8px', marginBottom:'10px' } },
                  h('input', { type:'password', placeholder:'sk-or-v1-...', value:orKeyInput, onChange:function(e){ setOrKeyInput(e.target.value); }, style: Object.assign({}, css.inp, {flex:1}) }),
                  h('button', { onClick:saveOrKey, style:css.btn('#0e7490') }, 'Guardar')
                ),
                h('label', { style: css.lbl }, 'Modelo OpenRouter'),
                h('div', { style: { display:'flex', gap:'8px' } },
                  h('input', { type:'text', placeholder:'anthropic/claude-sonnet-4', value:orModelInput, onChange:function(e){ setOrModelInput(e.target.value); }, style: Object.assign({}, css.inp, {flex:1, fontFamily:'monospace', fontSize:'0.82rem'}) }),
                  h('button', { onClick:saveOrModel, style:css.btnO('#0e7490') }, 'Aplicar')
                ),
                h('div', { style: { fontSize:'0.75rem', color:'#999', marginTop:'8px', lineHeight:1.5 } },
                  'Modelos populares: ',
                  ['anthropic/claude-sonnet-4','anthropic/claude-haiku-4-5','openai/gpt-4o','google/gemini-2.0-flash'].map(function(m, i){
                    return h('span', { key:m },
                      i > 0 ? ' · ' : '',
                      h('span', {
                        onClick: function(){ setOrModelInput(m); },
                        style: { cursor:'pointer', color:'#0e7490', textDecoration:'underline' }
                      }, m)
                    );
                  })
                )
              )
            : null
        ),
        h('div', { style: css.card },
          h('div', { style: { fontWeight:800, marginBottom:'10px' } }, '📥 Importador de notas demo'),
          h('div', { style: { fontSize:'0.85rem', color:'#666', marginBottom:'14px' } }, 'Genera 10 borradores distribuidos en los 5 pilares usando IA real.'),
          importing
            ? h('div', null,
                h('div', { style: { fontSize:'0.88rem', color:'#1d4ed8', marginBottom:'8px', fontWeight:700 } },
                  'Generando nota ' + importCurrent + ' de ' + importTotal + '...'
                ),
                h('div', { style: { background:'#e5e7eb', borderRadius:'8px', height:'10px', marginBottom:'12px' } },
                  h('div', { style: { background:'#1d4ed8', height:'10px', borderRadius:'8px', width: Math.round((importCurrent/importTotal)*100) + '%', transition:'width 0.4s' } })
                ),
                importLog.length > 0
                  ? h('div', { style: { background:'#f8f7f4', border:'1px solid #e5e0d8', borderRadius:'8px', padding:'10px 14px', maxHeight:'140px', overflowY:'auto' } },
                      importLog.map(function(line, i){
                        return h('div', { key:i, style: { fontSize:'0.78rem', color: line.startsWith('✓') ? '#15803d' : '#c0271b', padding:'2px 0' } }, line);
                      })
                    )
                  : null
              )
            : h('button', { onClick:importarDemo, style:css.btn('#1d4ed8') }, '📥 Importar notas demo'),
          importMsg ? h('div', { style: { marginTop:'10px', fontSize:'0.88rem', color: importMsg.startsWith('✅') ? '#15803d' : '#d97706' } }, importMsg) : null,
          !importing && importLog.length > 0
            ? h('div', { style: { marginTop:'10px', background:'#f8f7f4', border:'1px solid #e5e0d8', borderRadius:'8px', padding:'10px 14px', maxHeight:'140px', overflowY:'auto' } },
                importLog.map(function(line, i){
                  return h('div', { key:i, style: { fontSize:'0.78rem', color: line.startsWith('✓') ? '#15803d' : '#c0271b', padding:'2px 0' } }, line);
                })
              )
            : null
        )
      )
    )
  );
}

/* ── PRODUCCIÓN ── */
function Produccion() {
  return h('div', null,
    h('div', { style: css.card },
      h('div', { style: { fontWeight:800, fontSize:'1.1rem', marginBottom:'8px' } }, '📋 Estado editorial'),
      h('div', { style: { fontSize:'0.9rem', color:'#666', marginBottom:'20px' } }, 'Gestioná las entradas desde el editor de WordPress.'),
      h('div', { style: css.g2 },
        h('a', { href: erData.adminUrl+'edit.php?post_status=draft', style: { textDecoration:'none' } },
          h('div', { style: css.stat('#1d4ed8') },
            h('div', { style: { fontWeight:800, color:'#1d4ed8' } }, '📄 Borradores'),
            h('div', { style: { fontSize:'0.85rem', color:'#666', marginTop:'6px' } }, 'Ver y editar borradores →')
          )
        ),
        h('a', { href: erData.adminUrl+'edit.php?post_status=publish', style: { textDecoration:'none' } },
          h('div', { style: css.stat('#15803d') },
            h('div', { style: { fontWeight:800, color:'#15803d' } }, '✅ Publicadas'),
            h('div', { style: { fontSize:'0.85rem', color:'#666', marginTop:'6px' } }, 'Ver entradas publicadas →')
          )
        )
      )
    ),
    h('div', { style: css.card },
      h('div', { style: { fontWeight:800, fontSize:'1rem', marginBottom:'16px' } }, '🗂 Pilares editoriales'),
      h('div', { style: { display:'grid', gridTemplateColumns:'repeat(3,1fr)', gap:'12px' } },
        PILARES.map(function(p) {
          return h('a', { key:p.slug, href: erData.adminUrl+'edit.php?category_name='+p.slug, style: { textDecoration:'none' } },
            h('div', { style: { borderLeft:'4px solid '+p.color, background:'#fafaf8', borderRadius:'8px', padding:'14px 16px', border:'1px solid #e5e0d8' } },
              h('div', { style: { fontWeight:700, fontSize:'0.9rem', color:'#222' } }, p.nombre),
              h('div', { style: { fontSize:'0.78rem', color:'#888', marginTop:'4px' } }, 'Ver entradas →')
            )
          );
        })
      )
    ),
    h('div', { style: css.card },
      h('div', { style: { fontWeight:800, marginBottom:'14px' } }, '⚡ Acciones rápidas'),
      h('div', { style: { display:'flex', gap:'10px', flexWrap:'wrap' } },
        h('a', { href: erData.adminUrl+'post-new.php', style: Object.assign({}, css.btn(), {textDecoration:'none'}) }, '✏ Nueva entrada'),
        h('a', { href: erData.adminUrl+'upload.php', style: Object.assign({}, css.btn('#1d4ed8'), {textDecoration:'none'}) }, '📷 Multimedia'),
        h('a', { href: erData.adminUrl+'edit-tags.php?taxonomy=category', style: Object.assign({}, css.btn('#7c3aed'), {textDecoration:'none'}) }, '🗂 Categorías')
      )
    )
  );
}

/* ── INTELIGENCIA ── */
function Inteligencia() {
  const [respuesta, setRespuesta] = useState('');
  const [loading, setLoading] = useState(false);
  const [agenteActivo, setAgenteActivo] = useState(null);
  const [input, setInput] = useState('');

  const agentes = [
    { id:'A01', nombre:'SEO',               color:'#c0271b', system:'Sos el agente SEO de El Rufino. Optimizás contenido para Rank Math y Schema NewsMediaOrganization. Respondés en español rioplatense.', desc:'Rank Math · Schema · keywords editoriales' },
    { id:'A02', nombre:'Arquitectura',       color:'#1d4ed8', system:'Sos el agente de Arquitectura editorial de El Rufino. Diseñás categorías, slugs y taxonomías en WordPress.', desc:'Categorías WP · slugs · estructura' },
    { id:'A03', nombre:'Tema Visual',        color:'#7c3aed', system:'Sos el agente de Tema Visual de El Rufino. Manejás el child theme Newsup y la paleta B.', desc:'Child theme · paleta B · CSS' },
    { id:'A04', nombre:'Planificador',       color:'#15803d', system:'Sos el agente Planificador de El Rufino. Generás calendarios editoriales semanales con 3-5 piezas por día.', desc:'Calendario editorial · 7 días' },
    { id:'A05', nombre:'Redactor',           color:'#d97706', system:'Sos el redactor de El Rufino. Escribís notas con la regla de dos capas: hecho concreto + "Lo que significa". Español rioplatense, sin clichés.', desc:'Nota completa · regla 2 capas' },
    { id:'A06', nombre:'TikTok/Reel',        color:'#0e7490', system:'Sos el agente de TikTok y Reels de El Rufino. Escribís guiones de 60-90 seg con hook, desarrollo y CTA.', desc:'Guion 60-90 seg · hook · CTA' },
    { id:'A07', nombre:'Accountability',     color:'#be185d', system:'Sos el agente de Accountability de El Rufino. Rastreás promesas políticas y formulás preguntas de seguimiento.', desc:'¿Qué pasó con lo prometido?' },
    { id:'A08', nombre:'Stack WP',           color:'#065f46', system:'Sos el agente de Stack WordPress de El Rufino. Analizás arquitectura técnica y plugins críticos.', desc:'Arquitectura · plugins · fallos' },
    { id:'A09', nombre:'Servidor',           color:'#3730a3', system:'Sos el agente de Servidor de El Rufino. Manejás Hostinger, PHP, .htaccess y wp-config.php.', desc:'Hostinger · PHP · .htaccess' },
    { id:'A10', nombre:'Agenda de Datos',    color:'#92400e', system:'Sos el agente de Agenda de Datos de El Rufino. Identificás datos del INDEC y fuentes locales de Rufino.', desc:'datos · INDEC · municipio' },
    { id:'A11', nombre:'Imágenes',           color:'#5b21b6', system:'Sos el agente de Imágenes de El Rufino. Guiás producción visual, bancos y templates para redes.', desc:'Guía visual · bancos · templates' },
    { id:'A12', nombre:'Expansión Regional', color:'#6b7280', system:'Agente pausado.', desc:'⏸ PAUSADO — Corredor RN33', pausado:true },
  ];

  async function ejecutar() {
    if (!agenteActivo || !input.trim()) return;
    setLoading(true); setRespuesta('');
    const ag = agentes.find(function(a){ return a.id === agenteActivo; });
    const r = await ajax('er_claude', {
      system: ag.system,
      messages: JSON.stringify([{ role:'user', content:input }]),
      max_tokens: 1500,
    });
    setLoading(false);
    setRespuesta(r.success ? r.data.content : '❌ ' + (r.data && r.data.msg ? r.data.msg : 'Error'));
  }

  const ag = agentes.find(function(a){ return a.id === agenteActivo; });

  return h('div', null,
    h('div', { style: css.card },
      h('div', { style: { fontWeight:800, fontSize:'1rem', marginBottom:'16px' } }, '🤖 12 Agentes IA · Seleccioná uno'),
      h('div', { style: { display:'grid', gridTemplateColumns:'repeat(4,1fr)', gap:'10px' } },
        agentes.map(function(a) {
          const active = agenteActivo === a.id;
          return h('button', {
            key: a.id,
            onClick: function(){ if(!a.pausado) setAgenteActivo(a.id); },
            style: { border:'2px solid '+(active ? a.color : '#e5e0d8'), borderRadius:'10px', padding:'12px', background: active ? a.color+'10' : '#fafaf8', cursor: a.pausado ? 'default' : 'pointer', textAlign:'left', opacity: a.pausado ? 0.5 : 1 }
          },
            h('div', { style: { fontWeight:800, fontSize:'0.82rem', color: a.pausado ? '#999' : '#111', marginBottom:'4px' } }, a.id + ' · ' + a.nombre),
            h('div', { style: { fontSize:'0.72rem', color:'#888', lineHeight:1.4 } }, a.desc)
          );
        })
      )
    ),
    agenteActivo && ag && !ag.pausado ? h('div', { style: css.card },
      h('div', { style: { display:'flex', alignItems:'center', gap:'10px', marginBottom:'16px' } },
        h('span', { style: css.badge(ag.color) }, ag.id + ' · ' + ag.nombre)
      ),
      h('label', { style: css.lbl }, 'Tu instrucción para el agente'),
      h('textarea', { value:input, onChange:function(e){ setInput(e.target.value); }, rows:4, placeholder:'Escribí tu consulta...', style: Object.assign({}, css.inp, {height:'100px', resize:'vertical', fontFamily:'inherit'}) }),
      h('div', { style: { display:'flex', gap:'10px', marginTop:'12px' } },
        h('button', { onClick:ejecutar, disabled:loading, style:css.btn(ag.color) }, loading ? '⏳ Generando...' : '▶ Ejecutar agente'),
        h('button', { onClick:function(){ setInput(''); setRespuesta(''); }, style:css.btnO() }, 'Limpiar')
      ),
      respuesta ? h('div', { style: { marginTop:'20px', background:'#f8f7f4', border:'1px solid #e5e0d8', borderRadius:'10px', padding:'20px' } },
        h('div', { style: { fontWeight:800, marginBottom:'12px' } }, 'Respuesta:'),
        h('div', { style: { whiteSpace:'pre-wrap', lineHeight:1.7, fontSize:'0.9rem', color:'#222' } }, respuesta)
      ) : null
    ) : null
  );
}

/* ── SEGUIMIENTO ── */
function Seguimiento() {
  const [promesas, setPromesas] = useState([]);
  const [form, setForm] = useState({ texto:'', fuente:'', fecha:'' });
  const [saving, setSaving] = useState(false);
  const [exporting, setExporting] = useState(false);

  useEffect(function(){ ajax('er_get_promesas').then(function(r){ if(r.success) setPromesas(r.data); }); }, []);

  async function guardar() {
    if (!form.texto.trim()) return;
    setSaving(true);
    await ajax('er_save_promesa', form);
    const r = await ajax('er_get_promesas');
    if (r.success) setPromesas(r.data);
    setForm({ texto:'', fuente:'', fecha:'' });
    setSaving(false);
  }

  async function cambiarEstado(id, estado) {
    await ajax('er_update_promesa', { id:id, estado:estado });
    const r = await ajax('er_get_promesas');
    if (r.success) setPromesas(r.data);
  }

  async function exportar() {
    setExporting(true);
    const r = await ajax('er_export_promesas');
    if (r.success) {
      const blob = new Blob([r.data.csv], { type:'text/csv' });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url; a.download = 'promesas-'+new Date().toISOString().slice(0,10)+'.csv'; a.click();
    }
    setExporting(false);
  }

  const ec = { pendiente:'#d97706', verificado:'#15803d', incumplido:'#c0271b' };
  const el = { pendiente:'⏳ Pendiente', verificado:'✅ Verificado', incumplido:'❌ Incumplido' };

  return h('div', null,
    h('div', { style: css.card },
      h('div', { style: { fontWeight:800, fontSize:'1rem', marginBottom:'16px' } }, '➕ Registrar nueva promesa'),
      h('label', { style: css.lbl }, 'Promesa o declaración'),
      h('textarea', { value:form.texto, onChange:function(e){ setForm(Object.assign({},form,{texto:e.target.value})); }, rows:3, placeholder:'Ej: El intendente prometió asfaltar la calle Belgrano...', style: Object.assign({}, css.inp, {height:'80px', resize:'vertical', fontFamily:'inherit', marginBottom:'12px'}) }),
      h('div', { style: Object.assign({}, css.g2, {marginBottom:'12px'}) },
        h('div', null,
          h('label', { style: css.lbl }, 'Fuente'),
          h('input', { value:form.fuente, onChange:function(e){ setForm(Object.assign({},form,{fuente:e.target.value})); }, placeholder:'Ej: Conferencia de prensa...', style:css.inp })
        ),
        h('div', null,
          h('label', { style: css.lbl }, 'Fecha'),
          h('input', { type:'date', value:form.fecha, onChange:function(e){ setForm(Object.assign({},form,{fecha:e.target.value})); }, style:css.inp })
        )
      ),
      h('div', { style: { display:'flex', gap:'10px' } },
        h('button', { onClick:guardar, disabled:saving, style:css.btn() }, saving ? '⏳...' : '💾 Guardar promesa'),
        h('button', { onClick:exportar, disabled:exporting, style:css.btnO() }, exporting ? '⏳...' : '⬇ Exportar CSV')
      )
    ),
    h('div', { style: css.card },
      h('div', { style: { fontWeight:800, fontSize:'1rem', marginBottom:'16px' } }, '📋 Promesas registradas (' + promesas.length + ')'),
      promesas.length === 0
        ? h('div', { style: { color:'#999', textAlign:'center', padding:'30px 0' } }, 'No hay promesas registradas todavía.')
        : h('div', { style: { display:'flex', flexDirection:'column', gap:'12px' } },
            promesas.slice().reverse().map(function(p) {
              return h('div', { key:p.id, style: { border:'1px solid '+ec[p.estado]+'30', borderLeft:'4px solid '+ec[p.estado], borderRadius:'10px', padding:'16px' } },
                h('div', { style: { fontWeight:700, marginBottom:'6px' } }, p.texto),
                h('div', { style: { fontSize:'0.8rem', color:'#888', marginBottom:'10px' } }, '📌 '+(p.fuente||'—')+' · 📅 '+(p.fecha||'—')),
                h('div', { style: { display:'flex', gap:'8px', alignItems:'center', flexWrap:'wrap' } },
                  h('span', { style: css.badge(ec[p.estado]) }, el[p.estado]),
                  p.estado !== 'verificado'  ? h('button', { onClick:function(){ cambiarEstado(p.id,'verificado');  }, style: Object.assign({}, css.btnO('#15803d'), {padding:'4px 10px', fontSize:'0.78rem'}) }, '✅ Verificado')  : null,
                  p.estado !== 'incumplido'  ? h('button', { onClick:function(){ cambiarEstado(p.id,'incumplido');  }, style: Object.assign({}, css.btnO('#c0271b'), {padding:'4px 10px', fontSize:'0.78rem'}) }, '❌ Incumplido')  : null,
                  p.estado !== 'pendiente'   ? h('button', { onClick:function(){ cambiarEstado(p.id,'pendiente');   }, style: Object.assign({}, css.btnO('#d97706'), {padding:'4px 10px', fontSize:'0.78rem'}) }, '⏳ Pendiente')   : null
                )
              );
            })
          )
    )
  );
}

/* ── ASISTENTE ── */
function Asistente() {
  var s = {
    ytKeyInput: useState(''),
    ytKeyStatus: useState(null),
    ytUrl: useState(''),
    videoInfo: useState(null),
    loadingVideo: useState(false),
    videoError: useState(''),
    pilar: useState(''),
    keywords: useState(''),
    imagenB64: useState(''),
    imagenType: useState('image/jpeg'),
    imagenPreview: useState(''),
    generando: useState(false),
    borrador: useState(''),
    borradorError: useState(''),
    guardando: useState(false),
    guardadoUrl: useState(''),
    transcripcion: useState(''),
    loadingCaptions: useState(false),
    captionsMsg: useState(''),
  };
  var ytKeyInput = s.ytKeyInput[0]; var setYtKeyInput = s.ytKeyInput[1];
  var ytKeyStatus = s.ytKeyStatus[0]; var setYtKeyStatus = s.ytKeyStatus[1];
  var ytUrl = s.ytUrl[0]; var setYtUrl = s.ytUrl[1];
  var videoInfo = s.videoInfo[0]; var setVideoInfo = s.videoInfo[1];
  var loadingVideo = s.loadingVideo[0]; var setLoadingVideo = s.loadingVideo[1];
  var videoError = s.videoError[0]; var setVideoError = s.videoError[1];
  var pilar = s.pilar[0]; var setPilar = s.pilar[1];
  var keywords = s.keywords[0]; var setKeywords = s.keywords[1];
  var imagenB64 = s.imagenB64[0]; var setImagenB64 = s.imagenB64[1];
  var imagenType = s.imagenType[0]; var setImagenType = s.imagenType[1];
  var imagenPreview = s.imagenPreview[0]; var setImagenPreview = s.imagenPreview[1];
  var generando = s.generando[0]; var setGenerando = s.generando[1];
  var borrador = s.borrador[0]; var setBorrador = s.borrador[1];
  var borradorError = s.borradorError[0]; var setBorradorError = s.borradorError[1];
  var guardando = s.guardando[0]; var setGuardando = s.guardando[1];
  var guardadoUrl = s.guardadoUrl[0]; var setGuardadoUrl = s.guardadoUrl[1];
  var transcripcion = s.transcripcion[0]; var setTranscripcion = s.transcripcion[1];
  var loadingCaptions = s.loadingCaptions[0]; var setLoadingCaptions = s.loadingCaptions[1];
  var captionsMsg = s.captionsMsg[0]; var setCaptionsMsg = s.captionsMsg[1];
  var FILE_INPUT_ID = 'er-file-input';

  useEffect(function(){
    ajax('er_ytkey_status').then(function(r){ if(r.success) setYtKeyStatus(r.data); }).catch(function(){});
  }, []);

  function guardarYtKey() {
    if (!ytKeyInput.trim()) return;
    ajax('er_save_ytkey', { key: ytKeyInput.trim() }).then(function() {
      return ajax('er_ytkey_status');
    }).then(function(r) {
      if (r.success) setYtKeyStatus(r.data);
      setYtKeyInput('');
    }).catch(function(e){ setBorradorError('Error guardando YT key: ' + String(e)); });
  }

  function cargarVideo() {
    if (!ytUrl.trim()) return;
    setLoadingVideo(true); setVideoError(''); setVideoInfo(null);
    ajax('er_yt_info', { url: ytUrl.trim() }).then(function(r) {
      setLoadingVideo(false);
      if (r.success) { setVideoInfo(r.data); }
      else { setVideoError(r.data && r.data.msg ? r.data.msg : 'Error al cargar el video'); }
    }).catch(function(e){ setLoadingVideo(false); setVideoError('Error: ' + String(e)); });
  }

  function handleImagen(e) {
    var file = e.target.files[0];
    if (!file) return;
    setImagenType(file.type || 'image/jpeg');
    var reader = new FileReader();
    reader.onload = function(ev) {
      var result = ev.target.result;
      setImagenPreview(result);
      setImagenB64(result.split(',')[1] || '');
    };
    reader.readAsDataURL(file);
  }

  function obtenerCaptions() {
    if (!videoInfo || !videoInfo.video_id) return;
    setLoadingCaptions(true); setCaptionsMsg('');
    ajax('er_yt_captions', { video_id: videoInfo.video_id }).then(function(r) {
      setLoadingCaptions(false);
      if (r.success && r.data.transcripcion) {
        setTranscripcion(r.data.transcripcion);
        var fuente = r.data.fuente || '';
        var label = fuente.includes('es') ? 'español' : fuente.includes('en') ? 'inglés' : fuente;
        setCaptionsMsg('✓ Subtítulos cargados automáticamente (' + label + '). Revisalos y editá si es necesario.');
      } else {
        var msg = (r.data && r.data.msg) ? r.data.msg : 'No se pudieron obtener los subtítulos.';
        setCaptionsMsg('⚠ ' + msg);
      }
    }).catch(function(e){ setLoadingCaptions(false); setCaptionsMsg('⚠ Error: ' + String(e)); });
  }

  function generarBorrador() {
    if (!pilar) { setBorradorError('Seleccioná un pilar editorial.'); return; }
    if (!videoInfo && !keywords.trim() && !imagenB64 && !transcripcion.trim()) { setBorradorError('Necesitás al menos un insumo: video, transcripción, imagen o palabras clave.'); return; }
    setGenerando(true); setBorrador(''); setBorradorError(''); setGuardadoUrl('');
    ajax('er_asistente_generar', {
      pilar: pilar,
      keywords: keywords,
      transcripcion: transcripcion,
      video_titulo: videoInfo ? videoInfo.titulo : '',
      video_desc:   videoInfo ? videoInfo.descripcion : '',
      video_canal:  videoInfo ? videoInfo.canal : '',
      video_fecha:  videoInfo ? videoInfo.fecha : '',
      imagen_b64:   imagenB64,
      imagen_type:  imagenType,
    }).then(function(r) {
      setGenerando(false);
      if (r.success) { setBorrador(r.data.borrador); }
      else { setBorradorError(r.data && r.data.msg ? r.data.msg : 'Error al generar'); }
    }).catch(function(e){ setGenerando(false); setBorradorError('Error: ' + String(e)); });
  }

  function guardarEnWP() {
    if (!borrador) return;
    setGuardando(true);
    var tMatch = borrador.match(/\*\*T[IÍ]TULO:\*\*\s*(.+)/i);
    var titulo = tMatch ? tMatch[1].trim() : 'Borrador sin título';
    var html = borrador.replace(/\*\*(.+?)\*\*/g,'<strong>$1</strong>').split('\n').filter(function(l){ return l.trim(); }).map(function(l){ return '<p>'+l+'</p>'; }).join('\n');
    var pObj = PILARES.find(function(p){ return p.nombre === pilar; });
    ajax('er_asistente_guardar', {
      titulo: titulo,
      contenido: html,
      pilar_slug: pObj ? pObj.slug : '',
    }).then(function(r) {
      setGuardando(false);
      if (r.success) { setGuardadoUrl(r.data.edit_url); }
      else { setBorradorError(r.data && r.data.msg ? r.data.msg : 'Error al guardar'); }
    }).catch(function(e){ setGuardando(false); setBorradorError('Error: ' + String(e)); });
  }

  function limpiar() {
    setYtUrl(''); setVideoInfo(null); setVideoError('');
    setPilar(''); setKeywords(''); setTranscripcion('');
    setLoadingCaptions(false); setCaptionsMsg('');
    setImagenB64(''); setImagenPreview(''); setImagenType('image/jpeg');
    setBorrador(''); setBorradorError(''); setGuardadoUrl('');
    var fi = document.getElementById(FILE_INPUT_ID);
    if (fi) fi.value = '';
  }

  return h('div', null,
    h('div', { style: Object.assign({}, css.card, { background:'linear-gradient(135deg,#1a1a1a,#2d1a1a)', border:'1px solid #c0271b40' }) },
      h('div', { style: { display:'flex', alignItems:'center', gap:'14px' } },
        h('div', { style: { fontSize:'2rem' } }, '⚡'),
        h('div', null,
          h('div', { style: { fontWeight:900, fontSize:'1.2rem', color:'#fff' } }, 'Asistente de noticia'),
          h('div', { style: { color:'#999', fontSize:'0.85rem' } }, 'Video YouTube + imagen → Claude genera el borrador → guardás en WordPress')
        )
      )
    ),
    h('div', { style: css.g2 },
      h('div', null,
        h('div', { style: css.card },
          h('div', { style: { fontWeight:800, marginBottom:'12px' } }, '🔑 YouTube API Key ',
            ytKeyStatus && ytKeyStatus.configured ? h('span', { style: css.badge('#15803d') }, '✓ Configurada ('+ytKeyStatus.masked+')') : null
          ),
          !ytKeyStatus || !ytKeyStatus.configured
            ? h('div', { style: { padding:'10px 14px', background:'#fef2f2', border:'1px solid #fecaca', borderRadius:'8px', marginBottom:'12px', fontSize:'0.83rem', color:'#991b1b' } }, '⚠ Sin YouTube API key no podés verificar videos.')
            : null,
          h('div', { style: { display:'flex', gap:'8px' } },
            h('input', { type:'password', placeholder:'AIza...', value:ytKeyInput, onChange:function(e){ setYtKeyInput(e.target.value); }, style: Object.assign({}, css.inp, {flex:1}) }),
            h('button', { onClick:guardarYtKey, style:css.btn('#0e7490') }, 'Guardar')
          )
        ),
        h('div', { style: css.card },
          h('div', { style: { fontWeight:800, marginBottom:'8px' } }, '📹 Video YouTube (opcional)'),
          h('div', { style: { fontSize:'0.83rem', color:'#666', marginBottom:'10px' } }, 'Descargá el video de Facebook, subilo a YouTube como "No listado" y pegá el link.'),
          h('div', { style: { display:'flex', gap:'8px', marginBottom:'12px' } },
            h('input', { value:ytUrl, onChange:function(e){ setYtUrl(e.target.value); }, placeholder:'https://youtu.be/...', style: Object.assign({}, css.inp, {flex:1}) }),
            h('button', { onClick:cargarVideo, disabled:loadingVideo, style:css.btn('#0e7490') }, loadingVideo ? '⏳' : '▶ Cargar')
          ),
          videoError ? h('div', { style: { padding:'10px 14px', background:'#fef2f2', border:'1px solid #fecaca', borderRadius:'8px', fontSize:'0.83rem', color:'#991b1b' } }, '❌ '+videoError) : null,
          videoInfo ? h('div', { style: { background:'#f0fdf4', border:'1px solid #bbf7d0', borderRadius:'10px', padding:'14px' } },
            h('div', { style: { display:'flex', gap:'12px' } },
              videoInfo.thumbnail ? h('img', { src:videoInfo.thumbnail, alt:'', style: { width:'90px', height:'60px', objectFit:'cover', borderRadius:'6px', flexShrink:0 } }) : null,
              h('div', null,
                h('div', { style: { fontWeight:700, color:'#166534', marginBottom:'4px' } }, '✓ Video cargado'),
                h('div', { style: { fontWeight:600, fontSize:'0.88rem' } }, videoInfo.titulo),
                h('div', { style: { fontSize:'0.78rem', color:'#666', marginTop:'3px' } }, videoInfo.canal+' · '+videoInfo.fecha)
              )
            )
          ) : null
        ),
        h('div', { style: css.card },
          h('div', { style: { fontWeight:800, marginBottom:'8px' } }, '🎙 Contenido del video (transcripción)'),
          h('div', { style: { fontSize:'0.83rem', color:'#666', marginBottom:'10px' } },
            'Pegá lo que dice el entrevistado: citas textuales, puntos clave, datos que mencionó. Esto es lo que va a usar la IA para escribir la nota.'
          ),
          videoInfo
            ? h('div', { style: { marginBottom:'10px' } },
                h('button', {
                  onClick: obtenerCaptions,
                  disabled: loadingCaptions,
                  style: Object.assign({}, css.btn('#0e7490'), { fontSize:'0.85rem', padding:'8px 16px', width:'100%' })
                }, loadingCaptions ? '⏳ Obteniendo subtítulos...' : '🤖 Obtener subtítulos automáticos de YouTube'),
                captionsMsg
                  ? h('div', { style: {
                      fontSize:'0.78rem',
                      marginTop:'6px',
                      padding:'8px 12px',
                      borderRadius:'6px',
                      background: captionsMsg.startsWith('✓') ? '#f0fdf4' : '#fef9c3',
                      border: '1px solid ' + (captionsMsg.startsWith('✓') ? '#bbf7d0' : '#fde68a'),
                      color: captionsMsg.startsWith('✓') ? '#166534' : '#92400e',
                    }}, captionsMsg)
                  : null
              )
            : h('div', { style: { fontSize:'0.78rem', color:'#999', marginBottom:'8px', padding:'8px 12px', background:'#f8f7f4', borderRadius:'6px' } },
                '💡 Cargá un video de YouTube arriba para habilitar la obtención automática de subtítulos.'
              ),
          h('textarea', {
            value: transcripcion,
            onChange: function(e){ setTranscripcion(e.target.value); setCaptionsMsg(''); },
            rows: 6,
            placeholder: 'Ej: "Estamos trabajando para mejorar la guardia, incorporamos dos médicos nuevos..." — Florencia Maidana habló sobre el presupuesto, dijo que falta equipamiento para...',
            style: Object.assign({}, css.inp, { height:'140px', resize:'vertical', fontFamily:'inherit' })
          }),
          transcripcion.trim()
            ? h('div', { style: { fontSize:'0.75rem', color:'#15803d', marginTop:'6px' } }, '✓ ' + transcripcion.trim().split(/\s+/).length + ' palabras cargadas')
            : h('div', { style: { fontSize:'0.75rem', color:'#999', marginTop:'6px' } }, 'Sin transcripción — la IA usará solo el título y la descripción del video')
        ),
        h('div', { style: css.card },
          h('input', { id:FILE_INPUT_ID, type:'file', accept:'image/*', onChange:handleImagen, style: { display:'none' } }),
          h('button', { onClick:function(){ var fi=document.getElementById(FILE_INPUT_ID); if(fi) fi.click(); }, style:css.btnO('#7c3aed') }, '📎 Seleccionar imagen'),
          imagenPreview ? h('div', { style: { marginTop:'12px' } },
            h('img', { src:imagenPreview, alt:'Preview', style: { maxWidth:'100%', maxHeight:'160px', objectFit:'cover', borderRadius:'8px' } }),
            h('button', { onClick:function(){ setImagenB64(''); setImagenPreview(''); var fi2=document.getElementById(FILE_INPUT_ID); if(fi2) fi2.value=''; }, style: Object.assign({}, css.btnO('#c0271b'), {display:'block', marginTop:'6px', padding:'4px 12px', fontSize:'0.78rem'}) }, '✕ Quitar imagen')
          ) : null
        )
      ),
      h('div', null,
        h('div', { style: css.card },
          h('div', { style: { fontWeight:800, marginBottom:'16px' } }, '⚙ Configuración de la nota'),
          h('label', { style: css.lbl }, 'Pilar editorial *'),
          h('select', { value:pilar, onChange:function(e){ setPilar(e.target.value); }, style: Object.assign({}, css.inp, {marginBottom:'14px'}) },
            h('option', { value:'' }, '— Seleccioná un pilar —'),
            PILARES.map(function(p){ return h('option', { key:p.slug, value:p.nombre }, p.nombre); })
          ),
          h('label', { style: css.lbl }, 'Palabras clave / contexto adicional'),
          h('textarea', { value:keywords, onChange:function(e){ setKeywords(e.target.value); }, rows:4, placeholder:'Ej: reunión del Concejo del 15 de abril, aprobaron el presupuesto...', style: Object.assign({}, css.inp, {height:'100px', resize:'vertical', fontFamily:'inherit', marginBottom:'16px'}) }),
          h('div', { style: { display:'flex', gap:'10px' } },
            h('button', { onClick:generarBorrador, disabled:generando, style: Object.assign({}, css.btn(), {flex:1, fontSize:'1rem', padding:'14px'}) }, generando ? '⏳ La IA está escribiendo...' : '⚡ Generar borrador'),
            h('button', { onClick:limpiar, style:css.btnO() }, '↺ Limpiar')
          ),
          borradorError ? h('div', { style: { marginTop:'12px', padding:'10px 14px', background:'#fef2f2', border:'1px solid #fecaca', borderRadius:'8px', fontSize:'0.85rem', color:'#991b1b' } }, '❌ '+borradorError) : null
        ),
        borrador ? h('div', { style: css.card },
          h('div', { style: { display:'flex', justifyContent:'space-between', alignItems:'center', marginBottom:'14px' } },
            h('div', { style: { fontWeight:800 } }, '📄 Borrador generado'),
            h('button', { onClick:guardarEnWP, disabled:guardando, style:css.btn('#15803d') }, guardando ? '⏳ Guardando...' : '💾 Guardar en WordPress')
          ),
          guardadoUrl ? h('div', { style: { padding:'10px 16px', background:'#f0fdf4', border:'1px solid #bbf7d0', borderRadius:'8px', marginBottom:'14px', display:'flex', alignItems:'center', gap:'10px' } },
            h('span', { style: { color:'#15803d', fontWeight:700 } }, '✅ Guardado como borrador en WP'),
            h('a', { href:guardadoUrl, target:'_blank', style: Object.assign({}, css.btn('#15803d'), {fontSize:'0.8rem', padding:'6px 14px', textDecoration:'none'}) }, '✏ Abrir editor →')
          ) : null,
          h('div', { style: { background:'#f8f7f4', border:'1px solid #e5e0d8', borderRadius:'10px', padding:'20px', maxHeight:'500px', overflowY:'auto' } },
            h('pre', { style: { whiteSpace:'pre-wrap', lineHeight:1.8, fontSize:'0.88rem', color:'#222', fontFamily:'inherit', margin:0 } }, borrador)
          )
        ) : null
      )
    )
  );
}

/* ── APP PRINCIPAL ── */
function App() {
  const [screen, setScreen] = useState('dashboard');
  const screens = {
    dashboard:    { title:'Dashboard',    sub:'Estado general',           comp:Dashboard    },
    produccion:   { title:'Producción',   sub:'Gestión editorial',        comp:Produccion   },
    inteligencia: { title:'Inteligencia', sub:'12 agentes IA',            comp:Inteligencia },
    seguimiento:  { title:'Seguimiento',  sub:'Promesas y accountability', comp:Seguimiento  },
    asistente:    { title:'Asistente',    sub:'Generador de notas con IA', comp:Asistente   },
  };
  const s = screens[screen] || screens.dashboard;
  const Comp = s.comp;

  return h('div', { style: css.wrap },
    h(Sidebar, { screen:screen, setScreen:setScreen }),
    h('div', { style: css.main },
      h('div', { style: css.hdr },
        h('div', null,
          h('div', { style: { fontWeight:800, fontSize:'1.1rem', color:'#111' } }, s.title),
          h('div', { style: { fontSize:'0.78rem', color:'#999' } }, s.sub)
        ),
        h('div', { style: { marginLeft:'auto', display:'flex', gap:'10px', alignItems:'center' } },
          h('span', { style: { fontSize:'0.78rem', color:'#999' } }, 'v8.6.0'),
          h('a', { href:erData.siteUrl, target:'_blank', style: Object.assign({}, css.btnO(), {fontSize:'0.8rem', padding:'6px 14px', textDecoration:'none'}) }, '🌐 Ver sitio')
        )
      ),
      h('div', { style: css.body },
        screen === 'asistente'
          ? h(ErrorBoundary, null, h(Comp))
          : h(Comp)
      )
    )
  );
}

const root = ReactDOM.createRoot(document.getElementById('er-root'));
root.render(h(App));

})();
