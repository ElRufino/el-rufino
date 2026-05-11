/* El Rufino — Panel v8.1 JS */
(function ($) {
  'use strict';

  const root = document.getElementById('er-root');
  if (!root) return;

  const { ajaxUrl, nonce, isAdmin, wizardDone, version, categorias, pilares, keyOk, keyMasked, currentUser } = erData;

  const PILARES_DEF = [
    { code: 'P01', nombre: 'Rufino real',          color: '#c0271b', slug: 'rufino-real' },
    { code: 'P02', nombre: 'El campo habla',        color: '#4a7c59', slug: 'el-campo-habla' },
    { code: 'P03', nombre: 'Barrio a barrio',       color: '#2d5f8a', slug: 'barrio-a-barrio' },
    { code: 'P04', nombre: 'Generación Rufino',     color: '#7b4fa6', slug: 'generacion-rufino' },
    { code: 'P05', nombre: 'Seguimiento promesas',  color: '#1a1a1a', slug: 'seguimiento-promesas' },
    { code: 'P06', nombre: 'Rufino en datos',       color: '#c8600a', slug: 'contexto-datos' },
  ];

  // ESC → volver a WordPress
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') window.location.href = '/wp-admin/';
  });

  // ── Router ──────────────────────────────────────────────
  const view = root.dataset.view;

  if (view === 'wizard' || !wizardDone) {
    renderWizard();
  } else {
    isAdmin ? renderAdminHome() : renderEditorView();
  }

  // ══════════════════════════════════════════════════════════
  // WIZARD
  // ══════════════════════════════════════════════════════════
  function renderWizard() {
    let step = 1;
    const data = { claim: 'Lo que pasa y lo que significa', color: '#c0271b', api_key: '', pilares: ['P01','P02','P03','P04','P05','P06'] };

    function renderStep() {
      const pct = (step / 3) * 100;

      let stepContent = '';

      if (step === 1) {
        stepContent = `
          <div class="er-wizard-step-label">Paso 1 de 3</div>
          <h2 class="er-wizard-title">Identidad del medio</h2>
          <p class="er-wizard-desc">Confirmá el claim y color principal de El Rufino.</p>
          <div class="er-form-group">
            <label class="er-label">Claim del medio</label>
            <input type="text" class="er-input" id="w-claim" value="${escHtml(data.claim)}">
          </div>
          <div class="er-form-group">
            <label class="er-label">Color principal</label>
            <div class="er-input-group">
              <input type="color" class="er-input" id="w-color" value="${data.color}" style="width:60px;padding:4px">
              <input type="text" class="er-input" id="w-color-txt" value="${data.color}" placeholder="#c0271b">
            </div>
          </div>
        `;
      } else if (step === 2) {
        stepContent = `
          <div class="er-wizard-step-label">Paso 2 de 3</div>
          <h2 class="er-wizard-title">API Anthropic</h2>
          <p class="er-wizard-desc">Pegá tu API Key de Anthropic. Se guarda cifrada en la base de datos y nunca se muestra completa.</p>
          <div class="er-form-group">
            <label class="er-label">API Key <span class="er-label-hint">empieza con sk-ant-</span></label>
            <input type="password" class="er-input" id="w-api" placeholder="sk-ant-api03-..." value="${escHtml(data.api_key)}" autocomplete="off">
          </div>
          ${keyOk ? `<div class="er-alert er-alert-ok visible">✓ Ya tenés una key configurada (${escHtml(keyMasked)}). Podés dejar este campo vacío para mantenerla.</div>` : '<div class="er-alert er-alert-warn visible">Sin key configurada — el módulo de IA no va a funcionar.</div>'}
        `;
      } else if (step === 3) {
        const pCheckboxes = PILARES_DEF.map(p => `
          <label class="er-pilar-check ${data.pilares.includes(p.code) ? 'active' : ''}" data-code="${p.code}">
            <input type="checkbox" value="${p.code}" ${data.pilares.includes(p.code) ? 'checked' : ''}>
            <span class="er-pilar-dot" style="background:${p.color}"></span>
            <span class="er-pilar-code">${p.code}</span>
            <span class="er-pilar-nombre">${p.nombre}</span>
          </label>
        `).join('');
        stepContent = `
          <div class="er-wizard-step-label">Paso 3 de 3</div>
          <h2 class="er-wizard-title">Pilares editoriales</h2>
          <p class="er-wizard-desc">Las categorías P01-P06 ya están creadas en WordPress. Seleccioná cuáles activar en el módulo de Producción.</p>
          <div class="er-pilares-grid">${pCheckboxes}</div>
        `;
      }

      root.innerHTML = `
        <div class="er-header">
          <div class="er-header-marca">
            <div class="er-header-logo"><span>R</span></div>
            <div>
              <div class="er-header-nombre">El Rufino</div>
              <div class="er-header-sub">Configuración inicial</div>
            </div>
          </div>
          <div class="er-header-actions">
            <a href="/wp-admin/" class="er-btn er-btn-outline er-btn-sm">← Volver a WordPress</a>
          </div>
        </div>
        <div class="er-wizard-wrap">
          <div class="er-wizard-card">
            <div class="er-wizard-progress">
              <div class="er-wizard-progress-bar" style="width:${pct}%"></div>
            </div>
            <div class="er-wizard-body">
              ${stepContent}
              <div class="er-wizard-nav">
                ${step > 1
                  ? `<button class="er-btn er-btn-ghost er-btn-sm" id="w-prev">← Anterior</button>`
                  : `<span></span>`
                }
                ${step < 3
                  ? `<button class="er-btn er-btn-rojo" id="w-next">Siguiente →</button>`
                  : `<button class="er-btn er-btn-rojo" id="w-finish">✓ Finalizar configuración</button>`
                }
              </div>
            </div>
          </div>
        </div>
      `;

      bindWizardEvents();
    }

    function bindWizardEvents() {
      $('#w-color').on('input', function() { $('#w-color-txt').val(this.value); });
      $('#w-color-txt').on('input', function() { if (/^#[0-9a-f]{6}$/i.test(this.value)) $('#w-color').val(this.value); });

      $('.er-pilar-check').on('click', function() {
        const code = $(this).data('code');
        $(this).toggleClass('active');
        const cb = $(this).find('input');
        cb.prop('checked', !cb.prop('checked'));
        if (cb.prop('checked')) { if (!data.pilares.includes(code)) data.pilares.push(code); }
        else { data.pilares = data.pilares.filter(p => p !== code); }
      });

      $('#w-prev').on('click', () => { collectStep(); step--; renderStep(); });

      $('#w-next').on('click', () => {
        if (!collectStep()) return;
        step++;
        renderStep();
      });

      $('#w-finish').on('click', () => {
        collectStep();
        const $btn = $('#w-finish');
        $btn.text('Guardando...').prop('disabled', true);

        $.post(ajaxUrl, {
          action: 'er_save_wizard',
          nonce,
          claim: data.claim,
          color: data.color,
          api_key: data.api_key,
          pilares: data.pilares,
        }, res => {
          if (res.success) {
            window.location.href = res.data.redirect;
          } else {
            $btn.text('✓ Finalizar configuración').prop('disabled', false);
            alert('Error al guardar. Intentá de nuevo.');
          }
        });
      });
    }

    function collectStep() {
      if (step === 1) {
        const claim = $('#w-claim').val().trim();
        if (!claim) { alert('El claim no puede estar vacío'); return false; }
        data.claim = claim;
        data.color = $('#w-color').val();
      } else if (step === 2) {
        const k = $('#w-api').val().trim();
        if (k) data.api_key = k;
      }
      return true;
    }

    renderStep();
  }

  // ══════════════════════════════════════════════════════════
  // ADMIN HOME
  // ══════════════════════════════════════════════════════════
  function renderAdminHome() {
    root.innerHTML = `
      ${headerHtml('Sistema Operativo v8.1')}
      <div class="er-modules-wrap">
        <div class="er-modules-welcome">
          <h2>Hola, ${escHtml(currentUser)}</h2>
          <p>Panel IA v${version} · ¿Qué querés hacer hoy?</p>
        </div>
        <div class="er-modules-grid">
          <div class="er-module-card produccion" data-module="produccion">
            <span class="er-module-icon">✍️</span>
            <h3>Producción</h3>
            <p>Nueva nota con YouTube, transcripción e IA integrada</p>
          </div>
          <div class="er-module-card seguimiento" data-module="seguimiento">
            <span class="er-module-icon">📋</span>
            <h3>Seguimiento</h3>
            <p>Auditar promesas, semáforo de estado, exportar CSV</p>
          </div>
          <div class="er-module-card inteligencia" data-module="inteligencia">
            <span class="er-module-icon">🔍</span>
            <h3>Inteligencia</h3>
            <p>Contexto local y búsqueda en archivo</p>
          </div>
          <div class="er-module-card dashboard" data-module="dashboard">
            <span class="er-module-icon">⚙️</span>
            <h3>Dashboard</h3>
            <p>API Key Anthropic y configuración</p>
          </div>
        </div>
      </div>
    `;

    $('.er-module-card').on('click', function () {
      loadModule($(this).data('module'));
    });
  }

  // ══════════════════════════════════════════════════════════
  // EDITOR VIEW
  // ══════════════════════════════════════════════════════════
  function renderEditorView() {
    root.innerHTML = `
      ${headerHtml('')}
      <div class="er-editor-view">
        <div class="er-editor-btns">
          <button class="er-editor-btn" data-module="produccion">Nueva<br>Nota</button>
          <button class="er-editor-btn" data-module="borradores">Mis<br>Borradores</button>
          <button class="er-editor-btn" data-module="seguimiento">Promesas</button>
        </div>
        <a href="/wp-admin/admin.php?page=el-rufino" class="er-editor-link">Ver panel completo →</a>
      </div>
    `;
    $('.er-editor-btn').on('click', function () { loadModule($(this).data('module')); });
  }

  // ══════════════════════════════════════════════════════════
  // MÓDULO: PRODUCCIÓN
  // ══════════════════════════════════════════════════════════
  function loadProduccion() {
    const catOptions = categorias.map(c =>
      `<option value="${c.id}">${escHtml(c.name)}</option>`
    ).join('');

    const pilarOptions = PILARES_DEF.map(p =>
      `<option value="${p.code}">${p.code} — ${p.nombre}</option>`
    ).join('');

    root.innerHTML = `
      ${headerHtml('Producción', true)}
      <div class="er-produccion-layout">
        <div class="er-produccion-main">
          <h2 class="er-section-title">Nueva nota</h2>

          <div class="er-form-group">
            <label class="er-label">Título <span class="er-label-hint">regla de 2 capas: hecho + significado</span></label>
            <input type="text" class="er-input" id="p-titulo" placeholder="Ej: El municipio prometió pavimentar tres calles del sur">
          </div>

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
            <div class="er-form-group">
              <label class="er-label">Pilar editorial</label>
              <select class="er-select" id="p-pilar">${pilarOptions}</select>
            </div>
            <div class="er-form-group">
              <label class="er-label">Categoría WordPress</label>
              <select class="er-select" id="p-cat">${catOptions}</select>
            </div>
          </div>

          <div class="er-form-group">
            <label class="er-label">YouTube <span class="er-label-hint">opcional</span></label>
            <div class="er-input-group">
              <input type="text" class="er-input" id="p-yt" placeholder="https://youtu.be/...">
              <button class="er-btn er-btn-sm" id="p-yt-cargar">Cargar</button>
            </div>
            <div id="p-yt-preview" style="display:none"></div>
          </div>

          <div class="er-form-group">
            <label class="er-label">Transcripción / Material <span class="er-label-hint">lo que dijo el entrevistado, datos clave</span></label>
            <div style="position:relative">
              <textarea class="er-textarea" id="p-transcripcion" rows="5" placeholder="Pegá citas textuales, puntos clave, datos que menciona el video o la fuente..."></textarea>
              <button class="er-btn er-btn-sm" id="p-auto-trans" style="position:absolute;bottom:8px;right:8px;font-size:11px">Auto-transcribir</button>
            </div>
          </div>

          <div class="er-form-group">
            <label class="er-label">Tags <span class="er-label-hint">separados por comas</span></label>
            <input type="text" class="er-input" id="p-tags" placeholder="municipio, obras, barrio norte, promesas">
          </div>

          <div style="display:flex;gap:12px;align-items:center">
            <button class="er-btn er-btn-rojo" id="p-generar" style="flex:1;justify-content:center;padding:13px">⚡ Generar con IA</button>
          </div>

          <div class="er-loading" id="p-loading">
            <div class="er-spinner"></div>
            <span>Generando nota con Anthropic...</span>
          </div>

          <div class="er-alert er-alert-err" id="p-error"></div>

          <div class="er-output" id="p-output"></div>

          <div class="er-output-actions" id="p-output-actions" style="display:none">
            <button class="er-btn er-btn-rojo" id="p-guardar">💾 Guardar como borrador</button>
            <button class="er-btn er-btn-ghost er-btn-sm" id="p-copiar">Copiar texto</button>
            <button class="er-btn er-btn-ghost er-btn-sm" id="p-limpiar">Nueva nota</button>
          </div>

          <div class="er-alert" id="p-guardado"></div>
        </div>

        <div class="er-produccion-sidebar">
          <div class="er-sidebar-section">
            <div class="er-sidebar-title">🔍 Inteligencia</div>
            <div class="er-form-group">
              <label class="er-label" style="font-size:11px">Contexto local</label>
              <div class="er-input-group">
                <input type="text" class="er-input" id="s-buscar" placeholder="Buscar...">
                <button class="er-btn er-btn-sm" id="s-buscar-btn">→</button>
              </div>
              <div id="s-resultado" style="margin-top:8px;font-size:12px;color:var(--gris)"></div>
            </div>
          </div>

          <div class="er-sidebar-section">
            <div class="er-sidebar-title">📌 Registrar promesa</div>
            <div class="er-promesa-form">
              <input type="text" class="er-input" id="s-prom-texto" placeholder="Qué prometió" style="font-size:12px">
              <input type="text" class="er-input" id="s-prom-fuente" placeholder="Quién lo prometió" style="font-size:12px">
              <select class="er-select" id="s-prom-pilar" style="font-size:12px">
                ${PILARES_DEF.map(p => `<option value="${p.code}">${p.code}</option>`).join('')}
              </select>
              <button class="er-btn er-btn-sm" id="s-prom-guardar" style="width:100%;justify-content:center">Registrar</button>
              <div class="er-alert" id="s-prom-ok"></div>
            </div>
          </div>

          <div class="er-sidebar-section">
            <div class="er-sidebar-title">⏱ Promesas recientes</div>
            <div id="s-promesas-lista"><div style="font-size:12px;color:var(--gris)">Cargando...</div></div>
          </div>
        </div>
      </div>
    `;

    bindProduccion();
    loadPromesamsMini();
  }

  function bindProduccion() {
    // Cargar YouTube
    $('#p-yt-cargar').on('click', function () {
      const url = $('#p-yt').val().trim();
      if (!url) return;
      $(this).text('...').prop('disabled', true);
      $.post(ajaxUrl, { action: 'er_yt_info', nonce, url }, res => {
        $(this).text('Cargar').prop('disabled', false);
        if (res.success) {
          const d = res.data;
          if (!$('#p-titulo').val()) $('#p-titulo').val(d.titulo);
          $('#p-yt-preview').show().html(`
            <div class="er-yt-preview"><iframe src="${d.embed_url}" allowfullscreen></iframe></div>
            <div class="er-yt-info">
              <div class="er-yt-info-titulo">${escHtml(d.titulo)}</div>
              <div class="er-yt-info-canal">${escHtml(d.canal)}</div>
            </div>
          `);
        } else {
          showAlert('#p-error', 'No se pudo cargar el video: ' + (res.data?.msg || 'Error'), 'err');
        }
      });
    });

    // Auto-transcribir
    $('#p-auto-trans').on('click', function () {
      const url = $('#p-yt').val().trim();
      const id = url.match(/(?:youtu\.be\/|v=)([a-zA-Z0-9_-]{11})/)?.[1];
      if (!id) { showAlert('#p-error', 'Cargá un video de YouTube primero', 'err'); return; }
      $(this).text('...').prop('disabled', true);
      $.post(ajaxUrl, { action: 'er_yt_captions', nonce, video_id: id }, res => {
        $(this).text('Auto-transcribir').prop('disabled', false);
        if (res.success) {
          $('#p-transcripcion').val(res.data.transcripcion);
        } else {
          showAlert('#p-error', res.data?.msg || 'Sin subtítulos disponibles', 'warn');
        }
      });
    });

    // Generar con IA
    $('#p-generar').on('click', function () {
      const titulo = $('#p-titulo').val().trim();
      if (!titulo) { showAlert('#p-error', 'El título es obligatorio', 'err'); return; }

      $('#p-output').removeClass('visible').text('');
      $('#p-output-actions').hide();
      $('#p-error').removeClass('visible');
      $('#p-loading').addClass('visible');
      $(this).prop('disabled', true);

      $.post(ajaxUrl, {
        action:        'er_asistente_generar',
        nonce,
        titulo,
        pilar:         $('#p-pilar').val(),
        youtube:       $('#p-yt').val(),
        transcripcion: $('#p-transcripcion').val(),
        contexto:      $('#s-buscar').val(),
      }, res => {
        $('#p-loading').removeClass('visible');
        $('#p-generar').prop('disabled', false);
        if (res.success) {
          $('#p-output').addClass('visible').text(res.data.content);
          $('#p-output-actions').show();
        } else {
          showAlert('#p-error', res.data?.msg || 'Error al generar', 'err');
        }
      });
    });

    // Guardar borrador
    $('#p-guardar').on('click', function () {
      $(this).text('Guardando...').prop('disabled', true);
      $.post(ajaxUrl, {
        action:    'er_guardar_borrador',
        nonce,
        titulo:    $('#p-titulo').val(),
        cuerpo:    $('#p-output').text(),
        categoria: $('#p-cat').val(),
        tags:      $('#p-tags').val(),
      }, res => {
        $(this).text('💾 Guardar como borrador').prop('disabled', false);
        if (res.success) {
          showAlert('#p-guardado', `✓ Borrador guardado — <a href="${res.data.edit_url}" target="_blank">Editar en WordPress</a>`, 'ok');
        } else {
          showAlert('#p-guardado', res.data?.msg || 'Error al guardar', 'err');
        }
      });
    });

    // Copiar
    $('#p-copiar').on('click', function () {
      const text = $('#p-output').text();
      navigator.clipboard.writeText(text).then(() => {
        $(this).text('¡Copiado!');
        setTimeout(() => $(this).text('Copiar texto'), 2000);
      });
    });

    // Limpiar
    $('#p-limpiar').on('click', () => { loadProduccion(); });

    // Registrar promesa
    $('#s-prom-guardar').on('click', function () {
      const texto = $('#s-prom-texto').val().trim();
      if (!texto) { showAlert('#s-prom-ok', 'El texto es obligatorio', 'err'); return; }
      $(this).text('...').prop('disabled', true);
      $.post(ajaxUrl, {
        action: 'er_save_promesa',
        nonce,
        texto,
        fuente: $('#s-prom-fuente').val(),
        pilar:  $('#s-prom-pilar').val(),
        fecha:  new Date().toISOString().split('T')[0],
      }, res => {
        $(this).text('Registrar').prop('disabled', false);
        if (res.success) {
          showAlert('#s-prom-ok', '✓ Promesa registrada', 'ok');
          $('#s-prom-texto').val('');
          $('#s-prom-fuente').val('');
          loadPromesamsMini();
        }
      });
    });
  }

  function loadPromesamsMini() {
    $.post(ajaxUrl, { action: 'er_get_promesas', nonce }, res => {
      if (!res.success) return;
      const list = $('#s-promesas-lista');
      const promesas = (res.data || []).slice(-5).reverse();
      if (!promesas.length) {
        list.html('<div style="font-size:12px;color:var(--gris)">Sin promesas registradas aún.</div>');
        return;
      }
      list.html('<div class="er-promesas-mini-list">' +
        promesas.map(p => `<div class="er-promesa-mini"><strong>${p.pilar || ''}</strong> ${escHtml(p.texto.substring(0, 60))}${p.texto.length > 60 ? '...' : ''}</div>`).join('') +
      '</div>');
    });
  }

  // ══════════════════════════════════════════════════════════
  // MÓDULO: SEGUIMIENTO
  // ══════════════════════════════════════════════════════════
  function loadSeguimiento() {
    root.innerHTML = `
      ${headerHtml('Seguimiento', true)}
      <div class="er-seguimiento-wrap">
        <div class="er-seguimiento-header">
          <h2 class="er-section-title" style="margin:0">Promesas políticas</h2>
          <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
            <div class="er-filters" id="s-filters">
              <button class="er-filter-btn active" data-estado="todos">Todas</button>
              <button class="er-filter-btn" data-estado="pendiente">Pendientes</button>
              <button class="er-filter-btn" data-estado="cumplida">Cumplidas</button>
              <button class="er-filter-btn" data-estado="incumplida">Incumplidas</button>
              <button class="er-filter-btn" data-estado="en-proceso">En proceso</button>
            </div>
            <button class="er-btn er-btn-sm" id="s-export">⬇ CSV</button>
          </div>
        </div>
        <div id="s-tabla-wrap"><div class="er-spinner" style="margin:40px auto;display:block"></div></div>
      </div>
    `;

    let allPromesas = [];
    let filtroActivo = 'todos';

    $.post(ajaxUrl, { action: 'er_get_promesas', nonce }, res => {
      allPromesas = res.data || [];
      renderTabla();
    });

    function renderTabla() {
      const filtradas = filtroActivo === 'todos'
        ? allPromesas
        : allPromesas.filter(p => p.estado === filtroActivo);

      if (!filtradas.length) {
        $('#s-tabla-wrap').html(`
          <div class="er-empty-state">
            <div class="er-empty-state-icon">📋</div>
            <h3>${filtroActivo === 'todos' ? 'Sin promesas registradas' : 'Sin promesas en este estado'}</h3>
            <p>Las promesas se registran desde el módulo de Producción al redactar notas.</p>
          </div>
        `);
        return;
      }

      const rows = filtradas.map(p => `
        <tr>
          <td style="max-width:300px;font-weight:600">${escHtml(p.texto)}</td>
          <td>${escHtml(p.fuente || '—')}</td>
          <td style="white-space:nowrap">${escHtml(p.fecha || '—')}</td>
          <td><span style="font-size:11px;font-weight:700;color:var(--gris)">${escHtml(p.pilar || '—')}</span></td>
          <td><span class="er-badge er-badge-${p.estado || 'pendiente'}">${badgeLabel(p.estado)}</span></td>
          <td>
            <select class="er-estado-select" data-id="${p.id}">
              <option value="pendiente" ${p.estado==='pendiente'?'selected':''}>Pendiente</option>
              <option value="cumplida" ${p.estado==='cumplida'?'selected':''}>Cumplida</option>
              <option value="incumplida" ${p.estado==='incumplida'?'selected':''}>Incumplida</option>
              <option value="en-proceso" ${p.estado==='en-proceso'?'selected':''}>En proceso</option>
            </select>
          </td>
          <td>
            <button class="er-btn er-btn-ghost er-btn-sm s-del" data-id="${p.id}" title="Eliminar">✕</button>
          </td>
        </tr>
      `).join('');

      $('#s-tabla-wrap').html(`
        <div class="er-table-wrap">
          <table class="er-table">
            <thead><tr>
              <th>Promesa</th><th>Fuente</th><th>Fecha</th><th>Pilar</th><th>Estado</th><th>Cambiar</th><th></th>
            </tr></thead>
            <tbody>${rows}</tbody>
          </table>
        </div>
        <p style="margin-top:12px;font-size:12px;color:var(--gris)">${filtradas.length} promesa${filtradas.length !== 1 ? 's' : ''}</p>
      `);

      // Cambiar estado
      $('.er-estado-select').on('change', function () {
        const id = $(this).data('id');
        const estado = $(this).val();
        $.post(ajaxUrl, { action: 'er_update_promesa', nonce, id, estado }, res => {
          if (res.success) {
            const idx = allPromesas.findIndex(p => p.id === id);
            if (idx !== -1) allPromesas[idx].estado = estado;
            renderTabla();
          }
        });
      });

      // Eliminar
      $('.s-del').on('click', function () {
        if (!confirm('¿Eliminar esta promesa?')) return;
        const id = $(this).data('id');
        $.post(ajaxUrl, { action: 'er_delete_promesa', nonce, id }, res => {
          if (res.success) {
            allPromesas = allPromesas.filter(p => p.id !== id);
            renderTabla();
          }
        });
      });
    }

    // Filtros
    $('#s-filters').on('click', '.er-filter-btn', function () {
      $('.er-filter-btn').removeClass('active');
      $(this).addClass('active');
      filtroActivo = $(this).data('estado');
      renderTabla();
    });

    // Exportar CSV
    $('#s-export').on('click', () => {
      let csv = 'Promesa,Fuente,Fecha,Pilar,Estado\n';
      allPromesas.forEach(p => {
        csv += `"${(p.texto||'').replace(/"/g,'""')}","${(p.fuente||'').replace(/"/g,'""')}","${p.fecha||''}","${p.pilar||''}","${p.estado||''}"\n`;
      });
      const blob = new Blob(['\ufeff' + csv], { type: 'text/csv;charset=utf-8;' });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url; a.download = 'promesas-el-rufino.csv'; a.click();
      URL.revokeObjectURL(url);
    });
  }

  // ══════════════════════════════════════════════════════════
  // MÓDULO: BORRADORES
  // ══════════════════════════════════════════════════════════
  function loadBorradores() {
    root.innerHTML = `
      ${headerHtml('Mis borradores', true)}
      <div class="er-seguimiento-wrap">
        <div class="er-seguimiento-header">
          <h2 class="er-section-title" style="margin:0">Borradores recientes</h2>
          <button class="er-btn er-btn-rojo er-btn-sm" id="b-nueva">+ Nueva nota</button>
        </div>
        <div id="b-lista"><div class="er-spinner" style="margin:40px auto;display:block"></div></div>
      </div>
    `;

    $('#b-nueva').on('click', () => loadProduccion());

    $.post(ajaxUrl, { action: 'er_get_borradores', nonce }, res => {
      const borradores = res.data || [];
      if (!borradores.length) {
        $('#b-lista').html(`
          <div class="er-empty-state">
            <div class="er-empty-state-icon">📝</div>
            <h3>Sin borradores aún</h3>
            <p>Las notas guardadas desde Producción van a aparecer acá.</p>
          </div>
        `);
        return;
      }
      const rows = borradores.map(b => `
        <tr>
          <td style="font-weight:600">${escHtml(b.titulo)}</td>
          <td>${escHtml(b.cat || '—')}</td>
          <td style="white-space:nowrap;font-size:12px;color:var(--gris)">${escHtml(b.fecha)}</td>
          <td><a href="${b.edit_url}" target="_blank" class="er-btn er-btn-ghost er-btn-sm">Editar →</a></td>
        </tr>
      `).join('');
      $('#b-lista').html(`
        <div class="er-table-wrap">
          <table class="er-table">
            <thead><tr><th>Título</th><th>Categoría</th><th>Modificado</th><th></th></tr></thead>
            <tbody>${rows}</tbody>
          </table>
        </div>
      `);
    });
  }

  // ══════════════════════════════════════════════════════════
  // MÓDULO: INTELIGENCIA
  // ══════════════════════════════════════════════════════════
  function loadInteligencia() {
    root.innerHTML = `
      ${headerHtml('Inteligencia', true)}
      <div style="padding:32px;max-width:700px">
        <p style="color:var(--gris);margin-bottom:24px;font-size:14px">
          La Inteligencia está integrada como sidebar en el módulo de Producción. Desde ahí podés buscar contexto local y registrar promesas mientras redactás.
        </p>
        <button class="er-btn er-btn-rojo" onclick="document.querySelector('[data-module=produccion]')?.click()">
          Ir a Producción →
        </button>
        <div style="margin-top:32px;background:var(--blanco);border:1px solid var(--borde);border-radius:var(--radius);padding:24px">
          <h3 style="font-family:var(--font-titulo);font-size:18px;margin-bottom:12px">Contexto local</h3>
          <p style="font-size:13px;color:var(--gris);margin-bottom:16px">Buscá datos sobre Rufino para contextualizar tu nota.</p>
          <div class="er-input-group" style="margin-bottom:12px">
            <input type="text" class="er-input" id="i-buscar" placeholder="Ej: presupuesto municipal 2026, censo, escuelas...">
            <button class="er-btn" id="i-buscar-btn">Buscar</button>
          </div>
          <div id="i-resultado" style="font-size:13px;color:var(--gris);min-height:40px"></div>
        </div>
      </div>
    `;

    $('#i-buscar-btn').on('click', () => {
      const q = $('#i-buscar').val().trim();
      if (!q) return;
      $('#i-resultado').html('<div class="er-spinner" style="margin:0"></div>');
      // Búsqueda básica en posts publicados via WP
      $.post(ajaxUrl, {
        action: 'er_buscar_contexto',
        nonce,
        query: q,
      }, res => {
        if (res.success && res.data.length) {
          $('#i-resultado').html(
            res.data.map(r => `<div style="padding:8px 0;border-bottom:1px solid var(--borde)">
              <a href="${r.url}" target="_blank" style="font-weight:600;color:var(--negro)">${escHtml(r.titulo)}</a>
              <div style="color:var(--gris);font-size:12px">${escHtml(r.excerpt)}</div>
            </div>`).join('')
          );
        } else {
          $('#i-resultado').html('<span style="color:var(--gris)">Sin resultados para esa búsqueda.</span>');
        }
      });
    });

    $('#i-buscar').on('keydown', e => { if (e.key === 'Enter') $('#i-buscar-btn').click(); });
  }

  // ══════════════════════════════════════════════════════════
  // MÓDULO: DASHBOARD
  // ══════════════════════════════════════════════════════════
  function loadDashboard() {
    root.innerHTML = `
      ${headerHtml('Dashboard', true)}
      <div class="er-dashboard-wrap">
        <div class="er-dashboard-section">
          <h3>API Key Anthropic <span class="er-version-badge">v${version}</span></h3>
          <div class="er-form-group">
            <label class="er-label">Clave actual</label>
            <div class="er-key-status" id="d-key-status">
              ${keyOk
                ? `<span class="er-key-ok">✓ Configurada — ${escHtml(keyMasked)}</span>`
                : `<span class="er-key-no">✗ Sin configurar</span>`
              }
            </div>
          </div>
          <div class="er-form-group">
            <label class="er-label">Nueva clave <span class="er-label-hint">dejá vacío para no cambiarla</span></label>
            <div class="er-input-group">
              <input type="password" class="er-input" id="d-key" placeholder="sk-ant-api03-..." autocomplete="off">
              <button class="er-btn er-btn-rojo" id="d-save">Guardar</button>
            </div>
          </div>
          <div class="er-alert" id="d-alert"></div>
        </div>

        <div class="er-dashboard-section">
          <h3>Configuración del medio</h3>
          <a href="/wp-admin/admin.php?page=er-setup" class="er-btn er-btn-ghost er-btn-sm">Abrir Wizard de configuración →</a>
        </div>

        <div class="er-dashboard-section">
          <h3>Estado del plugin</h3>
          <table style="width:100%;font-size:13px">
            <tr><td style="padding:6px 0;color:var(--gris)">Versión</td><td><strong>v${version}</strong></td></tr>
            <tr><td style="padding:6px 0;color:var(--gris)">Modelo IA</td><td><strong>claude-sonnet-4-6</strong></td></tr>
            <tr><td style="padding:6px 0;color:var(--gris)">API Key</td><td><strong>${keyOk ? '✓ Activa' : '✗ Sin configurar'}</strong></td></tr>
            <tr><td style="padding:6px 0;color:var(--gris)">Wizard</td><td><strong>${erData.wizardDone ? '✓ Completado' : '⚠ Pendiente'}</strong></td></tr>
          </table>
        </div>
      </div>
    `;

    $('#d-save').on('click', function () {
      const key = $('#d-key').val().trim();
      if (!key) { showAlert('#d-alert', 'Ingresá una API Key para guardar', 'warn'); return; }
      if (!key.startsWith('sk-ant')) { showAlert('#d-alert', 'La key debe empezar con sk-ant-', 'err'); return; }
      $(this).text('Guardando...').prop('disabled', true);
      $.post(ajaxUrl, { action: 'er_save_key', nonce, key }, res => {
        $(this).text('Guardar').prop('disabled', false);
        if (res.success) {
          showAlert('#d-alert', '✓ API Key guardada correctamente', 'ok');
          $('#d-key-status').html(`<span class="er-key-ok">✓ Configurada — ${escHtml(res.data.masked)}</span>`);
          $('#d-key').val('');
        } else {
          showAlert('#d-alert', 'Error al guardar', 'err');
        }
      });
    });
  }

  // ══════════════════════════════════════════════════════════
  // ROUTER
  // ══════════════════════════════════════════════════════════
  window.loadModule = function (m) {
    if (m === 'produccion')   loadProduccion();
    else if (m === 'seguimiento') loadSeguimiento();
    else if (m === 'borradores')  loadBorradores();
    else if (m === 'inteligencia') loadInteligencia();
    else if (m === 'dashboard')   loadDashboard();
  };

  // ══════════════════════════════════════════════════════════
  // HELPERS
  // ══════════════════════════════════════════════════════════
  function headerHtml(subtitulo, showBack) {
    return `
      <div class="er-header">
        <div class="er-header-marca">
          <div class="er-header-logo"><span>R</span></div>
          <div>
            <div class="er-header-nombre">El Rufino</div>
            ${subtitulo ? `<div class="er-header-sub">${subtitulo}</div>` : ''}
          </div>
        </div>
        <div class="er-header-actions">
          ${showBack && isAdmin ? `<button class="er-btn er-btn-outline er-btn-sm" onclick="loadModule('home')">← Panel</button>` : ''}
          ${showBack && !isAdmin ? `<button class="er-btn er-btn-outline er-btn-sm" onclick="renderEditorView ? window.location.reload() : null">← Volver</button>` : ''}
          <a href="/wp-admin/" class="er-btn er-btn-outline er-btn-sm">WordPress →</a>
        </div>
      </div>
    `;
  }

  function showAlert(selector, msg, type) {
    const $el = $(selector);
    $el.removeClass('er-alert-ok er-alert-err er-alert-warn')
       .addClass(`er-alert-${type} visible`)
       .html(msg);
    setTimeout(() => $el.removeClass('visible'), 6000);
  }

  function escHtml(str) {
    if (!str) return '';
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function badgeLabel(estado) {
    const map = { pendiente: 'Pendiente', cumplida: 'Cumplida', incumplida: 'Incumplida', 'en-proceso': 'En proceso' };
    return map[estado] || estado || 'Pendiente';
  }

  // Patch: loadModule('home') para el botón ← Panel
  window.loadModule = function (m) {
    if (m === 'home')           isAdmin ? renderAdminHome() : renderEditorView();
    else if (m === 'produccion')   loadProduccion();
    else if (m === 'seguimiento')  loadSeguimiento();
    else if (m === 'borradores')   loadBorradores();
    else if (m === 'inteligencia') loadInteligencia();
    else if (m === 'dashboard')    loadDashboard();
  };

})(jQuery);
