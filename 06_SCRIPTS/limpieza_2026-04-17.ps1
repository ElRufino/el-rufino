# Script de limpieza — El Rufino Panel IA v4.21
# Generado: 17/4/2026, 06:10:12
# Filtro: 90 dias | all

$bkp = "$env:USERPROFILE\Downloads\_BACKUP_$(Get-Date -Format 'yyyyMMdd_HHmmss')"
New-Item -ItemType Directory -Path $bkp -Force | Out-Null
Write-Host "Backup en: $bkp" -ForegroundColor Green

Move-Item -Path "$env:USERPROFILE\Downloads\Analisis_Comparativo_LCT_vs_7151D2024.docx" -Destination "$bkp\Analisis_Comparativo_LCT_vs_7151D2024.docx" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Analisis_Comparativo_LCT_vs_7151D2024.docx" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Bolsa de trabajo - Bolsa de trabajo (1).csv" -Destination "$bkp\Bolsa de trabajo - Bolsa de trabajo (1).csv" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Bolsa de trabajo - Bolsa de trabajo (1).csv" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Bolsa de trabajo - Bolsa de trabajo.csv" -Destination "$bkp\Bolsa de trabajo - Bolsa de trabajo.csv" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Bolsa de trabajo - Bolsa de trabajo.csv" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Bolsa de trabajo - Respuestas de formulario 1.csv" -Destination "$bkp\Bolsa de trabajo - Respuestas de formulario 1.csv" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Bolsa de trabajo - Respuestas de formulario 1.csv" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\bolsa-unificador-v1.0.0.zip" -Destination "$bkp\bolsa-unificador-v1.0.0.zip" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: bolsa-unificador-v1.0.0.zip" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\bolsa-unificador-v1.1.0.zip" -Destination "$bkp\bolsa-unificador-v1.1.0.zip" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: bolsa-unificador-v1.1.0.zip" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\bolsa-unificador-v1.1.1.zip" -Destination "$bkp\bolsa-unificador-v1.1.1.zip" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: bolsa-unificador-v1.1.1.zip" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Chat de WhatsApp con CAMPAÑA FUERZA PATRIA .zip" -Destination "$bkp\Chat de WhatsApp con CAMPAÑA FUERZA PATRIA .zip" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Chat de WhatsApp con CAMPAÑA FUERZA PATRIA .zip" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Chat de WhatsApp con PJ Distrito Rufino 2024.zip" -Destination "$bkp\Chat de WhatsApp con PJ Distrito Rufino 2024.zip" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Chat de WhatsApp con PJ Distrito Rufino 2024.zip" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Declaracion_Institucional_Despido_Christian_SanMartin.docx" -Destination "$bkp\Declaracion_Institucional_Despido_Christian_SanMartin.docx" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Declaracion_Institucional_Despido_Christian_SanMartin.docx" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Elecciones_Rufino_2025_Paquete_Oficial.zip" -Destination "$bkp\Elecciones_Rufino_2025_Paquete_Oficial.zip" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Elecciones_Rufino_2025_Paquete_Oficial.zip" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\IR_2025-11-28_Acreditacion_Seminario - Respuestas de formulario 1 (1).csv" -Destination "$bkp\IR_2025-11-28_Acreditacion_Seminario - Respuestas de formulario 1 (1).csv" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: IR_2025-11-28_Acreditacion_Seminario - Respuestas de formulario 1 (1).csv" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\IR_2025-11-28_Acreditacion_Seminario - Respuestas de formulario 1 (2).csv" -Destination "$bkp\IR_2025-11-28_Acreditacion_Seminario - Respuestas de formulario 1 (2).csv" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: IR_2025-11-28_Acreditacion_Seminario - Respuestas de formulario 1 (2).csv" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\IR_2025-11-28_Acreditacion_Seminario - Respuestas de formulario 1.csv" -Destination "$bkp\IR_2025-11-28_Acreditacion_Seminario - Respuestas de formulario 1.csv" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: IR_2025-11-28_Acreditacion_Seminario - Respuestas de formulario 1.csv" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\WhatsApp Image 2025-10-31 at 21.06.34 (1).jpeg" -Destination "$bkp\WhatsApp Image 2025-10-31 at 21.06.34 (1).jpeg" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: WhatsApp Image 2025-10-31 at 21.06.34 (1).jpeg" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\WhatsApp Image 2025-10-31 at 21.06.34.jpeg" -Destination "$bkp\WhatsApp Image 2025-10-31 at 21.06.34.jpeg" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: WhatsApp Image 2025-10-31 at 21.06.34.jpeg" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\WhatsApp Image 2025-10-31 at 21.06.35.jpeg" -Destination "$bkp\WhatsApp Image 2025-10-31 at 21.06.35.jpeg" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: WhatsApp Image 2025-10-31 at 21.06.35.jpeg" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\WhatsApp Image 2025-10-22 at 14.21.45 (1).jpeg" -Destination "$bkp\WhatsApp Image 2025-10-22 at 14.21.45 (1).jpeg" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: WhatsApp Image 2025-10-22 at 14.21.45 (1).jpeg" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\WhatsApp Image 2025-10-22 at 14.21.45 (2).jpeg" -Destination "$bkp\WhatsApp Image 2025-10-22 at 14.21.45 (2).jpeg" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: WhatsApp Image 2025-10-22 at 14.21.45 (2).jpeg" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\WhatsApp Image 2025-10-22 at 14.21.45.jpeg" -Destination "$bkp\WhatsApp Image 2025-10-22 at 14.21.45.jpeg" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: WhatsApp Image 2025-10-22 at 14.21.45.jpeg" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\WhatsApp Image 2025-10-22 at 14.21.46.jpeg" -Destination "$bkp\WhatsApp Image 2025-10-22 at 14.21.46.jpeg" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: WhatsApp Image 2025-10-22 at 14.21.46.jpeg" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Centro_de_Computos_Fuerza_Patria_2025_v5.xlsx" -Destination "$bkp\Centro_de_Computos_Fuerza_Patria_2025_v5.xlsx" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Centro_de_Computos_Fuerza_Patria_2025_v5.xlsx" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Chat de WhatsApp con CAMPAÑA FUERZA PATRIA .zip" -Destination "$bkp\Chat de WhatsApp con CAMPAÑA FUERZA PATRIA .zip" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Chat de WhatsApp con CAMPAÑA FUERZA PATRIA .zip" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Elecciones_2025_Resumen_Publico.xlsx" -Destination "$bkp\Elecciones_2025_Resumen_Publico.xlsx" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Elecciones_2025_Resumen_Publico.xlsx" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Elecciones_2025_Rufino_v3.xlsx" -Destination "$bkp\Elecciones_2025_Rufino_v3.xlsx" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Elecciones_2025_Rufino_v3.xlsx" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Elecciones_2025_Rufino_v3_con_Resumen.xlsx" -Destination "$bkp\Elecciones_2025_Rufino_v3_con_Resumen.xlsx" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Elecciones_2025_Rufino_v3_con_Resumen.xlsx" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Chat de WhatsApp con CAMPAÑA FUERZA PATRIA .txt" -Destination "$bkp\Chat de WhatsApp con CAMPAÑA FUERZA PATRIA .txt" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Chat de WhatsApp con CAMPAÑA FUERZA PATRIA .txt" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Cristian Obermeller.vcf" -Destination "$bkp\Cristian Obermeller.vcf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Cristian Obermeller.vcf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Perez Castellanos.vcf" -Destination "$bkp\Perez Castellanos.vcf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Perez Castellanos.vcf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Elecciones_2025_Resumen_Publico.xlsx" -Destination "$bkp\Elecciones_2025_Resumen_Publico.xlsx" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Elecciones_2025_Resumen_Publico.xlsx" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Elecciones_2025_Rufino_v3_con_Resumen.xlsx" -Destination "$bkp\Elecciones_2025_Rufino_v3_con_Resumen.xlsx" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Elecciones_2025_Rufino_v3_con_Resumen.xlsx" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\ficha_tecnica.txt" -Destination "$bkp\ficha_tecnica.txt" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: ficha_tecnica.txt" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Berenguer.vcf" -Destination "$bkp\Berenguer.vcf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Berenguer.vcf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Chat de WhatsApp con PJ Distrito Rufino 2024.txt" -Destination "$bkp\Chat de WhatsApp con PJ Distrito Rufino 2024.txt" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Chat de WhatsApp con PJ Distrito Rufino 2024.txt" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Hugo.vcf" -Destination "$bkp\Hugo.vcf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Hugo.vcf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Mario Comedor Pancitas felices.vcf" -Destination "$bkp\Mario Comedor Pancitas felices.vcf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Mario Comedor Pancitas felices.vcf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Mirtita.vcf" -Destination "$bkp\Mirtita.vcf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Mirtita.vcf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Chat de WhatsApp con FUERZA PATRIA.txt" -Destination "$bkp\Chat de WhatsApp con FUERZA PATRIA.txt" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Chat de WhatsApp con FUERZA PATRIA.txt" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Cristian Obermeller.vcf" -Destination "$bkp\Cristian Obermeller.vcf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Cristian Obermeller.vcf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Gera Herrera.vcf" -Destination "$bkp\Gera Herrera.vcf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Gera Herrera.vcf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Oberneller Computos.vcf" -Destination "$bkp\Oberneller Computos.vcf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Oberneller Computos.vcf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Perez Castellanos.vcf" -Destination "$bkp\Perez Castellanos.vcf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Perez Castellanos.vcf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Sociedad Italiana.vcf" -Destination "$bkp\Sociedad Italiana.vcf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Sociedad Italiana.vcf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Chat de WhatsApp con CAMPAÑA FUERZA PATRIA .txt" -Destination "$bkp\Chat de WhatsApp con CAMPAÑA FUERZA PATRIA .txt" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Chat de WhatsApp con CAMPAÑA FUERZA PATRIA .txt" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Perez Castellanos.vcf" -Destination "$bkp\Perez Castellanos.vcf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Perez Castellanos.vcf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\changelog.txt" -Destination "$bkp\changelog.txt" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: changelog.txt" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\info.txt" -Destination "$bkp\info.txt" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: info.txt" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\logo_intersindical_solid_white.png" -Destination "$bkp\logo_intersindical_solid_white.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: logo_intersindical_solid_white.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\registros_con_id.csv" -Destination "$bkp\registros_con_id.csv" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: registros_con_id.csv" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_14653447_Maria_Angélica_Urtiaga.pdf" -Destination "$bkp\Certificado_14653447_Maria_Angélica_Urtiaga.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_14653447_Maria_Angélica_Urtiaga.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_14807929_Morillo_Lucilo.pdf" -Destination "$bkp\Certificado_14807929_Morillo_Lucilo.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_14807929_Morillo_Lucilo.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_16370557_Miguel_Ángel_Ciuna.pdf" -Destination "$bkp\Certificado_16370557_Miguel_Ángel_Ciuna.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_16370557_Miguel_Ángel_Ciuna.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_20499504_Pablo_J_Roca.pdf" -Destination "$bkp\Certificado_20499504_Pablo_J_Roca.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_20499504_Pablo_J_Roca.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_20703625_Gorosito_nanci.pdf" -Destination "$bkp\Certificado_20703625_Gorosito_nanci.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_20703625_Gorosito_nanci.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_23419585_Claudio_Covicchi.pdf" -Destination "$bkp\Certificado_23419585_Claudio_Covicchi.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_23419585_Claudio_Covicchi.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_24918155_Mauricio_Fernando_Marengo.pdf" -Destination "$bkp\Certificado_24918155_Mauricio_Fernando_Marengo.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_24918155_Mauricio_Fernando_Marengo.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_25773982_María_José_Barrios.pdf" -Destination "$bkp\Certificado_25773982_María_José_Barrios.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_25773982_María_José_Barrios.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_26094798_Gabriel_Alejandro_Perez.pdf" -Destination "$bkp\Certificado_26094798_Gabriel_Alejandro_Perez.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_26094798_Gabriel_Alejandro_Perez.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_26590222_Silvana_Daniela_Sanchez.pdf" -Destination "$bkp\Certificado_26590222_Silvana_Daniela_Sanchez.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_26590222_Silvana_Daniela_Sanchez.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_26591041_Ferreira_Maria_Laura.pdf" -Destination "$bkp\Certificado_26591041_Ferreira_Maria_Laura.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_26591041_Ferreira_Maria_Laura.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_26870662_Facundo_Marin_Tapia.pdf" -Destination "$bkp\Certificado_26870662_Facundo_Marin_Tapia.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_26870662_Facundo_Marin_Tapia.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_27379592_Norberto_Cermeli.pdf" -Destination "$bkp\Certificado_27379592_Norberto_Cermeli.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_27379592_Norberto_Cermeli.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_27419853_Cristian_David_Obermeller.pdf" -Destination "$bkp\Certificado_27419853_Cristian_David_Obermeller.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_27419853_Cristian_David_Obermeller.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_27419912_Maria_Alejandra_Belmudez.pdf" -Destination "$bkp\Certificado_27419912_Maria_Alejandra_Belmudez.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_27419912_Maria_Alejandra_Belmudez.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_27696918_Sergio_Arraras.pdf" -Destination "$bkp\Certificado_27696918_Sergio_Arraras.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_27696918_Sergio_Arraras.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_30399425_Juan_Ignacio_Alanis.pdf" -Destination "$bkp\Certificado_30399425_Juan_Ignacio_Alanis.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_30399425_Juan_Ignacio_Alanis.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_31981146_Luis_Uran.pdf" -Destination "$bkp\Certificado_31981146_Luis_Uran.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_31981146_Luis_Uran.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_32171144_Mauricio_Alanis.pdf" -Destination "$bkp\Certificado_32171144_Mauricio_Alanis.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_32171144_Mauricio_Alanis.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_32901013_Piazza_Oscar_Alberto.pdf" -Destination "$bkp\Certificado_32901013_Piazza_Oscar_Alberto.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_32901013_Piazza_Oscar_Alberto.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_34430317_Mauro_Corua.pdf" -Destination "$bkp\Certificado_34430317_Mauro_Corua.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_34430317_Mauro_Corua.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_35919851_Guido_Benedetto.pdf" -Destination "$bkp\Certificado_35919851_Guido_Benedetto.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_35919851_Guido_Benedetto.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_37902713_Nicolás_Saravia.pdf" -Destination "$bkp\Certificado_37902713_Nicolás_Saravia.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_37902713_Nicolás_Saravia.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_39662193_Gonzalo_Chiarotto.pdf" -Destination "$bkp\Certificado_39662193_Gonzalo_Chiarotto.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_39662193_Gonzalo_Chiarotto.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_40714928_Belén_Laura_Espinosa_Villalba.pdf" -Destination "$bkp\Certificado_40714928_Belén_Laura_Espinosa_Villalba.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_40714928_Belén_Laura_Espinosa_Villalba.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_43647731_Lautaro_Stieb.pdf" -Destination "$bkp\Certificado_43647731_Lautaro_Stieb.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_43647731_Lautaro_Stieb.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_43843223_Tapia_Samuel_Ezequías.pdf" -Destination "$bkp\Certificado_43843223_Tapia_Samuel_Ezequías.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_43843223_Tapia_Samuel_Ezequías.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_44067870_Francisco_Garbarini.pdf" -Destination "$bkp\Certificado_44067870_Francisco_Garbarini.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_44067870_Francisco_Garbarini.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_46998254_Nicolas_Burgos.pdf" -Destination "$bkp\Certificado_46998254_Nicolas_Burgos.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_46998254_Nicolas_Burgos.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_14653447.pdf" -Destination "$bkp\Certificado_14653447.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_14653447.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_14807929.pdf" -Destination "$bkp\Certificado_14807929.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_14807929.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_16370557.pdf" -Destination "$bkp\Certificado_16370557.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_16370557.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_20499504.pdf" -Destination "$bkp\Certificado_20499504.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_20499504.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_20703625.pdf" -Destination "$bkp\Certificado_20703625.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_20703625.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_23419585.pdf" -Destination "$bkp\Certificado_23419585.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_23419585.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_24918155.pdf" -Destination "$bkp\Certificado_24918155.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_24918155.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_25773982.pdf" -Destination "$bkp\Certificado_25773982.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_25773982.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_26094798.pdf" -Destination "$bkp\Certificado_26094798.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_26094798.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_26590222.pdf" -Destination "$bkp\Certificado_26590222.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_26590222.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_26591041.pdf" -Destination "$bkp\Certificado_26591041.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_26591041.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_26870662.pdf" -Destination "$bkp\Certificado_26870662.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_26870662.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_27379592.pdf" -Destination "$bkp\Certificado_27379592.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_27379592.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_27419853.pdf" -Destination "$bkp\Certificado_27419853.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_27419853.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_27419912.pdf" -Destination "$bkp\Certificado_27419912.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_27419912.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_27696918.pdf" -Destination "$bkp\Certificado_27696918.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_27696918.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_30399425.pdf" -Destination "$bkp\Certificado_30399425.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_30399425.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_31981146.pdf" -Destination "$bkp\Certificado_31981146.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_31981146.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_32171144.pdf" -Destination "$bkp\Certificado_32171144.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_32171144.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_32901013.pdf" -Destination "$bkp\Certificado_32901013.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_32901013.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_34430317.pdf" -Destination "$bkp\Certificado_34430317.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_34430317.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_35919851.pdf" -Destination "$bkp\Certificado_35919851.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_35919851.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_37902713.pdf" -Destination "$bkp\Certificado_37902713.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_37902713.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_39662193.pdf" -Destination "$bkp\Certificado_39662193.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_39662193.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_40714928.pdf" -Destination "$bkp\Certificado_40714928.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_40714928.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_43647731.pdf" -Destination "$bkp\Certificado_43647731.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_43647731.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_43843223.pdf" -Destination "$bkp\Certificado_43843223.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_43843223.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_44067870.pdf" -Destination "$bkp\Certificado_44067870.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_44067870.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\Certificado_46998254.pdf" -Destination "$bkp\Certificado_46998254.pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: Certificado_46998254.pdf" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\registros_con_id.csv" -Destination "$bkp\registros_con_id.csv" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: registros_con_id.csv" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\ADMINISTRATIVO_00_institucional.png" -Destination "$bkp\ADMINISTRATIVO_00_institucional.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: ADMINISTRATIVO_00_institucional.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\ADMINISTRATIVO_99_cierre.png" -Destination "$bkp\ADMINISTRATIVO_99_cierre.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: ADMINISTRATIVO_99_cierre.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\CONTACTO_00_institucional.png" -Destination "$bkp\CONTACTO_00_institucional.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: CONTACTO_00_institucional.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\CONTACTO_99_cierre.png" -Destination "$bkp\CONTACTO_99_cierre.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: CONTACTO_99_cierre.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\JOVENES_00_institucional.png" -Destination "$bkp\JOVENES_00_institucional.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: JOVENES_00_institucional.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\JOVENES_99_cierre.png" -Destination "$bkp\JOVENES_99_cierre.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: JOVENES_99_cierre.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\OFICIOS_00_institucional.png" -Destination "$bkp\OFICIOS_00_institucional.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: OFICIOS_00_institucional.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\OFICIOS_99_cierre.png" -Destination "$bkp\OFICIOS_99_cierre.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: OFICIOS_99_cierre.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\OFICIOS_perfil_0.png" -Destination "$bkp\OFICIOS_perfil_0.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: OFICIOS_perfil_0.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\OFICIOS_perfil_1.png" -Destination "$bkp\OFICIOS_perfil_1.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: OFICIOS_perfil_1.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\OFICIOS_perfil_10.png" -Destination "$bkp\OFICIOS_perfil_10.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: OFICIOS_perfil_10.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\OFICIOS_perfil_11.png" -Destination "$bkp\OFICIOS_perfil_11.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: OFICIOS_perfil_11.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\OFICIOS_perfil_12.png" -Destination "$bkp\OFICIOS_perfil_12.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: OFICIOS_perfil_12.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\OFICIOS_perfil_13.png" -Destination "$bkp\OFICIOS_perfil_13.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: OFICIOS_perfil_13.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\OFICIOS_perfil_14.png" -Destination "$bkp\OFICIOS_perfil_14.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: OFICIOS_perfil_14.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\OFICIOS_perfil_15.png" -Destination "$bkp\OFICIOS_perfil_15.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: OFICIOS_perfil_15.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\OFICIOS_perfil_16.png" -Destination "$bkp\OFICIOS_perfil_16.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: OFICIOS_perfil_16.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\OFICIOS_perfil_17.png" -Destination "$bkp\OFICIOS_perfil_17.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: OFICIOS_perfil_17.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\OFICIOS_perfil_18.png" -Destination "$bkp\OFICIOS_perfil_18.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: OFICIOS_perfil_18.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\OFICIOS_perfil_19.png" -Destination "$bkp\OFICIOS_perfil_19.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: OFICIOS_perfil_19.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\OFICIOS_perfil_2.png" -Destination "$bkp\OFICIOS_perfil_2.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: OFICIOS_perfil_2.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\OFICIOS_perfil_3.png" -Destination "$bkp\OFICIOS_perfil_3.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: OFICIOS_perfil_3.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\OFICIOS_perfil_4.png" -Destination "$bkp\OFICIOS_perfil_4.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: OFICIOS_perfil_4.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\OFICIOS_perfil_5.png" -Destination "$bkp\OFICIOS_perfil_5.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: OFICIOS_perfil_5.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\OFICIOS_perfil_6.png" -Destination "$bkp\OFICIOS_perfil_6.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: OFICIOS_perfil_6.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\OFICIOS_perfil_7.png" -Destination "$bkp\OFICIOS_perfil_7.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: OFICIOS_perfil_7.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\OFICIOS_perfil_8.png" -Destination "$bkp\OFICIOS_perfil_8.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: OFICIOS_perfil_8.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\OFICIOS_perfil_9.png" -Destination "$bkp\OFICIOS_perfil_9.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: OFICIOS_perfil_9.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\plantilla_base.png" -Destination "$bkp\plantilla_base.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: plantilla_base.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\portada_ADMINISTRATIVO.png" -Destination "$bkp\portada_ADMINISTRATIVO.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: portada_ADMINISTRATIVO.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\portada_CONTACTO.png" -Destination "$bkp\portada_CONTACTO.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: portada_CONTACTO.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\portada_JOVENES.png" -Destination "$bkp\portada_JOVENES.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: portada_JOVENES.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\portada_OFICIOS.png" -Destination "$bkp\portada_OFICIOS.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: portada_OFICIOS.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\portada_SERVICIOS.png" -Destination "$bkp\portada_SERVICIOS.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: portada_SERVICIOS.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\portada_TRANSPORTE.png" -Destination "$bkp\portada_TRANSPORTE.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: portada_TRANSPORTE.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\SERVICIOS_00_institucional.png" -Destination "$bkp\SERVICIOS_00_institucional.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: SERVICIOS_00_institucional.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\SERVICIOS_99_cierre.png" -Destination "$bkp\SERVICIOS_99_cierre.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: SERVICIOS_99_cierre.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\TRANSPORTE_00_institucional.png" -Destination "$bkp\TRANSPORTE_00_institucional.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: TRANSPORTE_00_institucional.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\TRANSPORTE_99_cierre.png" -Destination "$bkp\TRANSPORTE_99_cierre.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: TRANSPORTE_99_cierre.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\admin_inst.png" -Destination "$bkp\admin_inst.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: admin_inst.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\contacto_inst.png" -Destination "$bkp\contacto_inst.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: contacto_inst.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\jovenes_inst.png" -Destination "$bkp\jovenes_inst.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: jovenes_inst.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\oficios_inst.png" -Destination "$bkp\oficios_inst.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: oficios_inst.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\plantilla_individual.png" -Destination "$bkp\plantilla_individual.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: plantilla_individual.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\portada_ADMINISTRATIVO.png" -Destination "$bkp\portada_ADMINISTRATIVO.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: portada_ADMINISTRATIVO.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\portada_CONTACTO.png" -Destination "$bkp\portada_CONTACTO.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: portada_CONTACTO.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\portada_JOVENES.png" -Destination "$bkp\portada_JOVENES.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: portada_JOVENES.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\portada_OFICIOS.png" -Destination "$bkp\portada_OFICIOS.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: portada_OFICIOS.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\portada_SERVICIOS.png" -Destination "$bkp\portada_SERVICIOS.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: portada_SERVICIOS.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\portada_TRANSPORTE.png" -Destination "$bkp\portada_TRANSPORTE.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: portada_TRANSPORTE.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\servicios_inst.png" -Destination "$bkp\servicios_inst.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: servicios_inst.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\transporte_inst.png" -Destination "$bkp\transporte_inst.png" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: transporte_inst.png" -ForegroundColor Yellow

Move-Item -Path "$env:USERPROFILE\Downloads\COMUNICADO DE LA DEPARTAMENTAL DEL PARTIDO JUSTICIALISTA DEL DEPARTAMENTO GENERAL LÓPEZ (1).pdf" -Destination "$bkp\COMUNICADO DE LA DEPARTAMENTAL DEL PARTIDO JUSTICIALISTA DEL DEPARTAMENTO GENERAL LÓPEZ (1).pdf" -Force -ErrorAction SilentlyContinue
Write-Host "Movido: COMUNICADO DE LA DEPARTAMENTAL DEL PARTIDO JUSTICIALISTA DEL DEPARTAMENTO GENERAL LÓPEZ (1).pdf" -ForegroundColor Yellow

Write-Host "Total: 161 archivos" -ForegroundColor Cyan