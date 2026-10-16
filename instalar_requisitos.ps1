# ============================================================
# Script de instalación de requisitos - ConcursoFitMat (Laravel 7)
# EJECUTAR COMO ADMINISTRADOR:
#   Click derecho en PowerShell -> "Ejecutar como administrador"
#   Luego: cd "c:\Users\corde\OneDrive\Escritorio\School\5to semestre\IngenieriaSoftwareII\ConcursoFitMat"
#   Y ejecutar: .\instalar_requisitos.ps1
# ============================================================

Write-Host "========================================" -ForegroundColor Cyan
Write-Host " Instalador de requisitos - ConcursoFitMat" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# --- Paso 1: Instalar Chocolatey ---
if (-not (Get-Command choco -ErrorAction SilentlyContinue)) {
    Write-Host "[1/5] Instalando Chocolatey..." -ForegroundColor Yellow
    Set-ExecutionPolicy Bypass -Scope Process -Force
    [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072
    Invoke-Expression ((New-Object System.Net.WebClient).DownloadString('https://community.chocolatey.org/install.ps1'))
    
    # Recargar PATH para que choco esté disponible
    $env:Path = [System.Environment]::GetEnvironmentVariable("Path", "Machine") + ";" + [System.Environment]::GetEnvironmentVariable("Path", "User")
    
    if (Get-Command choco -ErrorAction SilentlyContinue) {
        Write-Host "[OK] Chocolatey instalado correctamente." -ForegroundColor Green
    } else {
        Write-Host "[ERROR] No se pudo instalar Chocolatey. Cierra y vuelve a abrir PowerShell como Admin e intenta de nuevo." -ForegroundColor Red
        exit 1
    }
} else {
    Write-Host "[1/5] Chocolatey ya esta instalado." -ForegroundColor Green
}

Write-Host ""

# --- Paso 2: Instalar PHP 8.0 ---
if (-not (Get-Command php -ErrorAction SilentlyContinue)) {
    Write-Host "[2/5] Instalando PHP 8.0..." -ForegroundColor Yellow
    choco install php --version=8.0.30 -y --params '/InstallDir:C:\tools\php80'
    
    # Recargar PATH
    $env:Path = [System.Environment]::GetEnvironmentVariable("Path", "Machine") + ";" + [System.Environment]::GetEnvironmentVariable("Path", "User")
    
    # Habilitar extensiones necesarias para Laravel en php.ini
    $phpIniPath = "C:\tools\php80\php.ini"
    if (-not (Test-Path $phpIniPath)) {
        $phpIniDevPath = "C:\tools\php80\php.ini-development"
        if (Test-Path $phpIniDevPath) {
            Copy-Item $phpIniDevPath $phpIniPath
        }
    }
    
    if (Test-Path $phpIniPath) {
        $extensions = @("extension=curl", "extension=fileinfo", "extension=mbstring", "extension=openssl", "extension=pdo_mysql", "extension=tokenizer", "extension=xml", "extension=zip", "extension=gd")
        $content = Get-Content $phpIniPath -Raw
        foreach ($ext in $extensions) {
            $extName = $ext -replace "extension=", ""
            # Descomentar si está comentado
            $content = $content -replace ";extension=$extName", "extension=$extName"
        }
        # Configurar extension_dir
        $content = $content -replace '; extension_dir = "ext"', 'extension_dir = "ext"'
        $content = $content -replace ';extension_dir = "ext"', 'extension_dir = "ext"'
        Set-Content $phpIniPath $content
        Write-Host "    Extensiones PHP habilitadas (pdo_mysql, mbstring, openssl, etc.)" -ForegroundColor DarkGray
    }
    
    Write-Host "[OK] PHP instalado." -ForegroundColor Green
} else {
    Write-Host "[2/5] PHP ya esta instalado: $(php -v | Select-Object -First 1)" -ForegroundColor Green
}

Write-Host ""

# --- Paso 3: Instalar Composer ---
if (-not (Get-Command composer -ErrorAction SilentlyContinue)) {
    Write-Host "[3/5] Instalando Composer..." -ForegroundColor Yellow
    choco install composer -y
    
    # Recargar PATH
    $env:Path = [System.Environment]::GetEnvironmentVariable("Path", "Machine") + ";" + [System.Environment]::GetEnvironmentVariable("Path", "User")
    
    Write-Host "[OK] Composer instalado." -ForegroundColor Green
} else {
    Write-Host "[3/5] Composer ya esta instalado: $(composer --version)" -ForegroundColor Green
}

Write-Host ""

# --- Paso 4: Instalar Node.js (LTS) y NPM ---
if (-not (Get-Command node -ErrorAction SilentlyContinue)) {
    Write-Host "[4/5] Instalando Node.js (LTS)..." -ForegroundColor Yellow
    choco install nodejs-lts -y
    
    # Recargar PATH
    $env:Path = [System.Environment]::GetEnvironmentVariable("Path", "Machine") + ";" + [System.Environment]::GetEnvironmentVariable("Path", "User")
    
    Write-Host "[OK] Node.js instalado." -ForegroundColor Green
} else {
    Write-Host "[4/5] Node.js ya esta instalado: $(node -v)" -ForegroundColor Green
}

Write-Host ""

# --- Paso 5: Instalar MySQL ---
if (-not (Get-Command mysql -ErrorAction SilentlyContinue)) {
    Write-Host "[5/5] Instalando MySQL..." -ForegroundColor Yellow
    choco install mysql -y
    
    # Recargar PATH
    $env:Path = [System.Environment]::GetEnvironmentVariable("Path", "Machine") + ";" + [System.Environment]::GetEnvironmentVariable("Path", "User")
    
    Write-Host "[OK] MySQL instalado." -ForegroundColor Green
} else {
    Write-Host "[5/5] MySQL ya esta instalado." -ForegroundColor Green
}

Write-Host ""

# Recargar PATH final
$env:Path = [System.Environment]::GetEnvironmentVariable("Path", "Machine") + ";" + [System.Environment]::GetEnvironmentVariable("Path", "User")

# --- Resumen ---
Write-Host "========================================" -ForegroundColor Cyan
Write-Host " RESUMEN DE INSTALACION" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan

$tools = @(
    @{Name="PHP";      Cmd="php"},
    @{Name="Composer"; Cmd="composer"},
    @{Name="Node.js";  Cmd="node"},
    @{Name="NPM";      Cmd="npm"},
    @{Name="MySQL";    Cmd="mysql"}
)

$allOk = $true
foreach ($tool in $tools) {
    if (Get-Command $tool.Cmd -ErrorAction SilentlyContinue) {
        Write-Host "  [OK] $($tool.Name)" -ForegroundColor Green
    } else {
        Write-Host "  [X]  $($tool.Name) - No detectado (puede requerir reiniciar la terminal)" -ForegroundColor Red
        $allOk = $false
    }
}

Write-Host ""
if ($allOk) {
    Write-Host "Todas las herramientas estan instaladas!" -ForegroundColor Green
    Write-Host ""
    Write-Host "SIGUIENTE PASO: Cierra esta terminal y abre una nueva (normal, no Admin)." -ForegroundColor Yellow
    Write-Host "Luego ejecuta el script: .\configurar_proyecto.ps1" -ForegroundColor Yellow
} else {
    Write-Host "Algunas herramientas no se detectaron." -ForegroundColor Yellow
    Write-Host "Cierra la terminal, vuelve a abrir y ejecuta este script de nuevo." -ForegroundColor Yellow
}

Write-Host ""
Write-Host "Presiona Enter para salir..."
Read-Host

