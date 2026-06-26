$plan = Get-ChildItem "F:\HERRAMIENTAS DE IA\01-panel inteligente\reportes\el_rufino_plan_v51_*.csv" |
Sort-Object LastWriteTime -Descending |
Select-Object -First 1

$destinos = @(
"01_NUCLEO_OPERATIVO\WEB_APP",
"01_NUCLEO_OPERATIVO\WORDPRESS",
"01_NUCLEO_OPERATIVO\PANEL_IA",
"06_BINARIOS\DESPLIEGUE"
)

Import-Csv $plan.FullName |
Where-Object { $_.DestinoRelativo -in $destinos } |
Format-Table -Wrap -AutoSize