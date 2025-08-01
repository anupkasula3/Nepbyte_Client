<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            [
                'company_name' => 'TechCorp Solutions',
                'contact_person' => 'Alice Johnson',
                'email' => 'alice.johnson@techcorp.com',
                'phone' => '+1-555-1001',
                'address' => '123 Tech Street, Silicon Valley, CA 94000',
                'website' => 'https://techcorp.com',
                'industry' => 'Technology',
                'client_type' => 'enterprise',
                'status' => 'active',
                'notes' => 'Long-term client with multiple ongoing projects.',
                'total_contract_value' => 250000.00,
                'contract_start_date' => '2023-01-15',
                'contract_end_date' => '2024-12-31',
            ],
            [
                'company_name' => 'Green Energy Inc',
                'contact_person' => 'Robert Green',
                'email' => 'robert@greenenergy.com',
                'phone' => '+1-555-2002',
                'address' => '456 Renewable Ave, Austin, TX 78701',
                'website' => 'https://greenenergy.com',
                'industry' => 'Renewable Energy',
                'client_type' => 'enterprise',
                'status' => 'active',
                'notes' => 'Focused on sustainable technology solutions.',
                'total_contract_value' => 180000.00,
                'contract_start_date' => '2023-06-01',
                'contract_end_date' => '2025-05-31',
            ],
            [
                'company_name' => 'Local Bakery Co',
                'contact_person' => 'Maria Rodriguez',
                'email' => 'maria@localbakery.com',
                'phone' => '+1-555-3003',
                'address' => '789 Main Street, Denver, CO 80202',
                'website' => 'https://localbakery.com',
                'industry' => 'Food & Beverage',
                'client_type' => 'small_business',
                'status' => 'active',
                'notes' => 'Small business needing e-commerce solution.',
                'total_contract_value' => 15000.00,
                'contract_start_date' => '2023-09-01',
                'contract_end_date' => '2024-08-31',
            ],
            [
                'company_name' => 'City Government',
                'contact_person' => 'James Wilson',
                'email' => 'james.wilson@citygovt.gov',
                'phone' => '+1-555-4004',
                'address' => '100 City Hall Plaza, Portland, OR 97201',
                'website' => 'https://citygovt.gov',
                'industry' => 'Government',
                'client_type' => 'government',
                'status' => 'active',
                'notes' => 'Municipal software modernization project.',
                'total_contract_value' => 500000.00,
                'contract_start_date' => '2023-03-01',
                'contract_end_date' => '2025-02-28',
            ],
            [
                'company_name' => 'Startup Innovations',
                'contact_person' => 'Sarah Kim',
                'email' => 'sarah@startupinnovations.com',
                'phone' => '+1-555-5005',
                'address' => '321 Innovation Blvd, Seattle, WA 98101',
                'website' => 'https://startupinnovations.com',
                'industry' => 'Technology',
                'client_type' => 'small_business',
                'status' => 'prospect',
                'notes' => 'Potential client interested in MVP development.',
                'total_contract_value' => null,
                'contract_start_date' => null,
                'contract_end_date' => null,
            ],
            [
                'company_name' => 'Dr. Michael Smith',
                'contact_person' => 'Dr. Michael Smith',
                'email' => 'dr.smith@medicalpractice.com',
                'phone' => '+1-555-6006',
                'address' => '555 Health Ave, Miami, FL 33101',
                'website' => 'https://drsmithpractice.com',
                'industry' => 'Healthcare',
                'client_type' => 'individual',
                'status' => 'active',
                'notes' => 'Medical practice management system.',
                'total_contract_value' => 35000.00,
                'contract_start_date' => '2023-07-15',
                'contract_end_date' => '2024-07-14',
            ],
        ];

        foreach ($clients as $client) {
            Client::create($client);
        }
    }
}
