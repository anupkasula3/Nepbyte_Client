<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Software Development',
                'description' => 'Responsible for developing and maintaining software applications and systems.',
                'head_of_department' => 'John Smith',
                'employee_count' => 15,
                'budget' => 500000.00,
                'location' => 'Floor 3, Building A',
                'is_active' => true,
            ],
            [
                'name' => 'Quality Assurance',
                'description' => 'Ensures software quality through testing and quality control processes.',
                'head_of_department' => 'Sarah Johnson',
                'employee_count' => 8,
                'budget' => 200000.00,
                'location' => 'Floor 2, Building A',
                'is_active' => true,
            ],
            [
                'name' => 'DevOps & Infrastructure',
                'description' => 'Manages deployment, infrastructure, and system operations.',
                'head_of_department' => 'Mike Wilson',
                'employee_count' => 6,
                'budget' => 300000.00,
                'location' => 'Floor 1, Building B',
                'is_active' => true,
            ],
            [
                'name' => 'UI/UX Design',
                'description' => 'Creates user interfaces and user experience designs for applications.',
                'head_of_department' => 'Emily Davis',
                'employee_count' => 5,
                'budget' => 150000.00,
                'location' => 'Floor 4, Building A',
                'is_active' => true,
            ],
            [
                'name' => 'Project Management',
                'description' => 'Oversees project planning, execution, and delivery.',
                'head_of_department' => 'David Brown',
                'employee_count' => 4,
                'budget' => 180000.00,
                'location' => 'Floor 5, Building A',
                'is_active' => true,
            ],
            [
                'name' => 'Human Resources',
                'description' => 'Manages employee relations, recruitment, and HR policies.',
                'head_of_department' => 'Lisa Anderson',
                'employee_count' => 3,
                'budget' => 120000.00,
                'location' => 'Floor 1, Building A',
                'is_active' => true,
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
