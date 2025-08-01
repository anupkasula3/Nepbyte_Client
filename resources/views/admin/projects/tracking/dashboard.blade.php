@extends('layouts.admin')

@section('title', $project->name . ' - Project Tracking Dashboard')
@section('page-title', 'Project Tracking Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $project->name }}</h1>
            <p class="text-sm text-gray-600">{{ $project->client->company_name }} • PM: {{ $project->projectManager->full_name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.projects.tracking.team', $project) }}"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-users mr-2"></i>Manage Team
            </a>
            <a href="{{ route('admin.projects.tracking.timeline', $project) }}"
               class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-history mr-2"></i>Timeline
            </a>
            <a href="{{ route('admin.projects.edit', $project) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-edit mr-2"></i>Edit Project
            </a>
        </div>
    </div>

    <!-- Project Status Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <i class="fas fa-tasks text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Tasks</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_tasks'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Completed</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['completed_tasks'] }}</p>
                    <p class="text-xs text-gray-500">
                        {{ $stats['total_tasks'] > 0 ? round(($stats['completed_tasks'] / $stats['total_tasks']) * 100, 1) : 0 }}% complete
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">In Progress</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['in_progress_tasks'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Overdue</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['overdue_tasks'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Project Progress</h3>
            <span class="text-sm font-medium text-gray-500">{{ $project->progress_percentage }}% Complete</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-3">
            <div class="bg-blue-600 h-3 rounded-full transition-all duration-300" style="width: {{ $project->progress_percentage }}%"></div>
        </div>
        <div class="mt-2 flex justify-between text-xs text-gray-500">
            <span>Started: {{ $project->start_date->format('M d, Y') }}</span>
            <span>Due: {{ $project->end_date->format('M d, Y') }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Team Overview -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Team Members ({{ $stats['team_members'] }})</h3>
                    <a href="{{ route('admin.projects.tracking.team', $project) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        Manage Team <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($project->activeTeamMembers->take(5) as $teamMember)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <img class="h-8 w-8 rounded-full" 
                                     src="https://ui-avatars.com/api/?name={{ urlencode($teamMember->employee->full_name) }}&background=6366f1&color=fff" 
                                     alt="{{ $teamMember->employee->full_name }}">
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $teamMember->employee->full_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $teamMember->employee->position }}</p>
                                </div>
                            </div>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $teamMember->role_color }}">
                                {{ ucfirst($teamMember->role) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No team members assigned yet.</p>
                    @endforelse
                    @if($project->activeTeamMembers->count() > 5)
                        <div class="text-center">
                            <a href="{{ route('admin.projects.tracking.team', $project) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                View all {{ $project->activeTeamMembers->count() }} members
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Task Distribution -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Task Distribution</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($taskDistribution as $distribution)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center">
                                    <img class="h-6 w-6 rounded-full" 
                                         src="https://ui-avatars.com/api/?name={{ urlencode($distribution['employee']->full_name) }}&background=6366f1&color=fff" 
                                         alt="{{ $distribution['employee']->full_name }}">
                                    <span class="ml-2 text-sm font-medium text-gray-900">{{ $distribution['employee']->full_name }}</span>
                                </div>
                                <span class="text-sm text-gray-500">{{ $distribution['total'] }} tasks</span>
                            </div>
                            <div class="grid grid-cols-3 gap-2 text-xs">
                                <div class="text-center">
                                    <div class="text-green-600 font-medium">{{ $distribution['completed'] }}</div>
                                    <div class="text-gray-500">Completed</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-blue-600 font-medium">{{ $distribution['in_progress'] }}</div>
                                    <div class="text-gray-500">In Progress</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-gray-600 font-medium">{{ $distribution['todo'] }}</div>
                                    <div class="text-gray-500">To Do</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No tasks assigned yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Upcoming Deadlines -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Upcoming Deadlines</h3>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @forelse($upcomingDeadlines as $task)
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $task->title }}</p>
                                <p class="text-xs text-gray-500">Assigned to: {{ $task->assignedEmployee->full_name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-900">{{ $task->due_date->format('M d') }}</p>
                                <p class="text-xs {{ $task->due_date->isToday() ? 'text-orange-600' : ($task->due_date->diffInDays() <= 3 ? 'text-red-600' : 'text-gray-500') }}">
                                    {{ $task->due_date->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No upcoming deadlines.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Recent Activity</h3>
                    <a href="{{ route('admin.projects.tracking.timeline', $project) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentActivities->take(5) as $activity)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="{{ $activity->activity_icon }} text-sm"></i>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm text-gray-900">{{ $activity->description }}</p>
                                <div class="flex items-center mt-1">
                                    <span class="text-xs text-gray-500">{{ $activity->user->full_name }}</span>
                                    <span class="mx-1 text-xs text-gray-400">•</span>
                                    <span class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No recent activity.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Time Tracking Summary -->
    @if($stats['total_estimated_hours'] > 0 || $stats['total_actual_hours'] > 0)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Time Tracking Summary</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['total_estimated_hours'] }}h</div>
                    <div class="text-sm text-gray-500">Estimated Hours</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['total_actual_hours'] }}h</div>
                    <div class="text-sm text-gray-500">Actual Hours</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold {{ $stats['total_actual_hours'] > $stats['total_estimated_hours'] ? 'text-red-600' : 'text-green-600' }}">
                        @if($stats['total_estimated_hours'] > 0)
                            {{ round(($stats['total_actual_hours'] / $stats['total_estimated_hours']) * 100, 1) }}%
                        @else
                            N/A
                        @endif
                    </div>
                    <div class="text-sm text-gray-500">Time Efficiency</div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
