<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExpenseCategory;
use App\Models\Account;

class AccountingSeeder extends Seeder
{
    /**
     * Run the database's seeds.
     */
    public function run(): void
    {
        // Create Expense Categories
        $expenseCategories = [
            ['name' => 'Office Supplies', 'description' => 'General office supplies and materials', 'color' => '#3B82F6'],
            ['name' => 'Travel & Transportation', 'description' => 'Business travel, flights, hotels, car rentals', 'color' => '#10B981'],
            ['name' => 'Meals & Entertainment', 'description' => 'Business meals and client entertainment', 'color' => '#F59E0B'],
            ['name' => 'Software & Subscriptions', 'description' => 'Software licenses and subscription services', 'color' => '#8B5CF6'],
            ['name' => 'Marketing & Advertising', 'description' => 'Marketing campaigns and advertising costs', 'color' => '#EF4444'],
            ['name' => 'Professional Services', 'description' => 'Legal, accounting, consulting services', 'color' => '#06B6D4'],
            ['name' => 'Equipment & Hardware', 'description' => 'Computer equipment and hardware purchases', 'color' => '#84CC16'],
            ['name' => 'Utilities', 'description' => 'Internet, phone, electricity, water', 'color' => '#F97316'],
            ['name' => 'Training & Education', 'description' => 'Employee training and educational expenses', 'color' => '#EC4899'],
            ['name' => 'Miscellaneous', 'description' => 'Other business expenses', 'color' => '#6B7280'],
        ];

        foreach ($expenseCategories as $category) {
            ExpenseCategory::create($category);
        }

        // Create Chart of Accounts
        $accounts = [
            // Assets
            ['code' => '1000', 'name' => 'Cash', 'type' => 'asset', 'subtype' => 'current_asset', 'opening_balance' => 50000],
            ['code' => '1100', 'name' => 'Accounts Receivable', 'type' => 'asset', 'subtype' => 'current_asset', 'opening_balance' => 25000],
            ['code' => '1200', 'name' => 'Office Equipment', 'type' => 'asset', 'subtype' => 'fixed_asset', 'opening_balance' => 15000],
            ['code' => '1300', 'name' => 'Computer Equipment', 'type' => 'asset', 'subtype' => 'fixed_asset', 'opening_balance' => 20000],

            // Liabilities
            ['code' => '2000', 'name' => 'Accounts Payable', 'type' => 'liability', 'subtype' => 'current_liability', 'opening_balance' => 8000],
            ['code' => '2100', 'name' => 'Credit Card Payable', 'type' => 'liability', 'subtype' => 'current_liability', 'opening_balance' => 3000],
            ['code' => '2200', 'name' => 'Accrued Expenses', 'type' => 'liability', 'subtype' => 'current_liability', 'opening_balance' => 2000],

            // Equity
            ['code' => '3000', 'name' => 'Owner\'s Equity', 'type' => 'equity', 'subtype' => 'owner_equity', 'opening_balance' => 97000],

            // Income
            ['code' => '4000', 'name' => 'Service Revenue', 'type' => 'income', 'subtype' => 'operating_income', 'opening_balance' => 0],
            ['code' => '4100', 'name' => 'Consulting Revenue', 'type' => 'income', 'subtype' => 'operating_income', 'opening_balance' => 0],
            ['code' => '4200', 'name' => 'Project Revenue', 'type' => 'income', 'subtype' => 'operating_income', 'opening_balance' => 0],
            ['code' => '4900', 'name' => 'Other Income', 'type' => 'income', 'subtype' => 'other_income', 'opening_balance' => 0],

            // Expenses
            ['code' => '5000', 'name' => 'Salaries & Wages', 'type' => 'expense', 'subtype' => 'operating_expense', 'opening_balance' => 0],
            ['code' => '5100', 'name' => 'Office Rent', 'type' => 'expense', 'subtype' => 'operating_expense', 'opening_balance' => 0],
            ['code' => '5200', 'name' => 'Utilities', 'type' => 'expense', 'subtype' => 'operating_expense', 'opening_balance' => 0],
            ['code' => '5300', 'name' => 'Office Supplies', 'type' => 'expense', 'subtype' => 'operating_expense', 'opening_balance' => 0],
            ['code' => '5400', 'name' => 'Software & Subscriptions', 'type' => 'expense', 'subtype' => 'operating_expense', 'opening_balance' => 0],
            ['code' => '5500', 'name' => 'Marketing & Advertising', 'type' => 'expense', 'subtype' => 'operating_expense', 'opening_balance' => 0],
            ['code' => '5600', 'name' => 'Travel & Transportation', 'type' => 'expense', 'subtype' => 'operating_expense', 'opening_balance' => 0],
            ['code' => '5700', 'name' => 'Meals & Entertainment', 'type' => 'expense', 'subtype' => 'operating_expense', 'opening_balance' => 0],
            ['code' => '5800', 'name' => 'Professional Services', 'type' => 'expense', 'subtype' => 'operating_expense', 'opening_balance' => 0],
            ['code' => '5900', 'name' => 'Training & Education', 'type' => 'expense', 'subtype' => 'operating_expense', 'opening_balance' => 0],
            ['code' => '6000', 'name' => 'Equipment Purchases', 'type' => 'expense', 'subtype' => 'operating_expense', 'opening_balance' => 0],
            ['code' => '6100', 'name' => 'Insurance', 'type' => 'expense', 'subtype' => 'operating_expense', 'opening_balance' => 0],
            ['code' => '6200', 'name' => 'Bank Fees', 'type' => 'expense', 'subtype' => 'operating_expense', 'opening_balance' => 0],
            ['code' => '6900', 'name' => 'Miscellaneous Expenses', 'type' => 'expense', 'subtype' => 'operating_expense', 'opening_balance' => 0],
        ];

        foreach ($accounts as $accountData) {
            $account = Account::create($accountData);
            $account->current_balance = $account->opening_balance;
            $account->save();
        }

        $this->command->info('Accounting data seeded successfully!');
    }
}
