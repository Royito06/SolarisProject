@echo off
title ConcursoFitMat - Sistema de Concurso de Fisica y Matematicas
echo ========================================================
echo   Iniciando ConcursoFitMat (Laravel 7)
echo ========================================================
echo.

:: Asegurar PATH con las herramientas instaladas
set "PATH=C:\tools\mysql\bin;C:\tools\node;C:\tools\php;%PATH%"

:: Verificar si MariaDB esta corriendo en puerto 3307
netstat -ano | findstr ":3307" >nul
if %errorlevel% equ 0 (
    echo [OK] Base de datos MariaDB ya esta en ejecucion en puerto 3307.
) else (
    echo [*] Iniciando MariaDB en puerto 3307...
    start /b "" "C:\tools\mysql\bin\mysqld.exe" --port=3307 --datadir="C:/tools/mysql/data"
    timeout /t 3 /nobreak >nul
    echo [OK] MariaDB iniciada.
)

echo.
echo ========================================================
echo   Servidor disponible en: http://127.0.0.1:8000
echo   Presiona CTRL+C en esta ventana para detener el servidor
echo ========================================================
echo.

:: Abrir navegador automaticamente
start http://127.0.0.1:8000

:: Iniciar servidor Laravel
php artisan serve --port=8000
pause

