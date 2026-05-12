@echo off
:: Arrastra cualquier .ps1 sobre este archivo para ejecutarlo
:: O usalo para elegir un script de esta carpeta

if "%~1"=="" (
    echo Arrastra un archivo .ps1 sobre este .bat para ejecutarlo.
    pause
    exit
)

powershell.exe -ExecutionPolicy Bypass -NoExit -File "%~1"
