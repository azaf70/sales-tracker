#!/bin/bash

# Docker Setup Script for Sales Tracker

echo "🚀 Setting up Sales Tracker with Docker..."

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker and try again."
    exit 1
fi

# Create .env file if it doesn't exist
if [ ! -f .env ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env
    echo "✅ .env file created"
else
    echo "✅ .env file already exists"
fi

# Update .env file for Docker
echo "🔧 Updating .env file for Docker..."
sed -i 's/DB_HOST=127.0.0.1/DB_HOST=db/g' .env
sed -i 's/REDIS_HOST=127.0.0.1/REDIS_HOST=redis/g' .env

# Build and start containers
echo "🐳 Building and starting Docker containers..."
docker-compose up -d --build

# Wait for database to be ready
echo "⏳ Waiting for database to be ready..."
sleep 30

# Run Laravel setup commands
echo "🔧 Setting up Laravel application..."
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan storage:link

echo "✅ Setup complete!"
echo ""
echo "🌐 Your application is now running at: http://localhost:8000"
echo "📊 Database is accessible at: localhost:3306"
echo "🔴 Redis is accessible at: localhost:6379"
echo "⚡ Vite dev server is running at: http://localhost:5173"
echo ""
echo "📝 Useful commands:"
echo "  docker-compose up -d          # Start containers"
echo "  docker-compose down           # Stop containers"
echo "  docker-compose logs -f app    # View app logs"
echo "  docker-compose exec app bash  # Access app container" 