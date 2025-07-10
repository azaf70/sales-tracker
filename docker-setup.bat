@echo off
echo 🚀 Setting up Sales Tracker with Docker...

REM Check if Docker is installed and running
docker --version >nul 2>&1
if errorlevel 1 (
    echo ❌ Docker is not installed. Please install Docker Desktop first.
    echo Download from: https://www.docker.com/products/docker-desktop/
    pause
    exit /b 1
)

REM Check if Docker is running
docker info >nul 2>&1
if errorlevel 1 (
    echo ❌ Docker Desktop is not running. Please start Docker Desktop first.
    echo Look for Docker Desktop in your system tray or Start menu.
    echo Wait for the whale icon to be steady (not animated) before continuing.
    pause
    exit /b 1
)

REM Check if Docker Compose is installed
docker-compose --version >nul 2>&1
if errorlevel 1 (
    echo ❌ Docker Compose is not installed. Please install Docker Compose first.
    pause
    exit /b 1
)

REM Copy environment file if it doesn't exist
if not exist .env (
    echo 📝 Creating .env file from docker/env.example...
    copy docker\env.example .env
    echo ✅ .env file created. Please review and update the configuration if needed.
) else (
    echo ✅ .env file already exists.
)

REM Build and start containers
echo 🔨 Building Docker containers...
docker-compose build

echo 🚀 Starting containers...
docker-compose up -d

REM Wait for containers to be ready
echo ⏳ Waiting for containers to be ready...
timeout /t 10 /nobreak >nul

REM Install PHP dependencies
echo 📦 Installing PHP dependencies...
docker-compose exec app composer install

REM Generate application key
echo 🔑 Generating application key...
docker-compose exec app php artisan key:generate

REM Run database migrations
echo 🗄️ Running database migrations...
docker-compose exec app php artisan migrate

REM Install Node.js dependencies
echo 📦 Installing Node.js dependencies...
docker-compose exec node npm install

REM Build frontend assets
echo 🎨 Building frontend assets...
docker-compose exec node npm run build

echo ✅ Setup complete!
echo.
echo 🌐 Your application is now running at: http://localhost:8000
echo 🗄️ Database is available at: localhost:3306
echo 🔴 Redis is available at: localhost:6379
echo.
echo 📋 Useful commands:
echo   - View logs: docker-compose logs -f
echo   - Stop containers: docker-compose down
echo   - Restart containers: docker-compose restart
echo   - Access app container: docker-compose exec app bash
echo   - Access node container: docker-compose exec node sh
echo.
pause 