/**
 * El Rufino — Panel IA
 * panel.js v8.6.0
 * React 18 UMD (sin build step). React y ReactDOM se cargan como globals via wp_enqueue_script.
 */
(function () {
  'use strict';

  var root = document.getElementById('er-root');
  if (!root) return;

  var h  = React.createElement;
  var useState    = React.useState;
  var useEffect   = React.useEffect;
  var useCallback = React.useCallback;
  var useRef      = React.useRef;
  var Fragment    = React.Fragment;

  /* ============================================================
     CONSTANTES
  ============================================================ */
  var RED    = '#c0271b';
  var DARK   = '#1f2a30';
  var CREAM  = '#f5f0e8';
  var BORDER = '#ddd8ce';

  var PILARES = [
    { slug: 'barrio-a-barrio',   nombre: 'Barrio a barrio',   color: '#b55233' },
    { slug: 'rufino-en-datos',   nombre: 'Rufino en datos',   color: '#2f6484' },
    { slug: 'el-campo-habla',    nombre: 'El campo habla',    color: '#617a45' },
    { slug: 'generacion-rufino', nombre: 'Generacion Rufino', color: '#c58a2b' },
    { slug: 'poder-y-gestion',   nombre: 'Poder y gestion',   color: '#1f2a30' },
  ];

  /* ============================================================
     HELPERS
  ============================================================ */
  function wpAjax(action, data) {
    var body = new FormData();
    body.append('action', action);
    body.append('nonce', erData.nonce);
    if (data) {
      Object.keys(data).forEach(function (k) { body.append(k, String(data[k])); });
    }
    return fetch(erData.ajaxUrl, { method: 'POST', body: body }).then(function (r) { return r.json(); });
  }

  function wpRest(path) {
    return fetch(erData.siteUrl + '/wp-json/wp/v2/' + path, {
      headers: { 'X-WP-Nonce': erData.nonce }
    }).then(function (r) { return r.json(); });
  }

  /* ============================================================
     ESTILOS
  ============================================================ */
  var S = {
    app: {
      display: 'flex', height: '100vh',
      fontFamily: "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif",
      background: '#f0ede8', color: '#1a1a1a', overflow: 'hidden',
    },
    sidebar: {
      width: 210, background: DARK, display: 'flex',
      flexDirection: 'column', flexShrink: 0, overflow: 'hidden',
    },
    sidebarHeader: {
      padding: '18px 16px 14px', borderBottom: '1px solid rgba(255,255,255,0.1)',
    },
    sidebarLogo: {
      color: '#fff', fontWeight: 900, fontSize: 17, letterSpacing: '-0.02em',
    },
    sidebarVersion: {
      color: 'rgba(255,255,255,0.38)', fontSize: 10, marginTop: 2,
    },
    sidebarNav: { flex: 1, padding: '6px 0', overflowY: 'auto' },
    sidebarFooter: {
      padding: '10px 16px', borderTop: '1px solid rgba(255,255,255,0.1)',
    },
    main: { flex: 1, display: 'flex', flexDirection: 'column', overflow: 'hidden', minWidth: 0 },
    topbar: {
      background: '#fff', borderBottom: '1px solid ' + BORDER,
      padding: '11px 24px', display: 'flex', alignItems: 'center',
      justifyContent: 'space-between', flexShrink: 0,
    },
    topbarTitle: { fontSize: 15, fontWeight: 700, color: DARK, margin: 0 },
    content: { flex: 1, overflowY: 'auto', padding: 20 },
    card: {
      background: '#fff', borderRadius: 8, padding: 20,
      boxShadow: '0 1px 3px rgba(0,0,0,0.07)', marginBottom: 16,
    },
    cardTitle: {
      fontSize: 11, fontWeight: 700, color: '#6b7280',
      textTransform: 'uppercase', letterSpacing: '0.06em', marginBottom: 14,
    },
    grid4: { display: 'grid', gridTemplateColumns: 'repeat(4,1fr)', gap: 12, marginBottom: 16 },
    grid2: { display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 16 },
    input: {
      width: '100%', padding: '8px 10px', borderRadius: 6,
      border: '1px solid ' + BORDER, fontSize: 13, boxSizing: 'border-box',
      outline: 'none', fontFamily: 'inherit',
    },
    textarea: {
      width: '100%', padding: '8px 10px', borderRadius: 6,
      border: '1px solid ' + BORDER, fontSize: 13, boxSizing: 'border-box',
      outline: 'none', fontFamily: 'inherit', resize: 'vertical',
    },
  };

  function btnStyle(variant) {
    var base = {
      padding: '7px 14px', borderRadius: 6, border: 'none',
      cursor: 'pointer', fontSize: 12, fontWeight: 600,
      transition: 'opacity 0.15s', display: 'inline-block', lineHeight: 1.4,
    };
    if (variant === 'primary') return Object.assign({}, base, { background: RED, color: '#fff' });
    if (variant === 'dark')    return Object.assign({}, base, { background: DARK, color: '#fff' });
    return Object.assign({}, base, { background: '#e5e7eb', color: '#374151' });
  }

  function navItemStyle(active) {
    return {
      display: 'flex', alignItems: 'center', gap: 10,
      padding: '9px 16px', cursor: 'pointer',
      color: active ? '#fff' : 'rgba(255,255,255,0.55)',
      background: active ? 'rgba(192,39,27,0.85)' : 'transparent',
      borderLeft: active ? '3px solid rgba(255,255,255,0.6)' : '3px solid transparent',
      fontSize: 13, fontWeight: active ? 600 : 400,
      userSelect: 'none', transition: 'background 0.1s',
    };
  }

  function statCardStyle(color) {
    return {
      background: color, borderRadius: 8, padding: '14px 18px', color: '#fff',
    };
  }

  function tagStyle(color) {
    return {
      background: color + '22', color: color, padding: '2px 8px',
      borderRadius: 10, fontSize: 11, fontWeight: 700,
    };
  }

  function badgeStyle(ok) {
    return {
      background: ok ? '#d1fae5' : '#fef3c7',
      color: ok ? '#065f46' : '#92400e',
      padding: '2px 7px', borderRadius: 10, fontSize: 10, fontWeight: 700,
    };
  }

  /* ============================================================
     PANTALLA 1: DASHBOARD
  ============================================================ */
  function Dashboard() {
    var statsState  = useState(null);
    var stats       = statsState[0];
    var setStats    = statsState[1];

    var checkState  = useState([]);
    var checklist   = checkState[0];
    var setCheck    = checkState[1];

    var antKState   = useState('');
    var antKey      = antKState[0];
    var setAntKey   = antKState[1];

    var antSState   = useState({ configured: false, masked: '' });
    var antStatus   = antSState[0];
    var setAntSt    = antSState[1];

    var savingState = useState(false);
    var saving      = savingState[0];
    var setSaving   = savingState[1];

    var impState    = useState(false);
    var importing   = impState[0];
    var setImporting = impState[1];

    var progState   = useState(0);
    var progress    = progState[0];
    var setProgress = progState[1];

    var logState    = useState([]);
    var importLog   = logState[0];
    var setLog      = logState[1];

    function loadAll() {
      wpAjax('er_stats').then(function (r) { if (r.success) setStats(r.data); });
      wpAjax('er_get_checklist').then(function (r) { if (r.success) setCheck(r.data); });
      wpAjax('er_key_status').then(function (r) {
        if (r.success) setAntSt({ configured: r.data.configured, masked: r.data.masked });
      });
    }

    useEffect(function () { loadAll(); }, []);

    function saveAnt() {
      setSaving(true);
      wpAjax('er_save_key', { key: antKey })
        .then(function () { return wpAjax('er_key_status'); })
        .then(function (r) {
          if (r.success) setAntSt({ configured: r.data.configured, masked: r.data.masked });
          setSaving(false);
          setAntKey('');
        });
    }

    function toggleItem(id, ok) {
      var updated = checklist.map(function (i) { return i.id === id ? Object.assign({}, i, { ok: !ok }) : i; });
      setCheck(updated);
      wpAjax('er_save_checklist', { items: JSON.stringify(updated) });
    }

    function runImport() {
      setImporting(true);
      setProgress(0);
      setLog([]);
      var idx = 0;
      function next() {
        if (idx >= 10) {
          setImporting(false);
          wpAjax('er_stats').then(function (r) { if (r.success) setStats(r.data); });
          return;
        }
        wpAjax('er_import_demo_one', { index: idx }).then(function (r) {
          var line = r.success ? ('✓ ' + (r.data.titulo || 'Nota ' + idx)) : ('✗ Error nota ' + idx);
          setLog(function (prev) { return prev.concat([line]); });
          setProgress(idx + 1);
          idx++;
          next();
        });
      }
      next();
    }

    var statCards = stats ? [
      { label: 'Publicadas',      value: stats.published, color: '#2563eb' },
      { label: 'Borradores',      value: stats.drafts,    color: '#7c3aed' },
      { label: 'Comentarios',     value: stats.comments,  color: '#0891b2' },
      { label: 'Actualizaciones', value: stats.updates,   color: stats.updates > 0 ? '#d97706' : '#059669' },
    ] : [];

    return h(Fragment, null,
      /* Stats */
      h('div', { style: S.grid4 },
        statCards.length === 0
          ? [0,1,2,3].map(function (i) {
              return h('div', { key: i, style: statCardStyle('#9ca3af') },
                h('div', { style: { fontSize: 28, fontWeight: 900 } }, '…'),
                h('div', { style: { fontSize: 11, opacity: 0.8, marginTop: 4 } }, 'Cargando')
              );
            })
          : statCards.map(function (sc) {
              return h('div', { key: sc.label, style: statCardStyle(sc.color) },
                h('div', { style: { fontSize: 28, fontWeight: 900, lineHeight: 1 } }, sc.value),
                h('div', { style: { fontSize: 11, opacity: 0.8, marginTop: 4 } }, sc.label)
              );
            })
      ),

      /* Checklist + Import */
      h('div', { style: S.grid2 },

        h('div', { style: S.card },
          h('div', { style: S.cardTitle }, 'Checklist de lanzamiento'),
          checklist.length === 0
            ? h('p', { style: { color: '#9ca3af', fontSize: 13 } }, 'Cargando…')
            : checklist.map(function (item) {
                return h('label', {
                  key: item.id,
                  style: {
                    display: 'flex', alignItems: 'center', gap: 10,
                    padding: '7px 0', cursor: 'pointer', fontSize: 13,
                    borderBottom: '1px solid #f3f4f6',
                  },
                },
                  h('input', {
                    type: 'checkbox', checked: !!item.ok,
                    onChange: function () { toggleItem(item.id, item.ok); },
                    style: { width: 15, height: 15, accentColor: RED, cursor: 'pointer', flexShrink: 0 },
                  }),
                  h('span', {
                    style: { textDecoration: item.ok ? 'line-through' : 'none', color: item.ok ? '#9ca3af' : '#374151' },
                  }, item.texto)
                );
              })
        ),

        h('div', { style: S.card },
          h('div', { style: S.cardTitle }, 'Importar notas demo'),
          h('p', { style: { fontSize: 13, color: '#6b7280', marginBottom: 14, lineHeight: 1.5 } },
            'Genera 10 notas con IA distribuidas en los 5 pilares editoriales y las guarda como borradores.'
          ),
          !importing && importLog.length === 0 && (!stats || stats.published < 5) && h('button', {
            onClick: runImport,
            style: btnStyle('primary'),
          }, 'Generar 10 notas demo'),

          importing && h('div', null,
            h('p', { style: { fontSize: 13, color: '#374151', marginBottom: 8 } }, 'Generando nota ' + progress + ' de 10…'),
            h('div', { style: { background: '#e5e7eb', borderRadius: 4, height: 6 } },
              h('div', { style: {
                background: RED, height: 6, borderRadius: 4,
                width: ((progress / 10) * 100) + '%', transition: 'width 0.3s',
              }})
            )
          ),

          importLog.length > 0 && !importing && h('div', null,
            h('p', { style: { fontSize: 12, color: '#059669', fontWeight: 700, marginBottom: 6 } }, '✓ Completado'),
            h('div', { style: { maxHeight: 120, overflowY: 'auto', fontSize: 11, color: '#374151', lineHeight: 1.6 } },
              importLog.map(function (l, i) { return h('div', { key: i }, l); })
            ),
            h('button', { onClick: runImport, style: Object.assign({}, btnStyle('ghost'), { marginTop: 10, fontSize: 11 }) }, 'Re-generar')
          )
        )
      ),

      /* Proveedor IA */
      h('div', { style: S.card },
        h('div', { style: S.cardTitle }, 'Proveedor IA — Anthropic (Claude)'),
        antStatus.configured && h('div', { style: { marginBottom: 10 } },
          h('span', { style: { background: '#eff6ff', color: '#1d4ed8', fontSize: 10, padding: '2px 7px', borderRadius: 10 } }, antStatus.masked)
        ),
        h('div', { style: { display: 'flex', gap: 8 } },
          h('input', {
            type: 'password', placeholder: 'sk-ant-api03-…',
            value: antKey, onChange: function (e) { setAntKey(e.target.value); },
            style: Object.assign({}, S.input, { flex: 1 }),
          }),
          h('button', {
            onClick: saveAnt, disabled: !antKey || saving,
            style: btnStyle('primary'),
          }, saving ? '…' : 'Guardar')
        )
      )
    );
  }

  /* ============================================================
     PANTALLA 2: PRODUCCION
  ============================================================ */
  function Produccion() {
    var postsState  = useState([]);
    var posts       = postsState[0];
    var setPosts    = postsState[1];

    var loadState   = useState(true);
    var loading     = loadState[0];
    var setLoading  = loadState[1];

    var filtState   = useState('any');
    var filter      = filtState[0];
    var setFilter   = filtState[1];

    useEffect(function () {
      setLoading(true);
      wpAjax('er_get_posts', { status: filter })
        .then(function (r) {
          setPosts(r.success && Array.isArray(r.data) ? r.data : []);
          setLoading(false);
        })
        .catch(function () { setLoading(false); });
    }, [filter]);

    function pilarColor(slug) {
      for (var i = 0; i < PILARES.length; i++) {
        if (PILARES[i].slug === slug) return PILARES[i].color;
      }
      return '#9ca3af';
    }

    return h('div', null,
      /* Toolbar */
      h('div', { style: { display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 16 } },
        h('div', { style: { display: 'flex', gap: 8 } },
          ['any', 'publish', 'draft'].map(function (f) {
            return h('button', {
              key: f, onClick: function () { setFilter(f); },
              style: btnStyle(f === filter ? 'primary' : 'ghost'),
            }, f === 'any' ? 'Todas' : f === 'publish' ? 'Publicadas' : 'Borradores');
          })
        ),
        h('a', {
          href: erData.adminUrl + 'post-new.php', target: '_self',
          style: Object.assign({}, btnStyle('dark'), { textDecoration: 'none' }),
        }, '+ Nueva entrada')
      ),

      h('div', { style: S.card },
        loading && h('p', { style: { color: '#9ca3af', fontSize: 13 } }, 'Cargando entradas…'),
        !loading && posts.length === 0 && h('p', { style: { color: '#9ca3af', fontSize: 13 } }, 'Sin entradas todavía.'),
        !loading && posts.map(function (post) {
          var color = pilarColor(post.cat_slug);
          var isPub = post.status === 'publish';
          return h('div', {
            key: post.id,
            style: {
              display: 'flex', alignItems: 'center', gap: 12,
              padding: '10px 0', borderBottom: '1px solid #f3f4f6',
            },
          },
            h('div', { style: { width: 4, height: 36, borderRadius: 2, background: color, flexShrink: 0 } }),
            h('div', { style: { flex: 1, minWidth: 0 } },
              h('a', {
                href: post.edit_url, target: '_self',
                style: {
                  fontSize: 14, fontWeight: 600, color: '#111', textDecoration: 'none',
                  display: 'block', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap',
                },
              }, post.title || '(sin título)'),
              h('div', { style: { fontSize: 11, color: '#9ca3af', marginTop: 2 } },
                post.cat_name + (post.date ? ' · ' + post.date : '')
              )
            ),
            h('span', { style: badgeStyle(isPub) }, isPub ? 'Pub' : 'Bor'),
            isPub && h('a', {
              href: post.view_url, target: '_blank',
              style: Object.assign({}, btnStyle('ghost'), { textDecoration: 'none', fontSize: 11 }),
            }, 'Ver'),
            h('a', {
              href: post.edit_url, target: '_self',
              style: Object.assign({}, btnStyle('ghost'), { textDecoration: 'none', fontSize: 11 }),
            }, 'Editar')
          );
        })
      )
    );
  }

  /* ============================================================
     PANTALLA 3: INTELIGENCIA
  ============================================================ */
  function Inteligencia() {
    var catState   = useState([]);
    var catStats   = catState[0];
    var setCatStats = catState[1];

    var loadState  = useState(true);
    var loading    = loadState[0];
    var setLoading = loadState[1];

    useEffect(function () {
      fetch(erData.siteUrl + '/wp-json/wp/v2/categories?per_page=50')
        .then(function (r) { return r.json(); })
        .then(function (cats) {
          if (!Array.isArray(cats)) { setLoading(false); return; }
          var mapped = PILARES.map(function (p) {
            var c = cats.filter(function (x) { return x.slug === p.slug; })[0];
            return Object.assign({}, p, { count: c ? c.count : 0 });
          });
          setCatStats(mapped);
          setLoading(false);
        })
        .catch(function () { setLoading(false); });
    }, []);

    var total = catStats.reduce(function (s, c) { return s + c.count; }, 0);
    var max   = Math.max.apply(null, catStats.map(function (c) { return c.count; }).concat([1]));

    return h('div', null,
      /* Barras por pilar */
      h('div', { style: S.card },
        h('div', { style: S.cardTitle }, 'Distribucion editorial por pilar'),
        loading && h('p', { style: { color: '#9ca3af', fontSize: 13 } }, 'Cargando…'),
        !loading && catStats.map(function (p) {
          return h('div', { key: p.slug, style: { marginBottom: 14 } },
            h('div', { style: { display: 'flex', justifyContent: 'space-between', marginBottom: 5 } },
              h('span', { style: { fontSize: 13, fontWeight: 600, color: p.color } }, p.nombre),
              h('span', { style: { fontSize: 13, color: '#6b7280' } }, p.count + (p.count === 1 ? ' nota' : ' notas'))
            ),
            h('div', { style: { background: '#e5e7eb', borderRadius: 4, height: 8 } },
              h('div', { style: {
                background: p.color, height: 8, borderRadius: 4,
                width: (max > 0 ? Math.round((p.count / max) * 100) : 0) + '%',
                transition: 'width 0.5s',
              }})
            )
          );
        }),
        !loading && h('div', { style: {
          marginTop: 18, padding: '12px 0', borderTop: '1px solid ' + BORDER,
          display: 'flex', justifyContent: 'space-between', alignItems: 'center',
        }},
          h('span', { style: { fontSize: 13, color: '#6b7280' } }, 'Total entradas publicadas'),
          h('span', { style: { fontSize: 22, fontWeight: 900, color: DARK } }, total)
        )
      ),

      /* Cards por pilar */
      h('div', { style: S.card },
        h('div', { style: S.cardTitle }, 'Cobertura por pilar'),
        loading && h('p', { style: { color: '#9ca3af', fontSize: 13 } }, 'Cargando…'),
        !loading && h('div', { style: { display: 'grid', gridTemplateColumns: 'repeat(5,1fr)', gap: 10 } },
          catStats.map(function (p) {
            var pct = total > 0 ? Math.round((p.count / total) * 100) : 0;
            return h('div', {
              key: p.slug,
              style: {
                textAlign: 'center', padding: '16px 8px', borderRadius: 8,
                border: '2px solid ' + p.color + '40', background: p.color + '08',
              },
            },
              h('div', { style: { fontSize: 26, fontWeight: 900, color: p.color, lineHeight: 1 } }, p.count),
              h('div', { style: { fontSize: 10, color: '#6b7280', marginTop: 6, fontWeight: 600, lineHeight: 1.3 } }, p.nombre),
              h('div', { style: { fontSize: 11, color: p.color, marginTop: 5, fontWeight: 700 } }, pct + '%')
            );
          })
        )
      )
    );
  }

  /* ============================================================
     PANTALLA 4: SEGUIMIENTO (PROMESAS)
  ============================================================ */
  var ESTADOS = {
    pendiente:  { label: 'Pendiente',  color: '#d97706', bg: '#fef3c7' },
    cumplida:   { label: 'Cumplida',   color: '#059669', bg: '#d1fae5' },
    incumplida: { label: 'Incumplida', color: '#dc2626', bg: '#fee2e2' },
    parcial:    { label: 'Parcial',    color: '#7c3aed', bg: '#ede9fe' },
  };

  var ESTADOS_COB = {
    activo:      { label: 'Activo',    color: '#1d4ed8', bg: '#eff6ff' },
    'en-espera': { label: 'En espera', color: '#d97706', bg: '#fef3c7' },
    cerrado:     { label: 'Cerrado',   color: '#6b7280', bg: '#f3f4f6' },
  };

  /* ============================================================
     COMPONENTE: COBERTURAS
  ============================================================ */
  function Coberturas() {
    var cobState      = useState([]);
    var coberturas    = cobState[0];
    var setCoberturas = cobState[1];

    var loadState  = useState(true);
    var loading    = loadState[0];
    var setLoading = loadState[1];

    var formState = useState({
      tema: '', tipo: 'Municipal', pilar: '', descripcion: '',
      proxima_accion: '', fecha_revision: '', nota_url: '', estado: 'activo',
    });
    var form    = formState[0];
    var setForm = formState[1];

    var addingState = useState(false);
    var adding      = addingState[0];
    var setAdding   = addingState[1];

    var showState = useState(false);
    var showForm  = showState[0];
    var setShow   = showState[1];

    function load() {
      setLoading(true);
      wpAjax('er_get_coberturas').then(function (r) {
        if (r.success) setCoberturas(Array.isArray(r.data) ? r.data : []);
        setLoading(false);
      });
    }
    useEffect(load, []);

    function addCobertura() {
      if (!form.tema.trim()) return;
      setAdding(true);
      wpAjax('er_save_cobertura', form).then(function () {
        setForm({ tema: '', tipo: 'Municipal', pilar: '', descripcion: '', proxima_accion: '', fecha_revision: '', nota_url: '', estado: 'activo' });
        setShow(false);
        setAdding(false);
        load();
      });
    }

    function updateEstadoCob(id, estado) {
      wpAjax('er_update_cobertura', { id: id, campo: 'estado', valor: estado });
      setCoberturas(function (prev) {
        return prev.map(function (c) { return c.id === id ? Object.assign({}, c, { estado: estado }) : c; });
      });
    }

    function deleteCobertura(id) {
      if (!confirm('¿Eliminar esta cobertura?')) return;
      wpAjax('er_delete_cobertura', { id: id });
      setCoberturas(function (prev) { return prev.filter(function (c) { return c.id !== id; }); });
    }

    function exportCsv() {
      var cols = ['id', 'tema', 'tipo', 'pilar', 'descripcion', 'proxima_accion', 'fecha_revision', 'nota_url', 'estado', 'fecha_apertura'];
      var header = 'ID,Tema,Tipo,Pilar,Descripcion,Proxima accion,Fecha revision,URL nota,Estado,Fecha apertura\n';
      var rows = coberturas.map(function (c) {
        return cols.map(function (k) { return '"' + String(c[k] || '').replace(/"/g, '""') + '"'; }).join(',');
      });
      var csv  = header + rows.join('\n');
      var blob = new Blob([csv], { type: 'text/csv;charset=utf-8' });
      var url  = URL.createObjectURL(blob);
      var a    = document.createElement('a');
      a.href = url; a.download = 'coberturas-el-rufino.csv'; a.click();
      URL.revokeObjectURL(url);
    }

    var TIPOS = ['Legislativo', 'Municipal', 'Judicial', 'Ejecutivo', 'Otro'];

    return h('div', null,
      h('div', { style: { display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 16 } },
        h('span', { style: { fontSize: 14, fontWeight: 700, color: DARK } },
          coberturas.length + (coberturas.length === 1 ? ' cobertura registrada' : ' coberturas registradas')
        ),
        h('div', { style: { display: 'flex', gap: 8 } },
          coberturas.length > 0 && h('button', { onClick: exportCsv, style: btnStyle('ghost') }, 'Exportar CSV'),
          h('button', {
            onClick: function () { setShow(function (v) { return !v; }); },
            style: btnStyle('primary'),
          }, showForm ? 'Cancelar' : '+ Nueva cobertura')
        )
      ),

      showForm && h('div', { style: Object.assign({}, S.card, { borderLeft: '3px solid ' + RED, marginBottom: 16 }) },
        h('div', { style: S.cardTitle }, 'Registrar cobertura'),
        h('input', {
          type: 'text', placeholder: 'Tema de la cobertura (requerido)',
          value: form.tema,
          onChange: function (e) { setForm(function (f) { return Object.assign({}, f, { tema: e.target.value }); }); },
          style: Object.assign({}, S.input, { marginBottom: 8 }),
        }),
        h('div', { style: { display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 8, marginBottom: 8 } },
          h('select', {
            value: form.tipo,
            onChange: function (e) { setForm(function (f) { return Object.assign({}, f, { tipo: e.target.value }); }); },
            style: S.input,
          },
            TIPOS.map(function (t) { return h('option', { key: t, value: t }, t); })
          ),
          h('select', {
            value: form.pilar,
            onChange: function (e) { setForm(function (f) { return Object.assign({}, f, { pilar: e.target.value }); }); },
            style: S.input,
          },
            [h('option', { key: '', value: '' }, 'Sin pilar')].concat(
              PILARES.map(function (p) { return h('option', { key: p.slug, value: p.slug }, p.nombre); })
            )
          )
        ),
        h('textarea', {
          placeholder: 'Descripcion de la cobertura…',
          value: form.descripcion, rows: 2,
          onChange: function (e) { setForm(function (f) { return Object.assign({}, f, { descripcion: e.target.value }); }); },
          style: Object.assign({}, S.textarea, { marginBottom: 8 }),
        }),
        h('div', { style: { display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 8, marginBottom: 8 } },
          h('input', {
            type: 'text', placeholder: 'Proxima accion',
            value: form.proxima_accion,
            onChange: function (e) { setForm(function (f) { return Object.assign({}, f, { proxima_accion: e.target.value }); }); },
            style: S.input,
          }),
          h('input', {
            type: 'date', value: form.fecha_revision,
            onChange: function (e) { setForm(function (f) { return Object.assign({}, f, { fecha_revision: e.target.value }); }); },
            style: S.input,
          })
        ),
        h('input', {
          type: 'url', placeholder: 'URL de la nota publicada',
          value: form.nota_url,
          onChange: function (e) { setForm(function (f) { return Object.assign({}, f, { nota_url: e.target.value }); }); },
          style: Object.assign({}, S.input, { marginBottom: 10 }),
        }),
        h('div', { style: { display: 'flex', gap: 8 } },
          h('button', {
            onClick: addCobertura, disabled: adding || !form.tema.trim(),
            style: btnStyle('primary'),
          }, adding ? 'Guardando…' : 'Guardar'),
          h('button', { onClick: function () { setShow(false); }, style: btnStyle('ghost') }, 'Cancelar')
        )
      ),

      h('div', { style: S.card },
        loading && h('p', { style: { color: '#9ca3af', fontSize: 13 } }, 'Cargando…'),
        !loading && coberturas.length === 0 && h('p', { style: { color: '#9ca3af', fontSize: 13 } },
          'Sin coberturas registradas. Registra la primera usando el boton de arriba.'
        ),
        coberturas.map(function (c) {
          var est      = ESTADOS_COB[c.estado] || ESTADOS_COB.activo;
          var pilarObj = PILARES.filter(function (p) { return p.slug === c.pilar; })[0];
          return h('div', {
            key: c.id,
            style: { display: 'flex', alignItems: 'flex-start', gap: 12, padding: '11px 0', borderBottom: '1px solid #f3f4f6' },
          },
            h('div', { style: { width: 10, height: 10, borderRadius: '50%', background: est.color, flexShrink: 0, marginTop: 5 } }),
            h('div', { style: { flex: 1, minWidth: 0 } },
              h('p', { style: { fontSize: 14, margin: '0 0 3px', fontWeight: 500, lineHeight: 1.4 } }, c.tema),
              h('div', { style: { fontSize: 11, color: '#9ca3af', display: 'flex', gap: 8, flexWrap: 'wrap', alignItems: 'center' } },
                h('span', null, c.tipo),
                pilarObj && h('span', { style: { color: pilarObj.color, fontWeight: 700 } }, pilarObj.nombre),
                c.fecha_revision && h('span', null, 'Rev: ' + c.fecha_revision),
                c.proxima_accion && h('span', null, c.proxima_accion),
                c.nota_url && h('a', { href: c.nota_url, target: '_blank', style: { color: '#1d4ed8' } }, 'Ver nota')
              )
            ),
            h('select', {
              value: c.estado,
              onChange: function (e) { updateEstadoCob(c.id, e.target.value); },
              style: {
                fontSize: 11, padding: '4px 8px', borderRadius: 6,
                border: '1px solid ' + BORDER,
                background: est.bg, color: est.color, fontWeight: 700, cursor: 'pointer',
              },
            },
              Object.keys(ESTADOS_COB).map(function (k) {
                return h('option', { key: k, value: k }, ESTADOS_COB[k].label);
              })
            ),
            h('button', {
              onClick: function () { deleteCobertura(c.id); },
              style: Object.assign({}, btnStyle('ghost'), { fontSize: 11, padding: '4px 8px', color: '#dc2626' }),
            }, '✕')
          );
        })
      )
    );
  }

  /* ============================================================
     PANTALLA 4: SEGUIMIENTO (PROMESAS + COBERTURAS)
  ============================================================ */
  function Seguimiento() {
    var tabSegSt  = useState('promesas');
    var tabSeg    = tabSegSt[0];
    var setTabSeg = tabSegSt[1];

    var promState   = useState([]);
    var promesas    = promState[0];
    var setPromesas = promState[1];

    var loadState   = useState(true);
    var loading     = loadState[0];
    var setLoading  = loadState[1];

    var formState   = useState({ texto: '', fuente: '', fecha: new Date().toISOString().slice(0, 10) });
    var form        = formState[0];
    var setForm     = formState[1];

    var addingState = useState(false);
    var adding      = addingState[0];
    var setAdding   = addingState[1];

    var showState   = useState(false);
    var showForm    = showState[0];
    var setShow     = showState[1];

    function load() {
      setLoading(true);
      wpAjax('er_get_promesas').then(function (r) {
        if (r.success) setPromesas(Array.isArray(r.data) ? r.data : []);
        setLoading(false);
      });
    }
    useEffect(load, []);

    function addPromesa() {
      if (!form.texto.trim()) return;
      setAdding(true);
      wpAjax('er_save_promesa', form).then(function () {
        setForm({ texto: '', fuente: '', fecha: new Date().toISOString().slice(0, 10) });
        setShow(false);
        setAdding(false);
        load();
      });
    }

    function updateEstado(id, estado) {
      wpAjax('er_update_promesa', { id: id, estado: estado });
      setPromesas(function (prev) {
        return prev.map(function (p) { return p.id === id ? Object.assign({}, p, { estado: estado }) : p; });
      });
    }

    function exportCsv() {
      wpAjax('er_export_promesas').then(function (r) {
        if (!r.success) return;
        var blob = new Blob([r.data.csv], { type: 'text/csv;charset=utf-8' });
        var url  = URL.createObjectURL(blob);
        var a    = document.createElement('a');
        a.href = url; a.download = 'promesas-el-rufino.csv'; a.click();
        URL.revokeObjectURL(url);
      });
    }

    function tabItemStyle(active) {
      return {
        padding: '8px 18px', cursor: 'pointer', fontSize: 13,
        fontWeight: active ? 700 : 400,
        color: active ? RED : '#6b7280',
        borderBottom: active ? '2px solid ' + RED : '2px solid transparent',
        userSelect: 'none', transition: 'color 0.1s',
      };
    }

    return h('div', null,
      h('div', { style: {
        display: 'flex', background: '#fff', borderRadius: 8,
        marginBottom: 16, borderBottom: '1px solid ' + BORDER, paddingLeft: 4,
      }},
        h('div', { style: tabItemStyle(tabSeg === 'promesas'),  onClick: function () { setTabSeg('promesas'); } },  '📌 Promesas'),
        h('div', { style: tabItemStyle(tabSeg === 'coberturas'), onClick: function () { setTabSeg('coberturas'); } }, '🔍 Coberturas')
      ),

      tabSeg === 'promesas' && h(Fragment, null,
        h('div', { style: { display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 16 } },
          h('span', { style: { fontSize: 14, fontWeight: 700, color: DARK } },
            promesas.length + (promesas.length === 1 ? ' promesa registrada' : ' promesas registradas')
          ),
          h('div', { style: { display: 'flex', gap: 8 } },
            promesas.length > 0 && h('button', { onClick: exportCsv, style: btnStyle('ghost') }, 'Exportar CSV'),
            h('button', {
              onClick: function () { setShow(function (v) { return !v; }); },
              style: btnStyle('primary'),
            }, showForm ? 'Cancelar' : '+ Nueva promesa')
          )
        ),

        showForm && h('div', { style: Object.assign({}, S.card, { borderLeft: '3px solid ' + RED, marginBottom: 16 }) },
          h('div', { style: S.cardTitle }, 'Registrar promesa'),
          h('textarea', {
            placeholder: 'Descripcion de la promesa…',
            value: form.texto, rows: 3,
            onChange: function (e) { setForm(function (f) { return Object.assign({}, f, { texto: e.target.value }); }); },
            style: Object.assign({}, S.textarea, { marginBottom: 8 }),
          }),
          h('div', { style: { display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 8, marginBottom: 10 } },
            h('input', {
              type: 'text', placeholder: 'Fuente (quien prometio)',
              value: form.fuente,
              onChange: function (e) { setForm(function (f) { return Object.assign({}, f, { fuente: e.target.value }); }); },
              style: S.input,
            }),
            h('input', {
              type: 'date', value: form.fecha,
              onChange: function (e) { setForm(function (f) { return Object.assign({}, f, { fecha: e.target.value }); }); },
              style: S.input,
            })
          ),
          h('div', { style: { display: 'flex', gap: 8 } },
            h('button', {
              onClick: addPromesa, disabled: adding || !form.texto.trim(),
              style: btnStyle('primary'),
            }, adding ? 'Guardando…' : 'Guardar'),
            h('button', { onClick: function () { setShow(false); }, style: btnStyle('ghost') }, 'Cancelar')
          )
        ),

        h('div', { style: S.card },
          loading && h('p', { style: { color: '#9ca3af', fontSize: 13 } }, 'Cargando…'),
          !loading && promesas.length === 0 && h('p', { style: { color: '#9ca3af', fontSize: 13 } },
            'Sin promesas registradas. Registra la primera usando el boton de arriba.'
          ),
          promesas.map(function (p) {
            var est = ESTADOS[p.estado] || ESTADOS.pendiente;
            return h('div', {
              key: p.id,
              style: { display: 'flex', alignItems: 'flex-start', gap: 12, padding: '11px 0', borderBottom: '1px solid #f3f4f6' },
            },
              h('div', { style: { width: 10, height: 10, borderRadius: '50%', background: est.color, flexShrink: 0, marginTop: 5 } }),
              h('div', { style: { flex: 1, minWidth: 0 } },
                h('p', { style: { fontSize: 14, margin: '0 0 3px', fontWeight: 500, lineHeight: 1.4 } }, p.texto),
                h('div', { style: { fontSize: 11, color: '#9ca3af' } },
                  [p.fuente, p.fecha].filter(Boolean).join(' · ')
                )
              ),
              h('select', {
                value: p.estado,
                onChange: function (e) { updateEstado(p.id, e.target.value); },
                style: {
                  fontSize: 11, padding: '4px 8px', borderRadius: 6,
                  border: '1px solid ' + BORDER,
                  background: est.bg, color: est.color, fontWeight: 700, cursor: 'pointer',
                },
              },
                Object.keys(ESTADOS).map(function (k) {
                  return h('option', { key: k, value: k }, ESTADOS[k].label);
                })
              )
            );
          })
        )
      ),

      tabSeg === 'coberturas' && h(Coberturas)
    );
  }

  /* ============================================================
     PANTALLA 5: ASISTENTE IA
  ============================================================ */
  function Asistente() {
    var ytUrlSt     = useState('');
    var ytUrl       = ytUrlSt[0];
    var setYtUrl    = ytUrlSt[1];

    var ytInfoSt    = useState(null);
    var ytInfo      = ytInfoSt[0];
    var setYtInfo   = ytInfoSt[1];

    var ytKeySt     = useState('');
    var ytKey       = ytKeySt[0];
    var setYtKey    = ytKeySt[1];

    var ytKStatusSt = useState({ configured: false, masked: '' });
    var ytKStatus   = ytKStatusSt[0];
    var setYtKSt    = ytKStatusSt[1];

    var transcSt    = useState('');
    var transc      = transcSt[0];
    var setTransc   = transcSt[1];

    var kwSt        = useState('');
    var keywords    = kwSt[0];
    var setKw       = kwSt[1];

    var pilarSt     = useState(PILARES[0].slug);
    var pilar       = pilarSt[0];
    var setPilar    = pilarSt[1];

    var imgB64St    = useState('');
    var imgB64      = imgB64St[0];
    var setImgB64   = imgB64St[1];

    var imgTypeSt   = useState('image/jpeg');
    var imgType     = imgTypeSt[0];
    var setImgType  = imgTypeSt[1];

    var borSt       = useState('');
    var borrador    = borSt[0];
    var setBorrador = borSt[1];

    var loadYtSt    = useState(false);
    var loadYt      = loadYtSt[0];
    var setLoadYt   = loadYtSt[1];

    var loadCapSt   = useState(false);
    var loadCap     = loadCapSt[0];
    var setLoadCap  = loadCapSt[1];

    var loadGenSt   = useState(false);
    var loadGen     = loadGenSt[0];
    var setLoadGen  = loadGenSt[1];

    var loadSaveSt  = useState(false);
    var loadSave    = loadSaveSt[0];
    var setLoadSave = loadSaveSt[1];

    var savedUrlSt  = useState('');
    var savedUrl    = savedUrlSt[0];
    var setSavedUrl = savedUrlSt[1];

    var errSt       = useState('');
    var err         = errSt[0];
    var setErr      = errSt[1];

    var fileRef     = useRef(null);

    useEffect(function () {
      wpAjax('er_ytkey_status').then(function (r) {
        if (r.success) setYtKSt({ configured: r.data.configured, masked: r.data.masked });
      });
    }, []);

    function saveYtKey() {
      wpAjax('er_save_ytkey', { key: ytKey }).then(function () {
        return wpAjax('er_ytkey_status');
      }).then(function (r) {
        if (r.success) setYtKSt({ configured: r.data.configured, masked: r.data.masked });
        setYtKey('');
      });
    }

    function fetchYt() {
      setLoadYt(true); setErr(''); setYtInfo(null);
      wpAjax('er_yt_info', { url: ytUrl }).then(function (r) {
        if (r.success) setYtInfo(r.data);
        else setErr((r.data && r.data.msg) || 'Error al obtener datos del video.');
        setLoadYt(false);
      });
    }

    function fetchCaptions() {
      if (!ytInfo) return;
      setLoadCap(true); setErr('');
      wpAjax('er_yt_captions', { video_id: ytInfo.video_id }).then(function (r) {
        if (r.success) setTransc(r.data.transcripcion);
        else setErr((r.data && r.data.msg) || 'No se encontraron subtitulos automaticos.');
        setLoadCap(false);
      });
    }

    function handleImage(e) {
      var file = e.target.files && e.target.files[0];
      if (!file) return;
      setImgType(file.type);
      var reader = new FileReader();
      reader.onload = function (ev) { setImgB64(ev.target.result); };
      reader.readAsDataURL(file);
    }

    function generar() {
      setLoadGen(true); setErr(''); setBorrador(''); setSavedUrl('');
      var data = { pilar: pilar, keywords: keywords, transcripcion: transc };
      if (ytInfo) {
        data.video_titulo = ytInfo.titulo;
        data.video_desc   = ytInfo.descripcion;
        data.video_canal  = ytInfo.canal;
        data.video_fecha  = ytInfo.fecha;
      }
      if (imgB64) { data.imagen_b64 = imgB64; data.imagen_type = imgType; }
      wpAjax('er_asistente_generar', data).then(function (r) {
        if (r.success) setBorrador(r.data.borrador);
        else setErr((r.data && r.data.msg) || 'Error al generar la nota.');
        setLoadGen(false);
      });
    }

    function guardar() {
      setLoadSave(true); setErr('');
      var titleMatch = borrador.match(/\*\*T[IÍ]TULO:\*\*\s*(.+)/);
      var titulo     = titleMatch ? titleMatch[1].trim() : 'Borrador sin titulo';
      wpAjax('er_asistente_guardar', {
        titulo: titulo,
        contenido: borrador.replace(/\n/g, '<br>'),
        pilar_slug: pilar,
      }).then(function (r) {
        if (r.success) setSavedUrl(r.data.edit_url);
        else setErr((r.data && r.data.msg) || 'Error al guardar.');
        setLoadSave(false);
      });
    }

    var canGenerate = !loadGen && (transc || ytInfo || keywords);

    return h('div', { style: { display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 16, alignItems: 'start' } },

      /* Columna izquierda: contexto */
      h('div', null,

        /* YT API Key */
        !ytKStatus.configured && h('div', { style: Object.assign({}, S.card, { borderLeft: '3px solid #d97706', marginBottom: 16 }) },
          h('div', { style: S.cardTitle }, 'YouTube API Key (opcional)'),
          h('div', { style: { display: 'flex', gap: 8 } },
            h('input', {
              type: 'password', placeholder: 'AIzaSy…',
              value: ytKey, onChange: function (e) { setYtKey(e.target.value); },
              style: Object.assign({}, S.input, { flex: 1 }),
            }),
            h('button', { onClick: saveYtKey, disabled: !ytKey, style: btnStyle('primary') }, 'Guardar')
          )
        ),

        /* Video YouTube */
        h('div', { style: S.card },
          h('div', { style: S.cardTitle }, 'Video YouTube (opcional)'),
          h('div', { style: { display: 'flex', gap: 8, marginBottom: ytInfo ? 12 : 0 } },
            h('input', {
              type: 'text', placeholder: 'youtu.be/… o youtube.com/watch?v=…',
              value: ytUrl, onChange: function (e) { setYtUrl(e.target.value); },
              onKeyDown: function (e) { if (e.key === 'Enter' && ytKStatus.configured) fetchYt(); },
              style: Object.assign({}, S.input, { flex: 1 }),
            }),
            h('button', {
              onClick: fetchYt,
              disabled: !ytUrl || loadYt || !ytKStatus.configured,
              style: btnStyle('primary'),
            }, loadYt ? '…' : 'Buscar')
          ),
          !ytKStatus.configured && h('p', { style: { fontSize: 11, color: '#9ca3af', margin: '6px 0 0' } },
            'Configura la YouTube API Key para buscar videos.'
          ),
          ytInfo && h('div', { style: { display: 'flex', gap: 12, alignItems: 'flex-start' } },
            ytInfo.thumbnail && h('img', { src: ytInfo.thumbnail, alt: '', style: { width: 90, borderRadius: 4, flexShrink: 0 } }),
            h('div', { style: { flex: 1, minWidth: 0 } },
              h('p', { style: { fontSize: 13, fontWeight: 600, margin: '0 0 3px', lineHeight: 1.3 } }, ytInfo.titulo),
              h('p', { style: { fontSize: 11, color: '#9ca3af', margin: '0 0 8px' } },
                ytInfo.canal + (ytInfo.fecha ? ' · ' + ytInfo.fecha : '')
              ),
              h('button', {
                onClick: fetchCaptions, disabled: loadCap,
                style: Object.assign({}, btnStyle('ghost'), { fontSize: 11 }),
              }, loadCap ? 'Obteniendo subtitulos…' : 'Obtener subtitulos automaticos')
            )
          )
        ),

        /* Transcripcion */
        h('div', { style: S.card },
          h('div', { style: S.cardTitle }, 'Transcripcion / contexto'),
          h('textarea', {
            placeholder: 'Pega la transcripcion, declaraciones o contexto adicional…',
            value: transc, rows: 6,
            onChange: function (e) { setTransc(e.target.value); },
            style: S.textarea,
          })
        ),

        /* Imagen */
        h('div', { style: S.card },
          h('div', { style: S.cardTitle }, 'Imagen de referencia (opcional)'),
          h('div', { style: { display: 'flex', gap: 12, alignItems: 'center' } },
            h('input', { ref: fileRef, type: 'file', accept: 'image/*', onChange: handleImage, style: { display: 'none' } }),
            h('button', {
              onClick: function () { if (fileRef.current) fileRef.current.click(); },
              style: btnStyle('ghost'),
            }, imgB64 ? 'Cambiar imagen' : 'Subir imagen'),
            imgB64 && h('img', { src: imgB64, alt: '', style: { height: 50, borderRadius: 4, border: '1px solid ' + BORDER } })
          )
        ),

        /* Config y generar */
        h('div', { style: S.card },
          h('div', { style: S.cardTitle }, 'Configurar nota'),
          h('label', { style: { fontSize: 12, color: '#6b7280', display: 'block', marginBottom: 4 } }, 'Pilar editorial'),
          h('select', {
            value: pilar, onChange: function (e) { setPilar(e.target.value); },
            style: Object.assign({}, S.input, { marginBottom: 12 }),
          },
            PILARES.map(function (p) { return h('option', { key: p.slug, value: p.slug }, p.nombre); })
          ),
          h('label', { style: { fontSize: 12, color: '#6b7280', display: 'block', marginBottom: 4 } }, 'Palabras clave / contexto adicional'),
          h('input', {
            type: 'text',
            placeholder: 'Ej: presupuesto 2026, intendente, barrio norte…',
            value: keywords, onChange: function (e) { setKw(e.target.value); },
            style: Object.assign({}, S.input, { marginBottom: 14 }),
          }),
          h('button', {
            onClick: generar, disabled: !canGenerate,
            style: Object.assign({}, btnStyle('primary'), { width: '100%', padding: '11px', fontSize: 13 }),
          }, loadGen ? 'Generando nota con IA…' : 'Generar borrador con IA')
        )
      ),

      /* Columna derecha: borrador */
      h('div', null,
        err && h('div', { style: {
          background: '#fee2e2', color: '#991b1b', padding: '10px 14px',
          borderRadius: 8, fontSize: 13, marginBottom: 12, lineHeight: 1.5,
        }}, err),

        !borrador && !loadGen && h('div', { style: Object.assign({}, S.card, { textAlign: 'center', padding: 48 }) },
          h('div', { style: { fontSize: 42, marginBottom: 10 } }, '✍️'),
          h('p', { style: { fontSize: 14, color: '#9ca3af', margin: 0 } }, 'El borrador aparece aquí'),
          h('p', { style: { fontSize: 12, color: '#d1d5db', marginTop: 6 } }, 'Completá el contexto y hacé clic en "Generar"')
        ),

        loadGen && h('div', { style: Object.assign({}, S.card, { textAlign: 'center', padding: 48 }) },
          h('div', { style: { fontSize: 14, color: '#6b7280' } }, 'Generando nota…'),
          h('div', { style: { fontSize: 11, color: '#9ca3af', marginTop: 6 } }, 'Puede tardar 15-30 segundos')
        ),

        borrador && h('div', { style: S.card },
          h('div', { style: { display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 12 } },
            h('div', { style: S.cardTitle }, 'Borrador generado'),
            h('div', { style: { display: 'flex', gap: 8, alignItems: 'center' } },
              savedUrl && h('a', {
                href: savedUrl, target: '_self',
                style: Object.assign({}, btnStyle('dark'), { textDecoration: 'none', fontSize: 11 }),
              }, 'Ver en WP'),
              h('button', {
                onClick: guardar, disabled: loadSave,
                style: Object.assign({}, btnStyle('primary'), { fontSize: 11 }),
              }, loadSave ? 'Guardando…' : 'Guardar en WordPress')
            )
          ),
          savedUrl && h('div', { style: {
            background: '#d1fae5', color: '#065f46', padding: '8px 12px',
            borderRadius: 6, fontSize: 12, marginBottom: 10,
          }}, '✓ Guardado como borrador en WordPress'),
          h('textarea', {
            value: borrador, onChange: function (e) { setBorrador(e.target.value); },
            style: Object.assign({}, S.textarea, {
              height: 520, fontFamily: "'JetBrains Mono', 'Courier New', monospace",
              fontSize: 12, lineHeight: 1.7,
            }),
          })
        )
      )
    );
  }

  /* ============================================================
     APP PRINCIPAL
  ============================================================ */
  var NAV = [
    { id: 'dashboard',    label: 'Dashboard',    icon: '◉' },
    { id: 'produccion',   label: 'Produccion',   icon: '✏️' },
    { id: 'inteligencia', label: 'Inteligencia', icon: '📊' },
    { id: 'seguimiento',  label: 'Seguimiento',  icon: '📋' },
    { id: 'asistente',    label: 'Asistente IA', icon: '🤖' },
  ];

  var TITLES = {
    dashboard:    'Dashboard',
    produccion:   'Produccion',
    inteligencia: 'Inteligencia editorial',
    seguimiento:  'Seguimiento de promesas',
    asistente:    'Asistente IA',
  };

  function App() {
    var tabState = useState('dashboard');
    var tab      = tabState[0];
    var setTab   = tabState[1];

    return h('div', { style: S.app },

      /* Sidebar */
      h('nav', { style: S.sidebar },
        h('div', { style: S.sidebarHeader },
          h('div', { style: S.sidebarLogo }, 'EL RUFINO'),
          h('div', { style: S.sidebarVersion }, 'Panel IA v' + erData.version)
        ),
        h('div', { style: S.sidebarNav },
          NAV.map(function (item) {
            return h('div', {
              key: item.id,
              style: navItemStyle(tab === item.id),
              onClick: function () { setTab(item.id); },
            },
              h('span', { style: { fontSize: 15, lineHeight: 1, width: 18, textAlign: 'center', flexShrink: 0 } }, item.icon),
              h('span', null, item.label)
            );
          })
        ),
        h('div', { style: S.sidebarFooter },
          h('a', {
            href: erData.siteUrl, target: '_self',
            style: { color: 'rgba(255,255,255,0.35)', fontSize: 11, textDecoration: 'none', display: 'block', marginBottom: 3 },
          }, '→ Ver sitio'),
          h('div', { style: { color: 'rgba(255,255,255,0.28)', fontSize: 10 } }, erData.userName)
        )
      ),

      /* Main */
      h('div', { style: S.main },
        h('div', { style: S.topbar },
          h('h1', { style: S.topbarTitle }, TITLES[tab]),
          h('a', {
            href: erData.adminUrl, target: '_self',
            style: { fontSize: 11, color: '#9ca3af', textDecoration: 'none' },
          }, 'WordPress /')
        ),
        h('div', { style: S.content },
          tab === 'dashboard'    && h(Dashboard),
          tab === 'produccion'   && h(Produccion),
          tab === 'inteligencia' && h(Inteligencia),
          tab === 'seguimiento'  && h(Seguimiento),
          tab === 'asistente'    && h(Asistente)
        )
      )
    );
  }

  /* Mount */
  ReactDOM.createRoot(root).render(h(App));

})();
