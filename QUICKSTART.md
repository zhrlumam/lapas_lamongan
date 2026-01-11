### ⚠️ PENTING: Folder 'bootstrap/' vs Framework 'Bootstrap'
Senior, jangan hapus folder `bootstrap/` di root proyek ya! 
- Folder **`bootstrap/`** ini adalah jantung sistem Laravel untuk proses "booting" aplikasi.
- Ini **BUKAN** framework CSS Bootstrap. Kita tetap pakai **Tailwind CSS** yang sudah Senior buat.

### First Time Setup:
1. **Pastikan MySQL di XAMPP sudah START/RUNNING** 🟢
2. **Install dependencies**
   ```bash
   composer update
   ```
3. **Setup environment**
   ```bash
   copy .env.example .env
   ```
4. **Generate key**
   ```bash
   php artisan key:generate
   ```

# 4. Create database 'lapas_lamongan' di phpMyAdmin

# 5. Run migrations
php artisan migrate

# 6. Create storage link
php artisan storage:link

# 7. Start server
php artisan serve
```

🌐 Open: http://127.0.0.1:8000

---

## Production Deployment

### On Server (via SSH):
```bash
# 1. Upload all files except vendor/ and _backup_native/

# 2. Install dependencies
composer install --optimize-autoloader --no-dev

# 3. Setup .env with production settings
# Edit .env manually with nano or vim

# 4. Generate key
php artisan key:generate

# 5. Run migrations
php artisan migrate --force

# 6. Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Set permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## Useful Commands

```bash
# Clear all caches
php artisan optimize:clear

# View routes
php artisan route:list

# Check application status
php artisan about

# Generate new migration
php artisan make:migration create_table_name

# Generate new controller
php artisan make:controller ControllerName

# Generate new model
php artisan make:model ModelName
```

---

**Document Root di cPanel/Hosting harus ke: `/public_html/public`**
