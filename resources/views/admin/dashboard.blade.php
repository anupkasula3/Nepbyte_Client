@extends('layouts.admin')

@section('title', 'Dashboard - NepByte Admin')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Employees Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $stats['total_employees'] }}</h3>
                    <p class="text-sm text-gray-600">Total Employees</p>
                    <p class="text-xs text-green-600">{{ $stats['active_employees'] }} Active</p>
                </div>
            </div>
        </div>

        <!-- Departments Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-building text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $stats['total_departments'] }}</h3>
                    <p class="text-sm text-gray-600">Departments</p>
                    <p class="text-xs text-green-600">{{ $stats['active_departments'] }} Active</p>
                </div>
            </div>
        </div>

        <!-- Projects Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-project-diagram text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $stats['total_projects'] }}</h3>
                    <p class="text-sm text-gray-600">Total Projects</p>
                    <p class="text-xs text-blue-600">{{ $stats['active_projects'] }} Active</p>
                </div>
            </div>
        </div>

        <!-- Equipment Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                    <i class="fas fa-laptop text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $stats['total_equipment'] }}</h3>
                    <p class="text-sm text-gray-600">Equipment</p>
                    <p class="text-xs text-green-600">{{ $stats['available_equipment'] }} Available</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Clients -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Clients</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['total_clients'] }}</p>
                    <p class="text-sm text-gray-600">{{ $stats['active_clients'] }} Active</p>
                </div>
                <i class="fas fa-handshake text-4xl text-blue-300"></i>
            </div>
        </div>

        <!-- Tasks -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Tasks</h3>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['total_tasks'] }}</p>
                    <p class="text-sm text-gray-600">{{ $stats['pending_tasks'] }} Pending</p>
                </div>
                <i class="fas fa-tasks text-4xl text-green-300"></i>
            </div>
        </div>

        <!-- Completed Projects -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Completed</h3>
                    <p class="text-3xl font-bold text-purple-600">{{ $stats['completed_projects'] }}</p>
                    <p class="text-sm text-gray-600">Projects Done</p>
                </div>
                <i class="fas fa-check-circle text-4xl text-purple-300"></i>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Projects -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Recent Projects</h3>
            </div>
            <div class="p-6">
                @if($recent_projects->count() > 0)
                    <div class="space-y-4">
                        @foreach($recent_projects as $project)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $project->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $project->client->company_name }}</p>
                                    <p class="text-xs text-gray-500">PM: {{ $project->projectManager->full_name }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($project->status == 'completed') bg-green-100 text-green-800
                                        @elseif($project->status == 'in_progress') bg-blue-100 text-blue-800
                                        @elseif($project->status == 'planning') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">${{ number_format($project->budget, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No recent projects</p>
                @endif
            </div>
        </div>

        <!-- Recent Employees -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Recent Employees</h3>
            </div>
            <div class="p-6">
                @if($recent_employees->count() > 0)
                    <div class="space-y-4">
                        @foreach($recent_employees as $employee)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <img class="h-10 w-10 rounded-full" 
                                         src="https://ui-avatars.com/api/?name={{ urlencode($employee->full_name) }}&background=6366f1&color=fff" 
                                         alt="{{ $employee->full_name }}">
                                    <div class="ml-3">
                                        <h4 class="font-medium text-gray-900">{{ $employee->full_name }}</h4>
                                        <p class="text-sm text-gray-600">{{ $employee->position }}</p>
                                        <p class="text-xs text-gray-500">{{ $employee->department->name }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($employee->status == 'active') bg-green-100 text-green-800
                                        @elseif($employee->status == 'inactive') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($employee->status) }}
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">{{ $employee->hire_date->format('M d, Y') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No recent employees</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Upcoming Tasks -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Upcoming Tasks</h3>
        </div>
        <div class="p-6">
            @if($upcoming_tasks->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($upcoming_tasks as $task)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $task->title }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $task->project->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $task->assignedEmployee->full_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $task->due_date->format('M d, Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            @if($task->priority == 'critical') bg-red-100 text-red-800
                                            @elseif($task->priority == 'high') bg-orange-100 text-orange-800
                                            @elseif($task->priority == 'medium') bg-yellow-100 text-yellow-800
                                            @else bg-green-100 text-green-800 @endif">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-8">No upcoming tasks</p>
            @endif
        </div>
    </div>
</div>
@endsection
