# ============================================================
# Instalador automático portátil - ConcursoFitMat (Laravel 7)
# NO requiere permisos de Administrador ni Chocolatey.
#
# Para ejecutar en PowerShell:
#   cd "ruta\a\ConcursoFitMat"
#   Set-ExecutionPolicy Bypass -Scope Process -Force
#   .\instalar_requisitos.ps1
# ============================================================

[System.Net.ServicePointManager]::SecurityProtocol = [System.Net.SecurityProtocolType]::Tls12
$ErrorActionPreference = "Stop"

Write-Host "========================================================" -ForegroundColor Cyan
Write-Host "   Instalador Automatico de Requisitos - ConcursoFitMat" -ForegroundColor Cyan
Write-Host "========================================================" -ForegroundColor Cyan
Write-Host ""

$toolsDir = "C:\tools"
if (-not (Test-Path $toolsDir)) {
    New-Item -ItemType Directory -Force -Path $toolsDir | Out-Null
    Write-Host "[OK] Directorio $toolsDir creado." -ForegroundColor Green
}

# -------------------------------------------------------------
# 1. PHP 8.0 Portable
# -------------------------------------------------------------
$phpDir = Join-Path $toolsDir "php"
if (-not (Test-Path (Join-Path $phpDir "php.exe"))) {
    Write-Host "[1/5] Descargando PHP 8.0 (portable)..." -ForegroundColor Yellow
    $phpZip = Join-Path $toolsDir "php.zip"
    $phpUrl = "https://windows.php.net/downloads/releases/archives/php-8.0.30-nts-Win32-vs16-x64.zip"
    try {
        Invoke-WebRequest -Uri $phpUrl -OutFile $phpZip -UseBasicParsing
    } catch {
        $phpUrl = "https://windows.php.net/downloads/releases/php-8.0.30-nts-Win32-vs16-x64.zip"
        Invoke-WebRequest -Uri $phpUrl -OutFile $phpZip -UseBasicParsing
    }

    Write-Host "      Extrayendo PHP en $phpDir..." -ForegroundColor Yellow
    if (Test-Path $phpDir) { Remove-Item $phpDir -Recurse -Force }
    Expand-Archive -Path $phpZip -DestinationPath $phpDir -Force
    Remove-Item $phpZip -Force -ErrorAction SilentlyContinue

    # Configurar php.ini
    $phpIni = Join-Path $phpDir "php.ini"
    Copy-Item (Join-Path $phpDir "php.ini-development") $phpIni -Force
    $ini = Get-Content $phpIni -Raw
    $ini = $ini -replace ';extension_dir = "ext"', "extension_dir = `"$phpDir\ext`""
    $ini = $ini -replace '; extension_dir = "ext"', "extension_dir = `"$phpDir\ext`""
    $extensions = @("curl", "fileinfo", "gd", "mbstring", "exif", "mysqli", "openssl", "pdo_mysql")
    foreach ($ext in $extensions) {
        $ini = $ini -replace ";extension=$ext", "extension=$ext"
    }
    Set-Content $phpIni $ini
    Write-Host "[OK] PHP 8.0 instalado y configurado." -ForegroundColor Green
} else {
    Write-Host "[1/5] PHP ya esta instalado en $phpDir." -ForegroundColor Green
}

# -------------------------------------------------------------
# 2. Composer
# -------------------------------------------------------------
$composerPhar = Join-Path $phpDir "composer.phar"
if (-not (Test-Path $composerPhar)) {
    Write-Host "[2/5] Descargando Composer..." -ForegroundColor Yellow
    $composerUrl = "https://getcomposer.org/download/latest-stable/composer.phar"
    Invoke-WebRequest -Uri $composerUrl -OutFile $composerPhar -UseBasicParsing
    Set-Content (Join-Path $phpDir "composer.bat") '@php "%~dp0composer.phar" %*'
    Set-Content (Join-Path $phpDir "composer.ps1") "& `"$phpDir\php.exe`" `"$composerPhar`" `$args"
    Write-Host "[OK] Composer instalado." -ForegroundColor Green
} else {
    Write-Host "[2/5] Composer ya esta instalado." -ForegroundColor Green
}

# -------------------------------------------------------------
# 3. Node.js (v18 LTS Portable)
# -------------------------------------------------------------
$nodeDir = Join-Path $toolsDir "node"
if (-not (Test-Path (Join-Path $nodeDir "node.exe"))) {
    Write-Host "[3/5] Descargando Node.js 18 LTS..." -ForegroundColor Yellow
    $nodeZip = Join-Path $toolsDir "node.zip"
    $nodeUrl = "https://nodejs.org/dist/v18.20.4/node-v18.20.4-win-x64.zip"
    Invoke-WebRequest -Uri $nodeUrl -OutFile $nodeZip -UseBasicParsing

    Write-Host "      Extrayendo Node.js en $nodeDir..." -ForegroundColor Yellow
    if (Test-Path $nodeDir) { Remove-Item $nodeDir -Recurse -Force }
    Expand-Archive -Path $nodeZip -DestinationPath $toolsDir -Force
    Remove-Item $nodeZip -Force -ErrorAction SilentlyContinue

    $extracted = Get-ChildItem $toolsDir -Directory | Where-Object { $_.Name -like "node-v18*" } | Select-Object -First 1
    if ($extracted) {
        Rename-Item $extracted.FullName $nodeDir -Force
    }
    Write-Host "[OK] Node.js y NPM instalados." -ForegroundColor Green
} else {
    Write-Host "[3/5] Node.js ya esta instalado en $nodeDir." -ForegroundColor Green
}

# -------------------------------------------------------------
# 4. MariaDB / MySQL (v10.6 Portable)
# -------------------------------------------------------------
$mysqlDir = Join-Path $toolsDir "mysql"
$mysqlBin = Join-Path $mysqlDir "bin"
$mysqlData = Join-Path $mysqlDir "data"
if (-not (Test-Path (Join-Path $mysqlBin "mysqld.exe"))) {
    Write-Host "[4/5] Descargando MariaDB 10.6 (compatible con MySQL)..." -ForegroundColor Yellow
    $mysqlZip = Join-Path $toolsDir "mysql.zip"
    $mariaUrl = "https://archive.mariadb.org/mariadb-10.6.20/winx64-packages/mariadb-10.6.20-winx64.zip"
    Invoke-WebRequest -Uri $mariaUrl -OutFile $mysqlZip -UseBasicParsing

    Write-Host "      Extrayendo MariaDB en $mysqlDir..." -ForegroundColor Yellow
    if (Test-Path $mysqlDir) { Remove-Item $mysqlDir -Recurse -Force }
    Expand-Archive -Path $mysqlZip -DestinationPath $toolsDir -Force
    Remove-Item $mysqlZip -Force -ErrorAction SilentlyContinue

    $extractedMaria = Get-ChildItem $toolsDir -Directory | Where-Object { $_.Name -like "mariadb-10.6*" } | Select-Object -First 1
    if ($extractedMaria) {
        Rename-Item $extractedMaria.FullName $mysqlDir -Force
    }

    Write-Host "      Inicializando archivos de datos de la base de datos..." -ForegroundColor Yellow
    New-Item -ItemType Directory -Force -Path $mysqlData | Out-Null
    & (Join-Path $mysqlBin "mysql_install_db.exe") --datadir="$mysqlData" --password="root" | Out-Null
    Write-Host "[OK] MariaDB instalada e inicializada." -ForegroundColor Green
} else {
    Write-Host "[4/5] MariaDB ya esta instalada en $mysqlDir." -ForegroundColor Green
}

# -------------------------------------------------------------
# 5. Agregar herramientas al PATH del Usuario
# -------------------------------------------------------------
$userPath = [System.Environment]::GetEnvironmentVariable("Path", "User")
$pathsToAdd = @($mysqlBin, $nodeDir, $phpDir)
$modifiedPath = $false
foreach ($p in $pathsToAdd) {
    if ($userPath -notlike "*$p*") {
        $userPath = "$p;$userPath"
        $modifiedPath = $true
    }
}
if ($modifiedPath) {
    [System.Environment]::SetEnvironmentVariable("Path", $userPath, "User")
    Write-Host "[OK] Rutas añadidas permanentemente al PATH del usuario." -ForegroundColor Green
}
$env:Path = "$mysqlBin;$nodeDir;$phpDir;$env:Path"

# -------------------------------------------------------------
# 6. Iniciar MariaDB y configurar base de datos concursofm
# -------------------------------------------------------------
Write-Host ""
Write-Host "[5/5] Configurando base de datos local y migraciones..." -ForegroundColor Yellow

$dbListening = Test-NetConnection -ComputerName 127.0.0.1 -Port 3307 -WarningAction SilentlyContinue
if (-not $dbListening.TcpTestSucceeded) {
    Start-Process -FilePath (Join-Path $mysqlBin "mysqld.exe") -ArgumentList "--port=3307","--datadir=$mysqlData" -WindowStyle Hidden
    Start-Sleep -Seconds 4
}

# Crear BD y usuarios
& (Join-Path $mysqlBin "mysql.exe") -P 3307 -h 127.0.0.1 -u root -proot -e "CREATE DATABASE IF NOT EXISTS concursofm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>$null
& (Join-Path $mysqlBin "mysql.exe") -P 3307 -h 127.0.0.1 -u root -proot -e "CREATE USER IF NOT EXISTS 'concursofm'@'127.0.0.1' IDENTIFIED BY 'btHRD729'; GRANT ALL PRIVILEGES ON concursofm.* TO 'concursofm'@'127.0.0.1'; CREATE USER IF NOT EXISTS 'concursofm'@'localhost' IDENTIFIED BY 'btHRD729'; GRANT ALL PRIVILEGES ON concursofm.* TO 'concursofm'@'localhost'; FLUSH PRIVILEGES;" 2>$null

# Asegurar archivo .env
$envFile = Join-Path $PSScriptRoot ".env"
if (-not (Test-Path $envFile)) {
    Copy-Item (Join-Path $PSScriptRoot ".env.example") $envFile
    & (Join-Path $phpDir "php.exe") (Join-Path $PSScriptRoot "artisan") key:generate --force
}

# Asegurar DB_HOST=127.0.0.1 en .env
$envContent = Get-Content $envFile -Raw
if ($envContent -match "DB_HOST=localhost") {
    $envContent = $envContent -replace "DB_HOST=localhost", "DB_HOST=127.0.0.1"
    Set-Content $envFile $envContent
}

# Ejecutar migraciones y seeders
Write-Host "      Ejecutando migraciones y seeders de Laravel..." -ForegroundColor Yellow
Set-Location $PSScriptRoot
& (Join-Path $phpDir "php.exe") artisan migrate --force
& (Join-Path $phpDir "php.exe") artisan db:seed --force

Write-Host ""
Write-Host "========================================================" -ForegroundColor Green
Write-Host "   ¡INSTALACION Y CONFIGURACION COMPLETADAS CON EXITO!  " -ForegroundColor Green
Write-Host "========================================================" -ForegroundColor Green
Write-Host ""
Write-Host "Para arrancar el proyecto en cualquier momento:" -ForegroundColor Cyan
Write-Host "  -> Solo dale doble clic al archivo 'iniciar.bat'" -ForegroundColor Yellow
Write-Host ""
Write-Host "Presiona Enter para cerrar..."
Read-Host
