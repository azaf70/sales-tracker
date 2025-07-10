# Docker Setup for Sales Tracker

This project has been containerized with Docker for easy development and deployment.

## Prerequisites

- Docker Desktop installed and running
- Docker Compose (usually included with Docker Desktop)

## Quick Start

### Option 1: Automated Setup (Recommended)

**For Linux/macOS:**
```bash
chmod +x docker-setup.sh
./docker-setup.sh
```

**For Windows PowerShell:**
```powershell
.\docker-setup.ps1
```

### Option 2: Manual Setup

1. **Create environment file:**
   ```bash
   cp .env.example .env
   ```

2. **Update .env for Docker:**
   - Change `DB_HOST=127.0.0.1` to `DB_HOST=db`
   - Change `REDIS_HOST=127.0.0.1` to `REDIS_HOST=redis`

3. **Build and start containers:**
   ```bash
   docker-compose up -d --build
   ```

4. **Run Laravel setup:**
   ```bash
   docker-compose exec app php artisan key:generate
   docker-compose exec app php artisan migrate --force
   docker-compose exec app php artisan storage:link
   ```

## Services

The Docker setup includes the following services:

- **app** (PHP 8.2 + Laravel): Main application container
- **nginx**: Web server (port 8000)
- **db** (MySQL 8.0): Database (port 3306)
- **redis**: Cache and session storage (port 6379)
- **node**: Vite development server (port 5173)

## Access Points

- **Application**: http://localhost:8000
- **Vite Dev Server**: http://localhost:5173
- **Database**: localhost:3306
- **Redis**: localhost:6379

## Useful Commands

### Container Management
```bash
# Start all services
docker-compose up -d

# Stop all services
docker-compose down

# View logs
docker-compose logs -f app
docker-compose logs -f nginx
docker-compose logs -f db

# Rebuild containers
docker-compose up -d --build
```

### Laravel Commands
```bash
# Access app container
docker-compose exec app bash

# Run artisan commands
docker-compose exec app php artisan migrate
docker-compose exec app php artisan make:controller ExampleController
docker-compose exec app php artisan tinker

# Run tests
docker-compose exec app php artisan test
```

### Node.js Commands
```bash
# Access node container
docker-compose exec node sh

# Install new npm packages
docker-compose exec node npm install package-name

# Build assets
docker-compose exec node npm run build
```

### Database Commands
```bash
# Access MySQL
docker-compose exec db mysql -u root -p

# Import database dump
docker-compose exec -T db mysql -u root -p database_name < dump.sql

# Export database
docker-compose exec db mysqldump -u root -p database_name > dump.sql
```

## Development Workflow

1. **Start development environment:**
   ```bash
   docker-compose up -d
   ```

2. **Make code changes** - files are mounted as volumes, so changes are reflected immediately

3. **For frontend changes** - Vite will hot-reload automatically

4. **For backend changes** - PHP-FPM will serve updated files immediately

5. **Database changes** - run migrations as needed:
   ```bash
   docker-compose exec app php artisan migrate
   ```

## Production Considerations

For production deployment:

1. **Update environment variables** in `.env`:
   - Set `APP_ENV=production`
   - Set `APP_DEBUG=false`
   - Configure production database credentials
   - Set secure `APP_KEY`

2. **Build production assets:**
   ```bash
   docker-compose exec node npm run build
   ```

3. **Optimize Laravel:**
   ```bash
   docker-compose exec app php artisan config:cache
   docker-compose exec app php artisan route:cache
   docker-compose exec app php artisan view:cache
   ```

## Troubleshooting

### Common Issues

1. **Port conflicts**: If ports 8000, 3306, 6379, or 5173 are in use, modify the ports in `docker-compose.yml`

2. **Permission issues**: Ensure Docker has proper permissions to access the project directory

3. **Database connection**: Wait for MySQL to fully start (usually 30-60 seconds) before running migrations

4. **Memory issues**: Increase Docker memory allocation in Docker Desktop settings

### Reset Everything
```bash
# Stop and remove all containers, networks, and volumes
docker-compose down -v

# Remove all images
docker-compose down --rmi all

# Start fresh
docker-compose up -d --build
```

## File Structure

```
docker/
├── nginx/
│   └── conf.d/
│       └── app.conf          # Nginx configuration
├── php/
│   └── local.ini            # PHP configuration
├── mysql/
│   └── my.cnf               # MySQL configuration
└── supervisor/
    └── supervisord.conf     # Process management
```

## Environment Variables

Key environment variables for Docker:

- `DB_HOST=db` - Database host (Docker service name)
- `REDIS_HOST=redis` - Redis host (Docker service name)
- `APP_URL=http://localhost:8000` - Application URL
- `QUEUE_CONNECTION=redis` - Queue driver
- `SESSION_DRIVER=redis` - Session driver
- `CACHE_DRIVER=redis` - Cache driver 