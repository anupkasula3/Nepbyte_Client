<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'name' => 'E-commerce Platform Development',
                'description' => 'Build a modern e-commerce platform with advanced features including inventory management, payment processing, and customer analytics.',
                'client_id' => 1, // TechCorp Solutions
                'project_manager_id' => 1, // John Smith
                'status' => 'in_progress',
                'priority' => 'high',
                'budget' => 150000.00,
                'actual_cost' => 75000.00,
                'start_date' => '2023-01-15',
                'end_date' => '2024-06-30',
                'progress_percentage' => 65,
                'technologies_used' => 'Laravel, Vue.js, MySQL, Redis, Stripe API',
                'requirements' => 'Multi-vendor marketplace, mobile responsive, payment gateway integration, inventory tracking',
                'notes' => 'Client very satisfied with progress. Regular weekly meetings scheduled.',
            ],
            [
                'name' => 'Energy Management System',
                'description' => 'Develop a comprehensive energy management and monitoring system for renewable energy installations.',
                'client_id' => 2, // Green Energy Inc
                'project_manager_id' => 5, // David Brown
                'status' => 'in_progress',
                'priority' => 'medium',
                'budget' => 120000.00,
                'actual_cost' => 45000.00,
                'start_date' => '2023-06-01',
                'end_date' => '2024-12-31',
                'progress_percentage' => 40,
                'technologies_used' => 'React, Node.js, PostgreSQL, IoT sensors, Chart.js',
                'requirements' => 'Real-time monitoring, data analytics, mobile app, reporting dashboard',
                'notes' => 'Integration with IoT sensors in progress.',
            ],
            [
                'name' => 'Bakery Website & Online Ordering',
                'description' => 'Create a modern website with online ordering system for local bakery business.',
                'client_id' => 3, // Local Bakery Co
                'project_manager_id' => 1, // John Smith
                'status' => 'completed',
                'priority' => 'low',
                'budget' => 15000.00,
                'actual_cost' => 14500.00,
                'start_date' => '2023-09-01',
                'end_date' => '2023-12-15',
                'actual_end_date' => '2023-12-10',
                'progress_percentage' => 100,
                'technologies_used' => 'WordPress, WooCommerce, PHP, MySQL',
                'requirements' => 'Online ordering, payment processing, inventory display, mobile responsive',
                'notes' => 'Project completed successfully. Client very happy with results.',
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}
