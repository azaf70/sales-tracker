# Docker Setup for Sales Tracker

This document provides instructions for running the Sales Tracker Laravel + Vue.js application using Docker.

## Prerequisites

- Docker Desktop (Windows/Mac) or Docker Engine (Linux)
- Docker Compose

## Quick Start

### Option 1: Automated Setup (Recommended)

Run the setup script to automatically configure everything:

```bash
chmod +x docker-setup.sh
./docker-setup.sh
```

### Option 2: Manual Setup

1. **Copy environment file:**
   ```bash
   cp docker/env.example .env
   ```

2. **Build and start containers:**
   ```bash
   docker-compose up -d --build
   ```

3. **Install dependencies:**
   ```bash
   # PHP dependencies
   docker-compose exec app composer install
   
   # Node.js dependencies
   docker-compose exec node npm install
   ```

4. **Generate application key:**
   ```bash
   docker-compose exec app php artisan key:generate
   ```

5. **Run database migrations:**
   ```bash
   docker-compose exec app php artisan migrate
   ```

6. **Build frontend assets:**
   ```bash
   docker-compose exec node npm run build
   ```

## Services

The Docker setup includes the following services:

- **app** (PHP-FPM 8.2): Laravel application backend
- **webserver** (Nginx): Web server for serving the application
- **db** (MySQL 8.0): Database server
- **redis** (Redis Alpine): Cache and session storage
- **node** (Node.js 18): Frontend build process

## Ports

- **8000**: Web application (http://localhost:8000)
- **3306**: MySQL database
- **6379**: Redis cache
- **5173**: Vite development server (internal)

## Environment Configuration

The Docker environment is configured in `docker/env.example`. Key settings:

- Database host: `db` (Docker service name)
- Redis host: `redis` (Docker service name)
- App URL: `http://localhost:8000`

## Development Workflow

### Starting the application:
```bash
docker-compose up -d
```

### Stopping the application:
```bash
docker-compose down
```

### Viewing logs:
```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f app
docker-compose logs -f webserver
docker-compose logs -f node
```

### Running Artisan commands:
```bash
docker-compose exec app php artisan [command]
```

### Running npm commands:
```bash
docker-compose exec node npm [command]
```

### Accessing containers:
```bash
# PHP container
docker-compose exec app bash

# Node container
docker-compose exec node sh

# Database container
docker-compose exec db mysql -u sales_tracker -p sales_tracker
```

## Frontend Development

For frontend development with hot reloading:

1. **Start the development server:**
   ```bash
   docker-compose exec node npm run dev
   ```

2. **Access the Vite dev server:**
   The Vite development server runs inside the container and is accessible through the Nginx proxy.

## Database Management

### Accessing MySQL:
```bash
docker-compose exec db mysql -u sales_tracker -p sales_tracker
```

### Running migrations:
```bash
docker-compose exec app php artisan migrate
```

### Seeding the database:
```bash
docker-compose exec app php artisan db:seed
```

### Resetting the database:
```bash
docker-compose exec app php artisan migrate:fresh --seed
```

## Troubleshooting

### Container won't start:
1. Check if ports are already in use:
   ```bash
   netstat -tulpn | grep :8000
   ```

2. Check Docker logs:
   ```bash
   docker-compose logs [service-name]
   ```

### Permission issues:
If you encounter permission issues with file ownership:

```bash
# Fix ownership
sudo chown -R $USER:$USER .

# Fix permissions
chmod -R 755 storage bootstrap/cache
```

### Database connection issues:
1. Ensure the database container is running:
   ```bash
   docker-compose ps
   ```

2. Check database logs:
   ```bash
   docker-compose logs db
   ```

3. Verify environment variables in `.env`

### Frontend build issues:
1. Clear node_modules and reinstall:
   ```bash
   docker-compose exec node rm -rf node_modules package-lock.json
   docker-compose exec node npm install
   ```

2. Clear Vite cache:
   ```bash
   docker-compose exec node npm run build
   ```

## Production Considerations

For production deployment:

1. Update environment variables for production
2. Use production-optimized Docker images
3. Configure proper SSL certificates
4. Set up proper backup strategies
5. Configure monitoring and logging
6. Use Docker secrets for sensitive data

## Useful Commands

```bash
# Rebuild containers after Dockerfile changes
docker-compose build --no-cache

# Restart a specific service
docker-compose restart app

# View resource usage
docker stats

# Clean up unused resources
docker system prune

# Backup database
docker-compose exec db mysqldump -u sales_tracker -p sales_tracker > backup.sql

# Restore database
docker-compose exec -T db mysql -u sales_tracker -p sales_tracker < backup.sql
``` 