#!/bin/bash

# Laravel Production Optimization Script
# This script runs all necessary Laravel optimization commands

echo "🚀 Starting Laravel Optimization..."
echo ""

# Step 1: Clear all caches
echo "📦 Step 1/6: Clearing all caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
echo "✅ Caches cleared!"
echo ""

# Step 2: Cache config
echo "⚙️  Step 2/6: Caching configuration..."
php artisan config:cache
echo "✅ Configuration cached!"
echo ""

# Step 3: Cache routes
echo "🛣️  Step 3/6: Caching routes..."
php artisan route:cache
echo "✅ Routes cached!"
echo ""

# Step 4: Cache views
echo "👁️  Step 4/6: Caching views..."
php artisan view:cache
echo "✅ Views cached!"
echo ""

# Step 5: Run optimize command
echo "⚡ Step 5/6: Running optimization..."
php artisan optimize
echo "✅ Optimization complete!"
echo ""

# Step 6: Create storage link if not exists
echo "🔗 Step 6/6: Creating storage symlink..."
php artisan storage:link 2>/dev/null || echo "Storage link already exists"
echo "✅ Storage link ready!"
echo ""

echo "🎉 Optimization Complete!"
echo ""
echo "Your Laravel application is now optimized for production."
echo "Performance improvements:"
echo "  ✓ Config files cached"
echo "  ✓ Routes cached"
echo "  ✓ Views pre-compiled"
echo "  ✓ Application optimized"
echo ""
echo "📊 Checking optimized files..."

# Check if cache files were created
if [ -f "bootstrap/cache/config.php" ]; then
    echo "  ✓ Config cache: OK"
else
    echo "  ✗ Config cache: MISSING"
fi

if [ -f "bootstrap/cache/routes-v7.php" ]; then
    echo "  ✓ Route cache: OK"
else
    echo "  ✗ Route cache: MISSING"
fi

if [ -d "storage/framework/views" ]; then
    view_count=$(find storage/framework/views -name "*.php" | wc -l)
    echo "  ✓ View cache: $view_count files compiled"
else
    echo "  ✗ View cache: MISSING"
fi

echo ""
echo "✨ All done! Your application should now be significantly faster."
