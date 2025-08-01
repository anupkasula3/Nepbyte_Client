# 🚀 One-Command Installation

## Super Quick Install

Choose your preferred method:

### Method 1: Laravel Artisan Command (Recommended)
```bash
php artisan accounting:install
```

### Method 2: Shell Script (Linux/Mac)
```bash
./install-accounting-system.sh
```

### Method 3: Batch File (Windows)
```cmd
install-accounting-system.bat
```

### Method 4: Manual One-Liner (All Platforms)
```bash
npm install chart.js chartjs-adapter-date-fns date-fns && php artisan migrate && php artisan db:seed --class=AccountingSeeder && npm run build && php artisan cache:clear && php artisan storage:link
```

## ✅ What Happens Automatically

All methods will:
1. 📦 Install Chart.js and dependencies
2. 🗄️ Run all database migrations
3. 🌱 Seed sample accounting data
4. 🎨 Build frontend assets
5. 🧹 Clear all caches
6. 🔗 Create storage symlinks

## 🎯 After Installation

Visit these URLs immediately:
- **Dashboard**: `/admin/accounting`
- **Invoices**: `/admin/invoices`
- **Expenses**: `/admin/expenses`

## 🎉 That's It!

Your complete accounting system is ready with:
- 📊 Interactive charts
- 💰 Invoice management
- 💸 Expense tracking
- 💳 Payment processing
- 📈 Financial reporting
- 🏦 Chart of accounts

**Total installation time: ~2-3 minutes** ⚡
