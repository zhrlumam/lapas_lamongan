# Laravel Lapas Lamongan - Deployment Guide

## 🚀 Pre-Deployment Checklist

### 1. Environment Configuration
```bash
cp .env.example .env
```

Edit `.env` file dengan konfigurasi production:
```env
APP_NAME="Lapas Lamongan"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://lapaslamongan.go.id

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=lapas_lamongan
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password
```

### 2. Install Dependencies
```bash
composer install --optimize-autoloader --no-dev
```

### 3. Generate Application Key
```bash
php artisan key:generate --force
```

### 4. Run Database Migrations
```bash
php artisan migrate --force
```

### 5. Optimize for Production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 6. Set Proper Permissions
```bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### 7. Configure Web Server

#### Apache (.htaccess)
Document root harus mengarah ke folder `public/`:
```apache
DocumentRoot /var/www/lapas-lamongan/public
```

#### Nginx
```nginx
server {
    listen 80;
    server_name lapaslamongan.go.id www.lapaslamongan.go.id;
    root /var/www/lapas-lamongan/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## 🔒 Security Hardening

1. **Disable Directory Listing**: Sudah diatur di `.htaccess`
2. **Hide Laravel Version**: File sudah bersih dari identifier
3. **CSRF Protection**: Aktif otomatis di semua form
4. **SQL Injection Protection**: Menggunakan Eloquent ORM
5. **XSS Protection**: Blade templating auto-escape output

## 📊 Monitoring & Maintenance

### Health Check Endpoint
```
GET /health
```

Response:
```json
{
    "status": "healthy",
    "timestamp": "2026-01-07 22:00:00",
    "app": "Lapas Lamongan"
}
```

### Log Monitoring
```bash
tail -f storage/logs/laravel.log
```

### Database Backup (Cron Job)
```bash
0 2 * * * mysqldump -u user -p lapas_lamongan > /backups/lapas_$(date +\%F).sql
```

## 🆘 Troubleshooting

### Clear All Caches
```bash
php artisan optimize:clear
```

### Permission Issues
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Database Connection Error
Periksa kredensial di file `.env` dan pastikan service MySQL aktif.

---
**Website ini siap untuk skala nasional dengan keamanan tingkat enterprise.**
