#!/bin/bash

echo "🚀 Setting up Sales Tracker with Docker..."

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "❌ Docker is not installed. Please install Docker first."
    exit 1
fi

# Check if Docker Compose is installed
if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose is not installed. Please install Docker Compose first."
    exit 1
fi

# Copy environment file if it doesn't exist
if [ ! -f .env ]; then
    echo "📝 Creating .env file from docker/env.example..."
    cp docker/env.example .env
    echo "✅ .env file created. Please review and update the configuration if needed."
else
    echo "✅ .env file already exists."
fi

# Build and start containers
echo "🔨 Building Docker containers..."
docker-compose build

echo "🚀 Starting containers..."
docker-compose up -d

# Wait for containers to be ready
echo "⏳ Waiting for containers to be ready..."
sleep 10

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
docker-compose exec app composer install

# Generate application key
echo "🔑 Generating application key..."
docker-compose exec app php artisan key:generate

# Run database migrations
echo "🗄️ Running database migrations..."
docker-compose exec app php artisan migrate

# Install Node.js dependencies
echo "📦 Installing Node.js dependencies..."
docker-compose exec node npm install

# Build frontend assets
echo "🎨 Building frontend assets..."
docker-compose exec node npm run build

echo "✅ Setup complete!"
echo ""
echo "🌐 Your application is now running at: http://localhost:8000"
echo "🗄️ Database is available at: localhost:3306"
echo "🔴 Redis is available at: localhost:6379"
echo ""
echo "📋 Useful commands:"
echo "  - View logs: docker-compose logs -f"
echo "  - Stop containers: docker-compose down"
echo "  - Restart containers: docker-compose restart"
echo "  - Access app container: docker-compose exec app bash"
echo "  - Access node container: docker-compose exec node sh"
echo "" 