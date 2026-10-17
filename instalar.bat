@echo off
title Instalador Automatico de Requisitos - ConcursoFitMat
cd /d "%~dp0"
echo ========================================================
echo   Instalando y configurando ConcursoFitMat...
echo ========================================================
echo.
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0instalar_requisitos.ps1"
pause

