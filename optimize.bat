@echo off
REM Laravel Production Optimization Script for Windows
REM This script runs all necessary Laravel optimization commands

echo.
echo ========================================
echo    Laravel Production Optimization
echo ========================================
echo.

REM Step 1: Clear all caches
echo [1/6] Clearing all caches...
call php artisan config:clear
call php artisan route:clear
call php artisan view:clear
call php artisan cache:clear
echo [OK] Caches cleared!
echo.

REM Step 2: Cache config
echo [2/6] Caching configuration...
call php artisan config:cache
echo [OK] Configuration cached!
echo.

REM Step 3: Cache routes
echo [3/6] Caching routes...
call php artisan route:cache
echo [OK] Routes cached!
echo.

REM Step 4: Cache views
echo [4/6] Caching views...
call php artisan view:cache
echo [OK] Views cached!
echo.

REM Step 5: Run optimize command
echo [5/6] Running optimization...
call php artisan optimize
echo [OK] Optimization complete!
echo.

REM Step 6: Create storage link if not exists
echo [6/6] Creating storage symlink...
call php artisan storage:link 2>nul
echo [OK] Storage link ready!
echo.

echo ========================================
echo    Optimization Complete!
echo ========================================
echo.
echo Your Laravel application is now optimized for production.
echo.
echo Performance improvements:
echo   * Config files cached
echo   * Routes cached
echo   * Views pre-compiled
echo   * Application optimized
echo.

REM Check if cache files were created
echo Checking optimized files...
if exist "bootstrap\cache\config.php" (
    echo   [OK] Config cache
) else (
    echo   [X] Config cache MISSING
)

if exist "bootstrap\cache\routes-v7.php" (
    echo   [OK] Route cache
) else (
    echo   [X] Route cache MISSING
)

if exist "storage\framework\views" (
    echo   [OK] View cache directory
) else (
    echo   [X] View cache MISSING
)

echo.
echo All done! Your application should now be significantly faster.
echo.
pause
