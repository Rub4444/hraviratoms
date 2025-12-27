# Docker Setup for Laravel Application

This project includes Docker configuration for local development with PHP-FPM, Nginx, and MySQL.

## Prerequisites

- Docker Desktop (or Docker Engine + Docker Compose)
- Git

## Quick Start

1. **Start the Docker containers:**
   ```bash
   docker compose up --build -d
   ```

2. **Copy environment file (if not already done):**
   ```bash
   cp .env.example .env
   ```

3. **Generate application key:**
   ```bash
   docker compose exec app php artisan key:generate
   ```

4. **Run database migrations:**
   ```bash
   docker compose exec app php artisan migrate
   ```

5. **Access the application:**
   - Open your browser and navigate to: **http://localhost:8000**

## Docker Services

- **app**: PHP 8.2-FPM container running Laravel
- **nginx**: Web server serving the application
- **mysql**: MySQL 8.0 database server

## Common Commands

### Artisan Commands

Run any Laravel artisan command inside the app container:

```bash
# Run migrations
docker compose exec app php artisan migrate

# Run migrations with seeding
docker compose exec app php artisan migrate --seed

# Clear cache
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
docker compose exec app php artisan view:clear

# Tinker
docker compose exec app php artisan tinker

# Create a new controller
docker compose exec app php artisan make:controller MyController
```

### Composer Commands

```bash
# Install dependencies
docker compose exec app composer install

# Update dependencies
docker compose exec app composer update

# Add a new package
docker compose exec app composer require vendor/package
```

### NPM/Vite Commands

If you need to build assets, you can run npm commands:

```bash
# Install npm dependencies (run on host machine)
npm install

# Build assets (run on host machine)
npm run build

# Watch for changes (run on host machine)
npm run dev
```

### Database Access

Connect to MySQL from your host machine:

- **Host**: `localhost`
- **Port**: `3306`
- **Database**: `laravel`
- **Username**: `laravel`
- **Password**: `secret`
- **Root Password**: `rootsecret`

You can also access MySQL directly from the container:

```bash
docker compose exec mysql mysql -u laravel -psecret laravel
```

### Container Management

```bash
# Start containers
docker compose up -d

# Stop containers
docker compose down

# View logs
docker compose logs -f

# View logs for specific service
docker compose logs -f app
docker compose logs -f nginx
docker compose logs -f mysql

# Rebuild containers (after Dockerfile changes)
docker compose up --build -d

# Stop and remove containers, volumes, and networks
docker compose down -v
```

## Environment Variables

Make sure your `.env` file has the following database configuration:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret
```

## Troubleshooting

### Permission Issues

If you encounter permission issues with storage or cache directories:

```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### Container Not Starting

Check the logs:
```bash
docker compose logs app
docker compose logs nginx
docker compose logs mysql
```

### Database Connection Issues

Ensure the MySQL container is running:
```bash
docker compose ps
```

Wait a few seconds after starting containers for MySQL to initialize, then try again.

### Clear Everything and Start Fresh

```bash
docker compose down -v
docker compose up --build -d
```

## Development Workflow

1. Make changes to your code (files are mounted as volumes, so changes are reflected immediately)
2. For PHP changes, no restart needed
3. For config changes, clear cache: `docker compose exec app php artisan config:clear`
4. For asset changes, rebuild with `npm run build` or `npm run dev`

## Notes

- The `vendor` folder is installed inside the container, not on your host machine
- Database data persists in a Docker volume named `mysql_data`
- All application files are mounted as volumes for live development
- The application runs on port 8000 by default (change in `docker-compose.yml` if needed)


