# CW KNUST Attendance System - Docker Setup

This guide will help you set up and run the CW KNUST Attendance System using Docker Desktop.

## Prerequisites

1. **Docker Desktop** - Make sure Docker Desktop is installed and running
2. **Git** - For cloning the repository (if not already done)

## Quick Start

### Option 1: Using the Setup Script (Recommended)

#### For Windows:
1. Double-click `setup.bat` or run it from Command Prompt
2. Wait for the setup to complete
3. Open your browser and go to `http://localhost:8000`

#### For Linux/Mac:
1. Open terminal in the project directory
2. Run: `chmod +x setup.sh && ./setup.sh`
3. Wait for the setup to complete
4. Open your browser and go to `http://localhost:8000`

### Option 2: Manual Setup

1. **Create .env file** (if not exists):
   ```bash
   # Copy the .env.example content and create .env file
   # Or run the setup script which will create it automatically
   ```

2. **Build and start containers**:
   ```bash
   docker-compose up --build -d
   ```

3. **Wait for MySQL to be ready** (about 30 seconds)

4. **Generate application key**:
   ```bash
   docker-compose exec app php artisan key:generate
   ```

5. **Run migrations and seeders**:
   ```bash
   docker-compose exec app php artisan migrate --force
   docker-compose exec app php artisan db:seed --force
   ```

6. **Clear caches**:
   ```bash
   docker-compose exec app php artisan config:clear
   docker-compose exec app php artisan cache:clear
   docker-compose exec app php artisan view:clear
   ```

## Accessing the Application

- **Main Application**: http://localhost:8000
- **phpMyAdmin**: http://localhost:8080
  - Username: `root`
  - Password: `secret`

## Default Login Credentials

- **Email**: admin@example.com
- **Password**: password

## Docker Services

The application consists of three main services:

1. **app** - Laravel PHP application (Port 8000)
2. **mysql** - MySQL database (Port 3306)
3. **phpmyadmin** - Database management interface (Port 8080)

## Useful Commands

### Start/Stop Services
```bash
# Start all services
docker-compose up -d

# Stop all services
docker-compose down

# View logs
docker-compose logs -f app

# Rebuild containers
docker-compose up --build -d
```

### Access Container Shell
```bash
# Access app container
docker-compose exec app bash

# Access MySQL container
docker-compose exec mysql mysql -u root -p
```

### Laravel Commands
```bash
# Run migrations
docker-compose exec app php artisan migrate

# Run seeders
docker-compose exec app php artisan db:seed

# Clear caches
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan view:clear

# Generate application key
docker-compose exec app php artisan key:generate
```

## Troubleshooting

### Common Issues

1. **Port already in use**:
   - Stop other services using ports 8000, 8080, or 3306
   - Or modify the ports in `docker-compose.yml`

2. **Permission denied**:
   - Make sure Docker Desktop has access to the project directory
   - Run the setup script as administrator (Windows)

3. **Database connection failed**:
   - Wait for MySQL to fully start (about 30 seconds)
   - Check if the database credentials match in `.env`

4. **Application key not set**:
   - Run: `docker-compose exec app php artisan key:generate`

### Viewing Logs
```bash
# View app logs
docker-compose logs -f app

# View MySQL logs
docker-compose logs -f mysql

# View all logs
docker-compose logs -f
```

### Resetting Everything
```bash
# Stop and remove all containers, networks, and volumes
docker-compose down -v

# Remove all images
docker system prune -a

# Start fresh
./setup.bat  # or ./setup.sh
```

## Development

### Making Changes
1. Edit files in your local directory
2. Changes are automatically reflected due to volume mounting
3. Clear caches if needed: `docker-compose exec app php artisan config:clear`

### Adding New Dependencies
1. Edit `composer.json` or `package.json`
2. Rebuild the container: `docker-compose up --build -d`

## Production Considerations

For production deployment:
1. Change `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false`
3. Use proper database credentials
4. Configure proper mail settings
5. Set up SSL certificates
6. Use a production web server (nginx/apache) instead of Laravel's built-in server

## Support

If you encounter any issues:
1. Check the logs: `docker-compose logs -f app`
2. Ensure Docker Desktop is running
3. Verify all ports are available
4. Check the troubleshooting section above 