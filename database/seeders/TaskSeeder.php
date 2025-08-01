<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tasks = [
            [
                'title' => 'Database Design and Setup',
                'description' => 'Design and implement the database schema for the e-commerce platform including products, orders, customers, and inventory tables.',
                'project_id' => 1, // E-commerce Platform Development
                'assigned_to' => 1, // John Smith
                'created_by' => 1, // John Smith
                'status' => 'completed',
                'priority' => 'high',
                'due_date' => '2023-02-15',
                'completed_date' => '2023-02-10',
                'estimated_hours' => 40,
                'actual_hours' => 35,
                'notes' => 'Database schema completed ahead of schedule. All tables created with proper relationships.',
            ],
            [
                'title' => 'User Authentication System',
                'description' => 'Implement secure user registration, login, and password reset functionality with email verification.',
                'project_id' => 1, // E-commerce Platform Development
                'assigned_to' => 2, // Sarah Johnson
                'created_by' => 1, // John Smith
                'status' => 'completed',
                'priority' => 'high',
                'due_date' => '2023-03-01',
                'completed_date' => '2023-02-28',
                'estimated_hours' => 32,
                'actual_hours' => 30,
                'notes' => 'Authentication system implemented with Laravel Sanctum. All security tests passed.',
            ],
            [
                'title' => 'Product Catalog Management',
                'description' => 'Create admin interface for managing products, categories, and inventory with image upload functionality.',
                'project_id' => 1, // E-commerce Platform Development
                'assigned_to' => 3, // Mike Wilson
                'created_by' => 1, // John Smith
                'status' => 'in_progress',
                'priority' => 'medium',
                'due_date' => '2023-04-15',
                'estimated_hours' => 50,
                'notes' => 'Product CRUD operations completed. Working on image upload and optimization.',
            ],
            [
                'title' => 'Payment Gateway Integration',
                'description' => 'Integrate Stripe payment processing with support for credit cards and digital wallets.',
                'project_id' => 1, // E-commerce Platform Development
                'assigned_to' => 1, // John Smith
                'created_by' => 1, // John Smith
                'status' => 'todo',
                'priority' => 'critical',
                'due_date' => '2023-05-01',
                'estimated_hours' => 25,
                'notes' => 'Waiting for product catalog completion before starting payment integration.',
            ],
            [
                'title' => 'IoT Sensor Integration',
                'description' => 'Develop API endpoints to receive and process data from renewable energy monitoring sensors.',
                'project_id' => 2, // Energy Management System
                'assigned_to' => 3, // Mike Wilson
                'created_by' => 5, // David Brown
                'status' => 'in_progress',
                'priority' => 'high',
                'due_date' => '2023-08-15',
                'estimated_hours' => 60,
                'notes' => 'API structure designed. Working on real-time data processing implementation.',
            ],
            [
                'title' => 'Energy Analytics Dashboard',
                'description' => 'Create interactive dashboard showing energy production, consumption, and efficiency metrics.',
                'project_id' => 2, // Energy Management System
                'assigned_to' => 4, // Emily Davis
                'created_by' => 5, // David Brown
                'status' => 'todo',
                'priority' => 'medium',
                'due_date' => '2023-09-30',
                'estimated_hours' => 45,
                'notes' => 'Waiting for IoT integration completion to start dashboard development.',
            ],
            [
                'title' => 'Mobile App Development',
                'description' => 'Develop React Native mobile app for monitoring energy systems on the go.',
                'project_id' => 2, // Energy Management System
                'assigned_to' => 1, // John Smith
                'created_by' => 5, // David Brown
                'status' => 'todo',
                'priority' => 'low',
                'due_date' => '2023-11-15',
                'estimated_hours' => 80,
                'notes' => 'Mobile app development scheduled for later phase of the project.',
            ],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}
