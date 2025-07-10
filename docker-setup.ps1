# Docker Setup Script for Sales Tracker (PowerShell)

Write-Host "🚀 Setting up Sales Tracker with Docker..." -ForegroundColor Green

# Check if Docker is running
try {
    docker info | Out-Null
} catch {
    Write-Host "❌ Docker is not running. Please start Docker and try again." -ForegroundColor Red
    exit 1
}

# Create .env file if it doesn't exist
if (-not (Test-Path ".env")) {
    Write-Host "📝 Creating .env file..." -ForegroundColor Yellow
    Copy-Item ".env.example" ".env"
    Write-Host "✅ .env file created" -ForegroundColor Green
} else {
    Write-Host "✅ .env file already exists" -ForegroundColor Green
}

# Update .env file for Docker
Write-Host "🔧 Updating .env file for Docker..." -ForegroundColor Yellow
(Get-Content .env) -replace 'DB_HOST=127\.0\.0\.1', 'DB_HOST=db' | Set-Content .env
(Get-Content .env) -replace 'REDIS_HOST=127\.0\.0\.1', 'REDIS_HOST=redis' | Set-Content .env

# Fix permissions and create directories
Write-Host "🔧 Setting up directories and permissions..." -ForegroundColor Yellow
$directories = @(
    "storage/framework/cache",
    "storage/framework/sessions", 
    "storage/framework/views",
    "storage/logs",
    "bootstrap/cache"
)

foreach ($dir in $directories) {
    if (-not (Test-Path $dir)) {
        New-Item -ItemType Directory -Path $dir -Force | Out-Null
    }
}

# Build and start containers
Write-Host "🐳 Building and starting Docker containers..." -ForegroundColor Yellow
docker-compose up -d --build

# Wait for database to be ready
Write-Host "⏳ Waiting for database to be ready..." -ForegroundColor Yellow
Start-Sleep -Seconds 30

# Run Laravel setup commands
Write-Host "🔧 Setting up Laravel application..." -ForegroundColor Yellow
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan storage:link

Write-Host "✅ Setup complete!" -ForegroundColor Green
Write-Host ""
Write-Host "🌐 Your application is now running at: http://localhost:8000" -ForegroundColor Cyan
Write-Host "📊 Database is accessible at: localhost:3306" -ForegroundColor Cyan
Write-Host "🔴 Redis is accessible at: localhost:6379" -ForegroundColor Cyan
Write-Host "⚡ Vite dev server is running at: http://localhost:5173" -ForegroundColor Cyan
Write-Host ""
Write-Host "📝 Useful commands:" -ForegroundColor Yellow
Write-Host "  docker-compose up -d          # Start containers" -ForegroundColor White
Write-Host "  docker-compose down           # Stop containers" -ForegroundColor White
Write-Host "  docker-compose logs -f app    # View app logs" -ForegroundColor White
Write-Host "  docker-compose exec app bash  # Access app container" -ForegroundColor White 