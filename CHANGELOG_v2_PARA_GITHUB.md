# Changelog — El Rufino

Registro de cambios del plugin WordPress y del proyecto.

Formato basado en [Keep a Changelog](https://keepachangelog.com/)  
Versionado siguiendo [Semantic Versioning](https://semver.org/)

---

## [4.3.0] - 2026-04-08

### Added
- **Motor de Base de Datos:** Creación de tabla `er_promesas` para sistema de seguimiento de promesas públicas
- **Dashboard Fullscreen:** Modo Canvas OS para interfaz inmersiva
- **Estructura comentada:** Preparación de código para Pilar P05 (Poder y gestión)
- **Documentación:** Estructura de base de datos documentada en código

### Changed
- **Plugin Description:** Actualizada a "Dashboard inmersivo (Fullscreen) y Motor de Base de Datos para Promesas"
- **UI:** Optimización para modo pantalla completa

### Technical
- Tabla `wp_er_promesas` con campos: id, fecha, funcionario, promesa, estado, evidencia_url, proxima_revision
- Charset y collation configurados dinámicamente desde WordPress

---

## [4.2.0] - 2026-04-07

### Added
- **Interfaz Crema/Rojo:** Implementación completa de paleta de marca (Variante B)
- **Sidebar lateral:** Mejora de contraste y legibilidad
- **Modo Canvas:** Primera versión del modo pantalla completa

### Changed
- **Colores del dashboard:** Aplicación sistemática de #c0271b, #1a1a1a, #ffffff, #f5f0e8
- **Navegación:** Optimización visual

---

## [4.1.0] - 2026-04-06

### Added
- **Primera sincronización en GitHub**
- **Navegación negra:** Header negro con línea roja inferior (#c0271b)
- **Child Theme activo:** Tema hijo instalado y operativo
- **Menú único:** Layout de una sola fila implementado

### Fixed
- Corrección de layout de menú (de múltiples filas a una sola)

---

## [4.0.0] - 2026-04-05

### Added
- **Plugin base:** Primera versión funcional del plugin El Rufino
- **Panel de contexto:** Interfaz para visualizar contexto del proyecto
- **Agentes IA:** Sistema de 7 agentes operativos integrado

### Changed
- Migración desde versiones anteriores no documentadas

---

## Roadmap

### [4.4.0] - Estimado: Abril 2026
- [ ] Interfaz frontend para registro de promesas
- [ ] Sistema de alertas de vencimiento
- [ ] Integración completa con Pilar P05 editorial
- [ ] Panel de administración de promesas

### [5.0.0] - Estimado: Mayo 2026
- [ ] Lanzamiento público del medio
- [ ] Sistema de categorías y entradas base (6 pilares)
- [ ] Configuración SEO completa (Schema NewsMediaOrganization)
- [ ] Canal WhatsApp broadcast operativo
- [ ] Primeras 10-15 entradas publicadas

---

## Notas de versión

### Convención de versionado

**MAJOR.MINOR.PATCH** (ej: 4.3.0)

- **MAJOR:** Cambios que rompen compatibilidad o rediseños completos
- **MINOR:** Nuevas funcionalidades compatibles hacia atrás
- **PATCH:** Correcciones de bugs y mejoras menores

### Categorías de cambios

- **Added:** Nuevas funcionalidades
- **Changed:** Cambios en funcionalidades existentes
- **Deprecated:** Funcionalidades que serán removidas
- **Removed:** Funcionalidades removidas
- **Fixed:** Corrección de bugs
- **Security:** Correcciones de seguridad
- **Technical:** Detalles técnicos para desarrolladores

---

**Última actualización:** 08/04/2026  
**Versión actual del plugin:** 4.3.0
