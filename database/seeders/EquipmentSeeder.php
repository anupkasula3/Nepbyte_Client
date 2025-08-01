<?php

namespace Database\Seeders;

use App\Models\Equipment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $equipment = [
            [
                'name' => 'MacBook Pro 16"',
                'asset_tag' => 'NB-LAP-001',
                'serial_number' => 'C02XJ0AAJGH5',
                'model' => 'MacBook Pro 16-inch',
                'manufacturer' => 'Apple',
                'category' => 'laptop',
                'status' => 'assigned',
                'assigned_to' => 1, // John Smith
                'purchase_date' => '2023-01-15',
                'purchase_price' => 2499.00,
                'warranty_expiry' => '2024-01-15',
                'location' => 'Floor 3, Building A',
                'specifications' => 'M1 Pro chip, 16GB RAM, 512GB SSD, 16-inch Retina display',
                'notes' => 'Primary development machine for team lead',
            ],
            [
                'name' => 'Dell XPS 13',
                'asset_tag' => 'NB-LAP-002',
                'serial_number' => 'HXKWM73',
                'model' => 'XPS 13 9310',
                'manufacturer' => 'Dell',
                'category' => 'laptop',
                'status' => 'assigned',
                'assigned_to' => 2, // Sarah Johnson
                'purchase_date' => '2023-03-10',
                'purchase_price' => 1299.00,
                'warranty_expiry' => '2026-03-10',
                'location' => 'Floor 2, Building A',
                'specifications' => 'Intel i7-1165G7, 16GB RAM, 512GB SSD, 13.3-inch FHD display',
                'notes' => 'QA testing machine',
            ],
            [
                'name' => 'HP EliteDesk 800',
                'asset_tag' => 'NB-DES-001',
                'serial_number' => 'MXL2040B2K',
                'model' => 'EliteDesk 800 G6',
                'manufacturer' => 'HP',
                'category' => 'desktop',
                'status' => 'available',
                'assigned_to' => null,
                'purchase_date' => '2023-02-20',
                'purchase_price' => 899.00,
                'warranty_expiry' => '2026-02-20',
                'location' => 'Storage Room',
                'specifications' => 'Intel i5-10500, 8GB RAM, 256GB SSD, Windows 11 Pro',
                'notes' => 'Backup desktop computer',
            ],
            [
                'name' => 'iPhone 14 Pro',
                'asset_tag' => 'NB-MOB-001',
                'serial_number' => 'F2LN8QKXQ6',
                'model' => 'iPhone 14 Pro',
                'manufacturer' => 'Apple',
                'category' => 'mobile',
                'status' => 'assigned',
                'assigned_to' => 3, // Mike Wilson
                'purchase_date' => '2023-04-15',
                'purchase_price' => 999.00,
                'warranty_expiry' => '2024-04-15',
                'location' => 'Floor 1, Building B',
                'specifications' => '128GB, Space Black, iOS 16',
                'notes' => 'Company phone for DevOps engineer',
            ],
            [
                'name' => 'Canon ImageCLASS Printer',
                'asset_tag' => 'NB-PRT-001',
                'serial_number' => 'KBM01234',
                'model' => 'ImageCLASS MF445dw',
                'manufacturer' => 'Canon',
                'category' => 'printer',
                'status' => 'available',
                'assigned_to' => null,
                'purchase_date' => '2023-01-30',
                'purchase_price' => 299.00,
                'warranty_expiry' => '2024-01-30',
                'location' => 'Floor 3, Building A',
                'specifications' => 'Monochrome laser, duplex printing, wireless, 40 ppm',
                'notes' => 'Shared printer for development team',
            ],
        ];

        foreach ($equipment as $item) {
            Equipment::create($item);
        }
    }
}
