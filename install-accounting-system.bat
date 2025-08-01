@echo off
echo 🚀 Starting Accounting System Installation...
echo ================================================

REM Check if we're in a Laravel project
if not exist "artisan" (
    echo ❌ Error: This doesn't appear to be a Laravel project root directory.
    echo Please run this script from your Laravel project root.
    pause
    exit /b 1
)

echo ✅ Laravel project detected

REM Install NPM dependencies
echo 📦 Installing NPM dependencies...
call npm install chart.js chartjs-adapter-date-fns date-fns
if errorlevel 1 (
    echo ❌ Failed to install NPM dependencies
    pause
    exit /b 1
)

REM Run database migrations
echo 🗄️ Running database migrations...
call php artisan migrate
if errorlevel 1 (
    echo ❌ Failed to run migrations
    pause
    exit /b 1
)

REM Run the accounting seeder
echo 🌱 Seeding accounting data...
call php artisan db:seed --class=AccountingSeeder
if errorlevel 1 (
    echo ❌ Failed to seed data
    pause
    exit /b 1
)

REM Build frontend assets
echo 🎨 Building frontend assets...
call npm run build
if errorlevel 1 (
    echo ❌ Failed to build assets
    pause
    exit /b 1
)

REM Clear application cache
echo 🧹 Clearing application cache...
call php artisan cache:clear
call php artisan config:clear
call php artisan route:clear
call php artisan view:clear

REM Create storage symlink if it doesn't exist
echo 🔗 Creating storage symlink...
call php artisan storage:link

echo.
echo 🎉 Accounting System Installation Complete!
echo ================================================
echo.
echo ✅ All migrations have been run
echo ✅ Sample data has been seeded
echo ✅ Frontend assets have been built
echo ✅ Cache has been cleared
echo.
echo 🌐 You can now access your accounting system at:
echo    👉 Main Dashboard: /admin/accounting
echo    👉 Invoices: /admin/invoices
echo    👉 Expenses: /admin/expenses
echo    👉 Payments: /admin/payments
echo    👉 Budgets: /admin/budgets
echo.
echo 📊 Features Available:
echo    • Financial Dashboard with Charts
echo    • Invoice Management
echo    • Expense Tracking with Approvals
echo    • Payment Processing
echo    • Budget Planning
echo    • Financial Reporting
echo    • Chart of Accounts
echo    • Double-Entry Transactions
echo.
echo 🔧 If you encounter any issues, check:
echo    • Database connection is working
echo    • Web server has write permissions to storage/
echo    • All dependencies are installed
echo.
echo Happy accounting! 💰
pause
