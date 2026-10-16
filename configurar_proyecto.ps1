# ============================================================
# Script de configuración del proyecto - ConcursoFitMat (Laravel 7)
# EJECUTAR DESPUES de instalar los requisitos (instalar_requisitos.ps1)
# En una terminal NORMAL (no necesita Admin):
#   cd "c:\Users\corde\OneDrive\Escritorio\School\5to semestre\IngenieriaSoftwareII\ConcursoFitMat"
#   .\configurar_proyecto.ps1
# ============================================================

Write-Host "========================================" -ForegroundColor Cyan
Write-Host " Configuracion del proyecto - ConcursoFitMat" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Verificar herramientas
$missing = @()
if (-not (Get-Command php -ErrorAction SilentlyContinue)) { $missing += "PHP" }
if (-not (Get-Command composer -ErrorAction SilentlyContinue)) { $missing += "Composer" }
if (-not (Get-Command node -ErrorAction SilentlyContinue)) { $missing += "Node.js" }
if (-not (Get-Command npm -ErrorAction SilentlyContinue)) { $missing += "NPM" }

if ($missing.Count -gt 0) {
    Write-Host "[ERROR] Faltan herramientas: $($missing -join ', ')" -ForegroundColor Red
    Write-Host "Ejecuta primero instalar_requisitos.ps1 como Administrador." -ForegroundColor Yellow
    Write-Host "Si ya lo hiciste, cierra y vuelve a abrir la terminal." -ForegroundColor Yellow
    exit 1
}

Write-Host "[OK] Todas las herramientas detectadas." -ForegroundColor Green
Write-Host ""

# --- Paso 1: Instalar dependencias PHP ---
Write-Host "[1/4] Instalando dependencias PHP (composer install)..." -ForegroundColor Yellow
composer install --no-interaction
Write-Host "[OK] Dependencias PHP instaladas." -ForegroundColor Green
Write-Host ""

# --- Paso 2: Instalar dependencias frontend ---
Write-Host "[2/4] Instalando dependencias frontend (npm install)..." -ForegroundColor Yellow
npm install
Write-Host "[OK] Dependencias frontend instaladas." -ForegroundColor Green
Write-Host ""

# --- Paso 3: Compilar assets ---
Write-Host "[3/4] Compilando assets (npm run dev)..." -ForegroundColor Yellow
npm run dev
Write-Host "[OK] Assets compilados." -ForegroundColor Green
Write-Host ""

# --- Paso 4: Configurar .env para desarrollo local ---
Write-Host "[4/4] Verificando configuracion .env..." -ForegroundColor Yellow
$envPath = Join-Path $PSScriptRoot ".env"
if (Test-Path $envPath) {
    Write-Host "  Archivo .env encontrado." -ForegroundColor DarkGray
    Write-Host "  NOTA: Revisa que DB_PORT, DB_USERNAME y DB_PASSWORD" -ForegroundColor DarkGray
    Write-Host "  coincidan con tu instalacion local de MySQL." -ForegroundColor DarkGray
    Write-Host "  Actualmente:" -ForegroundColor DarkGray
    
    $envContent = Get-Content $envPath
    $dbLines = $envContent | Where-Object { $_ -match "^DB_" }
    foreach ($line in $dbLines) {
        Write-Host "    $line" -ForegroundColor DarkGray
    }
} else {
    Write-Host "  Copiando .env.example a .env..." -ForegroundColor DarkGray
    Copy-Item ".env.example" ".env"
    php artisan key:generate
    Write-Host "  [OK] Archivo .env creado y clave generada." -ForegroundColor Green
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host " CONFIGURACION COMPLETA" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Para iniciar el servidor de desarrollo:" -ForegroundColor Green
Write-Host "  php artisan serve --port=8000" -ForegroundColor White
Write-Host ""
Write-Host "IMPORTANTE - Antes de iniciar, necesitas:" -ForegroundColor Yellow
Write-Host "  1. Asegurate de que MySQL este corriendo" -ForegroundColor White
Write-Host "  2. Crea la base de datos: mysql -u root -e 'CREATE DATABASE concursofm;'" -ForegroundColor White
Write-Host "  3. Crea el usuario: mysql -u root -e ""CREATE USER 'concursofm'@'localhost' IDENTIFIED BY 'btHRD729'; GRANT ALL ON concursofm.* TO 'concursofm'@'localhost';""" -ForegroundColor White
Write-Host "  4. Ejecuta migraciones: php artisan migrate" -ForegroundColor White
Write-Host "  5. Inicia el servidor: php artisan serve --port=8000" -ForegroundColor White
Write-Host ""
Write-Host "La app estara disponible en: http://localhost:8000" -ForegroundColor Cyan
Write-Host ""
Write-Host "Presiona Enter para salir..."
Read-Host

