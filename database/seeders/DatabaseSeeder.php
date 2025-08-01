<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DepartmentSeeder::class,
            EmployeeSeeder::class,
            ClientSeeder::class,
            ProjectSeeder::class,
            EquipmentSeeder::class,
            TaskSeeder::class,
            ProjectTeamSeeder::class,
            NotificationSeeder::class,
            AccountingSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@nepbyte.com',
        ]);
    }
}
