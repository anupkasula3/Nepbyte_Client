<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            [
                'employee_id' => 'EMP001',
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'john.smith@nepbyte.com',
                'phone' => '+1-555-0101',
                'position' => 'Head of Development',
                'department_id' => 1,
                'salary' => 95000.00,
                'hire_date' => '2020-01-15',
                'birth_date' => '1985-03-20',
                'address' => '123 Main St, Tech City, TC 12345',
                'employment_type' => 'full-time',
                'status' => 'active',
                'emergency_contact_name' => 'Jane Smith',
                'emergency_contact_phone' => '+1-555-0102',
                'skills' => 'PHP, Laravel, JavaScript, React, Team Leadership',
            ],
            [
                'employee_id' => 'EMP002',
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'email' => 'sarah.johnson@nepbyte.com',
                'phone' => '+1-555-0201',
                'position' => 'QA Manager',
                'department_id' => 2,
                'salary' => 75000.00,
                'hire_date' => '2020-03-10',
                'birth_date' => '1988-07-15',
                'address' => '456 Oak Ave, Tech City, TC 12346',
                'employment_type' => 'full-time',
                'status' => 'active',
                'emergency_contact_name' => 'Robert Johnson',
                'emergency_contact_phone' => '+1-555-0202',
                'skills' => 'Test Automation, Selenium, JIRA, Quality Assurance',
            ],
            [
                'employee_id' => 'EMP003',
                'first_name' => 'Mike',
                'last_name' => 'Wilson',
                'email' => 'mike.wilson@nepbyte.com',
                'phone' => '+1-555-0301',
                'position' => 'DevOps Engineer',
                'department_id' => 3,
                'salary' => 85000.00,
                'hire_date' => '2019-11-20',
                'birth_date' => '1987-12-05',
                'address' => '789 Pine St, Tech City, TC 12347',
                'employment_type' => 'full-time',
                'status' => 'active',
                'emergency_contact_name' => 'Lisa Wilson',
                'emergency_contact_phone' => '+1-555-0302',
                'skills' => 'AWS, Docker, Kubernetes, CI/CD, Linux',
            ],
            [
                'employee_id' => 'EMP004',
                'first_name' => 'Emily',
                'last_name' => 'Davis',
                'email' => 'emily.davis@nepbyte.com',
                'phone' => '+1-555-0401',
                'position' => 'Senior UI/UX Designer',
                'department_id' => 4,
                'salary' => 70000.00,
                'hire_date' => '2021-02-01',
                'birth_date' => '1990-04-18',
                'address' => '321 Elm St, Tech City, TC 12348',
                'employment_type' => 'full-time',
                'status' => 'active',
                'emergency_contact_name' => 'Mark Davis',
                'emergency_contact_phone' => '+1-555-0402',
                'skills' => 'Figma, Adobe Creative Suite, User Research, Prototyping',
            ],
            [
                'employee_id' => 'EMP005',
                'first_name' => 'David',
                'last_name' => 'Brown',
                'email' => 'david.brown@nepbyte.com',
                'phone' => '+1-555-0501',
                'position' => 'Project Manager',
                'department_id' => 5,
                'salary' => 80000.00,
                'hire_date' => '2020-08-15',
                'birth_date' => '1983-09-25',
                'address' => '654 Maple Ave, Tech City, TC 12349',
                'employment_type' => 'full-time',
                'status' => 'active',
                'emergency_contact_name' => 'Susan Brown',
                'emergency_contact_phone' => '+1-555-0502',
                'skills' => 'Agile, Scrum, Project Planning, Risk Management',
            ],
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }
}
