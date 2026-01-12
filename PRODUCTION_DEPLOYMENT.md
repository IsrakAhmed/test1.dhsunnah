# 🚀 Production Deployment Guide

এই গাইডটি আপনার Laravel School Management System প্রজেক্ট production server-এ deploy করার জন্য।

## ⚠️ Important: Before Deployment

### 1. Environment Configuration

আপনার production server-এ `.env` file আপডেট করুন:

```bash
# Copy the production environment template
cp env_for_production.txt .env
```

**Important settings যা অবশ্যই আপডেট করতে হবে:**

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database credentials
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node dependencies
npm install

# Build production assets
npm run build
```

### 3. Set Permissions

```bash
# Set proper permissions for storage and cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Set owner (replace www-data with your web server user)
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

### 4. Run Database Migrations

```bash
php artisan migrate --force
```

## 🚄 Laravel Optimization Commands

Production-এ deploy করার পর **অবশ্যই** এই optimization commands রান করুন:

### Method 1: Using the Optimization Script (Recommended)

```bash
# Make the script executable (Linux/Mac)
chmod +x optimize.sh

# Run the optimization script
./optimize.sh
```

### Method 2: Manual Commands

```bash
# Clear all existing caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Generate optimized files
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Create symbolic link for storage
php artisan storage:link
```

## 📊 Performance Benefits

এই optimizations করার পর আপনি পাবেন:

- ✅ **40-60% faster response time** - Route এবং config caching এর জন্য
- ✅ **Reduced database load** - File-based sessions এবং cache ব্যবহার করে
- ✅ **Better memory management** - Pagination এর মাধ্যমে
- ✅ **Faster page rendering** - Deferred script loading

## 🔄 When to Re-optimize

নিচের কাজগুলো করার পর **পুনরায় optimize** করতে হবে:

- `.env` file পরিবর্তন করলে → `php artisan config:cache`
- Routes পরিবর্তন করলে → `php artisan route:cache`
- Views পরিবর্তন করলে → `php artisan view:cache`
- যেকোনো config file পরিবর্তন করলে → `php artisan config:cache`

## 🔒 Security Checklist

- [ ] `APP_DEBUG=false` সেট করা আছে
- [ ] `APP_ENV=production` সেট করা আছে
- [ ] Strong `APP_KEY` generate করা আছে
- [ ] Database credentials secure রাখা হয়েছে
- [ ] `.env` file github-এ commit করা হয়নি

## 🧪 Testing After Deployment

```bash
# Test database connection
php artisan migrate:status

# Test cache is working
php artisan tinker
>>> cache()->put('test', 'working', 60);
>>> cache()->get('test');
>>> exit

# Check optimized files exist
ls -la bootstrap/cache/
```

## 🆘 Troubleshooting

### Issue: "500 Internal Server Error"
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Clear all caches and re-optimize
php artisan optimize:clear
./optimize.sh
```

### Issue: "Permission Denied"
```bash
# Fix permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Issue: "Class not found"
```bash
# Regenerate autoload files
composer dump-autoload --optimize
```

## 📝 Production Maintenance

### Daily Tasks
- Monitor error logs: `tail -f storage/logs/laravel.log`
- Check disk space: `df -h`

### Weekly Tasks
- Update dependencies (if needed): `composer update`
- Backup database
- Check application performance

### After Code Updates
```bash
# Pull latest code
git pull origin main

# Install/update dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build

# Run migrations
php artisan migrate --force

# Re-optimize
./optimize.sh

# Restart services (if using queue workers)
php artisan queue:restart
```

## 🎯 Next Steps

1. Setup automated backups for your database
2. Configure SSL certificate (Let's Encrypt recommended)
3. Setup monitoring tools (e.g., Laravel Telescope for development)
4. Configure a CDN for static assets (optional)
5. Setup Redis for better cache performance (optional but recommended)

---

**Need Help?** Check Laravel documentation: https://laravel.com/docs/deployment
