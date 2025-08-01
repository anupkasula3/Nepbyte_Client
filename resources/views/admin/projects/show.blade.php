@extends('layouts.admin')

@section('title', $project->name . ' - Project Details')
@section('page-title', 'Project Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">{{ $project->name }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.projects.tracking.dashboard', $project) }}"
               class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-chart-line mr-2"></i>Project Tracking
            </a>
            <a href="{{ route('admin.projects.edit', $project) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-edit mr-2"></i>Edit Project
            </a>
            <a href="{{ route('admin.projects.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Projects
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Project Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Project Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Project Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $project->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Client</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $project->client->company_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Project Manager</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $project->projectManager->full_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($project->status == 'completed') bg-green-100 text-green-800
                                @elseif($project->status == 'in_progress') bg-blue-100 text-blue-800
                                @elseif($project->status == 'planning') bg-yellow-100 text-yellow-800
                                @elseif($project->status == 'on_hold') bg-orange-100 text-orange-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Priority</label>
                            <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($project->priority == 'critical') bg-red-100 text-red-800
                                @elseif($project->priority == 'high') bg-orange-100 text-orange-800
                                @elseif($project->priority == 'medium') bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst($project->priority) }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Progress</label>
                            <div class="mt-1 flex items-center">
                                <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $project->progress_percentage }}%"></div>
                                </div>
                                <span class="text-sm text-gray-900">{{ $project->progress_percentage }}%</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-500">Description</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $project->description }}</p>
                    </div>
                </div>
            </div>

            <!-- Timeline & Budget -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Timeline & Budget</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Start Date</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $project->start_date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">End Date</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $project->end_date->format('M d, Y') }}</p>
                        </div>
                        @if($project->actual_end_date)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Actual End Date</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $project->actual_end_date->format('M d, Y') }}</p>
                            </div>
                        @endif
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Duration</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $project->start_date->diffInDays($project->end_date) }} days</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Budget</label>
                            <p class="mt-1 text-sm text-gray-900">${{ number_format($project->budget, 2) }}</p>
                        </div>
                        @if($project->actual_cost > 0)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Actual Cost</label>
                                <p class="mt-1 text-sm text-gray-900">${{ number_format($project->actual_cost, 2) }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Technical Details -->
            @if($project->technologies_used || $project->requirements)
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Technical Details</h3>
                    </div>
                    <div class="p-6">
                        @if($project->technologies_used)
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-500">Technologies Used</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $project->technologies_used }}</p>
                            </div>
                        @endif
                        @if($project->requirements)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Requirements</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $project->requirements }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Project Tasks -->
            @if($project->tasks->count() > 0)
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Project Tasks</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($project->tasks as $task)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="text-md font-medium text-gray-900">{{ $task->title }}</h4>
                                        <div class="flex space-x-2">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                @if($task->status == 'completed') bg-green-100 text-green-800
                                                @elseif($task->status == 'in_progress') bg-blue-100 text-blue-800
                                                @elseif($task->status == 'review') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                            </span>
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                @if($task->priority == 'critical') bg-red-100 text-red-800
                                                @elseif($task->priority == 'high') bg-orange-100 text-orange-800
                                                @elseif($task->priority == 'medium') bg-yellow-100 text-yellow-800
                                                @else bg-green-100 text-green-800 @endif">
                                                {{ ucfirst($task->priority) }}
                                            </span>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-3">{{ $task->description }}</p>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <span class="font-medium text-gray-500">Assigned to:</span>
                                            <span class="text-gray-900">{{ $task->assignedEmployee->full_name }}</span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-500">Due Date:</span>
                                            <span class="text-gray-900">{{ $task->due_date->format('M d, Y') }}</span>
                                        </div>
                                        @if($task->estimated_hours)
                                            <div>
                                                <span class="font-medium text-gray-500">Estimated:</span>
                                                <span class="text-gray-900">{{ $task->estimated_hours }}h</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Project Card -->
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <div class="h-20 w-20 rounded-full bg-purple-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-project-diagram text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">{{ $project->name }}</h3>
                <p class="text-sm text-gray-500">{{ $project->client->company_name }}</p>
                <p class="text-sm text-gray-500">PM: {{ $project->projectManager->full_name }}</p>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Quick Stats</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Total Tasks</span>
                        <span class="text-sm font-medium text-gray-900">{{ $project->tasks->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Completed Tasks</span>
                        <span class="text-sm font-medium text-gray-900">{{ $project->tasks->where('status', 'completed')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Progress</span>
                        <span class="text-sm font-medium text-gray-900">{{ $project->progress_percentage }}%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Budget Used</span>
                        <span class="text-sm font-medium text-gray-900">
                            @if($project->budget > 0)
                                {{ number_format(($project->actual_cost / $project->budget) * 100, 1) }}%
                            @else
                                0%
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Days Remaining</span>
                        <span class="text-sm font-medium text-gray-900">
                            @if($project->end_date->isFuture())
                                {{ now()->diffInDays($project->end_date) }}
                            @else
                                Overdue
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.tasks.create') }}?project={{ $project->id }}"
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium text-center block">
                        <i class="fas fa-plus mr-2"></i>Add Task
                    </a>
                    <a href="{{ route('admin.projects.edit', $project) }}"
                       class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium text-center block">
                        <i class="fas fa-edit mr-2"></i>Edit Project
                    </a>
                    <a href="{{ route('admin.clients.show', $project->client) }}"
                       class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium text-center block">
                        <i class="fas fa-building mr-2"></i>View Client
                    </a>
                </div>
            </div>

            @if($project->notes)
                <!-- Notes -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Notes</h3>
                    <p class="text-sm text-gray-700">{{ $project->notes }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
