<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class InstallAccountingSystem extends Command
{
    protected $signature = 'accounting:install {--force : Force installation even if tables exist}';
    protected $description = 'Install the complete accounting system';

    public function handle()
    {
        $this->info('🚀 Installing Accounting System...');
        $this->newLine();

        // Check if accounting tables already exist
        if (!$this->option('force') && $this->accountingTablesExist()) {
            if (!$this->confirm('Accounting tables already exist. Do you want to continue? This may overwrite existing data.')) {
                $this->error('Installation cancelled.');
                return 1;
            }
        }

        $this->installStep('📦 Installing NPM dependencies...', function() {
            $this->runCommand('npm install chart.js chartjs-adapter-date-fns date-fns');
        });

        $this->installStep('🗄️ Running database migrations...', function() {
            Artisan::call('migrate', ['--force' => true]);
        });

        $this->installStep('🌱 Seeding accounting data...', function() {
            Artisan::call('db:seed', ['--class' => 'AccountingSeeder', '--force' => true]);
        });

        $this->installStep('🎨 Building frontend assets...', function() {
            $this->runCommand('npm run build');
        });

        $this->installStep('🧹 Clearing application cache...', function() {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
        });

        $this->installStep('🔗 Creating storage symlink...', function() {
            if (!File::exists(public_path('storage'))) {
                Artisan::call('storage:link');
            }
        });

        $this->newLine();
        $this->info('🎉 Accounting System Installation Complete!');
        $this->newLine();
        
        $this->displaySuccessMessage();

        return 0;
    }

    private function installStep($message, $callback)
    {
        $this->info($message);
        
        try {
            $callback();
            $this->line('   ✅ Success');
        } catch (\Exception $e) {
            $this->error('   ❌ Failed: ' . $e->getMessage());
            throw $e;
        }
    }

    private function runCommand($command)
    {
        $process = proc_open(
            $command,
            [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ],
            $pipes,
            base_path()
        );

        if (is_resource($process)) {
            fclose($pipes[0]);
            $output = stream_get_contents($pipes[1]);
            $error = stream_get_contents($pipes[2]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            
            $returnCode = proc_close($process);
            
            if ($returnCode !== 0) {
                throw new \Exception("Command failed: $error");
            }
        }
    }

    private function accountingTablesExist()
    {
        try {
            return DB::getSchemaBuilder()->hasTable('expense_categories') ||
                   DB::getSchemaBuilder()->hasTable('accounts') ||
                   DB::getSchemaBuilder()->hasTable('invoices');
        } catch (\Exception $e) {
            return false;
        }
    }

    private function displaySuccessMessage()
    {
        $this->table(
            ['Feature', 'Status', 'Access URL'],
            [
                ['Financial Dashboard', '✅ Ready', '/admin/accounting'],
                ['Invoice Management', '✅ Ready', '/admin/invoices'],
                ['Expense Tracking', '✅ Ready', '/admin/expenses'],
                ['Payment Processing', '✅ Ready', '/admin/payments'],
                ['Budget Planning', '✅ Ready', '/admin/budgets'],
                ['Chart of Accounts', '✅ Ready', '/admin/accounts'],
                ['Transactions', '✅ Ready', '/admin/transactions'],
                ['Expense Categories', '✅ Ready', '/admin/expense-categories'],
            ]
        );

        $this->newLine();
        $this->info('📊 Features Available:');
        $this->line('   • Interactive Financial Dashboard with Charts');
        $this->line('   • Complete Invoice Management System');
        $this->line('   • Expense Tracking with Approval Workflows');
        $this->line('   • Payment Processing and History');
        $this->line('   • Budget Planning and Monitoring');
        $this->line('   • Professional Financial Reporting');
        $this->line('   • Double-Entry Accounting System');
        $this->line('   • Chart of Accounts Management');

        $this->newLine();
        $this->info('🎯 Quick Start:');
        $this->line('   1. Visit /admin/accounting for the main dashboard');
        $this->line('   2. Create your first invoice at /admin/invoices/create');
        $this->line('   3. Add expenses at /admin/expenses/create');
        $this->line('   4. Set up budgets at /admin/budgets/create');

        $this->newLine();
        $this->info('💡 Sample Data Included:');
        $this->line('   • 10 Expense Categories with colors');
        $this->line('   • Complete Chart of Accounts');
        $this->line('   • Account balances for testing');

        $this->newLine();
        $this->warn('🔧 Troubleshooting:');
        $this->line('   • Ensure database connection is working');
        $this->line('   • Check web server write permissions to storage/');
        $this->line('   • Verify all NPM dependencies are installed');
        $this->line('   • Run "php artisan route:list" to see all routes');

        $this->newLine();
        $this->info('🎉 Happy Accounting!');
    }
}
