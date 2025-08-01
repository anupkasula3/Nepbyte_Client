<?php

namespace Database\Seeders;

use App\Models\Notification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $notifications = [
            // Task-related notifications
            [
                'user_id' => 2, // Sarah Johnson
                'project_id' => 1,
                'type' => 'task_assigned',
                'title' => 'New Task Assigned',
                'message' => 'You have been assigned a new task: User Authentication System',
                'action_url' => '/admin/tasks/2',
                'data' => [
                    'task_id' => 2,
                    'task_title' => 'User Authentication System',
                    'priority' => 'high',
                ],
                'created_at' => now()->subHours(2),
            ],
            [
                'user_id' => 1, // John Smith
                'project_id' => 1,
                'type' => 'task_completed',
                'title' => 'Task Completed',
                'message' => 'Task "Database Design and Setup" has been completed',
                'action_url' => '/admin/tasks/1',
                'data' => [
                    'task_id' => 1,
                    'task_title' => 'Database Design and Setup',
                ],
                'is_read' => true,
                'read_at' => now()->subHour(),
                'created_at' => now()->subHours(3),
            ],
            [
                'user_id' => 3, // Mike Wilson
                'project_id' => 2,
                'type' => 'task_assigned',
                'title' => 'New Task Assigned',
                'message' => 'You have been assigned a new task: IoT Sensor Integration',
                'action_url' => '/admin/tasks/5',
                'data' => [
                    'task_id' => 5,
                    'task_title' => 'IoT Sensor Integration',
                    'priority' => 'high',
                ],
                'created_at' => now()->subMinutes(30),
            ],

            // Project-related notifications
            [
                'user_id' => 1, // John Smith
                'project_id' => 1,
                'type' => 'project_updated',
                'title' => 'Project Progress Updated',
                'message' => 'E-commerce Platform Development is now 45% complete',
                'action_url' => '/admin/projects/1/tracking/dashboard',
                'data' => [
                    'project_name' => 'E-commerce Platform Development',
                    'progress' => 45,
                ],
                'created_at' => now()->subHours(1),
            ],
            [
                'user_id' => 5, // David Brown
                'project_id' => 2,
                'type' => 'team_member_added',
                'title' => 'New Team Member Added',
                'message' => 'Emily Davis has been added to the Energy Management System project',
                'action_url' => '/admin/projects/2/tracking/team',
                'data' => [
                    'project_name' => 'Energy Management System',
                    'member_name' => 'Emily Davis',
                ],
                'is_read' => true,
                'read_at' => now()->subMinutes(45),
                'created_at' => now()->subHours(4),
            ],

            // Deadline notifications
            [
                'user_id' => 4, // Emily Davis
                'project_id' => 1,
                'type' => 'deadline_approaching',
                'title' => 'Task Deadline Approaching',
                'message' => 'Task "Product Catalog Management" is due in 2 days',
                'action_url' => '/admin/tasks/3',
                'data' => [
                    'task_title' => 'Product Catalog Management',
                    'due_date' => now()->addDays(2)->format('Y-m-d'),
                ],
                'created_at' => now()->subMinutes(15),
            ],
            [
                'user_id' => 2, // Sarah Johnson
                'project_id' => 3,
                'type' => 'project_updated',
                'title' => 'Project Deadline Changed',
                'message' => 'Corporate Website Redesign deadline has been updated to December 31, 2023',
                'action_url' => '/admin/projects/3/tracking/dashboard',
                'data' => [
                    'project_name' => 'Corporate Website Redesign',
                    'new_deadline' => '2023-12-31',
                ],
                'created_at' => now()->subMinutes(45),
            ],

            // General notifications
            [
                'user_id' => 1, // John Smith
                'project_id' => null,
                'type' => 'project_completed',
                'title' => 'Project Milestone Reached',
                'message' => 'Congratulations! The team has completed 50% of all active projects',
                'action_url' => '/admin/projects',
                'data' => [
                    'milestone' => '50% completion',
                ],
                'created_at' => now()->subDays(1),
            ],
        ];

        foreach ($notifications as $notification) {
            Notification::create($notification);
        }
    }
}
