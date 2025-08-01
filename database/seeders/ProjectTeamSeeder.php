<?php

namespace Database\Seeders;

use App\Models\ProjectActivity;
use App\Models\ProjectTeamMember;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add team members to projects
        $teamMembers = [
            // E-commerce Platform Development (Project 1)
            [
                'project_id' => 1,
                'employee_id' => 1, // John Smith (Project Manager)
                'role' => 'lead',
                'joined_at' => '2023-01-15 09:00:00',
                'responsibilities' => 'Overall project coordination, technical architecture decisions, and team leadership.',
            ],
            [
                'project_id' => 1,
                'employee_id' => 2, // Sarah Johnson
                'role' => 'member',
                'joined_at' => '2023-01-16 10:00:00',
                'responsibilities' => 'Frontend development, user interface design, and user experience optimization.',
            ],
            [
                'project_id' => 1,
                'employee_id' => 3, // Mike Wilson
                'role' => 'member',
                'joined_at' => '2023-01-17 11:00:00',
                'responsibilities' => 'Backend development, database design, and API development.',
            ],
            [
                'project_id' => 1,
                'employee_id' => 4, // Emily Davis
                'role' => 'reviewer',
                'joined_at' => '2023-01-18 14:00:00',
                'responsibilities' => 'Code review, quality assurance, and testing coordination.',
            ],

            // Energy Management System (Project 2)
            [
                'project_id' => 2,
                'employee_id' => 5, // David Brown (Project Manager)
                'role' => 'lead',
                'joined_at' => '2023-06-01 09:00:00',
                'responsibilities' => 'Project management, client communication, and technical oversight.',
            ],
            [
                'project_id' => 2,
                'employee_id' => 3, // Mike Wilson
                'role' => 'member',
                'joined_at' => '2023-06-02 10:00:00',
                'responsibilities' => 'IoT integration, sensor data processing, and backend development.',
            ],
            [
                'project_id' => 2,
                'employee_id' => 4, // Emily Davis
                'role' => 'member',
                'joined_at' => '2023-06-03 11:00:00',
                'responsibilities' => 'Dashboard development, data visualization, and frontend implementation.',
            ],
            [
                'project_id' => 2,
                'employee_id' => 1, // John Smith
                'role' => 'observer',
                'joined_at' => '2023-06-05 15:00:00',
                'responsibilities' => 'Technical consultation and architecture review.',
            ],

            // Corporate Website Redesign (Project 3)
            [
                'project_id' => 3,
                'employee_id' => 2, // Sarah Johnson (Project Manager)
                'role' => 'lead',
                'joined_at' => '2023-09-01 09:00:00',
                'responsibilities' => 'Project coordination, design leadership, and client communication.',
            ],
            [
                'project_id' => 3,
                'employee_id' => 4, // Emily Davis
                'role' => 'member',
                'joined_at' => '2023-09-02 10:00:00',
                'responsibilities' => 'Content management, SEO optimization, and quality assurance.',
            ],
        ];

        foreach ($teamMembers as $member) {
            ProjectTeamMember::create($member);
        }

        // Add some project activities
        $activities = [
            // E-commerce Platform activities
            [
                'project_id' => 1,
                'user_id' => 1,
                'activity_type' => 'project_updated',
                'description' => 'Project kickoff meeting completed and initial requirements gathered',
                'subject_type' => 'App\\Models\\Project',
                'subject_id' => 1,
                'created_at' => '2023-01-15 10:00:00',
            ],
            [
                'project_id' => 1,
                'user_id' => 1,
                'activity_type' => 'team_member_added',
                'description' => 'Added Sarah Johnson to the team as member',
                'subject_type' => 'App\\Models\\ProjectTeamMember',
                'subject_id' => 2,
                'created_at' => '2023-01-16 10:00:00',
            ],
            [
                'project_id' => 1,
                'user_id' => 1,
                'activity_type' => 'team_member_added',
                'description' => 'Added Mike Wilson to the team as member',
                'subject_type' => 'App\\Models\\ProjectTeamMember',
                'subject_id' => 3,
                'created_at' => '2023-01-17 11:00:00',
            ],
            [
                'project_id' => 1,
                'user_id' => 1,
                'activity_type' => 'task_created',
                'description' => 'Created task: Database Design and Setup',
                'subject_type' => 'App\\Models\\Task',
                'subject_id' => 1,
                'created_at' => '2023-01-20 09:00:00',
            ],
            [
                'project_id' => 1,
                'user_id' => 1,
                'activity_type' => 'task_completed',
                'description' => 'Completed task: Database Design and Setup',
                'subject_type' => 'App\\Models\\Task',
                'subject_id' => 1,
                'created_at' => '2023-02-10 16:00:00',
            ],

            // Energy Management System activities
            [
                'project_id' => 2,
                'user_id' => 5,
                'activity_type' => 'project_updated',
                'description' => 'Project initiated with GreenTech Solutions',
                'subject_type' => 'App\\Models\\Project',
                'subject_id' => 2,
                'created_at' => '2023-06-01 09:00:00',
            ],
            [
                'project_id' => 2,
                'user_id' => 5,
                'activity_type' => 'team_member_added',
                'description' => 'Added Mike Wilson to the team as member',
                'subject_type' => 'App\\Models\\ProjectTeamMember',
                'subject_id' => 6,
                'created_at' => '2023-06-02 10:00:00',
            ],
            [
                'project_id' => 2,
                'user_id' => 5,
                'activity_type' => 'task_created',
                'description' => 'Created task: IoT Sensor Integration',
                'subject_type' => 'App\\Models\\Task',
                'subject_id' => 5,
                'created_at' => '2023-06-10 14:00:00',
            ],

            // Corporate Website activities
            [
                'project_id' => 3,
                'user_id' => 2,
                'activity_type' => 'project_updated',
                'description' => 'Website redesign project started for TechCorp Industries',
                'subject_type' => 'App\\Models\\Project',
                'subject_id' => 3,
                'created_at' => '2023-09-01 09:00:00',
            ],
            [
                'project_id' => 3,
                'user_id' => 2,
                'activity_type' => 'team_member_added',
                'description' => 'Added Emily Davis to the team as member',
                'subject_type' => 'App\\Models\\ProjectTeamMember',
                'subject_id' => 10,
                'created_at' => '2023-09-02 10:00:00',
            ],
        ];

        foreach ($activities as $activity) {
            ProjectActivity::create($activity);
        }
    }
}
