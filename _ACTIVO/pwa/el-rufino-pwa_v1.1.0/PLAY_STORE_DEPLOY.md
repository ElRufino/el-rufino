# El Rufino — Guía provisional para una futura publicación Android

## Estado

`NO HABILITADA PARA EJECUCIÓN`

Esta guía describe dependencias futuras. No acredita que los requisitos de
Google Play, Android o una herramienta de empaquetado continúen iguales. Antes
de usarla deberá contrastarse con documentación oficial vigente.

## Requisitos previos

- PWA v1.1.0 aprobada en pruebas locales y HTTPS.
- Responsable legal identificado.
- URL final bajo HTTPS.
- Política de privacidad revisada y publicada.
- Package ID aprobado y estable.
- Cuenta de publicación con responsable y recuperación definidos.
- Clave de firma bajo custodia documentada.
- Capturas reales y recursos de tienda aprobados.

## Digital Asset Links

La candidata conserva únicamente:

`docs/assetlinks.template.json`

El archivo público real deberá generarse con:

- Package ID definitivo;
- huella SHA-256 del certificado real.

Su ubicación pública será:

`https://elrufino.com.ar/.well-known/assetlinks.json`

No debe publicarse la plantilla ni guardarse una clave privada en el
repositorio.

## Secuencia futura

1. Revalidar requisitos oficiales.
2. Aprobar Package ID y responsables.
3. Generar el paquete firmado en entorno controlado.
4. Custodiar y respaldar la clave.
5. Generar Digital Asset Links desde el certificado.
6. Realizar prueba interna.
7. Verificar instalación y actualización.
8. Completar la ficha con información y capturas reales.
9. Revisar declaraciones de privacidad y seguridad del build final.
10. Solicitar autorización humana de publicación.

## Fuera de alcance

Este archivo no autoriza:

- crear cuentas;
- pagar cargos;
- descargar SDK;
- generar AAB o APK;
- generar claves;
- publicar en Play Console;
- desplegar en producción.
