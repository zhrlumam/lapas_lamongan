# 📁 PANDUAN FILE PERMISSIONS - PRODUCTION SERVER

## 🔐 PERMISSION STRUCTURE

```
755 = rwxr-xr-x (Owner: read+write+execute, Group: read+execute, Others: read+execute)
644 = rw-r--r-- (Owner: read+write, Group: read, Others: read)
775 = rwxrwxr-x (Owner: read+write+execute, Group: read+write+execute, Others: read+execute)
600 = rw------- (Owner: read+write, Group: none, Others: none)
```

---

## 📋 PERMISSION CHECKLIST

### **Root Directory**
```bash
/var/www/lapas_lamongan/
├── 755 (directories)
└── 644 (files)
```

### **Storage Directory (MUST BE WRITABLE)**
```bash
storage/
├── 775 storage/
├── 775 storage/app/
├── 775 storage/app/public/
├── 775 storage/framework/
├── 775 storage/framework/cache/
├── 775 storage/framework/sessions/
├── 775 storage/framework/views/
└── 775 storage/logs/
```

### **Bootstrap Cache (MUST BE WRITABLE)**
```bash
bootstrap/
└── 775 bootstrap/cache/
```

### **Public Directory**
```bash
public/
├── 755 public/
├── 644 public/index.php
├── 644 public/.htaccess
├── 775 public/uploads/
├── 775 public/bukti_pengaduan/
└── 775 public/galeri/
```

### **Sensitive Files (RESTRICTED)**
```bash
600 .env
644 .env.example
644 composer.json
644 composer.lock
644 artisan
```

### **Configuration Files**
```bash
644 config/*.php
644 routes/*.php
644 app/**/*.php
```

---

## 🚀 QUICK SETUP COMMANDS

### **For Linux/Ubuntu Server:**

```bash
# Navigate to project directory
cd /var/www/lapas_lamongan

# Set ownership to web server user
sudo chown -R www-data:www-data .

# Set default permissions for directories
sudo find . -type d -exec chmod 755 {} \;

# Set default permissions for files
sudo find . -type f -exec chmod 644 {} \;

# Set writable permissions for storage
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache

# Set writable permissions for public upload directories
sudo chmod -R 775 public/uploads
sudo chmod -R 775 public/bukti_pengaduan
sudo chmod -R 775 public/galeri

# Protect .env file
sudo chmod 600 .env

# Make artisan executable
sudo chmod 755 artisan

# Verify permissions
ls -la
```

### **For Shared Hosting (cPanel):**

```bash
# Via SSH or File Manager:
# Directories: 755
# Files: 644
# storage/: 775
# bootstrap/cache/: 775
# .env: 600
```

---

## ⚠️ COMMON PERMISSION ERRORS

### **Error: "Permission denied" saat upload file**
```bash
# Solution:
sudo chmod -R 775 storage/app/public
sudo chmod -R 775 public/uploads
sudo chown -R www-data:www-data storage public
```

### **Error: "Failed to open stream" di storage/logs**
```bash
# Solution:
sudo chmod -R 775 storage/logs
sudo chown -R www-data:www-data storage/logs
```

### **Error: "Unable to create cache file"**
```bash
# Solution:
sudo chmod -R 775 bootstrap/cache
sudo chown -R www-data:www-data bootstrap/cache
```

### **Error: "The stream or file could not be opened"**
```bash
# Solution:
sudo chmod -R 775 storage
sudo chown -R www-data:www-data storage
php artisan cache:clear
```

---

## 🔍 VERIFICATION SCRIPT

Save as `check_permissions.sh`:

```bash
#!/bin/bash

echo "=== Checking Laravel Permissions ==="
echo ""

# Check storage
echo "Storage directory:"
ls -ld storage
ls -ld storage/logs
ls -ld storage/framework/cache

echo ""
echo "Bootstrap cache:"
ls -ld bootstrap/cache

echo ""
echo "Public uploads:"
ls -ld public/uploads 2>/dev/null || echo "public/uploads not found"
ls -ld public/bukti_pengaduan 2>/dev/null || echo "public/bukti_pengaduan not found"

echo ""
echo ".env file:"
ls -l .env

echo ""
echo "=== Permission Check Complete ==="
```

Run with:
```bash
chmod +x check_permissions.sh
./check_permissions.sh
```

---

## 📌 SECURITY NOTES

1. **NEVER** set 777 permissions in production
2. **ALWAYS** use 600 for .env file
3. **ONLY** storage and bootstrap/cache need 775
4. **VERIFY** ownership is set to web server user (www-data, apache, nginx)
5. **TEST** file upload after setting permissions

---

## 🆘 EMERGENCY FIX

If website is completely broken due to permissions:

```bash
cd /var/www/lapas_lamongan
sudo chown -R www-data:www-data .
sudo chmod -R 755 .
sudo chmod -R 775 storage bootstrap/cache
sudo chmod 600 .env
php artisan cache:clear
php artisan config:clear
```

---

**Last Updated:** 9 Januari 2026  
**For:** Lapas Kelas IIB Lamongan Production Server
