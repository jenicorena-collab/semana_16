$ErrorActionPreference = 'Stop'
$Root = Split-Path -Parent $MyInvocation.MyCommand.Path
$Project = Join-Path $Root 'taskboard_semana16'
$Overlay = Join-Path $Root 'overlay'

Write-Host '=== Instalador Semana 16 - Laravel 11 + Sanctum ===' -ForegroundColor Cyan
if (-not (Get-Command php -ErrorAction SilentlyContinue)) { throw 'PHP no está en PATH. Instala PHP 8.2+ y vuelve a intentarlo.' }
if (-not (Get-Command composer -ErrorAction SilentlyContinue)) { throw 'Composer no está en PATH. Instala Composer y vuelve a intentarlo.' }
if (Test-Path $Project) { throw "Ya existe $Project. Renombra o elimina esa carpeta antes de instalar." }

composer create-project laravel/laravel $Project '^11.0'
if ($LASTEXITCODE -ne 0) { throw 'Falló composer create-project.' }
Push-Location $Project
try {
    composer require laravel/sanctum '^4.0'
    if ($LASTEXITCODE -ne 0) { throw 'Falló la instalación de Sanctum.' }
    php artisan install:api --no-interaction
    if ($LASTEXITCODE -ne 0) { throw 'Falló php artisan install:api.' }

    # Copiar archivos de la práctica sobre el esqueleto recién creado.
    Copy-Item (Join-Path $Overlay 'app\*') (Join-Path $Project 'app') -Recurse -Force
    Copy-Item (Join-Path $Overlay 'routes\api.php') (Join-Path $Project 'routes\api.php') -Force
    Copy-Item (Join-Path $Overlay 'database\migrations\*') (Join-Path $Project 'database\migrations') -Force
    Copy-Item (Join-Path $Overlay 'database\seeders\DatabaseSeeder.php') (Join-Path $Project 'database\seeders\DatabaseSeeder.php') -Force
    Copy-Item (Join-Path $Overlay 'tests\Feature\*') (Join-Path $Project 'tests\Feature') -Recurse -Force
    Copy-Item (Join-Path $Root 'CAMBIOS.md') (Join-Path $Project 'CAMBIOS.md') -Force
    Copy-Item (Join-Path $Root 'README.md') (Join-Path $Project 'README_SEMANA16.md') -Force
    Copy-Item (Join-Path $Root 'docs') (Join-Path $Project 'docs_semana16') -Recurse -Force

    # SQLite local para evitar configurar un servidor de base de datos.
    New-Item -ItemType File -Path (Join-Path $Project 'database\database.sqlite') -Force | Out-Null
    $envLines = Get-Content '.env'
    $envLines = $envLines -replace '^DB_CONNECTION=.*$', 'DB_CONNECTION=sqlite'
    $envLines = $envLines -replace '^DB_DATABASE=.*$', 'DB_DATABASE=database/database.sqlite'
    $envLines | Set-Content '.env'
    php artisan config:clear
    php artisan migrate:fresh --seed
    if ($LASTEXITCODE -ne 0) { throw 'Falló migrate:fresh --seed.' }
    Write-Host ''
    Write-Host 'Instalación terminada.' -ForegroundColor Green
    Write-Host "Proyecto: $Project"
    Write-Host 'Inicia con: php artisan serve'
    Write-Host 'Abre http://127.0.0.1:8000/api'
} finally { Pop-Location }
