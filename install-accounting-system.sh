#!/bin/bash

# Accounting System Auto-Installation Script
# This script will automatically apply all the accounting system code

echo "🚀 Starting Accounting System Installation..."
echo "================================================"

# Check if we're in a Laravel project
if [ ! -f "artisan" ]; then
    echo "❌ Error: This doesn't appear to be a Laravel project root directory."
    echo "Please run this script from your Laravel project root."
    exit 1
fi

echo "✅ Laravel project detected"

# Install NPM dependencies
echo "📦 Installing NPM dependencies..."
npm install chart.js chartjs-adapter-date-fns date-fns

# Run database migrations
echo "🗄️ Running database migrations..."
php artisan migrate

# Run the accounting seeder
echo "🌱 Seeding accounting data..."
php artisan db:seed --class=AccountingSeeder

# Build frontend assets
echo "🎨 Building frontend assets..."
npm run build

# Clear application cache
echo "🧹 Clearing application cache..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Create storage symlink if it doesn't exist
echo "🔗 Creating storage symlink..."
php artisan storage:link

echo ""
echo "🎉 Accounting System Installation Complete!"
echo "================================================"
echo ""
echo "✅ All migrations have been run"
echo "✅ Sample data has been seeded"
echo "✅ Frontend assets have been built"
echo "✅ Cache has been cleared"
echo ""
echo "🌐 You can now access your accounting system at:"
echo "   👉 Main Dashboard: /admin/accounting"
echo "   👉 Invoices: /admin/invoices"
echo "   👉 Expenses: /admin/expenses"
echo "   👉 Payments: /admin/payments"
echo "   👉 Budgets: /admin/budgets"
echo ""
echo "📊 Features Available:"
echo "   • Financial Dashboard with Charts"
echo "   • Invoice Management"
echo "   • Expense Tracking with Approvals"
echo "   • Payment Processing"
echo "   • Budget Planning"
echo "   • Financial Reporting"
echo "   • Chart of Accounts"
echo "   • Double-Entry Transactions"
echo ""
echo "🔧 If you encounter any issues, check:"
echo "   • Database connection is working"
echo "   • Web server has write permissions to storage/"
echo "   • All dependencies are installed"
echo ""
echo "Happy accounting! 💰"
